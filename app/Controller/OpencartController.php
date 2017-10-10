<?php
//use Cake\Event\Event;

App::uses('Component', 'Controller');
App::uses('ApiHelperComponent', 'Component');

class OpencartController extends AppController {
  
  private $oc_store_id = 0;
  private $oc_api_url  = 'http://127.0.0.1:8080/opencart/index.php?route=api';
  private $oc_api_key  = array(
       'key'=>'11RMhYxl3UZZql2ddEglH3TAraQo2fKJ7wuaWcGyJ5kF41WD8H7sZxJDy4CLZ0r7skcwQ14Q2M2EqjqE2sNyia7EN9yfjQWUYASuHo1r2MnfFPl6WtAV6gmkfEQ7pxgAkBgF8oWdz7o0NRSHpPYV7fMOiJcJsFWfeieBG1OWr5Z6ww7qiSw34EXIOlDfxvVaLb8b1j8pQH3ziuLFsC8o8UHZ1jh2E6gxWlEiYlMCsL6PlNIRP3GtHa65vNNUb97xno');
       
  public function beforeFilter() {
  	
    parent::beforeFilter();
    
    $db = ConnectionManager::getDataSource("default"); 
    $row  = $db->fetchAll("select oc_api_url, oc_api_key, oc_store_id from admins where is_super_admin = 'N' and status='A' limit 1");    

    $this->oc_store_id = $row[0]['admins']['oc_store_id'];  
    $this->oc_api_url  = $row[0]['admins']['oc_api_url'];  
    $key  = $row[0]['admins']['oc_api_key'];

    if($row[0]['admins']['oc_api_key'] == ''){
    	$key = 'RMhYxl3UZZql2ddEglH3TAraQo2fKJ7wuaWcGyJ5kF41WD8H7sZxJDy4CLZ0r7skcwQ14Q2M2EqjqE2sNyia7EN9yfjQWUYASuHo1r2MnfFPl6WtAV6gmkfEQ7pxgAkBgF8oWdz7o0NRSHpPYV7fMOiJcJsFWfeieBG1OWr5Z6ww7qiSw34EXIOlDfxvVaLb8b1j8pQH3ziuLFsC8o8UHZ1jh2E6gxWlEiYlMCsL6PlNIRP3GtHa65vNNUb97xno';
    }
    
    $this->oc_api_key = array('key' => $key);
    
  }

