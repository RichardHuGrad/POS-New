      <?php

class Order extends AppModel {

    public $name = 'Order';
    public $validate = array();

    public $hasMany = array(
        'OrderItem' => array(
            'className' => 'OrderItem',
            'foreignKey' => 'order_id'
        ),
        'OrderSplit' => array(
            'className' => 'OrderSplit',
            'foreignKey' => 'order_no'
        ),
    );

    // public $belongsTo = array(
    //     'Admin' => array(
    //         'className' => 'Admin',
    //         'foreignKey' => false,
    //         'conditions' => array('Admin.id = Order.cashier_id')
    //     )
    // );

    // insert a new order in orders
    // return Order.id
    public function insertOrder($cashier_id, $counter_id, $table_no, $order_type, $tax) {
        $insert_data = array(
            'order_no' => $table_no.sprintf("%07d",date('zHi')),
            'cashier_id' => $cashier_id, // cashier should be restaurant_id
            'counter_id' => $counter_id,
            'table_no' => $table_no,
            'is_completed' => 'N',
            'order_type' => $order_type,
            'tax' => $tax,
            'created' => date('Y-m-d H:i:s')
        );
        $this->save($insert_data, false);

        return $this->id;
    }

    public function getOrderIdByOrderNo($order_no) {
        $data = $this->find("first", array(
                'fields' => array('Order.id'),
                'conditions' => array('Order.order_no' => $order_no)
            ));

        return $data['Order']['id'];
    }


    // recalculate order's bill info by count the OrderItem
    // include: subtotal, tax_amount, total, discount

    // edge case: remove all item
    public function updateBillInfo($order_id) {

        $Order_detail = $this->find('first', array(
                'recursive' => -1,
                'conditions' => array('Order.id' => $order_id)
            ));


        // get all items from OrderItem
        $order_item_list = $this->OrderItem->find('all', array(
                'recursive' => -1,
                'conditions' => array('OrderItem.order_id' => $order_id)
            ));

        $data = array();
        $data['Order']['id'] = $order_id;
        $data['Order']['subtotal'] = 0;
        $data['Order']['tax'] = floatval($Order_detail['Order']['tax']);
        $data['Order']['tax_amount'] = 0;
        $data['Order']['total'] = 0;
        $data['Order']['discount_value'] = 0;

        

        foreach ($order_item_list as $order_item) {
            $data['Order']['subtotal'] += ($order_item['OrderItem']['price'] + ($order_item['OrderItem']['extras_amount'] ? $order_item['OrderItem']['extras_amount'] : 0)) * $order_item['OrderItem']['qty'];
        }
        
        if ($Order_detail['Order']['fix_discount'] && $Order_detail['Order']['fix_discount'] > 0) {
            $data['Order']['discount_value'] = $Order_detail['Order']['fix_discount'];
        } else if ($Order_detail['Order']['percent_discount'] && $Order_detail['Order']['percent_discount'] > 0) {
            $data['Order']['discount_value'] = $data['Order']['subtotal'] * $Order_detail['Order']['percent_discount'] / 100;
        }

        $after_discount = $data['Order']['subtotal'] - $data['Order']['discount_value'];

        $after_discount = max(0, $after_discount);
        $data['Order']['after_discount'] = $after_discount;

        // tax should be after discount
        $data['Order']['tax_amount'] = $after_discount * $data['Order']['tax'] / 100;

        $data['Order']['total'] = $after_discount + $data['Order']['tax_amount'];

        $this->save($data, false);
    }


    public function getMergeOrderInfo($order_ids) {
        $data = array(
                "order_nos" => "",
                "table_nos" => "",
                "print_items" => array(),
                "subtotal" => 0, 
                "discount_value" => 0, 
                "after_discount" => 0,
                "tax" => 0, 
                "tax_amount" => 0,
                "total" => 0,
                "paid" => 0,
                "change" => 0,
            );


        $order_nos = array();
        $table_nos = array();
        $printItems = array();

        foreach ($order_ids as $order_id) {
            $Order_detail = $this->find('first', array(
                    'conditions' => array('Order.id' => $order_id)
                ));
            array_push($data['print_items'], $Order_detail['OrderItem']);
            $data['subtotal'] += $Order_detail['Order']['subtotal'];
            $data['discount_value'] += $Order_detail['Order']['discount_value'];
            $data['after_discount'] += $Order_detail['Order']['after_discount'];
            $data['tax'] = $Order_detail['Order']['tax'];
            $data['tax_amount'] += $Order_detail['Order']['tax_amount'];
            $data['total'] += $Order_detail['Order']['total'];
            $data['paid'] += $Order_detail['Order']['paid'];
            $data['change'] += $Order_detail['Order']['change'];
            array_push($order_nos, $Order_detail['Order']['order_no']);
            array_push($table_nos, $Order_detail['Order']['table_no']);
        }

        $data['order_nos'] = implode(",", $order_nos);
        $data['table_nos'] = implode(",", $table_nos);

        return $data;
    } 


    public function getDailyOrderInfo() {
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
        $Orders1 = $this->find("all", array('conditions' => $conditions , 'fields' => $fields ));

        $conditions = array('Order.table_status' => 'P', 'Order.is_completed' => 'Y', 'Order.created >=' => date('c', $tm17), 'Order.created <' => date('c', $tm23));
        $Orders2 = $this->find("all", array('conditions' => $conditions , 'fields' => $fields ));

        $conditions = array('Order.table_status' => 'P', 'Order.is_completed' => 'Y', 'Order.created >=' => date('c', $tm23), 'Order.created <' => date('c', $tm04));
        $Orders3 = $this->find("all", array('conditions' => $conditions , 'fields' => $fields ));
        //print_r($Printer);

        array_push($data, $Order1);
        array_push($data, $Order2);
        array_push($data, $Order3);

        return $data;
    }

}

?>