<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Cousines Controller
 *
 * @property \App\Model\Table\CousinesTable $Cousines
 */
class CousinesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Restaurants', 'Categories']
        ];
        $cousines = $this->paginate($this->Cousines);

        $this->set(compact('cousines'));
        $this->set('_serialize', ['cousines']);
    }

    /**
     * View method
     *
     * @param string|null $id Cousine id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $cousine = $this->Cousines->get($id, [
            'contain' => ['Restaurants', 'Categories', 'CousineLocales']
        ]);

        $this->set('cousine', $cousine);
        $this->set('_serialize', ['cousine']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $cousine = $this->Cousines->newEntity();
        if ($this->request->is('post')) {
            $cousine = $this->Cousines->patchEntity($cousine, $this->request->data);
            if ($this->Cousines->save($cousine)) {
                $this->Flash->success(__('The cousine has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The cousine could not be saved. Please, try again.'));
        }
        $restaurants = $this->Cousines->Restaurants->find('list', ['limit' => 200]);
        $categories = $this->Cousines->Categories->find('list', ['limit' => 200]);
        $this->set(compact('cousine', 'restaurants', 'categories'));
        $this->set('_serialize', ['cousine']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Cousine id.
     * @return \Cake\Network\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $cousine = $this->Cousines->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $cousine = $this->Cousines->patchEntity($cousine, $this->request->data);
            if ($this->Cousines->save($cousine)) {
                $this->Flash->success(__('The cousine has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The cousine could not be saved. Please, try again.'));
        }
        $restaurants = $this->Cousines->Restaurants->find('list', ['limit' => 200]);
        $categories = $this->Cousines->Categories->find('list', ['limit' => 200]);
        $this->set(compact('cousine', 'restaurants', 'categories'));
        $this->set('_serialize', ['cousine']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Cousine id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $cousine = $this->Cousines->get($id);
        if ($this->Cousines->delete($cousine)) {
            $this->Flash->success(__('The cousine has been deleted.'));
        } else {
            $this->Flash->error(__('The cousine could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
