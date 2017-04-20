<?php
class DbHandler {
	
	public $fontStr1 = "simsun";
    public $handle;
    public $fontH = 28; // font height
    public $fontW = 10; // font width

    public $itemLineLen = 180;
    // public $charNo = $this->itemLineLen / $this->fontW;
    public $charNo = 20;
    public $lineStartPos = 10;
	private $conn;
	
	function __construct() {
		require_once dirname(__FILE__) . '/DbConnect.php';
		// opening db connection
		$db = new DbConnect();
		$this->conn = $db->connect();
	}
	
	/**
     * This function used to insert data in the table passed as parameter
     * @params $data array 
     * @params $table string 
     * @return bool
     */
    function insertData($data = NULL, $table="") {
		$saveData = array();
		foreach ($data as $key => $val) {
			if ($val != '') {
				$escKey = '`' . $key . '`';
				$saveData[$escKey] = "'" . $val . "'";
			}
		}
		if (!empty($saveData)) {
			$columns = implode(',', array_keys($saveData));
			$values = implode(',', array_values($saveData));
			$sql = "INSERT INTO $table ($columns) VALUES ($values)";
			//return $sql;exit;
			$result = mysql_query($sql);
			if ($result) {
				return mysql_insert_id($this->conn);
			}
			else
			{
				return mysql_error($this->conn);
				//return 0;
			}
		}
		return false;
    }
	
	/**
     * This function used to update data in any table
     * @params array $data update data 
     * @params int $id id primary key
	 * @params string $table Table name
     * @return boolean
     */
    function updateData($data = NULL, $table="", $id = 0) {
	
		$setPart = array();
		$bindings = array();

		foreach ($data as $key => $value) {
			if ($value != '') {
			$setPart[] = "`$key` = '$value'";
			}
		}

		$sql = "UPDATE {$table} SET " . implode(', ', $setPart) . " WHERE id = $id";

		$res = mysqli_query($this->conn, $sql);
		if(is_numeric(mysqli_affected_rows($this->conn)))
			return true;
		else
			return false;
    }

	public function cashierLogin($username, $password, $devicetoken, $deviceid) {
		$regAt = date('Y-m-d H:i:s');
		$userData = $this->isUsernameExists($username);
		if (!$userData) {
			return 'INVALID_USERNAME';
		} else {
			if ($userData['password'] != md5($password)) {
					return 'INVALID_USERNAME_PASSWORD';
			}
			if ($userData['status'] == 'I') {
				return 'USER_ACCOUNT_DEACTVATED';
			}
			$update_token = "UPDATE cashiers set ipad_deviceid='', ipad_devicetoken='', logintype='NA' where ipad_deviceid='$deviceid'";
			$up_token = mysql_query($update_token);

			$update_user = "UPDATE cashiers set ipad_devicetoken='$devicetoken', ipad_deviceid='$deviceid', logintype='M' where id=".$userData['id'];
			$up_user = mysql_query($update_user);
			$userData = $this->getUserById($userData['id']);
			return $userData;
		}
	}
	
	public function cashierLogout($userid) { 
		$cashierUpdate="update cashiers set ipad_deviceid='', ipad_devicetoken='', logintype='NA' where id=$userid";
		if(mysql_query($cashierUpdate)) {
			return 'SUCCESSFULLY_DONE';  
		} else {
			return 'UNABLE_TO_PROCEED';  
		}
	}

	public function cashierTables($userid) {
		$userData = $this->getUserById($userid);
		/*for($i=1; $i<=$userData['no_of_takeout_tables']; $i++) {
			$take_row=$i-1;
			$takeout[$take_row]['table_no'] = 'T'.$i;
			$takeout[$take_row]['order_no'] = '';
			$takeout[$take_row]['order_type'] = 'A';
			$takeout[$take_row]['created'] = '';
		}*/

		for($i=1; $i<=$userData['no_of_tables']; $i++) {
			$dine_row=$i-1;
			$dinein[$dine_row]['order_id'] = "0";
			$dinein[$dine_row]['table_no'] = "$i";
			$dinein[$dine_row]['order_no'] = '';
			$dinein[$dine_row]['order_type'] = 'A';
			$dinein[$dine_row]['created'] = '';
			$dinein[$dine_row]['table_color'] = 'AVAILABLE';
		}

		/*for($i=1; $i<=$userData['no_of_waiting_tables']; $i++) {
			$wait_row=$i-1;
			$waiting[$wait_row]['table_no'] = 'T'.$i;
			$waiting[$wait_row]['order_no'] = '';
			$waiting[$wait_row]['order_type'] = 'A';
			$waiting[$wait_row]['created'] = '';
		}*/

		$output = array();
		$takeOrder_row="select o.* from orders o where o.cashier_id='".$userData['restaurant_id']."' and is_completed='N' and order_type='D' order by table_no asc";
		if ($takeOrder_row_res = mysql_query($takeOrder_row)) {
			if(count($takeOrder_row_res)>0) {
				while ($arr = mysql_fetch_assoc($takeOrder_row_res)) {

					/*$take_key = array_search('T'.$arr['table_no'], array_column($takeout, 'table_no'));
					if ($take_key>=0 && $arr['order_type']=='T') {
						$takeout[$take_key]['order_no'] = $arr['order_no'];
						$takeout[$take_key]['order_type'] = $arr['order_type'];
						$takeout[$take_key]['created'] = $arr['created'];
					}*/

					$dine_key = array_search($arr['table_no'], array_column($dinein, 'table_no'));
					if ($dine_key>=0 && $arr['order_type']=='D') {
						$dinein[$dine_key]['order_id'] = $arr['id'];
						$dinein[$dine_key]['order_no'] = $arr['order_no'];
						$dinein[$dine_key]['order_type'] = $arr['order_type'];
						$dinein[$dine_key]['created'] = $arr['created'];
						if($arr['reservation_id']>0) {
							$dinein[$dine_key]['table_color'] = 'RESERVED';
						} else {
							$dinein[$dine_key]['table_color'] = 'OCCUPIED';
						}
					}

				   
					/*$wait_key = array_search('T'.$arr['table_no'], array_column($waiting, 'table_no'));
					if ($take_key>=0 && $arr['order_type']=='W') {
						$waiting[$take_key]['order_no'] = $arr['order_no'];
						$waiting[$take_key]['order_type'] = $arr['order_type'];
						$waiting[$take_key]['created'] = $arr['created'];
					}*/
				}
			} 
			$output=array();
			//$output['takeout']=$takeout;
			$output['dinein']=$dinein;
			//$output['waiting']=$waiting;
			return $output;
		} else {
			return 'UNABLE_TO_PROCEED';
		}
	}

	public function pendingOrders($userid, $type, $tableno) {
		$userData = $this->getUserById($userid);
		$order_row="select o.* from orders o where o.order_type='$type' and is_completed='N' and o.cashier_id='".$userData['restaurant_id']."'";
		if ($tableno>0)
			$order_row.=" and o.table_no = '$tableno'";
		$order_row.=" order by o.id desc";

		if ($order_res = mysql_query($order_row)) {
			$num_rows = mysql_num_rows($order_res);
			if ($num_rows > 0) {
				$output = array();
				$count=0;
				while ($arr = mysql_fetch_assoc($order_res)) {
					$output[$count]['id'] = $arr['id'];
					$output[$count]['order_no'] = $arr['order_no'];
					$output[$count]['table_no'] = $arr['table_no'];
					$output[$count]['tax'] = number_format($arr['tax'],2);
					$output[$count]['tax_amount'] = number_format($arr['tax_amount'],2);
					$output[$count]['subtotal'] = number_format($arr['subtotal'],2);
					$output[$count]['total'] = number_format($arr['total'],2);
					$output[$count]['created'] = $arr['created'];
					$output[$count]['name'] = $arr['name'];
					$output[$count]['noofperson'] = $arr['noofperson'];
					$output[$count]['phoneno'] = $arr['phoneno'];
					$output[$count]['takeout_date'] = $arr['takeout_date'];
					$output[$count]['takeout_time'] = $arr['takeout_time'];
					$output[$count]['reservation_id'] = $arr['reservation_id'];
					$output[$count]['promocode'] = $arr['promocode'];
					$output[$count]['fix_discount'] = number_format($arr['fix_discount'],2);
					$output[$count]['percent_discount'] = number_format($arr['percent_discount'],2);
					$output[$count]['discount_value'] = number_format($arr['discount_value'],2);
					$orderItemsArr=$this->getOrderItems($arr['id']);
					if (count($orderItemsArr)>0) {
						$i=0;
						while ($itemArr = mysql_fetch_assoc($orderItemsArr)) {
							$output[$count]['items'][$i]['id']=$itemArr['id'];
							$output[$count]['items'][$i]['item_id']=$itemArr['item_id'];
							$output[$count]['items'][$i]['name_en']=$itemArr['name_en'];
							$output[$count]['items'][$i]['name_zh']=$itemArr['name_xh'];
							$output[$count]['items'][$i]['price']=$itemArr['price'];
							$output[$count]['items'][$i]['qty']=$itemArr['qty'];
							$output[$count]['items'][$i]['tax']=number_format($itemArr['tax'],2);
							$output[$count]['items'][$i]['tax_amount']=number_format($itemArr['tax_amount'],2);
							$output[$count]['items'][$i]['discount']=number_format($itemArr['discount'],2);
							//$output[$count]['items'][$i]['selected_extras']=$itemArr['selected_extras'];
							$tempJSON = json_decode($itemArr['selected_extras']);
							$output[$count]['items'][$i]['selected_extras']=empty($itemArr['selected_extras']) || count($tempJSON)<1?NULL:$itemArr['selected_extras'];
							
							$output[$count]['items'][$i]['all_extras']=$itemArr['all_extras'];
							$output[$count]['items'][$i]['extras_amount']=number_format($itemArr['extras_amount'],2);
							$output[$count]['items'][$i]['delivery_type']=$itemArr['delivery_type'];
							
							$extrasPrice = 0;
							if(!empty($output[$count]['items'][$i]['selected_extras']))
							{
								$extrasArr = json_decode($output[$count]['items'][$i]['selected_extras'], true);
								foreach($extrasArr AS $extra)
								{
									//die("hello");
									$extrasPrice += $extra['price'];
									//$output[$count]['items'][$i]['extrasPrice'][] = $extrasPrice;
								}
							}
							$output[$count]['items'][$i]['price_with_extras']=number_format(($itemArr['price']+$extrasPrice), 2);

							$output[$count]['items'][$i]['actual_unit_price']=number_format($itemArr['actual_unit_price'],2);
							$output[$count]['items'][$i]['order_unit_price']=number_format($itemArr['order_unit_price'],2);

							$output[$count]['items'][$i]['is_print']=$itemArr['is_print'];
							$output[$count]['items'][$i]['category_id']=$itemArr['category_id'];
							$output[$count]['items'][$i]['is_kitchen']=$itemArr['is_kitchen'];

							$i++;
						}
					} else {
						$output[$count]['items']=array();
					}
					$count++;
				}
				return $output;
			} else {
				return 'NO_RECORD_FOUND';
			}
		} else {
			return 'UNABLE_TO_PROCEED';
		}
	}

