<?php 

App::uses('Component', 'Controller');

class PrintComponent extends Component {

	public $components = array('Session');

	public function __construct() {
		$this->Cashier = ClassRegistry::init('Cashier');
		$this->Order = ClassRegistry::init('Order');
		$this->OrderItem = ClassRegistry::init('OrderItem');
		$this->Category = ClassRegistry::init('Category');
	}


	public function printPayReceipt() {

	    $order_no = $this->data['order_no'];
	    $table_no = $this->data['table_no']; 
	    $type = $this->data['type'];
	    $logo_name = $this->data['logo_name'];


	    // get order bill info from database
	    $Order_detail = $this->Order->find('first', array(
	            // 'recursive' => -1,
	            'conditions' => array('Order.order_no' => $order_no)
	        ));
	    // print_r($Order_detail);
	    $printItems = $Order_detail['OrderItem'];
	    $billInfo = $Order_detail['Order'];

	    $order_id = $this->Order->getOrderIdByOrderNo($order_no);
	    $printItems = $this->OrderItem->getMergedItems($order_id);


	    $printerName = $this->Cashier->getServicePrinterName( $this->Session->read('Front.id'));
	    $print = new PrintLib();
	    echo $print->printPayReceiptDoc($order_no, $table_no, $type, $printerName, $printItems, $billInfo, $logo_name,true, false);


	}

    public function printPayBill() {
        $order_no = $this->data['order_no'];
        $table_no = $this->data['table_no']; 
        $type = $this->data['type'];
        $logo_name = $this->data['logo_name'];


        // get order bill info from database
        $Order_detail = $this->Order->find('first', array(
                // 'recursive' => -1,
                'conditions' => array('Order.order_no' => $order_no)
            ));
        // print_r($Order_detail);
        $printItems = $Order_detail['OrderItem'];
        $billInfo = $Order_detail['Order'];

        $order_id = $this->Order->getOrderIdByOrderNo($order_no);
        $printItems = $this->OrderItem->getMergedItems($order_id);
        // print_r($printItems);

        
        // $printItems = array();
        $printerName = $this->Cashier->getServicePrinterName( $this->Session->read('Front.id'));
        $print = new PrintLib();
        echo $print->printPayBillDoc($order_no, $table_no, $type, $printerName, $printItems, $billInfo, $logo_name,true, false); 
    }


    public function printTokitchen() {
        // according to order_id
        // find all items in order, print all items which is not print
        if (!isset($this->data['order_no']) || !isset($this->data['type']) || !isset( $this->data['table'])) {
            return;
        }

        $order_no = $this->data['order_no'];
        $type = $this->data['type'];
        $table = $this->data['table'];

        $order_id = $this->Order->getOrderIdByOrderNo($order_no);


        // get all un printed items
        $orderItemsDetail = $this->OrderItem->find('all', array(
                'recursive' => -1,
                'fields' => array(
                    'OrderItem.id',
                    'OrderItem.name_en',
                    'OrderItem.name_xh',
                    'OrderItem.category_id',
                    'OrderItem.qty',
                    'OrderItem.selected_extras',
                    'OrderItem.is_takeout',
                    'OrderItem.is_print',
                    'OrderItem.special_instruction'
                    ),
                'conditions' => array(
                    'OrderItem.order_id' => $order_id, 
                    'OrderItem.is_print' => 'N'
                    ),
            ));

        // print_r ($orderItemsDetail);
        


        // seperate items by printer
        $printItems = array();
        foreach ($orderItemsDetail as $itemDetail) {
            $category_id = $itemDetail['OrderItem']['category_id'];
            $printer = $this->Category->getPrinterById($category_id);

            $selected_extras_list = json_decode($itemDetail['OrderItem']['selected_extras'], true);
            $selected_extras_arr = array();
                if (!empty($selected_extras_list)) {
                    foreach ($selected_extras_list as $selected_extra) {
                        array_push($selected_extras_arr, $selected_extra['name']);
                    }
                }
                
            $itemDetail['OrderItem']['selected_extras'] = join(',', $selected_extras_arr);


            if (!isset($printItems[$printer])) {
                $printItems[$printer] = array();
            }

            array_push($printItems[$printer], $itemDetail['OrderItem']);
        }

        // print_r($printItems);

        if (!empty($printItems['K'])) {

            $printerName = $this->Cashier->getKitchenPrinterName( $this->Session->read('Front.id'));
            $print = new PrintLib();
            $print->printKitchenItemDoc($order_no, $table, $type, $printerName, $printItems['K'],true, false);
        }

        if (!empty($printItems['C'])) {
            $printerName = $this->Cashier->getServicePrinterName( $this->Session->read('Front.id'));
            $print = new PrintLib();
            $print->printKitchenItemDoc($order_no, $table, $type, $printerName, $printItems['C'], true, false);
        }


        

        // change all items is_print to 'Y'
        foreach ($orderItemsDetail as $itemDetail) {
            $itemDetail['OrderItem']['is_print'] = 'Y';
            $this->OrderItem->save($itemDetail, false);
        }
    }


