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
        $this->set('tab_open', 'reports');
    }

    /**
     * admin_index For listing of reports
     * @return mixed
     */
    public function admin_index($range = 0) {

        $this->checkAccess('Report', 'can_view');
        $this->loadModel("Order");
        $this->layout = 'admin';
        $limit = DEFAULT_PAGE_SIZE;
        $order = 'order_no ASC';
        $conditions = array();

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
        $month = @$this->params->query['year'] ? @$this->params->query['date'] : date("Y-m");
        $date = @$this->params->query['date'] ? @$this->params->query['date'] : date("Y-m-d");
        $cashier = @$this->params->query['cashier'];

        //Modified by Yishou Liao @ Dec 07 2016
        switch ($range) {
            case 0:
                $conditions['Order.created like '] = "%$date%";
                break;
            case 1:
                $conditions['Order.created like '] = "%$month%";
                break;
            case 2:
                $conditions['Order.created like '] = "%$year%";
                break;
        };
        //End @ Dec 07 2016

        if ($cashier)
            $conditions['Order.counter_id'] = $cashier;

        $query = array(
            'conditions' => $conditions,
        );
        
        if ('all' == $limit) {
            $records = $this->Order->find('all', $query);
        } else {
            $query['limit'] = $limit;
            $this->paginate = $query;
            $records = $this->paginate('Order');
        };

        //Modified by Yishou Liao @ Dec 07 2016
        $query = array(
            'conditions' => $conditions,
            'fields' => array(
                'sum(Order.change) as change_total', 'sum(Order.card_val) as card_val', 'sum(Order.cash_val) as cash_val', 'sum(Order.subtotal) as subtotal', 'sum(Order.tax_amount) as tax_amount', 'sum(Order.total) as total', 'DATE_FORMAT(Order.created, "%m") as month'
            )//,
            //'recursive' => -1
        );

        //Modified by Yishou Liao @ Dec 07 2016
        switch ($range) {
            case 0:
                $query['group'] = 'DATE_FORMAT(Order.created, "%Y-%m-%d")';
                break;
            case 1:
                $query['group'] = 'DATE_FORMAT(Order.created, "%Y-%m")';
                break;
            case 2:
                $query['group'] = 'DATE_FORMAT(Order.created, "%Y")';
                break;
        };
        //End @ Dec 07 2016

        // modified by Yu Dec 13, 2016
        $this->set('range', $range);

        $records_summaies = $this->Order->find('all', $query);
        //End @ Dec 07 2016
        // get all cashiers list        
        $this->loadModel('Cashier');
        $conditions = [];
        if ($is_super_admin <> 'Y')
            $conditions = array('restaurant_id' => $this->Session->read('Admin.id'));

        $cashiers = $this->Cashier->find('list', array('fields' => array('Cashier.id', 'Cashier.firstname'), 'conditions' => $conditions, 'order' => array('Cashier.firstname' => 'ASC')));
        $this->set(compact('records', 'records_summaies', 'limit', 'is_super_admin', 'cashier', 'cashiers', 'range'));
    }

}
