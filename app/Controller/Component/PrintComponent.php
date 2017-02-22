<?php

App::uses('Component', 'Controller');
App::uses('PrintLib', 'Lib');
App::uses('TimeComponent', 'Component');

class PrintComponent extends Component {
    // public $components = array('Session', 'Time');

	public $status = 'success';

	public function __construct() {
        $this->Admin = ClassRegistry::init('Admin');
  		$this->Order = ClassRegistry::init('Order');
        $this->OrderItem = ClassRegistry::init('OrderItem');
        $this->Category = ClassRegistry::init('Category');
	}

    /**
     * printPayReceipt
     *
     * Parameters:
     *      $args['restaurant_id']
     *      $args['order_id']
     */
	public function printPayReceipt($args) {

        if (empty($args['restaurant_id'])) {
            throw new Exception('Missing argument: restaurant_id');
        }
        if (empty($args['order_id'])) {
            throw new Exception('Missing argument: order_id');
        }

        $order_id = $args['order_id'];

        $orderDetail = $this->Order->find('first', array(
                // 'recursive' => -1,
                'conditions' => array(
                        'Order.id' => $order_id
                    )
            ));

        $type = $orderDetail['Order']['order_type'];
        $table_no = $orderDetail['Order']['table_no'];
        $order_no = $orderDetail['Order']['order_no'];
	    $logoPath = $this->Admin->getLogoPathByid($args['restaurant_id']);

	    $printItems = $orderDetail['OrderItem'];
	    $billInfo = $orderDetail['Order'];

	    $printItems = $this->OrderItem->getMergedItems($order_id);


	    $printerName = $this->Admin->getServicePrinterName($args['restaurant_id']);
	    $print = new PrintLib();
	    echo $print->printPayReceiptDoc($order_no, $table_no, $type, $printerName, $printItems, $billInfo, $logoPath,true, false);


	}

    /**
     * printPayBill
     *
     * Parameters:
     *      $args['restaurant_id']
     *      $args['order_id']
     */
    public function printPayBill($args) {
        if (empty($args['restaurant_id'])) {
            throw new Exception('Missing argument: restaurant_id');
        }
        if (empty($args['order_id'])) {
            throw new Exception('Missing argument: order_id');
        }

        $order_id = $args['order_id'];

        $orderDetail = $this->Order->find('first', array(
                // 'recursive' => -1,
                'conditions' => array(
                        'Order.id' => $order_id
                    )
            ));

        $type = $orderDetail['Order']['order_type'];
        $table_no = $orderDetail['Order']['table_no'];
        $order_no = $orderDetail['Order']['order_no'];
        $logoPath = $this->Admin->getLogoPathByid($args['restaurant_id']);

        $printItems = $orderDetail['OrderItem'];
        $billInfo = $orderDetail['Order'];

        $printItems = $this->OrderItem->getMergedItems($order_id);


        $printerName = $this->Admin->getServicePrinterName($args['restaurant_id']);
        $print = new PrintLib();
        echo $print->printPayBillDoc($order_no, $table_no, $type, $printerName, $printItems, $billInfo, $logoPath,true, false);
    }

    /**
     * printTokitchen
     *
     * Parameters:
     *      $args['restaurant_id']
     *      $args['order_id']
     */
    public function printTokitchen($args) {

        if (empty($args['restaurant_id'])) {
            throw new Exception('Missing argument: restaurant_id');
        }

        if (empty($args['order_id'])) {
            throw new Exception('Missing argument: order_id');
        }
        // according to order_id
        // find all items in order, print all items which is not print

        $order_id = $args['order_id'];

        $orderDetail = $this->Order->find('first', array(
                'recursive' => -1,
                'fields' => array(
                        'Order.order_type',
                        'Order.table_no',
                        'Order.order_no'
                    ),
                'conditions' => array(
                        'Order.id' => $order_id
                    )
            ));

        $type = $orderDetail['Order']['order_type'];
        $table = $orderDetail['Order']['table_no'];
        $order_no = $orderDetail['Order']['order_no'];


        // get all un printed items
        $printItems = $this->OrderItem->getUnprintItemsByOrderId($order_id);

        // print_r($printItems);

        if (!empty($printItems['K'])) {

            $printerName = $this->Admin->getKitchenPrinterName($args['restaurant_id']);
            $print = new PrintLib();
            $print->printKitchenItemDoc($order_no, $table, $type, $printerName, $printItems['K'],true, false);
        }

        if (!empty($printItems['C'])) {
            $printerName = $this->Admin->getServicePrinterName($args['restaurant_id']);
            $print = new PrintLib();
            $print->printKitchenItemDoc($order_no, $table, $type, $printerName, $printItems['C'], true, false);
        }

        $this->OrderItem->setAllItemsToPrinted($order_id);
    }

