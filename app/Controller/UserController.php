<?php
    class UserController extends AppController {
        public function beforeFilter() {

            parent::beforeFilter();
            $this->Auth->allow('index', 'forgot_password');
            $this->layout = "user";

        }

        public function login() {
            if ($this->request->is('post')) {
                $this->loadModel("Cashier");
                if (isset($this->request->data['Cashier']['username']) && isset($this->request->data['Cashier']['password'])) {
                    $username = $this->request->data['Cashier']['username'];
                    $password = Security::hash($this->data['Cashier']['password'], 'md5', false);

                    $cond = array(
                        'Cashier.password' => $password,
                        'OR' => array(
                            'Cashier.email' => $username,
                        // 'OR' => array('Cashier.mobile_no' => $username)
                        )
                    );
                    $user = $this->Cashier->find('first', array(
                        'conditions' => $cond,
                    ));
                    if (!empty($user)) {
                        if ($user['Cashier']['status'] == "A") {
                            if ($user['Cashier']['is_verified'] == "Y") {
                                $user['Cashier']['type'] = 'cashier';
                                $this->Auth->login($user['Cashier']);
                            } else {
                                $this->Session->setFlash('Your account not verified,please contact to admin', 'error');
                                $this->redirect("index");
                            }
                        } else {
                            $this->Session->setFlash('Your account is deactivated by admin,please contact to admin', 'error');
                            $this->redirect("index");
                        }
                    }
                    if ($this->Auth->login()) {
                        $this->redirect($this->Auth->loginRedirect);
                    } else {
                        $this->Session->setFlash('Invalid Username OR Password.', 'error');
                    }
                }
            }


            if ($this->Auth->user('type') <> 'cashier') {
                // logout previous user
                $this->Auth->logout();
            }

            if ($this->Auth->loggedIn() || $this->Auth->login()) {
                return $this->redirect(array('controller' => 'homes', 'action' => 'dashboard'));
            }
        }

        public function logout() {
            $user = $this->Auth->user();
            $this->Session->setFlash(sprintf(__('%s you have successfully logged out'), $this->Auth->user('firstname')), 'success');
            $this->redirect($this->Auth->logout());
        }

        public function forgot_password() {
            if ($this->request->is('post')) {
                $this->loadModel("Cashier");
                $email_id = $this->request->data['Cashier']['email'];
                $cond = array(
                    'Cashier.email' => $email_id,
                );
                $user = $this->Cashier->find('first', array(
                    'conditions' => $cond,
                ));
                if (!empty($user)) {
                    if ($user['Cashier']['status'] == "A") {
                        if ($user['Cashier']['is_verified'] == "Y") {
                            $password = rand(100000, 999999);
                            $password_md5 = Security::hash($password, 'md5');
                            $this->Cashier->updateAll(array('Cashier.password' => "'" . $password_md5 . "'"), array('Cashier.id' => $user['Cashier']['id']));
                            //send mail//
                            $Email = new CakeEmail();
                            $name = $user['Cashier']['firstname'] . " " . $user['Cashier']['lastname'];
                            $Email->from(WEBSITE_MAIL)
                                    ->to($email_id)
                                    ->subject('New Password')
                                    ->template("forgotpassword")
                                    ->emailFormat("html")
                                    ->viewVars(array('email' => $email_id, 'password' => $password, 'name' => $name))
                                    ->send();

                            //end
                            $this->Session->setFlash('New password has been sent to your registered email', 'success');
                            $this->redirect("forgot_password");
                        } else {
                            $this->Session->setFlash('Your account not verified,please contact to admin', 'error');
                            $this->redirect("forgot_password");
                        }
                    } else {
                        $this->Session->setFlash('Your account is deactivated by admin,please contact to admin', 'error');
                        $this->redirect("forgot_password");
                    }
                } else {
                    $this->Session->setFlash('Sorry, this email not registered.', 'error');
                }
            }
        }
    }

 ?>
