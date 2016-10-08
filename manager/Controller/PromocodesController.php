<?php

/**
 * Class PromocodesController
 */
class PromocodesController extends AppController {

    public $uses = array('Promocode');
    public $components = array('Session', 'Paginator');

    /**
     * beforeFilter
     * @return null
     */
    public function beforeFilter() {
        parent::beforeFilter();
         $this->set('tab_open', 'promocodes');
    }

    /**
     * admin_index For listing of promocodes
     * @return mixed
     */
    public function admin_index() {

        $this->checkAccess('Promocode', 'can_view');

        $this->layout = 'admin';
        $limit = DEFAULT_PAGE_SIZE;
        $order = 'Promocode.created DESC';
        $conditions = array();

        if (!empty($this->request->data)) {

            if(isset($this->request->data['Promocode']) && !empty($this->request->data['Promocode'])) {
                $search_data = $this->request->data['Promocode'];
                $this->Session->write('cashier_search', $search_data);
            }

            if(isset($this->request->data['PageSize']['records_per_page']) && !empty($this->request->data['PageSize']['records_per_page'])) {
                $this->Session->write('page_size', $this->request->data['PageSize']['records_per_page']);
            }
        }

        if($this->Session->check('page_size')){
            $limit = $this->Session->read('page_size');
        }

        if($this->Session->check('cashier_search')){
            $search = $this->Session->read('cashier_search');

            if(!empty($search['search'])){
                $conditions['OR'] = array(
                    'Admin.restaurant_name LIKE' => '%' . $search['search'] . '%',
                    'Promocode.code LIKE' => '%' . $search['search'] . '%',
                );
            }

            if(!empty($search['status'])){
                $conditions['Promocode.status'] = $search['status'];
            }
            if(!empty($search['is_verified'])){
                $conditions['Promocode.is_verified'] = $search['is_verified'];
            }
            if(!empty($search['registered_from'])){
                $conditions['date(Promocode.created) >='] = strtotime($search['registered_from']);
            }
            if(!empty($search['registered_till'])){
                $conditions['date(Promocode.created) <='] = strtotime($search['registered_till']);
            }

        }
        $is_super_admin = $this->Session->read('Admin.is_super_admin');
        if('Y' <> $is_super_admin){
            $conditions['Promocode.restaurant_id'] = $this->Session->read('Admin.id');            
        }

        // pr($conditions);
        $query = array(
            'conditions' => $conditions,
            'fields' => array(
                'Admin.restaurant_name', 'Promocode.*'
            )
        );
        if('all' == $limit){
            $customer_list = $this->Promocode->find('all', $query);
        }else{
            $query['limit'] = $limit;
            $this->paginate = $query;
            $customer_list = $this->paginate();
        }
        $this->set(compact('customer_list', 'limit', 'order'));
    }

    /**
     * To add or edit cashier detail
     * @param string $id
     * @return mixed
     */
    function admin_add_edit($id = '') {

        if('' == $id){
            $this->checkAccess('Promocode', 'can_add');
        }
        else{
            $this->checkAccess('Promocode', 'can_edit');
        }

        $this->layout = 'admin';

        if (!empty($this->request->data)) {
            $this->Promocode->set($this->request->data);
            if ($this->Promocode->validates()) {

                if ($this->Promocode->save($this->request->data, $validate = false)) {

                    if('' == $id){
                        $this->Session->setFlash('Promocode has been added successfully', 'success');
                    }else{
                        $this->Session->setFlash('Promocode has been updated successfully', 'success');
                    }

                    $this->redirect(array('plugin' => false, 'controller' => 'promocodes', 'action' => 'index', 'admin' => true));
                }
            }
        }

        if('' != $id){
            $id = base64_decode($id);
            $customer_data = $this->Promocode->find('first', array('conditions' => array('Promocode.id' => $id)));
            if(empty($customer_data)){
                $this->Session->setFlash('Invalid Request', 'error');
                $this->redirect(array('plugin' => false, 'controller' => 'promocodes', 'action' => 'index', 'admin' => true));
            }

            if (empty($this->request->data)) {
                $this->request->data = $customer_data;
            }
        }
        $this->loadModel('Admin');
        $restaurants = $this->Admin->find('list',
            array('fields' => array('Admin.id', 'Admin.restaurant_name'), 'conditions' => array('Admin.status' => 'A', 'Admin.is_super_admin' => 'N'), 'order' => array('Admin.firstname' => 'ASC')));


        $this->set(compact('id', 'restaurants'));
    }

    /**
     * Change the status of the cashier
     * @param string $id
     * @param string $status
     * @return null
     */
    public function admin_status($id = '', $status = '') {

        $this->checkAccess('Promocode', 'can_edit');
        $id = base64_decode($id);

        $is_valid = true;
        $name = $email = '';
        if('' == $id || '' == $status){
            $is_valid = false;
        }else{
            $check_user_exists = $this->Promocode->Find('first', array('fields' => array('Promocode.code'), 'conditions' => array('Promocode.id' => $id)));
            if (empty($check_user_exists)) {
                $is_valid = false;
            }
        }

        if($is_valid) {

            $this->Promocode->updateAll(array('Promocode.status' => "'" . $status . "'"), array('Promocode.id' => $id));
            
            $this->Session->setFlash('Promocode status has been changed successfully', 'success');
            $this->redirect(Router::url( $this->referer(), true ));

        }else{
            $this->Session->setFlash('Invalid Request', 'error');
            $this->redirect(array('plugin' => false, 'controller' => 'promocodes', 'action' => 'index', 'admin' => true));
        }
    }

