<?php
App::uses('Component', 'Controller');
App::uses('ApiHelperComponent', 'Component');
App::uses('PrintComponent', 'Controller/Component');
App::uses('PrintLib', 'Lib');

class OrderHandlerComponent extends Component {
    public $status = 'success';
    public $components = array('Paginator');

    public function __construct() {
        // register model
        $this->Admin = ClassRegistry::init('Admin');
        $this->Order = ClassRegistry::init('Order');
        $this->OrderLog  = ClassRegistry::init('OrderLog');
        $this->OrderItem = ClassRegistry::init('OrderItem');
        $this->Log       = ClassRegistry::init('Log');
        $this->Category  = ClassRegistry::init('Category');
        $this->Cashier   = ClassRegistry::init('Cashier');
        $this->Cousine   = ClassRegistry::init('Cousine');
        $this->Extra     = ClassRegistry::init('Extra');        
    }

    public function addItem($args) {
        ApiHelperComponent::verifyRequiredParams($args, ['item_id', 'table', 'type', 'cashier_id']);

        // get parameters
        $item_id = $args['item_id'];
        $table = $args['table'];
        $type = $args['type'];
        $cashier_id = $args['cashier_id'];

        $admin_detail = $this->Cashier->find("first", array(
            'fields' => array('Admin.tax', 'Admin.default_tip_rate', 'Admin.id'),
            'conditions' => array('Cashier.id' => $cashier_id)
                )
        );

        // print_r($admin_detail);

        $tax_rate = $admin_detail['Admin']['tax']; // 13
        if($type == 'D'){
        	$default_tip_rate = $admin_detail['Admin']['default_tip_rate']; 
        }else{
        	$default_tip_rate = 0;
        }
        
        $restaurant_id = $admin_detail['Admin']['id'];
        // print_r($tax_rate);
        // print_r($restaurant_id);

        $CousineDetail = $this->Cousine->getCousineInfo($item_id);

        $Order_detail = $this->Order->find("first", array(
            'fields' => array('Order.id', 'Order.subtotal', 'Order.total', 'Order.tax_amount', 'Order.discount_value', 'Order.promocode', 'Order.fix_discount', 'Order.percent_discount'),
            'conditions' => array('Order.cashier_id' => $restaurant_id, 'Order.table_no' => $table, 'Order.is_completed' => 'N', 'Order.order_type' => $type )
                )
        );

        // print_r($Order_detail);

        if (empty($Order_detail)) {
            // to create a new order
            $order_id = $this->Order->insertOrder($restaurant_id, $cashier_id, $table, $type, $tax_rate, $default_tip_rate);
            //return array('ret' => 0, 'message' => 'No order found!');
        } else {
            $order_id = $Order_detail['Order']['id'];
        }

        $query_str = "SELECT comb_num FROM cousines WHERE id = " . $item_id;
        $comb_num = $this->Cousine->query($query_str);
        $query_str = "SELECT extrascategories.* FROM `extrascategories` WHERE extrascategories.status = 'A'";
        $extras_categories = $this->Order->query($query_str);
        if ($comb_num[0]['cousines']['comb_num'] == 0) {
            $query_str = "SELECT extras.* FROM `extras` JOIN extrascategories ON extras.category_id = extrascategories.id WHERE extras.status = 'A' AND extrascategories.extras_num = 0 ";
        }else{
            $query_str = "SELECT extras.* FROM `extras` JOIN extrascategories ON extras.category_id = extrascategories.id WHERE extras.status = 'A' AND (extrascategories.extras_num = 0 " . " OR extrascategories.id = " . $comb_num[0]['cousines']['comb_num'] . ")";
        }


        if ($CousineDetail['Cousine']['is_tax'] == 'Y') {
            $tax_amount = $tax_rate * $CousineDetail['Cousine']['price'] / 100;
        } else {
            $tax_amount = 0;
        }


        $comb_id = $this->Cousine->find("first", array(
                'conditions' => array('Cousine.id' => $item_id)
            ))['Cousine']['comb_num'];

        $order_item_id = $this->OrderItem->insertOrderItem($order_id, $item_id, $CousineDetail['Cousine']['en'], $CousineDetail['Cousine']['zh'], $CousineDetail['Cousine']['price'], $CousineDetail['Cousine']['category_id'], /*!empty($extras) ? json_encode($extras) : "",*/ $tax_rate, $tax_amount, 1, $comb_id);


        $this->Order->updateBillInfo($order_id);

        $json['extra_categories'] = array();
        if (isset($CousineDetail['Cousine']['ExtraCategories']) && $CousineDetail['Cousine']['ExtraCategories']) {
        	foreach ($CousineDetail['Cousine']['ExtraCategories'] as $cate_id) {
        		$json['extra_categories'][] = $cate_id;
         	}
        }
        $json['order_item_id'] = $order_item_id;
        $json['comb_id']  = $comb_id;
        $json['comb_num'] = $comb_num[0]['cousines']['comb_num'];
        return json_encode($json);
    }


