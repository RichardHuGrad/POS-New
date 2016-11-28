<?php
class DbHandler {
    private $conn;
    function __construct() {
        require_once dirname(__FILE__) . '/DbConnect.php';
        // opening db connection
        $db = new DbConnect();
        $this->conn = $db->connect();
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
        }else{
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
            $dinein[$dine_row]['table_no'] = 'T'.$i;
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

                    $dine_key = array_search('T'.$arr['table_no'], array_column($dinein, 'table_no'));
                    if ($dine_key>=0 && $arr['order_type']=='D') {
                        $dinein[$dine_row]['order_no'] = $arr['order_no'];
                        $dinein[$dine_row]['order_type'] = $arr['order_type'];
                        $dinein[$dine_row]['created'] = $arr['created'];
                        $dinein[$dine_row]['table_color'] = 'OCCUPIED';
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
                    $output[$count]['tax'] = $arr['tax'];
                    $output[$count]['tax_amount'] = $arr['tax_amount'];
                    $output[$count]['subtotal'] = $arr['subtotal'];
                    $output[$count]['total'] = $arr['total'];
                    $output[$count]['created'] = $arr['created'];

                    $orderItemsArr=$this->getOrderItems($arr['id']);
                    if (count($orderItemsArr)>0) {
                        $i=0;
                        while ($itemArr = mysql_fetch_assoc($orderItemsArr)) {
                            $output[$count]['items'][$i]['id']=$itemArr['id'];
                            $output[$count]['items'][$i]['name_en']=$itemArr['name_en'];
                            $output[$count]['items'][$i]['name_zh']=$itemArr['name_xh'];
                            $output[$count]['items'][$i]['price']=$itemArr['price'];
                            $output[$count]['items'][$i]['qty']=$itemArr['qty'];
                            $output[$count]['items'][$i]['tax']=$itemArr['tax'];
                            $output[$count]['items'][$i]['tax_amount']=$itemArr['tax_amount'];
                            $output[$count]['items'][$i]['selected_extras']=$itemArr['selected_extras'];
                            $output[$count]['items'][$i]['all_extras']=$itemArr['all_extras'];
                            $output[$count]['items'][$i]['extras_amount']=$itemArr['extras_amount'];
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
        $categories_row="select c.id, (select group_concat(name SEPARATOR '----') as itemname from category_locales cl where cl.category_id=c.id) as catname from categories c where c.status='A'";
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

                    $itemsArr=$this->getCategoryItems($arr['id']);
                    if (count($itemsArr)>0) {
                        $i=0;
                        while ($itemArr = mysql_fetch_assoc($itemsArr)) {
                            $output[$count]['items'][$i]['id']=$itemArr['id'];
                            $itemNameArr=explode('----', $itemArr['itemname']);
                            $output[$count]['items'][$i]['itemname_en'] = $itemNameArr[0];
                            $output[$count]['items'][$i]['itemname_zh'] = $itemNameArr[1];
                            $output[$count]['items'][$i]['price']=$itemArr['price'];
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
                    $output[$count]['price'] = $arr['price'];
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

        $reservations_row.=" order by reserve_date, reserve_time asc";
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
                    $order_insert = "insert into orders set cashier_id='".$userData['restaurant_id']."', counter_id='$userid', table_no='$tableno', order_type='D', created='$reservedat', reservation_id='$reservationid', , tax='".$userData['tax']."'";
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

    public function makeOrder($userid, $type, $itemdata, $tableno) {
        $orderedat=date('Y-m-d H:i:s');
        if(!$this->isTableAvailable($tableno)) {
            $itemdataArr=json_decode($itemdata);





            $userData = $this->getUserById($userid);
            $order_insert = "insert into orders set cashier_id='".$userData['restaurant_id']."', counter_id='$userid', table_no='$tableno', order_type='D', created='$reservedat', tax='".$userData['tax']."'";
            if(mysql_query($order_insert)) {
                $orderId = mysql_insert_id();
                $order_no=str_pad($orderId, 5, rand(98753, 87563), STR_PAD_LEFT);
                $order_update = "update orders set order_no='$order_no' where id=$orderId";
                $OrderUpdate=mysql_query($order_update);





                return 'SUCCESSFULLY_DONE';
            } else {
                return 'UNABLE_TO_PROCEED';
            }
        } else {
            return 'TABLE_ALREADY_OCCUPIED';
        }
    }
    
    /*------------------------------------------------- Called functions -----------------------------------------------*/
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
        $sel_user = "SELECT * from orders WHERE table_no='$tableno' and is_completed='N' and order_type='D' and DATE(created)='$todayDate'";
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

    public function isUsernameExists($username) {
        $sel_user = "SELECT id, password, status, email from cashiers WHERE email='$username'";
        $user = mysql_query($sel_user);
        if (mysql_num_rows($user) > 0) {
            $data=mysql_fetch_assoc($user);
            return $data;
        }
    }

    public function validateUser($mobileno, $password) {
        $sel_user = "SELECT id from cashiers WHERE email = '$mobileno' AND (password = md5('$password') or password = '$password') and status='A'";
        $user = mysql_query($sel_user);
        if (mysql_num_rows($user) > 0) {
            $userid = mysql_fetch_assoc($user);
            return $userid;
        }
    }
    
    public function getUserById($userid) {
        $sel_user = "SELECT c.*, a.restaurant_name, a.address, a.tax, a.no_of_tables, a.no_of_takeout_tables, a.no_of_waiting_tables, a.table_size, a.table_order, a.takeout_table_size, a.waiting_table_size from cashiers c JOIN admins a ON a.id=c.restaurant_id WHERE c.id=$userid";
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
}
?>
