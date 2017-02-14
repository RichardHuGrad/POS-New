<?php
/**
  * @var \App\View\AppView $this
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Category Locale'), ['action' => 'edit', $categoryLocale->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Category Locale'), ['action' => 'delete', $categoryLocale->id], ['confirm' => __('Are you sure you want to delete # {0}?', $categoryLocale->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Category Locales'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Category Locale'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Categories'), ['controller' => 'Categories', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Category'), ['controller' => 'Categories', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="categoryLocales view large-9 medium-8 columns content">
    <h3><?= h($categoryLocale->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Category') ?></th>
            <td><?= $categoryLocale->has('category') ? $this->Html->link($categoryLocale->category->id, ['controller' => 'Categories', 'action' => 'view', $categoryLocale->category->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($categoryLocale->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Lang Code') ?></th>
            <td><?= h($categoryLocale->lang_code) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($categoryLocale->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($categoryLocale->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($categoryLocale->modified) ?></td>
        </tr>
    </table>
</div>
