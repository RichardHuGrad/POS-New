<?php 

App::uses('PrintLib', 'Lib');
class PayController extends AppController {

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


        
        // $printItems = array();
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


    public function donepayment() {

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