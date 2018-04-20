<?php

/**
 * Class CousinesController
 */
class CousinesController extends AppController {

    public $uses = array('Cousine', 'CousineLocal', 'Language','Extrascategory');
    public $components = array('Session', 'Paginator');

    /**
     * beforeFilter
     * @return null
     */
    public function beforeFilter() {
        parent::beforeFilter();
        $this->set('tab_open', 'cousines');
    }

    /**
     * admin_index For listing of cousines
     * @return mixed
     */
    public function admin_index(){

        $this->layout = LAYOUT_ADMIN;
        $limit = DEFAULT_PAGE_SIZE;
        $order = 'eng_name ASC';
        $conditions = array();

        if (!empty($this->request->data)) {
        	if (isset($this->request->data['get_web'])) {
        		$this->loadModel('Api');
        		if (($cousines = $this->Api->get_cousines()) && ($cousines['status'] == 'OK')) {
        			if ($error = $this->Cousine->sync_cousines($cousines['data'])) {
        				$this->Session->setFlash($error, 'error');
        			} else {
        				$this->Session->setFlash("Cousine Synchronized", 'success');
        			}
        		}
        	}
        	 
            if(isset($this->request->data['Cousine']) && !empty($this->request->data['Cousine'])) {
                $search_data = $this->request->data['Cousine'];
                $this->Session->write('cousine_search', $search_data);
            }

            if(isset($this->request->data['PageSize']['records_per_page']) && !empty($this->request->data['PageSize']['records_per_page'])) {
                $this->Session->write('page_size', $this->request->data['PageSize']['records_per_page']);
            }
        }

        if($this->Session->check('page_size')){
            $limit = $this->Session->read('page_size');
        }

        if($this->Session->check('cousine_search')){
            $search = $this->Session->read('cousine_search');

            if(!empty($search['search'])){
                $conditions['or'] = array(
                    'eng_name LIKE'=>'%' . $search['search'] . '%',
                    'zh_name LIKE'=>'%' . $search['search'] . '%',
                    );
            }

            if(!empty($search['status'])){
                $conditions['Cousine.status'] = $search['status'];
            }
            if(!empty($search['category_id'])){
                $conditions['Cousine.category_id'] = $search['category_id'];
            }

        }
        $is_super_admin = $this->Session->read('Admin.is_super_admin');
        if('Y' <> $is_super_admin){
            $conditions['Cousine.restaurant_id'] = $this->Session->read('Admin.id');            
        }

        $query = array(
            'conditions' => $conditions,
            'order' => $order
        );
        $this->Cousine->virtualFields['eng_name'] = "Select name from cousine_locals where cousine_locals.parent_id = Cousine.id and lang_code = 'en'";
        $this->Cousine->virtualFields['zh_name'] = "Select name from cousine_locals where cousine_locals.parent_id = Cousine.id and lang_code = 'zh'";
        $this->Cousine->virtualFields['category_en_name'] = "Select name from category_locales where category_locales.category_id = Cousine.category_id and lang_code = 'en'";
        $this->Cousine->virtualFields['category_zh_name'] = "Select name from category_locales where category_locales.category_id = Cousine.category_id and lang_code = 'zh'";
        $this->Cousine->virtualFields['no_of_orders'] = "Select count(order_items.item_id) from order_items where order_items.item_id = Cousine.id";

        if('all' == $limit){
            $cousines = $this->Cousine->find('all', $query);
        }else{
            $query['limit'] = $limit;
            $this->paginate = $query;
            $cousines = $this->paginate('Cousine');
        }
        $languages = $this->Language->find('list', array('fields' => array('lang_code', 'language')));

        $this->loadModel('CategoryLocale');
        $categories = $this->CategoryLocale->find('list',
            array(
                'fields' => array('CategoryLocale.category_id', 'CategoryLocale.name'),
                'conditions' => array('CategoryLocale.lang_code' => 'en'),
                'order' => array('CategoryLocale.name' => 'ASC')
            )
        );
        
        $this->loadModel('Admin');
        $has_web = $this->Admin->has_web();
        
        $this->set(compact('cousines', 'limit', 'order', 'languages', 'categories', 'has_web'));
    }

