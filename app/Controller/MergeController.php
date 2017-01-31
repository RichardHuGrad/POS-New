<?php 
class MergeController extends AppController {

    public function getOrdersAmount() {
        $this->layout = false;
        $this->autoRender = NULL;
        $this->loadModel('Order');

        $order_ids = $this->data['order_ids'];
        $orders = array();
        foreach($order_ids as $order_id) {
            $temp = $this->Order->find('first', array(
                'conditions' => array(
                        'Order.id' => $order_id
                    )
                ));

            array_push($orders, $temp['Order']);
        }

        // print_r($orders);

        return json_encode($orders);
    }

    public function donemergepayment() {

        $this->layout = false;
        $this->autoRender = NULL;

        // pr($this->data); die;
        // get all params
        $order_id = explode(",", $this->data['order_id']);
        $table = $this->data['table'];
        $table_merge = explode(",", $this->data['table_merge']);
        $main_order_id = $this->data['main_order_id'];
        $type = $this->data['type'];
        $paid_by = strtoupper($this->data['paid_by']);

        $pay = $this->data['pay'];
        $change = $this->data['change'];

        // save order to database
        //Modified by Yishou Liao @ Oct 16 2016.
        $this->loadModel('Order');

        for ($i = 0; $i < count($order_id); $i++) {
            $data['Order']['id'] = $order_id[$i];
            $table_detail = $this->Order->find("first", array('fields' => array('Order.table_no', 'total'), 'conditions' => array('Order.id' => $data['Order']['id']), 'recursive' => false));

            if ($this->data['card_val'] and $this->data['cash_val']) {
            $data['Order']['paid_by'] = "MIXED";
            } elseif ($this->data['card_val']) {
            $data['Order']['paid_by'] = "CARD";
            } elseif ($this->data['cash_val']) {
            $data['Order']['paid_by'] = "CASH";
            };
            $data['Order']['table_status'] = 'P';
            $data['Order']['is_kitchen'] = 'Y';
            $data['Order']['is_completed'] = 'Y';



            if ($table_detail['Order']['table_no'] == $table) {
            $data['Order']['paid'] = $table_detail['Order']['total'] + $change;
            $data['Order']['change'] = $change;

            $data['Order']['card_val'] = $this->data['card_val'];
            $data['Order']['cash_val'] = $this->data['cash_val'];
            $data['Order']['tip_paid_by'] = $this->data['tip_paid_by'];
            $data['Order']['tip'] = $this->data['tip_val'];
            } else {
            $data['Order']['paid'] = $table_detail['Order']['total'];
            $data['Order']['change'] = 0;

            $data['Order']['card_val'] = 0;
            $data['Order']['cash_val'] = 0;
            $data['Order']['tip_paid_by'] = $this->data['tip_paid_by'];
            $data['Order']['tip'] = 0;
                    $data['Order']['merge_id'] = $main_order_id; //用负数代表此处为合单，去掉负号的那个数代表主桌的付款Order的Id号
            };

            $this->Order->save($data, false);

            $this->loadModel('Cousine');
            $this->Cousine->query("UPDATE cousines set `popular` = `popular`+1 where id in(SELECT (item_id) from order_items where order_id = '$order_id[$i]')");
        };

        // save all 
        // update popularity status

        $this->Session->setFlash('Order successfully completed.', 'success');
        echo true;
    }

