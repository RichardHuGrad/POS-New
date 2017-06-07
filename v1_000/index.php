<?php
require_once '../include/DbHandler.php';
require '../lib/Slim/Slim.php';
\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
$user_id = NULL;
$zone_id = NULL;

error_reporting(E_ALL);
//error_reporting(0);
ini_set('display_errors', '1');

/**
* Cashier Login
* url - /login
* method - POST
* params - username(mandatory), password(mandatory), devicetoken(mandatory), deviceid(mandatory)
**/
$app->post('/login', function() use ($app) {
    verifyRequiredParams(array('username', 'password', 'deviceid', 'devicetoken'));
    $username = $app->request()->post('username');
    $password = $app->request()->post('password');
    $devicetoken = $app->request()->post('devicetoken');
    $deviceid = $app->request()->post('deviceid');

    $response = array();
    $db = new DbHandler();
    $res = $db->cashierLogin($username, $password, $devicetoken, $deviceid);
    if ($res == 'ACCOUNT_DEACTVATED') {
        $response['code'] = 1;
        $response['error'] = true;
        $response['message'] = "Account Deactivated";
        echoRespnse(200, $response);
    } else if ($res == 'INVALID_USERNAME_PASSWORD') {
        $response['code'] = 2;
        $response['error'] = true;
        $response['message'] = "Invalid Urername OR Password";
        echoRespnse(200, $response);
    } else if ($res == 'INVALID_USERNAME') {
        $response['code'] = 3;
        $response['error'] = true;
        $response['message'] = "Username is not registered with us";
        echoRespnse(200, $response);
    } else {
        $response['code'] = 0;
        $response["error"] = false;
        $response['message'] = 'Login user data';
        $response['data']['id'] = $res['id'];
        $response['data']['firstname'] = $res['firstname'];
        $response['data']['lastname'] = $res['lastname'];
        $response['data']['mobile_no'] = $res['mobile_no'];
        $response['data']['email'] = $res['email'];
        $response['data']['password'] = $res['password'];
        $response['data']['profilepic'] = $res['image'];

        $response['data']['restaurant_name'] = $res['restaurant_name'];
        $response['data']['street'] = $res['street'];
        $response['data']['city'] = $res['city'];
        $response['data']['province'] = $res['province'];
        $response['data']['tax'] = $res['tax'];
        $response['data']['no_of_tables'] = $res['no_of_tables'];
        $response['data']['no_of_takeout_tables'] = $res['no_of_takeout_tables'];
        $response['data']['no_of_waiting_tables'] = $res['no_of_waiting_tables'];
        $response['data']['table_size'] = $res['table_size'];

        $table_order=str_replace('"', '',str_replace(']', '', str_replace('[', '', $res['table_order'])));
        $table_orderArrOutput=array();

		$table_orderArr=explode(',', $table_order);
		for($i=0; $i<count($table_orderArr); $i++) {
			if (preg_match('/left: (.*?)%/', $table_orderArr[$i], $left) === 1) {
			    $left[1];
			}
			if (preg_match('/top: (.*?)%/', $table_orderArr[$i], $top) === 1) {
			    $top[1];
			}
			$table_orderArrOutput[$i]['left']=$left[1];
			$table_orderArrOutput[$i]['top']=$top[1];
		}

        $response['data']['table_order'] = $table_orderArrOutput;
        $response['data']['takeout_table_size'] = $res['takeout_table_size'];
        $response['data']['waiting_table_size'] = $res['waiting_table_size'];

        $response['data']['printer_ip'] = $res['printer_ip'];
        $response['data']['kitchen_printer_device'] = $res['kitchen_printer_device'];
        $response['data']['service_printer_device'] = $res['service_printer_device'];
        echoRespnse(201, $response);
    }
});

/**
* Cashier Logout
* url - /logout
* method - GET
* header Params - username(mandatory), password(mandatory) 
**/
$app->get('/logout', 'authenticate', function() use ($app) {
    global $user_id;
    $db = new DbHandler();
    $response = array();
    $user = $db->cashierLogout($user_id);
    if ($user == 'UNABLE_TO_PROCEED') {
        $response['code'] = 1;
        $response['error'] = true;
        $response['message'] = "Unable to proceed your request";
        echoRespnse(200, $response);
    } else {
        $response['code'] = 0;
        $response["error"] = false;
        $response["message"] = "User Logout successfully";
        echoRespnse(201, $response);
    }
});

/**
* Cashier Details (Authenticated)
* url - /cashierdetails/:cashierid
* method - GET
* header Params - username(mandatory), password(mandatory)
**/
$app->get('/cashierdetails/:cashierid', 'authenticate', function($cashierid) use ($app) {
    global $user_id;
    $response = array();
    $db = new DbHandler();
    $res = $db->getUserById($cashierid);
    if (!$res) {
        $response['code'] = 1;
        $response['error'] = true;
        $response['message'] = "Invalid Cashier ID";
        echoRespnse(200, $response);
    } else {
        $response['code'] = 0;
        $response["error"] = false;
        $response['message'] = 'User Profile data';
        $response['data']['id'] = $res['id'];
        $response['data']['firstname'] = $res['firstname'];
        $response['data']['lastname'] = $res['lastname'];
        $response['data']['mobile_no'] = $res['mobile_no'];
        $response['data']['email'] = $res['email'];
        $response['data']['password'] = $res['password'];
        $response['data']['profilepic'] = $res['image'];

        $response['data']['restaurant_name'] = $res['restaurant_name'];
        $response['data']['street'] = $res['street'];
        $response['data']['city'] = $res['city'];
        $response['data']['province'] = $res['province'];
        $response['data']['tax'] = $res['tax'];
        $response['data']['no_of_tables'] = $res['no_of_tables'];
        $response['data']['no_of_takeout_tables'] = $res['no_of_takeout_tables'];
        $response['data']['no_of_waiting_tables'] = $res['no_of_waiting_tables'];
        $response['data']['table_size'] = $res['table_size'];
        $response['data']['table_order'] = $res['table_order'];
        $response['data']['takeout_table_size'] = $res['takeout_table_size'];
        $response['data']['waiting_table_size'] = $res['waiting_table_size'];
        echoRespnse(201, $response);
    }
});

/**
* Table list assigned to cashier
* url - /cashiertables
* method - GET
* header Params - username(mandatory), password(mandatory)
**/
$app->get('/cashiertables', 'authenticate', function() use ($app) {
    global $user_id;     
    $response = array();
    $db = new DbHandler();
    $res = $db->cashierTables($user_id);   
    if ($res=='UNABLE_TO_PROCEED') {
	    $response['code'] = 1;
        $response['error'] = true;
        $response['message'] = "Unable to proceed";
	    echoRespnse(200, $response);
    } else {
	    $response['code'] = 0;
        $response['error'] = false;
        $response['message'] = "Table list"; 
	    $response['data'] = $res;
	    echoRespnse(201, $response);
    }
});

/**
* All pending orders
* url - /pendingorders/:type/:tableno
* type :- T / W , T-> Takeout, W -> Waiting
* tableno :- default 0 when type= W / T
* method - GET
* header Params - username(mandatory), password(mandatory)
**/
$app->get('/pendingorders/:type/:tableno', 'authenticate', function($type, $tableno) use ($app) {  
    global $user_id;       
    $response = array();
    $db = new DbHandler();
    $res = $db->pendingOrders($user_id, $type, $tableno);   
    if ($res=='NO_RECORD_FOUND') { 
        $response['code'] = 1;
        $response['error'] = true; 
        $response['message'] = "No Record found"; 
        echoRespnse(200, $response);
    } else if ($res=='UNABLE_TO_PROCEED') {
        $response['code'] = 2;
        $response['error'] = true;
        $response['message'] = "Unable to proceed";
        echoRespnse(200, $response);
    } else {
        $response['code'] = 0;
        $response['error'] = false;
        $response['message'] = "Pending Orders list"; 
        $response['data'] = $res;
        echoRespnse(201, $response);
    }
});