  public function setApi() {
  	
    $db = ConnectionManager::getDataSource("default"); 
    $row  = $db->fetchAll("select oc_api_url, oc_api_key, oc_store_id from admins where is_super_admin = 'N' and status='A' limit 1");    

    $this->oc_store_id = $row[0]['admins']['oc_store_id'];  
    $this->oc_api_url  = $row[0]['admins']['oc_api_url'];  
    $key  = $row[0]['admins']['oc_api_key'];

    if($row[0]['admins']['oc_api_key'] == ''){
    	$key = 'RMhYxl3UZZql2ddEglH3TAraQo2fKJ7wuaWcGyJ5kF41WD8H7sZxJDy4CLZ0r7skcwQ14Q2M2EqjqE2sNyia7EN9yfjQWUYASuHo1r2MnfFPl6WtAV6gmkfEQ7pxgAkBgF8oWdz7o0NRSHpPYV7fMOiJcJsFWfeieBG1OWr5Z6ww7qiSw34EXIOlDfxvVaLb8b1j8pQH3ziuLFsC8o8UHZ1jh2E6gxWlEiYlMCsL6PlNIRP3GtHa65vNNUb97xno';
    }
    
    $this->oc_api_key = array('key' => $key);  
  
  }
  
  
  //add local product,option etc.. to remote opencart site
  public function addProcuct() {
	$this->layout = false;
    $this->autoRender = NULL;

    //ApiHelperComponent::verifyRequiredParams($args, ['order_no','discountType','discountValue']);

    $db = ConnectionManager::getDataSource("default"); 

    //$sql_categorys = "select * from (SELECT category_id as category_id,IF(lang_code = 'en', 1, 2) AS language_id, name, '' AS description,NAME AS meta_title FROM	category_locales WHERE category_id IN (SELECT id	FROM categories	WHERE	STATUS = 'A')) as categorys";

    $sql_categorys = "select * from (SELECT category_id as category_id,IF(lang_code = 'en', 1, 2) AS language_id, name, '' AS description,name AS meta_title FROM	category_locales WHERE category_id IN (SELECT id	FROM categories	WHERE	STATUS = 'A')) as categorys";
    
    $sql_products = "select * from(SELECT a.parent_id as product_id,IF (a.lang_code = 'en', 1, 2) AS language_id, a.name, a.name AS meta_title, b.category_id as category_id, b.price,b.comb_num FROM cousine_locals a, cousines b WHERE a.parent_id = b.id and b.STATUS = 'A') as products";
		
    $sql_options = "(SELECT	id as option_id, '1' as language_id, name FROM	extrascategories WHERE	STATUS = 'A') union all (SELECT	id as option_id, '2' as language_id, name_zh FROM	extrascategories WHERE	STATUS = 'A') order by option_id,language_id";

    $sql_optionvalues = "(SELECT id as option_value_id, '1' as language_id, category_id as option_id, name FROM	extras WHERE	STATUS = 'A') union all (SELECT	id as option_id, '2' as language_id, category_id as option_id, name_zh FROM	extras WHERE	STATUS = 'A') order by option_value_id,language_id";

    $rows1  = $db->fetchAll($sql_categorys);
    $rows2  = $db->fetchAll($sql_products);
    $rows3  = $db->fetchAll($sql_options);
    $rows4  = $db->fetchAll($sql_optionvalues);
		
	foreach($rows1 as $row1){
		$categorys[] = $row1['categorys'];
	}
	foreach($rows2 as $rows2){
		$products[] = $rows2['products'];
	}
	foreach($rows3 as $row3){
		$options[] = $row3['0'];
	}
	foreach($rows4 as $row4){
		$optionvalues[] = $row4['0'];
	}

    $data = array(
        'categorys' => $categorys, 
        'products'  => $products, 
        'options'   => $options, 
        'optionvalues' => $optionvalues, 
    ); 
              
    unset($rows1,$row1,$rows2,$row2,$rows3,$row3,$rows4,$row4,$categorys,$products,$options,$optionvalues);
    
    ini_set('post_max_size',"32M");

    //use curl api call to add product
    list($ret, $response) = $this->makeCall("/product/addFromPos", $data);
    
    exit($response);
    
  }


  //add local orders&order_items to remote opencart site
  public function addOrders() {
	$this->layout = false;
    $this->autoRender = NULL;

    //ApiHelperComponent::verifyRequiredParams($args, ['order_no','discountType','discountValue']);

    $db = ConnectionManager::getDataSource("default"); 

	//取出上次的运行时间作为本次开始时间,没有的话就设为3天前
    $sql = "select id,oc_last_push_order_time from admins where is_super_admin !='Y'  limit 1";
    $r  = $db->query($sql); 
    $stime = $r[0]['admins']['oc_last_push_order_time'];
	if(!$stime || $stime==""){
		$stime = date('Y-m-d H:i:s',strtotime('-3 day'));
	}
	//截止时间为当前时间
	$etime = date('Y-m-d H:i:s'); 

    $sql_orders = "select * from orders where created>'$stime'";
    $sql_order_items  = "select * from order_items where order_id in (select id from orders where created>'$stime')";
		
    $rows1  = $db->fetchAll($sql_orders);
    $rows2  = $db->fetchAll($sql_order_items);
		
	foreach($rows1 as $row1){
		$orders[] = $row1['orders'];
	}
	foreach($rows2 as $rows2){
		$order_items[] = $rows2['order_items'];
	}

    $data = array(
        'store_id' => $this->oc_store_id, 
        'orders'   => $orders, 
        'order_items'   => $order_items, 
    ); 
              
    unset($rows1,$row1,$rows2,$row2,$orders,$order_items);
    
    ini_set('post_max_size',"64M");

    //use curl api call to add product
    list($ret, $response) = $this->makeCall("/order/addFromPos", $data);
    
    if($ret == 1){ //更新时间戳
    	$db->query("update admins set oc_last_push_order_time='$etime' where id ={$r[0]['admins']['oc_last_push_order_time']}");
    }
    exit($response);
    
  }

