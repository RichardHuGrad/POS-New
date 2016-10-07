<?php
ini_set("display_errors", "1");
error_reporting(E_ALL);
require_once '../include/DbConnect.php';
require_once '../include/DbHandler.php';
require_once '../include/PassHash.php';
require '../lib/Slim/Slim.php';

use Braintree\Configuration;

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
$user_id = NULL;
 

/**
 * Verifying required params posted or not
 */
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
        // Required field(s) are missing or empty
        // echo error json and stop the app
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
 * Echoing json response to client
 * @param String $status_code Http response code
 * @param Int $response Json response
 */
function echoRespnse($status_code, $response) {
    $app = \Slim\Slim::getInstance();
    // Http response code
    $app->status($status_code);
 
    // setting response content type to json
    $app->contentType('application/json');
 
    echo json_encode($response);
}

/**
 * Validating email address
 */
function validateEmail($email) {
    $app = \Slim\Slim::getInstance();
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response["code"] = 11;
        $response["error"] = true;
        $response["message"] = 'Email address is not valid';
        echoRespnse(200, $response);
        $app->stop();
    }
}


/**
 * User Registration
 * url - /userregisteration
 * method - POST
 * params - firstname(mandatory), lastname(mandatory), mobile_no(mandatory), address(mandatory), date_of_birth(mandatory), email(mandatory), password(mandatory),
 */
$app->post('/registeration', function () use ($app) {
    // check for required params
    verifyRequiredParams(array('email', 'firstname', 'lastname', 'mobile_no', 'address', 'date_of_birth', 'password'));

    $response = array();

    // reading post params
    $firstname = $app->request->post('firstname');
    $email = $app->request->post('email');
    $password = $app->request->post('password');

    $firstname = $app->request->post('firstname');
    $lastname = $app->request->post('lastname');
    $mobile_no = $app->request->post('mobile_no');
    $address = $app->request->post('address');
    $date_of_birth = $app->request->post('date_of_birth');

   	validateEmail($email);
    $db = new DbHandler();
    if(isset($_FILES['image']) && $_FILES['image'] != '') {   
		$image = $_FILES['image']; 
	}else {
		$image = ''; 
	}

	if(isset($_FILES['age_proof_doc']) && $_FILES['age_proof_doc'] != '') {   
		$age_proof_doc = $_FILES['age_proof_doc']; 
	}else {
		$age_proof_doc = ''; 
	}	

    $res = $db->createUser($firstname, $lastname, $mobile_no, $address, $date_of_birth, $image, $age_proof_doc, $email, $password);
    if ($res == 'UNABLE_TO_PROCEED') {
        $response["code"] = 1;
        $response["error"] = true;
        $response["message"] = "Unable to proceed";
        echoRespnse(200, $response);
    } else if ($res == 'EMAIL_ALREADY_EXISTED') {
        $response["code"] = 2;
        $response["error"] = true;
        $response["message"] = "Email already exists";
        echoRespnse(200, $response);
    }  else {
        $response["code"] = 0;
        $response["error"] = false;
        $response["message"] = "Customer successfully registered";
        $response["data"] = $res;
        echoRespnse(201, $response);
    }
});


/**
 * User Login
 * url - /userlogin
 * method - POST
 * params - name (optional), email (mandatory) ,password (optional),loginfrom(mandatory),device_token(mandatory),
 * loginfrom  -   N -> normal,F -> facebook,G -> google+
 */
$app->post('/login', function() use ($app)
{                          
	// check for required params
	verifyRequiredParams(array('email', 'loginfrom', 'device_type', 'device_token', 'user_type'));
	$response = array();
	
	// reading post params
    $name = $app->request->post('name');
    $email = $app->request->post('email');   
	$loginfrom = $app->request->post('loginfrom');
	$password = $app->request->post('password');
	$device_type = $app->request->post('device_type');
	$device_token = $app->request->post('device_token');
	$user_type = $app->request->post('user_type');

	$db = new DbHandler(); 
	$res = $db->loginuser($name, $email, $loginfrom, $password, $device_type, $device_token, $user_type);
	if ($res == INVALID_REQUEST)  
	{ 
		$response["code"] = 1;
		$response["error"] = true;
		$response["message"] = "Invalid Login From";  
	} 
	else if ($res == NEED_PASSWORD)  
	{  
		$response["code"] = 2;
		$response["error"] = true;
		$response["message"] = "Need password for login from App";    
	}
	else if ($res == INVALID_EMAIL)  
	{  
		$response["code"] = 2;
		$response["error"] = true;
		$response["message"] = "Invalid Email Id";    
	}
	
	else if ($res == INVALID_EMAIL_PASSWORD)  
	{
		$response["code"] = 4;
		$response["error"] = true;
		$response["message"] = "Invalid Email Id or Password";   
	}
	
	else if ($res == UNABLE_TO_PROCEED)  
	{
		$response["code"] = 6;
		$response["error"] = true;
		$response["message"] = "Unable to proceed your request";    
	}
	else if ($res == EMAIL_ALREADY_EXISTED)  
	{
		$response["code"] = 7;
		$response["error"] = true;
		$response["message"] = "Email already exist";        
	}
	else
	{ 
		$response["code"] = 0;
		$response["error"] = false;
		$response["message"] = "User Profile"; 
		$response['data']['userid'] = $res['id'];
		$response['data']['firstname'] = $res['firstname'];
		$response['data']['lastname'] = $res['lastname'];
		$response['data']['email'] = $res['email'];
		$response['data']['status'] = $res['status'];
		$response['data']['loginfrom'] = $res['loginfrom'];
		
		/*if ($res['image']!='')
		{		
			$response['data']['image'] = PROFILEPIC.$res['image']; 			
		} else {
			$response['data']['image'] = '';
		}*/
	}
	echoRespnse(200, $response);
});


