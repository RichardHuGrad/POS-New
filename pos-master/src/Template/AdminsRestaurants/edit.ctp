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
                ['action' => 'delete', $adminsRestaurant->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $adminsRestaurant->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Admins Restaurants'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Admins'), ['controller' => 'Admins', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Admin'), ['controller' => 'Admins', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Restaurants'), ['controller' => 'Restaurants', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Restaurant'), ['controller' => 'Restaurants', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="adminsRestaurants form large-9 medium-8 columns content">
    <?= $this->Form->create($adminsRestaurant) ?>
    <fieldset>
        <legend><?= __('Edit Admins Restaurant') ?></legend>
        <?php
            echo $this->Form->input('admin_id', ['options' => $admins]);
            echo $this->Form->input('restaurant_id', ['options' => $restaurants]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
