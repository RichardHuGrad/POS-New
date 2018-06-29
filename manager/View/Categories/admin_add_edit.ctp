<?php
$option_status = array('A' => 'Active', 'I' => 'Inactive');
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
                            <h1 class="mainTitle"><?php echo ('' == $id) ? 'Add' : 'Edit'; ?> Category</h1>
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
                            <?php echo $this->Form->create('Category', array('method' => 'post', 'class' => 'form', 'role' => 'form', 'autocomplete' => 'off', 'type' => 'file'));
                            echo $this->Form->input('id', array('type' => 'hidden', 'required' => false)); ?>
                            <div class="row">

                                <?php foreach ($languages as $key => $language){ ?>
								<?php $readonly = ''; if (($key  == "zh") && ($remote_id)) $readonly = 1; ?>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">Category (<?php echo $language; ?>)<span class="symbol required"></span></label>
                                            <?php
                                            if ($readonly) {
                                            	echo $this->Form->input('CategoryLocale.' . $key . '.name', array('type' => 'text', 'readonly' => 'readonly', 'maxlength' => '200', 'class' =>'form-control validate[required]', 'div' => false, 'label' => false, 'required' => false));
                                            } else {
                                            	echo $this->Form->input('CategoryLocale.' . $key . '.name', array('type' => 'text', 'maxlength' => '200', 'class' =>'form-control validate[required]', 'div' => false, 'label' => false, 'required' => false));
                                            }
                                            echo $this->Form->input('CategoryLocale.' . $key . '.id', array('type' => 'hidden', 'required' => false));
                                            ?>
                                        </div>
                                    </div>

                                <?php } ?>

                                
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Receipt Printer</label>
                                        <?php 

                                        $printer = array(
                                            'K'=>'Kitchen',
                                            'C'=>'Cashier'
                                            );
                                        echo $this->Form->input('printer', array('options' => $printer, 'class' => 'form-control', 'empty' => "Select Printer", 'label' => false, 'div' => false)); ?>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Status</label>
                                        <?php echo $this->Form->input('status', array('options' => $option_status, 'class' => 'form-control', 'empty' => false, 'label' => false, 'div' => false)); ?>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Server ID</label>
                                        <?php echo $this->Form->input('remote_id', array('type' =>'text', 'class' => 'form-control', 'label' => false, 'div' => false, 'required' => false)); ?>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Order</label>
                                        <?php echo $this->Form->input('orderby', array('type' =>'text', 'class' => 'form-control', 'label' => false, 'div' => false, 'required' => false)); ?>
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
                                        array('plugin' => false,'controller' => 'categories','action' => 'index', 'admin' => true),
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

<?php
echo $this->Html->css(array('validationEngine.jquery'));
echo $this->Html->script(array('jquery.validationEngine-en', 'jquery.validationEngine'));
?>
<script type="text/javascript">
    $(document).ready(function () {
        $("#CategoryAdminAddEditForm").validationEngine();
    });
</script>