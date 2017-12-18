<?php

class OrderItem extends AppModel {

    public $name = 'OrderItem';
    public $validate = array();

    public $belongsTo = array(
        'Order' => array(
            'className' => 'Order',
            'foreignKey' => 'order_id'
        ),
    );

}

?>