<?php

/**
 * Class ExtracateController
 * Auther Name: Yishou Liao
 * Modified Date: Dec 03 2016
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
        	if (isset($this->request->data['get_web'])) {
        		$this->loadModel('Api');
        		if (($extrascategories = $this->Api->get_extrascategories()) && ($extrascategories['status'] == 'OK')) { // extrascategory
        			if ( ! $this->Extrascategory->sync_extrascategories($extrascategories['data'])) {
        				$this->Session->setFlash('Extra Categories Synchronized ', 'success');
        			}
        		}
        	}
        	 
            if (isset($this->request->data['Extracategory']) && !empty($this->request->data['Extracategory'])) {
                $search_data = $this->request->data['Extracategory'];
                $this->Session->write('Extracategory_search', $search_data);
            }

            if (isset($this->request->data['PageSize']['records_per_page']) && !empty($this->request->data['PageSize']['records_per_page'])) {
                $this->Session->write('page_size', $this->request->data['PageSize']['records_per_page']);
            }
        }

        if ($this->Session->check('page_size')) {
            $limit = $this->Session->read('page_size');
        }

        if ($this->Session->check('Extracategory_search')) {
            $search = $this->Session->read('Extracategory_search');

            if (!empty($search['search'])) {
                $conditions['or'] = array(
                    'name LIKE' => '%' . $search['search'] . '%',
                    'name_zh LIKE' => '%' . $search['search'] . '%',
                );
            }

            if (!empty($search['status'])) {
                $conditions['Extrascategory.status'] = $search['status'];
            }
        }

        $query = array(
            'conditions' => $conditions,
            'order' => $order
        );

        if ('all' == $limit) {
            $extrascategory = $this->Extrascategory->find('all', $query);
        } else {
            $query['limit'] = $limit;
            $this->paginate = $query;
            $extrascategory = $this->paginate('Extrascategory');
        };

        $this->loadModel('Admin');
    	$has_web = $this->Admin->has_web();

    	$this->set(compact('extrascategory', 'limit', 'order', 'languages', 'has_web'));
    }

    /**
     * To add or edit extras category
     * @param string $id
     * @return mixed
     */
    function admin_add_edit($id = '') {
        $this->layout = LAYOUT_ADMIN;
        $id = base64_decode($id);
        $languages = $this->Language->find('list', array('fields' => array('lang_code', 'language'), 'conditions' => array('status' => 'A')));
        
        if (!empty($this->request->data)) {

            $this->Extrascategory->set($this->request->data);


            if ('' != $id) {
                $conditions = array('Extrascategory.id !=' => $id);
            }

            if ('' == $this->data['Extrascategory']['name']) {
                $this->Extrascategory->validationErrors[$lang_code]['name'][] = 'Please Enter Category Name';
            } else {
                $conditions['Extrascategory.name'] = $this->data['Extrascategory']['name'];
                $check_unique = $this->Extrascategory->find('count', array('conditions' => $conditions, 'limit' => 1));
                if ($check_unique > 0) {
                    $this->Extrascategory->validationErrors[$lang_code]['name'][] = 'Extra Category Name Already Exists';
                }
            };
            if ('' == $this->data['Extrascategory']['name_zh']) {
                $this->Extrascategory->validationErrors[$lang_code]['name_zh'][] = 'Please Enter Category Name';
            } else {
                $conditions['Extrascategory.name_zh'] = $this->data['Extrascategory']['name_zh'];
                $check_unique = $this->Extrascategory->find('count', array('conditions' => $conditions, 'limit' => 1));
                if ($check_unique > 0) {
                    $this->Extrascategory->validationErrors[$lang_code]['name_zh'][] = 'Extra Category Name Already Exists';
                }
            };

            if ($this->Extrascategory->validates()) {
                if ($this->Extrascategory->save($this->request->data, $validate = false)) {

                    $last_id = $this->Extrascategory->id;

                    if ('' == $id) {
                        $this->Session->setFlash('Category has been added successfully', 'success');
                    } else {
                        $this->Session->setFlash('Category has been updated successfully', 'success');
                    }
                    $this->redirect(array('plugin' => false, 'controller' => 'extracate', 'action' => 'index', 'admin' => true));
                }
            }
        }

        $remote_id = 0;
        if ('' != $id) {

            $result_data = $this->Extrascategory->find('first', array('conditions' => array('Extrascategory.id' => $id)));
            if (empty($result_data)) {
                $this->Session->setFlash('Invalid Request !', 'error');
                $this->redirect(array('plugin' => false, 'controller' => 'extracate', 'action' => 'index', 'admin' => true));
            }
            $remote_id = $result_data['Extrascategory']['remote_id'];

            if (empty($this->request->data)) {
                $this->request->data = $result_data;
            }
        }
        $this->set(compact('id', 'remote_id', 'languages'));
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
        if ('' == $id || '' == $status) {
            $is_valid = false;
        } else {
            $check_user_exists = $this->Extrascategory->Find('count', array('conditions' => array('Extrascategory.id' => $id), 'limit' => 1));
            if (0 == $check_user_exists) {
                $is_valid = false;
            }
        }

        if (!$is_valid) {
            $this->Session->setFlash('Invalid Request !', 'error');
            $this->redirect(array('plugin' => false, 'controller' => 'categories', 'action' => 'index', 'admin' => true));
        }

        $this->Extrascategory->updateAll(array('Extrascategory.status' => "'" . $status . "'"), array('Extrascategory.id' => $id));

        $this->Session->setFlash('Extras Category status has been changed successfully', 'success');
        $this->redirect(Router::url($this->referer(), true));
    }

    /**
     * Delete the category
     * @param string $id
     * @return null
     */
    public function admin_delete($id = '') {
        $id = base64_decode($id);
        
        //Modified by Yishou Liao @ Dec 05 2016
        $this->loadModel('Extra');
        $check_record = $this->Extra->query("SELECT * FROM `extras` WHERE extras.status = 'A' and extras.category_id = " . $id);

        if (count($check_record) > 0) {
            $this->Session->setFlash('Extra Category Name Could Not be Deleted');
        } else {
            $this->Extrascategory->delete($id);
            $this->Session->setFlash('Extras Category has been deleted successfully', 'success');
        };

        $this->redirect(array('plugin' => false, 'controller' => 'extracate', 'action' => 'index', 'admin' => true));
    }

}
