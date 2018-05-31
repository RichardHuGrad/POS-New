<?php
class Cousine extends AppModel {

    public $name = 'Cousine';
    public $validate = array();

    public $hasMany = array(
        'CousineLocal' => array(
            'className' => 'CousineLocal',
            'foreignKey' => 'parent_id'
        ),
        'CousineExtrascategories' => array(
            'className' => 'CousineExtrascategories',
            'foreignKey' => 'cousine_id'
        )
    );

	public function sync_cousines($datas) {
		$nowtm = time();
		
		$categories = ClassRegistry::init('Categories');
		$cousine_extrascategories = ClassRegistry::init('CousineExtrascategories');
		$extrascategory = ClassRegistry::init('Extrascategory');
		foreach ($datas as $rc) {
			/*
			 * Array (
			 *     [id] => 27
			 *     [name] => 招牌卤肉面
			 *     [img] => images/4/2018/02/c0gbp0TN40IqHmbt39qN90hH93uUK3.jpg
			 *     [num] => 10000
			 *     [money] => 11.99
			 *     [type_id] => 28
			 *     [signature] => 1
			 *     [one] => 0
			 *     [uniacid] => 4
			 *     [xs_num] => 0
			 *     [sit_ys_num] => 0
			 *     [is_shelves] => 1
			 *     [dishes_type] => 3
			 *     [box_fee] => 0.00
			 *     [wm_money] => 11.99
			 *     [details] =>
			 *     [sorting] => 11
			 *     [store_id] => 27 )
			 *     [options] => Array(
			 *     		[0] => Array(
			 *     			[id] => 1
			 *     			[dish_id] => 27
			 *     			[option_id] => 5
			 *     			[date_added] = 2018-03-30 10:30:23
			 *     			[date_updated] = 2018-03-30 10:30:23
			 *     		)
			 *     		[1] => Aarray(
			 *     			[id] => 2
			 *     			[dish_id] => 31
			 *     			[option_id] => 6
			 *     			[date_added] = 2018-03-30 10:30:23
			 *     			[date_updated] = 2018-03-30 10:30:23
			 *     		)
			 *     )
			 */
			// Check type first
			$category_id = 0;
			if ($cur = $categories->find("first", array('conditions' => array('remote_id' => $rc['type_id'])))) {
				$category_id = $cur['Categories']['id'];
			} else {
				return "Please get category first, some records isn't synchronized";
			}
			if ($cur = $this->find("first", array('conditions' => array('remote_id' => $rc['id'])))) {
				/*
				 * Array(
				 *   [Cousine] => Array
				 *       [id] => 10
				 *       [restaurant_id] => 5
				 *       [casier_id] => 3
				 *       [price] => 12.99
				 *       [category_id] => 5
				 *       [comb_num] => 0
				 *       [image] => 1467777127_cousine.png
				 *       [status] => A
				 *       [created] => 1467364439
				 *       [popular] => 780
				 *       [is_tax] => Y
				 *       [modified] => 1503460986
				 *       [remote_id] => 0
				 *   )
				 *   [CousineLocal] => Array(
				 *       [0] => Array(
				 *           [id] => 15
				 *           [parent_id] => 10
				 *           [name] => Noodles w/Beef Sirloin
				 *           [lang_code] => en
				 *           [created] => 1467364439
				 *           [modified] => 1503460986
				 *       )
				 *       [1] => Array(
				 *           [id] => 16
				 *           [parent_id] => 10
				 *           [name] => 宋嫂牛肉面
				 *           [lang_code] => zh
				 *           [created] => 1467364439
				 *           [modified] => 1503460986
				 *       )
				 *   )
				 * )
				 */
				$cur['Cousine']['price'] = $rc['money'];
				$cur['Cousine']['category_id'] = $category_id;
				$cur['Cousine']['modified'] = $nowtm;
				if (isset($cur['CousineLocal'][1]) && ($cur['CousineLocal'][1]['lang_code'] == 'zh')) {
					$cur['CousineLocal'][1]['name'] = $rc['name'];
					$cur['CousineLocal'][1]['modified'] = $nowtm;
				}
				$this->saveAssociated($cur);
				
				$cousine_id = $cur['Cousine']['id'];
				// Sync Options
				$cousine_extrascategories->deleteAll(array('CousineExtrascategories.cousine_id' => $cur['Cousine']['id']), false);
				foreach ($rc['options'] as $opt) {
					if ($excate = $extrascategory->find("first", array('conditions' => array('remote_id' => $opt['option_id'])))) {
						$new_ce = array('CousineExtrascategories' => array('id' => 0, 'cousine_id' => $cousine_id, "extrascategorie_id" => $excate['Extrascategory']['id']));
						$cousine_extrascategories->save($new_ce);
					} else {
						return "Please get extra category first, some record (".$opt['id'].") isn't synchronized";
					}
				}				
			} else if ($curl = $this->CousineLocal->find("first", array('conditions' => array('CousineLocal.name' => $rc['name'])))) {
				if ($cur = $this->find("first", array('conditions' => array('id' => $curl['CousineLocal']['parent_id'])))) {
					// Same name just add remote ID
					$cur['Cousine']['price'] = $rc['money'];
					$cur['Cousine']['category_id'] = $category_id;
					$cur['Cousine']['modified'] = $nowtm;
					$cur['Cousine']['remote_id'] = $rc['id'];
					$cur['CousineLocal'][1]['modified'] = $nowtm;
					$this->saveAssociated($cur);
					
					$cousine_id = $cur['Cousine']['id'];
					// Sync Options
					$cousine_extrascategories->deleteAll(array('CousineExtrascategories.cousine_id' => $cur['Cousine']['id']), false);
					foreach ($rc['options'] as $opt) {
						if ($excate = $extrascategory->find("first", array('conditions' => array('remote_id' => $opt['option_id'])))) {
							$new_ce = array('CousineExtrascategories' => array('id' => 0, 'cousine_id' => $cousine_id, "extrascategorie_id" => $excate['Extrascategory']['id']));
							$cousine_extrascategories->save($new_ce);
						} else {
							return "Please get extra category first, some record (".$opt['id'].") isn't synchronized";
						}
					}				
				}
			} else {
				$newrc = array(
						'Cousine' => array(
								'restaurant_id' => 5,
								'casier_id' => 3,
								'category_id' => $category_id,
								'price' => $rc['money'],
								'created' => $nowtm,
								'modified' => $nowtm,
								'remote_id' => $rc['id']
						),
						'CousineLocal' => array(
								array(
										'name' => $rc['name'],
										'lang_code' => 'en',
										'created' => $nowtm,
										'modified' => $nowtm
										),
								array(
										'name' => $rc['name'],
										'lang_code' => 'zh',
										'created' => $nowtm,
										'modified' => $nowtm
										),
						)
				);
				$this->saveAssociated($newrc);
				$cousine_id = $this->getLastInsertID();
				// Sync Options
				foreach ($rc['options'] as $opt) {
					if ($excate = $extrascategory->find("first", array('conditions' => array('remote_id' => $opt['option_id'])))) {
						$new_ce = array('CousineExtrascategories' => array('id' => 0, 'cousine_id' => $cousine_id, "extrascategorie_id" => $excate['Extrascategory']['id']));
						$cousine_extrascategories->save($new_ce);
					} else {
						return "Please get extra category first, some record (".$opt['id'].") isn't synchronized";
					}
				}				
			}
			/* 
			$dbo = $this->getDatasource();
			$logs = $dbo->getLog();
			echo "<pre>";
			print_r($logs);
			die("XXXXXXXX");
			*/
		}
		return "";
	}
}
?>