<div class="cousineLocales index">
	<h2><?php echo __('Cousine Locales'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('cousine_id'); ?></th>
			<th><?php echo $this->Paginator->sort('name'); ?></th>
			<th><?php echo $this->Paginator->sort('lang_code'); ?></th>
			<th><?php echo $this->Paginator->sort('created'); ?></th>
			<th><?php echo $this->Paginator->sort('modified'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($cousineLocales as $cousineLocale): ?>
	<tr>
		<td><?php echo h($cousineLocale['CousineLocale']['id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($cousineLocale['Cousine']['id'], array('controller' => 'cousines', 'action' => 'view', $cousineLocale['Cousine']['id'])); ?>
		</td>
		<td><?php echo h($cousineLocale['CousineLocale']['name']); ?>&nbsp;</td>
		<td><?php echo h($cousineLocale['CousineLocale']['lang_code']); ?>&nbsp;</td>
		<td><?php echo h($cousineLocale['CousineLocale']['created']); ?>&nbsp;</td>
		<td><?php echo h($cousineLocale['CousineLocale']['modified']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $cousineLocale['CousineLocale']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $cousineLocale['CousineLocale']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $cousineLocale['CousineLocale']['id']), null, __('Are you sure you want to delete # %s?', $cousineLocale['CousineLocale']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>
	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Cousine Locale'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Cousines'), array('controller' => 'cousines', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cousine'), array('controller' => 'cousines', 'action' => 'add')); ?> </li>
	</ul>
</div>