  //fetch online orders into local
  public function getOcOrders() {
		
	$this->layout = false;
    $this->autoRender = NULL;

    $this->loadModel('Order');
    $this->loadModel('OrderItem');
    $this->loadModel('Cousine');
    
    $db = ConnectionManager::getDataSource("default"); 

    $row  = $db->fetchAll("select id as restaurant_id, tax, no_of_online_tables from admins where is_super_admin = 'N' and status='A' limit 1");    

    $tax_rate      = $row[0]['admins']['tax'];           // 13
    $restaurant_id = $row[0]['admins']['restaurant_id'];
    $no_of_online_tables = $row[0]['admins']['no_of_online_tables'];
    
    ini_set('post_max_size',"32M");
    
    //begin api login to get token
    $data = array('store_id' => $this->oc_store_id);
    
    list($ret, $response) = $this->makeCall("/order/getOcOrders", $data);
    if($ret == 0){
    	$this->Session->setFlash($response, 'error');
    	return $this->redirect(array('controller' => 'homes', 'action' => 'dashboard'));
    	//exit($response);
    } 
      
    $orders = json_decode($response);
    $already_exists = 0;
    foreach($orders as $order){
    	
    	//已经取过该订单则跳过
    	$ret =$this->Order->hasAny(['order_no'=>'L'.$order->order_id]);
    	if($ret){
    		$already_exists++;
    		continue;
    	}	
    	    	
    	//取当前Online可用桌号
    	for($i=1; $i<=$no_of_online_tables; $i++){    		
    		$ret =$this->Order->hasAny(['order_type'=>'L','is_completed'=>'N','table_no'=>$i]);
    		if(!$ret){
    			$table_no = $i;
    			break;
    		}
    	}
    	
        $insert_order_data = array(
            'order_no'     => "L".$order->order_id,
            'cashier_id'   => $restaurant_id, // cashier should be restaurant_id
            'counter_id'   => 0,              // online订单,初始counter_id = 0;
            'table_no'     => $table_no,
            'table_status' => 'P',            // Paid and display green
            'tax' => $tax_rate,
            'tax_amount' => $order->order_tax,
            'default_tip_rate'   => 0,
            'default_tip_amount' => 0,
            'subtotal'   => $order->sub_total,
            'total'      => $order->total,
            'card_val'   => $order->total,
            'paid'       => $order->total,
            'paid_by'    => 'CARD',
            'phone' => $order->telephone,
            'is_completed' => 'N',
            'order_type' => 'L',
            'created' => date('Y-m-d H:i:s')
      );           
      
      $this->Order->create();
      $ret = $this->Order->save($insert_order_data, false);
      if (!empty($ret)) {

        //insert order_items
        $insert_orderitem_data = array();
        $order_id = $this->Order->getLastInsertId();
        foreach($order->order_products as $order_item){
        
        	$CousineDetail = $this->Cousine->getCousineInfo($order_item->product_id);
        	
        	$comb_id = $CousineDetail['Cousine']['comb_num'];
          
    	    $insert_orderitem_data[]['OrderItem'] = array(
              'order_id'    => $order_id,
              'item_id'     => $order_item->product_id,
              'name_en'     => $CousineDetail['Cousine']['en'],
              'name_xh'     => $CousineDetail['Cousine']['zh'],
              'price'       => $order_item->price,
              'category_id' => $order_item->category_id,
              'selected_extras' => json_encode($order->options), 
              'tax' => $tax_rate,
              'tax_amount'  => $order_item->tax,
              'qty'         => $order_item->quantity,
              'comb_id'     => $comb_id,
              'is_takeout'  => $order_item->is_takeout,
              'created'     => date('Y-m-d H:i:s'),
            );
        }
      }

      if(!empty($insert_orderitem_data)){
        $this->OrderItem->saveMany($insert_orderitem_data);
      }
      		
    }
    
    $count = count($orders);
    //exit("Complete, total orders: $count, already exists orders: $already_exists!");
    
    $this->Session->setFlash("Complete, get online orders: $count, already exists orders: $already_exists!", 'success');
    
    return $this->redirect(array('controller' => 'homes', 'action' => 'dashboard'));
        
  }


