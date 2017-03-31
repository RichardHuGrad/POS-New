<?php
App::uses('Component', 'Controller');
App::uses('ApiHelperComponent', 'Component');


class TablesComponent extends Component {
    public $status = 'success';

    public function __construct() {
        $this->Admin = ClassRegistry::init('Admin');
        $this->Order = ClassRegistry::init('Order');
        $this->OrderItem = ClassRegistry::init('OrderItem');
        $this->Category = ClassRegistry::init('Category');
        $this->Cousine = ClassRegistry::init('Cousine');
        $this->Extra = ClassRegistry::init('Extra');
        $this->Extrascategory = ClassRegistry::init('Extrascategory');
    }

    public function index() {
        //  get all current table info

    }

    public function view($args) {
        ApiHelperComponent::verifyRequiredParams($args, ['type', 'table']);
        $tableType = $args['type'];
        $tableNo = $args['table'];

        $orderDetail = $this->Order->find('first', array(
                            'conditions' => array('Order.order_type' => $tableType, 'Order.table_no' => $tableNo, 'Order.is_completed' => 'N')
                        ));
        // $res = array('status' =>)
        return $orderDetail;
    }

    public function getOrderInfoById($args) {
        ApiHelperComponent::verifyRequiredParams($args, ['order_id']);
        $orderId = $args['order_id'];
        $orderDetail = $this->Order->find('first', array(
                            'conditions' => array('Order.order_id' => $orderId)
                        ));
        return $orderDetail;
    }

    public function getOrderInfoByOrderNo($args) {
        ApiHelperComponent::verifyRequiredParams($args, ['order_no']);
        $orderNo = $args['order_no'];
        $orderDetail = $this->Order->find('first', array(
                            'conditions' => array('Order.order_no')
                        ));
        return $orderDetail;
    }

    public function getOrderItemInfoById($args) {
        ApiHelperComponent::verifyRequiredParams($args, ['item_id']);
        $orderItemId = $args['item_id'];
        $orderItemDetail = $this->OrderItem->find('first', array(
            'conditions' => array('OrderItem.id' => $orderItemId)
        ));

        return $orderItemDetail;
    }

    public function getAllCousines($args) {
        ApiHelperComponent::verifyRequiredParams($args, ['status']);
        // return $this->Cousine->getAllCousines();
        return $this->Cousine->find('all', array(
            'conditions' => array('Cousine.status' => $args['status'])
        ));
    }

    public function getAllCousineCategories($args) {
        ApiHelperComponent::verifyRequiredParams($args, ['status']);
        // return $this->Category->getAllCategories();
        return $this->Category->find('all', array(
            'conditions' => array('Category.status' => $args['status'])
        ));
    }

    public function getCousinesByCategoryId($args) {
        ApiHelperComponent::verifyRequiredParams($args, ['category_id']);
        // return $this->Cousine->getAllCousinesByCategoryId($args['category_id']);
        return $this->Cousine->find('all', array(
            'conditions' => array(
                'category_id' =>  $args['category_id']
            )
        ));
    }


    public function getAllExtras($args) {
        ApiHelperComponent::verifyRequiredParams($args, ['status']);
        return $this->Extra->find('all', array(
            'conditions' => array('Extra.status' => $args['status'])
        ));
    }

    public function getAllExtraCategories($args) {
        ApiHelperComponent::verifyRequiredParams($args, ['status']);
        return $this->Extrascategory->find('all', array(
            'conditions' => array('Extrascategory.status' => $args['status'])
        ));
        // return null;
    }

}