    public function removeItem($args) {
        ApiHelperComponent::verifyRequiredParams($args, ['item_id_list', 'order_no', 'cashier_id']);

        // get all params
        $item_id_list = $args['item_id_list'];
        $order_no = $args['order_no'];
        $cashier_id = $args['cashier_id'];
        $order_id = $this->Order->getOrderIdByOrderNo($order_no);
        $restaurant_id = $this->Cashier->getRestaurantId($cashier_id);
        
        $PrintC = new PrintComponent();

        //PrintComponent::printKitchenRemoveItem(array('restaurant_id'=> $restaurant_id, 'order_id'=>$order_id, 'item_id_list'=>$item_id_list));
        $PrintC->printKitchenRemoveItem(array('restaurant_id'=> $restaurant_id, 'order_id'=>$order_id, 'item_id_list'=>$item_id_list));


        foreach ($item_id_list as $item_id) {
            // delete all item in order_item table
            $data['id'] = $item_id;
            $this->OrderItem->delete($data);
        }

        // update order amount
        $this->Order->updateBillInfo($order_id);
        
        return array('ret' => 1, 'message' => 'Complete!');

    }

    public function changePrice($args) {
        ApiHelperComponent::verifyRequiredParams($args, ['item_id_list', 'table', 'type', 'order_no', 'price']);
        // get all params
        $item_id_list = $args['item_id_list'];
        $table = $args['table'];
        $type = $args['type'];
        $order_no = $args['order_no'];
        $price = $args['price'];

        foreach ($item_id_list as $item_id) {
            $itemDetail = $this->OrderItem->find('first',
                    array(
                        'conditions' => array(
                            'OrderItem.id' => $item_id
                            )
                        )
                );
                
            if(!$itemDetail){
               return array('ret' => 0, 'message' => "Error order_items id: $item_id !");
            }
                
            $itemDetail['OrderItem']['price'] = $price;

            // print_r($itemDetail);

            $this->OrderItem->save($itemDetail, false);
        }

        // recalculate price
        $order_id = $this->Order->getOrderIdByOrderNo($order_no);
        
        $this->Order->updateBillInfo($order_id);

        return array('ret' => 1, 'message' => 'Complete!');
      
    }
    

    public function changeQuantity($args) {
        ApiHelperComponent::verifyRequiredParams($args, ['item_id_list', 'table', 'type', 'order_no', 'quantity']);
        // get all params
        $item_id_list = $args['item_id_list'];
        $quantity = $args['quantity'];
        $table = $args['table'];
        $type = $args['type'];
        $order_no = $args['order_no'];

        foreach ($item_id_list as $item_id) {
            $itemDetail = $this->OrderItem->find('first',
                    array(
                        'conditions' => array(
                            'OrderItem.id' => $item_id
                            )
                        )
                );
            
            if(!$itemDetail){
               return array('ret' => 0, 'message' => "Error order_items id: $item_id !");
            }
            
            $itemDetail['OrderItem']['qty'] = $quantity;

            // print_r($itemDetail);

            $this->OrderItem->save($itemDetail, false);
        }

        // recalculate price
        $order_id = $this->Order->getOrderIdByOrderNo($order_no);
        $this->Order->updateBillInfo($order_id);
        
        return array('ret' => 1, 'message' => 'Complete!');

    }


