<?php
/**
  * @var \App\View\AppView $this
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Category Locale'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Categories'), ['controller' => 'Categories', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Category'), ['controller' => 'Categories', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="categoryLocales index large-9 medium-8 columns content">
    <h3><?= __('Category Locales') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('category_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('name') ?></th>
                <th scope="col"><?= $this->Paginator->sort('lang_code') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                <th scope="col"><?= $this->Paginator->sort('modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categoryLocales as $categoryLocale): ?>
            <tr>
                <td><?= $this->Number->format($categoryLocale->id) ?></td>
                <td><?= $categoryLocale->has('category') ? $this->Html->link($categoryLocale->category->id, ['controller' => 'Categories', 'action' => 'view', $categoryLocale->category->id]) : '' ?></td>
                <td><?= h($categoryLocale->name) ?></td>
                <td><?= h($categoryLocale->lang_code) ?></td>
                <td><?= h($categoryLocale->created) ?></td>
                <td><?= h($categoryLocale->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $categoryLocale->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $categoryLocale->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $categoryLocale->id], ['confirm' => __('Are you sure you want to delete # {0}?', $categoryLocale->id)]) ?>
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
