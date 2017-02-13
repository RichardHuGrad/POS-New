<div class="categories view">
<h2><?php echo __('Category'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($category['Category']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Restaurant'); ?></dt>
		<dd>
			<?php echo $this->Html->link($category['Restaurant']['id'], array('controller' => 'restaurants', 'action' => 'view', $category['Restaurant']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Status'); ?></dt>
		<dd>
			<?php echo h($category['Category']['status']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($category['Category']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($category['Category']['modified']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Printer'); ?></dt>
		<dd>
			<?php echo h($category['Category']['printer']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Is Synced'); ?></dt>
		<dd>
			<?php echo h($category['Category']['is_synced']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Category'), array('action' => 'edit', $category['Category']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Category'), array('action' => 'delete', $category['Category']['id']), null, __('Are you sure you want to delete # %s?', $category['Category']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Categories'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Category'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Restaurants'), array('controller' => 'restaurants', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Restaurant'), array('controller' => 'restaurants', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Category Locales'), array('controller' => 'category_locales', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Category Locale'), array('controller' => 'category_locales', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Cousines'), array('controller' => 'cousines', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cousine'), array('controller' => 'cousines', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Category Locales'); ?></h3>
	<?php if (!empty($category['CategoryLocale'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Category Id'); ?></th>
		<th><?php echo __('Name'); ?></th>
		<th><?php echo __('Lang Code'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Modified'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($category['CategoryLocale'] as $categoryLocale): ?>
		<tr>
			<td><?php echo $categoryLocale['id']; ?></td>
			<td><?php echo $categoryLocale['category_id']; ?></td>
			<td><?php echo $categoryLocale['name']; ?></td>
			<td><?php echo $categoryLocale['lang_code']; ?></td>
			<td><?php echo $categoryLocale['created']; ?></td>
			<td><?php echo $categoryLocale['modified']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'category_locales', 'action' => 'view', $categoryLocale['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'category_locales', 'action' => 'edit', $categoryLocale['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'category_locales', 'action' => 'delete', $categoryLocale['id']), null, __('Are you sure you want to delete # %s?', $categoryLocale['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Category Locale'), array('controller' => 'category_locales', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php echo __('Related Cousines'); ?></h3>
	<?php if (!empty($category['Cousine'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Restaurant Id'); ?></th>
		<th><?php echo __('Price'); ?></th>
		<th><?php echo __('Category Id'); ?></th>
		<th><?php echo __('Comb Num'); ?></th>
		<th><?php echo __('Status'); ?></th>
		<th><?php echo __('Is Tax'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Modified'); ?></th>
		<th><?php echo __('Is Synced'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($category['Cousine'] as $cousine): ?>
		<tr>
			<td><?php echo $cousine['id']; ?></td>
			<td><?php echo $cousine['restaurant_id']; ?></td>
			<td><?php echo $cousine['price']; ?></td>
			<td><?php echo $cousine['category_id']; ?></td>
			<td><?php echo $cousine['comb_num']; ?></td>
			<td><?php echo $cousine['status']; ?></td>
			<td><?php echo $cousine['is_tax']; ?></td>
			<td><?php echo $cousine['created']; ?></td>
			<td><?php echo $cousine['modified']; ?></td>
			<td><?php echo $cousine['is_synced']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'cousines', 'action' => 'view', $cousine['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'cousines', 'action' => 'edit', $cousine['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'cousines', 'action' => 'delete', $cousine['id']), null, __('Are you sure you want to delete # %s?', $cousine['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Cousine'), array('controller' => 'cousines', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