	public function items($userid, $categoryid) {
		$categories_row="select c.id, c.printer, (select group_concat(name SEPARATOR '----') as itemname from category_locales cl where cl.category_id=c.id) as catname from categories c where c.status='A'";
		if ($categoryid>0)
			$categories_row.=" and c.id=$categoryid";
		if ($categories_res = mysql_query($categories_row)) {
			$num_rows = mysql_num_rows($categories_res);
			if ($num_rows > 0) {
				$output = array();
				$count=0;
				while ($arr = mysql_fetch_assoc($categories_res)) {
					$output[$count]['id'] = $arr['id'];
					$catNameArr=explode('----', $arr['catname']);

					$output[$count]['catname_en'] = $catNameArr[0];
					$output[$count]['catname_zh'] = $catNameArr[1];
					$output[$count]['printer'] = $arr['printer'];

					$itemsArr=$this->getCategoryItems($arr['id']);
					if (count($itemsArr)>0) {
						$i=0;
						while ($itemArr = mysql_fetch_assoc($itemsArr)) {
							$output[$count]['items'][$i]['id']=$itemArr['id'];
							$itemNameArr=explode('----', $itemArr['itemname']);
							$output[$count]['items'][$i]['itemname_en'] = $itemNameArr[0];
							$output[$count]['items'][$i]['itemname_zh'] = $itemNameArr[1];
							$output[$count]['items'][$i]['price']=number_format($itemArr['price'], 2);
							$output[$count]['items'][$i]['is_tax']=$itemArr['is_tax'];
							$i++;
						}
					} else {
						$output[$count]['items']=array();
					}
					$count++;
				}
				return $output;
			} else {
				return 'NO_RECORD_FOUND';
			}
		} else {
			return 'UNABLE_TO_PROCEED';
		}
	}

	public function extras($userid, $type) {
		$extras_row="select * from extras where status='A'";
		if ($type!='0')
			$extras_row.=" and type='$type'";
		if ($extras_res = mysql_query($extras_row)) {
			$num_rows = mysql_num_rows($extras_res);
			if ($num_rows > 0) {
				$output = array();
				$count=0;
				while ($arr = mysql_fetch_assoc($extras_res)) {
					$output[$count]['id'] = $arr['id'];
					$output[$count]['name_en'] = $arr['name'];
					$output[$count]['name_zh'] = $arr['name_zh'];
					$output[$count]['price'] = number_format($arr['price'], 2);
					$count++;
				}
				return $output;
			} else {
				return 'NO_RECORD_FOUND';
			}
		} else {
			return 'UNABLE_TO_PROCEED';
		}
	}

	public function reservation($userid, $name, $noofperson, $phoneno, $date, $time, $required) {
		$userData = $this->getUserById($userid);
		$createdat=date('Y-m-d H:i:s');
		$reserve_query = "insert into reservations set restaurant_id='".$userData['restaurant_id']."', cashier_id='$userid', name='$name', noofperson='$noofperson',phoneno='$phoneno', reserve_date='$date', reserve_time='$time', required='$required', created='$createdat'";
		if(mysql_query($reserve_query)) {
			$insertId = mysql_insert_id();
			return $insertId;
		} else {
			return 'UNABLE_TO_PROCEED';
		}
	}

	public function cancelReservation($userid, $reservationid, $reason) {
		$cancelledat=date('Y-m-d H:i:s');
		$reserve_query = "update reservations set cancelledby='$userid', cancelledreason='$reason', cancelledat='$cancelledat', status='C' where id=$reservationid";
		if(mysql_query($reserve_query)) {
			return 'SUCCESSFULLY_DONE';
		} else {
			return 'UNABLE_TO_PROCEED';
		}
	}

	public function reservations($userid, $type) {
		$reservations_row="select * from reservations";
		if ($type!='0')
			$reservations_row.=" where status='$type'";

		$reservations_row.=" order by id desc";
		if ($reservations_res = mysql_query($reservations_row)) {
			$num_rows = mysql_num_rows($reservations_res);
			if ($num_rows > 0) {
				$output = array();
				$count=0;
				while ($arr = mysql_fetch_assoc($reservations_res)) {
					$output[$count]['id'] = $arr['id'];
					$output[$count]['name'] = $arr['name'];
					$output[$count]['noofperson'] = $arr['noofperson'];
					$output[$count]['phoneno'] = $arr['phoneno'];
					$output[$count]['reserve_date'] = $arr['reserve_date'];
					$output[$count]['reserve_time'] = $arr['reserve_time'];
					$output[$count]['required'] = $arr['required'];
					$output[$count]['status'] = $arr['status'];
					$output[$count]['created'] = $arr['created'];
					$count++;
				}
				return $output;
			} else {
				return 'NO_RECORD_FOUND';
			}
		} else {
			return 'UNABLE_TO_PROCEED';
		}
	}

	public function updateReservation($userid, $reservationid, $name, $noofperson, $phoneno, $date, $time, $required) {
		$modified=date('Y-m-d H:i:s');
		$reserve_query = "update reservations set name='$name', noofperson='$noofperson',phoneno='$phoneno', reserve_date='$date', reserve_time='$time', required='$required', modified='$modified' where id=$reservationid";
		if(mysql_query($reserve_query)) {
			return 'SUCCESSFULLY_DONE';
		} else {
			return 'UNABLE_TO_PROCEED';
		}
	}

	public function reserveTable($userid, $reservationid, $tableno) {
		$reservedat=date('Y-m-d H:i:s');
		if(!$this->isTableAvailable($tableno)) {
			if($reserveData=$this->isValidReservationId($reservationid)) {
				if ($reserveData['status']=='C') {
					return 'ALREADY_CANCELLED';
				}
				if ($reserveData['status']=='A') {
					return 'ALREADY_ASSIGNED';
				}
				$reserve_query = "update reservations set reservedby='$userid', reservedat='$reservedat', status='A' where id=$reservationid";
				if(mysql_query($reserve_query)) {
					$userData = $this->getUserById($userid);
					$order_insert = "insert into orders set cashier_id='".$userData['restaurant_id']."', counter_id='$userid', table_no='$tableno', order_type='D', created='$reservedat', reservation_id='$reservationid', tax='".$userData['tax']."', tax_amount='0', subtotal='0', total='0'";
					$OrderInsert=mysql_query($order_insert);
					$orderId = mysql_insert_id();
					$order_no=str_pad($orderId, 5, rand(98753, 87563), STR_PAD_LEFT);

					$order_update = "update orders set order_no='$order_no' where id=$orderId";
					$OrderUpdate=mysql_query($order_update);
					return 'SUCCESSFULLY_DONE';
				} else {
					return 'UNABLE_TO_PROCEED';
				}
			} else {
				return 'INVALID_RESERVATION';
			}
		} else {
			return 'TABLE_ALREADY_OCCUPIED';
		}
	}

	public function makeOrderDineIn($userid, $type, $itemdata, $tableno) {
		$orderedat=date('Y-m-d H:i:s');
		if(!$this->isTableAvailable($tableno)) {
			$itemdataArr=json_decode($itemdata);
			$userData = $this->getUserById($userid);
			$order_insert = "insert into orders set cashier_id='".$userData['restaurant_id']."', counter_id='$userid', table_no='$tableno', order_type='D', created='$orderedat', tax='".$userData['tax']."'";
			if(mysql_query($order_insert)) {
				$orderId = mysql_insert_id();
				$order_no=str_pad($orderId, 5, rand(98753, 87563), STR_PAD_LEFT);
				$order_update = "update orders set order_no='$order_no' where id=$orderId";
				$OrderUpdate=mysql_query($order_update);
				$totItems=count($itemdataArr);
				$totprice=0;
				$totextra=0;
				$tottax=0;
				if ($totItems>0) {
					for ($i=0; $i<$totItems; $i++) {
						$selectedextras=json_encode($itemdataArr[$i]->selectedextras);
						$allextras=json_encode($itemdataArr[$i]->allextras);
						$itemData=$this->getItemData($itemdataArr[$i]->itemid);
						$itemnameArr=explode('----', $itemData['cousine']);
						$itemNameEn=$itemnameArr[0];
						$itemNameZh=$itemnameArr[1];
						
						$price=$itemData['price'] * $itemdataArr[$i]->qty;
						$extraprice = 0;
						foreach($itemdataArr[$i]->selectedextras as $num => $values) {
						   $extraprice += $values->price;
						}
						$totprice += $price;
						$totextra += $extraprice;

						$itemPrice=$itemData['price'] * $itemdataArr[$i]->qty;
						$itemTot= $itemPrice + $totextra;
					    $taxAmount= $itemTot * $userData['tax'] / 100;
					    $tottax +=$taxAmount;

						$orderitem_insert = "insert into order_items set order_id='$orderId', item_id='".$itemdataArr[$i]->itemid."', name_en='$itemNameEn', name_xh='$itemNameZh', category_id='".$itemData['category_id']."', price='$price', qty='".$itemdataArr[$i]->qty."', tax='".$userData['tax']."', tax_amount='$taxAmount', selected_extras='$selectedextras', all_extras='$allextras', extras_amount='$extraprice', actual_unit_price='".$itemData['price']."', order_unit_price='".$itemData['price']."'";
						$items_added=mysql_query($orderitem_insert);
					}
				}
				$subTotal= $totprice + $totextra;
				$tax = $tottax;
				$orderTotal= $subTotal + $tax;
				
				$orderPrice_update = "update orders set tax_amount='$tax', subtotal='$subTotal', total='$orderTotal' where id=$orderId";
				$orderPriceUpdate=mysql_query($orderPrice_update);
				$res['order_no']=$order_no;
				$res['order_id']=$orderId;
				return $res;
			} else {
				return 'UNABLE_TO_PROCEED';
			}
		} else {
			return 'TABLE_ALREADY_OCCUPIED';
		}
	}

	public function makeOrderOther($userid, $type, $name, $noofperson, $phoneno, $takeout_date, $takeout_time) {
		$orderedat=date('Y-m-d H:i:s');

		$userData = $this->getUserById($userid);
		$order_insert = "insert into orders set cashier_id='".$userData['restaurant_id']."', counter_id='$userid', table_no='0', order_type='$type', created='$orderedat', tax='".$userData['tax']."', name='$name', noofperson='$noofperson', phoneno='$phoneno', tax_amount='0', subtotal='0', total='0', card_val='0', cash_val='0', tip='0', paid='0', `change`='0', fix_discount='0', percent_discount='0', discount_value='0'";
		if($type=='T') {
			$order_insert .= ", takeout_date='$takeout_date', takeout_time='$takeout_time'";
		}
		if(mysql_query($order_insert)) {
			$orderId = mysql_insert_id();
			$order_no=str_pad($orderId, 5, rand(98753, 87563), STR_PAD_LEFT);
			$order_update = "update orders set order_no='$order_no' where id=$orderId";
			$OrderUpdate=mysql_query($order_update);
			$res['order_no']=$order_no;
			$res['order_id']=$orderId;
			return $res;
		} else {
			return 'UNABLE_TO_PROCEED';
		}
	}

