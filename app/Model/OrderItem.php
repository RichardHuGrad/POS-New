<?php

class OrderItem extends AppModel {

    public $name = 'OrderItem';
    public $validate = array();

    public $belongsTo = array(
        'Order' => array(
            'className' => 'Order',
            'foreignKey' => 'order_id'
        ),
    );


	public function getOrderItemPrintStatus($order_id) {

    }

    public function insertOrderItem($order_id, $item_id, $name_en, $name_xh, $price, $category_id, $all_extras, $tax, $tax_amount, $qty) {
    	$insert_data = array(
            'order_id' => $order_id,
            'item_id' => $item_id,
            'name_en' => $name_en,
            'name_xh' => $name_xh,
            'price' => $price,
            'category_id' => $category_id,
            'created' => date('Y-m-d H:i:s'),
            'all_extras' => $all_extras, 
            'tax' => $tax,
            'tax_amount' => $tax_amount,
            'qty' => $qty,
        );


        $this->save($insert_data, false);
    }

    public function updateExtraAmount($id) {
    	$item_detail = $this->find("first", array(
                'fields' => array('OrderItem.id', 'OrderItem.price', 'OrderItem.order_id', 'OrderItem.extras_amount', 'OrderItem.selected_extras', 'OrderItem.tax' ,'OrderItem.tax_amount'),
                'conditions' => array('OrderItem.id' => $id)
                    )
            );
    	$extras_amount = 0;
    	// print_r($item_detail['OrderItem']['selected_extras']);
    	// echo gettype($item_detail['OrderItem']['selected_extras']);
    	if (!empty($item_detail['OrderItem']['selected_extras'])) {
    		$extras_array = json_decode($item_detail['OrderItem']['selected_extras'], true);
    		foreach ($extras_array as $extra) {
	    		$extras_amount += floatval($extra['price']);
	    	}
	    	// print_r ($extras_array);
    	} else {
    		return;
    	}

    	$item_detail['OrderItem']['extras_amount'] = $extras_amount;

    	$item_detail['OrderItem']['tax_amount'] = ($item_detail['OrderItem']['extras_amount'] + $item_detail['OrderItem']['price'] ) * $item_detail['OrderItem']['tax'] / 100;

    	$this->save($item_detail, false);

    	// notice: keep update order at the end
    	$this->Order->updateBillInfo($item_detail['OrderItem']['order_id']);
    }


}

?>