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

}

?>