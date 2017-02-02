<?php 


function mbStrSplit($string, $len=1) {
    $start = 0;
    $strlen = mb_strlen($string);
    while ($strlen) {
        $array[] = mb_substr($string,$start,$len,"utf8");
        $string = mb_substr($string, $len, $strlen,"utf8");
        $strlen = mb_strlen($string);
    }
    return $array;
}

class PrintLib {
    public $fontStr1 = "simsun"; 

    // print all items with cancelled tag
    public function printCancelledItems($order_no, $table_no, $table_type, $printer_name, $item_detail, $print_zh=true, $print_en=false) {
        // do not check $item_id_list 

        $debug_str = json_encode($item_detail);

        if (!function_exists('printer_open')) {
            return $debug_str;
        }

        // add cancel for each item
        for ($i = 0; $i < count($item_detail); ++$i) {
            $item_detail[$i]['name_xh'] = "(取消)" .  $item_detail[$i]['name_xh'];
            $item_detail[$i]['name_en'] = "(Cancel)" . $item_detail[$i]['name_en'];
        }



        $handle = printer_open($printer_name);
        printer_start_doc($handle, "kitchen");

        $strategy = new KitchenStrategy();

        // print header
        $strategy->printHeaderPage($handle, $order_no, $table_no, $table_type, true);

        // print items
        $strategy->printItemsPage($handle, $item_detail, $table_type);

        // print footer
        $strategy->printFooterPage($handle);


        printer_end_doc($handle);
        printer_close($handle);

        // send feedback to server
        return $debug_str;
    }


    public function printKitchenItemDoc($order_no, $table_no, $table_type, $printer_name, $item_detail, $print_zh=true, $print_en=false) {

        $debug_str = json_encode($item_detail);

        if (!function_exists('printer_open')) {
            return $debug_str;
        }


        $handle = printer_open($printer_name);
        printer_start_doc($handle, "kitchen");

        $strategy = new KitchenStrategy();

        // print header
        $strategy->printHeaderPage($handle, $order_no, $table_no, $table_type, true);

        // print items
        $strategy->printItemsPage($handle, $item_detail, $table_type);

        // print footer
        $strategy->printFooterPage($handle);

        printer_end_doc($handle);
        printer_close($handle);

        return $debug_str;

    }

    public function printUrgeItemDoc($order_no, $table_no, $table_type, $printer_name, $item_detail, $print_zh=true, $print_en=false) {
        $debug_str = json_encode($item_detail);

        if (!function_exists('printer_open')) {
            return $debug_str;
        }

        // add cancel for each item
        for ($i = 0; $i < count($item_detail); ++$i) {
            $item_detail[$i]['name_xh'] = "(加急)" .  $item_detail[$i]['name_xh'];
            $item_detail[$i]['name_en'] = "(Urgent)" . $item_detail[$i]['name_en'];
        }



        $handle = printer_open($printer_name);
        printer_start_doc($handle, "kitchen");

        $strategy = new KitchenStrategy();

        // print header
        $strategy->printHeaderPage($handle, $order_no, $table_no, $table_type, true);

        // print items
        $strategy->printItemsPage($handle, $item_detail, $table_type);

        // print footer
        $strategy->printFooterPage($handle);


        printer_end_doc($handle);
        printer_close($handle);

        // send feedback to server
        return $debug_str;
    }

    public function printPayBillDoc($order_no, $table_no, $table_type, $printer_name, $item_detail, $bill_info,$logo_name,$print_zh=true, $print_en=false) {
        $debug_str = json_encode($item_detail);
        $debug_str .= json_encode($bill_info);

        if (!function_exists('printer_open')) {
            return $debug_str;
        }

        $handle = printer_open($printer_name);
        printer_start_doc($handle, "my_Receipt");

        $strategy = new PayStrategy();
        // print header
        $strategy->printHeaderPage($handle, $order_no, $table_no, $table_type, $logo_name, true);
        $strategy->printItemsPage($handle, $item_detail);
        $strategy->printBillInfoPage($handle, $bill_info, false);
        $strategy->printFooterPage($handle);

        printer_end_doc($handle);
        printer_close($handle);

        return $debug_str;
    }


