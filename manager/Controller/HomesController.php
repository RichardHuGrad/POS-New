<?php

/**
 * Class HomesController
 */
class HomesController extends AppController {

    public $components = array('Paginator');

    /**
     * beforeFilter
     * @return null
     */
    public function beforeFilter() {

	parent::beforeFilter();
	$this->Auth->allow('index');
    }

    /**
     * Website home page
     * @return mixed
     */
    public function index() {
	$this->layout = false;
	if ($this->request->is('post')) {
	    if (isset($this->request->data['Admin']['username']) && isset($this->request->data['Admin']['password'])) {
		$username = $this->request->data['Admin']['username'];
		$password = Security::hash($this->data['Admin']['password'], 'md5', false);
		$user = $this->Admin->find('first', array(
		    'conditions' => array(
			'Admin.email' => $username, 'Admin.password' => $password, 'Admin.status' => 'A'
		    )
		));
		if (!empty($user)) {
		    $this->Auth->login($user['Admin']);
		}
		if ($this->Auth->login()) {
		    $this->redirect($this->Auth->loginRedirect);
		} else {
		    $this->Session->setFlash('Invalid Username OR Password.', 'error');
		}
	    }
	}

	if ($this->Auth->loggedIn() || $this->Auth->login()) {
	    return $this->redirect(array('controller' => 'admins', 'action' => 'dashboard', 'admin' => true));
	}
    }

}