/**
* Get all items with category
* url - /items/:categoryid
* categoryid :- default 0  , If O then all items and categories will return
* method - GET
* header Params - username(mandatory), password(mandatory)
**/
$app->get('/items/:categoryid', 'authenticate', function($categoryid) use ($app) {  
    global $user_id;       
    $response = array();
    $db = new DbHandler();
    $res = $db->items($user_id, $categoryid);   
    if ($res=='NO_RECORD_FOUND') { 
        $response['code'] = 1;
        $response['error'] = true; 
        $response['message'] = "No Record found"; 
        echoRespnse(200, $response);
    } else if ($res=='UNABLE_TO_PROCEED') {
        $response['code'] = 2;
        $response['error'] = true;
        $response['message'] = "Unable to proceed";
        echoRespnse(200, $response);
    } else {
        $response['code'] = 0;
        $response['error'] = false;
        $response['message'] = "Items list"; 
        $response['data'] = $res;
        echoRespnse(201, $response);
    }
});

/**
* Get extras list with type
* url - /extras/:type
* method - GET
* header Params - username(mandatory), password(mandatory)
**/
$app->get('/extras/:type', 'authenticate', function($type) use ($app) {  
    global $user_id;       
    $response = array();
    $db = new DbHandler();
    if ($type=='P') {
    	$type='E';
    }
    $res = $db->extras($user_id, $type);   
    if ($res=='NO_RECORD_FOUND') { 
        $response['code'] = 1;
        $response['error'] = true; 
        $response['message'] = "No Record found"; 
        echoRespnse(200, $response);
    } else if ($res=='UNABLE_TO_PROCEED') {
        $response['code'] = 2;
        $response['error'] = true;
        $response['message'] = "Unable to proceed";
        echoRespnse(200, $response);
    } else {
        $response['code'] = 0;
        $response['error'] = false;
        $response['message'] = "Extras list"; 
        $response['data'] = $res;
        echoRespnse(201, $response);
    }
});

/**
* Make a reservation
* url - /reservation 
* method - POST
* params - name(mandatory), noofperson(mandatory), phoneno(mandatory), date(mandatory), time(mandatory), required(optional)
* header Params - email (mandatory), password (mandatory)
**/
$app->post('/reservation', 'authenticate', function() use ($app) {
    global $user_id;
    // check for required params
    verifyRequiredParams(array('name', 'noofperson', 'phoneno', 'date', 'time'));
    $response = array();
    // reading post params
    $name = $app->request->post('name');
    $noofperson = $app->request->post('noofperson');
    $phoneno = $app->request->post('phoneno');
    $date = $app->request->post('date');
    $time = $app->request->post('time');
    $required = $app->request->post('required');
    
    $db = new DbHandler();
    $res = $db->reservation($user_id, $name, $noofperson, $phoneno, $date, $time, $required);
    if ($res == 'UNABLE_TO_PROCEED') {
        $response["code"] = 1;
        $response["error"] = true;
        $response["message"] = "Unable to proceed";
        echoRespnse(200, $response);
    } else {
        $response["code"] = 0;
        $response["error"] = false;
        $response["message"] = "Request successfully Saved";
        $response["data"] = $res;
        echoRespnse(201, $response);
    }
});

/**
* Cancel reservation
* url - /cancelreservation 
* method - POST
* params - reservationid(mandatory), reason(optional)
* header Params - email (mandatory), password (mandatory)
**/
$app->post('/cancelreservation', 'authenticate', function() use ($app) {
    global $user_id;
    // check for required params
    verifyRequiredParams(array('reservationid'));
    $response = array();
    // reading post params
    $reservationid = $app->request->post('reservationid');
    $reason = $app->request->post('reason');
    
    $db = new DbHandler();
    $res = $db->cancelReservation($user_id, $reservationid, $reason);
    if ($res == 'UNABLE_TO_PROCEED') {
        $response["code"] = 1;
        $response["error"] = true;
        $response["message"] = "Unable to proceed";
        echoRespnse(200, $response);
    } else {
        $response["code"] = 0;
        $response["error"] = false;
        $response["message"] = "Request successfully Updated";
        echoRespnse(201, $response);
    }
});

/**
* Get all reservation
* url - /reservations/:type
* type :- P -> Pending, A -> Assigned, C-> Cancelled, If all needed then use 'O'
* method - GET
* header Params - username(mandatory), password(mandatory)
**/
$app->get('/reservations/:type', 'authenticate', function($type) use ($app) {  
    global $user_id;       
    $response = array();
    $db = new DbHandler();
    $res = $db->reservations($user_id, $type);   
    if ($res=='NO_RECORD_FOUND') { 
        $response['code'] = 1;
        $response['error'] = true; 
        $response['message'] = "No Record found"; 
        echoRespnse(200, $response);
    } else if ($res=='UNABLE_TO_PROCEED') {
        $response['code'] = 2;
        $response['error'] = true;
        $response['message'] = "Unable to proceed";
        echoRespnse(200, $response);
    } else {
        $response['code'] = 0;
        $response['error'] = false;
        $response['message'] = "Reseravtion list"; 
        $response['data'] = $res;
        echoRespnse(201, $response);
    }
});

/**
* Update reservation
* url - /updatereservation 
* method - POST
* params - reservationid(mandatory), name(mandatory), noofperson(mandatory), phoneno(mandatory), date(mandatory), time(mandatory), required(optional)
* header Params - email (mandatory), password (mandatory)
**/
$app->post('/updatereservation', 'authenticate', function() use ($app) {
    global $user_id;
    // check for required params
    verifyRequiredParams(array('reservationid', 'name', 'noofperson', 'phoneno', 'date', 'time'));
    $response = array();
    // reading post params
    $reservationid = $app->request->post('reservationid');
    $name = $app->request->post('name');
    $noofperson = $app->request->post('noofperson');
    $phoneno = $app->request->post('phoneno');
    $date = $app->request->post('date');
    $time = $app->request->post('time');
    $required = $app->request->post('required');
    
    $db = new DbHandler();
    $res = $db->updateReservation($user_id, $reservationid, $name, $noofperson, $phoneno, $date, $time, $required);
    if ($res == 'UNABLE_TO_PROCEED') {
        $response["code"] = 1;
        $response["error"] = true;
        $response["message"] = "Unable to proceed";
        echoRespnse(200, $response);
    } else {
        $response["code"] = 0;
        $response["error"] = false;
        $response["message"] = "Request successfully Updated";
        echoRespnse(201, $response);
    }
});

/**
* Assign a table against reservation
* url - /reservetable 
* method - POST
* params - reservationid(mandatory), tableno(mandatory)
* header Params - email (mandatory), password (mandatory)
**/
$app->post('/reservetable', 'authenticate', function() use ($app) {
    global $user_id;
    // check for required params
    verifyRequiredParams(array('reservationid', 'tableno'));
    $response = array();
    // reading post params
    $reservationid = $app->request->post('reservationid');
    $tableno = $app->request->post('tableno');
   
    $db = new DbHandler();
    $res = $db->reserveTable($user_id, $reservationid, $tableno);
    if ($res == 'UNABLE_TO_PROCEED') {
        $response["code"] = 1;
        $response["error"] = true;
        $response["message"] = "Unable to proceed";
        echoRespnse(200, $response);
    } else if ($res == 'TABLE_ALREADY_OCCUPIED') {
        $response["code"] = 2;
        $response["error"] = true;
        $response["message"] = "Table already occupied";
        echoRespnse(200, $response);
    } else if ($res == 'ALREADY_CANCELLED') {
        $response["code"] = 3;
        $response["error"] = true;
        $response["message"] = "Reservation already cancelled";
        echoRespnse(200, $response);
    } else if ($res == 'INVALID_RESERVATION') {
        $response["code"] = 4;
        $response["error"] = true;
        $response["message"] = "Reservation not found";
        echoRespnse(200, $response);
    } else if ($res == 'ALREADY_ASSIGNED') {
        $response["code"] = 5;
        $response["error"] = true;
        $response["message"] = "Table already assigned";
        echoRespnse(200, $response);
    } else {
        $response["code"] = 0;
        $response["error"] = false;
        $response["message"] = "Request successfully Updated";
        echoRespnse(201, $response);
    }
});

