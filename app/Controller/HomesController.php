<?php

/**
 * Class HomesController
 * Note*- here cashier id is related to the restaurant id
 */
App::uses('PrintLib', 'Lib');
class HomesController extends AppController {
    public $fontStr1 = "simsun";


    public $components = array('Paginator');

    /**
     * beforeFilter
     * @return null
     */
    public function beforeFilter() {

        parent::beforeFilter();
        $this->Auth->allow('index', 'forgot_password');
        $this->layout = "default";
    }

    /**
     * Website home page
     * @return mixed
     */
    public function index() {

        if ($this->request->is('post')) {
            $this->loadModel("Cashier");
            if (isset($this->request->data['Cashier']['username']) && isset($this->request->data['Cashier']['password'])) {
                $username = $this->request->data['Cashier']['username'];
                $password = Security::hash($this->data['Cashier']['password'], 'md5', false);

                $cond = array(
                    'Cashier.password' => $password,
                    'OR' => array(
                        'Cashier.email' => $username,
                    // 'OR' => array('Cashier.mobile_no' => $username)
                    )
                );
                $user = $this->Cashier->find('first', array(
                    'conditions' => $cond,
                ));
                if (!empty($user)) {
                    if ($user['Cashier']['status'] == "A") {
                        if ($user['Cashier']['is_verified'] == "Y") {
                            $user['Cashier']['type'] = 'cashier';
                            $this->Auth->login($user['Cashier']);
                        } else {
                            $this->Session->setFlash('Your account not verified,please contact to admin', 'error');
                            $this->redirect("index");
                        }
                    } else {
                        $this->Session->setFlash('Your account is deactivated by admin,please contact to admin', 'error');
                        $this->redirect("index");
                    }
                }
                if ($this->Auth->login()) {
                    $this->redirect($this->Auth->loginRedirect);
                } else {
                    $this->Session->setFlash('Invalid Username OR Password.', 'error');
                }
            }
        }


        if ($this->Auth->user('type') <> 'cashier') {
            // logout previous user
            $this->Auth->logout();
        }

        if ($this->Auth->loggedIn() || $this->Auth->login()) {
            return $this->redirect(array('controller' => 'homes', 'action' => 'dashboard'));
        }
    }

    public function logout() {
        $user = $this->Auth->user();
        $this->Session->setFlash(sprintf(__('%s you have successfully logged out'), $this->Auth->user('firstname')), 'success');
        $this->redirect($this->Auth->logout());
    }

    public function forgot_password() {
        if ($this->request->is('post')) {
            $this->loadModel("Cashier");
            $email_id = $this->request->data['Cashier']['email'];
            $cond = array(
                'Cashier.email' => $email_id,
            );
            $user = $this->Cashier->find('first', array(
                'conditions' => $cond,
            ));
            if (!empty($user)) {
                if ($user['Cashier']['status'] == "A") {
                    if ($user['Cashier']['is_verified'] == "Y") {
                        $password = rand(100000, 999999);
                        $password_md5 = Security::hash($password, 'md5');
                        $this->Cashier->updateAll(array('Cashier.password' => "'" . $password_md5 . "'"), array('Cashier.id' => $user['Cashier']['id']));
                        //send mail//
                        $Email = new CakeEmail();
                        $name = $user['Cashier']['firstname'] . " " . $user['Cashier']['lastname'];
                        $Email->from(WEBSITE_MAIL)
                                ->to($email_id)
                                ->subject('New Password')
                                ->template("forgotpassword")
                                ->emailFormat("html")
                                ->viewVars(array('email' => $email_id, 'password' => $password, 'name' => $name))
                                ->send();

                        //end
                        $this->Session->setFlash('New password has been sent to your registered email', 'success');
                        $this->redirect("forgot_password");
                    } else {
                        $this->Session->setFlash('Your account not verified,please contact to admin', 'error');
                        $this->redirect("forgot_password");
                    }
                } else {
                    $this->Session->setFlash('Your account is deactivated by admin,please contact to admin', 'error');
                    $this->redirect("forgot_password");
                }
            } else {
                $this->Session->setFlash('Sorry, this email not registered.', 'error');
            }
        }
    }

