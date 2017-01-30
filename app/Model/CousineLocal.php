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

    public function getEnName($id) {
    	return $this->find("first", array(
    		"recursive" => -1,
            "fields" => array("CousineLocal.name"),
            'conditions' => array("CousineLocal.parent_id" => $id, "CousineLocal.lang_code"=> "en")
    		))["CousineLocal"]["name"];

    }

    public function getZhName($id) {
    	return $this->find("first", array(
    		"recursive" => -1,
            "fields" => array("CousineLocal.name"),
            'conditions' => array("CousineLocal.parent_id" => $id, "CousineLocal.lang_code"=> "zh")
    		))["CousineLocal"]["name"];
    }

}

?>
