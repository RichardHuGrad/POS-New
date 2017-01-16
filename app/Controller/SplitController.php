<?php 

class SplitController extends AppController {

    public function split() {
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
            'fields' => array('Order.paid', 'Order.tip', 'Order.cash_val', 'Order.card_val', 'Order.change', 'Order.order_no', 'Order.tax', 'Order.table_status', 'Order.tax_amount', 'Order.subtotal', 'Order.total', 'Order.message', 'Order.discount_value', 'Order.promocode', 'Order.fix_discount', 'Order.percent_discount'),
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

        // print_r ($Order_detail);

        $this->set(compact('Order_detail', 'cashier_detail', 'type', 'table', 'orders_no', 'split_method'));
    }



    public function addPopular($order_id) {
        $this->layout = false;
        $this->autoRender = NULL;

    	$this->loadModel('Cousine');
        $this->Cousine->query("UPDATE cousines set `popular` = `popular`+1 where id in(SELECT (item_id) from order_items where order_id = '$order_id')");
        

        $this->Session->setFlash('Order successfully completed.', 'success');
    }

    // 
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
        echo json_encode($data);
        // echo json_encode($originalOrder);
        $this->Order->save($data);

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

}



 ?>