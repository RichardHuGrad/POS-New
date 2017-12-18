<?php
App::uses('PrintConfig', 'Lib');
class PrintController extends AppController {
    public $fontStr1 = "simsun";
    public $handle;
    public $fontH = 28; // font height
    public $fontW = 10; // font width
    public $open_drawer = false;
    public $drawer_code = '';
    
    public $itemLineLen = 180;
    // public $charNo = $this->itemLineLen / $this->fontW;
    public $charNo = 20;
    public $lineStartPos = 10;

/*
    public function printBill() {
        $this->layout = false;
        $this->autoRender = NULL;
        $items = json_decode($this->data['items']);
        echo json_encode($items[0]);
    }
*/
    public function printReceipt() {
        $this->layout = false;
        $this->autoRender = NULL;

        $items = json_decode($this->data['items']);
        echo json_encode($items[0]);
    }


    public function switchZh() {
        $fontZh = printer_create_font($this->fontStr1, $this->fontH, $this->fontW, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($this->handle, $fontZh);
    }

    public function switchEn() {
        $font = printer_create_font("Arial", 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($this->handle, $font);
    }


    public function printZh($str, $x, $y,$font_bold=false) {
    	
        if($font_bold == true){
          //$font = printer_create_font($this->fontStr1, $this->fontH, $this->fontW, 1500, false, false, false, 0);
          $font =printer_create_font("simsun", 32, 14, 1200, false, false, false, 0);
        }else{
          $font = printer_create_font($this->fontStr1, $this->fontH, $this->fontW, PRINTER_FW_MEDIUM, false, false, false, 0);
        }
        printer_select_font($this->handle, $font);
        printer_draw_text($this->handle, iconv("UTF-8", "gb2312", $str), $x, $y);
        printer_delete_font($font);
    }

    public function printBigZh ($str, $x, $y) {
        $font = printer_create_font($this->fontStr1, 32, 14, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($this->handle, $font);
        printer_draw_text($this->handle, iconv("UTF-8", "gb2312", $str), $x, $y);
        printer_delete_font($font);
    }

    // each chinese character take two byte
    // 注意$y是引用型参数
    public function printItemZh($str, $x, &$y) {
        $font = printer_create_font($this->fontStr1, $this->fontH, $this->fontW, PRINTER_FW_MEDIUM, false, false, false, 0);
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

	// 注意$y是引用型参数
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
            $start += 20; //每行打印20个字符
            //break;
        }

        printer_delete_font($font);
    }

    public function printEn($str, $x, $y,$font_bold=false) {

        if($font_bold == true){
          $font =printer_create_font("Arial", 32, 14, 1200, false, false, false, 0);
        }else{
          $font = printer_create_font("Arial", 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        }
        
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


    // order number
    public function printOriginalBill($order_no, $table_no, $table_type, $printer_name, $print_zh=true, $is_receipt=true) {
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

        $default_tip_rate   = $order['default_tip_rate'];
        $default_tip_amount = $order['default_tip_amount'];        
        
        $total = $order['total'];
        $logo_name = $this->data['logo_name'];

        $type = (($table_type == 'D') ? '[[堂食]]' : (($table_type == 'T') ? '[[外卖]]' : (($table_type == 'W') ? '[[等候]]' : '')));

        echo json_encode($order);

        date_default_timezone_set("America/Toronto");
        $date_time = date("l M d Y h:i:s A");


        $this->handle = printer_open($printer_name);
        printer_start_doc($this->handle, "my_Receipt");
        printer_start_page($this->handle);
        printer_draw_bmp($this->handle, $logo_name, 100, 20, 263, 100);

        $adminModel = ClassRegistry::init('Admin');
        $query = array(
            'conditions' => array('is_super_admin' => 'N'),
            'recursive' => -1, 
            'fields' => array('mobile_no','address','city','province','zipcode','print_offset'),
        );
        $data = $adminModel->find('first',$query);
        $addressLine1 = $data['Admin']['address'];
        $addressLine2 = $data['Admin']['city'] . ' '. $data['Admin']['province'];
        $offset = explode(',' , $data['Admin']['print_offset']);

        $this->printBigEn($addressLine1, $offset[0], 130);
        $this->printBigEn($addressLine2, $offset[1], 168);
        $this->printBigEn($data['Admin']['mobile_no'] , $offset[2], 206);

        $print_y = 244;

        if ($print_zh == true) {
        	if($default_tip_rate>0){
            	$this->printZh("此单已包含小费，感谢您的光临", 100, $print_y);
        	}else{
            	$this->printZh("此单不包含小费，感谢您的光临", 100, $print_y);
        	}
            $print_y+=40;
            $this->printZh("谢谢", 210, $print_y);
            $print_y+=40;
        }


        $this->printBigEn("Order#: " . $order_no , 32, $print_y);
        $print_y+=40;
        $this->printBigZh("Table:". $type . iconv("UTF-8", "gb2312", "# " . $table_no) , 32, $print_y);
        $print_y+=38;

        $pen = printer_create_pen(PRINTER_PEN_SOLID, 2, "000000");
        printer_select_pen($this->handle, $pen);
        printer_draw_line($this->handle, 21, $print_y, 600, $print_y);

        // print order item
        $print_y += 20;
        for ($i = 0; $i < count($items); ++$i) {
            $this->printEn("1", 10, $print_y);
            $this->printEn(number_format($items[$i]['price'], 2), 360, $print_y);
            $this->printItemEn($items[$i]['name_en'], 50, $print_y);

            if ($print_zh == true) {
                $this->printItemZh($items[$i]['name_zh'], 50, $print_y);
            };

			//如果extra的价格不为0,则打印
            if (isset($items[$i]['extras_amount']) && $items[$i]['extras_amount']>0) {
                $this->printEn(number_format($items[$i]['extras_amount'], 2), 360, $print_y);
                $this->printItemZh($items[$i]['selected_extras_name'], 32, $print_y);
            }
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
                $this->printZh("AfterDiscount", 58, $print_y);
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
	
		//缺省收小费，则打印
		if($default_tip_rate >0){
	        if ($print_zh == true) {
	            $this->printZh("Tip", 58, $print_y);
	            $this->printZh("(" . $default_tip_rate . "%)", 100, $print_y);
	            $this->printZh("小费：", 168, $print_y);
	        } else {
	            $this->printEn("Tip", 58, $print_y);
	            $this->printEn("(" . $tax_rate . "%) :", 100, $print_y);
	        }
	        $this->printEn(number_format($default_tip_amount, 2), 360, $print_y);
	        $print_y += 30;
		}

        if ($print_zh == true) {
            $this->printZh("Total", 58, $print_y,true);
            $this->printZh("总计：", 148, $print_y,true);
        } else {
            $this->printEn( "Total :", 58, $print_y,true);
        };
        $this->printEn(number_format($total, 2), 360, $print_y,true);
        $print_y += 30;

        if (PrintConfig::$hasHstNumber) {
            $this->printEn("Hst Number: " . PrintConfig::$hstNumber, 80, $print_y);
            $print_y += 30;
        }
        $this->printEn($date_time, 60, $print_y);

        printer_end_page($this->handle);
        printer_end_doc($this->handle);
        printer_close($this->handle);

        echo true;
        exit;
    }

	
	//最后一个参数为1,则打印付款账单;false则打印receipt
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

        $default_tip_rate   = $suborder['default_tip_rate'];
        $default_tip_amount = $suborder['default_tip_amount'];

        $total = $suborder['total'];
        $received_card = $suborder['received_card'];
        $received_cash = $suborder['received_cash'];
        $received_total = $suborder['received_total'];
        $paid = $received_total;
        $tip_amount = $suborder['tip_amount'];
        $tip_card = $suborder['tip_card'];
        $tip_cash = $suborder['tip_cash'];
        $change = $suborder['change'];

        if ($received_card>0 and $received_cash>0) {
            $paid_by = "MIXED";
        } elseif ($received_card>0) {
            $paid_by = "CARD";
        } else {
            $paid_by = "CASH";
        };

        $logo_name = $this->data['logo_name'];

        $type = (($table_type == 'D') ? '[[堂食]]' : (($table_type == 'T') ? '[[外卖]]' : (($table_type == 'W') ? '[[等候]]' : '')));

        date_default_timezone_set("America/Toronto");
        $date_time = date("l M d Y h:i:s A");

        $this->handle = printer_open($printer_name);
        printer_start_doc($this->handle, "my_Receipt");
        printer_start_page($this->handle);

        printer_draw_bmp($this->handle, $logo_name, 100, 20, 263, 100);

        $adminModel = ClassRegistry::init('Admin');
        $query = array(
            'conditions' => array('is_super_admin' => 'N'),
            'recursive' => -1, 
            'fields' => array('mobile_no','address','city','province','zipcode','print_offset'),
        );
        $data = $adminModel->find('first',$query);
        $addressLine1 = $data['Admin']['address'];
        $addressLine2 = $data['Admin']['city'] . ' '. $data['Admin']['province'];
        $offset = explode(',' , $data['Admin']['print_offset']);

        $this->printBigEn($addressLine1, $offset[0], 130);
        $this->printBigEn($addressLine2, $offset[1], 168);
        $this->printBigEn($data['Admin']['mobile_no'] , $offset[2], 206);
/*        
        $this->printBigEn(PrintConfig::$addressLine1['content'], PrintConfig::$addressLine1['offset_x'], 130);
        $this->printBigEn(PrintConfig::$addressLine2['content'], PrintConfig::$addressLine2['offset_x'], 168);
        $this->printBigEn(PrintConfig::$phone['content'], PrintConfig::$phone['offset_x'], 206);
*/

        $print_y = 244;

        if ($print_zh == true) {
            if($default_tip_rate>0){
                $this->printZh("此单已包含小费，感谢您的光临", 100, $print_y);
            }else{
                $this->printZh("此单不包含小费，感谢您的光临", 100, $print_y);
            }
            $print_y+=40;
            $this->printZh("谢谢", 210, $print_y);
            $print_y+=40;
        }


        $this->printBigEn("Order#: " . $order_no . '-' . $suborder_no , 32, $print_y);
        $print_y+=40;
        $this->printBigZh("Table:". $type . iconv("UTF-8", "gb2312", "# " . $table_no) , 32, $print_y);

        $this->loadModel('Order');
        //$Order = ClassRegistry::init('Order');
        $phone = $this->Order->getPhoneByOrderNo($order_no);
        if($phone != ''){
            $print_y += 42;
          printer_draw_text($this->handle, "Phone:" . $phone, 32, $print_y);
        }
        
        
        $print_y+=38;
        $pen = printer_create_pen(PRINTER_PEN_SOLID, 2, "000000");
        printer_select_pen($this->handle, $pen);
        printer_draw_line($this->handle, 21, $print_y, 600, $print_y);

        // print order item
        $print_y += 20;
        for ($i = 0; $i < count($items); ++$i) {
            $this->printEn("1", 10, $print_y);
            $this->printEn(number_format($items[$i]['price'], 2), 360, $print_y);
            $this->printItemEn($items[$i]['name_en'], 50, $print_y);

            // $print_y += 30;
            if ($print_zh == true) {
                // $this->printZh($items[$i]['name_zh'], 10, $print_y);
                $this->printItemZh($items[$i]['name_zh'], 50, $print_y);
            };

            // $print_y += 30;

			//如果extra的价格不为0,则打印name(注意:item price已经包含了extra price)
            if (isset($items[$i]['extras_amount']) && $items[$i]['extras_amount']>0) {
                $this->printItemZh($items[$i]['selected_extras_name'], 32, $print_y);
            }

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
                $this->printEn("AfterDiscount :", 58, $print_y);
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

        //缺省收小费，则打印
        if($default_tip_rate >0){
            if ($print_zh == true) {
                $this->printZh("Tip", 58, $print_y);
                $this->printZh("(" . $default_tip_rate . "%)", 100, $print_y);
                $this->printZh("小费：", 168, $print_y);
            } else {
                $this->printEn("Tip", 58, $print_y);
                $this->printEn("(" . $tax_rate . "%) :", 100, $print_y);
            }
            $this->printEn(number_format($default_tip_amount, 2), 360, $print_y);
            $print_y += 30;
        }

        if ($print_zh == true) {
            $this->printZh("Total", 58, $print_y,true);
            $this->printZh("总计：", 148, $print_y,true);
        } else {
            $this->printEn( "Total :", 58, $print_y,true);
        };
        $this->printEn(number_format($total, 2), 360, $print_y,true);
        $print_y += 30;


        if ($is_receipt == true) {
            if ($print_zh == true) {
                $this->printZh("Paid", 58, $print_y);
                $this->printZh("付款({$paid_by})：", 148, $print_y);
            } else {
                $this->printEn("Paid({$paid_by}):", 58, $print_y);
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

        if (PrintConfig::$hasHstNumber) {
            $this->printEn("Hst Number: " . PrintConfig::$hstNumber, 80, $print_y);
            $print_y += 30;
        }
        $this->printEn($date_time, 60, $print_y);

        printer_end_page($this->handle);
        printer_end_doc($this->handle);

        if($received_cash > 0){
            $this->drawer_code = chr(27).chr(112).chr(48).chr(55).chr(121);
            printer_set_option($this->handle, PRINTER_MODE, "RAW");
            printer_write($this->handle, $this->drawer_code);
        }

        printer_close($this->handle);

        echo true;
        exit;

    }
}


?>
