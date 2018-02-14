<?php
class Member extends AppModel {
	public $name = 'Member';
	public $validate = array();
	public $hasMany = array(
			'MemberTran' => array(
					'className' => 'MemberTran',
					'foreignKey' => 'member_id' 
			) 
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
		$this->virtualFields = array('amount' => 'SELECT SUM(member_trans.amount) FROM member_trans WHERE member_trans.member_id=Member.id');
		$data = $this->find("all", array(
				'fields' => array('id', 'cardnumber', 'name', 'phone', 'amount'),
				'limit' => 10,
				'recursive' => 0,
				'conditions' => array(
						'OR' => array(
							'Member.id LIKE ' => $str."%",
							'Member.cardnumber LIKE ' => $str."%",
							'Member.phone LIKE ' => $str."%",
							'Member.name LIKE ' => "%".$str."%",
						)
				)
		));
		
		if (empty($data)) {
			return false;
		} else {
			return $data;
		}
	}
}
?>
