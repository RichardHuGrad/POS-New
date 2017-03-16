<?php
App::uses('PrintConfig', 'Lib');

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



        $headerPage = new KitchenHeaderPage($order_no, $table_no, $table_type);
        $itemsPage = new KitchenItemsPage($item_detail, $table_type);
        $footerPage = new TimeFooterPage();

        $doc = new BasicDoc($printer_name, array($headerPage, $itemsPage, $footerPage));
        $doc->printDoc();

        // send feedback to server
        return $debug_str;
    }


    public function printKitchenItemDoc($order_no, $table_no, $table_type, $printer_name, $item_detail, $print_zh=true, $print_en=false) {

        $debug_str = json_encode($item_detail);

        if (!function_exists('printer_open')) {
            return $debug_str;
        }


        $headerPage = new KitchenHeaderPage($order_no, $table_no, $table_type);
        $itemsPage = new KitchenItemsPage($item_detail, $table_type);
        $footerPage = new TimeFooterPage();

        $doc = new BasicDoc($printer_name, array($headerPage, $itemsPage, $footerPage));
        $doc->printDoc();

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

        $headerPage = new KitchenHeaderPage($order_no, $table_no, $table_type);
        $itemsPage = new KitchenItemsPage($item_detail, $table_type);
        $footerPage = new TimeFooterPage();

        $doc = new BasicDoc($printer_name, array($headerPage, $itemsPage, $footerPage));
        $doc->printDoc();

        // send feedback to server
        return $debug_str;
    }

    public function printPayBillDoc($order_no, $table_no, $table_type, $printer_name, $item_detail, $bill_info,$logo_name,$print_zh=true, $print_en=false) {
        $debug_str = json_encode($item_detail);
        $debug_str .= json_encode($bill_info);

        if (!function_exists('printer_open')) {
            return $debug_str;
        }


        $headerPage = new LogoHeaderPage($order_no, $table_no, $table_type, $logo_name);
        $itemsPage = new PayItemsPage($item_detail);
        $countPage = new BillPage($bill_info);
        $footerPage = new TimeHSTFooterPage();

        $doc = new BasicDoc($printer_name, array($headerPage, $itemsPage, $countPage, $footerPage));
        $doc->printDoc();




        return $debug_str;
    }


    public function printPayReceiptDoc($order_no, $table_no, $table_type, $printer_name, $item_detail, $bill_info,$logo_name,$print_zh=true, $print_en=false) {
        $debug_str = json_encode($item_detail);
        $debug_str .= json_encode($bill_info);

        if (!function_exists('printer_open')) {
            return $debug_str;
        }

        $headerPage = new LogoHeaderPage($order_no, $table_no, $table_type, $logo_name);
        $itemsPage = new PayItemsPage($item_detail);
        $countPage = new ReceiptPage($bill_info);
        $footerPage = new TimeHSTFooterPage();

        $doc = new BasicDoc($printer_name, array($headerPage, $itemsPage, $countPage, $footerPage));
        $doc->printDoc();


        return $debug_str;
    }

    public function printMergeBillDoc($order_nos, $table_nos, $table_type, $printer_name, $item_details, $bill_info, $logo_name,$print_zh=true, $print_en=false) {
        $debug_str = json_encode($item_details);
        $debug_str .= json_encode($bill_info);

        if (!function_exists('printer_open')) {
            return $debug_str;
        }

        $headerPage = new LogoHeaderPage($order_nos, $table_nos, $table_type, $logo_name);
        $itemsPage = new MergeItemsPage($item_details);
        $countPage = new BillPage($bill_info);
        $footerPage = new TimeHSTFooterPage();

        $doc = new BasicDoc($printer_name, array($headerPage, $itemsPage, $countPage, $footerPage));
        $doc->printDoc();
    }

    public function printMergeReceiptDoc($order_nos, $table_nos, $table_type, $printer_name, $item_details, $bill_info, $logo_name,$print_zh=true, $print_en=false) {
        $debug_str = json_encode($item_details);
        $debug_str .= json_encode($bill_info);

        if (!function_exists('printer_open')) {
            return $debug_str;
        }

        $headerPage = new LogoHeaderPage($order_nos, $table_nos, $table_type, $logo_name);
        $itemsPage = new MergeItemsPage($item_details);
        $countPage = new ReceiptPage($bill_info);
        $footerPage = new TimeHSTFooterPage();
        $doc = new BasicDoc($printer_name, array($headerPage, $itemsPage, $countPage, $footerPage));
        $doc->printDoc();
    }

    public function printDailyReportDoc($printer_name, $dailyAmount, $dailyAmountTotal) {
        $debug_str = json_encode($dailyAmount);
        // $debug_str .= json_encode($dailyItems);

        if (!function_exists('printer_open')) {
            return $debug_str;
        }

        $headerPage = new TextHeaderPage("All Orders (总单)", 108);
        $dailyAmountPage = new ReportCountPage($dailyAmount);
        $dailyAmountDetailPage = new ReportCountDetailPage($dailyAmountTotal);

        $doc = new BasicDoc($printer_name, array($headerPage, $dailyAmountPage, $dailyAmountDetailPage));
        $doc->printDoc();

        return $debug_str;
    }

    public function printDailyItemsDoc($printer_name, $dailyItems) {
        // $debug_str = json_encode($dailyAmount);
        $debug_str .= json_encode($dailyItems);

        if (!function_exists('printer_open')) {
            return $debug_str;
        }

        $dailyItemHeaderPage = new TextHeaderPage("Sales Statistics 销量统计", 30);
        $dailyItemsPage = new ReportItemsPage($dailyItems);

        $doc = new BasicDoc($printer_name, array($dailyItemHeaderPage, $dailyItemsPage));
        $doc->printDoc();

        return $debug_str;
    }

}


