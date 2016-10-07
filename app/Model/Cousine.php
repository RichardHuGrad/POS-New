<?php

class Cousine extends AppModel {

    public $name = 'Cousine';
    public $validate = array();


    public $belongsTo = array(
        'Category' => array(
            'className' => 'Category',
            'foreignKey' => 'category_id'
        )
    );
    public $hasMany = array(
        'CousineLocal' => array(
            'className' => 'CousineLocal',
            'foreignKey' => 'parent_id'
        ),
        'Extra' => array(
            'className' => 'Extra',
            'foreignKey' => 'cousine_id',
            'conditions'=> array('status'=>'A')
        )
    );

}

?>