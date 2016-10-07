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

    // public $belongsTo = array(
    //     'Admin' => array(
    //         'className' => 'Admin',
    //         'foreignKey' => false,
    //         'conditions' => array('Admin.id = Order.cashier_id')
    //     )
    // );

}

?>