<?php

class Category extends AppModel {

    public $name = 'Category';
    public $validate = array();

    public $hasMany = array(
        'CategoryLocale' => array(
            'className' => 'CategoryLocale',
            'foreignKey' => 'category_id'
        )
    );

}

?>