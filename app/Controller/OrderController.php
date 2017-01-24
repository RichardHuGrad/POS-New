<?php 
App::uses('PrintLib', 'Lib');
class OrderController extends AppController {

    public $components = array('Paginator');

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

        $this->set(compact('records', 'cashier_detail', 'table', 'type', 'populars', 'Order_detail', 'extras', 'extra_categories'));

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
            $order_id = $this->Order->insertOrder($restaurant_id, $this->Session->read('Front.id'), $table, $type, $tax_rate);
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


        $comb_id = $this->Cousine->find("first", array(
                'conditions' => array('Cousine.id' => $item_id)
            ))['Cousine']['comb_num'];
        
        $this->OrderItem->insertOrderItem($order_id, $item_id, $item_detail['CousineLocal'][0]['name'], $item_detail['CousineLocal'][1]['name'], $item_detail['Cousine']['price'], $item_detail['Category']['id'], !empty($extras) ? json_encode($extras) : "", $tax_rate, $tax_amount, 1, $comb_id);


        $this->Order->updateBillInfo($order_id);


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


    public function removeitem() {
        
        $this->layout = false;
        // get cashier details        
        $this->loadModel('Cashier');
        $this->loadModel('OrderItem');
        $this->loadModel('Order');

        // get all params
        $item_id_list = $this->data['selected_item_id_list'];
        $table = $this->data['table'];
        $type = $this->data['type'];
        $order_no = $this->data['order_no'];

        if (empty($item_id_list)) {
            return false;
        }



        $cancel_items = array('K'=> array(), 'C'=>array());


        foreach ($item_id_list as $item_id) {
            // if the item is printed
            // send to kitchen print
            $item_detail = $this->OrderItem->query("SELECT order_items.*,categories.printer FROM  `order_items` JOIN `categories` ON order_items.category_id=categories.id WHERE order_items.id = " . $item_id . " LIMIT 1");
            // print_r($item_detail);

            $is_print = $item_detail[0]['order_items']['is_print'];
            $printer = $item_detail[0]['categories']['printer'];
            if ($is_print == 'Y') {

                $selected_extras_list = json_decode($item_detail[0]['order_items']['selected_extras'], true);
                $selected_extras_arr = array();
                if (!empty($selected_extras_list)) {
                    foreach ($selected_extras_list as $selected_extra) {
                        array_push($selected_extras_arr, $selected_extra['name']);
                    }
                }
                
                $item_detail[0]['order_items']['selected_extras'] = join(',', $selected_extras_arr);
                array_push($cancel_items[$printer], $item_detail[0]['order_items']);

            } // else do nothing


            // delete all item in order_item table 
            $data['id'] = $item_id;
            $this->OrderItem->delete($data);
        }

        // echo json_encode($cancel_items);
        // echo empty($cancel_items['K']);
        if (!empty($cancel_items['K'])) {

            $printerName = $this->Cashier->getKitchenPrinterName( $this->Session->read('Front.id'));
            $print = new PrintLib();
            $print->printCancelledItems($order_no, $table, $type, $printerName, $cancel_items['K'],true, false);
        }
        if (!empty($cancel_items['C'])) {

            $printerName = $this->Cashier->getServicePrinterName( $this->Session->read('Front.id'));
            $print = new PrintLib();
            $print->printCancelledItems($order_no, $table, $type, $printerName, $cancel_items['C'],true, false);
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

        // send removed items to kitchen printer
        // $order_id, $item_list
 
        // update order amount
        // para: order_id, tax_rate
        $this->Order->updateBillInfo($Order_detail['Order']['id']);


        $this->set($this->getAllDBInfo($table, $type));
        $this->render('summarypanel');
    }

    public function urgeItem() {
        
        $this->layout = false;
        // get cashier details        
        $this->loadModel('Cashier');
        $this->loadModel('OrderItem');
        $this->loadModel('Order');

        // get all params
        $item_id_list = $this->data['selected_item_id_list'];
        $table = $this->data['table'];
        $type = $this->data['type'];
        $order_no = $this->data['order_no'];

        if (empty($item_id_list)) {
            return false;
        }



        $cancel_items = array('K'=> array(), 'C'=>array());


        foreach ($item_id_list as $item_id) {
            // if the item is printed
            // send to kitchen print
            $item_detail = $this->OrderItem->query("SELECT order_items.*,categories.printer FROM  `order_items` JOIN `categories` ON order_items.category_id=categories.id WHERE order_items.id = " . $item_id . " LIMIT 1");
            // print_r($item_detail);

            $is_print = $item_detail[0]['order_items']['is_print'];
            $printer = $item_detail[0]['categories']['printer'];
            if ($is_print == 'Y') {

                $selected_extras_list = json_decode($item_detail[0]['order_items']['selected_extras'], true);
                $selected_extras_arr = array();
                if (!empty($selected_extras_list)) {
                    foreach ($selected_extras_list as $selected_extra) {
                        array_push($selected_extras_arr, $selected_extra['name']);
                    }
                }
                
                $item_detail[0]['order_items']['selected_extras'] = join(',', $selected_extras_arr);
                array_push($cancel_items[$printer], $item_detail[0]['order_items']);

            } // else do nothing

        }

        // echo json_encode($cancel_items);
        // echo empty($cancel_items['K']);
        if (!empty($cancel_items['K'])) {

            $printerName = $this->Cashier->getKitchenPrinterName( $this->Session->read('Front.id'));
            $print = new PrintLib();
            $print->printUrgeItemDoc($order_no, $table, $type, $printerName, $cancel_items['K'],true, false);
        }
        if (!empty($cancel_items['C'])) {

            $printerName = $this->Cashier->getServicePrinterName( $this->Session->read('Front.id'));
            $print = new PrintLib();
            $print->printUrgeItemDoc($order_no, $table, $type, $printerName, $cancel_items['C'],true, false);
        }

        // send removed items to kitchen printer
        // $order_id, $item_list
 
        // update order amount
        // para: order_id, tax_rate
        // $this->Order->updateBillInfo($Order_detail['Order']['id']);


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


    public function changePrice() {
        $this->layout = false;
        // get cashier details        
        $this->loadModel('Cashier');
        $this->loadModel('OrderItem');
        $this->loadModel('Order');

        // get all params
        $item_id_list = $this->data['selected_item_id_list'];
        $table = $this->data['table'];
        $type = $this->data['type'];
        $order_no = $this->data['order_no'];
        $price = $this->data['price'];


        if (empty($item_id_list)) {
            return false;
        }

        
        foreach ($item_id_list as $item_id) {
            $itemDetail = $this->OrderItem->find('first',
                    array(
                        'conditions' => array(
                            'OrderItem.id' => $item_id 
                            )
                        )
                );
            $itemDetail['OrderItem']['price'] = $price;

            // print_r($itemDetail);

            $this->OrderItem->save($itemDetail, false);
        }

        // recalculate price
        $order_id = $this->Order->getOrderIdByOrderNo($order_no);
        $this->Order->updateBillInfo($order_id);


        $this->set($this->getAllDBInfo($table, $type));
        $this->render('summarypanel');
    }


    public function changeQuantity() {
        $this->layout = false;

        $this->loadModel('Cashier');
        $this->loadModel('OrderItem');
        $this->loadModel('Order');

        // get all params
        $item_id_list = $this->data['selected_item_id_list'];
        $quantity = $this->data['quantity'];
        $table = $this->data['table'];
        $type = $this->data['type'];
        $order_no = $this->data['order_no'];

        if (empty($item_id_list)) {
            return false;
        }


        foreach ($item_id_list as $item_id) {
            $itemDetail = $this->OrderItem->find('first',
                    array(
                        'conditions' => array(
                            'OrderItem.id' => $item_id 
                            )
                        )
                );
            $itemDetail['OrderItem']['qty'] = $quantity;

            // print_r($itemDetail);

            $this->OrderItem->save($itemDetail, false);
        }
        
        // recalculate price
        $order_id = $this->Order->getOrderIdByOrderNo($order_no);
        $this->Order->updateBillInfo($order_id);


        $this->set($this->getAllDBInfo($table, $type));
        $this->render('summarypanel');

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


    public function batchAddExtras() {

        $this->layout = false;

        $selected_item_id_list = $this->data['selected_item_id_list'];
        $selected_extras_id_list = $this->data['selected_extras_id'];
        $table = $this->data['table'];
        $type = $this->data['type'];


        // get cashier details        
        $this->loadModel('Cashier');
        $cashier_detail = $this->Cashier->find("first", array(
            'fields' => array('Cashier.firstname', 'Cashier.lastname', 'Cashier.id', 'Cashier.image', 'Admin.id'),
            'conditions' => array('Cashier.id' => $this->Session->read('Front.id'))
                )
        );


        $this->loadModel('OrderItem');
        $this->loadModel('Extra');


        $extras_amount = 0;
        
        $selected_extras_list = [];
        foreach ($selected_extras_id_list as $extra_id) {
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

        foreach ($selected_item_id_list as $item_id) {


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

            $this->OrderItem->save($item_detail, false);

            // update extra amount will also incur the updateBillInfo() function
            $this->OrderItem->updateExtraAmount($item_id);

        }


        $this->set($this->getAllDBInfo($table, $type));
        $this->render('summarypanel');
    }

        // overwrite all extras of items
    public function addExtras() {
        $this->layout = false;

        $item_id = $this->data['selected_item_id'];
        // selected_extras_id_list maybe empty
        $selected_extras_id_list = isset($this->data['selected_extras_id']) ?  $this->data['selected_extras_id'] : [];
        $table = $this->data['table'];
        $type = $this->data['type'];


        // get cashier details        
        $this->loadModel('Cashier');
        $cashier_detail = $this->Cashier->find("first", array(
            'fields' => array('Cashier.firstname', 'Cashier.lastname', 'Cashier.id', 'Cashier.image', 'Admin.id'),
            'conditions' => array('Cashier.id' => $this->Session->read('Front.id'))
                )
        );

        $this->loadModel('OrderItem');
        $this->loadModel('Extra');


        $extras_amount = 0;
        
        $selected_extras_list = [];
        foreach ($selected_extras_id_list as $extra_id) {
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
            'fields' => array('OrderItem.id', 'OrderItem.extras_amount', 'OrderItem.selected_extras'),
            'conditions' => array('OrderItem.id' => $item_id)
                )
        );

        $item_detail['OrderItem']['selected_extras'] = json_encode($selected_extras_list);

        $this->OrderItem->save($item_detail, false);

        // update extra amount will also incur the updateBillInfo() function
        $this->OrderItem->updateExtraAmount($item_id);



        $this->set($this->getAllDBInfo($table, $type));
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
            'fields' => array('Order.id','Order.order_no', 'Order.tax', 'Order.tax_amount', 'Order.subtotal', 'Order.total', 'Order.message', 'Order.discount_value', 'Order.promocode', 'Order.fix_discount', 'Order.percent_discount'),
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




}

 ?>