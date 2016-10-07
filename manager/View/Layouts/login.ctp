<?php
/**
 *
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
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
$cakeDescription = __d('cake_dev', 'Welcome to Tueeter');
?>
<!DOCTYPE html>
<html>
    <head>
        <?php echo $this->Html->charset(); ?>
        <title>
            POS Restaurant Administrator panel  -:
            <?php echo $title_for_layout; ?>
        </title>
        <?php echo $this->Html->meta('favicon.ico', 'images/favicon.ico', array('type' => 'icon')); ?>
        <?php
        echo $this->Html->css(array('bootstrap.min.css', 'font-awesome.min.css', 'themify-icons.min.css', 'animate.min.css', 'perfect-scrollbar.min.css', 'switchery.min.css', 'styles', 'plugins', 'themes/theme-1.css'));
        echo $this->Html->script(array('jquery.min.js', 'bootstrap.min.js', 'modernizr.js', 'jquery.cookie.js', 'perfect-scrollbar.min.js', 'switchery.min.js', 'jquery.sparkline.min.js', 'main.js', 'login'));
        echo $this->Html->scriptBlock("
		jQuery(document).ready(function () {
			Login.init();
            Main.init();
        });
		", array('inline' => false));
        ?>
        <?php
        $css = array('styles');
        echo $this->Html->css($css);

        $js = array('jquery.min', 'tytabs.jquery.min', 'html5');
        echo $this->Html->script($js);

        echo $this->fetch('meta');
        echo $this->fetch('css');
        echo $this->fetch('script');
        ?>
    </head>
    <body class="login">
        <!-- start: LOGIN -->
        <div class="row">
            <div class="main-login col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-4 col-md-offset-4">
                <div class="logo margin-top-30" style="text-align:center;">
                    <?php //echo $this->Html->image('/img/logo.png', array('border' => 0, 'alt' => 'Utrem', 'title' => 'Utrem')); ?>
                    <h3 style="color:#007AFF">POS Restaurant</h3>
                </div>
                <!-- start: LOGIN BOX -->
                <div class="box-login">
                    <?php echo $this->Session->flash(); ?>
                    <?php echo $this->Form->create('Admin', array('type' => 'POST')) ?>
                    <fieldset>
                        <legend>
                            Sign in to your account
                        </legend>
                        <p>
                            Please enter your name and password to log in.
                        </p>
                        <div class="form-group">
                            <span class="input-icon">
                                <?php echo $this->Form->input('username', array('type' => 'text', 'placeholder' => 'Username', 'required' => 'required', 'class' => 'form-control', 'div' => false, 'label' => false)) ?>
                                <i class="fa fa-user"></i> </span>
                        </div>
                        <div class="form-group form-actions">
                            <span class="input-icon">
                                <?php echo $this->Form->input('password', array('type' => 'password', 'placeholder' => 'Password', 'required' => 'required', 'class' => 'form-control', 'div' => false, 'label' => false)) ?>
                                <i class="fa fa-lock"></i>                                    
                        </div>
                        <div class="form-actions">
                            <?php echo $this->Form->button('Login <i class="fa fa-arrow-circle-right"></i>', array('type' => 'submit', 'class' => 'btn btn-primary pull-right')); ?>
                        </div>                            
                    </fieldset>
                    <?php echo $this->Form->end(); ?>
                    <!-- start: COPYRIGHT -->
                    <div class="copyright">
                        &copy; <span class="current-year"></span><span class="text-bold text-uppercase">pos restaurant</span>. <span>All rights reserved</span>
                    </div>
                    <!-- end: COPYRIGHT -->
                </div>
                <!-- end: LOGIN BOX -->
            </div>
        </div>
    </body>
</html>

