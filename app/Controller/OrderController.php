<?php 

class OrderController extends AppController {

	public function addItem() {
        $this->layout = false;
        // $this->autoRender = NULL;
        // get tax details        
        $this->loadModel('Cashier');
        $tax_detail = $this->Cashier->find("first", array(
            'fields' => array('Admin.tax', 'Admin.id'),
            'conditions' => array('Cashier.id' => $this->Session->read('Front.id'))
                )
        );
        
        // get all params
        $item_id = $this->data['item_id'];
        $table = $this->data['table'];
        $type = $this->data['type'];

        // get item details        
        $this->loadModel('Cousine');
        $item_detail = $this->Cousine->find("first", array(
            'fields' => array('Cousine.price', 'Category.id', 'Cousine.is_tax,Cousine.comb_num'),
            'conditions' => array('Cousine.id' => $item_id)
                )
        );
        
        //Modified by Yishou Liao @ Dec 15 2016
        $get_comb_flag = $this->Cousine->query("SELECT extras_num FROM extrascategories WHERE id = " . $item_detail['Cousine']['comb_num']);
        $show_extras_flag = false;
        if (count($get_comb_flag)>0){
            if ($get_comb_flag[0]['extrascategories']['extras_num']>0){
                $show_extras_flag = true;
            }
        };
        //End @ Dec 15 2016
        
        // check the item already exists or not
        $this->loadModel('Order');
        $this->loadModel('OrderItem');
        $Order_detail = $this->Order->find("first", array(
            'fields' => array('Order.id', 'Order.subtotal', 'Order.total', 'Order.tax_amount', 'Order.discount_value', 'Order.promocode', 'Order.fix_discount', 'Order.percent_discount'),
            'conditions' => array(
                'Order.cashier_id' => $tax_detail['Admin']['id'],
                'Order.table_no' => $table,
                'Order.is_completed' => 'N',
                'Order.order_type' => $type
            )
                )
        );

        if (empty($Order_detail)) {

            // to create a new order
            $insert_data = array(
                'cashier_id' => $tax_detail['Admin']['id'],
                'counter_id' => $this->Session->read('Front.id'),
                'table_no' => $table,
                'is_completed' => 'N',
                'order_type' => $type,
                'tax' => $tax_detail['Admin']['tax'],
                'created' => date('Y-m-d H:i:s')
            );
            $this->Order->save($insert_data, false);
            $order_id = $this->Order->getLastInsertId();

            // update order no            
            $data['Order']['id'] = $order_id;
            // Change to DateTime related $data['Order']['order_no'] = str_pad($order_id, 5, rand(98753, 87563), STR_PAD_LEFT);
            $data['Order']['order_no'] = $table.sprintf("%07d",date('zHi'));
            $this->Order->save($data, false);
        } else {
            $order_id = $Order_detail['Order']['id'];
        }

         //Modified by Yishou Liao @ Dec 13 2016
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
        //End
        
        // add items to order items db table
        $insert_data = array(
            'order_id' => $order_id,
            'item_id' => $item_id,
            'name_en' => $item_detail['CousineLocal'][0]['name'],
            'name_xh' => $item_detail['CousineLocal'][1]['name'],
            'price' => $item_detail['Cousine']['price'],
            'category_id' => $item_detail['Category']['id'],
            'created' => date('Y-m-d H:i:s'),
            'all_extras' =>!empty($extras) ? json_encode($extras) : "", //!empty($item_detail['Extra']) ? json_encode($item_detail['Extra']) : "", //Modified by Yishou Liao @ Dec 13 2016
            'tax' => $tax_detail['Admin']['tax'],
            'tax_amount' => ($item_detail['Cousine']['is_tax'] == 'Y' ? ($tax_detail['Admin']['tax'] * $item_detail['Cousine']['price'] / 100) : 0),
        );
        $insert_data['qty'] = 1;
        if (!empty($Order_detail)) {

            // check the product already exists or not
            $order_item_detail = $this->OrderItem->find("first", array(
                'fields' => array('OrderItem.qty', 'OrderItem.id'),
                'conditions' => array('OrderItem.item_id' => $item_id, 'OrderItem.order_id' => $order_id)
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
        $data['Order']['subtotal'] = @$Order_detail['Order']['subtotal'] + @$Order_detail['Order']['discount_value'] + $item_detail['Cousine']['price']; //Modified by Yishou Liao @ Dec 21 2016
        $data['Order']['tax'] = $tax_detail['Admin']['tax'];
        $data['Order']['tax_amount'] = (@$data['Order']['subtotal'] * $data['Order']['tax']/100); //Modified by Yishou Liao @ Dec 21 2016
        $data['Order']['total'] = ($data['Order']['subtotal'] + $data['Order']['tax_amount']);

        // calculate discount if exists
        if (!empty($Order_detail)) {
            $data['Order']['discount_value'] = $Order_detail['Order']['discount_value'];
            if ($Order_detail['Order']['percent_discount']) {
                $data['Order']['discount_value'] = $data['Order']['subtotal'] * $Order_detail['Order']['percent_discount'] / 100;//Modified by Yishou Liao @ Dec 21 2016
            } else if ($Order_detail['Order']['fix_discount']) {
                if ($Order_detail['Order']['fix_discount'] > $data['Order']['total']) {
                    $data['Order']['discount_value'] = $data['Order']['total'];
                } else {
                    $data['Order']['discount_value'] = $Order_detail['Order']['fix_discount'];
                }
            }
            //$data['Order']['total'] = $this->convertoround($data['Order']['total'] - $data['Order']['discount_value']);
            //Modified by Yishou Liao @ Dec 21 2016
            $data['Order']['subtotal'] = $data['Order']['subtotal'] - $data['Order']['discount_value']; 
            $data['Order']['tax_amount'] = (@$data['Order']['subtotal'] * $data['Order']['tax']/100); 
            $data['Order']['total'] = ($data['Order']['subtotal'] + $data['Order']['tax_amount']);
            //End @ Dec 21 2016
        } else {
            //$data['Order']['total'] = $this->convertoround($data['Order']['subtotal'] + $data['Order']['tax_amount']); // Modified by Yishou Liao @ Nov 15 2016
            $data['Order']['total'] = $data['Order']['subtotal'] + $data['Order']['tax_amount'];
        }



        $this->Order->save($data, false);


        $this->OrderItem->virtualFields['image'] = "Select image from cousines where cousines.id = OrderItem.item_id";
        $Order_detail = $this->Order->find("first", array(
            'fields' => array('Order.order_no', 'Order.tax', 'Order.tax_amount', 'Order.subtotal', 'Order.total', 'Order.message', 'Order.discount_value', 'Order.promocode', 'Order.fix_discount', 'Order.percent_discount'),
            'conditions' => array('Order.cashier_id' => $tax_detail['Admin']['id'],
                'Order.table_no' => $table,
                'Order.is_completed' => 'N',
                'Order.order_type' => $type
            )
                )
        );

        //Modified by Yishou Liao @ Oct 26 2016.
        $Order_detail_print = $this->Order->query("SELECT order_items.*,categories.printer FROM `orders` JOIN `order_items` ON orders.id =  order_items.order_id JOIN `categories` ON order_items.category_id=categories.id WHERE orders.cashier_id = " . $tax_detail['Admin']['id'] . " AND  orders.table_no = " . $table . " AND order_items.is_print = 'N' AND orders.is_completed = 'N' AND orders.order_type = '" . $type . "' ");
        //End.
        //Modified by Yishou Liao @ Nov 16 2016.
        if (isset($_SESSION['DELEITEM_' . $table])) {
            $deleitem = explode("#", $_SESSION['DELEITEM_' . $table]);
            for ($i = 0; $i < count($deleitem); $i++) {
                $deleitem[$i] = explode("*", $deleitem[$i]);
            };
        };

        if (isset($deleitem)) {
            for ($i = 0; $i < count($deleitem); $i++) {
                $arr_tmp = array('order_items' => array(), 'categories' => array('printer' => $deleitem[$i][17]));
                array_splice($deleitem[$i], -1);
                //array_splice($deleitem[$i],-5);
                $deleitem[$i][13] = 'C';

                $arr_tmp['order_items'] = $deleitem[$i];

                array_push($Order_detail_print, $arr_tmp);
            };
        };
        //End.
        // get cashier details        
        $this->loadModel('Cashier');
        $cashier_detail = $this->Cashier->find("first", array(
            'fields' => array('Cashier.firstname', 'Cashier.lastname', 'Cashier.id', 'Cashier.image'),
            'conditions' => array('Cashier.id' => $this->Session->read('Front.id'))
                )
        );

        $this->set(compact('Order_detail', 'cashier_detail', 'Order_detail_print','extras_categories','show_extras_flag')); //Modified by Yishou Liao @ Dec 13 2016.
        $this->render('summarypanel');
    }


}

 ?>