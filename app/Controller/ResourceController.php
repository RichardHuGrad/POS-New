<?php
App::uses('PrintLib', 'Lib');

class OrderController extends AppController {
    public $components = array('ResourceHandler');

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('index', 'forgot_password');
        // $this->layout = "default";
        // array_push($this->components, 'Order');
    }

    public function getOrderInfoByTable() {
        $this->layout = false;
        $this->autoRender = NULL;

        $type = $this->data['type'];
        $table = $this->data['table'];

        $res = $this->ResouceHandler->getOrderInfoByTable(array(
                    'type' => $type,
                    'table' => $table
                ));

        return $res;
    }
}