    public function takeout($args) {
        ApiHelperComponent::verifyRequiredParams($args, ['item_id_list']);

        $item_id_list = $args['item_id_list'];
        
        // $order_no = $args['order_no'];

        foreach ($item_id_list as $item_id) {
            // if the item is printed
            // send to kitchen print
            $item_detail = $this->OrderItem->query("SELECT order_items.*,categories.printer FROM  `order_items` JOIN `categories` ON order_items.category_id=categories.id WHERE order_items.id = " . $item_id . " LIMIT 1");
            
            if(!$item_detail){
            	return array('ret' => 0, 'message' => 'id '.$item_id.' not exist!');
            } 
            
            /*
            // print_r($item_detail);
            $is_print = $item_detail[0]['order_items']['is_print'];
            $printer = $item_detail[0]['categories']['printer'];
            
            if ($is_print == 'Y') {
                if ($printer == 'K') {
                    // send to kitchen
                    echo $printer;
                } else if ($printer == 'C') {
                    // send to front
                    echo $printer;
                }
                echo $is_print;
            } // else do nothing
            */


            // set all item in order_item table as is_takeout 'Y'            
            if ($item_detail[0]['order_items']['is_takeout'] == 'Y') {
               //$update_para['is_takeout'] = 'N'; // revert is_takeout flag
            } else if ($item_detail[0]['order_items']['is_takeout'] == 'N') {
                $update_para['is_takeout'] = 'Y';
            }

            $update_para['id'] = $item_id;
            $this->OrderItem->save($update_para, false);
        }
        
        return array('ret' => 1, 'message' => 'Complete!');
    }


    public function batchAddExtras($args) {
        ApiHelperComponent::verifyRequiredParams($args, ['item_id_list', 'extra_id_list', 'table', 'type', 'special', 'cashier_id']);

        $selected_item_id_list = $args['item_id_list'];
        $selected_extras_id_list = $args['extra_id_list'];
        $table = $args['table'];
        $type = $args['type'];
        $special = $args['special'];
        $cashier_id = $args['cashier_id'];

        // get cashier details
        $cashier_detail = $this->Cashier->find("first", array(
            'fields' => array('Cashier.firstname', 'Cashier.lastname', 'Cashier.id', 'Cashier.image', 'Admin.id'),
            'conditions' => array('Cashier.id' => $cashier_id)
                )
        );

        $extras_amount = 0;

        $selected_extras_list = [];
        foreach ($selected_extras_id_list as $extra_id) {
        	
        	  if($extra_id == '') continue;
                  	
            $extra_details = $this->Extra->find("first", array(
                    "fields" => array('Extra.id', 'Extra.price', 'Extra.name_zh', 'Extra.category_id'),
                    'conditions' => array('Extra.id' => $extra_id)
                ));
            $temp_data = array(
                    'id' => $extra_details['Extra']['id'],
                    'price' => $extra_details['Extra']['price'],
                    'name' => $extra_details['Extra']['name_zh'],
                    'category_id' => $extra_details['Extra']['category_id']
                );
            array_push($selected_extras_list, $temp_data);
        }
        // echo json_encode($selected_extras_list);

        if(empty($selected_extras_list)){
	        return array('ret' => 1, 'message' => 'No extras selected!');
        }

        foreach ((array)$selected_item_id_list as $item_id) {
            $item_detail = $this->OrderItem->find("first", array(
                'fields' => array('OrderItem.id', 'OrderItem.extras_amount', 'OrderItem.selected_extras'),
                'conditions' => array('OrderItem.id' => $item_id)
                    )
            );

            if (empty($item_detail['OrderItem']['selected_extras'])) {
                $item_detail['OrderItem']['selected_extras'] = json_encode($selected_extras_list);
            } else {
                $item_detail['OrderItem']['selected_extras'] = json_decode($item_detail['OrderItem']['selected_extras'], true);
                $item_detail['OrderItem']['selected_extras'] = json_encode(array_merge($item_detail['OrderItem']['selected_extras'], $selected_extras_list));
            }

            if (!empty($special)) {
                $item_detail['OrderItem']['special_instruction'] = $special;
            }


            $this->OrderItem->save($item_detail, false);

            // update extra amount will also incur the updateBillInfo() function
            $this->OrderItem->updateExtraAmount($item_id);
        }
        
        return array('ret' => 1, 'message' => 'success');

    }


