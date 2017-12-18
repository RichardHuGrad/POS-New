<?php

/**
 * Class CashiersController
 */
class CashiersController extends AppController {

    public $uses = array('Cashier');
    public $components = array('Session', 'Paginator');

    /**
     * beforeFilter
     * @return null
     */
    public function beforeFilter() {
        parent::beforeFilter();
         $this->set('tab_open', 'cashiers');
    }

    /**
     * admin_index For listing of cashiers
     * @return mixed
     */
    public function admin_index() {

        $this->checkAccess('Cashier', 'can_view');

        $this->layout = 'admin';
        $limit = DEFAULT_PAGE_SIZE;
        $order = 'Cashier.created DESC';
        $conditions = array();

        if (!empty($this->request->data)) {

            if(isset($this->request->data['Cashier']) && !empty($this->request->data['Cashier'])) {
                $search_data = $this->request->data['Cashier'];
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
                    'Admin.restaurant_name LIKE' => '%' . $search['search'] . '%',
                    'Cashier.firstname LIKE' => '%' . $search['search'] . '%',
                    'Cashier.lastname LIKE' => '%' . $search['search'] . '%',
                    'Cashier.email LIKE' => '%' . $search['search'] . '%',
                    'Cashier.mobile_no LIKE' => '%' . $search['search'] . '%',
                );
            }

            if(!empty($search['status'])){
                $conditions['Cashier.status'] = $search['status'];
            }
            if(!empty($search['is_verified'])){
                $conditions['Cashier.is_verified'] = $search['is_verified'];
            }
            if(!empty($search['registered_from'])){
                $conditions['date(Cashier.created) >='] = $search['registered_from'];
            }
            if(!empty($search['registered_till'])){
                $conditions['date(Cashier.created) <='] = $search['registered_till'];
            }

        }
        $is_super_admin = $this->Session->read('Admin.is_super_admin');
        if('Y' <> $is_super_admin){
            $conditions['Cashier.restaurant_id'] = $this->Session->read('Admin.id');            
        }

        $this->Cashier->virtualFields['no_of_orders'] = "Select count(orders.id) from orders where orders.counter_id = Cashier.id";

        $query = array(
            'conditions' => $conditions,
            'fields' => array(
                'Admin.restaurant_name', 'Cashier.id', 'Cashier.userid', 'Cashier.firstname', 'Cashier.lastname', 'Cashier.email', 'Cashier.mobile_no', 'Cashier.status', 'Cashier.position','Cashier.is_verified', 'Cashier.created', 'no_of_orders'
            ),
            'order' => 'Cashier.created DESC'
        );
        if('all' == $limit){
            $customer_list = $this->Cashier->find('all', $query);
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
            $this->checkAccess('Cashier', 'can_add');
        }
        else{
            $this->checkAccess('Cashier', 'can_edit');
        }

        $this->layout = 'admin';

        if (!empty($this->request->data)) {
            $this->Cashier->set($this->request->data);
            if ($this->Cashier->validates()) {

                 $is_error_image = 0;
                if (isset($this->request->data['Cashier']['image']['name']) && $this->request->data['Cashier']['image']['name'] != "") {

                    @unlink(CASHIER_UPLOAD_PATH.'thumbnail/' . @$result_data['Cashier']['image']);
                    $is_image_uploaded = 1;
                    $allowed_extension = array('jpg', 'jpeg', 'png', 'gif');
                    $extension = pathinfo($this->request->data['Cashier']['image']['name'], PATHINFO_EXTENSION);
                    $extension = strtolower($extension);
                    if (!in_array($extension, $allowed_extension)) {
                        $is_error_image = 1;
                        $this->Session->setFlash(__("Uploaded Signature Image should be of " . implode(", ", $allowed_extension) . " type only"), 'error');
                    } else {
                        $is_error_image = 0;
                        $product_pic = time() . "_Cashier." . $extension;
                        if (move_uploaded_file($this->request->data['Cashier']['image']['tmp_name'], CASHIER_UPLOAD_PATH . $product_pic)) {
                            $this->resize($product_pic, 60, CASHIER_UPLOAD_PATH);
                            $this->request->data['Cashier']['image'] = $product_pic;
                            unlink(CASHIER_UPLOAD_PATH . $product_pic);
                        }
                    }
                } else {
                    $this->request->data['Cashier']['image'] = @$result_data['Cashier']['image'];
                }

                if(isset($this->request->data['Cashier']['password'])) {
                    $this->request->data['Cashier']['password'] = Security::hash($this->request->data['Cashier']['password'], 'md5');
                }
                if ($this->Cashier->save($this->request->data, $validate = false)) {

                    if('' == $id){
                        $this->Session->setFlash('Cashier has been added successfully', 'success');
                    }else{
                        $this->Session->setFlash('Cashier has been updated successfully', 'success');
                    }

                    $this->redirect(array('plugin' => false, 'controller' => 'cashiers', 'action' => 'index', 'admin' => true));
                }
            }
        }

