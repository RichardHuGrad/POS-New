<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * AdminsRestaurants Controller
 *
 * @property \App\Model\Table\AdminsRestaurantsTable $AdminsRestaurants
 */
class AdminsRestaurantsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Admins', 'Restaurants']
        ];
        $adminsRestaurants = $this->paginate($this->AdminsRestaurants);

        $this->set(compact('adminsRestaurants'));
        $this->set('_serialize', ['adminsRestaurants']);
    }

    /**
     * View method
     *
     * @param string|null $id Admins Restaurant id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $adminsRestaurant = $this->AdminsRestaurants->get($id, [
            'contain' => ['Admins', 'Restaurants']
        ]);

        $this->set('adminsRestaurant', $adminsRestaurant);
        $this->set('_serialize', ['adminsRestaurant']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $adminsRestaurant = $this->AdminsRestaurants->newEntity();
        if ($this->request->is('post')) {
            $adminsRestaurant = $this->AdminsRestaurants->patchEntity($adminsRestaurant, $this->request->data);
            if ($this->AdminsRestaurants->save($adminsRestaurant)) {
                $this->Flash->success(__('The admins restaurant has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The admins restaurant could not be saved. Please, try again.'));
        }
        $admins = $this->AdminsRestaurants->Admins->find('list', ['limit' => 200]);
        $restaurants = $this->AdminsRestaurants->Restaurants->find('list', ['limit' => 200]);
        $this->set(compact('adminsRestaurant', 'admins', 'restaurants'));
        $this->set('_serialize', ['adminsRestaurant']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Admins Restaurant id.
     * @return \Cake\Network\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $adminsRestaurant = $this->AdminsRestaurants->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $adminsRestaurant = $this->AdminsRestaurants->patchEntity($adminsRestaurant, $this->request->data);
            if ($this->AdminsRestaurants->save($adminsRestaurant)) {
                $this->Flash->success(__('The admins restaurant has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The admins restaurant could not be saved. Please, try again.'));
        }
        $admins = $this->AdminsRestaurants->Admins->find('list', ['limit' => 200]);
        $restaurants = $this->AdminsRestaurants->Restaurants->find('list', ['limit' => 200]);
        $this->set(compact('adminsRestaurant', 'admins', 'restaurants'));
        $this->set('_serialize', ['adminsRestaurant']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Admins Restaurant id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $adminsRestaurant = $this->AdminsRestaurants->get($id);
        if ($this->AdminsRestaurants->delete($adminsRestaurant)) {
            $this->Flash->success(__('The admins restaurant has been deleted.'));
        } else {
            $this->Flash->error(__('The admins restaurant could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
