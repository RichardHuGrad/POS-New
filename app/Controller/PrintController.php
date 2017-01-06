<?php 

class PrintController extends AppController {

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

	// order number
	public function printOriginalBill($order_no, $table_no, $printer_name, $print_zh=true) {
		$this->layout = false;
        $this->autoRender = NULL;	

        $order = $this->data['order'];
        $items = $order['items'];
        
        $subtotal = $order['subtotal'];
        $discount_type = $order['discount_type'];
        $discount_value = $order['discount_value'];
        $discount_amount = $order['discount_amount'];
        $tax_rate = $order['tax_rate'];
        $tax_amount = $order['tax_amount'];
        $total = $order['total'];

        echo json_encode($order);

            date_default_timezone_set("America/Toronto");
        $date_time = date("l M d Y h:i:s A");

        $handle = printer_open($printer_name);
        printer_start_doc($handle, "my_Receipt");
        printer_start_page($handle);
        $font = printer_create_font("Arial", 32, 14, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($handle, $font);
        printer_draw_text($handle, "2038 Yonge St.", 156, 130);
        printer_draw_text($handle, "Toronto ON M4S 1Z9", 110, 168);
        printer_draw_text($handle, "416-792-4476", 156, 206);

        $print_y = 244;

        if ($print_zh == true) {
        	$font = printer_create_font(iconv("UTF-8", "gb2312", "宋体"), 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        	printer_select_font($handle, $font);
            printer_draw_text($handle, iconv("UTF-8", "gb2312", "此单不包含小费，感谢您的光临"), 100, $print_y);
            $print_y+=40;
            printer_draw_text($handle, iconv("UTF-8", "gb2312", "谢谢"), 210, $print_y);
            $print_y+=40;
        }

        $font = printer_create_font("Arial", 32, 14, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($handle, $font);

        printer_draw_text($handle, "Order Number: #" . $order_no , 32, $print_y);
        $print_y+=40;
        printer_draw_text($handle, "Table:" . iconv("UTF-8", "gb2312", $table_no), 32, $print_y);
        $print_y+=38;

        $pen = printer_create_pen(PRINTER_PEN_SOLID, 2, "000000");
        printer_select_pen($handle, $pen);
        printer_draw_line($handle, 21, $print_y, 600, $print_y);

        // print order item
        $print_y += 20;
        for ($i = 0; $i < count($items); ++$i) {
        	$font = printer_create_font("Arial", 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
            printer_select_font($handle, $font);

            printer_draw_text($handle, $items[$i]['name_en'], 32, $print_y);
            printer_draw_text($handle, number_format($items[$i]['price'], 2), 360, $print_y);
            $print_y += 20;
            if ($print_zh == true) {
                $font = printer_create_font(iconv("UTF-8", "gb2312", "宋体"), 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
                printer_select_font($handle, $font);

                printer_draw_text($handle, iconv("UTF-8", "gb2312", $items[$i]['name_zh']), 136, $print_y);
                
            };
            if ($items[$i]['selected_extras_name']) {
            	$print_y += 20;
            	$font = printer_create_font(iconv("UTF-8", "gb2312", "宋体"), 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
                printer_select_font($handle, $font);

                for ($j = 0; $j < count($items[$i]['selected_extras_name']); ++$j) {
                	printer_draw_text($handle, iconv("UTF-8", "gb2312", $items[$i]['selected_extras_name'][$j]), 32, $print_y);
                	$print_y += 20;
                }

	            printer_draw_text($handle, number_format($items[$i]['extra_amount'], 2), 360, $print_y);
            }

            $print_y += 40;
        }

        $print_y += 10;
        $pen = printer_create_pen(PRINTER_PEN_SOLID, 2, "000000");
        printer_select_pen($handle, $pen);
        printer_draw_line($handle, 21, $print_y, 600, $print_y);

        $print_y += 10;
        $font = printer_create_font("Arial", 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($handle, $font);
        if ($print_zh == true) {
            printer_draw_text($handle, iconv("UTF-8", "gb2312", "Subtotal :"), 58, $print_y);
        } 

        
		printer_draw_text($handle, $date_time, 80, $print_y);

        printer_delete_font($font);

        printer_end_page($handle);
        printer_end_doc($handle);
        printer_close($handle);

        echo true;
        exit;

        
    }
}

 ?>