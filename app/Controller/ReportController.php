<?php
App::uses('PrintLib', 'Lib');
class ReportController extends AppController {

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('index', 'forgot_password');
        $this->layout = "default";
    }

    public function index() {

    }


    public function getAmountInfo() {
        $this->layout = false;
        $this->autoRender = false;

        $this->loadModel('Order');
        // expect time arrays
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

        return json_encode($dailyAmount);
    }

    public function getItemsInfo() {
        $this->layout = false;
        $this->autoRender = false;

        $this->loadModel('OrderItem');

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

        return json_encode($dailyItems);
    }

    //Modified by Jack @2017-01-05
    public function printTodayOrders() {
        $this->layout = false;
        $this->autoRender = false;

        $this->loadModel('Cashier');

        $restaurant_id = $this->Cashier->getRestaurantId($this->Session->read('Front.id'));

        $this->Print->printTodayOrders(array('restaurant_id'=> $restaurant_id, 'order_id'=>$order_id));

	}

    public function printTodayItems() {
        $this->layout = false;
        $this->autoRender = false;

        $this->loadModel('Cashier');

        $restaurant_id = $this->Cashier->getRestaurantId($this->Session->read('Front.id'));

        $this->Print->printTodayItems(array('restaurant_id'=> $restaurant_id, 'order_id'=>$order_id));
    }


}
