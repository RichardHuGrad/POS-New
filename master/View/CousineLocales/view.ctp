<div class="cousineLocales view">
<h2><?php echo __('Cousine Locale'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($cousineLocale['CousineLocale']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Cousine'); ?></dt>
		<dd>
			<?php echo $this->Html->link($cousineLocale['Cousine']['id'], array('controller' => 'cousines', 'action' => 'view', $cousineLocale['Cousine']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($cousineLocale['CousineLocale']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Lang Code'); ?></dt>
		<dd>
			<?php echo h($cousineLocale['CousineLocale']['lang_code']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($cousineLocale['CousineLocale']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($cousineLocale['CousineLocale']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Cousine Locale'), array('action' => 'edit', $cousineLocale['CousineLocale']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Cousine Locale'), array('action' => 'delete', $cousineLocale['CousineLocale']['id']), null, __('Are you sure you want to delete # %s?', $cousineLocale['CousineLocale']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Cousine Locales'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cousine Locale'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Cousines'), array('controller' => 'cousines', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cousine'), array('controller' => 'cousines', 'action' => 'add')); ?> </li>
	</ul>
</div>