    public function printMergeBill() {

        $order_ids = $this->data['order_ids'];
        $type = $this->data['type'];
        $logo_name = $this->data['logo_name'];

        $mergeData = $this->Order->getMergeOrderInfo($order_ids);

        $printerName = $this->Cashier->getServicePrinterName( $this->Session->read('Front.id'));
        $print = new PrintLib();
        echo $print->printMergeBillDoc($mergeData['order_nos'], $mergeData['table_nos'], $type, $printerName, $mergeData['print_items'], $mergeData, $logo_name,true, false);

        // print_r($mergeData);
       
    }


    public function printMergeReceipt() {
        $order_ids = $this->data['order_ids'];
        $type = $this->data['type'];
        $logo_name = $this->data['logo_name'];

        $mergeData = $this->Order->getMergeOrderInfo($order_ids);

        $printerName = $this->Cashier->getServicePrinterName( $this->Session->read('Front.id'));
        $print = new PrintLib();
        echo $print->printMergeReceiptDoc($mergeData['order_nos'], $mergeData['table_nos'], $type, $printerName, $mergeData['print_items'], $mergeData, $logo_name,true, false);

        print_r($mergeData);
       
    }


        //Modified by Jack @2017-01-05
    public function printTodayOrders() {

		date_default_timezone_set("America/Toronto");
		$date_time = date("l M d Y h:i:s A");
		$timeline = strtotime(date("Y-m-d 11:00:00")); 
		$nowtm = time();
		if ($timeline > $nowtm) {
			// before 11 am
			$timeline -= 86400;
		}
		$tm11 = $timeline;
		$timeline += 3600 * 6;
		$tm17 = $timeline;
		$timeline += 3600 * 6;
		$tm23 = $timeline;
		$timeline += 3600 * 5;
		$tm04 = $timeline;

        $dailyAmount = $this->Order->getDailyOrderInfo(array($tm11, $tm17, $tm23, $tm04));
        $dailyAmountTotal = $this->Order->getDailyOrderInfo(array($tm11, $tm04));
        // $dailyItems = $this->OrderItem->getDailyItemCount(array($tm11, $tm04));

        // print_r($dailyItems);


        $printerName = $this->Cashier->getServicePrinterName( $this->Session->read('Front.id'));
        $print = new PrintLib();
        echo $print->printDailyReportDoc($printerName, $dailyAmount, $dailyAmountTotal);
	}

    public function printTodayItems() {

        date_default_timezone_set("America/Toronto");
        $date_time = date("l M d Y h:i:s A");
        $timeline = strtotime(date("Y-m-d 11:00:00")); 
        $nowtm = time();
        if ($timeline > $nowtm) {
            // before 11 am
            $timeline -= 86400;
        }
        $tm11 = $timeline;
        $timeline += 3600 * 6;
        $tm17 = $timeline;
        $timeline += 3600 * 6;
        $tm23 = $timeline;
        $timeline += 3600 * 5;
        $tm04 = $timeline;

        // $dailyAmount = $this->Order->getDailyOrderInfo(array($tm11, $tm17, $tm23, $tm04));
        $dailyItems = $this->OrderItem->getDailyItemCount(array($tm11, $tm04));

        // print_r($dailyItems);


        $printerName = $this->Cashier->getServicePrinterName( $this->Session->read('Front.id'));
        $print = new PrintLib();
        echo $print->printDailyItemsDoc($printerName, $dailyItems);
    }

}

 ?>