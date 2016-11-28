<?php echo $this->Html->css(array('sweet-alert.css', 'ie9.css', 'toastr.min.css', 'select2.min.css', 'DT_bootstrap.css'), null, array('inline' => false));
echo $this->Html->script(array('select2.min.js', 'jquery.dataTables.min.js', 'table-data.js', 'sweet-alert.min.js', 'ui-notifications.js'), array('inline' => false)); ?>

<script type="text/javascript">
    jQuery(document).ready(function () {
        UINotifications.init();
        TableData.init();

        jQuery('#reset_button').click(function(){
            jQuery('.reset-field').val('');
            jQuery('#order_by').val('Customer.created DESC');
        });

        jQuery('#records_per_page').change(function(){
            jQuery('#pageSizeForm').submit();
        });

    });
</script>

<?php $option_admin_type = array('Y' => 'Super Admin', 'N' => 'Sub Admin');
$option_status = array('A' => 'Active', 'I' => 'Inactive');
$option_order = array(
    'Admin.firstname ASC, Admin.lastname ASC, Admin.created DESC' => 'Restaurant Name Ascending',
    'Admin.firstname DESC, Admin.lastname DESC, Admin.created DESC' => 'Restaurant Name Descending',
    'Admin.email ASC, Admin.created DESC' => 'Email Ascending',
    'Admin.email DESC, Admin.created DESC' => 'Email Descending',
    'Admin.status ASC, Admin.created DESC' => 'Status Ascending',
    'Admin.status DESC, Admin.created DESC' => 'Status Descending',
    'Admin.created ASC' => 'Created On Ascending',
    'Admin.created DESC' => 'Created On Descending'
);

