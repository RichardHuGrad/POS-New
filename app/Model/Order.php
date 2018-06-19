<?php
class Order extends AppModel {

    public $name = 'Order';
    public $validate = array();

    public $hasMany = array(
        'OrderItem' => array(
            'className' => 'OrderItem',
            'foreignKey' => 'order_id'
        ),
        'OrderSplit' => array(
            'className' => 'OrderSplit',
            'foreignKey' => 'order_no'
        ),
    );

    // public $belongsTo = array(
    //     'Admin' => array(
    //         'className' => 'Admin',
    //         'foreignKey' => false,
    //         'conditions' => array('Admin.id = Order.cashier_id')
    //     )
    // );

    public function next_order_no($pre) {
        $data = $this->find("first", array(
                'fields' => array('Order.order_no'),
                'order' => array('Order.id DESC')));
        if (empty($data['Order']['order_no'])) {
        	$order_no = "unknown number";
        } else {
        	$order_no = substr($data['Order']['order_no'], -10);
        }
        $today = date('ymd');
        if ($today != substr($order_no, 0, 6)) {
            $idx = 0;
        } else {
            $idx = (int)substr($order_no, 6);
        }
		$idx++;
		return $pre.$today.str_pad($idx, 4, "0", STR_PAD_LEFT);
    }

    // insert a new order in orders
    // return Order.id
    public function insertOrder($cashier_id, $counter_id, $table_no, $order_type, $tax, $default_tip_rate=0) {
    	  sleep(1);
        $insert_data = array(
            'order_no'   => $this->next_order_no($order_type.$table_no),
            //'order_no'   => $order_type.$table_no.date('ymdHi'),
            'cashier_id' => $cashier_id, // cashier should be restaurant_id
            'counter_id' => $counter_id,
            'table_no' => $table_no,
            'is_completed' => 'N',

            'order_type' => $order_type,
            'tax' => $tax,
            'default_tip_rate' => $default_tip_rate,
            'created' => date('Y-m-d H:i:s')
        );
        $this->save($insert_data, false);

        return $this->id;
    }

    public function getOrderIdByOrderNo($order_no) {
        $data = $this->find("first", array(
                'fields' => array('Order.id'),
                'conditions' => array('Order.order_no' => $order_no)
            ));
            
        if(empty($data)) 
           return false;
        else 
           return $data['Order']['id'];
    }

    public function getPhoneByOrderNo($order_no) {
    	
    	  //merged order_nos like "D61704181612,D81704181613"
    	  $order_no = explode(",", $order_no);
    	  
        $data = $this->find("first", array(
                'fields' => array('Order.phone'),
                'conditions' => array('Order.order_no' => $order_no)
            ));
            
        if(empty($data)) 
           return '';
        else 
           return $data['Order']['phone'];
    }

	public function update_reason($order_id, $reason, $message='') {
		$Order_detail = $this->find('first', array(
				'recursive' => -1,
				'conditions' => array('Order.id' => $order_id)
		));
		if ($Order_detail) {
			$Order_detail['Order']['reason'] = $reason;
			if (!empty($message)) {
				$Order_detail['Order']['message'] = $message;
			}
			$this->save($Order_detail, false);
		}
	}
    // recalculate order's bill info by count the OrderItem
    // include: subtotal, tax_amount, total, discount

