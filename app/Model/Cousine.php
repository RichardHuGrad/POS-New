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
        'CousineExtrascategories' => array(
            'className' => 'CousineExtrascategories',
            'foreignKey' => 'cousine_id'
        )
    	// 'Extra' => array(
        //     'className' => 'Extra',
        //     'foreignKey' => false,
        //     'conditions'=> array('status'=>'A'),
        //     'order' => array('category_id') //Modified by Yishou Liao @ Nov 30 2016
        // )
    );


    public function getCousineInfo($id) {
        $CousineDetail = $this->find("first", array(
            "recursive" => -1,
            "fields" => array('Cousine.price', 'Cousine.is_tax', 'Cousine.comb_num', 'Cousine.category_id'),
            "conditions" => array('Cousine.id' => $id)
            )
        );
        // $CousineLocalDetail = $this->CousineLocal->find("first", array(
        //         "recursive" => -1,
        //         "fields" => array("CousineLocal.name"),
        //         'conditions' => array("CousineLocal.id" => $category_id, "CousineLocal.id"=> "en")
        //     ))

        $enName = $this->CousineLocal->getEnName($id);
        $zhName = $this->CousineLocal->getZhName($id);
        // combine the data
        $CousineDetail['Cousine']['en'] = $enName;
        $CousineDetail['Cousine']['zh'] = $zhName;

        // Extra Categories
        $categories = $this->CousineExtrascategories->find("list", array("conditions" => array("cousine_id" => $id), "fields" => array("CousineExtrascategories.extrascategorie_id")));
        $CousineDetail['Cousine']['ExtraCategories'] = $categories;
        
        return $CousineDetail;
    }


    public function getAllCousines($status) {
        $CousineDetails = $this->find("all", array(
            "recursive" => -1,
			"conditions" => $status
            // "fields" => array('Cousine.price', 'Cousine.is_tax', 'Cousine.comb_num', 'Cousine.category_id')
            )
        );

        foreach($CousineDetails as &$CousineDetail) {
            $id = $CousineDetail['Cousine']['id'];
            $enName = $this->CousineLocal->getEnName($id);
            $zhName = $this->CousineLocal->getZhName($id);

            $CousineDetail['Cousine']['en'] = $enName;
            $CousineDetail['Cousine']['zh'] = $zhName;
        }

        return $CousineDetails;
    }

    public function getAllCousinesByCategoryId($category_id) {
        $CousineDetails = $this->find("all", array(
            "recursive" => -1,
            // "fields" => array('Cousine.price', 'Cousine.is_tax', 'Cousine.comb_num', 'Cousine.category_id')
            'conditions' => array('Cousine.category_id' => $category_id)
            )
        );

        foreach($CousineDetails as &$CousineDetail) {
            $id = $CousineDetail['Cousine']['id'];
            $enName = $this->CousineLocal->getEnName($id);
            $zhName = $this->CousineLocal->getZhName($id);

            $CousineDetail['Cousine']['en'] = $enName;
            $CousineDetail['Cousine']['zh'] = $zhName;
        }

        return $CousineDetails;
    }

}

?>