	public function addOrderItem($userid, $itemdata, $orderid) {
		$orderedat=date('Y-m-d H:i:s');
		if($orderData=$this->isOrderExist($orderid)) {
			if ($orderData['is_completed']=='Y') {
				return 'ALREADY_COMPLETED';
			}
			$itemdataArr=json_decode($itemdata);
			$userData = $this->getUserById($userid);

			$totItems=count($itemdataArr);
			$totprice=0;
			$totextra=0;
			if ($totItems>0) {
				for ($i=0; $i<$totItems; $i++) {
					$selectedextras=json_encode($itemdataArr[$i]->selectedextras);
					$allextras=json_encode($itemdataArr[$i]->allextras);
					$itemData=$this->getItemData($itemdataArr[$i]->itemid);
					$itemnameArr=explode('----', $itemData['cousine']);
					$itemNameEn=$itemnameArr[0];
					$itemNameZh=$itemnameArr[1];
					
					$price=$itemData['price'] * $itemdataArr[$i]->qty;
					$extraprice = 0;
					/* foreach($itemdataArr[$i]->selectedextras as $num => $values) {
					   $extraprice += $values->price;
					} */
					//Changes by Abhishek
					foreach($selectedextras as $num => $values) {
					   $extraprice += $values->price;
					}

					$totprice += $price;
					$totextra += $extraprice;
					$itemPrice=$itemData['price'] * $itemdataArr[$i]->qty;
					$itemTot= $itemPrice + $totextra;
					$taxAmount= $itemTot / $userData['tax'];
					$discount=0;
					// if($orderData['promocode']!='' || !is_null($orderData['promocode'])) {
					//     $discount=$this->getDiscountOnItem($itemdataArr[$i], $orderData['promocode']);
					// }

					

					$orderitem_insert = "insert into order_items set order_id='$orderid', item_id='".$itemdataArr[$i]->itemid."', name_en='$itemNameEn', name_xh='$itemNameZh', category_id='".$itemData['category_id']."', price='$price', qty='".$itemdataArr[$i]->qty."', tax='".$userData['tax']."', tax_amount='$taxAmount', selected_extras='$selectedextras', all_extras='$allextras', extras_amount='$extraprice', actual_unit_price='".$itemData['price']."', order_unit_price='".$itemData['price']."'";
					$items_added=mysql_query($orderitem_insert);
				}
			}
			$orderTotal = $totprice + $totextra;

			if ($orderData['fix_discount']>0 || $orderData['percent_discount']>0){
				if ($orderData['fix_discount']>0) {
					$subTotal= $orderTotal - $orderData['fix_discount'];
				}
				if ($orderData['percent_discount']>0) {
					$subTotal= $orderTotal * $orderData['percent_discount'] / 100;
				}
			} else {
				$subTotal= $orderTotal;
			}
			$tax = $subTotal * $userData['tax'] / 100;
			$total= $subTotal + $tax;

			$orderPrice_update = "update orders set tax_amount=tax_amount + $tax, subtotal=subtotal + $subTotal, total=total + $total where id=$orderid";
			$orderPriceUpdate=mysql_query($orderPrice_update);
			return $orderData['order_no'];
		} else {
			return 'INVALID_ORDERID';
		}
	}

	public function removeOrderItem($userid, $itemdata, $orderid) {
		$orderedat=date('Y-m-d H:i:s');
		if($orderData=$this->isOrderExist($orderid)) {
			if ($orderData['is_completed']=='Y') {
				return 'ALREADY_COMPLETED';
			}
			$itemdataArr=json_decode($itemdata);
			$userData = $this->getUserById($userid);
			$totItems=count($itemdataArr);
			$totprice=0;
			$totextra=0;
			$taxAmount=0;
			if ($totItems>0) {
				for ($i=0; $i<$totItems; $i++) {
					$itemData=$this->getOrderItemData($itemdataArr[$i]->rowid);
					$totprice += $itemData ['price'];
					$totextra += $itemData['extras_amount'];
					$taxAmount = $itemData['tax_amount'];
					$orderitem_remove = "delete from order_items where id=".$itemdataArr[$i]->rowid;
					$items_removed=mysql_query($orderitem_remove);
				}
			}
			$orderTotal = $totprice;

			if ($orderData['fix_discount']>0 || $orderData['percent_discount']>0){
				if ($orderData['fix_discount']>0) {
					$subTotal= $orderTotal - $orderData['fix_discount'];
				}
				if ($orderData['percent_discount']>0) {
					$subTotal= $orderTotal * $orderData['percent_discount'] / 100;
				}

			} else {
				$subTotal= $orderTotal;
			}
			$tax = $subTotal * $userData['tax'] / 100;
    
            if(count($this->getOrderItems($orderid))==0) {
            	$orderPrice_update = "update orders set tax_amount='0', subtotal='0', total='0' where id=$orderid";
            } else {
			   /* $orderTotal = $totprice;
				$tax = $taxAmount;
				$subTotal= $orderTotal - $taxAmount;*/
				$total=$orderTotal + $tax;
				$orderPrice_update = "update orders set tax_amount=tax_amount - $tax, subtotal=subtotal - $subTotal, total=total - $total where id=$orderid";
		    }
			$orderPriceUpdate=mysql_query($orderPrice_update);
			return $orderid;
		} else {
			return 'INVALID_ORDERID';
		}
	}

	public function changeTable($user_id, $tableno, $newtableno, $orderid) {
		$reservedat=date('Y-m-d H:i:s');
		if(!$this->isTableAvailable($newtableno)) {
			$order_insert = "update orders set table_no='$newtableno', order_type='D' where id=$orderid";
			if(mysql_query($order_insert)) {
				return 'SUCCESSFULLY_UPDATED';
			} else {
				return 'UNABLE_TO_PROCEED';
			}
		} else {
			return 'TABLE_ALREADY_OCCUPIED';
		}
	}

	public function applyDiscount($userid, $type, $value, $orderid) {
		$this->removePromocode($orderid, $value);
		$orderedat=date('Y-m-d H:i:s');
		if($orderData=$this->isOrderExist($orderid)) {
			if ($orderData['is_completed']=='Y') {
				return 'ALREADY_COMPLETED';
			}
			$promocode='';
			$fix_discount=0;
			$percent_discount=0;

			if ($type=='F') {
				$subTotal=$orderData['subtotal'] - $value;
				$tax= $subTotal * $orderData['tax'] / 100;
				$orderTotal= $subTotal + $tax;
				$fix_discount=$value;
				$discount_value=$value;
			} else if ($type=='P') {
				$discount_value = ($orderData['subtotal'] + $orderData['discount_value']) * $value / 100;
				$subTotal= ($orderData['subtotal'] + $orderData['discount_value']) - $discount_value;
				$tax= $subTotal * $orderData['tax'] / 100;
				$orderTotal= $subTotal + $tax;
				$percent_discount=$value;
			} else {
				$todayDate=date('Y-m-d');
				$todayTime=date('H:i:s');
				$today=strtolower(date('l'));
				if($isPromoValid=$this->verifyPromocode($value, $orderid)) {
					$userData = $this->getUserById($userid);
					if($userData['restaurant_id']!=$isPromoValid['restaurant_id']) {
						return 'NOT_VALID_FOR_THIS_RESTAURANT';
					}

					if ($isPromoValid['valid_from']<=$todayDate && $isPromoValid['valid_to']>=$todayDate) {
						if ($isPromoValid['valid_from']==$todayDate && $isPromoValid['start_time']>=$todayTime) {
							return 'PROMO_NOT_STARTED';
						}
						if ($isPromoValid['valid_to']==$todayDate && $isPromoValid['end_time']<=$todayTime) {
							return 'PROMO_EXPIRED_TIME';
						}
						$weekDaysArr=explode(',', $isPromoValid['week_days']);
						if($isDayExist=in_array($today, $weekDaysArr)) {
							if ($isPromoValid['category_id']==0) {
								if($isPromoValid['discount_type']==0) {
									$subTotal=$orderData['subtotal'] - $isPromoValid['discount_value'];
									$tax= $subTotal * $orderData['tax'] / 100;
									$orderTotal= $subTotal + $tax;
									$fix_discount=$isPromoValid['discount_value'];
									$discount_value=$isPromoValid['discount_value'];
									$promocode=$value;
								} else {
									$discount_value=$orderData['subtotal'] / $isPromoValid['discount_value'];
									$subTotal=$orderData['subtotal'] - $discount_value;
									$tax= $subTotal * $orderData['tax'] / 100;
									$orderTotal= $subTotal + $tax;
									$percent_discount=$isPromoValid['discount_value'];
									$promocode=$value;
								}
							} else {
								$promocode=$value;
								$orderItem=$this->getOrderItems($orderid);
								$count = 0;
								while ($itemArr = mysql_fetch_assoc($orderItem)) {
									$discount=0;
									if ($isPromoValid['item_id']>0) {            
										if($itemArr['item_id']==$isPromoValid['item_id']) {
											if($isPromoValid['discount_type']==0) {
												$itemTot=$itemArr['price'] + $itemArr['extra_amount'];
												if($itemTot>=$isPromoValid['discount_value']) {
													$discount=$isPromoValid['discount_value'];
												} else {
													$discount=$itemTot;
												}
											} else {
												$discount= ($itemArr['price'] + $itemArr['extra_amount']) * $isPromoValid['discount_value'] / 100;
											}
										}
									} else {
										if($itemArr['category_id']==$isPromoValid['category_id']) {
											if($isPromoValid['discount_type']==0) {
												$itemTot=$itemArr['price'] + $itemArr['extra_amount'];
												if($itemTot>=$isPromoValid['discount_value']) {
													$discount=$isPromoValid['discount_value'];
												} else {
													$discount=$itemTot;
												}
											} else {
												$discount = ($itemArr['price'] + $itemArr['extra_amount']) * $isPromoValid['discount_value'] / 100;
											}
										}
									}
									$discountArr[]=$discount;
									if($discount>0) {
										$orderItem_update = "update order_items set discount=$discount where id=".$itemArr['id'];
										$orderItemUpdate=mysql_query($orderItem_update);
									}
								}
								$discount_value=array_sum($discountArr);
								$subTotal=$orderData['subtotal'] - $discount_value;
								$tax= $subTotal * $orderData['tax'] / 100;
								$orderTotal= $subTotal + $tax;
								$percent_discount=$isPromoValid['discount_value'];
								$promocode=$value;
							}
						} else {
							return 'DAY_NOT_EXIST';
						}
					} else {
						return 'PROMO_EXPIRED_DATE';
					}
				} else {
					return 'INVALID_PROMOCODE';
				}              
			}
			$orderPrice_update = "update orders set tax_amount=$tax, subtotal=$subTotal, total=$orderTotal, promocode='$promocode', fix_discount='$fix_discount', discount_value='$discount_value', percent_discount='$percent_discount' where id=$orderid";
			$orderPriceUpdate=mysql_query($orderPrice_update);
			return $orderData['order_no'];
		} else {
			return 'INVALID_ORDERID';
		}
	}

	public function orderItemExtras($user_id, $orderid, $rowid) {
		$extras_row="select selected_extras from order_items where id=$rowid";
		if ($extras_res = mysql_query($extras_row)) {
			$num_rows = mysql_num_rows($extras_res);
			if ($num_rows > 0) {
				$output = array();
				$count=0;
				$jsonData = mysql_fetch_assoc($extras_res);
				$extras=json_decode($jsonData['selected_extras']);

				for ($i=0; $i < count($extras); $i++) {
					$output[$count]['id'] = $extras[$i]->id;
					$output[$count]['name_en'] = $extras[$i]->name;
					$output[$count]['name_zh'] = $extras[$i]->name_zh;
					$output[$count]['price'] = number_format($extras[$i]->price, 2);
					$count++;
				}
				return $output;
			} else {
				return 'NO_RECORD_FOUND';
			}
		} else {
			return 'UNABLE_TO_PROCEED';
		}
	}

