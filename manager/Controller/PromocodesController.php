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
                $this->Session->write('Promocode_search', $search_data);
            }

            if(isset($this->request->data['PageSize']['records_per_page']) && !empty($this->request->data['PageSize']['records_per_page'])) {
                $this->Session->write('page_size', $this->request->data['PageSize']['records_per_page']);
            }
        }

        if($this->Session->check('page_size')){
            $limit = $this->Session->read('page_size');
        }

        if($this->Session->check('Promocode_search')){
            $search = $this->Session->read('Promocode_search');

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
     * To add or edit Promocode detail
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
        	
    	      //已经存在该promocode则给出提示并返回
    	      $ret =$this->Promocode->hasAny(['code'=>$this->request->data['Promocode']['code']]);
    	      if($ret && $id==''){
    	      	$this->Session->setFlash('Promocode already exists!', 'error');
    	      }else{
        	
              $this->Promocode->set($this->request->data);
              
              if ($this->Promocode->validates()) {
              
	              $start_time =  $this->request->data['Promocode']['start_time'];
	              $end_time   =  $this->request->data['Promocode']['end_time'];
	              $this->request->data['Promocode']['start_time'] = date("H:i:s", strtotime(trim($start_time)));
	              $this->request->data['Promocode']['end_time'] = date("H:i:s", strtotime(trim($end_time)));
	              $this->request->data['Promocode']['week_days'] = implode(",", $this->request->data['Promocode']['week_days']);
              
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
        }

        if('' != $id){
            $id = base64_decode($id);
            $customer_data = $this->Promocode->find('first', array('conditions' => array('Promocode.id' => $id)));
            if(empty($customer_data)){
                $this->Session->setFlash('Invalid Request', 'error');
                $this->redirect(array('plugin' => false, 'controller' => 'promocodes', 'action' => 'index', 'admin' => true));
            }

            if (empty($this->request->data)) {
            	
	            $customer_data['Promocode']['start_time'] = date('h:i A', strtotime($customer_data['Promocode']['start_time']));
	            $customer_data['Promocode']['end_time'] = date('h:i A', strtotime($customer_data['Promocode']['end_time']));
	            $customer_data['Promocode']['week_days'] = $customer_data['Promocode']['week_days']?explode(",", $customer_data['Promocode']['week_days']):"";
	            
              $this->request->data = $customer_data;
            }
        }
        
        $this->loadModel('Admin');
        $restaurants = $this->Admin->find('list',
            array('fields' => array('Admin.id', 'Admin.restaurant_name'), 'conditions' => array('Admin.status' => 'A', 'Admin.is_super_admin' => 'N'), 'order' => array('Admin.firstname' => 'ASC')));
        
        $this->loadModel('CategoryLocale');
        $categories = $this->CategoryLocale->find('list',
            array(
                'fields' => array('CategoryLocale.category_id', 'CategoryLocale.name'),
                'conditions' => array('CategoryLocale.lang_code' => 'en'),
                'order' => array('CategoryLocale.name' => 'ASC')
            )
        );

		    $is_super_admin = $this->Session->read('Admin.is_super_admin');
		    
        if('Y' <> $is_super_admin)
        		$restaurant_id = $this->Session->read('Admin.id');
		    else
    		    $restaurant_id = @$customer_data['Promocode']['restaurant_id'];
           
           
        $this->loadModel('CousineLocal');
        $items_list = $this->CousineLocal->find('all', array(
            'conditions' => array(
                'Cousine.restaurant_id' => @$restaurant_id, 'CousineLocal.lang_code' => 'en','Cousine.category_id' => @$customer_data['Promocode']['category_id'], 
            ),
            'fields' => array(
                'CousineLocal.parent_id', 'CousineLocal.name'
            )
        )); 
        
        $arr = [];
        if(!empty($items_list)){
        	foreach ($items_list as $key => $value) {
        		# code...
        		$arr[$value['CousineLocal']['parent_id']] = $value['CousineLocal']['name'];
          }
        }
    	  
    	  $items_list = $arr;
        
        $this->set(compact('id', 'restaurants', 'categories', 'items_list'));
    }

    /**
     * Change the status of the Promocode
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
     * Delete the Promocode
     * @param string $id
     * @return null
     */
    public function admin_delete($id = '') {

        $this->checkAccess('Promocode', 'can_delete');
        $id = base64_decode($id);
        $this->Promocode->delete($id);

        $this->Session->setFlash('Promocode has been deleted successfully', 'success');
        $this->redirect(array('plugin' => false, 'controller' => 'promocodes', 'action' => 'index', 'admin' => true));

    }

    public function admin_get_item() {
        $this->autoRender = false;
        $this->autoLayout = false;
        if ($this->request->is('ajax')) {
            $categoryid = $this->request->data['categoryid'];
            $restaurant_id = $this->request->data['restaurant_id'];


	        $this->loadModel('CousineLocal');
	        $items_list = $this->CousineLocal->find('all', array(
	            'conditions' => array(
	                'Cousine.restaurant_id' => @$restaurant_id, 
	                'CousineLocal.lang_code' => 'en',
	                'Cousine.category_id' => $categoryid, 
	            ),
	            'fields' => array(
	                'CousineLocal.parent_id', 'CousineLocal.name'
	            )
	        ));
	        // pr($items_list); 
            $output = "<option value=''>Select</option>";
	        		
            if(!empty($items_list))
	            foreach ($items_list as $key => $index) {
	            	$output.="<option value='" . $index['CousineLocal']['parent_id'] . "'>" . $index['CousineLocal']['name'] . "</option>";
	            }
            echo $output;
        }
    }

}
