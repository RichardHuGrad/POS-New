<?php
App::uses('Component', 'Controller');

class ApiHelperComponent extends Component {

    public function __construct() {
        $this->Admin = ClassRegistry::init('Admin');
    }
	
    public static function verifyRequiredParams($args, $required_fields) {
        $error = false;
        $error_fields = "";

        foreach ($required_fields as $field) {
            if (!isset($args[$field])) {
                $error = true;
                $error_fields .= $field . ', ';
                throw new Exception('Missing argument: ' . $field);
            }
        }

        return !$error;
    }
    

    //verify admin password
    public function isAdminPassword($args) {
    	
        ApiHelperComponent::verifyRequiredParams($args, ['password']);

        // get all params
        $password     = md5($args['password']);
        $ret = $this->Admin->find("first", array(
            'fields' => array('Admin.id'),
            'conditions' => array('Admin.is_super_admin'=>'Y','Admin.password' => $password)
        ));

        if (empty($ret)) {
           return array('ret' => 0, 'message' => 'No');
        } else {
           return array('ret' => 1, 'message' => 'Yes');
        }
    }
    
}

?>
