<?php

class Order extends AppModel {

    public $name = 'Order';
    public $validate = array();

    public $hasMany = array(
        'OrderItem' => array(
            'className' => 'OrderItem',
            'foreignKey' => 'order_id'
        ),
    );
    public $belongsTo = array(
        'Cashier' => array(
            'className' => 'Cashier',
            'foreignKey' => 'counter_id' 
        )
    );

    public function deleteByOrderNo($order_no) {
        // record the deleted order to the log system
        $order_detail = $this->find('first', array(
                            'recursive' => -1,
                            'conditions' => array(
                                    'order_no' => $order_no
                                )
                        ));
        $order_id = $order_detail['Order']['id'];

        $log_detail = array('OrderLog' => array('order_no' => $order_detail['Order']['order_no'], 'json' => json_encode($order_detail['Order']), 'operation' => 'delete'));
        
        print_r($log_detail);
        $OrderLog = ClassRegistry::init('OrderLog');
        $OrderLog->create();
        $OrderLog->save($log_detail, false);

        $this->delete(array('Order.id' => $order_id), false);
    }

}

?>