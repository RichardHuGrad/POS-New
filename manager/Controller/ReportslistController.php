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
        $order = 'order_no ASC';
        $conditions = array();
        
        if (!empty($this->request->data)) {

            if (isset($this->request->data['Extracategory']) && !empty($this->request->data['Extracategory'])) {
                $search_data = $this->request->data['Extracategory'];
                $this->Session->write('Extracategory_search', $search_data);
            }

            if (isset($this->request->data['PageSize']['records_per_page']) && !empty($this->request->data['PageSize']['records_per_page'])) {
                $this->Session->write('page_size', $this->request->data['PageSize']['records_per_page']);
            }
        }
        
        if ($this->Session->check('page_size')) {
            $limit = $this->Session->read('page_size');
        }

        $conditions = array('Order.is_completed' => 'Y');
        $is_super_admin = $this->Session->read('Admin.is_super_admin');
        if ('Y' <> $is_super_admin) {
            $conditions['Order.is_hide'] = 'N';
            $conditions['Order.cashier_id'] = $this->Session->read('Admin.id');
        }

        $year = @$this->params->query['year'] ? @$this->params->query['year'] : date("Y");
        $month = @$this->params->query['year'] ? @$this->params->query['date'] : date("m");
        $date = @$this->params->query['date'] ? @$this->params->query['date'] : date("Y-m-d");
        $cashier = @$this->params->query['cashier'];

        //Today's List.
        $conditions['Order.created like '] = "%$date%";
        
        if ($cashier)
            $conditions['Order.counter_id'] = $cashier;

        $query = array(
            'conditions' => $conditions,
        );

        if ('all' == $limit) {
            $records_today = $this->Order->find('all', $query);
        } else {
            $query['limit'] = $limit;
            $this->paginate = $query;
            $records_today = $this->paginate('Order');
        };
        //End Today.
        
        //Current month's List.
        $conditions['Order.created like '] = "%$month%";
        
        if ($cashier)
            $conditions['Order.counter_id'] = $cashier;

        $query = array(
            'conditions' => $conditions,
        );

        if ('all' == $limit) {
            $records_month = $this->Order->find('all', $query);
        } else {
            $query['limit'] = $limit;
            $this->paginate = $query;
            $records_month = $this->paginate('Order');
        };
        //End Current month.
        
        //Current Year's List.
        $conditions['Order.created like '] = "%$year%";
        
        if ($cashier)
            $conditions['Order.counter_id'] = $cashier;

        $query = array(
            'conditions' => $conditions,
        );

        if ('all' == $limit) {
            $records_year = $this->Order->find('all', $query);
        } else {
            $query['limit'] = $limit;
            $this->paginate = $query;
            $records_year = $this->paginate('Order');
        };
        //End Current Year.
        
        // get all cashiers list        
        $this->loadModel('Cashier');
        $conditions = [];
        if ($is_super_admin <> 'Y')
            $conditions = array('restaurant_id' => $this->Session->read('Admin.id'));
//        xdebug_break();
        $cashiers = $this->Cashier->find('list', array('fields' => array('Cashier.id', 'Cashier.firstname'), 'conditions' => $conditions, 'order' => array('Cashier.firstname' => 'ASC')));
        $this->set(compact('records_today','records_month','records_year', 'limit', 'is_super_admin', 'cashier', 'cashiers'));
    }

}
