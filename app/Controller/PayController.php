<?php

App::uses('PrintLib', 'Lib');
class PayController extends AppController {
    public $components = array('PayHandler');

    public function beforeFilter() {

        parent::beforeFilter();
        $this->Auth->allow('index', 'forgot_password');
        $this->layout = "default";
    }

    public function index() {

        // get cashier details
        $this->loadModel('Cashier');
        $cashier_detail = $this->Cashier->find("first", array(
            'fields' => array('Cashier.firstname', 'Cashier.lastname', 'Cashier.id', 'Cashier.image', 'Admin.id','Admin.kitchen_printer_device','Admin.service_printer_device'),
            'conditions' => array('Cashier.id' => $this->Session->read('Front.id'))
                )
        );
        
        $admin_passwd = $this->Cashier->query("SELECT admins.password FROM admins WHERE admins.is_super_admin='Y' ");

        $order_no = @$this->params['url']['order_no'];

        // get all params
        $type = @$this->params['named']['type'];
        $table = @$this->params['named']['table'];

        if ($order_no) {
            $conditions = array('Order.cashier_id' => $cashier_detail['Admin']['id'],
                'Order.order_no' => $order_no
            );
        } else {
            $conditions = array('Order.cashier_id' => $cashier_detail['Admin']['id'],
                'Order.table_no' => $table,
                'Order.is_completed' => 'N',
                'Order.order_type' => $type
            );
        }

        // get order details
        $this->loadModel('Order');
        $this->loadModel('OrderItem');

        $this->OrderItem->virtualFields['image'] = "Select image from cousines where cousines.id = OrderItem.item_id";
        $Order_detail = $this->Order->find("first", array(
            'fields' => array('Order.paid', 'Order.tip', 'Order.cash_val', 'Order.card_val', 'Order.change', 'Order.order_no', 'Order.tax', 'Order.table_status', 'Order.tax_amount', 'Order.subtotal', 'Order.after_discount','Order.total', 'Order.message', 'Order.reason', 'Order.discount_value', 'Order.promocode', 'Order.fix_discount', 'Order.percent_discount', 'Order.default_tip_rate', 'Order.default_tip_amount'),
            'conditions' => $conditions
                )
        );
        
        if (empty($Order_detail)) {
            $this->Session->setFlash('Sorry, order does not exist 抱歉，订单不存在。.', 'error');
            return $this->redirect(array('controller' => 'homes', 'action' => 'dashboard'));
        }
        
        //if this order splited, redirect to split controller
        $this->loadModel('Cookie');
        $is_split = $this->Cookie->hasAny(array( 'key like' => @$Order_detail['Order']['order_no'].'%'));
        if($is_split){
           return $this->redirect(array('controller'=>'split', 'action'=>'index', 'table'=>$table, 'type'=>$type, 'split_method' =>'1'));
        }     
           
           
        $type = @$Order_detail['Order']['order_type'] ? @$Order_detail['Order']['order_type'] : $type;
        $table = @$Order_detail['Order']['table_no'] ? @$Order_detail['Order']['table_no'] : $table;

        // get all order no.
        $orders_no = $this->Order->find("list", array(
            'fields' => array('Order.order_type', 'Order.order_no', 'Order.table_no'),
            'conditions' => array('Order.cashier_id' => $cashier_detail['Admin']['id'], 'Order.is_completed' => 'N'),
            'recursive' => -1
                )
        );

        $this->set(compact('Order_detail', 'cashier_detail', 'admin_passwd', 'type', 'table', 'orders_no'));
    }

    public function printReceipt() {
        $this->layout = false;
        $this->autoRender = NULL;

        $this->loadModel('Cashier');
        $this->loadModel('Order');

        $order_no = $this->data['order_no'];
        $order_id = $this->Order->getOrderIdByOrderNo($order_no);
        $restaurant_id = $this->Cashier->getRestaurantId($this->Session->read('Front.id'));

        return $this->Print->printPayReceipt(array('restaurant_id'=> $restaurant_id, 'order_id'=>$order_id));
    }


    //two ways call this function: one is from pay page, one is from dashboard
    public function printBill() {
        $this->layout = false;
        $this->autoRender = NULL;

        $this->loadModel('Cashier');
        $this->loadModel('Order');

        $order_no = $this->data['order_no'];
        if(!$this->request->is('ajax')){
           $order_no = $this->request->params['named']['order'];
        }
        
        $order_id = $this->Order->getOrderIdByOrderNo($order_no);
        $restaurant_id = $this->Cashier->getRestaurantId($this->Session->read('Front.id'));

        $this->Print->printPayBill(array('restaurant_id'=> $restaurant_id, 'order_id'=>$order_id));
                       
        if(!$this->request->is('ajax')){
          return $this->redirect(array('controller' => 'homes', 'action' => 'dashboard'));
        }

    }


    public function complete() {

        $this->layout = false;
        $this->autoRender = NULL;

        $this->PayHandler->completeOrder(array(
            'order_id' => $this->data['order_id'],
            'table' => $this->data['table'],
            'type' => $this->data['type'],
            'paid_by' => strtoupper($this->data['paid_by']),
            'pay' => $this->data['pay'],
            'change' => $this->data['change'],
            'membercard_id' => $this->data['membercard_id'],
        	'membercard_val' => $this->data['membercard_val'],
            'card_val' => $this->data['card_val'],
        	'cash_val' => $this->data['cash_val'],
            'tip_paid_by' => $this->data['tip_paid_by'],
            'tip' => $this->data['tip_val'] ? $this->data['tip_val'] : 0
        ));


        $this->Session->setFlash('Order successfully completed.', 'success');

        echo true;
        exit; //Modified by Yishou Liao @ Nov 29 2016
    }

}

 ?>
