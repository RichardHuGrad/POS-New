<?php

/**
 * Class RestaurantsController
 */
class RestaurantsController extends AppController {

    public $uses = array('Restaurant');
    public $components = array('Session', 'Paginator');

    /**
     * beforeFilter
     * @return null
     */
    public function beforeFilter() {
        parent::beforeFilter();
         $this->set('tab_open', 'restaurant');
    }

    /**
     * admin_index For listing of restaurants
     * @return mixed
     */
    public function admin_index() {

        $this->checkAccess('Restaurant', 'can_view');

        $this->layout = 'admin';
        $limit = DEFAULT_PAGE_SIZE;
        $order = 'Restaurant.created DESC';
        $conditions = array();

        if (!empty($this->request->data)) {

            if(isset($this->request->data['Restaurant']) && !empty($this->request->data['Restaurant'])) {
                $search_data = $this->request->data['Restaurant'];
                $this->Session->write('restaurant_search', $search_data);
            }

            if(isset($this->request->data['PageSize']['records_per_page']) && !empty($this->request->data['PageSize']['records_per_page'])) {
                $this->Session->write('page_size', $this->request->data['PageSize']['records_per_page']);
            }
        }

        if($this->Session->check('page_size')){
            $limit = $this->Session->read('page_size');
        }

        if($this->Session->check('restaurant_search')){
            $search = $this->Session->read('restaurant_search');
            $order = $search['order_by'];

            if(!empty($search['search'])){
                $conditions['OR'] = array(
                    'Restaurant.firstname LIKE' => '%' . $search['search'] . '%',
                    'Restaurant.lastname LIKE' => '%' . $search['search'] . '%',
                    'Restaurant.email LIKE' => '%' . $search['search'] . '%',
                    'Restaurant.mobile_no LIKE' => '%' . $search['search'] . '%',
                );
            }

            if(!empty($search['status'])){
                $conditions['Restaurant.status'] = $search['status'];
            }
            if(!empty($search['is_verified'])){
                $conditions['Restaurant.is_verified'] = $search['is_verified'];
            }
            if(!empty($search['registered_from'])){
                $conditions['date(Restaurant.created) >='] = $search['registered_from'];
            }
            if(!empty($search['registered_till'])){
                $conditions['date(Restaurant.created) <='] = $search['registered_till'];
            }

        }

        $query = array(
            'conditions' => $conditions,
            'fields' => array(
                'Restaurant.id', 'Restaurant.firstname', 'Restaurant.lastname', 'Restaurant.email', 'Restaurant.mobile_no', 'Restaurant.status', 'Restaurant.is_verified', 'Restaurant.created'
            ),
            'order' => $order
        );
        if('all' == $limit){
            $customer_list = $this->Restaurant->find('all', $query);
        }else{
            $query['limit'] = $limit;
            $this->paginate = $query;
            $customer_list = $this->paginate();
        }
        $this->set(compact('customer_list', 'limit', 'order'));
    }

    /**
     * To view detail of restaurant
     * @param string $id
     * @return mixed
     */
    function admin_detail($id = '') {

        $this->checkAccess('Restaurant', 'can_view');
        $this->layout = 'admin';

        if('' != $id){
            $id = base64_decode($id);
            $customer_data = $this->Restaurant->find('first', array('conditions' => array('Restaurant.id' => $id)));
            if(empty($customer_data)){
                $this->Session->setFlash('Invalid Request', 'error');
                $this->redirect(array('plugin' => false, 'controller' => 'restaurants', 'action' => 'index', 'admin' => true));
            }
            $this->set(compact('customer_data'));

        }else{
            $this->Session->setFlash('Invalid Request', 'error');
            $this->redirect(array('plugin' => false, 'controller' => 'restaurants', 'action' => 'index', 'admin' => true));
        }

    }

    /**
     * To add or edit restaurant detail
     * @param string $id
     * @return mixed
     */
    function admin_add_edit($id = '') {

        if('' == $id){
            $this->checkAccess('Restaurant', 'can_add');
        }
        else{
            $this->checkAccess('Restaurant', 'can_edit');
        }

        $this->layout = 'admin';

        if (!empty($this->request->data)) {
            $this->Restaurant->set($this->request->data);
            if ($this->Restaurant->validates()) {

                if(isset($this->request->data['Restaurant']['password'])) {
                    $this->request->data['Restaurant']['password'] = Security::hash($this->request->data['Restaurant']['password'], 'md5');
                }
                if ($this->Restaurant->save($this->request->data, $validate = false)) {

                    if('' == $id){
                        $this->Session->setFlash('Restaurant has been added successfully', 'success');
                    }else{
                        $this->Session->setFlash('Restaurant has been updated successfully', 'success');
                    }

                    $this->redirect(array('plugin' => false, 'controller' => 'restaurants', 'action' => 'index', 'admin' => true));
                }
            }
        }

        if('' != $id){
            $id = base64_decode($id);
            $customer_data = $this->Restaurant->find('first', array('conditions' => array('Restaurant.id' => $id)));
            if(empty($customer_data)){
                $this->Session->setFlash('Invalid Request', 'error');
                $this->redirect(array('plugin' => false, 'controller' => 'restaurants', 'action' => 'index', 'admin' => true));
            }

            if (empty($this->request->data)) {
                $this->request->data = $customer_data;
            }
        }

        $this->set(compact('id'));
    }

