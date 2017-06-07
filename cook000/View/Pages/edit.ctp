<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery("#cancel_button").click(function () {
            window.location.href = '<?php echo $this->Html->url(array('plugin' => false, 'controller' => 'restaurants', 'action' => 'index')); ?>';
        });
    });
</script>
<?php echo $this->Html->script(array('ckeditor/ckeditor'));?>
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
                            <h1 class="mainTitle">Edit Page</h1>                            
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
                            <?php echo $this->Form->create('Page', array('method' => 'post', 'class' => 'form', 'role' => 'form', 'enctype' => 'multipart/form-data')); ?>                    
                            <?php echo $this->Form->input('id',array('type' => 'hidden')); ?>
                         
									<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label">Name <span class="symbol required"></span></label> 
													<?php echo $this->Form->input('name', array('type' => 'text', 'maxlength' => '100', 'class' => 'form-control', 'id' => 'place', 'div' => false, 'label' => false, 'required' => true)); ?>
													<span id="place-error" class="help-block"></span>
												</div>
											</div>
										   
										</div>
										<div class="row">
										   <div class="col-md-12">
												<div class="form-group">
													<label class="control-label">Content <span class="symbol required"></span></label> 
													<?php echo $this->Form->textarea("discription",array('id'=>'body')); ?><span class="help-inline"><?php echo $this->Form->error("Page.content",array("wrap"=>false)); ?>
													<span id="place-error" class="help-block"></span>
												</div>
											</div>
								
								    <script type="text/javascript">
									// <![CDATA[
											CKEDITOR.replace( 'body',
											{
												height: 350,
												width: 800,
												enterMode : CKEDITOR.ENTER_BR
											});
									//]]>		
									</script>
								
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