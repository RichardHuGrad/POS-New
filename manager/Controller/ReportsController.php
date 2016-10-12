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
         $this->set('tab_open', 'reports');
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
        $order = 'Order.created DESC';

        $conditions = array();
        $is_super_admin = $this->Session->read('Admin.is_super_admin');
        if('Y' <> $is_super_admin){
            $conditions = array('is_hide'=>'N');
        }

        if (!empty($this->request->data)) {

            if(isset($this->request->data['Order']) && !empty($this->request->data['Order'])) {
                $search_data = $this->request->data['Order'];
                $this->Session->write('order_search', $search_data);
            }

            if(isset($this->request->data['PageSize']['records_per_page']) && !empty($this->request->data['PageSize']['records_per_page'])) {
                $this->Session->write('page_size', $this->request->data['PageSize']['records_per_page']);
            }
        }

        if($this->Session->check('page_size')){
            $limit = $this->Session->read('page_size');
        }

        if($this->Session->check('order_search')){
            $search = $this->Session->read('order_search');

            if(!empty($search['table_status'])){
                $conditions['Order.table_status'] =array(@$search['table_status'][0], @$search['table_status'][1]);
            }
            if(!empty($search['paid_by'])){
                $conditions['Order.paid_by'] =array(@$search['paid_by'][0], @$search['paid_by'][1], @$search['paid_by'][2]);
            }

            if(!empty($search['cooking_status'])){
                $conditions['Order.cooking_status'] =array(@$search['cooking_status'][0], @$search['cooking_status'][1]);
            }


            if(!empty($search['search'])){
                $conditions['Order.order_no'] = $search['search'];
            }
            if(!empty($search['registered_from'])){
                $conditions['date(Order.created) >='] = $search['registered_from'];
            }
            if(!empty($search['registered_till'])){
                $conditions['date(Order.created) <='] = $search['registered_till'];
            }

        }

        $query = array(
            'conditions' => $conditions,
            'fields' => array(
                'Order.counter_id', 'Cashier.firstname', 'Cashier.lastname', 'count(Order.id) as counter', 'sum(Order.total) as total', 'sum(Order.tip) as total_tip', 'sum(Order.tax_amount) as total_tax_amount', 'sum(Order.card_val) as total_card_val', 'sum(Order.cash_val) as total_cash_val'
            ),
            'order' => $order,
            'group'=>"Order.counter_id",
            // 'recursive'=>-1
        );
        if('all' == $limit){
            $records = $this->Order->find('all', $query);
        }else{
            $query['limit'] = $limit;
            $this->paginate = $query;
            $records = $this->paginate('Order');
        }
        // pr($records);
        $this->set(compact('records', 'limit', 'order', 'is_super_admin'));
    }

}
