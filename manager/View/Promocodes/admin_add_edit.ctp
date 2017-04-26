
<?php echo $this->Html->css(array('timepicki'), null, array('inline' => false)); ?>
<?php echo $this->Html->script(array('timepicki'), array('inline' => false)); ?>

<?php echo $this->Html->css(array('bootstrap-datetimepicker'));
echo $this->Html->script(array('bootstrap-datepicker')); 

echo $this->Html->css(array('validationEngine.jquery'));
echo $this->Html->script(array('jquery.validationEngine-en', 'jquery.validationEngine'));
?>


<script type="text/javascript">
    jQuery(document).ready(function () {
        $("#PromocodeAdminAddEditForm").validationEngine();
        jQuery('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            startDate: '1y'
        });

        jQuery('#PromocodeStartTime').timepicki({increase_direction:'up'});
        jQuery('#PromocodeEndTime').timepicki({increase_direction:'up'});
    });
    $(document).ready(function() {
        $("input[type=checkbox]").addClass("validate[minCheckbox[1]] checkbox");

        $("#PromocodeCategoryId").on("change", function() {
            $("#PromocodeItemId").attr('disabled', 'disabled');
            $.ajax({
                type: 'POST',
                url: '<?php echo $this->Html->url(array('plugin' => false, 'controller' => 'promocodes', 'action' => 'get_item')); ?>',
                data: {categoryid: $("#PromocodeCategoryId").val(), restaurant_id: $("#PromocodeRestaurantId").val()},
                success: function(data) {
                    $("#cancel_button").removeAttr('disabled');
                    $("#submit_button").removeAttr('disabled');
                    $("#PromocodeItemId").removeAttr('disabled');
                    $("#PromocodeItemId").html(data);
                }
            })
        });
    });
</script>

<?php $option_yes_no = array('Y' => 'Yes', 'N' => 'No');
$option_status = array('1' => 'Active', '0' => 'Inactive');
?>
<style>
.radio, .checkbox {
    margin-left: 22px;
}
</style>
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
                            <h1 class="mainTitle"><?php echo ('' == $id) ? 'Add' : 'Edit'; ?> Promocode</h1>
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
                            <?php echo $this->Form->create('Promocode', array('method' => 'post', 'class' => 'form', 'role' => 'form', 'autocomplete' => 'off', 'type' => 'file'));
                            echo $this->Form->input('id', array('type' => 'hidden', 'required' => false)); ?>
                            <div class="row">
                                <?php

                                $is_super_admin = $this->Session->read('Admin.is_super_admin');
                                if('Y' == $is_super_admin){
                                ?>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Select Restaurant <span class="symbol required"></span></label>
                                        <?php echo $this->Form->input('restaurant_id', array('options' => $restaurants, 'class' => 'form-control validate[required]', 'empty' => 'Select Restaurant', 'label' => false, 'div' => false, 'required' => true)); ?>
                                    </div>
                                </div>
                                <?php } else {
                                    echo $this->Form->input('restaurant_id', array('type' => 'hidden', 'value'=>$this->Session->read('Admin.id'), 'required' => false));
                                }?>
                                
                             <!--
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?php echo $this->Form->input('category_id', array('type' => 'select', 'options' => $categories, 'empty' => 'Select', 'label' => 'Select Category', 'div' => false, 'required' => false, 'class' => 'form-control')); ?>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?php echo $this->Form->input('item_id', array('type' => 'select', 'options' => @$items_list, 'empty' => 'Select', 'label' => 'Select Menu', 'div' => false, 'required' => false, 'class' => 'form-control')); ?>
                                    </div>
                                </div>  
                             -->

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?php echo $this->Form->input('code', array( 'type' => 'text', 'maxlength' => '255', 'label' => 'Promo code <span class="symbol required"></span>', 'div' => false, 'required' => true, 'class' => 'form-control validate[required]')); ?>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?php echo $this->Form->input('discount_type', array('options' => array(0 => 'Fixed', 1 => 'Percentage'), 'type' => 'select', 'maxlength' => '255', 'label' => 'Discount Type <span class="symbol required"></span>', 'div' => false, 'required' => true, 'class' => 'form-control validate[required]', 'empty' => 'Select')); ?>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?php echo $this->Form->input('discount_value', array('type' => 'text', 'maxlength' => '25', 'label' => 'Discount Value <span class="symbol required"></span>', 'div' => false, 'required' => true, 'class' => 'form-control validate[required]')); ?>
                                    </div> 
                                </div>

                                
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">Active Days <span class="symbol required"></span></label>
                                        <?php 
                                        $options = array(
                                                'monday'=>'Monday',
                                                'tuesday'=>'Tuesday',
                                                'wednesday'=>'Wednesday',
                                                'thursday'=>'Thursday',
                                                'friday'=>'Friday',
                                                'saturday'=>'Saturday',
                                                'sunday'=>'Sunday',
                                            );
                                        echo $this->Form->input('week_days', array( 
                                            'multiple' => 'checkbox',
                                            // 'separator'=> '</div><div class="checkbox">',
                                            // 'before' => '<div class="checkbox">',
                                            // 'after' => '</div>',
                                            'div' => false, 
                                            'options' =>  $options,
                                            'label' => false,
                                            "legend" => false,
                                             
                                           ), array('class'=>'validate[minCheckbox[1]] checkbox')
                                        );   
                                        ?>
                                    </div>
                                </div> 
                                     <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">Start Time <span class="symbol required"></span></label>
                                            <?php echo $this->Form->input('start_time', array('type' => 'text', 'class' => 'form-control validate[required]', 'div' => false, 'label' => false, 'required' => true, 'required' => true)); ?>
                                        </div>
                                    </div>   
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">End Time <span class="symbol required"></span></label>
                                            <?php echo $this->Form->input('end_time', array('type' => 'text', 'class' => 'form-control validate[required]', 'div' => false, 'label' => false, 'required' => true, 'required' => true)); ?>
                                        </div>
                                    </div> 
                                
                                

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?php echo $this->Form->input('valid_from', array('type' => 'text', 'maxlength' => '255', 'label' => 'Valid From <span class="symbol required"></span>', 'div' => false, 'required' => true, 'class' => 'form-control validate[required] datepicker')); ?>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                     <div class="form-group">
                                        <?php echo $this->Form->input('valid_to', array('type' => 'text', 'maxlength' => '255', 'label' => 'Valid To <span class="symbol required"></span>', 'div' => false, 'required' => true, 'class' => 'form-control validate[required] datepicker')); ?>
                                    </div>
                                </div>

                                
                                <!-- <div class="col-md-6">
                                    <div class="form-group">
                                        <?php echo $this->Form->input('is_multiple', array('type' => 'checkbox', 'label' => '  Use Multiple', 'div' => false, 'required' => false, 'class' => '')); ?>
                                    </div>
                                </div> -->


                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Status</label>
                                        <?php echo $this->Form->input('status', array('options' => $option_status, 'required' => true, 'class' => 'form-control', 'empty' => false, 'label' => false, 'div' => false)); ?>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">&nbsp;</label>
                                        &nbsp;                                        
                                    </div>                                                                              
                                </div>   

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
                                        array('plugin' => false,'controller' => 'promocodes','action' => 'index', 'admin' => true),
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