    // edge case: remove all item
    public function updateBillInfo($order_id) {

        $Order_detail = $this->find('first', array(
                'recursive' => -1,
                'conditions' => array('Order.id' => $order_id)
        ));

        // get all items from OrderItem
        $order_item_list = $this->OrderItem->find('all', array(
                'recursive' => -1,
                'conditions' => array('OrderItem.order_id' => $order_id)
            ));

        $data = array();
        $data['Order']['id'] = $order_id;
        $data['Order']['subtotal'] = 0;
        $data['Order']['tax'] = floatval($Order_detail['Order']['tax']);
        $data['Order']['tax_amount'] = 0;
        $data['Order']['total'] = 0;
        $data['Order']['discount_value'] = 0;

        foreach ($order_item_list as $order_item) {
            $data['Order']['subtotal'] += ($order_item['OrderItem']['price'] + ($order_item['OrderItem']['extras_amount'] ? $order_item['OrderItem']['extras_amount'] : 0)) * $order_item['OrderItem']['qty'];
        }

        if ($Order_detail['Order']['fix_discount'] && $Order_detail['Order']['fix_discount'] > 0) {
            $data['Order']['discount_value'] = $Order_detail['Order']['fix_discount'];
        } else if ($Order_detail['Order']['percent_discount'] && $Order_detail['Order']['percent_discount'] > 0) {
            $data['Order']['discount_value'] = $data['Order']['subtotal'] * $Order_detail['Order']['percent_discount'] / 100;
        }

        $after_discount = $data['Order']['subtotal'] - $data['Order']['discount_value'];

        $after_discount = max(0, $after_discount);
        $data['Order']['after_discount'] = $after_discount;

        // tax should be after discount
        $data['Order']['tax_amount'] = $after_discount * $data['Order']['tax'] / 100;

		//缺省小费为税后金额乘以tip rate
		$data['Order']['default_tip_amount'] = ($after_discount + $data['Order']['tax_amount']) * $Order_detail['Order']['default_tip_rate']/100;
		
        $data['Order']['total'] = $after_discount + $data['Order']['tax_amount'] + $data['Order']['default_tip_amount'];

        $this->save($data, false);
    }


    public function getMergeOrderInfo($order_ids) {
        $data = array(
                "order_nos" => "",
                "table_nos" => "",
                "print_items" => array(),
                "subtotal" => 0,
                "discount_value" => 0,
                "after_discount" => 0,
                "tax" => 0,
                "tax_amount" => 0,
                "default_tip_rate" => 0,
                "default_tip_amount" => 0,
                "total" => 0,
                "paid" => 0,
                "change" => 0,
                "paid_by"=>"",
            );

        $order_nos = array();
        $table_nos = array();
        $printItems = array();

        foreach ($order_ids as $order_id) {
            $Order_detail = $this->find('first', array(
                    'conditions' => array('Order.id' => $order_id)
                ));
                
            array_push($data['print_items'], $this->OrderItem->getMergedItems($order_id));
            $data['subtotal'] += $Order_detail['Order']['subtotal'];
            $data['discount_value'] += $Order_detail['Order']['discount_value'];
            $data['after_discount'] += $Order_detail['Order']['after_discount'];
            $data['tax'] = $Order_detail['Order']['tax'];
            $data['tax_amount'] += $Order_detail['Order']['tax_amount'];

            $data['default_tip_rate'] = $Order_detail['Order']['default_tip_rate'];
            $data['default_tip_amount'] += $Order_detail['Order']['default_tip_amount'];

            $data['total'] += $Order_detail['Order']['total'];
            $data['paid'] += $Order_detail['Order']['paid'];
            $data['change'] += $Order_detail['Order']['change'];
            $data['paid_by'] = $Order_detail['Order']['paid_by'];

            array_push($order_nos, $Order_detail['Order']['order_no']);
            array_push($table_nos, $Order_detail['Order']['table_no']);
        }

        $data['order_nos'] = implode(",", $order_nos);
        $data['table_nos'] = implode(",", $table_nos);

        return $data;
    }


