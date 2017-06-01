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

	public function getTableNos($args) {
		ApiHelperComponent::verifyRequiredParams($args, ['restaurant_id']);

		$tableDetail = $this->Admin->find('first', array(
									'fields' => array(
										'Admin.no_of_tables',
										'Admin.no_of_takeout_tables',
										'Admin.no_of_waiting_tables',
										'Admin.no_of_online_tables'
									),
									'conditions' => array(
										'Admin.id' => $args['restaurant_id']
									)
							));
		$res = array(
					'dineIn'  => $tableDetail['Admin']['no_of_tables'],
					'takeout' => $tableDetail['Admin']['no_of_takeout_tables'],
					'waiting' => $tableDetail['Admin']['no_of_waiting_tables'],
					'online'  => $tableDetail['Admin']['no_of_online_tables']);
		return $res;
	}

	public function getAllTablesSummary ($args) {
		return $this->Order->find('all', array(
                        'fields' => array('Order.id','Order.order_no', 'Order.table_status', 'Order.table_no','Order.order_type', 'Order.total', 'Order.paid', 'Order.created'),
						'recursive' => -1,
						'conditions' => array('Order.is_completed' => 'N')
                    ));
	}

	public function getTablesSummaryByType ($args) {
		ApiHelperComponent::verifyRequiredParams($args, ['type']);
		return $this->Order->find('all', array(
						'fields' => array('Order.id','Order.order_no', 'Order.table_status', 'Order.table_no','Order.order_type', 'Order.total', 'Order.paid', 'Order.created'),
						'recursive' => -1,
						'conditions' => array('Order.is_completed' => 'N', 'Order.order_type' => $args['type'])
					));
	}


    public function getOrderInfoByTable($args) {
        ApiHelperComponent::verifyRequiredParams($args, ['type', 'table']);
        $tableType = $args['type'];
        $tableNo = $args['table'];

        $orderDetail = $this->Order->find('first', array(
                            'conditions' => array('Order.order_type' => $tableType, 'Order.table_no' => $tableNo, 'Order.is_completed' => 'N')
                        ));
        if(empty($orderDetail)){
        	return array('ret' => 0, 'message' => 'Currently no order on this table!');
        }else{        
        	return $orderDetail;
        }
    }

    public function getOrderInfoById($args) {
        ApiHelperComponent::verifyRequiredParams($args, ['order_id']);
        $orderId = $args['order_id'];
        $orderDetail = $this->Order->find('first', array(
                            'conditions' => array('Order.id' => $orderId)
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
			'recursive' => -1,
			'conditions' => array('OrderItem.id' => $orderItemId)
        ));

        return $orderItemDetail;
    }

    public function getAllOrderItemsByOrderId($args) {
        ApiHelperComponent::verifyRequiredParams($args, ['order_id']);
        return $this->OrderItem->find('all', array(
                        'recursive' => -1,
                        // 'fields' => array('OrderItem')
                        'conditions' => array('OrderItem.order_id' => $args['order_id'])
                    ));
    }

    public function getAllCousines($args) {
        ApiHelperComponent::verifyRequiredParams($args, ['status']);
        return $this->Cousine->getAllCousines($args['status']);
        // return $this->Cousine->find('all', array(
        //     'conditions' => array('Cousine.status' => $args['status'])
        // ));
    }

    public function getAllCousineCategories($args) {
        ApiHelperComponent::verifyRequiredParams($args, ['status']);
        return $this->Category->getAllCategories($args['status']);
        // return $this->Category->find('all', array(
        //     'conditions' => array('Category.status' => $args['status'])
        // ));
    }

    public function getCousinesByCategoryId($args) {
        ApiHelperComponent::verifyRequiredParams($args, ['category_id']);
        return $this->Cousine->getAllCousinesByCategoryId($args['category_id']);
        // return $this->Cousine->find('all', array(
        //     'conditions' => array(
        //         'category_id' =>  $args['category_id']
        //     )
        // ));
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
    }

    // public function getAllTableStatus() {
    //     return $this->Order->find('list', array(
    //                     'fields' => array('Order.table_no', 'Order.table_status', 'Order.order_type'),
    //                     'conditions' => array('Order.is_completed' => 'N')
    //                 ));
    // }
	//
    // public function getAllDineinTableStatus() {
    //     return $this->Order->find("list", array(
    //                     'fields' => array('Order.table_no', 'Order.table_status'),
    //                     'conditions' => array('Order.is_completed' => 'N', 'Order.order_type' => 'D')
    //                         )
    //                 );
    // }
	//
    // public function getAllTakeoutTableStatus() {
    //     return $this->Order->find("list", array(
    //                     'fields' => array('Order.table_no', 'Order.table_status'),
    //                     'conditions' => array('Order.is_completed' => 'N', 'Order.order_type' => 'T')
    //                         )
    //                 );
    // }
	//
    // public function getAllWaitingTableStatus() {
    //     return $this->Order->find("list", array(
    //                     'fields' => array('Order.table_no', 'Order.table_status'),
    //                     'conditions' => array('Order.is_completed' => 'N', 'Order.order_type' => 'W')
    //                         )
    //                 );
    // }


}
