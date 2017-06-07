<?php
/**
  * @var \App\View\AppView $this
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Cousine Locale'), ['action' => 'edit', $cousineLocale->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Cousine Locale'), ['action' => 'delete', $cousineLocale->id], ['confirm' => __('Are you sure you want to delete # {0}?', $cousineLocale->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Cousine Locales'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Cousine Locale'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Cousines'), ['controller' => 'Cousines', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Cousine'), ['controller' => 'Cousines', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="cousineLocales view large-9 medium-8 columns content">
    <h3><?= h($cousineLocale->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Cousine') ?></th>
            <td><?= $cousineLocale->has('cousine') ? $this->Html->link($cousineLocale->cousine->id, ['controller' => 'Cousines', 'action' => 'view', $cousineLocale->cousine->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($cousineLocale->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Lang Code') ?></th>
            <td><?= h($cousineLocale->lang_code) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($cousineLocale->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= $this->Number->format($cousineLocale->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= $this->Number->format($cousineLocale->modified) ?></td>
        </tr>
    </table>
</div>
