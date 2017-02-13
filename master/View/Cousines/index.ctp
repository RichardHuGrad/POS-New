<div class="cousines index">
	<h2><?php echo __('Cousines'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('restaurant_id'); ?></th>
			<th><?php echo $this->Paginator->sort('price'); ?></th>
			<th><?php echo $this->Paginator->sort('category_id'); ?></th>
			<th><?php echo $this->Paginator->sort('comb_num'); ?></th>
			<th><?php echo $this->Paginator->sort('status'); ?></th>
			<th><?php echo $this->Paginator->sort('is_tax'); ?></th>
			<th><?php echo $this->Paginator->sort('created'); ?></th>
			<th><?php echo $this->Paginator->sort('modified'); ?></th>
			<th><?php echo $this->Paginator->sort('is_synced'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($cousines as $cousine): ?>
	<tr>
		<td><?php echo h($cousine['Cousine']['id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($cousine['Restaurant']['id'], array('controller' => 'restaurants', 'action' => 'view', $cousine['Restaurant']['id'])); ?>
		</td>
		<td><?php echo h($cousine['Cousine']['price']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($cousine['Category']['id'], array('controller' => 'categories', 'action' => 'view', $cousine['Category']['id'])); ?>
		</td>
		<td><?php echo h($cousine['Cousine']['comb_num']); ?>&nbsp;</td>
		<td><?php echo h($cousine['Cousine']['status']); ?>&nbsp;</td>
		<td><?php echo h($cousine['Cousine']['is_tax']); ?>&nbsp;</td>
		<td><?php echo h($cousine['Cousine']['created']); ?>&nbsp;</td>
		<td><?php echo h($cousine['Cousine']['modified']); ?>&nbsp;</td>
		<td><?php echo h($cousine['Cousine']['is_synced']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $cousine['Cousine']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $cousine['Cousine']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $cousine['Cousine']['id']), null, __('Are you sure you want to delete # %s?', $cousine['Cousine']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Cousine'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Restaurants'), array('controller' => 'restaurants', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Restaurant'), array('controller' => 'restaurants', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Categories'), array('controller' => 'categories', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Category'), array('controller' => 'categories', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Cousine Locals'), array('controller' => 'cousine_locals', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cousine Local'), array('controller' => 'cousine_locals', 'action' => 'add')); ?> </li>
	</ul>
</div>
