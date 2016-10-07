<div class="sidebar app-aside" id="sidebar">
    <div class="sidebar-container perfect-scrollbar">
        <nav>
            
            <!-- end: SEARCH FORM -->
            <!-- start: MAIN NAVIGATION MENU -->
            <div class="navbar-title">
                <span>Main Navigation</span>
            </div>
            <ul class="main-navigation-menu">
                <li class="<?php echo (isset($tab_open) && $tab_open == 'dashboard') ? 'active open' : '' ?>">
                    <a href="<?php echo $this->Html->url(array('plugin' => false,'controller' => 'admins','action' => 'dashboard', 'admin' => true)); ?>">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="ti-home"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title">Dashboard</span>
                            </div>
                        </div>
                    </a>
                </li>
                <?php
                $is_super_admin = $this->Session->read('Admin.is_super_admin');
                if('Y' == $is_super_admin){
                 ?>
                    <li class="<?php echo (isset($tab_open) && $tab_open == 'admin_users') ? 'active open' : '' ?>">
                        <a href="<?php echo $this->Html->url(array('plugin' => false,'controller' => 'admins','action' => 'users', 'admin' => true)); ?>">
                            <div class="item-content">
                                <div class="item-media fa-1x">
                                    <i class="fa fa-graduation-cap"></i>
                                </div>
                                <div class="item-inner">
                                    <span class="title">Restaurant Owners Management</span>
                                </div>
                            </div>
                        </a>
                    </li>

                    <li class="<?php echo (isset($tab_open) && $tab_open == 'categories') ? 'active open' : '' ?>">
                        <a href="<?php echo $this->Html->url(array('plugin' => false,'controller' => 'categories','action' => 'index', 'admin' => 'true')); ?>">
                            <div class="item-content">
                                <div class="item-media">
                                    <i class="fa fa-list"></i>
                                </div>
                                <div class="item-inner">
                                    <span class="title">Categories Management</span>
                                </div>
                            </div>
                        </a>
                    </li> 
                   <!--  <li class="<?php echo (isset($tab_open) && $tab_open == 'extras') ? 'active open' : '' ?>">
                        <a href="<?php echo $this->Html->url(array('plugin' => false,'controller' => 'extras','action' => 'index')); ?>">
                            <div class="item-content">
                                <div class="item-media">
                                    <i class="ti-menu"></i>
                                </div>
                                <div class="item-inner">
                                    <span class="title"> Extras Management </span>
                                </div>
                            </div>
                        </a>
                    </li>   -->
                <?php }?>
                <li class="<?php echo (isset($tab_open) && $tab_open == 'cashiers') ? 'active open' : '' ?>">
                    <a href="<?php echo $this->Html->url(array('plugin' => false,'controller' => 'cashiers','action' => 'index', 'admin' => 'true')); ?>">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="fa fa-money"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title">Cashiers Management</span>
                            </div>
                        </div>
                    </a>
                </li>  
                <li class="<?php echo (isset($tab_open) && $tab_open == 'cooks') ? 'active open' : '' ?>">
                    <a href="<?php echo $this->Html->url(array('plugin' => false,'controller' => 'cooks','action' => 'index', 'admin' => 'true')); ?>">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="fa fa-cutlery"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title">Cooks Management</span>
                            </div>
                        </div>
                    </a>
                </li>   

                <li class="<?php echo (isset($tab_open) && $tab_open == 'cousines') ? 'active open' : '' ?>">
                    <a href="<?php echo $this->Html->url(array('plugin' => false,'controller' => 'cousines','action' => 'index', 'admin' => 'true')); ?>">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="fa fa-cutlery"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title">Cuisines Management</span>
                            </div>
                        </div>
                    </a>
                </li>

                <li class="<?php echo (isset($tab_open) && $tab_open == 'promocodes') ? 'active open' : '' ?>">
                    <a href="<?php echo $this->Html->url(array('plugin' => false,'controller' => 'promocodes','action' => 'index', 'admin' => 'true')); ?>">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="fa fa-ticket"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title">Promocode Management</span>
                            </div>
                        </div>
                    </a>
                </li>  

                <li class="<?php echo (isset($tab_open) && $tab_open == 'orders') ? 'active open' : '' ?>">
                    <a href="<?php echo $this->Html->url(array('plugin' => false,'controller' => 'orders','action' => 'index', 'admin' => 'true')); ?>">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="fa fa-cart-plus"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title">Order Management</span>
                            </div>
                        </div>
                    </a>
                </li>

                <li class="<?php echo (isset($tab_open) && $tab_open == 'reports') ? 'active open' : '' ?>">
                    <a href="<?php echo $this->Html->url(array('plugin' => false,'controller' => 'reports','action' => 'index', 'admin' => 'true')); ?>">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="fa fa-reorder"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title">Reports</span>
                            </div>
                        </div>
                    </a>
                </li>
                
            </ul>                
        </nav>
    </div>
</div>