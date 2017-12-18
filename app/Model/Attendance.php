<?php

class Attendance extends AppModel {

    public $name = 'Attendance';
    public $validate = array();

    public $belongsTo = array(
        'Cashier' => array(
            'className' => 'userid',
            'foreignKey' => 'userid'
        )
    );

}

?>