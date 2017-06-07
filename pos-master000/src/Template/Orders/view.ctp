<?php
/**
  * @var \App\View\AppView $this
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Order'), ['action' => 'edit', $order->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Order'), ['action' => 'delete', $order->id], ['confirm' => __('Are you sure you want to delete # {0}?', $order->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Orders'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Order'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Restaurants'), ['controller' => 'Restaurants', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Restaurant'), ['controller' => 'Restaurants', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="orders view large-9 medium-8 columns content">
    <h3><?= h($order->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Restaurant') ?></th>
            <td><?= $order->has('restaurant') ? $this->Html->link($order->restaurant->id, ['controller' => 'Restaurants', 'action' => 'view', $order->restaurant->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Order No') ?></th>
            <td><?= h($order->order_no) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Tip Paid By') ?></th>
            <td><?= h($order->tip_paid_by) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Promocode') ?></th>
            <td><?= h($order->promocode) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Order Type') ?></th>
            <td><?= h($order->order_type) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Is Completed') ?></th>
            <td><?= h($order->is_completed) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Paid By') ?></th>
            <td><?= h($order->paid_by) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($order->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Restaurant Order') ?></th>
            <td><?= $this->Number->format($order->restaurant_order) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Table No') ?></th>
            <td><?= $this->Number->format($order->table_no) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Tax') ?></th>
            <td><?= $this->Number->format($order->tax) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Tax Amount') ?></th>
            <td><?= $this->Number->format($order->tax_amount) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Subtotal') ?></th>
            <td><?= $this->Number->format($order->subtotal) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Total') ?></th>
            <td><?= $this->Number->format($order->total) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Card Val') ?></th>
            <td><?= $this->Number->format($order->card_val) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Cash Val') ?></th>
            <td><?= $this->Number->format($order->cash_val) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Tip') ?></th>
            <td><?= $this->Number->format($order->tip) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Paid') ?></th>
            <td><?= $this->Number->format($order->paid) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Change') ?></th>
            <td><?= $this->Number->format($order->change) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Fix Discount') ?></th>
            <td><?= $this->Number->format($order->fix_discount) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Percent Discount') ?></th>
            <td><?= $this->Number->format($order->percent_discount) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Discount Value') ?></th>
            <td><?= $this->Number->format($order->discount_value) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('After Discount') ?></th>
            <td><?= $this->Number->format($order->after_discount) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Merge') ?></th>
            <td><?= $this->Number->format($order->merge) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Message') ?></h4>
        <?= $this->Text->autoParagraph(h($order->message)); ?>
    </div>
    <div class="row">
        <h4><?= __('Reason') ?></h4>
        <?= $this->Text->autoParagraph(h($order->reason)); ?>
    </div>
</div>
