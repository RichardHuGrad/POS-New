<?php
/**
 * Application level View Helper
 *
 * This file is application-wide helper file. You can put all
 * application-wide helper-related methods here.
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
 * @package       app.View.Helper
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Helper', 'View');

/**
 * Common helper
 *
 * Add your application-wide methods in the class below, your helpers
 * will inherit them.
 *
 * @package       app.View.Helper
 */
class CommonHelper extends AppHelper {

    var $helpers = array('Session');

    /**
     * textLimit: For limit the text
     * @param string $text
     * @param int $limit
     * @return string
     */
    function textLimit($text = '', $limit = 50){

        return (strlen($text) > $limit) ? substr($text, 0, $limit) . '...' : $text;

    }

    /**
     * Check the access permission for the admin user
     * @param array $privilage_data data of access permission
     * @param string $module module name of the on which check permission
     * @param string $action type of action in module can_view/can_add/can_edit/can_delete
     * @return bool
     */
    function checkAccess($privilage_data = array(), $module = '', $action = ''){

        $is_super_admin = $this->Session->read('Admin.is_super_admin');
        if('Y' == $is_super_admin){
            return true;
        }else {
            if(!empty($privilage_data)){
                foreach ($privilage_data as $d){
                    if($d['AdminPrivilage']['module'] == $module && 'Y' == $d['AdminPrivilage'][$action]){
                        return true;
                    }
                }
            }
            return false;
        }
    }

}