/**
* Make Order For Dinein
* url - /makeorderdinein 
* method - POST
* params - type(mandatory), itemdata(mandatory), tableno(optional)
* type :- D-> Dinin
* itemdata = [{ "itemid": "10",  "qty": "1", "allextras": 
[{ "id": "56", "name": "extra egg", "name_zh": "extra egg", "price": "2" }, {
        "id": "57", "name": "extra cha-shu", "name_zh": "extra cha-shu", "price": "3.5" }],
    "selectedextras": []
}]
* header Params - email (mandatory), password (mandatory)
**/
$app->post('/makeorderdinein', 'authenticate', function() use ($app) {
    global $user_id;
    // check for required params
    verifyRequiredParams(array('type', 'itemdata', 'tableno'));
    $type = $app->request->post('type');
    $response = array();
    // reading post params
    
    $itemdata = $app->request->post('itemdata');
    $tableno = $app->request->post('tableno');
    $db = new DbHandler();
    $res = $db->makeOrderDineIn($user_id, $type, $itemdata, $tableno);
    if ($res == 'INVALID_ORDER_DATA') {
        $response["code"] = 1;
        $response["error"] = true;
        $response["message"] = "Inavlid Data";
        echoRespnse(200, $response);
    } else if ($res == 'UNABLE_TO_PROCEED') {
        $response["code"] = 2;
        $response["error"] = true;
        $response["message"] = "Unable to proceed";
        echoRespnse(200, $response);
    } else if ($res == 'TABLE_ALREADY_OCCUPIED') {
        $response["code"] = 3;
        $response["error"] = true;
        $response["message"] = "Table is already occupied";
        echoRespnse(200, $response);
    } else {
        $response["code"] = 0;
        $response["error"] = false;
        $response["message"] = "Order successfully Saved";
        $response['order_no'] = $res['order_no'];
        $response['order_id'] = $res['order_id'];
        echoRespnse(201, $response);
    }
});

/**
* Make Order
* url - /makeorderother
* method - POST
* params - type(mandatory), name(mandatory), noofperson(mandatory), phoneno(mandatory)
* type :- T / W : Takeout / Waiting
* when type = T
* takeout_date(mandatory), takeout_time(mandatory)
**/
$app->post('/makeorderother', 'authenticate', function() use ($app) {
    global $user_id;
    // check for required params
    verifyRequiredParams(array('type', 'name', 'noofperson', 'phoneno'));
    $type = $app->request->post('type');
    if ($type=='T') {
        verifyRequiredParams(array('takeout_date', 'takeout_time'));
    }
    $response = array();
    // reading post params
    $type = $app->request->post('type');
    $name = $app->request->post('name');
    $noofperson = $app->request->post('noofperson');
    $phoneno = $app->request->post('phoneno');
    $takeout_date = $app->request->post('takeout_date');
    $takeout_time = $app->request->post('takeout_time');
    
    $db = new DbHandler();
    $res = $db->makeOrderOther($user_id, $type, $name, $noofperson, $phoneno, $takeout_date, $takeout_time);
    if ($res == 'UNABLE_TO_PROCEED') {
        $response["code"] = 2;
        $response["error"] = true;
        $response["message"] = "Unable to proceed";
        echoRespnse(200, $response);
    } else {
        $response["code"] = 0;
        $response["error"] = false;
        $response["message"] = "Order successfully Created";
        $response['order_no'] = $res['order_no'];
        $response['order_id'] = $res['order_id'];
        echoRespnse(201, $response);
    }
});

/**
* Add item in order
* url - /addorderitem 
* method - POST
* params - itemdata(mandatory), orderid(mandatory)
* itemdata = [{ "itemid": "68",  "qty": "20", "allextras": 
[{ "id": "56", "name": "extra egg", "name_zh": "extra egg", "price": "2" }, {
        "id": "57", "name": "extra cha-shu", "name_zh": "extra cha-shu", "price": "3.5" }],
    "selectedextras": [{"id": "56", "price": "2", "name": "extra egg", "name_zh": "extra egg" }, {
        "id": "57", "price": "3.5", "name": "extra cha-shu", "name_zh": "extra cha-shu"}]
}]
* header Params - email (mandatory), password (mandatory)
**/
$app->post('/addorderitem', 'authenticate', function() use ($app) {
    global $user_id;
    // check for required params
    verifyRequiredParams(array('itemdata', 'orderid'));
    $response = array();
    // reading post params
    
    $itemdata = $app->request->post('itemdata');
    $orderid = $app->request->post('orderid');
    
    $db = new DbHandler();
    $res = $db->addOrderItem($user_id, $itemdata, $orderid);
    if ($res == 'INVALID_ORDERID') {
        $response["code"] = 1;
        $response["error"] = true;
        $response["message"] = "Inavlid Order id";
        echoRespnse(200, $response);
    } else if ($res == 'UNABLE_TO_PROCEED') {
        $response["code"] = 2;
        $response["error"] = true;
        $response["message"] = "Unable to proceed";
        echoRespnse(200, $response);
    } else if ($res == 'ALREADY_COMPLETED') {
        $response["code"] = 3;
        $response["error"] = true;
        $response["message"] = "Order already completed";
        echoRespnse(200, $response);
    } else {
        $response["code"] = 0;
        $response["error"] = false;
        $response["message"] = "Order successfully Saved";
        $response["data"] = $res;
        echoRespnse(201, $response);
    }
});

/**
* Remove item from order
* url - /removeorderitem 
* method - POST
* params - itemdata(mandatory), orderid(mandatory)
* itemdata = [{ "itemid": "68", "rowid":"153"}]
* header Params - email (mandatory), password (mandatory)
**/
$app->post('/removeorderitem', 'authenticate', function() use ($app) {
    global $user_id;
    // check for required params
    verifyRequiredParams(array('itemdata', 'orderid'));
    $response = array();
    // reading post params
    $itemdata = $app->request->post('itemdata');
    $orderid = $app->request->post('orderid');
    
    $db = new DbHandler();
    $res = $db->removeOrderItem($user_id, $itemdata, $orderid);
    if ($res == 'INVALID_ORDER_DATA') {
        $response["code"] = 1;
        $response["error"] = true;
        $response["message"] = "Inavlid Data";
        echoRespnse(200, $response);
    } else if ($res == 'UNABLE_TO_PROCEED') {
        $response["code"] = 2;
        $response["error"] = true;
        $response["message"] = "Unable to proceed";
        echoRespnse(200, $response);
    } else if ($res == 'TABLE_ALREADY_OCCUPIED') {
        $response["code"] = 3;
        $response["error"] = true;
        $response["message"] = "Table is already occupied";
        echoRespnse(200, $response);
    } else {
        $response["code"] = 0;
        $response["error"] = false;
        $response["message"] = "Order successfully Updated";
        $response["data"] = $res;
        echoRespnse(201, $response);
    }
});

