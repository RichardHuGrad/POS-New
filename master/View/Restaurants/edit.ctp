<div class="restaurants form">
<?php echo $this->Form->create('Restaurant'); ?>
	<fieldset>
		<legend><?php echo __('Edit Restaurant'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name_en');
		echo $this->Form->input('name_zh');
		echo $this->Form->input('address');
		echo $this->Form->input('mobile');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Restaurant.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Restaurant.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Restaurants'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Categories'), array('controller' => 'categories', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Category'), array('controller' => 'categories', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Cousines'), array('controller' => 'cousines', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cousine'), array('controller' => 'cousines', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Orders'), array('controller' => 'orders', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Order'), array('controller' => 'orders', 'action' => 'add')); ?> </li>
	</ul>
</div>
