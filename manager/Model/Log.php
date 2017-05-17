<?php

class Log extends AppModel {

    public $name = 'Log';
    
    public $belongsTo = array(
        'Cashier' => array(
            'className' => 'Cashier',
            'foreignKey' => 'cashier_id'
        )
    );
}

?>