abstract class HeaderPage {
    abstract function printPage($handle);
}

// with logo, address information
class KitchenHeaderPage extends HeaderPage {
    private $order_no, $table_no, $table_type, $print_zh, $print_en;
    public function __construct($order_no, $table_no, $table_type, $print_zh=true, $print_en = false) {
        $this->order_no = $order_no;
        $this->table_no = $table_no;
        $this->table_type = $table_type;
        $this->print_zh = $print_zh;
        $this->print_en = $print_en;
    }

    public function printPage($handle) {
        printer_start_page($handle);

        $type_map = array('D' => '[[堂食]]', 'T' => '[[外卖]]', 'W' => '[[等候]]');
        $table_type_str = $type_map[$this->table_type];

        $y = 10;


        if ($this->print_zh == true) {
            $font = printer_create_font('simsun', 42, 18, PRINTER_FW_BOLD, false, false, false, 0);
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
        printer_draw_text($handle, "Order Number: #" . $this->order_no, 32, $y);

        $y += 35;
        printer_draw_text($handle, "Table:" . iconv("UTF-8", "gb2312", $table_type_str . '#' . $this->table_no), 32, $y);
        //End

        $y += 35;
        $pen = printer_create_pen(PRINTER_PEN_SOLID, 2, "000000");
        printer_select_pen($handle, $pen);
        printer_draw_line($handle, 21, $y, 600, $y);


        printer_delete_font($font);
        printer_end_page($handle);
    }
}

class LogoHeaderPage extends HeaderPage {
    private $order_no, $table_no, $table_type, $logo_name, $print_zh, $print_en;

    public function __construct($order_no, $table_no, $table_type, $logo_name, $print_zh=true, $print_en=false) {
        $this->order_no = $order_no;
        $this->table_no = $table_no;
        $this->table_type = $table_type;
        $this->logo_name = $logo_name;
        $this->print_zh = $print_zh;
        $this->print_en = $print_en;
    }