    /**
     * To add or edit category
     * @param string $id
     * @return mixed
     */
    function admin_add_edit($id = '') {

        $this->layout = LAYOUT_ADMIN;
        $id = base64_decode($id);
        $languages = $this->Language->find('list', array('fields' => array('lang_code', 'language'), 'conditions' => array('status' => 'A')));

        if($id) {
            $result_data = $this->Cousine->find('first', array('conditions' => array('Cousine.id' => $id)));
            if(empty($result_data)){
                $this->Session->setFlash('Invalid Request !', 'error');
                $this->redirect(array('plugin' => false, 'controller' => 'cousines', 'action' => 'index', 'admin' => true));
            }
        }

        //Modified by Yishou Liao @ Dec 13 2016
        $comb_detail_tmp = $this->Extrascategory->query("SELECT * FROM extrascategories WHERE status='A' AND extras_num>0");
        $option_comb = array('0' => 'No comb');
        foreach ($comb_detail_tmp as $comb) {
            $option_comb[$comb['extrascategories']['id']] = $comb['extrascategories']['name'] . '(' . $comb['extrascategories']['name_zh'] . ')';
        }
        //End @ Dec 13 2016
        
        $this->loadModel('CategoryLocale');
        $categories = $this->CategoryLocale->find('list',
            array(
                'fields' => array('CategoryLocale.category_id', 'CategoryLocale.name'),
                'conditions' => array('CategoryLocale.lang_code' => 'en'),
                'order' => array('CategoryLocale.name' => 'ASC')
            )
        );
        
        $this->loadModel('Admin');
        $restaurants = $this->Admin->find('list',
            array('fields' => array('Admin.id', 'Admin.restaurant_name'), 'conditions' => array('Admin.status' => 'A', 'Admin.is_super_admin' => 'N'), 'order' => array('Admin.firstname' => 'ASC')));


        $this->loadModel('Cashier');

        $is_super_admin = $this->Session->read('Admin.is_super_admin');
        if('Y' <> $is_super_admin){
            $cashiers = $this->Cashier->find('list',
            array('fields' => array('Cashier.id', "Cashier.firstname"), 'conditions' => array('Cashier.status' => 'A', 'Cashier.restaurant_id'=>$this->Session->read('Admin.id')), 'order' => array('Cashier.firstname' => 'ASC')));
           
        } else {
            $cashiers = $this->Cashier->find('list',
            array('fields' => array('Cashier.id', "Cashier.firstname"), 'conditions' => array('Cashier.status' => 'A', 'Cashier.restaurant_id'=>@$result_data['Cousine']['restaurant_id']), 'order' => array('Cashier.firstname' => 'ASC')));

        }
        
        $remote_id = @$result_data['Cousine']['remote_id'];
        if (!empty($this->request->data)) {

            $this->Cousine->set($this->request->data);

            ###### custom validation start for CousineLocal name ########
            if('' != $id){
                $conditions = array('CousineLocal.parent_id !=' => $id);
            }
            foreach ($this->data['CousineLocal'] as $lang_code => $val){
                if('' == $val['name']){
                    $this->CousineLocal->validationErrors[$lang_code]['name'][] = 'Please Enter Cousine Name';
                }else{
                    $conditions['CousineLocal.name'] = $val['name'];
                    $check_unique = $this->CousineLocal->find('count', array('conditions' => $conditions, 'limit' => 1));
                    if($check_unique > 0){
                        $this->CousineLocal->validationErrors[$lang_code]['name'][] = 'Cousine Name Already Exists';
                    }
                }
            }
            ###### custom validation end for CousineLocal name ########

            if ($this->Cousine->validates() && $this->CousineLocal->validates()) {
                
                $is_error_image = 0;
                if (isset($this->request->data['Cousine']['image']['name']) && $this->request->data['Cousine']['image']['name'] != "") {

                    @unlink(COUSINE_UPLOAD_PATH.'thumbnail/' . @$result_data['Cousine']['image']);
                    $is_image_uploaded = 1;
                    $allowed_extension = array('jpg', 'jpeg', 'png', 'gif');
                    $extension = pathinfo($this->request->data['Cousine']['image']['name'], PATHINFO_EXTENSION);
                    $extension = strtolower($extension);
                    if (!in_array($extension, $allowed_extension)) {
                        $is_error_image = 1;
                        $this->Session->setFlash(__("Uploaded Signature Image should be of " . implode(", ", $allowed_extension) . " type only"), 'error');
                    } else {
                        $is_error_image = 0;
                        $product_pic = time() . "_cousine." . $extension;
                        if (move_uploaded_file($this->request->data['Cousine']['image']['tmp_name'], COUSINE_UPLOAD_PATH . $product_pic)) {
                            $this->resize($product_pic, 400, COUSINE_UPLOAD_PATH);
                            $this->request->data['Cousine']['image'] = $product_pic;
                            unlink(COUSINE_UPLOAD_PATH . $product_pic);
                        }
                    }
                } else {
                    $this->request->data['Cousine']['image'] = @$result_data['Cousine']['image'];
                }

                if ($this->Cousine->save($this->request->data, $validate = false)) {

                    $last_id = $this->Cousine->id;

                    foreach ($this->data['CousineLocal'] as $lang_code => $val){

                        $locale_data['CousineLocal'] = array(
                            'id' => $val['id'],
                            'parent_id' => $last_id,
                            'name' => $val['name'],
                            'lang_code' => $lang_code
                        );
                        $this->CousineLocal->save($locale_data, $validate = false);
                    }

                    if('' == $id){
                        $this->Session->setFlash('Cousine has been added successfully', 'success');
                    }else{
                        $this->Session->setFlash('Cousine has been updated successfully', 'success');
                    }
                    $this->redirect(array('plugin' => false, 'controller' => 'cousines', 'action' => 'index', 'admin' => true));
                }
            }
            $new_data = $this->Cousine->find('first', array('conditions' => array('Cousine.id' => $id)));
            $remote_id = $new_data['Cousine']['remote_id'];
        }

        if('' != $id){

           if (empty($this->request->data)) {
                $tmp = array();
                foreach ($result_data['CousineLocal'] as $d){
                    $tmp['CousineLocal'][$d['lang_code']] = array(
                        'name' => $d['name'],
                        'id' => $d['id']
                    );
                }
                $result_data = array_merge($result_data, $tmp);
                $this->request->data = $result_data;
            }

        }
        $this->set(compact('id', 'languages', 'categories', 'restaurants', 'cashiers','option_comb','remote_id'));
    }

