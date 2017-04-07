<?php

class Attend extends AppModel {

    public $name = 'Attend';
    public $validate = array();

    public $belongsTo = array(
        'Cashier' => array(
            'className' => 'userid',
            'foreignKey' => 'userid'
        )
    );

}

?>