<div class="container">
    <div class="loginpage clearfix">

	<div class="login-logo"><center><?php echo $this->Html->image('login-logo.jpg', array('class' => 'img-responsive', 'alt' => 'POS', 'title' => 'POS')); ?></center></div>

	<?php echo $this->Session->flash(); ?>
	<?php echo $this->Form->create('Cashier', array('type' => 'POST')) ?>
	<div class="form-group">
	    <div class="form-round"><i class="fa fa-user" aria-hidden="true"></i></div>

	    <?php echo $this->Form->input('username', array('type' => 'text', 'placeholder' => __('User Name'), 'required' => 'required', 'class' => 'form-control', 'div' => false, 'label' => false)) ?>

	</div>
	<div class="form-group">
	    <div class="form-round"><i class="fa fa-lock" aria-hidden="true"></i></div>
	    <?php echo $this->Form->input('password', array('type' => 'password', 'placeholder' => __('Password'), 'required' => 'required', 'class' => 'form-control', 'div' => false, 'label' => false)) ?>

	</div>
	<div class="text-center"><button type="submit" class="btn"><?php echo __('Sign in') ?></button></div>
	<div class="text-center">
		<button type="button" class="btn attend" style="background-color:#FFBD9D" data-toggle="modal" data-target="#modal_checkin"><?php echo __('Checkin') ?></button>
		
	</div>
	
	<div class="text-center forget-txt">
		<a href="<?php echo $this->Html->url(array('plugin' => false,'controller' => 'homes','action' => 'forgot_password')); ?>"><?php echo __('Forgot your password?') ?></a></div>
	<?php echo $this->Form->end(); ?>
    </div>
</div>

<div class="modal fade clearfix" id="modal_checkin" role="dialog">
   <div class="modal-dialog modal-lg" style="width:500px">
       <div class="modal-content clearfix">
           <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal">&times;</button>
               <h4>Check in</h4>
           </div>
           <div class="modal-body clearfix">
               <input id="checkin-id" type="text" maxlength="100" style="font-size:25px;height:38px" />
           </div>
           <div class="modal-footer clearfix">
               
               <button type="button" id="btn-checkin" class="pull-right btn btn-lg btn-success" data-dismiss="modal">OK 确认</button>
           </div>
       </div>
   </div>
</div>

<?php
echo $this->Html->script(array('jquery.min.js', 'bootstrap.min.js'));
echo $this->fetch('script');
?>


<script type="text/javascript" charset="utf-8">

$(document).ready( function(){

    $("#modal_checkin").on('shown.bs.modal', function () {
          $("#checkin-id").focus();
    }) ; 

    $('#btn-checkin').on('click', function() {
        $.ajax({
            url: "<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'checkin')); ?>",
            method: "post",
            data: { userid: $("#checkin-id").val() },
            success: function(html){ 
            	alert(html);
            	$("#checkin-id").val("");
            }
        })
    });

});

</script>