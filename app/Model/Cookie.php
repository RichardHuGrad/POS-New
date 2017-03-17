<?php

class Cookie extends AppModel {

    public $name = 'Cookie';
    public $validate = array();

    public function setCookie($key, $value) {

		// $this->removeCookie($key);
        $data = $this->find('first', array(
    			'conditions' => array(
    				'key' => $key
    				)
    		));

        if (!empty(data)) {
            $data['Cookie']['value'] = $value;
            $data['Cookie']['created'] = date('Y-m-d H:i:s');
            $this->save($data, false);
        } else {
            $insert_data = array(
                'key' => $key,
                'value' => $value,
                'created' => date('Y-m-d H:i:s'),
            );
            $this->save($insert_data, false);
        }



    }

    public function getCookie($key) {
    	$data = $this->find('first', array(
    			'conditions' => array(
    				'key' => $key
    				)
    		));

    	if (isset($data['Cookie'])) {
    		return $data['Cookie']['value'];
    	} else {
    		return ;
    	}


    }

    public function removeCookie($key) {

    	// $data['key'] = $key;
    	$this->deleteAll(array('Cookie.key' => $key), false);
    }


}

 ?>
