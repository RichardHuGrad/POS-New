<?php

App::uses('Component', 'Controller');

class SyncComponent extends Component {
    
    public $status = 'success';

	public function __construct() {
        $this->Admin = ClassRegistry::init('Admin');
  		$this->Order = ClassRegistry::init('Order');
        $this->OrderItem = ClassRegistry::init('OrderItem');
        $this->Category = ClassRegistry::init('Category');
	}


}

 ?>
