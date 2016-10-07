<?php
/**
 * Static content controller.
 *
 * This file will render views from views/pages/
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('AppController', 'Controller');

/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class PagesController extends AppController {

/**
 * This controller does not use a model
 *
 * @var array
 */
	public $uses = array();
    public $components = array('Session', 'Paginator');

    /**
     * beforeFilter
     */
    public function beforeFilter() {
        parent::beforeFilter();
        $this->set('tab_open', 'pages');
    }


    /**
     * Listing of static pages
     * @return mixed
     */
    public function admin_index(){

        $this->checkAccess('Page', 'can_view');
        $this->layout = 'admin';
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
            'order' => array('Page.name' => 'ASC')
        );
        if('all' == $limit){
            $page_list = $this->Page->find('all', $query);
        }else{
            $query['limit'] = $limit;
            $this->paginate = $query;
            $page_list = $this->paginate();
        }
        $this->set(compact('page_list', 'limit'));

    }

    /**
     * Add or edit static page
     * @param string $id
     * @return mixed
     */
    function admin_add_edit($id = '') {

        if('' == $id){
            $this->checkAccess('Page', 'can_add');
        }
        else{
            $this->checkAccess('Page', 'can_edit');
        }
        $this->layout = 'admin';

        if (!empty($this->request->data)) {

            $this->request->data['Page']['slug'] = $this->converStringToUrl($this->request->data['Page']['name']);

            $this->Page->set($this->request->data);

            if ($this->Page->validates()) {

                if ($this->Page->save($this->request->data, $validate = false)) {

                    if('' == $id){
                        $this->Session->setFlash('Page has been added successfully', 'success');
                    }else{
                        $this->Session->setFlash('Page has been updated successfully', 'success');
                    }
                    $this->redirect(array('plugin' => false, 'controller' => 'pages', 'action' => 'index', 'admin' => true));
                }
            }
        }

        if('' != $id){
            $id = base64_decode($id);
            $page_data = $this->Page->find('first', array('conditions' => array('Page.id' => $id)));
            if(empty($page_data)){
                $this->Session->setFlash('Invalid Request', 'error');
                $this->redirect(array('plugin' => false, 'controller' => 'pages', 'action' => 'index', 'admin' => true));
            }
            if (empty($this->request->data)) {
                $this->request->data = $page_data;
            }
        }

        $this->set(compact('id'));
    }

    /**
     * Change the status of the static page
     * @param string $id
     * @param string $status
     * @return null
     */
    function admin_status($id = '', $status = '') {

        $this->checkAccess('Page', 'can_edit');
        $id = base64_decode($id);

        $is_valid = true;
        if('' == $id || '' == $status){
            $is_valid = false;
        }else{
            $check_exists = $this->Page->Find('count', array('conditions' => array('Page.id' => $id), 'limit' => 1));
            if (0 == $check_exists) {
                $is_valid = false;
            }
        }

        if(!$is_valid) {
            $this->Session->setFlash('Invalid Request', 'error');
            $this->redirect(array('plugin' => false, 'controller' => 'pages', 'action' => 'index', 'admin' => true));
        }

        $this->Page->updateAll(array('Page.status' => "'" . $status . "'"), array('Page.id' => $id));

        $this->Session->setFlash('Page status has been changed successfully', 'success');
        $this->redirect(Router::url( $this->referer(), true ));
    }

    /**
     * delete the Page from databse
     * @param string $id
     * @return null
     */
    function admin_delete($id = ''){
        $this->checkAccess('Page', 'can_delete');
        $id = base64_decode($id);
        $this->Page->delete($id);

        $this->Session->setFlash('Page has been deleted successfully', 'success');
        $this->redirect(array('plugin' => false, 'controller' => 'pages', 'action' => 'index', 'admin' => true));

    }


    
		
    
    

		
	public function display() {
		$path = func_get_args();

		$count = count($path);
		if (!$count) {
			return $this->redirect('/');
		}
		$page = $subpage = $title_for_layout = null;

		if (!empty($path[0])) {
			$page = $path[0];
		}
		if (!empty($path[1])) {
			$subpage = $path[1];
		}
		if (!empty($path[$count - 1])) {
			$title_for_layout = Inflector::humanize($path[$count - 1]);
		}
		$this->set(compact('page', 'subpage', 'title_for_layout'));

		try {
			$this->render(implode('/', $path));
		} catch (MissingViewException $e) {
			if (Configure::read('debug')) {
				throw $e;
			}
			throw new NotFoundException();
		}
	}
        
        public function index(){
			
			$this->paginate = array(
				 
				'limit' => 20,
				'order' => array(
					'Page.createdon' => 'DESC'
				)
			);

           $this->set('pages_list', $this->paginate());
			
		}
		
		 public  function add() {
          $this->layout = 'admin'; 
                 if ($this->request->is('post') || $this->request->is('put')) {
					//pr($this->request->data); exit;
					if ($this->Page->save($this->request->data,false)) {
						$this->Session->setFlash('Page has been added successfully', 'success');
						$this->redirect(array('plugin' => false, 'controller' => 'pages', 'action' => 'index'));
					  }
				
                   }
           }
		   
		public function edit() {
        $user_id = $this->params->query['id'];
        if (!$user_id || $user_id == NULL) {
            $this->Session->setFlash('Invalid request to edit Page', 'error');
            $this->redirect(array('plugin' => false, 'controller' => 'pages', 'action' => 'index'));
        } else {
            // check that user exists or not
            $check_user_exists = $this->Page->Find('count', array(
                'conditions' => array(
                    'Page.id' => $user_id
                ),
                'recursive' => -1
            ));
            if ($check_user_exists == 0) {
                $this->Session->setFlash('Page does not exists', 'error');
                $this->redirect(array('plugin' => false, 'controller' => 'pages', 'action' => 'index'));
            }
        }
              
        $users_data = $this->Page->find('first', array(
            'conditions' => array(
                'Page.id' => $user_id
            )
        ));
        $this->set('users_data', $users_data);
        if ($this->request->is('post') || $this->request->is('put')) {
           
					if ($this->Page->save($this->request->data,false)) {
						$this->Session->setFlash('Page has been added successfully', 'success');
						$this->redirect(array('plugin' => false, 'controller' => 'pages', 'action' => 'index'));
						}
				
			} else {
				$this->data = $users_data;
			}
    }
	
	
	   public function status() {
        $item_id = $this->params['named']['id'];
        $item_status = $this->params['named']['status'];
        if (!$item_id) {
            $this->Session->setFlash('Invalid Request, Page id not found', 'default', array('class' => 'error'));
            $this->redirect(array('controller' => 'pages', 'action' => 'index'));
        } else {

            // check that item exists or not
            $check_user_exists = $this->Page->Find('count', array(
                'conditions' => array(
                    'Page.id' => $item_id
                ),
                'recursive' => -1
            ));
            if ($check_user_exists == 0) {
                $this->Session->setFlash('Page does not exists', 'error');
                $this->redirect(array('plugin' => false, 'controller' => 'pages', 'action' => 'index'));
            }
        }

        // update status of template as per condition 
        $update_status = $this->Page->updateAll(array('Page.status' => "'" . $item_status . "'"), array('Page.id' => $item_id));
        $this->Session->setFlash('Page status has been changed successfully', 'success');
        $this->redirect(array('plugin' => false, 'controller' => 'pages', 'action' => 'index'));

        exit;
    }

    public function delete() {
        $item_id = $this->params->query['id'];
        if (!$item_id) {
            $this->Session->setFlash('Invalid Request, Page id not found', 'error');
            echo json_encode(array('succ' => 0, 'msg' => 'Invalid Request, Page id not found'));
            die;
        } else {

            // fetch order's of user
            $orders_list = $this->Page->find('first', array(
                'conditions' => array(
                    'Page.id' => $item_id
                ),
                'fields' => array(
                    'Page.id'
                ),
                'recursive' => -1
            ));

            if (!empty($orders_list)) {

                if ($this->Page->delete($orders_list['Page']['id'])) {
                    
                }
                $this->Session->setFlash('Page deleted successfully', 'success');
                echo json_encode(array('succ' => 1, 'msg' => 'Page deleted successfully'));
                die;
            } else {
                $this->Session->setFlash('Page couldn\'t be deleted, please try again later', 'error');
                echo json_encode(array('succ' => 0, 'msg' => 'Page couldn\'t be deleted, please try again later'));
                die;
            }
        }
        exit;
    }

}
