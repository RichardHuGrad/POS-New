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
            'order_no' => $order_type.$table_no.date('ymdHi'),
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
                "paid_by"=>"",
            );


        $order_nos = array();
        $table_nos = array();
        $printItems = array();

        foreach ($order_ids as $order_id) {
            $Order_detail = $this->find('first', array(
                    'conditions' => array('Order.id' => $order_id)
                ));
            // array_push($data['print_items'], $Order_detail['OrderItem']);
            array_push($data['print_items'], $this->OrderItem->getMergedItems($order_id));
            $data['subtotal'] += $Order_detail['Order']['subtotal'];
            $data['discount_value'] += $Order_detail['Order']['discount_value'];
            $data['after_discount'] += $Order_detail['Order']['after_discount'];
            $data['tax'] = $Order_detail['Order']['tax'];
            $data['tax_amount'] += $Order_detail['Order']['tax_amount'];
            $data['total'] += $Order_detail['Order']['total'];
            $data['paid'] += $Order_detail['Order']['paid'];
            $data['change'] += $Order_detail['Order']['change'];
            $data['paid_by'] = $Order_detail['Order']['paid_by'];

            array_push($order_nos, $Order_detail['Order']['order_no']);
            array_push($table_nos, $Order_detail['Order']['table_no']);
        }

        $data['order_nos'] = implode(",", $order_nos);
        $data['table_nos'] = implode(",", $table_nos);

        return $data;
    }


    public function getDailyOrderInfo($timeline_arr) {

        $data = array();
        for ($i = 0; $i < count($timeline_arr) - 1; ++$i) {
            $conditions =
            $Orders = $this->find("all", array(
                'recursive' => -1,
                'fields' =>  array('Order.order_no', 'Order.cashier_id', 'Order.table_no', 'Order.total', 'Order.paid', 'Order.cash_val', 'Order.card_val', 'Order.tax_amount', 'Order.discount_value', 'Order.percent_discount','Order.paid_by', 'Order.tip','Order.tip_paid_by', 'Order.change'),
                'conditions' => array('Order.table_status' => 'P', 'Order.is_completed' => 'Y', 'Order.created >=' => date('c', $timeline_arr[$i]), 'Order.created <' => date('c', $timeline_arr[$i + 1]))

                ));

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
                'tax' => 0,
                'real_total' => 0,
                'order_num' => sizeof($Orders),
                'start_time' => $timeline_arr[$i],
                'end_time' => $timeline_arr[$i + 1],
                );

            foreach ($Orders as $o) {
                $order = $o['Order'];
                $totalArr['paid_cash_total'] += $order['cash_val'] - $order['change'];
                $totalArr['paid_card_total'] += $order['card_val'];

                $totalArr['total'] += $order['total'];

                if ($order['paid_by'] == 'CASH') { // CARD, CASH, MIXED and NO TIP
                    $totalArr['cash_total'] += $order['total'];
                } else if ($order['paid_by'] == 'CARD') { // CARD, CASH, MIXED and NO TIP
                    $totalArr['card_total'] += $order['total'];
                } else {
                    $totalArr['card_mix_total'] += $order['card_val'];
                    $totalArr['cash_mix_total'] += $order['total'] - $order['card_val'];
                }
                $totalArr['total_tip'] += $order['tip'];
                if ($order['tip_paid_by'] == 'CASH') { // CARD, CASH, MIXED and NO TIP
                    $totalArr['cash_tip_total'] += $order['tip'];
                } else if ($order['tip_paid_by'] == 'CARD') {
                    $totalArr['card_tip_total'] += $order['tip'];
                } else { // MIX
                    $totalArr['mix_tip_total'] += $order['tip'];
                }
                $totalArr['tax'] += $order['tax_amount'];
            }

            $totalArr['real_total'] = $totalArr['paid_cash_total'] + $totalArr['paid_card_total'];

            array_push($data, $totalArr);
        }

        return $data;
    }

}

?>