/**
 * User Forgot Password
 * url - /forgotpassword
 * method - POST
 * params - email (mandatory)
 */
$app->post('/forgotpassword', function () use ($app) {
    verifyRequiredParams(array('email'));

    $email = $app->request()->post('email');
    $response = array();

    $db = new DbHandler();
    $user = $db->sendNewPassword($email);
    if ($user == UNABLE_TO_PROCEED) {
        $response['code'] = 1;
        $response['error'] = true;
        $response['message'] = "Unable to proceed your request";
    } else if ($user == INVALID_USERNAME) {
        $response['code'] = 1;
        $response['error'] = true;
        $response['message'] = "Email address does not exist";
    } else {
        $response['code'] = 0;
        $response['error'] = false;
        $response['message'] = "New Password sent to your registered mail address";
    }
    echoRespnse(200, $response);
});


/**
 * User Change Password
 * url - /changepassword
 * method - POST
 * params - oldpassword (mandatory), newpassword (mandatory)
 * header Params - email (mandatory), password (mandatory)
 */
$app->post('/changepassword', 'authenticate', function () use ($app) {
    global $user_id;
    verifyRequiredParams(array('oldpassword', 'newpassword'));

    $oldpassword = $app->request()->post('oldpassword');
    $newpassword = $app->request()->post('newpassword');
    $response = array();

    $db = new DbHandler();
    $user = $db->changePassword($user_id, $oldpassword, $newpassword);
    if ($user == UNABLE_TO_PROCEED) {
        $response['code'] = 1;
        $response['error'] = true;
        $response['message'] = "Unable to proceed your request";
    } else if ($user == INVALID_USERNAME) {
        $response['code'] = 1;
        $response['error'] = true;
        $response['message'] = "Invalid user access";
    } else if ($user == INVALID_OLD_PASSWORD) {
        $response['code'] = 1;
        $response['error'] = true;
        $response['message'] = "Invalid old password";
    } else {
        $response['code'] = 0;
        $response['error'] = false;
        $response['message'] = "Password successfully changed";
    }
    echoRespnse(200, $response);
});

/**
 * User Profile Update
 * url - /updateprofile
 * method - POST
 * params - fullname(mandatory), gender(mandatory), dob(mandatory), image(optional),     	gluten_free(optional),vegetarian(optional),vegan(optional),dairy_free(optional),low _sodium(optional),Kosher(optional),halal(optional) ,organic(optional) 
 * header Params - username(mandatory), password(mandatory) 
 * Note gender = M-> Male, F->Female
 * dob -> (date formate) 1988-02-20
 *
 */
