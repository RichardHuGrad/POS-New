<?php 

class OrderController extends AppController {

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
            'fields' => array('Order.order_no', 'Order.order_type'),
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
        
        $query_str = "SELECT extras.* FROM `extras` WHERE extras.category_id = 1";

        $all_tastes = $this->Extra->query($query_str);
        $tastes = array();
        foreach ($all_tastes as $taste){
            array_push($tastes, $taste['extras']);
        }

        $this->set(compact('records', 'cashier_detail', 'table', 'type', 'populars', 'Order_detail', 'tastes'));

        // print_r($tastes);
    }

    public function addItem() {
        $this->layout = false;
        $this->autoRender = NULL;

        // get parameters
        $item_id = $this->data['item_id'];
        $table = $this->data['table'];
        $type = $this->data['type'];

        //get tax_rate
        $this->loadModel('Cashier');

        $admin_detail = $this->Cashier->find("first", array(
            'fields' => array('Admin.tax', 'Admin.id'),
            'conditions' => array('Cashier.id' => $this->Session->read('Front.id'))
                )
        );

        $tax_rate = $admin_detail['Admin']['tax']; // 13
        $restaurant_id = $admin_detail['Admin']['id'];
        // print_r($tax_rate);
        // print_r($restaurant_id);

        // get item details        
        $this->loadModel('Cousine');
        $item_detail = $this->Cousine->find("first", array(
            'fields' => array('Cousine.price', 'Category.id', 'Cousine.is_tax', 'Cousine.comb_num'),
            'conditions' => array('Cousine.id' => $item_id)
                )
        );

        // print_r($item_detail['Cousine']['comb_num']);

        // get comb num
        // ['Cousine']['comb_num'] namely extrascategories.id
        // $get_comb_flag to $item_comb_num
        $get_comb_flag = $this->Cousine->query("SELECT extras_num FROM extrascategories WHERE id = " . $item_detail['Cousine']['comb_num']);
        $show_extras_flag = false;
        if (count($get_comb_flag)>0){
            if ($get_comb_flag[0]['extrascategories']['extras_num']>0){
                $show_extras_flag = true;
            }
        };
        // print_r($get_comb_flag);


        $this->loadModel('Order');
        $this->loadModel('OrderItem');
        $Order_detail = $this->Order->find("first", array(
            'fields' => array('Order.id', 'Order.subtotal', 'Order.total', 'Order.tax_amount', 'Order.discount_value', 'Order.promocode', 'Order.fix_discount', 'Order.percent_discount'),
            'conditions' => array('Order.cashier_id' => $restaurant_id, 'Order.table_no' => $table, 'Order.is_completed' => 'N', 'Order.order_type' => $type )
                )
        );

        // print_r($Order_detail);

        if (empty($Order_detail)) {
            // to create a new order
            $order_id = $this->createOrder($restaurant_id, $this->Session->read('Front.id'), $table, $type, $tax_rate);
        } else {
            $order_id = $Order_detail['Order']['id'];
        }

        $this->loadModel('Cousine');
        $query_str = "SELECT comb_num FROM cousines WHERE id = " . $item_id;
        $comb_num = $this->Cousine->query($query_str);
        $query_str = "SELECT extrascategories.* FROM `extrascategories` WHERE extrascategories.status = 'A'";
        $extras_categories = $this->Order->query($query_str);
        if ($comb_num[0]['cousines']['comb_num'] == 0) {
            $query_str = "SELECT extras.* FROM `extras` JOIN extrascategories ON extras.category_id = extrascategories.id WHERE extras.status = 'A' AND extrascategories.extras_num = 0 ";
        }else{
            $query_str = "SELECT extras.* FROM `extras` JOIN extrascategories ON extras.category_id = extrascategories.id WHERE extras.status = 'A' AND (extrascategories.extras_num = 0 " . " OR extrascategories.id = " . $comb_num[0]['cousines']['comb_num'] . ")";
        }


        $all_extras = $this->Order->query($query_str);
        $extras = array();
        foreach ($all_extras as $exts){
            array_push($extras,$exts['extras']);
        }
        // print_r($all_extras);
        // print_r($extras);

        // print_r($Order_detail['Order']);

        // add items to order items db table

        if ($item_detail['Cousine']['is_tax'] == 'Y') {
            $tax_amount = $tax_rate * $item_detail['Cousine']['price'] / 100;
        } else {
            $tax_amount = 0;
        }

        $this->createOrderItem($order_id, $item_id, $item_detail['CousineLocal'][0]['name'], $item_detail['CousineLocal'][1]['name'], $item_detail['Cousine']['price'], $item_detail['Category']['id'], !empty($extras) ? json_encode($extras) : "", $tax_rate, $tax_amount, 1);


        $this->updateOrderPrice($order_id, $tax_rate);


        $this->OrderItem->virtualFields['image'] = "Select image from cousines where cousines.id = OrderItem.item_id";
        $Order_detail = $this->Order->find("first", array(
            'fields' => array('Order.order_no', 'Order.tax', 'Order.tax_amount', 'Order.subtotal', 'Order.total', 'Order.message', 'Order.discount_value', 'Order.promocode', 'Order.fix_discount', 'Order.percent_discount'),
            'conditions' => array('Order.cashier_id' => $restaurant_id,
                'Order.table_no' => $table,
                'Order.is_completed' => 'N',
                'Order.order_type' => $type
            )
                )
        );

        //Modified by Yishou Liao @ Oct 26 2016.
        $Order_detail_print = $this->Order->query("SELECT order_items.*,categories.printer FROM `orders` JOIN `order_items` ON orders.id =  order_items.order_id JOIN `categories` ON order_items.category_id=categories.id WHERE orders.cashier_id = " . $restaurant_id . " AND  orders.table_no = " . $table . " AND order_items.is_print = 'N' AND orders.is_completed = 'N' AND orders.order_type = '" . $type . "' ");
        
        // get cashier details        
        $this->loadModel('Cashier');
        $cashier_detail = $this->Cashier->find("first", array(
            'fields' => array('Cashier.firstname', 'Cashier.lastname', 'Cashier.id', 'Cashier.image'),
            'conditions' => array('Cashier.id' => $this->Session->read('Front.id'))
                )
        );

        // print_r($data);
        // print_r($Order_detail);
        // print_r ($Order_detail_print);

        $this->set(compact('Order_detail', 'cashier_detail', 'Order_detail_print','extras_categories','show_extras_flag')); //Modified by Yishou Liao @ Dec 13 2016.
        $this->render('summarypanel');

    }

        // get all taste
    public function getAllTaste() {
        $this->loadModel('Cousine');
        $query_str = "SELECT comb_num FROM cousines WHERE id = " . $item_id;
        $comb_num = $this->Cousine->query($query_str);
        $query_str = "SELECT extrascategories.* FROM `extrascategories` WHERE extrascategories.status = 'A'";
        $extras_categories = $this->Order->query($query_str);
        if ($comb_num[0]['cousines']['comb_num'] == 0) {
            $query_str = "SELECT extras.* FROM `extras` JOIN extrascategories ON extras.category_id = extrascategories.id WHERE extras.status = 'A' AND extrascategories.extras_num = 0 ";
        }else{
            $query_str = "SELECT extras.* FROM `extras` JOIN extrascategories ON extras.category_id = extrascategories.id WHERE extras.status = 'A' AND (extrascategories.extras_num = 0 " . " OR extrascategories.id = " . $comb_num[0]['cousines']['comb_num'] . ")";
        };


        $all_extras = $this->Order->query($query_str);
        $extras = array();
        foreach ($all_extras as $exts){
            array_push($extras,$exts['extras']);
        }
    }

    // initialize the order_no
    // return new order_id
    public function createOrder($cashier_id, $counter_id, $table_no, $order_type, $tax) {
        $this->layout = false;
        $this->autoRender = NULL;

        $this->loadModel('Order');

        $insert_data = array(
            'cashier_id' => $cashier_id, // cashier should be restaurant_id
            'counter_id' => $counter_id,
            'table_no' => $table_no,
            'is_completed' => 'N',
            'order_type' => $order_type,
            'tax' => $tax,
            'created' => date('Y-m-d H:i:s')
        );
        $this->Order->save($insert_data, false);
        $order_id = $this->Order->getLastInsertId();

        // update order no            
        $data['Order']['id'] = $order_id;
        // Change to DateTime related $data['Order']['order_no'] = str_pad($order_id, 5, rand(98753, 87563), STR_PAD_LEFT);
        $data['Order']['order_no'] = $table_no.sprintf("%07d",date('zHi'));
        $this->Order->save($data, false);

        return $order_id;
    }

    // update price in Order
    // todo: change the calculation of items 
    public function updateOrderPrice($order_id, $tax_rate) {
        $this->layout = false;
        $this->autoRender = NULL;

        $this->loadModel('Order');
        $this->loadModel('OrderItem');

        $Order_detail = $this->OrderItem->find('first', array(
                            'conditions' => array('Order.id' => $order_id)
                        ));

        // get all items from OrderItem
        $order_item_list = $this->OrderItem->find('all', array(
                            'conditions' => array('OrderItem.order_id' => $order_id)
                        ));

        $data = array();
        $data['Order']['id'] = $order_id;
        $data['Order']['subtotal'] = 0;
        $data['Order']['tax'] = $tax_rate;
        $data['Order']['tax_amount'] = 0;
        $data['Order']['total'] = 0;
        $data['Order']['discount_value'] = 0;

        

        foreach ($order_item_list as $order_item) {
            $data['Order']['subtotal'] += $order_item['OrderItem']['price'] + ($order_item['OrderItem']['extras_amount'] ? $order_item['OrderItem']['extras_amount'] : 0);

        }
        
        if ($Order_detail['Order']['fix_discount'] && $Order_detail['Order']['fix_discount'] > 0) {
            $data['Order']['discount_value'] = $Order_detail['Order']['fix_discount'];
        } else if ($Order_detail['Order']['percent_discount'] && $Order_detail['Order']['percent_discount'] > 0) {
            $data['Order']['discount_value'] = $data['Order']['subtotal'] * $Order_detail['Order']['percent_discount'] / 100;
        }

        $after_discount = $data['Order']['subtotal'] - $data['Order']['discount_value'];

        // tax should be after discount
        $data['Order']['tax_amount'] = $after_discount * $data['Order']['tax'] / 100;

        $data['Order']['total'] = $data['Order']['subtotal'] - $data['Order']['discount_value'] + $data['Order']['tax_amount'];

        $this->Order->save($data, false);
    } 

    // insert data into OrderItem
    public function createOrderItem($order_id, $item_id, $name_en, $name_xh, $price, $category_id, $all_extras, $tax, $tax_amount, $qty) {
        $this->layout = false;
        $this->autoRender = NULL;

        $this->loadModel('OrderItem');

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


        $this->OrderItem->save($insert_data, false);
    }


    public function removeitem() {
        $this->layout = false;
        // get cashier details        
        $this->loadModel('Cashier');
        $this->loadModel('OrderItem');
        $this->loadModel('Order');
        $this->loadModel('OrderItem');

        // get all params
        $item_id_list = $this->data['selected_item_id_list'];
        $table = $this->data['table'];
        $type = $this->data['type'];
        $order_no = $this->data['order_no'];

        if (empty($item_id_list)) {
            return false;
        }

        
        foreach ($item_id_list as $item_id) {
            // if the item is printed
            // send to kitchen print
            $item_detail = $this->OrderItem->query("SELECT order_items.*,categories.printer FROM  `order_items` JOIN `categories` ON order_items.category_id=categories.id WHERE order_items.id = " . $item_id . " LIMIT 1");
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


            // delete all item in order_item table 
            $data['id'] = $item_id;
            $this->OrderItem->delete($data);
        }


        $Order_detail = $this->Order->find("first", array(
                'fields' => array('Order.id', 'Order.tax'),
                'conditions' => array(
                    'Order.order_no' => $order_no,
                    'Order.table_no' => $table,
                    'Order.is_completed' => 'N',
                    'Order.order_type' => $type
                )
            )
        );
 
        // update order amount
        // para: order_id, tax_rate
        $this->updateOrderPrice($Order_detail['Order']['id'], $Order_detail['Order']['tax']);



        // $cashier_detail = $this->Cashier->find("first", array(
        //     'fields' => array('Cashier.firstname', 'Cashier.lastname', 'Cashier.id', 'Cashier.image', 'Admin.id'),
        //     'conditions' => array('Cashier.id' => $this->Session->read('Front.id'))
        //         )
        // );
        
        // $this->OrderItem->virtualFields['image'] = "Select image from cousines where cousines.id = OrderItem.item_id";
        // $Order_detail = $this->Order->find("first", array(
        //     'fields' => array('Order.order_no', 'Order.tax', 'Order.tax_amount', 'Order.subtotal', 'Order.total', 'Order.message', 'Order.discount_value', 'Order.promocode', 'Order.fix_discount', 'Order.percent_discount'),
        //     'conditions' => array('Order.cashier_id' => $cashier_detail['Admin']['id'],
        //         'Order.table_no' => $table,
        //         'Order.is_completed' => 'N',
        //         'Order.order_type' => $type
        //     )
        //         )
        // );
        // $extras_categories = $this->Order->query("SELECT extrascategories.* FROM `extrascategories` WHERE extrascategories.status = 'A' ");
        // $Order_detail_print = $this->Order->query("SELECT order_items.*,categories.printer FROM `orders` JOIN `order_items` ON orders.id =  order_items.order_id JOIN `categories` ON order_items.category_id=categories.id WHERE orders.cashier_id = " . $cashier_detail['Admin']['id'] . " AND  orders.table_no = " . $table . " AND order_items.is_print = 'N' AND orders.is_completed = 'N' AND orders.order_type = '" . $type . "' ");

        
        // $this->set(compact('Order_detail', 'cashier_detail', 'Order_detail_print','extras_categories')); //Modified by Yishou Liao @ Dec 13 2016

        $this->set($this->getAllDBInfo($table, $type));
        $this->render('summarypanel');
    }

    public function getAllDBInfo($table, $type) {
        $this->loadModel('Cashier');
        $this->loadModel('OrderItem');
        $this->loadModel('Order');
        $this->loadModel('OrderItem');
        

        $cashier_detail = $this->Cashier->find("first", array(
            'fields' => array('Cashier.firstname', 'Cashier.lastname', 'Cashier.id', 'Cashier.image', 'Admin.id'),
            'conditions' => array('Cashier.id' => $this->Session->read('Front.id'))
                )
        );
        
        $this->OrderItem->virtualFields['image'] = "Select image from cousines where cousines.id = OrderItem.item_id";
        $Order_detail = $this->Order->find("first", array(
            'fields' => array('Order.order_no', 'Order.tax', 'Order.tax_amount', 'Order.subtotal', 'Order.total', 'Order.message', 'Order.discount_value', 'Order.promocode', 'Order.fix_discount', 'Order.percent_discount'),
            'conditions' => array('Order.cashier_id' => $cashier_detail['Admin']['id'],
                'Order.table_no' => $table,
                'Order.is_completed' => 'N',
                'Order.order_type' => $type
            )
                )
        );
        $extras_categories = $this->Order->query("SELECT extrascategories.* FROM `extrascategories` WHERE extrascategories.status = 'A' ");
        $Order_detail_print = $this->Order->query("SELECT order_items.*,categories.printer FROM `orders` JOIN `order_items` ON orders.id =  order_items.order_id JOIN `categories` ON order_items.category_id=categories.id WHERE orders.cashier_id = " . $cashier_detail['Admin']['id'] . " AND  orders.table_no = " . $table . " AND order_items.is_print = 'N' AND orders.is_completed = 'N' AND orders.order_type = '" . $type . "' ");

        
        return compact('Order_detail', 'cashier_detail', 'Order_detail_print','extras_categories');

    }


    public function takeout() {
        $this->layout = false;

         // get cashier details        
        $this->loadModel('Cashier');
        $this->loadModel('OrderItem');
        $this->loadModel('Order');
        $this->loadModel('OrderItem');

        // get all params
        $item_id_list = $this->data['selected_item_id_list'];
        $table = $this->data['table'];
        $type = $this->data['type'];
        // $order_no = $this->data['order_no'];

        if (empty($item_id_list)) {
            return false;
        }

        foreach ($item_id_list as $item_id) {
            // if the item is printed
            // send to kitchen print
            $item_detail = $this->OrderItem->query("SELECT order_items.*,categories.printer FROM  `order_items` JOIN `categories` ON order_items.category_id=categories.id WHERE order_items.id = " . $item_id . " LIMIT 1");
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


            // set all item in order_item table as is_takeout 'Y' 
            // revert all is_takeout flag
            if ($item_detail[0]['order_items']['is_takeout'] == 'Y') {
                $update_para['is_takeout'] = 'N';
            } else if ($item_detail[0]['order_items']['is_takeout'] == 'N') {
                $update_para['is_takeout'] = 'Y';
            }
            
            $update_para['id'] = $item_id;
            $this->OrderItem->save($update_para, false);
            
            // $this->OrderItem->delete($data);

        }


        $cashier_detail = $this->Cashier->find("first", array(
            'fields' => array('Cashier.firstname', 'Cashier.lastname', 'Cashier.id', 'Cashier.image', 'Admin.id'),
            'conditions' => array('Cashier.id' => $this->Session->read('Front.id'))
                )
        );
        
        $this->OrderItem->virtualFields['image'] = "Select image from cousines where cousines.id = OrderItem.item_id";
        $Order_detail = $this->Order->find("first", array(
            'fields' => array('Order.order_no', 'Order.tax', 'Order.tax_amount', 'Order.subtotal', 'Order.total', 'Order.message', 'Order.discount_value', 'Order.promocode', 'Order.fix_discount', 'Order.percent_discount'),
            'conditions' => array('Order.cashier_id' => $cashier_detail['Admin']['id'],
                'Order.table_no' => $table,
                'Order.is_completed' => 'N',
                'Order.order_type' => $type
            )
                )
        );
        $extras_categories = $this->Order->query("SELECT extrascategories.* FROM `extrascategories` WHERE extrascategories.status = 'A' ");
        $Order_detail_print = $this->Order->query("SELECT order_items.*,categories.printer FROM `orders` JOIN `order_items` ON orders.id =  order_items.order_id JOIN `categories` ON order_items.category_id=categories.id WHERE orders.cashier_id = " . $cashier_detail['Admin']['id'] . " AND  orders.table_no = " . $table . " AND order_items.is_print = 'N' AND orders.is_completed = 'N' AND orders.order_type = '" . $type . "' ");

        
        $this->set(compact('Order_detail', 'cashier_detail', 'Order_detail_print','extras_categories')); //Modified by Yishou Liao @ Dec 13 2016
        $this->render('summarypanel');
    }


        // add discount function
    public function add_discount() {
        $this->layout = false;
        $this->autoRender = NULL;
        //Modified by Yishou Liao @ Nov 19 2016
        $discount_type = -1;
        $discount_value = -1;
        //End
        // get all params
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
            $this->loadModel('Order');
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

        echo json_encode($response);
    }

    // remove items discount
    public function remove_discount() {

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
        
        $this->set(compact('Order_detail', 'cashier_detail','extras_categories'));
        $this->render('summarypanel');
    }


    public function summarypanel($table, $type) {

        // get cashier details        
        $this->loadModel('Cashier');
        $cashier_detail = $this->Cashier->find("first", array(
            'fields' => array('Cashier.firstname', 'Cashier.lastname', 'Cashier.id', 'Cashier.image', 'Admin.id'),
            'conditions' => array('Cashier.id' => $this->Session->read('Front.id'))
                )
        );

        // get cashier details        
        $this->loadModel('Cashier');

        $this->layout = false;

        // get order details 
        $this->loadModel('Order');
        $this->loadModel('OrderItem');

        $this->OrderItem->virtualFields['image'] = "Select image from cousines where cousines.id = OrderItem.item_id";
        $Order_detail = $this->Order->find("first", array(
            'fields' => array('Order.order_no', 'Order.tax', 'Order.tax_amount', 'Order.subtotal', 'Order.total', 'Order.message', 'Order.discount_value', 'Order.promocode', 'Order.fix_discount', 'Order.percent_discount'),
            'conditions' => array('Order.cashier_id' => $cashier_detail['Admin']['id'],
                'Order.table_no' => $table,
                'Order.is_completed' => 'N',
                'Order.order_type' => $type
            )
                )
        );

        //Modified by Yishou LIao @ Oct 26 2016.
        $Order_detail_print = $this->Order->query("SELECT order_items.*,categories.printer FROM `orders` JOIN `order_items` ON orders.id =  order_items.order_id JOIN `categories` ON order_items.category_id=categories.id WHERE orders.cashier_id = " . $cashier_detail['Admin']['id'] . " AND  orders.table_no = " . $table . " AND order_items.is_print = 'N' AND orders.is_completed = 'N' AND orders.order_type = '" . $type . "' ");

        //Modified by Yishou Liao @ Dec 04 2016
        $extras_categories = $this->Order->query("SELECT extrascategories.* FROM `extrascategories` WHERE extrascategories.status = 'A' ");
        //End
        
        //Modified by Yishou Liao @ Dec 09 & Dec 13 2016
        /*$all_extras = $this->Order->query("SELECT extras.* FROM `extras` WHERE extras.status = 'A' ");
        $extras = array();
        foreach ($all_extras as $exts){
                array_push($extras,$exts['extras']);
        }*/
        //End @ Dec 13 2016
        
        $this->set(compact('Order_detail', 'cashier_detail', 'Order_detail_print','extras_categories'));
        //End @ Dec 09 2016
    }

}

 ?>