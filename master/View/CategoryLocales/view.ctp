<div class="categoryLocales view">
<h2><?php echo __('Category Locale'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($categoryLocale['CategoryLocale']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Category'); ?></dt>
		<dd>
			<?php echo $this->Html->link($categoryLocale['Category']['id'], array('controller' => 'categories', 'action' => 'view', $categoryLocale['Category']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($categoryLocale['CategoryLocale']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Lang Code'); ?></dt>
		<dd>
			<?php echo h($categoryLocale['CategoryLocale']['lang_code']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($categoryLocale['CategoryLocale']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($categoryLocale['CategoryLocale']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Category Locale'), array('action' => 'edit', $categoryLocale['CategoryLocale']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Category Locale'), array('action' => 'delete', $categoryLocale['CategoryLocale']['id']), null, __('Are you sure you want to delete # %s?', $categoryLocale['CategoryLocale']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Category Locales'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Category Locale'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Categories'), array('controller' => 'categories', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Category'), array('controller' => 'categories', 'action' => 'add')); ?> </li>
	</ul>
</div>
