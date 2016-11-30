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
            jQuery('#order_by').val('Promocode.created DESC');
        });

        jQuery('#records_per_page').change(function(){
            jQuery('#pageSizeForm').submit();
        });
    });
</script>

<?php $option_yes_no = array('Y' => 'Yes', 'N' => 'No');
$option_status = array('1' => 'Active', '0' => 'Inactive');

$search_txt = $status = $is_verified = $registered_from = $registered_till = '';
if($this->Session->check('cashier_search')){
    $search = $this->Session->read('cashier_search');
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
                            <h1 class="mainTitle pull-left">Promocodes List</h1>
                            <div class="row pull-right">
                                <?php
                                    echo $this->Html->link('Add Promocode <i class="fa fa-plus"></i>',
                                        array('plugin' => false, 'controller' => 'promocodes', 'action' => 'add_edit', 'admin' => true),
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
                        <?php echo $this->Form->create('Promocode', array(
                            'url' => array('controller' => 'promocodes', 'action' => 'index', 'admin' => true), 'class' => 'form', 'role' => 'search', 'autocomplete' => 'off')
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

                       <!--  <div class="col-md-4">
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
                        </div> -->


                        <div class="col-md-12">
                            <?php echo $this->Form->button('Reset <i class="fa fa-times-circle"></i>',array('class' => 'btn btn-primary btn-wide pull-right','type' => 'button','id' => 'reset_button'));
                            echo $this->Form->button('Search <i class="fa fa-arrow-circle-right"></i>',array('class' => 'btn btn-primary btn-wide pull-right margin-right-10','type' => 'submit','id' => 'submit_button')) ?>
                        </div>


                        <?php echo $this->Form->end(); ?>
                        <div class="clearfix"></div>
                    </div>

                    <?php echo $this->Form->create('PageSize', array(
                            'url' => array('controller' => 'promocodes', 'action' => 'index', 'admin' => true), 'class' => 'form', 'autocomplete' => 'off', 'id' => 'pageSizeForm')
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
                                        <th>Promocode</th>
                                        <th>Valid From</th>
                                        <th>Valid To</th>
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
                                                <td><?php echo ($customer['Promocode']['code']); ?></td>
                                                <td><?php echo $customer['Promocode']['valid_from']; ?></td>
                                                <td><?php echo $customer['Promocode']['valid_to']; ?></td>
                                                <td> <?php
                                                    if ($customer['Promocode']['status']) {
                                                        echo $this->Html->image('/img/test-pass-icon.png', array('border' => 0, 'alt' => 'Active', 'title' => 'Active'));
                                                    } else {
                                                        echo $this->Html->image('/img/cross.png', array('border' => 0, 'alt' => 'Inactive', 'title' => 'Inactive'));
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php echo date(DATETIME_FORMAT, ($customer['Promocode']['created'])); ?>
                                                </td>
                                                <td><div class="visible-md visible-lg hidden-sm hidden-xs">
                                                        <?php
                                                            if ($customer['Promocode']['status']) {
                                                                echo $this->Html->link('<i class="fa fa-check-circle"></i>', array('controller' => 'promocodes', 'action' => 'status', base64_encode($customer['Promocode']['id']), '0', 'admin' => true), array('title' => 'Click here to inactive', 'escape' => false, 'class' => 'btn btn-transparent btn-xs'));
                                                            } else {
                                                                echo $this->Html->link('<i class="fa fa-times-circle"></i>', array('controller' => 'promocodes', 'action' => 'status', base64_encode($customer['Promocode']['id']), '1', 'admin' => true), array('title' => 'Click here to active', 'escape' => false, 'class' => 'btn btn-transparent btn-xs'));
                                                            }

                                                            echo $this->Html->link('<i class="fa fa-pencil"></i>',
                                                                array('plugin' => false, 'controller' => 'promocodes', 'action' => 'add_edit', base64_encode($customer['Promocode']['id']), 'admin' => true),
                                                                array('class' => 'btn btn-transparent btn-xs', 'title' => 'Click here to edit', 'escape' => false)
                                                            );

                                                            echo $this->Html->link('<i class="fa fa-trash"></i>',
                                                                array('plugin' => false, 'controller' => 'promocodes', 'action' => 'delete', base64_encode($customer['Promocode']['id']), 'admin' => true),
                                                                array('class' => 'btn btn-transparent btn-xs', 'title' => 'Click here to delete', 'escape' => false, "onclick"=>"return confirm('Are you sure you want to delete?')")
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
                                            <td colspan="8">No Promocodes here.</td>
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