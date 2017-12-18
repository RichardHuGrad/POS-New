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

            // Order.is_completed = 'Y'
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

    // get all unprint items and seperate them by printer id
    public function getUnprintItemsByOrderId($order_id) {
        $orderItemsDetail = $this->find('all', array(
                'recursive' => -1,
                'fields' => array(
                    'OrderItem.id',
                    'OrderItem.name_en',
                    'OrderItem.name_xh',
                    'OrderItem.category_id',
                    'OrderItem.qty',
                    'OrderItem.selected_extras',
                    'OrderItem.is_takeout',
                    'OrderItem.is_print',
                    'OrderItem.special_instruction'
                    ),
                'conditions' => array(
                    'OrderItem.order_id' => $order_id, 
                    'OrderItem.is_print' => 'N'
                    ),
            ));

        // seperate items by printer
        $printItems = array();

        $this->Category = ClassRegistry::init('Category');

        foreach ($orderItemsDetail as $itemDetail) {
            $category_id = $itemDetail['OrderItem']['category_id'];
            $rc = $this->Category->getPrinterAndGroupById($category_id);
            $printer  = $rc['printer'];
            $group_id = $rc['group_id'];

			$itemDetail['OrderItem']['group_id'] = $group_id;
			
            $selected_extras_list = json_decode($itemDetail['OrderItem']['selected_extras'], true);
            $selected_extras_arr = array();
            if (!empty($selected_extras_list)) {
                foreach ($selected_extras_list as $selected_extra) {
                    array_push($selected_extras_arr, $selected_extra['name']);
                }
            }
                
            $itemDetail['OrderItem']['selected_extras'] = join(',', $selected_extras_arr);

			/*
            if (!isset($printItems[$printer])) {
                $printItems[$printer] = array();
            }
            array_push($printItems[$printer], $itemDetail['OrderItem']);
            */
            //第二维group_id是控制分组打印(不同的category分属于不同组,同一组的item打印一张单子,例如:招牌面和火锅米线一张单,钵钵鸡、饭、小菜等一张单，这两张单都在kitchen打印,但是分属于kitchen不同的部门来做菜)
            $printItems[$printer][$group_id][]=$itemDetail['OrderItem'];
        }

        return $printItems;
    }


    public function setAllItemsToPrinted($order_id) {
        $orderItemsDetail = $this->find('all', array(
                'recursive' => -1,
                'fields' => array(
                    'OrderItem.id',
                    'OrderItem.name_en',
                    'OrderItem.name_xh',
                    'OrderItem.category_id',
                    'OrderItem.qty',
                    'OrderItem.selected_extras',
                    'OrderItem.is_takeout',
                    'OrderItem.is_print',
                    'OrderItem.special_instruction'
                    ),
                'conditions' => array(
                    'OrderItem.order_id' => $order_id, 
                    'OrderItem.is_print' => 'N'
                    ),
            ));

                // change all items is_print to 'Y'
        foreach ($orderItemsDetail as $itemDetail) {
            $itemDetail['OrderItem']['is_print'] = 'Y';
            $this->save($itemDetail, false);
        }

    }


    
}

?>