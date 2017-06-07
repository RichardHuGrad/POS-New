<?php

class Extra extends AppModel {

    public $name = 'Extra';


    public $belongsTo = array(
        'Cousine' => array(
            'className' => 'Cousine',
            'foreignKey' => 'cousine_id'
        )
    );

	
    public $validate = array(
        'name' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Last name can\'t be empty',
                'allowEmpty' => false
            )
        ),        
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
        if (!is_numeric($number['mobile_no'])) {
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