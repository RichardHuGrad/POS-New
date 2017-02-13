<?php
App::uses('AppController', 'Controller');
/**
 * CousineLocales Controller
 *
 * @property CousineLocale $CousineLocale
 * @property PaginatorComponent $Paginator
 */
class CousineLocalesController extends AppController {

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
		$this->CousineLocale->recursive = 0;
		$this->set('cousineLocales', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->CousineLocale->exists($id)) {
			throw new NotFoundException(__('Invalid cousine locale'));
		}
		$options = array('conditions' => array('CousineLocale.' . $this->CousineLocale->primaryKey => $id));
		$this->set('cousineLocale', $this->CousineLocale->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->CousineLocale->create();
			if ($this->CousineLocale->save($this->request->data)) {
				$this->Session->setFlash(__('The cousine locale has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The cousine locale could not be saved. Please, try again.'));
			}
		}
		$cousines = $this->CousineLocale->Cousine->find('list');
		$this->set(compact('cousines'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->CousineLocale->exists($id)) {
			throw new NotFoundException(__('Invalid cousine locale'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->CousineLocale->save($this->request->data)) {
				$this->Session->setFlash(__('The cousine locale has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The cousine locale could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('CousineLocale.' . $this->CousineLocale->primaryKey => $id));
			$this->request->data = $this->CousineLocale->find('first', $options);
		}
		$cousines = $this->CousineLocale->Cousine->find('list');
		$this->set(compact('cousines'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->CousineLocale->id = $id;
		if (!$this->CousineLocale->exists()) {
			throw new NotFoundException(__('Invalid cousine locale'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->CousineLocale->delete()) {
			$this->Session->setFlash(__('The cousine locale has been deleted.'));
		} else {
			$this->Session->setFlash(__('The cousine locale could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}}