    //End.
    //Modified by Yishou Liao @ Nov 15 2016
    public function printMergeReceipt($table_no, $printer_name, $print_zh = false) {
        $Print_Item = $this->data['Print_Item'];
        $logo_name = $this->data['logo_name'];
        $memo = isset($this->data['memo']) ? $this->data['memo'] : "";
        $subtotal = isset($this->data['subtotal']) ? $this->data['subtotal'] : 0;
        //Modified by Yishou Liao @ Nov 29 2016
        $tax_amount = isset($this->data['tax_amount']) ? $this->data['tax_amount'] : 0;
        $discount = isset($this->data['discount']) ? $this->data['discount'] : 0;
        $after_discount = isset($this->data['after_discount']) ? $this->data['after_discount'] : 0;
        $paid = isset($this->data['paid']) ? $this->data['paid'] : 0;
        $change = isset($this->data['change']) ? $this->data['change'] : 0;
        //End
        $total = isset($this->data['total']) ? $this->data['total'] : 0;
        $order_no = $this->data['order_no'];
        $merge_str = $this->data['merge_str'];

        date_default_timezone_set("America/Toronto");
        $date_time = date("l M d Y h:i:s A");

        $handle = printer_open($printer_name);
        printer_start_doc($handle, "my_Receipt");
        printer_start_page($handle);

        //Print Logo image
        printer_draw_bmp($handle, $logo_name, 100, 20, 263, 100);
        //End.
        //Print title
        $font = printer_create_font("Arial", 32, 14, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($handle, $font);
        printer_draw_text($handle, "2038 Yonge St.", 156, 130);
        printer_draw_text($handle, "Toronto ON M4S 1Z9", 110, 168);
        printer_draw_text($handle, "416-792-4476", 156, 206);
        /*printer_draw_text($handle, "3700 Midland Ave. #108", 156, 130);
        printer_draw_text($handle, "Scarborogh ON M1V 0B3", 110, 168);
        printer_draw_text($handle, "647-352-5333", 156, 206);*/

        $print_y = 244;
        if ($print_zh == true) {
            $font = printer_create_font($this->fontStr1, 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
            printer_select_font($handle, $font);
            printer_draw_text($handle, iconv("UTF-8", "gb2312", "此单不包含小费，感谢您的光临"), 100, $print_y);
            $print_y+=40;
            printer_draw_text($handle, iconv("UTF-8", "gb2312", "谢谢"), 210, $print_y);
            $print_y+=40;
        };
        //End
        //Print order information
        $font = printer_create_font("Arial", 32, 14, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($handle, $font);
        printer_draw_text($handle, "Order Number: " . $order_no, 32, $print_y);
        $print_y+=40;
        printer_draw_text($handle, "Table:" . iconv("UTF-8", "gb2312", $table_no . $merge_str), 32, $print_y);
        $print_y+=38;
        //End

        $pen = printer_create_pen(PRINTER_PEN_SOLID, 2, "000000");
        printer_select_pen($handle, $pen);
        printer_draw_line($handle, 21, $print_y, 600, $print_y);

        //Print order items
        $print_y += 20;
        foreach (array_keys($Print_Item) as $key) {
            $font = printer_create_font("Arial", 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
            printer_select_font($handle, $font);

            printer_draw_text($handle, " # " . $Print_Item[$key][0][18], 32, $print_y);
            $print_y += 40;

            for ($i = 0; $i < count($Print_Item[$key]); $i++) {
                $font = printer_create_font("Arial", 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
                printer_select_font($handle, $font);

                printer_draw_text($handle, $Print_Item[$key][$i][7], 32, $print_y);
                printer_draw_text($handle, number_format($Print_Item[$key][$i][6], 2), 360, $print_y);

                $print_str = $Print_Item[$key][$i][3];
                $len = 0;
                while (strlen($print_str) != 0) {
                    $print_str = substr($Print_Item[$key][$i][3], $len, 18);
                    printer_draw_text($handle, $print_str, 122, $print_y);
                    $len+=18;
                    if (strlen($print_str) != 0) {
                        $print_y+=30;
                    };
                    break;
                };
                if ($print_zh == true) {
                    $font = printer_create_font($this->fontStr1, 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
                    printer_select_font($handle, $font);

                    printer_draw_text($handle, iconv("UTF-8", "gb2312", $Print_Item[$key][$i][4]), 136, $print_y);
                    $print_y += 30;
                };
            };
        };
        //End.

        $print_y += 10;
        $pen = printer_create_pen(PRINTER_PEN_SOLID, 2, "000000");
        printer_select_pen($handle, $pen);
        printer_draw_line($handle, 21, $print_y, 600, $print_y);
        
        //Print Subtotal
        $print_y += 10;
        $font = printer_create_font("Arial", 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($handle, $font);
        if ($print_zh == true) {
            printer_draw_text($handle, iconv("UTF-8", "gb2312", "Subtoal"), 58, $print_y);
        } else {
            printer_draw_text($handle, iconv("UTF-8", "gb2312", "Subtoal :"), 58, $print_y);
        }

        if ($print_zh == true) {
            $font = printer_create_font($this->fontStr1, 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
            printer_select_font($handle, $font);
            printer_draw_text($handle, iconv("UTF-8", "gb2312", "小计："), 148, $print_y);
        };

        $font = printer_create_font("Arial", 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($handle, $font);
        printer_draw_text($handle, iconv("UTF-8", "gb2312", number_format($subtotal, 2)), 360, $print_y);
        //End.
        
        //Modified by Yishou Liao @ Nov 29 2016
        if ($discount > 0) {
            //Print Discount
        $print_y += 40;
        $font = printer_create_font("Arial", 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($handle, $font);
        if ($print_zh == true) {
            printer_draw_text($handle, iconv("UTF-8", "gb2312", "Discount"), 58, $print_y);
        } else {
            printer_draw_text($handle, iconv("UTF-8", "gb2312", "Discount :"), 58, $print_y);
        }

        if ($print_zh == true) {
            $font = printer_create_font($this->fontStr1, 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
            printer_select_font($handle, $font);
            printer_draw_text($handle, iconv("UTF-8", "gb2312", "小计："), 148, $print_y);
        };

        $font = printer_create_font("Arial", 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($handle, $font);
        printer_draw_text($handle, iconv("UTF-8", "gb2312", number_format($discount, 2)), 360, $print_y);
        //End.
        //Print After_Discount
        $print_y += 40;
        $font = printer_create_font("Arial", 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($handle, $font);
        if ($print_zh == true) {
            printer_draw_text($handle, iconv("UTF-8", "gb2312", "After Discount"), 58, $print_y);
        } else {
            printer_draw_text($handle, iconv("UTF-8", "gb2312", "After Discount :"), 58, $print_y);
        }

        if ($print_zh == true) {
            $font = printer_create_font($this->fontStr1, 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
            printer_select_font($handle, $font);
            printer_draw_text($handle, iconv("UTF-8", "gb2312", "小计："), 148, $print_y);
        };

        $font = printer_create_font("Arial", 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($handle, $font);
        printer_draw_text($handle, iconv("UTF-8", "gb2312", number_format($after_discount, 2)), 360, $print_y);
        //End.
        };
        //End
        
        //Print Tax
        $print_y += 40;
        printer_draw_text($handle, iconv("UTF-8", "gb2312", "Hst"), 58, $print_y);

        if ($print_zh == true) {
            $font = printer_create_font($this->fontStr1, 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
            printer_select_font($handle, $font);
            printer_draw_text($handle, iconv("UTF-8", "gb2312", "税："), 168, $print_y);
        };

        $font = printer_create_font("Arial", 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($handle, $font);
        if ($print_zh == true) {
            printer_draw_text($handle, iconv("UTF-8", "gb2312", "(13%)"), 100, $print_y);
        } else {
            printer_draw_text($handle, iconv("UTF-8", "gb2312", "(13%) :"), 100, $print_y);
        };
        printer_draw_text($handle, iconv("UTF-8", "gb2312", number_format(($after_discount * 13 / 100), 2)), 360, $print_y);
        //End.
        //Print Total
        $print_y += 40;
        if ($print_zh == true) {
            printer_draw_text($handle, iconv("UTF-8", "gb2312", "Total"), 58, $print_y);
        } else {
            printer_draw_text($handle, iconv("UTF-8", "gb2312", "Total :"), 58, $print_y);
        };

        if ($print_zh == true) {
            $font = printer_create_font($this->fontStr1, 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
            printer_select_font($handle, $font);
            printer_draw_text($handle, iconv("UTF-8", "gb2312", "总计："), 148, $print_y);
        };

        $font = printer_create_font("Arial", 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($handle, $font);
        printer_draw_text($handle, iconv("UTF-8", "gb2312", number_format($total, 2)), 360, $print_y);
        //End.

        //Modified by Yishou Liao @ Nov 29 2016
        if ($paid > 0) {
            $print_y += 50;
            $pen = printer_create_pen(PRINTER_PEN_SOLID, 2, "000000");
            printer_select_pen($handle, $pen);
            printer_draw_line($handle, 21, $print_y, 600, $print_y);

            //Print paid
            $print_y += 10;
            if ($print_zh == true) {
                printer_draw_text($handle, iconv("UTF-8", "gb2312", "Paid"), 58, $print_y);
            } else {
                printer_draw_text($handle, iconv("UTF-8", "gb2312", "Paid :"), 58, $print_y);
            };

            if ($print_zh == true) {
                $font = printer_create_font($this->fontStr1, 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
                printer_select_font($handle, $font);
                printer_draw_text($handle, iconv("UTF-8", "gb2312", "付款："), 148, $print_y);
            };

            $font = printer_create_font("Arial", 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
            printer_select_font($handle, $font);
            printer_draw_text($handle, iconv("UTF-8", "gb2312", number_format($paid, 2)), 360, $print_y);
            //End.
            //Print change
            $print_y += 40;
            if ($print_zh == true) {
                printer_draw_text($handle, iconv("UTF-8", "gb2312", "Change"), 58, $print_y);
            } else {
                printer_draw_text($handle, iconv("UTF-8", "gb2312", "Change :"), 58, $print_y);
            };

            if ($print_zh == true) {
                $font = printer_create_font($this->fontStr1, 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
                printer_select_font($handle, $font);
                printer_draw_text($handle, iconv("UTF-8", "gb2312", "找零："), 148, $print_y);
            };

            $font = printer_create_font("Arial", 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
            printer_select_font($handle, $font);
            printer_draw_text($handle, iconv("UTF-8", "gb2312", number_format($change, 2)), 360, $print_y);
            //End.
        }
        //End
        
        $print_y += 40;
        printer_draw_text($handle, $date_time, 80, $print_y);

        printer_delete_font($font);

        printer_end_page($handle);
        printer_end_doc($handle);
        printer_close($handle);

        echo true;
        exit;
    }
}