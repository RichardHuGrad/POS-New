<div id="app">
    <!-- sidebar -->
    <?php echo $this->element('sidebar'); ?>
    <!-- / sidebar -->
    <div class="app-content">
        <!-- start: TOP NAVBAR -->
        <?php echo $this->element('header'); ?>
        <!-- end: TOP NAVBAR -->
        <div class="main-content" >
            <div class="wrap-content container" id="container">

                <div class="help-block"><?php echo $this->Session->Flash(); ?></div>

                <div class="container-fluid container-fullw bg-white">

                <div class="row">
                    <div class="col-sm-7">
                        <h1 class="mainTitle ng-scope" translate="dashboard.WELCOME" translate-values="{ appName: app.name }"> Dashboard</h1>
                    </div>
                    <div class="col-sm-5">
                        <!-- start: MINI STATS WITH SPARKLINE -->
                        <ul class="mini-stats pull-right ng-scope" ng-controller="SparklineCtrl">
                            <li>
                                <div class="values">
                                    <strong class="text-dark"><?php echo $record[0]['no_of_order'] ?></strong>
                                    <p class="text-small no-margin">
                                        No Of Orders
                                    </p>
                                </div>
                            </li>
                            <li>
                                <div class="values">
                                    <strong class="text-dark">$<?php echo number_format($record[0]['sale'], 2) ?></strong>
                                    <p class="text-small no-margin">
                                        Total Sale
                                    </p>
                                </div>
                            </li>
                        </ul>
                        <!-- end: MINI STATS WITH SPARKLINE -->
                    </div>
                </div>

                    <div class="row">


                        <?php
                            $is_super_admin = $this->Session->read('Admin.is_super_admin');
                            if('Y' == $is_super_admin){?>
                            <div class="col-sm-6">
                                <div class="panel panel-white no-radius text-center">
                                    <div class="panel-body">
                                        <span class="fa-stack fa-3x"> <i class="fa fa-graduation-cap text-primary"></i></span>
                                        <h2 class="StepTitle">Restaurant Management</h2>
                                        <p class="text-small">
                                            To view detail of Restaurants.
                                        </p>
                                        <p class="links cl-effect-1">
                                           <a href="<?php echo $this->Html->url(array('plugin' => false,'controller' => 'admins','action' => 'users', 'admin' => true)); ?>">
                                           Click Here
                                           </a>

                                        </p>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>

                            <div class="col-sm-6">
                                <div class="panel panel-white no-radius text-center">
                                    <div class="panel-body">
                                        <span class="fa-stack fa-3x"> <i class="fa fa-money text-primary"></i></span>
                                        <h2 class="StepTitle">Staff Management</h2>
                                        <p class="text-small">
                                            To view detail of Staff.
                                        </p>
                                        <p class="links cl-effect-1">
                                           <a href="<?php echo $this->Html->url(array('plugin' => false,'controller' => 'cashiers','action' => 'admin_index', 'admin' => true)); ?>">
                                           Click Here
                                           </a>

                                        </p>
                                    </div>
                                </div>
                            </div>

                        <div class="col-sm-6">
                            <div class="panel panel-white no-radius text-center">
                                <div class="panel-body">
                                    <span class="fa-stack fa-3x"> <i class="fa fa-cart-plus text-primary"></i></span>
                                    <h2 class="StepTitle">Order<br/> Management</h2>
                                    <p class="text-small">
                                        To view detail of Orders.
                                    </p>
                                    <p class="links cl-effect-1">
                                       <a href="<?php echo $this->Html->url(array('plugin' => false,'controller' => 'orders','action' => 'admin_index', 'admin' => true)); ?>">
                                       Click Here
                                       </a>

                                    </p>
                                </div>
                            </div>
                        </div>

                            <div class="col-sm-6">
                                <div class="panel panel-white no-radius text-center">
                                    <div class="panel-body">
                                        <span class="fa-stack fa-3x"> <i class="fa fa-cutlery text-primary"></i></span>
                                        <h2 class="StepTitle">Product Management</h2>
                                        <p class="text-small">
                                            To view detail of Product.
                                        </p>
                                        <p class="links cl-effect-1">
                                           <a href="<?php echo $this->Html->url(array('plugin' => false,'controller' => 'cousines','action' => 'admin_index', 'admin' => true)); ?>">
                                           Click Here
                                           </a>

                                        </p>
                                    </div>
                                </div>
                            </div>
							
                    </div>
                </div>
                
			
            </div>
        </div>
    </div>
    <!-- start: FOOTER -->
    <?php echo $this->element('footer'); ?>
    <!-- end: FOOTER -->    
</div>

