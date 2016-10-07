<?php
/**
 * @author Manoj Sharma
 */
class DbHandler
{
    private $conn;
    function __construct()
	{
        require_once dirname(__FILE__) . '/DbConnect.php';
        // opening db connection
        $db = new DbConnect();
        $this->conn = $db->connect();
    }	
 
	public function getBeaconsList()
	{
		$select_beacon="SELECT * from beacons where status='A'";
		$beacons= mysqli_query($this->conn,$select_beacon);
        return $beacons;
	}
 
	public function getCategoriesList()
	{
		$select_cat="SELECT * from categories where status='A'";
		$categories=mysqli_query($this->conn,$select_cat);
        return $categories;
	}
	
	public function getMenusList($catId)
	{
		$select_menu="SELECT * from menus where status='A' and categoryid=$catId";
		$menus=mysqli_query($this->conn,$select_menu);
        return $menus;
	}
	
	public function getEventsList()
	{
		$select_menu="SELECT * from events where status='A' and eventdate=CURDATE()";
		$menus=mysqli_query($this->conn,$select_menu);
        return $menus;
	}
 
	public function getOffersList()
	{
		$select_menu="SELECT * from offers where status='A' and NOW() between startedon and endedon";
		$menus=mysqli_query($this->conn,$select_menu);
        return $menus;
	}
	
	public function createUser($firstname, $lastname, $mobile_no, $address, $date_of_birth, $image, $age_proof_doc, $email, $password) {
		$created = date("Y-m-d h:i:s");
		$passwords = md5($password);
        $response = array();
        if (!$this->isEmailExists($email)) {

			$pass= $password;
            $user_image = "";
            if ($image != '') {
				if(isset($image['name']) && $image['name'] != '')
				{ 
					$ex1=explode(".",$image['name']);
					$ext=end($ex1);
					$user_image = rand(1,9999999).'-'.time()."porfileimage.".$ext; 
					if (!move_uploaded_file($image['tmp_name'],"../app/webroot/uploads/customer_images/".$user_image))
					{
						$user_image = ''; 
					}
				}
			}

			$user_age_proof_doc = ""; 
            if ($age_proof_doc != '') {
				if(isset($age_proof_doc['name']) && $age_proof_doc['name'] != '')
				{ 
					$ex1=explode(".",$age_proof_doc['name']);
					$ext=end($ex1);
					$user_age_proof_doc = rand(1,9999999).'-'.time()."doc.".$ext; 
					if (!move_uploaded_file($age_proof_doc['tmp_name'],"../app/webroot/uploads/customer_age_proof_docs/".$user_age_proof_doc))
					{
						$user_age_proof_doc = ""; 
					}
				}
			}

            $save_user = "INSERT INTO users(firstname, lastname, mobile_no, address, date_of_birth, image, age_proof_doc, email, password, created) VALUES '($firstname', '$lastname', '$mobile_no', '$address', '$date_of_birth', '$user_image', '$user_age_proof_doc', '$email', '$password','$created')";	

            $result = mysqli_query($this->conn,$save_user);
            if ($result) {
                $inserted_id = mysqli_insert_id($this->conn);

                // update user doc and image name as related to user id
                if($user_age_proof_doc) {
                	$new_name = "age_proof_".base64_encode($inserted_id)."_doc";
                	@rename("../app/webroot/uploads/customer_age_proof_docs/".$user_age_proof_doc, "../app/webroot/uploads/customer_age_proof_docs/".$new_name);
                	
                	// update customer image name to DB table
	                $sql = "update users set age_proof_doc = '$new_name' where users.id = '$inserted_id'";
		            mysqli_query($this->conn, $sql);
		            $user_age_proof_doc = $new_name;
                }

                if($user_image) {
                	$new_name = "profile_image_".base64_encode($inserted_id);
                	@rename("../app/webroot/uploads/customer_images/".$user_image, "../app/webroot/uploads/customer_images/".$new_name);

                	// update customer image name to DB table
	                $sql = "update users set image = '$new_name' where users.id = '$inserted_id'";
		            mysqli_query($this->conn, $sql);
		            $user_image = $new_name;
                }

                $response['userid'] = $inserted_id;
                $response['firstname'] = '' . $firstname;

                $response['lastname'] = '' . $lastname;
                $response['mobile_no'] = '' . $mobile_no;
                $response['address'] = '' . $address;
                $response['date_of_birth'] = '' . $date_of_birth;
                $response['image'] = '' . $user_image;
                $response['age_proof_doc'] = '' . $user_age_proof_doc;
                $response['firstname'] = '' . $firstname;
                $response['firstname'] = '' . $firstname;
                $response['firstname'] = '' . $firstname;


                $response['email'] = '' . $email;
				
                $sub = 'Register Successfully on Utrem App';
				$headers = "From: info@utremapp.com\r\n"; 
				$headers .= "Reply-To: \r\n";
				$headers .= "MIME-Version: 1.0\r\n";
				$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
				$message = "<html><head></head><body>
				<table><tr><td> Hello : ".$firstname." ".$lastname." </td></tr>
				<tr><td>Your are successfully registered on Utrem App. </td></tr>  
				<tr><td> Your User Name : ".$firstname." ".$lastname." </td></tr>
				<tr><td> Password : ".$pass." </td></tr>
				<tr><td>Thanks, </td></tr>
				<tr><td>Utrem App </td></tr>        
				</table>
				</body></html>";											
				$mail_res = mail($email, $sub, $message, $headers);  
					
                return $response;
            } else {
                return 'UNABLE_TO_PROCEED';
            }
        
		} else {
            return 'EMAIL_ALREADY_EXISTED';
        }
        return $response;
    }
	