/**
* Change Table
* url - /changetable 
* method - POST
* params - tableno(mandatory), newtableno(mandatory), orderid(mandatory)
* header Params - email (mandatory), password (mandatory)
**/
$app->post('/changetable', 'authenticate', function() use ($app) {
    global $user_id;
    // check for required params
    verifyRequiredParams(array('orderid', 'tableno', 'newtableno'));
    $response = array();
    // reading post params
    $orderid = $app->request->post('orderid');
    $tableno = $app->request->post('tableno');
    $newtableno = $app->request->post('newtableno');
    
    $db = new DbHandler();
    $res = $db->changeTable($user_id, $tableno, $newtableno, $orderid);
    if ($res == 'UNABLE_TO_PROCEED') {
        $response["code"] = 1;
        $response["error"] = true;
        $response["message"] = "Unable to proceed";
        echoRespnse(200, $response);
    } else if ($res == 'TABLE_ALREADY_OCCUPIED') {
        $response["code"] = 2;
        $response["error"] = true;
        $response["message"] = "Table is already occupied";
        echoRespnse(200, $response);
    } else {
        $response["code"] = 0;
        $response["error"] = false;
        $response["message"] = "Table no successfully updated";
        $response["data"] = $res;
        echoRespnse(201, $response);
    }
});

/**
* Apply Discount
* url - /applydiscount
* params - type(mandatory), value(mandatory), orderid(mandatory)
* type :- P / F / PR , P -> Percent, F -> Fixed, PR -> Promocode
* If type == PR then value = promocide
* If type == P then value = percent to be apply
* If type == F then value = value to be discount
* method - POST
* header Params - username(mandatory), password(mandatory)
**/
$app->post('/applydiscount', 'authenticate', function() use ($app) {
    global $user_id;
    // check for required params
    verifyRequiredParams(array('type', 'value', 'orderid'));
    $response = array();
    // reading post params
    $type = $app->request->post('type');
    $value = $app->request->post('value');
    $orderid = $app->request->post('orderid');
    
    $db = new DbHandler();
    $res = $db->applyDiscount($user_id, $type, $value, $orderid);
    if ($res == 'UNABLE_TO_PROCEED') {
        $response["code"] = 1;
        $response["error"] = true;
        $response["message"] = "Unable to proceed";
        echoRespnse(200, $response);
    } else if ($res == 'INVALID_PROMOCODE' || $res=='PROMO_NOT_STARTED' || $res=='NOT_VALID_FOR_THIS_RESTAURANT') {
        $response["code"] = 2;
        $response["error"] = true;
        $response["message"] = "Please apply valid promocode";
        echoRespnse(200, $response);
    } else if ($res == 'PROMO_EXPIRED_DATE' || $res == 'PROMO_EXPIRED_TIME') {
        $response["code"] = 3;
        $response["error"] = true;
        $response["message"] = "Promocode expired";
        echoRespnse(200, $response);
    } else if ($res == 'DAY_NOT_EXIST') {
        $response["code"] = 4;
        $response["error"] = true;
        $response["message"] = "This offer is not valid for today";
        echoRespnse(200, $response);
    } else {
        $response["code"] = 0;
        $response["error"] = false;
        $response["message"] = "Table no successfully updated";
        $response["data"] = $res;
        echoRespnse(201, $response);
    }
});

/**
* Remove Discount
* url - /removediscount
* params -  orderid(mandatory)
* method - POST
* header Params - username(mandatory), password(mandatory)
**/
$app->post('/removediscount', 'authenticate', function() use ($app) {
    global $user_id;
    // check for required params
    verifyRequiredParams(array('orderid'));
    $response = array();
    // reading post params
     $orderid = $app->request->post('orderid');
    
    $db = new DbHandler();
    $res = $db->removePromocode($orderid);
    if ($res == 'INVALID_PROMOCODE') {
        $response["code"] = 1;
        $response["error"] = true;
        $response["message"] = "Unable to proceed";
        echoRespnse(200, $response);
    } else {
        $response["code"] = 0;
        $response["error"] = false;
        $response["message"] = "Discount successfully removed";
        //$response["data"] = $res;
        echoRespnse(201, $response);
    }
});

/**
* Get order selected extras with item
* url - /orderitemextras/:orderid/:rowid
* method - GET
* header Params - username(mandatory), password(mandatory)
**/
$app->get('/orderitemextras/:orderid/:rowid', 'authenticate', function($orderid, $rowid) use ($app) {  
    global $user_id;       
    $response = array();
    $db = new DbHandler();

    $res = $db->orderItemExtras($user_id, $orderid, $rowid);   
    if ($res=='NO_RECORD_FOUND') { 
        $response['code'] = 1;
        $response['error'] = true; 
        $response['message'] = "No Record found"; 
        echoRespnse(200, $response);
    } else if ($res=='UNABLE_TO_PROCEED') {
        $response['code'] = 2;
        $response['error'] = true;
        $response['message'] = "Unable to proceed";
        echoRespnse(200, $response);
    } else {
        $response['code'] = 0;
        $response['error'] = false;
        $response['message'] = "Extras list"; 
        $response['data'] = $res;
        echoRespnse(201, $response);
    }
});

/**
* Order History (20 records per page)
* url - /orderhistory/:type/:tableno/:pageno
* type :- T / W , T-> Takeout, W -> Waiting
* pageno :- If 0 then all records else page data
* method - GET
* header Params - username(mandatory), password(mandatory)
**/
$app->get('/orderhistory/:type/:tableno/:page', 'authenticate', function($type, $tableno, $page) use ($app) {  
    global $user_id;       
    $response = array();
    $db = new DbHandler();
    $res = $db->orderHistory($user_id, $type, $tableno, $page);   
    if ($res=='NO_RECORD_FOUND') { 
        $response['code'] = 1;
        $response['error'] = true; 
        $response['message'] = "No Record found"; 
        echoRespnse(200, $response);
    } else if ($res=='UNABLE_TO_PROCEED') {
        $response['code'] = 2;
        $response['error'] = true;
        $response['message'] = "Unable to proceed";
        echoRespnse(200, $response);
    } else {
    	$totpages = $db->orderHistoryPages($user_id, $type, $tableno); 
        $response['code'] = 0;
        $response['error'] = false;
        $response['message'] = "Order History"; 
        $response['data'] = $res;
        $response['pages'] = $totpages;
        echoRespnse(201, $response);
    }
});

/**
* Make Tabel available
* url - /makeavailable
* params -  orderid(mandatory), tableno(mandatory), username(mandatory), password(mandatory)
* method - POST
* NOTE :- username + password must be used for manager / admin in params
* username : restaurant@pos_v1.com , password : 123456
* header Params - username(mandatory), password(mandatory)
**/
$app->post('/makeavailable', 'authenticate', function() use ($app) {
    global $user_id;
    // check for required params
    verifyRequiredParams(array('orderid', 'tableno', 'username', 'password'));
    $response = array();
    // reading post params
    $orderid = $app->request->post('orderid');
    $tableno = $app->request->post('tableno');
    $username = $app->request->post('username');
    $password = $app->request->post('password');
    
    $db = new DbHandler();
    $res = $db->makeAvailable($orderid, $tableno, $username, $password);
    if ($res == 'UNABLE_TO_PROCEED') {
        $response["code"] = 1;
        $response["error"] = true;
        $response["message"] = "Unable to proceed";
        echoRespnse(200, $response);
    } else if ($res == 'INVALID_ORDERID') {
        $response["code"] = 2;
        $response["error"] = true;
        $response["message"] = "Order id is not valid";
        echoRespnse(200, $response);
    } else if ($res == 'ALREADY_COMPLETED') {
        $response["code"] = 3;
        $response["error"] = true;
        $response["message"] = "Order already marked as completed";
        echoRespnse(200, $response);
    } else if ($res == 'INVALID_USERNAME') {
        $response["code"] = 4;
        $response["error"] = true;
        $response["message"] = "Username not exist";
        echoRespnse(200, $response);
    } else if ($res == 'INVALID_USERNAME_PASSWORD') {
        $response["code"] = 5;
        $response["error"] = true;
        $response["message"] = "Please enter valid username password";
        echoRespnse(200, $response);
    } else if ($res == 'USER_ACCOUNT_DEACTVATED') {
        $response["code"] = 6;
        $response["error"] = true;
        $response["message"] = "Manager account deactivated";
        echoRespnse(200, $response);
    } else {
        $response["code"] = 0;
        $response["error"] = false;
        $response["message"] = "Table is available for new orders";
        //$response["data"] = $res;
        echoRespnse(201, $response);
    }
});

