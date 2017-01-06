<?php 

class PrintController extends AppController {

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
    }

    // each chinese character take two byte
    public function printItemZh($str, $x, &$y) {
        $fontZh = printer_create_font(iconv("UTF-8", "gb2312", "宋体"), $this->fontH, $this->fontW, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($this->handle, $fontZh);

        // change the str to chinese string
        $str =  iconv("UTF-8", "gb2312", $str);
        $start = 0;

        while (strlen($str) != 0) {
            $print_str = substr($str, $start, $this->charNo);
            printer_draw_text($this->handle, $print_str, $x, $y);
            

            $y += 30; // change the line

            $start += $this->charNo;
            $str = substr($str, $start);
        }

        // printer_draw_text($this->handle, iconv("UTF-8", "gb2312", $str), $x, $y);
    }

    public function printItemEn($str, $x, &$y) {
        $font = printer_create_font("Arial", 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($this->handle, $font);

        while (strlen($str) != 0) {
            $print_str = substr($str, $start, $this->charNo);
            printer_draw_text($this->handle, $print_str, $x, $y);
            

            $y += 30; // change the line

            $start += $this->charNo;
            $str = substr($str, $start);
        }
    }

    public function printEn($str, $x, $y) {
        $font = printer_create_font("Arial", 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($this->handle, $font);
        printer_draw_text($this->handle, $str, $x, $y);
    }

    public function printBigEn($str, $x, $y) {
        $font = printer_create_font("Arial", 32, 14, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($this->handle, $font);
        printer_draw_text($this->handle, $str, $x, $y);
    }


    // order number
    public function printOriginalBill($order_no, $table_no, $printer_name, $print_zh=true, $is_receipt=true) {
        $this->layout = false;
        $this->autoRender = NULL;   

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

        echo json_encode($order);

        date_default_timezone_set("America/Toronto");
        $date_time = date("l M d Y h:i:s A");

        


        $this->handle = printer_open($printer_name);
        printer_start_doc($this->handle, "my_Receipt");
        printer_start_page($this->handle);
        $this->printBigEn("2038 Yonge St.", 156, 130);
        $this->printBigEn("Toronto ON M4S 1Z9", 110, 168);
        $this->printBigEn("416-792-4476", 156, 206);

        $print_y = 244;

        if ($print_zh == true) {
            $this->printZh("此单不包含小费，感谢您的光临", 100, $print_y);
            $print_y+=40;
            $this->printZh("谢谢", 210, $print_y);
            $print_y+=40;
        }


        $this->printBigEn("Order Number: #" . $order_no , 32, $print_y);
        $print_y+=40;
        $this->printBigEn("Table:" . iconv("UTF-8", "gb2312", $table_no), 32, $print_y);
        $print_y+=38;

        $pen = printer_create_pen(PRINTER_PEN_SOLID, 2, "000000");
        printer_select_pen($this->handle, $pen);
        printer_draw_line($this->handle, 21, $print_y, 600, $print_y);

        // print order item
        $print_y += 20;
        for ($i = 0; $i < count($items); ++$i) {
            $this->printEn($items[$i]['name_en'], 10, $print_y);
            $this->printEn(number_format($items[$i]['price'], 2), 360, $print_y);
            $print_y += 30;
            if ($print_zh == true) {
                $this->printZh($items[$i]['name_zh'], 10, $print_y);
            };

            $print_y += 30;

            if (!empty(trim($items[$i]['selected_extras_name']))) {
                // $print_y += 30;
                $this->printItemZh( $items[$i]['selected_extras_name'], 32, $print_y);

                $this->printEn( number_format($items[$i]['extra_amount'], 2), 360, $print_y);
            }

            $print_y += 40;
        }

        $print_y += 10;
        $pen = printer_create_pen(PRINTER_PEN_SOLID, 2, "000000");
        printer_select_pen($this->handle, $pen);
        printer_draw_line($Printer, 21, $print_y, 600, $print_y);

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

        printer_delete_font($font);

        printer_end_page($this->handle);
        printer_end_doc($this->handle);
        printer_close($this->handle);

        echo true;
        exit;

        
    }


    public function printSplitReceipt($order_no, $table_no, $printer_name, $print_zh=true, $is_receipt) {
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

        date_default_timezone_set("America/Toronto");
        $date_time = date("l M d Y h:i:s A");


        $this->handle = printer_open($printer_name);
        printer_start_doc($this->handle, "my_Receipt");
        printer_start_page($this->handle);
        $this->printBigEn("2038 Yonge St.", 156, 130);
        $this->printBigEn("Toronto ON M4S 1Z9", 110, 168);
        $this->printBigEn("416-792-4476", 156, 206);

        $print_y = 244;

        if ($print_zh == true) {
            $this->printZh("此单不包含小费，感谢您的光临", 100, $print_y);
            $print_y+=40;
            $this->printZh("谢谢", 210, $print_y);
            $print_y+=40;
        }


        $this->printBigEn("Order Number: #" . $order_no . '-' . $suborder_no , 32, $print_y);
        $print_y+=40;
        $this->printBigEn("Table:" . iconv("UTF-8", "gb2312", $table_no), 32, $print_y);
        $print_y+=38;

        $pen = printer_create_pen(PRINTER_PEN_SOLID, 2, "000000");
        printer_select_pen($this->handle, $pen);
        printer_draw_line($this->handle, 21, $print_y, 600, $print_y);

        // print order item
        $print_y += 20;
        for ($i = 0; $i < count($items); ++$i) {
            $this->printEn($items[$i]['name_en'], 10, $print_y);
            $this->printEn(number_format($items[$i]['price'], 2), 360, $print_y);
            $print_y += 30;
            if ($print_zh == true) {
                $this->printZh($items[$i]['name_zh'], 10, $print_y);
            };

            $print_y += 30;

            if (!empty(trim($items[$i]['selected_extras_name']))) {
                // $print_y += 30;
                $this->printItemZh( $items[$i]['selected_extras_name'], 32, $print_y);

                // $this->printEn( number_format($items[$i]['extra_amount'], 2), 360, $print_y);
            }

            $print_y += 40;
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

        printer_delete_font($font);

        printer_end_page($this->handle);
        printer_end_doc($this->handle);
        printer_close($this->handle);

        echo true;
        exit;

    }
}




 ?>