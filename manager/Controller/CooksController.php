<?php

/**
 * Class CooksController
 */
class CooksController extends AppController {

    public $uses = array('Cook');
    public $components = array('Session', 'Paginator');

    /**
     * beforeFilter
     * @return null
     */
    public function beforeFilter() {
        parent::beforeFilter();
         $this->set('tab_open', 'cooks');
    }

    /**
     * admin_index For listing of cooks
     * @return mixed
     */
    public function admin_index() {

        $this->checkAccess('Cook', 'can_view');

        $this->layout = 'admin';
        $limit = DEFAULT_PAGE_SIZE;
        $order = 'Cook.created DESC';
        $conditions = array();

        if (!empty($this->request->data)) {

            if(isset($this->request->data['Cook']) && !empty($this->request->data['Cook'])) {
                $search_data = $this->request->data['Cook'];
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
            $order = $search['order_by'];

            if(!empty($search['search'])){
                $conditions['OR'] = array(
                    'concat_ws(" ", Admin.restaurant_name) LIKE' => '%' . $search['search'] . '%',
                    'concat_ws(" ", Cook.firstname, Cook.lastname) LIKE' => '%' . $search['search'] . '%',
                    'Cook.lastname LIKE' => '%' . $search['search'] . '%',
                    'Cook.email LIKE' => '%' . $search['search'] . '%',
                    'Cook.mobile_no LIKE' => '%' . $search['search'] . '%',
                );
            }

            if(!empty($search['status'])){
                $conditions['Cook.status'] = $search['status'];
            }
            if(!empty($search['is_verified'])){
                $conditions['Cook.is_verified'] = $search['is_verified'];
            }
            if(!empty($search['registered_from'])){
                $conditions['date(Cook.created) >='] = $search['registered_from'];
            }
            if(!empty($search['registered_till'])){
                $conditions['date(Cook.created) <='] = $search['registered_till'];
            }

        }

        $is_super_admin = $this->Session->read('Admin.is_super_admin');
        if('Y' <> $is_super_admin){
            $conditions['Cook.restaurant_id'] = $this->Session->read('Admin.id');            
        }

        $query = array(
            'conditions' => $conditions,
            'fields' => array(
                'Admin.restaurant_name', 'Admin.lastname', 'Cook.id', 'Cook.userid', 'Cook.firstname', 'Cook.lastname', 'Cook.email', 'Cook.mobile_no', 'Cook.status', 'Cook.is_verified', 'Cook.created'
            ),
            'order' => $order
        );
        if('all' == $limit){
            $customer_list = $this->Cook->find('all', $query);
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
            $this->checkAccess('Cook', 'can_add');
        }
        else{
            $this->checkAccess('Cook', 'can_edit');
        }

        $this->layout = 'admin';

        if (!empty($this->request->data)) {
            $this->Cook->set($this->request->data);
            if ($this->Cook->validates()) {

                 $is_error_image = 0;
                if (isset($this->request->data['Cook']['image']['name']) && $this->request->data['Cook']['image']['name'] != "") {

                    @unlink(COOK_UPLOAD_PATH.'thumbnail/' . @$result_data['Cook']['image']);
                    $is_image_uploaded = 1;
                    $allowed_extension = array('jpg', 'jpeg', 'png', 'gif');
                    $extension = pathinfo($this->request->data['Cook']['image']['name'], PATHINFO_EXTENSION);
                    $extension = strtolower($extension);
                    if (!in_array($extension, $allowed_extension)) {
                        $is_error_image = 1;
                        $this->Session->setFlash(__("Uploaded Signature Image should be of " . implode(", ", $allowed_extension) . " type only"), 'error');
                    } else {
                        $is_error_image = 0;
                        $product_pic = time() . "_Cashier." . $extension;
                        if (move_uploaded_file($this->request->data['Cook']['image']['tmp_name'], COOK_UPLOAD_PATH . $product_pic)) {
                            $this->resize($product_pic, 60, COOK_UPLOAD_PATH);
                            $this->request->data['Cook']['image'] = $product_pic;
                            unlink(COOK_UPLOAD_PATH . $product_pic);
                        }
                    }
                } else {
                    $this->request->data['Cook']['image'] = @$result_data['Cook']['image'];
                }

                if(isset($this->request->data['Cook']['password'])) {
                    $this->request->data['Cook']['password'] = Security::hash($this->request->data['Cook']['password'], 'md5');
                }
                if ($this->Cook->save($this->request->data, $validate = false)) {

                    if('' == $id){
                        $this->Session->setFlash('Cook has been added successfully', 'success');
                    }else{
                        $this->Session->setFlash('Cook has been updated successfully', 'success');
                    }

                    $this->redirect(array('plugin' => false, 'controller' => 'cooks', 'action' => 'index', 'admin' => true));
                }
            }
        }

        if('' != $id){
            $id = base64_decode($id);
            $customer_data = $this->Cook->find('first', array('conditions' => array('Cook.id' => $id)));
            if(empty($customer_data)){
                $this->Session->setFlash('Invalid Request', 'error');
                $this->redirect(array('plugin' => false, 'controller' => 'cooks', 'action' => 'index', 'admin' => true));
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

        $this->checkAccess('Cook', 'can_edit');
        $id = base64_decode($id);

        $is_valid = true;
        $name = $email = '';
        if('' == $id || '' == $status){
            $is_valid = false;
        }else{
            $check_user_exists = $this->Cook->Find('first', array('fields' => array('Cook.firstname', 'Cook.lastname', 'Cook.email'), 'conditions' => array('Cook.id' => $id)));
            if (empty($check_user_exists)) {
                $is_valid = false;
            }else{
                $name = ucfirst($check_user_exists['Cook']['firstname']) . ' ' . ucfirst($check_user_exists['Cook']['lastname']);
                $email = $check_user_exists['Cook']['email'];
            }
        }

        if($is_valid) {

            $this->Cook->updateAll(array('Cook.status' => "'" . $status . "'"), array('Cook.id' => $id));

            if('A' == $status){
                $viewVars = array('name' => $name, 'type' => 'active');
                $subject = 'POS: Profile Activation';
            }else{
                $viewVars = array('name' => $name, 'type' => 'inactive');
                $subject = 'POS: Profile Deactivated';
            }

            $this->sendMail($email, $subject, 'status_update', 'default', $viewVars);
            
            $this->Session->setFlash('Cook status has been changed successfully', 'success');
            $this->redirect(Router::url( $this->referer(), true ));

        }else{
            $this->Session->setFlash('Invalid Request', 'error');
            $this->redirect(array('plugin' => false, 'controller' => 'cooks', 'action' => 'index', 'admin' => true));
        }
    }

    /**
     * Delete the cashier
     * @param string $id
     * @return null
     */
    public function admin_delete($id = '') {

        $this->checkAccess('Cook', 'can_delete');
        $id = base64_decode($id);
        $this->Cook->delete($id);

        $this->Session->setFlash('Cook has been deleted successfully', 'success');
        $this->redirect(array('plugin' => false, 'controller' => 'cooks', 'action' => 'index', 'admin' => true));

    }

    /**
     * Listing of cooks whom age proof document is pending for approval
     * @return mixed
     */
    public function admin_pending_approvals(){
        $this->checkAccess('Cook', 'can_view');
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
            'conditions' => array('Cook.is_verified' => 'N'),
            'fields' => array(
                'Cook.id', 'Cook.firstname', 'Cook.lastname', 'Cook.email', 'Cook.mobile_no'
            ),
            'order' => array('Cook.created' => 'DESC')
        );
        if('all' == $limit){
            $customer_list = $this->Cook->find('all', $query);
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

        $this->checkAccess('Cook', 'can_edit');
        $id = base64_decode($id);

        $is_valid = true;
        $name = $email = '';
        if('' == $id){
            $is_valid = false;
        }else{
            $check_user_exists = $this->Cook->Find('first', array('fields' => array('Cook.firstname', 'Cook.lastname', 'Cook.email'), 'conditions' => array('Cook.id' => $id)));
            if (empty($check_user_exists)) {
                $is_valid = false;
            }else{
                $name = ucfirst($check_user_exists['Cook']['firstname']) . ' ' . ucfirst($check_user_exists['Cook']['lastname']);
                $email = $check_user_exists['Cook']['email'];
            }
        }

        if($is_valid) {
            $this->Cook->updateAll(array('Cook.is_verified' => "'Y'"), array('Cook.id' => $id));

            $viewVars = array('name' => $name, 'type' => 'approve');
            $this->sendMail($email, 'POS: Profile Approve', 'status_update', 'default', $viewVars);

            $this->Session->setFlash('Cook has been approved successfully', 'success');
            $this->redirect(array('plugin' => false, 'controller' => 'cooks', 'action' => 'pending_approvals', 'admin' => true));
        }else{
            $this->Session->setFlash('Invalid Request', 'error');
            $this->redirect(array('plugin' => false, 'controller' => 'cooks', 'action' => 'pending_approvals', 'admin' => true));
        }
        
    }

    /**
     * Change the password of the cashier by the admin
     * @param string $id
     * @return null
     */
    function admin_change_password($id = '') {

        $this->checkAccess('Cook', 'can_edit');
        $this->layout = 'admin';

        $id = base64_decode($id);
        $is_valid = true;
        if('' == $id){
            $is_valid = false;
        }else{
            $user_data = $this->Cook->Find('first', array(
                'fields' => array('Cook.firstname', 'Cook.lastname'),
                'conditions' => array('Cook.id' => $id), 'limit' => 1
            ));

            $this->set(compact('id', 'user_data'));
            if (empty($user_data)) {
                $is_valid = false;
            }
        }

        if(!$is_valid) {
            $this->Session->setFlash('Invalid Request', 'error');
            $this->redirect(array('plugin' => false, 'controller' => 'cooks', 'action' => 'index', 'admin' => true));
        }

        if (!empty($this->request->data)) {

            $this->Cook->set($this->request->data);

            if ($this->Cook->validates()) {

                $new_password = Security::hash($this->request->data['Cook']['password'], 'md5');

                $this->Cook->updateAll(array('Cook.password' => "'" . $new_password . "'"), array('Cook.id' => $id));
                $this->Session->setFlash('Password has been updated successfully', 'success');
                $this->redirect(array('plugin' => false, 'controller' => 'cooks', 'action' => 'index', 'admin' => true));
            }
        }
    }

}