  public function closeOcOrders($order_no='') {
		
	$this->layout = false;
    $this->autoRender = NULL;

    ini_set('post_max_size',"32M");
    
    $data = array('order_no' => $order_no);
    
    //begin api login to get token
    list($ret, $response) = $this->makeCall("/order/completeOcOrders",$data);
    if($ret == 0) return $response;
  
      return $response;
    
  }


  private function makeCall($action,$data='') {

    //Firstly, login to get token
    $ch = curl_init ();
    curl_setopt ( $ch, CURLOPT_URL, $this->oc_api_url."/login" );
    curl_setopt ( $ch, CURLOPT_POST, 1 );
    curl_setopt ( $ch, CURLOPT_HEADER, 0 );
    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
    curl_setopt ( $ch, CURLOPT_POSTFIELDS, $this->oc_api_key );
    $response = json_decode(curl_exec ( $ch ));
    curl_close ( $ch );
    if(isset($response->token)) {
        $token = $response->token;
    } else {
        $msg = "Fail to get token!";
        if (isset($response->error)) {
            $msg = implode((array)$response->error, ";");
        }

        return array(0, $msg);
    }

    //use token to call api
    $url = $this->oc_api_url.$action.'/&token=' . $token ;
    $ch = curl_init( $url );

    curl_setopt ( $ch, CURLOPT_POST, 1 );
    curl_setopt ( $ch, CURLOPT_HEADER, 0 );
    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
    if( $data != ''){
      curl_setopt ( $ch, CURLOPT_POSTFIELDS, http_build_query($data));
    }
        
    $response = curl_exec ( $ch );
    curl_close( $ch );
    
    if(!$response){
    	return array(0, "Fail to call $action.");    	
    }else{
    	return array(1, $response);
    }
        
  }

  
  private function getToken($url='') {

    //begin api login to get token
    if($url==''){
    	$url= "http://127.0.0.1:8080/opencart/index.php?route=api/login";
    }else{
    	$url = $url."/login";
    }
    
    $keys = array(
       'key' => $this->key
    );
        
    $ch = curl_init ();
    curl_setopt ( $ch, CURLOPT_URL, $url );
    curl_setopt ( $ch, CURLOPT_POST, 1 );
    curl_setopt ( $ch, CURLOPT_HEADER, 0 );
    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
    curl_setopt ( $ch, CURLOPT_POSTFIELDS, $keys );
    $response = json_decode(curl_exec ( $ch ));
    curl_close ( $ch );
    if(isset($response->token)) {
      $token = $response->token;
      return array(1, $token);
    } else {
      return array(0, "Fail to get token!");
    }
    
  }

	
  public function setApikey($key='') {    
    if($key == ''){
    	$key = 'RMhYxl3UZZql2ddEglH3TAraQo2fKJ7wuaWcGyJ5kF41WD8H7sZxJDy4CLZ0r7skcwQ14Q2M2EqjqE2sNyia7EN9yfjQWUYASuHo1r2MnfFPl6WtAV6gmkfEQ7pxgAkBgF8oWdz7o0NRSHpPYV7fMOiJcJsFWfeieBG1OWr5Z6ww7qiSw34EXIOlDfxvVaLb8b1j8pQH3ziuLFsC8o8UHZ1jh2E6gxWlEiYlMCsL6PlNIRP3GtHa65vNNUb97xno';
    }
    
    $this->oc_api_key = array(
       'key' => $key
    );            
  }


}