    /**
     * printKitchenUrgeItem
     *
     * Parameters:
     *      $args['restaurant_id']
     *      $args['order_id']
     *      $args['item_id_list']
     */
    public function printKitchenUrgeItem($args) {

        if (empty($args['restaurant_id'])) {
            throw new Exception('Missing argument: restaurant_id');
        }
        if (empty($args['order_id'])) {
            throw new Exception('Missing argument: order_id');
        }
        if (empty($args['item_id_list'])) {
            throw new Exception('Missing argument: item_id_list');
        }

        // get all params
        $restaurant_id = $args['restaurant_id'];
        $item_id_list = $args['item_id_list'];
        $order_id = $arg['order_id'];

        $orderDetail = $this->Order->find('first', array(
                'recursive' => -1,
                'fields' => array(
                        'Order.order_type',
                        'Order.table_no',
                        'Order.order_no'
                    ),
                'conditions' => array(
                        'Order.id' => $order_id
                    )
            ));

        $type = $orderDetail['Order']['order_type'];
        $table = $orderDetail['Order']['table_no'];
        $order_no = $orderDetail['Order']['order_no'];

        $cancel_items = array('K'=> array(), 'C'=>array());


        foreach ($item_id_list as $item_id) {
            // if the item is printed
            // send to kitchen print
            $item_detail = $this->OrderItem->query("SELECT order_items.*,categories.printer FROM  `order_items` JOIN `categories` ON order_items.category_id=categories.id WHERE order_items.id = " . $item_id . " LIMIT 1");
            // print_r($item_detail);

            $is_print = $item_detail[0]['order_items']['is_print'];
            $printer = $item_detail[0]['categories']['printer'];
            if ($is_print == 'Y') {

                $selected_extras_list = json_decode($item_detail[0]['order_items']['selected_extras'], true);
                $selected_extras_arr = array();
                if (!empty($selected_extras_list)) {
                    foreach ($selected_extras_list as $selected_extra) {
                        array_push($selected_extras_arr, $selected_extra['name']);
                    }
                }

                $item_detail[0]['order_items']['selected_extras'] = join(',', $selected_extras_arr);
                array_push($cancel_items[$printer], $item_detail[0]['order_items']);

            } // else do nothing

        }

        // echo json_encode($cancel_items);
        // echo empty($cancel_items['K']);
        if (!empty($cancel_items['K'])) {
            $printerName = $this->Admin->getKitchenPrinterName($args['restaurant_id']);
            $print = new PrintLib();
            $print->printUrgeItemDoc($order_no, $table, $type, $printerName, $cancel_items['K'],true, false);
        }
        if (!empty($cancel_items['C'])) {
            $printerName = $this->Admin->getServicePrinterName($args['restaurant_id']);
            $print = new PrintLib();
            $print->printUrgeItemDoc($order_no, $table, $type, $printerName, $cancel_items['C'],true, false);
        }
    }


    /**
     * printKitchenRemoveItem
     *
     * Parameters:
     *      $args['restaurant_id']
     *      $args['order_id']
     *      $args['item_id_list']
     */
    public function printKitchenRemoveItem($args) {

        if (empty($args['restaurant_id'])) {
            throw new Exception('Missing argument: restaurant_id');
        }
        if (empty($args['order_id'])) {
            throw new Exception('Missing argument: order_id');
        }
        if (empty($args['item_id_list'])) {
            throw new Exception('Missing argument: item_id_list');
        }

        // get all params
        $restaurant_id = $args['restaurant_id'];
        $item_id_list = $args['item_id_list'];
        $order_id = $args['order_id'];

        $orderDetail = $this->Order->find('first', array(
                'recursive' => -1,
                'fields' => array(
                        'Order.order_type',
                        'Order.table_no',
                        'Order.order_no'
                    ),
                'conditions' => array(
                        'Order.id' => $order_id
                    )
            ));

        $type = $orderDetail['Order']['order_type'];
        $table = $orderDetail['Order']['table_no'];
        $order_no = $orderDetail['Order']['order_no'];

        $cancel_items = array('K'=> array(), 'C'=>array());


        foreach ($item_id_list as $item_id) {
            // if the item is printed
            // send to kitchen print
            $item_detail = $this->OrderItem->query("SELECT order_items.*,categories.printer FROM  `order_items` JOIN `categories` ON order_items.category_id=categories.id WHERE order_items.id = " . $item_id . " LIMIT 1");
            // print_r($item_detail);

            $is_print = $item_detail[0]['order_items']['is_print'];
            $printer = $item_detail[0]['categories']['printer'];
            if ($is_print == 'Y') {

                $selected_extras_list = json_decode($item_detail[0]['order_items']['selected_extras'], true);
                $selected_extras_arr = array();
                if (!empty($selected_extras_list)) {
                    foreach ($selected_extras_list as $selected_extra) {
                        array_push($selected_extras_arr, $selected_extra['name']);
                    }
                }

                $item_detail[0]['order_items']['selected_extras'] = join(',', $selected_extras_arr);
                array_push($cancel_items[$printer], $item_detail[0]['order_items']);

            } // else do nothing
        }

        // echo json_encode($cancel_items);
        // echo empty($cancel_items['K']);
        if (!empty($cancel_items['K'])) {

            $printerName = $this->Admin->getKitchenPrinterName($args['restaurant_id']);
            $print = new PrintLib();
            echo $print->printCancelledItems($order_no, $table, $type, $printerName, $cancel_items['K'],true, false);
        }
        if (!empty($cancel_items['C'])) {

            $printerName = $this->Admin->getServicePrinterName($args['restaurant_id']);
            $print = new PrintLib();
            $print->printCancelledItems($order_no, $table, $type, $printerName, $cancel_items['C'],true, false);
        }
    }


