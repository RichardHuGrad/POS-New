<?php
/**
 * AppShell file
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         CakePHP(tm) v 2.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('Shell', 'Console');
App::uses('ComponentCollection', 'Controller');
App::uses('OrderHandlerComponent', 'Controller/Component');
App::uses('ApiHelperComponent', 'Controller/Component');

/**
 * Application Shell
 *
 * Add your application-wide methods in the class below, your shells
 * will inherit them.
 *
 * @package app.Console.Command
 */
class AppShell extends Shell {
	//const WECHATSERVER = "https://wx.eatopia.ca/";
	const WECHATSERVER="https://pos.auroratech.top/";
	const WECHATTEST = 0;
	
	public $components = array('Paginator','OrderHandler','Access');
	public $no_of_online_tables = 0;
	
    public $fontStr1 = "simsun";
    public $handle;
    public $fontH = 28; // font height
    public $fontW = 10; // font width
	public $uses = array(
			'Admin' 
	);
	
	public function startup() {
		$collection = new ComponentCollection();
		$this->OrderHandler = $collection->load('OrderHandler');
		$this->ApiHelper = $collection->load('ApiHelper');
	}
	
	public function printBigEn($str, $x, $y) {
		$font = printer_create_font("Arial", 32, 14, PRINTER_FW_MEDIUM, false, false, false, 0);
		printer_select_font($this->handle, $font);
		printer_draw_text($this->handle, $str, $x, $y);
		
		printer_delete_font($font);
	}
	
	public function printZh($str, $x, $y, $font_bold = true, $newline=false) {
		if ($font_bold == true) {
			// $font = printer_create_font($this->fontStr1, $this->fontH, $this->fontW, 1500, false, false, false, 0);
			$font = printer_create_font("simsun", 32, 14, 1200, false, false, false, 0);
		} else {
			$font = printer_create_font($this->fontStr1, $this->fontH, $this->fontW, PRINTER_FW_MEDIUM, false, false, false, 0);
		}
		printer_select_font($this->handle, $font);
		if ($newline) {
			$print_str = mb_substr($str, 0, 16);
			printer_draw_text($this->handle, iconv("UTF-8", "gb2312", $print_str), $x, $y);
			$str = mb_substr($str, 16);
			if (mb_strlen($str, 'UTF-8') > 0) {
				printer_draw_text($this->handle, iconv("UTF-8", "gb2312", $str), $x, $y+48);
			}
		} else {
			printer_draw_text($this->handle, iconv("UTF-8", "gb2312", $str), $x, $y);
		}
		printer_delete_font($font);
	}
	
