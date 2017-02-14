<?php
/**
  * @var \App\View\AppView $this
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Restaurant'), ['action' => 'edit', $restaurant->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Restaurant'), ['action' => 'delete', $restaurant->id], ['confirm' => __('Are you sure you want to delete # {0}?', $restaurant->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Restaurants'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Restaurant'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Categories'), ['controller' => 'Categories', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Category'), ['controller' => 'Categories', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Cousines'), ['controller' => 'Cousines', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Cousine'), ['controller' => 'Cousines', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Orders'), ['controller' => 'Orders', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Order'), ['controller' => 'Orders', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="restaurants view large-9 medium-8 columns content">
    <h3><?= h($restaurant->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Name En') ?></th>
            <td><?= h($restaurant->name_en) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Name Zh') ?></th>
            <td><?= h($restaurant->name_zh) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Address') ?></th>
            <td><?= h($restaurant->address) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Mobile') ?></th>
            <td><?= h($restaurant->mobile) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($restaurant->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($restaurant->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($restaurant->modified) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Categories') ?></h4>
        <?php if (!empty($restaurant->categories)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Restaurant Id') ?></th>
                <th scope="col"><?= __('Status') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col"><?= __('Printer') ?></th>
                <th scope="col"><?= __('Is Synced') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($restaurant->categories as $categories): ?>
            <tr>
                <td><?= h($categories->id) ?></td>
                <td><?= h($categories->restaurant_id) ?></td>
                <td><?= h($categories->status) ?></td>
                <td><?= h($categories->created) ?></td>
                <td><?= h($categories->modified) ?></td>
                <td><?= h($categories->printer) ?></td>
                <td><?= h($categories->is_synced) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Categories', 'action' => 'view', $categories->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Categories', 'action' => 'edit', $categories->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Categories', 'action' => 'delete', $categories->id], ['confirm' => __('Are you sure you want to delete # {0}?', $categories->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Cousines') ?></h4>
        <?php if (!empty($restaurant->cousines)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Restaurant Id') ?></th>
                <th scope="col"><?= __('Price') ?></th>
                <th scope="col"><?= __('Category Id') ?></th>
                <th scope="col"><?= __('Comb Num') ?></th>
                <th scope="col"><?= __('Status') ?></th>
                <th scope="col"><?= __('Is Tax') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col"><?= __('Is Synced') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($restaurant->cousines as $cousines): ?>
            <tr>
                <td><?= h($cousines->id) ?></td>
                <td><?= h($cousines->restaurant_id) ?></td>
                <td><?= h($cousines->price) ?></td>
                <td><?= h($cousines->category_id) ?></td>
                <td><?= h($cousines->comb_num) ?></td>
                <td><?= h($cousines->status) ?></td>
                <td><?= h($cousines->is_tax) ?></td>
                <td><?= h($cousines->created) ?></td>
                <td><?= h($cousines->modified) ?></td>
                <td><?= h($cousines->is_synced) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Cousines', 'action' => 'view', $cousines->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Cousines', 'action' => 'edit', $cousines->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Cousines', 'action' => 'delete', $cousines->id], ['confirm' => __('Are you sure you want to delete # {0}?', $cousines->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Orders') ?></h4>
        <?php if (!empty($restaurant->orders)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Restaurant Id') ?></th>
                <th scope="col"><?= __('Origin Order Id') ?></th>
                <th scope="col"><?= __('Order No') ?></th>
                <th scope="col"><?= __('Table No') ?></th>
                <th scope="col"><?= __('Tax') ?></th>
                <th scope="col"><?= __('Tax Amount') ?></th>
                <th scope="col"><?= __('Subtotal') ?></th>
                <th scope="col"><?= __('Total') ?></th>
                <th scope="col"><?= __('Card Val') ?></th>
                <th scope="col"><?= __('Cash Val') ?></th>
                <th scope="col"><?= __('Tip') ?></th>
                <th scope="col"><?= __('Tip Paid By') ?></th>
                <th scope="col"><?= __('Paid') ?></th>
                <th scope="col"><?= __('Change') ?></th>
                <th scope="col"><?= __('Promocode') ?></th>
                <th scope="col"><?= __('Message') ?></th>
                <th scope="col"><?= __('Reason') ?></th>
                <th scope="col"><?= __('Order Type') ?></th>
                <th scope="col"><?= __('Is Completed') ?></th>
                <th scope="col"><?= __('Paid By') ?></th>
                <th scope="col"><?= __('Fix Discount') ?></th>
                <th scope="col"><?= __('Percent Discount') ?></th>
                <th scope="col"><?= __('Discount Value') ?></th>
                <th scope="col"><?= __('After Discount') ?></th>
                <th scope="col"><?= __('Merge Id') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($restaurant->orders as $orders): ?>
            <tr>
                <td><?= h($orders->id) ?></td>
                <td><?= h($orders->restaurant_id) ?></td>
                <td><?= h($orders->origin_order_id) ?></td>
                <td><?= h($orders->order_no) ?></td>
                <td><?= h($orders->table_no) ?></td>
                <td><?= h($orders->tax) ?></td>
                <td><?= h($orders->tax_amount) ?></td>
                <td><?= h($orders->subtotal) ?></td>
                <td><?= h($orders->total) ?></td>
                <td><?= h($orders->card_val) ?></td>
                <td><?= h($orders->cash_val) ?></td>
                <td><?= h($orders->tip) ?></td>
                <td><?= h($orders->tip_paid_by) ?></td>
                <td><?= h($orders->paid) ?></td>
                <td><?= h($orders->change) ?></td>
                <td><?= h($orders->promocode) ?></td>
                <td><?= h($orders->message) ?></td>
                <td><?= h($orders->reason) ?></td>
                <td><?= h($orders->order_type) ?></td>
                <td><?= h($orders->is_completed) ?></td>
                <td><?= h($orders->paid_by) ?></td>
                <td><?= h($orders->fix_discount) ?></td>
                <td><?= h($orders->percent_discount) ?></td>
                <td><?= h($orders->discount_value) ?></td>
                <td><?= h($orders->after_discount) ?></td>
                <td><?= h($orders->merge_id) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Orders', 'action' => 'view', $orders->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Orders', 'action' => 'edit', $orders->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Orders', 'action' => 'delete', $orders->id], ['confirm' => __('Are you sure you want to delete # {0}?', $orders->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
