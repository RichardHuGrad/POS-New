<div class="cousines form">
<?php echo $this->Form->create('Cousine'); ?>
	<fieldset>
		<legend><?php echo __('Edit Cousine'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('restaurant_id');
		echo $this->Form->input('price');
		echo $this->Form->input('category_id');
		echo $this->Form->input('comb_num');
		echo $this->Form->input('status');
		echo $this->Form->input('is_tax');
		echo $this->Form->input('is_synced');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Cousine.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Cousine.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Cousines'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Restaurants'), array('controller' => 'restaurants', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Restaurant'), array('controller' => 'restaurants', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Categories'), array('controller' => 'categories', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Category'), array('controller' => 'categories', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Cousine Locales'), array('controller' => 'cousine_locales', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cousine Locale'), array('controller' => 'cousine_locales', 'action' => 'add')); ?> </li>
	</ul>
</div>
