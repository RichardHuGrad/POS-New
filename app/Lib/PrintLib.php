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



    public function printHeaderPage($handle, $order_no, $table_no, $print_zh=true, $header_type) {
        printer_start_page($handle);

        $y = 10;

        if ($header_type == "kitchen") {
            if ($print_zh == true) {
                $font = printer_create_font(iconv("UTF-8", "gb2312", "宋体"), 42, 18, PRINTER_FW_BOLD, false, false, false, 0);
                printer_select_font($handle, $font);
                printer_draw_text($handle, iconv("UTF-8", "gb2312", "后厨组"), 138, $y);
            } else {
                $font = printer_create_font("Arial", 42, 18, PRINTER_FW_MEDIUM, false, false, false, 0);
                printer_select_font($handle, $font);
                printer_draw_text($handle, "Kitchen", 138, $y);
            }
        }
        printer_end_page($handle);
        

        printer_start_page($handle);

        $y = 0;
        //Print order information
        $font = printer_create_font("Arial", 32, 14, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($handle, $font);
        printer_draw_text($handle, "Order Number: #" . $order_no, 32, $y);

        $y += 35;
        printer_draw_text($handle, "Table:" . iconv("UTF-8", "gb2312", $table_no), 32, $y);
        //End

        $y += 35;
        $pen = printer_create_pen(PRINTER_PEN_SOLID, 2, "000000");
        printer_select_pen($this->handle, $pen);
        printer_draw_line($this->handle, 21, $y, 600, $y);

        printer_end_page($handle);
    }

    public function printFooterPage($handle) {
        printer_start_page($handle);

        date_default_timezone_set("America/Toronto");
        $date_time = date("l M d Y h:i:s A");

        $print_y = 10;
        $pen = printer_create_pen(PRINTER_PEN_SOLID, 2, "000000");
        printer_select_pen($handle, $pen);
        printer_draw_line($handle, 21, $print_y, 600, $print_y);
        printer_end_page($handle);
        
        $print_y += 10;
        $font = printer_create_font("Arial", 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($handle, $font);
        printer_draw_text($handle, $date_time, 80, $print_y);


        printer_end_page($handle);
    }




    // print all items with cancelled tag
    public function printCancelledItems($order_no, $table_no, $printer_name, $item_detail, $print_zh=true, $print_en=true) {
        // do not check $item_id_list 

        if (!function_exists('printer_open')) {
            $test_str = "";

            foreach ($item_detail as $item) {
                $name_zh = $item['order_items']['name_xh'];
                $name_en = $item['order_items']['name_en'];
                // $price = $item['order_items']['price'];
                $selected_extras = $item['order_items']['selected_extras'];

                if ($item['order_items']['is_takeout']) {
                    $test_str .= "(外卖)";
                }

                $test_str .= $name_zh . $name_en . '(取消)' . '    ' . $selected_extras;
            }

            return $test_str;

        }

        $handle = printer_open($printer_name);
        printer_start_doc($handle, "kitchen");

        // print header
        $this->printHeaderPage($handle, $order_no, $table_no, true, );

        printer_start_page($handle);

        $font = printer_create_font(iconv("UTF-8", "gb2312", "宋体"), 42, 18, PRINTER_FW_BOLD, false, false, false, 0);
        printer_select_font($handle, $font);

        $test_str = "";

        foreach ($item_detail as $item) {
            $name_zh = $item['order_items']['name_xh'];
            $name_en = $item['order_items']['name_en'];
            // $price = $item['order_items']['price'];
            $selected_extras = $item['order_items']['selected_extras'];

            if ($item['order_items']['is_takeout']) {
                $test_str .= "(外卖)";
            }

            $test_str .= $name_zh . $name_en . '(取消)' . '    ' . $selected_extras;

            printer_draw_text($handle, $test_str, 80, $print_y);
        }



        // print footer
        $this->printFooterPage($handle);


        printer_delete_font($font);
        printer_end_doc($handle);
        printer_close($handle);
    }


}




 ?>