    public function printPayReceiptDoc($order_no, $table_no, $table_type, $printer_name, $item_detail, $bill_info,$logo_name,$print_zh=true, $print_en=false) {
        $debug_str = json_encode($item_detail);
        $debug_str .= json_encode($bill_info);

        if (!function_exists('printer_open')) {
            return $debug_str;
        }

        $handle = printer_open($printer_name);
        printer_start_doc($handle, "my_Receipt");

        $strategy = new PayStrategy();
        // print header
        $strategy->printHeaderPage($handle, $order_no, $table_no, $table_type, $logo_name, true);
        $strategy->printItemsPage($handle, $item_detail);
        $strategy->printBillInfoPage($handle, $bill_info, true);
        $strategy->printFooterPage($handle);

        printer_end_doc($handle);
        printer_close($handle);

        return $debug_str;
    }

    public function printMergeBillDoc($order_nos, $table_nos, $table_type, $printer_name, $item_details, $bill_info, $logo_name,$print_zh=true, $print_en=false) {
        $debug_str = json_encode($item_detail);
        $debug_str .= json_encode($bill_info);

        if (!function_exists('printer_open')) {
            return $debug_str;
        }
        $handle = printer_open($printer_name);
        printer_start_doc($handle, "my_Receipt");

        $strategy = new MergeDoc();
        // print header
        $strategy->printHeaderPage($handle, $order_nos, $table_nos, $table_type, $logo_name, true);
        $strategy->printItemsPage($handle, $item_details);
        $strategy->printBillInfoPage($handle, $bill_info, false);
        $strategy->printFooterPage($handle);

        printer_end_doc($handle);
        printer_close($handle);
    }

    public function printMergeReceiptDoc($order_nos, $table_nos, $table_type, $printer_name, $item_details, $bill_info, $logo_name,$print_zh=true, $print_en=false) {
        $debug_str = json_encode($item_detail);
        $debug_str .= json_encode($bill_info);

        if (!function_exists('printer_open')) {
            return $debug_str;
        }
        $handle = printer_open($printer_name);
        printer_start_doc($handle, "my_Receipt");

        $strategy = new MergeDoc();
        // print header
        $strategy->printHeaderPage($handle, $order_nos, $table_nos, $table_type, $logo_name, true);
        $strategy->printItemsPage($handle, $item_details);
        $strategy->printBillInfoPage($handle, $bill_info, true);
        $strategy->printFooterPage($handle);

        printer_end_doc($handle);
        printer_close($handle);
    }


}

interface PrintStrategyInterface {
    public function printHeaderPage($handle, $order_no, $table_no, $table_type, $print_zh);
    public function printItemsPage($handle, $item_detail, $table_type);
    public function printFooterPage($handle);
}