    public function getDailyOrderInfo($timeline_arr) {

        $data = array();
        for ($i = 0; $i < count($timeline_arr) - 1; ++$i) {
            $conditions =
            $Orders = $this->find("all", array(
                'recursive' => -1,
                'fields' =>  array('Order.order_no', 'Order.cashier_id', 'Order.table_no', 'Order.total', 'Order.paid', 'Order.cash_val', 'Order.card_val', 'Order.tax_amount', 'Order.default_tip_amount', 'Order.discount_value', 'Order.percent_discount','Order.paid_by', 'Order.tip','Order.tip_paid_by', 'Order.change'),
                'conditions' => array('Order.table_status' => 'P', 'Order.is_completed' => 'Y', 'Order.order_type <>' => 'L', 'Order.created >=' => date('c', $timeline_arr[$i]), 'Order.created <' => date('c', $timeline_arr[$i + 1]))

                ));

            $totalArr = array(
                'total' => 0,
                'cash_total' => 0,
                'card_total' => 0,
                'membercard_total' => 0,
            	'cash_mix_total' => 0,
                'card_mix_total' => 0,
                'paid_cash_total' => 0,
                'paid_card_total' => 0,
                'total_tip' => 0,
                'cash_tip_total' => 0,
                'card_tip_total' => 0,
                'default_tip_mix' => 0,
                'default_tip_card' => 0,
                'default_tip_cash' => 0,
                'tax' => 0,
                'real_total' => 0,
                'order_num' => sizeof($Orders),
                'start_time' => $timeline_arr[$i],
                'end_time' => $timeline_arr[$i + 1],
                );

            foreach ($Orders as $o) {
                $order = $o['Order'];
                $totalArr['paid_cash_total'] += ($order['cash_val'] - $order['change']);
                $totalArr['paid_card_total'] += $order['card_val'];
                $totalArr['paid_membercard_total'] += $order['membercard_val'];
                
                $totalArr['total'] += $order['total'];

				//目前的系统,没有现金小费,现金都是找零,小费是额外给服务员的,没有计入订单的.缺省小费和卡付小费计入订单.
				// CARD, CASH, MIXED and NO TIP
                if ($order['paid_by'] == 'CASH') { 
                	
                    $totalArr['cash_total'] += $order['total'];
                    
                    $totalArr['default_tip_cash'] += $order['default_tip_amount'];
                    
                } else if ($order['paid_by'] == 'CARD') { 
                	
                    $totalArr['card_total'] += $order['total'];
                    
                    $totalArr['card_tip_total'] += $order['tip'];
                    $totalArr['default_tip_card'] +=$order['default_tip_amount'];
                    
                } else if ($order['paid_by'] == 'MEMBERCARD') { 
                	
                    $totalArr['membercard_total'] += $order['total'];
                    
                    $totalArr['membercard_tip_total'] += $order['tip'];
                    $totalArr['default_tip_card'] +=$order['default_tip_amount'];
                    
                } else {
                	
                	//Paid by MIXED
                	//这种情况,现金多了都会找零,卡付时多余的是小费
                	//对于缺省小费：有分单则根据子单来拆分
                	//没有分单则缺省小费统一计入default_tip_mix
                	
                    $totalArr['card_mix_total'] += $order['card_val'];
                    
                    $totalArr['cash_mix_total'] += ($order['total'] - $order['card_val']);

            		$o_splits = $this->query("SELECT default_tip_amount, paid_card,paid_cash,tip_card,tip_cash FROM order_splits WHERE order_no = '".$order['order_no']. "'");

                    if(!empty($o_splits[0]['order_splits'])){
                        foreach($o_splits as $os){
                            $paid_membercard = $os['order_splits']['paid_membercard'];
                        	$paid_card = $os['order_splits']['paid_card'];
                            $paid_cash = $os['order_splits']['paid_cash'];
                            
                            if($paid_card>0 && $paid_cash>0){
                            	//mixed for this suborder
                            	$totalArr['default_tip_mix'] += $os['order_splits']['default_tip_amount'];
                            }else if($paid_card>0){  //by card 	
                    			$totalArr['card_tip_total'] += $order['tip'];
                    			
                            	$totalArr['default_tip_card'] += $os['order_splits']['default_tip_amount'];
                            }else{    //by cash
                            	$totalArr['default_tip_cash'] += $os['order_splits']['default_tip_amount'];
                            }
                        }
                    }else{ //mixed for this order
                    	$totalArr['card_tip_total'] += $order['tip'];
                        
                        $totalArr['default_tip_mix'] += $os['order_splits']['default_tip_amount'];
                    }                    
                }
                                
                $totalArr['total_tip'] += ($order['tip']+$order['default_tip_amount']);

                $totalArr['tax'] += $order['tax_amount'];
            }

            $totalArr['real_total'] = $totalArr['paid_cash_total'] + $totalArr['paid_card_total'];

            array_push($data, $totalArr);
        }
		
		//如果缺省小费不对,说明混合付款的时候,这个单同时用了现金和卡,请检查$totalArr['default_tip_mix']
        return $data;
    }

}

?>
