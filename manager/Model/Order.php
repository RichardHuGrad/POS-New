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

        $OrderLog = ClassRegistry::init('OrderLog');
        $OrderLog->insertLog($order_detail, 'delete');

        $this->delete(array('Order.id' => $order_id), false);
    }



}

?>