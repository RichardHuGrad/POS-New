<?php

class Cookie extends AppModel {

    public $name = 'Cookie';
    public $validate = array();

    public function setCookie($key, $value) {

		//$this->removeCookie($key);
        $data = $this->find('first', array(
    			'conditions' => array(
    				'key' => $key
    				)
    		));

        if (!empty($data)) {
        	
        	//判断如果没有suborder,则删除所有cookie
        	$arr = explode("_", $key);
        	if($arr[2] == 'order'){
        		$ord = json_decode($value);
        		if($ord->suborderNum==0){
        			$order_no = $arr[0];
        			$this->deleteAll(array('Cookie.key like' => $order_no.'%'), false);
        			return false;
        		}
        	}
        	#判断结束
        	
            $data['Cookie']['value'] = $value;
            $data['Cookie']['created'] = date('Y-m-d H:i:s');
            $this->save($data, false);
        } else {
            $this->createCookie($key, $value);
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

    public function createCookie($key, $value) {
        $insert_data = array(
            'key' => $key,
            'value' => $value,
            'created' => date('Y-m-d H:i:s'),
        );

        $this->save($insert_data, false);
    }


}

 ?>