    public function dashboard() {

        // get all table details
        $this->loadModel('Cashier');
        $tables = $this->Cashier->find("first", array(
            'fields' => array('Admin.table_size', 'Admin.table_order', 'Admin.takeout_table_size', 'Admin.waiting_table_size', 'Admin.no_of_tables', 'Admin.no_of_waiting_tables', 'Admin.no_of_takeout_tables', 'Admin.id', 'Admin.kitchen_printer_device', 'Admin.service_printer_device'),
            'conditions' => array('Cashier.id' => $this->Session->read('Front.id'))
                )
        );
       
        //Modified by Yishou Liao @ Dec 12 2016
        $admin_passwd = $this->Cashier->query("SELECT admins.password FROM admins WHERE admins.is_super_admin='Y' ");
        //End @ Dec 12 2016
        
        // get table availability
        $this->loadModel('Order');
        $dinein_tables_status = $this->Order->find("list", array(
            'fields' => array('Order.table_no', 'Order.table_status'),
            'conditions' => array('Order.cashier_id' => $tables['Admin']['id'], 'Order.is_completed' => 'N', 'Order.order_type' => 'D')
                )
        );
        $takeway_tables_status = $this->Order->find("list", array(
            'fields' => array('Order.table_no', 'Order.table_status'),
            'conditions' => array('Order.cashier_id' => $tables['Admin']['id'], 'Order.is_completed' => 'N', 'Order.order_type' => 'T')
                )
        );
        $waiting_tables_status = $this->Order->find("list", array(
            'fields' => array('Order.table_no', 'Order.table_status'),
            'conditions' => array('Order.cashier_id' => $tables['Admin']['id'], 'Order.is_completed' => 'N', 'Order.order_type' => 'W')
                )
        );

        // get all order no.
        $orders_no = $this->Order->find("list", array(
            'fields' => array('Order.order_type', 'Order.order_no', 'Order.table_no'),
            'conditions' => array('Order.cashier_id' => $tables['Admin']['id'], 'Order.is_completed' => 'N'),
            'recursive' => false
                )
        );

        $orders_total = $this->Order->find("list", array(
            'fields' => array('Order.order_no', 'Order.total'),
            'conditions' => array('Order.cashier_id' => $tables['Admin']['id'], 'Order.is_completed' => 'N'),
            'recursive' => false
                )
        );

        // get all order times.
        $orders_time = $this->Order->find("list", array(
            'fields' => array('Order.order_type', 'Order.created', 'Order.table_no'),
            'conditions' => array('Order.cashier_id' => $tables['Admin']['id'], 'Order.is_completed' => 'N'),
            'recursive' => false
                )
        );
        $colors = array(
            'P' => 'greenwrap',
            'N' => 'notpaidwrap',
            'A' => 'availablebwrap',
            'V' => 'notpaidwrap',
        );

        // print_r($orders_total);

        $this->set(compact('tables', 'dinein_tables_status', 'takeway_tables_status', 'waiting_tables_status', 'colors', 'orders_no', 'orders_time', 'orders_total','admin_passwd'));
    }

    public function allorders() {

        // get all table details
        $this->loadModel('Cashier');
        $tables = $this->Cashier->find("first", array(
            'fields' => array('Admin.table_size', 'Admin.takeout_table_size', 'Admin.waiting_table_size', 'Admin.no_of_tables', 'Admin.id'),
            'conditions' => array('Cashier.id' => $this->Session->read('Front.id'))
                )
        );


        // get all orders
        $this->loadModel('Order');
        $this->loadModel('OrderItem');

        $this->OrderItem->virtualFields['category_name_en'] = "Select category_locales.name from category_locales where OrderItem.category_id = category_locales.category_id and category_locales.lang_code = 'en'";
        $this->OrderItem->virtualFields['category_name_zh'] = "Select category_locales.name from category_locales where OrderItem.category_id = category_locales.category_id and category_locales.lang_code = 'zh'";
        $orders = $this->Order->find("all", array(
            // 'fields'=>array('Order.table_no', 'Order.table_status'),
            'conditions' => array('Order.cashier_id' => $tables['Admin']['id'], 'Order.is_completed' => 'N'),
            'order' => 'Order.created asc'
                )
        );

        $final_orders = [];
        if (!empty($orders)) {
            foreach ($orders as $key => $value) {
                $categories = [];
                $items_array = [];

                # prepare order by category name
                if (!empty($value['OrderItem'])) {
                    foreach ($value['OrderItem'] as $item_key => $item) {
                        $items_array[$item['category_name_en'] . "|||" . $item['category_name_zh'] . "|||" . $item['category_id']][] = $item;
                    }
                }
                $value['Order']['order_created'] = $this->timeAgo(strtotime($value['Order']['created']));
                $final_orders[] = array(
                    'Order' => $value['Order'],
                    'categories' => $items_array
                );
            }
        }
        $this->set(compact('final_orders'));
    }

