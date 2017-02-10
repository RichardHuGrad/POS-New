<?php
App::uses('Component', 'Controller');
App::uses('Security', 'Utility');
App::uses('ClassRegistry', 'Utility');

class AccessComponent extends Component {

  public $components = array('Cookie');

  public $status = 'success';

  // public function register($args) {
  //   if (empty($args['username'])) {
  //     throw new Exception('Missing argument: username');
  //   }
  //   if (empty($args['password'])) {
  //     throw new Exception('Missing argument: password');
  //   }
  //   $Api = ClassRegistry::init('Api');
  //   $Api->create();

  //   return $Api->save(array(
  //     'username' => $args['username'],
  //     'password' => Security::hash($args['password'], 'md5', false)
  //   ));
  // }

  // /**
  // * return true: login success, false: login failure
  // */
  // public function login($args) {
  //   if (empty($args['username'])) {
  //     throw new Exception('Missing argument: username');
  //   }
  //   if (empty($args['password'])) {
  //     throw new Exception('Missing argument: password');
  //   }
  //   $Api = ClassRegistry::init('Api');
  //   $data = $Api->find('first', array(
  //     'conditions' => array(
  //       'Api.username' => $args['username'],
  //       'Api.password' => Security::hash($args['password'], 'md5', false)
  //     )
  //   ));
  //   if (!empty($data)) {
  //     if (empty($data['Api']['token'])) {
  //       $request = $this->_Collection->getController()->request;
  //       $data['Api']['token'] = md5(uniqid());
  //       $data['Api']['ip'] = $request->clientIp(false);
  //       $Api->save($data);
  //     }
  //     // $this->Cookie->write('auth_token', $data['Api']['token']);
  //     setcookie('auth_token', $data['Api']['token'], time() + (86400 * 30), "/");

  //     // return $data['Api']['token'];
  //     return true;
  //   }

  //   $this->status = "failure";
  //   return false;
  // }

  // public function logout($args) {
  //   // if ($this->Cookie->check('auth_token')) {
  //   if (isset($_COOKIE['auth_token'])) {
  //     $request = $this->_Collection->getController()->request;
  //     $Api = ClassRegistry::init('Api');
  //     $data = $Api->find('first', array(
  //       'conditions' => array(
  //         // 'Api.token' => $this->Cookie->read('auth_token'),
  //         'Api.token' => $_COOKIE['auth_token'],
  //         'Api.ip' => $request->clientIp(false)
  //       )
  //     ));
  //     if (!empty($data)) {
  //       $data['Api']['token'] = null;
  //       $data['Api']['ip'] = null;
  //       $Api->save($data);
  //       // $this->Cookie->delete('auth_token');
  //       setcookie('auth_token', "", time() - (86400 * 30), "/");

  //       return true;
  //     }
  //   }

  //   $this->status = "failure";
  //   return false;
  // }

  // public function validate($args) {
  //   // if ($this->Cookie->check('auth_token')) {
  //   if (isset($_COOKIE['auth_token'])) {
  //     $request = $this->_Collection->getController()->request;
  //     $Api = ClassRegistry::init('Api');
  //     $data = $Api->find('first', array(
  //       'conditions' => array(
  //         // 'Api.token' => $this->Cookie->read('auth_token'),
  //         'Api.token' => $_COOKIE['auth_token'],
  //         'Api.ip' => $request->clientIp(false)
  //       )
  //     ));
  //     if (!empty($data)) {
  //       return true;
  //     }
  //   }

  //   $this->status = "failure";
  //   return false;
  // }

  // cashier password is based on hash md5
  public function generateToken($args) {
    if (empty($args['email'])) {
      throw new Exception('Missing argument: email');
    }
    if (empty($args['password'])) {
      throw new Exception('Missing argument: password');
    }

    $Cashier = ClassRegistry::init('Cashier');
    

    $data = $Cashier->find('first', array(
      'recursive' => -1,
      'conditions' => array(
        'Cashier.email' => $args['email'],
        'Cashier.password' => Security::hash($args['password'], 'md5', false)
      )
    ));

    $Api = ClassRegistry::init('Api');
    if (!empty($data)) {
      $api_data = $Api->find('first', array(
        'recursive' => -1,
        'conditions'=> array(
          'Api.email' => $args['email'],
          'Api.password' =>  Security::hash($args['password'], 'md5', false)
          )
        ));

      if (!empty($api_data)) {
        $api_data['Api']['token'] = md5(uniqid());
      } else {
        $api_data = array('Api' => array(
          'email' => $args['email'],
          'password' => Security::hash($args['password'], 'md5', false),
          'token' => md5(uniqid())
        ));
        $Api->create();
      }
      return $Api->save($api_data);
    }

    $this->status = "failure";
    return false;

  }

  public function validate($args) {
    // args['access_token']
    if (!empty($args['access_token'])) {
      $Api = ClassRegistry::init('Api');
      $data = $Api->find('first', array(
          'conditions' => array(
              'Api.token' => $args['access_token']
            )
        ));

      if (!empty($data)) {
        return true;
      }
    }

    $this->status = "failure";
    return false;
  }
}