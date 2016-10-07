
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
                            <h1 class="mainTitle">Change Password</h1>                            
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
                            <?php echo $this->Form->input('id',array('type' => 'hidden')); ?>
                          	
                            <br/>
                         
							
							<div class="row">
                               <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">New Password <span class="symbol required"></span></label> 
                                        <?php echo $this->Form->input('password', array('type' => 'password',  'class' => 'form-control',  'label' => false, 'div' => false)); ?>
                                        <span id="place-error" class="help-block"></span>
                                    </div>
                                </div>
								
								<div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Confirm New Password <span class="symbol required"></span></label> 
                                        <?php echo $this->Form->input('confirm_password', array('type' => 'password',  'class' => 'form-control',  'label' => false, 'div' => false)); ?>
                                        <span id="place-error" class="help-block"></span>
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