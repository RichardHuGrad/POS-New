<?php
/**
  * @var \App\View\AppView $this
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Admins Restaurant'), ['action' => 'edit', $adminsRestaurant->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Admins Restaurant'), ['action' => 'delete', $adminsRestaurant->id], ['confirm' => __('Are you sure you want to delete # {0}?', $adminsRestaurant->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Admins Restaurants'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Admins Restaurant'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Admins'), ['controller' => 'Admins', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Admin'), ['controller' => 'Admins', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Restaurants'), ['controller' => 'Restaurants', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Restaurant'), ['controller' => 'Restaurants', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="adminsRestaurants view large-9 medium-8 columns content">
    <h3><?= h($adminsRestaurant->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Admin') ?></th>
            <td><?= $adminsRestaurant->has('admin') ? $this->Html->link($adminsRestaurant->admin->id, ['controller' => 'Admins', 'action' => 'view', $adminsRestaurant->admin->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Restaurant') ?></th>
            <td><?= $adminsRestaurant->has('restaurant') ? $this->Html->link($adminsRestaurant->restaurant->id, ['controller' => 'Restaurants', 'action' => 'view', $adminsRestaurant->restaurant->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($adminsRestaurant->id) ?></td>
        </tr>
    </table>
</div>