    /**
     * Change the status of the restaurant
     * @param string $id
     * @param string $status
     * @return null
     */
    public function admin_status($id = '', $status = '') {

        $this->checkAccess('Restaurant', 'can_edit');
        $id = base64_decode($id);

        $is_valid = true;
        $name = $email = '';
        if('' == $id || '' == $status){
            $is_valid = false;
        }else{
            $check_user_exists = $this->Restaurant->Find('first', array('fields' => array('Restaurant.firstname', 'Restaurant.lastname', 'Restaurant.email'), 'conditions' => array('Restaurant.id' => $id)));
            if (empty($check_user_exists)) {
                $is_valid = false;
            }else{
                $name = ucfirst($check_user_exists['Restaurant']['firstname']) . ' ' . ucfirst($check_user_exists['Restaurant']['lastname']);
                $email = $check_user_exists['Restaurant']['email'];
            }
        }

        if($is_valid) {

            $this->Restaurant->updateAll(array('Restaurant.status' => "'" . $status . "'"), array('Restaurant.id' => $id));

            if('A' == $status){
                $viewVars = array('name' => $name, 'type' => 'active');
                $subject = 'POS: Profile Activation';
            }else{
                $viewVars = array('name' => $name, 'type' => 'inactive');
                $subject = 'POS: Profile Deactivated';
            }

            $this->sendMail($email, $subject, 'status_update', 'default', $viewVars);
            
            $this->Session->setFlash('Restaurant status has been changed successfully', 'success');
            $this->redirect(Router::url( $this->referer(), true ));

        }else{
            $this->Session->setFlash('Invalid Request', 'error');
            $this->redirect(array('plugin' => false, 'controller' => 'restaurants', 'action' => 'index', 'admin' => true));
        }
    }

    /**
     * Delete the restaurant
     * @param string $id
     * @return null
     */
    public function admin_delete($id = '') {

        $this->checkAccess('Restaurant', 'can_delete');
        $id = base64_decode($id);
        $this->Restaurant->updateAll(array('Restaurant.status' => "'D'"), array('Restaurant.id' => $id));

        $this->Session->setFlash('Restaurant has been deleted successfully', 'success');
        $this->redirect(array('plugin' => false, 'controller' => 'restaurants', 'action' => 'index', 'admin' => true));

    }

    /**
     * Listing of restaurants whom age proof document is pending for approval
     * @return mixed
     */
    public function admin_pending_approvals(){
        $this->checkAccess('Restaurant', 'can_view');
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
            'conditions' => array('Restaurant.is_verified' => 'N'),
            'fields' => array(
                'Restaurant.id', 'Restaurant.firstname', 'Restaurant.lastname', 'Restaurant.email', 'Restaurant.mobile_no'
            ),
            'order' => array('Restaurant.created' => 'DESC')
        );
        if('all' == $limit){
            $customer_list = $this->Restaurant->find('all', $query);
        }else{
            $query['limit'] = $limit;
            $this->paginate = $query;
            $customer_list = $this->paginate();
        }
        $this->set(compact('customer_list', 'limit'));
    }


    /**
     * Approve the selected restaurant
     * @param string $id
     * @return null
     */
    public function admin_approve_customer($id = '') {

        $this->checkAccess('Restaurant', 'can_edit');
        $id = base64_decode($id);

        $is_valid = true;
        $name = $email = '';
        if('' == $id){
            $is_valid = false;
        }else{
            $check_user_exists = $this->Restaurant->Find('first', array('fields' => array('Restaurant.firstname', 'Restaurant.lastname', 'Restaurant.email'), 'conditions' => array('Restaurant.id' => $id)));
            if (empty($check_user_exists)) {
                $is_valid = false;
            }else{
                $name = ucfirst($check_user_exists['Restaurant']['firstname']) . ' ' . ucfirst($check_user_exists['Restaurant']['lastname']);
                $email = $check_user_exists['Restaurant']['email'];
            }
        }

        if($is_valid) {
            $this->Restaurant->updateAll(array('Restaurant.is_verified' => "'Y'"), array('Restaurant.id' => $id));

            $viewVars = array('name' => $name, 'type' => 'approve');
            $this->sendMail($email, 'POS: Profile Approve', 'status_update', 'default', $viewVars);

            $this->Session->setFlash('Restaurant has been approved successfully', 'success');
            $this->redirect(array('plugin' => false, 'controller' => 'restaurants', 'action' => 'pending_approvals', 'admin' => true));
        }else{
            $this->Session->setFlash('Invalid Request', 'error');
            $this->redirect(array('plugin' => false, 'controller' => 'restaurants', 'action' => 'pending_approvals', 'admin' => true));
        }
        
    }

    /**
     * Change the password of the restaurant by the admin
     * @param string $id
     * @return null
     */
    function admin_change_password($id = '') {

        $this->checkAccess('Restaurant', 'can_edit');
        $this->layout = 'admin';

        $id = base64_decode($id);
        $is_valid = true;
        if('' == $id){
            $is_valid = false;
        }else{
            $user_data = $this->Restaurant->Find('first', array(
                'fields' => array('Restaurant.firstname', 'Restaurant.lastname'),
                'conditions' => array('Restaurant.id' => $id), 'limit' => 1
            ));

            $this->set(compact('id', 'user_data'));
            if (empty($user_data)) {
                $is_valid = false;
            }
        }

        if(!$is_valid) {
            $this->Session->setFlash('Invalid Request', 'error');
            $this->redirect(array('plugin' => false, 'controller' => 'restaurants', 'action' => 'index', 'admin' => true));
        }

        if (!empty($this->request->data)) {

            $this->Restaurant->set($this->request->data);

            if ($this->Restaurant->validates()) {

                $new_password = Security::hash($this->request->data['Restaurant']['password'], 'md5');

                $this->Restaurant->updateAll(array('Restaurant.password' => "'" . $new_password . "'"), array('Restaurant.id' => $id));
                $this->Session->setFlash('Password has been updated successfully', 'success');
                $this->redirect(array('plugin' => false, 'controller' => 'restaurants', 'action' => 'index', 'admin' => true));
            }
        }
    }

}