	public function orderHistory($userid, $type, $tableno, $page) {
		$todayDate=date('Y-m-d');
		$offset = 20;
		$limit = ($page - 1) * 20;
		$userData = $this->getUserById($userid);
		//$order_row="select o.* from orders o where o.order_type='$type' and table_no='$tableno' and DATE(created)='$todayDate'";
		$order_row="select o.* from orders o where o.order_type='$type' and table_no='$tableno'";
		$order_row.=" order by o.id desc";
		if($page > 0)
			$order_row .= " limit $limit, $offset";

		if ($order_res = mysql_query($order_row)) {
			$num_rows = mysql_num_rows($order_res);
			if ($num_rows > 0) {
				$output = array();
				$count=0;
				while ($arr = mysql_fetch_assoc($order_res)) {
					$output[$count]['id'] = $arr['id'];
					$output[$count]['order_no'] = $arr['order_no'];
					$output[$count]['table_no'] = $arr['table_no'];
					$output[$count]['tax'] = number_format($arr['tax'], 2);
					$output[$count]['tax_amount'] = number_format($arr['tax_amount'], 2);
					$output[$count]['subtotal'] = number_format($arr['subtotal'], 2);
					$output[$count]['total'] = number_format($arr['total'], 2);
					$output[$count]['created'] = $arr['created'];
					$output[$count]['name'] = $arr['name'];
					$output[$count]['noofperson'] = $arr['noofperson'];
					$output[$count]['phoneno'] = $arr['phoneno'];
					$output[$count]['takeout_date'] = $arr['takeout_date'];
					$output[$count]['takeout_time'] = $arr['takeout_time'];
					$output[$count]['is_completed'] = $arr['is_completed'];

					$output[$count]['promocode'] = $arr['promocode'];
					$output[$count]['fix_discount'] = number_format($arr['fix_discount'], 2);
					$output[$count]['percent_discount'] = number_format($arr['percent_discount'], 2);
					$output[$count]['discount_value'] = number_format($arr['discount_value'], 2);
					$orderItemsArr=$this->getOrderItems($arr['id']);
					if (count($orderItemsArr)>0) {
						$i=0;
						while ($itemArr = mysql_fetch_assoc($orderItemsArr)) {
							$output[$count]['items'][$i]['id']=$itemArr['id'];
							$output[$count]['items'][$i]['item_id']=$itemArr['item_id'];
							$output[$count]['items'][$i]['name_en']=$itemArr['name_en'];
							$output[$count]['items'][$i]['name_zh']=$itemArr['name_xh'];
							$output[$count]['items'][$i]['price']=number_format($itemArr['price'], 2);
							$output[$count]['items'][$i]['qty']=$itemArr['qty'];
							$output[$count]['items'][$i]['tax']=number_format($itemArr['tax'], 2);
							$output[$count]['items'][$i]['tax_amount']=number_format($itemArr['tax_amount'], 2);
							$output[$count]['items'][$i]['discount']=number_format($itemArr['discount'], 2);
							$output[$count]['items'][$i]['selected_extras']=$itemArr['selected_extras'];
							$extrasPrice = 0;
							if(!empty($output[$count]['items'][$i]['selected_extras']))
							{
								$extrasArr = json_decode($output[$count]['items'][$i]['selected_extras'], true);
								foreach($extrasArr AS $extra)
								{
									//die("hello");
									$extrasPrice += $extra['price'];
									//$output[$count]['items'][$i]['extrasPrice'][] = $extrasPrice;
								}
							}
							$output[$count]['items'][$i]['price_with_extras']=number_format(($itemArr['price']+$extrasPrice), 2);
							$output[$count]['items'][$i]['all_extras']=$itemArr['all_extras'];
							$output[$count]['items'][$i]['extras_amount']=number_format($itemArr['extras_amount'], 2);
							$output[$count]['items'][$i]['delivery_type']=$itemArr['delivery_type'];
							$output[$count]['items'][$i]['actual_unit_price']=number_format($itemArr['actual_unit_price'], 2);
							$output[$count]['items'][$i]['order_unit_price']=number_format($itemArr['order_unit_price'], 2);
							$i++;
						}
					} else {
						$output[$count]['items']=array();
					}
					$count++;
				}
				return $output;
			} else {
				return 'NO_RECORD_FOUND';
			}
		} else {
			return 'UNABLE_TO_PROCEED';
		}
	}

	public function orderHistoryPages($userid, $type, $tableno) {
		$todayDate=date('Y-m-d');
		$userData = $this->getUserById($userid);
		$order_row="select o.* from orders o where o.order_type='$type' and table_no='$tableno' and DATE(created)='$todayDate'";
		$order_detail_res = mysql_query($query);
		$num_rows = mysql_num_rows($order_detail_res);
		$totrecords = $num_rows;
		$totpages = ceil($totrecords / 20);
		return $totpages;
	}

	public function makeAvailable($orderid, $tableno, $username, $password) {
		if($orderData=$this->isOrderExist($orderid)) {
			if($orderData['is_completed']=='Y') {
				return 'ALREADY_COMPLETED';
			}
			$managerData=$this->isManager($username, $password);
			if($managerData>0) {
				$managerId=$managerData['id'];
				$update_order = "update orders set is_completed='Y', manager_id=$managerId where id=$orderid";
				if(mysql_query($update_order)) {
					return 'SUCCESSFULLY_UPDATED';
				} else {
					return 'UNABLE_TO_PROCEED';
				}            
			} else {
				return $managerData;
			}
		} else {
			return 'INVALID_ORDERID';
		}
	}

	public function changeItemDeliveryType($orderid, $orderitemid, $tableno, $oldtype, $newtype) {
		if($orderData=$this->isOrderExist($orderid)) {
			if($orderData['is_completed']=='Y') {
				return 'ALREADY_COMPLETED';
			}
			/*if ($orderData['delivery_type']==$newtype) {
				return 'ALREADY_UPDATED';
			}
			if ($orderData['delivery_type']==$oldtype) {
				return 'OLD_DELIVERY_TYPE_NOT_MATCHED';
			}*/
			$update_order = "update order_items set delivery_type='$newtype' where id=$orderitemid";
			if(mysql_query($update_order)) {
				return 'SUCCESSFULLY_UPDATED';
			} else {
				return 'UNABLE_TO_PROCEED';
			}            
			
		} else {
			return 'INVALID_ORDERID';
		}
	}

	public function changeOrderItemPrice($user_id, $orderid, $orderitemid, $tableno, $oldprice, $newprice) {
		if($orderData=$this->isOrderExist($orderid)) {
			$newprice=number_format($newprice,2);
			
			if($orderData['is_completed']=='Y') {
				return 'ALREADY_COMPLETED';
			}
			/*if ($orderData['price_changed_by']==$newprice) {
				return 'ALREADY_UPDATED';
			}
			if ($orderData['delivery_type']==$oldtype) {
				return 'OLD_DELIVERY_TYPE_NOT_MATCHED';
			}*/

            $orderItemData=$this->getOrderItemData($orderitemid);

            $oldPrice=$orderItemData['price'];
            $oldTax=$orderItemData['tax'];

            $newPrice= $newprice * $orderItemData['qty'];
            $newPrice=number_format($newPrice, 2);

            $newTax= ($newPrice + $orderItemData['extras_amount']) * $orderItemData['tax'] / 100;

			$update_orderItem = "update order_items set order_unit_price='$newprice', price_changed_by=$user_id, price='$newPrice', tax_amount='$newTax' where id=$orderitemid";
		

			if(mysql_query($update_orderItem)) {
				$orderTax= $oldTax - $newTax;
				$orderSubTotal = $oldPrice - $newPrice;
				$orderTotal= $orderTax + $orderSubTotal;

				$update_order = "update orders set tax_amount= tax_amount + $orderTax, subtotal= subtotal + $orderSubTotal,
				total=total + $orderTotal where id=$orderid";
				mysql_query($update_order);

				return 'SUCCESSFULLY_UPDATED';
			} else {
				return 'UNABLE_TO_PROCEED';
			}
		} else {
			return 'INVALID_ORDERID';
		}
	}

	/* public function updateOrderItem($userid, $orderid, $itemid, $rowid, $allextras, $qty, $selectedextras, $specialinstruction) {
		$orderedat=date('Y-m-d H:i:s');
		if($orderData=$this->isOrderExist($orderid)) {
			if ($orderData['is_completed']=='Y') {
				return 'ALREADY_COMPLETED';
			}
			$userData = $this->getUserById($userid);

			$totprice=0;
			$totextra=0;

			//$selectedextras=json_encode($itemdataArr[$i]->selectedextras);
			//$allextras=json_encode($itemdataArr[$i]->allextras);
			$itemData=$this->getItemData($itemid);
			$itemnameArr=explode('----', $itemData['cousine']);
			$itemNameEn=$itemnameArr[0];
			$itemNameZh=$itemnameArr[1];
			
			$price=$itemData['price'] * $qty;
			$extraprice = 0;
			foreach($itemdataArr[$i]->selectedextras as $num => $values) {
			   $extraprice += $values->price;
			}

			$totprice += $price;
			$totextra += $extraprice;
			$itemPrice=$itemData['price'] * $qty;
			$itemTot= $itemPrice + $totextra;
			$taxAmount= $itemTot / $userData['tax'];
			$discount=0;
			// if($orderData['promocode']!='' || !is_null($orderData['promocode'])) {
			//     $discount=$this->getDiscountOnItem($itemdataArr[$i], $orderData['promocode']);
			// }

			$orderitem_update = "update order_items set order_id='$orderid', item_id='".$itemid."', name_en='$itemNameEn', name_xh='$itemNameZh', category_id='".$itemData['category_id']."', price='$price', qty='".$qty."', tax='".$userData['tax']."', tax_amount='$taxAmount', selected_extras='$selectedextras', all_extras='$allextras', extras_amount='$extraprice', special_instructions='$specialinstruction' where id=$rowid";
			$orderitem_updated=mysql_query($orderitem_update);
			
			$orderTotal = $totprice + $totextra;

			if ($orderData['fix_discount']>0 || $orderData['percent_discount']>0){
				if ($orderData['fix_discount']>0) {
					$subTotal= $orderTotal - $orderData['fix_discount'];
				}
				if ($orderData['percent_discount']>0) {
					$subTotal= $orderTotal * $orderData['percent_discount'] / 100;
				}
			} else {
				$subTotal= $orderTotal;
			}
			$tax = $subTotal * $userData['tax'] / 100;

			$orderPrice_update = "update orders set tax_amount=tax_amount + $tax, subtotal=subtotal + $subTotal, total=total + $orderTotal where id=$orderid";
			$orderPriceUpdate=mysql_query($orderPrice_update);
			return $orderData['order_no'];
		} else {
			return 'INVALID_ORDERID';
		}
	} */
	
