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

    public function getAllCategories($status) {
        $categoryDetails = $this->find('all', array(
                                'recursive' => -1,
								'conditions' => $status
                            ));
        foreach($categoryDetails as &$categoryDetail) {
            $id = $categoryDetail['Category']['id'];
            $enName = $this->CategoryLocale->getEnName($id);
            $zhName = $this->CategoryLocale->getZhName($id);

            $categoryDetail['Category']['en'] = $enName;
            $categoryDetail['Category']['zh'] = $zhName;
        }

        return $categoryDetails;
    }

    public function getPrinterAndGroupById($id) {
        $item = $this->find('first', array(
                'fields' => array('Category.printer','Category.group_id'),
                'conditions' => array('Category.id' => $id)
            )
        );
        return $item['Category'];
    }

}

?>