/**
* Change item delivery type
* url - /changeitemdeliverytype
* params -  orderid(mandatory), orderitemid(mandatory), tableno(mandatory), oldtype(mandatory), newtype(mandatory)
* method - POST
* header Params - username(mandatory), password(mandatory)
**/
$app->post('/changeitemdeliverytype', 'authenticate', function() use ($app) {
    global $user_id;
    // check for required params
    verifyRequiredParams(array('orderid', 'orderitemid', 'tableno', 'oldtype', 'newtype'));
    $response = array();
    // reading post params
    $orderid = $app->request->post('orderid');
    $orderitemid = $app->request->post('orderitemid');
    $tableno = $app->request->post('tableno');
    $oldtype = $app->request->post('oldtype');
    $newtype = $app->request->post('newtype');
    
    $db = new DbHandler();
    $res = $db->changeItemDeliveryType($orderid, $orderitemid, $tableno, $oldtype, $newtype);
    if ($res == 'UNABLE_TO_PROCEED') {
        $response["code"] = 1;
        $response["error"] = true;
        $response["message"] = "Unable to proceed";
        echoRespnse(200, $response);
    } else if ($res == 'INVALID_ORDERID') {
        $response["code"] = 2;
        $response["error"] = true;
        $response["message"] = "Order id is not valid";
        echoRespnse(200, $response);
    } else if ($res == 'ALREADY_COMPLETED') {
        $response["code"] = 3;
        $response["error"] = true;
        $response["message"] = "Order already marked as completed";
        echoRespnse(200, $response);
    } else {
        $response["code"] = 0;
        $response["error"] = false;
        $response["message"] = "Item delivery type successfully updated";
        echoRespnse(201, $response);
    }
});

/**
* Change order item price
* url - /changeorderitemprice
* params -  orderid(mandatory), orderitemid(mandatory), tableno(mandatory), oldprice(mandatory), newprice(mandatory)
* method - POST
* header Params - username(mandatory), password(mandatory)
**/
$app->post('/changeorderitemprice', 'authenticate', function() use ($app) {
    global $user_id;
    // check for required params
    verifyRequiredParams(array('orderid', 'orderitemid', 'tableno', 'oldprice', 'newprice'));
    $response = array();
    // reading post params
    $orderid = $app->request->post('orderid');
    $orderitemid = $app->request->post('orderitemid');
    $tableno = $app->request->post('tableno');
    $oldprice = $app->request->post('oldprice');
    $newprice = $app->request->post('newprice');
    
    $db = new DbHandler();
    $res = $db->changeOrderItemPrice($user_id, $orderid, $orderitemid, $tableno, $oldprice, $newprice);
    if ($res == 'UNABLE_TO_PROCEED') {
        $response["code"] = 1;
        $response["error"] = true;
        $response["message"] = "Unable to proceed";
        echoRespnse(200, $response);
    } else if ($res == 'INVALID_ORDERID') {
        $response["code"] = 2;
        $response["error"] = true;
        $response["message"] = "Order id is not valid";
        echoRespnse(200, $response);
    } else if ($res == 'ALREADY_COMPLETED') {
        $response["code"] = 3;
        $response["error"] = true;
        $response["message"] = "Order already marked as completed";
        echoRespnse(200, $response);
    } else {
		
        $response["code"] = 0;
        $response["error"] = false;
        if ($newprice==0) {
			$response["message"] = "Item marked as free"; 
		} else {
			$response["message"] = "Item price successfully updated"; 
		}
        echoRespnse(201, $response);
    }
});

/**
* Update quantity + add / edit extras
* url - /updateorderitem 
* method - POST
* params - orderid(mandatory), itemid(mandatory), rowid(mandatory), allextras(mandatory), selectedextras(optional), quantity(mandatory), specialinstruction(optional)
* allextras= [{"id": "56", "name": "extra egg", "name_zh": "extra egg", "price": "2", "type":"T"},
	 {"id": "57", "name": "extra cha-shu", "name_zh": "extra cha-shu", "price": "3.5", "type":"P"}]
* selectedextras= [{"id": "56", "price": "2", "name": "extra egg", "name_zh": "extra egg", "type":"T"},
         {"id": "57", "price": "3.5", "name": "extra cha-shu", "name_zh": "extra cha-shu", "type":"P"}]
* specialinstruction : NO / LESS / MORE
* header Params - email(mandatory), password(mandatory)
**/
$app->post('/updateorderitem', 'authenticate', function() use ($app) {
    global $user_id;
    // check for required params
    verifyRequiredParams(array('orderid', 'itemid', 'rowid', 'allextras', 'quantity', 'selectedextras'));
    $response = array();
    
    // reading post params
    $orderid = $app->request->post('orderid');
    $itemid = $app->request->post('itemid');
    $rowid = $app->request->post('rowid');
    $allextras = $app->request->post('allextras');
    $quantity = $app->request->post('quantity');
    $selectedextras = $app->request->post('selectedextras');
    $specialinstruction = $app->request->post('specialinstruction');
    
    $db = new DbHandler();
    $res = $db->updateOrderItem($user_id, $orderid, $itemid, $rowid, $allextras, $quantity, $selectedextras, $specialinstruction);
    if ($res == 'INVALID_ORDERID') {
        $response["code"] = 1;
        $response["error"] = true;
        $response["message"] = "Inavlid Order id";
        echoRespnse(200, $response);
    } else if ($res == 'UNABLE_TO_PROCEED') {
        $response["code"] = 2;
        $response["error"] = true;
        $response["message"] = "Unable to proceed";
        echoRespnse(200, $response);
    } else if ($res == 'ALREADY_COMPLETED') {
        $response["code"] = 3;
        $response["error"] = true;
        $response["message"] = "Order already completed";
        echoRespnse(200, $response);
    } else {
        $response["code"] = 0;
        $response["error"] = false;
        $response["message"] = "Order successfully Saved";
        $response["data"] = $res;
        echoRespnse(201, $response);
    }
});

/**
* Merging Table Data
* url - /mergingtabledata/:orderid/:mergedorderids
* mergedtablesno :- comma separated, if more than 1 merging table
* method - GET
* header Params - username(mandatory), password(mandatory)
**/
$app->get('/mergingtabledata/:orderid/:mergedorderids', 'authenticate', function($orderid, $mergedorderids) use ($app) {  
    global $user_id;       
    $response = array();
    $db = new DbHandler();
    $res = $db->mergingTableData($user_id, $orderid, $mergedorderids);   

    if ($res=='NO_RECORD_FOUND') { 
        $response['code'] = 1;
        $response['error'] = true; 
        $response['message'] = "No Record found"; 
        echoRespnse(200, $response);
    } else if ($res=='UNABLE_TO_PROCEED') {
        $response['code'] = 2;
        $response['error'] = true;
        $response['message'] = "Unable to proceed";
        echoRespnse(200, $response);
    } else {
        $response["code"] = 0;
        $response["error"] = false;
        $response["message"] = "Merging Table Data"; 
        $response["data"] = $res;
        echoRespnse(201, $response);
    }
});