    public function cookings() {

        // get all table details
        $this->loadModel('Cashier');
        $tables = $this->Cashier->find("first", array(
            'fields' => array('Admin.table_size', 'Admin.takeout_table_size', 'Admin.waiting_table_size', 'Admin.no_of_tables', 'Admin.id', 'Admin.printer_ip', 'Admin.printer_device_id'),
            'conditions' => array('Cashier.id' => $this->Session->read('Front.id'))
                )
        );

        // get all orders
        $this->loadModel('Order');
        $this->loadModel('Category');
        $this->loadModel('OrderItem');

        $this->OrderItem->virtualFields['category_name_en'] = "Select category_locales.name from category_locales where OrderItem.category_id = category_locales.category_id and category_locales.lang_code = 'en'";
        $this->OrderItem->virtualFields['category_name_zh'] = "Select category_locales.name from category_locales where OrderItem.category_id = category_locales.category_id and category_locales.lang_code = 'zh'";

        $orders = $this->OrderItem->find("all", array(
            'fields' => array('Order.message', 'Order.table_no', 'Order.table_status', 'Order.order_type', 'Order.order_no', 'Order.created as order_created', 'OrderItem.*'),
            'conditions' => array(
                'Order.cashier_id' => $tables['Admin']['id'], 'Order.is_completed' => 'N', 'Order.is_kitchen' => 'Y'
            ),
                )
        );

        // categories all records
        $items_array = [];
        if (!empty($orders)) {
            foreach ($orders as $key => $value) {

                # prepare order by category name
                $item = $value['OrderItem'];
                $item['message'] = $value['Order']['message'];
                $item['table_no'] = $value['Order']['table_no'];
                $item['order_no'] = $value['Order']['order_no'];
                $item['order_type'] = $value['Order']['order_type'];
                $item['order_created'] = $value['Order']['order_created'];
                $item['time_ago'] = $this->timeAgo(strtotime($value['Order']['order_created']));

                $items_array[$item['category_name_en'] . "|||" . $item['category_name_zh'] . "|||" . $item['category_id']][$item['order_id']][] = $item;
            }
        }

        $type = @$this->params['named']['type'];

        // resort elemets array
        if ($type == 'finished') {
            foreach ($items_array as $key => $records) {
                # code...
                foreach ($records as $order_id => $value) {
                    $finished = 1;
                    foreach ($value as $item) {
                        if ($item['is_done'] == 'N')
                            $finished = 0;
                    }
                    if (!$finished) {
                        unset($items_array[$key][$order_id]);
                    }
                }
            }
        } else {
            foreach ($items_array as $key => $records) {
                # code...
                foreach ($records as $order_id => $value) {
                    $finished = 1;
                    foreach ($value as $item) {
                        if ($item['is_done'] == 'N')
                            $finished = 0;
                    }
                    if ($finished) {
                        unset($items_array[$key][$order_id]);
                    }
                }
            }
        }

        // unset category list
        foreach ($items_array as $key => $records) {
            # code...
            if (empty($records))
                unset($items_array[$key]);
        }
        $this->set(compact('items_array', 'type', 'tables'));
    }

    function doneitem() {
        $this->layout = false;
        $this->autoRender = NULL;

        // get all params
        $item_id = $this->data['item_id'];

        // check item if already done or not
        $this->loadModel('OrderItem');
        $this->loadModel('Order');
        $item_detail = $this->OrderItem->find("first", array(
            'fields' => array('OrderItem.is_done', 'OrderItem.order_id'),
            'conditions' => array('OrderItem.id' => $item_id),
                )
        );

        if ($item_detail['OrderItem']['is_done'] == 'Y') {
            $data['OrderItem']['is_done'] = 'N';
        } else {
            $data['OrderItem']['is_done'] = 'Y';
        }
        // save order to database        
        $data['OrderItem']['id'] = $item_id;
        $this->OrderItem->save($data, false);

        // check all order items is finished or not
        $order_status = $this->OrderItem->find("first", array(
            'fields' => array('count(OrderItem.id) as counter'),
            'conditions' => array('OrderItem.order_id' => $item_detail['OrderItem']['order_id'], 'OrderItem.is_done' => 'N'),
                )
        );
        if (!$order_status[0]['counter']) {

            $data_order['Order']['id'] = $item_detail['OrderItem']['order_id'];
            $data_order['Order']['cooking_status'] = 'COOKED';
            $this->Order->save($data_order, false);
        } else {

            $data_order['Order']['id'] = $item_detail['OrderItem']['order_id'];
            $data_order['Order']['cooking_status'] = 'UNCOOKED';
            $this->Order->save($data_order, false);
        }


        if ($data['OrderItem']['is_done'] == 'N')
            echo json_encode(array('done' => false));
        else
            echo json_encode(array('done' => true));
    }

    function doneallitem() {
        $this->layout = false;
        $this->autoRender = NULL;

        // get all params
        $item_ids = explode(",", $this->data['item_id']);

        // save order to database    
        $this->loadModel('OrderItem');
        foreach ($item_ids as $key => $value) {
            # code...
            $data['OrderItem']['id'] = $value;
            $data['OrderItem']['is_done'] = 'Y';
            $this->OrderItem->save($data, false);
        }


        $this->loadModel('Order');
        $item_detail = $this->OrderItem->find("first", array(
            'fields' => array('OrderItem.is_done', 'OrderItem.order_id'),
            'conditions' => array('OrderItem.id' => $item_ids[0]),
                )
        );

        // check all order items is finished or not
        $order_status = $this->OrderItem->find("first", array(
            'fields' => array('count(OrderItem.id) as counter'),
            'conditions' => array('OrderItem.order_id' => $item_detail['OrderItem']['order_id'], 'OrderItem.is_done' => 'N'),
                )
        );
        if (!$order_status[0]['counter']) {

            $data_order['Order']['id'] = $item_detail['OrderItem']['order_id'];
            $data_order['Order']['cooking_status'] = 'COOKED';
            $this->Order->save($data_order, false);
        } else {

            $data_order['Order']['id'] = $item_detail['OrderItem']['order_id'];
            $data_order['Order']['cooking_status'] = 'UNCOOKED';
            $this->Order->save($data_order, false);
        }




        echo true;
    }

