<?php

App::uses('Security', 'Utility');
App::uses('CakeEmail', 'Network/Email');

class Admin extends AppModel {

    public $name = 'Admin';

    public $validate = array(

        'firstname' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'First name can\'t be empty',
                'allowEmpty' => false
            )
        ),
        'lastname' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Last name can\'t be empty',
                'allowEmpty' => false
            )
        ),
        'email' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Email can\'t be empty',
                'allowEmpty' => false
            ),
            // 'validEmail' => array(
            //     'rule' => array('email'),
            //     'message' => 'Please enter valid email address',
            //     'allowEmpty' => false
            // ),
            'isUnique' => array(
                'rule' => array('isUnique'),
                'message' => 'Email already exists, please use different one'
            )   
        ),
        'old_password' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Password can\'t be empty',
                'allowEmpty' => false
            ),
            'rule1' => array(
                'rule' => array('old_password_check'),
                'message' => 'Incorrect old password',
            )
        ),
        'password' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Password can\'t be empty',
                'allowEmpty' => false
            ),
            'between' => array(
                'rule' => array('between', 5, 20),
                'message' => 'Password must be between %d and %d characters',
            ),
            'rule1' => array(
                'rule' => array('compare_old_new_password'),
                'message' => 'Old password and new password can\'t be same',
            )
        ),
        'confirm_password' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Confirm password can\'t be empty',
                'allowEmpty' => false
            ),
            'compare' => array(
                'rule' => array('validate_passwords'),
                'message' => 'Password and confirm password not matched',
            )
        ),
        'restaurant_name' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Restaurant name can\'t be empty',
                'allowEmpty' => false
            )
        ),
        'mobile_no' => array(
            'allowNumberOnly' => array(
                'rule' => array('allowNumberOnly'),
                'message' => 'Mobile number should be numeric only',
                'allowEmpty' => false
            ),
            'between' => array(
                'rule' => array('between', 8, 15),
                'message' => 'Contact number must be between %d and %d characters only',
            )
        ),
        'tax' => array(
            'rule' => array('decimal'),
            'message' => 'Please enter a valid tax amount',
            'allowEmpty' => false
        ),
        'no_of_table' => array(
            'rule' => array('decimal'),
            'message' => 'Please enter a valid no of table',
            'allowEmpty' => false
        )

    );

    //Check user entered old password is correct
    public function old_password_check(){

        $id = $this->data[$this->alias]['id'];
        $old_password = Security::hash($this->data[$this->alias]['old_password'], 'md5');

        return $this->find('count', array(
            'conditions' => array(
                'Admin.password' => $old_password,
                'Admin.id' => $id
            ),
            'limit' => 1
        ));
    }

    //Check new paswword should not be same as existing password
    public function compare_old_new_password(){

        if(isset($this->data[$this->alias]['old_password'])) {
            $old_password = $this->data[$this->alias]['old_password'];
            $new_password = $this->data[$this->alias]['password'];

            return ($old_password == $new_password) ? false : true;
        }
        return true;
    }

    public function validEmail($email) {
        $regExp = '/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i';
        if (!preg_match($regExp, $email['email'])) {
            return false;
        } else {
            return true;
        }
    }

    public function validate_passwords() {
		return $this->data[$this->alias]['password'] === $this->data[$this->alias]['confirm_password'];
    }

    public function allowNumberOnly($number) {
        if (!is_numeric($number['mobile_no'])) {
            return FALSE;
        } else {
            return true;
        }
    }   
	function ChangePassword() {
		
		$validate1 = array(
				'password'=>array(
								'mustNotEmpty'=>array(
								'rule' => 'notEmpty',
								'message'=> 'Please enter password',
								'last'=>true)
								),
							'confirm_password'=>array(
								'rule1'=>array(
								'rule' => 'notEmpty',
								'message'=> 'Please enter confirm password',
								'on' => 'create'
								),
								'rule2'=>array(
								'rule'=>'matchuserspassword',
								'message'=> 'Password and confirm password does not match.'
								)
								)
			);
			
		$this->validate=$validate1;
		return $this->validates();
	}
	
	public function matchuserspassword(){
		  //return $this->data[$this->alias]['password'] === $this->data[$this->alias]['confirm_password'];
		$password		=	$this->data['User']['password'];
		$temppassword	=	$this->data['User']['confirm_password'];
		if($password==$temppassword)
			return true;
		else
			return false;
	
	}

    public function getLogoPathByid($id) {
        $logo_path = $this->find('first', array(
                'fields' => array('Admin.logo_path'),
                'conditions' => array('Admin.id' => $id)
            ))['Admin']['logo_path'];

        return $logo_path;
    }

    public function getKitchenPrinterName($id) {
        $cashier_detail = $this->find("first", array(
            'fields' => array('Admin.kitchen_printer_device'),
            'conditions' => array('Admin.id' => $id)
                )
        );

        return $cashier_detail['Admin']['kitchen_printer_device'];
    }

    public function getServicePrinterName($id) {
        $cashier_detail = $this->find("first", array(
            'fields' => array('Admin.service_printer_device'),
            'conditions' => array('Admin.id' => $id)
                )
        );

        return $cashier_detail['Admin']['service_printer_device'];
    }
}
