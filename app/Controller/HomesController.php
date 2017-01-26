<?php

/**
 * Class HomesController
 * Note*- here cashier id is related to the restaurant id
 */
App::uses('PrintLib', 'Lib');
class HomesController extends AppController {



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

    public function order() {
        // get all recepie items according to category
        $this->loadModel('Category');
        $this->loadModel('Cousine');

        // get cashier details        
        $this->loadModel('Cashier');
        $cashier_detail = $this->Cashier->find("first", array(
            'fields' => array('Cashier.firstname', 'Cashier.lastname', 'Cashier.id', 'Cashier.image', 'Cashier.restaurant_id', 'Admin.id','Admin.kitchen_printer_device','Admin.service_printer_device'),
            'conditions' => array('Cashier.id' => $this->Session->read('Front.id')),
                )
        );

        $this->Category->bindModel(
                array(
                    'hasMany' => array(
                        'Cousine' => array(
                            'className' => 'Cousine',
                            'foreignKey' => 'category_id',
                            'conditions' => array('Cousine.status' => 'A', 'Cousine.restaurant_id' => $cashier_detail['Cashier']['restaurant_id']),
                        )
                    )
                )
        );
        $this->Cousine->virtualFields['eng_name'] = "Select name from cousine_locals where cousine_locals.parent_id = Cousine.id and lang_code = 'en'";
        $this->Cousine->virtualFields['zh_name'] = "Select name from cousine_locals where cousine_locals.parent_id = Cousine.id and lang_code = 'zh'";

        $this->Category->virtualFields['eng_name'] = "Select name from category_locales where category_locales.category_id = Category.id and lang_code = 'en'";
        $this->Category->virtualFields['zh_name'] = "Select name from category_locales where category_locales.category_id = Category.id and lang_code = 'zh'";

        $records = $this->Category->find("all", array(
            // 'fields'=>array('Admin.table_size', 'Admin.takeout_table_size', 'Admin.waiting_table_size'),
            'conditions' => array('Category.status' => 'A')
                )
        );

        // get popular dishes
        $this->loadModel('Cousine');
        $this->Cousine->virtualFields['eng_name'] = "Select name from cousine_locals where cousine_locals.parent_id = Cousine.id and lang_code = 'en'";
        $this->Cousine->virtualFields['zh_name'] = "Select name from cousine_locals where cousine_locals.parent_id = Cousine.id and lang_code = 'zh'";
        $populars = $this->Cousine->find("all", array(
            'conditions' => array('Cousine.status' => 'A', 'Cousine.restaurant_id' => $cashier_detail['Cashier']['restaurant_id']),
            'order' => 'Cousine.popular DESC',
            'recursive' => false,
            'limit' => 30
                )
        );

        $type = $this->params['named']['type'];
        $table = $this->params['named']['table'];

        // get order detail
        $this->loadModel('Order');
        $conditions = array('Order.cashier_id' => $cashier_detail['Admin']['id'],
            'Order.table_no' => $table,
            'Order.is_completed' => 'N',
            'Order.order_type' => $type
        );
        $Order_detail = $this->Order->find("first", array(
            'fields' => array('Order.order_no', 'Order.order_type', 'Order.id'),
            'conditions' => $conditions,
            'recursive' => false
                )
        );

        $this->loadModel('Extra');
        $taste = $this->Extra->find('all', array(
                'conditions'=> array('Extra.category_id' => 1)
            ));


        //Modified by Yishou Liao @ Dec 09 2016
        //$order_id = $this->Order->find('all',array('fields' => 'Max(id) as max_id'));
        //$order_no = str_pad(($order_id[0][0]['max_id']+1), 5, rand(98753, 87563), STR_PAD_LEFT);
        //End @ Dec 09 2016
        
        $query_str = "SELECT extras.* FROM `extras`";

        $all_extras = $this->Extra->query($query_str);
        $extras = array();
        foreach ($all_extras as $extra){
            array_push($extras, $extra['extras']);
        }

        $query_str = "SELECT extrascategories.* FROM extrascategories WHERE extrascategories.status = 'A'";
        $all_extra_categories = $this->Extra->query($query_str);
        $extra_categories= array();
        foreach ($all_extra_categories as $category){
            array_push($extra_categories, $category['extrascategories']);
        }


        if (!empty($Order_detail['Order']['id'])) {
            $this->Order->updateBillInfo($Order_detail['Order']['id']);
        }
        // $this->Order->updateBillInfo($)
        // print_r ($Order_detail);
        // print_r($all_extras);
        $this->set(compact('records', 'cashier_detail', 'table', 'type', 'populars', 'Order_detail', 'extras', 'extra_categories'));

        // print_r($tastes);
    }

