<?php
class MemberTran extends AppModel {
	public $name = 'MemberTran';
	public $validate = array();
    public $belongsTo = array(
        'Member' => array(
            'className' => 'Member',
            'foreignKey' => 'id'
        ),
    );
	
	public function insertMember($cardnumber, $name, $phone, $notes) {
		$insert_data = array(
				'cardnumber' => $cardnumber,
				'name' => $name,
				'phone' => $phone,
				'notes' => $notes,
				'created' => date('Y-m-d') 
		);
		$this->save($insert_data, false);
		
		return $this->id;
	}
	
	public function searchMember($str) {
		$data = $this->find("All", array(
				'limit' => 10,
				'conditions' => array(
						'OR' => array(
							'Member.id LIKE ' => $str."%",
							'Member.cardnumber LIKE ' => $str."%",
							'Member.phone LIKE ' => $str."%",
							'Member.name LIKE ' => "%".$str."%",
						),
				) 
		));
		
		if (empty($data)) {
			return false;
		} else {
			return $data['Member'];
		}
	}
}
?>
