<?php

class CousineExtrascategories extends AppModel {
    public $name = 'CousineExtrascategories';


	public function sync_extras($datas) {
		$nowtm = time();
		$extrascategory = ClassRegistry::init('Extrascategory');
		$cousine = ClassRegistry::init('Cousine');
		
		foreach ($datas as $rc) {
			if ($cur = $this->find("first", array('conditions' => array('remote_id' => $rc['id'])))) {
				// Has record. Update it if changed.
				$cur_category = $extrascategory->find("first", array('conditions' => array('remote_id' => $rc['option_id'])));
				$cur_cousine = $cousine->find("first", array('conditions' => array('remote_id' => $rc['dish_id'])));
				
				if (empty($cur_category)) {
					return "Unknown local category: [" . $rc['option_id'] . "]([" . $rc['id'] . "][" . $rc['dish_id'] . "] "; 
				}
				if (empty($cur_cousine)) {
					return "Unknown local cousine: [" . $rc['dish_id'] . "]([" . $rc['id'] . "][" . $rc['option_id'] . "] "; 
				}
				
				if (($cur_category['Extrascategory']['id'] != $cur['CousineExtrascategories']['extrascategorie_id']) || ($cur_cousine['Cousine']['id'] != $cur['CousineExtrascategories']['cousine_id'])) {
					$cur['CousineExtrascategories']['extrascategorie_id'] = $cur_category['Extrascategory']['id'];
					$cur['CousineExtrascategories']['cousine_id'] = $cur_cousine['Cousine']['id'];
					$cur['CousineExtrascategories']['tm'] = date("Y-m-d H:i:s");
					$this->saveAssociated($cur);
				}
			} else {
				$cur_category = $extrascategory->find("first", array('conditions' => array('remote_id' => $rc['option_id']), 'recursive' => FALSE));
				$cur_cousine = $cousine->find("first", array('conditions' => array('remote_id' => $rc['dish_id']), 'recursive' => FALSE));
				if (empty($cur_category)) {
					return "Unknown local category: [" . $rc['option_id'] . "]([" . $rc['id'] . "][" . $rc['dish_id'] . "] "; 
				}
				if (empty($cur_cousine)) {
					return "Unknown local cousine: [" . $rc['dish_id'] . "]([" . $rc['id'] . "][" . $rc['option_id'] . "] "; 
				}
				
				$cur = $this->find("first", array('conditions' => array('cousine_id' => $cur_cousine['Cousine']['id'], 'extrascategorie_id' => $cur_category['Extrascategory']['id'])));
				if ($cur) {
					// Has same define. Update remote_id only
					$cur['CousineExtrascategories']['remote_id'] = $rc['id'];
					$cur['CousineExtrascategories']['tm'] = date("Y-m-d H:i:s");
				} else {
					// No found, create new one
					$cur = array('CousineExtrascategories' => array(
							'id' => 0,
							'extrascategorie_id' => $cur_category['Extrascategory']['id'],
							'cousine_id' => $cur_cousine['Cousine']['id'],
							'remote_id' => $rc['id'],
							'tm' => date("Y-m-d H:i:s")
					));
				}
				$this->saveAssociated($cur);
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