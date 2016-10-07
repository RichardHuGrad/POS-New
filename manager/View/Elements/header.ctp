<header class="navbar navbar-default navbar-static-top">
    <!-- start: NAVBAR HEADER -->
    <div class="navbar-header">
        <a href="#" class="sidebar-mobile-toggler pull-left hidden-md hidden-lg" class="btn btn-navbar sidebar-toggle" data-toggle-class="app-slide-off" data-toggle-target="#app" data-toggle-click-outside="#sidebar">
            <i class="ti-align-justify"></i>
        </a>
         <?php echo $this->Html->link('<h4 style="color:#007AFF;display: inline;margin-left: 16px;">POS Restaurant</h4>',array('plugin' => false,'controller' => 'admins','action' => 'dashboard', 'admin' => true),array('escape' => false)); ?>

        <a href="#" class="sidebar-toggler pull-right visible-md visible-lg" data-toggle-class="app-sidebar-closed" data-toggle-target="#app">
            <i class="ti-align-justify"></i>
        </a>
        <a class="pull-right menu-toggler visible-xs-block" id="menu-toggler" data-toggle="collapse" href=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <i class="ti-view-grid"></i>
        </a>
    </div>
    <!-- end: NAVBAR HEADER -->
    <!-- start: NAVBAR COLLAPSE -->
    <div class="navbar-collapse collapse">
        <ul class="nav navbar-right">                    
            <li class="dropdown current-user">
                <a href class="dropdown-toggle" data-toggle="dropdown">
                     <span class="username"><?php echo $this->Session->read('Admin.firstname'); ?> <i class="ti-angle-down"></i></i></span>
                </a>
                <ul class="dropdown-menu dropdown-dark">
                    <li>
                        <?php echo $this->Html->link('Update Profile',array('plugin' => false,'controller' => 'admins','action' => 'add_editprofile', base64_encode($this->Session->read('Admin.id')), 'admin' => true)); ?>
                    </li>
                    <li>
                        <?php echo $this->Html->link('Change Password',array('plugin' => false,'controller' => 'admins','action' => 'change_password', 'admin' => true)); ?>
                    </li>
                    <li>
                        <?php echo $this->Html->link('Logout',array('plugin' => false,'controller' => 'admins','action' => 'logout', 'admin' => true)); ?>
                    </li>
                </ul>
            </li>
            <!-- end: USER OPTIONS DROPDOWN -->
        </ul>
    </div>
        <!-- end: NAVBAR COLLAPSE -->
</header>