	public function updateOrderItem($userid, $orderid, $itemid, $rowid, $allextras, $qty, $selectedextras, $specialinstruction) {
		$orderedat=date('Y-m-d H:i:s');
		if($orderData=$this->isOrderExist($orderid)) {
			if ($orderData['is_completed']=='Y') {
				return 'ALREADY_COMPLETED';
			}
			$userData = $this->getUserById($userid);

			$totprice=0;
			$totextra=0;

			//$selectedextras=json_encode($itemdataArr[$i]->selectedextras);
			//$allextras=json_encode($itemdataArr[$i]->allextras);
			$itemData=$this->getItemData($itemid);
			$itemnameArr=explode('----', $itemData['cousine']);
			$itemNameEn=$itemnameArr[0];
			$itemNameZh=$itemnameArr[1];
			
			$price=$itemData['price'] * $qty;
			//echo $price;die;
			$extraprice = 0.0;
			//Changes made by Abhishek. $itemdataArr doesn't have any value. Let's assign it.
			$tempArr = stripslashes($selectedextras);
			$itemdataArr = json_decode($tempArr);
			//var_dump($itemdataArr);die;
			foreach($itemdataArr as $num => $values) {
				//echo $values->price;
			   $extraprice += $values->price;
			}
			//die;
			$totprice += $price;
			$totextra += $extraprice;
			$itemPrice=$itemData['price'] * $qty;
			$itemTot= $itemPrice + $totextra;
			
			//$taxAmount= $itemTot / $userData['tax'];
			//Changes made by Abhishek
			$taxAmount= $itemTot * $userData['tax'] / 100;
			$discount=0;
			// if($orderData['promocode']!='' || !is_null($orderData['promocode'])) {
			//     $discount=$this->getDiscountOnItem($itemdataArr[$i], $orderData['promocode']);
			// }
			
			//fetching old extras amount
			$oldOrderItemSql = "
							SELECT oi.extras_amount
							FROM order_items oi
							WHERE oi.id=$rowid";
			$oldOrderItemExe = mysql_query($oldOrderItemSql);
			$oldOrderItemData = mysql_fetch_assoc($oldOrderItemExe);
			$extras_amount_old = $oldOrderItemData['extras_amount'];
			
			
			$orderitem_update = "update order_items set order_id='$orderid', item_id='".$itemid."', name_en='$itemNameEn', name_xh='$itemNameZh', category_id='".$itemData['category_id']."', price='$price', qty='".$qty."', tax='".$userData['tax']."', tax_amount='$taxAmount', selected_extras='$selectedextras', all_extras='$allextras', extras_amount=$extraprice, special_instructions='$specialinstruction' where id=$rowid";
			//echo $orderitem_update;die;
			$orderitem_updated=mysql_query($orderitem_update);
			
			$orderTotal = $totprice + $totextra;

			//$orderPrice_update = "update orders set tax_amount=tax_amount + $tax, subtotal=subtotal + $subTotal, total=total + $orderTotal where id=$orderid";
			##Changes by Abhishek
			//fetching old order amount
			$oldOrderSql = "
							SELECT o.id, o.subtotal, o.total, o.tax, o.tax_amount, o.discount_value, o.promocode, o.fix_discount, o.percent_discount
							FROM orders o
							WHERE o.id=$orderid";
			$oldOrderExe = mysql_query($oldOrderSql);
			$oldOrderData = mysql_fetch_assoc($oldOrderExe);
			$tax_amount_old = $oldOrderData['tax_amount'];
			$subtotal_old = $oldOrderData['subtotal'];
			$total_old = $oldOrderData['total'];
			
			$data = array();
			//$data['id'] = $Order_detail['Order']['id'];
			$subtotalNew = @$subtotal_old - $extras_amount_old + $totextra;
			$tax_amountNew = ($subtotalNew * $userData['tax'] / 100);
			$totalNew = ($subtotalNew + $tax_amountNew);
			$discountValue = 0;
			if ($orderData['fix_discount']>0 || $orderData['percent_discount']>0){
				if ($orderData['fix_discount']>0) {
					$discountValue= $orderData['fix_discount'];
				}
				if ($orderData['percent_discount']>0) {
					$discountValue= $totalNew * $orderData['percent_discount'] / 100;
				}
			}
			$totalNew = $totalNew - $discountValue;
			//$tax = $subtotalNew * $userData['tax'] / 100;
			
			$orderPrice_update = "update orders set tax_amount=$tax_amountNew, subtotal=$subtotalNew, total=$totalNew , discount_value=$discountValue where id=$orderid";
			$orderPriceUpdate=mysql_query($orderPrice_update);
			return $orderData['order_no'];
		} else {
			return 'INVALID_ORDERID';
		}
	}

	public function mergingTableData($userid, $orderid, $mergedorderids) {
		$allIds=$orderid.','.$mergedorderids;
		$order_row="select group_concat(order_no) as ordernos, sum(tax_amount) as tax_amount, sum(subtotal) as subtotal, sum(total) as total, sum(discount_value) as discount,(select group_concat(concat(name_en, '---',name_xh,'---', qty,'---',price, '---', table_no) SEPARATOR '-+-') from order_items oi JOIN orders o ON o.id=oi.order_id and order_id IN($allIds)) as orderdata from orders where id IN ($allIds)";
		if ($order_res = mysql_query($order_row)) {
			$num_rows = mysql_num_rows($order_res);
			if ($num_rows > 0) {
				$output = array();
				$count=0;
				$arr = mysql_fetch_assoc($order_res);
				$output[$count]['tax_amount'] = number_format($arr['tax_amount'], 2);
				$output[$count]['subtotal'] = number_format($arr['subtotal'], 2);
				$output[$count]['total'] = number_format($arr['total'], 2);
				$output[$count]['discount'] = number_format($arr['discount'], 2);
				$output[$count]['ordernos'] = $arr['ordernos'];
			   
				$itemsData=explode('-+-',$arr['orderdata']);
				if(count($itemsData)>0) {
					for($i=0; $i<count($itemsData); $i++) {
						$itemData=explode('---',$itemsData[$i]);
						$output[$count]['items'][$i]['name_en']=$itemData[0];
						$output[$count]['items'][$i]['name_zh']=$itemData[1];
						$output[$count]['items'][$i]['qty']=$itemData[2];
						$output[$count]['items'][$i]['price']=number_format($itemData[3], 2);
						$output[$count]['items'][$i]['table_no']=number_format($itemData[4], 2);
					}
				}
				return $output;
			} else {
				return 'NO_RECORD_FOUND';
			}
		} else {
			return 'UNABLE_TO_PROCEED';
		}
	}

	public function makePayment($user_id, $orderid, $mergedorderids, $tip, $paymenttype, $totamountpaid, $tippaidby, $change=0, $discount=0) {
        if($orderData=$this->isOrderExist($orderid)) {
            /*if ($orderData['is_completed']=='Y') {
                return 'ALREADY_COMPLETED';
            }*/

            if($paymenttype=='CARD') {
                $subquery=", card_val='$totamountpaid'";
            } else {
                $subquery=", cash_val='$totamountpaid'";
            }

            if($tippaidby==0) {
                $tipquery=", tip_paid_by=NULL";
            } else {
                $tipquery=", cash_val='$tippaidby'";
            }

            if($mergedorderids==0) {
                $mergeId=0;
            } else {
                $mergeId=$orderid;
            }

           
            $orderPrice_update = "update orders set tip='$tip' $subquery $tipquery, paid=$totamountpaid, is_completed='Y', paid_by='$paymenttype', merge_id=$mergeId, `change`='$change', discount_value='$discount', is_kitchen='Y', table_status='P' where id=$orderid";

            $orderPriceUpdate=mysql_query($orderPrice_update);

            $populer_update = "update cousines set `popular` = `updater`+1 where id IN (select item_id from order_items where order_id=$orderid)";
            $populerUpdate=mysql_query($populer_update);

            if($mergedorderids!=0) {
                $mergedorderidArr=explode(',', $mergedorderids);
                for ($i=0; $i<count($mergedorderidArr); $i++) {
                    $orderPrice_update2 = "update orders set paid='$totamountpaid', is_completed='Y', paid_by='$paymenttype', merge_id=$orderid, is_kitchen='Y', table_status='P', `change`='0', card_val='0', cash_val='0', tip='0' $tipquery where id=".$mergedorderidArr[$i];
                    $orderPriceUpdate=mysql_query($orderPrice_update2);

                    $populer_update2 = "update cousines set `popular` = `updater`+1 where id IN (select item_id from order_items where order_id='".$mergedorderidArr[$i]."')";
                    $populerUpdate2=mysql_query($populer_update2);
                }
            }
            return 'SUCCESSFULLY_DONE';
        } else {
            return 'INVALID_ORDERID';
        }
    }

    public function makePaymentSplitDish($user_id, $orderid, $tip, $tippaidby, $paymenttype, $totamountpaid, $tax, $discount, $change, $orderitemids, $ordertotal) {
        if($orderData=$this->isOrderExist($orderid)) {
            $isItemExistInOrder=$this->itemExistInOrder($orderid, $orderitemids);
            if($isItemExistInOrder=='ALRIGHT') {
                if($paymenttype=='CARD') {
                    $subquery=", card_val='$totamountpaid'";
                } else {
                    $subquery=", cash_val='$totamountpaid'";
                }

                if($tippaidby==0) {
                    $tipquery=", tip_paid_by=NULL";
                } else {
                    $tipquery=", cash_val='$tippaidby'";
                }
        
                $newOrder_insert = "insert into orders set order_no='".$orderData['order_no']."', reorder_no='".$orderData['reorder_no']."', hide_no='".$orderData['hide_no']."', cashier_id='".$orderData['cashier_id']."', counter_id='".$orderData['counter_id']."', table_no='".$orderData['table_no']."', table_status='".$orderData['table_status']."', tax='".$orderData['tax']."', tax_amount='$tax', subtotal='$subtotal', total='$ordertotal', tip='$tip', $subquery $tipquery, paid='$totamountpaid', change='$change', promocode='".$orderData['order_no']."', message='".$orderData['order_no']."', reason='".$orderData['order_no']."', order_type='".$orderData['order_no']."', is_kitchen='".$orderData['order_no']."', cooking_status='".$orderData['order_no']."', is_hide='".$orderData['order_no']."',  is_completed='Y', paid_by='$paymenttype', created='".$orderData['created']."'";
                $newOrderInsert=mysql_query($newOrder_insert);
                $InsertedOrderId=mysql_insert_id();

                $newOrderNo=$orderid.'_'.$InsertedOrderId;
                $orderNo_update = "update orders set order_no='$newOrderNo' where id=$InsertedOrderId";
                $orderNoUpdate=mysql_query($orderNo_update);

                $orderItems_update = "update order_items set order_id='$InsertedOrderId' where id IN ($orderitemids)";
                $orderItemsUpdate=mysql_query($orderItems_update);

                $sel_totitems = "select count(*) as tot from order_items where order_id=$orderid";
                $totItems = mysql_query($sel_totitems);
                $totitems = mysql_fetch_assoc($totItems);
                if ($totitems['tot']==0) {
                    $delete_order = "delete from orders where id=$orderid";
                    $deleted = mysql_query($delete_order);
                }
                return 'SUCCESSFULLY_DONE';
            } else {
                return $isItemExistInOrder;
            }
        } else {
            return 'INVALID_ORDERID';
        }
    }

	public function makePaymentMerge($user_id, $orderid, $mergedorderids, $tip, $paymenttype, $totamount) {
		$orderedat=date('Y-m-d H:i:s');
		if($orderData=$this->isOrderExist($orderid)) {
			if ($orderData['is_completed']=='Y') {
				return 'ALREADY_COMPLETED';
			}

			if($paymenttype=='CARD') {
				$subquery=", card_val='$totamount'";
			} else {
				$subquery=", cash_val='$totamount'";
			}
		   
			$orderPrice_update = "update orders set $subquery, tip='$tip', paid=total, is_completed='Y', paid_by='$paymenttype', merge_id=$orderid where id=$orderid";
			$orderPriceUpdate=mysql_query($orderPrice_update);

			$mergedorderidArr=explode(',', $mergedorderids);
			for ($i=0; $i<count($mergedorderidArr); $i++) {
				$orderPrice_update2 = "update orders set paid=total, is_completed='Y', paid_by='$paymenttype', merge_id=$orderid where id=".$mergedorderidArr[$i];
				$orderPriceUpdate=mysql_query($orderPrice_update2);
			}
			return 'SUCCESSFULLY_DONE';
		} else {
			return 'INVALID_ORDERID';
		}
	}
	
