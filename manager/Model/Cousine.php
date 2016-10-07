<?php

class Cousine extends AppModel {

    public $name = 'Cousine';
    public $validate = array();

    public $hasMany = array(
        'CousineLocal' => array(
            'className' => 'CousineLocal',
            'foreignKey' => 'parent_id'
        )
    );

}

?>