	 private function isEmailExists($email) {
        $save_user = "SELECT id from users WHERE email ='$email'";
        $result = mysqli_query($this->conn,$save_user);
        $num_rows = mysqli_num_rows($result);
        return $num_rows > 0;
    }
	
	
	public function loginuser_11_5_2016($name, $email, $loginfrom, $password, $device_token, $user_type)
	{            

		switch($user_type) {
			case 'C':
				$table = 'customers';
				break;
			case 'V':
				$table = 'vendors';
				break;
			case 'DB':
				$table = 'delivery_boys';
				break;
			case 'VU':
				$table = 'vendor_users';
				break;
			default:
				$table = 'customers';
		}

		if ($loginfrom == 'F')
		{        
			$chkusr = mysqli_query($this->conn,"select * from {$table} where  email='$email'");
			if(mysqli_num_rows($chkusr)>0) {      
				$chkusr = mysqli_query($this->conn,"select * from {$table} where email='$email'");
		
				if(mysqli_num_rows($chkusr)>0) {   
					$usrdat = mysqli_fetch_assoc($chkusr); 
					if($usrdat['status'] == 'D') {   
						return USER_ACCOUNT_DEACTVATED; 
					}
					else {
						$update_user = "UPDATE {$table} set device_token='$device_token', loginfrom='F' where email='$email'";
						$result = mysqli_query($this->conn,$update_user);
						return $usrdat; 
					}
				}
				else {
					
					return INVALID_REQUEST;
				}
			} 
			else {  
				$chkmmel = 0;
				if($email != '') {  
					$chkkmel = mysqli_query($this->conn,"select * from {$table} where email='$email' ");
					if(mysqli_num_rows($chkkmel)>0) {
						$chkmmel = 1;
					}
				}
				
				if($chkmmel == 0) { 
					if($password != '') {  
						$npassword = $password;
						$password = md5($password); 
					}
					else {  
						$npassword = $this->generateRandomPassword(6);
						$password = md5($npassword);
					}
					
					$save_user = "insert into {$table} set name='".$name."', 
					                                 email='".$email."',
													 password='".$password."',
													 status='A', 												 
													 loginfrom='F',   
													 created='".date('Y-m-d H:i:s')."'";   
						//	echo $save_user; exit;								 
					$user=mysqli_query($this->conn,$save_user);
					$insertId=mysqli_insert_id($this->conn);
					if ($insertId>0)
						{													
						$usrdat = mysqli_query($this->conn,"select * from {$table} where id=".$insertId);
						if(mysqli_num_rows($usrdat)>0) {
							$datusr = mysqli_fetch_assoc($usrdat);
							$msg = "Your Login Id : " . $email . " AND  Password : " . $npassword;
							@mail($email, "Utrem app login detail", $msg, "From :info@utremapp.com");
						
							return $datusr;   
						} 
					}
					else
					{
						return UNABLE_TO_PROCEED;
					}
				}else{
					return EMAIL_ALREADY_EXISTED;   
				}
			}
		}  
		else if($loginfrom == 'G') 
		{        
			$chkusr = mysqli_query($this->conn,"select * from {$table} where  email='$email'");
			if(mysqli_num_rows($chkusr)>0) {   
			//echo "select * from users where loginfrom='G' and email='$email'" ;  
				$chkusr = mysqli_query($this->conn,"select * from {$table} where email='$email'");
		
				if(mysqli_num_rows($chkusr)>0) {   
					$usrdat = mysqli_fetch_assoc($chkusr); 
					if($usrdat['status'] == 'D') {   
						return USER_ACCOUNT_DEACTVATED; 
					}else{
						$update_user = "UPDATE {$table} set device_token='$device_token', loginfrom='G' where email='$email'";
						$result = mysqli_query($this->conn,$update_user);
						return $usrdat; 
					}
				}else {
					
					return INVALID_REQUEST;
				}
			}else{  
				$chkmmel = 0;
				if($email != '') {  
					$chkkmel = mysqli_query($this->conn,"select * from {$table} where email='$email' ");
					if(mysqli_num_rows($chkkmel)>0) {
						$chkmmel = 1;
					}
				}
				
				if($chkmmel == 0) { 
					if($password != '') {  
						$npassword = $password;
						$password = md5($password); 
					}else{  						
						$npassword = $this->generateRandomPassword(6);
						$password = md5($npassword);						 
					}					
					$save_user = "insert into {$table} set name='".$name."', 
					                                 email='".$email."',
													 password='".$password."',
													 status='A', 												 
													 loginfrom='G',   
													 created='".date('Y-m-d H:i:s')."'";   								 
					$user=mysqli_query($this->conn,$save_user);
					$insertId=mysqli_insert_id($this->conn);
					if ($insertId>0)
						{														
						$usrdat = mysqli_query($this->conn,"select * from {$table} where id=".$insertId);
						if(mysqli_num_rows($usrdat)>0) {
							$datusr = mysqli_fetch_assoc($usrdat);							
							$msg = "Your Login Id : " . $email . " AND  Password : " . $npassword;
							@mail($email, "Utrem app login detail", $msg, "From :info@utremapp.com");
							return $datusr;   
						} 
					}else{
						return UNABLE_TO_PROCEED;
					}
				}else{
					return EMAIL_ALREADY_EXISTED;   
				}
			}
		}
		else if($loginfrom == 'N')
		{
			if($password != '') {  
			$chkusrlogin = mysqli_query($this->conn,"SELECT * FROM {$table} WHERE email='$email'");
			if(mysqli_num_rows($chkusrlogin)>0) { 
			$password1 = md5($password);				
			$usrdats = mysqli_query($this->conn,"SELECT * FROM {$table} WHERE password='".$password1."' AND email='$email'");
				if(mysqli_num_rows($usrdats)>0) { 
					$datusr = mysqli_fetch_assoc($usrdats); 
					if($datusr['status'] == 'D') { 
						return USER_ACCOUNT_DEACTVATED; 
					}else{
						$update_user = "UPDATE {$table} SET device_token='$device_token', loginfrom='N' WHERE email='$email'";
						$result = mysqli_query($this->conn,$update_user);
						return $datusr; 
					}
				}else{
					return INVALID_EMAIL_PASSWORD;   
				}
			}else{
				return INVALID_EMAIL;
			}
			}
			else {
				return NEED_PASSWORD;
			}
		}else{ 
			return INVALID_REQUEST;     
		}
    }

