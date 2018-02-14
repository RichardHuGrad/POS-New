<?php
/**
 * Class MemberController
 * 
 */
App::uses('PrintLib', 'Lib');
App::uses('OpencartController', 'Controller');
class MemberController extends AppController {
	public $fontStr1 = "simsun";
	public $components = array(
			'Paginator',
			'OrderHandler',
			'Access' 
	);
	
	/**
	 * beforeFilter
	 * 
	 * @return null
	 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('index', 'forgot_password');
		$this->Auth->allow('index', 'checkin');
		$this->layout = "default";
	}
	
	/**
	 * Website home page
	 * 
	 * @return mixed
	 */
	public function index() {
	}
	
	public function search() {
		$data = array("status" => "Fail", "message" => "Unknown");
		if ($this->request->is('post')) {
			$this->layout = false;
			$this->autoRender = NULL;

			$this->Member = ClassRegistry::init('Member');
			$this->MemberTran = ClassRegistry::init('MemberTran');
			$str = $this->request->data['search'];
			
			$rts = $this->Member->searchMember($str);
			if ($rts) {
				$data['status'] = 'OK';
				$data['message'] = "Find members ";
				$data['members'] = $rts;
			}
		}
		echo json_encode($data);
		exit();
	}
	
	public function edit() {
		$data = array("status" => "Fail", "message" => "Unknown");
		if ($this->request->is('post')) {
			$this->Member = ClassRegistry::init('Member');
			$this->MemberTran = ClassRegistry::init('MemberTran');
			$member = array();
			if ($member_id = $this->request->data['member_id']) {
				$member['id'] = $member_id;
			}
			$member['cardnumber'] = $this->request->data['cardnumber'];
			$member['name'] = $this->request->data['name'];
			$member['phone'] = $this->request->data['phone'];
			$member['notes'] = $this->request->data['notes'];
					
			$this->layout = false;
			$this->autoRender = NULL;
			
			if ($mbm = $this->Member->save($member)) {
				$data['status'] = 'OK';
				$data['message'] = "Added new member ";
				$data['member'] = $mbm['Member'];
			} else {
				$data['message'] = "Data error";
			}
		}
		echo json_encode($data);
		exit();
	}
	
	public function member() {
		$this->layout = false;
		$this->autoRender = NULL;
		
		$data = array("status" => "Fail", "message" => "Unknown");
		if ($this->request->is('post')) {
			$this->loadModel('Member');
				
			$member_id = $this->data['member_id'];
			
			$this->Member->virtualFields = array(
					'amount' => "SELECT SUM(member_trans.amount) FROM member_trans WHERE member_trans.member_id=Member.id",
					'paid' => "SELECT SUM(member_trans.amount) FROM member_trans WHERE member_trans.member_id=Member.id AND member_trans.opt='Pay'",
					'filled' => "SELECT SUM(member_trans.amount) FROM member_trans WHERE member_trans.member_id=Member.id AND member_trans.opt!='Pay'",
			);
			$member = $this->Member->find("first", array(
					'fields' => array('id', 'cardnumber', 'name', 'phone', 'tm', 'notes', 'created', 'amount', 'paid', 'filled'),
					'order' => array('id DESC'),
					'conditions' => array(
							'Member.id' => $member_id 
					) 
			));
			if ($member) {
				$data['status'] = 'OK';
				$data['message'] = 'Member information';
				$data['member'] = $member['Member'];
				$data['trans'] = array();
				foreach ($member['MemberTran'] as $tran) {
					$dt = $this->Member->query("SELECT SUM(member_trans.amount) as total FROM member_trans WHERE member_trans.member_id='".(int)$member_id."' AND member_trans.id<='".(int)$tran['id']."'");
					$tran['total'] = $dt[0][0]['total'];
					$tran['tm'] = substr($tran['tm'], 0, 10);
					$data['trans'][] = $tran;
				}
			}
		}
		echo json_encode($data);
		exit();
	}
	public function add_fund() {
		$this->layout = false;
		$this->autoRender = NULL;
		
		$data = array("status" => "Fail", "message" => "Unknown");
		if ($this->request->is('post')) {
			$member_id = $this->data['member_id'];
			$amount = $this->data['amount'];
			$opt = $this->data['opt'];	// Cash, Credit Card
			// $table_no = $this->params['named']['table_no'];
			
			$this->Member = ClassRegistry::init('Member');
			$this->MemberTran = ClassRegistry::init('MemberTran');
			
			$member = $this->Member->find("first", array(
					// 'fields' => array('Cashier.firstname', 'Cashier.lastname', 'Cashier.id', 'Cashier.image', 'Admin.id'),
					'conditions' => array(
							'Member.id' => $member_id 
					) 
			));
			
			if ($member) {
				$trans = $this->MemberTran->save(array(
						'member_id' => $member_id, 
						'opt' => $opt,
						'amount' => $amount
				));
				if ($trans) {
					$data['status'] = 'OK';
					$data['message'] = 'Added amount: ' . $amount;
				} else {
					$data['message'] = 'Add amount: ' . $amount . ' Failed';
				}
			} else {
				$data['message'] = 'Unknown Member';
			}
		}
		echo json_encode($data);
		exit();
	}

	public function pay_fund() {
		$this->layout = false;
		$this->autoRender = NULL;
		
		$data = array("status" => "Fail", "message" => "Unknown");
		if ($this->request->is('post')) {
			$member_id = $this->data['member_id'];
			$amount = $this->data['amount'];
			$type = 'Pay';
			// $table_no = $this->params['named']['table_no'];
			
			$this->Member = ClassRegistry::init('Member');
			$this->MemberTran = ClassRegistry::init('MemberTran');
			
			$member = $this->Member->find("first", array(
					// 'fields' => array('Cashier.firstname', 'Cashier.lastname', 'Cashier.id', 'Cashier.image', 'Admin.id'),
					'conditions' => array(
							'Member.id' => $member_id 
					) 
			));
			
			if ($member) {
				$trans = $this->MemberTran->save(array(
						'member_id' => $member_id, 
						'opt' => $type,
						'amount' => (-1) * $amount
				));
				if ($trans) {
					$data['status'] = 'OK';
					$data['message'] = 'Paid amount: ' . $amount;
				} else {
					$data['message'] = 'Pay amount: ' . $amount . ' Failed';
				}
			} else {
				$data['message'] = 'Unknown Member';
			}
		}
		echo json_encode(array(
				'member' => $Member,
				'trans' => $trans 
		));
		exit();
	}
}