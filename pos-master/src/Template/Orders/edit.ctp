<?php
/**
  * @var \App\View\AppView $this
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $order->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $order->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Orders'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Restaurants'), ['controller' => 'Restaurants', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Restaurant'), ['controller' => 'Restaurants', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="orders form large-9 medium-8 columns content">
    <?= $this->Form->create($order) ?>
    <fieldset>
        <legend><?= __('Edit Order') ?></legend>
        <?php
            echo $this->Form->input('restaurant_id', ['options' => $restaurants]);
            echo $this->Form->input('restaurant_order');
            echo $this->Form->input('order_no');
            echo $this->Form->input('table_no');
            echo $this->Form->input('tax');
            echo $this->Form->input('tax_amount');
            echo $this->Form->input('subtotal');
            echo $this->Form->input('total');
            echo $this->Form->input('card_val');
            echo $this->Form->input('cash_val');
            echo $this->Form->input('tip');
            echo $this->Form->input('tip_paid_by');
            echo $this->Form->input('paid');
            echo $this->Form->input('change');
            echo $this->Form->input('promocode');
            echo $this->Form->input('message');
            echo $this->Form->input('reason');
            echo $this->Form->input('order_type');
            echo $this->Form->input('is_completed');
            echo $this->Form->input('paid_by');
            echo $this->Form->input('fix_discount');
            echo $this->Form->input('percent_discount');
            echo $this->Form->input('discount_value');
            echo $this->Form->input('after_discount');
            echo $this->Form->input('merge');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
