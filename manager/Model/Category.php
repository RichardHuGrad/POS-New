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

	public function sync_categories($categories) {
		$nowtm = time();
		foreach ($categories as $cat) {
			if ($cur = $this->find("first", array('conditions' => array('remote_id' => $cat['id'])))) {
				/*
				 * Array(
				 *   [Category] => Array(
				 *       [id] => 14
				 *       [status] => A
				 *       [created] => 1488303170
				 *       [modified] => 1488303170
				 *       [printer] => K
				 *       [group_id] => 2
				 *       [remote_id] => 0
				 *   )
				 *   [CategoryLocale] => Array(
				 *       [0] => Array(
				 *           [id] => 25
				 *           [category_id] => 14
				 *           [name] =>  Hot Pot Noodle
				 *           [lang_code] => en
				 *           [created] => 1488303170
				 *           [modified] => 1488303170
				 *       )
				 *       [1] => Array(
				 *           [id] => 26
				 *           [category_id] => 14
				 *           [name] => 火锅米线
				 *           [lang_code] => zh
				 *           [created] => 1488303170
				 *           [modified] => 1488303170
				 *       )
				 *   )
				 * )
				 */
				$cur['Category']['modified'] = $nowtm;
				$cur['CategoryLocale'][1]['name'] = $cat['type_name'];	// Only possiable is name changed
				$cur['CategoryLocale'][1]['modified'] = $nowtm;
				$this->saveAssociated($cur);
			} else if ($curl = $this->CategoryLocale->find("first", array('conditions' => array('CategoryLocale.name' => $cat['type_name'])))) {
				if ($cur = $this->find("first", array('conditions' => array('id' => $curl['CategoryLocale']['category_id'])))) {
					// Same name just add remote ID
					$cur['Category']['modified'] = $nowtm;
					$cur['Category']['remote_id'] = $cat['id'];
					$this->saveAssociated($cur);
				}
			} else {
				$newcate = array(
						'Category' => array(
								'status' => 'A',
								'created' => $nowtm,
								'modified' => $nowtm,
								'printer' => 'K',
								'group_id' => 2,
								'remote_id' => $cat['id']
						),
						'CategoryLocale' => array(
								array(
										'name' => $cat['type_name'],
										'lang_code' => 'en',
										'created' => $nowtm,
										'modified' => $nowtm
										),
								array(
										'name' => $cat['type_name'],
										'lang_code' => 'zh',
										'created' => $nowtm,
										'modified' => $nowtm
										),
						)
				);
				$this->saveAssociated($newcate);
			}
			/* XXXXXXXXXX
			$dbo = $this->getDatasource();
			$logs = $dbo->getLog();
			echo "<pre>";
			print_r($logs);
			die("XXXXXXXX");
			*/
		}
	}
}

?>