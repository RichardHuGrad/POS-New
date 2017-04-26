<?php echo $this->Html->css(array('bootstrap-datetimepicker'));
echo $this->Html->script(array('bootstrap-datepicker')); ?>
<script type="text/javascript">
    jQuery(document).ready(function () {

        jQuery('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            endDate: '-18y'
        });

    });
</script>

<?php 
$option_position = array('K' => 'Kitchen', 'S' => 'Service');
$option_yes_no   = array('Y' => 'Yes', 'N' => 'No');
$option_status   = array('A' => 'Active', 'I' => 'Inactive');
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
                <!-- start: PAGE TITLE -->
                <section id="page-title">
                    <div class="row">
                        <div class="col-sm-8">
                            <h1 class="mainTitle"><?php echo ('' == $id) ? 'Add' : 'Edit'; ?> Employee</h1>
                        </div>                        
                    </div>
                </section>
                <!-- end: PAGE TITLE -->
                <!-- Global Messages -->
                <?php echo $this->Session->flash(); ?>
                <!-- Global Messages End -->
                <!-- start: FORM VALIDATION EXAMPLE 1 -->
                <div class="container-fluid container-fullw bg-white">
                    <div class="row">
                        <div class="col-md-12">   
                            <?php echo $this->Form->create('Cashier', array('method' => 'post', 'class' => 'form', 'role' => 'form', 'autocomplete' => 'off', 'type' => 'file'));
                            echo $this->Form->input('id', array('type' => 'hidden', 'required' => false)); ?>
                            <div class="row">
                                <?php

                                $is_super_admin = $this->Session->read('Admin.is_super_admin');
                                if('Y' == $is_super_admin){
                                ?>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Select Restaurant  <span class="symbol required"></span></label>
                                        <?php echo $this->Form->input('restaurant_id', array('options' => $restaurants, 'class' => 'form-control', 'empty' => 'Select Restaurant', 'label' => false, 'div' => false, 'required' => false)); ?>
                                    </div>
                                </div>
                                <?php } else {
                                    echo $this->Form->input('restaurant_id', array('type' => 'hidden', 'value'=>$this->Session->read('Admin.id'), 'required' => false));
                                }?>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">UserID <span class="symbol required"></span></label>
                                        <?php echo $this->Form->input('userid', array('type' => 'text', 'maxlength' => '3', 'class' =>'form-control', 'div' => false, 'label' => false, 'required' => true)); ?>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Select Position  <span class="symbol required"></span></label>
                                        <?php echo $this->Form->input('position', array('options' => $option_position, 'class' => 'form-control', 'empty' => 'Select position', 'label' => false, 'div' => false, 'required' => false)); ?>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">First Name <span class="symbol required"></span></label>
                                        <?php echo $this->Form->input('firstname', array('type' => 'text', 'maxlength' => '200', 'class' =>'form-control', 'div' => false, 'label' => false, 'required' => true)); ?>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Last Name <span class="symbol required"></span></label>
                                        <?php echo $this->Form->input('lastname', array('type' => 'text', 'maxlength' => '200', 'class' =>'form-control', 'div' => false, 'label' => false, 'required' => true)); ?>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Email <span class="symbol required"></span></label>
                                        <?php echo $this->Form->input('email', array('type' => 'text', 'maxlength' => '200', 'class' =>'form-control', 'div' => false, 'label' => false, 'required' => true)); ?>
                                    </div>
                                </div>
                                <?php if('' == $id){ ?>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">Password <span class="symbol required"></span></label>
                                            <?php echo $this->Form->input('password', array('type' => 'password', 'maxlength' => '50', 'class' =>'form-control', 'div' => false, 'label' => false, 'required' => true)); ?>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">Confirm Password <span class="symbol required"></span></label>
                                            <?php echo $this->Form->input('confirm_password', array('type' => 'password', 'maxlength' => '50', 'class' =>'form-control', 'div' => false, 'label' => false, 'required' => true)); ?>
                                        </div>
                                    </div>
                                <?php } ?>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Phone Number </label>
                                        <?php echo $this->Form->input('mobile_no', array('type' => 'text', 'maxlength' => '20', 'class' =>'form-control', 'div' => false, 'label' => false, 'required' => false)); ?>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Image</label>
                                        <?php echo $this->Form->input('image', array('type' => 'file', 'maxlength' => '200', 'class' => 'form-control', 'id' => 'image', 'div' => false, 'label' => false)); ?>
                                        <span id="beaconkey-error" class="help-block"></span>
                                    </div>                                                                              
                                </div>  

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">&nbsp;</label>
                                        &nbsp;                                        
                                    </div>                                                                              
                                </div>   
                                <?php
                                if($id) {
                                 ?>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">&nbsp;</label>
                                            <?php if ($this->request->data['Cashier']['image']) { ?>
                                                <?php echo $this->Html->image(CASHIER_IMAGE_PATH . $this->request->data['Cashier']['image'], array('border' => 0, 'width' => 100)); ?>
                                            <?php } else { ?>
                                                <?php echo $this->Html->image('/img/no_image.jpg', array('border' => 0, 'width' => 100)); ?>
                                            <?php } ?>
                                        </div> 
                                    </div>
                                <?php }?>

                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div>
                                        <span class="symbol required"></span>Required Fields
                                        <hr>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-7">                                        
                                </div>
                                <div class="col-md-5">
                                    <?php echo $this->Form->button('Save <i class="fa fa-arrow-circle-right"></i>',array('class' => 'btn btn-primary btn-wide pull-left_form','type' => 'submit','id' => 'submit_button'));

                                    echo $this->Html->link('Cancel <i class="fa fa-times-circle"></i>',
                                        array('plugin' => false,'controller' => 'cashiers','action' => 'index', 'admin' => true),
                                        array('class' => 'btn btn-primary btn-wide pull-right', 'escape' => false)
                                    );
                                    ?>

                                </div>
                            </div>
                            <?php echo $this->Form->end(); ?>
                        </div>
                    </div>
                </div>
                <!-- end: FORM VALIDATION EXAMPLE 1 -->
            </div>
        </div>
    </div>
    <!-- start: FOOTER -->
   <?php echo $this->element('footer'); ?>
    <!-- end: FOOTER -->
</div>