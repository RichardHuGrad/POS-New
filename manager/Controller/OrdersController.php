<?php

/**
 * Class OrdersController
 */
class OrdersController extends AppController {

    public $uses = array('Order');
    public $components = array('Session', 'Paginator');

    /**
     * beforeFilter
     * @return null
     */
    public function beforeFilter() {
        parent::beforeFilter();
         $this->set('tab_open', 'orders');
    }

    /**
     * admin_index For listing of orders
     * @return mixed
     */
    public function admin_index() {

        $this->checkAccess('Order', 'can_view');

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
                $conditions['Order.order_no'] = str_replace("#", "", $search['search']);
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
            'order' => $order,
            'recursive'=>-1
        );
        if('all' == $limit){
            $records = $this->Order->find('all', $query);
        }else{
            $query['limit'] = $limit;
            $this->paginate = $query;
            $records = $this->paginate();
        }
        $this->set(compact('records', 'limit', 'order', 'is_super_admin'));
    }

    /**
     * edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function admin_edit($id = null) {
        if (!$this->Order->exists($id)) {
            throw new NotFoundException(__('Invalid order'));
        }
        if ($this->request->is(array('post', 'put'))) {

            if ($this->Order->update($id,$this->request->data)) {
            	
                $this->Session->setFlash(__('The order has been saved.'));
                return $this->redirect(array('action' => 'index', 'admin' => true));
            } else {
                $this->Session->setFlash(__('The order could not be saved. Please, try again.'));
            }
                        
        } else {
            $options = array(
                'recursive' => -1,
                'conditions' => array('Order.' . $this->Order->primaryKey => $id)
            );            
            $this->request->data = $this->Order->find('first', $options);
        }
        
        $cashiers = $this->Order->Cashier->find('list', array(
            'fields' => array('Cashier.id', 'Cashier.email'),
            'conditions' => array(
               'Cashier.status' => 'A','Cashier.is_verified' => 'Y'
            ),
            'recursive' => 0
    	));
    
        $this->set(compact('cashiers'));
    }

    /**
     * To generate reorder
     * @param none
     * @return mixed
     */
    function admin_reorder() {
        $this->layout = false;
        $this->autoRender = NULL;
        
        // get max of reorder_no
        $reorder_no = $this->Order->find('first', array('fields'=>array('max(Order.reorder_no) as max_reorder_no')));
        $reorder_no = $reorder_no[0]['max_reorder_no'] + 1;
                
        // get max of reorder_no
        $hide_no = $this->Order->find('first', array('fields'=>array('max(Order.hide_no) as max_hide_no')));
        $hide_no = $hide_no[0]['max_hide_no'] + 1;

        // check whole order is hide or show
        $ids = explode(",", $this->data['Reorder']['ids']);
        if(!empty($this->data['Reorder']['display']))
            foreach($ids as $val) {
                if(in_array($val, $this->data['Reorder']['display'])) {

                    // update reorder no
                    $this->Order->query("UPDATE orders set `reorder_no` = '$reorder_no',is_hide = 'N' where id = '$val' and !reorder_no");    
                    if($this->Order->getAffectedRows())
                        $reorder_no += 1;
                } else {

                    // update reorder no
                    $this->Order->query("UPDATE orders set `hide_no` = '$hide_no',is_hide = 'Y' where id = '$val' and !hide_no"); 
                    if($this->Order->getAffectedRows())
                        $hide_no += 1; 
                }
            }
        else {
            $this->Session->setFlash('Please select at least one order.', 'error');
            $this->redirect(array('plugin' => false, 'controller' => 'orders', 'action' => 'index', 'admin' => true));
        }
        $this->Session->setFlash('Order settings successfully updated.', 'success');
        $this->redirect(array('plugin' => false, 'controller' => 'orders', 'action' => 'index', 'admin' => true));

    }

    /**
     * To view order detail
     * @param string $id
     * @return mixed
     */
    function admin_vieworder($id = '') {

        $id = base64_decode($id);
        $this->layout = 'admin';

        if (!empty($this->request->data)) {
            $this->Order->set($this->request->data);
            if ($this->Order->validates()) {

                $this->request->data['Order']['id'] = $id;
                //if ($this->Order->save($this->request->data, $validate = false)) {
                if ($this->Order->update($id,$this->request->data)) {
                    $this->Session->setFlash('Order has been updated successfully', 'success');
                    $this->redirect(array('plugin' => false, 'controller' => 'orders', 'action' => 'index', 'admin' => true));
                }
            }
        }

        $Order_detail = $this->Order->find('first', array('conditions' => array('Order.id' => $id)));
        if(empty($Order_detail)){
            $this->Session->setFlash('Invalid Request', 'error');
            $this->redirect(array('plugin' => false, 'controller' => 'orders', 'action' => 'index', 'admin' => true));
        }

        if (empty($this->request->data)) {
            $this->request->data = $Order_detail;
        }

        $this->set(compact('id', 'Order_detail'));
    }

    /**
     * Change the status of the cashier
     * @param string $id
     * @param string $status
     * @return null
     */
    public function admin_status($id = '', $status = '') {

        $this->checkAccess('Order', 'can_edit');
        $id = base64_decode($id);

        $is_valid = true;
        $name = $email = '';
        if('' == $id || '' == $status){
            $is_valid = false;
        }else{
            $check_user_exists = $this->Order->Find('first', array('fields' => array('Order.firstname', 'Order.lastname', 'Order.email'), 'conditions' => array('Order.id' => $id)));
            if (empty($check_user_exists)) {
                $is_valid = false;
            }else{
                $name = ucfirst($check_user_exists['Order']['firstname']) . ' ' . ucfirst($check_user_exists['Order']['lastname']);
                $email = $check_user_exists['Order']['email'];
            }
        }

        if($is_valid) {

            $this->Order->updateAll(array('Order.status' => "'" . $status . "'"), array('Order.id' => $id));

            if('A' == $status){
                $viewVars = array('name' => $name, 'type' => 'active');
                $subject = 'POS: Profile Activation';
            }else{
                $viewVars = array('name' => $name, 'type' => 'inactive');
                $subject = 'POS: Profile Deactivated';
            }

            $this->sendMail($email, $subject, 'status_update', 'default', $viewVars);
            
            $this->Session->setFlash('Order status has been changed successfully', 'success');
            $this->redirect(Router::url( $this->referer(), true ));

        }else{
            $this->Session->setFlash('Invalid Request', 'error');
            $this->redirect(array('plugin' => false, 'controller' => 'orders', 'action' => 'index', 'admin' => true));
        }
    }

    /**
     * Delete the cashier
     * @param string $id
     * @return null
     */
    public function admin_batch_delete(/*$id = ''*/) {

        $this->layout = false;
        $this->autoRender = NULL;

        $this->loadModel('Order');
        $this->loadModel('OrderLog');
        
        // $this->checkAccess('Order', 'can_delete');
        $order_nos = $this->data['order_nos'];

        foreach($order_nos as $order_no) {
            $order_detail = $this->Order->find('first', array(
                            'recursive' => -1,
                            'conditions' => array(
                                    'order_no' => $order_no
                                )
                        ));
            $order_id = $order_detail['Order']['id'];

            $this->OrderLog->insertLog($order_detail, 'void;');

            $this->Order->delete(array('Order.id' => $order_id), false);
        }

        $this->Session->setFlash('Order has been deleted successfully', 'success');
        
        //cannot run in ajax call
        //$this->redirect(array('plugin' => false, 'controller' => 'orders', 'action' => 'index', 'admin' => true));

    }
    

    /**
     * delete method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function admin_delete($id = null) {
        $this->loadModel('Order');
        $this->loadModel('OrderLog');

        $this->Order->id = $id;
        if (!$this->Order->exists()) {
            throw new NotFoundException(__('Invalid order'));
        }
        // $this->request->onlyAllow('post', 'delete');
        $order_detail = $this->Order->find('first', array('recursive' => -1,'conditions' => array('Order.id'=> $id)));
        // print_r($order_detail);
        $this->OrderLog->insertLog($order_detail, 'void;');
        $this->Order->delete(array('Order.id' => $id), false);

        return $this->redirect(array('action' => 'index'));
    }


    public function admin_edit_log() { //未用
        $this->layout = false;
        $this->autoRender = NULL;
        
        $this->loadModel('OrderLog');
        $this->loadModel('Order');

        $order_no = $this->data['order_no'];

        $order_detail = $this->Order->find('first', array(
                            'recursive' => -1,
                            'conditions' => array(
                                    'Order.order_no' => $order_no
                                )
                        ));
        print_r($order_detail);

        $this->OrderLog->insertLog($order_detail, 'edit');
    }


    /**
     * Listing of orders whom age proof document is pending for approval
     * @return mixed
     */
    public function admin_pending_approvals(){
        $this->checkAccess('Order', 'can_view');
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
            'conditions' => array('Order.is_verified' => 'N'),
            'fields' => array(
                'Order.id', 'Order.firstname', 'Order.lastname', 'Order.email', 'Order.mobile_no'
            ),
            'order' => array('Order.created' => 'DESC')
        );
        if('all' == $limit){
            $records = $this->Order->find('all', $query);
        }else{
            $query['limit'] = $limit;
            $this->paginate = $query;
            $records = $this->paginate();
        }
        $this->set(compact('records', 'limit'));
    }


    /**
     * Approve the selected cashier
     * @param string $id
     * @return null
     */
    public function admin_approve_customer($id = '') {

        $this->checkAccess('Order', 'can_edit');
        $id = base64_decode($id);

        $is_valid = true;
        $name = $email = '';
        if('' == $id){
            $is_valid = false;
        }else{
            $check_user_exists = $this->Order->Find('first', array('fields' => array('Order.firstname', 'Order.lastname', 'Order.email'), 'conditions' => array('Order.id' => $id)));
            if (empty($check_user_exists)) {
                $is_valid = false;
            }else{
                $name = ucfirst($check_user_exists['Order']['firstname']) . ' ' . ucfirst($check_user_exists['Order']['lastname']);
                $email = $check_user_exists['Order']['email'];
            }
        }

        if($is_valid) {
            $this->Order->updateAll(array('Order.is_verified' => "'Y'"), array('Order.id' => $id));

            $viewVars = array('name' => $name, 'type' => 'approve');
            $this->sendMail($email, 'POS: Profile Approve', 'status_update', 'default', $viewVars);

            $this->Session->setFlash('Order has been approved successfully', 'success');
            $this->redirect(array('plugin' => false, 'controller' => 'orders', 'action' => 'pending_approvals', 'admin' => true));
        }else{
            $this->Session->setFlash('Invalid Request', 'error');
            $this->redirect(array('plugin' => false, 'controller' => 'orders', 'action' => 'pending_approvals', 'admin' => true));
        }
        
    }

    /**
     * Change the password of the cashier by the admin
     * @param string $id
     * @return null
     */
    function admin_change_password($id = '') {

        $this->checkAccess('Order', 'can_edit');
        $this->layout = 'admin';

        $id = base64_decode($id);
        $is_valid = true;
        if('' == $id){
            $is_valid = false;
        }else{
            $user_data = $this->Order->Find('first', array(
                'fields' => array('Order.firstname', 'Order.lastname'),
                'conditions' => array('Order.id' => $id), 'limit' => 1
            ));

            $this->set(compact('id', 'user_data'));
            if (empty($user_data)) {
                $is_valid = false;
            }
        }

        if(!$is_valid) {
            $this->Session->setFlash('Invalid Request', 'error');
            $this->redirect(array('plugin' => false, 'controller' => 'orders', 'action' => 'index', 'admin' => true));
        }

        if (!empty($this->request->data)) {

            $this->Order->set($this->request->data);

            if ($this->Order->validates()) {

                $new_password = Security::hash($this->request->data['Order']['password'], 'md5');

                $this->Order->updateAll(array('Order.password' => "'" . $new_password . "'"), array('Order.id' => $id));
                $this->Session->setFlash('Password has been updated successfully', 'success');
                $this->redirect(array('plugin' => false, 'controller' => 'orders', 'action' => 'index', 'admin' => true));
            }
        }
    }

}