/**
* Make Payment
* url - /makepayment
* method - POST
* params - orderid(mandatory), mergedorderids(mandatory), tip(mandatory), paymenttype(mandatory), totamountpaid(mandatory), tippaidby(mandatory), change(mandatory), discount(mandatory)
* tip : default 0
* mergedorderids : default 0
* tippaidby : default 0 else CARD / CASH
* paymenttype :- CARD / CASH
* change : default 0 -- returning amount
* discount : default 0
* header Params - email(mandatory), password(mandatory)
**/
$app->post('/makepayment', 'authenticate', function() use ($app) {
    global $user_id;
    // check for required params
    verifyRequiredParams(array('orderid', 'mergedorderids', 'tip', 'paymenttype', 'totamountpaid', 'tippaidby'));
    $response = array();
    // reading post params
    $orderid = $app->request->post('orderid');
    $mergedorderids = $app->request->post('mergedorderids');
    $tip = $app->request->post('tip');
    $paymenttype = $app->request->post('paymenttype');
    $totamountpaid = $app->request->post('totamountpaid');
    $tippaidby = $app->request->post('tippaidby');

    $change = $app->request->post('change');
    $discount = $app->request->post('discount');

    $db = new DbHandler();
    $res = $db->makePayment($user_id, $orderid, $mergedorderids, $tip, $paymenttype, $totamountpaid, $tippaidby, $change, $discount);
    if ($res == 'INVALID_ORDERID') {
        $response["code"] = 1;
        $response["error"] = true;
        $response["message"] = "Inavlid Order id";
        echoRespnse(200, $response);
    } else if ($res == 'UNABLE_TO_PROCEED') {
        $response["code"] = 2;
        $response["error"] = true;
        $response["message"] = "Unable to proceed";
        echoRespnse(200, $response);
    } else if ($res == 'ALREADY_COMPLETED') {
        $response["code"] = 3;
        $response["error"] = true;
        $response["message"] = "Order already completed";
        echoRespnse(200, $response);
    } else {
        $response["code"] = 0;
        $response["error"] = false;
        $response["message"] = "Successfully made payment";
        $response["data"] = $res;
        echoRespnse(201, $response);
    }
});

/**
* Make Payment for Split bill -- Split by people only
* url - /makepaymentsplitdish
* method - POST
* params - orderid(mandatory), tip(mandatory), tippaidby(mandatory), paymenttype(mandatory), totamountpaid(mandatory), tax(mandatory), discount(mandatory), change(mandatory), orderitemids(mandatory), ordertotal(mandatory)
* tip : default 0
* tippaidby : default 0 else CARD / CASH
* paymenttype :- CARD / CASH
* discount : default 0
* change : default 0 -- returning amount
* orderitemids : comma separeted
* header Params - email(mandatory), password(mandatory)
**/
$app->post('/makepaymentsplitdish', 'authenticate', function() use ($app) {
    global $user_id;
    // check for required params
    verifyRequiredParams(array('orderid', 'tip', 'tippaidby', 'paymenttype', 'totamountpaid', 'tax', 'discount', 'change', 'orderitemids', 'ordertotal'));
    $response = array();
    // reading post params 
    $orderid = $app->request->post('orderid');
    $tip = $app->request->post('tip');
    $tippaidby = $app->request->post('tippaidby');
    $paymenttype = $app->request->post('paymenttype');
    $totamountpaid = $app->request->post('totamountpaid');
    $tax = $app->request->post('tax');
    $discount = $app->request->post('discount');
    $change = $app->request->post('change');
    $orderitemids = $app->request->post('orderitemids');
    $ordertotal = $app->request->post('ordertotal');

    $db = new DbHandler();
    $res = $db->makePaymentSplitDish($user_id, $orderid, $tip, $tippaidby, $paymenttype, $totamountpaid, $tax, $discount, $change, $orderitemids, $ordertotal);
    if ($res == 'INVALID_ORDERID') {
        $response["code"] = 1;
        $response["error"] = true;
        $response["message"] = "Inavlid Order id";
        echoRespnse(200, $response);
    } else if ($res == 'SUCCESSFULLY_DONE') {
        $response["code"] = 0;
        $response["error"] = false;
        $response["message"] = "Successfully made payment";
        echoRespnse(201, $response);
    } else if ($res == 'ITEMS_NOT_FOUND') {
        $response["code"] = 2;
        $response["error"] = true;
        $response["message"] = "Order Items Not Found";
        echoRespnse(200, $response);
    } else {
        $response["code"] = 3;
        $response["error"] = true;
        $response["message"] = "Please include these items only";
        $response["data"] = $res;
        echoRespnse(200, $response);
    }
});

/**
* Make Payment merge
* url - /makepaymentmerge 
* method - POST
* params - orderid(mandatory), mergedorderids(mandatory), tip(mandatory), paymenttype(mandatory)
* tip : default 0
* paymenttype :- CARD / CASH
* header Params - email(mandatory), password(mandatory)
**/
$app->post('/makepaymentmerge', 'authenticate', function() use ($app) {
    global $user_id;
    // check for required params
    verifyRequiredParams(array('orderid', 'mergedorderids', 'tip', 'paymenttype'));
    $response = array();
    // reading post params
    
    $orderid = $app->request->post('orderid');
    $mergedorderids = $app->request->post('mergedorderids');
    $tip = $app->request->post('tip');
    $paymenttype = $app->request->post('paymenttype');

    $db = new DbHandler();
    $res = $db->makePaymentMerge($user_id, $orderid, $mergedorderids, $tip, $paymenttype);
    if ($res == 'INVALID_ORDERID') {
        $response["code"] = 1;
        $response["error"] = true;
        $response["message"] = "Inavlid Order id";
        echoRespnse(200, $response);
    } else if ($res == 'UNABLE_TO_PROCEED') {
        $response["code"] = 2;
        $response["error"] = true;
        $response["message"] = "Unable to proceed";
        echoRespnse(200, $response);
    } else if ($res == 'ALREADY_COMPLETED') {
        $response["code"] = 3;
        $response["error"] = true;
        $response["message"] = "Order already completed";
        echoRespnse(200, $response);
    } else {
        $response["code"] = 0;
        $response["error"] = false;
        $response["message"] = "Successfully made payment";
        $response["data"] = $res;
        echoRespnse(201, $response);
    }
});

/**
* Send A Print to Kitchen
* url - /printTokitchen 
* method - POST
* params - printer(mandatory), order_no(mandatory), order_type(mandatory), table_no(mandatory), table(mandatory), Print_Item(mandatory), splititem(mandatory), print_zh(mandatory)
*examples
* table_no :  [[Dinein]] #19
* table : 19
* printer :- Printer[K]:PRINTER1
			Printer[C]:PRINTER2
* print_zh: bool true or false Send true for printing in Chinese		
* header Params - email(mandatory), password(mandatory)
**/
/* $app->post('/printTokitchen', 'authenticate', function() use ($app) {
    global $user_id;
    // check for required params
    verifyRequiredParams(array('printer', 'order_no', 'order_type', 'table_no', 'table', 'Print_Item', 'splititem', 'print_zh'));
    $response = array();
    // reading post params
    
    $Printer = $app->request->post('printer');
    $order_no = $app->request->post('order_no');
    $order_type = $app->request->post('order_type');
    $table_no = $app->request->post('table_no');
	$table = $app->request->post('table');
    $Print_Item = $app->request->post('Print_Item');
    $splititem = $app->request->post('splititem');
    $print_zh = $app->request->post('print_zh');

    $db = new DbHandler();
    $res = $db->sendPrintToKitchen($Printer, $order_no, $order_type, $table_no, $table, $Print_Item, $splititem, $print_zh);
    if ($res) {
		$response["code"] = 0;
        $response["error"] = false;
        $response["message"] = "Successfully sent";
        echoRespnse(201, $response);
    } else {
        $response["code"] = 2;
        $response["error"] = true;
        $response["message"] = "Unable to proceed";
        echoRespnse(200, $response);
    }
}); */

