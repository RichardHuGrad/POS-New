<?php

class Extrascategory extends AppModel {

    public $name = 'Extrascategory';
    
    public $hasMany = array(
        'Extra' => array(
            'className' => 'Extra',
            'foreignKey' => 'category_id'
        )
    );

	public function sync_extrascategories($datas) {
		$nowtm = time();
		// $extra = ClassRegistry::init('Extra');
		
		foreach ($datas as $rc) {
			if ($cur = $this->find("first", array('conditions' => array('remote_id' => $rc['id'])))) {
				$extras = json_decode($rc['options'], TRUE);
				$cur['Extrascategory']['modified'] = $nowtm;
				$cur['Extrascategory']['name_zh'] = $rc['name'];	// Only possiable is name changed
				$cur['Extrascategory']['extras_num'] = 0;
				
				$dbo = $this->getDatasource();
				if ($rc['type'] == 3) {
					$cur['Extrascategory']['extras_num'] = $extras['limit'];
				}
				$this->saveAssociated($cur);
				// Disable old extras
				$sql = "UPDATE `pos`.`extras` SET `status` = 'I' WHERE `pos`.`extras`.`id` = '" . $cur['Extrascategory']['id'] . "'";
				$this->Extra->query($sql);
				if (empty($rc['type']) || ($rc['type'] == 1)) {
					foreach ($extras['values'] as $ex) {
						if ($cex1 = $this->Extra->find("first", array('recursive' => FALSE, 'conditions' => array('Extra.category_id' => $cur['Extrascategory']['id'], 'Extra.name_zh' => $ex['name'])))) {
							$cex = $cex1['Extra'];
							$cex['price'] = $ex['price'];
							$cex['status'] = 'A';
						} else {
							$cex = array('id' => 0, 'name' => $ex['name'], 'name_zh' => $ex['name'], 'price' => $ex['price'], 'status' => 'A', 'category_id' => $cur['Extrascategory']['id'], 'created' => date("Y-m-d H:i:s"));
						}
						$this->Extra->save($cex);
						$this->Extra->clear();
					}
				} else if ($rc['type'] == 2) {
					$price = ((int)$extras['limit']) ? ($extras['total'] / (int)$extras['limit']) : $extras['total'];
					foreach ($extras['values'] as $ex) {
						if ($cex1 = $this->Extra->find("first", array('recursive' => FALSE, 'conditions' => array('Extra.category_id' => $cur['Extrascategory']['id'], 'Extra.name_zh' => $ex['name'])))) {
							$cex = $cex1['Extra'];
							$cex['price'] = $price;
							$cex['status'] = 'A';
						} else {
							$cex = array('id' => 0, 'name' => $ex['name'], 'name_zh' => $ex['name'], 'price' => $price, 'status' => 'A', 'category_id' => $cur['Extrascategory']['id'], 'created' => date("Y-m-d H:i:s"));
						}
						$this->Extra->save($cex);
						$this->Extra->clear();
					}
				}
			} else if ($cur = $this->find("first", array('conditions' => array('Extrascategory.name_zh' => $rc['name'])))) {
				$extras = json_decode($rc['options'], TRUE);
				$cur['Extrascategory']['modified'] = $nowtm;
				$cur['Extrascategory']['status'] = 'A';
				$cur['Extrascategory']['extras_num'] = 0;
				$cur['Extrascategory']['remote_id'] = $rc['id'];
				if ($rc['type'] == 3) {
					$cur['Extrascategory']['extras_num'] = $extras['limit'];
				}
				$this->saveAssociated($cur);
				// Disable old extras
				$sql = "UPDATE `pos`.`extras` SET `status` = 'I' WHERE `pos`.`extras`.`id` = '" . $cur['Extrascategory']['id'] . "'";
				$this->Extra->query($sql);
				if (empty($rc['type']) || ($rc['type'] == 1)) {
					foreach ($extras as $ex) {
						if ($cex1 = $this->Extra->find("first", array('recursive' => FALSE, 'conditions' => array('Extra.category_id' => $cur['Extrascategory']['id'], 'Extra.name_zh' => $ex['name'])))) {
							$cex = $cex1['Extra'];
							$cex['price'] = $ex['price'];
							$cex['status'] = 'A';
						} else {
							$cex = array('id' => 0, 'name' => $ex['name'], 'name_zh' => $ex['name'], 'price' => $ex['price'], 'status' => 'A', 'category_id' => $cur['Extrascategory']['id'], 'created' => date("Y-m-d H:i:s"));
						}
						$this->Extra->save($cex);
						$this->Extra->clear();
					}
				} else if ($rc['type'] == 2) {
					$price = ((int)$extras['limit']) ? ($extras['total'] / (int)$extras['limit']) : $extras['total'];
					foreach ($extras['values'] as $ex) {
						if ($cex1 = $this->Extra->find("first", array('recursive' => FALSE, 'conditions' => array('Extra.category_id' => $cur['Extrascategory']['id'], 'Extra.name_zh' => $ex['name'])))) {
							$cex = $cex1['Extra'];
							$cex['price'] = $price;
							$cex['status'] = 'A';
						} else {
							$cex = array('id' => 0, 'name' => $ex['name'], 'name_zh' => $ex['name'], 'price' => $price, 'status' => 'A', 'category_id' => $cur['Extrascategory']['id'], 'created' => date("Y-m-d H:i:s"));
						}
						$this->Extra->save($cex);
						$this->Extra->clear();
					}
				}
			} else {
				$extras = json_decode($rc['options'], TRUE);
				$newcate = array(
						'Extrascategory' => array(
								'name' => $rc['name'],
								'name_zh' => $rc['name'],
								'extras_num' => 0,
								'status' => 'A',
								'modified' => $nowtm,
								'remote_id' => $rc['id']
						),
						'Extra' => array()
				);
				
				if (empty($rc['type']) || ($rc['type'] == 1)) {
					foreach ($extras['values'] as $ex) {
						$newcate['Extra'][] = array('id' => 0, 'name' => $ex['name'], 'name_zh' => $ex['name'], 'price' => $ex['price'], 'status' => 'A', 'created' => date("Y-m-d H:i:s"));
					}
				} else if ($rc['type'] == 2) {
					$newcate['Extrascategory']['extras_num'] = (int)$extras['limit'];
					$price = ((int)$extras['limit']) ? ($extras['total'] / (int)$extras['limit']) : $extras['total'];
					foreach ($extras['values'] as $ex) {
						$newcate['Extra'][] = array('id' => 0, 'name' => $ex['name'], 'name_zh' => $ex['name'], 'price' => $price, 'status' => 'A', 'created' => date("Y-m-d H:i:s"));
					}
				}
				$this->saveAssociated($newcate);
				$this->Extra->clear();
			}
			/* XXXXXXXXXX
			$dbo = $this->getDatasource();
			$logs = $dbo->getLog();
			echo "<pre>";
			print_r($logs);
			die("XXXXXXXX");
			*/
			
		}
		return '';
	}
}

?>