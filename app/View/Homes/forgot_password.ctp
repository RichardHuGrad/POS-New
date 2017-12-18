<div class="container">
    <div class="loginpage clearfix">

	<div class="login-logo"><center><?php echo $this->Html->image('login-logo.jpg', array('class' => 'img-responsive', 'alt' => 'POS', 'title' => 'POS')); ?></center></div>

	<?php echo $this->Session->flash(); ?>
	<?php echo $this->Form->create('Cashier', array('type' => 'POST')) ?>
	<div class="form-group">
	    <div class="form-round"><i class="fa fa-user" aria-hidden="true"></i></div>

	    <?php echo $this->Form->input('email', array('type' => 'email', 'placeholder' => __('Email'), 'required' => 'required', 'class' => 'form-control', 'div' => false, 'label' => false)) ?>

	</div>

	<div class="text-center"><button type="submit" class="btn"><?php echo __('Submit'); ?></button></div>
	<?php echo $this->Form->end(); ?>
	<div class="text-center forget-txt"><a href="<?php echo $this->Html->url(array('plugin' => false,'controller' => 'homes','action' => 'index')); ?>"><?php echo __("Already have an account?"); ?></a></div>
	</form>
    </div>
</div>
