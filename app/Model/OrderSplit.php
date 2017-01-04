<?php

class OrderSplit extends AppModel {

    public $name = 'OrderSplit';
    public $validate = array();

    public $belongsTo = array(
        'Order' => array(
            'className' => 'Order',
            'foreignKey' => 'order_no'
        ),
    );

}

?>