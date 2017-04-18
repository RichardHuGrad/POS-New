<?php echo $this->Html->css(array('sweet-alert.css', 'ie9.css', 'toastr.min.css', 'select2.min.css', 'DT_bootstrap.css', 'bootstrap-datetimepicker'), null, array('inline' => false));
echo $this->Html->script(array('select2.min.js', 'jquery.dataTables.min.js', 'table-data.js', 'sweet-alert.min.js', 'ui-notifications.js', 'bootstrap-datepicker'), array('inline' => false)); ?>

<script type="text/javascript">
    jQuery(document).ready(function () {
        UINotifications.init();
        TableData.init();

        jQuery('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            endDate: '0d'
        });

        jQuery('#reset_button').click(function(){
            jQuery('.reset-field').val('');
            jQuery('#order_by').val('Cook.created DESC');
        });

        jQuery('#records_per_page').change(function(){
            jQuery('#pageSizeForm').submit();
        });
    });
</script>

<?php $option_yes_no = array('Y' => 'Yes', 'N' => 'No');
$option_status = array('A' => 'Active', 'I' => 'Inactive');
$option_order = array(
    'Cook.firstname ASC, Cook.lastname ASC, Cook.created DESC' => 'Cook Name Ascending',
    'Cook.firstname DESC, Cook.lastname DESC, Cook.created DESC' => 'Cook Name Descending',
    'Cook.email ASC, Cook.created DESC' => 'Email Ascending',
    'Cook.email DESC, Cook.created DESC' => 'Email Descending',
    'Cook.status ASC, Cook.created DESC' => 'Status Ascending',
    'Cook.status DESC, Cook.created DESC' => 'Status Descending',
    'Cook.created ASC' => 'Registered On Ascending',
    'Cook.created DESC' => 'Registered On Descending',
    'Cook.userid ASC' => 'UserID On Ascending',
    'Cook.userid DESC' => 'UserID On Descending',
);

