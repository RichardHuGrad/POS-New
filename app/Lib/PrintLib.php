<?php 

class PrintLib {

    public $handle;
    public $fontH = 28; // font height
    public $fontW = 10; // font width

    public $itemLineLen = 180;
    // public $charNo = $this->itemLineLen / $this->fontW;
    public $charNo = 20;
    public $lineStartPos = 10;

    public function printBill() {
        $this->layout = false;
        $this->autoRender = NULL;

        $items = json_decode($this->data['items']);

        echo json_encode($items[0]);
    }

    public function printReceipt() {
        $this->layout = false;
        $this->autoRender = NULL;

        $items = json_decode($this->data['items']);




        echo json_encode($items[0]);
    }



    public function header() {
        $this->layout = false;
        $this->autoRender = NULL;

        $header = "";

        return $header;
    }


    public function footer() {
        $this->layout = false;
        $this->autoRender = NULL;

        $footer = "";

        return $footer;
    }


    public function switchZh() {
        $fontZh = printer_create_font(iconv("UTF-8", "gb2312", "宋体"), $this->fontH, $this->fontW, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($this->handle, $fontZh);
    }

    public function switchEn() {
        $font = printer_create_font("Arial", 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($this->handle, $font);
    }


    public function printZh($str, $x, $y) {
        $font = printer_create_font(iconv("UTF-8", "gb2312", "宋体"), $this->fontH, $this->fontW, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($this->handle, $font);
        printer_draw_text($this->handle, iconv("UTF-8", "gb2312", $str), $x, $y);
        printer_delete_font($font);
    }

    public function printBigZh ($str, $x, $y) {
        $font = printer_create_font(iconv("UTF-8", "gb2312", "宋体"), 32, 14, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($this->handle, $font);
        printer_draw_text($this->handle, iconv("UTF-8", "gb2312", $str), $x, $y);
        printer_delete_font($font);
    }

    // each chinese character take two byte
    public function printItemZh($str, $x, &$y) {
        $font = printer_create_font(iconv("UTF-8", "gb2312", "宋体"), $this->fontH, $this->fontW, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($this->handle, $font);

        // change the str to chinese string
        // $str =  iconv("UTF-8", "gb2312", $str);
        $start = 0;

        while (mb_strlen($str, 'UTF-8') > 0) {
            $print_str = mb_substr($str, $start, 10);
            printer_draw_text($this->handle, iconv("UTF-8", "gb2312", $print_str), $x, $y);
            $str = mb_substr($str, $start);
            if (mb_strlen($str, 'UTF-8') > 0 ) {
                $y += $this->fontH + 2; // change the line
            }
            
            $start += 10;
           
        }

        // printer_draw_text($this->handle, iconv("UTF-8", "gb2312", $str), $x, $y);
        printer_delete_font($font);
    }

    public function printItemEn($str, $x, &$y) {
        $font = printer_create_font("Arial", 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($this->handle, $font);

        $start = 0;
        while (strlen($str) != 0) {
            $print_str = substr($str, $start, 20);
            printer_draw_text($this->handle, $print_str, $x, $y);
            $str = mb_substr($str, $start);

            if (mb_strlen($str, 'UTF-8') > 0 ) {
                $y += $this->fontH + 2; // change the line
            }
            $start += 20;
        }

        printer_delete_font($font);
    }

    public function printEn($str, $x, $y) {
        $font = printer_create_font("Arial", 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($this->handle, $font);
        printer_draw_text($this->handle, $str, $x, $y);

        printer_delete_font($font);
    }

    public function printBigEn($str, $x, $y) {
        $font = printer_create_font("Arial", 32, 14, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($this->handle, $font);
        printer_draw_text($this->handle, $str, $x, $y);

        printer_delete_font($font);
    }



    // print all items with cancelled tag
    public function printCancelledItems($item_id_list, $printer_name, $print_zh=true, $print_en=true) {
        // do not check $item_id_list 


        $this->handle = printer_open($printer_name);
        printer_start_doc($this->handle, "kitchen");
        printer_start_page($this->handle);

        if ($print_zh == true) {
            $font = printer_create_font(iconv("UTF-8", "gb2312", "宋体"), 42, 18, PRINTER_FW_BOLD, false, false, false, 0);
            printer_select_font($handle, $font);
            printer_draw_text($handle, iconv("UTF-8", "gb2312", "后厨组（分单）"), 138, 20);
        } else {
            $font = printer_create_font("Arial", 42, 18, PRINTER_FW_MEDIUM, false, false, false, 0);
            printer_select_font($handle, $font);
            printer_draw_text($handle, "Kitchen", 138, 20);
        };
    }



    // order number
    public function printOriginalBill($order_no, $table_no, $table_type, $printer_name, $print_zh=true, $is_receipt=true) {
        $order = $this->data['order'];
        $items = $order['items'];
        
        $subtotal = $order['subtotal'];
        $discount_type = $order['discount_type'];
        $discount_value = $order['discount_value'];
        $discount_amount = $order['discount_amount'];
        $after_discount = $order['after_discount'];
        $tax_rate = $order['tax_rate'];
        $tax_amount = $order['tax_amount'];
        $total = $order['total'];
        $logo_name = $this->data['logo_name'];

        $type = (($table_type == 'D') ? '[[堂食]]' : (($table_type == 'T') ? '[[外卖]]' : (($table_type == 'W') ? '[[等候]]' : '')));

        echo json_encode($order);

        date_default_timezone_set("America/Toronto");
        $date_time = date("l M d Y h:i:s A");

        


        $this->handle = printer_open($printer_name);
        printer_start_doc($this->handle, "my_Receipt");
        printer_start_page($this->handle);
        /*$this->printBigEn("2038 Yonge St.", 156, 130);
        $this->printBigEn("Toronto ON M4S 1Z9", 110, 168);
        $this->printBigEn("416-792-4476", 156, 206);*/
        printer_draw_bmp($this->handle, $logo_name, 100, 20, 263, 100);

        $this->printBigEn("3700 Midland Ave. #108", 156, 130);
        $this->printBigEn("Scarborogh ON M1V 0B3", 110, 168);
        $this->printBigEn("647-352-5333", 156, 206);

        $print_y = 244;

        if ($print_zh == true) {
            $this->printZh("此单不包含小费，感谢您的光临", 100, $print_y);
            $print_y+=40;
            $this->printZh("谢谢", 210, $print_y);
            $print_y+=40;
        }


        $this->printBigEn("Order Number: #" . $order_no , 32, $print_y);
        $print_y+=40;
        $this->printBigZh("Table:". $type . iconv("UTF-8", "gb2312", "#" . $table_no) , 32, $print_y);
        $print_y+=38;

        $pen = printer_create_pen(PRINTER_PEN_SOLID, 2, "000000");
        printer_select_pen($this->handle, $pen);
        printer_draw_line($this->handle, 21, $print_y, 600, $print_y);

        // print order item
        $print_y += 20;
        for ($i = 0; $i < count($items); ++$i) {
            $this->printEn(number_format($items[$i]['price'], 2), 360, $print_y);
            $this->printItemEn($items[$i]['name_en'], 10, $print_y);
            
            // $print_y += 30;
            if ($print_zh == true) {
                $this->printItemZh($items[$i]['name_zh'], 10, $print_y);
            };

            // $print_y += 30;

            if (!empty(trim($items[$i]['selected_extras_name']))) {
                // $print_y += 30;
                $this->printItemZh( $items[$i]['selected_extras_name'], 32, $print_y);

                $this->printEn( number_format($items[$i]['extra_amount'], 2), 360, $print_y);
            }

            // $print_y += 40;
        }

        $print_y += 10;
        $pen = printer_create_pen(PRINTER_PEN_SOLID, 2, "000000");
        printer_select_pen($this->handle, $pen);
        printer_draw_line($this->handle, 21, $print_y, 600, $print_y);

        $print_y += 10;

        if ($print_zh == true) {
            $this->printZh("Subtoal", 58, $print_y);
            $this->printZh("小计:", 148, $print_y);
        } else {
            $this->printEn("Subtoal :", 58, $print_y);
        };
        $this->printEn(number_format($subtotal, 2), 360, $print_y);
        $print_y += 30;

        if (floatval($discount_amount) > 0 ) {
            if ($print_zh == true) {
                $this->printZh("Discount", 58, $print_y);
                $this->printZh("折扣：", 148, $print_y);
            } else {
                $this->printEn("Discount :", 148, $print_y);
            }
            $this->printEn(number_format($discount_amount, 2), 360, $print_y);

            $print_y += 30;

            if ($print_zh == true) {
                $this->printZh("After Discount", 58, $print_y);
                $this->printZh("折后价：", 148, $print_y);
            } else {
                $this->printEn("After Discount :", 58, $print_y);
            } 

            $this->printEn(number_format($after_discount, 2), 360, $print_y);

            $print_y += 30;
        }

        if ($print_zh == true) {
            $this->printZh("Hst", 58, $print_y);
            $this->printZh("(" . $tax_rate . "%)", 100, $print_y);
            $this->printZh("税：", 168, $print_y);
        } else {
            $this->printEn("Hst", 58, $print_y);
            $this->printEn("(" . $tax_rate . "%) :", 100, $print_y);
        }
        $this->printEn(number_format($tax_amount, 2), 360, $print_y);
        $print_y += 30;
        
        if ($print_zh == true) {
            $this->printZh("Total", 58, $print_y);
            $this->printZh("总计：", 148, $print_y);
        } else {
            $this->printEn( "Total :", 58, $print_y);
        };
        $this->printEn(number_format($total, 2), 360, $print_y);
        $print_y += 30;

        /*if ($is_receipt == true) {
            if ($print_zh == true) {
                printZh("Paid", 58, $print_y);
                printZh("付款：", 148, $print_y);
            } else {
                printEn("Paid :", 58, $print_y);
            }
            // printEn()
        }*/



        $this->printEn($date_time, 80, $print_y);

        

        printer_end_page($this->handle);
        printer_end_doc($this->handle);
        printer_close($this->handle);

        echo true;
        exit;

        
    }


    public function printSplitReceipt($order_no, $table_no, $table_type, $printer_name, $print_zh=true, $is_receipt=false) {
        $this->layout = false;
        $this->autoRender = NULL;   

        $suborder = $this->data['suborder'];
        $items = $suborder['items'];
        $suborder_no = $suborder['suborder_no'];
        $subtotal = $suborder['subtotal'];
        $discount_type = $suborder['discount_type'];
        $discount_value = $suborder['discount_value'];
        $discount_amount = $suborder['discount_amount'];
        $after_discount = $suborder['after_discount'];
        $tax_rate = $suborder['tax_rate'];
        $tax_amount = $suborder['tax_amount'];
        $total = $suborder['total'];
        $received_card = $suborder['received_card'];
        $received_cash = $suborder['received_cash'];
        $received_total = $suborder['received_total'];
        $paid = $received_total;
        $tip_amount = $suborder['tip_amount'];
        $tip_card = $suborder['tip_card'];
        $tip_cash = $suborder['tip_cash'];
        $change = $suborder['change'];

        $logo_name = $this->data['logo_name'];

        $type = (($table_type == 'D') ? '[[堂食]]' : (($table_type == 'T') ? '[[外卖]]' : (($table_type == 'W') ? '[[等候]]' : '')));

        date_default_timezone_set("America/Toronto");
        $date_time = date("l M d Y h:i:s A");


        $this->handle = printer_open($printer_name);
        printer_start_doc($this->handle, "my_Receipt");
        printer_start_page($this->handle);
        /*$this->printBigEn("2038 Yonge St.", 156, 130);
        $this->printBigEn("Toronto ON M4S 1Z9", 110, 168);
        $this->printBigEn("416-792-4476", 156, 206);*/
        printer_draw_bmp($this->handle, $logo_name, 100, 20, 263, 100);
        $this->printBigEn("3700 Midland Ave. #108", 156, 130);
        $this->printBigEn("Scarborogh ON M1V 0B3", 110, 168);
        $this->printBigEn("647-352-5333", 156, 206);

        $print_y = 244;

        if ($print_zh == true) {
            $this->printZh("此单不包含小费，感谢您的光临", 100, $print_y);
            $print_y+=40;
            $this->printZh("谢谢", 210, $print_y);
            $print_y+=40;
        }


        $this->printBigEn("Order Number: #" . $order_no . '-' . $suborder_no , 32, $print_y);
        $print_y+=40;
        $this->printBigZh("Table:". $type . iconv("UTF-8", "gb2312", "#" . $table_no) , 32, $print_y);
        $print_y+=38;

        $pen = printer_create_pen(PRINTER_PEN_SOLID, 2, "000000");
        printer_select_pen($this->handle, $pen);
        printer_draw_line($this->handle, 21, $print_y, 600, $print_y);

        // print order item
        $print_y += 20;
        for ($i = 0; $i < count($items); ++$i) {
            $this->printEn(number_format($items[$i]['price'], 2), 360, $print_y);
            $this->printItemEn($items[$i]['name_en'], 10, $print_y);
            
            // $print_y += 30;
            if ($print_zh == true) {
                // $this->printZh($items[$i]['name_zh'], 10, $print_y);
                $this->printItemZh($items[$i]['name_zh'], 10, $print_y);
            };

            // $print_y += 30;

            if (!empty(trim($items[$i]['selected_extras_name']))) {
                // $print_y += 30;
                $this->printItemZh( $items[$i]['selected_extras_name'], 32, $print_y);

                // $this->printEn( number_format($items[$i]['extra_amount'], 2), 360, $print_y);
            }

            // $print_y += 40;
        }

        $print_y += 10;
        $pen = printer_create_pen(PRINTER_PEN_SOLID, 2, "000000");
        printer_select_pen($this->handle, $pen);
        printer_draw_line($this->handle, 21, $print_y, 600, $print_y);

        $print_y += 10;

        if ($print_zh == true) {
            $this->printZh("Subtoal", 58, $print_y);
            $this->printZh("小计:", 148, $print_y);
        } else {
            $this->printEn("Subtoal :", 58, $print_y);
        };
        $this->printEn(number_format($subtotal, 2), 360, $print_y);
        $print_y += 30;

        if (floatval($discount_amount) > 0 ) {
            if ($print_zh == true) {
                $this->printZh("Discount", 58, $print_y);
                $this->printZh("折扣：", 148, $print_y);
            } else {
                $this->printEn("Discount :", 148, $print_y);
            }
            $this->printEn(number_format($discount_amount, 2), 360, $print_y);

            $print_y += 30;

            if ($print_zh == true) {
                $this->printZh("After Discount", 58, $print_y);
                $this->printZh("折后价：", 148, $print_y);
            } else {
                $this->printEn("After Discount :", 58, $print_y);
            } 

            $this->printEn(number_format($after_discount, 2), 360, $print_y);

            $print_y += 30;
        }

        if ($print_zh == true) {
            $this->printZh("Hst", 58, $print_y);
            $this->printZh("(" . $tax_rate . "%)", 100, $print_y);
            $this->printZh("税：", 168, $print_y);
        } else {
            $this->printEn("Hst", 58, $print_y);
            $this->printEn("(" . $tax_rate . "%) :", 100, $print_y);
        }
        $this->printEn(number_format($tax_amount, 2), 360, $print_y);
        $print_y += 30;
        
        if ($print_zh == true) {
            $this->printZh("Total", 58, $print_y);
            $this->printZh("总计：", 148, $print_y);
        } else {
            $this->printEn( "Total :", 58, $print_y);
        };
        $this->printEn(number_format($total, 2), 360, $print_y);
        $print_y += 30;


        if ($is_receipt == true) {
            if ($print_zh == true) {
                $this->printZh("Paid", 58, $print_y);
                $this->printZh("付款：", 148, $print_y);
            } else {
                $this->printEn("Paid :", 58, $print_y);
            }
            $this->printEn(number_format($paid, 2), 360, $print_y);
            $print_y += 30;

            if ($print_zh == true) {
                $this->printZh("Change", 58, $print_y);
                $this->printZh("找零：", 148, $print_y);
            } else {
                $this->printEn("Change :", 58, $print_y);
            };
            $this->printEn(number_format($change, 2), 360, $print_y);

        }

        $print_y += 30;

        $this->printEn($date_time, 80, $print_y);


        printer_end_page($this->handle);
        printer_end_doc($this->handle);
        printer_close($this->handle);

        echo true;
        exit;

    }





    public function printToKitchen($print_zh = false, $splitItme = false) {

        $this->loadModel('OrderItem');
        $this->loadModel('Order');


        for ($x = 0; $x < (isset($Print_Item_split) ? count($Print_Item_split) : 1); $x++) {
            $current_items = $this->data['current_items'];
            $Printer = $this->data['Printer'];
            $order_no = $this->data['order_no'];

            $order_id = $this->Order->getOrderIdByOrderNo($order_no);
            echo $order_id;

            $order_type = $this->data['order_type'];
            $table_no = $this->data['table_no'];
            $table = $this->data['table'];

            foreach (array_keys($Printer) as $key) {
                $printer_name = $Printer[$key];
                $printer_loca = $key;

                $check_print_flag = false;
                for ($i = 0; $i < count($Print_Item); $i++) {
                    if ($Print_Item[$i][count($Print_Item[$i]) - 1] == $printer_loca) {
                        $check_print_flag = true;
                    };
                };

                if ($check_print_flag) {

                    date_default_timezone_set("America/Toronto");
                    $date_time = date("l M d Y h:i:s A");

                    $handle = printer_open($printer_name);
                    printer_start_doc($handle, "my_Receipt");
                    printer_start_page($handle);

                    if ($print_zh == true) {
                        $font = printer_create_font(iconv("UTF-8", "gb2312", "宋体"), 42, 18, PRINTER_FW_BOLD, false, false, false, 0);
                        printer_select_font($handle, $font);
                        printer_draw_text($handle, iconv("UTF-8", "gb2312", "后厨组（分单）"), 138, 20);
                    } else {
                        $font = printer_create_font("Arial", 42, 18, PRINTER_FW_MEDIUM, false, false, false, 0);
                        printer_select_font($handle, $font);
                        printer_draw_text($handle, "Kitchen", 138, 20);
                    };


                    //Print order information
                    $font = printer_create_font("Arial", 32, 14, PRINTER_FW_MEDIUM, false, false, false, 0);
                    printer_select_font($handle, $font);
                    printer_draw_text($handle, "Order Number: #" . $order_no, 32, 80);
                    printer_draw_text($handle, "Table:" . iconv("UTF-8", "gb2312", $table_no), 32, 120);
                    //End

                    $pen = printer_create_pen(PRINTER_PEN_SOLID, 2, "000000");
                    printer_select_pen($handle, $pen);
                    printer_draw_line($handle, 21, 160, 600, 160);

                    //Print order items
                    $print_y = 180;
                    for ($i = 0; $i < count($Print_Item); $i++) {
                        if ($Print_Item[$i][(count($Print_Item[$i]) - 1)] == $printer_loca) {
                            if ($print_zh == true) {
                                $font = printer_create_font("Arial", 32, 12, PRINTER_FW_MEDIUM, false, false, false, 0);
                            } else {
                                $font = printer_create_font("Arial", 34, 14, PRINTER_FW_MEDIUM, false, false, false, 0);
                            };
                            printer_select_font($handle, $font);

                            printer_draw_text($handle, $Print_Item[$i][7], 32, $print_y);

                            $print_str = $Print_Item[$i][3];
                            if ($Print_Item[$i][17] == 'Y') $print_str = '(Takeaway) ' .  $print_str;
                            $print_str_save = $print_str;
                            $len = 0;
                            while (strlen($print_str) != 0) {
                                $print_str = substr($print_str_save, $len, 16);
                                printer_draw_text($handle, $print_str, 122, $print_y);
                                $len+=16;
                                if (strlen($print_str) != 0) {
                                    $print_y+=32;
                                };
                            };
                            $print_y-=32;

                            if ($print_zh == true) {
                                $print_y += 32;
                                $font = printer_create_font(iconv("UTF-8", "gb2312", "宋体"), 38, 16, PRINTER_FW_BOLD, false, false, false, 0);
                                printer_select_font($handle, $font);

                                $print_str = $Print_Item[$i][4];
                                if ($Print_Item[$i][17] == 'Y') $print_str = '(外卖) ' .  $print_str;

                                printer_draw_text($handle, iconv("UTF-8", "gb2312", $print_str), 120, $print_y);


                                if ($order_type == "T" || $Print_Item[$i][16] == "#T#") {
                                    printer_draw_text($handle, iconv("UTF-8", "gb2312", "(外带)"), 366, $print_y);
                                };
                                if ($Print_Item[$i][13] == "C") {
                                    printer_draw_text($handle, iconv("UTF-8", "gb2312", "(取消)"), 366, $print_y);
                                };
                                $print_y+=38;
                            } else {
                                if ($order_type == "T" || $Print_Item[$i][16] == "#T#") {
                                    printer_draw_text($handle, "(Takeout)", 366, $print_y);
                                };
                                if ($Print_Item[$i][13] == "C") {
                                    printer_draw_text($handle, "(Cancel)", 366, $print_y);
                                };
                                $print_y += 32;
                            };
                            if (strlen($Print_Item[$i][10]) > 0) {
                                $font = printer_create_font(iconv("UTF-8", "gb2312", "宋体"), 28, 14, PRINTER_FW_BOLD, false, false, false, 0);
                                printer_select_font($handle, $font);
                                $print_str = $Print_Item[$i][10];
                                $len = mb_strlen($print_str, 'UTF-8');
                                $strb = 0;
                                while ($len > $strb) {
                                    $subStr = mb_substr($print_str, $strb, 16);
                                    printer_draw_text($handle, iconv("UTF-8", "gb2312", $subStr), 120, $print_y);
                                    $strb += 16;
                                    $print_y+=32;
                                }

                                $print_y-=32;
                            };
                            $print_y += 46;
                        };
                    };
                    //End.
                    $print_y += 10;
                    $pen = printer_create_pen(PRINTER_PEN_SOLID, 2, "000000");
                    printer_select_pen($handle, $pen);
                    printer_draw_line($handle, 21, $print_y, 600, $print_y);

                    $print_y += 10;
                    $font = printer_create_font("Arial", 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
                    printer_select_font($handle, $font);
                    printer_draw_text($handle, $date_time, 80, $print_y);

                    printer_delete_font($font);

                    printer_end_page($handle);
                    printer_end_doc($handle);
                    printer_close($handle);
                };
            };

            if (isset($_SESSION['DELEITEM_' . $table])) {
                unset($_SESSION['DELEITEM_' . $table]);
            };

            echo $Print_Item;

            echo true;
        }
        exit;
    }
}




 ?>