/**
* Send A Print to Kitchen
* url - /printTokitchen 
* method - POST
* params - order_no(mandatory)
* header Params - email(mandatory), password(mandatory)
**/
$app->post('/printTokitchen', 'authenticate', function() use ($app) {
    global $user_id;
    // check for required params
    verifyRequiredParams(array('order_no','printer_name'));
    $response = array();
    // reading post params
    
    $Printer = $app->request->post('printer');
   
    $db = new DbHandler();
    $res = $db->sendPrintToKitchen($order_no, $user_id);
    if($res==NO_ITEM_UNPRINTED){
        $response["code"] = 2;
        $response["error"] = true;
        $response["message"] = "No item remaining to send to kitchen";
        echoRespnse(200, $response);
    } else if($res==ORDER_NOT_EXIST){
        $response["code"] = 2;
        $response["error"] = true;
        $response["message"] = "Order doesn't exist.";
        echoRespnse(200, $response);
    } else if($res==INVALID_ORDERID){
        $response["code"] = 2;
        $response["error"] = true;
        $response["message"] = "Invalid Order";
        echoRespnse(200, $response);
    } else if($res==INVALID_CASHIER){
        $response["code"] = 2;
        $response["error"] = true;
        $response["message"] = "Invalid Cashier";
        echoRespnse(200, $response);
    }
	else {
		$response["code"] = 0;
        $response["error"] = false;
        $response["message"] = "Successfully sent";
        echoRespnse(201, $response);
    } 
});

/**
* Print Receipt
* url - /printreceipt
* method - POST
* params - order_no(mandatory)
* header Params - email(mandatory), password(mandatory)
**/
$app->post('/printreceipt', 'authenticate', function() use ($app) {
	//echo "Hello";die;
    global $user_id;
    // check for required params
    verifyRequiredParams(array('order_no','printer_name'));
    $response = array();
    // reading post params
	//echo $user_id;die;
    
    $order_no = $app->request->post('order_no');
    $cashier_id = $user_id;
	
    $db = new DbHandler();
    $res = $db->printReceipt($order_no, $cashier_id);
	echo $res;
    if ($res == 'INVALID_ORDERID') {
        $response["code"] = 1;
        $response["error"] = true;
        $response["message"] = "Inavlid Order id";
        echoRespnse(200, $response);
    } else if($res==ORDER_NOT_EXIST){
        $response["code"] = 2;
        $response["error"] = true;
        $response["message"] = "Order doesn't exist.";
        echoRespnse(200, $response);
    } else if ($res == 'INVALID_CASHIER') {
        $response["code"] = 2;
        $response["error"] = true;
        $response["message"] = "Invalid User";
        echoRespnse(200, $response);
    } else {
        $response["code"] = 0;
        $response["error"] = false;
        $response["message"] = "Successfully printed";
        $response["data"] = $res;
        echoRespnse(201, $response);
    }
});

/**
* Print Split Bill
* url - /printsplit
* method - POST
* params - order_no(mandatory)
* header Params - email(mandatory), password(mandatory)
**/
/* $app->post('/printsplit', 'authenticate', function() use ($app) {
	//echo "Hello";die;
    global $user_id;
    // check for required params
    verifyRequiredParams(array('order_no','printer_name'));
    $response = array();
    // reading post params
	//echo $user_id;die;
    
    $order_no = $app->request->post('order_no');
    $cashier_id = $user_id;
	
    $db = new DbHandler();
    $res = $db->printReceipt($order_no, $cashier_id);
	echo $res;
    if ($res == 'INVALID_ORDERID') {
        $response["code"] = 1;
        $response["error"] = true;
        $response["message"] = "Inavlid Order id";
        echoRespnse(200, $response);
    } else if($res==ORDER_NOT_EXIST){
        $response["code"] = 2;
        $response["error"] = true;
        $response["message"] = "Order doesn't exist.";
        echoRespnse(200, $response);
    } else if ($res == 'INVALID_CASHIER') {
        $response["code"] = 2;
        $response["error"] = true;
        $response["message"] = "Invalid User";
        echoRespnse(200, $response);
    } else {
        $response["code"] = 0;
        $response["error"] = false;
        $response["message"] = "Successfully printed";
        $response["data"] = $res;
        echoRespnse(201, $response);
    }
}); */


/**
* Split Bill
* url - /splitorder 
* method - POST
* params - table_no(mandatory), order_no(mandatory), suborder(mandatory)
	table_no => 2
    order_no => 98528
		suborder[0][suborder_no] => 1
		suborder[0][subtotal] => 5.5
		suborder[0][discount_value] => 0
		suborder[0][discount_type] => 'UNKNOWN' or 'FIXED' or 'PERCENT'
		suborder[0][discount_amount] => 0
		suborder[0][tax] => 13
		suborder[0][tax_amount] => 0.72
		suborder[0][total] => 6.22
		suborder[0][paid_card] => 0
		suborder[0][paid_cash] => 6.22
		suborder[0][tip_card] => 0
		suborder[0][tip_cash] => 0
		suborder[0][change] => 0
		suborder[0][items] => [{"item_id":0,"image":"1467777127_cousine.png","name_en":"Noodles w/Beef Sirloin","name_zh":"å®‹å«‚ç‰›è‚‰é¢","selected_extras_name":"","price":10.99,"extras_amount":0,"quantity":1,"order_item_id":"1771","state":"share","shared_suborders":[1,2],"assigned_suborder":0}]
* header Params - email(mandatory), password(mandatory)
**/
$app->post('/splitorder', 'authenticate', function() use ($app) {
    global $user_id;
    // check for required params
    verifyRequiredParams(array('table_no', 'order_no'));
    $response = array();
    // reading post params
    
    $data['table_no'] = $app->request->post('table_no');
    $data['order_no'] = $app->request->post('order_no');
    $data['suborder'] = $app->request->post('suborder');
	if(empty($data['suborder']))
	{
		$response["code"] = 1;
        $response["error"] = true;
        $response["message"] = "Suborder is missing";
        echoRespnse(200, $response);
	}
	
    $db = new DbHandler();
    $res = $db->splitBill($data);
	//var_dump($res);
    if ($res == 'INVALID_ORDERID') {
        $response["code"] = 1;
        $response["error"] = true;
        $response["message"] = "Inavlid Order id";
        echoRespnse(200, $response);
    } else if ($res == 'UNABLE_TO_PROCEED') {
        $response["code"] = 2;
        $response["error"] = true;
        $response["message"] = "Unable to proceed";
        echoRespnse(200, $response);
    } else if ($res == 'ALREADY_COMPLETED') {
        $response["code"] = 3;
        $response["error"] = true;
        $response["message"] = "Order already completed";
        echoRespnse(200, $response);
    } else {
        $response["code"] = 0;
        $response["error"] = false;
        $response["message"] = "Successfully done.";
        $response["data"] = $res;
        echoRespnse(201, $response);
    }
});

/**
* Complete Order
* url - /completeorder
* method - POST
* params - order_no(mandatory), card_val(mandatory), cash_val(mandatory), tip(mandatory), tip_paid_by(mandatory), change(mandatory), paid_by(mandatory)
	order_no:120460213
	card_val:0
	cash_val:12.44
	tip:0
	tip_paid_by:NO TIP or 'CARD' or 'CASH' or 'MIXED'
	change:0
	paid_by:CASH/CARD
* header Params - email(mandatory), password(mandatory)
**/
$app->post('/completeorder', 'authenticate', function() use ($app) {
    global $user_id;
    // check for required params
    verifyRequiredParams(array('order_no'));
    $response = array();
    // reading post params
    
    $data['order_no'] = $app->request->post('order_no');
	$data['card_val'] = $app->request->post('card_val');
	$data['cash_val'] = $app->request->post('cash_val');
	$data['tip'] = $app->request->post('tip');
	$data['tip_paid_by'] = $app->request->post('tip_paid_by');
	$data['change'] = $app->request->post('change');
	$data['paid_by'] = $app->request->post('paid_by');
	
    $db = new DbHandler();
    $res = $db->completeOrder($data);
	//var_dump($res);
    if ($res == 'INVALID_ORDERID') {
        $response["code"] = 1;
        $response["error"] = true;
        $response["message"] = "Inavlid Order id";
        echoRespnse(200, $response);
    } else if ($res == 'UNABLE_TO_PROCEED') {
        $response["code"] = 2;
        $response["error"] = true;
        $response["message"] = "Unable to proceed";
        echoRespnse(200, $response);
    } else if ($res == 'ALREADY_COMPLETED') {
        $response["code"] = 3;
        $response["error"] = true;
        $response["message"] = "Order already completed";
        echoRespnse(200, $response);
    } else {
        $response["code"] = 0;
        $response["error"] = false;
        $response["message"] = "Successfully done.";
        $response["data"] = $res;
        echoRespnse(201, $response);
    }
});