$search_txt = $status = $is_verified = $registered_from = $registered_till = '';
if($this->Session->check('cashier_search')){
    $search = $this->Session->read('cashier_search');
    $search_txt = $search['search'];
    $status = $search['status'];
    // $is_verified = $search['is_verified'];
    $registered_from = $search['registered_from'];
    $registered_till = $search['registered_till'];
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
                            <h1 class="mainTitle pull-left">Cooks List</h1>
                            <div class="row pull-right">
                                <?php
                                    echo $this->Html->link('Add Cook <i class="fa fa-plus"></i>',
                                        array('plugin' => false, 'controller' => 'cooks', 'action' => 'add_edit', 'admin' => true),
                                        array('class' => 'btn btn-green', 'escape' => false)
                                    );
                                 ?>
                            </div>
                        </div>                        
                    </div>
                </section>
                <?php echo $this->Session->flash(); ?>

                <div class="container-fluid container-fullw bg-white">

                    <!-- start: SEARCH FORM START -->
                    <div class="border-around margin-bottom-15 padding-10">
                        <?php echo $this->Form->create('Cook', array(
                            'url' => array('controller' => 'cooks', 'action' => 'index', 'admin' => true), 'class' => 'form', 'role' => 'search', 'autocomplete' => 'off')
                        ); ?>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Search</label>
                                <?php echo $this->Form->input('search', array('type' => 'text', 'value' => $search_txt, 'placeholder' => 'Search...', 'class' =>'form-control reset-field', 'div' => false, 'label' => false, 'required' => false)); ?>
                            </div>
                        </div>

                        <!-- <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Verified</label>
                                <?php echo $this->Form->input('is_verified', array('options' => $option_yes_no, 'value' => $is_verified, 'class' => 'form-control reset-field', 'empty' => 'All', 'label' => false, 'div' => false)); ?>
                            </div>
                        </div> -->

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Status</label>
                                <?php echo $this->Form->input('status', array('options' => $option_status, 'value' => $status, 'class' => 'form-control reset-field', 'empty' => 'All', 'label' => false, 'div' => false)); ?>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Registered From</label>
                                <?php echo $this->Form->input('registered_from', array('value' => $registered_from, 'type' => 'text', 'class' =>'form-control datepicker reset-field', 'div' => false, 'label' => false, 'required' => false)); ?>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Registered To</label>
                                <?php echo $this->Form->input('registered_till', array('value' => $registered_till, 'type' => 'text', 'class' =>'form-control datepicker reset-field', 'div' => false, 'label' => false, 'required' => false)); ?>
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
                            'url' => array('controller' => 'cooks', 'action' => 'index', 'admin' => true), 'class' => 'form', 'autocomplete' => 'off', 'id' => 'pageSizeForm')
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
                                        <th>Restaurant</th>
                                        <th>UserID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Mobile</th>
                                        <!-- <th>Is Verified</th> -->
                                        <th>Status</th>
                                        <th>Registered On</th>
                                        <th class="hidden-xs">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($customer_list)) { ?>
                                        <?php foreach ($customer_list as $customer) { ?>
                                            <tr>
                                                <td><?php echo ucfirst($customer['Admin']['restaurant_name']); ?></td>
                                                <td><?php echo $customer['Cook']['userid']; ?></td>
                                                <td><?php echo ucfirst($customer['Cook']['firstname']) . ' ' . ucfirst($customer['Cook']['lastname']); ?></td>
                                                <td><?php echo $customer['Cook']['email']; ?></td>
                                                <td><?php echo $customer['Cook']['mobile_no']; ?></td>
                                                <!-- <td> <?php
                                                    if ($customer['Cook']['is_verified'] == 'Y') {
                                                        echo $this->Html->image('/img/test-pass-icon.png', array('border' => 0, 'alt' => 'Yes', 'title' => 'Yes'));
                                                    } else {
                                                        echo $this->Html->image('/img/cross.png', array('border' => 0, 'alt' => 'No', 'title' => 'No'));
                                                    }
                                                    ?>
                                                </td> -->
                                                <td> <?php
                                                    if ($customer['Cook']['status'] == 'A') {
                                                        echo $this->Html->image('/img/test-pass-icon.png', array('border' => 0, 'alt' => 'Active', 'title' => 'Active'));
                                                    } else {
                                                        echo $this->Html->image('/img/cross.png', array('border' => 0, 'alt' => 'Inactive', 'title' => 'Inactive'));
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php echo date(DATETIME_FORMAT, strtotime($customer['Cook']['created'])); ?>
                                                </td>
                                                <td><div class="visible-md visible-lg hidden-sm hidden-xs">
                                                        <?php
                                                            if ($customer['Cook']['status'] == 'A') {
                                                                echo $this->Html->link('<i class="fa fa-check-circle"></i>', array('controller' => 'cooks', 'action' => 'status', base64_encode($customer['Cook']['id']), 'I', 'admin' => true), array('title' => 'Click here to inactive', 'escape' => false, 'class' => 'btn btn-transparent btn-xs'));
                                                            } else {
                                                                echo $this->Html->link('<i class="fa fa-times-circle"></i>', array('controller' => 'cooks', 'action' => 'status', base64_encode($customer['Cook']['id']), 'A', 'admin' => true), array('title' => 'Click here to active', 'escape' => false, 'class' => 'btn btn-transparent btn-xs'));
                                                            }

                                                            echo $this->Html->link('<i class="fa fa-pencil"></i>',
                                                                array('plugin' => false, 'controller' => 'cooks', 'action' => 'add_edit', base64_encode($customer['Cook']['id']), 'admin' => true),
                                                                array('class' => 'btn btn-transparent btn-xs', 'title' => 'Click here to edit customer details', 'escape' => false)
                                                            );

                                                            echo $this->Html->link('<i class="fa fa-key"></i>',
                                                                array('plugin' => false, 'controller' => 'cooks', 'action' => 'change_password', base64_encode($customer['Cook']['id']), 'admin' => true),
                                                                array('class' => 'btn btn-transparent btn-xs tooltips', 'title' => 'Click here to change password', 'escape' => false)
                                                            );
                                                            echo $this->Html->link('<i class="fa fa-trash"></i>',
                                                                array('plugin' => false, 'controller' => 'cooks', 'action' => 'delete',  base64_encode($customer['Cook']['id']), 'admin' => true),
                                                                array('class' => 'btn btn-transparent btn-xs', 'title' => 'Click here to delete cook', "onclick"=>"return confirm('Are you sure you want to delete this cook?')", 'escape' => false)
                                                            );
                                                        
                                                        ?>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                            if('all' != $limit){ ?>
                                                <tr>
                                                    <td colspan="8">
                                                        <?php echo $this->element('pagination'); ?>
                                                    </td>
                                                </tr>
                                        <?php }
                                        } else {
                                        ?>
                                        <tr>
                                            <td colspan="8">No Cooks here.</td>
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