    public function printPage($handle) {
        printer_start_page($handle);

        $type_map = array('D' => '[[堂食]]', 'T' => '[[外卖]]', 'W' => '[[等候]]');
        $table_type_str = $type_map[$this->table_type];

        // print Logo image
        printer_draw_bmp($handle, $this->logo_name, 100, 20, 263, 100);

        $font = printer_create_font("Arial", 32, 14, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($handle, $font);
        // print address line
        printer_draw_text($handle, PrintConfig::$addressLine1["content"], PrintConfig::$addressLine1["offset_x"], 130);
        printer_draw_text($handle, PrintConfig::$addressLine2["content"], PrintConfig::$addressLine2["offset_x"], 168);
        printer_draw_text($handle, PrintConfig::$phone["content"], printConfig::$phone["offset_x"], 206);

        $print_y = 244;
        if ($this->print_zh == true) {
            $font = printer_create_font('simsun', 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
            printer_select_font($handle, $font);
            printer_draw_text($handle, iconv("UTF-8", "gb2312", "此单不包含小费，感谢您的光临"), 100, $print_y);
            $print_y+=40;
            printer_draw_text($handle, iconv("UTF-8", "gb2312", "The tips are not included"), 110, $print_y);
            // printer_draw_text($handle, iconv("UTF-8", "gb2312", "谢谢"), 210, $print_y);
            $print_y+=40;
        };

        $font = printer_create_font("Arial", 32, 14, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($handle, $font);

        printer_draw_text($handle, "Order Number: #" . $this->order_no, 32, $print_y);
        $print_y+=40;
        printer_draw_text($handle, "Table:" . iconv("UTF-8", "gb2312", $table_type_str . '#' . $this->table_no), 32, $print_y);
        $print_y+=38;

        $pen = printer_create_pen(PRINTER_PEN_SOLID, 2, "000000");
        printer_select_pen($handle, $pen);
        printer_draw_line($handle, 21, $print_y, 600, $print_y);

        printer_end_page($handle);
    }
}


class TextHeaderPage extends HeaderPage {
    private $text, $x, $y;
    function __construct($text, $x, $y=0) {
        $this->text = $text;
        $this->x = $x;
        $this->y = $y;
    }

    public function printPage($handle) {
        printer_start_page($handle);
        $font = printer_create_font('simsun', 42, 18, PRINTER_FW_BOLD, false, false, false, 0);
        printer_select_font($handle, $font);
        printer_draw_text($handle, iconv("UTF-8", "gb2312", $this->text), $this->x, $this->y);
        printer_end_page($handle);
    }
}

abstract class ItemsPage {
    abstract function printPage($handle);
}

class KitchenItemsPage extends ItemsPage {
    private $item_detail, $table_type;

    public function __construct($item_detail, $table_type) {
        $this->item_detail = $item_detail;
        $this->table_type = $table_type;
    }

    public function printPage($handle) {
        foreach ($this->item_detail as $item) {
            printer_start_page($handle);

            $font1H = 32;
            $font2H = 38;
            $font3H = 32;
            $font1 = printer_create_font("Arial", $font1H, 12, PRINTER_FW_MEDIUM, false, false, false, 0);
            $font2 = printer_create_font('simsun', $font2H, 16, PRINTER_FW_BOLD, false, false, false, 0);

            $font3 = printer_create_font('simsun', $font3H, 14, PRINTER_FW_BOLD, false, false, false, 0); //maximum 12 per line


            $name_zh = $item['name_xh'];
            $name_en = $item['name_en'];
            $qty = $item['qty'];
            $special = $item['special_instruction'];
            // $price = $item['price'];
            $selected_extras = $item['selected_extras'];

            if ($item['is_takeout'] == 'Y' || $this->table_type == "T") {
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
}


class PayItemsPage extends ItemsPage {
    private $item_detail;

    public function __construct($item_detail) {
        $this->item_detail = $item_detail;
    }
    public function printPage($handle) {
        foreach ($this->item_detail as $item) {
            printer_start_page($handle);


            $font = printer_create_font('simsun', 28, 12, PRINTER_FW_MEDIUM, false, false, false, 0);

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


class MergeItemsPage extends ItemsPage {
    private $item_details;

    public function __construct($item_details) {
        $this->item_details = $item_details;
    }

    public function printPage($handle) {
        $i = 0;
        foreach($this->item_details as $item_detail) {
            printer_start_page($handle);
            $font = printer_create_font('simsun', 28, 12, PRINTER_FW_MEDIUM, false, false, false, 0);
            printer_select_font($handle, $font);

            printer_draw_text($handle, '#' . $i++, 10, 0);

            printer_delete_font($font);
            printer_end_page($handle);
            foreach ($item_detail as $item) {
                printer_start_page($handle);


                $font = printer_create_font('simsun', 28, 12, PRINTER_FW_MEDIUM, false, false, false, 0);

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

}

/**
*
*/
class ReportItemsPage extends ItemsPage {
    private $item_detail, $print_zh, $pritn_en;
    function __construct($item_detail, $print_zh=true, $print_en=false) {
        $this->item_detail = $item_detail;
        $this->print_zh = $print_zh;
        $this->print_en = $print_en;
    }

    public function printPage($handle) {

        $font = printer_create_font('simsun', 32, 14, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($handle, $font);

        foreach($this->item_detail as $spanItems) {
            printer_start_page($handle);
            $print_y = 20;
            printer_draw_text($handle, date("Y-m-d H:i", $spanItems['start_time']) . " - " .date("Y-m-d H:i", $spanItems['end_time']), 30, $print_y);
            $print_y+=32;

            printer_draw_line($handle, 21, $print_y, 600, $print_y);
            printer_end_page($handle);
            foreach($spanItems['items'] as $item) {
                printer_start_page($handle);
                if ($this->print_zh) {
                    printer_draw_text($handle, iconv("UTF-8", "gb2312", $item['name_xh']) , 32, 0);
                    printer_draw_text($handle, iconv("UTF-8", "gb2312", "总共: " . $item['qty_sum']) , 300, 0);
                } else {
                    printer_draw_text($handle, iconv("UTF-8", "gb2312", $item['name_en']) , 32, 0);
                    printer_draw_text($handle, iconv("UTF-8", "gb2312", "Count" . $item['qty_sum']) , 300, 0);
                }
                printer_end_page($handle);
            }
        }

        printer_delete_font($font);
    }
}


abstract class CountPage {
    abstract function printPage($handle);

    public function format3Columns($handle, $str1, $str2, $num) {
        printer_start_page($handle);
        $font = printer_create_font('simsun', 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($handle, $font);

        printer_draw_text($handle, iconv("UTF-8", "gb2312", $str1), 58, 0);
        printer_draw_text($handle, iconv("UTF-8", "gb2312", $str2), 148, 0);

        printer_draw_text($handle, iconv("UTF-8", "gb2312", number_format($num, 2)), 400, 0);

        printer_delete_font($font);
        printer_end_page($handle);
    }
}

class BillPage extends CountPage {
    private $bill_info;

    public function __construct($bill_info) {
        $this->bill_info = $bill_info;
    }

    public function printPage($handle) {
        // $billArr = array()`
        $bill_info = $this->bill_info;
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

        $this->format3Columns($handle, "Subtotal", "小计:", $subtotal);

        if (floatval($discount_amount) > 0 ) {
            $this->format3Columns($handle, "Discount", "折扣:", $discount_amount);
            $this->format3Columns($handle, "After Discount", "折后价:", $after_discount);
        }
        $this->format3Columns($handle, "Hst"."(" . $tax_rate . "%)", "税:", $tax_amount);

        $this->format3Columns($handle, "Total", "总计:", $total);

    }



}

class ReceiptPage extends CountPage {
    private $bill_info;

    public function __construct($bill_info) {
        $this->bill_info = $bill_info;
    }

    public function printPage($handle) {
         // $billArr = array()`
        $bill_info = $this->bill_info;
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

        $this->format3Columns($handle, "Subtotal", "小计:", $subtotal);

        if (floatval($discount_amount) > 0 ) {
            $this->format3Columns($handle, "Discount", "折扣:", $discount_amount);
            $this->format3Columns($handle, "After Discount", "折后价:", $after_discount);
        }
        $this->format3Columns($handle, "Hst"."(" . $tax_rate . "%)", "税:", $tax_amount);

        $this->format3Columns($handle, "Total", "总计:", $total);

        $this->format3Columns($handle, "Paid", "付款:", $paid);
        $this->format3Columns($handle, "Change", "找零:", $change);
    }
}

class ReportCountDetailPage extends CountPage {
    private $dailyAmount, $print_zh, $print_en;
    public function __construct($dailyAmount, $print_zh=true, $print_en=false) {
        $this->dailyAmount = $dailyAmount;
        $this->print_zh = $print_zh;
        $this->print_en = $print_en;
    }

    public function printPage($handle) {
        $font = printer_create_font('simsun', 32, 14, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($handle, $font);

        foreach ($this->dailyAmount as $spanAmount) {

            printer_start_page($handle);

            $print_y = 30;
            // print time title
            printer_draw_text($handle, date("Y-m-d H:i", $spanAmount['start_time']) . " - " .date("Y-m-d H:i", $spanAmount['end_time']), 30, $print_y);
            $print_y+=32;

            printer_draw_line($handle, 21, $print_y, 600, $print_y);
            $print_y+=32;

            if ($spanAmount['real_total'] > 0) {
                $paid_cash_percent = " " . number_format($spanAmount['paid_cash_total'] * 100 / $spanAmount['real_total'], 2) . '%';
                $paid_card_percent = " " . number_format($spanAmount['paid_card_total'] * 100 / $spanAmount['real_total'], 2) . '%';
            } else {
                $paid_cash_percent = "-";
                $paid_card_percent = "-";
            }
            if ($this->print_zh == true) {
                printer_draw_text($handle, iconv("UTF-8", "gb2312", '税额 : ') . sprintf('%0.2f', $spanAmount['tax']), 32, $print_y); $print_y+=32;

                printer_draw_text($handle, iconv("UTF-8", "gb2312", '总计 : ') . sprintf('%0.2f', $spanAmount['total']) . " ( " . $spanAmount['order_num'] . iconv("UTF-8", "gb2312", " 单 ) "), 32, $print_y); $print_y+=32;
                printer_draw_text($handle, iconv("UTF-8", "gb2312", '实收总计 : ') . sprintf('%0.2f', $spanAmount['real_total']), 32, $print_y); $print_y+=32;
                printer_draw_text($handle, iconv("UTF-8", "gb2312", '实收现金 : ') . sprintf('%0.2f', $spanAmount['paid_cash_total']) . " ( " . $paid_cash_percent . " ) ", 32, $print_y); $print_y+=32;
                printer_draw_text($handle, iconv("UTF-8", "gb2312", '实收卡类 : ') . sprintf('%0.2f', $spanAmount['paid_card_total']) . " ( " . $paid_card_percent . " ) ", 32, $print_y); $print_y+=32;

            } else {
                printer_draw_text($handle, 'TAX Total : ' . sprintf('%0.2f', $spanAmount['tax']), 32, $print_y); $print_y+=32;

                printer_draw_text($handle, 'Total : ' . sprintf('%0.2f', $spanAmount['total']) . " ( " . $spanAmount['order_num'] . " sales ) ", 32, $print_y); $print_y+=32;
                printer_draw_text($handle, 'Paid Total : ' . sprintf('%0.2f', $spanAmount['real_total']), 32, $print_y); $print_y+=32;
                printer_draw_text($handle, 'Paid Cash Total : ' . sprintf('%0.2f', $spanAmount['paid_cash_total']) . " ( " . $paid_cash_percent . " ) ", 32, $print_y); $print_y+=32;
                printer_draw_text($handle, 'Paid Card Total : ' . sprintf('%0.2f', $spanAmount['paid_card_total']) . " ( " . $paid_card_percent . " ) ", 32, $print_y); $print_y+=32;

            }
            $print_y+=30;

            printer_end_page($handle);
        }


        printer_delete_pen($pen);

        printer_delete_font($font);
    }
}

/**
* only print tax and total
*/
class ReportCountPage extends CountPage
{
    private $dailyAmount, $print_zh, $print_en;
    public function __construct($dailyAmount, $print_zh=true, $print_en=false) {
        $this->dailyAmount = $dailyAmount;
        $this->print_zh = $print_zh;
        $this->print_en = $print_en;
    }

    public function printPage($handle) {

        $font = printer_create_font('simsun', 32, 14, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($handle, $font);

        foreach ($this->dailyAmount as $spanAmount) {

            printer_start_page($handle);

            $print_y = 30;
            // print time title
            printer_draw_text($handle, date("Y-m-d H:i", $spanAmount['start_time']) . " - " .date("Y-m-d H:i", $spanAmount['end_time']), 30, $print_y);
            $print_y+=32;

            printer_draw_line($handle, 21, $print_y, 600, $print_y);
            $print_y+=32;

            if ($spanAmount['real_total'] > 0) {
                $paid_cash_percent = " " . number_format($spanAmount['paid_cash_total'] * 100 / $spanAmount['real_total'], 2) . '%';
                $paid_card_percent = " " . number_format($spanAmount['paid_card_total'] * 100 / $spanAmount['real_total'], 2) . '%';
            } else {
                $paid_cash_percent = "-";
                $paid_card_percent = "-";
            }
            if ($this->print_zh == true) {
                printer_draw_text($handle, iconv("UTF-8", "gb2312", '税额 : ') . sprintf('%0.2f', $spanAmount['tax']), 32, $print_y); $print_y+=32;

                printer_draw_text($handle, iconv("UTF-8", "gb2312", '总计 : ') . sprintf('%0.2f', $spanAmount['total']) . " ( " . $spanAmount['order_num'] . iconv("UTF-8", "gb2312", " 单 ) "), 32, $print_y); $print_y+=32;
            } else {
                printer_draw_text($handle, 'TAX Total : ' . sprintf('%0.2f', $spanAmount['tax']), 32, $print_y); $print_y+=32;

                printer_draw_text($handle, 'Total : ' . sprintf('%0.2f', $spanAmount['total']) . " ( " . $spanAmount['order_num'] . " sales ) ", 32, $print_y); $print_y+=32;
            }
            $print_y+=30;

            printer_end_page($handle);
        }


        printer_delete_pen($pen);

        printer_delete_font($font);
    }


}

abstract class FooterPage {
    abstract function printPage($handle);
}

class TimeFooterPage extends FooterPage {
    public function printPage($handle) {
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
class TimeHSTFooterPage extends FooterPage {
    public function printPage($handle) {
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
        if (PrintConfig::$hasHstNumber) {
            printer_draw_text($handle, "Hst Number: " . PrintConfig::$hstNumber, 80, $print_y);
            $print_y += 30;
        }
        printer_draw_text($handle, $date_time, 80, $print_y);

        printer_delete_font($font);
        printer_end_page($handle);
    }
}


/**
*
*/
class BasicDoc
{
    private $pages;
    private $printerName;
    function __construct($printerName, $pages){
        $this->printerName = $printerName;
        $this->pages = $pages;
    }

    function printDoc() {

        $this->handle = printer_open($this->printerName);
        printer_start_doc($this->handle, "Doc");

        foreach($this->pages as $page) {
            $page->printPage($this->handle);
        }

        printer_end_doc($this->handle);
        printer_close($this->handle);

    }
}


// columnsFormater($numOfColumns, column_array($key=>$value))

// bill formater

// kitchen item formater





 ?>