    /**
     * printMergeBill
     *
     * Parameters:
     *      $args['restaurant_id']
     *      $args['order_id']
     */
    public function printMergeBill($args) {
        if (empty($args['restaurant_id'])) {
            throw new Exception('Missing argument: restaurant_id');
        }
        if (empty($args['order_ids'])) {
            throw new Exception('Missing argument: order_ids');
        }

        $order_ids = $args['order_ids'];

        $orderDetail = $this->Order->find('first', array(
                'recursive' => -1,
                'fields' => array(
                        'Order.order_type',
                        'Order.table_no',
                        'Order.order_no'
                    ),
                'conditions' => array(
                        'Order.id' => $order_ids[0]
                    )
            ));

        $type = $orderDetail['Order']['order_type'];

        $logoPath = $this->Admin->getLogoPathByid($args['restaurant_id']);

        $mergeData = $this->Order->getMergeOrderInfo($order_ids);

        $printerName = $this->Admin->getServicePrinterName($args['restaurant_id']);
        $print = new PrintLib();
        echo $print->printMergeBillDoc($mergeData['order_nos'], $mergeData['table_nos'], $type, $printerName, $mergeData['print_items'], $mergeData, $logoPath,true, false);

        // print_r($mergeData);

    }


    /**
     * printMergeReceipt
     *
     * Parameters:
     *      $args['restaurant_id']
     *      $args['order_id']
     */
    public function printMergeReceipt($args) {
        if (empty($args['restaurant_id'])) {
            throw new Exception('Missing argument: restaurant_id');
        }
        if (empty($args['order_ids'])) {
            throw new Exception('Missing argument: order_ids');
        }

        $order_ids = $args['order_ids'];

        $orderDetail = $this->Order->find('first', array(
                'recursive' => -1,
                'fields' => array(
                        'Order.order_type',
                        'Order.table_no',
                        'Order.order_no'
                    ),
                'conditions' => array(
                        'Order.id' => $order_ids[0]
                    )
            ));

        $type = $orderDetail['Order']['order_type'];

        $logoPath = $this->Admin->getLogoPathByid($args['restaurant_id']);

        $mergeData = $this->Order->getMergeOrderInfo($order_ids);

        $printerName = $this->Admin->getServicePrinterName($args['restaurant_id']);
        $print = new PrintLib();
        echo $print->printMergeReceiptDoc($mergeData['order_nos'], $mergeData['table_nos'], $type, $printerName, $mergeData['print_items'], $mergeData, $logoPath,true, false);

        // print_r($mergeData);

    }


    /**
     * printTodayOrders
     *
     * Parameters:
     *      $args['restaurant_id']
     *      $args['type']
     */
    public function printTotalOrders($args) {
        if (empty($args['restaurant_id'])) {
            throw new Exception('Missing argument: restaurant_id');
        }
        if (empty($args['type'])) {
            throw new Exception('Missing argument: type');
        }

        $timeArray = TimeComponent::getTimelineArray($args['type']);


        if ($args['type'] != "month") {
            $dailyAmount = $this->Order->getDailyOrderInfo($timeArray);
        }
        $dailyAmountTotal = $this->Order->getDailyOrderInfo(array(reset($timeArray), end($timeArray)));
        // $dailyItems = $this->OrderItem->getDailyItemCount(array($tm11, $tm04));

        $printerName = $this->Admin->getServicePrinterName($args['restaurant_id']);
        $print = new PrintLib();
        echo $print->printDailyReportDoc($printerName, $dailyAmount, $dailyAmountTotal);
	}


    /**
     * printTodayItems
     *
     * Parameters:
     *      $args['restaurant_id']
     *      $args['type']
     */
    public function printTotalItems($args) {

        if (empty($args['restaurant_id'])) {
            throw new Exception('Missing argument: restaurant_id');
        }
        if (empty($args['type'])) {
            throw new Exception('Missing argument: type');
        }

        $timeArray = TimeComponent::getTimelineArray($args['type']);

        // $dailyAmount = $this->Order->getDailyOrderInfo(array($tm11, $tm17, $tm23, $tm04));
        $dailyItems = $this->OrderItem->getDailyItemCount(array(reset($timeArray), end($timeArray)));

        // print_r($dailyItems);


        $printerName = $this->Admin->getServicePrinterName($args['restaurant_id']);
        $print = new PrintLib();
        echo $print->printDailyItemsDoc($printerName, $dailyItems);
    }



}

 ?>
