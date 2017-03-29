<?php

class DiscountController extends AppController {

    public $components = array('DiscountHandler');

	public function addDiscount() {
		$this->layout = false;
        $this->autoRender = NULL;

		$res = $this->DiscountHandler->addDiscount(array(
			'order_no' => $this->data['order_no'],
			'fix_discount' => $this->data['fix_discount'],
			'percent_discount' => $this->data['discount_percent'],
			'promocode' => $this->data['promocode']
		));

        return json_encode($res);
	}

	public function removeDiscount() {
		$this->layout = false;
        $this->autoRender = NULL;

        $res = $this->DiscountHandler->removeDiscount(array(
			'order_no' => $this->data['order_no']
		));

        return json_encode($res);
	}


}