    function recookallitem() {
        $this->layout = false;
        $this->autoRender = NULL;

        // get all params
        $item_ids = explode(",", $this->data['item_id']);

        // save order to database    
        $this->loadModel('OrderItem');
        foreach ($item_ids as $key => $value) {
            # code...
            $data['OrderItem']['id'] = $value;
            $data['OrderItem']['is_done'] = 'N';
            $this->OrderItem->save($data, false);
        }

        $this->loadModel('Order');
        $item_detail = $this->OrderItem->find("first", array(
            'fields' => array('OrderItem.is_done', 'OrderItem.order_id'),
            'conditions' => array('OrderItem.id' => $item_ids[0]),
                )
        );

        $data_order['Order']['id'] = $item_detail['OrderItem']['order_id'];
        $data_order['Order']['cooking_status'] = 'UNCOOKED';
        $this->Order->save($data_order, false);
        echo true;
    }


    public function tableHisdetail() {
        // get cashier details
        $this->loadModel('Cashier');
        $cashier_detail = $this->Cashier->find("first", array(
            'fields' => array('Cashier.firstname', 'Cashier.lastname', 'Cashier.id', 'Cashier.image', 'Admin.id'),
            'conditions' => array('Cashier.id' => $this->Session->read('Front.id'))
                )
        );

        $table_no = $this->params['named']['table_no'];
        $order_id = $this->params['named']['order_id'];
        
        $this->loadModel('Order');
        $this->loadModel('OrderItem');

        $conditions = array('Order.cashier_id' => $cashier_detail['Admin']['id'],
            'Order.id' => $order_id,
        	'Order.table_no' => $table_no,
            'Order.is_completed' => 'Y',
            'Order.order_type' => 'D',
            'Order.created >=' => date("Ymd")/* , strtotime("-2 weeks")) */
        );

        $Order_detail = $this->Order->find("first", array(
            'fields' => array('Order.paid', 'Order.tip', 'Order.cash_val', 'Order.card_val', 'Order.change', 'Order.order_no', 'Order.tax', 'Order.table_status', 'Order.tax_amount', 'Order.subtotal', 'Order.total', 'Order.message', 'Order.discount_value', 'Order.promocode', 'Order.fix_discount', 'Order.percent_discount', 'Order.created'),
            'conditions' => $conditions
                )
        );
        if (empty($Order_detail)) {
            $this->Session->setFlash('Sorry, there is no table history for today.', 'error');
            return $this->redirect(array('controller' => 'homes', 'action' => 'dashboard'));
        }

        $today = date('Y-m-d H:i', strtotime($Order_detail['Order']['created']));

        $this->set(compact('Order_detail', 'cashier_detail', 'table_no', 'order_id', 'today'));
    }

    public function tableHisupdate() {
        $this->layout = false;
        $this->autoRender = NULL;
    	
    	// get cashier details
        $this->loadModel('Cashier');
        $cashier_detail = $this->Cashier->find("first", array(
            'fields' => array('Cashier.firstname', 'Cashier.lastname', 'Cashier.id', 'Cashier.image', 'Admin.id'),
            'conditions' => array('Cashier.id' => $this->Session->read('Front.id'))
                )
        );

        $table_no = $this->data['table_no'];
        $order_id = $this->data['order_id'];
        
        $this->loadModel('Order');
        $this->loadModel('Log');

        $conditions = array('Order.cashier_id' => $cashier_detail['Admin']['id'],
            'Order.id' => $order_id,
        	'Order.table_no' => $table_no,
            'Order.is_completed' => 'Y',
            'Order.order_type' => 'D',
            'Order.created >=' => date("Ymd")/* , strtotime("-2 weeks")) */
        );

        $Order_detail = $this->Order->find("first", array(
            'fields' => array('Order.paid', 'Order.tip', 'Order.cash_val', 'Order.card_val', 'Order.change', 'Order.order_no', 'Order.tax', 'Order.table_status', 'Order.tax_amount', 'Order.subtotal', 'Order.total', 'Order.message', 'Order.discount_value', 'Order.promocode', 'Order.fix_discount', 'Order.percent_discount', 'Order.created'),
            'conditions' => $conditions
                )
        );
        if (empty($Order_detail)) {
            $this->Session->setFlash('Sorry, there is no table history for today.', 'error');
            return $this->redirect(array('controller' => 'homes', 'action' => 'dashboard'));
        }

        $subtotal = $this->data['subtotal'];
        $discount_value = $this->data['discount_value'];
        $total = $this->data['total'];
        $paid = $this->data['paid'];
        $cash_val = $this->data['cash_val'];
        $card_val = $this->data['card_val'];
        $change = $this->data['change'];
        $tip = $this->data['tip'];
        
        $logs = '';
        $data = array();
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
        	$logArr = array('cashier_id' => $cashier_detail['Cashier']['id'], 'admin_id' => $cashier_detail['Admin']['id'], 'logs' => $logs);
        	$this->Log->save($logArr);
        	$data['id'] = $order_id;
        	$this->Order->save($data);
        }
        
