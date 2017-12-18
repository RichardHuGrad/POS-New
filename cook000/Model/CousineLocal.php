<?php

class CousineLocal extends AppModel {

    public $name = 'CousineLocal';
    public $validate = array();

    public $belongsTo = array(
        'Cousine' => array(
            'className' => 'Cousine',
            'foreignKey' => 'parent_id' 
        )
    );

}

?>
