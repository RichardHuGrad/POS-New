<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
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
 * @package       app.Config
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/View/Pages/home.ctp)...
 */
    Router::connect('/', array('controller' => 'admins', 'action' => 'login', 'admin' => true));
/**
 * ...and connect the rest of 'Pages' controller's URLs.
 */
	//Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));
    Router::connect('/admin', array('controller' => 'admins', 'action' => 'login', 'admin' => true));
    Router::connect('/admin/dashboard', array('controller' => 'admins', 'action' => 'dashboard', 'admin' => true));
    Router::connect('/admin/logout', array('controller' => 'admins', 'action' => 'logout', 'admin' => true));
    Router::connect('/admin/change_password', array('controller' => 'admins', 'action' => 'change_password', 'admin' => true));
	Router::connect('/admin/customers', array('controller' => 'customers', 'action' => 'index', 'admin' => true));


    Router::connect('/admin/categories/add', array('controller' => 'categories', 'action' => 'add_edit', 'admin' => true));
    Router::connect('/admin/categories/edit/:id', array('controller' => 'categories', 'action' => 'add_edit', 'admin' => true),
        array(
            'pass' => array('id')
        )
    );

    //Modified by Yishou Liao @ Dec 02 2016
    Router::connect('/admin/extracate/add', array('controller' => 'extracate', 'action' => 'add_edit', 'admin' => true));
    Router::connect('/admin/extracate/edit/:id', array('controller' => 'extracate', 'action' => 'add_edit', 'admin' => true),
        array(
            'pass' => array('id')
        )
    );
    //End
    
    //Modified by Yishou Liao @ Dec 04 2016
    Router::connect('/admin/extras/add', array('controller' => 'extras', 'action' => 'add_edit', 'admin' => true));
    Router::connect('/admin/extras/edit/:id', array('controller' => 'extras', 'action' => 'add_edit', 'admin' => true),
        array(
            'pass' => array('id')
        )
    );
    //End

    Router::connect('/admin/cuisines', array('controller' => 'cousines', 'action' => 'index', 'admin' => true));
    Router::connect('/admin/cuisines/add', array('controller' => 'cousines', 'action' => 'add_edit', 'admin' => true));
    Router::connect('/admin/cuisines/edit/:id', array('controller' => 'cousines', 'action' => 'add_edit', 'admin' => true),
        array(
            'pass' => array('id')
        )
    );


/**
 * Load all plugin routes. See the CakePlugin documentation on
 * how to customize the loading of plugin routes.
 */
	CakePlugin::routes();

/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
	require CAKE . 'Config' . DS . 'routes.php';
