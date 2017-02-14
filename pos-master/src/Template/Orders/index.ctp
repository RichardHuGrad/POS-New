<?php
/**
  * @var \App\View\AppView $this
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Order'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Restaurants'), ['controller' => 'Restaurants', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Restaurant'), ['controller' => 'Restaurants', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="orders index large-9 medium-8 columns content">
    <h3><?= __('Orders') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <!-- <th scope="col"><?= $this->Paginator->sort('id') ?></th> -->
                <th scope="col"><?= $this->Paginator->sort('restaurant_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('restaurant_order', 'Order Id') ?></th>
                <!-- <th scope="col"><?= $this->Paginator->sort('order_no') ?></th> -->
                <!-- <th scope="col"><?= $this->Paginator->sort('table_no') ?></th> -->
                <!-- <th scope="col"><?= $this->Paginator->sort('tax') ?></th> -->
                <th scope="col"><?= $this->Paginator->sort('tax_amount') ?></th>
                <th scope="col"><?= $this->Paginator->sort('subtotal') ?></th>
                <th scope="col"><?= $this->Paginator->sort('total') ?></th>
                <!-- <th scope="col"><?= $this->Paginator->sort('card_val') ?></th> -->
                <!-- <th scope="col"><?= $this->Paginator->sort('cash_val') ?></th> -->
                <th scope="col"><?= $this->Paginator->sort('tip') ?></th>
                <!-- <th scope="col"><?= $this->Paginator->sort('tip_paid_by') ?></th> -->
                <th scope="col"><?= $this->Paginator->sort('paid') ?></th>
                <th scope="col"><?= $this->Paginator->sort('change') ?></th>
                <!-- <th scope="col"><?= $this->Paginator->sort('promocode') ?></th> -->
                <!-- <th scope="col"><?= $this->Paginator->sort('order_type') ?></th> -->
                <!-- <th scope="col"><?= $this->Paginator->sort('is_completed') ?></th> -->
                <!-- <th scope="col"><?= $this->Paginator->sort('paid_by') ?></th> -->
                <!-- <th scope="col"><?= $this->Paginator->sort('fix_discount') ?></th> -->
                <!-- <th scope="col"><?= $this->Paginator->sort('percent_discount') ?></th> -->
                <th scope="col"><?= $this->Paginator->sort('discount_value') ?></th>
                <th scope="col"><?= $this->Paginator->sort('after_discount') ?></th>
                <!-- <th scope="col"><?= $this->Paginator->sort('merge') ?></th> -->
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
            <tr>
                <td><?= $this->Number->format($order->id) ?></td>
                <td><?= $order->has('restaurant') ? $this->Html->link($order->restaurant->id, ['controller' => 'Restaurants', 'action' => 'view', $order->restaurant->id]) : '' ?></td>
                <td><?= $this->Number->format($order->restaurant_order) ?></td>
                <td><?= h($order->order_no) ?></td>
                <td><?= $this->Number->format($order->table_no) ?></td>
                <td><?= $this->Number->format($order->tax) ?></td>
                <td><?= $this->Number->format($order->tax_amount) ?></td>
                <td><?= $this->Number->format($order->subtotal) ?></td>
                <td><?= $this->Number->format($order->total) ?></td>
                <td><?= $this->Number->format($order->card_val) ?></td>
                <td><?= $this->Number->format($order->cash_val) ?></td>
                <td><?= $this->Number->format($order->tip) ?></td>
                <td><?= h($order->tip_paid_by) ?></td>
                <td><?= $this->Number->format($order->paid) ?></td>
                <td><?= $this->Number->format($order->change) ?></td>
                <td><?= h($order->promocode) ?></td>
                <td><?= h($order->order_type) ?></td>
                <td><?= h($order->is_completed) ?></td>
                <td><?= h($order->paid_by) ?></td>
                <td><?= $this->Number->format($order->fix_discount) ?></td>
                <td><?= $this->Number->format($order->percent_discount) ?></td>
                <td><?= $this->Number->format($order->discount_value) ?></td>
                <td><?= $this->Number->format($order->after_discount) ?></td>
                <td><?= $this->Number->format($order->merge) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $order->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $order->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $order->id], ['confirm' => __('Are you sure you want to delete # {0}?', $order->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
    </div>
</div>
