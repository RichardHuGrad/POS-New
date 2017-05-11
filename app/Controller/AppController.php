<?php

/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
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
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('Controller', 'Controller');
define('SITEURL', Router::url('/', true));

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

    public $components = array(
        'Session',
        'Cookie',
        'Print',
        'Time',
        'ApiHelper',
        'Auth' => array(
            'loginAction' => array('controller' => 'homes', 'action' => 'index'),
            'loginRedirect' => array('controller' => 'homes', 'action' => 'dashboard'),
            'logoutRedirect' => array('controller' => 'homes', 'action' => 'index'),
            'authenticate' => array(
                'Form' => array(
                    'userModel' => 'Cashier',
                    'fields' => array(
                        'username' => 'email',
                        'password' => 'password'
                    ),
                    'scope' => array('Cashier.status' => 'A')
                )
            )
        )
    );
    public $helpers = array('Session', 'Html', 'Form', 'Common');

    public function beforeFilter() {

        if (isset($this->request->query['debug']) && 'true' === $this->request->query['debug']) {
            Configure::write('debug', 2);
        }

        $sessionKey = 'Front';
        authComponent::$sessionKey = $sessionKey;

        Security::setHash('md5');
        parent::beforeFilter();
        $this->layout = 'admin';
        $this->Auth->allow('signin');
        //pr($this->Session->read('Auth')); die;
        //pr($this->params); die;

        if (!$this->Session->read('Config.language')) {
            $this->Session->write('Config.language', 'zho');
        }
    }

    function resize($image_name, $size, $folder_name) {
        $file_extension = $this->getFileExtension($image_name);
        switch ($file_extension) {
            case 'jpg':
            case 'jpeg':
                $image_src = imagecreatefromjpeg($folder_name . '/' . $image_name);
                break;
            case 'png':
                $image_src = imagecreatefrompng($folder_name . '/' . $image_name);
                break;
            case 'gif':
                $image_src = imagecreatefromgif($folder_name . '/' . $image_name);
                break;
        }
        $true_width = imagesx($image_src);
        $true_height = imagesy($image_src);

        $width = $size;
        $height = ($width / $true_width) * $true_height;

        $image_des = imagecreatetruecolor($width, $height);

        imagecopyresampled($image_des, $image_src, 0, 0, 0, 0, $width, $height, $true_width, $true_height);

        switch ($file_extension) {
            case 'jpg':
            case 'jpeg':
                imagejpeg($image_des, $folder_name . '/thumbnail/' . $image_name, 100);
                break;
            case 'png':
                imagepng($image_des, $folder_name . '/thumbnail/' . $image_name, 8);
                break;
            case 'gif':
                imagegif($image_des, $folder_name . '/thumbnail/' . $image_name, 100);
                break;
        }
        return $image_des;
    }

    function timeAgo($time_ago){
        $dec = (time() - $time_ago)/3600;
         // start by converting to seconds
        $seconds = ($dec * 3600);
        // we're given hours, so let's get those the easy way
         $hours = floor($dec);
        // since we've "calculated" hours, let's remove them from the seconds variable
        $seconds -= $hours * 3600;
        // calculate minutes left
        $minutes = floor($seconds / 60);
        // remove those from seconds as well
        $seconds -= $minutes * 60;
        // return the time formatted HH:MM:SS
        if($hours < 1)
            return $this->lz($minutes).":".$this->lz($seconds);
        else
            return $this->lz($hours).":".$this->lz($minutes).":".$this->lz($seconds);
    }
    function lz($num)
    {
        return (strlen($num) < 2) ? "0{$num}" : str_pad(round($num), 2, 0, STR_PAD_LEFT);
    }

    function getFileExtension($file) {
        $extension = pathinfo($file, PATHINFO_EXTENSION);
        $extension = strtolower($extension);
        return $extension;
    }

    /**
     * Convert string into url string
     * @param string $string
     * @return string
     */
    function converStringToUrl($string = '') {

        $string = preg_replace('/[^\\pL0-9]+/u', '-', $string);
        $string = trim($string, "-");
        $string = iconv("utf-8", "us-ascii//TRANSLIT", $string);
        $string = preg_replace('/[^-a-z0-9]+/i', '', $string);
        $string = strtolower($string);
        return $string;
    }

    /**
     * To get global setting data from global_settings table
     * @return mixed
     */
    function globalSettings() {
        $this->loadModel('GlobalSetting');
        $setting = $this->GlobalSetting->find('first');
        return array_shift($setting);
    }

    /**
     * to send email
     * @param string $to_email
     * @param string $subject
     * @param string $view
     * @param string $layout
     * @param array $viewVars
     * @param string $format
     * @return bool
     */
    function sendMail($to_email = '', $subject = '', $view = '', $layout = '', $viewVars = array(), $format = 'html') {

        //$to_email = 'narendra.prajapat@brsoftech.org';
        $setting = $this->globalSettings();
        if (!empty($setting['from_email']) && !empty($to_email)) {
            $from = $setting['from_email'];

            App::uses("CakeEmail", "Network/Email");
            $mail = new CakeEmail();
            $mail->from(array($from => EMAIL_FROM_TEXT));
            $mail->to($to_email);
            $mail->template($view, $layout);
            $mail->emailFormat($format);
            $mail->viewVars($viewVars);
            $mail->subject($subject);
            try {
                //For now sending email is disabled due to restrict unwanted mail sending in development process
                $mail->send();
                return true;
            } catch (Exception $ex) {
                return false;
            }
        }
    }

}
