<?php
class Api {
	//const WECHATSERVER = "https://wx.eatopia.ca/";
	const WECHATSERVER="https://pos.auroratech.top/";
	const WECHATTEST = 0;
	
	public $name = 'Api';
	
	public function get_remote_data($opt) {
		$admin_model = ClassRegistry::init('Admin');
		
		$dt = $admin_model->has_web();
		if (!is_array($dt) || (sizeof($dt) != 2)) {
			return FALSE;
		}
		
		$url = self::WECHATSERVER . "web/index.php?c=site&a=entry&do=storesync&m=zh_dianc&version_id=1&act=".$opt."&sid=" . $dt[0] . "&skey=" . md5($dt[1]);
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
		return $rts;
	}

	public function get_categories() {
		return $this->get_remote_data('category');
	}

	public function get_cousines() {
		return $this->get_remote_data('cousine');
	}
	
	public function get_extrascategories() {
		return $this->get_remote_data('extrascategory');
	}
	
	public function get_extras() {
		return $this->get_remote_data('options');
	}
}
