<?php

/**
 * Class HomesController
 * Note*- here cashier id is related to the restaurant id
 */
App::uses('PrintLib', 'Lib');
App::uses('OpencartController', 'Controller');

class HomesController extends AppController {
	const WECHATSERVER="https://pos.auroratech.top/";
	const WECHATTEST = 0;
	
	public $fontStr1 = "simsun";
	public $components = array('Paginator','OrderHandler','Access');
	public $no_of_takeout_tables = 0;
	public $no_of_waiting_tables = 0;
	public $no_of_online_tables = 0;
	
	public $handle;
	public $fontH = 28; // font height
	public $fontW = 10; // font width
	public $uses = array(
			'Admin'
	);
	
    /**
     * beforeFilter
     * @return null
     */
    public function beforeFilter() {

        parent::beforeFilter();
        $this->Auth->allow('index', 'forgot_password');
        
        $this->Auth->allow('index', 'checkin');
        
        $this->layout = "default";
    }

    /**
     * Website home page
     * @return mixed
     */
    public function index() {

        if ($this->request->is('post')) {
            $this->loadModel("Cashier");
            if (isset($this->request->data['Cashier']['username']) && isset($this->request->data['Cashier']['password'])) {
                $username = $this->request->data['Cashier']['username'];
                $password = Security::hash($this->data['Cashier']['password'], 'md5', false);

                $cond = array(                    
                    "OR" => array(
                        'Cashier.email'  => $username,
                        'Cashier.userid' => $username,
                    ),
                    "Cashier.password" => $password,
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

    public function dashboard() {

        // get all table details
        $this->loadModel('Cashier');
        $tables = $this->Cashier->find("first", array(
            'fields' => array('Admin.table_size', 'Admin.table_order', 'Admin.takeout_table_size', 'Admin.waiting_table_size', 'Admin.no_of_tables', 'Admin.no_of_waiting_tables', 'Admin.no_of_takeout_tables', 'Admin.no_of_online_tables', 'Admin.id', 'Admin.kitchen_printer_device', 'Admin.service_printer_device','Admin.net_order_voice'),
            'conditions' => array('Cashier.id' => $this->Session->read('Front.id'))
                )
        );

        $net_order_voice = $tables['Admin']['net_order_voice'];

        //Modified by Yishou Liao @ Dec 12 2016
        $admin_passwd = $this->Cashier->query("SELECT admins.password FROM admins WHERE admins.is_super_admin='Y' ");
        //End @ Dec 12 2016

        // get table availability
        $this->loadModel('Order');
        $tables_status = $this->Order->find("list", array(
            'fields' => array('Order.table_no', 'Order.table_status','Order.order_type'),
            'conditions' => array('Order.cashier_id' => $tables['Admin']['id'], 'Order.is_completed' => 'N')
                )
        );
        $dinein_tables_status  = @$tables_status['D'];
        $takeway_tables_status = @$tables_status['T'];
        $waiting_tables_status = @$tables_status['W'];
        $online_tables_status  = @$tables_status['L'];
/*
        $dinein_tables_status = $this->Order->find("list", array(
            'fields' => array('Order.table_no', 'Order.table_status'),
            'conditions' => array('Order.cashier_id' => $tables['Admin']['id'], 'Order.is_completed' => 'N', 'Order.order_type' => 'D')
                )
        );
        $takeway_tables_status = $this->Order->find("list", array(
            'fields' => array('Order.table_no', 'Order.table_status'),
            'conditions' => array('Order.cashier_id' => $tables['Admin']['id'], 'Order.is_completed' => 'N', 'Order.order_type' => 'T')
                )
        );
        $waiting_tables_status = $this->Order->find("list", array(
            'fields' => array('Order.table_no', 'Order.table_status'),
            'conditions' => array('Order.cashier_id' => $tables['Admin']['id'], 'Order.is_completed' => 'N', 'Order.order_type' => 'W')
                )
        );
        $online_tables_status = $this->Order->find("list", array(
            'fields' => array('Order.table_no', 'Order.table_status'),
            'conditions' => array('Order.cashier_id' => $tables['Admin']['id'], 'Order.is_completed' => 'N', 'Order.order_type' => 'L')
                )
        );

*/

        // get all order no.
        $orders_no = $this->Order->find("list", array(
            'fields' => array('Order.order_type', 'Order.order_no', 'Order.table_no'),
            'conditions' => array('Order.cashier_id' => $tables['Admin']['id'], 'Order.is_completed' => 'N'),
            'recursive' => -1
                )
        );

        $orders_cooked = array();
        foreach ($orders_no as $lvl1) {
        	foreach ($lvl1 as $odn) {
        		if ($s = $this->Order->hasNoneCookedItem($odn)) {
        			$orders_cooked[] = $odn;
        		}
        	}
        }
        
        // get all order phone.
        $orders_phone = $this->Order->find("list", array(
            'fields' => array('Order.order_type', 'Order.phone', 'Order.table_no'),
            'conditions' => array('Order.cashier_id' => $tables['Admin']['id'], 'Order.is_completed' => 'N'),
            'recursive' => -1
                )
        );

        $orders_message = $this->Order->find("list", array(
            'fields' => array('Order.order_type', 'Order.message', 'Order.table_no'),
            'conditions' => array('Order.cashier_id' => $tables['Admin']['id'], 'Order.is_completed' => 'N'),
            'recursive' => -1
                )
        );

/*
        // if there is split-order on this table 
        //$this->loadModel('OrderSplit');
        $this->Order->virtualFields['isSplit']= "Select 'Y' from cookies where cookies.key like concat(Order.order_no,'%') limit 1";
        $orders_split = $this->Order->find("list", array(
            'fields' => array('Order.order_type', 'Order.isSplit', 'Order.table_no'),
            'conditions' => array('Order.cashier_id' => $tables['Admin']['id'], 'Order.is_completed' => 'N'),
            'recursive' => -1
                )
        );
*/

        $orders_total = $this->Order->find("list", array(
            'fields' => array('Order.order_no', 'Order.total'),
            'conditions' => array('Order.cashier_id' => $tables['Admin']['id'], 'Order.is_completed' => 'N'),
            'recursive' => -1
                )
        );

        // get all order times.
        $orders_time = $this->Order->find("list", array(
            'fields' => array('Order.order_type', 'Order.created', 'Order.table_no'),
            'conditions' => array('Order.cashier_id' => $tables['Admin']['id'], 'Order.is_completed' => 'N'),
            'recursive' => -1
                )
        );
        
        $colors = array(
            'P' => 'greenwrap',      //table_status='P' :Paid
            'N' => 'notpaidwrap',
            'A' => 'availablebwrap',
            'V' => 'notpaidwrap',
            'R' => 'receiptwrap',
        );

        // print_r($orders_total);
        
        // Clear dashboard page reload flag
        $this->Admin->clear();
        $rest = $this->Admin->find("first", array('conditions' => array('Admin.is_super_admin' => 'N','Admin.status' => 'A')));
        if ($rest && $rest['Admin']['net_new_order']) {
	        $this->Admin->id = $rest['Admin']['id'];
	        $this->Admin->saveField('net_new_order', 0);
	        $this->Admin->clear();
        }
        
        $this->set(compact('tables','orders_cooked', 'dinein_tables_status','takeway_tables_status', 'waiting_tables_status','online_tables_status','colors','orders_no','orders_phone','orders_time','orders_total','admin_passwd', 'orders_message', 'net_order_voice'));
    }
    
    public function checknew() {
        $this->layout = NULL;
        $this->autoRender = false;

        $this->get_net_order();		// Get net order now is loaded by browser

        $this->loadModel('Admin');
    	$rest = $this->Admin->find("first", array('conditions' => array('Admin.is_super_admin' => 'N', 'Admin.status' => 'A')));
    	if ($rest && $rest['Admin']['net_new_order']) {
			$this->Admin->id = $rest['Admin']['id'];
			$this->Admin->saveField('net_new_order', '');
			$this->Admin->clear();
    		$json = array('reload' => 1);
    	} else {
    		$json = array('reload' => 0);
    	}
    	
    	$this->response->type('application/json');
    	echo json_encode($json);
    }
    
	private function save_net_order($ordertype, $order) {
		$od = $this->RemoteOrderSync->find('first', array('conditions' => array('order_type' => $ordertype, 'order_id' => $order['id'])));
		if (empty($od)) {
			$savedata = array(
					'RemoteOrderSync' => array(
							'order_type' => $ordertype,
							'order_id' => $order['id'],
							'record' => json_encode($order)
					)
			);
			$this->RemoteOrderSync->save($savedata);
			$id = $this->RemoteOrderSync->id;
			return $id;
		}
		return FALSE;
	}
	
	private function print_reserve($orders, $restaurant_id) {
		$printerName = $this->Admin->getKitchenPrinterName($restaurant_id);
		$printerCut = $this->Admin->getKitchenPrinterCut($restaurant_id);
		$print = new PrintLib();
		
		foreach($orders as $order) {
			$print->printReserveOrder($order, $printerName);
			$this->save_net_order('yyorders', $order);
			$this->RemoteOrderSync->clear();
		}
	}

	private function insert_net_orders($orders, $restaurant_id, $print_now) {
		$this->loadModel('Cashier');
		$this->loadModel('OrderItem');
		$this->loadModel('Cousine');
		$this->loadModel('Order');
		$this->loadModel('Log');
		$this->loadModel('Extra');
		$this->loadModel('Extrascategory');
		
		$printerKitchen = $this->Admin->getKitchenPrinterName($restaurant_id);
		$printerDesk = $this->Admin->getServicePrinterName($restaurant_id);
		$printerCut = $this->Admin->getKitchenPrinterCut($restaurant_id);
		
		$print = new PrintLib();
		$cashier = $this->Cashier->find("first",
				array(
						'fields' => array('Cashier.firstname', 'Cashier.lastname', 'Cashier.id', 'Cashier.image', 'Admin.id'),
						'conditions' => array('Cashier.id' => $this->Session->read('Front.id'))
				)
		);
		
		$admin = $this->Cashier->find("first",
				array(
						'fields' => array(
								'Admin.no_of_tables', 
								'Admin.no_of_waiting_tables', 
								'Admin.no_of_takeout_tables', 
								'Admin.no_of_online_tables', 
								'Admin.id', 
								'Admin.tax', 
								'Admin.default_tip_rate',
								'Admin.net_order_kitchen',
								'Admin.net_takeout_kitchen',
								'Admin.net_order_voice',
								'Admin.touch_screen_sound',
								'Admin.default_tip_after_tax',
								'Admin.main_page_show_price'
						),
						'conditions' => array('Cashier.id' => $cashier['Cashier']['id'])
				)
		);

    	$has_net_order = 0;
		// $print->printReserveOrder($order, $printerDesk);
    	foreach ($orders as $order) {
			$net_order_id = $this->save_net_order('orders', $order);
    		if ( ! $net_order_id ) {
    			continue;
			}
			if ((int)$print_now <= 0) {
				$print->printNetOrder($order, $printerDesk);
			} else {
				/*
				 * [id] => 58
				 * [user_id] => 29
				 * [order_num] => 201803291637331394
				 * [state] => 4
				 * [time] => 2018-03-29 16:37:33
				 * [pay_time] => 0
				 * [money] => 39.54
				 * [preferential] => 0
				 * [tel] =>
				 * [name] =>
				 * [address] =>
				 * [delivery_time] =>
				 * [time2] => 1522359453
				 * [cancel_time] => 0
				 * [uniacid] => 4
				 * [type] => 2							type=2: In store Order;  type=1: Take Out Order
				 * [dn_state] => 2
				 * [table_id] => 27
				 * [freight] => 0.00
				 * [box_fee] => 0.00
				 * [coupons_id] => 0
				 * [voucher_id] => 0
				 * [seller_id] => 27
				 * [note] =>
				 * [area] =>
				 * [lat] =>
				 * [lng] =>
				 * [del] => 2
				 * [sh_ordernum] =>
				 * [pay_type] => 0
				 * [del2] => 0
				 * [is_take] => 0
				 * [is_yue] => 0
				 * [completion_time] => 0
				 * [form_id] =>
				 * [dishes] => Array(
				 * 		[0] => Array(
				 * 		[id] => 152
				 * 		[img] => images/4/2018/03/qqEz6PEq9pAP6tuGMPKEq969CMpGt9.jpg
				 * 		[number] => 1
				 * 		[order_id] => 58
				 * 		[name] => 双拼双拼
				 * 		[money] => 3.99
				 * 		[uniacid] => 4
				 * 		[dishes_id] => 409
				 * 		[spec] =>
				 * 		[options] => [{"type":"1","name":"\u53e3\u5473","values":[{"name":"\u4e0d\u8fa3","price":0,"quantity":1},{"name":"\u4e0d\u9ebb","price":0,"quantity":2}]},{"type":"0","name":"\u66f4\u6362\u4e3b\u98df","values":[{"name":"\u5c0f\u9762\u6362\u9178\u8fa3\u7c89","price":1,"quantity":1}]},{"type":"2","name":"\u53cc\u62fc","values":[{"name":"\u85d5\u7247","price":0,"quantity":1},{"name":"\u571f\u8c46","price":0,"quantity":1}],"limit":"2","total":"30"}]
				 * 	)
				 * )
				 * [tablename] => 1号桌
				 * )
				 */
				$this->Log->save(array('operation' => "weborder get", 'logs' => json_encode($order)));
				$memotxt = array('error' => array(), 'message' => array());
				
				$order_id = 0;
				$reason = "Net order# " . $order['order_num'] . "; ";
				if (($order['type'] == 2) || ($order['type'] == 1)) {
					// In store (2 => D) or Take out (1 => T) order, Just create or add items to order
					foreach ($order['dishes'] as $dishes) {
						$CousineDetail = $this->Cousine->find('first', array('conditions' => array('Cousine.remote_id' => $dishes['dishes_id'])));
						if ( ! $CousineDetail) {
							$memotxt['error'][] = "Can't find Cousine. Remote ID: " . $dishes['id'] . "; ";
							continue;
						}
						//Debug echo "Local: CousineDetail\n"; print_r($CousineDetail); print_r($dishes);
						$item_id = $CousineDetail['Cousine']['id'];
						$table = (int)$order['tablename'];
						$type = ($order['type'] == 2) ? 'D' : 'L';	// D: Dine in; L: internet
						$cashier_id = $cashier['Cashier']['id'];
						$tax_rate = $admin['Admin']['tax'];
						$default_tip_rate = $admin['Admin']['default_tip_rate'];
						$restaurant_id = $admin['Admin']['id'];
						if (empty($order_id)) {
							$Order_detail = $this->Order->find("first",
								array('fields' => array('Order.id', 'Order.subtotal', 'Order.total', 'Order.reason', 'Order.tax_amount', 'Order.discount_value', 'Order.promocode', 'Order.fix_discount', 'Order.percent_discount'),
									'conditions' => array('Order.counter_id' => $cashier_id, 'Order.table_no' => $table, 'Order.is_completed' => 'N', 'Order.order_type' => $type )
								)
							);
							//Debug echo "Order_detail\n"; print_r($Order_detail);
							if ( ! empty($Order_detail)) {
								$order_id = $Order_detail['Order']['id'];
								$reason = $Order_detail['Order']['reason'] . "; Add other Order: " . $order['order_num'] . "; ";
							}
						}
						
						if (empty($order_id)) {
							// to create a new order
							$table_limit = 0;
							if ($type == 'L') {
								if ($order['type'] == 1) {
									if ($order['is_take'] == 1) {
										$type = 'T';	// Take away
										$table_limit = $this->no_of_takeout_tables;
									} else if ($order['is_take'] == 2) {
										$type = 'W';	// Waiting
										$table_limit = $this->no_of_waiting_tables;
									} else {
										$type = '';
									}
								}
								if ($type) {
									for ($table = 1; $table <= $table_limit; $table++) {
										$t = $this->Order->find("first",array('conditions' => array('Order.table_no' => $table, 'Order.order_type' => $type), 'order' => array('Order.created DESC')));
										if (empty($t) || ($t['Order']['table_status'] == 'A') || ($t['Order']['table_status'] == 'V')) {
											// New table or Available table
											break;
										}
									}
									if ($table > $table_limit) {
										$this->RemoteOrderSync->delete($net_order_id, FALSE);
										// reach maximum drop order !!!!! need fix in future
										// echo "reach maximum table limit !!!!! Order dropped !!! ".$order['order_num']." \n"; //XXXXXXXXXXXXX
    									return;
									}
								}
							}
							$order_id = $this->Order->insertOrder($restaurant_id, $cashier_id, $table, $type, $tax_rate, $default_tip_rate);
							// echo "order_id : ".$order_id."\n"; echo "insertOrder($restaurant_id, $cashier_id, $table, $type, $tax_rate, $default_tip_rate)\n";
						}
						if ($CousineDetail['Cousine']['is_tax'] == 'Y') {
							$tax_amount = $tax_rate * $CousineDetail['Cousine']['price'] / 100;
						} else {
							$tax_amount = 0;
						}
						
						$order_item_id = $this->OrderItem->insertOrderItem($order_id, $item_id, $CousineDetail['CousineLocal'][0]['name'], $CousineDetail['CousineLocal'][1]['name'], $CousineDetail['Cousine']['price'], $CousineDetail['Cousine']['category_id'], /*!empty($extras) ? json_encode($extras) : "",*/ $tax_rate, $tax_amount, $dishes['number'], $CousineDetail['Cousine']['comb_num']);
						$extra_id_array = array();
						if (!empty($dishes['options']) && ($options = json_decode($dishes['options'], TRUE))) {
							//Debug echo "options:\n"; print_r($options);
							foreach ($options as $opt) {
								if ($extracategory = $this->Extrascategory->find('first', array('conditions' => array('Extrascategory.remote_id' => $opt['options_id']), 'recursive' => -1))) {
									//Debug echo "extracategory:\n"; print_r($extracategory);
									foreach ($opt['values'] as $extra) {
										if ($ext = $this->Extra->find('first', array('conditions' => array('Extra.name_zh' => $extra['name'], 'Extra.category_id' => $extracategory['Extrascategory']['id']), 'recursive' => -1))) {
											//Debug echo "ext:\n"; print_r($ext);
											$quantity = isset($extra['quantity']) ? (int)$extra['quantity'] : 1;
											if (empty($quantity)) $quantity = 1;
											for ($i = 0; $i < $quantity; $i++) {
												$extra_id_array[] = $ext['Extra']['id'];
											}
										} else {
											$memotxt['error'][] = "Can't find Extrascategory's Extra. Remote ID: " . $opt['options_id'] . "; name: " . $extra['name'] . "; local ID: " . $extracategory['id'];
										}
									}
								} else {
									$memotxt['error'][] = "Can't find Extrascategory. Remote ID: " . $opt['options_id'] . "; ";
								}
								//Debug echo "error:\n"; print_r($memotxt['error']);
								//Debug echo "extra_id_array:\n"; print_r($extra_id_array);
							}
						}
						if (!empty($extra_id_array)) {
							$this->OrderHandler->addExtras(array(
									'item_id' => $order_item_id,
									'extra_id_list' => $extra_id_array,
									'table' => $table,
									'type' => $type,
									'special' => '',
									'cashier_id' => $cashier_id)
									);
						}
						$this->Order->updateBillInfo($order_id);
						$this->OrderItem->clear();
					}
				} else {
					// Unknown Order, Do't process
					continue;
				}
				
				if ($order_id) {
					$message = '';
					if ($order['type'] == 1) {
						$reason .= $order['name'] . " - " . $order['tel'] . " - " . $order['address'];
						if ($order['is_take'] == 1) {
							$message = __('TAKE OUT'); //"Takeout";
						} else if ($order['is_take'] == 2) {
							$message = __('Delivery'); //"Delivery";
						}
					}
					$this->Order->update_reason($order_id, $reason, $message);
					$has_net_order = 1;
				}
				$this->RemoteOrderSync->clear();
    			$this->Order->clear();
    			
    			if ($order_id) {
    				if (($type == 'T') && ($admin['Admin']['net_takeout_kitchen'])) { // take away
    					$this->Print->printTokitchen(array('restaurant_id'=> $restaurant_id, 'order_id'=>$order_id));
    				} else if (($type = 'D') && ($admin['Admin']['net_order_kitchen'])) { // Dine in
    					$this->Print->printTokitchen(array('restaurant_id'=> $restaurant_id, 'order_id'=>$order_id));
    				}
    			}
			}
    	}
    	return  $has_net_order;
    }
    
	private function get_net_order() {
		$rest = $this->Admin->find("first", array('conditions' => array('Admin.is_super_admin' => 'N', 'Admin.status' => 'A')));
		
		$tm = strtotime($rest['Admin']['net_last_sync_tm']);
		$now = time();
		if (($now - $tm) < 15) {
			// mutliple callers, skip to fast call
			return FALSE;
		}
		
		// Setup last sync access time
		$this->Admin->id = $rest['Admin']['id'];
		$this->Admin->saveField('net_last_sync_tm', date("Y-m-d H:i:s"));
		$this->Admin->clear();
		
		$mobile_no = $rest['Admin']['mobile_no'];
		$this->no_of_online_tables = $rest['Admin']['no_of_online_tables'];
		$this->no_of_waiting_tables = $rest['Admin']['no_of_waiting_tables'];
		$this->no_of_takeout_tables = $rest['Admin']['no_of_takeout_tables'];
		$dt = preg_split("/-/", $mobile_no);
		if (!is_array($dt) || (sizeof($dt) != 2)) {
			// Wrong format about store and phone 
			return FALSE;
		}
		
		$url = self::WECHATSERVER . "web/index.php?c=site&a=entry&do=storeorder&m=zh_dianc&version_id=1&sid=" . $dt[0] . "&skey=" . md5($dt[1]) . "&sync=2";
		if (self::WECHATTEST) {
			$url .= "&test=1";
		}
		
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		$response = curl_exec($curl);
		curl_close($curl);
		
		$rts = json_decode($response, TRUE);

		$this->loadModel('RemoteOrderSync');
		if (is_array($rts) && ($rts['status'] == 'OK') && ((sizeof($rts['orders']) > 0) || (sizeof($rts['yyorders']) > 0))) {
			// Have some order to sync
			if (sizeof($rts['yyorders']) > 0) {
				// have reserve order
				$this->print_reserve($rts['yyorders'], $rest['Admin']['id']);
			}
			
			// have some order for
			if (sizeof($rts['orders']) > 0) {
				if ($this->insert_net_orders($rts['orders'], $rest['Admin']['id'], $rest['Admin']['no_of_online_tables'])) {
					$this->Admin->id = $rest['Admin']['id'];
					$this->Admin->saveField('net_new_order', 1);
					$this->Admin->clear();
				}
			}
		}

		// Send back ack to cloud server
		$sync_orders = $this->RemoteOrderSync->find('all', array('conditions' => array('synced' => 0)));
		foreach ($sync_orders as $sync) {
			if ($sync['RemoteOrderSync']['order_type'] == 'yyorders') {
				$syncurl = $url . "&ydorderid=" . $sync['RemoteOrderSync']['order_id'];
			} else { 
				// $sync['RemoteOrderSync']['order_type'] == 'orders'
				$syncurl = $url . "&orderid=" . $sync['RemoteOrderSync']['order_id'];
			}
			
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $syncurl);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			$response = curl_exec($curl);
			curl_close($curl);
			$sync_rt = json_decode($response, TRUE);
			if (is_array($sync_rt) && ($sync_rt['status'] == 'OK') && ($sync_rt['id'] == $sync['RemoteOrderSync']['order_id'])) {
				$sync['RemoteOrderSync']['synced'] = 1;
			}
			$this->RemoteOrderSync->save($sync);
			$this->RemoteOrderSync->clear();
		}
    }
    
    public function allorders() {

        // get all table details
        $this->loadModel('Cashier');
        $tables = $this->Cashier->find("first", array(
            'fields' => array('Admin.table_size', 'Admin.takeout_table_size', 'Admin.waiting_table_size', 'Admin.no_of_tables', 'Admin.id'),
            'conditions' => array('Cashier.id' => $this->Session->read('Front.id'))
                )
        );


        // get all orders
        $this->loadModel('Order');
        $this->loadModel('OrderItem');

        $this->OrderItem->virtualFields['category_name_en'] = "Select category_locales.name from category_locales where OrderItem.category_id = category_locales.category_id and category_locales.lang_code = 'en'";
        $this->OrderItem->virtualFields['category_name_zh'] = "Select category_locales.name from category_locales where OrderItem.category_id = category_locales.category_id and category_locales.lang_code = 'zh'";
        $orders = $this->Order->find("all", array(
            // 'fields'=>array('Order.table_no', 'Order.table_status'),
            'conditions' => array('Order.cashier_id' => $tables['Admin']['id'], 'Order.is_completed' => 'N'),
            'order' => 'Order.created asc'
                )
        );

        $final_orders = [];
        if (!empty($orders)) {
            foreach ($orders as $key => $value) {
                $categories = [];
                $items_array = [];

                # prepare order by category name
                if (!empty($value['OrderItem'])) {
                    foreach ($value['OrderItem'] as $item_key => $item) {
                        $items_array[$item['category_name_en'] . "|||" . $item['category_name_zh'] . "|||" . $item['category_id']][] = $item;
                    }
                }
                $value['Order']['order_created'] = $this->timeAgo(strtotime($value['Order']['created']));
                $final_orders[] = array(
                    'Order' => $value['Order'],
                    'categories' => $items_array
                );
            }
        }
        $this->set(compact('final_orders'));
    }

    public function cookings() {

        // get all table details
        $this->loadModel('Cashier');
        $tables = $this->Cashier->find("first", array(
            'fields' => array('Admin.table_size', 'Admin.takeout_table_size', 'Admin.waiting_table_size', 'Admin.no_of_tables', 'Admin.id', 'Admin.printer_ip', 'Admin.printer_device_id'),
            'conditions' => array('Cashier.id' => $this->Session->read('Front.id'))
                )
        );

        // get all orders
        $this->loadModel('Order');
        $this->loadModel('Category');
        $this->loadModel('OrderItem');

        $this->OrderItem->virtualFields['category_name_en'] = "Select category_locales.name from category_locales where OrderItem.category_id = category_locales.category_id and category_locales.lang_code = 'en'";
        $this->OrderItem->virtualFields['category_name_zh'] = "Select category_locales.name from category_locales where OrderItem.category_id = category_locales.category_id and category_locales.lang_code = 'zh'";

        $orders = $this->OrderItem->find("all", array(
            'fields' => array('Order.message', 'Order.table_no', 'Order.table_status', 'Order.order_type', 'Order.order_no', 'Order.created as order_created', 'OrderItem.*'),
            'conditions' => array(
                'Order.cashier_id' => $tables['Admin']['id'], 'Order.is_completed' => 'N', 'Order.is_kitchen' => 'Y'
            ),
                )
        );

        // categories all records
        $items_array = [];
        if (!empty($orders)) {
            foreach ($orders as $key => $value) {

                # prepare order by category name
                $item = $value['OrderItem'];
                $item['message'] = $value['Order']['message'];
                $item['table_no'] = $value['Order']['table_no'];
                $item['order_no'] = $value['Order']['order_no'];
                $item['order_type'] = $value['Order']['order_type'];
                $item['order_created'] = $value['Order']['order_created'];
                $item['time_ago'] = $this->timeAgo(strtotime($value['Order']['order_created']));

                $items_array[$item['category_name_en'] . "|||" . $item['category_name_zh'] . "|||" . $item['category_id']][$item['order_id']][] = $item;
            }
        }

        $type = @$this->params['named']['type'];

        // resort elemets array
        if ($type == 'finished') {
            foreach ($items_array as $key => $records) {
                # code...
                foreach ($records as $order_id => $value) {
                    $finished = 1;
                    foreach ($value as $item) {
                        if ($item['is_done'] == 'N')
                            $finished = 0;
                    }
                    if (!$finished) {
                        unset($items_array[$key][$order_id]);
                    }
                }
            }
        } else {
            foreach ($items_array as $key => $records) {
                # code...
                foreach ($records as $order_id => $value) {
                    $finished = 1;
                    foreach ($value as $item) {
                        if ($item['is_done'] == 'N')
                            $finished = 0;
                    }
                    if ($finished) {
                        unset($items_array[$key][$order_id]);
                    }
                }
            }
        }

        // unset category list
        foreach ($items_array as $key => $records) {
            # code...
            if (empty($records))
                unset($items_array[$key]);
        }
        $this->set(compact('items_array', 'type', 'tables'));
    }

    function doneitem() {
        $this->layout = false;
        $this->autoRender = NULL;

        // get all params
        $item_id = $this->data['item_id'];

        // check item if already done or not
        $this->loadModel('OrderItem');
        $this->loadModel('Order');
        $item_detail = $this->OrderItem->find("first", array(
            'fields' => array('OrderItem.is_done', 'OrderItem.order_id'),
            'conditions' => array('OrderItem.id' => $item_id),
                )
        );

        if ($item_detail['OrderItem']['is_done'] == 'Y') {
            $data['OrderItem']['is_done'] = 'N';
        } else {
            $data['OrderItem']['is_done'] = 'Y';
        }
        // save order to database
        $data['OrderItem']['id'] = $item_id;
        $this->OrderItem->save($data, false);

        // check all order items is finished or not
        $order_status = $this->OrderItem->find("first", array(
            'fields' => array('count(OrderItem.id) as counter'),
            'conditions' => array('OrderItem.order_id' => $item_detail['OrderItem']['order_id'], 'OrderItem.is_done' => 'N'),
                )
        );
        if (!$order_status[0]['counter']) {

            $data_order['Order']['id'] = $item_detail['OrderItem']['order_id'];
            $data_order['Order']['cooking_status'] = 'COOKED';
            $this->Order->save($data_order, false);
        } else {

            $data_order['Order']['id'] = $item_detail['OrderItem']['order_id'];
            $data_order['Order']['cooking_status'] = 'UNCOOKED';
            $this->Order->save($data_order, false);
        }


        if ($data['OrderItem']['is_done'] == 'N')
            echo json_encode(array('done' => false));
        else
            echo json_encode(array('done' => true));
    }

    function doneallitem() {
        $this->layout = false;
        $this->autoRender = NULL;

        // get all params
        $item_ids = explode(",", $this->data['item_id']);

        // save order to database
        $this->loadModel('OrderItem');
        foreach ($item_ids as $key => $value) {
            # code...
            $data['OrderItem']['id'] = $value;
            $data['OrderItem']['is_done'] = 'Y';
            $this->OrderItem->save($data, false);
        }


        $this->loadModel('Order');
        $item_detail = $this->OrderItem->find("first", array(
            'fields' => array('OrderItem.is_done', 'OrderItem.order_id'),
            'conditions' => array('OrderItem.id' => $item_ids[0]),
                )
        );

        // check all order items is finished or not
        $order_status = $this->OrderItem->find("first", array(
            'fields' => array('count(OrderItem.id) as counter'),
            'conditions' => array('OrderItem.order_id' => $item_detail['OrderItem']['order_id'], 'OrderItem.is_done' => 'N'),
                )
        );
        if (!$order_status[0]['counter']) {

            $data_order['Order']['id'] = $item_detail['OrderItem']['order_id'];
            $data_order['Order']['cooking_status'] = 'COOKED';
            $this->Order->save($data_order, false);
        } else {

            $data_order['Order']['id'] = $item_detail['OrderItem']['order_id'];
            $data_order['Order']['cooking_status'] = 'UNCOOKED';
            $this->Order->save($data_order, false);
        }

        echo true;
    }

    function recookallitem() {
        $this->layout = false;
        $this->autoRender = NULL;

        // get all params
        $item_ids = explode(",", $this->data['item_id']);

        // save order to database
        $this->loadModel('OrderItem');
        foreach ($item_ids as $key => $value) {
            # code...
            $data['OrderItem']['id'] = $value;
            $data['OrderItem']['is_done'] = 'N';
            $this->OrderItem->save($data, false);
        }

        $this->loadModel('Order');
        $item_detail = $this->OrderItem->find("first", array(
            'fields' => array('OrderItem.is_done', 'OrderItem.order_id'),
            'conditions' => array('OrderItem.id' => $item_ids[0]),
                )
        );

        $data_order['Order']['id'] = $item_detail['OrderItem']['order_id'];
        $data_order['Order']['cooking_status'] = 'UNCOOKED';
        $this->Order->save($data_order, false);
        echo true;
    }


    public function tableHisdetail() {
        // get cashier details
        $this->loadModel('Cashier');
        $cashier_detail = $this->Cashier->find("first", array(
            'fields' => array('Cashier.firstname', 'Cashier.lastname', 'Cashier.id', 'Cashier.image', 'Admin.id','Admin.show2nd'),
            'conditions' => array('Cashier.id' => $this->Session->read('Front.id'))
                )
        );
        
        $admin_passwd = $this->Cashier->query("SELECT admins.password FROM admins WHERE admins.is_super_admin='Y' ");

        $show2nd = $cashier_detail['Admin']['show2nd'];

        $table_no = $this->params['named']['table_no'];
        $order_id = $this->params['named']['order_id'];
        $order_type = empty($this->params['named']['order_type'])?'D':$this->params['named']['order_type'];

        $this->loadModel('Order');
        $this->loadModel('OrderItem');

        $dinein_table_status = $this->Order->query("SELECT table_status FROM orders WHERE cashier_id='{$cashier_detail['Admin']['id']}' and is_completed='N' and order_type='$order_type' and table_no='$table_no' ");
        if($dinein_table_status){
            $dinein_table_status = $dinein_table_status[0]['orders']['table_status'];
        }else{
            $dinein_table_status = "";
        }
        
        $conditions = array('Order.cashier_id' => $cashier_detail['Admin']['id'],
            'Order.id' => $order_id,
        	  'Order.table_no' => $table_no,
            'Order.is_completed' => 'Y',
            'Order.order_type' => $order_type,
            'Order.created >=' => date("Ymd")/* , strtotime("-2 weeks")) */
        );

        $Order_detail = $this->Order->find("first", array(
            'fields' => array('Order.paid', 'Order.tip', 'Order.cash_val', 'Order.card_val', 'Order.change', 'Order.order_no', 'Order.tax', 'Order.table_status', 'Order.tax_amount', 'Order.subtotal', 'Order.total', 'Order.message', 'Order.discount_value', 'Order.promocode', 'Order.fix_discount', 'Order.percent_discount', 'Order.created'),
            'conditions' => $conditions
                )
        );
        if (empty($Order_detail)) {
            $this->Session->setFlash('Sorry, there is no table history for today.', 'error');
            return $this->redirect(array('controller' => 'homes', 'action' => 'dashboard'));
        }

        $today = date('Y-m-d H:i', strtotime($Order_detail['Order']['created']));

        $this->set(compact('Order_detail', 'cashier_detail','admin_passwd','table_no', 'order_id','order_type','today','dinein_table_status', 'show2nd'));
    }

    public function tableHisupdate() {
        $this->layout = false;
        $this->autoRender = NULL;

		    $res = $this->OrderHandler->tableHisupdate(array(
		    	'order_id'  => $this->data['order_id'],
		    	'subtotal'  => $this->data['subtotal'],
		    	'discount_value'  => $this->data['discount_value'],
		    	'total'     => $this->data['total'],
		    	'paid'      => $this->data['paid'],
		    	'cash_val'  => $this->data['cash_val'],
		    	'card_val'  => $this->data['card_val'],
		    	'change'    => $this->data['change'],
		    	'tip'       => $this->data['tip'],
		    	'cashier_id' => $this->Session->read('Front.id')   	
		    ));
    	
        echo json_encode($res);
        exit;
    }
    

    public function tableHistory() {
        // get cashier details
        $this->loadModel('Cashier');
        $cashier_detail = $this->Cashier->find("first", array(
            'fields' => array('Cashier.firstname', 'Cashier.lastname', 'Cashier.id', 'Cashier.image', 'Admin.id'),
            'conditions' => array('Cashier.id' => $this->Session->read('Front.id'))
                )
        );

        $admin_passwd = $this->Cashier->query("SELECT admins.password FROM admins WHERE admins.is_super_admin='Y' ");


        $table_no   = $this->params['named']['table_no'];
        $order_type = empty($this->params['named']['order_type'])?'D':$this->params['named']['order_type'];

        $this->loadModel('Order');
        $this->loadModel('OrderItem');

        $conditions = array('Order.cashier_id' => $cashier_detail['Admin']['id'],
            'Order.table_no' => $table_no,
            'Order.is_completed' => 'Y',
            'Order.order_type' => $order_type,
            'Order.created >=' => date("Ymd")/*, strtotime("-2 weeks")) */
        );

        $Order_detail = $this->Order->find("all", array(
            'fields' => array('Order.paid', 'Order.tip', 'Order.cash_val', 'Order.card_val', 'Order.change', 'Order.order_no', 'Order.tax', 'Order.table_status', 'Order.tax_amount', 'Order.subtotal', 'Order.total', 'Order.message', 'Order.discount_value', 'Order.promocode', 'Order.fix_discount', 'Order.percent_discount', 'Order.created'),
            'conditions' => $conditions
                )
        );
        
        if (empty($Order_detail)) {
            $this->Session->setFlash('Sorry, there is no table history for today.', 'error');
            return $this->redirect(array('controller' => 'homes', 'action' => 'dashboard'));
        }

        $this->paginate = array(
            'fields' => array('Order.paid', 'Order.tip', 'Order.cash_val', 'Order.card_val', 'Order.change', 'Order.order_no', 'Order.tax', 'Order.table_status', 'Order.tax_amount', 'Order.subtotal', 'Order.total', 'Order.message', 'Order.discount_value', 'Order.promocode', 'Order.fix_discount', 'Order.percent_discount', 'Order.created'),
            'conditions' => $conditions,
            'limit' => 10,
            'order' => array('Order.created' => 'desc')
        );

        $Order_detail = $this->paginate('Order');
        $today = date('Y-m-d H:i', strtotime($Order_detail[0]['Order']['created']));

        $this->set(compact('Order_detail', 'cashier_detail','admin_passwd','table_no','order_type', 'today'));
    }


    public function tableRestore() {

        //$this->layout = 'ajax';
        $this->layout = false;
        $this->autoRender = NULL;

		    $res = $this->OrderHandler->tableRestore(array(
		    	'order_id'   => $this->data['order_id'],
		    	'cashier_id' => $this->Session->read('Front.id')
		    ));
    	
        echo json_encode($res);
        exit;
    }


    public function closeOrder() {
    	
        $this->layout = false;
        $this->autoRender = NULL;

        // get all params
        $order_no = $this->params['named']['order'];

        $this->loadModel('Order');
        
        $order_id = $this->Order->getOrderIdByOrderNo($order_no);
        $data['Order']['id'] = $order_id;
        $data['Order']['table_status'] = 'P';
        $data['Order']['is_kitchen'] = 'Y';
        $data['Order']['is_completed']   = 'Y';
        $data['Order']['cooking_status'] = 'COOKED';
        
        $this->Order->save($data, false);
                               
        $oc = new OpencartController();
        $oc->setApi(); //beforeFilter not called in this case
        $oc->closeOcOrders($order_no);
        
        $this->Session->setFlash('Close order successfully! 成功关闭订单.', 'success');
        return $this->redirect(array('controller' => 'homes', 'action' => 'dashboard'));
    }


    public function makeavailable() {
        $this->layout = false;
        $this->autoRender = NULL;

		    $res = $this->OrderHandler->makeavailable(array(
		    	'order_no' => $this->params['named']['order'],
		    	'cashier_id' => $this->Session->read('Front.id')
		    ));

/*
        // get all params
        $order_no = $this->params['named']['order'];

        $this->loadModel('Order');
        //$this->loadModel('OrderLog');
        $this->loadModel('Log');

        $order_detail = $this->Order->find('first', array(
               'recursive' => -1,
               'conditions' => array(
                       'order_no' => $order_no
                   )
           ));
        
        $logArr = array('cashier_id' => $this->Session->read('Front.id'), 'admin_id' => $order_detail['Order']['cashier_id'],'operation'=>'Void(makeavailable)', 'logs' => json_encode($order_detail));
        $this->Log->save($logArr);

            
        //$this->OrderLog->insertLog($order_detail, 'delete(makeavailable)');        
        //delete order and order_item
        //$this->Order->deleteAll(array('Order.order_no' => $order_no), false);        
        
        // update order
        $this->Order->updateAll(array('table_status'=>"'V'",'is_completed' => "'Y'"), array('Order.order_no' => $order_no));
*/      
        if($res['ret'] == 1){
        	$this->Session->setFlash('Table successfully marked as available 成功清空本桌.', 'success');
        }else{
        	$this->Session->setFlash($res['message'], 'error');
        }
        
        return $this->redirect(array('controller' => 'homes', 'action' => 'dashboard'));
    }


    public function move_order() {

        $this->layout = false;
        $this->autoRender = NULL;

        // get all params
        $type = @$this->params['named']['type'];
        $table = @$this->params['named']['table'];
        $order_no = @$this->params['named']['order_no'];

		    $data = $this->OrderHandler->moveOrder(
		       array( 'type'  => $type, 'table' => $table, 'order_no' => $order_no
		    ));
        
        /* 换桌时不修改订单号
        //modify order_no with new table and type
        //online orders 的编码规则和pos系统里面不一样
        if(strpos($order_no ,"-") !== FALSE){
            $order_no = $type.$table.substr($order_no,strpos($order_no,'-'));
        }else{
            $order_no = $type.$table.substr($order_no,-10);
        }
        */

        //app\View\Pay\index.ctp 付款界面move_order时有该参数
        $ref = @$this->params['named']['ref'];

		    
        /*
        $this->loadModel('Order'); 
          
        // get old order infomation       
        $Order_detail = $this->Order->find("first", array(
            'fields' => array('Order.cashier_id', 'Order.table_no', 'Order.order_type', 'Order.phone'),
            'conditions' => array('Order.order_no' => $order_no),
            'recursive' => -1
                )
        );        
        $restaurant_id = $Order_detail['Order']['cashier_id'];
        $old_type      = $Order_detail['Order']['order_type'];
        $old_table     = $Order_detail['Order']['table_no'];
        $phone         = $Order_detail['Order']['phone'];
        //print kitchen notification when change table(not from online table)
        if($old_type != 'L'){        	
          $this->loadModel('Admin');    
          $printerName = $this->Admin->getKitchenPrinterName($restaurant_id);
          $print = new PrintLib();
        //  $print->printKitchenChangeTable($order_no, $table, $type, $old_table,$old_type, $printerName,true,$phone);
        }
        
        // update order to database 
        // need to quoto the string value(only field new value,not condition)
        $this->Order->updateAll( array('table_no' => $table, 'order_type' =>"'$type'") , array('Order.order_no' => $order_no));
        */        

        $this->Session->setFlash('Order table successfully changed 成功换桌.', 'success');
        if ($ref)
          return $this->redirect(array('controller' => 'pay', 'action' => 'index', 'table' => $table, 'type' => $type));
        else
          return $this->redirect(array('controller' => 'homes', 'action' => 'dashboard'));
    }


    public function inquiry() {

    }

    public function switchLang() {
        $this->layout = false;
        $this->autoRender = NULL;

        $lang = $this->data['lang'];

        $this->Session->write('Config.language', $lang);

        echo $lang;
    }


    public function updateordermessage() {

        // get all params
        $order_id = $this->data['order_id'];
        $message = $this->data['message'];
        $is_kitchen = $this->data['is_kitchen'];

        //Modified by Yishou Liao @ Oct 27 2016.
        $table = $this->data['table'];
        $type = $this->data['type'];
        //End.

        $this->layout = false;
        $this->autoRender = NULL;

        // update message in order table
        $this->loadModel('Order');
        $data = array();
        $data['Order']['id'] = $order_id;
        $data['Order']['message'] = $message;
        $data['Order']['is_kitchen'] = $is_kitchen;
        $data['Order']['is_print'] = 'Y';
        $this->Order->save($data, false);

        if ($is_kitchen == 'Y')
            $this->Session->setFlash('Cooking items successfully sent to kitchen.', 'success');

        //Modified by Yishou Liao @ Oct 27 2016.
        $this->loadModel('Cashier');
        $cashier_detail = $this->Cashier->find("first", array(
            'fields' => array('Cashier.firstname', 'Cashier.lastname', 'Cashier.id', 'Cashier.image', 'Admin.id'),
            'conditions' => array('Cashier.id' => $this->Session->read('Front.id'))
                )
        );

        $this->Order->query("UPDATE order_items,orders SET order_items.is_print = 'Y' WHERE orders.id = order_items.order_id and orders.cashier_id = " . $cashier_detail['Admin']['id'] . " AND  orders.table_no = " . $table . " AND order_items.is_print = 'N' AND orders.is_completed = 'N' AND orders.order_type = '" . $type . "' ");

        //End.

        echo true;
    }


    // check total value #As in Canada, the price is keeping using $9.89, but we don’t have $0.01 now, any amount smaller than 0.02 will be round to 0, more than 0.03 will be round to 0.05.
    function convertoround($amount) {
        $this->layout = false;
        $this->autoRender = false;
        $amount_array = explode(".", $amount);
        if (@$amount_array[1][1] < 3) {
            $afterdot = @$amount_array[1][0] . '0';
        } else if (@$amount_array[1][1] >= 3) {
            $afterdot = @$amount_array[1][0] . '5';
        }
        if ($afterdot) {
            $amount = $amount_array[0] . '.' . $afterdot;
        }
        return $amount;
    }

    public function checkin() {

        $this->layout = false;
        $this->autoRender = NULL;

        // get params
        $userid = $this->data['userid'];

		    $data = $this->Access->checkin(array( 'userid'  => $userid));

        echo $data['message'];
    }

    public function checkout() {

        $this->layout = false;
        $this->autoRender = NULL;

        // get params
        $userid = $this->data['userid'];

		    $data = $this->Access->checkout(array( 'userid'  => $userid));

        echo $data['message'];
/*
        $this->loadModel('Cashier');
        if(empty($this->Cashier->findByUserid($userid))){
        	return "Userid is not valid!";
        }

        $this->loadModel('Attendance');
        
        $time    = date('Y-m-d H:i:s');
        $day     = substr ($time , 0, 10);
        $checkout = substr ($time , -8); 
                
        $data = array();
        $data['Attendance']['userid']    = $userid;
        $data['Attendance']['day']       = $day;
        $data['Attendance']['checkout'] = $checkout;
        
        $id = $this->Attendance->field('id', array('userid' => $userid,'day' => $day,'checkout' => ''));
        if($id != ""){
        	$data['Attendance']['id']  = $id;
        }else{
        	return "Please checkin first!";
        }
        
        $this->Attendance->save($data, false);

        //$this->Session->setFlash('Checkin successfully', 'success');

        echo "Sucess";
*/        
    }

    //End
}
