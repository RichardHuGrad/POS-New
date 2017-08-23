<?php

class Restaurant extends AppModel {

    public $name = 'Restaurant';
	
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
            'validEmail' => array(
                'rule' => array('email'),
                'message' => 'Please enter valid email address',
                'allowEmpty' => false
            ),
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

    public function validate_passwords() {
        return $this->data[$this->alias]['password'] === $this->data[$this->alias]['confirm_password'];
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

    public function allowNumberOnly($number) {
    	$no = str_replace("-","",$number['mobile_no']);
        //if (!is_numeric($number['mobile_no'])) {
        if (!is_numeric($no)) {
            return false;
        } else {
            return true;
        }
    }

    public function validate_file($file_data, $field)
    {
        $file_data = array_shift($file_data);

        if (0 === $file_data['error']) {

            $allowed_extensions = array();
            if('age_proof' == $field){
                $allowed_extensions = array('pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx');
            }

            $file_name = $file_data['name'];
            $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
            if(!in_array($file_ext, $allowed_extensions)) {
                return false;
            }
        }
        return true;

    }

}

?>