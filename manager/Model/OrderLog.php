<?php

class OrderLog extends AppModel {

    public $name = 'OrderLog';
    public $validate = array();


    public function insertLog ($order_detail, $operation) {
        $log_detail = array('OrderLog' => array('order_no' => $order_detail['Order']['order_no'], 'json' => json_encode($order_detail['Order']), 'operation' => $operation,'last_update'=>date('Y-m-d H:i:s')));
        $this->create();
        $this->save($log_detail, false);
    }

}

?>