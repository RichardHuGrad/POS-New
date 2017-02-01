<?php 

App::uses('PrintLib', 'Lib');
class PayController extends AppController {

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
            'fields' => array('Order.paid', 'Order.tip', 'Order.cash_val', 'Order.card_val', 'Order.change', 'Order.order_no', 'Order.tax', 'Order.table_status', 'Order.tax_amount', 'Order.subtotal', 'Order.after_discount','Order.total', 'Order.message', 'Order.discount_value', 'Order.promocode', 'Order.fix_discount', 'Order.percent_discount'),
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
            'recursive' => false
                )
        );

        $this->set(compact('Order_detail', 'cashier_detail', 'type', 'table', 'orders_no'));
    }

    public function printReceipt() {
        $this->layout = false;
        $this->autoRender = NULL;

        $this->loadModel('Cashier');
        $this->loadModel('Order');

        $order_no = $this->data['order_no'];
        $table_no = $this->data['table_no']; 
        $type = $this->data['type'];
        $logo_name = $this->data['logo_name'];


        // get order bill info from database
        $Order_detail = $this->Order->find('first', array(
                // 'recursive' => -1,
                'conditions' => array('Order.order_no' => $order_no)
            ));
        // print_r($Order_detail);
        $printItems = $Order_detail['OrderItem'];
        $billInfo = $Order_detail['Order'];

        $printerName = $this->Cashier->getServicePrinterName( $this->Session->read('Front.id'));
        $print = new PrintLib();
        echo $print->printPayReceiptDoc($order_no, $table_no, $type, $printerName, $printItems, $billInfo, $logo_name,true, false);


    }

    public function printBill() {
        $this->layout = false;
        $this->autoRender = NULL;

        $this->loadModel('Cashier');
        $this->loadModel('Order');

        $order_no = $this->data['order_no'];
        $table_no = $this->data['table_no']; 
        $type = $this->data['type'];
        $logo_name = $this->data['logo_name'];


        // get order bill info from database
        $Order_detail = $this->Order->find('first', array(
                // 'recursive' => -1,
                'conditions' => array('Order.order_no' => $order_no)
            ));
        // print_r($Order_detail);
        $printItems = $Order_detail['OrderItem'];
        $billInfo = $Order_detail['Order'];


        
        // $printItems = array();
        $printerName = $this->Cashier->getServicePrinterName( $this->Session->read('Front.id'));
        $print = new PrintLib();
        echo $print->printPayBillDoc($order_no, $table_no, $type, $printerName, $printItems, $billInfo, $logo_name,true, false); 
    }


    public function complete() {

        $this->layout = false;
        $this->autoRender = NULL;

        // pr($this->data); die;
        // get all params
        $order_id = $this->data['order_id'];
        $table = $this->data['table'];
        $type = $this->data['type'];
        $paid_by = strtoupper($this->data['paid_by']);

        $pay = $this->data['pay'];
        $change = $this->data['change'];

        // save order to database        
        $data['Order']['id'] = $order_id;
        if ($this->data['card_val'] and $this->data['cash_val'])
            $data['Order']['paid_by'] = "MIXED";

        elseif ($this->data['card_val'])
            $data['Order']['paid_by'] = "CARD";

        elseif ($this->data['cash_val'])
            $data['Order']['paid_by'] = "CASH";

        $data['Order']['table_status'] = 'P';

        $data['Order']['paid'] = $pay;
        $data['Order']['change'] = $change;
        $data['Order']['is_kitchen'] = 'Y';

        $data['Order']['is_completed'] = 'Y';

        $data['Order']['card_val'] = $this->data['card_val'];
        $data['Order']['cash_val'] = $this->data['cash_val'];
        $data['Order']['tip_paid_by'] = $this->data['tip_paid_by'];
        $data['Order']['tip'] = $this->data['tip_val'];

        $this->loadModel('Order');
        $this->Order->save($data, false);

        // update popularity status
        $this->loadModel('Cousine');
        $this->Cousine->query("UPDATE cousines set `popular` = `popular`+1 where id in(SELECT (item_id) from order_items where order_id = '$order_id')");

        // save all 
        $this->Session->setFlash('Order successfully completed.', 'success');

        echo true;
        exit; //Modified by Yishou Liao @ Nov 29 2016
    }

}

 ?>