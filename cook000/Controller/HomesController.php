<?php

/**
 * Class HomesController
 */
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
        //$this->layout = false;
        if ($this->request->is('post')) {
            $this->loadModel("Cook");
            if (isset($this->request->data['Cook']['username']) && isset($this->request->data['Cook']['password'])) {
                $username = $this->request->data['Cook']['username'];
                $password = Security::hash($this->data['Cook']['password'], 'md5', false);

                $cond = array(
                    'Cook.password' => $password,
                    'OR' => array(
                        'Cook.email' => $username,
                    // 'OR' => array('Cook.mobile_no' => $username)
                    )
                );
                $user = $this->Cook->find('first', array(
                    'conditions' => $cond,
                ));
                if (!empty($user)) {
                    if ($user['Cook']['status'] == "A") {
                        if ($user['Cook']['is_verified'] == "Y") {
                            $user['Cook']['type'] = "cook";
                            $this->Auth->login($user['Cook']);

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

        if($this->Auth->user('type') <> 'cook') {
            // logout previous user
            $this->Auth->logout();
        }
        if ($this->Auth->loggedIn() || $this->Auth->login()) {
            return $this->redirect(array('controller' => 'homes', 'action' => 'cookings'));
        }
    }

    public function logout() {
        $user = $this->Auth->user();
        $this->Session->setFlash(sprintf(__('%s you have successfully logged out'), $this->Auth->user('firstname')), 'success');
        $this->redirect($this->Auth->logout());
    }

    public function forgot_password() {
        if ($this->request->is('post')) {
            $this->loadModel("Cook");
            $email_id = $this->request->data['Cook']['email'];
            $cond = array(
                'Cook.email' => $email_id,
            );
            $user = $this->Cook->find('first', array(
                'conditions' => $cond,
            ));
            if (!empty($user)) {
                if ($user['Cook']['status'] == "A") {
                    if ($user['Cook']['is_verified'] == "Y") {
                        $password = rand(100000, 999999);
                        $password_md5 = Security::hash($password, 'md5');
                        $this->Cook->updateAll(array('Cook.password' => "'" . $password_md5 . "'"), array('Cook.id' => $user['Cook']['id']));
                        //send mail//
                        $Email = new CakeEmail();
                        $name = $user['Cook']['firstname'] . " " . $user['Cook']['lastname'];
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

    public function cookings() {

        // get cook details     
        $this->loadModel('Cook');   
        $user = $this->Cook->find('first', array(
            'fields'=>array(
            'Cook.restaurant_id'),
            'conditions' => array('Cook.id'=>$this->Session->read('Front.id'))
        ));

        // get all orders
        $this->loadModel('Order');
        $this->loadModel('Category');
        $this->loadModel('OrderItem');

        $this->OrderItem->virtualFields['category_name_en'] = "Select category_locales.name from category_locales where OrderItem.category_id = category_locales.category_id and category_locales.lang_code = 'en'";
        $this->OrderItem->virtualFields['category_name_zh'] = "Select category_locales.name from category_locales where OrderItem.category_id = category_locales.category_id and category_locales.lang_code = 'zh'";

        $orders = $this->OrderItem->find("all", 
            array(
                'fields'=>array('Order.message','Order.table_no', 'Order.table_status', 'Order.order_type', 'Order.order_no', 'Order.created as order_created', 'OrderItem.*'),
                'conditions'=>array(
                    'Order.cashier_id'=>$user['Cook']['restaurant_id'], 'Order.is_completed'=>'N', 'Order.is_kitchen'=>'Y'
                    ),
                )
            );

        // categories all records
        $items_array = [];
        if(!empty($orders)){
            foreach ($orders as $key => $value) {

                # prepare order by category name
                $item = $value['OrderItem'];
                $item['message'] = $value['Order']['message'];
                $item['table_no'] = $value['Order']['table_no'];
                $item['order_no'] = $value['Order']['order_no'];
                $item['order_type'] = $value['Order']['order_type'];
                $item['order_created'] = $value['Order']['order_created'];
                $item['time_ago'] = $this->timeAgo(strtotime($value['Order']['order_created']));

                $items_array[$item['category_name_en']."|||".$item['category_name_zh']."|||".$item['category_id']][$item['order_id']][] = $item;
            }
        }

        $type = @$this->params['named']['type'];

        // resort elemets array
        if($type == 'finished') {
            foreach ($items_array as $key => $records) {
                # code...
                foreach($records as $order_id => $value) {
                    $finished = 1;
                    foreach($value as $item) {
                        if($item['is_done'] == 'N')
                            $finished = 0;
                    }
                    if(!$finished) {
                        unset($items_array[$key][$order_id]);
                    }
                }

            }
        } else {
            foreach ($items_array as $key => $records) {
                # code...
                foreach($records as $order_id => $value) {
                    $finished = 1;
                    foreach($value as $item) {
                        if($item['is_done'] == 'N')
                            $finished = 0;
                    }
                    if($finished) {
                        unset($items_array[$key][$order_id]);
                    }
                }

            }
        }

        // unset category list
        foreach ($items_array as $key => $records) {
            # code...
            if(empty($records))
               unset($items_array[$key]); 
        }
        $this->set(compact('items_array', 'type'));
    }

   function doneitem() {
        $this->layout = false;
        $this->autoRender = NULL;
        
        // get all params
        $item_id = $this->data['item_id'];

        // check item if already done or not
        $this->loadModel('OrderItem');
        $item_detail = $this->OrderItem->find("first", 
            array(
                'fields'=>array('OrderItem.is_done'),
                'conditions'=>array('OrderItem.id'=>$item_id),
            )
        );
        if($item_detail['OrderItem']['is_done'] == 'Y') {
            $data['OrderItem']['is_done'] = 'N';
        } else{
            $data['OrderItem']['is_done'] = 'Y';
        }
        // save order to database        
        $data['OrderItem']['id'] = $item_id;
        $this->OrderItem->save($data, false);
        if( $data['OrderItem']['is_done'] == 'N') 
            echo json_encode(array('done'=>false));
        else            
            echo json_encode(array('done'=>true));
        
    }

    function doneallitem() {
        $this->layout = false;
        $this->autoRender = NULL;
        
        // get all params
        $item_ids = explode(",", $this->data['item_id']);

        // save order to database    
        foreach ($item_ids as $key => $value) {
                # code...
            $data['OrderItem']['id'] = $value;
            $data['OrderItem']['is_done'] = 'Y';
            $this->loadModel('OrderItem');
            $this->OrderItem->save($data, false);
        }    
        
        echo true;
    }

    function recookallitem() {
        $this->layout = false;
        $this->autoRender = NULL;
        
        // get all params
        $item_ids = explode(",", $this->data['item_id']);

        // save order to database    
        foreach ($item_ids as $key => $value) {
                # code...
            $data['OrderItem']['id'] = $value;
            $data['OrderItem']['is_done'] = 'N';
            $this->loadModel('OrderItem');
            $this->OrderItem->save($data, false);
        }    
        
        echo true;
    }

    public function order() {
        // get all recepie items according to category
        $this->loadModel('Category');
        $this->loadModel('Cousine');

        // get cashier details        
        $this->loadModel('Cashier');
        $cashier_detail = $this->Cashier->find("first", 
            array(
                'fields'=>array('Cashier.firstname', 'Cashier.lastname', 'Cashier.id', 'Cashier.image', 'Cashier.restaurant_id'),
                'conditions'=>array('Cashier.id'=>$this->Session->read('Front.id')),
                )
            );

        $this->Category->bindModel(
            array(
                'hasMany'=>array(
                        'Cousine' => array(
                            'className' => 'Cousine',
                            'foreignKey' => 'category_id',
                            'conditions'=>array('Cousine.status'=>'A', 'Cousine.restaurant_id'=>$cashier_detail['Cashier']['restaurant_id']),
                            
                        )
                    )
                )
            );
        $this->Cousine->virtualFields['eng_name'] = "Select name from cousine_locals where cousine_locals.parent_id = Cousine.id and lang_code = 'en'";
        $this->Cousine->virtualFields['zh_name'] = "Select name from cousine_locals where cousine_locals.parent_id = Cousine.id and lang_code = 'zh'";

        $this->Category->virtualFields['eng_name'] = "Select name from category_locales where category_locales.category_id = Category.id and lang_code = 'en'";
        $this->Category->virtualFields['zh_name'] = "Select name from category_locales where category_locales.category_id = Category.id and lang_code = 'zh'";

        $records = $this->Category->find("all", 
            array(

                // 'fields'=>array('Admin.table_size', 'Admin.takeout_table_size', 'Admin.waiting_table_size'),
                'conditions'=>array('Category.status'=>'A')
                )
            );

        // get popular dishes
        $this->loadModel('Cousine');
        $this->Cousine->virtualFields['eng_name'] = "Select name from cousine_locals where cousine_locals.parent_id = Cousine.id and lang_code = 'en'";
        $this->Cousine->virtualFields['zh_name'] = "Select name from cousine_locals where cousine_locals.parent_id = Cousine.id and lang_code = 'zh'";
        $populars = $this->Cousine->find("all", 
            array(
                'conditions'=>array('Cousine.status'=>'A', 'Cousine.restaurant_id'=>$cashier_detail['Cashier']['restaurant_id']),
                'order'=>'Cousine.popular DESC',
                'recursive'=>-1,
                'limit'=>30
                )
            );

        $type = $this->params['named']['type'];
        $table = $this->params['named']['table'];

        // get order detail
        $this->loadModel('Order');
        $conditions = array('Order.cashier_id'=>$this->Session->read('Front.id'),
                        'Order.table_no'=>$table,
                        'Order.is_completed'=>'N',
                        'Order.order_type'=>$type
                    );
        $Order_detail = $this->Order->find("first", 
            array(
                'fields'=>array('Order.order_no'),
                'conditions'=> $conditions,
                'recursive'=>-1                
                )
            );  

        $this->set(compact('records', 'cashier_detail', 'table', 'type', 'populars', 'Order_detail'));
    }

    public function dashboard() {
        return $this->redirect(array('controller' => 'homes', 'action' => 'cookings'));
    }

    public function donepayment() {

        $this->layout = false;
        $this->autoRender = NULL;
        
        // get all params
        $order_id = $this->data['order_id'];
        $table = $this->data['table'];
        $type = $this->data['type'];
        $paid_by = strtoupper($this->data['paid_by']);

        $pay = $this->data['pay'];
        $change = $this->data['change'];

        // save order to database        
        $data['Order']['id'] = $order_id;
        $data['Order']['table_status'] = 'P';
        $data['Order']['paid_by'] = $paid_by;

        $data['Order']['paid'] = $pay;
        $data['Order']['change'] = $change;
        $data['Order']['is_kitchen'] = 'Y';
        $this->loadModel('Order');
        $this->Order->save($data, false);

        // update popularity status
        $this->loadModel('Cousine');
        $this->Cousine->query("UPDATE cousines set `popular` = `popular`+1 where id in(SELECT (item_id) from order_items where order_id = '$order_id')");    

        // save all 
        $this->Session->setFlash('Order successfully completed.', 'success');
        echo true;
    }

    
    public function makeavailable() {

        $this->layout = false;
        $this->autoRender = NULL;
        
        // get all params
        $order_no = $this->params['named']['order'];

        $this->loadModel('Order');
        $this->Order->updateAll(array('is_completed'=>"'Y'"), array('Order.order_no'=>$order_no));
        // save all 
        $this->Session->setFlash('Table successfully marked as available.', 'success');
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
        $this->Order->updateAll(array('table_no'=>$table, 'order_type'=>"'".$type."'"), array('Order.order_no'=>$order_no));
        $this->Session->setFlash('Order table successfully changed.', 'success');
        if($ref)
            return $this->redirect(array('controller' => 'homes', 'action' => 'pay', 'table'=>$table, 'type'=>$type));
        else
            return $this->redirect(array('controller' => 'homes', 'action' => 'dashboard'));

    }

    public function inquiry() {
    }


    public function additems() {

        $this->layout = false;
        // $this->autoRender = NULL;

        // get tax details        
        $this->loadModel('Cashier');
        $tax_detail = $this->Cashier->find("first", 
            array(
                'fields'=>array('Admin.tax'),
                'conditions'=>array('Cashier.id'=>$this->Session->read('Front.id'))
                )
            );

        // get all params
        $item_id = $this->data['item_id'];
        $table = $this->data['table'];
        $type = $this->data['type'];


        // get item details        
        $this->loadModel('Cousine');
        $item_detail = $this->Cousine->find("first", 
            array(
                'fields'=>array('Cousine.price', 'Category.id', 'Cousine.is_tax'),
                'conditions'=>array('Cousine.id'=>$item_id)
                )
            );

        // check the item already exists or not
        $this->loadModel('Order');
        $this->loadModel('OrderItem');
        $Order_detail = $this->Order->find("first", 
            array(
                'fields'=>array('Order.id', 'Order.subtotal', 'Order.total', 'Order.tax_amount'),
                'conditions'=>array('Order.cashier_id'=>$this->Session->read('Front.id'),
                    'Order.table_no'=>$table,
                    'Order.is_completed'=>'N',
                    'Order.order_type'=>$type
                    )
                )
            );

        if(empty($Order_detail)) {

            // to create a new order
            $insert_data = array(
                'cashier_id'=>$this->Session->read('Front.id'),
                'table_no'=>$table,
                'is_completed'=>'N',
                'order_type'=>$type,
                'tax'=>$tax_detail['Admin']['tax'],
                'created'=>date('Y-m-d H:i:s')
                );
            $this->Order->save($insert_data, false);
            $order_id = $this->Order->getLastInsertId();

            // update order no            
            $data['Order']['id'] = $order_id;
            $data['Order']['order_no'] = str_pad($order_id, 5, rand(98753,87563), STR_PAD_LEFT);
            $this->Order->save($data, false);

        } else {
           $order_id = $Order_detail['Order']['id'];
        }

        // add items to order items db table
        $insert_data = array(
            'order_id'=>$order_id,
            'item_id'=>$item_id,
            'name_en'=>$item_detail['CousineLocal'][0]['name'],
            'name_xh'=>$item_detail['CousineLocal'][1]['name'],
            'price'=>$item_detail['Cousine']['price'],
            'category_id'=>$item_detail['Category']['id'],
            'created'=>date('Y-m-d H:i:s'),
            'all_extras' => !empty($item_detail['Extra'])?json_encode($item_detail['Extra']):"",
            'tax'=>$tax_detail['Admin']['tax'],
            'tax_amount'=>($item_detail['Cousine']['is_tax'] == 'Y'?($tax_detail['Admin']['tax']*$item_detail['Cousine']['price']/100):0),
            );
        $insert_data['qty'] = 1;
        if(!empty($Order_detail)) {

            // check the product already exists or not
            $order_item_detail = $this->OrderItem->find("first", 
                array(
                    'fields'=>array('OrderItem.qty', 'OrderItem.id'),
                    'conditions'=>array('OrderItem.item_id'=>$item_id, 'OrderItem.order_id'=>$order_id)
                    )
            );
            // if(!empty($order_item_detail)) {
            //     $insert_data['qty'] = $order_item_detail['OrderItem']['qty'] + 1;
            //     $insert_data['id'] = $order_item_detail['OrderItem']['id'];
            // }
        }
        $this->OrderItem->save($insert_data, false);

        // update order amount        
        $data = array(); 
        $data['Order']['id'] = $order_id;
        $data['Order']['subtotal'] = @$Order_detail['Order']['subtotal'] + $item_detail['Cousine']['price'];
        $data['Order']['tax'] = $tax_detail['Admin']['tax'];
        $data['Order']['tax_amount'] = (@$Order_detail['Order']['tax_amount'] + $insert_data['tax_amount']);
        $data['Order']['total'] = $data['Order']['subtotal'] + $data['Order']['tax_amount'];
        $this->Order->save($data, false);


        $this->OrderItem->virtualFields['image'] = "Select image from cousines where cousines.id = OrderItem.item_id";
        $Order_detail = $this->Order->find("first", 
            array(
                'fields'=>array('Order.order_no','Order.tax','Order.tax_amount','Order.subtotal','Order.total','Order.message'),
                'conditions'=>array('Order.cashier_id'=>$this->Session->read('Front.id'),
                    'Order.table_no'=>$table,
                    'Order.is_completed'=>'N',
                    'Order.order_type'=>$type
                    )
                )
            );


        // get cashier details        
        $this->loadModel('Cashier');
        $cashier_detail = $this->Cashier->find("first", 
            array(
                'fields'=>array('Cashier.firstname', 'Cashier.lastname', 'Cashier.id', 'Cashier.image'),
                'conditions'=>array('Cashier.id'=>$this->Session->read('Front.id'))
                )
            );

        $this->set(compact('Order_detail', 'cashier_detail'));
        $this->render('summarypanel');
    }


    public function updateordermessage() {

        // get all params
        $order_id = $this->data['order_id'];
        $message = $this->data['message'];
        $is_kitchen = $this->data['is_kitchen'];

        $this->layout = false;
        $this->autoRender = NULL;

        // update message in order table
        $this->loadModel('Order');     
        $data = array(); 
        $data['Order']['id'] = $order_id;
        $data['Order']['message'] = $message;
        $data['Order']['is_kitchen'] = $is_kitchen;
        $this->Order->save($data, false);
        if($is_kitchen == 'Y')
            $this->Session->setFlash('Cooking items successfully sent to kitchen.', 'success');
        echo true;
    }

    public function removeitem() {

        // get all params
        $item_id = $this->data['item_id'];
        $table = $this->data['table'];
        $order_id = $this->data['order_id'];
        $type = $this->data['type'];

        $this->layout = false;
        // $this->autoRender = NULL;

        // get tax details        
        $this->loadModel('OrderItem');
        $item_detail = $this->OrderItem->find("first", 
            array(
                'fields'=>array('OrderItem.price','OrderItem.extras_amount','OrderItem.qty','OrderItem.id', 'OrderItem.tax_amount'),
                'conditions'=>array('OrderItem.id'=>$item_id)
                )
            );

        if($item_detail['OrderItem']['qty'] > 1) {
            // update item quantity
            $update_qty['qty'] = $item_detail['OrderItem']['qty'] - 1;
            $update_qty['id'] = $item_detail['OrderItem']['id'];
            $this->OrderItem->save($update_qty, false);

        } else {
            // delete item details        
            $this->OrderItem->delete($item_id);
        }
        // check the item already exists or not
        $this->loadModel('Order');
        $Order_detail = $this->Order->find("first", 
            array(
                'fields'=>array('Order.id', 'Order.subtotal', 'Order.total', 'Order.tax', 'Order.tax_amount'),
                'conditions'=>array('Order.cashier_id'=>$this->Session->read('Front.id'),
                    'Order.table_no'=>$table,
                    'Order.is_completed'=>'N',
                    'Order.order_type'=>$type
                    )
                )
            );

        // update order amount        
        $data = array(); 
        $data['Order']['id'] = $order_id;
        $data['Order']['subtotal'] = @$Order_detail['Order']['subtotal'] - $item_detail['OrderItem']['price'] - $item_detail['OrderItem']['extras_amount'] ;
        $data['Order']['tax_amount'] = ($Order_detail['Order']['tax_amount'] - $item_detail['OrderItem']['tax_amount']);
        $data['Order']['total'] = $data['Order']['subtotal'] + $data['Order']['tax_amount'];
        $this->Order->save($data, false);


        $this->OrderItem->virtualFields['image'] = "Select image from cousines where cousines.id = OrderItem.item_id";
        $Order_detail = $this->Order->find("first", 
            array(
                'fields'=>array('Order.order_no','Order.tax','Order.tax_amount','Order.subtotal','Order.total','Order.message'),
                'conditions'=>array('Order.cashier_id'=>$this->Session->read('Front.id'),
                    'Order.table_no'=>$table,
                    'Order.is_completed'=>'N',
                    'Order.order_type'=>$type
                    )
                )
            );


        // get cashier details        
        $this->loadModel('Cashier');
        $cashier_detail = $this->Cashier->find("first", 
            array(
                'fields'=>array('Cashier.firstname', 'Cashier.lastname', 'Cashier.id', 'Cashier.image'),
                'conditions'=>array('Cashier.id'=>$this->Session->read('Front.id'))
                )
            );

        $this->set(compact('Order_detail', 'cashier_detail'));
        $this->render('summarypanel');
    }


    public function add_extras() {

        // get all params
        $item_id = $this->data['item_id'];
        $table = $this->data['table'];
        $type = $this->data['type'];
        $extras = $this->data['extras']; #comma separated

        $this->layout = false;
        // $this->autoRender = NULL;

        // get tax details        
        $this->loadModel('OrderItem');
        $item_detail = $this->OrderItem->find("first", 
            array(
                'fields'=>array('OrderItem.price','OrderItem.extras_amount','OrderItem.qty','OrderItem.id', 'OrderItem.all_extras'),
                'conditions'=>array('OrderItem.id'=>$item_id)
                )
            );

        // get all selected extras prices
        $extras_amount = 0;
        $selected_extras = "";
        if($extras) {
            $extras = explode(",", $extras);
            $all_extras = json_decode($item_detail['OrderItem']['all_extras'], true);
            foreach ($all_extras as $key => $value) {
                # code...
                if(in_array($value['id'], $extras)) {
                    $extras_amount += $value['price'];
                    $selected_extras[] = array('id'=>$value['id'], 'price'=>$value['price'], 'name'=>$value['name']." ".$value['name_zh']);
                }
            }
        }
        // save data to items table        
        $update_orderitem['selected_extras'] = $selected_extras?json_encode($selected_extras):"";
        $update_orderitem['extras_amount'] = $extras_amount;
        $update_orderitem['id'] = $item_id;
        $this->OrderItem->save($update_orderitem, false);

        // update all order amount to order table
        $this->loadModel('Order');
        $Order_detail = $this->Order->find("first", 
            array(
                'fields'=>array('Order.id', 'Order.subtotal', 'Order.total', 'Order.tax', 'Order.tax_amount'),
                'conditions'=>array('Order.cashier_id'=>$this->Session->read('Front.id'),
                    'Order.table_no'=>$table,
                    'Order.is_completed'=>'N',
                    'Order.order_type'=>$type
                    )
                )
            );

        // update order amount        
        $data = array(); 
        $data['Order']['id'] = $Order_detail['Order']['id'];
        $data['Order']['subtotal'] = @$Order_detail['Order']['subtotal'] - $item_detail['OrderItem']['extras_amount'] + $extras_amount;
        // $data['Order']['tax_amount'] = ($data['Order']['subtotal']*$Order_detail['Order']['tax']/100);
        $data['Order']['total'] = $data['Order']['subtotal'] + $Order_detail['Order']['tax_amount'];
        $this->Order->save($data, false);


        $this->OrderItem->virtualFields['image'] = "Select image from cousines where cousines.id = OrderItem.item_id";
        $Order_detail = $this->Order->find("first", 
            array(
                'fields'=>array('Order.order_no','Order.tax','Order.tax_amount','Order.subtotal','Order.total','Order.message'),
                'conditions'=>array('Order.cashier_id'=>$this->Session->read('Front.id'),
                    'Order.table_no'=>$table,
                    'Order.is_completed'=>'N',
                    'Order.order_type'=>$type
                    )
                )
            );


        // get cashier details        
        $this->loadModel('Cashier');
        $cashier_detail = $this->Cashier->find("first", 
            array(
                'fields'=>array('Cashier.firstname', 'Cashier.lastname', 'Cashier.id', 'Cashier.image'),
                'conditions'=>array('Cashier.id'=>$this->Session->read('Front.id'))
                )
            );

        $this->set(compact('Order_detail', 'cashier_detail'));
        $this->render('summarypanel');
    }

    public function summarypanel($table, $type) {

        $this->layout = false;

        // get order details 
        $this->loadModel('Order');
        $this->loadModel('OrderItem');

        $this->OrderItem->virtualFields['image'] = "Select image from cousines where cousines.id = OrderItem.item_id";
        $Order_detail = $this->Order->find("first", 
            array(
                'fields'=>array('Order.order_no','Order.tax','Order.tax_amount','Order.subtotal','Order.total','Order.message'),
                'conditions'=>array('Order.cashier_id'=>$this->Session->read('Front.id'),
                    'Order.table_no'=>$table,
                    'Order.is_completed'=>'N',
                    'Order.order_type'=>$type
                    )
                )
            );

        // get cashier details        
        $this->loadModel('Cashier');
        $cashier_detail = $this->Cashier->find("first", 
            array(
                'fields'=>array('Cashier.firstname', 'Cashier.lastname', 'Cashier.id', 'Cashier.image'),
                'conditions'=>array('Cashier.id'=>$this->Session->read('Front.id'))
                )
            );

        $this->set(compact('Order_detail', 'cashier_detail'));
    }

}