    /**
     * Delete the cashier
     * @param string $id
     * @return null
     */
    public function admin_delete($id = '') {

        $this->checkAccess('Promocode', 'can_delete');
        $id = base64_decode($id);
        $this->Promocode->updateAll(array('Promocode.status' => "'D'"), array('Promocode.id' => $id));

        $this->Session->setFlash('Promocode has been deleted successfully', 'success');
        $this->redirect(array('plugin' => false, 'controller' => 'promocodes', 'action' => 'index', 'admin' => true));

    }

    /**
     * Listing of promocodes whom age proof document is pending for approval
     * @return mixed
     */
    public function admin_pending_approvals(){
        $this->checkAccess('Promocode', 'can_view');
        $this->layout = 'admin';
        $this->set('tab_open', 'customer_pending_approval');
        $limit = DEFAULT_PAGE_SIZE;

        if (!empty($this->request->data)) {

            if(isset($this->request->data['PageSize']['records_per_page']) && !empty($this->request->data['PageSize']['records_per_page'])) {
                $this->Session->write('page_size', $this->request->data['PageSize']['records_per_page']);
            }
        }

        if($this->Session->check('page_size')){
            $limit = $this->Session->read('page_size');
        }
        $query = array(
            'conditions' => array('Promocode.is_verified' => 'N'),
            'fields' => array(
                'Promocode.id', 'Promocode.firstname', 'Promocode.lastname', 'Promocode.email', 'Promocode.mobile_no'
            ),
            'order' => array('Promocode.created' => 'DESC')
        );
        if('all' == $limit){
            $customer_list = $this->Promocode->find('all', $query);
        }else{
            $query['limit'] = $limit;
            $this->paginate = $query;
            $customer_list = $this->paginate();
        }
        $this->set(compact('customer_list', 'limit'));
    }


    /**
     * Approve the selected cashier
     * @param string $id
     * @return null
     */
    public function admin_approve_customer($id = '') {

        $this->checkAccess('Promocode', 'can_edit');
        $id = base64_decode($id);

        $is_valid = true;
        $name = $email = '';
        if('' == $id){
            $is_valid = false;
        }else{
            $check_user_exists = $this->Promocode->Find('first', array('fields' => array('Promocode.firstname', 'Promocode.lastname', 'Promocode.email'), 'conditions' => array('Promocode.id' => $id)));
            if (empty($check_user_exists)) {
                $is_valid = false;
            }else{
                $name = ucfirst($check_user_exists['Promocode']['firstname']) . ' ' . ucfirst($check_user_exists['Promocode']['lastname']);
                $email = $check_user_exists['Promocode']['email'];
            }
        }

        if($is_valid) {
            $this->Promocode->updateAll(array('Promocode.is_verified' => "'Y'"), array('Promocode.id' => $id));

            $viewVars = array('name' => $name, 'type' => 'approve');
            $this->sendMail($email, 'POS: Profile Approve', 'status_update', 'default', $viewVars);

            $this->Session->setFlash('Promocode has been approved successfully', 'success');
            $this->redirect(array('plugin' => false, 'controller' => 'promocodes', 'action' => 'pending_approvals', 'admin' => true));
        }else{
            $this->Session->setFlash('Invalid Request', 'error');
            $this->redirect(array('plugin' => false, 'controller' => 'promocodes', 'action' => 'pending_approvals', 'admin' => true));
        }
        
    }

    /**
     * Change the password of the cashier by the admin
     * @param string $id
     * @return null
     */
    function admin_change_password($id = '') {

        $this->checkAccess('Promocode', 'can_edit');
        $this->layout = 'admin';

        $id = base64_decode($id);
        $is_valid = true;
        if('' == $id){
            $is_valid = false;
        }else{
            $user_data = $this->Promocode->Find('first', array(
                'fields' => array('Promocode.firstname', 'Promocode.lastname'),
                'conditions' => array('Promocode.id' => $id), 'limit' => 1
            ));

            $this->set(compact('id', 'user_data'));
            if (empty($user_data)) {
                $is_valid = false;
            }
        }

        if(!$is_valid) {
            $this->Session->setFlash('Invalid Request', 'error');
            $this->redirect(array('plugin' => false, 'controller' => 'promocodes', 'action' => 'index', 'admin' => true));
        }

        if (!empty($this->request->data)) {

            $this->Promocode->set($this->request->data);

            if ($this->Promocode->validates()) {

                $new_password = Security::hash($this->request->data['Promocode']['password'], 'md5');

                $this->Promocode->updateAll(array('Promocode.password' => "'" . $new_password . "'"), array('Promocode.id' => $id));
                $this->Session->setFlash('Password has been updated successfully', 'success');
                $this->redirect(array('plugin' => false, 'controller' => 'promocodes', 'action' => 'index', 'admin' => true));
            }
        }
    }

}
