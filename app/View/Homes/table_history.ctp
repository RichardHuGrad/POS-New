<header >
    <?php echo $this->element('navbar'); ?>
</header>
<div class="container">
	<?php /*print_r($cashier_detail); echo'<br><br>'; var_dump($Order_detail); echo'<br><br>';var_dump($Order_detail); echo'<br><br>'; echo $this->Paginator->counter();
		echo "<br><br>";*/
		?>
	<div class="paginator col-xs-12">
		<?php
		echo $this->Paginator->prev('<< Prev', null, null, array('class'=>'disabled'));
		echo "&nbsp&nbsp"; ?>
		<span>
			<?php echo $this->Paginator->counter();?>
		</span>
		<?php
		echo "&nbsp&nbsp";
		echo $this->Paginator->next('Next >>', null, null, array('class'=>'disabled'));
		?>
	</div>
</div>

<div class="container">
	<div class="clearfix cartwrap-wrap"></div>

    <div class="order-wrap">
    <?php echo $this->Session->flash(); ?>
        <div class="col-md-12 col-sm-12 col-xs-12 order-left">
        
        <?php 
           if($order_type=='D')
             echo "<h2>Table 桌 [[Dinein]]# $table_no, @ $today</h2>";
           else if($order_type=='T')
        	   echo "<h2>Table 桌 [[Takeout]]# $table_no, @ $today</h2>";
        ?>

            <div class="order-summary">
                <div class="order-summary-indent clearfix">
                    <ul>
                      <?php foreach ($Order_detail as $order) { ?>
                      <?php     $items_number = count($order['OrderItem']);  ?>
                            <li class="clearfix">
                                <div class="row txt16">
                                    <div class="col-md-2 col-sm-2 col-xs-2">
                                        <div class="pull-left titlebox1">
                                            <?php echo $order['Order']['created']; ?>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xs-2">
                                        <div class="pull-left titlebox1">
                                            <?php echo $order['Order']['order_no']; ?>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xs-2">
                                        <div class="pull-left titlebox1">
                                            <?php echo number_format($order['Order']['subtotal'], 2); ?>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xs-2">
                                        <div class="pull-left titlebox1">
                                            <?php echo number_format($order['Order']['tax_amount'], 2); ?>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xs-2">
                                        <div class="pull-left titlebox1">
                                            <?php echo number_format($order['Order']['total'], 2); ?>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xs-2">
                                        <div class="pull-left titlebox1">
                                            <a tabindex="-1" href="<?php echo $this->Html->url(array('controller'=>'homes', 'action'=>'tableHisdetail', 'table_no'=>$table_no, 'order_id'=>$order['Order']['id'],'order_type'=>$order_type)); ?>">Detail</a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
echo $this->Html->script(array('jquery.min.js', 'bootstrap.min.js', 'jQuery.print.js','md5.js'));
echo $this->fetch('script');
?>
