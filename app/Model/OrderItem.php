<?php

class OrderItem extends AppModel {

    public $name = 'OrderItem';
    public $validate = array();

    public $belongsTo = array(
        'Order' => array(
            'className' => 'Order',
            'foreignKey' => 'order_id'
        ),
    );

    // public  $virtualFields = array('item_id_count' => 'COUNT(OrderItem.item_id)');


	public function getOrderItemPrintStatus($order_id) {

    }

    public function insertOrderItem($order_id, $item_id, $name_en, $name_xh, $price, $category_id, /*$all_extras, */$tax, $tax_amount, $qty, $comb_id) {
    	$insert_data = array(
            'order_id' => $order_id,
            'item_id' => $item_id,
            'name_en' => $name_en,
            'name_xh' => $name_xh,
            'price' => $price,
            'category_id' => $category_id,
            'created' => date('Y-m-d H:i:s'),
            // 'all_extras' => $all_extras, 
            'tax' => $tax,
            'tax_amount' => $tax_amount,
            'qty' => $qty,
            'comb_id' => $comb_id,
        );


        if($this->save($insert_data, false)) {
            $lastId = $this->id;
            return $lastId;
        }
    }

    public function updateExtraAmount($id) {
    	$item_detail = $this->find("first", array(
                'fields' => array('OrderItem.id', 'OrderItem.price', 'OrderItem.order_id', 'OrderItem.extras_amount', 'OrderItem.selected_extras', 'OrderItem.tax' ,'OrderItem.tax_amount'),
                'conditions' => array('OrderItem.id' => $id)
                    )
            );
    	$extras_amount = 0;
    	// print_r($item_detail['OrderItem']['selected_extras']);
    	// echo gettype($item_detail['OrderItem']['selected_extras']);
    	if (!empty($item_detail['OrderItem']['selected_extras'])) {
    		$extras_array = json_decode($item_detail['OrderItem']['selected_extras'], true);
    		foreach ($extras_array as $extra) {
	    		$extras_amount += floatval($extra['price']);
	    	}
	    	// print_r ($extras_array);
    	} else {
    		return;
    	}

    	$item_detail['OrderItem']['extras_amount'] = $extras_amount;

    	$item_detail['OrderItem']['tax_amount'] = ($item_detail['OrderItem']['extras_amount'] + $item_detail['OrderItem']['price'] ) * $item_detail['OrderItem']['tax'] / 100;

    	$this->save($item_detail, false);

    	// notice: keep update order at the end
    	$this->Order->updateBillInfo($item_detail['OrderItem']['order_id']);
    }


    /**
     * return
     *  Array ( [0] => Array ( 
     *   [items] => Array ( 
     *       [0] => Array ( [item_id] => 10 [name_en] => Noodles w/Beef Sirloin [name_xh] => 宋嫂牛肉面 [item_id_count] => 1 ) [1] => Array ( [item_id] => 14 [name_en] => Noodles w/Tomatoes & Beef Sirloin [name_xh] => 番茄牛肉面 [item_id_count] => 1 ) [2] => Array ( [item_id] => 16 [name_en] => Chongqing-style Noodles [name_xh] => 麻辣小面 [item_id_count] => 1 ) [3] => Array ( [item_id] => 30 [name_en] => Noodles w/Grilled Hot Peppers [name_xh] => 特色烧椒面 [item_id_count] => 1 ) [4] => Array ( [item_id] => 31 [name_en] => Classic Noodles w/Minced Meat [name_xh] => 经典干拌面 [item_id_count] => 1 ) ) 
     *   [start_time] => 1486051200 
     *   [end_time] => 1486112400 ) ) 
     */
    public function getDailyItemCount($timeline_arr) {
        $data = array();
        $this->virtualFields = array(
            'item_id_count' => 'COUNT(OrderItem.item_id)',
            'qty_sum' => 'SUM(OrderItem.qty)'
            );
       
        for ($i = 0; $i < count($timeline_arr) - 1; ++$i) {
            $arr = array(
                    'items' => array(),
                    'start_time' => $timeline_arr[$i],
                    'end_time' => $timeline_arr[$i + 1],
                );


            $items = $this->find("all", array(
                    'recursive' => -1,
                    'fields' =>  array('OrderItem.item_id', 'OrderItem.item_id_count', 'OrderItem.qty_sum'),
                    'conditions' => array('OrderItem.is_print' => 'Y','OrderItem.created >=' => date('c', $timeline_arr[$i]), 'OrderItem.created <' => date('c', $timeline_arr[$i + 1])),
                    'group' => array('OrderItem.item_id'),
                ));
            
            foreach ($items as $item) {
                $tempItem = $this->find('first', array(
                        'recursive' => -1,
                        'fields' => array('OrderItem.item_id','OrderItem.name_en', 'OrderItem.name_xh'),
                        'conditions' => array('OrderItem.item_id' => $item['OrderItem']['item_id'])
                    ));
                $tempItem['OrderItem']['qty_sum'] = $item['OrderItem']['qty_sum'];
                array_push($arr['items'], $tempItem['OrderItem']);
            }

            array_push($data, $arr);
        }

        return $data;

    }

    public function getMergedItems($order_id) {
        $data = array();
        // $this->virtualFields['item_id_count'] = 'COUNT(OrderItem.item_id)';
        $this->virtualFields = array(
            'item_id_count' => 'COUNT(OrderItem.item_id)',
            'qty_sum' => 'SUM(OrderItem.qty)'
            );
        $items = $this->find("all", array(
                    'recursive' => -1,
                    'fields' =>  array('OrderItem.qty_sum','OrderItem.item_id', 'OrderItem.item_id_count', 'OrderItem.price'),
                    'conditions' => array('OrderItem.order_id' => $order_id),
                    'group' => array('OrderItem.item_id, OrderItem.price'),
                ));

        // return $items;

        foreach ($items as $item) {
            $tempItem = $this->find('first', array(
                    'recursive' => -1,
                    // 'fields' => array('OrderItem.item_id','OrderItem.name_en', 'OrderItem.name_xh'),
                    'conditions' => array('OrderItem.order_id' => $order_id, 'OrderItem.item_id' => $item['OrderItem']['item_id'], 'OrderItem.price' => $item['OrderItem']['price'])
                ));

            // return $tempItem;
            $tempItem['OrderItem']['qty'] = $item['OrderItem']['qty_sum'];
            array_push($data, $tempItem['OrderItem']);
        }

        return $data;
    }


}

?>