	public function insert_orders($orders) {
        $this->loadModel('Cashier');
        $this->loadModel('OrderItem');
        $this->loadModel('Cousine');
        $this->loadModel('Order');
        $this->loadModel('Admin');
        $this->loadModel('Log');
        $this->loadModel('Extra');
        $this->loadModel('Extrascategory');
        
        $cashier = $this->Cashier->find("first", 
        		array(
        				'fields' => array('Cashier.firstname', 'Cashier.lastname', 'Cashier.id', 'Cashier.image', 'Admin.id'),
        				'conditions' => array('Cashier.is_verified' => 'Y', 'Cashier.status' => 'A')
        				// 'conditions' => array('Cashier.id' => $this->Session->read('Front.id'))
        		)
        );

        $admin = $this->Cashier->find("first",
        		array(
        				'fields' => array('Admin.no_of_tables', 'Admin.no_of_waiting_tables', 'Admin.no_of_takeout_tables', 'Admin.no_of_online_tables', 'Admin.id', 'Admin.tax', 'Admin.default_tip_rate'),
        				'conditions' => array('Cashier.id' => $cashier['Cashier']['id'])
        		)
   		);
        
		foreach ($orders as $order) {
			//Debug print_r($order);
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
			 * 				[id] => 152
			 * 				[img] => images/4/2018/03/qqEz6PEq9pAP6tuGMPKEq969CMpGt9.jpg
			 * 				[number] => 1
			 * 				[order_id] => 58
			 * 				[name] => 双拼双拼
			 * 				[money] => 3.99
			 * 				[uniacid] => 4
			 * 				[dishes_id] => 409
			 * 				[spec] =>
			 * 				[options] => [{"type":"1","name":"\u53e3\u5473","values":[{"name":"\u4e0d\u8fa3","price":0,"quantity":1},{"name":"\u4e0d\u9ebb","price":0,"quantity":2}]},{"type":"0","name":"\u66f4\u6362\u4e3b\u98df","values":[{"name":"\u5c0f\u9762\u6362\u9178\u8fa3\u7c89","price":1,"quantity":1}]},{"type":"2","name":"\u53cc\u62fc","values":[{"name":"\u85d5\u7247","price":0,"quantity":1},{"name":"\u571f\u8c46","price":0,"quantity":1}],"limit":"2","total":"30"}]
			 * 		)
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
					$type = ($order['type'] == 2) ? 'D' : 'L';
					$cashier_id = $cashier['Cashier']['id'];
					$tax_rate = $admin['Admin']['tax'];
					$default_tip_rate = $admin['Admin']['default_tip_rate'];
					$restaurant_id = $admin['Admin']['id'];
					if (empty($order_id)) {
						$Order_detail = $this->Order->find("first",
								array(
										'fields' => array('Order.id', 'Order.subtotal', 'Order.total', 'Order.reason', 'Order.tax_amount', 'Order.discount_value', 'Order.promocode', 'Order.fix_discount', 'Order.percent_discount'),
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
						if ($type == 'L') {
							for ($table = 1; $table <= $this->no_of_online_tables; $table++) {
								$t = $this->Order->find("first",array('conditions' => array('Order.table_no' => $table, 'Order.order_type' => 'L')));
								if (empty($t) || $t['Order']['table_status'] == 'A') {
									// New table or Available table
									break;
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
								$memotxt['error'][] = "Can't find Extrascategory. Remote ID: " . $opt['id'] . "; ";
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
			}
			$this->Order->clear();
		}
	}
	
	public function print_reserve($orders, $printer_name) {
		$print_x = 25;
		foreach($orders as $order) {
			$this->handle = printer_open($printer_name);
			printer_start_doc($this->handle, "order");
			printer_start_page($this->handle);
			
			$print_y = 30;
			$this->printZh("预定餐桌：" . $order['order_num'], $print_x, $print_y);
			$print_y += 48;
			$this->printZh("预计到店时间：" . $order['xz_date'] . " " . $order['yjdd_date'], $print_x, $print_y);
			$print_y += 48;
			$this->printZh("联系人：" . $order['link_name'], $print_x, $print_y);
			$print_y += 48;
			$this->printZh("联系电话：" . $order['link_tel'], $print_x, $print_y);
			$print_y += 48;
			$this->printZh("人数：" . $order['jc_num'], $print_x, $print_y);
			
			printer_end_page($this->handle);
			printer_end_doc($this->handle);
			printer_close($this->handle);
		}
	}
	
	public function main() {
		$rest = $this->Admin->find("first", array(
				/*
				'fields' => array(
						'Admin.id',
						'Admin.print_offset',
						'Admin.is_super_admin',
						'Admin.mobile_no',
						'Admin.no_of_online_tables',
						'Admin.status',
						'Admin.kitchen_printer_device',
						'Admin.service_printer_device' 
				),
				*/
				'conditions' => array(
						'Admin.is_super_admin' => 'N',
						'Admin.status' => 'A' 
				) 
		));

		$tm = strtotime($rest['Admin']['oc_last_push_order_time']);
		$now = time();
		if (($now - $tm) <= 60) {
			echo "Some one running, exit\n";
			exit;
		}
		
		while (TRUE) {
		$this->Admin->id = $rest['Admin']['id'];
		$this->Admin->saveField('oc_last_push_order_time', date("Y-m-d H:i:s"));
		//debug($this->Admin->validationErrors);
		//debug($this->Admin->getDataSource()->getLog(false, false));
		$this->Admin->clear();
		$mobile_no = $rest['Admin']['mobile_no'];
		$this->no_of_online_tables = $rest['Admin']['no_of_online_tables'];
		$dt = preg_split("/-/", $mobile_no);
		if (!is_array($dt) || (sizeof($dt) != 2)) {
			die("Unknow Store ID and Phone");
		}
		
		$url = self::WECHATSERVER . "web/index.php?c=site&a=entry&do=storeorder&m=zh_dianc&version_id=1&sid=" . $dt[0] . "&skey=" . md5($dt[1]);
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

		//print_r($rts);
		if (is_array($rts) && ($rts['status'] == 'OK') && ((sizeof($rts['orders']) > 0) || (sizeof($rts['yyorders']) > 0))) {
			if ($rest['Admin']['no_of_online_tables']) {
				if (sizeof($rts['orders']) > 0) {
					$this->insert_orders($rts['orders']);
				}
				if (sizeof($rts['yyorders']) > 0) {
					$this->print_reserve($rts['yyorders'], $rest['Admin']['service_printer_device']);
				}
				return ;
			}
			$offset = explode(',', $rest['Admin']['print_offset']);
			$printer_name = $rest['Admin']['service_printer_device'];
			
			$print_x = 25;
			foreach ($rts['orders'] as $order) {
				$this->handle = printer_open($printer_name);
				printer_start_doc($this->handle, "order");
				printer_start_page($this->handle);
			
				$print_y = 30;
				$this->printZh("Eatopia食客邦订单   " . (($order['type']==1) ? '外卖' : '堂食 桌号:'.$order['tablename']), $print_x, $print_y);
				$print_y += 48;
				$this->printZh("单号：" . $order['order_num'], $print_x, $print_y);
				$print_y += 48;
				$this->printZh("日期 / 时间： " . $order['time'], $print_x, $print_y);
				
				$pen = printer_create_pen(PRINTER_PEN_SOLID, 2, "000000");
				printer_select_pen($this->handle, $pen);
				printer_draw_line($this->handle, 21, $print_y - 10, 600, $print_y - 10);
				
				foreach ($order['dishes'] as $dish) {
					$print_y += 48;
					$this->printZh($dish['name'], $print_x, $print_y, true, true);
					$this->printZh("$" . $dish['money'], $print_x + 360, $print_y);
					$print_y += 48;
					$this->printZh(" x " . $dish['number'], $print_x + 360, $print_y);
					if (!empty($dish['options']) && ($allopts = json_decode($dish['options'], TRUE))) {
						foreach ($allopts as $opts) {
							if (empty($opts['type']) || ($opts['type'] == 1)) {
								foreach ($opts['values'] as $v) {
									$print_y += 48;
									$this->printZh($v['name'], $print_x + 60, $print_y);
									$this->printZh("$" . number_format($v['price'], 2), $print_x + 340, $print_y);
									if ($v['quantity'] > 1) {
										$print_y += 48;
										$this->printZh(" x " . $v['number'], $print_x + 360, $print_y);
									}
								}
							} else if ($opts['type'] == 2) {
								foreach ($opts['values'] as $v) {
									$print_y += 48;
									$this->printZh($v['name'], $print_x + 60, $print_y);
									$this->printZh("$" . number_format($v['price'], 2), $print_x + 340, $print_y);
									if ($v['quantity'] > 1) {
										$this->printZh(" x " . $v['number'], $print_x + 360, $print_y);
									}
								}
								$print_y += 48;
								$this->printZh("$" . number_format($opts['total'], 2), $print_x + 340, $print_y);
							}
						}
					}
				}
				$print_y += 48;
				$this->printZh("总计：", $print_x + 100, $print_y, true);
				$this->printZh($order['money'], $print_x + 300, $print_y);
				if (!empty($order['note'])) {
					$print_y += 48;
					$this->printZh("留言：" . $order['note'], $print_x + 100, $print_y);
				}
		        printer_end_page($this->handle);
		        printer_end_doc($this->handle);
		        printer_close($this->handle);
			}
		}
		//echo "Sleep 15 second";
		sleep(15);
		}
	}
}
