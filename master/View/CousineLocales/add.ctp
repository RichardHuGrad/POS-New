<div class="cousineLocales form">
<?php echo $this->Form->create('CousineLocale'); ?>
	<fieldset>
		<legend><?php echo __('Add Cousine Locale'); ?></legend>
	<?php
		echo $this->Form->input('cousine_id');
		echo $this->Form->input('name');
		echo $this->Form->input('lang_code');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Cousine Locales'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Cousines'), array('controller' => 'cousines', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cousine'), array('controller' => 'cousines', 'action' => 'add')); ?> </li>
	</ul>
</div>
