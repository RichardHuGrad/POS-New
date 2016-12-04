<?php

class Extrascategory extends AppModel {

    public $name = 'Extrascategory';
    
    public $hasMany = array(
        'Extra' => array(
            'className' => 'Extra',
            'foreignKey' => 'category_id'
        )
    );
}

?>