	public function loginuser($name, $email, $loginfrom, $password, $device_type, $device_token, $user_type)
	{

		switch($user_type) {
			case 'C':
				$table = 'customers';
				break;
			case 'V':
				$table = 'vendors';
				break;
			case 'DB':
				$table = 'delivery_boys';
				break;
			case 'VU':
				$table = 'vendor_users';
				break;
			default:
				$table = 'customers';
		}

		$login_types = array('N', 'F', 'G');
		if(in_array($loginfrom, $login_types)){

			$query = "SELECT * FROM {$table} WHERE email = '{$email}' LIMIT 1";
			$chkusrlogin = mysqli_query($this->conn, $query);
			$datusr = mysqli_fetch_assoc($chkusrlogin);
			$datetime = date('Y-m-d H:i:s');
			if('N' == $loginfrom) {

				if(!empty($datusr) && $datusr['password'] == md5($password)) {
					if ('A' == $datusr['status']) {
						if ('VU' != $user_type) {
							$update_user = "UPDATE {$table} SET loginfrom = '{$loginfrom}', device_token = '{$device_token}', device_type = '{$device_type}'";

							if('C' == $user_type) {
								$update_user .= ", last_login = '{$datetime}'";
							}
							$update_user .= " WHERE email='{$email}' LIMIT 1";
							mysqli_query($this->conn, $update_user);
						}
						return $datusr;
					} else {
						return USER_ACCOUNT_DEACTVATED;
					}
				}else{
					return INVALID_EMAIL_PASSWORD;
				}
			}else{

				if('C' == $user_type) {
					if (!empty($datusr)) {
						if ($datusr['status'] == 'A') {
							$update_user = "UPDATE {$table} SET device_token='{$device_token}', device_type = '{$device_type}', loginfrom = '{$loginfrom}', last_login = '{$datetime}' WHERE email = '{$email}' LIMIT 1";
							mysqli_query($this->conn, $update_user);
							return $datusr;
						} else {
							return USER_ACCOUNT_DEACTVATED;
						}
					} else {
						if ('' != $password) {
							$npassword = $password;
							$password = md5($password);
						} else {
							$npassword = $this->generateRandomPassword(6);
							$password = md5($npassword);
						}
						$save_user = "INSERT INTO {$table} SET firstname = '{$name}', 
												 email = '{$email}',
												 password = '{$password}',
												 device_token = '{$device_token}',
												 device_type = '{$device_type}',
												 is_verified = 'N',
												 status = 'A', 												 
												 loginfrom = '{$loginfrom}',   
												 last_login = '{$datetime}',   
												 created = '{$datetime}'";

						mysqli_query($this->conn, $save_user);
						$insertId = mysqli_insert_id($this->conn);
						if ($insertId > 0) {
							$usrdat = mysqli_query($this->conn, "SELECT * FROM {$table} WHERE id = {$insertId}");
							if (mysqli_num_rows($usrdat) > 0) {
								$datusr = mysqli_fetch_assoc($usrdat);
								$msg = "Your Login Id : " . $email . " AND  Password : " . $npassword;
								@mail($email, "Utrem app login detail", $msg, "From :info@utremapp.com");
								return $datusr;
							}
						} else {
							return UNABLE_TO_PROCEED;
						}
					}
				}else{
					return INVALID_EMAIL_PASSWORD;
				}
			}
		}else{
			return INVALID_REQUEST;
		}
	}
	
