<?php 

App::uses('PrintLib', 'Lib');
class PayController extends AppController {
    public $fontStr1 = "simsun";

    public function printReceipt($order_no, $table_no, $printer_name, $print_zh = false) {
        $Print_Item = $this->data['Print_Item'];
        
        $logo_name = $this->data['logo_name'];
        $memo = isset($this->data['memo']) ? $this->data['memo'] : "";
        $subtotal = isset($this->data['subtotal']) ? $this->data['subtotal'] : 0.00;
        //Modified by Yishou Liao @ Nov 29 2016
        $discount = isset($this->data['discount']) ? $this->data['discount'] : 0.00;
        $after_discount = isset($this->data['after_discount']) ? $this->data['after_discount'] : 0.00;
        $tax_Amount = isset($this->data['tax_Amount']) ? $this->data['tax_Amount'] : 0.00;
        $paid = isset($this->data['paid']) ? $this->data['paid'] : 0.00;
        $change = isset($this->data['change']) ? $this->data['change'] : 0.00;
        //End
        $tax = isset($this->data['tax']) ? $this->data['tax'] : 0.00;
        $total = isset($this->data['total']) ? $this->data['total'] : 0.00;
        $split_no = isset($this->data['split_no']) ? "" . $this->data['split_no'] : "";

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
        /*printer_draw_text($handle, "2038 Yonge St.", 156, 130);
        printer_draw_text($handle, "Toronto ON M4S 1Z9", 110, 168);
        printer_draw_text($handle, "416-792-4476", 156, 206);
*/		printer_draw_text($handle, "3700 Midland Ave. #108", 156, 130);
        printer_draw_text($handle, "Scarborogh ON M1V 0B3", 110, 168);
        printer_draw_text($handle, "647-352-5333", 156, 206);

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
        //Modified by Yishou Liao @ Nov 09 2016
        if ($split_no != "") {
            $split_no = " - " . $split_no;
        };
        //End
        printer_draw_text($handle, "Order Number: #" . $order_no . $split_no, 32, $print_y);
        $print_y+=40;
        printer_draw_text($handle, "Table:" . iconv("UTF-8", "gb2312", $table_no), 32, $print_y);
        $print_y+=38;
        //End

        $pen = printer_create_pen(PRINTER_PEN_SOLID, 2, "000000");
        printer_select_pen($handle, $pen);
        printer_draw_line($handle, 21, $print_y, 600, $print_y);

        printer_end_page($handle);
        
        printer_start_page($handle);
        //Print order items
        $print_y = 20;
        for ($i = 0; $i < count($Print_Item); $i++) {
            $font = printer_create_font("Arial", 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
            printer_select_font($handle, $font);
            
            printer_draw_text($handle, $Print_Item[$i][7], 32, $print_y);
            printer_draw_text($handle, number_format($Print_Item[$i][6]+$Print_Item[$i][12], 2), 360, $print_y);
            $print_str = $Print_Item[$i][3];
            $len = 0;
            while (strlen($print_str) != 0) {
                $print_str = substr($Print_Item[$i][3], $len, 18);
                printer_draw_text($handle, $print_str, 122, $print_y);
                $len+=18;
                if (strlen($print_str) != 0) {
                    $print_y+=30;
                }
                break;
            }
            if ($print_zh == true) {
                $font = printer_create_font($this->fontStr1, 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
                printer_select_font($handle, $font);

                printer_draw_text($handle, iconv("UTF-8", "gb2312", $Print_Item[$i][4]), 136, $print_y);
                $print_y += 30;
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
        };

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
            };

            if ($print_zh == true) {
                $font = printer_create_font($this->fontStr1, 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
                printer_select_font($handle, $font);
                printer_draw_text($handle, iconv("UTF-8", "gb2312", "折扣："), 148, $print_y);
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
            };

            if ($print_zh == true) {
                $font = printer_create_font($this->fontStr1, 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
                printer_select_font($handle, $font);
                printer_draw_text($handle, iconv("UTF-8", "gb2312", "折后价："), 148, $print_y);
            };

            $font = printer_create_font("Arial", 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
            printer_select_font($handle, $font);
            printer_draw_text($handle, iconv("UTF-8", "gb2312", number_format($after_discount, 2)), 360, $print_y);
            //End.
        };
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
            printer_draw_text($handle, iconv("UTF-8", "gb2312", "(" . $tax . "%)"), 100, $print_y);
        } else {
            printer_draw_text($handle, iconv("UTF-8", "gb2312", "(" . $tax . "%) :"), 100, $print_y);
        };
        printer_draw_text($handle, iconv("UTF-8", "gb2312", number_format($tax_Amount, 2)), 360, $print_y);
        //End.
        //End @ Nov 29 2016
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

        if ($memo != "") {
            //Print average
            $print_y += 40;
            printer_draw_text($handle, "Average", 58, $print_y);

            if ($print_zh == true) {
                $font = printer_create_font($this->fontStr1, 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
                printer_select_font($handle, $font);
                printer_draw_text($handle, iconv("UTF-8", "gb2312", "人均："), 148, $print_y);
            };

            $font = printer_create_font("Arial", 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
            printer_select_font($handle, $font);
            printer_draw_text($handle, iconv("UTF-8", "gb2312", number_format($memo, 2)), 360, $print_y);
            //End.
        };

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


    public function donepayment() {

        $this->layout = false;
        $this->autoRender = NULL;

        // pr($this->data); die;
        // get all params
        $order_id = $this->data['order_id'];
        $table = $this->data['table'];
        $type = $this->data['type'];
        $paid_by = strtoupper($this->data['paid_by']);

        $pay = $this->data['pay'];
        $change = $this->data['change'];

        // save order to database        
        $data['Order']['id'] = $order_id;
        if ($this->data['card_val'] and $this->data['cash_val'])
            $data['Order']['paid_by'] = "MIXED";

        elseif ($this->data['card_val'])
            $data['Order']['paid_by'] = "CARD";

        elseif ($this->data['cash_val'])
            $data['Order']['paid_by'] = "CASH";

        $data['Order']['table_status'] = 'P';

        $data['Order']['paid'] = $pay;
        $data['Order']['change'] = $change;
        $data['Order']['is_kitchen'] = 'Y';

        $data['Order']['is_completed'] = 'Y';

        $data['Order']['card_val'] = $this->data['card_val'];
        $data['Order']['cash_val'] = $this->data['cash_val'];
        $data['Order']['tip_paid_by'] = $this->data['tip_paid_by'];
        $data['Order']['tip'] = $this->data['tip_val'];

        $this->loadModel('Order');
        $this->Order->save($data, false);

        // update popularity status
        $this->loadModel('Cousine');
        $this->Cousine->query("UPDATE cousines set `popular` = `popular`+1 where id in(SELECT (item_id) from order_items where order_id = '$order_id')");

        // save all 
        $this->Session->setFlash('Order successfully completed.', 'success');

        echo true;
        exit; //Modified by Yishou Liao @ Nov 29 2016
    }

}

 ?>