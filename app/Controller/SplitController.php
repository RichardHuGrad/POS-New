<?php 

class SplitController extends AppController {


    public function donepayment() {

        $this->layout = false;
        $this->autoRender = NULL;

        // pr($this->data); die;
        // get all params
        $order_id = $this->data['order_id'];
        $table = $this->data['table'];
        $type = $this->data['type'];
        $paid_by = strtoupper($this->data['paid_by']);

        $pay = $this->data['pay'];
        $change = $this->data['change'];

        // save order to database        
        $data['Order']['id'] = $order_id;
        if ($this->data['card_val'] and $this->data['cash_val'])
            $data['Order']['paid_by'] = "MIXED";

        elseif ($this->data['card_val'])
            $data['Order']['paid_by'] = "CARD";

        elseif ($this->data['cash_val'])
            $data['Order']['paid_by'] = "CASH";

        $data['Order']['table_status'] = 'P';

        $data['Order']['paid'] = $pay;
        $data['Order']['change'] = $change;
        $data['Order']['is_kitchen'] = 'Y';

        $data['Order']['is_completed'] = 'Y';

        $data['Order']['card_val'] = $this->data['card_val'];
        $data['Order']['cash_val'] = $this->data['cash_val'];
        $data['Order']['tip_paid_by'] = $this->data['tip_paid_by'];
        $data['Order']['tip'] = $this->data['tip_val'];

        $this->loadModel('Order');
        $this->Order->save($data, false);

        // update popularity status
        $this->loadModel('Cousine');
        $this->Cousine->query("UPDATE cousines set `popular` = `popular`+1 where id in(SELECT (item_id) from order_items where order_id = '$order_id')");

        // save all 
        $this->Session->setFlash('Order successfully completed.', 'success');

        echo true;
        exit; //Modified by Yishou Liao @ Nov 29 2016
    }

    public function addPopular() {
    	$this->loadModel('OrderItem');
        // for ($i = 0; $i < count($order_detail); $i++) {
        //     $this->OrderItem->id = $order_detail[$i];
        //     $this->OrderItem->saveField('order_id', ((int) $max_id[0]['maxid'] + 1), false);
        // };
    }

    // 
    public function updateOriginalOrder() {

    }

