<?php
/**
  * @var \App\View\AppView $this
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Cousine Locale'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Cousines'), ['controller' => 'Cousines', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Cousine'), ['controller' => 'Cousines', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="cousineLocales index large-9 medium-8 columns content">
    <h3><?= __('Cousine Locales') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('cousine_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('name') ?></th>
                <th scope="col"><?= $this->Paginator->sort('lang_code') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                <th scope="col"><?= $this->Paginator->sort('modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cousineLocales as $cousineLocale): ?>
            <tr>
                <td><?= $this->Number->format($cousineLocale->id) ?></td>
                <td><?= $cousineLocale->has('cousine') ? $this->Html->link($cousineLocale->cousine->id, ['controller' => 'Cousines', 'action' => 'view', $cousineLocale->cousine->id]) : '' ?></td>
                <td><?= h($cousineLocale->name) ?></td>
                <td><?= h($cousineLocale->lang_code) ?></td>
                <td><?= $this->Number->format($cousineLocale->created) ?></td>
                <td><?= $this->Number->format($cousineLocale->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $cousineLocale->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $cousineLocale->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $cousineLocale->id], ['confirm' => __('Are you sure you want to delete # {0}?', $cousineLocale->id)]) ?>
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
