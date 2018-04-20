<?php
$option_status = array('A' => 'Active', 'I' => 'Inactive');
$option_extrascate = array();
foreach ($Extrascategory_data as $categories){
	$option_extrascate[$categories['Extrascategory']['id']] = $categories['Extrascategory']['name'] . '(' . $categories['Extrascategory']['name_zh'] . ')';
};
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
                            <h1 class="mainTitle"><?php echo ('' == $id) ? 'Add' : 'Edit'; ?> Extra</h1>
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
                            <?php echo $this->Form->create('Extra', array('method' => 'post', 'class' => 'form', 'role' => 'form', 'autocomplete' => 'off', 'type' => 'file'));
                            echo $this->Form->input('id', array('type' => 'hidden', 'required' => false)); ?>
                            <div class="row">
                                <?php
                                    echo $this->Form->input('cousine_id', array('type' => 'hidden', 'value'=>$cousine_id, 'required' => false));
                                ?>
                             <!--
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?php echo $this->Form->input('type', array('options' => array('T' => 'Topping', 'E' => 'Extra'), 'type' => 'select', 'label' => 'Type <span class="symbol required"></span>', 'div' => false, 'required' => true, 'class' => 'form-control validate[required]', 'empty' => 'Select')); ?>
                                    </div>
                                </div>
                             -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Extra Name (EN)<span class="symbol required"></span></label>
                                        <?php echo $this->Form->input('name', array('type' => 'text', 'maxlength' => '200', 'class' =>'form-control', 'div' => false, 'label' => false, 'required' => true)); ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Extra Name (ZH)<span class="symbol required"></span></label>
                                        <?php 
                                        if ($remote_id) {
                                        	echo $this->Form->input('name_zh', array('type' => 'text', 'readonly' => 'readonly', 'maxlength' => '200', 'class' =>'form-control', 'div' => false, 'label' => false, 'required' => true));
                                        } else {
                                        	echo $this->Form->input('name_zh', array('type' => 'text', 'maxlength' => '200', 'class' =>'form-control', 'div' => false, 'label' => false, 'required' => true));
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Price <span class="symbol required"></span></label>
                                        <?php 
                                        if ($remote_id) {
                                        	echo $this->Form->input('price', array('type' => 'text', 'maxlength' => '5', 'readonly' => 'readonly', 'class' =>'form-control', 'div' => false, 'label' => false, 'required' => true)); 
                                        } else {
                                        	echo $this->Form->input('price', array('type' => 'text', 'maxlength' => '5', 'class' =>'form-control', 'div' => false, 'label' => false, 'required' => true));
                                        }
                                        ?>
                                    </div>
                                </div>

<!-- Modified by Yishou Liao @ Dec 04 2016 -->
								<div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Extras Category</label>
                                        <?php 
                                        if ($remote_id) {
                                        	echo $this->Form->input('category_id', array('type' => 'text', 'readonly' => 'readonly', 'class' => 'form-control', 'div' => false, 'label' => false, 'required' => false));
                                        } else {
                                        	echo $this->Form->input('category_id', array('options' => $option_extrascate, 'class' => 'form-control', 'empty' => false, 'label' => false, 'div' => false));
                                        }
                                        ?>
                                    </div>
                                </div>
<!-- End -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Status</label>
                                        <?php echo $this->Form->input('status', array('options' => $option_status, 'class' => 'form-control', 'empty' => false, 'label' => false, 'div' => false)); ?>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Server ID</label>
                                        <?php
                                        echo $this->Form->input('remote_id', array('type' => 'text', 'default' => 0, 'maxlength' => '200', 'class' =>'form-control', 'empty' => false, 'div' => false, 'label' => false, 'required' => false));
                                        ?>
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
                                        array('plugin' => false,'controller' => 'extras','action' => 'index', 'admin' => true, "?"=>array("id"=>$cousine_id)),
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