    public function pay() {

        // get cashier details        
        $this->loadModel('Cashier');
        $cashier_detail = $this->Cashier->find("first", array(
            'fields' => array('Cashier.firstname', 'Cashier.lastname', 'Cashier.id', 'Cashier.image', 'Admin.id','Admin.kitchen_printer_device','Admin.service_printer_device'),
            'conditions' => array('Cashier.id' => $this->Session->read('Front.id'))
                )
        );

        $order_no = @$this->params['url']['order_no'];

        // get all params
        $type = @$this->params['named']['type'];
        $table = @$this->params['named']['table'];

        if ($order_no) {
            $conditions = array('Order.cashier_id' => $cashier_detail['Admin']['id'],
                'Order.order_no' => $order_no
            );
        } else {
            $conditions = array('Order.cashier_id' => $cashier_detail['Admin']['id'],
                'Order.table_no' => $table,
                'Order.is_completed' => 'N',
                'Order.order_type' => $type
            );
        }

        // get order details 
        $this->loadModel('Order');
        $this->loadModel('OrderItem');

        $this->OrderItem->virtualFields['image'] = "Select image from cousines where cousines.id = OrderItem.item_id";
        $Order_detail = $this->Order->find("first", array(
            'fields' => array('Order.paid', 'Order.tip', 'Order.cash_val', 'Order.card_val', 'Order.change', 'Order.order_no', 'Order.tax', 'Order.table_status', 'Order.tax_amount', 'Order.subtotal', 'Order.after_discount','Order.total', 'Order.message', 'Order.discount_value', 'Order.promocode', 'Order.fix_discount', 'Order.percent_discount'),
            'conditions' => $conditions
                )
        );
        if (empty($Order_detail)) {
            $this->Session->setFlash('Sorry, order does not exist 抱歉，订单不存在。.', 'error');
            return $this->redirect(array('controller' => 'homes', 'action' => 'dashboard'));
        }
        $type = @$Order_detail['Order']['order_type'] ? @$Order_detail['Order']['order_type'] : $type;
        $table = @$Order_detail['Order']['table_no'] ? @$Order_detail['Order']['table_no'] : $table;

        // get all order no.
        $orders_no = $this->Order->find("list", array(
            'fields' => array('Order.order_type', 'Order.order_no', 'Order.table_no'),
            'conditions' => array('Order.cashier_id' => $cashier_detail['Admin']['id'], 'Order.is_completed' => 'N'),
            'recursive' => false
                )
        );

        $this->set(compact('Order_detail', 'cashier_detail', 'type', 'table', 'orders_no'));
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
            return $this->redirect(array('controller' => 'homes', 'action' => 'pay', 'table' => $table, 'type' => $type));
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



    // add discount function
    public function add_discount() {
        $this->layout = false;
        $this->autoRender = NULL;

        $this->loadModel('Order');
        //Modified by Yishou Liao @ Nov 19 2016
        $discount_type = -1;
        $discount_value = -1;
        //End
        // get all params
        // $order_id = $this->data['order_id'];
        // $order_no = $this->data['order_no'];
        // $order_id = $this->Order->getOrderIdByOrderNo($order_no);
        $order_id = $this->data['order_id'];
        $mainorder_id = isset($this->data['mainorder_id']) ? $this->data['mainorder_id'] : $order_id;
        $fix_discount = $this->data['fix_discount'];
        $percent_discount = $this->data['discount_percent'];
        $promocode = $this->data['promocode'];

        //Modified by Yishou Liao @ Nov 18 2016
        if (!empty($fix_discount)) {
            $order_id_tmp = explode(",", $order_id);
            $order_id_arr = array($mainorder_id);
        } else {
            $order_id_arr = explode(",", $order_id);
        };

        for ($i = 0; $i < count($order_id_arr); $i++) {
            $order_id = $order_id_arr[$i];
            //End
            // get order details  
            
            $Order_detail = $this->Order->find("first", array(
                'fields' => array('Order.order_no', 'Order.tax', 'Order.tax_amount', 'Order.subtotal', 'Order.total', 'Order.message', 'Order.discount_value', 'Order.promocode', 'Order.fix_discount', 'Order.percent_discount'),
                'conditions' => array(
                    'Order.id' => $order_id,
                )
                    )
            );

            $data = array();
            $data['Order']['id'] = $order_id;

            // check discount is applicable or not
            if ($fix_discount) {
                if ($Order_detail['Order']['total'] < $fix_discount) {
                    $response = array(
                        'error' => true,
                        'message' => 'Please add valid discount'
                    );
                } else {

                    $data['Order']['discount_value'] = $fix_discount;
                    $data['Order']['fix_discount'] = $fix_discount;
                    $data['Order']['percent_discount'] = 0;
                    $data['Order']['promocode'] = "";
                    //Modified by Yishou Liao @ Nov 18 2016
                    //$data['Order']['total'] = $Order_detail['Order']['total'] - $data['Order']['discount_value'];
                    $data['Order']['subtotal'] = $Order_detail['Order']['subtotal'] - $data['Order']['discount_value'];
                    $data['Order']['tax_amount'] = $data['Order']['subtotal'] * $Order_detail['Order']['tax'] / 100;
                    $data['Order']['total'] = $data['Order']['subtotal'] + $data['Order']['tax_amount'];
                    //End

                    $this->Order->save($data, false);
                    $response = array(
                        'error' => false,
                        'message' => 'Discount successfully applied',
                        'discount_type' => $discount_type,
                        'discount_value' => $discount_value
                    );
                }
            } else if ($percent_discount) {
                if ($percent_discount > 100) {
                    $response = array(
                        'error' => true,
                        'message' => 'Please add valid discount'
                    );
                } else {

                    $data['Order']['discount_value'] = $Order_detail['Order']['subtotal'] * $percent_discount / 100;
                    $data['Order']['percent_discount'] = $percent_discount;
                    $data['Order']['fix_discount'] = 0;
                    $data['Order']['promocode'] = "";
                    //Modified by Yishou Liao @ Nov 18 2016
                    //$data['Order']['total'] = $Order_detail['Order']['total'] - $data['Order']['discount_value'];
                    $data['Order']['subtotal'] = $Order_detail['Order']['subtotal'] - $data['Order']['discount_value'];
                    $data['Order']['tax_amount'] = $data['Order']['subtotal'] * $Order_detail['Order']['tax'] / 100;
                    $data['Order']['total'] = $data['Order']['subtotal'] + $data['Order']['tax_amount'];
                    //End

                    $this->Order->save($data, false);
                    $response = array(
                        'error' => false,
                        'message' => 'Discount successfully applied',
                        'discount_type' => $discount_type,
                        'discount_value' => $discount_value
                    );
                }
            } else if ($promocode <> "") {
                // check promocode valid or not here
                $this->loadModel('Promocode');
                $promo_detail = $this->Promocode->find("first", array(
                    'conditions' => array(
                        'Promocode.code' => $promocode,
                    )
                        )
                );


                if (empty($promo_detail)) {
                    $response = array(
                        'error' => true,
                        'message' => 'Promocode does not exist.'
                    );
                } else {
                    // check promocode dates
                    if (!(time() >= strtotime($promo_detail['Promocode']['valid_from']) and time() <= strtotime($promo_detail['Promocode']['valid_to']))) {
                        $response = array(
                            'error' => true,
                            'message' => 'Sorry, promo code is expired'
                        );
                    } else {
                        //Modified by Yishou Liao @ Nov 19 2016
                        $discount_type = $promo_detail['Promocode']['discount_type'];
                        $discount_value = $promo_detail['Promocode']['discount_value'];
                        //End
                        // get promocode discount and validate here
                        if ($promo_detail['Promocode']['discount_type'] == 1) {
                            // calculate percentage here
                            $data['Order']['discount_value'] = $Order_detail['Order']['subtotal'] * $promo_detail['Promocode']['discount_value'] / 100;
                            $data['Order']['percent_discount'] = $promo_detail['Promocode']['discount_value'];
                            $data['Order']['fix_discount'] = 0;
                            //Modified by Yishou Liao @ Nov 18 2016
                            //$data['Order']['total'] = $Order_detail['Order']['total'] - $data['Order']['discount_value'];
                            $data['Order']['subtotal'] = $Order_detail['Order']['subtotal'] - $data['Order']['discount_value'];
                            $data['Order']['tax_amount'] = $data['Order']['subtotal'] * $Order_detail['Order']['tax'] / 100;
                            $data['Order']['total'] = $data['Order']['subtotal'] + $data['Order']['tax_amount'];
                            //End
                        } else {
                            // calculate fix discount here
                            //Modified by Yishou Liao @ Nov 18 2016 (The goal is only discount for main table.)
                            if ($order_id == $mainorder_id) {
                                $discount_val = $promo_detail['Promocode']['discount_value'];
                                //if ($Order_detail['Order']['total'] < $discount_val) {
                                if ($Order_detail['Order']['subtotal'] < $discount_val) {//Modified by Yishou Liao @ Nov 18 2016
                                    //$data['Order']['discount_value'] = $Order_detail['Order']['total'];
                                    //$data['Order']['fix_discount'] = $Order_detail['Order']['total'];
                                    $data['Order']['discount_value'] = $Order_detail['Order']['subtotal'];
                                    $data['Order']['fix_discount'] = $Order_detail['Order']['subtotal'];
                                } else {
                                    $data['Order']['discount_value'] = $discount_val;
                                    $data['Order']['fix_discount'] = $discount_val;
                                }
                                $data['Order']['percent_discount'] = 0;
                                //Modified by Yishou Liao @ Nov 18 2016
                                //$data['Order']['total'] = $Order_detail['Order']['total'] - $data['Order']['discount_value'];
                                $data['Order']['subtotal'] = $Order_detail['Order']['subtotal'] - $data['Order']['discount_value'];
                                $data['Order']['tax_amount'] = $data['Order']['subtotal'] * $Order_detail['Order']['tax'] / 100;
                                $data['Order']['total'] = $data['Order']['subtotal'] + $data['Order']['tax_amount'];
                                //End
                            }; //Modified by Yishou Liao @ Nov 18 2016 (if };)
                        }
                        $data['Order']['promocode'] = $promocode;

                        $this->Order->save($data, false);
                        $response = array(
                            'error' => false,
                            'message' => 'Discount successfully applied',
                            'discount_type' => $discount_type,
                            'discount_value' => $discount_value
                        );
                    }
                }
            }
        }; //Modified by Yishou Liao @ Nov 18 2016 (for };)


        // print_r($data);
        $this->loadModel('Order');
        $this->Order->updateBillInfo($order_id);

        echo json_encode($response);
    }

    // remove items discount
    public function remove_discount() {
        $this->layout = false;
        $this->autoRender = NULL;
        // get cashier details        
        $this->loadModel('Cashier');
        $cashier_detail = $this->Cashier->find("first", array(
            'fields' => array('Cashier.firstname', 'Cashier.lastname', 'Cashier.id', 'Cashier.image', 'Admin.id'),
            'conditions' => array('Cashier.id' => $this->Session->read('Front.id'))
                )
        );

        // get all params
        $order_id = $this->data['order_id'];

        //Modified by Yishou Liao @ Nov 18 2016
        $order_id_arr = explode(",", $order_id);
        //End

        for ($i = 0; $i < count($order_id_arr); $i++) {
            $order_id = $order_id_arr[$i];
            //End
            $this->loadModel('Order');
            $Order_detail = $this->Order->find("first", array(
                'fields' => array('Order.total', 'Order.subtotal', 'Order.tax', 'Order.discount_value'),
                'conditions' => array(
                    'Order.id' => $order_id,
                ),
                'recursive' => -1
                    )
            );

            $this->layout = false;

            $this->loadModel('OrderItem');

            //update order details        
            $data['Order']['id'] = $order_id;
            $data['Order']['discount_value'] = 0;
            $data['Order']['promocode'] = "";
            $data['Order']['fix_discount'] = 0;
            $data['Order']['percent_discount'] = 0;
            //Modified by Yishou Liao @ Nov 18 2016
            //$data['Order']['total'] = $Order_detail['Order']['total'] + $Order_detail['Order']['discount_value'];
            $data['Order']['subtotal'] = $Order_detail['Order']['subtotal'] + $Order_detail['Order']['discount_value'];
            $data['Order']['tax_amount'] = $data['Order']['subtotal'] * $Order_detail['Order']['tax'] / 100;
            $data['Order']['total'] = $data['Order']['subtotal'] + $data['Order']['tax_amount'];
            //End
            $this->Order->save($data, false);
        }; //End (for }; )
        $this->OrderItem->virtualFields['image'] = "Select image from cousines where cousines.id = OrderItem.item_id";
        $Order_detail = $this->Order->find("first", array(
            'fields' => array('Order.order_no', 'Order.tax', 'Order.tax_amount', 'Order.subtotal', 'Order.total', 'Order.message', 'Order.discount_value', 'Order.promocode', 'Order.fix_discount', 'Order.percent_discount'),
            'conditions' => array(
                'Order.id' => $order_id,
            )
                )
        );

        //Modified by Yishou Liao @ Dec 05 2016
        $extras_categories = $this->Order->query("SELECT extrascategories.* FROM `extrascategories` WHERE extrascategories.status = 'A' ");
        //End
        
        $this->loadModel('Order');
        $this->Order->updateBillInfo($order_id);

        $this->set(compact('Order_detail', 'cashier_detail','extras_categories'));
        // $this->render('summarypanel');
    }

    //Modified by Yishou Liao @ Oct 13 2016.
    public function merge() {
        // get cashier details
        $this->loadModel('Cashier');
        $cashier_detail = $this->Cashier->find("first", array(
            'fields' => array('Cashier.firstname', 'Cashier.lastname', 'Cashier.id', 'Cashier.image', 'Admin.id','Admin.kitchen_printer_device','Admin.service_printer_device'),
            'conditions' => array('Cashier.id' => $this->Session->read('Front.id'))
                )
        );
        
        $order_no = @$this->params['url']['order_no'];

        // get all params
        $type = @$this->params['named']['type'];
        $table = @$this->params['named']['table'];
        $tablemerge = explode(",", ($table . "," . @$this->params['named']['tablemerge']));


        if ($order_no) {
            $conditions = array('Order.cashier_id' => $cashier_detail['Admin']['id'],
                'Order.order_no' => $order_no
            );
        } else {
            $conditions = array('Order.cashier_id' => $cashier_detail['Admin']['id'],
                'Order.table_no' => $tablemerge,
                'Order.is_completed' => 'N',
                'Order.order_type' => $type
            );
        }

        // get order details 
        $this->loadModel('Order');
        $this->loadModel('OrderItem');

        $this->OrderItem->virtualFields['image'] = "Select image from cousines where cousines.id = OrderItem.item_id";
        $Order_detail = $this->Order->find("all", array(
            'fields' => array('Order.table_no', 'Order.paid', 'Order.tip', 'Order.cash_val', 'Order.card_val', 'Order.change', 'Order.order_no', 'Order.tax', 'Order.table_status', 'Order.tax_amount', 'Order.subtotal', 'Order.after_discount','Order.total', 'Order.message', 'Order.discount_value', 'Order.promocode', 'Order.fix_discount', 'Order.percent_discount'),
            'conditions' => $conditions
                )
        );

        if (empty($Order_detail)) {
            $this->Session->setFlash('Sorry, order does not exist 抱歉，订单不存在。.', 'error');
            return $this->redirect(array('controller' => 'homes', 'action' => 'dashboard'));
        }
        $type = @$Order_detail['Order']['order_type'] ? @$Order_detail['Order']['order_type'] : $type;
        $table = @$Order_detail['Order']['table_no'] ? @$Order_detail['Order']['table_no'] : $table;

        // get all order no.
        $orders_no = $this->Order->find("list", array(
            'fields' => array('Order.order_type', 'Order.order_no', 'Order.table_no'),
            'conditions' => array('Order.cashier_id' => $cashier_detail['Admin']['id'], 'Order.is_completed' => 'N'),
            'recursive' => false
                )
        );

        $order_id_merge = array();
        foreach ($Order_detail as $O) {
            array_push($order_id_merge, $O['Order']['id']);
        }

        // print_r($order_id_merge);

        $this->set(compact('Order_detail', 'order_id_merge', 'cashier_detail', 'type', 'table', 'tablemerge', 'orders_no'));
    }

    //End.
    //Modified by Yishou Liao @ Oct 16 2016.
    public function donemergepayment() {

        $this->layout = false;
        $this->autoRender = NULL;

        // pr($this->data); die;
        // get all params
        $order_id = explode(",", $this->data['order_id']);
        $table = $this->data['table'];
        $table_merge = explode(",", $this->data['table_merge']);
        $main_order_id = $this->data['main_order_id'];
        $type = $this->data['type'];
        $paid_by = strtoupper($this->data['paid_by']);

        $pay = $this->data['pay'];
        $change = $this->data['change'];

        // save order to database
        //Modified by Yishou Liao @ Oct 16 2016.
        $this->loadModel('Order');

        for ($i = 0; $i < count($order_id); $i++) {
            $data['Order']['id'] = $order_id[$i];
            $table_detail = $this->Order->find("first", array('fields' => array('Order.table_no', 'total'), 'conditions' => array('Order.id' => $data['Order']['id']), 'recursive' => false));

            if ($this->data['card_val'] and $this->data['cash_val']) {
                $data['Order']['paid_by'] = "MIXED";
            } elseif ($this->data['card_val']) {
                $data['Order']['paid_by'] = "CARD";
            } elseif ($this->data['cash_val']) {
                $data['Order']['paid_by'] = "CASH";
            };
            $data['Order']['table_status'] = 'P';
            $data['Order']['is_kitchen'] = 'Y';
            $data['Order']['is_completed'] = 'Y';



            if ($table_detail['Order']['table_no'] == $table) {
                $data['Order']['paid'] = $table_detail['Order']['total'] + $change;
                $data['Order']['change'] = $change;

                $data['Order']['card_val'] = $this->data['card_val'];
                $data['Order']['cash_val'] = $this->data['cash_val'];
                $data['Order']['tip_paid_by'] = $this->data['tip_paid_by'];
                $data['Order']['tip'] = $this->data['tip_val'];
            } else {
                $data['Order']['paid'] = $table_detail['Order']['total'];
                $data['Order']['change'] = 0;

                $data['Order']['card_val'] = 0;
                $data['Order']['cash_val'] = 0;
                $data['Order']['tip_paid_by'] = $this->data['tip_paid_by'];
                $data['Order']['tip'] = 0;
				$data['Order']['merge_id'] = $main_order_id; //用负数代表此处为合单，去掉负号的那个数代表主桌的付款Order的Id号
            };

            $this->Order->save($data, false);

            $this->loadModel('Cousine');
            $this->Cousine->query("UPDATE cousines set `popular` = `popular`+1 where id in(SELECT (item_id) from order_items where order_id = '$order_id[$i]')");
        };

        // save all 
        // update popularity status

        $this->Session->setFlash('Order successfully completed.', 'success');
        echo true;
    }

    //End.
    //Modified by Yishou Liao @ Oct 17 2016.
    function split() {
        // get cashier details        
        $this->loadModel('Cashier');
        $cashier_detail = $this->Cashier->find("first", array(
            'fields' => array('Cashier.firstname', 'Cashier.lastname', 'Cashier.id', 'Cashier.image', 'Admin.id','Admin.kitchen_printer_device','Admin.service_printer_device'),
            'conditions' => array('Cashier.id' => $this->Session->read('Front.id'))
                )
        );

        $order_no = @$this->params['url']['order_no'];

        // get all params
        $type = $this->params['named']['type'];
        $table = $this->params['named']['table'];
        $split_method = @$this->params['named']['split_method'];

        if ($order_no) {
            $conditions = array('Order.cashier_id' => $cashier_detail['Admin']['id'],
                'Order.order_no' => $order_no
            );
        } else {
            $conditions = array('Order.cashier_id' => $cashier_detail['Admin']['id'],
                'Order.table_no' => $table,
                'Order.is_completed' => 'N',
                'Order.order_type' => $type
            );
        }

        // get order details 
        $this->loadModel('Order');
        $this->loadModel('OrderItem');

        $this->OrderItem->virtualFields['image'] = "Select image from cousines where cousines.id = OrderItem.item_id";
        $Order_detail = $this->Order->find("first", array(
            'fields' => array('Order.paid', 'Order.tip', 'Order.cash_val', 'Order.card_val', 'Order.change', 'Order.order_no', 'Order.tax', 'Order.table_status', 'Order.tax_amount', 'Order.subtotal', 'Order.total', 'Order.message', 'Order.discount_value', 'Order.promocode', 'Order.fix_discount', 'Order.percent_discount'),
            'conditions' => $conditions
                )
        );

        if (empty($Order_detail)) {
            $this->Session->setFlash('Sorry, order does not exist 抱歉，订单不存在。.', 'error');
            return $this->redirect(array('controller' => 'homes', 'action' => 'dashboard'));
        }
        $type = @$Order_detail['Order']['order_type'] ? @$Order_detail['Order']['order_type'] : $type;
        $table = @$Order_detail['Order']['table_no'] ? @$Order_detail['Order']['table_no'] : $table;

        // get all order no.
        $orders_no = $this->Order->find("list", array(
            'fields' => array('Order.order_type', 'Order.order_no', 'Order.table_no'),
            'conditions' => array('Order.cashier_id' => $cashier_detail['Admin']['id'], 'Order.is_completed' => 'N'),
            'recursive' => false
                )
        );

        // print_r ($Order_detail);

        $this->set(compact('Order_detail', 'cashier_detail', 'type', 'table', 'orders_no', 'split_method'));
    }

    
    //End.
    //Modified by Jack @2017-01-05
    public function printTodayOrders($print_zh = false) {
        // $this->loadModel('Cashier');
    	if (empty($this->data['Printer'])) {
    		die("No Printer");
    	}

		date_default_timezone_set("America/Toronto");
		$date_time = date("l M d Y h:i:s A");
		$timeline = strtotime(date("Y-m-d 11:00:00"));
		$nottm = time();
		if ($timeline < $nowtm) {
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

    	$Printer = $this->data['Printer'];
    	$this->loadModel('Order');
        $fields = array(
        		'Order.order_no',
        		'Order.cashier_id',
        		'Order.table_no',
        		'Order.total',
        		'Order.paid',
        		'Order.cash_val',
        		'Order.card_val',
        		'Order.tax_amount',
        		'Order.discount_value',
        		'Order.percent_discount',
        		'Order.paid_by',
        		'Order.tip',
        		'Order.tip_paid_by'
        );

    	$conditions = array('Order.table_status' => 'P', 'Order.is_completed' => 'Y', 'Order.created >=' => date('c', $tm11), 'Order.created <' => date('c', $tm17));
        $Orders1 = $this->Order->find("all", array('conditions' => $conditions , 'fields' => $fields ));

    	$conditions = array('Order.table_status' => 'P', 'Order.is_completed' => 'Y', 'Order.created >=' => date('c', $tm17), 'Order.created <' => date('c', $tm23));
        $Orders2 = $this->Order->find("all", array('conditions' => $conditions , 'fields' => $fields ));

    	$conditions = array('Order.table_status' => 'P', 'Order.is_completed' => 'Y', 'Order.created >=' => date('c', $tm23), 'Order.created <' => date('c', $tm04));
        $Orders3 = $this->Order->find("all", array('conditions' => $conditions , 'fields' => $fields ));
        //print_r($Printer);

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
				$font = printer_create_font(iconv("UTF-8", "gb2312", "宋体"), 42, 18, PRINTER_FW_BOLD, false, false, false, 0);
				printer_select_font($handle, $font);
				printer_draw_text($handle, iconv("UTF-8", "gb2312", "All Orders (总单)"), 108, 20);
				$font = printer_create_font(iconv("UTF-8", "gb2312", "宋体"), 32, 14, PRINTER_FW_MEDIUM, false, false, false, 0);
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
			$tmArr[0] = date("Y-m-d H:i", $tm11);
			$tmArr[1] = date("Y-m-d H:i", $tm17);
			$tmArr[2] = date("Y-m-d H:i", $tm23);
			$tmArr[3] = date("Y-m-d H:i", $tm04);
			$tmidx = 0;
			foreach (array($Orders1, $Orders2, $Orders3) as $Orders) {
				printer_draw_text($handle, $tmArr[$tmidx] . " - " . $tmArr[$tmidx + 1], $c1, $print_y);
				$tmidx++;
				$print_y+=32;
				$totalArr = array(
					'total' => 0,
					'cash_total' => 0,
					'card_total' => 0,
					'cash_mix_total' => 0,
					'card_mix_total' => 0,
					'paid_cash_total' => 0,
					'paid_card_total' => 0,
					'total_tip' => 0,
					'cash_tip_total' => 0,
					'card_tip_total' => 0,
					'mix_tip_total' => 0,
					'tax' => 0);
				foreach ($Orders as $o) {
					$order = $o['Order'];
					//printer_draw_text($handle, $order['order_no'], $c1, $print_y);
					//printer_draw_text($handle, $order['paid_by'], $c2, $print_y);
					//printer_draw_text($handle, number_format($order['total'], 2), $c3, $print_y);
					//$print_y+=32;
					if (!isset($cashierArr[$order['cashier_id']])) {
						$cashierArr[$order['cashier_id']] = array('total' => 0, 'cash_total' => 0, 'card_total' => 0, 'cash_mix_total' => 0, 'card_mix_total' => 0, 'total_tip' => 0, 'cash_tip_total' => 0, 'card_tip_total' => 0, 'mix_tip_total' => 0);
					}
					$cashierArr[$order['cashier_id']]['total'] += $order['total'];
					$totalArr['total'] += $order['total'];
					$totalArr['paid_cash_total'] += $order['cash_val'];
					$totalArr['paid_card_total'] += $order['card_val'];
	
					if ($order['paid_by'] == 'CASH') { // CARD, CASH, MIXED and NO TIP
						$cashierArr[$order['cashier_id']]['cash_total'] += $order['total'];
						$totalArr['cash_total'] += $order['total'];
					} else if ($order['paid_by'] == 'CARD') { // CARD, CASH, MIXED and NO TIP
						$cashierArr[$order['cashier_id']]['card_total'] += $order['total'];
						$totalArr['card_total'] += $order['total'];
					} else {
						$cashierArr[$order['cashier_id']]['card_mix_total'] += $order['card_val'];
						$totalArr['card_mix_total'] += $order['card_val'];
						$cashierArr[$order['cashier_mix_id']]['cash_total'] += $order['total'] - $order['card_val'];
						$totalArr['cash_mix_total'] += $order['total'] - $order['card_val'];
					}
					$cashierArr[$order['cashier_id']]['total_tip'] += $order['tip'];
					$totalArr['total_tip'] += $order['tip'];
					if ($order['tip_paid_by'] == 'CASH') { // CARD, CASH, MIXED and NO TIP
						$cashierArr[$order['cashier_id']]['cash_tip_total'] += $order['tip'];
						$totalArr['cash_tip_total'] += $order['tip'];
					} else if ($order['tip_paid_by'] == 'CARD') {
						$cashierArr[$order['cashier_id']]['card_tip_total'] += $order['tip'];
						$totalArr['card_tip_total'] += $order['tip'];
					} else { // MIX
						$cashierArr[$order['cashier_id']]['mix_tip_total'] += $order['tip'];
						$totalArr['mix_tip_total'] += $order['tip'];
					}
					$totalArr['tax'] += $order['tax_amount'];
				}
				$print_y+=16;
				printer_draw_line($handle, 21, $print_y, 600, $print_y);
				$print_y+=32;
				
				$real_total = $totalArr['paid_cash_total'] + $totalArr['paid_card_total'];
				if ($real_total > 0) {
					$paid_cash_percent = " " . number_format($totalArr['paid_cash_total'] * 100 / $real_total, 2) . '%';
					$paid_card_percent = " " . number_format($totalArr['paid_card_total'] * 100 / $real_total, 2) . '%';
				} else {
					$paid_cash_percent = "-";
					$paid_card_percent = "-";
				}
				if ($print_zh == true) {
					printer_draw_text($handle, iconv("UTF-8", "gb2312", '税额 : ') . sprintf('%0.2f', $totalArr['tax']), 32, $print_y); $print_y+=32;
					//printer_draw_text($handle, iconv("UTF-8", "gb2312", '现金额 : ') . sprintf('%0.2f', $totalArr['cash_total']), 32, $print_y); $print_y+=32;
					//printer_draw_text($handle, iconv("UTF-8", "gb2312", '卡类额 : ') . sprintf('%0.2f', $totalArr['card_total']), 32, $print_y); $print_y+=32;
					//printer_draw_text($handle, iconv("UTF-8", "gb2312", '混和现金额 : ') . sprintf('%0.2f', $totalArr['cash_mix_total']), 32, $print_y); $print_y+=32;
					//printer_draw_text($handle, iconv("UTF-8", "gb2312", '混和卡类额 : ') . sprintf('%0.2f', $totalArr['card_mix_total']), 32, $print_y); $print_y+=32;
					printer_draw_text($handle, iconv("UTF-8", "gb2312", '总计 : ') . sprintf('%0.2f', $totalArr['total']) . " ( " . sizeof($Orders) . iconv("UTF-8", "gb2312", " 单 ) "), 32, $print_y); $print_y+=32;
					printer_draw_text($handle, iconv("UTF-8", "gb2312", '实收总计 : ') . sprintf('%0.2f', $real_total), 32, $print_y); $print_y+=32;
					printer_draw_text($handle, iconv("UTF-8", "gb2312", '实收现金 : ') . sprintf('%0.2f', $totalArr['paid_cash_total']) . " ( " . $paid_cash_percent . " ) ", 32, $print_y); $print_y+=32;
					printer_draw_text($handle, iconv("UTF-8", "gb2312", '实收卡类 : ') . sprintf('%0.2f', $totalArr['paid_card_total']) . " ( " . $paid_card_percent . " ) ", 32, $print_y); $print_y+=32;
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
					printer_draw_text($handle, 'TAX Total : ' . sprintf('%0.2f', $totalArr['tax']), 32, $print_y); $print_y+=32;
					//printer_draw_text($handle, 'Cash Total : ' . sprintf('%0.2f', $totalArr['cash_total']), 32, $print_y); $print_y+=32;
					//printer_draw_text($handle, 'Card Total : ' . sprintf('%0.2f', $totalArr['card_total']), 32, $print_y); $print_y+=32;
					//printer_draw_text($handle, 'Mix Cash Total : ' . sprintf('%0.2f', $totalArr['cash_mix_total']), 32, $print_y); $print_y+=32;
					//printer_draw_text($handle, 'Mix Card Total : ' . sprintf('%0.2f', $totalArr['card_mix_total']), 32, $print_y); $print_y+=32;
					printer_draw_text($handle, 'Total : ' . sprintf('%0.2f', $totalArr['total']) . " ( " . sizeof($Orders) . " sales ) ", 32, $print_y); $print_y+=32;
					printer_draw_text($handle, 'Paid Total : ' . sprintf('%0.2f', $real_total), 32, $print_y); $print_y+=32;
					printer_draw_text($handle, 'Paid Cash Total : ' . sprintf('%0.2f', $totalArr['paid_cash_total']) . " ( " . $paid_cash_percent . " ) ", 32, $print_y); $print_y+=32;
					printer_draw_text($handle, 'Paid Card Total : ' . sprintf('%0.2f', $totalArr['paid_card_total']) . " ( " . $paid_card_percent . " ) ", 32, $print_y); $print_y+=32;
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
			printer_delete_font($font);
			printer_end_page($handle);
			printer_end_doc($handle);
			printer_close($handle);
		};
		exit;
	}

    public function printTokitchen() {
        $this->layout = false;
        $this->autoRender = NULL;

        $this->loadModel('Order');
        $this->loadModel('OrderItem');
        $this->loadModel('Category');
        $this->loadModel('Cashier');
        // according to order_id
        // find all items in order, print all items which is not print
        if (!isset($this->data['order_no']) || !isset($this->data['type']) || !isset( $this->data['table'])) {
            return;
        }

        $order_no = $this->data['order_no'];
        $type = $this->data['type'];
        $table = $this->data['table'];

        $order_id = $this->Order->getOrderIdByOrderNo($order_no);


        // get all un printed items
        $orderItemsDetail = $this->OrderItem->find('all', array(
                'fields' => array(
                    'OrderItem.id',
                    'OrderItem.name_en',
                    'OrderItem.name_xh',
                    'OrderItem.category_id',
                    'OrderItem.qty',
                    'OrderItem.selected_extras',
                    'OrderItem.is_takeout',
                    'OrderItem.is_print',
                    ),
                'conditions' => array(
                    'OrderItem.order_id' => $order_id, 
                    'OrderItem.is_print' => 'N'
                    ),
            ));

        // print_r ($orderItemsDetail);
        


        // seperate items by printer
        $printItems = array();
        foreach ($orderItemsDetail as $itemDetail) {
            $category_id = $itemDetail['OrderItem']['category_id'];
            $printer = $this->Category->getPrinterById($category_id);

            $selected_extras_list = json_decode($itemDetail['OrderItem']['selected_extras'], true);
            $selected_extras_arr = array();
                if (!empty($selected_extras_list)) {
                    foreach ($selected_extras_list as $selected_extra) {
                        array_push($selected_extras_arr, $selected_extra['name']);
                    }
                }
                
            $itemDetail['OrderItem']['selected_extras'] = join(',', $selected_extras_arr);


            if (!isset($printItems[$printer])) {
                $printItems[$printer] = array();
            }

            array_push($printItems[$printer], $itemDetail['OrderItem']);
        }

        // print_r($printItems);

        if (!empty($printItems['K'])) {

            $printerName = $this->Cashier->getKitchenPrinterName( $this->Session->read('Front.id'));
            $print = new PrintLib();
            $print->printKitchenItemDoc($order_no, $table, $type, $printerName, $printItems['K'],true, false);
        }

        if (!empty($printItems['C'])) {
            $printerName = $this->Cashier->getServicePrinterName( $this->Session->read('Front.id'));
            $print = new PrintLib();
            $print->printKitchenItemDoc($order_no, $table, $type, $printerName, $printItems['C'], true, false);
        }


        

        // change all items is_print to 'Y'
        foreach ($orderItemsDetail as $itemDetail) {
            $itemDetail['OrderItem']['is_print'] = 'Y';
            $this->OrderItem->save($itemDetail, false);
        }

        $this->set($this->getAllDBInfo($table, $type));
        $this->render('summarypanel');
    }




    //Modified by Yishou Liao @ Nov 15 2016
    public function printReceipt($order_no, $table_no, $printer_name, $print_zh = false) {
        $Print_Item = $this->data['Print_Item'];
        
        $logo_name = $this->data['logo_name'];
        $memo = isset($this->data['memo']) ? $this->data['memo'] : "";
        $subtotal = isset($this->data['subtotal']) ? $this->data['subtotal'] : 0.00;
        //Modified by Yishou Liao @ Nov 29 2016
        $discount = isset($this->data['discount']) ? $this->data['discount'] : 0.00;
        $after_discount = isset($this->data['after_discount']) ? $this->data['after_discount'] : 0.00;
        $tax_Amount = isset($this->data['tax_Amount']) ? $this->data['tax_Amount'] : 0.00;
        $paid = isset($this->data['paid']) ? $this->data['paid'] : 0.00;
        $change = isset($this->data['change']) ? $this->data['change'] : 0.00;
        //End
        $tax = isset($this->data['tax']) ? $this->data['tax'] : 0.00;
        $total = isset($this->data['total']) ? $this->data['total'] : 0.00;
        $split_no = isset($this->data['split_no']) ? "" . $this->data['split_no'] : "";

        date_default_timezone_set("America/Toronto");
        $date_time = date("l M d Y h:i:s A");

        $handle = printer_open($printer_name);
        printer_start_doc($handle, "my_Receipt");
        printer_start_page($handle);

        //Print Logo image
        printer_draw_bmp($handle, $logo_name, 100, 20, 263, 100);
        //End.
        //Print title
        $font = printer_create_font("Arial", 32, 14, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($handle, $font);
        /*printer_draw_text($handle, "2038 Yonge St.", 156, 130);
        printer_draw_text($handle, "Toronto ON M4S 1Z9", 110, 168);
        printer_draw_text($handle, "416-792-4476", 156, 206);
*/		printer_draw_text($handle, "3700 Midland Ave. #108", 156, 130);
        printer_draw_text($handle, "Scarborogh ON M1V 0B3", 110, 168);
        printer_draw_text($handle, "647-352-5333", 156, 206);

        $print_y = 244;
        if ($print_zh == true) {
            $font = printer_create_font(iconv("UTF-8", "gb2312", "宋体"), 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
            printer_select_font($handle, $font);
            printer_draw_text($handle, iconv("UTF-8", "gb2312", "此单不包含小费，感谢您的光临"), 100, $print_y);
            $print_y+=40;
            printer_draw_text($handle, iconv("UTF-8", "gb2312", "谢谢"), 210, $print_y);
            $print_y+=40;
        };
        //End
        //Print order information
        $font = printer_create_font("Arial", 32, 14, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($handle, $font);
        //Modified by Yishou Liao @ Nov 09 2016
        if ($split_no != "") {
            $split_no = " - " . $split_no;
        };
        //End
        printer_draw_text($handle, "Order Number: #" . $order_no . $split_no, 32, $print_y);
        $print_y+=40;
        printer_draw_text($handle, "Table:" . iconv("UTF-8", "gb2312", $table_no), 32, $print_y);
        $print_y+=38;
        //End

        $pen = printer_create_pen(PRINTER_PEN_SOLID, 2, "000000");
        printer_select_pen($handle, $pen);
        printer_draw_line($handle, 21, $print_y, 600, $print_y);


        
        //Print order items
        $print_y += 20;
        for ($i = 0; $i < count($Print_Item); $i++) {
            $font = printer_create_font("Arial", 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
            printer_select_font($handle, $font);
            
            printer_draw_text($handle, $Print_Item[$i][7], 32, $print_y);
            printer_draw_text($handle, number_format($Print_Item[$i][6]+$Print_Item[$i][12], 2), 360, $print_y);
            $print_str = $Print_Item[$i][3];
            $len = 0;
            while (strlen($print_str) != 0) {
                $print_str = substr($Print_Item[$i][3], $len, 18);
                printer_draw_text($handle, $print_str, 122, $print_y);
                $len+=18;
                if (strlen($print_str) != 0) {
                    $print_y+=40;
                };
            };
            if ($print_zh == true) {
                $font = printer_create_font(iconv("UTF-8", "gb2312", "宋体"), 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
                printer_select_font($handle, $font);

                printer_draw_text($handle, iconv("UTF-8", "gb2312", $Print_Item[$i][4]), 136, $print_y);
                $print_y += 40;
            };
        };
        //End.

        $print_y += 10;
        $pen = printer_create_pen(PRINTER_PEN_SOLID, 2, "000000");
        printer_select_pen($handle, $pen);
        printer_draw_line($handle, 21, $print_y, 600, $print_y);

        //Print Subtotal
        $print_y += 10;
        $font = printer_create_font("Arial", 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($handle, $font);
        if ($print_zh == true) {
            printer_draw_text($handle, iconv("UTF-8", "gb2312", "Subtoal"), 58, $print_y);
        } else {
            printer_draw_text($handle, iconv("UTF-8", "gb2312", "Subtoal :"), 58, $print_y);
        };

        if ($print_zh == true) {
            $font = printer_create_font(iconv("UTF-8", "gb2312", "宋体"), 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
            printer_select_font($handle, $font);
            printer_draw_text($handle, iconv("UTF-8", "gb2312", "小计："), 148, $print_y);
        };

        $font = printer_create_font("Arial", 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($handle, $font);
        printer_draw_text($handle, iconv("UTF-8", "gb2312", number_format($subtotal, 2)), 360, $print_y);
        //End.
        //Modified by Yishou Liao @ Nov 29 2016
        if ($discount > 0) {
            //Print Discount
            $print_y += 40;
            $font = printer_create_font("Arial", 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
            printer_select_font($handle, $font);
            if ($print_zh == true) {
                printer_draw_text($handle, iconv("UTF-8", "gb2312", "Discount"), 58, $print_y);
            } else {
                printer_draw_text($handle, iconv("UTF-8", "gb2312", "Discount :"), 58, $print_y);
            };

            if ($print_zh == true) {
                $font = printer_create_font(iconv("UTF-8", "gb2312", "宋体"), 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
                printer_select_font($handle, $font);
                printer_draw_text($handle, iconv("UTF-8", "gb2312", "折扣："), 148, $print_y);
            };

            $font = printer_create_font("Arial", 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
            printer_select_font($handle, $font);
            printer_draw_text($handle, iconv("UTF-8", "gb2312", number_format($discount, 2)), 360, $print_y);
            //End.
            //Print After_Discount
            $print_y += 40;
            $font = printer_create_font("Arial", 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
            printer_select_font($handle, $font);
            if ($print_zh == true) {
                printer_draw_text($handle, iconv("UTF-8", "gb2312", "After Discount"), 58, $print_y);
            } else {
                printer_draw_text($handle, iconv("UTF-8", "gb2312", "After Discount :"), 58, $print_y);
            };

            if ($print_zh == true) {
                $font = printer_create_font(iconv("UTF-8", "gb2312", "宋体"), 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
                printer_select_font($handle, $font);
                printer_draw_text($handle, iconv("UTF-8", "gb2312", "折后价："), 148, $print_y);
            };

            $font = printer_create_font("Arial", 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
            printer_select_font($handle, $font);
            printer_draw_text($handle, iconv("UTF-8", "gb2312", number_format($after_discount, 2)), 360, $print_y);
            //End.
        };
        //Print Tax
        $print_y += 40;
        printer_draw_text($handle, iconv("UTF-8", "gb2312", "Hst"), 58, $print_y);

        if ($print_zh == true) {
            $font = printer_create_font(iconv("UTF-8", "gb2312", "宋体"), 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
            printer_select_font($handle, $font);
            printer_draw_text($handle, iconv("UTF-8", "gb2312", "税："), 168, $print_y);
        };

        $font = printer_create_font("Arial", 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($handle, $font);
        if ($print_zh == true) {
            printer_draw_text($handle, iconv("UTF-8", "gb2312", "(" . $tax . "%)"), 100, $print_y);
        } else {
            printer_draw_text($handle, iconv("UTF-8", "gb2312", "(" . $tax . "%) :"), 100, $print_y);
        };
        printer_draw_text($handle, iconv("UTF-8", "gb2312", number_format($tax_Amount, 2)), 360, $print_y);
        //End.
        //End @ Nov 29 2016
        //Print Total
        $print_y += 40;
        if ($print_zh == true) {
            printer_draw_text($handle, iconv("UTF-8", "gb2312", "Total"), 58, $print_y);
        } else {
            printer_draw_text($handle, iconv("UTF-8", "gb2312", "Total :"), 58, $print_y);
        };

        if ($print_zh == true) {
            $font = printer_create_font(iconv("UTF-8", "gb2312", "宋体"), 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
            printer_select_font($handle, $font);
            printer_draw_text($handle, iconv("UTF-8", "gb2312", "总计："), 148, $print_y);
        };

        $font = printer_create_font("Arial", 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($handle, $font);
        printer_draw_text($handle, iconv("UTF-8", "gb2312", number_format($total, 2)), 360, $print_y);
        //End.

        if ($memo != "") {
            //Print average
            $print_y += 40;
            printer_draw_text($handle, "Average", 58, $print_y);

            if ($print_zh == true) {
                $font = printer_create_font(iconv("UTF-8", "gb2312", "宋体"), 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
                printer_select_font($handle, $font);
                printer_draw_text($handle, iconv("UTF-8", "gb2312", "人均："), 148, $print_y);
            };

            $font = printer_create_font("Arial", 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
            printer_select_font($handle, $font);
            printer_draw_text($handle, iconv("UTF-8", "gb2312", number_format($memo, 2)), 360, $print_y);
            //End.
        };

        //Modified by Yishou Liao @ Nov 29 2016
        if ($paid > 0) {
            $print_y += 50;
            $pen = printer_create_pen(PRINTER_PEN_SOLID, 2, "000000");
            printer_select_pen($handle, $pen);
            printer_draw_line($handle, 21, $print_y, 600, $print_y);

            //Print paid
            $print_y += 10;
            if ($print_zh == true) {
                printer_draw_text($handle, iconv("UTF-8", "gb2312", "Paid"), 58, $print_y);
            } else {
                printer_draw_text($handle, iconv("UTF-8", "gb2312", "Paid :"), 58, $print_y);
            };

            if ($print_zh == true) {
                $font = printer_create_font(iconv("UTF-8", "gb2312", "宋体"), 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
                printer_select_font($handle, $font);
                printer_draw_text($handle, iconv("UTF-8", "gb2312", "付款："), 148, $print_y);
            };

            $font = printer_create_font("Arial", 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
            printer_select_font($handle, $font);
            printer_draw_text($handle, iconv("UTF-8", "gb2312", number_format($paid, 2)), 360, $print_y);
            //End.
            //Print change
            $print_y += 40;
            if ($print_zh == true) {
                printer_draw_text($handle, iconv("UTF-8", "gb2312", "Change"), 58, $print_y);
            } else {
                printer_draw_text($handle, iconv("UTF-8", "gb2312", "Change :"), 58, $print_y);
            };

            if ($print_zh == true) {
                $font = printer_create_font(iconv("UTF-8", "gb2312", "宋体"), 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
                printer_select_font($handle, $font);
                printer_draw_text($handle, iconv("UTF-8", "gb2312", "找零："), 148, $print_y);
            };

            $font = printer_create_font("Arial", 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
            printer_select_font($handle, $font);
            printer_draw_text($handle, iconv("UTF-8", "gb2312", number_format($change, 2)), 360, $print_y);
            //End.
        }
        //End

        $print_y += 40;
        printer_draw_text($handle, $date_time, 80, $print_y);

        printer_delete_font($font);

        printer_end_page($handle);
        printer_end_doc($handle);
        printer_close($handle);

        echo true;
        exit;
    }

    //End.
    //Modified by Yishou Liao @ Nov 15 2016
    public function printMergeReceipt($table_no, $printer_name, $print_zh = false) {
        $Print_Item = $this->data['Print_Item'];
        $logo_name = $this->data['logo_name'];
        $memo = isset($this->data['memo']) ? $this->data['memo'] : "";
        $subtotal = isset($this->data['subtotal']) ? $this->data['subtotal'] : 0;
        //Modified by Yishou Liao @ Nov 29 2016
        $tax_amount = isset($this->data['tax_amount']) ? $this->data['tax_amount'] : 0;
        $discount = isset($this->data['discount']) ? $this->data['discount'] : 0;
        $after_discount = isset($this->data['after_discount']) ? $this->data['after_discount'] : 0;
        $paid = isset($this->data['paid']) ? $this->data['paid'] : 0;
        $change = isset($this->data['change']) ? $this->data['change'] : 0;
        //End
        $total = isset($this->data['total']) ? $this->data['total'] : 0;
        $order_no = $this->data['order_no'];
        $merge_str = $this->data['merge_str'];

        date_default_timezone_set("America/Toronto");
        $date_time = date("l M d Y h:i:s A");

        $handle = printer_open($printer_name);
        printer_start_doc($handle, "my_Receipt");
        printer_start_page($handle);

        //Print Logo image
        printer_draw_bmp($handle, $logo_name, 100, 20, 263, 100);
        //End.
        //Print title
        $font = printer_create_font("Arial", 32, 14, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($handle, $font);
        printer_draw_text($handle, "2038 Yonge St.", 156, 130);
        printer_draw_text($handle, "Toronto ON M4S 1Z9", 110, 168);
        printer_draw_text($handle, "416-792-4476", 156, 206);
		/*printer_draw_text($handle, "3700 Midland Ave. #108", 156, 130);
        printer_draw_text($handle, "Scarborogh ON M1V 0B3", 110, 168);
        printer_draw_text($handle, "647-352-5333", 156, 206);*/

        $print_y = 244;
        if ($print_zh == true) {
            $font = printer_create_font(iconv("UTF-8", "gb2312", "宋体"), 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
            printer_select_font($handle, $font);
            printer_draw_text($handle, iconv("UTF-8", "gb2312", "此单不包含小费，感谢您的光临"), 100, $print_y);
            $print_y+=40;
            printer_draw_text($handle, iconv("UTF-8", "gb2312", "谢谢"), 210, $print_y);
            $print_y+=40;
        };
        //End
        //Print order information
        $font = printer_create_font("Arial", 32, 14, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($handle, $font);
        printer_draw_text($handle, "Order Number: " . $order_no, 32, $print_y);
        $print_y+=40;
        printer_draw_text($handle, "Table:" . iconv("UTF-8", "gb2312", $table_no . $merge_str), 32, $print_y);
        $print_y+=38;
        //End

        $pen = printer_create_pen(PRINTER_PEN_SOLID, 2, "000000");
        printer_select_pen($handle, $pen);
        printer_draw_line($handle, 21, $print_y, 600, $print_y);

        //Print order items
        $print_y += 20;
        foreach (array_keys($Print_Item) as $key) {
            $font = printer_create_font("Arial", 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
            printer_select_font($handle, $font);

            printer_draw_text($handle, " # " . $Print_Item[$key][0][18], 32, $print_y);
            $print_y += 40;

            for ($i = 0; $i < count($Print_Item[$key]); $i++) {
                $font = printer_create_font("Arial", 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
                printer_select_font($handle, $font);

                printer_draw_text($handle, $Print_Item[$key][$i][7], 32, $print_y);
                printer_draw_text($handle, number_format($Print_Item[$key][$i][6], 2), 360, $print_y);

                $print_str = $Print_Item[$key][$i][3];
                $len = 0;
                while (strlen($print_str) != 0) {
                    $print_str = substr($Print_Item[$key][$i][3], $len, 18);
                    printer_draw_text($handle, $print_str, 122, $print_y);
                    $len+=18;
                    if (strlen($print_str) != 0) {
                        $print_y+=40;
                    };
                };
                if ($print_zh == true) {
                    $font = printer_create_font(iconv("UTF-8", "gb2312", "宋体"), 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
                    printer_select_font($handle, $font);

                    printer_draw_text($handle, iconv("UTF-8", "gb2312", $Print_Item[$key][$i][4]), 136, $print_y);
                    $print_y += 40;
                };
            };
        };
        //End.

        $print_y += 10;
        $pen = printer_create_pen(PRINTER_PEN_SOLID, 2, "000000");
        printer_select_pen($handle, $pen);
        printer_draw_line($handle, 21, $print_y, 600, $print_y);
        
        //Print Subtotal
        $print_y += 10;
        $font = printer_create_font("Arial", 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($handle, $font);
        if ($print_zh == true) {
            printer_draw_text($handle, iconv("UTF-8", "gb2312", "Subtoal"), 58, $print_y);
        } else {
            printer_draw_text($handle, iconv("UTF-8", "gb2312", "Subtoal :"), 58, $print_y);
        }

        if ($print_zh == true) {
            $font = printer_create_font(iconv("UTF-8", "gb2312", "宋体"), 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
            printer_select_font($handle, $font);
            printer_draw_text($handle, iconv("UTF-8", "gb2312", "小计："), 148, $print_y);
        };

        $font = printer_create_font("Arial", 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($handle, $font);
        printer_draw_text($handle, iconv("UTF-8", "gb2312", number_format($subtotal, 2)), 360, $print_y);
        //End.
        
        //Modified by Yishou Liao @ Nov 29 2016
        if ($discount > 0) {
            //Print Discount
        $print_y += 40;
        $font = printer_create_font("Arial", 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($handle, $font);
        if ($print_zh == true) {
            printer_draw_text($handle, iconv("UTF-8", "gb2312", "Discount"), 58, $print_y);
        } else {
            printer_draw_text($handle, iconv("UTF-8", "gb2312", "Discount :"), 58, $print_y);
        }

        if ($print_zh == true) {
            $font = printer_create_font(iconv("UTF-8", "gb2312", "宋体"), 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
            printer_select_font($handle, $font);
            printer_draw_text($handle, iconv("UTF-8", "gb2312", "小计："), 148, $print_y);
        };

        $font = printer_create_font("Arial", 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($handle, $font);
        printer_draw_text($handle, iconv("UTF-8", "gb2312", number_format($discount, 2)), 360, $print_y);
        //End.
        //Print After_Discount
        $print_y += 40;
        $font = printer_create_font("Arial", 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($handle, $font);
        if ($print_zh == true) {
            printer_draw_text($handle, iconv("UTF-8", "gb2312", "After Discount"), 58, $print_y);
        } else {
            printer_draw_text($handle, iconv("UTF-8", "gb2312", "After Discount :"), 58, $print_y);
        }

        if ($print_zh == true) {
            $font = printer_create_font(iconv("UTF-8", "gb2312", "宋体"), 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
            printer_select_font($handle, $font);
            printer_draw_text($handle, iconv("UTF-8", "gb2312", "小计："), 148, $print_y);
        };

        $font = printer_create_font("Arial", 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($handle, $font);
        printer_draw_text($handle, iconv("UTF-8", "gb2312", number_format($after_discount, 2)), 360, $print_y);
        //End.
        };
        //End
        
        //Print Tax
        $print_y += 40;
        printer_draw_text($handle, iconv("UTF-8", "gb2312", "Hst"), 58, $print_y);

        if ($print_zh == true) {
            $font = printer_create_font(iconv("UTF-8", "gb2312", "宋体"), 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
            printer_select_font($handle, $font);
            printer_draw_text($handle, iconv("UTF-8", "gb2312", "税："), 168, $print_y);
        };

        $font = printer_create_font("Arial", 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($handle, $font);
        if ($print_zh == true) {
            printer_draw_text($handle, iconv("UTF-8", "gb2312", "(13%)"), 100, $print_y);
        } else {
            printer_draw_text($handle, iconv("UTF-8", "gb2312", "(13%) :"), 100, $print_y);
        };
        printer_draw_text($handle, iconv("UTF-8", "gb2312", number_format(($after_discount * 13 / 100), 2)), 360, $print_y);
        //End.
        //Print Total
        $print_y += 40;
        if ($print_zh == true) {
            printer_draw_text($handle, iconv("UTF-8", "gb2312", "Total"), 58, $print_y);
        } else {
            printer_draw_text($handle, iconv("UTF-8", "gb2312", "Total :"), 58, $print_y);
        };

        if ($print_zh == true) {
            $font = printer_create_font(iconv("UTF-8", "gb2312", "宋体"), 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
            printer_select_font($handle, $font);
            printer_draw_text($handle, iconv("UTF-8", "gb2312", "总计："), 148, $print_y);
        };

        $font = printer_create_font("Arial", 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($handle, $font);
        printer_draw_text($handle, iconv("UTF-8", "gb2312", number_format($total, 2)), 360, $print_y);
        //End.

        //Modified by Yishou Liao @ Nov 29 2016
        if ($paid > 0) {
            $print_y += 50;
            $pen = printer_create_pen(PRINTER_PEN_SOLID, 2, "000000");
            printer_select_pen($handle, $pen);
            printer_draw_line($handle, 21, $print_y, 600, $print_y);

            //Print paid
            $print_y += 10;
            if ($print_zh == true) {
                printer_draw_text($handle, iconv("UTF-8", "gb2312", "Paid"), 58, $print_y);
            } else {
                printer_draw_text($handle, iconv("UTF-8", "gb2312", "Paid :"), 58, $print_y);
            };

            if ($print_zh == true) {
                $font = printer_create_font(iconv("UTF-8", "gb2312", "宋体"), 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
                printer_select_font($handle, $font);
                printer_draw_text($handle, iconv("UTF-8", "gb2312", "付款："), 148, $print_y);
            };

            $font = printer_create_font("Arial", 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
            printer_select_font($handle, $font);
            printer_draw_text($handle, iconv("UTF-8", "gb2312", number_format($paid, 2)), 360, $print_y);
            //End.
            //Print change
            $print_y += 40;
            if ($print_zh == true) {
                printer_draw_text($handle, iconv("UTF-8", "gb2312", "Change"), 58, $print_y);
            } else {
                printer_draw_text($handle, iconv("UTF-8", "gb2312", "Change :"), 58, $print_y);
            };

            if ($print_zh == true) {
                $font = printer_create_font(iconv("UTF-8", "gb2312", "宋体"), 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
                printer_select_font($handle, $font);
                printer_draw_text($handle, iconv("UTF-8", "gb2312", "找零："), 148, $print_y);
            };

            $font = printer_create_font("Arial", 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
            printer_select_font($handle, $font);
            printer_draw_text($handle, iconv("UTF-8", "gb2312", number_format($change, 2)), 360, $print_y);
            //End.
        }
        //End
        
        $print_y += 40;
        printer_draw_text($handle, $date_time, 80, $print_y);

        printer_delete_font($font);

        printer_end_page($handle);
        printer_end_doc($handle);
        printer_close($handle);

        echo true;
        exit;
    }

    //End
}
