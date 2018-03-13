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
                                    <span class="title">Restaurant Manager</span>
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
                <?php }?>

                <li class="<?php echo (isset($tab_open) && $tab_open == 'cousines') ? 'active open' : '' ?>">
                    <a href="<?php echo $this->Html->url(array('plugin' => false,'controller' => 'cousines','action' => 'index', 'admin' => 'true')); ?>">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="fa fa-cutlery"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title">Product Management</span>
                            </div>
                        </div>
                    </a>
                </li>

				<!-- Modified by Yishou Liao @ Dec 01 2016 -->
                <li class="<?php echo (isset($tab_open) && $tab_open == 'extracate') ? 'active open' : '' ?>">
                    <a href="<?php echo $this->Html->url(array('plugin' => false,'controller' => 'extracate','action' => 'index', 'admin' => 'true')); ?>">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="fa fa-list"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title">Extra Category Management</span>
                            </div>
                        </div>
                    </a>
                </li>
                <li class="<?php echo (isset($tab_open) && $tab_open == 'extra') ? 'active open' : '' ?>">
                    <a href="<?php echo $this->Html->url(array('plugin' => false,'controller' => 'extras','action' => 'index', 'admin' => 'true')); ?>">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="fa fa-cutlery"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title">Extra Management</span>
                            </div>
                        </div>
                    </a>
                </li>
                <!-- End -->
                
                <li class="<?php echo (isset($tab_open) && $tab_open == 'cashiers') ? 'active open' : '' ?>">
                    <a href="<?php echo $this->Html->url(array('plugin' => false,'controller' => 'cashiers','action' => 'index', 'admin' => 'true')); ?>">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="fa fa-money"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title">Manager Management</span>
                            </div>
                        </div>
                    </a>
                </li>  
               
               <!--               
                <li class="<?php echo (isset($tab_open) && $tab_open == 'cooks') ? 'active open' : '' ?>">
                    <a href="<?php echo $this->Html->url(array('plugin' => false,'controller' => 'cooks','action' => 'index', 'admin' => 'true')); ?>">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="fa fa-cutlery"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title">Staff Managementt</span>
                            </div>
                        </div>
                    </a>
                </li>   
               -->

                <li class="slide_class <?php echo (isset($tab_open) && in_array($tab_open, array('promocodes', 'specials'))) ? 'active open' : '' ?>">
                    <a href="javascript:void(0)">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="fa fa-ticket"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title">Marketing Management</span><i class="icon-arrow"></i>
                            </div>
                        </div>
                    </a>
                    <ul class="sub-menu" style="<?php echo (isset($tab_open) && in_array($tab_open, array('promocodes', 'specials'))) ? '' : 'display: none;' ?>">
                        <li class="<?php echo (isset($tab_open) && $tab_open == 'promocodes') ? 'active' : '' ?>">
                            <a href="<?php echo $this->Html->url(array('plugin' => false,'controller' => 'promocodes','action' => 'index', 'admin' => true)); ?>">
                                <span class="title">Promocodes and Specials</span>
                            </a>
                        </li>

                    </ul>
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

                <li class="slide_class <?php echo (isset($tab_open) && in_array($tab_open, array('rreports', 'rcousines', 'rcategories', 'rcustomers', 'rusers'))) ? 'active open' : '' ?>">
                    <a href="javascript:void(0)">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="fa fa-ticket"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title">Reports</span><i class="icon-arrow"></i>
                            </div>
                        </div>
                    </a>
                    <ul class="sub-menu" style="<?php echo (isset($tab_open) && in_array($tab_open, array('reports', 'rcousines', 'rcategories', 'rcustomers', 'rusers'))) ? '' : 'display: none;' ?>">
                        <li class="<?php echo (isset($tab_open) && $tab_open == 'rreports') ? 'active' : '' ?>">
                            <a href="<?php echo $this->Html->url(array('plugin' => false,'controller' => 'reports','action' => 'index', 'admin' => true)); ?>">
                                <span class="title">Sales Reports</span>
                            </a>
                        </li>

                        <li class="<?php echo (isset($tab_open) && $tab_open == 'rcousines') ? 'active' : '' ?>">
                            <a href="<?php echo $this->Html->url(array('plugin' => false,'controller' => 'reports','action' => 'cousines', 'admin' => true)); ?>">
                                <span class="title">Cousines Reports</span>
                            </a>
                        </li>

                        <li class="<?php echo (isset($tab_open) && $tab_open == 'rcategories') ? 'active' : '' ?>">
                            <a href="<?php echo $this->Html->url(array('plugin' => false,'controller' => 'reports','action' => 'categories', 'admin' => true)); ?>">
                                <span class="title">Categories Reports</span>
                            </a>
                        </li>

                        <li class="<?php echo (isset($tab_open) && $tab_open == 'rcustomers') ? 'active' : '' ?>">
                            <a href="<?php echo $this->Html->url(array('plugin' => false,'controller' => 'reports','action' => 'customers', 'admin' => true)); ?>">
                                <span class="title">Customers Reports</span>
                            </a>
                        </li>

                        <li class="<?php echo (isset($tab_open) && $tab_open == 'rusers') ? 'active' : '' ?>">
                            <a href="<?php echo $this->Html->url(array('plugin' => false,'controller' => 'reports','action' => 'users', 'admin' => true)); ?>">
                                <span class="title">Users Reports</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- li class="<?php echo (isset($tab_open) && $tab_open == 'reports') ? 'active open' : '' ?>">
                    <a href="<?php echo $this->Html->url(array('plugin' => false,'controller' => 'reports','action' => 'index', 'admin' => 'true')); ?>">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="fa fa-reorder"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title">Sales Reports</span>
                            </div>
                        </div>
                    </a>
                </li -->

                <li class="<?php echo (isset($tab_open) && $tab_open == 'attendances') ? 'active open' : '' ?>">
                    <a href="<?php echo $this->Html->url(array('plugin' => false,'controller' => 'attendances','action' => 'index', 'admin' => 'true')); ?>">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="fa fa-user"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title">Attendance Reports</span>
                            </div>
                        </div>
                    </a>
                </li>

                <li class="<?php echo (isset($tab_open) && $tab_open == 'Logs') ? 'active open' : '' ?>">
                    <a href="<?php echo $this->Html->url(array('plugin' => false,'controller' => 'logs','action' => 'index', 'admin' => 'true')); ?>">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="fa fa-user"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title">Logs</span>
                            </div>
                        </div>
                    </a>
                </li>

                <!-- comment out by Ty Dec 13 2016 -->
                <!-- Modified by Yishou Liao @ Dec 06 2016 -->
               <!-- 
                <li class="<?php echo (isset($tab_open) && $tab_open == 'reportslist') ? 'active open' : '' ?>">
                   <a href="<?php echo $this->Html->url(array('plugin' => false,'controller' => 'reportslist','action' => 'index', 'admin' => 'true')); ?>">
                       <div class="item-content">
                           <div class="item-media">
                               <i class="fa fa-reorder"></i>
                           </div>
                           <div class="item-inner">
                               <span class="title">Sales Report List</span>
                           </div>
                       </div>
                   </a>
               </li> -->
                <!-- End -->
            </ul>                
        </nav>
    </div>
</div>