    public function addExtras($args) {
    	
        ApiHelperComponent::verifyRequiredParams($args, ['item_id', 'extra_id_list', 'table', 'type', 'special', 'cashier_id']);
         
        $item_id = $args['item_id'];
        
        $selected_extras_id_list = $args['extra_id_list'];
        $table = $args['table'];
        $type = $args['type'];
        $special = $args['special'];
        $cashier_id = $args['cashier_id'];

        //$this->Log->query("INSERT INTO logs(cashier_id,operation,logs) VALUES ('0',1,'{$selected_extras_id_list[0]}' )");

        // selected_extras_id_list maybe empty
        if(@$selected_extras_id_list[0]==''){
        	$this->OrderItem->query("UPDATE order_items set selected_extras = '' where id =$item_id ");           	
	        return array('ret' => 1, 'message' => 'No extras selected!');
	        exit;
        }

        
        // get cashier details
        $cashier_detail = $this->Cashier->find("first", array(
            'fields' => array('Cashier.firstname', 'Cashier.lastname', 'Cashier.id', 'Cashier.image', 'Admin.id'),
            'conditions' => array('Cashier.id' => $cashier_id)
                )
        );

        $extras_amount = 0;

        $selected_extras_list = [];
        foreach ((array)$selected_extras_id_list as $extra_id) {
        	          
           $extra_details = $this->Extra->find("first", array(
                   "fields" => array('Extra.id', 'Extra.price', 'Extra.name_zh', 'Extra.category_id'),
                   'conditions' => array('Extra.id' => $extra_id)
               ));
           $temp_data = array(
                   'id' => $extra_details['Extra']['id'],
                   'price' => $extra_details['Extra']['price'],
                   'name' => $extra_details['Extra']['name_zh'],
                   'category_id' => $extra_details['Extra']['category_id']
               );
           array_push($selected_extras_list, $temp_data);
        }
        // echo json_encode($selected_extras_list);

        
        $item_detail = $this->OrderItem->find("first", array(
            'recursive' => -1,
            'fields' => array('OrderItem.id', 'OrderItem.extras_amount', 'OrderItem.selected_extras'),
            'conditions' => array('OrderItem.id' => $item_id)
                )
        );

        $item_detail['OrderItem']['selected_extras'] = json_encode($selected_extras_list);
        $item_detail['OrderItem']['special_instruction'] = $special;

        $this->OrderItem->save($item_detail, false);

        // update extra amount will also incur the updateBillInfo() function
        $this->OrderItem->updateExtraAmount($item_id);
        
        return array('ret' => 1, 'message' => 'success');
    }

