<?php
App::uses('AppController', 'Controller');
/**
 * Cousines Controller
 *
 * @property Cousine $Cousine
 * @property PaginatorComponent $Paginator
 */
class CousinesController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Cousine->recursive = 0;
		$this->set('cousines', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Cousine->exists($id)) {
			throw new NotFoundException(__('Invalid cousine'));
		}
		$options = array('conditions' => array('Cousine.' . $this->Cousine->primaryKey => $id));
		$this->set('cousine', $this->Cousine->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Cousine->create();
			if ($this->Cousine->save($this->request->data)) {
				$this->Session->setFlash(__('The cousine has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The cousine could not be saved. Please, try again.'));
			}
		}
		$restaurants = $this->Cousine->Restaurant->find('list');
		$categories = $this->Cousine->Category->find('list');
		$this->set(compact('restaurants', 'categories'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Cousine->exists($id)) {
			throw new NotFoundException(__('Invalid cousine'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Cousine->save($this->request->data)) {
				$this->Session->setFlash(__('The cousine has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The cousine could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Cousine.' . $this->Cousine->primaryKey => $id));
			$this->request->data = $this->Cousine->find('first', $options);
		}
		$restaurants = $this->Cousine->Restaurant->find('list');
		$categories = $this->Cousine->Category->find('list');
		$this->set(compact('restaurants', 'categories'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Cousine->id = $id;
		if (!$this->Cousine->exists()) {
			throw new NotFoundException(__('Invalid cousine'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Cousine->delete()) {
			$this->Session->setFlash(__('The cousine has been deleted.'));
		} else {
			$this->Session->setFlash(__('The cousine could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}}
