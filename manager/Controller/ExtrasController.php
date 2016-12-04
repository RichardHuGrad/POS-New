<?php

/**
 * Class ExtrasController
 */
class ExtrasController extends AppController {
    public $uses = array('Extra','Extrascategory');
    public $components = array('Session', 'Paginator');

    /**
     * beforeFilter
     * @return null
     */
    public function beforeFilter() {
        parent::beforeFilter();
        $this->set('tab_open', 'extra');
    }

    /**
     * admin_index For listing of extras
     * @return mixed
     */
    public function admin_index() {
        //$id = $this->params->query['id']; //Modified by Yishou Liao @ Dec 01 2016
        $this->layout = 'admin';
        $limit = DEFAULT_PAGE_SIZE;
        $order = 'Extra.name ASC';
        $conditions = array(
                // 'Extra.cousine_id'=>$id
        );

        if (!empty($this->request->data)) {

            if (isset($this->request->data['Extra']) && !empty($this->request->data['Extra'])) {
                $search_data = $this->request->data['Extra'];
                $this->Session->write('Color_search', $search_data);
            }

            if (isset($this->request->data['PageSize']['records_per_page']) && !empty($this->request->data['PageSize']['records_per_page'])) {
                $this->Session->write('page_size', $this->request->data['PageSize']['records_per_page']);
            }
        }

        if ($this->Session->check('page_size')) {
            $limit = $this->Session->read('page_size');
        }
        xdebug_break();
        if ($this->Session->check('Color_search')) {
            $search = $this->Session->read('Color_search');
            $order = $search['order_by'];

            if (!empty($search['search'])) {
                $conditions['Extra.name LIKE'] = '%' . $search['search'] . '%';
                // $conditions['Admin.restaurant_name LIKE'] = '%' . $search['search'] . '%';
            }

            if (!empty($search['status'])) {
                $conditions['Extra.status'] = $search['status'];
            }
        }

        $query = array(
            'conditions' => $conditions,
            'order' => $order
        );
        if ('all' == $limit) {
            $extras = $this->Extra->find('all', $query);
        } else {
            $query['limit'] = $limit;
            $this->paginate = $query;
            $extras = $this->paginate();
        }
        $this->loadModel('CousineLocal');
        //Modified by Yishou Liao @ Dec 01 2016
        //$CousineLocal_data = $this->CousineLocal->find('first', array('fields'=>array('CousineLocal.name'), 'conditions' => array('CousineLocal.parent_id' => $id, 'lang_code'=>'en')));
        $CousineLocal_data = $this->CousineLocal->find('first', array('fields' => array('CousineLocal.name'), 'conditions' => array('lang_code' => 'en')));
        
        //$this->set(compact('extras', 'limit', 'order', 'id', 'CousineLocal_data'));
        $this->set(compact('extras', 'limit', 'order', 'CousineLocal_data'));
        //End
    }

    /**
     * To add or edit Extra
     * @param string $id
     * @return mixed
     */
    function admin_add_edit($id = '') {
        //$cousine_id = $this->params->query['id'];

        //Modified by Yishou Liao @ Dec 04 2016
        $id = base64_decode($id);
        $cousine_id = $id;
        //End @ Dec 04 2016

        $this->layout = 'admin';

        if (!empty($this->request->data)) {

            $this->Extra->set($this->request->data);

            if ($this->Extra->validates()) {
                if ($this->Extra->save($this->request->data, $validate = false)) {

                    if ('' == $id) {
                        $this->Session->setFlash('Extra has been added successfully', 'success');
                    } else {
                        $this->Session->setFlash('Extra has been updated successfully', 'success');
                    }
                    $this->redirect(array('plugin' => false, 'controller' => 'extras', 'action' => 'index', '?' => array('id' => $cousine_id), 'admin' => true));
                }
            }
        }

        if ('' != $id) {
            //$id = base64_decode($id); //Modified by Yishou Liao @ Dec 04 2016
            $Color_data = $this->Extra->find('first', array('conditions' => array('Extra.id' => $id)));
            if (empty($Color_data)) {
                $this->Session->setFlash('Invalid Request', 'error');
                $this->redirect(array('plugin' => false, 'controller' => 'extras', 'action' => 'index', '?' => array('id' => $cousine_id), 'admin' => true));
            }

            if (empty($this->request->data)) {
                $this->request->data = $Color_data;
            }
        }
        $this->loadModel('Admin');
        $restaurants = $this->Admin->find('list', array('fields' => array('Admin.id', 'Admin.restaurant_name'), 'conditions' => array('Admin.status' => 'A', 'Admin.is_super_admin' => 'N'), 'order' => array('Admin.firstname' => 'ASC')));

         //Modified by Yishou Liao @ Dec 04 2016
        $Extrascategory_data = $this->Extrascategory->find('all', array('fields' => array('Extrascategory.id,Extrascategory.name,Extrascategory.name_zh'), 'conditions' => array('status' => 'A')));
        //End @ Dec 04 2016
        
        $this->set(compact('id', 'restaurants', 'cousine_id','Extrascategory_data'));
    }

    /**
     * Change the status of the Extra
     * @param string $id
     * @param string $status
     * @return null
     */
    public function admin_status($id = '', $status = '') {

        $id = base64_decode($id);

        $is_valid = true;
        if ('' == $id || '' == $status) {
            $is_valid = false;
        } else {
            $check_user_exists = $this->Extra->Find('count', array('conditions' => array('Extra.id' => $id), 'limit' => 1));
            if (0 == $check_user_exists) {
                $is_valid = false;
            }
        }

        if (!$is_valid) {
            $this->Session->setFlash('Invalid Request', 'error');
            $this->redirect(array('plugin' => false, 'controller' => 'extras', 'action' => 'index', 'admin' => true));
        }

        $this->Extra->updateAll(array('Extra.status' => "'" . $status . "'"), array('Extra.id' => $id));

        $this->Session->setFlash('Extra status has been changed successfully', 'success');
        $this->redirect(Router::url($this->referer(), true));
    }

    /**
     * Delete the Extra
     * @param string $id
     * @return null
     */
    public function admin_delete($id = '') {

        $cousine_id = $this->params->query['id'];

        $id = base64_decode($id);
        $this->Extra->delete($id);

        $this->Session->setFlash('Extra has been deleted successfully', 'success');
        $this->redirect(array('plugin' => false, 'controller' => 'extras', 'action' => 'index', 'admin' => true, '?' => array('id' => $cousine_id)));
    }

}