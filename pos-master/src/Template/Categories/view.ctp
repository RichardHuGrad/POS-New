<?php
/**
  * @var \App\View\AppView $this
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Category'), ['action' => 'edit', $category->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Category'), ['action' => 'delete', $category->id], ['confirm' => __('Are you sure you want to delete # {0}?', $category->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Categories'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Category'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Restaurants'), ['controller' => 'Restaurants', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Restaurant'), ['controller' => 'Restaurants', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Category Locales'), ['controller' => 'CategoryLocales', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Category Locale'), ['controller' => 'CategoryLocales', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Cousines'), ['controller' => 'Cousines', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Cousine'), ['controller' => 'Cousines', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="categories view large-9 medium-8 columns content">
    <h3><?= h($category->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Restaurant') ?></th>
            <td><?= $category->has('restaurant') ? $this->Html->link($category->restaurant->id, ['controller' => 'Restaurants', 'action' => 'view', $category->restaurant->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Status') ?></th>
            <td><?= h($category->status) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Printer') ?></th>
            <td><?= h($category->printer) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Is Synced') ?></th>
            <td><?= h($category->is_synced) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($category->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($category->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($category->modified) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Category Locales') ?></h4>
        <?php if (!empty($category->category_locales)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Category Id') ?></th>
                <th scope="col"><?= __('Name') ?></th>
                <th scope="col"><?= __('Lang Code') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($category->category_locales as $categoryLocales): ?>
            <tr>
                <td><?= h($categoryLocales->id) ?></td>
                <td><?= h($categoryLocales->category_id) ?></td>
                <td><?= h($categoryLocales->name) ?></td>
                <td><?= h($categoryLocales->lang_code) ?></td>
                <td><?= h($categoryLocales->created) ?></td>
                <td><?= h($categoryLocales->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'CategoryLocales', 'action' => 'view', $categoryLocales->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'CategoryLocales', 'action' => 'edit', $categoryLocales->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'CategoryLocales', 'action' => 'delete', $categoryLocales->id], ['confirm' => __('Are you sure you want to delete # {0}?', $categoryLocales->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Cousines') ?></h4>
        <?php if (!empty($category->cousines)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Restaurant Id') ?></th>
                <th scope="col"><?= __('Price') ?></th>
                <th scope="col"><?= __('Category Id') ?></th>
                <th scope="col"><?= __('Comb Num') ?></th>
                <th scope="col"><?= __('Status') ?></th>
                <th scope="col"><?= __('Is Tax') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col"><?= __('Is Synced') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($category->cousines as $cousines): ?>
            <tr>
                <td><?= h($cousines->id) ?></td>
                <td><?= h($cousines->restaurant_id) ?></td>
                <td><?= h($cousines->price) ?></td>
                <td><?= h($cousines->category_id) ?></td>
                <td><?= h($cousines->comb_num) ?></td>
                <td><?= h($cousines->status) ?></td>
                <td><?= h($cousines->is_tax) ?></td>
                <td><?= h($cousines->created) ?></td>
                <td><?= h($cousines->modified) ?></td>
                <td><?= h($cousines->is_synced) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Cousines', 'action' => 'view', $cousines->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Cousines', 'action' => 'edit', $cousines->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Cousines', 'action' => 'delete', $cousines->id], ['confirm' => __('Are you sure you want to delete # {0}?', $cousines->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
