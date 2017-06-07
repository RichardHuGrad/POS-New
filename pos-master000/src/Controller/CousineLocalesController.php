<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * CousineLocales Controller
 *
 * @property \App\Model\Table\CousineLocalesTable $CousineLocales
 */
class CousineLocalesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Cousines']
        ];
        $cousineLocales = $this->paginate($this->CousineLocales);

        $this->set(compact('cousineLocales'));
        $this->set('_serialize', ['cousineLocales']);
    }

    /**
     * View method
     *
     * @param string|null $id Cousine Locale id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $cousineLocale = $this->CousineLocales->get($id, [
            'contain' => ['Cousines']
        ]);

        $this->set('cousineLocale', $cousineLocale);
        $this->set('_serialize', ['cousineLocale']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $cousineLocale = $this->CousineLocales->newEntity();
        if ($this->request->is('post')) {
            $cousineLocale = $this->CousineLocales->patchEntity($cousineLocale, $this->request->data);
            if ($this->CousineLocales->save($cousineLocale)) {
                $this->Flash->success(__('The cousine locale has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The cousine locale could not be saved. Please, try again.'));
        }
        $cousines = $this->CousineLocales->Cousines->find('list', ['limit' => 200]);
        $this->set(compact('cousineLocale', 'cousines'));
        $this->set('_serialize', ['cousineLocale']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Cousine Locale id.
     * @return \Cake\Network\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $cousineLocale = $this->CousineLocales->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $cousineLocale = $this->CousineLocales->patchEntity($cousineLocale, $this->request->data);
            if ($this->CousineLocales->save($cousineLocale)) {
                $this->Flash->success(__('The cousine locale has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The cousine locale could not be saved. Please, try again.'));
        }
        $cousines = $this->CousineLocales->Cousines->find('list', ['limit' => 200]);
        $this->set(compact('cousineLocale', 'cousines'));
        $this->set('_serialize', ['cousineLocale']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Cousine Locale id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $cousineLocale = $this->CousineLocales->get($id);
        if ($this->CousineLocales->delete($cousineLocale)) {
            $this->Flash->success(__('The cousine locale has been deleted.'));
        } else {
            $this->Flash->error(__('The cousine locale could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
