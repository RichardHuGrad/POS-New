<div class="restaurants view">
<h2><?php echo __('Restaurant'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($restaurant['Restaurant']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name En'); ?></dt>
		<dd>
			<?php echo h($restaurant['Restaurant']['name_en']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name Zh'); ?></dt>
		<dd>
			<?php echo h($restaurant['Restaurant']['name_zh']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Address'); ?></dt>
		<dd>
			<?php echo h($restaurant['Restaurant']['address']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Mobile'); ?></dt>
		<dd>
			<?php echo h($restaurant['Restaurant']['mobile']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Restaurant'), array('action' => 'edit', $restaurant['Restaurant']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Restaurant'), array('action' => 'delete', $restaurant['Restaurant']['id']), null, __('Are you sure you want to delete # %s?', $restaurant['Restaurant']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Restaurants'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Restaurant'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Categories'), array('controller' => 'categories', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Category'), array('controller' => 'categories', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Cousines'), array('controller' => 'cousines', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cousine'), array('controller' => 'cousines', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Orders'), array('controller' => 'orders', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Order'), array('controller' => 'orders', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Categories'); ?></h3>
	<?php if (!empty($restaurant['Category'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Restaurant Id'); ?></th>
		<th><?php echo __('Status'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Modified'); ?></th>
		<th><?php echo __('Printer'); ?></th>
		<th><?php echo __('Is Synced'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($restaurant['Category'] as $category): ?>
		<tr>
			<td><?php echo $category['id']; ?></td>
			<td><?php echo $category['restaurant_id']; ?></td>
			<td><?php echo $category['status']; ?></td>
			<td><?php echo $category['created']; ?></td>
			<td><?php echo $category['modified']; ?></td>
			<td><?php echo $category['printer']; ?></td>
			<td><?php echo $category['is_synced']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'categories', 'action' => 'view', $category['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'categories', 'action' => 'edit', $category['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'categories', 'action' => 'delete', $category['id']), null, __('Are you sure you want to delete # %s?', $category['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Category'), array('controller' => 'categories', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php echo __('Related Cousines'); ?></h3>
	<?php if (!empty($restaurant['Cousine'])): ?>
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
	<?php foreach ($restaurant['Cousine'] as $cousine): ?>
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
<div class="related">
	<h3><?php echo __('Related Orders'); ?></h3>
	<?php if (!empty($restaurant['Order'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Restaurant Id'); ?></th>
		<th><?php echo __('Restaurant Order Id'); ?></th>
		<th><?php echo __('Order No'); ?></th>
		<th><?php echo __('Table No'); ?></th>
		<th><?php echo __('Tax'); ?></th>
		<th><?php echo __('Tax Amount'); ?></th>
		<th><?php echo __('Subtotal'); ?></th>
		<th><?php echo __('Total'); ?></th>
		<th><?php echo __('Card Val'); ?></th>
		<th><?php echo __('Cash Val'); ?></th>
		<th><?php echo __('Tip'); ?></th>
		<th><?php echo __('Tip Paid By'); ?></th>
		<th><?php echo __('Paid'); ?></th>
		<th><?php echo __('Change'); ?></th>
		<th><?php echo __('Promocode'); ?></th>
		<th><?php echo __('Message'); ?></th>
		<th><?php echo __('Reason'); ?></th>
		<th><?php echo __('Order Type'); ?></th>
		<th><?php echo __('Is Completed'); ?></th>
		<th><?php echo __('Paid By'); ?></th>
		<th><?php echo __('Fix Discount'); ?></th>
		<th><?php echo __('Percent Discount'); ?></th>
		<th><?php echo __('Discount Value'); ?></th>
		<th><?php echo __('After Discount'); ?></th>
		<th><?php echo __('Merge Id'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($restaurant['Order'] as $order): ?>
		<tr>
			<td><?php echo $order['id']; ?></td>
			<td><?php echo $order['restaurant_id']; ?></td>
			<td><?php echo $order['restaurant_order_id']; ?></td>
			<td><?php echo $order['order_no']; ?></td>
			<td><?php echo $order['table_no']; ?></td>
			<td><?php echo $order['tax']; ?></td>
			<td><?php echo $order['tax_amount']; ?></td>
			<td><?php echo $order['subtotal']; ?></td>
			<td><?php echo $order['total']; ?></td>
			<td><?php echo $order['card_val']; ?></td>
			<td><?php echo $order['cash_val']; ?></td>
			<td><?php echo $order['tip']; ?></td>
			<td><?php echo $order['tip_paid_by']; ?></td>
			<td><?php echo $order['paid']; ?></td>
			<td><?php echo $order['change']; ?></td>
			<td><?php echo $order['promocode']; ?></td>
			<td><?php echo $order['message']; ?></td>
			<td><?php echo $order['reason']; ?></td>
			<td><?php echo $order['order_type']; ?></td>
			<td><?php echo $order['is_completed']; ?></td>
			<td><?php echo $order['paid_by']; ?></td>
			<td><?php echo $order['fix_discount']; ?></td>
			<td><?php echo $order['percent_discount']; ?></td>
			<td><?php echo $order['discount_value']; ?></td>
			<td><?php echo $order['after_discount']; ?></td>
			<td><?php echo $order['merge_id']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'orders', 'action' => 'view', $order['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'orders', 'action' => 'edit', $order['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'orders', 'action' => 'delete', $order['id']), null, __('Are you sure you want to delete # %s?', $order['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Order'), array('controller' => 'orders', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
