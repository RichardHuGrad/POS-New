<?php
App::uses('Component', 'Controller');

class ApiHelperComponent extends Component {
	
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
        
}

?>