	public function splitBill($data) {
		
		$created=date('Y-m-d H:i:s');
		$orderno = $data['order_no'];
		$tableno = $data['table_no'];
		$suborders = $data['suborder'];
		/* $arr = array(1=>array("Hello", "Hola"), 2=>array("Bloha", "bye"));;
		print_r($arr);die; */
		$gotorderid = $this->fetchOrderID($orderno);
		$orderid = $gotorderid['id'];
		if($orderid>0)
		{
			//var_dump($data);die;
			if($orderData=$this->isOrderExist($orderid)) {
				if ($orderData['is_completed']=='Y') {
					return 'ALREADY_COMPLETED';
				}
				//echo "Before Query.";die;
				//forming query to insert order splits
				$columns = "(`table_no`, `order_no`, `suborder_no`, `subtotal`, `discount_type`, `discount_value`, `discount_amount`, `tax`, `tax_amount`, `total`, `paid_card`, `paid_cash`, `tip_card`, `tip_cash`, `change`, `created`, `items`)";
				$values = "";
				$i = 0;
				foreach($suborders as $suborder)
				{
					$values .= "($tableno, '$orderno', '".mysql_real_escape_string($suborder['suborder_no'])."', ".$suborder['subtotal'].", '".mysql_real_escape_string($suborder['discount_type'])."', ".$suborder['discount_value'].", ".$suborder['discount_amount'].", ".$suborder['tax'].", ".$suborder['tax_amount'].", ".$suborder['total'].", ".$suborder['paid_card'].", ".$suborder['paid_cash'].", ".$suborder['tip_card'].", ".$suborder['tip_cash'].", ".$suborder['change'].", '$created', '".$suborder['items']."'),";
				}
				
				$values = substr($values, 0, -1);
				$query = "INSERT INTO order_splits$columns VALUES$values";
				//echo $query;die;
				$execQuery = mysql_query($query);
				
				if($execQuery)
				{
					//checking if the order amount is equal to the sum of splitted amount. If yes, then we'll mark order status as completed.
					/* $query = "
								SELECT o.total AS totalOrderAmount, SUM(os.total) AS totalSplitAmount
								FROM orders o INNER JOIN order_splits os
								ON o.order_no=os.order_no
								WHERE o.order_no=".$data['order_no']."
								GROUP BY os.order_no";
					$execQuery = mysql_query($query);
					$amountData = mysql_fetch_assoc($execQuery);
					if($amountData['totalOrderAmount'] == $amountData['totalSplitAmount'])
					{
						//order is completed
						$query = "
								UPDATE orders o
								SET o.is_completed='Y'
								WHERE o.order_no=".$data['order_no'];
						$queryExe = mysql_query($query);
					} */
					return 'SUCCESSFULLY_DONE';
				}
				else
				{
					//echo "IN ELSE";
					//echo $insertSplit;//die;
					//echo mysql_error($this->conn);die;
					return 'UNABLE_TO_PROCEED';
				}
			} else {
				return 'INVALID_ORDERID';
			}
		}	
	}
	
	public function completeOrder($data) {
		//$data['created']=date('Y-m-d H:i:s');
		$gotorderid = $this->fetchOrderID($data['order_no']);
		$orderid = $gotorderid['id'];
		if($orderid>0)
		{
			//var_dump($data);die;
			if($orderData=$this->isOrderExist($orderid)) {
				if ($orderData['is_completed']=='Y') {
					return 'ALREADY_COMPLETED';
				}
				$data['is_completed'] = 'Y';
				$data['table_status'] = 'P';
				//echo "Before Query.";die;
				//checking if the order_no and suborder_no are already present in the db or not.
				$updateOrder = $this->updateData($data, 'orders', $orderid);
				if($updateOrder)
				{
					//updating in cousine table
					$query = "UPDATE cousines set `popular` = `popular`+1 where id in(SELECT (item_id) from order_items where order_id = '$orderid')";
					$execQuery = mysql_query($query);
					return 'SUCCESSFULLY_DONE';
				}
				else
				{
					//echo "IN ELSE";
					//echo $insertSplit;//die;
					return 'UNABLE_TO_PROCEED';
				}
			} else {
				return 'INVALID_ORDERID';
			}
		}	
	}
	
	public function printSplit($order_no, $table_no, $suborders, $table_type, $print_zh, $cashier_id) {
		$is_receipt = 1;
		//$data['created']=date('Y-m-d H:i:s');
		$userdata = $this->getUserById($cashier_id);
		$gotorderid = $this->fetchOrderID($order_no);
		$orderid = $gotorderid['id'];
		if($orderid>0)
		{
			//var_dump($data);die;
			if($orderData=$this->isOrderExist($orderid)) {
				if ($orderData['is_completed']=='Y') {
					return 'ALREADY_COMPLETED';
				}
				$restaurant_id = $userdata['restaurant_id'];
				$printerName = $this->getServicePrinterName($restaurant_id);
				//$logoname = $this->getServicePrinterName($restaurant_id);
				
				$logoPath = $this->getLogo($restaurant_id);
				$logoname = basename($logoPath);
				$type = (($table_type == 'D') ? '[[堂食]]' : (($table_type == 'T') ? '[[外卖]]' : (($table_type == 'W') ? '[[等候]]' : '')));

				foreach($suborders as $suborder)
				{
					//print_r($suborder);die;
					$this->printSplitBill($printerName, $logoname, $table_no, $order_no, $suborder, $type, $print_zh, $is_receipt);
				}

				return 'SUCCESS';
				
			} else {
				return 'INVALID_ORDERID';
			}
		}	
	}
	
	/*------------------------------------------------- Called functions -----------------------------------------------*/

	public function printSplitBill($printerName, $logoname, $table_no, $order_no, $suborder, $type, $print_zh, $is_receipt)
	{
		date_default_timezone_set("America/Toronto");
		$date_time = date("l M d Y h:i:s A");
		$items = $suborder['items'];
		$suborder_no = $suborder['suborder_no'];
		$subtotal = $suborder['subtotal'];
		$discount_type = $suborder['discount_type'];
		$discount_value = $suborder['discount_value'];
		$discount_amount = $suborder['discount_amount'];
		$after_discount = $suborder['after_discount'];
		$tax_rate = $suborder['tax_rate'];
		$tax_amount = $suborder['tax_amount'];
		$total = $suborder['total'];
		$received_card = $suborder['received_card'];
		$received_cash = $suborder['received_cash'];
		$received_total = $suborder['received_total'];
		$paid = $received_total;
		$tip_amount = $suborder['tip_amount'];
		$tip_card = $suborder['tip_card'];
		$tip_cash = $suborder['tip_cash'];
		$change = $suborder['change'];
		$this->handle = printer_open($printerName);
		printer_start_doc($this->handle, "my_Receipt");
		printer_start_page($this->handle);
		
		file_exists("../app/webroot/img/".$logoname)?printer_draw_bmp($this->handle, "../app/webroot/img/".$logoname, 100, 20, 263, 100):true;
		$this->printBigEn("3700 Midland Ave. #108", 156, 130);
		$this->printBigEn("Scarborogh ON M1V 0B3", 110, 168);
		$this->printBigEn("647-352-5333", 156, 206);

		$print_y = 244;
		//$print_zh = true;
		if ($print_zh == true) {
			$this->printZh("此单不包含小费，感谢您的光临", 100, $print_y);
			$print_y+=40;
			$this->printZh("谢谢", 210, $print_y);
			$print_y+=40;
		}
		
		//echo $table_no;die;

		$this->printBigEn("Order#: " . $order_no . '-' . $suborder_no , 32, $print_y);
		$print_y+=40;
		$this->printBigZh("Table:". $type . iconv("UTF-8", "gb2312", "#" . $table_no) , 32, $print_y);
		$print_y+=38;

		$pen = printer_create_pen(PRINTER_PEN_SOLID, 2, "000000");
		printer_select_pen($this->handle, $pen);
		printer_draw_line($this->handle, 21, $print_y, 600, $print_y);

		// print order item
		$print_y += 20;
		for ($i = 0; $i < count($items); ++$i) {
			$this->printEn("1", 10, $print_y);
			$this->printEn(number_format($items[$i]['price'], 2), 360, $print_y);
			$this->printItemEn($items[$i]['name_en'], 50, $print_y);
			
			// $print_y += 30;
			if ($print_zh == true) {
				// $this->printZh($items[$i]['name_zh'], 10, $print_y);
				$this->printItemZh($items[$i]['name_zh'], 50, $print_y);
			};

			// $print_y += 30;

			// if (!empty(trim($items[$i]['selected_extras_name']))) {
			//     // $print_y += 30;
			//     $this->printItemZh( $items[$i]['selected_extras_name'], 32, $print_y);

			//     // $this->printEn( number_format($items[$i]['extra_amount'], 2), 360, $print_y);
			// }

			// $print_y += 40;
		}

		$print_y += 10;
		$pen = printer_create_pen(PRINTER_PEN_SOLID, 2, "000000");
		printer_select_pen($this->handle, $pen);
		printer_draw_line($this->handle, 21, $print_y, 600, $print_y);

		$print_y += 10;

		if ($print_zh == true) {
			$this->printZh("Subtoal", 58, $print_y);
			$this->printZh("小计:", 148, $print_y);
		} else {
			$this->printEn("Subtoal :", 58, $print_y);
		}
		$this->printEn(number_format($subtotal, 2), 360, $print_y);
		$print_y += 30;

		if (floatval($discount_amount) > 0 ) {
			if ($print_zh == true) {
				$this->printZh("Discount", 58, $print_y);
				$this->printZh("折扣：", 148, $print_y);
			} else {
				$this->printEn("Discount :", 148, $print_y);
			}
			$this->printEn(number_format($discount_amount, 2), 360, $print_y);

			$print_y += 30;

			if ($print_zh == true) {
				$this->printZh("After Discount", 58, $print_y);
				$this->printZh("折后价：", 148, $print_y);
			} else {
				$this->printEn("After Discount :", 58, $print_y);
			} 

			$this->printEn(number_format($after_discount, 2), 360, $print_y);

			$print_y += 30;
		}

		if ($print_zh == true) {
			$this->printZh("Hst", 58, $print_y);
			$this->printZh("(" . $tax_rate . "%)", 100, $print_y);
			$this->printZh("税：", 168, $print_y);
		} else {
			$this->printEn("Hst", 58, $print_y);
			$this->printEn("(" . $tax_rate . "%) :", 100, $print_y);
		}
		$this->printEn(number_format($tax_amount, 2), 360, $print_y);
		$print_y += 30;
		
		if ($print_zh == true) {
			$this->printZh("Total", 58, $print_y);
			$this->printZh("总计：", 148, $print_y);
		} else {
			$this->printEn( "Total :", 58, $print_y);
		};
		$this->printEn(number_format($total, 2), 360, $print_y);
		$print_y += 30;


		if ($is_receipt == true) {
			if ($print_zh == true) {
				$this->printZh("Paid", 58, $print_y);
				$this->printZh("付款：", 148, $print_y);
			} else {
				$this->printEn("Paid :", 58, $print_y);
			}
			$this->printEn(number_format($paid, 2), 360, $print_y);
			$print_y += 30;

			if ($print_zh == true) {
				$this->printZh("Change", 58, $print_y);
				$this->printZh("找零：", 148, $print_y);
			} else {
				$this->printEn("Change :", 58, $print_y);
			};
			$this->printEn(number_format($change, 2), 360, $print_y);

		}

		$print_y += 30;

		$this->printEn($date_time, 80, $print_y);


		printer_end_page($this->handle);
		printer_end_doc($this->handle);
		printer_close($this->handle);
	}
	public function getLogo($restaurant_id)
	{
		$getlogo = "SELECT a.logo_path FROM admins a WHERE a.id=$restaurant_id";
		$gettinglogo = mysql_query($getlogo);
		if (mysql_num_rows($gettinglogo) > 0) {
			$gotlogo=mysql_fetch_assoc($gettinglogo);
			return $gotlogo['logo_path'];
		}
		return "";
	}
	
	public function isManager($username, $password) {
		$sel_manager = "select id, password, status from admins where email='$username'";
		$manager = mysql_query($sel_manager);
		if (mysql_num_rows($manager) > 0) {
			$data=mysql_fetch_assoc($manager);
			if ($data['password']==md5($password)) {
				if ($data['status']=='A') {
					return $data['id'];
				} else {
					return 'USER_ACCOUNT_DEACTVATED';
				}
			} else {
				return 'INVALID_USERNAME_PASSWORD';
			}
		} else {
			return 'INVALID_USERNAME';
		}
	}

