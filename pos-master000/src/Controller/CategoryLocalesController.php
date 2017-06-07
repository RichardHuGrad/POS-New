<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * CategoryLocales Controller
 *
 * @property \App\Model\Table\CategoryLocalesTable $CategoryLocales
 */
class CategoryLocalesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Categories']
        ];
        $categoryLocales = $this->paginate($this->CategoryLocales);

        $this->set(compact('categoryLocales'));
        $this->set('_serialize', ['categoryLocales']);
    }

    /**
     * View method
     *
     * @param string|null $id Category Locale id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $categoryLocale = $this->CategoryLocales->get($id, [
            'contain' => ['Categories']
        ]);

        $this->set('categoryLocale', $categoryLocale);
        $this->set('_serialize', ['categoryLocale']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $categoryLocale = $this->CategoryLocales->newEntity();
        if ($this->request->is('post')) {
            $categoryLocale = $this->CategoryLocales->patchEntity($categoryLocale, $this->request->data);
            if ($this->CategoryLocales->save($categoryLocale)) {
                $this->Flash->success(__('The category locale has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The category locale could not be saved. Please, try again.'));
        }
        $categories = $this->CategoryLocales->Categories->find('list', ['limit' => 200]);
        $this->set(compact('categoryLocale', 'categories'));
        $this->set('_serialize', ['categoryLocale']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Category Locale id.
     * @return \Cake\Network\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $categoryLocale = $this->CategoryLocales->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $categoryLocale = $this->CategoryLocales->patchEntity($categoryLocale, $this->request->data);
            if ($this->CategoryLocales->save($categoryLocale)) {
                $this->Flash->success(__('The category locale has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The category locale could not be saved. Please, try again.'));
        }
        $categories = $this->CategoryLocales->Categories->find('list', ['limit' => 200]);
        $this->set(compact('categoryLocale', 'categories'));
        $this->set('_serialize', ['categoryLocale']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Category Locale id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $categoryLocale = $this->CategoryLocales->get($id);
        if ($this->CategoryLocales->delete($categoryLocale)) {
            $this->Flash->success(__('The category locale has been deleted.'));
        } else {
            $this->Flash->error(__('The category locale could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
