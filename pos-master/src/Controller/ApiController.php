<?php

namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;


class ApiController extends Controller {

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
    }

    public function syncOrders() {
        $this->viewBuilder()->setLayout(false);
        $this->autoRender = NULL;
        $this->loadModel('Orders');

        $args = $this->request->data;

        // if (empty($args['order_detail'])) {
        //     throw new Exception('Missing argument: order_detail');
        // }
        // if (empty($args['order_id'])) {
        //     throw new Exception('Missing argument: order_id');
        // }
        // if (empty($args['access_token'])) {
        //     throw new Exception('Missing argument: access_token');
        // }

        unset($args['order_detail']['sync']);
        unset($args['order_detail']['is_deleted']);
        $data['Orders'] = $args['order_detail'];
        $orders = $this->Orders->newEntity();
        $orders = $this->Orders->patchEntity($orders, $args['order_detail']);
        $this->Orders->save($orders);
        echo json_encode($orders);
    }

    public function syncCousine() {
        $this->viewBuilder()->setLayout(false);
        $this->autoRender = NULL;
        $this->loadModel('Cousines');
        $this->loadModel('CousineLocales');
        $this->loadModel('Categories');

        $args = $this->request->data;

        // find all categories, filter by sync="N", restaurant_id
        $result = $this->Categories->find('all',
            array('conditions' => array(
                'is_synced' => 'N',
                'restaurant_id' => $args['restaurant_id']
            ))
        );

        echo json_encode($result);
    }
	//
	// public function delegate($object, $command) {
	// 	$this->viewBuilder()->setLayout(false);
    //     $this->autoRender = NULL;
	// 	$result = null;
	// 	try {
	// 		if ($this->request->is('post') || $this->request->is('put')) {
	// 		  $args = $this->request->data;
	// 		} else {
	// 		  $args = $this->request->query;
	// 		}
	// 		$component = Inflector::camelize($object);
	// 		// if ($component !== 'Access') {
	// 	    //   	$this->_validateAccess($args);
	// 	    // }
	// 		$this->{$component} = $this->Components->load($component);
	// 		$this->{$component}->initialize($this);
	// 		$action = Inflector::camelize($command);
	// 		$return = $this->{$component}->{$action}($args);
	// 		if ($this->{$component}->status === 'success') {
	// 		  $result = $this->_success($return);
	// 		} else {
	// 		  $result = $this->_fail($return);
	// 		}
	// 	} catch(Exception $e) {
	// 		$result = $this->_error($e->getMessage(), $e->getCode(), $result);
	// 	}
	//
	// 	$this->response->type('json');
	// 	$this->response->statusCode(200);
	// 	$this->response->body($result);
	// 	$this->response->send();
	// 	$this->_stop();
	// }
	//
	//
	// protected function _format($status, $response = array()) {
	//   $object = new stdClass();
	//   $object->status = $status;
	//   foreach ($response as $param => $value) {
	//     $object->{$param} = $value;
	//   }
	//   return json_encode($object);
	// }
	//
	// protected function _success($data = null) {
	//   return $this->_format('success', array('data' => $data));
	// }
	//
	// protected function _fail($data = null) {
	//   return $this->_format('fail', array('data' => $data));
	// }
	//
	// protected function _error($message = 'Unknown', $code = 0, $data = array()) {
	//   return $this->_format('error', array(
	//     'message' => $message,
	//     'code' => $code,
	//     'data' => $data
	//   ));
	// }
	//
	// protected function _validateAccess($args) {
	// 	$this->Access = $this->Components->load('Access');
	// 	if (!$this->Access->validate($args)) {
	// 		throw new ForbiddenException();
	// 	}
	// }
}

 ?>
