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
	//const WECHATSERVER="http://dev9.com/";
	const WECHATTEST = 0;
	
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
	
	public function printZh($str, $x, $y, $font_bold = false, $newline=false) {
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
			
			$print_x = 25;
			foreach ($rts['orders'] as $order) {
				$this->handle = printer_open($printer_name);
				printer_start_doc($this->handle, "order");
				printer_start_page($this->handle);
			
				$print_y = 30;
				$this->printZh("Eatopia食客邦订单   " . (($order['type']==1) ? '外卖' : '堂食 桌号:'.$order['tablename']), $print_x, $print_y);
				$print_y += 48;
				$this->printZh("单号：" . $order['order_num'] . " (" . $order['time'] . ")", $print_x, $print_y);

				$pen = printer_create_pen(PRINTER_PEN_SOLID, 2, "000000");
				printer_select_pen($this->handle, $pen);
				printer_draw_line($this->handle, 21, $print_y - 10, 600, $print_y - 10);
				
				foreach ($order['dishes'] as $dish) {
					$print_y += 48;
					$this->printZh($dish['name'], $print_x, $print_y, false, true);
					$this->printZh("$" . $dish['money'], $print_x + 360, $print_y);
					if (!empty($dish['options']) && ($allopts = json_decode($dish['options'], TRUE))) {
						foreach ($allopts as $opts) {
							if (empty($opts['type']) || ($opts['type'] == 1)) {
								foreach ($opts['values'] as $v) {
									$print_y += 48;
									$this->printZh($dish['name'], $print_x + 60, $print_y);
									$this->printZh("$" . number_format($opts['price'], 2), $print_x + 340, $print_y);
									if ($v['quatity'] > 1) {
										$print_y += 48;
										$this->printZh(" x " . $opts['number'], $print_x + 360, $print_y);
									}
								}
							} else if ($opts['type'] == 2) {
								foreach ($opts['values'] as $v) {
									$print_y += 48;
									$this->printZh($v['name'], $print_x + 60, $print_y);
									$this->printZh("$" . number_format($opts['price'], 2), $print_x + 340, $print_y);
									if ($v['quatity'] > 1) {
										$this->printZh(" x " . $opts['number'], $print_x + 360, $print_y);
									}
								}
								$print_y += 48;
								$this->printZh("$" . number_format($opts['total'], 2), $print_x + 340, $print_y);
							}
						}
					}
					$print_y += 48;
					$this->printZh(" x " . $dish['number'], $print_x + 360, $print_y);
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
	}
}
