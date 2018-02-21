<?php
App::uses('PrintLib', 'Lib');
class SplitController extends AppController {

    public $components = array('Paginator');

    public function beforeFilter() {

        parent::beforeFilter();
        $this->Auth->allow('index', 'forgot_password');
        $this->Member = ClassRegistry::init('Member');
        $this->MemberTran = ClassRegistry::init('MemberTran');
        
        $this->layout = "default";
    }

    public function index() {
        // get cashier details
        $this->loadModel('Cashier');
        $cashier_detail = $this->Cashier->find("first", array(
            'fields' => array('Cashier.firstname', 'Cashier.lastname', 'Cashier.id', 'Cashier.image', 'Admin.id','Admin.kitchen_printer_device','Admin.service_printer_device'),
            'conditions' => array('Cashier.id' => $this->Session->read('Front.id'))
                )
        );

        $order_no = @$this->params['url']['order_no'];

        // get all params
        $type = $this->params['named']['type'];
        $table = $this->params['named']['table'];
        $split_method = @$this->params['named']['split_method'];

        if ($order_no) {
            $conditions = array('Order.cashier_id' => $cashier_detail['Admin']['id'],
                'Order.order_no' => $order_no
            );
        } else {
            $conditions = array('Order.cashier_id' => $cashier_detail['Admin']['id'],
                'Order.table_no' => $table,
                'Order.is_completed' => 'N',
                'Order.order_type' => $type
            );
        }

        // get order details
        $this->loadModel('Order');
        $this->loadModel('OrderItem');

        $this->OrderItem->virtualFields['image'] = "Select image from cousines where cousines.id = OrderItem.item_id";
        $Order_detail = $this->Order->find("first", array(
            'fields' => array('Order.paid', 'Order.tip', 'Order.cash_val', 'Order.card_val', 'Order.membercard_val', 'Order.membercard_id', 'Order.change', 'Order.order_no', 'Order.tax',  'Order.default_tip_rate', 'Order.table_status', 'Order.tax_amount', 'Order.subtotal', 'Order.total', 'Order.message', 'Order.discount_value', 'Order.promocode', 'Order.fix_discount', 'Order.percent_discount'),
            'conditions' => $conditions
                )
        );

        if (empty($Order_detail)) {
            $this->Session->setFlash('Sorry, order does not exist 抱歉，订单不存在。.', 'error');
            return $this->redirect(array('controller' => 'homes', 'action' => 'dashboard'));
        }
        $type = @$Order_detail['Order']['order_type'] ? @$Order_detail['Order']['order_type'] : $type;
        $table = @$Order_detail['Order']['table_no'] ? @$Order_detail['Order']['table_no'] : $table;

        // get all order no.
        $orders_no = $this->Order->find("list", array(
            'fields' => array('Order.order_type', 'Order.order_no', 'Order.table_no'),
            'conditions' => array('Order.cashier_id' => $cashier_detail['Admin']['id'], 'Order.is_completed' => 'N'),
            'recursive' => false
                )
        );

        $this->set(compact('Order_detail', 'cashier_detail', 'type', 'table', 'orders_no', 'split_method'));
    }

    public function addPopular($order_id) {
        $this->layout = false;
        $this->autoRender = NULL;

    	  $this->loadModel('Cousine');
        $this->Cousine->query("UPDATE cousines set `popular` = `popular`+1 where id in(SELECT (item_id) from order_items where order_id = '$order_id')");


        $this->Session->setFlash('Order successfully completed.', 'success');
    }

    public function updateOriginalOrder() {
        $this->layout = false;
        $this->autoRender = NULL;

        $this->loadModel('Order');

        $order_no = $this->data['order_no'];
        $originalOrder = $this->Order->find("first", array('fields' => array('Order.id, Order.order_no', 'Order.table_no'), 'conditions' => array('Order.order_no' => $order_no), 'recursive' => false));

        // $originalOrder['Order'][]

        $data['Order'] = $this->data;
        $data['Order']['id'] = $originalOrder['Order']['id'];
        $data['Order']['is_completed'] = 'Y';
        $data['Order']['table_status'] = 'P';
        echo json_encode($data);
        // echo json_encode($originalOrder);
        $this->Order->save($data);

        $o_splits = $this->Order->query("SELECT membercard_id, paid_membercard, order_no, total  FROM order_splits WHERE order_no = '".$data['Order']['order_no']. "'");
        if(!empty($o_splits[0]['order_splits'])){
        	foreach($o_splits as $os){
        		$member = $this->Member->find("first", array('recursive' => 0, 'conditions' => array('Member.id' => $os['order_splits']['membercard_id'])));
        		if ($member) {
        			$trans = $this->MemberTran->save(array('member_id' => $os['order_splits']['membercard_id'], 'order_number' => $os['order_splits']['order_no'], 'bill_amount' => $os['order_splits']['total'], 'opt' => 'Pay', 'amount' => $os['order_splits']['paid_membercard'] * (-1)));
        			$this->MemberTran->clear();
        		}
        	}
        }
        
        $order_id = $originalOrder['Order']['id'];
        $this->addPopular($order_id);
    }

    public function storeSuborder() {
        $this->layout = false;
        $this->autoRender = NULL;

        $this->loadModel('OrderSplit');

        $data['OrderSplit'] = $this->data;

        echo json_encode($data);
        $this->OrderSplit->save($data);
    }


    public function printOriginalBill($order_no, $table_no, $table_type, $printer_name, $print_zh=true, $is_receipt=true) {
        $this->layout = false;
        $this->autoRender = NULL;

        $print = new PrintLib();
        // echo $print->printCancelledItems($order_no, $table, $cancel_items['K'], 'K',true, true);
    }


    public function printSplitReceipt($order_no, $table_no, $table_type, $printer_name, $print_zh=true, $is_receipt=false) {

        $this->layout = false;
        $this->autoRender = NULL;

    }


    public function setCookie() {
        $this->layout = false;
        $this->autoRender = NULL;
        $this->loadModel('Cookie');


        $key = $this->data['key'];
        $value = $this->data['value'];

        $this->Cookie->setCookie($key, $value);

    }

    public function getCookie() {
        $this->layout = false;
        $this->autoRender = NULL;
        $this->loadModel('Cookie');

        $key = $this->data['key'];

        return $this->Cookie->getCookie($key);
    }

    public function removeCookie() {
        $this->layout = false;
        $this->autoRender = NULL;
        $this->loadModel('Cookie');

        $key = $this->data['key'];

        $this->Cookie->removeCookie($key);
    }
}

?>