$app->post('/updateprofile', 'authenticate', function() use ($app)  
{  
	global $user_id;
	$db = new DbHandler();
	// check for required params
	verifyRequiredParams(array('fullname','gender','dob'));
	
	$response = array();
	// reading post params
	$fullname = $app->request->post('fullname');
	$gender = $app->request->post('gender');
	$dob = $app->request->post('dob');

	$gluten_free = $app->request->post('gluten_free');
	$vegetarian = $app->request->post('vegetarian');
	$vegan = $app->request->post('vegan');	
	$dairy_free = $app->request->post('dairy_free');
	$low_sodium = $app->request->post('low_sodium');
	$Kosher = $app->request->post('kosher');	
	$halal = $app->request->post('halal');	
	$organic = $app->request->post('organic');	
	
	
	
	if(isset($_FILES['image']) && $_FILES['image'] != '') {   
		$image = $_FILES['image']; 
	}else {
		$image = ''; 
	}
	
	$res = $db->updateUser($user_id, $fullname, $gender, $dob, $image, $gluten_free,$vegetarian, $vegan, $dairy_free, $low_sodium, $Kosher, $halal, $organic); 
	if ($res==UNABLE_TO_PROCEED)
	{   
		$response['code'] = 1;
		$response['error'] = true;
		$response['message'] = "Unable to proceed your request";
	}
	else if ($res==INVALID_USER)
	{
		$response['code'] = 2;
		$response['error'] = true;
		$response['message'] = "Invalid user";
	}
	else if ($res==EMAIL_ALREADY_EXISTED)
	{
		$response['code'] = 3;
		$response['error'] = true;
		$response['message'] = "Email already exist";
	}
	else 
	{
		$response["code"] = 0;
		$response["error"] = false;
		$response["message"] = "User Profile successfully updated, User Data";
		$response['data']['userid'] = $res['id'];
		$response['data']['name'] = $res['name'];   
		$response['data']['email'] = $res['email'];		
		$response['data']['status'] = $res['status'];
		$response['data']['loginfrom'] = $res['loginfrom']; 
		$response['data']['dob'] = $res['dob'];
		$response['data']['gender'] = ''.$res['gender'];		
		$response['data']['gluten_free'] = ''.$res['gluten_free'];
		$response['data']['vegetarian'] = ''.$res['vegetarian'];
		$response['data']['vegan'] = ''.$res['vegan'];
		$response['data']['dairy_free'] = ''.$res['dairy_free'];
		$response['data']['kosher'] = ''.$res['kosher'];
		$response['data']['halal'] = ''.$res['halal'];
		$response['data']['organic'] = ''.$res['organic'];
		$response['data']['low_sodium'] = ''.$res['low_sodium'];		
		if ($res['image']!='')
			{				
				$response['data']['image'] = PROFILEPIC.$res['image']; 							
			}
			else
			{
				$response['data']['image'] = '';
			}
	}
	echoRespnse(200, $response);
});

/**
 * Adding Middle Layer to authenticate every request
 * Checking if the request has valid api key in the 'Authorization' header
 */
