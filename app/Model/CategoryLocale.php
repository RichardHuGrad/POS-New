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


    public function getEnName($categoryId) {
        return $this->find("first", array(
            "recursive" => -1,
            "fields" => array("CategoryLocale.name"),
            'conditions' => array("CategoryLocale.category_id" => $categoryId, "CategoryLocale.lang_code"=> "en")
            ))["CategoryLocale"]["name"];

    }

    public function getZhName($categoryId) {
        return $this->find("first", array(
            "recursive" => -1,
            "fields" => array("CategoryLocale.name"),
            'conditions' => array("CategoryLocale.category_id" => $categoryId, "CategoryLocale.lang_code"=> "zh")
            ))["CategoryLocale"]["name"];
    }

}

?>
