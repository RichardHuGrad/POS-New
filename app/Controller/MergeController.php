<?php 
class MergeController extends AppController {

	public function getOrdersAmount() {
		$this->layout = false;
        $this->autoRender = NULL;
        $this->loadModel('Order');

        $order_ids = $this->data['order_ids'];
        $orders = array();
        foreach($order_ids as $order_id) {
        	$temp = $this->Order->find('first', array(
        		'conditions' => array(
        				'Order.id' => $order_id
        			)
        		));

        	array_push($orders, $temp['Order']);
        }

        // print_r($orders);

        return json_encode($orders);
	}
}