    public function tableHistory($args) {

        ApiHelperComponent::verifyRequiredParams($args, ['restaurant_id','table','type']);

        $restaurant_id = $args['restaurant_id'];
        $table         = $args['table'];
        $order_type    = $args['type'];

        $conditions = array('Order.cashier_id' => $restaurant_id,
            'Order.table_no' => $table,
            'Order.is_completed' => 'Y',
            'Order.order_type' => $order_type,
            'Order.created >=' => date("Ymd")/* , strtotime("-2 weeks")) */
        );

        $Order_detail = $this->Order->find("all", array(
            'fields' => array('Order.paid', 'Order.tip', 'Order.cash_val', 'Order.card_val', 'Order.change', 'Order.order_no', 'Order.tax', 'Order.table_status', 'Order.tax_amount', 'Order.subtotal', 'Order.total', 'Order.message', 'Order.discount_value', 'Order.promocode', 'Order.fix_discount', 'Order.percent_discount', 'Order.created'),
            'conditions' => $conditions
                )
        );
        
        if (empty($Order_detail)) {
        	  $json['ret'] = 0;
        	  $json['message'] = "Sorry, there is no table history for today.";
            return json_encode($json);
        }
/*
        $this->paginate = array(
            'fields' => array('Order.paid', 'Order.tip', 'Order.cash_val', 'Order.card_val', 'Order.change', 'Order.order_no', 'Order.tax', 'Order.table_status', 'Order.tax_amount', 'Order.subtotal', 'Order.total', 'Order.message', 'Order.discount_value', 'Order.promocode', 'Order.fix_discount', 'Order.percent_discount', 'Order.created'),
            'conditions' => $conditions,
            'limit' => 10,
            'order' => array('Order.created' => 'desc')
        );

        $Order_detail = $this->paginate('Order');
*/
        $today = date('Y-m-d H:i', strtotime($Order_detail[0]['Order']['created']));
        
        $json['ret'] = 1;
        $json['Order_detail'] = $Order_detail;
        $json['table_no'] = $table;
        $json['today'] = $today;
        return json_encode($json);
    }