	 public function generateRandomPassword($length) {
        $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
        for ($i = 0; $i <= $length; $i++) {
            $num = rand(0, strlen($characters) - 1);
            $output[] = $characters[$num];
        }
        return implode($output);
    }
	
	 //send new password on forgot password
    public function sendNewPassword($email) {
        $sel_user = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($this->conn,$sel_user);
        $user = mysqli_fetch_assoc($result);
        if (!empty($user)) {
            $newpass = $this->generateRandomPassword(6);
            $newpassdb = md5($newpass);
            $update_user = "UPDATE users set password='$newpassdb' where email='$email'";
            $result = mysqli_query($this->conn,$update_user);
            $msg = "Your Login Id : " . $email . " AND New Password : " . $newpass;
            //echo $user['email'];die;


            @mail($user['email'], "utremapp New password", $msg, "From :info@utremapp.com");

            if ($result) {
                return SUCCESSFULLY_DONE;
            } else {
                return UNABLE_TO_PROCEED;
            }
        } else {
            return INVALID_USERNAME;
        }
    }

    //update passsword
    public function changePassword($user_id, $oldpassword, $newpassword)
	{		 
	//echo $user_id; exit;
		$select_user="SELECT * FROM users WHERE id =".$user_id;
		$user_res=mysqli_query($this->conn,$select_user);
		$user=mysqli_fetch_assoc($user_res);
		if (!empty($user))
		{
			//$oldpassword=md5($oldpassword);
			if ($user['password']==$oldpassword || $user['password']==md5($oldpassword))
			{  
				$newpassword = md5($newpassword);
				$update_user="UPDATE users set password='$newpassword',modified=NOW() where id=$user_id";
				$user_update=mysqli_query($this->conn,$update_user);
				$result=mysqli_affected_rows($this->conn);
				if ($result){
					return SUCCESSFULLY_DONE;
				}else{
					return UNABLE_TO_PROCEED;
				}
			}
			else{
				return INVALID_OLD_PASSWORD;
			}
		}
		else
		{
			return INVALID_USER; 
		}
	}
	
