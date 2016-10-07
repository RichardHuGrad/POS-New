<?php

App::uses('Security', 'Utility');
App::uses('CakeEmail', 'Network/Email');

class User extends AppModel {

    public $name = 'User';
	
    public $validate = array(
        'name' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Name can\'t be empty',
                'allowEmpty' => false
            ),
            'between' => array(
                'rule' => array('between', 3, 50),
                'message' => 'Name must be between %d and %d characters',
            )
        ),
        'email' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Email can\'t be empty',
                'allowEmpty' => false
            ),
            'validEmail' => array(
                'rule' => array('validEmail'),
                'message' => 'Please enter valid email address',
                'allowEmpty' => false
            ),
            'isUnique' => array(
                'rule' => array('isUnique'),
                'message' => 'Email already exists, please use different one',
                'on' => 'create'
            )
        ),
        'password' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Password can\'t be empty',
                'allowEmpty' => false
            ),
           /* 'between' => array(
                'rule' => array('between', 5, 20),
                'message' => 'User Password must be between %d and %d characters',
            )*/
        ),
        'confirm_password' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Confirm password can\'t be empty',
                'allowEmpty' => false
            ),
            'compare' => array(
                'rule' => array('validate_passwords'),
                'message' => 'The passwords you entered do not match.',
            )
        ),
        'cleaner_password' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Password can\'t be empty',
                'allowEmpty' => false
            ),
            'between' => array(
                'rule' => array('between', 6, 20),
                'message' => 'Password must be between %d and %d characters',
            )
        ),
        'cleaner_confirm_password' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Confirm password can\'t be empty',
                'allowEmpty' => false
            ),
            'compare' => array(
                'rule' => array('validate_passwords_cleaner'),
                'message' => 'The passwords you entered do not match.',
            )
        ),
        'admin_old_password' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Old Password can\'t be empty',
                'allowEmpty' => false
            )
        ),
        'admin_password' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Password can\'t be empty',
                'allowEmpty' => false
            ),
            'between' => array(
                'rule' => array('between', 6, 20),
                'message' => 'Password must be between %d and %d characters',
            )
        ),
        'admin_confirm_password' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Confirm password can\'t be empty',
                'allowEmpty' => false
            ),
            'compare' => array(
                'rule' => array('validate_passwords_admin'),
                'message' => 'The passwords you entered do not match.',
            )
        ),
        'contact_number' => array(
           
            'allowNumberOnly' => array(
                'rule' => array('allowNumberOnly'),
                'message' => 'Contact number should be numeric only',
                'allowEmpty' => false
            ),
            'between' => array(
                'rule' => array('between', 8, 15),
                'message' => 'Contact number must be between %d and %d characters only',
            )
        ),
        'user_image' => array(
            'extension' => array(
                'rule' => array('check_extension'),
                'message' => 'User image extension should be of jpg, jpeg, JPG, JPEG, png, gif, PNG, GIF only'
            ),
            'upload-file' => array(
                'rule' => array('check_file_upload'),
                'message' => 'Error in uploading file'
            )
        )
    );

    public function validEmail($email) {
        $regExp = '/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i';
        if (!preg_match($regExp, $email['email'])) {
            return false;
        } else {
            return true;
        }
    }

    public function validate_passwords() {
		$this->data[$this->alias]['confirm_password']= md5($this->data[$this->alias]['confirm_password']);
        //echo $this->data[$this->alias]['confirm_password']; exit;
		return $this->data[$this->alias]['password'] === $this->data[$this->alias]['confirm_password'];
    }

    public function validate_passwords_cleaner() {
        return $this->data[$this->alias]['cleaner_password'] === $this->data[$this->alias]['cleaner_confirm_password'];
    }

    public function validate_passwords_admin() {
        return $this->data[$this->alias]['admin_password'] === $this->data[$this->alias]['admin_confirm_password'];
    }

    public function allowNumberOnly($number) {
        if (!is_numeric($number['contact_number'])) {
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

}