    public function tableHisupdate($args) {
        $this->layout = false;
        $this->autoRender = NULL;

        ApiHelperComponent::verifyRequiredParams($args, ['order_id', 'subtotal', 'discount_value', 'total', 'paid', 'cash_val', 'card_val', 'change', 'tip']);

        $order_id = $args['order_id'];
        $conditions = array('Order.id' => $order_id);  
        
        $Order_detail = $this->Order->find("first", array(
            'fields' => array('Order.cashier_id','Order.paid', 'Order.tip', 'Order.cash_val', 'Order.card_val', 'Order.change', 'Order.order_no', 'Order.tax', 'Order.table_status', 'Order.tax_amount', 'Order.subtotal', 'Order.total', 'Order.message', 'Order.discount_value', 'Order.promocode', 'Order.fix_discount', 'Order.percent_discount', 'Order.created'),
            'conditions' => $conditions
                )
        );
        
        if (empty($Order_detail)) {
        	 return array('ret' => 0, 'message' => 'Error, order detail not found.'); 	 
        }

        $subtotal = $args['subtotal'];
        $discount_value = $args['discount_value'];
        $total = $args['total'];
        $paid = $args['paid'];
        $cash_val = $args['cash_val'];
        $card_val = $args['card_val'];
        $change = $args['change'];
        $tip = $args['tip'];

        $logs = '';
        $data = array();

        if ($cash_val > 0 and $card_val > 0)
            $data['paid_by'] = "MIXED";
        elseif ($card_val > 0)
            $data['paid_by'] = "CARD";
        else
            $data['paid_by'] = "CASH";
        
        if ($subtotal != number_format($Order_detail['Order']['subtotal'],2)) {
        	$logs .= 'subtotal[' . $subtotal . ' <= ' . $Order_detail['Order']['subtotal'] . "]";
        	$data['subtotal'] = $subtotal;
        	$data['tax_amount'] = $subtotal *  $Order_detail['Order']['tax'] / 100;
        }
        if ($discount_value != number_format($Order_detail['Order']['discount_value'],2)) {
        	$logs .= 'discount_value[' . $discount_value . ' <= ' . $Order_detail['Order']['discount_value'] . "]";
        	$data['discount_value'] = $discount_value;
        }
        if ($total != number_format($Order_detail['Order']['total'],2)) {
        	$logs .= 'total[' . $total . ' <= ' . $Order_detail['Order']['total'] . "]";
        	$data['total'] = $total;
        }
        if ($paid != number_format($Order_detail['Order']['paid'],2)) {
        	$logs .= 'paid[' . $paid . ' <= ' . $Order_detail['Order']['paid'] . "]";
        	$data['paid'] = $paid;
        }
        if ($cash_val != number_format($Order_detail['Order']['cash_val'],2)) {
        	$logs .= 'cash_val[' . $cash_val . ' <= ' . $Order_detail['Order']['cash_val'] . "]";
        	$data['cash_val'] = $cash_val;
        }
        if ($card_val != number_format($Order_detail['Order']['card_val'],2)) {
        	$logs .= 'card_val[' . $card_val . ' <= ' . $Order_detail['Order']['card_val'] . "]";
        	$data['card_val'] = $card_val;
        }
        if ($change != number_format($Order_detail['Order']['change'],2)) {
        	$logs .= 'change[' . $change . ' <= ' . $Order_detail['Order']['change'] . "]";
        	$data['change'] = $change;
        }
        if ($tip != number_format($Order_detail['Order']['tip'],2)) {
        	$logs .= 'tip[' . $tip . ' <= ' . $Order_detail['Order']['tip'] . "]";
        	$data['tip'] = $tip;
        }

        if ($logs != '') {
        	$logs = 'OrderNo: '.$Order_detail['Order']['order_no']."}". $logs ."}";
        	
        	$logArr = array('cashier_id' => $args['cashier_id'], 'admin_id' => $Order_detail['Order']['cashier_id'],'operation'=>'tableHisupdate', 'logs' => $logs);
        	$this->Log->save($logArr);
        	
        	$data['id'] = $order_id;
        	$ret = $this->Order->save($data);
        }else{
        	$ret = 1;
        }
        
        if ($ret)
        	return array('ret' => 1, 'message' => 'Success');
        else 
        	return array('ret' => 0, 'message' => 'Update error.');        
        
    }
    
        
    public function makeavailable($args) {

	  	ApiHelperComponent::verifyRequiredParams($args, ['order_no']);
	  	  	  	  
        $order_no = $args['order_no'];     
        
        $order_detail = $this->Order->find('first', array(
                 'recursive' => -1,
                 'conditions' => array(
                 'order_no' => $order_no
              )
        ));
                

        // update order
        try{
          $this->Order->updateAll(array('table_status'=>"'V'",'is_completed' => "'Y'"), array('Order.order_no' => $order_no));
        }catch(Exception $e){
        	return array('ret' => 0, 'message' => $e->getMessage() );
        }

        $logArr = array('cashier_id' => $args['cashier_id'], 'admin_id' => $order_detail['Order']['cashier_id'],'operation'=>'Void(makeavailable)', 'logs' => json_encode($order_detail));
        $this->Log->save($logArr);
        
        return array('ret' => 1, 'message' => 'Complete!');             
    }


