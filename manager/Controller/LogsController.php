<?php

/**
 * Class LogsController
 */
class LogsController extends AppController {

    public $uses = array('Log');
    public $components = array('Session', 'Paginator');

    /**
     * beforeFilter
     * @return null
     */
    public function beforeFilter() {
        parent::beforeFilter();
        $this->set('tab_open', 'Logs');
    }

    /**
     * admin_index For listing of orders
     * @return mixed
     */
    public function admin_index() {

        $this->checkAccess('Log', 'can_view');

        $this->layout = 'admin';
        $this->loadModel('Log');
        
        $limit = DEFAULT_PAGE_SIZE;
        $order = 'Log.created DESC';

        $conditions = array();
        $is_super_admin = $this->Session->read('Admin.is_super_admin');
        if('Y' <> $is_super_admin){
          //  $conditions = array('is_hide'=>'N');
        }

        if (!empty($this->request->data)) {

            if(isset($this->request->data['Log']) && !empty($this->request->data['Log'])) {
                $search_data = $this->request->data['Log'];
                $this->Session->write('log_search', $search_data);
            }

            if(isset($this->request->data['PageSize']['records_per_page']) && !empty($this->request->data['PageSize']['records_per_page'])) {
                $this->Session->write('page_size', $this->request->data['PageSize']['records_per_page']);
            }
        }

        if($this->Session->check('page_size')){
        	  $limit = $this->Session->read('page_size');

            //$limit = strtolower($this->Session->read('page_size'))=='all'? 100000000 : $this->Session->read('page_size');
        }

        if($this->Session->check('log_search')){
            $search = $this->Session->read('log_search');

            if(!empty($search['name'])){
                $conditions['Log.name like'] = '%'.$search['name'].'%';
            }
            if(!empty($search['operation'])){
                $conditions['Log.operation like '] = '%'.$search['operation'].'%';
            }
            if(!empty($search['logs'])){
                $conditions['Log.logs like '] = '%'.$search['logs'].'%';
            }
            if(!empty($search['created'])){
                $conditions['Log.created like '] = '%'.$search['created'].'%';
            }

        }
       
        $this->Log->virtualFields['name'] = "Select concat(firstname,' ',lastname) as name from cashiers where cashiers.id = Log.cashier_id";
        
        $query = array(
            'conditions' => $conditions,
            'order' => $order,
            'recursive'=>-1
        );
        
        if('all' == $limit){
            $records = $this->Log->find('all', $query);            
        }else{
            $query['limit'] = $limit;
            $this->paginate = $query;
            $records = $this->paginate();
        }
        
        $this->set(compact('records', 'limit', 'order', 'is_super_admin'));
    }


    public function admin_batch_delete() {

        $this->layout = false;
        $this->autoRender = NULL;

        $this->loadModel('Log');
        
        $ids = $this->data['ids'];
        $ret = $this->Log->deleteAll(array('Log.id' => $ids), false);
        
        $this->Session->setFlash('Records have been deleted successfully', 'success');
        
    }


}
