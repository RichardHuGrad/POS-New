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
                ['action' => 'delete', $restaurant->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $restaurant->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Restaurants'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Categories'), ['controller' => 'Categories', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Category'), ['controller' => 'Categories', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Cousines'), ['controller' => 'Cousines', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Cousine'), ['controller' => 'Cousines', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Orders'), ['controller' => 'Orders', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Order'), ['controller' => 'Orders', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="restaurants form large-9 medium-8 columns content">
    <?= $this->Form->create($restaurant) ?>
    <fieldset>
        <legend><?= __('Edit Restaurant') ?></legend>
        <?php
            echo $this->Form->input('name_en');
            echo $this->Form->input('name_zh');
            echo $this->Form->input('address');
            echo $this->Form->input('mobile');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