	public function moveOrder($args) {
	  	
	  ApiHelperComponent::verifyRequiredParams($args, ['type', 'table', 'order_no']);
	  
	  $type  = $args['type'];
      $table = $args['table'];
      $order_no = $args['order_no'];
    
      $Order_detail = $this->Order->find("first", array(
          'fields' => array('Order.id,Order.cashier_id', 'Order.table_no', 'Order.order_type', 'Order.phone'),
          'conditions' => array('Order.order_no' => $order_no),
          'recursive' => -1
              )
      );        

      $restaurant_id = $Order_detail['Order']['cashier_id'];
      $old_type      = $Order_detail['Order']['order_type'];
      $old_table     = $Order_detail['Order']['table_no'];
      $phone         = $Order_detail['Order']['phone'];

      
      //print kitchen notification when change table(not from online table)
      if($old_type != 'L'){        	
        $printerName = $this->Admin->getKitchenPrinterName($restaurant_id);
        $print = new PrintLib();
        $print->printKitchenChangeTable($order_no, $table, $type, $old_table,$old_type, $printerName,true,$phone);
      }
	  
	  #think about default_tip when changing table
	  // update new table information to database		
 	  if($old_type == $type || substr($order_no,0,1) == 'L'){ 
 	  	$ret = $this->Order->updateAll( array('table_no' => $table, 'order_type' =>"'$type'") , array('Order.order_no' => $order_no));
 	  }else{
 	  	
 	    if($type == 'D'){
			$query = array(
			    'conditions' => array('is_super_admin' => 'N'),
			    'recursive' => -1, 
			    'fields' => array('default_tip_rate'),
			);

			$admin_detail = $this->Admin->find('first',$query);
			$default_tip_rate = $admin_detail['Admin']['default_tip_rate']; 
 	    	
 	  		$ret = $this->Order->updateAll( array('table_no' => $table, 'order_type' =>"'$type'", 'default_tip_rate' => $default_tip_rate) , array('Order.order_no' => $order_no));
 	  		
 	  	}else{
 	  		$ret = $this->Order->updateAll( array('table_no' => $table, 'order_type' =>"'$type'", 'default_tip_rate' => 0) , array('Order.order_no' => $order_no));
 	  	}
 	  	//这种情况需要更新小费信息
 	  	$this->Order->updateBillInfo($Order_detail['Order']['id']);
 	  }
	  
      
      if($ret) return array('ret' => 1, 'message' => 'Move successfully.');
      else return array('ret' => 0, 'message' => 'Fail to move order!');        
    	    
	}
    
    
    public function editPhone($args) {
        ApiHelperComponent::verifyRequiredParams($args, ['restaurant_id', 'order_no', 'phone']);
    
        $restaurant_id = $args['restaurant_id'];
        $order_no = $args['order_no'];
        $phone    = $args['phone'];
        
        $order_id = $this->Order->getOrderIdByOrderNo($order_no);
        if(!$order_id) return array('ret' => 0, 'message' => "Order $order_no not exist!");
        
        $this->Order->id = $order_id;
        $ret=$this->Order->saveField('phone', $phone);
       
        if($ret) return array('ret' => 1, 'message' => 'Add successfully.');
        else return array('ret' => 0, 'message' => 'Fail to update order!');        
    }

    public function tableRestore($args) {

	  	ApiHelperComponent::verifyRequiredParams($args, ['order_id']);

        $order_id  = $args['order_id'];

        $this->layout = false;
        $this->autoRender = NULL;

        $order_detail = $this->Order->find('first', array(
            'recursive' => -1,
            'conditions' => array('id' => $order_id)
        ));
    	
        $logs = '';
        $data = array();

       	$data['paid']     = '';
       	$data['paid_by']  = '';
       	$data['cash_val'] = '';
       	$data['card_val'] = '';
       	$data['change']   = '';
       	$data['tip'] = '';
       	$data['tip'] = '';
       	$data['tip_paid_by'] = '';
       	$data['table_status']= 'N';
       	$data['is_completed']= 'N';

        $logArr = array('cashier_id' => $args['cashier_id'], 'admin_id' => $order_detail['Order']['cashier_id'],'operation'=>'Table restore', 'logs' => json_encode($order_detail));
        
        $this->Log->save($logArr);
        
        $data['id'] = $order_id;
        $ret = $this->Order->save($data);

        if ($ret)
        	return array('ret' => 1, 'url' => Router::url(array('controller' => 'homes', 'action' => 'dashboard')), 'message' => 'Success');
        else 
        	return array('ret' => 0, 'message' => 'Error.');
        
        //echo json_encode($r);
    }


}

?>