	public function pay() {
        $this->layout = false;
        $this->autoRender = NULL;

        // pr($this->data); die;
        // get all params
        $order_id = $this->data['order_id'];
        $table_id = $this->data['table_id'];
        $order_type = $this->data['order_type'];
        $suborder_id = $this->data['suborder_id'];
        $subtotal = $this->data['subtotal'];
        $discount = $this->data['discount'];
        $tax = $this->data['tax'];
        $total = $this->data['total'];
        $change = $this->data['change'];
        $received = $this->data['received'];
        $tip = $this->data['tip'];
        $suborder_detail = json_decode($this->data['suborder_detail']);

        //  should change database tip_paid_by structure
        $tip_type;
        if (floatval($tip['card']) > 0 && floatval($tip['cash']) > 0) {
        	$tip_type = 'MIXED';
        } else if (floatval($tip['card']) > 0) {
        	$tip_type = 'CARD';
        } else if (floatval($tip['cash']) > 0) {
        	$tip_type = 'CASH';
        } else {
        	$tip_type = 'No TIP';
        }

        
        $paid_type;
        if (floatval($received['card']) > 0 && floatval($received['cash']) > 0) {
        	$paid_type = 'MIXED';
        } else if (floatval($received['card']) > 0) {
        	$paid_type = 'CARD';
        } else if (floatval($received['cash']) > 0) {
        	$paid_type = 'CASH';
        } else {
        	$paid_type = 'No PAID';
        }

        echo $paid_type;

        

        $new_orderno = $order_id . '_' . $suborder_id;
        $data['Order']['order_no'] = $new_orderno;
        
        // $data['Order']['recorder_no'] = $split_detail['Order']['recorder_no'];
        // $data['Order']['hide_no'] = $split_detail['Order']['hide_no'];
        // $data['Order']['cashier_id'] = $split_detail['Order']['cashier_id'];
        // $data['Order']['counter_id'] = $split_detail['Order']['counter_id'];
        echo $new_orderno;
        $data['Order']['table_no'] = $table_id;
        $data['Order']['table_status'] = 'P';
        $data['Order']['tax'] = round($tax["tax"] * 100);
        $data['Order']['tax_amount'] = $tax['amount'];
        $data['Order']['subtotal'] = $subtotal;
        $data['Order']['total'] = $total;
        $data['Order']['card_val'] = $received['card'];
        $data['Order']['cash_val'] = $received['cash'];
        $data['Order']['tip'] = $tip['amount'];
        /*if($tip_type) 
        	$data['Order']['tip_paid_by'] = $tip_type;*/
        $data['Order']['paid'] = $received['total'];
        $data['Order']['change'] = $change;
        $data['Order']['order_type'] = $order_type;
		$data['Order']['is_kitchen'] = 'Y';
        $data['Order']['is_completed'] = 'Y';
        $data['Order']['paid_by'] = $paid_type;
        if ($discount["type"] = 'fixed') {
        	$data['Order']['fix_discount'] = $discount['value'];
        	$data['Order']['discount_value'] = $discount['value'];
        } else if ($discount["type"] = 'percent') {
        	$data['Order']['percent_discount'] = $discount['value'];
        	$data['Order']['discount_value'] = round(floatval($subtotal) * floatval($discount['value']) / 100);
        }

        $this->loadModel('Order');
        $this->Order->save($data);
        echo json_encode($data);

       

        // $this->Order->save($data, false);

        /*$paid_by = strtoupper($this->data['paid_by']);
        $split_method = $this->data['split_method'];

        $pay = $this->data['pay'];
        $change = $this->data['change'];
        if ($this->data['card_val'] and $this->data['cash_val'])
            $data['Order']['paid_by'] = "MIXED";

        elseif ($this->data['card_val'])
            $data['Order']['paid_by'] = "CARD";

        elseif ($this->data['cash_val'])
            $data['Order']['paid_by'] = "CASH";


        $data['Order']['paid'] = $pay;
        $data['Order']['change'] = $change;
        $data['Order']['discount'] = $this->data['discount']; //Modified by Yishou Liao @ Nov 19 2016
        $data['Order']['is_kitchen'] = 'Y';


        $data['Order']['card_val'] = $this->data['card_val'];
        $data['Order']['cash_val'] = $this->data['cash_val'];
        $data['Order']['tip_paid_by'] = $this->data['tip_paid_by'];
        $data['Order']['tip'] = $this->data['tip_val'];*/

 /*       if ($split_method == 0) {//平均分单
            // save order to database        
            $data['Order']['id'] = $order_id;
            $data['Order']['is_completed'] = 'Y';
            $data['Order']['table_status'] = 'P';

            $this->loadModel('Order');
            $this->Order->save($data, false);

            // update popularity status
            $this->loadModel('Cousine');
            $this->Cousine->query("UPDATE cousines set `popular` = `popular`+1 where id in(SELECT (item_id) from order_items where order_id = '$order_id')");

            // save all 
            $this->Session->setFlash('Order successfully completed.', 'success');
            echo true;
        } else {//按每个人点的菜分单
            $account_no = $this->data['account_no'];
            $order_detail = explode(",", $this->data['order_detail']);
            $this->loadModel('Order');
            $split_detail = $this->Order->find("first", array('fields' => array('Order.order_no', 'Order.table_no', 'Order.total', 'Order.tax', 'Order.reorder_no', 'Order.hide_no', 'Order.cashier_id', 'Order.counter_id', 'Order.promocode', 'Order.message', 'Order.reason', 'Order.order_type', 'Order.cooking_status', 'Order.is_hide', 'Order.discount_value'), 'conditions' => array('Order.id' => $order_id), 'recursive' => false));
            
            $max_id = $this->Order->find("first", array('fields' => array('MAX(Order.ID) as maxid')));
            $new_orderno = $split_detail['Order']['order_no'] . "_" . ((int) $max_id[0]['maxid'] + 1);

            $data['Order']['order_no'] = $new_orderno;
            $data['Order']['tax'] = round($split_detail['Order']['tax'], 2);
            $data['Order']['recorder_no'] = $split_detail['Order']['recorder_no'];
            $data['Order']['hide_no'] = $split_detail['Order']['hide_no'];
            $data['Order']['cashier_id'] = $split_detail['Order']['cashier_id'];
            $data['Order']['counter_id'] = $split_detail['Order']['counter_id'];
            $data['Order']['table_no'] = $split_detail['Order']['table_no'];

            $data['Order']['total'] = $data['Order']['paid'] - $data['Order']['change'];
            $data['Order']['subtotal'] = $data['Order']['total'] / ($data['Order']['tax']/100+1);

            $data['Order']['tax_amount'] = $data['Order']['total'] - $data['Order']['subtotal'];

            $data['Order']['is_completed'] = 'Y';
            //$data['Order']['discount_value'] = $split_detail['Order']['discount_value'];
            $data['Order']['discount_value'] = $data['Order']['discount']; //Modified by Yishou Liao @ Nov 19 2016
            $data['Order']['promocode'] = $split_detail['Order']['promocode'];
            $data['Order']['message'] = $split_detail['Order']['message'];
            $data['Order']['reason'] = $split_detail['Order']['reason'];
            $data['Order']['order_type'] = $split_detail['Order']['order_type'];
            $data['Order']['cooking_status'] = $split_detail['Order']['cooking_status'];
            $data['Order']['is_hide'] = $split_detail['Order']['is_hide'];

            $this->Order->save($data);
            $sumsubtotal1 = $this->Order->query("SELECT SUM(`subtotal`) as sumsubtotal, SUM(discount_value) as discount_value FROM `orders` WHERE `order_no` LIKE '%" . $split_detail['Order']['order_no'] . "_%'");

            $this->loadModel('OrderItem');
            for ($i = 0; $i < count($order_detail); $i++) {
                $this->OrderItem->id = $order_detail[$i];
                $this->OrderItem->saveField('order_id', ((int) $max_id[0]['maxid'] + 1), false);
            };

            $sumsubtotal2 = $this->Order->query("SELECT `subtotal` FROM `orders` WHERE `order_no` = '" . $split_detail['Order']['order_no'] . "'");
            
            if (($sumsubtotal1[0][0]['sumsubtotal'] + $sumsubtotal1[0][0]['discount_value']) >= $sumsubtotal2[0]['orders']['subtotal']) {
                $this->Order->query("DELETE FROM `orders` WHERE id =  " . $order_id);
            };

            echo true;
        };
        */
        exit;
    }

}



 ?>