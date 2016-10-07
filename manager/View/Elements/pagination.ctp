<div class="pull-left">
    <?php
    echo $this->Paginator->first('<< First') . '&nbsp;&nbsp;';
    echo $this->Paginator->numbers(array('first' => 2, 'last' => 2));
    echo '&nbsp;&nbsp;' . $this->Paginator->last('Last >>');
    ?>
</div>
<div class="pull-right">
    <?php echo $this->Paginator->counter('Page {:page} of {:pages}'); ?>
</div>