<?php
App::uses('Component', 'Controller');
App::uses('PrintLib', 'Lib');
App::uses('TimeComponent', 'Component');

class ResourceHandlerComponent extends Component {

    public function __construct() {
        $this->Admin = ClassRegistry::init('Admin');
        $this->Order = ClassRegistry::init('Order');
        $this->OrderItem = ClassRegistry::init('OrderItem');
        $this->Category = ClassRegistry::init('Category');
    }

    // public $components = array('Session',
    public function getOrderInfoByTable($args) {
        $tableType = $args['type'];
        $tableNo = $args['table'];

        $orderDetail = $this->Order->find('first', array(
                                'conditions' => array(
                                    'order_type' => $tableType,
                                    'table_no' => $tableNo
                                )
                            ));
        return json_encode($orderDetail);
    }

}