    /**
     * Change the status of the category
     * @param string $id
     * @param string $status
     * @return null
     */
    public function admin_status($id = '', $status = '') {

        $id = base64_decode($id);

        $is_valid = true;
        if('' == $id || '' == $status){
            $is_valid = false;
        }else{
            $check_user_exists = $this->Cousine->Find('count', array('conditions' => array('Cousine.id' => $id), 'limit' => 1));
            if (0 == $check_user_exists) {
                $is_valid = false;
            }
        }

        if(!$is_valid) {
            $this->Session->setFlash('Invalid Request !', 'error');
            $this->redirect(array('plugin' => false, 'controller' => 'cousines', 'action' => 'index', 'admin' => true));
        }

        $this->Cousine->updateAll(array('Cousine.status' => "'" . $status . "'"), array('Cousine.id' => $id));

        $this->Session->setFlash('Cousine status has been changed successfully', 'success');
        $this->redirect(Router::url( $this->referer(), true ));
    }

    /**
     * Delete the category
     * @param string $id
     * @return null
     */
    public function admin_delete($id = '') {

        $id = base64_decode($id);

        // get cashier image details
        $details = $this->Cousine->find('first', array('conditions' => array('Cousine.id' => $id), 'fields'=>'Cousine.image'));
        if(!empty($details) and @$details['Cousine']['image']) {
            // unlink image
            unlink(COUSINE_UPLOAD_PATH ."thumbnail/". $details['Cousine']['image']);
        }

        $this->Cousine->delete($id);

        $this->Session->setFlash('Cousine has been deleted successfully', 'success');
        $this->redirect(array('plugin' => false, 'controller' => 'cousines', 'action' => 'index', 'admin' => true));

    }

    /**
     * Delete the cousine image
     * @param string $id
     * @return null
     */
    public function admin_deleteimage() {

        $id = $this->params['named']['id'];
        $image = $this->params['named']['image'];

        unlink(COUSINE_UPLOAD_PATH.'thumbnail/'.$image);
        $id = base64_decode($id);
        $this->Cousine->updateAll(array('Cousine.image' => "''"), array('Cousine.id' => $id));

        $this->Session->setFlash('Cousine image has been deleted successfully', 'success');
        $this->redirect(array('plugin' => false, 'controller' => 'cousines', 'action' => 'add_edit', 'admin' => true, $this->params['named']['id']));

    }





    public function admin_get_cashiers() {
        $this->autoRender = false;
        $this->autoLayout = false;
        if ($this->request->is('ajax')) {
            $locationid = $this->request->data['locationid'];
            $this->loadModel('Cashier');
            $records = $this->Cashier->find('all', array(
            'conditions' => array(
                'Cashier.restaurant_id' => $locationid
            ),
            'fields' => array(
                'Cashier.id', 'Cashier.firstname', 'Cashier.lastname'
            )
            ));
            $output = "<option value=''>Select Cashier</option>";
            $counter = 0;
            foreach ($records as $key => $index) {
            $output.="<option value='" . $index['Cashier']['id'] . "'>" . $index['Cashier']['firstname'] ." ".$index['Cashier']['lastname'] . "</option>";
            }
            echo $output;
        }
    }

}