$search_txt = $status = '';
if($this->Session->check('admin_search')){
    $search = $this->Session->read('admin_search');
    $search_txt = $search['search'];
    $status = $search['status'];
}
?>

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
                <section id="page-title">
                    <div class="row">
                        <div class="col-sm-12 col-md-12">
                            <h1 class="mainTitle pull-left">Restaurant Owners</h1>
                            <div class="row pull-right">
                                <?php
                                
                                    /*echo $this->Html->link('Add Restaurant Owner <i class="fa fa-plus"></i>',
                                        array('plugin' => false, 'controller' => 'admins', 'action' => 'add_edit', 'admin' => true),
                                        array('class' => 'btn btn-green', 'escape' => false)
                                    );*/
                                 ?>
                            </div>
                        </div>                        
                    </div>
                </section>
                <?php echo $this->Session->flash(); ?>

                <div class="container-fluid container-fullw bg-white">

                    <!-- start: SEARCH FORM START -->
                    <div class="border-around margin-bottom-15 padding-10">
                        <?php echo $this->Form->create('Admin', array(
                            'url' => array('controller' => 'admins', 'action' => 'users', 'admin' => true), 'class' => 'form', 'role' => 'search', 'autocomplete' => 'off')
                        ); ?>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Search</label>
                                <?php echo $this->Form->input('search', array('type' => 'text', 'value' => $search_txt, 'placeholder' => 'Search...', 'class' =>'form-control reset-field', 'div' => false, 'label' => false, 'required' => false)); ?>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Status</label>
                                <?php echo $this->Form->input('status', array('options' => $option_status, 'value' => $status, 'class' => 'form-control reset-field', 'empty' => 'All', 'label' => false, 'div' => false, 'required' => false)); ?>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Order By</label>
                                <?php echo $this->Form->input('order_by', array('options' => $option_order, 'value' => $order, 'id' => 'order_by', 'class' => 'form-control', 'empty' => false, 'label' => false, 'div' => false)); ?>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <?php echo $this->Form->button('Reset <i class="fa fa-times-circle"></i>',array('class' => 'btn btn-primary btn-wide pull-right','type' => 'button','id' => 'reset_button'));
                            echo $this->Form->button('Search <i class="fa fa-arrow-circle-right"></i>',array('class' => 'btn btn-primary btn-wide pull-right margin-right-10','type' => 'submit','id' => 'submit_button')) ?>
                        </div>


                        <?php echo $this->Form->end(); ?>
                        <div class="clearfix"></div>
                    </div>

                    <?php echo $this->Form->create('PageSize', array(
                            'url' => array('controller' => 'admins', 'action' => 'users', 'admin' => true), 'class' => 'form', 'autocomplete' => 'off', 'id' => 'pageSizeForm')
                    ); ?>
                        <div class="form-group pull-left">
                            <label class="control-label">Records Per Page</label>
                            <?php echo $this->Form->input('records_per_page', array('options' => unserialize(PAGING_OPTIONS), 'value' => $limit, 'id' => 'records_per_page', 'class' => 'form-control', 'empty' => false, 'label' => false, 'div' => false)); ?>
                        </div>
                    <?php echo $this->Form->end(); ?>

                    <div class="row">
                        <div class="col-md-12">                           
                            <table class="table table-striped table-bordered table-hover table-full-width">
                                <thead>
                                    <tr>
                                        <th>Restaurant Name</th>
                                        <th>Email</th>
                                        <th>Status</th>
                                        <th>Created On</th>
                                        <th class="hidden-xs">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($user_list)) { ?>
                                        <?php foreach ($user_list as $user) { ?>
                                            <tr>
                                                <td><?php echo ucfirst($user['Admin']['restaurant_name']); ?></td>
                                                <td><?php echo $user['Admin']['email']; ?></td>
                                                <td> <?php
                                                    if ($user['Admin']['status'] == 'A') {
                                                        echo $this->Html->image('/img/test-pass-icon.png', array('border' => 0, 'alt' => 'Active', 'title' => 'Active'));
                                                    } else {
                                                        echo $this->Html->image('/img/cross.png', array('border' => 0, 'alt' => 'Inactive', 'title' => 'Inactive'));
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php echo date(DATETIME_FORMAT, strtotime($user['Admin']['created'])); ?>
                                                </td>
                                                <td>
                                                    <div class="visible-md visible-lg hidden-sm hidden-xs">
                                                        <?php
                                                        if($this->Common->checkAccess($privilage_data, 'Admin', 'can_edit')) {

                                                            if ($user['Admin']['status'] == 'A') {
                                                                echo $this->Html->link('<i class="fa fa-check-circle"></i>', array('controller' => 'admins', 'action' => 'status', base64_encode($user['Admin']['id']), 'I', 'admin' => true), array('title' => 'Click here to inactive', 'escape' => false, 'class' => 'btn btn-transparent btn-xs'));
                                                            } else {
                                                                echo $this->Html->link('<i class="fa fa-times-circle"></i>', array('controller' => 'admins', 'action' => 'status', base64_encode($user['Admin']['id']), 'A', 'admin' => true), array('title' => 'Click here to active', 'escape' => false, 'class' => 'btn btn-transparent btn-xs'));
                                                            }

                                                            echo $this->Html->link('<i class="fa fa-pencil"></i>',
                                                                array('plugin' => false, 'controller' => 'admins', 'action' => 'add_edit', base64_encode($user['Admin']['id']), 'admin' => true),
                                                                array('class' => 'btn btn-transparent btn-xs', 'title' => 'Click here to edit admin user details', 'escape' => false)
                                                            );
                                                            
                                                            echo $this->Html->link('<i class="fa fa-random"></i>',
                                                                array('plugin' => false, 'controller' => 'admins', 'action' => 'reorder_table', base64_encode($user['Admin']['id']), 'admin' => true),
                                                                array('class' => 'btn btn-transparent btn-xs tooltips', 'title' => 'Click here to reorder tables', 'escape' => false)
                                                            );

                                                            echo $this->Html->link('<i class="fa fa-key"></i>',
                                                                array('plugin' => false, 'controller' => 'admins', 'action' => 'change_password', base64_encode($user['Admin']['id']), 'admin' => true),
                                                                array('class' => 'btn btn-transparent btn-xs tooltips', 'title' => 'Click here to change password', 'escape' => false)
                                                            );
                                                        }
                                                        ?>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                            if('all' != $limit){ ?>
                                                <tr>
                                                    <td colspan="5">
                                                        <?php echo $this->element('pagination'); ?>
                                                    </td>
                                                </tr>
                                        <?php }
                                        } else {
                                        ?>
                                        <tr>
                                            <td colspan="5">No admin user here.</td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
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