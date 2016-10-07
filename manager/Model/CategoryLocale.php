<?php

class CategoryLocale extends AppModel {

    public $name = 'CategoryLocale';
    public $validate = array();

    public $belongsTo = array(
        'Category' => array(
            'className' => 'Category',
            'foreignKey' => 'category_id' 
        )
    );

}

?>
