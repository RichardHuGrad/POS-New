<?php
echo $this->Html->css(array('validationEngine.jquery'));
echo $this->Html->script(array('jquery.validationEngine-en', 'jquery.validationEngine'));
?>
<script >
    $(document).ready(function () {
     
        $("#UserAddForm").validationEngine();
    });
</script>		
<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery("#cancel_button").click(function () {
            window.location.href = '<?php echo $this->Html->url(array('plugin' => false, 'controller' => 'users', 'action' => 'index')); ?>';
        });
    });
</script>


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
                            <h1 class="mainTitle">Add User</h1>                            
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
                            <?php echo $this->Form->create('User', array('method' => 'post', 'class' => 'form', 'role' => 'form', 'enctype' => 'multipart/form-data')); ?>     
								
                            <br/>
                         <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Name <span class="symbol required"></span></label> 
                                        <?php echo $this->Form->input('name', array('type' => 'text', 'maxlength' => '100', 'class' => 'form-control  validate[required,maxSize[30]]', 'id' => 'place', 'div' => false, 'label' => false,)); ?>
                                        <span id="place-error" class="help-block"></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Email <span class="symbol required"></span></label> 
                                        <?php echo $this->Form->input('email', array('type' => 'text',  'class' => 'form-control validate[required,custom[email]]', 'label' => false, 'div' => false, 'value'=>$data['Location']['email'])); ?>
                                        <span id="place-error" class="help-block"></span>
                                    </div>
                                </div>
							</div>
							<div class="row">
                               <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Password <span class="symbol required"></span></label> 
                                        <?php echo $this->Form->input('password', array('type' => 'password',  'class' => 'form-control validate[required]', 'label' => false, 'div' => false)); ?>
                                        <span id="place-error" class="help-block"></span>
                                    </div>
                                </div>
								
								<div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Confirm Password <span class="symbol required"></span></label> 
                                        <?php echo $this->Form->input('confirm_password', array('type' => 'password validate[required,equals[password]]', 'class' => 'form-control',  'label' => false, 'div' => false)); ?>
                                        <span id="place-error" class="help-block"></span>
                                    </div>
                                </div>
							</div>	
								
							<div class="row">
								<div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Dob<span class="symbol required"></span></label>(YYYY-MM-DD)
                                         <?php echo $this->Form->input('dob', array('type' => 'text',  'class' => 'form-control validate[required,custom[date]]',  'label' => false, 'div' => false)); ?>
                                        <span id="beaconkey-error" class="help-block"></span>
                                    </div>
                                </div>
								
								<div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Gender<span class="symbol required"></span></label></br>
                                         <?php //$gender=$employee['EmployeePersonal']['gender'];
												$options2= array('M' => 'Male','F' => 'Female');
												$attributes2 = array('legend' => false,//'value' => $gender,
												'class' => 'validate[required]','required' => true,);
												echo $this->Form->radio('gender', $options2, $attributes2);
										 ?>
                                        <span id="beaconkey-error" class="help-block"></span>
                                    </div>
                                  </div>
								</div>
								<div class="row">
								   <div class="col-md-6">
                                    <div class="form-group">
							            <label class="control-label">Dietary Preferences<span class="symbol required"></span></label></br>
                                        <?php echo $this->Form->input('gluten_free', array('type' => 'checkbox', 'value' => 'Y', 'hiddenField' => 'N', 'div' => false, 'label' => false)); ?> <label>Gluten-free</label> &nbsp; &nbsp; &nbsp;
										
										<?php echo $this->Form->input('vegetarian', array('type' => 'checkbox', 'value' => 'Y', 'hiddenField' => 'N', 'div' => false, 'label' => false));?> <label>Vegetarian</label> &nbsp; &nbsp; &nbsp;
										
										<?php echo $this->Form->input('vegan', array('type' => 'checkbox', 'value' => 'Y', 'hiddenField' => 'N', 'div' => false, 'label' => false)); ?> <label>Vegan</label> &nbsp; &nbsp; &nbsp;
										
										<?php echo $this->Form->input('dairy_free', array('type' => 'checkbox', 'value' => 'Y', 'hiddenField' => 'N', 'div' => false, 'label' => false)); ?> <label>Dairy-free</label> &nbsp; &nbsp; &nbsp; </br>
										
										<?php echo $this->Form->input('kosher', array('type' => 'checkbox', 'value' => 'Y', 'hiddenField' => 'N', 'div' => false, 'label' => false)); ?> <label>Kosher</label>  &nbsp; &nbsp; &nbsp;
										
										<?php echo $this->Form->input('halal', array('type' => 'checkbox', 'value' => 'Y', 'hiddenField' => 'N', 'div' => false, 'label' => false)); ?> <label>Halal</label>  &nbsp; &nbsp; &nbsp;
										
										<?php echo $this->Form->input('organic', array('type' => 'checkbox', 'value' => 'Y', 'hiddenField' => 'N', 'div' => false, 'label' => false)); ?> <label>Organic</label>  &nbsp; &nbsp; &nbsp;
										
                                        <span id="beaconkey-error" class="help-block"></span>
                                    </div>
                                  </div>
								
								
								
								
							      <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Image<span class="symbol required"></span></label>
                                        <?php echo $this->Form->input('image', array('type' => 'file', 'maxlength' => '200', 'class' => 'form-control validate[required]', 'id' => 'image', 'div' => false, 'label' => false, )); ?>
                                        <span id="beaconkey-error" class="help-block"></span>
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
                                    <?php echo $this->Form->button('Save <i class="fa fa-arrow-circle-right"></i>', array('class' => 'btn btn-primary btn-wide pull-left_form', 'type' => 'submit', 'id' => 'submit_button')) ?>
                                    <?php echo $this->Form->button('Cancel <i class="fa fa-arrow-circle-right"></i>', array('class' => 'btn btn-primary btn-wide pull-right', 'type' => 'button', 'id' => 'cancel_button')) ?>
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