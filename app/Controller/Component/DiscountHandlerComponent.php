<?php
App::uses('Component', 'Controller');
App::uses('ApiHelperComponent', 'Component');

class DiscountHandlerComponent extends Component {
	public $status = 'success';

    public function __construct() {
        $this->Order = ClassRegistry::init('Order');
        $this->Promocode = ClassRegistry::init('Promocode');
    }

    // todo change parameters
    // type
    // value
    // order_no
    public function addDiscount($args) {
        ApiHelperComponent::verifyRequiredParams($args, ['order_no']);

        // get all params
        $order_no = $args['order_no'];
        $order_id = $this->Order->getOrderIdByOrderNo($order_no);
        // $order_id = $args['order_id'];
        $fix_discount = isset($args['fix_discount']) ? floatval($args['fix_discount']) : 0;
        $percent_discount = isset($args['percent_discount']) ? floatval($args['percent_discount']) : 0;
        $promocode = $args['promocode'];

        $response = array('error' => false, 'message' => 'Add discount successfully');


        if ($fix_discount > 0) {
        	$response = $this->fixDiscountStrategy($order_id, $fix_discount);
        } else if ($percent_discount > 0) {
        	$response = $this->percentDiscountStrategy($order_id, $percent_discount);
        } else if ($promocode != "") {
        	$response = $this->promocodeDiscountStrategy($order_id, $promocode);
        }

        $Order_detail = $this->Order->find("first", array(
	    	'fields' => array('Order.id', 'Order.fix_discount', 'Order.promocode', 'Order.percent_discount'),
	    	'conditions' => array('Order.id' => $order_id)
    	));

        // print_r($data);
        // update order amount

        $this->Order->updateBillInfo($order_id);

        return $response;

    }

    public function removeDiscount($args) {
        ApiHelperComponent::verifyRequiredParams($args, ['order_no']);

        $order_no = $args['order_no'];
        $order_id = $this->Order->getOrderIdByOrderNo($order_no);

        $Order_detail = $this->Order->find("first", array(
            'fields' => array('Order.id', 'Order.fix_discount', 'Order.promocode', 'Order.percent_discount'),
            'conditions' => array('Order.id' => $order_id)
            ));

        $Order_detail['Order']['fix_discount'] = 0;
        $Order_detail['Order']['percent_discount'] = 0;
        $Order_detail['Order']['promocode'] = "";

        $this->Order->save($Order_detail);

        $this->Order->updateBillInfo($order_id);

    }


    public function fixDiscountStrategy($order_id, $discount_value) {
		$Order_detail = $this->Order->find("first", array(
        	'fields' => array('Order.id', 'Order.fix_discount', 'Order.promocode', 'Order.percent_discount'),
        	'conditions' => array('Order.id' => $order_id)
        	));
		$Order_detail['Order']['fix_discount'] = $discount_value;

		$this->Order->save($Order_detail, false);

		$response = array('error' => false, 'message' => 'Add discount successfully');

		return $response;
	}

	public function percentDiscountStrategy($order_id, $discount_value) {
		$Order_detail = $this->Order->find("first", array(
        	'fields' => array('Order.id', 'Order.fix_discount', 'Order.promocode', 'Order.percent_discount'),
        	'conditions' => array('Order.id' => $order_id)
        	));
		if ($discount_value > 100) {
    		$response = array('error' => true,'message' => 'Please add valid discount');
    	} else {
    		$response = array('error' => false, 'message' => 'Add discount successfully');
    		$Order_detail['Order']['percent_discount'] = $discount_value;

    		$this->Order->save($Order_detail, false);
    	}

		return $response;
	}


	public function promocodeDiscountStrategy($order_id, $promocode) {
		$promo_detail = $this->Promocode->find("first", array(
			'conditions' => array('Promocode.code' => $promocode)
			));

		$Order_detail = $this->Order->find("first", array(
        	'fields' => array('Order.id', 'Order.fix_discount', 'Order.promocode', 'Order.percent_discount'),
        	'conditions' => array('Order.id' => $order_id)
        	));

		$response = array('error' => false, 'message' => 'Discount successfully applied');

        if (empty($promo_detail)) {
            $response = array( 'error' => true, 'message' => 'Promocode does not exist.');
        } else {

			if (!(time() >= strtotime($promo_detail['Promocode']['valid_from']) and time() <= strtotime($promo_detail['Promocode']['valid_to']))) {
			    $response = array(
			        'error' => true,
			        'message' => 'Sorry, promo code is expired'
			    );
			} else if ($promo_detail['Promocode']['discount_type'] == 1) {// percent discount
				$Order_detail['Order']['promocode'] = $promocode;
				$Order_detail['Order']['percent_discount'] = $promo_detail['Promocode']['discount_value'];

				$this->Order->save($Order_detail, false);
        	}
        }

        return $response;
	}

}