/**
* Print Split Bill
* url - /printSplit
* method - POST
* params - 	1. order_no(mandatory), 
			2. suborders(mandatory) - It will be an array containing following keys
				2a. suborders[0][after_discount] = 8.75
				2b. suborders[0][change] = 0
				2c. suborders[0][discount_amount] = 0
				2d. suborders[0][discount_type]	= 'UNKNOWN' or 'FIXED' or 'PERCENT'
				2e. suborders[0][discount_value] = 0
				2f. suborders[0][items][0][name_en] = Noodles w/Beef Sirloin s2
				2g. suborders[0][items][0][name_zh] = å®‹å«‚ç‰›è‚‰é¢ s2
				2h. suborders[0][items][0][price] = 5.5
				2i. suborders[0][items][0][quantity] = 1
				2j. suborders[0][items][0][selected_extras_name]
				2k. suborders[0][received_card]	= 0
				2l. suborders[0][received_cash]	= 0
				2m. suborders[0][received_total] = 0
				2n. suborders[0][suborder_no] = 2
				2o. suborders[0][subtotal] = 8.75
				2p. suborders[0][tax_amount] = 1.14
				2q. suborders[0][tax_rate] = 13
				2r. suborders[0][tip_amount] = 0
				2s. suborders[0][tip_card] = 0
				2t. suborders[0][tip_cash] = 0
				2u. suborders[0][total]	= 9.89
			3. table_type 'D', 'T', 'W'
			4. print_zh 1 or 0
			5. table_no 
* header Params - email(mandatory), password(mandatory)
**/
$app->post('/printSplit', 'authenticate', function() use ($app) {
    global $user_id;
    // check for required params
    verifyRequiredParams(array('order_no', 'table_type'));
    $response = array();
    // reading post params
    
    $order_no = $app->request->post('order_no');
	$suborders = $app->request->post('suborders');
	$table_type = $app->request->post('table_type');
	$print_zh = $app->request->post('print_zh');
	$table_no = $app->request->post('table_no');
	
	if(empty($suborders))
	{
		$response["code"] = 1;
        $response["error"] = true;
        $response["message"] = "suborders missing";
        echoRespnse(200, $response);
	}
    $db = new DbHandler();
    $res = $db->printSplit($order_no, $table_no, $suborders, $table_type, $print_zh, $user_id);
	//var_dump($res);die;
    if ($res == 'INVALID_ORDERID') {
        $response["code"] = 1;
        $response["error"] = true;
        $response["message"] = "Inavlid Order id";
        echoRespnse(200, $response);
    } else if ($res == 'UNABLE_TO_PROCEED') {
        $response["code"] = 2;
        $response["error"] = true;
        $response["message"] = "Unable to proceed";
        echoRespnse(200, $response);
    } else if ($res == 'ALREADY_COMPLETED') {
        $response["code"] = 3;
        $response["error"] = true;
        $response["message"] = "Order already completed";
        echoRespnse(200, $response);
    } else {
        $response["code"] = 0;
        $response["error"] = false;
        $response["message"] = "Successfully done.";
        $response["data"] = $res;
        echoRespnse(201, $response);
    }
});

/**
* Get chat messages
* url - /messages/:eventid/:userid
* currently my events will return in both type (created by me)
* method - GET
* header Params - email (mandatory), password (mandatory)
**/
$app->post('/pushnoti', function($eventid, $userid) use ($app) { 
    verifyRequiredParams(array('token'));
    $token = $app->request()->post('token');
    $response = array();
    $db = new DbHandler();
    $message='test noti from pos';
    //$token='APA91bGRClKUtgoZMswPqFaXJ-oCb_cz7mIb01m3bNUIFvSSEjMG0a-g4GUZj1cxISyi_k5Zdt1_Xd91jN9pusNVzZhTrlZzQg7XPDggdu10M681Yx8oHAjhrgM6odR6ESDyqhG4DjfH';
    $res = $db->pushNotification($token, $message);   
    
});

function verifyUser($user_id) {
    $response = array();
    $db = new DbHandler();
    if ($db->isUserExists($user_id)) {
        return true;
    } else {
        $app = \Slim\Slim::getInstance();
        $response["code"] = 11;
        $response["error"] = true;
        $response["message"] = 'Invalid User id ' . $user_id;
        echoRespnse(200, $response);
        $app->stop();
    }
}

/**
* Verifying required params posted or not
**/
function verifyRequiredParams($required_fields) {
    $error = false;
    $error_fields = "";
    $request_params = array();
    $request_params = $_REQUEST;
    // Handling PUT request params
    if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
        $app = \Slim\Slim::getInstance();
        parse_str($app->request()->getBody(), $request_params);
    }
    foreach ($required_fields as $field) {
        if (!isset($request_params[$field]) || strlen(trim($request_params[$field])) <= 0) {
            $error = true;
            $error_fields .= $field . ', ';
        }
    }
    if ($error) {
        $response = array();
        $app = \Slim\Slim::getInstance();
        $response["code"] = 10;
        $response["error"] = true;
        $response["message"] = 'Required field(s) ' . substr($error_fields, 0, -2) . ' is missing or empty';
        echoRespnse(400, $response);
        $app->stop();
    }
}

/**
* Validating email address
**/
function validateEmail($email) {
    $app = \Slim\Slim::getInstance();
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response["code"] = 11;
        $response["error"] = true;
        $response["message"] = 'Email address is not valid';
        echoRespnse(400, $response);
        $app->stop();
    }
}

/**
* Echoing json response to client
* @param String $status_code Http response code
* @param Int $response Json response
**/
function echoRespnse($status_code, $response) {
    $app = \Slim\Slim::getInstance();
    // Http response code
    $app->status($status_code);
    // setting response content type to json
    $app->contentType('application/json');
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
}

function validateLevel($level) {
    $app = \Slim\Slim::getInstance();
    if ($level <= 0 || $level > 8) {
        $response["code"] = 13;
        $response["error"] = true;
        $response["message"] = 'Invalid Value For Level';
        echoRespnse(400, $response);
        $app->stop();
    }
}

/**
* Adding Middle Layer to authenticate every request
* Checking if the request has valid api key in the 'Authorization' header
**/
function authenticate(\Slim\Route $route) {
    $app = \Slim\Slim::getInstance();
    $realm = 'Protected APIS';
    $req = $app->request();
    $res = $app->response();
    $username = $req->headers('PHP_AUTH_USER');
    $password = $req->headers('PHP_AUTH_PW');
    if (isset($username) && $username != '' && isset($password) && $password != '') {
		$db = new DbHandler();
        if ($userdata = $db->validateUser($username, $password)) {
			
            global $user_id;
            $user_id = $userdata["id"];
            return true;
        } else {
            $res->header('WWW-Authenticate', sprintf('Basic realm="%s"', $realm));
            $res = $app->response();
            $res->status(401);
            $app->stop();
        }
    } else {
        $res->header('WWW-Authenticate', sprintf('Basic realm="%s"', $realm));
        $res = $app->response();
        $res->status(401);
        $app->stop();
    }
}
$app->run();
?>
