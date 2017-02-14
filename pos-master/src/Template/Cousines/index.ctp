<?php
/**
  * @var \App\View\AppView $this
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Cousine'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Restaurants'), ['controller' => 'Restaurants', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Restaurant'), ['controller' => 'Restaurants', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Categories'), ['controller' => 'Categories', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Category'), ['controller' => 'Categories', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Cousine Locales'), ['controller' => 'CousineLocales', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Cousine Locale'), ['controller' => 'CousineLocales', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="cousines index large-9 medium-8 columns content">
    <h3><?= __('Cousines') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('restaurant_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('price') ?></th>
                <th scope="col"><?= $this->Paginator->sort('category_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('comb_num') ?></th>
                <th scope="col"><?= $this->Paginator->sort('status') ?></th>
                <th scope="col"><?= $this->Paginator->sort('is_tax') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                <th scope="col"><?= $this->Paginator->sort('modified') ?></th>
                <th scope="col"><?= $this->Paginator->sort('is_synced') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cousines as $cousine): ?>
            <tr>
                <td><?= $this->Number->format($cousine->id) ?></td>
                <td><?= $cousine->has('restaurant') ? $this->Html->link($cousine->restaurant->id, ['controller' => 'Restaurants', 'action' => 'view', $cousine->restaurant->id]) : '' ?></td>
                <td><?= $this->Number->format($cousine->price) ?></td>
                <td><?= $cousine->has('category') ? $this->Html->link($cousine->category->id, ['controller' => 'Categories', 'action' => 'view', $cousine->category->id]) : '' ?></td>
                <td><?= $this->Number->format($cousine->comb_num) ?></td>
                <td><?= h($cousine->status) ?></td>
                <td><?= h($cousine->is_tax) ?></td>
                <td><?= h($cousine->created) ?></td>
                <td><?= h($cousine->modified) ?></td>
                <td><?= h($cousine->is_synced) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $cousine->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $cousine->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $cousine->id], ['confirm' => __('Are you sure you want to delete # {0}?', $cousine->id)]) ?>
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
