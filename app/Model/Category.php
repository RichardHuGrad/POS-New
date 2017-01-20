<?php

class Category extends AppModel {

    public $name = 'Category';
    public $validate = array();

    public $hasMany = array(
        'CategoryLocale' => array(
            'className' => 'CategoryLocale',
            'foreignKey' => 'category_id'
        ),
        // 'Cousine' => array(
        //     'className' => 'Cousine',
        //     'foreignKey' => 'category_id'
        // ),
    );

    public function getPrinterById($id) {
        $item = $this->find('first', array(
                'fields' => array('Category.printer'),
                'conditions' => array('Category.id' => $id)
            )   
        );

        return $item['Category']['printer'];
    }

}

?>