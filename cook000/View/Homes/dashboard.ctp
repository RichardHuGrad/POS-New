<body>
    <header class="home-header text-center">
        <div class="container">
            <div class="home-logo"><a href="<?php echo $this->Html->url(array('controller'=>'homes','action'=>'dashboard')) ?>">
                <?php echo $this->Html->image("logo-home.jpg", array('alt' => "POS")); ?>
                </a>
            </div>
            <div class="logout"><a href="<?php echo $this->Html->url(array('controller'=>'homes','action'=>'logout')) ?>">Logout 登出</a></div>
        </div>
        <center>
            <?php echo $this->Html->image('logo.jpg', array('class' => 'img-responsive', 'alt' => 'POS', 'title' => 'POS')); ?>
        </center>
    </header>

    <div class="sublistingwrap clearfix text-center">
        <div class="container">
            <ul>
                <li class="clearfix">
                    <a href="<?php echo $this->Html->url(array('controller'=>'homes', 'action'=>'allorders')) ?>">
                        <div class="order-brn clearfix">
                            <span class="doc-order"><?php echo $this->Html->image('order-list.png', array('alt' => 'Order', 'title' => 'Order')); ?></span>
                            <span class="order-txt">Order 订购</span>
                        </div>
                    </a>
                </li>
                <li class="clearfix">
                    <a href="<?php echo $this->Html->url(array('controller'=>'homes', 'action'=>'cookings')) ?>">
                        <div class="inquery-brn clearfix">
                            <span class="doc-order"><?php echo $this->Html->image('cooking.png', array('alt' => 'Cooking', 'title' => 'Cooking')); ?></span>
                            <span class="inquiry-txt">Cooking 烹饪</span>
                        </div>
                    </a>
                </li>
                <li class="clearfix">
                    <a href="<?php echo $this->Html->url(array('controller'=>'homes', 'action'=>'inquiry')) ?>">
                        <div class="inquery-brn clearfix">
                            <span class="doc-order"><?php echo $this->Html->image('inquery-icon.png', array('alt' => 'Inquiry', 'title' => 'Inquiry')); ?></span>
                            <span class="inquiry-txt">Inquiry 查询</span>
                        </div>
                    </a>
                </li>
            </ul>
            <div class="table-colors">
                <div class="clearfix marginB15">
                    <div class="pull-left paidb"></div>
                    <div class="pull-left paid-txt">Paid 付费</div>
                </div>
                <div class="clearfix marginB15">
                    <div class="pull-left notpaid"></div>
                    <div class="pull-left paid-txt">Not Paid 未支付</div>
                </div>
                <div class="clearfix marginB15">
                    <div class="pull-left availableb"></div>
                    <div class="pull-left paid-txt">Available 可用的</div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="clearfix homepage">

            <?php echo $this->Session->flash(); ?>
            <div class="clearfix dine-box">
                <button class="dinebtn"><?php echo $this->Html->image('dine-icon.png', array('alt' => 'POS', 'title' => 'Dine')); ?> Dine in Table 您可以在表</button>
            </div>

            <div class="clearfix marginB30">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12 dine-wrap">
                        <ul>
                        	<?php
                        	$dine_table = @explode(",", $tables['Admin']['table_size']);
                        	for($i = 1; $i <= $tables['Admin']['no_of_tables']; $i++) {
                        	?>
	                        <li class="clearfix">
	                        	 <ul class="dropdown-menu">
                                    <div class="arrow"></div>
                                    <li <?php if(@$dinein_tables_status[$i] == 'P')echo 'class="disabled"';?>><a tabindex="-1" href="<?php if(@$dinein_tables_status[$i] <> 'P')echo $this->Html->url(array('controller'=>'homes', 'action'=>'order', 'table'=>$i, 'type'=>'D')); else echo "javascript:void(0)"; ?>">Order <br/>订购</a></li>

                                    <li <?php if(@$dinein_tables_status[$i] <> 'N' and @$dinein_tables_status[$i] <> 'V')echo 'class="disabled"';?>><a tabindex="-1" href="<?php if(@$dinein_tables_status[$i] == 'N' OR @$dinein_tables_status[$i] == 'V')echo $this->Html->url(array('controller'=>'homes', 'action'=>'pay', 'table'=>$i, 'type'=>'D')); else echo "javascript:void(0)";?>">Pay<br/>工资</a></li>
                                    <li class="dropdown-submenu <?php if(!@$dinein_tables_status[$i])echo 'disabled';?>">
                                        <a class="test" tabindex="-1" href="<?php if(@$dinein_tables_status[$i])echo $this->Html->url(array('controller'=>'homes', 'action'=>'changetable', 'table'=>$i, 'type'=>'D')); else echo "javascript:void(0)";?>">Change Table<br/>更改表</a>
                                    	<?php if(@$dinein_tables_status[$i]) {;?>
	                                        <ul class="dropdown-menu">
                                                <div class="customchangemenu clearfix">
	                                            <div class="left-arrow"></div>
                                                <div class="col-md-12 col-sm-12 col-xs-12 text-center timetable">DINE IN 在用餐</div>
                                                <?php for($t = 1; $t <= $tables['Admin']['no_of_tables']; $t++) {
                                                    if(!@$orders_no[$t]['D'] and $t <> $i){ ?>
	                                               <div class="col-md-6 col-sm-6 col-xs-6 text-center timetable"><a href="<?php echo $this->Html->url(array('controller'=>'homes', 'action'=>'move_order', 'table'=>$t, 'type'=>'D', 'order_no'=>@$orders_no[$i]['D']));?>"><?php echo $t; ?></a></div>
                                                <?php }}?>
                                                <div class="col-md-12 col-sm-12 col-xs-12 text-center timetable">TAKE OUT </div>
                                                <?php for($t = 1; $t <= TAKEOUT_TABLE; $t++) {
                                                if(!@$orders_no[$t]['T']){  ?>
                                                   <div class="col-md-6 col-sm-6 col-xs-6 text-center timetable"><a href="<?php echo $this->Html->url(array('controller'=>'homes', 'action'=>'move_order', 'table'=>$t, 'type'=>'T', 'order_no'=>@$orders_no[$i]['D']));?>"><?php echo $t; ?></a></div>
                                                <?php } }?>
                                                <div class="col-md-12 col-sm-12 col-xs-12 text-center timetable">Delivery 送餐</div>
                                                <?php for($t = 1; $t <= WAITING_TABLE; $t++) {
                                                if(!@$orders_no[$t]['W']){  ?>
                                                   <div class="col-md-6 col-sm-6 col-xs-6 text-center timetable"><a href="<?php echo $this->Html->url(array('controller'=>'homes', 'action'=>'move_order', 'table'=>$t, 'type'=>'W', 'order_no'=>@$orders_no[$i]['D']));?>"><?php echo $t; ?></a></div>
                                                <?php } }?>
                                                </div>
	                                        </ul>
                                        <?php }?>
                                    </li>

                                    <li <?php if(@$dinein_tables_status[$i] <> 'P')echo 'class="disabled"';?>><a tabindex="-1" href="<?php if(@$dinein_tables_status[$i] == 'P')echo $this->Html->url(array('controller'=>'homes', 'action'=>'makeavailable', 'table'=>$i, 'type'=>'D', 'order'=>@$orders_no[$i]['D'])); else echo "javascript:void(0)";?>">Make Avialable<br/>使有效</a></li>
	                        	</ul>
	                            <div class="<?php if(isset($dinein_tables_status[$i])) echo $colors[$dinein_tables_status[$i]]; else echo 'availablebwrap'; ?> clearfix  dropdown-toggle" data-toggle="dropdown">
	                                <div class="number-txt for-dine"><?php echo str_pad($i, 2, 0, STR_PAD_LEFT); ?></div>
                                   <?php
                                    if(!@$dinein_tables_status[$i]) {
                                        ?>
                                            <div class="txt16">&nbsp;</div>
                                        <?php
                                        }
                                    ?>
                                    <div class="txt16 <?php if(isset($dinein_tables_status[$i])) echo "whitecolor"; else echo "lightcolor"; ?>">
	                                	<?php
                                	 	if(!@$dinein_tables_status[$i]) 
	                                		echo "Available 可用的";
                                		else
                                			echo "Order 订购#: ".@$orders_no[$i]['D'];                                   	
	                                	?>
	                                </div>
                                    <?php if(@$dinein_tables_status[$i]) {  ?>
                                    <div class="txt16 <?php if(isset($dinein_tables_status[$i])) echo "whitecolor"; else echo "lightcolor"; ?>">Time 时间: <?php echo @$orders_time[$i]['D']?date("H:i:s", strtotime(@$orders_time[$i]['D'])):"" ?></div>
                                    <?php }
                                        ?>
	                                <div class="txt16 <?php if(isset($dinein_tables_status[$i])) echo "whitecolor"; else echo "lightcolor"; ?>">Seat 座位: <?php echo @$dine_table[$i-1] ?></div>

	                            </div>
	                        </li>
	                        <?php }?>
                        </ul>
                    </div>                    
                </div>
            </div>

            <div class="clearfix dine-box">
                <button class="dinebtn"><?php echo $this->Html->image('dine-icon.png', array('alt' => 'POS', 'title' => 'Dine')); ?>  Takeout Table 外卖表</button>
            </div>

            <div class="clearfix marginB30">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12 dine-wrap">
                        <ul>
                        	<?php
                        	$takeout_tables = @explode(",", $tables['Admin']['takeout_table_size']);
                        	for($i = 1; $i <= TAKEOUT_TABLE; $i++) {
                        	?>
	                            <li class="clearfix">
	                            	<ul class="dropdown-menu">
	                                    <div class="arrow"></div>
	                                    <li <?php if(@$takeway_tables_status[$i] == 'P')echo 'class="disabled"';?>><a tabindex="-1" href="<?php if(@$takeway_tables_status[$i] <> 'P')echo $this->Html->url(array('controller'=>'homes', 'action'=>'order', 'table'=>$i, 'type'=>'T')); else echo "javascript:void(0)"; ?>">Order<br>订购</a></li>
	                                    <li <?php if(@$takeway_tables_status[$i] <> 'N' and @$takeway_tables_status[$i] <> 'V')echo 'class="disabled"';?>><a tabindex="-1" href="<?php if(@$takeway_tables_status[$i] == 'N' OR @$takeway_tables_status[$i] == 'V')echo $this->Html->url(array('controller'=>'homes', 'action'=>'pay', 'table'=>$i, 'type'=>'T')); else echo "javascript:void(0)";?>">Pay<br/>工资</a></li>
	                                    <li class="dropdown-submenu <?php if(!@$takeway_tables_status[$i])echo 'disabled';?>">
	                                        <a class="test" tabindex="-1" href="<?php if(@$takeway_tables_status[$i])echo $this->Html->url(array('controller'=>'homes', 'action'=>'changetable', 'table'=>$i, 'type'=>'T')); else echo "javascript:void(0)";?>">Change Table<br/>更改表</a>
	                                    	<?php if(@$takeway_tables_status[$i]) {;?>
	                                         <ul class="dropdown-menu">
                                                <div class="customchangemenu clearfix">
                                                <div class="left-arrow"></div>
                                                <div class="col-md-12 col-sm-12 col-xs-12 text-center timetable">DINE IN 在用餐</div>
                                                <?php for($t = 1; $t <= $tables['Admin']['no_of_tables']; $t++) {
                                                    if(!@$orders_no[$t]['D']){ ?>
                                                   <div class="col-md-6 col-sm-6 col-xs-6 text-center timetable"><a href="<?php echo $this->Html->url(array('controller'=>'homes', 'action'=>'move_order', 'table'=>$t, 'type'=>'D', 'order_no'=>@$orders_no[$i]['T']));?>"><?php echo $t; ?></a></div>
                                                <?php }}?>
                                                <div class="col-md-12 col-sm-12 col-xs-12 text-center timetable">TAKE OUT 取出</div>
                                                <?php for($t = 1; $t <= TAKEOUT_TABLE; $t++) {
                                                if(!@$orders_no[$t]['T'] and $t <> $i){  ?>
                                                   <div class="col-md-6 col-sm-6 col-xs-6 text-center timetable"><a href="<?php echo $this->Html->url(array('controller'=>'homes', 'action'=>'move_order', 'table'=>$t, 'type'=>'T', 'order_no'=>@$orders_no[$i]['T']));?>"><?php echo $t; ?></a></div>
                                                <?php } }?>
                                                <div class="col-md-12 col-sm-12 col-xs-12 text-center timetable">Delivery 送餐</div>
                                                <?php for($t = 1; $t <= WAITING_TABLE; $t++) {
                                                if(!@$orders_no[$t]['W']){  ?>
                                                   <div class="col-md-6 col-sm-6 col-xs-6 text-center timetable"><a href="<?php echo $this->Html->url(array('controller'=>'homes', 'action'=>'move_order', 'table'=>$t, 'type'=>'W', 'order_no'=>@$orders_no[$i]['T']));?>"><?php echo $t; ?></a></div>
                                                <?php } }?>
                                            </div>
                                            </ul>
	                                        <?php }?>
	                                    </li>
	                                    <li <?php if(@$takeway_tables_status[$i] <> 'P')echo 'class="disabled"';?>><a tabindex="-1" href="<?php if(@$takeway_tables_status[$i] == 'P')echo $this->Html->url(array('controller'=>'homes', 'action'=>'makeavailable', 'table'=>$i, 'type'=>'T', 'order'=>@$orders_no[$i]['T'])); else echo "javascript:void(0)";?>">Make Avialable<br/>使有效</a></li>
		                        	</ul>
	                                <div class="<?php if(isset($takeway_tables_status[$i])) echo $colors[$takeway_tables_status[$i]]; else echo 'availablebwrap'; ?> clearfix  dropdown-toggle" data-toggle="dropdown">
		                                <!-- <div class="takeout-txt">Takeout</div> -->
                                            <div class="number-txt"><?php echo str_pad($i, 2, 0, STR_PAD_LEFT); ?></div>
                                            <?php 
                                            if(@$takeway_tables_status[$i]) {
                                            ?>
			                                <div class="txt16 <?php if(isset($takeway_tables_status[$i])) echo "whitecolor"; else echo "lightcolor"; ?>">
			                                	<?php
                                                    echo "Order 订购#: ".@$orders_no[$i]['T'];                            	
			                                	?>
			                                </div>
			                                <div class="txt16 <?php if(isset($takeway_tables_status[$i])) echo "whitecolor"; else echo "lightcolor"; ?>">Time 时间: <?php echo @$orders_time[$i]['T']?date("H:i:s", strtotime(@$orders_time[$i]['T'])):"" ?></div>
		                            	
                                            <?php }?>
		                            </div>
	                            </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>


            <div class="clearfix dine-box">
                <button class="dinebtn"><?php echo $this->Html->image('dine-icon.png', array('alt' => 'POS', 'title' => 'Dine')); ?> Delivery Table 送餐表</button>
            </div>

            <div class="clearfix marginB30">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12 dine-wrap">
                        <ul>
                        	<?php
                        	$wait_table = @explode(",", $tables['Admin']['waiting_table_size']);
                        	for($i = 1; $i <= WAITING_TABLE; $i++) {
                        	?>
	                            <li class="clearfix">
	                        	 <ul class="dropdown-menu">
                                    <div class="arrow"></div>
                                    <li <?php if(@$waiting_tables_status[$i] == 'P')echo 'class="disabled"';?>><a tabindex="-1" href="<?php if(@$waiting_tables_status[$i] <> 'P')echo $this->Html->url(array('controller'=>'homes', 'action'=>'order', 'table'=>$i, 'type'=>'W')); else echo "javascript:void(0)"; ?>">Order<br/>订购</a></li>
                                    <li <?php if(@$waiting_tables_status[$i] <> 'N' OR @$waiting_tables_status[$i] <> 'V')echo 'class="disabled"';?>><a tabindex="-1" href="<?php if(@$waiting_tables_status[$i] == 'N' OR @$waiting_tables_status[$i] == 'V')echo $this->Html->url(array('controller'=>'homes', 'action'=>'pay', 'table'=>$i, 'type'=>'W')); else echo "javascript:void(0)";?>">Pay<br/>工资</a></li>
                                    <li class="dropdown-submenu <?php if(!@$waiting_tables_status[$i])echo 'disabled';?>">
                                        <a class="test" tabindex="-1" href="<?php if(@$waiting_tables_status[$i])echo $this->Html->url(array('controller'=>'homes', 'action'=>'changetable', 'table'=>$i, 'type'=>'W')); else echo "javascript:void(0)";?>">Change Table</br/>更改表</a>
                                    	<?php if(@$waiting_tables_status[$i]) {;?>
	                                        <ul class="dropdown-menu">
                                                <div class="customchangemenu clearfix">
                                                <div class="left-arrow"></div>
                                                <div class="col-md-12 col-sm-12 col-xs-12 text-center timetable"> DINE IN 在用餐</div>
                                                <?php for($t = 1; $t <= $tables['Admin']['no_of_tables']; $t++) {
                                                    if(!@$orders_no[$t]['D']){ ?>
                                                   <div class="col-md-6 col-sm-6 col-xs-6 text-center timetable"><a href="<?php echo $this->Html->url(array('controller'=>'homes', 'action'=>'move_order', 'table'=>$t, 'type'=>'D', 'order_no'=>@$orders_no[$i]['W']));?>"><?php echo $t; ?></a></div>
                                                <?php }}?>
                                                <div class="col-md-12 col-sm-12 col-xs-12 text-center timetable">TAKE OUT 取出</div>
                                                <?php for($t = 1; $t <= TAKEOUT_TABLE; $t++) {
                                                if(!@$orders_no[$t]['T']){  ?>
                                                   <div class="col-md-6 col-sm-6 col-xs-6 text-center timetable"><a href="<?php echo $this->Html->url(array('controller'=>'homes', 'action'=>'move_order', 'table'=>$t, 'type'=>'T', 'order_no'=>@$orders_no[$i]['W']));?>"><?php echo $t; ?></a></div>
                                                <?php } }?>
                                                <div class="col-md-12 col-sm-12 col-xs-12 text-center timetable">Delivery 送餐</div>
                                                <?php for($t = 1; $t <= WAITING_TABLE; $t++) {
                                                if(!@$orders_no[$t]['W'] and $t <> $i){  ?>
                                                   <div class="col-md-6 col-sm-6 col-xs-6 text-center timetable"><a href="<?php echo $this->Html->url(array('controller'=>'homes', 'action'=>'move_order', 'table'=>$t, 'type'=>'W', 'order_no'=>@$orders_no[$i]['W']));?>"><?php echo $t; ?></a></div>
                                                <?php } }?>
                                            </div>
                                            </ul>
                                        <?php }?>
                                    </li>
                                    <li <?php if(@$waiting_tables_status[$i] <> 'P')echo 'class="disabled"';?>><a tabindex="-1" href="<?php if(@$waiting_tables_status[$i] == 'P')echo $this->Html->url(array('controller'=>'homes', 'action'=>'makeavailable', 'table'=>$i, 'type'=>'W', 'order'=>@$orders_no[$i]['W'])); else echo "javascript:void(0)";?>">Make Avialable<br/>使有效</a></li>
	                        	</ul>
	                            <div class="<?php if(isset($waiting_tables_status[$i])) echo $colors[$waiting_tables_status[$i]]; else echo 'availablebwrap'; ?> clearfix  dropdown-toggle" data-toggle="dropdown">
	                                <div class="number-txt"><?php echo str_pad($i, 2, 0, STR_PAD_LEFT); ?></div>
	                                <div class="txt16 <?php if(isset($waiting_tables_status[$i])) echo "whitecolor"; else echo "lightcolor"; ?>">
	                                	<?php
	                                	if(!@$waiting_tables_status[$i]) 
	                                		echo "Available 可用的";
                                		else
                                            echo "Order 订购#: ".@$orders_no[$i]['W'];                       	
	                                	?>
	                                </div>
	                                <div class="txt16 <?php if(isset($waiting_tables_status[$i])) echo "whitecolor"; else echo "lightcolor"; ?>">Seat 座位: <?php echo @$dine_table[$i-1] ?></div>
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
echo $this->Html->script(array('jquery.min.js', 'bootstrap.min.js'));
echo $this->fetch('script');
?>
<script>
	$(document).ready(function () {
	    $('.dropdown-submenu a.test').on("click", function (e) {
	        $(this).next('ul').toggle();
	        e.stopPropagation();
	        e.preventDefault();
	    });
	});
</script>
</body>