	/*public function getDiscountOnItem($orderData, $promocode]) {
		$isPromoValid=$this->verifyPromocode($promocode, $orderid);
		$itemData=$this->getItemData($orderData->itemid);

		$price=$itemData['price'] * $itemdataArr[$i]->qty;
		$extraprice = 0;
		foreach($itemdataArr[$i]->selectedextras as $num => $values) {
		   $extraprice += $values->price;
		}
		$totprice=$price + $extraprice;

		if ($isPromoValid['category_id']==0) {
			if($isPromoValid['discount_type']==0) {
				$discount=0;
			} else {
				$discount= $totprice * $isPromoValid['discount_value'] / 100;
			}
		} else {
			$orderItem=$this->getItemData($orderData->id);
			$count = 0;
			while ($itemArr = mysql_fetch_assoc($orderItem)) {
				$discount=0;
				if ($isPromoValid['item_id']>0) {            
					if($itemArr['id']==$isPromoValid['item_id']) {
						if($isPromoValid['discount_type']==0) {
							$itemTot=$itemArr['price'] + $itemArr['extra_amount'];
							if($itemTot>=$isPromoValid['discount_value']) {
								$discount=$isPromoValid['discount_value'];
							} else {
								$discount=$itemTot;
							}
						} else {
							$discount= ($itemArr['price'] + $itemArr['extra_amount']) * $isPromoValid['discount_value'] / 100;
						}
					}
				} else {
					if($itemArr['category_id']==$isPromoValid['category_id']) {
						if($isPromoValid['discount_type']==0) {
							$itemTot=$itemArr['price'] + $itemArr['extra_amount'];
							if($itemTot>=$isPromoValid['discount_value']) {
								$discount=$isPromoValid['discount_value'];
							} else {
								$discount=$itemTot;
							}
						} else {
							$discount = ($itemArr['price'] + $itemArr['extra_amount']) * $isPromoValid['discount_value'] / 100;
						}
					}
				}
				$discountArr[]=$discount;
				if($discount>0) {
					$orderItem_update = "update order_items set discount=$discount where id=".$itemArr['id'];
					$orderItemUpdate=mysql_query($orderItem_update);
				}
		  
			}
		}
	}*/

	public function removePromocode($orderid, $promocode) {
		$sel_order = "select * from orders where id=$orderid";
		$order = mysql_query($sel_order);
		if (mysql_num_rows($order) > 0) {
			$data=mysql_fetch_assoc($order);

			$subTotal=$data['discount_value'] + $data['subtotal'];
			$tax= $subTotal * $data['tax'] / 100;
			$total=$subTotal + $tax;
			
			$orderPrice_update = "update orders set tax_amount=$tax, subtotal=$subTotal, total=$total, promocode=NULL, fix_discount=NULL, discount_value=NULL, percent_discount=NULL where id=$orderid";
			$orderPriceUpdate=mysql_query($orderPrice_update);

			$orderItemDiscount = "update order_items set discount=0 where order_id=$orderid";
			$orderItemDiscountUpdate=mysql_query($orderItemDiscount);

			return 'SUCCESSFULLY_REMOVED';
		} else {
			return 'INVALID_PROMOCODE';
		}
	}
	
	/* public function sendPrintToKitchen($Printer, $order_no, $order_type, $table_no, $table, $Print_Item, $splititem, $print_zh)
	{
		if ($splitItme == false) {
            $Print_Item = $Print_Item;
        } else {
            $Print_Item_split = $Print_Item;
            $Print_Item = array();
        };
        //End
        for ($x = 0; $x < (isset($Print_Item_split) ? count($Print_Item_split) : 1); $x++) {//Modified by Yishou Liao @ Nov 28 2016
            if (isset($Print_Item_split)) {
                $Print_Item[0] = $Print_Item_split[$x];
            }; //Modified by Yishou Liao @ Nov 28 2016
            

            foreach (array_keys($Printer) as $key) {
                $printer_name = $Printer[$key];
                $printer_loca = $key;

                $check_print_flag = false;
                for ($i = 0; $i < count($Print_Item); $i++) {
                    if ($Print_Item[$i][count($Print_Item[$i]) - 1] == $printer_loca) {
                        $check_print_flag = true;
                    };
                };

                if ($check_print_flag) {

                    date_default_timezone_set("America/Toronto");
                    $date_time = date("l M d Y h:i:s A");

                    $handle = printer_open($printer_name);
                    printer_start_doc($handle, "my_Receipt");
                    printer_start_page($handle);

                    if ($print_zh == true) {
                        $font = printer_create_font(iconv("UTF-8", "gb2312", "宋体"), 42, 18, PRINTER_FW_BOLD, false, false, false, 0);
                        printer_select_font($handle, $font);
                        printer_draw_text($handle, iconv("UTF-8", "gb2312", "后厨组（分单）"), 138, 20);
                    } else {
                        $font = printer_create_font("Arial", 42, 18, PRINTER_FW_MEDIUM, false, false, false, 0);
                        printer_select_font($handle, $font);
                        printer_draw_text($handle, "Kitchen", 138, 20);
                    };


                    //Print order information
                    $font = printer_create_font("Arial", 32, 14, PRINTER_FW_MEDIUM, false, false, false, 0);
                    printer_select_font($handle, $font);
                    printer_draw_text($handle, "Order Number: #" . $order_no, 32, 80);
                    printer_draw_text($handle, "Table:" . iconv("UTF-8", "gb2312", $table_no), 32, 120);
                    //End

                    $pen = printer_create_pen(PRINTER_PEN_SOLID, 2, "000000");
                    printer_select_pen($handle, $pen);
                    printer_draw_line($handle, 21, 160, 600, 160);

                    //Print order items
                    $print_y = 180;
                    for ($i = 0; $i < count($Print_Item); $i++) {
                        if ($Print_Item[$i][(count($Print_Item[$i]) - 1)] == $printer_loca) {
                            if ($print_zh == true) {
                                $font = printer_create_font("Arial", 32, 12, PRINTER_FW_MEDIUM, false, false, false, 0);
                            } else {
                                $font = printer_create_font("Arial", 34, 14, PRINTER_FW_MEDIUM, false, false, false, 0);
                            };
                            printer_select_font($handle, $font);

                            printer_draw_text($handle, $Print_Item[$i][7], 32, $print_y);

                            $print_str = $Print_Item[$i][3];
                            $len = 0;
                            while (strlen($print_str) != 0) {
                                $print_str = substr($Print_Item[$i][3], $len, 16);
                                printer_draw_text($handle, $print_str, 122, $print_y);
                                $len+=16;
                                if (strlen($print_str) != 0) {
                                    $print_y+=32;
                                };
                            };
                            $print_y-=32;

                            if ($print_zh == true) {
                                $print_y += 32;
                                $font = printer_create_font(iconv("UTF-8", "gb2312", "宋体"), 38, 16, PRINTER_FW_BOLD, false, false, false, 0);
                                printer_select_font($handle, $font);

                                printer_draw_text($handle, iconv("UTF-8", "gb2312", $Print_Item[$i][4]), 120, $print_y);


                                if ($order_type == "T" || $Print_Item[$i][16] == "#T#") {
                                    printer_draw_text($handle, iconv("UTF-8", "gb2312", "(外带)"), 366, $print_y);
                                };
                                if ($Print_Item[$i][13] == "C") {
                                    printer_draw_text($handle, iconv("UTF-8", "gb2312", "(取消)"), 366, $print_y);
                                };
                            } else {
                                if ($order_type == "T" || $Print_Item[$i][16] == "#T#") {
                                    printer_draw_text($handle, "(Takeout)", 366, $print_y);
                                };
                                if ($Print_Item[$i][13] == "C") {
                                    printer_draw_text($handle, "(Cancel)", 366, $print_y);
                                };
                            };
                            if (strlen($Print_Item[$i][10]) > 0) {
                                $font = printer_create_font(iconv("UTF-8", "gb2312", "宋体"), 28, 14, PRINTER_FW_BOLD, false, false, false, 0);
                                printer_select_font($handle, $font);
                                $print_str = $Print_Item[$i][10];
                                $len = 0;
                                $print_y+=32;
                                while (strlen($print_str) != 0) {
                                    $print_str = substr($Print_Item[$i][10], $len, 16);
                                    printer_draw_text($handle, iconv("UTF-8", "gb2312", $print_str), 120, $print_y);
                                    $len+=16;
                                    if (strlen($print_str) != 0) {
                                        $print_y+=32;
                                    };
                                };
                                $print_y-=32;
                            };
                            $print_y += 46;
                        };
                    };
                    //End.
                    $print_y += 10;
                    $pen = printer_create_pen(PRINTER_PEN_SOLID, 2, "000000");
                    printer_select_pen($handle, $pen);
                    printer_draw_line($handle, 21, $print_y, 600, $print_y);

                    $print_y += 10;
                    $font = printer_create_font("Arial", 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
                    printer_select_font($handle, $font);
                    printer_draw_text($handle, $date_time, 80, $print_y);

                    printer_delete_font($font);

                    printer_end_page($handle);
                    printer_end_doc($handle);
                    printer_close($handle);
                };
            };

            if (isset($_SESSION['DELEITEM_' . $table])) {
                unset($_SESSION['DELEITEM_' . $table]);
            };

            echo true;
        }
	} */

	public function sendPrintToKitchen($order_no, $cashier_id)
	{
		//first we will get restaurant_id
		$userdata = $this->getUserById($cashier_id);
		if(!empty($userdata))
		{
			//Now getting order id
			$gotorderid = $this->fetchOrderID($order_no);
			$orderid = $gotorderid['id'];
			if($orderid>0)
			{
				$restaurant_id = $userdata['restaurant_id'];
				$orderDetail = $this->isOrderExist($orderid);
				if(!empty($orderDetail))
				{
					$type = $orderDetail['order_type'];
					$table = $orderDetail['table_no'];
					$order_no = $orderDetail['order_no'];


					// get all un printed items
					$printItems = $this->getUnprintItemsByOrderId($order_id);
					if($printItems)
					{
						if (!empty($printItems['K'])) {

							$printerName = $this->getKitchenPrinterName($restaurant_id);
							$print = new PrintLib();
							$print->printKitchenItemDoc($order_no, $table, $type, $printerName, $printItems['K'],true, false);
						}

						if (!empty($printItems['C'])) {
							$printerName = $this->getServicePrinterName($restaurant_id);
							$print = new PrintLib();
							$print->printKitchenItemDoc($order_no, $table, $type, $printerName, $printItems['C'], true, false);
						}

						$this->setAllItemsToPrinted($order_id);
					}
					else
					{
						echo NO_ITEM_UNPRINTED;
					}
					// print_r($printItems);
				}
				else
				{
					echo ORDER_NOT_EXIST;
				}
			}
			else
			{
				echo INVALID_ORDERID;
			}
			
		}
		else
		{
			echo INVALID_CASHIER;
		}
	}

