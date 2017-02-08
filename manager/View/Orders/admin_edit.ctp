<div id="app">
    <!-- sidebar -->
    <?php echo $this->element('sidebar'); ?>
    <!-- / sidebar -->
    <div class="app-content">
        <!-- start: TOP NAVBAR -->
        <?php echo $this->element('header'); ?> 

        <div class="orders form">
		<?php echo $this->Form->create('Order'); ?>
			<fieldset>
				<legend><?php echo __('Edit Order'); ?></legend>
			<?php
				echo $this->Form->input('id');
				echo $this->Form->input('order_no');
				echo $this->Form->input('reorder_no');
				echo $this->Form->input('hide_no');
				echo $this->Form->input('cashier_id');
				echo $this->Form->input('counter_id');
				echo $this->Form->input('table_no');
				echo $this->Form->input('table_status');
				echo $this->Form->input('tax');
				echo $this->Form->input('tax_amount');
				echo $this->Form->input('subtotal');
				echo $this->Form->input('total');
				echo $this->Form->input('card_val');
				echo $this->Form->input('cash_val');
				echo $this->Form->input('tip');
				echo $this->Form->input('tip_paid_by');
				echo $this->Form->input('paid');
				echo $this->Form->input('change');
				echo $this->Form->input('promocode');
				echo $this->Form->input('message');
				echo $this->Form->input('reason');
				echo $this->Form->input('order_type');
				echo $this->Form->input('is_kitchen');
				echo $this->Form->input('cooking_status');
				echo $this->Form->input('is_hide');
				echo $this->Form->input('is_completed');
				echo $this->Form->input('paid_by');
				echo $this->Form->input('fix_discount');
				echo $this->Form->input('percent_discount');
				echo $this->Form->input('discount_value');
				echo $this->Form->input('merge_id');
				echo $this->Form->input('after_discount');
			?>
			</fieldset>
		<?php echo $this->Form->end(__('Submit')); ?>
		</div>
		<div class="actions">
			<h3><?php echo __('Actions'); ?></h3>
			<ul>

				<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Order.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Order.id'))); ?></li>
			</ul>
		</div>


    </div>

</div>