        $r['status'] = 'OK';
        $r['url'] = Router::url(array('controller' => 'homes', 'action' => 'tableHistory',  'table_no' => $table_no, 'order_id' => $order_id));
        echo json_encode($r);
    }

    public function tableHistory() {
        // get cashier details
        $this->loadModel('Cashier');
        $cashier_detail = $this->Cashier->find("first", array(
            'fields' => array('Cashier.firstname', 'Cashier.lastname', 'Cashier.id', 'Cashier.image', 'Admin.id'),
            'conditions' => array('Cashier.id' => $this->Session->read('Front.id'))
                )
        );

        $table_no = $this->params['named']['table_no'];

        $this->loadModel('Order');
        $this->loadModel('OrderItem');

        $conditions = array('Order.cashier_id' => $cashier_detail['Admin']['id'],
            'Order.table_no' => $table_no,
            'Order.is_completed' => 'Y',
            'Order.order_type' => 'D',
            'Order.created >=' => date("Ymd")/* , strtotime("-2 weeks")) */
        );

        $Order_detail = $this->Order->find("all", array(
            'fields' => array('Order.paid', 'Order.tip', 'Order.cash_val', 'Order.card_val', 'Order.change', 'Order.order_no', 'Order.tax', 'Order.table_status', 'Order.tax_amount', 'Order.subtotal', 'Order.total', 'Order.message', 'Order.discount_value', 'Order.promocode', 'Order.fix_discount', 'Order.percent_discount', 'Order.created'),
            'conditions' => $conditions
                )
        );
        if (empty($Order_detail)) {
            $this->Session->setFlash('Sorry, there is no table history for today.', 'error');
            return $this->redirect(array('controller' => 'homes', 'action' => 'dashboard'));
        }

        $this->paginate = array(
            'fields' => array('Order.paid', 'Order.tip', 'Order.cash_val', 'Order.card_val', 'Order.change', 'Order.order_no', 'Order.tax', 'Order.table_status', 'Order.tax_amount', 'Order.subtotal', 'Order.total', 'Order.message', 'Order.discount_value', 'Order.promocode', 'Order.fix_discount', 'Order.percent_discount', 'Order.created'),
            'conditions' => $conditions,
            'limit' => 10,
            'order' => array('Order.created' => 'desc')
        );

        $Order_detail = $this->paginate('Order');
        $today = date('Y-m-d H:i', strtotime($Order_detail[0]['Order']['created']));

        $this->set(compact('Order_detail', 'cashier_detail', 'table_no', 'today'));
    }


    public function makeavailable() {
        $this->layout = false;
        $this->autoRender = NULL;

        // get all params
        $order_no = $this->params['named']['order'];

        $this->loadModel('Order');
        $this->Order->updateAll(array('is_completed' => "'Y'"), array('Order.order_no' => $order_no));

        // save all 
        $this->Session->setFlash('Table successfully marked as available 成功清空本桌.', 'success');
        return $this->redirect(array('controller' => 'homes', 'action' => 'dashboard'));
    }

    public function move_order() {

        $this->layout = false;
        $this->autoRender = NULL;

        // get all params
        $type = @$this->params['named']['type'];
        $table = @$this->params['named']['table'];
        $order_no = @$this->params['named']['order_no'];
        $ref = @$this->params['named']['ref'];

        // update order to database 
        $this->loadModel('Order');
        $this->Order->updateAll(array('table_no' => $table, 'order_type' => "'" . $type . "'"), array('Order.order_no' => $order_no));
        $this->Session->setFlash('Order table successfully changed 成功换桌.', 'success');
        if ($ref)
            return $this->redirect(array('controller' => 'pay', 'action' => 'index', 'table' => $table, 'type' => $type));
        else
            return $this->redirect(array('controller' => 'homes', 'action' => 'dashboard'));
    }

    public function inquiry() {
        
    }



 


    public function updateordermessage() {

        // get all params
        $order_id = $this->data['order_id'];
        $message = $this->data['message'];
        $is_kitchen = $this->data['is_kitchen'];

        //Modified by Yishou Liao @ Oct 27 2016.
        $table = $this->data['table'];
        $type = $this->data['type'];
        //End.

        $this->layout = false;
        $this->autoRender = NULL;

        // update message in order table
        $this->loadModel('Order');
        $data = array();
        $data['Order']['id'] = $order_id;
        $data['Order']['message'] = $message;
        $data['Order']['is_kitchen'] = $is_kitchen;
        $data['Order']['is_print'] = 'Y';
        $this->Order->save($data, false);

        if ($is_kitchen == 'Y')
            $this->Session->setFlash('Cooking items successfully sent to kitchen.', 'success');

        //Modified by Yishou Liao @ Oct 27 2016.
        $this->loadModel('Cashier');
        $cashier_detail = $this->Cashier->find("first", array(
            'fields' => array('Cashier.firstname', 'Cashier.lastname', 'Cashier.id', 'Cashier.image', 'Admin.id'),
            'conditions' => array('Cashier.id' => $this->Session->read('Front.id'))
                )
        );

        $this->Order->query("UPDATE order_items,orders SET order_items.is_print = 'Y' WHERE orders.id = order_items.order_id and orders.cashier_id = " . $cashier_detail['Admin']['id'] . " AND  orders.table_no = " . $table . " AND order_items.is_print = 'N' AND orders.is_completed = 'N' AND orders.order_type = '" . $type . "' ");

        //End.

        echo true;
    }
    

    // check total value #As in Canada, the price is keeping using $9.89, but we don’t have $0.01 now, any amount smaller than 0.02 will be round to 0, more than 0.03 will be round to 0.05.
    function convertoround($amount) {
        $this->layout = false;
        $this->autoRender = false;
        $amount_array = explode(".", $amount);
        if (@$amount_array[1][1] < 3) {
            $afterdot = @$amount_array[1][0] . '0';
        } else if (@$amount_array[1][1] >= 3) {
            $afterdot = @$amount_array[1][0] . '5';
        }
        if ($afterdot) {
            $amount = $amount_array[0] . '.' . $afterdot;
        }
        return $amount;
    }

    //Modified by Jack @2017-01-05
    public function printTodayOrders($print_zh = false) {
        // $this->loadModel('Cashier');
        $this->loadModel('Order');
        $this->loadModel('OrderItem');
    	if (empty($this->data['Printer'])) {
    		die("No Printer");
    	}

		date_default_timezone_set("America/Toronto");
		$date_time = date("l M d Y h:i:s A");
		$timeline = strtotime(date("Y-m-d 11:00:00")); 
		$nowtm = time();
		if ($timeline > $nowtm) {
			// before 11 am
			$timeline -= 86400;
		}
		$tm11 = $timeline;
		$timeline += 3600 * 6;
		$tm17 = $timeline;
		$timeline += 3600 * 6;
		$tm23 = $timeline;
		$timeline += 3600 * 5;
		$tm04 = $timeline;

        $dailyAmount = $this->Order->getDailyOrderInfo(array($tm11, $tm17, $tm23, $tm04));
        $dailyItems = $this->OrderItem->getDailyItemCount(array($tm11, $tm04));

        print_r($dailyItems);

    	$Printer = $this->data['Printer'];
        $printer_name = $Printer['C'];
		$handle = '';
		if ($printer_name) {
			$handle = printer_open($printer_name);
		} else {
			die("Can't open printer");
		}
		if ($handle) {
			printer_start_doc($handle, "All Orders");
			printer_start_page($handle);
			
			if ($print_zh == true) {
				$font = printer_create_font($this->fontStr1, 42, 18, PRINTER_FW_BOLD, false, false, false, 0);
				printer_select_font($handle, $font);
				printer_draw_text($handle, iconv("UTF-8", "gb2312", "All Orders (总单)"), 108, 20);
				$font = printer_create_font($this->fontStr1, 32, 14, PRINTER_FW_MEDIUM, false, false, false, 0);
			} else {
				$font = printer_create_font("Arial", 42, 18, PRINTER_FW_MEDIUM, false, false, false, 0);
				printer_select_font($handle, $font);
				printer_draw_text($handle, "All Orders", 138, 20);
				$font = printer_create_font("Arial", 32, 14, PRINTER_FW_MEDIUM, false, false, false, 0);
			};
			
			//Print order information
			printer_select_font($handle, $font);
			$c1 = 30;
/*
			$c2 = 230;
			$c3 = 350;
			if ($print_zh == true) {
				$orderNstr = iconv("UTF-8", "gb2312", "订单号");
				$paybystr = iconv("UTF-8", "gb2312", "类型");
				$toralStr = iconv("UTF-8", "gb2312", "总计");
			} else {
				$orderNstr = "Order #";
				$paybystr = "Pay By";
				$toralStr = "Total";
			}
			
			printer_draw_text($handle, $orderNstr, $c1, 80);
			printer_draw_text($handle, $paybystr, $c2, 80);
			printer_draw_text($handle, $toralStr, $c3, 80);
				
*/
			$pen = printer_create_pen(PRINTER_PEN_SOLID, 2, "000000");
			printer_select_pen($handle, $pen);
			//printer_draw_line($handle, 21, 160, 600, 160);
			//Print orders
			//$cashierArr = array();
			$print_y = 120;
            // print_r($dailyAmount);
			foreach ($dailyAmount as $spanAmount) {
                // print time title

				printer_draw_text($handle, date("Y-m-d H:i", $spanAmount['start_time']) . " - " .date("Y-m-d H:i", $spanAmount['end_time']), $c1, $print_y);
				$print_y+=32;

				printer_draw_line($handle, 21, $print_y, 600, $print_y);
				$print_y+=32;
				
				if ($spanAmount['real_total'] > 0) {
					$paid_cash_percent = " " . number_format($spanAmount['paid_cash_total'] * 100 / $spanAmount['real_total'], 2) . '%';
					$paid_card_percent = " " . number_format($spanAmount['paid_card_total'] * 100 / $spanAmount['real_total'], 2) . '%';
				} else {
					$paid_cash_percent = "-";
					$paid_card_percent = "-";
				}
				if ($print_zh == true) {
					printer_draw_text($handle, iconv("UTF-8", "gb2312", '税额 : ') . sprintf('%0.2f', $spanAmount['tax']), 32, $print_y); $print_y+=32;
					//printer_draw_text($handle, iconv("UTF-8", "gb2312", '现金额 : ') . sprintf('%0.2f', $totalArr['cash_total']), 32, $print_y); $print_y+=32;
					//printer_draw_text($handle, iconv("UTF-8", "gb2312", '卡类额 : ') . sprintf('%0.2f', $totalArr['card_total']), 32, $print_y); $print_y+=32;
					//printer_draw_text($handle, iconv("UTF-8", "gb2312", '混和现金额 : ') . sprintf('%0.2f', $totalArr['cash_mix_total']), 32, $print_y); $print_y+=32;
					//printer_draw_text($handle, iconv("UTF-8", "gb2312", '混和卡类额 : ') . sprintf('%0.2f', $totalArr['card_mix_total']), 32, $print_y); $print_y+=32;
					printer_draw_text($handle, iconv("UTF-8", "gb2312", '总计 : ') . sprintf('%0.2f', $spanAmount['total']) . " ( " . $spanAmount['order_num'] . iconv("UTF-8", "gb2312", " 单 ) "), 32, $print_y); $print_y+=32;
					printer_draw_text($handle, iconv("UTF-8", "gb2312", '实收总计 : ') . sprintf('%0.2f', $spanAmount['real_total']), 32, $print_y); $print_y+=32;
					printer_draw_text($handle, iconv("UTF-8", "gb2312", '实收现金 : ') . sprintf('%0.2f', $spanAmount['paid_cash_total']) . " ( " . $paid_cash_percent . " ) ", 32, $print_y); $print_y+=32;
					printer_draw_text($handle, iconv("UTF-8", "gb2312", '实收卡类 : ') . sprintf('%0.2f', $spanAmount['paid_card_total']) . " ( " . $paid_card_percent . " ) ", 32, $print_y); $print_y+=32;
					//$print_y+=16;
					//printer_draw_text($handle, iconv("UTF-8", "gb2312", '现金小费总计 : ') . sprintf('%0.2f', $totalArr['cash_tip_total']), 32, $print_y); $print_y+=32;
					//printer_draw_text($handle, iconv("UTF-8", "gb2312", '卡类小费总计 : ') . sprintf('%0.2f', $totalArr['card_tip_total']), 32, $print_y); $print_y+=32;
					//printer_draw_text($handle, iconv("UTF-8", "gb2312", '混和小费总计 : ') . sprintf('%0.2f', $totalArr['mix_tip_total']), 32, $print_y); $print_y+=32;
					//printer_draw_text($handle, iconv("UTF-8", "gb2312", '小费总计 : ') . sprintf('%0.2f', $totalArr['total_tip']), 32, $print_y); $print_y+=32;
				/*		
					$print_y+=16;
					foreach ($cashierArr as $key => $cs) {
						$print_y+=16;
						printer_draw_line($handle, 21, $print_y, 600, $print_y);
						$print_y+=16;
						printer_draw_text($handle, iconv("UTF-8", "gb2312", '收银台 ') . $key, 32, $print_y); $print_y+=32;
						$print_y+=8;
						printer_draw_line($handle, 21, $print_y, 600, $print_y);
						$print_y+=8;
						printer_draw_text($handle, iconv("UTF-8", "gb2312", '现金额 : ') . sprintf('%0.2f', $cs['cash_total']), 32, $print_y); $print_y+=32;
						printer_draw_text($handle, iconv("UTF-8", "gb2312", '卡类额 : ') . sprintf('%0.2f', $cs['card_total']), 32, $print_y); $print_y+=32;
						printer_draw_text($handle, iconv("UTF-8", "gb2312", '混和现金额 : ') . sprintf('%0.2f', $cs['cash_mix_total']), 32, $print_y); $print_y+=32;
						printer_draw_text($handle, iconv("UTF-8", "gb2312", '混和卡类额 : ') . sprintf('%0.2f', $cs['card_mix_total']), 32, $print_y); $print_y+=32;
						printer_draw_text($handle, iconv("UTF-8", "gb2312", '总计 : ') . sprintf('%0.2f', $cs['total']), 32, $print_y); $print_y+=32;
						$print_y+=16;
						printer_draw_text($handle, iconv("UTF-8", "gb2312", '现金小费总计 : ') . sprintf('%0.2f', $cs['cash_tip_total']), 32, $print_y); $print_y+=32;
						printer_draw_text($handle, iconv("UTF-8", "gb2312", '卡类小费总计 : ') . sprintf('%0.2f', $cs['card_tip_total']), 32, $print_y); $print_y+=32;
						printer_draw_text($handle, iconv("UTF-8", "gb2312", '混和小费总计 : ') . sprintf('%0.2f', $cs['mix_tip_total']), 32, $print_y); $print_y+=32;
						printer_draw_text($handle, iconv("UTF-8", "gb2312", '小费总计 : ') . sprintf('%0.2f', $cs['total_tip']), 32, $print_y); $print_y+=32;
					}
				*/
				} else {
					printer_draw_text($handle, 'TAX Total : ' . sprintf('%0.2f', $spanAmount['tax']), 32, $print_y); $print_y+=32;
					//printer_draw_text($handle, 'Cash Total : ' . sprintf('%0.2f', $totalArr['cash_total']), 32, $print_y); $print_y+=32;
					//printer_draw_text($handle, 'Card Total : ' . sprintf('%0.2f', $totalArr['card_total']), 32, $print_y); $print_y+=32;
					//printer_draw_text($handle, 'Mix Cash Total : ' . sprintf('%0.2f', $totalArr['cash_mix_total']), 32, $print_y); $print_y+=32;
					//printer_draw_text($handle, 'Mix Card Total : ' . sprintf('%0.2f', $totalArr['card_mix_total']), 32, $print_y); $print_y+=32;
					printer_draw_text($handle, 'Total : ' . sprintf('%0.2f', $spanAmount['total']) . " ( " . sizeof($Orders) . " sales ) ", 32, $print_y); $print_y+=32;
					printer_draw_text($handle, 'Paid Total : ' . sprintf('%0.2f', $spanAmount['real_total']), 32, $print_y); $print_y+=32;
					printer_draw_text($handle, 'Paid Cash Total : ' . sprintf('%0.2f', $spanAmount['paid_cash_total']) . " ( " . $paid_cash_percent . " ) ", 32, $print_y); $print_y+=32;
					printer_draw_text($handle, 'Paid Card Total : ' . sprintf('%0.2f', $spanAmount['paid_card_total']) . " ( " . $paid_card_percent . " ) ", 32, $print_y); $print_y+=32;
					//$print_y+=16;
					//printer_draw_text($handle, 'Cash Tip Total : ' . sprintf('%0.2f', $totalArr['cash_tip_total']), 32, $print_y); $print_y+=32;
					//printer_draw_text($handle, 'Card Tip Total : ' . sprintf('%0.2f', $totalArr['card_tip_total']), 32, $print_y); $print_y+=32;
					//printer_draw_text($handle, 'Mix Tip Total : ' . sprintf('%0.2f', $totalArr['mix_tip_total']), 32, $print_y); $print_y+=32;
					//printer_draw_text($handle, 'Tip Total : ' . sprintf('%0.2f', $totalArr['total_tip']), 32, $print_y); $print_y+=32;
						
				/*		
					$print_y+=16;
					foreach ($cashierArr as $key => $cs) {
						$print_y+=16;
						printer_draw_line($handle, 21, $print_y, 600, $print_y);
						$print_y+=16;
						printer_draw_text($handle, 'Cashier ' . $key, 32, $print_y); $print_y+=32;
						$print_y+=8;
						printer_draw_line($handle, 21, $print_y, 600, $print_y);
						$print_y+=8;
						printer_draw_text($handle, 'Cash Total : ' . sprintf('%0.2f', $cs['cash_total']), 32, $print_y); $print_y+=32;
						printer_draw_text($handle, 'Card Total : ' . sprintf('%0.2f', $cs['card_total']), 32, $print_y); $print_y+=32;
						printer_draw_text($handle, 'Mix Cash Total : ' . sprintf('%0.2f', $cs['cash_mix_total']), 32, $print_y); $print_y+=32;
						printer_draw_text($handle, 'Mix Card Total : ' . sprintf('%0.2f', $cs['card_mix_total']), 32, $print_y); $print_y+=32;
						printer_draw_text($handle, 'Total : ' . sprintf('%0.2f', $cs['total']), 32, $print_y); $print_y+=32;
					}
				*/
				}
				$print_y+=60;
			}

            printer_end_page($handle);

            printer_start_page($handle);
            // print title 
            $y = 10;
            $pen = printer_create_pen(PRINTER_PEN_SOLID, 2, "000000");
            printer_select_pen($handle, $pen);
            printer_draw_line($handle, 21, $y, 600, $y);
            $y += 10;
            $font = printer_create_font($this->fontStr1, 42, 18, PRINTER_FW_BOLD, false, false, false, 0);
            printer_select_font($handle, $font);
            printer_draw_text($handle, iconv("UTF-8", "gb2312", "Sales Statistics 销量统计"), 30, $y);
            $font = printer_create_font($this->fontStr1, 32, 14, PRINTER_FW_MEDIUM, false, false, false, 0);
            printer_select_font($handle, $font);


            printer_end_page($handle);

            foreach($dailyItems as $spanItems) {
                foreach($spanItems['items'] as $item) {
                    printer_start_page($handle);
                    if ($print_zh) {
                        printer_draw_text($handle, iconv("UTF-8", "gb2312", $item['OrderItem']['name_xh']) , 32, 0);
                        printer_draw_text($handle, iconv("UTF-8", "gb2312", "总共: " . $item['OrderItem']['item_id_count']) , 300, 0);
                    } else {
                        printer_draw_text($handle, iconv("UTF-8", "gb2312", $item['OrderItem']['name_en']) , 32, 0);
                        printer_draw_text($handle, iconv("UTF-8", "gb2312", "Count" . $item['OrderItem']['item_id_count']) , 32, 0);
                    }
                    printer_end_page($handle);
                }
                
            }


			printer_delete_font($font);
            printer_end_doc($handle);
			printer_close($handle);
		};
		exit;
	}

   

    

    //End
}