	public function updateUser($user_id, $fullname, $gender, $dob, $image, $gluten_free,$vegetarian, $vegan, $dairy_free, $low_sodium, $Kosher, $halal, $organic)
	{    
        $response = array();
        $db = new DbHandler();
		$chk_user = mysqli_query($this->conn,"select  * from users where id=".$user_id);
		//echo "select * from users where id=$user_id"; exit;
		if(mysqli_num_rows($chk_user) > 0) {  
			$pass_data = mysqli_fetch_array($chk_user);
			$updtd = date('Y-m-d H:i:s');
			$country  = (isset($country) && !empty($country)) ? $country : 0;
			$city     = (isset($city) && !empty($city)) ? $city : 0;
			$select_user="update users set name='$fullname', gender='$gender', dob='$dob', gluten_free='$gluten_free', vegetarian='$vegetarian' , vegan='$vegan' , dairy_free='$dairy_free' , low_sodium='$low_sodium' , Kosher='$Kosher' , halal='$halal', organic='$organic' ";			
			if ($image != ''){ 		
		      $user_image = mysqli_query($this->conn,"select image from users where id=".$user_id);
		      $uimagedata = mysqli_fetch_array($user_image);
			  //echo $uimagedata['image']; exit;
				if(isset($image['name']) && $image['name'] != '')
				{ 
					$ex1=explode(".",$image['name']);
					$ext=end($ex1);
					$user_image = $user_id.'-'.time()."_flixa.".$ext; 
					//echo "../app/webroot/uploads/users/$image "; exit;
					if (move_uploaded_file($image['tmp_name'],"../app/webroot/uploads/profile_pic/".$user_image))
					{
						if(file_exists("../app/webroot/uploads/profile_pic/".$uimagedata['image'])){
                         	@unlink("../app/webroot/uploads/profile_pic/".$uimagedata['image']);
                       }
					
					}
				}
						 
				$select_user.=", image='$user_image'"; 
			}						
			$select_user.=", modified=NOW() where id=$user_id"; 
		//echo $select_user; exit;
			$user_res = mysqli_query($this->conn,$select_user);
			
			$result=mysqli_affected_rows($this->conn);
				if($result > 0) {  
					$chk_user = mysqli_query($this->conn,"select * from users where id=".$user_id);
					$udata = mysqli_fetch_array($chk_user);
					//print_r($udata); exit;
					return $udata;
				}else { 
					return UNABLE_TO_PROCEED;
				}
		}
		else { 
			return EMAIL_ALREADY_EXISTED;
		}
    }
	
	
	  public function validateUser($email, $password) {
		//echo $email . $password; exit;
        $sel_user = "SELECT id  from users WHERE email = '" . $email . "' AND (password = '" . $password . "' or password = '" . md5($password) . "') and status='A'";       
        $user = mysqli_query($this->conn,$sel_user);
        $user_id = mysqli_fetch_assoc($user);
        if (mysqli_num_rows($user) > 0) {
            return $user_id;
        }
    }
	
	
	public function getPageContent($page_id) {
		
		$res=mysqli_query($this->conn,"select * from pages  where id=".$page_id." and status='A'");
		if(mysqli_num_rows($res) > 0) {
			$r = mysqli_fetch_assoc($res);
		    $response = array();
			$response['id'] = $r['id'];
			$response['name'] = strip_tags($r['name']);			
			$response['discription'] = str_replace(array("\r", "\n"), '', strip_tags($r['discription']));
			return $response;
		}
		else {
			return UNABLE_TO_PROCEED;
		}
	}	
	
	
	 public function restroListByLatLlong($user_id, $latitude, $longitude){
        $response    = array();
        $query = "SELECT *,
                        ( 
                            6371 * acos(
                                        cos( radians($latitude) ) 
                                        * cos( radians( lat ) ) 
                                        * cos( radians( `long` ) - radians($longitude) ) 
                                        + sin( radians($latitude) ) * sin( radians( lat ) ) 
                                    ) 
                        ) as distance 
                            from locations 
                                where is_deleted='N'
									having distance < 1000 
                                    order by distance
									";
        // echo  $query; exit;
        $result = mysqli_query($this->conn,$query);
        $num_rows = mysqli_num_rows($result);
        if ($num_rows > 0) {
                $restaurantListArray = '';
                $count = 0;
                while ($array = mysqli_fetch_assoc($result)) {
                    $restaurantListArray['id'] = $array['id'];
                    $restaurantListArray['name'] = $array['name'];
                    $restaurantListArray['address'] = $array['address'];
                    $restaurantListArray['unique_id'] = $array['unique_id'];

                    $restaurantListArray['pro_beacon_key'] = $array['pro_beacon_key'];
                    $restaurantListArray['dev_beacon_key'] = $array['dev_beacon_key'];
                    $restaurantListArray['display_name'] = $array['display_name'];
                    $restaurantListArray['phone'] = $array['phone'];
                    $restaurantListArray['lat'] = $array['lat'];
                    $restaurantListArray['long'] = $array['long'];

					$restaurantListArray['distance'] = number_format($array['distance'],1);	
                            
                    $response[$count] = $restaurantListArray;
                    $count++;
                }

            return $response;
        } else {
            return 'UNABLE_TO_PROCEED';
        }
    }

