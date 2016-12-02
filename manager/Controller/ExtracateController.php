<?php

/**
 * Class CategoriesController
 */
class ExtracateController extends AppController {

    public $uses = array('Extrascategory', 'Language');
    public $components = array('Session', 'Paginator');

    /**
     * beforeFilter
     * @return null
     */
    public function beforeFilter() {
        parent::beforeFilter();
        $this->set('tab_open', 'extracate');
    }

    /**
     * admin_index For listing of categories
     * @return mixed
     */
    public function admin_index() {
        $this->layout = LAYOUT_ADMIN;
        $limit = DEFAULT_PAGE_SIZE;
        $order = 'name ASC';
        $conditions = array();

        if (!empty($this->request->data)) {

            if (isset($this->request->data['extrascategory']) && !empty($this->request->data['extrascategory'])) {
                $search_data = $this->request->data['extrascategory'];
                $this->Session->write('extrascategory_search', $search_data);
            }

            if (isset($this->request->data['PageSize']['records_per_page']) && !empty($this->request->data['PageSize']['records_per_page'])) {
                $this->Session->write('page_size', $this->request->data['PageSize']['records_per_page']);
            }
        }

        if ($this->Session->check('page_size')) {
            $limit = $this->Session->read('page_size');
        }

        if ($this->Session->check('extrascategory_search')) {
            $search = $this->Session->read('extrascategory_search');
            // $order = $search['order_by'];

            if (!empty($search['search'])) {
                $conditions['or'] = array(
                    'name LIKE' => '%' . $search['search'] . '%',
                    'name_zh LIKE' => '%' . $search['search'] . '%',
                );
            }

            if (!empty($search['status'])) {
                $conditions['extrascategory.status'] = $search['status'];
            }
        }
        
        $query = array(
            'conditions' => $conditions,
            'order' => $order
        );
        
        if('all' == $limit){
            $extrascategory = $this->Extrascategory->find('all', $query);
        }else{
            $query['limit'] = $limit;
            $this->paginate = $query;
            $extrascategory = $this->paginate('Extrascategory');
        };
        
        $this->set(compact('extrascategory', 'limit', 'order', 'languages'));
    }

}
