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
	<?php echo $this->Form->end(); ?>
	<div class="text-center forget-txt">
		<a href="<?php echo $this->Html->url(array('plugin' => false,'controller' => 'user','action' => 'forgot_password')); ?>"><?php echo __('Forgot your password?') ?></a></div>
	</form>
    </div>
</div>
