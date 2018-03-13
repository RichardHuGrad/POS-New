<?php

/**
 * Class OrdersController
 */
class ReportsController extends AppController {

    public $uses = array('Report');
    public $components = array('Session', 'Paginator');

    /**
     * beforeFilter
     * @return null
     */
    public function beforeFilter() {
        parent::beforeFilter();
         $act = $this->request->params['action'];
         if ($act == 'admin_cousines') {
         	$this->set('tab_open', 'rcousines');
         } else if ($act == 'admin_categories') {
         	$this->set('tab_open', 'rcategories');
         } else if ($act == 'admin_customers') {
         	$this->set('tab_open', 'rcustomers');
         } else if ($act == 'admin_users') {
         	$this->set('tab_open', 'rusers');
         } else {
         	$this->set('tab_open', 'reports');
         }
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

    /**
     * admin_index For listing of reports
     * @return mixed
     */
	public function admin_cousines() {
		$this->checkAccess('Report', 'can_view');
		$this->loadModel("OrderItem");
		$this->layout = 'admin';
		$limit = DEFAULT_PAGE_SIZE;

		$dtfrom = @$this->params->query['dtfrom'] ? @$this->params->query['dtfrom'] : date("Y-m-d");
		$dtto = @$this->params->query['dtto'] ? @$this->params->query['dtto'] : date("Y-m-d");
		
		$conditions = array('OrderItem.created >=' => $dtfrom . " 00:00:00", 'OrderItem.created <=' => $dtto . " 23:59:59");
		
		$query = array(
				'conditions' => $conditions,
				'fields' => array(
						'sum(OrderItem.qty) as total', 'OrderItem.item_id', 'OrderItem.name_en', 'OrderItem.name_xh'
				),
				'group'=>'OrderItem.item_id',
				'order'=>'total DESC',
				'recursive'=>FALSE
		);
		$records = $this->OrderItem->find('all', $query);
		//echo "<pre>"; print_r($records); die("XX");

		// $this->set(compact('records', 'datetype', 'curdt', 'curdtstr'));
		$this->set(compact('records', 'dtfrom', 'dtto', 'curdt', 'curdtstr'));
    }

    /**
     * admin_index For listing of reports
     * @return mixed
     */
    public function admin_categories() {
		$this->checkAccess('Report', 'can_view');
		$this->loadModel("OrderItem");
		$this->loadModel("CategoryLocales");
		$this->layout = 'admin';
		$limit = DEFAULT_PAGE_SIZE;

		$dtfrom = @$this->params->query['dtfrom'] ? @$this->params->query['dtfrom'] : date("Y-m-d");
		$dtto = @$this->params->query['dtto'] ? @$this->params->query['dtto'] : date("Y-m-d");
		
		$conditions = array('OrderItem.created >=' => $dtfrom . " 00:00:00", 'OrderItem.created <=' => $dtto . " 23:59:59");
		
		$query = array(
				'conditions' => $conditions,
				'fields' => array(
						'sum(OrderItem.qty) as total', 'OrderItem.category_id'
				),
				'group'=>'OrderItem.category_id',
				'order'=>'total DESC',
				'recursive'=>FALSE
		);
		$records = $this->OrderItem->find('all', $query);
		foreach ($records as $key => $rc) {
			$query = array(
				'conditions' => array('CategoryLocales.category_id' => $rc['OrderItem']['category_id']),
				'recursive'=>FALSE
			);
			$langs = $this->CategoryLocales->find('all', $query);
			foreach ($langs as $lang) {
				if ($lang['CategoryLocales']['lang_code'] == 'en') {
					$records[$key]['name_en'] = $lang['CategoryLocales']['name'];
				} else if ($lang['CategoryLocales']['lang_code'] == 'zh') {
					$records[$key]['name_zh'] = $lang['CategoryLocales']['name'];
				}
			}
		}

		$this->set(compact('records', 'dtfrom', 'dtto', 'curdt', 'curdtstr'));
    }

    /**
     * admin_index For listing of reports
     * @return mixed
     */
    public function admin_customers() {
		$this->checkAccess('Report', 'can_view');
		$this->loadModel("Members");
		$this->layout = 'admin';
		$limit = DEFAULT_PAGE_SIZE;

		$dtfrom = @$this->params->query['dtfrom'] ? @$this->params->query['dtfrom'] : date("Y-m-d");
		$dtto = @$this->params->query['dtto'] ? @$this->params->query['dtto'] : date("Y-m-d");
		
		$query = "SELECT 
				m.*,
				COUNT(IF( t.amount > 0, t.amount, NULL)) AS charged_cnt, 
				SUM(IF( t.amount > 0, t.amount, 0)) AS charged_amt,  
				COUNT(IF( t.amount < 0, t.amount, NULL)) AS paid_cnt, 
				SUM(IF( t.amount < 0, t.amount, 0)) AS paid_amt  
				FROM member_trans t
				RIGHT JOIN members m ON (t.member_id=m.id)
				WHERE t.tm>=" .  $this->Members->getDataSource()->value($dtfrom, 'string') .
				" AND t.tm<=" .  $this->Members->getDataSource()->value($dtto, 'string') .
				" GROUP BY t.member_id";
		$records = $this->Members->query($query);
		// echo "<pre>"; print_r($records); die("XX");

		$this->set(compact('records', 'dtfrom', 'dtto', 'curdt', 'curdtstr'));
    }

    /**
     * admin_index For listing of reports
     * @return mixed
     */
    public function admin_users() {
		$this->checkAccess('Report', 'can_view');
		$this->loadModel("Orders");
		$this->layout = 'admin';
		$limit = DEFAULT_PAGE_SIZE;

		$dtfrom = @$this->params->query['dtfrom'] ? @$this->params->query['dtfrom'] : date("Y-m-d");
		$dtto = @$this->params->query['dtto'] ? @$this->params->query['dtto'] : date("Y-m-d");
		
		$dtfrom = "2010-01-01";
		$dtto = "2018-04-01";
		$query = "SELECT c.*, COUNT(o.total) AS cnt, SUM(o.total) AS total, SUM(o.card_val + o.membercard_val + o.cash_val) AS paid, SUM(o.tip) AS tip FROM orders o 
				JOIN cashiers c ON (o.counter_id=c.id)
				WHERE o.is_completed='Y' AND o.created>=" .  $this->Orders->getDataSource()->value($dtfrom, 'string') . " AND o.created<=" .  $this->Orders->getDataSource()->value($dtto, 'string') .
				" GROUP BY o.counter_id";
		$records = $this->Orders->query($query);
		// echo "<pre>"; print_r($records); die("XX");

		$this->set(compact('records', 'dtfrom', 'dtto', 'curdt', 'curdtstr'));
    }

}
