<?php
$option_table_status = array('P'=>'Paid','N' =>'Not paid','A' =>'Available','V' =>'Void','R' =>'Receipt Printed');
$option_order_type = array('D'=>'Dinein','T'=>'Takeway','W'=>'Delivery','L' =>'Online');
$option_paid_by  = array('CARD' => 'CARD', 'CASH' => 'CASH', 'MIXED' => 'MIXED');
$option_yes_no  = array('Y' => 'Yes', 'N' => 'No');
?>

<div id="app">
    <!-- sidebar -->
    <?php echo $this->element('sidebar'); ?>
    <!-- / sidebar -->
    <div class="app-content">
        <!-- start: TOP NAVBAR -->
        <?php echo $this->element('header'); ?> 

        <div class="main-content" >

	        <div class="orders form">
      			<?php echo $this->Form->create('Order'); ?>
      				<fieldset>
      					<legend><?php echo __('Edit Order'); ?></legend>
      				<?php
      				//  echo $this->Form->input('order_no', array('label' => array('class' => 'col-sm-2 control-label'),'style'=>'width:200px;'));
      				
      					echo $this->Form->input('id', array('label' => array('class' => 'col-sm-2 control-label'),'style'=>'width:200px;'));
      					echo $this->Form->input('order_no', array('label' => array('class' => 'col-sm-2 control-label'),'style'=>'width:200px;'));
      					// echo $this->Form->input('reorder_no');
      					// echo $this->Form->input('hide_no');
      					echo $this->Form->input('cashier_id', array('label' => array('class' => 'col-sm-2 control-label'),'style'=>'width:200px;'));
      					// echo $this->Form->input('counter_id');
      					echo $this->Form->input('table_no', array('label' => array('class' => 'col-sm-2 control-label'),'style'=>'width:200px;'));
      					echo $this->Form->input('table_status', array('label' => array('class' => 'col-sm-2 control-label'),'style'=>'width:200px;','options' => $option_table_status, 'empty' => '...'));
      					echo $this->Form->input('tax', array('label' => array('class' => 'col-sm-2 control-label'),'style'=>'width:200px;'));
      					echo $this->Form->input('tax_amount', array('label' => array('class' => 'col-sm-2 control-label'),'style'=>'width:200px;'));
      					echo $this->Form->input('subtotal', array('label' => array('class' => 'col-sm-2 control-label'),'style'=>'width:200px;'));
      					echo $this->Form->input('total', array('label' => array('class' => 'col-sm-2 control-label'),'style'=>'width:200px;'));
      					echo $this->Form->input('card_val', array('label' => array('class' => 'col-sm-2 control-label'),'style'=>'width:200px;'));
      					echo $this->Form->input('cash_val', array('label' => array('class' => 'col-sm-2 control-label'),'style'=>'width:200px;'));
      					echo $this->Form->input('tip', array('label' => array('class' => 'col-sm-2 control-label'),'style'=>'width:200px;'));
      					echo $this->Form->input('tip_paid_by', array('label' => array('class' => 'col-sm-2 control-label'),'style'=>'width:200px;'));
      					echo $this->Form->input('paid', array('label' => array('class' => 'col-sm-2 control-label'),'style'=>'width:200px;'));
      					echo $this->Form->input('paid_by', array('label' => array('class' => 'col-sm-2 control-label'),'style'=>'width:200px;' ,'options' => $option_paid_by, 'empty' => '...'));
      					echo $this->Form->input('change', array('label' => array('class' => 'col-sm-2 control-label'),'style'=>'width:200px;'));
      					echo $this->Form->input('promocode', array('label' => array('class' => 'col-sm-2 control-label'),'style'=>'width:200px;'));
      					// echo $this->Form->input('message', array('label' => array('class' => 'col-sm-2 control-label'),'style'=>'width:200px;'));
      					// echo $this->Form->input('reason');
      					echo $this->Form->input('order_type', array('label' => array('class' => 'col-sm-2 control-label'),'style'=>'width:200px;' ,'options' => $option_order_type, 'empty' => '...'));
      					echo $this->Form->input('is_kitchen', array('label' => array('class' => 'col-sm-2 control-label'),'style'=>'width:200px;'));
      					echo $this->Form->input('cooking_status', array('label' => array('class' => 'col-sm-2 control-label'),'style'=>'width:200px;'));
      					// echo $this->Form->input('is_hide');
      					echo $this->Form->input('is_completed', array('label' => array('class' => 'col-sm-2 control-label'),'style'=>'width:200px;' ,'options' => $option_yes_no, 'empty' => '...'));
      					// echo $this->Form->input('paid_by');
      					echo $this->Form->input('fix_discount', array('label' => array('class' => 'col-sm-2 control-label'),'style'=>'width:200px;'));
      					echo $this->Form->input('percent_discount', array('label' => array('class' => 'col-sm-2 control-label'),'style'=>'width:200px;'));
      					echo $this->Form->input('discount_value', array('label' => array('class' => 'col-sm-2 control-label'),'style'=>'width:200px;'));
      					// echo $this->Form->input('merge_id');
      					echo $this->Form->input('after_discount', array('label' => array('class' => 'col-sm-2 control-label'),'style'=>'width:200px;'));
      				?>
      				</fieldset>
      				<?php echo $this->Form->end(__('Submit')); ?>
      			  <div class="actions">
      			  	<h3><?php echo __('Actions'); ?></h3>
      			  	<ul>
              
      			  		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Order.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Order.id'))); ?></li>
      			  	</ul>
			        </div>

			    </div>
		    </div>
		
    </div>

</div>

<script>
	$('form').submit(function(e) {
		// e.preventDefault();
		$.ajax({
			url: "<?php echo $this->Html->url(array('controller' => 'orders', 'action' => 'edit_log', 'admin' => true)); ?>",
			method: 'post',
			data: {order_no: $('#OrderOrderNo').val()}
		})
		console.log($('#OrderOrderNo').val());
	});
	
</script>

