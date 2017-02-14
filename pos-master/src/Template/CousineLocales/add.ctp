<?php
/**
  * @var \App\View\AppView $this
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Cousine Locales'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Cousines'), ['controller' => 'Cousines', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Cousine'), ['controller' => 'Cousines', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="cousineLocales form large-9 medium-8 columns content">
    <?= $this->Form->create($cousineLocale) ?>
    <fieldset>
        <legend><?= __('Add Cousine Locale') ?></legend>
        <?php
            echo $this->Form->input('cousine_id', ['options' => $cousines]);
            echo $this->Form->input('name');
            echo $this->Form->input('lang_code');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
