<?php
/**
  * @var \App\View\AppView $this
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Cousine'), ['action' => 'edit', $cousine->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Cousine'), ['action' => 'delete', $cousine->id], ['confirm' => __('Are you sure you want to delete # {0}?', $cousine->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Cousines'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Cousine'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Restaurants'), ['controller' => 'Restaurants', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Restaurant'), ['controller' => 'Restaurants', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Categories'), ['controller' => 'Categories', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Category'), ['controller' => 'Categories', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Cousine Locales'), ['controller' => 'CousineLocales', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Cousine Locale'), ['controller' => 'CousineLocales', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="cousines view large-9 medium-8 columns content">
    <h3><?= h($cousine->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Restaurant') ?></th>
            <td><?= $cousine->has('restaurant') ? $this->Html->link($cousine->restaurant->id, ['controller' => 'Restaurants', 'action' => 'view', $cousine->restaurant->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Category') ?></th>
            <td><?= $cousine->has('category') ? $this->Html->link($cousine->category->id, ['controller' => 'Categories', 'action' => 'view', $cousine->category->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Status') ?></th>
            <td><?= h($cousine->status) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Is Tax') ?></th>
            <td><?= h($cousine->is_tax) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Is Synced') ?></th>
            <td><?= h($cousine->is_synced) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($cousine->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Price') ?></th>
            <td><?= $this->Number->format($cousine->price) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Comb Num') ?></th>
            <td><?= $this->Number->format($cousine->comb_num) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($cousine->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($cousine->modified) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Cousine Locales') ?></h4>
        <?php if (!empty($cousine->cousine_locales)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Cousine Id') ?></th>
                <th scope="col"><?= __('Name') ?></th>
                <th scope="col"><?= __('Lang Code') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($cousine->cousine_locales as $cousineLocales): ?>
            <tr>
                <td><?= h($cousineLocales->id) ?></td>
                <td><?= h($cousineLocales->cousine_id) ?></td>
                <td><?= h($cousineLocales->name) ?></td>
                <td><?= h($cousineLocales->lang_code) ?></td>
                <td><?= h($cousineLocales->created) ?></td>
                <td><?= h($cousineLocales->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'CousineLocales', 'action' => 'view', $cousineLocales->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'CousineLocales', 'action' => 'edit', $cousineLocales->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'CousineLocales', 'action' => 'delete', $cousineLocales->id], ['confirm' => __('Are you sure you want to delete # {0}?', $cousineLocales->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
