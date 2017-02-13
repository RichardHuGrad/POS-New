<?php
App::uses('AppController', 'Controller');
/**
 * CategoryLocales Controller
 *
 * @property CategoryLocale $CategoryLocale
 * @property PaginatorComponent $Paginator
 */
class CategoryLocalesController extends AppController {

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
		$this->CategoryLocale->recursive = 0;
		$this->set('categoryLocales', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->CategoryLocale->exists($id)) {
			throw new NotFoundException(__('Invalid category locale'));
		}
		$options = array('conditions' => array('CategoryLocale.' . $this->CategoryLocale->primaryKey => $id));
		$this->set('categoryLocale', $this->CategoryLocale->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->CategoryLocale->create();
			if ($this->CategoryLocale->save($this->request->data)) {
				$this->Session->setFlash(__('The category locale has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The category locale could not be saved. Please, try again.'));
			}
		}
		$categories = $this->CategoryLocale->Category->find('list');
		$this->set(compact('categories'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->CategoryLocale->exists($id)) {
			throw new NotFoundException(__('Invalid category locale'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->CategoryLocale->save($this->request->data)) {
				$this->Session->setFlash(__('The category locale has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The category locale could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('CategoryLocale.' . $this->CategoryLocale->primaryKey => $id));
			$this->request->data = $this->CategoryLocale->find('first', $options);
		}
		$categories = $this->CategoryLocale->Category->find('list');
		$this->set(compact('categories'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->CategoryLocale->id = $id;
		if (!$this->CategoryLocale->exists()) {
			throw new NotFoundException(__('Invalid category locale'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->CategoryLocale->delete()) {
			$this->Session->setFlash(__('The category locale has been deleted.'));
		} else {
			$this->Session->setFlash(__('The category locale could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}}