        if('' != $id){
            $id = base64_decode($id);
            $customer_data = $this->Cashier->find('first', array('conditions' => array('Cashier.id' => $id)));
            if(empty($customer_data)){
                $this->Session->setFlash('Invalid Request', 'error');
                $this->redirect(array('plugin' => false, 'controller' => 'cashiers', 'action' => 'index', 'admin' => true));
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

        $this->checkAccess('Cashier', 'can_edit');
        $id = base64_decode($id);

        $is_valid = true;
        $name = $email = '';
        if('' == $id || '' == $status){
            $is_valid = false;
        }else{
            $check_user_exists = $this->Cashier->Find('first', array('fields' => array('Cashier.firstname', 'Cashier.lastname', 'Cashier.email'), 'conditions' => array('Cashier.id' => $id)));
            if (empty($check_user_exists)) {
                $is_valid = false;
            }else{
                $name = ucfirst($check_user_exists['Cashier']['firstname']) . ' ' . ucfirst($check_user_exists['Cashier']['lastname']);
                $email = $check_user_exists['Cashier']['email'];
            }
        }

        if($is_valid) {

            $this->Cashier->updateAll(array('Cashier.status' => "'" . $status . "'"), array('Cashier.id' => $id));

            if('A' == $status){
                $viewVars = array('name' => $name, 'type' => 'active');
                $subject = 'POS: Profile Activation';
            }else{
                $viewVars = array('name' => $name, 'type' => 'inactive');
                $subject = 'POS: Profile Deactivated';
            }

            $this->sendMail($email, $subject, 'status_update', 'default', $viewVars);
            
            $this->Session->setFlash('Cashier status has been changed successfully', 'success');
            $this->redirect(Router::url( $this->referer(), true ));

        }else{
            $this->Session->setFlash('Invalid Request', 'error');
            $this->redirect(array('plugin' => false, 'controller' => 'cashiers', 'action' => 'index', 'admin' => true));
        }
    }

    /**
     * Delete the cashier
     * @param string $id
     * @return null
     */
    public function admin_delete($id = '') {

        $id = base64_decode($id);

        // get cashier image details
        $details = $this->Cashier->find('first', array('conditions' => array('Cashier.id' => $id), 'fields'=>'Cashier.image'));
        if(!empty($details) and @$details['Cashier']['image']) {
            // unlink image
            unlink(CASHIER_UPLOAD_PATH ."thumbnail/". $details['Cashier']['image']);
        }

        $this->Cashier->delete($id);

        $this->Session->setFlash('Cashier has been deleted successfully', 'success');
        $this->redirect(array('plugin' => false, 'controller' => 'cashiers', 'action' => 'index', 'admin' => true));

    }

    /**
     * Listing of cashiers whom age proof document is pending for approval
     * @return mixed
     */
    public function admin_pending_approvals(){
        $this->checkAccess('Cashier', 'can_view');
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
            'conditions' => array('Cashier.is_verified' => 'N'),
            'fields' => array(
                'Cashier.id', 'Cashier.firstname', 'Cashier.lastname', 'Cashier.email', 'Cashier.mobile_no'
            ),
            'order' => array('Cashier.created' => 'DESC')
        );
        if('all' == $limit){
            $customer_list = $this->Cashier->find('all', $query);
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

        $this->checkAccess('Cashier', 'can_edit');
        $id = base64_decode($id);

        $is_valid = true;
        $name = $email = '';
        if('' == $id){
            $is_valid = false;
        }else{
            $check_user_exists = $this->Cashier->Find('first', array('fields' => array('Cashier.firstname', 'Cashier.lastname', 'Cashier.email'), 'conditions' => array('Cashier.id' => $id)));
            if (empty($check_user_exists)) {
                $is_valid = false;
            }else{
                $name = ucfirst($check_user_exists['Cashier']['firstname']) . ' ' . ucfirst($check_user_exists['Cashier']['lastname']);
                $email = $check_user_exists['Cashier']['email'];
            }
        }

        if($is_valid) {
            $this->Cashier->updateAll(array('Cashier.is_verified' => "'Y'"), array('Cashier.id' => $id));

            $viewVars = array('name' => $name, 'type' => 'approve');
            $this->sendMail($email, 'POS: Profile Approve', 'status_update', 'default', $viewVars);

            $this->Session->setFlash('Cashier has been approved successfully', 'success');
            $this->redirect(array('plugin' => false, 'controller' => 'cashiers', 'action' => 'pending_approvals', 'admin' => true));
        }else{
            $this->Session->setFlash('Invalid Request', 'error');
            $this->redirect(array('plugin' => false, 'controller' => 'cashiers', 'action' => 'pending_approvals', 'admin' => true));
        }
        
    }

    /**
     * Change the password of the cashier by the admin
     * @param string $id
     * @return null
     */
    function admin_change_password($id = '') {

        $this->checkAccess('Cashier', 'can_edit');
        $this->layout = 'admin';

        $id = base64_decode($id);
        $is_valid = true;
        if('' == $id){
            $is_valid = false;
        }else{
            $user_data = $this->Cashier->Find('first', array(
                'fields' => array('Cashier.firstname', 'Cashier.lastname'),
                'conditions' => array('Cashier.id' => $id), 'limit' => 1
            ));

            $this->set(compact('id', 'user_data'));
            if (empty($user_data)) {
                $is_valid = false;
            }
        }

        if(!$is_valid) {
            $this->Session->setFlash('Invalid Request', 'error');
            $this->redirect(array('plugin' => false, 'controller' => 'cashiers', 'action' => 'index', 'admin' => true));
        }

        if (!empty($this->request->data)) {

            $this->Cashier->set($this->request->data);

            if ($this->Cashier->validates()) {

                $new_password = Security::hash($this->request->data['Cashier']['password'], 'md5');

                $this->Cashier->updateAll(array('Cashier.password' => "'" . $new_password . "'"), array('Cashier.id' => $id));
                $this->Session->setFlash('Password has been updated successfully', 'success');
                $this->redirect(array('plugin' => false, 'controller' => 'cashiers', 'action' => 'index', 'admin' => true));
            }
        }
    }

}