class KitchenStrategy {
    public $fontStr1 = "simsun";
    public function printHeaderPage($handle, $order_no, $table_no, $table_type, $print_zh) {
        printer_start_page($handle);

        $table_type_str = "";
        if ($table_type == 'D') {
            $table_type_str = '[[堂食]]';
        } else if ($table_type == 'T') {
            $table_type_str = '[[外卖]]';
        } else if ($table_type == 'W') {
            $table_type_str = '[[等候]]';
        }

        $y = 10;


        if ($print_zh == true) {
            $font = printer_create_font($this->fontStr1, 42, 18, PRINTER_FW_BOLD, false, false, false, 0);
            printer_select_font($handle, $font);
            printer_draw_text($handle, iconv("UTF-8", "gb2312", "后厨组"), 138, $y);
        } else {
            $font = printer_create_font("Arial", 42, 18, PRINTER_FW_MEDIUM, false, false, false, 0);
            printer_select_font($handle, $font);
            printer_draw_text($handle, "Kitchen", 138, $y);
        }
            
        printer_end_page($handle);
        

        printer_start_page($handle);

        $y = 0;
        //Print order information
        $font = printer_create_font("Arial", 32, 14, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($handle, $font);
        printer_draw_text($handle, "Order Number: #" . $order_no, 32, $y);

        $y += 35;
        printer_draw_text($handle, "Table:" . iconv("UTF-8", "gb2312", $table_type_str . '#' . $table_no), 32, $y);
        //End

        $y += 35;
        $pen = printer_create_pen(PRINTER_PEN_SOLID, 2, "000000");
        printer_select_pen($handle, $pen);
        printer_draw_line($handle, 21, $y, 600, $y);


        printer_delete_font($font);
        printer_end_page($handle);
    }

    public function printItemsPage($handle, $item_detail, $table_type) {
        foreach ($item_detail as $item) {
            printer_start_page($handle);

            $font1H = 32;
            $font2H = 38;
            $font3H = 32; 
            $font1 = printer_create_font("Arial", $font1H, 12, PRINTER_FW_MEDIUM, false, false, false, 0);
            $font2 = printer_create_font($this->fontStr1, $font2H, 16, PRINTER_FW_BOLD, false, false, false, 0);
            
            $font3 = printer_create_font($this->fontStr1, $font3H, 14, PRINTER_FW_BOLD, false, false, false, 0); //maximum 12 per line
            

            $name_zh = $item['name_xh'];
            $name_en = $item['name_en'];
            $qty = $item['qty'];
            $special = $item['special_instruction'];
            // $price = $item['price'];
            $selected_extras = $item['selected_extras'];

            if ($item['is_takeout'] == 'Y' || $table_type == "T") {
                $name_zh = "(外卖)" . $name_zh;
                $name_en = "(Take out)" . $name_en;
            }

            $y = 10;

            printer_select_font($handle, $font1);
            printer_draw_text($handle, $qty, 10, $y);
            printer_draw_text($handle, $name_en, 80, $y);
            $y += $font1H + 3;
        
            printer_select_font($handle, $font2);
            printer_draw_text($handle,iconv("UTF-8", "gb2312", $name_zh), 80, $y);
            $y += $font2H + 3;
            
            printer_select_font($handle, $font3);

            if (strlen($selected_extras) > 0) {
                $selected_extras_arr = mbStrSplit($selected_extras, 14);
                foreach($selected_extras_arr as $line) {
                    printer_draw_text($handle, iconv("UTF-8", "gb2312", $line), 80, $y);
                    $y += $font3H;
                }
            }
            if (strlen($special) > 0) {
                $special = '特:' . $special;
                $special_arr = mbStrSplit($special, 14);
                foreach($special_arr as $line) {
                    printer_draw_text($handle, iconv("UTF-8", "gb2312", $line), 80, $y);
                    $y += $font3H;
                }
            }

            printer_delete_font($font1);
            printer_delete_font($font2);
            printer_delete_font($font3);

            printer_end_page($handle);
        }    
    }

    public function printFooterPage($handle) {
        printer_start_page($handle);

        date_default_timezone_set("America/Toronto");
        $date_time = date("l M d Y h:i:s A");

        $print_y = 10;
        $pen = printer_create_pen(PRINTER_PEN_SOLID, 2, "000000");
        printer_select_pen($handle, $pen);
        printer_draw_line($handle, 21, $print_y, 600, $print_y);
        
        $print_y += 10;
        $font = printer_create_font("Arial", 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($handle, $font);
        printer_draw_text($handle, $date_time, 80, $print_y);

        printer_delete_font($font);
        printer_end_page($handle);
    }
}

class PayStrategy {
    public $fontStr1 = "simsun";
    private $titleFontZh;
    private $titleFontEn;
    private $fontZh;
    private $fontEn;
    
    public function printHeaderPage($handle, $order_no, $table_no, $table_type, $logo_name, $print_zh=true) {
        printer_start_page($handle);


        $table_type_str = "";
        if ($table_type == 'D') {
            $table_type_str = '[[堂食]]';
        } else if ($table_type == 'T') {
            $table_type_str = '[[外卖]]';
        } else if ($table_type == 'W') {
            $table_type_str = '[[等候]]';
        }

        // print Logo image
        printer_draw_bmp($handle, $logo_name, 100, 20, 263, 100);

        $font = printer_create_font("Arial", 32, 14, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($handle, $font);
        // print address line
        printer_draw_text($handle, "3700 Midland Ave. #108", 156, 130);
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

        $font = printer_create_font("Arial", 32, 14, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($handle, $font);

        printer_draw_text($handle, "Order Number: #" . $order_no, 32, $print_y);
        $print_y+=40;
        printer_draw_text($handle, "Table:" . iconv("UTF-8", "gb2312", $table_type_str . '#' . $table_no), 32, $print_y);
        $print_y+=38;

        $pen = printer_create_pen(PRINTER_PEN_SOLID, 2, "000000");
        printer_select_pen($handle, $pen);
        printer_draw_line($handle, 21, $print_y, 600, $print_y);

        printer_end_page($handle);
    }

    public function printItemsPage($handle, $item_detail/*, $table_type*/) {
        foreach ($item_detail as $item) {
            printer_start_page($handle);

            
            $font = printer_create_font($this->fontStr1, 28, 12, PRINTER_FW_MEDIUM, false, false, false, 0);

            $name_zh = $item['name_xh'];
            $name_en = $item['name_en'];
            $qty = $item['qty'];
            $special = $item['special_instruction'];
            $price = $item['price'];
            $selected_extras = $item['selected_extras'];

            // if ($item['is_takeout'] == 'Y' || $table_type == "T") {
            //     $name_zh = "(外卖)" . $name_zh;
            //     $name_en = "(Take out)" . $name_en;
            // }

            $y = 10;
            $origin_y = $y;

            printer_select_font($handle, $font);
            printer_draw_text($handle, mbStrSplit($name_en, 20)[0], 80, $y);
            $y += 30;
        
            printer_select_font($handle, $font);
            printer_draw_text($handle,iconv("UTF-8", "gb2312", $name_zh), 80, $y);
            $y += 30;

            printer_draw_text($handle, $qty, 10, $origin_y);
            printer_draw_text($handle, number_format($price, 2), 400, $origin_y);
            

           /* if (strlen($selected_extras) > 0) {
                $selected_extras_arr = mbStrSplit($selected_extras, 14);
                foreach($selected_extras_arr as $line) {
                    printer_draw_text($handle, iconv("UTF-8", "gb2312", $line), 80, $y);
                    $y += $font3H;
                }
            }
            */

            printer_delete_font($font);

            printer_end_page($handle);
        }    
    }
    private function formatBillItem($handle, $str1, $str2, $num) {
        printer_start_page($handle);
        $font = printer_create_font($this->fontStr1, 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($handle, $font);
        
        printer_draw_text($handle, iconv("UTF-8", "gb2312", $str1), 58, 0);
        printer_draw_text($handle, iconv("UTF-8", "gb2312", $str2), 148, 0);

        printer_draw_text($handle, iconv("UTF-8", "gb2312", number_format($num, 2)), 400, 0);
        
        printer_delete_font($font);
        printer_end_page($handle);
    }

    public function printBillInfoPage($handle, $bill_info, $is_receipt) {
        // $billArr = array()`
        
        $subtotal = $bill_info['subtotal'];
        $discount_amount = $bill_info['discount_value'];
        $after_discount = $bill_info['after_discount'];
        $tax_rate = $bill_info['tax'];
        $tax_amount = $bill_info['tax_amount'];
        $total = $bill_info['total'];
        $paid = $bill_info['paid'];
        $change = $bill_info['change'];


        printer_start_page($handle);
        $print_y = 10;
        $pen = printer_create_pen(PRINTER_PEN_SOLID, 2, "000000");
        printer_select_pen($handle, $pen);
        printer_draw_line($handle, 21, $print_y, 600, $print_y);
        printer_end_page($handle);

        $this->formatBillItem($handle, "Subtotal", "小计:", $subtotal);

        if (floatval($discount_amount) > 0 ) {
            $this->formatBillItem($handle, "Discount", "折扣:", $discount_amount);
            $this->formatBillItem($handle, "After Discount", "折后价:", $after_discount);
        }
        $this->formatBillItem($handle, "Hst"."(" . $tax_rate . "%)", "税:", $tax_amount);

        $this->formatBillItem($handle, "Total", "总计:", $total);

        if ($is_receipt == true) {
            $this->formatBillItem($handle, "Paid", "付款:", $paid);
            $this->formatBillItem($handle, "Change", "找零:", $change);
        }

    }

    public function printFooterPage($handle) {
        printer_start_page($handle);

        date_default_timezone_set("America/Toronto");
        $date_time = date("l M d Y h:i:s A");

        $print_y = 10;
        $pen = printer_create_pen(PRINTER_PEN_SOLID, 2, "000000");
        printer_select_pen($handle, $pen);
        printer_draw_line($handle, 21, $print_y, 600, $print_y);
        
        $print_y += 10;
        $font = printer_create_font("Arial", 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($handle, $font);
        printer_draw_text($handle, $date_time, 80, $print_y);

        printer_delete_font($font);
        printer_end_page($handle);
    }
}


class MergeDoc {
    public $fontStr1 = "simsun";
    private $titleFontZh;
    private $titleFontEn;
    private $fontZh;
    private $fontEn;
    
    // order_nos
    // table_nos
    public function printHeaderPage($handle, $order_nos, $table_nos, $table_type, $logo_name, $print_zh=true) {
        printer_start_page($handle);


        $table_type_str = "";
        if ($table_type == 'D') {
            $table_type_str = '[[堂食]]';
        } else if ($table_type == 'T') {
            $table_type_str = '[[外卖]]';
        } else if ($table_type == 'W') {
            $table_type_str = '[[等候]]';
        }

        // print Logo image
        printer_draw_bmp($handle, $logo_name, 100, 20, 263, 100);

        $font = printer_create_font("Arial", 32, 14, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($handle, $font);
        // print address line
        printer_draw_text($handle, "3700 Midland Ave. #108", 156, 130);
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

        $font = printer_create_font("Arial", 32, 14, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($handle, $font);

        printer_draw_text($handle, "Order Number: #" . $order_nos, 32, $print_y);
        $print_y+=40;
        printer_draw_text($handle, "Table:" . iconv("UTF-8", "gb2312", $table_type_str . '#' . $table_nos), 32, $print_y);
        $print_y+=38;

        $pen = printer_create_pen(PRINTER_PEN_SOLID, 2, "000000");
        printer_select_pen($handle, $pen);
        printer_draw_line($handle, 21, $print_y, 600, $print_y);

        printer_end_page($handle);
    }

    public function printItemsPage($handle, $item_details/*, $table_type*/) {
        $i = 0;
        foreach($item_details as $item_detail) {
            printer_start_page($handle);
            $font = printer_create_font($this->fontStr1, 28, 12, PRINTER_FW_MEDIUM, false, false, false, 0);
            printer_select_font($handle, $font);

            printer_draw_text($handle, '#' . $i++, 10, $y);

            printer_delete_font($font);
            printer_end_page($handle);
            foreach ($item_detail as $item) {
                printer_start_page($handle);

                
                $font = printer_create_font($this->fontStr1, 28, 12, PRINTER_FW_MEDIUM, false, false, false, 0);

                $name_zh = $item['name_xh'];
                $name_en = $item['name_en'];
                $qty = $item['qty'];
                $special = $item['special_instruction'];
                $price = $item['price'];
                $selected_extras = $item['selected_extras'];

                // if ($item['is_takeout'] == 'Y' || $table_type == "T") {
                //     $name_zh = "(外卖)" . $name_zh;
                //     $name_en = "(Take out)" . $name_en;
                // }

                $y = 10;
                $origin_y = $y;

                printer_select_font($handle, $font);
                printer_draw_text($handle, mbStrSplit($name_en, 20)[0], 80, $y);
                $y += 30;
            
                printer_select_font($handle, $font);
                printer_draw_text($handle,iconv("UTF-8", "gb2312", $name_zh), 80, $y);
                $y += 30;

                printer_draw_text($handle, $qty, 10, $origin_y);
                printer_draw_text($handle, number_format($price, 2), 400, $origin_y);
                

               /* if (strlen($selected_extras) > 0) {
                    $selected_extras_arr = mbStrSplit($selected_extras, 14);
                    foreach($selected_extras_arr as $line) {
                        printer_draw_text($handle, iconv("UTF-8", "gb2312", $line), 80, $y);
                        $y += $font3H;
                    }
                }
                */

                printer_delete_font($font);

                printer_end_page($handle);
            }
        }
    }
    private function formatBillItem($handle, $str1, $str2, $num) {
        printer_start_page($handle);
        $font = printer_create_font($this->fontStr1, 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($handle, $font);
        
        printer_draw_text($handle, iconv("UTF-8", "gb2312", $str1), 58, 0);
        printer_draw_text($handle, iconv("UTF-8", "gb2312", $str2), 148, 0);

        printer_draw_text($handle, iconv("UTF-8", "gb2312", number_format($num, 2)), 400, 0);
        
        printer_delete_font($font);
        printer_end_page($handle);
    }

    public function printBillInfoPage($handle, $bill_info, $is_receipt) {
        // $billArr = array()`
        
        $subtotal = $bill_info['subtotal'];
        $discount_amount = $bill_info['discount_value'];
        $after_discount = $bill_info['after_discount'];
        $tax_rate = $bill_info['tax'];
        $tax_amount = $bill_info['tax_amount'];
        $total = $bill_info['total'];
        $paid = $bill_info['paid'];
        $change = $bill_info['change'];


        printer_start_page($handle);
        $print_y = 10;
        $pen = printer_create_pen(PRINTER_PEN_SOLID, 2, "000000");
        printer_select_pen($handle, $pen);
        printer_draw_line($handle, 21, $print_y, 600, $print_y);
        printer_end_page($handle);

        $this->formatBillItem($handle, "Subtotal", "小计:", $subtotal);

        if (floatval($discount_amount) > 0 ) {
            $this->formatBillItem($handle, "Discount", "折扣:", $discount_amount);
            $this->formatBillItem($handle, "After Discount", "折后价:", $after_discount);
        }
        $this->formatBillItem($handle, "Hst"."(" . $tax_rate . "%)", "税:", $tax_amount);

        $this->formatBillItem($handle, "Total", "总计:", $total);

        if ($is_receipt == true) {
            $this->formatBillItem($handle, "Paid", "付款:", $paid);
            $this->formatBillItem($handle, "Change", "找零:", $change);
        }

    }

    public function printFooterPage($handle) {
        printer_start_page($handle);

        date_default_timezone_set("America/Toronto");
        $date_time = date("l M d Y h:i:s A");

        $print_y = 10;
        $pen = printer_create_pen(PRINTER_PEN_SOLID, 2, "000000");
        printer_select_pen($handle, $pen);
        printer_draw_line($handle, 21, $print_y, 600, $print_y);
        
        $print_y += 10;
        $font = printer_create_font("Arial", 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($handle, $font);
        printer_draw_text($handle, $date_time, 80, $print_y);

        printer_delete_font($font);
        printer_end_page($handle);
    }
}


class DailyReportDoc {

}

interface HeaderPage {
    public function printHeaderPage();
}

interface FooterPage {
    public function printFooterPage();
}


// class HeaderPage {
//     public function KitchenHeaderPage() {

//     }

//     public function ReceiptHeaderPage() {

//     }

//     public function MergeHeaderPage() {

//     }

//     public function SplitHeaderPage() {

//     }
// }




 ?>