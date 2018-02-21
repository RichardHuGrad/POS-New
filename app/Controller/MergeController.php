<?php 
App::uses('PrintLib', 'Lib');
class MergeController extends AppController {
	
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
        $tablemerge = explode(",", ($table . "," . @$this->params['named']['tablemerge']));


        if ($order_no) {
            $conditions = array('Order.cashier_id' => $cashier_detail['Admin']['id'],
                'Order.order_no' => $order_no
            );
        } else {
            $conditions = array('Order.cashier_id' => $cashier_detail['Admin']['id'],
                'Order.table_no' => $tablemerge,
                'Order.is_completed' => 'N',
                'Order.order_type' => $type
            );
        }

        // get order details 
        $this->loadModel('Order');
        $this->loadModel('OrderItem');

        $this->OrderItem->virtualFields['image'] = "Select image from cousines where cousines.id = OrderItem.item_id";
        $Order_detail = $this->Order->find("all", array(
            'fields' => array('Order.table_no', 'Order.paid', 'Order.tip', 'Order.cash_val', 'Order.card_val', 'Order.membercard_val', 'Order.membercard_id', 'Order.change', 'Order.order_no', 'Order.tax', 'Order.table_status', 'Order.tax_amount', 'Order.default_tip_rate', 'Order.default_tip_amount', 'Order.subtotal', 'Order.after_discount','Order.total', 'Order.message', 'Order.discount_value', 'Order.promocode', 'Order.fix_discount', 'Order.percent_discount'),
            'conditions' => $conditions
                )
        );

        if (empty($Order_detail)) {
            $this->Session->setFlash('Sorry, order does not exist 抱歉，订单不存在。.', 'error');
            return $this->redirect(array('controller' => 'homes', 'action' => 'dashboard'));
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

        $order_id_merge = array();
        foreach ($Order_detail as $O) {
            array_push($order_id_merge, $O['Order']['id']);
        }

        // print_r($order_id_merge);

        $this->set(compact('Order_detail', 'order_id_merge', 'cashier_detail', 'admin_passwd' ,'type', 'table', 'tablemerge', 'orders_no'));
    }


    public function getOrdersAmount() {
        $this->layout = false;
        $this->autoRender = NULL;
        $this->loadModel('Order');

        $order_ids = $this->data['order_ids'];
        $orders = array();
        foreach($order_ids as $order_id) {
            $temp = $this->Order->find('first', array(
                'conditions' => array(
                        'Order.id' => $order_id
                    )
                ));

            array_push($orders, $temp['Order']);
        }

        // print_r($orders);

        return json_encode($orders);
    }

/*
    public function complete() {

        // pr($this->data); die;
        // get all params
        $order_id = explode(",", $this->data['order_id']);
        $table = $this->data['table'];
        $table_merge = explode(",", $this->data['table_merge']);
        $main_order_id = $this->data['main_order_id'];
        $type = $this->data['type'];
        $paid_by = strtoupper($this->data['paid_by']);

        $paid = $this->data['pay'];
        $change = $this->data['change'];

        // save order to database
        //Modified by Yishou Liao @ Oct 16 2016.
        $this->loadModel('Order');

        for ($i = 0; $i < count($order_id); $i++) {
            $data['Order']['id'] = $order_id[$i];
            $table_detail = $this->Order->find("first", array('fields' => array('Order.table_no', 'total'), 'conditions' => array('Order.id' => $data['Order']['id']), 'recursive' => false));

            if ($this->data['card_val'] and $this->data['cash_val']) {
                $data['Order']['paid_by'] = "MIXED";
            } elseif ($this->data['card_val']) {
                $data['Order']['paid_by'] = "CARD";
            } elseif ($this->data['cash_val']) {
                $data['Order']['paid_by'] = "CASH";
            };
            $data['Order']['table_status'] = 'P';
            $data['Order']['is_kitchen'] = 'Y';
            $data['Order']['is_completed'] = 'Y';



            if ($table_detail['Order']['table_no'] == $table) {
                $data['Order']['paid'] = $paid;
                $data['Order']['change'] = $change;

                $data['Order']['card_val'] = $this->data['card_val'];
                $data['Order']['cash_val'] = $this->data['cash_val'];
                $data['Order']['tip_paid_by'] = $this->data['tip_paid_by'];
                $data['Order']['tip'] = $this->data['tip_val'];
            } else {
                $data['Order']['paid'] = 0;
                $data['Order']['change'] = 0;

                $data['Order']['card_val'] = 0;
                $data['Order']['cash_val'] = 0;
                // $data['Order']['tip_paid_by'] = $this->data['tip_paid_by'];
                $data['Order']['tip'] = 0;
                $data['Order']['merge_id'] = $main_order_id; //用负数代表此处为合单，去掉负号的那个数代表主桌的付款Order的Id号
            };

            $this->Order->save($data, false);

            $this->loadModel('Cousine');
            $this->Cousine->query("UPDATE cousines set `popular` = `popular`+1 where id in(SELECT (item_id) from order_items where order_id = '$order_id[$i]')");
        };

        // save all 
        // update popularity status

        $this->Session->setFlash('Order successfully completed.', 'success');
        echo true;
       
    }
*/ 
    public function completeMergeOrder() {

        $this->layout = false;
        $this->autoRender = NULL;
		
		//这里传的是逗号分隔的order_ids(和api中传的数组不一样)
		$this->request->data["order_ids"] = explode(",", $this->request->data["order_ids"]);
		
		$res = $this->PayHandler->completeMergeOrder($this->request->data);
        return $res;
    }

    public function printBill() {
        $this->layout = false;
        $this->autoRender = NULL;

        $this->loadModel('Cashier');
        $this->loadModel('Order');

        $order_ids = $this->data['order_ids'];
        $restaurant_id = $this->Cashier->getRestaurantId($this->Session->read('Front.id'));
        
        $res = $this->Print->printMergeBill(array('restaurant_id'=> $restaurant_id, 'order_ids'=>$order_ids));
        return json_encode($res);
    }


    public function printReceipt() {
        $this->layout = false;
        $this->autoRender = NULL;

        $this->loadModel('Cashier');
        $this->loadModel('Order');

        $order_ids = $this->data['order_ids'];
        $restaurant_id = $this->Cashier->getRestaurantId($this->Session->read('Front.id'));
        
        $this->Print->printMergeReceipt(array('restaurant_id'=> $restaurant_id, 'order_ids'=>$order_ids));
    }
}