	public function printReceipt($order_no, $cashier_id)
	{
		//first we will get restaurant_id
		$userdata = $this->getUserById($cashier_id);
		if(!empty($userdata))
		{
			//Now getting order id
			$gotorderid = $this->fetchOrderID($order_no);
			$orderid = $gotorderid['id'];
			if($orderid>0)
			{
				$restaurant_id = $userdata['restaurant_id'];
				$orderDetail = $this->isOrderExist($orderid);
				if(!empty($orderDetail))
				{
					$printerName = $this->getServicePrinterName($restaurant_id);

					$type = $orderDetail['order_type'];
					$table_no = $orderDetail['table_no'];
					$order_no = $orderDetail['order_no'];
					$logoPath = LOGOPATH;
					$billInfo = $orderDetail;
					
					//fetching order items
					
					$printItems = $this->getMergedItems($orderid);

					//$printerName = $printer_name;
					$print = new PrintLib();
					echo $print->printPayBillDoc($order_no, $table_no, $type, $printer_name, $printItems, $billInfo, $logoPath,true, false);
				}
				else
				{
					echo ORDER_NOT_EXIST;
				}
			}
			else
			{
				echo INVALID_ORDERID;
			}
			
		}
		else
		{
			echo INVALID_CASHIER;
		}
	}

	public function verifyPromocode($value, $orderid=0) {
		//discounttype=0 then FIXED else Percent
		$sel_promocode = "select * from promocodes where code='$value' and status='1'";
		$promocode = mysql_query($sel_promocode);
		if (mysql_num_rows($promocode) > 0) {
			$data=mysql_fetch_assoc($promocode);
			return $data;
		}
	}
	
	public function fetchOrderID($orderno) {
		
		$getOrderID = "select id from orders where order_no='$orderno'";
		$gettingOrderID = mysql_query($getOrderID);
		if (mysql_num_rows($gettingOrderID) > 0) {
			$gotOrderID=mysql_fetch_assoc($gettingOrderID);
			return $gotOrderID;
		}
		return 0;
	}
	
	public function getKitchenPrinterName($id) {
		
		$printer_nameSQL = "select kitchen_printer_device from admins where id=$id";
		$printer_nameExe = mysql_query($printer_nameSQL);
		if (mysql_num_rows($printer_nameExe) > 0) {
			$data=mysql_fetch_assoc($printer_nameExe);
			return $data['kitchen_printer_device'];
		}
    }

    public function getServicePrinterName($id) {
		$printer_nameSQL = "select service_printer_device from admins where id=$id";
		$printer_nameExe = mysql_query($printer_nameSQL);
		if (mysql_num_rows($printer_nameExe) > 0) {
			$data=mysql_fetch_assoc($printer_nameExe);
			return $data['service_printer_device'];
		}
    }
  
	public function getOrderItemData($rowid) {
		$sel_order = "select * from order_items where id=$rowid";
		$order = mysql_query($sel_order);
		if (mysql_num_rows($order) > 0) {
			$data=mysql_fetch_assoc($order);
			return $data;
		}
	}

	public function isOrderExist($orderId) {
		$sel_order = "select * from orders where id=$orderId";
		$order = mysql_query($sel_order);
		if (mysql_num_rows($order) > 0) {
			$data=mysql_fetch_assoc($order);
			return $data;
		}
	}

	public function getItemData($itemId) {
		$sel_item = "SELECT c.*, (select group_concat(name SEPARATOR '----') from cousine_locals where parent_id=c.id) as cousine from cousines c WHERE c.id=$itemId";
		$item = mysql_query($sel_item);
		if (mysql_num_rows($item) > 0) {
			$data=mysql_fetch_assoc($item);
			return $data;
		}
	}

	public function isValidReservationId($reservationid) {
		$todayDate=date('Y-m-d H:i:s');
		$sel_reservation = "SELECT * from reservations WHERE id=$reservationid";
		$reservation = mysql_query($sel_reservation);
		if (mysql_num_rows($reservation) > 0) {
			$data=mysql_fetch_assoc($reservation);
			return $data;
		}
	}

	public function isTableAvailable($tableno) {
		$todayDate=date('Y-m-d');
		$sel_user = "SELECT * from orders WHERE table_no='$tableno' and is_completed='N' and order_type='D'";
		//and DATE(created)='$todayDate'
		$user = mysql_query($sel_user);
		if (mysql_num_rows($user) > 0) {
			$data=mysql_fetch_assoc($user);
			return $data;
		}
	}

	function convertoround($amount) {
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
	
	public function getCategoryItems($categoryid) {
	   $sel_items = "SELECT c.*, (select group_concat(name SEPARATOR '----') as itemname from cousine_locals cl where cl.parent_id=c.id) as itemname from cousines c WHERE category_id=$categoryid and c.status='A'";
		$items = mysql_query($sel_items);
		if (mysql_num_rows($items) > 0) {
			return $items;
		} 
	}

	public function getOrderItems($orderid) {
	   $sel_items = "SELECT * from order_items WHERE order_id=$orderid";
		$items = mysql_query($sel_items);
		if (mysql_num_rows($items) > 0) {
			return $items;
		} 
	}
	
	public function getMergedItems($orderid) {
		$data = array();
		
		$query= "
					SELECT COUNT(item_id) AS item_id_count, SUM(qty) AS qty, price, item_id, name_en, name_xh, price, special_instructionS, selected_extras
					FROM order_items
					WHERE order_id=$orderid
					GROUP BY item_id, price
				";
		$fetchedItems = mysql_query($query);
		while($fetchedItemsArr = mysql_fetch_assoc($fetchedItems))
		{
			array_push($data, $fetchedItemsArr);
		}
		return $data;
	}
	
	public function getUnprintItemsByOrderId($orderid) {
		$data = array();
		
		$query= "
					SELECT 
						OrderItem.id,
						OrderItem.name_en,
						OrderItem.name_xh,
						OrderItem.category_id,
						OrderItem.qty,
						OrderItem.selected_extras,
						OrderItem.is_print,
						OrderItem.special_instructions,
						ct.printer
					FROM
						order_items OrderItem INNER JOIN categories ct
						ON OrderItem.category_id=ct.id
					WHERE
						OrderItem.order_id=$orderid AND OrderItem.is_print='N'
				";//OrderItem.is_takeout is not present and special_instructions is special_instruction there in live DB.
		$fetchedItems = mysql_query($query);
		while($fetchedItemsArr = mysql_fetch_assoc($fetchedItems))
		{
			$printer = $fetchedItemsArr['printer'];
			$selected_extras_list = json_decode($fetchedItemsArr['selected_extras'], true);
            $selected_extras_arr = array();
                if (!empty($selected_extras_list)) {
                    foreach ($selected_extras_list as $selected_extra) {
                        array_push($selected_extras_arr, $selected_extra['name']);
                    }
                }
                
            $fetchedItemsArr['selected_extras'] = join(',', $selected_extras_arr);


            if (!isset($data[$printer])) {
                $data[$printer] = array();
            }

            array_push($data[$printer], $fetchedItemsArr);
			//array_push($data, $fetchedItemsArr);
		}
		return $data;
	}
	
	public function setAllItemsToPrinted($orderid)
	{
		$sel_items = "UPDATE order_items SET is_print='Y' WHERE order_id=$orderid AND is_print='N'";
		$items = mysql_query($sel_items);
		if (mysql_affected_rows($items) > 0) {
			return true;
		}
	}

	public function isUsernameExists($username) {
		$sel_user = "SELECT id, password, status, email from cashiers WHERE email='$username'";
		$user = mysql_query($sel_user);
		if (mysql_num_rows($user) > 0) {
			$data=mysql_fetch_assoc($user);
			return $data;
		}
	}

	public function validateUser($username, $password) {
		$sel_user = "SELECT id from cashiers WHERE email = '$username' AND (password = md5('$password') or password = '$password') and status='A'";
		$user = mysql_query($sel_user);
		if (mysql_num_rows($user) > 0) {
			$userid = mysql_fetch_assoc($user);
			return $userid;
		}
	}
	
	public function getUserById($userid) {
		$sel_user = "SELECT c.*, a.restaurant_name, a.address, a.tax, a.no_of_tables, a.no_of_takeout_tables, a.no_of_waiting_tables, a.table_size, a.table_order, a.takeout_table_size, a.waiting_table_size, a.printer_ip, a.kitchen_printer_device, a.service_printer_device from cashiers c JOIN admins a ON a.id=c.restaurant_id WHERE c.id=$userid";
		$user = mysql_query($sel_user);
		if (mysql_num_rows($user) > 0) {
			$userdata = mysql_fetch_assoc($user);
			return $userdata;
		}
	}

	public function pushNotification($deviceToken, $message) {
		$token = $deviceToken;
		$message = array("data" => $message);
		$url = 'https://android.googleapis.com/gcm/send';
		$fields = array(
			'registration_ids' => array($token),
			'data' => $message,
		);
		//print_r($fields); 
		$headers = array(
			'Authorization: key=AIzaSyB_4G54ifqa2TOU0Ofnm8RcmMrMqwCwbac',
			'Content-Type: application/json'
		);

		// Open connection
		$ch = curl_init();
		// Set the url, number of POST vars, POST data
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// Disabling SSL Certificate support temporarly
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
		// Execute post
		$result = curl_exec($ch);
		if ($result === FALSE) {
			die('Curl failed: ' . curl_error($ch));
		}
		// Close connection
		curl_close($ch);
		//print_r($result); exit; 
		return $result;
	}
	
	/* Printer Related Functions */
	public function switchZh() {
        $fontZh = printer_create_font($this->fontStr1, $this->fontH, $this->fontW, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($this->handle, $fontZh);
    }

    public function switchEn() {
        $font = printer_create_font("Arial", 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($this->handle, $font);
    }


    public function printZh($str, $x, $y) {
        $font = printer_create_font($this->fontStr1, $this->fontH, $this->fontW, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($this->handle, $font);
        printer_draw_text($this->handle, iconv("UTF-8", "gb2312", $str), $x, $y);
        printer_delete_font($font);
    }

    public function printBigZh ($str, $x, $y) {
        $font = printer_create_font($this->fontStr1, 32, 14, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($this->handle, $font);
        printer_draw_text($this->handle, iconv("UTF-8", "gb2312", $str), $x, $y);
        printer_delete_font($font);
    }

    // each chinese character take two byte
    public function printItemZh($str, $x, &$y) {
        $font = printer_create_font($this->fontStr1, $this->fontH, $this->fontW, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($this->handle, $font);

        // change the str to chinese string
        // $str =  iconv("UTF-8", "gb2312", $str);
        $start = 0;

        while (mb_strlen($str, 'UTF-8') > 0) {
            $print_str = mb_substr($str, $start, 10);
            printer_draw_text($this->handle, iconv("UTF-8", "gb2312", $print_str), $x, $y);
            $str = mb_substr($str, $start);
            if (mb_strlen($str, 'UTF-8') > 0 ) {
                $y += $this->fontH + 2; // change the line
            }
            
            $start += 10;
           
        }

        // printer_draw_text($this->handle, iconv("UTF-8", "gb2312", $str), $x, $y);
        printer_delete_font($font);
    }

    public function printItemEn($str, $x, &$y) {
        $font = printer_create_font("Arial", 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($this->handle, $font);

        $start = 0;
        while (strlen($str) != 0) {
            $print_str = substr($str, $start, 20);
            printer_draw_text($this->handle, $print_str, $x, $y);
            $str = mb_substr($str, $start);

            if (mb_strlen($str, 'UTF-8') > 0 ) {
                $y += $this->fontH + 2; // change the line
            }
            $start += 20;
            break;
        }

        printer_delete_font($font);
    }

    public function printEn($str, $x, $y) {
        $font = printer_create_font("Arial", 28, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($this->handle, $font);
        printer_draw_text($this->handle, $str, $x, $y);

        printer_delete_font($font);
    }

    public function printBigEn($str, $x, $y) {
        $font = printer_create_font("Arial", 32, 14, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($this->handle, $font);
        printer_draw_text($this->handle, $str, $x, $y);

        printer_delete_font($font);
    }
}
?>
