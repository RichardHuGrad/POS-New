<div class="cousines view">
<h2><?php echo __('Cousine'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($cousine['Cousine']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Restaurant'); ?></dt>
		<dd>
			<?php echo $this->Html->link($cousine['Restaurant']['id'], array('controller' => 'restaurants', 'action' => 'view', $cousine['Restaurant']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Price'); ?></dt>
		<dd>
			<?php echo h($cousine['Cousine']['price']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Category'); ?></dt>
		<dd>
			<?php echo $this->Html->link($cousine['Category']['id'], array('controller' => 'categories', 'action' => 'view', $cousine['Category']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Comb Num'); ?></dt>
		<dd>
			<?php echo h($cousine['Cousine']['comb_num']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Status'); ?></dt>
		<dd>
			<?php echo h($cousine['Cousine']['status']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Is Tax'); ?></dt>
		<dd>
			<?php echo h($cousine['Cousine']['is_tax']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($cousine['Cousine']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($cousine['Cousine']['modified']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Is Synced'); ?></dt>
		<dd>
			<?php echo h($cousine['Cousine']['is_synced']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Cousine'), array('action' => 'edit', $cousine['Cousine']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Cousine'), array('action' => 'delete', $cousine['Cousine']['id']), null, __('Are you sure you want to delete # %s?', $cousine['Cousine']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Cousines'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cousine'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Restaurants'), array('controller' => 'restaurants', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Restaurant'), array('controller' => 'restaurants', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Categories'), array('controller' => 'categories', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Category'), array('controller' => 'categories', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Cousine Locals'), array('controller' => 'cousine_locals', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cousine Local'), array('controller' => 'cousine_locals', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Cousine Locals'); ?></h3>
	<?php if (!empty($cousine['CousineLocal'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Cousine Id'); ?></th>
		<th><?php echo __('Name'); ?></th>
		<th><?php echo __('Lang Code'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Modified'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($cousine['CousineLocal'] as $cousineLocal): ?>
		<tr>
			<td><?php echo $cousineLocal['id']; ?></td>
			<td><?php echo $cousineLocal['cousine_id']; ?></td>
			<td><?php echo $cousineLocal['name']; ?></td>
			<td><?php echo $cousineLocal['lang_code']; ?></td>
			<td><?php echo $cousineLocal['created']; ?></td>
			<td><?php echo $cousineLocal['modified']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'cousine_locals', 'action' => 'view', $cousineLocal['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'cousine_locals', 'action' => 'edit', $cousineLocal['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'cousine_locals', 'action' => 'delete', $cousineLocal['id']), null, __('Are you sure you want to delete # %s?', $cousineLocal['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Cousine Local'), array('controller' => 'cousine_locals', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
