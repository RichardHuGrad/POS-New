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
                ['action' => 'delete', $cousine->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $cousine->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Cousines'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Restaurants'), ['controller' => 'Restaurants', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Restaurant'), ['controller' => 'Restaurants', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Categories'), ['controller' => 'Categories', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Category'), ['controller' => 'Categories', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Cousine Locales'), ['controller' => 'CousineLocales', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Cousine Locale'), ['controller' => 'CousineLocales', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="cousines form large-9 medium-8 columns content">
    <?= $this->Form->create($cousine) ?>
    <fieldset>
        <legend><?= __('Edit Cousine') ?></legend>
        <?php
            echo $this->Form->input('restaurant_id', ['options' => $restaurants]);
            echo $this->Form->input('price');
            echo $this->Form->input('category_id', ['options' => $categories]);
            echo $this->Form->input('comb_num');
            echo $this->Form->input('status');
            echo $this->Form->input('is_tax');
            echo $this->Form->input('is_synced');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
