<?php 

class SplitController extends AppController {

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