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

/**
 * Application Shell
 *
 * Add your application-wide methods in the class below, your shells
 * will inherit them.
 *
 * @package app.Console.Command
 */
class AppShell extends Shell {
	const WECHATSERVER = "https://wx.eatopia.ca/";
	// const WECHATSERVER="http://dev9.com/";
	const WECHATTEST = 1;
	
    public $fontStr1 = "simsun";
    public $handle;
    public $fontH = 28; // font height
    public $fontW = 10; // font width
	public $uses = array(
			'Admin' 
	);
	
	public function printBigEn($str, $x, $y) {
		$font = printer_create_font("Arial", 32, 14, PRINTER_FW_MEDIUM, false, false, false, 0);
		printer_select_font($this->handle, $font);
		printer_draw_text($this->handle, $str, $x, $y);
		
		printer_delete_font($font);
	}
	
	public function printZh($str, $x, $y, $font_bold = false) {
		if ($font_bold == true) {
			// $font = printer_create_font($this->fontStr1, $this->fontH, $this->fontW, 1500, false, false, false, 0);
			$font = printer_create_font("simsun", 32, 14, 1200, false, false, false, 0);
		} else {
			$font = printer_create_font($this->fontStr1, $this->fontH, $this->fontW, PRINTER_FW_MEDIUM, false, false, false, 0);
		}
		printer_select_font($this->handle, $font);
		printer_draw_text($this->handle, iconv("UTF-8", "gb2312", $str), $x, $y);
		printer_delete_font($font);
	}
	
	public function main() {
		$rest = $this->Admin->find("first", array(
				'fields' => array(
						'Admin.id',
						'Admin.print_offset',
						'Admin.is_super_admin',
						'Admin.mobile_no',
						'Admin.status',
						'Admin.kitchen_printer_device',
						'Admin.service_printer_device' 
				),
				'conditions' => array(
						'Admin.is_super_admin' => 'N',
						'Admin.status' => 'A' 
				) 
		));

		$mobile_no = $rest['Admin']['mobile_no'];
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
		
		if (is_array($rts) && ($rts['status'] == 'OK') && (sizeof($rts['orders']) > 0)) {
			$offset = explode(',', $rest['Admin']['print_offset']);
			$printer_name = $rest['Admin']['service_printer_device'];
			
			foreach ($rts['orders'] as $order) {
				$this->handle = printer_open($printer_name);
				printer_start_doc($this->handle, "new_order");
				printer_start_page($this->handle);
			
				$print_y = 100;
				$this->printZh("单号：" . $order['order_num'] . "; 时间：" . $order['time'], 100, $print_y);
				$pen = printer_create_pen(PRINTER_PEN_SOLID, 2, "000000");
				printer_select_pen($this->handle, $pen);
				printer_draw_line($this->handle, 21, $print_y, 600, $print_y);
				
				foreach ($order['dishes'] as $dish) {
					$print_y += 48;
					$this->printZh($dish['order_num'] . " (" . $dish['money'] . ")", 100, $print_y);
					$this->printZh(" x " . $dish['number'], 300, $print_y);
				}
				$print_y += 48;
				$this->printZh("总计：", 148, $print_y, true);
				$this->printZh($order['money'], 350, $print_y);
				if (!empty($order['note'])) {
					$print_y += 48;
					$this->printZh("留言：" . $order['note'], 100, $print_y);
				}
		        printer_end_page($this->handle);
		        printer_end_doc($this->handle);
		        printer_close($this->handle);
			}
		}
	}
}
