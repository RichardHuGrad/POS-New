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
		<button type="button" class="btn attend" style="background-color: 	#FFBD9D"><?php echo __('签到') ?></button>
		
	</div>
	<?php echo $this->Form->end(); ?>
	<div class="text-center forget-txt">
		<a href="<?php echo $this->Html->url(array('plugin' => false,'controller' => 'homes','action' => 'forgot_password')); ?>"><?php echo __('Forgot your password?') ?></a></div>
	</form>
    </div>
</div>

<div class="modal fade clearfix" id="edit-phone-component-modal" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content clearfix">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4>Input Phone</h4>
                </div>
                <div class="modal-body clearfix">


                         <div class="dropdown-menu dropdown-overlay">
                            	<ul class="dine-tables">
                                    <!--<div class="arrow"></div>-->
                                    <li class="dropdown-title">堂食18</li>
                                    <li><a tabindex="-1" href="/order/index/table:18/type:D">订单</a></li>

                                    <li class="dropdown-submenu ">
                                        <a class="test" tabindex="-1" href="/homes/changetable/table:18/type:D">换桌</a>
                                    </li>
                                    <li><a tabindex="-1" href="/pay/index/table:18/type:D">付款</a></li>

                                    <li><a tabindex="-1" href="javascript:makeavailable('/homes/makeavailable/table:18/type:D/order:180641432');">变空桌</a></li>
                                    </li>

                                    <li class=" bottom-submenu"><a class="test" tabindex="-1" href="/split/index/table:18/type:D/split_method:1">分单</a>
                                    </li>

                                    <li><a tabindex="-1" href="/homes/tableHistory/table_no:18">历史订单</a>
                                    </li>
                            	</ul>
                          </div>



                </div>
                <div class="modal-footer clearfix">
                    
                    <button type="button" id="check_in" class="pull-right btn btn-lg btn-success">OK 确认</button>
                </div>
            </div>
        </div>
</div>



<?php
echo $this->Html->script(array('jquery.min.js', 'bootstrap.min.js'));
echo $this->fetch('script');
?>
