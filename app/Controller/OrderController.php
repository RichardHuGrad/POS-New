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
        $this->loadModel('CousineExtrascategories');
        
        
        if (empty($this->Session->read('Front.id'))) {
            return $this->redirect($this->Auth->logout());
        }

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
            'conditions' => array('Category.status' => 'A'),
        	'order' => array('Category.orderby'),
        ));
        foreach ($records as $key1 => $rc1) {
        	foreach ($rc1['Cousine'] as $key2 => $rc2) {
        		$rc3 = $this->CousineExtrascategories->find('list', array('fields' => array('extrascategorie_id'), 'conditions' => array('cousine_id' => $rc2['id'])));
        		$records[$key1]['Cousine'][$key2]['extrascategories'] = array_values($rc3);
        		sort($records[$key1]['Cousine'][$key2]['extrascategories']);
        	}
        }

        // get popular dishes
        $this->Cousine->virtualFields['eng_name'] = "Select name from cousine_locals where cousine_locals.parent_id = Cousine.id and lang_code = 'en'";
        $this->Cousine->virtualFields['zh_name'] = "Select name from cousine_locals where cousine_locals.parent_id = Cousine.id and lang_code = 'zh'";
        $populars = $this->Cousine->find("all", array(
            'conditions' => array('Cousine.status' => 'A', 'Cousine.restaurant_id' => $cashier_detail['Cashier']['restaurant_id']),
            'order' => 'Cousine.popular DESC',
            'recursive' => -1,
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
            'fields' => array('Order.order_no', 'Order.order_type', 'Order.table_status', 'Order.id', 'Order.phone', 'Order.message', 'Order.reason'),
            'conditions' => $conditions,
            'recursive' => -1
                )
        );

        $this->loadModel('Extra');
        $taste = $this->Extra->find('all', array(
                'conditions'=> array('Extra.category_id' => 1)
        ));

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


        //if(!empty($Order_detail['Order']['id'])) {
        //  $this->Order->updateBillInfo($Order_detail['Order']['id']);
        //}
        
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


    public function removeitem() {
        $this->layout = false;
        $this->autoRender = NULL;

        $res = $this->OrderHandler->removeItem(array(
            'item_id_list' => $this->data['selected_item_id_list'],
            'order_no' => $this->data['order_no'],
            'cashier_id' => $this->Session->read('Front.id')
        ));

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
        $order_no = $this->data['order_no'];
        $order_id = $this->Order->getOrderIdByOrderNo($order_no);
        $restaurant_id = $this->Cashier->getRestaurantId($this->Session->read('Front.id'));

        $this->Print->printKitchenUrgeItem(array('restaurant_id'=> $restaurant_id, 'order_id'=>$order_id, 'item_id_list'=>$item_id_list));
    }

    public function changePrice() {
        $this->layout = false;
        $this->autoRender = NULL;

        $this->OrderHandler->changePrice(array(
            'item_id_list' => $this->data['selected_item_id_list'],
            'table' => $this->data['table'],
            'type' => $this->data['type'],
            'order_no' => $this->data['order_no'],
            'price' => $this->data['price']
        ));
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

        $this->OrderHandler->batchAddExtras(array(
            'item_id_list' => $this->data['selected_item_id_list'],
            'extra_id_list' => $this->data['selected_extras_id'],
            'table' => $this->data['table'],
            'type' => $this->data['type'],
            'special' => $this->data['special'],
            'cashier_id' => $this->Session->read('Front.id')
        ));
    }

        // overwrite all extras of items and special instruction
    public function addExtras() {
        $this->layout = false;
        $this->autoRender = NULL;
                
        $this->OrderHandler->addExtras(array(
            'item_id' => $this->data['selected_item_id'],
            'extra_id_list' => $this->data['selected_extras_id'],
            'table' => $this->data['table'],
            'type' => $this->data['type'],
            'special' => $this->data['special'],
            'cashier_id' => $this->Session->read('Front.id')
        ));
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
           //$this->Order->updateBillInfo($Order_detail['Order']['id']);
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

    public function editPhone() {
        $this->layout = false;
        $this->autoRender = NULL;

        $this->loadModel('Cashier');
        $this->loadModel('Order');

        // get all params
        $phone = $this->data['phone'];
        $order_no = $this->data['order_no'];
        
        $order_id = $this->Order->getOrderIdByOrderNo($order_no);
        
        $this->Order->query("UPDATE orders set `phone` = '$phone' where id = $order_id");

        $this->Session->setFlash('Order successfully completed.', 'success');
    }



}

 ?>