    public function listrestaurants($user_id){
        $response    = array();
        $query = "SELECT *
                            from locations 
                                where is_deleted='N'
                                ";
        // echo  $query; exit;
        $result = mysqli_query($this->conn,$query);
        $num_rows = mysqli_num_rows($result);
        if ($num_rows > 0) {
                $restaurantListArray = '';
                $count = 0;
                while ($array = mysqli_fetch_assoc($result)) {
                    $restaurantListArray['id'] = $array['id'];
                    $restaurantListArray['name'] = $array['name'];
                    $restaurantListArray['address'] = $array['address'];
                    $restaurantListArray['unique_id'] = $array['unique_id'];

                    $restaurantListArray['pro_beacon_key'] = $array['pro_beacon_key'];
                    $restaurantListArray['dev_beacon_key'] = $array['dev_beacon_key'];
                    $restaurantListArray['display_name'] = $array['display_name'];
                    $restaurantListArray['phone'] = $array['phone'];
                    $restaurantListArray['lat'] = $array['lat'];
                    $restaurantListArray['long'] = $array['long'];
                    $response[$count] = $restaurantListArray;
                    $count++;
                }
            return $response;
        } else {
            return 'UNABLE_TO_PROCEED';
        }
    }
    public function getCategories($restaurant_id){
        $response    = array();
        $query = "SELECT *
                            from categories 
                                where location_id='$restaurant_id' and status = 'A'
                                ";
        $result = mysqli_query($this->conn,$query);
        $num_rows = mysqli_num_rows($result);
        if ($num_rows > 0) {
                $categorieslistarray = '';
                $count = 0;
                while ($array = mysqli_fetch_assoc($result)) {
                    $categorieslistarray['id'] = $array['id'];
                    $categorieslistarray['name'] = $array['name'];
                    $categorieslistarray['unique_id'] = $array['unique_id'];
					$categorieslistarray['image'] = $array['unique_id']?CATEGORY_IMAGE_URL . 'thumbnail/' .$array['image']:"";
                    $response[$count] = $categorieslistarray;
                    $count++;
                }
            return $response;
        } else {
            return 'UNABLE_TO_PROCEED';
        }
    }
    public function getMenus($categoryid){
        $response    = array();
        $query = "SELECT *
                            from menus 
                                where categoryid='$categoryid' and status = 'A' and  is_deleted = 'N'
                                ";
        $result = mysqli_query($this->conn,$query);
        $num_rows = mysqli_num_rows($result);
        if ($num_rows > 0) {
                $categorieslistarray = '';
                $count = 0;
                while ($array = mysqli_fetch_assoc($result)) {

                	// get menu related items
	                $related_resoponse = '';
                	if($array['related_items']) {
	                	$query = "SELECT id, name
	                            from extras 
	                                where id in(".$array['related_items'].") 
	                                ";
				        $result_items = mysqli_query($this->conn,$query);
				        $num_rows = mysqli_num_rows($result_items);
				        if ($num_rows > 0) {
                			$count_items = 0;
			                while ($related_items_array = mysqli_fetch_assoc($result_items)) {
			                	$related_items = array();
			                	$related_items['id'] = $related_items_array['id'];
			                    $related_items['name'] = $related_items_array['name'];
			                    $related_resoponse[$count_items] = $related_items;
			                    $count_items++;
			                }
			            }
			        }

			        // get menu images here
			        $response_images = "";
			        $query = "SELECT image
	                            from menu_images 
	                                where menu_id = '".$array['id']."'
	                                ";
				        $result_images = mysqli_query($this->conn,$query);
				        $num_rows = mysqli_num_rows($result_images);
				        if ($num_rows > 0) {
			                while ($image_array = mysqli_fetch_assoc($result_images)) {
			                	$images = array();
			                	$images['image'] = MENU_IMAGE_URL . 'thumbnail/' .$image_array['image'];
			                    $response_images[] = $images;
			                }
			            }

                    $categorieslistarray['id'] = $array['id'];
                    $categorieslistarray['name'] = $array['name'];
                    $categorieslistarray['unique_id'] = $array['unique_id'];
                    $categorieslistarray['price'] = $array['price'];
                    $categorieslistarray['ingredients'] = $array['ingredients'];
                    $categorieslistarray['extras'] = $related_resoponse;
                    $categorieslistarray['images'] = $response_images;
                    $response[$count] = $categorieslistarray;
                    $count++;
                }
            return $response;
        } else {
            return 'UNABLE_TO_PROCEED';
        }
    }
	
}
 
?>
