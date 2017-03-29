<?php
App::uses('PrintLib', 'Lib');

class OrderController extends AppController {

    public $components = array('Paginator', 'OrderHandler');

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('index', 'forgot_password');
        $this->layout = "default";
        // array_push($this->components, 'Order');
    }

    public function index() {
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


    public function addItem() {
        $this->layout = false;
        $this->autoRender = NULL;
        // get parameters
        $item_id = $this->data['item_id'];
        $table = $this->data['table'];
        $type = $this->data['type'];
        $cashier_id = $this->Session->read('Front.id');

        $res = $this->OrderHandler->addItem(array('item_id' => $item_id, 'table' => $table, 'type' => $type, 'cashier_id' => $cashier_id));

        return $res;
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
        $this->autoRender = NULL;

        // get all params
        $item_id_list = $this->data['selected_item_id_list'];
        $order_no = $this->data['order_no'];

        $res = $this->OrderHandler->removeItem(array('item_id_list' => $item_id_list, 'order_no' => $order_no, 'cashier_id' => $this->Session->read('Front.id')));

        return $res;
    }

    public function urgeItem() {

        $this->layout = false;
        $this->autoRender = NULL;
        // get cashier details
        $this->loadModel('Cashier');
        $this->loadModel('OrderItem');
        $this->loadModel('Order');

        // get all params
        $item_id_list = $this->data['selected_item_id_list'];
        if (empty($item_id_list)) {
            return false;
        }
        $order_no = $this->data['order_no'];
        $order_id = $this->Order->getOrderIdByOrderNo($order_no);
        $restaurant_id = $this->Cashier->getRestaurantId($this->Session->read('Front.id'));

        $this->Print->printKitchenUrgeItem(array('restaurant_id'=> $restaurant_id, 'order_id'=>$order_id, 'item_id_list'=>$item_id_list));
    }

    public function changePrice() {
        $this->layout = false;
        $this->autoRender = NULL;
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

        $this->OrderHandler->changePrice(array('item_id_list' => $item_id_list, 'table' => $table, 'type' => $type, 'order_no' => $order_no, 'price' => $price));
    }


    public function changeQuantity() {
        $this->layout = false;
        $this->autoRender = NULL;

        $this->OrderHandler->changeQuantity(array(
            'item_id_list' => $this->data['selected_item_id_list'],
            'quantity' =>$this->data['quantity'],
            'table' => $this->data['table'],
            'type' => $this->data['type'],
            'order_no' => $this->data['order_no']
        ));

    }



    public function takeout() {
        $this->layout = false;
        $this->autoRender = NULL;

        $this->OrderHandler->takeout(array(
            'item_id_list' => $this->data['selected_item_id_list'],
            'table' => $this->data['table'],
            'type' => $this->data['type']
        ));
    }


    public function batchAddExtras() {

        $this->layout = false;
        $this->autoRender = NULL;

        $selected_item_id_list = $this->data['selected_item_id_list'];
        $selected_extras_id_list = $this->data['selected_extras_id'];
        $table = $this->data['table'];
        $type = $this->data['type'];
        $special = $this->data['special'];


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

            if (!empty($special)) {
                $item_detail['OrderItem']['special_instruction'] = $special;
            }


            $this->OrderItem->save($item_detail, false);

            // update extra amount will also incur the updateBillInfo() function
            $this->OrderItem->updateExtraAmount($item_id);

        }
    }

        // overwrite all extras of items and special instruction
    public function addExtras() {
        $this->layout = false;
        $this->autoRender = NULL;

        $item_id = $this->data['selected_item_id'];
        // selected_extras_id_list maybe empty
        $selected_extras_id_list = isset($this->data['selected_extras_id']) ?  $this->data['selected_extras_id'] : [];
        $table = $this->data['table'];
        $type = $this->data['type'];
        $special = $this->data['special'];


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
    }


    public function summarypanel($table, $type) {

        $this->layout = false;

        $this->loadModel('Cashier');
        $this->loadModel('OrderItem');
        $this->loadModel('Order');


        $cashier_detail = $this->Cashier->find("first", array(
            'fields' => array('Cashier.firstname', 'Cashier.lastname', 'Cashier.id', 'Cashier.image', 'Admin.id'),
            'conditions' => array('Cashier.id' => $this->Session->read('Front.id'))
                )
        );

        $this->OrderItem->virtualFields['image'] = "Select image from cousines where cousines.id = OrderItem.item_id";
        $Order_detail = $this->Order->find("first", array(
            // 'fields' => array('Order.id','Order.order_no', 'Order.tax', 'Order.tax_amount', 'Order.subtotal', 'Order.after_discount', 'Order.total', 'Order.message', 'Order.discount_value', 'Order.promocode', 'Order.fix_discount', 'Order.percent_discount'),
            'conditions' => array('Order.cashier_id' => $cashier_detail['Admin']['id'],
                'Order.table_no' => $table,
                'Order.is_completed' => 'N',
                'Order.order_type' => $type
            )
                )
        );
        $extras_categories = $this->Order->query("SELECT extrascategories.* FROM `extrascategories` WHERE extrascategories.status = 'A' ");


        if (!empty($Order_detail['Order']['id'])) {
            $this->Order->updateBillInfo($Order_detail['Order']['id']);
        }
        // print_r($Order_detail);
        // print_r($cashier_detail);
        // print_r($extras_categories);

        $this->set(compact('Order_detail', 'cashier_detail','extras_categories'));
    }


    public function printTokitchen() {
        $this->layout = false;
        $this->autoRender = NULL;

        $this->loadModel('Order');
        $this->loadModel('Cashier');

        $order_no = $this->data['order_no'];
        $order_id = $this->Order->getOrderIdByOrderNo($order_no);
        $restaurant_id = $this->Cashier->getRestaurantId($this->Session->read('Front.id'));

        $this->Print->printTokitchen(array('restaurant_id'=> $restaurant_id, 'order_id'=>$order_id));
    }




}

 ?>