function authenticate(\Slim\Route $route) {
    $app = \Slim\Slim::getInstance();
    $realm = 'Protected APIS';

    $req = $app->request();
    $res = $app->response();

    $username = $req->headers('PHP_AUTH_USER');
    $password = $req->headers('PHP_AUTH_PW');

    if (isset($username) && $username != '' && isset($password) && $password != '') {
		
		$dbconn = new DbConnect();
        $db = new DbHandler();
		
        if ($userid = $db->validateUser($username, $password)) {
            global $user_id;
            $user_id = $userid["id"];
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

/**
 * Get Page Content
 * url - /getpage
 * method - GET
 * params - page_id (mandatory), 
 */
$app->get('/getpage/:page_id', function($page_id) use ($app){
	//global $user_id;	
	$response = array();	
	$db = new DbHandler();	
    $dbconn = new DbConnect();
	$r=$db->getPageContent($page_id);	
	if ($r=='')
	{
		$response['code'] = 1;
		$response['error'] = true;
		$response['message'] = "No Record Found";
	}
	else if ($r==UNABLE_TO_PROCEED)
	{
		$response['code'] = 2;
		$response['error'] = true;
		$response['message'] = "No Record Found";
	}
	else
	{
		$response['code'] = 0;
		$response['error'] = false;
		$response['message'] = "Page Content:-";
		$response['data'] = $r;
	}
	
	echoRespnse(200, $response);
});

/**
 * Get Restaurants  List via latitude ,longitude
 * url - /searchrestaurants
 * method - POST
 * params - latitude (mandatory), longitude (mandatory)
 * header Params - email (mandatory), password (mandatory)
 */
$app->post('/searchrestaurants','authenticate', function () use ($app) {
    global $user_id;
    verifyRequiredParams(array('latitude', 'longitude'));

    $latitude = $app->request()->post('latitude');
    $longitude = $app->request()->post('longitude');
    $response = array();

    $db = new DbHandler();
    $user = $db->restroListByLatLlong($user_id, $latitude, $longitude);
    if ($user == UNABLE_TO_PROCEED) {
        $response['code'] = 1;
        $response['error'] = true;
        $response['message'] = "Unable to proceed your request";
    }  else {
        $response['code'] = 0;
        $response['error'] = false;
        $response['userdata'] = $user;
        $response['message'] = "Restaurants List";
    }
    echoRespnse(200, $response);
});


/**
 * Get Restaurants  List via latitude ,longitude
 * url - /listrestaurants
 * method - POST
 * header Params - email (mandatory), password (mandatory)
 */
$app->post('/listrestaurants','authenticate', function () use ($app) {
    global $user_id;

    $response = array();

    $db = new DbHandler();
    $user = $db->listrestaurants($user_id);
    if ($user == UNABLE_TO_PROCEED) {
        $response['code'] = 1;
        $response['error'] = true;
        $response['message'] = "Unable to proceed your request";
    }  else {
        $response['code'] = 0;
        $response['error'] = false;
        $response['userdata'] = $user;
        $response['message'] = "Restaurants List";
    }
    echoRespnse(200, $response);
});


/**
 * Get categories  List via latitude ,longitude
 * url - /getCategoriesList
 * method - POST
 * params - restaurant_id (mandatory)
 * header Params - email (mandatory), password (mandatory)
 */
$app->get('/getCategories/:restaurant_id', 'authenticate', function($restaurant_id) use ($app){

	$response = array();	
	$db = new DbHandler();	
    $dbconn = new DbConnect();
	$r=$db->getCategories($restaurant_id);	
	if ($r=='')
	{
		$response['code'] = 1;
		$response['error'] = true;
		$response['message'] = "No Record Found";
	}
	else if ($r==UNABLE_TO_PROCEED)
	{
		$response['code'] = 2;
		$response['error'] = true;
		$response['message'] = "No Record Found";
	}
	else
	{
		$response['code'] = 0;
		$response['error'] = false;
		$response['message'] = "Categories List:-";
		$response['data'] = $r;
	}
	echoRespnse(200, $response);
});



/**
 * Get categories  List via latitude ,longitude
 * url - /getMenus
 * method - POST
 * params - category_id (mandatory)
 * header Params - email (mandatory), password (mandatory)
 */
$app->get('/getMenus/:category_id', 'authenticate', function($category_id) use ($app){

	$response = array();	
	$db = new DbHandler();	
    $dbconn = new DbConnect();
	$r=$db->getMenus($category_id);	
	if ($r=='')
	{
		$response['code'] = 1;
		$response['error'] = true;
		$response['message'] = "No Record Found";
	}
	else if ($r==UNABLE_TO_PROCEED)
	{
		$response['code'] = 2;
		$response['error'] = true;
		$response['message'] = "No Record Found";
	}
	else
	{
		$response['code'] = 0;
		$response['error'] = false;
		$response['message'] = "Menus List:-";
		$response['data'] = $r;
	}
	
	echoRespnse(200, $response);
});


/**
 * Get Page Content
 * url - /generatetoken
 * method - GET
 * params - customer_id (mandatory), 
 */
$app->get('/generatetoken', function() use ($app){
	$response = array();	

	require_once 'Setup.php';

    Configuration::environment('sandbox');
    Configuration::merchantId('tbb23ppsvmcmxrmc');
    Configuration::publicKey('kc9k4sgn4wxxg666');
    Configuration::privateKey('41000f2f335b5f4d2df8da86f66a136a');
	$clientToken = Braintree_ClientToken::generate();

	if ($clientToken=='')
	{
		$response['code'] = 1;
		$response['error'] = true;
		$response['message'] = "Some Errors Occured, please try after some time";
	}
	else
	{
		$response['code'] = 0;
		$response['error'] = false;
		$response['message'] = "Token successfully generated.";
		$response['data'] = array('token'=>$clientToken);
	}
	
	echoRespnse(200, $response);
});


/**
 * Get Page Content
 * url - /braintreetransaction
 * method - GET
 */
$app->post('/braintreetransaction', function() use ($app){
	

    verifyRequiredParams(array('payment_method_nonce'));

    $payment_method_nonce = $app->request()->post('payment_method_nonce');

	require_once 'Setup.php';

    Configuration::environment('sandbox');
    Configuration::merchantId('tbb23ppsvmcmxrmc');
    Configuration::publicKey('kc9k4sgn4wxxg666');
    Configuration::privateKey('41000f2f335b5f4d2df8da86f66a136a');

    $result = Braintree_Transaction::sale([
		'amount' => '100.00',
		'paymentMethodNonce' => $payment_method_nonce,
		'options' => [
		'submitForSettlement' => True
	  ]
	]);

	if (!$result->success)
	{
		$response['code'] = 1;
		$response['error'] = true;
		$response['message'] = $result->errors->deepAll();
	}
	else
	{
		$transaction = $result->transaction;
		$transaction->status;
		$response['code'] = 0;
		$response['error'] = false;
		$response['message'] = "Payment successfully done.";
		$response['data'] = array('transaction_status'=>$transaction->status, 'transaction_id'=>$transaction->id);
	}
	
	echoRespnse(200, $response);
});



$app->run();
?>

