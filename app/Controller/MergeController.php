<?php 
App::uses('PrintLib', 'Lib');
class MergeController extends AppController {

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

    public function complete() {

        $this->layout = false;
        $this->autoRender = NULL;

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


    public function printBill() {
        $this->layout = false;
        $this->autoRender = NULL;

        $this->loadModel('Cashier');
        $this->loadModel('Order');

        $order_ids = $this->data['order_ids'];
        $type = $this->data['type'];
        $logo_name = $this->data['logo_name'];

        $mergeData = $this->Order->getMergeOrderInfo($order_ids);

        $printerName = $this->Cashier->getServicePrinterName( $this->Session->read('Front.id'));
        $print = new PrintLib();
        echo $print->printMergeBillDoc($mergeData['order_nos'], $mergeData['table_nos'], $type, $printerName, $mergeData['print_items'], $mergeData, $logo_name,true, false);

        // print_r($mergeData);
       
    }


    public function printReceipt() {
        $this->layout = false;
        $this->autoRender = NULL;

        $this->loadModel('Cashier');
        $this->loadModel('Order');

        $order_ids = $this->data['order_ids'];
        $type = $this->data['type'];
        $logo_name = $this->data['logo_name'];

        $mergeData = $this->Order->getMergeOrderInfo($order_ids);

        $printerName = $this->Cashier->getServicePrinterName( $this->Session->read('Front.id'));
        $print = new PrintLib();
        echo $print->printMergeReceiptDoc($mergeData['order_nos'], $mergeData['table_nos'], $type, $printerName, $mergeData['print_items'], $mergeData, $logo_name,true, false);

        print_r($mergeData);
       
    }
}