<?php
App::uses('PrintLib', 'Lib');
class ReportController extends AppController {

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('index', 'forgot_password');
        $this->layout = "default";
    }

    public function index() {
    	$this->loadModel('Cashier');
      $admin_passwd = $this->Cashier->query("SELECT admins.password FROM admins WHERE admins.is_super_admin='Y' ");
      
      $this->set(compact('admin_passwd'));
    }


    public function getAmountInfo() {
        $this->layout = false;
        $this->autoRender = false;

        $this->loadModel('Order');
        // expect time arrays
        $type = $this->data['type'];

        $timeArray = $this->Time->getTimelineArray($type);

        if ($type == "month") {
            $dailyAmount = $this->Order->getDailyOrderInfo($timeArray);
            return json_encode($dailyAmount);
        } else {
            $dailyAmount = $this->Order->getDailyOrderInfo($timeArray);
            $dailyAmountTotal = $this->Order->getDailyOrderInfo(array(reset($timeArray), end($timeArray)));

            return json_encode(array_merge($dailyAmount, $dailyAmountTotal));
        }

       
    }

    public function getItemsInfo() {
        $this->layout = false;
        $this->autoRender = false;

        $this->loadModel('OrderItem');
        $type = $this->data['type'];

        $timeArray = $this->Time->getTimelineArray($type);

        // $dailyAmount = $this->Order->getDailyOrderInfo(array($tm11, $tm17, $tm23, $tm04));
        $dailyItems = $this->OrderItem->getDailyItemCount(array(reset($timeArray), end($timeArray)));

        return json_encode($dailyItems);
    }

    //Modified by Jack @2017-01-05
    public function printTodayOrders() {
        $this->layout = false;
        $this->autoRender = false;

        $this->loadModel('Cashier');

        $type = $this->data['type'];


        $restaurant_id = $this->Cashier->getRestaurantId($this->Session->read('Front.id'));

        $this->Print->printTotalOrders(array('restaurant_id'=> $restaurant_id, 'type'=>$type));

	}

    public function printTodayItems() {
        $this->layout = false;
        $this->autoRender = false;

        $this->loadModel('Cashier');

        $type = $this->data['type'];

        $restaurant_id = $this->Cashier->getRestaurantId($this->Session->read('Front.id'));

        $this->Print->printTotalItems(array('restaurant_id'=> $restaurant_id, 'type'=>$type));
    }


}
