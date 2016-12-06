<?php

/**
 * Class OrdersController
 */
class ReportslistController extends AppController {

    public $uses = array('Report');
    public $components = array('Session', 'Paginator');

    /**
     * beforeFilter
     * @return null
     */
    public function beforeFilter() {
        parent::beforeFilter();
         $this->set('tab_open', 'reportslist');
    }

    /**
     * admin_index For listing of reports
     * @return mixed
     */
    public function admin_index() {

        $this->checkAccess('Report', 'can_view');
        $this->loadModel("Order");
        $this->layout = 'admin';
        $limit = DEFAULT_PAGE_SIZE;

        $conditions = array('Order.is_completed'=>'Y');
        $is_super_admin = $this->Session->read('Admin.is_super_admin');
        if('Y' <> $is_super_admin){
            $conditions['Order.is_hide'] = 'N';
            $conditions['Order.cashier_id'] = $this->Session->read('Admin.id');
        }

        $year = @$this->params->query['year']?@$this->params->query['year']:date("Y");
        $date = @$this->params->query['date']?@$this->params->query['date']:date("Y-m-d");
        $cashier = @$this->params->query['cashier'];
        $conditions['Order.created like '] = "%$year%";
        if($cashier)
            $conditions['Order.counter_id'] = $cashier;

        $query = array(
            'conditions' => $conditions,
            'fields' => array(
                'sum(Order.total) as total', 'DATE_FORMAT(Order.created, "%m") as month'
            ),
            'group'=>'DATE_FORMAT(Order.created, "%m")',
            'recursive'=>-1
        );
        $records = $this->Order->find('all', $query);
        $months = array(0,0,0,0,0,0,0,0,0,0,0,0);
        if(!empty($records)) {
            foreach ($records as $key => $value) {
                $months[intval($value[0]['month'])-1] = round($value[0]['total'], 2);
            }
        }
        $months = implode(",", $months);

        // get daily statics
        $conditions_new = array('Order.is_completed'=>'Y');
        $conditions_new['created like'] = "%$date%";
        
        if('Y' <> $is_super_admin){
            $conditions_new['Order.is_hide'] = 'N';
            $conditions_new['Order.cashier_id'] = $this->Session->read('Admin.id');
        }

        if($cashier)
            $conditions_new['Order.counter_id'] = $cashier;
        $query = array(
            'conditions' => $conditions_new,
            'fields' => array(
                'sum(Order.total) as total', 'DATE_FORMAT(Order.created, "%H") as hour'
            ),
            'group'=>'DATE_FORMAT(Order.created, "%H")',
            'recursive'=>-1
        );
        $records = $this->Order->find('all', $query);
        $hour = array(
            "'12:00 am-1:00 am'",
            "'1:00 am-2:00 am'",
            "'2:00 am-3:00am'",
            "'3:00am-4:00am'",
            "'4:00am-5:00am'",
            "'5:00am-6:00am'",
            "'6:00am-7:00am'",
            "'7:00am-8:00am'",
            "'8:00am-9:00am'",
            "'9:00am-10:00am'",
            "'10:00am-11:00am'",
            "'11:00am-12:00pm'",
            "'12:00pm-1:00pm'",
            "'1:00pm-2:00pm'",
            "'2:00pm-3:00pm'",
            "'3:00pm-4:00pm'",
            "'4:00pm-5:00pm'",
            "'5:00pm-6:00pm'",
            "'6:00pm-7:00pm'",
            "'7:00pm-8:00pm'",
            "'8:00pm-9:00pm'",
            "'9:00pm-10:00pm'",
            "'10:00pm-11:00pm'",
            "'11:00pm-11:59pm'");
        $hours = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
        if(!empty($records)) {
            foreach ($records as $key => $value) {
                $hours[intval($value[0]['hour'])] = round($value[0]['total'], 2);
            }
        }
        $hours = implode(",", $hours);
        $hour = implode(",", $hour);

        // get all cashiers list        
        $this->loadModel('Cashier');
        $conditions = [];
        if($is_super_admin <> 'Y')
            $conditions = array('restaurant_id'=> $this->Session->read('Admin.id'));

        $cashiers = $this->Cashier->find('list',
            array('fields' => array('Cashier.id', 'Cashier.firstname'), 'conditions' => $conditions, 'order' => array('Cashier.firstname' => 'ASC')));
        $this->set(compact('records', 'limit', 'order', 'is_super_admin', 'months', 'year', 'date', 'cashier', 'hours', 'hour', 'cashiers'));
    }

}
