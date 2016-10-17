<body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script>
        $(document).ready(function () {
            // setTimeout(function(){
            //    window.location.reload(1);
            // }, 30000);

            if ($(window).width() <= 780) {
                $(".dine_ul, .dine_li").removeAttr("style")
                // $("").removeAttr("style");
            }
        });
    </script>
    <header class="home-header text-center">        
        <div class="home-logo">
            <a href="<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'dashboard')) ?>">
                <?php echo $this->Html->image("logo-home.jpg", array('alt' => "POS", 'class' => 'logo-img')); ?>
            </a>					
            <div class="HomeText text-left">
                <a href="<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'index')) ?>">Home 主页</a>
                <a href="javascript:void(0)" onclick="window.history.back()">Back 返回</a>
            </div>					
        </div>
        <div class="logout"><a href="<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'logout')) ?>">Logout 登出</a></div>

        <div class="sublistingwrap clearfix text-center">
            <div class="container">
                <div class="col-md-8 clearfix col-sm-6 col-sm-offset-2 col-md-offset-0 dashboard-btn-list">

                    <ul>


                        <li class="clearfix">
                            <a href="<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'inquiry')) ?>">
                                <div class="inquery-brn clearfix">
                                    <span class="doc-order"><?php echo $this->Html->image('inquery-icon.png', array('alt' => 'Inquiry', 'title' => 'Inquiry')); ?></span>
                                    <span class="inquiry-txt">Inquiry 查询</span>
                                </div>
                            </a>
                        </li>

                        <li class="clearfix">
                            <a href="<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'cookings')) ?>">
                                <div class="inquery-brn clearfix">
                                    <span class="doc-order"><?php echo $this->Html->image('cooking.png', array('alt' => 'Cooking', 'title' => 'Cooking')); ?></span>
                                    <span class="inquiry-txt">Cooking 烹饪</span>
                                </div>
                            </a>
                        </li>

                        <li class="clearfix">
                            <a href="<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'allorders')) ?>">
                                <div class="inquery-brn clearfix">
                                    <span class="doc-order"><?php echo $this->Html->image('order-list.png', array('alt' => 'Order', 'title' => 'Order')); ?></span>
                                    <span class="order-txt">Order 点餐</span>
                                </div>
                            </a>
                        </li>


                    </ul>

                </div>




                <div class="col-md-4 clearfix col-sm-4">
                    <div class="table-colors">
                        <!-- <div class="clearfix marginB5">
                            <div class="pull-left paidb"></div>
                            <div class="pull-left paid-txt">Paid 已付费</div>
                        </div> -->
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
        </div>	
    </header>
        <div class="clearfix homepage">
            <?php echo $this->Session->flash(); ?>
            <!--<div class="clearfix dine-box">
			<div class="col-md-12">
                <button class="dinebtn"><?php //echo $this->Html->image('dine-icon.png', array('alt' => 'POS', 'title' => 'Dine')); ?> Dine in Table 堂食</button>
            </div>
			</div>-->

            <div class="clearfix marginB30">                
                    <div class="col-md-12 col-sm-12 col-xs-12 dine-wrap">
                        <ul class="dine_ul" style="height:auto; overflow:auto; min-height: 480px; padding:0">
                        	<?php
                            $dine_table = @explode(",", $tables['Admin']['table_size']);
                            $dine_table_order = @$tables['Admin']['table_order']?@json_decode($tables['Admin']['table_order'], true):array();
                        	for($i = 1; $i <= $tables['Admin']['no_of_tables']; $i++) {
                        	?>
	                        <li class="clearfix dine_li" style="<?php echo @$dine_table_order[$i-1] ?>">
	                        	 <ul class="dropdown-menu dine-tables">
                                    <div class="arrow"></div>
                                    <li <?php if(@$dinein_tables_status[$i] == 'P')echo 'class="disabled"';?>><a tabindex="-1" href="<?php if(@$dinein_tables_status[$i] <> 'P')echo $this->Html->url(array('controller'=>'homes', 'action'=>'order', 'table'=>$i, 'type'=>'D')); else echo "javascript:void(0)"; ?>">Order <br/>点餐</a></li>

                                    <li class="dropdown-submenu <?php if(!@$dinein_tables_status[$i])echo 'disabled';?>">
                                        <a class="test" tabindex="-1" href="<?php if(@$dinein_tables_status[$i])echo $this->Html->url(array('controller'=>'homes', 'action'=>'changetable', 'table'=>$i, 'type'=>'D')); else echo "javascript:void(0)";?>">Change Table<br/>换桌</a>
                                    	<?php if(@$dinein_tables_status[$i]) {;?>
	                                        <ul class="dropdown-menu">
                                                <div class="customchangemenu clearfix">
	                                            <div class="left-arrow"></div>
                                                <div class="col-md-12 col-sm-12 col-xs-12 text-center timetable">DINE IN 堂食</div>
                                                <?php
                                                for ($t = 1; $t <= $tables['Admin']['no_of_tables']; $t++) {
                                                    if (!@$orders_no[$t]['D'] and $t <> $i) {
                                                        ?>
                                                        <div class="col-md-6 col-sm-6 col-xs-6 text-center timetable"><a href="<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'move_order', 'table' => $t, 'type' => 'D', 'order_no' => @$orders_no[$i]['D'])); ?>"><?php echo $t; ?></a></div>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                                <div class="col-md-12 col-sm-12 col-xs-12 text-center timetable">TAKE OUT 外卖 </div>
                                                <?php
                                                for ($t = 1; $t <= $tables['Admin']['no_of_takeout_tables']; $t++) {
                                                    if (!@$orders_no[$t]['T']) {
                                                        ?>
                                                        <div class="col-md-6 col-sm-6 col-xs-6 text-center timetable"><a href="<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'move_order', 'table' => $t, 'type' => 'T', 'order_no' => @$orders_no[$i]['D'])); ?>"><?php echo $t; ?></a></div>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                                <div class="col-md-12 col-sm-12 col-xs-12 text-center timetable">WAITING 等候</div>
                                                <?php for($t = 1; $t <= $tables['Admin']['no_of_waiting_tables']; $t++) {
                                                if(!@$orders_no[$t]['W']){  ?>
                                                   <div class="col-md-6 col-sm-6 col-xs-6 text-center timetable"><a href="<?php echo $this->Html->url(array('controller'=>'homes', 'action'=>'move_order', 'table'=>$t, 'type'=>'W', 'order_no'=>@$orders_no[$i]['D']));?>"><?php echo $t; ?></a></div>
                                                <?php } }?>
                                                </div>
	                                        </ul>
                                        <?php }?>
                                    </li>
                                    <li <?php if(@$dinein_tables_status[$i] <> 'N' and @$dinein_tables_status[$i] <> 'V')echo 'class="disabled"';?>><a tabindex="-1" href="<?php if(@$dinein_tables_status[$i] == 'N' OR @$dinein_tables_status[$i] == 'V')echo $this->Html->url(array('controller'=>'homes', 'action'=>'pay', 'table'=>$i, 'type'=>'D')); else echo "javascript:void(0)";?>">Pay<br/>结账</a></li>

                                    <li <?php if(@$dinein_tables_status[$i] == 'A')echo 'class="disabled"';?>><a tabindex="-1" href="<?php if(@$dinein_tables_status[$i] <> 'A')echo $this->Html->url(array('controller'=>'homes', 'action'=>'makeavailable', 'table'=>$i, 'type'=>'D', 'order'=>@$orders_no[$i]['D'])); else echo "javascript:void(0)";?>">Make Available<br/>变空桌</a></li>

                                    <!-- Modified by Yishou Liao @ Oct 13 2016. -->
                                <li class="dropdown-submenu" <?php if (@$dinein_tables_status[$i] <> 'N' and @ $dinein_tables_status[$i] <> 'V') echo 'class="disabled"'; ?>>
                                    <a class="test" tabindex="-1" href="<?php
                                    if (@$dinein_tables_status[$i])
                                        echo $this->Html->url(array('controller' => 'homes', 'action' => 'changetable', 'table' => $i, 'type' => 'D'));
                                    else
                                        echo "javascript:void(0)";
                                    ?>">Merge Bill<br />合单</a>
                                       <?php
                                       if (@$dinein_tables_status[$i]) {
                                           ;
                                           ?>
                                        <ul class="dropdown-menu">
                                            <div class="customchangemenu clearfix">
                                                <div class="left-arrow"></div>
                                                <div class="col-md-12 col-sm-12 col-xs-12 text-center timetable">Merge Bill 合单</div>
                                                <?php
                                                $dinein_tables_keys = array_keys($dinein_tables_status);
                                                for ($t = 0; $t < count(@$dinein_tables_status); $t++) {
                                                    if (@$dinein_tables_status[$dinein_tables_keys[$t]] == "N" && $dinein_tables_keys[$t] != $i) {
                                                        ?>
                                                        <div class="col-md-6 col-sm-6 col-xs-6 text-center timetable"><input type="checkbox" value = "<?php echo $dinein_tables_keys[$t]; ?>" id="mergetable[]" name= "mergetable[]"> <?php echo $dinein_tables_keys[$t]; ?></div>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                                <input type="button" onclick="mergebill(<?php echo $i ?>);" name="mergebill" id="mergebill" value="Okay">
                                            </div>
                                        </ul>
                                    <?php } ?>
                                </li>

                                <li <?php if (@$dinein_tables_status[$i] <> 'N' and @ $dinein_tables_status[$i] <> 'V') echo 'class="disabled"'; ?>><a tabindex="-1" href="<?php
                                    if (@$dinein_tables_status[$i] <> 'A')
                                        echo $this->Html->url(array('controller' => 'homes', 'action' => 'split', 'table' => $i, 'type' => 'D', 'order' => @$orders_no[$i]['D']));
                                    else
                                        echo "javascript:void(0)";
                                    ?>">Split Bill<br />分单</a></li>
                                <!-- End. -->
                                
                                    <li><a tabindex="-1" href="<?php echo $this->Html->url(array('controller'=>'homes', 'action'=>'tableHistory', 'table_no'=>$i)); ?>">History</a></li>
	                        	</ul>
	                            <div class="<?php if(isset($dinein_tables_status[$i])) echo $colors[$dinein_tables_status[$i]]; else echo 'availablebwrap'; ?> clearfix  dropdown-toggle" data-toggle="dropdown">
	                                <div class="number-txt for-dine">堂食<?php echo str_pad($i, 2, 0, STR_PAD_LEFT); ?></div>
                                   
                                    <div class="order_no_box <?php if(isset($dinein_tables_status[$i])) echo "whitecolor"; else echo "lightcolor"; ?>">
	                                	<?php
                                	 	if(!@$dinein_tables_status[$i]) 
	                                		echo "&nbsp;";
                                		else
                                			echo @$orders_no[$i]['D'];
	                                	?>
	                                </div>
	                                <div class="txt12 text-center <?php if(isset($dinein_tables_status[$i])) echo "whitecolor"; else echo "lightcolor"; ?>"><?php if(@$dinein_tables_status[$i]) {  ?> <?php echo @$orders_time[$i]['D']?date("H:i", strtotime(@$orders_time[$i]['D'])):"" ?><?php }?>
	                            </div>
	                        </li>
	                        <?php }?>
                        </ul>
                    </div>                    
                
            </div>

        <div class="clearfix dine-box">
            <div class="col-md-12">
                <button class="dinebtn"><?php echo $this->Html->image('dine-icon.png', array('alt' => 'POS', 'title' => 'Dine')); ?>  Takeout Table 外卖桌</button>
            </div>
        </div>

        <div class="clearfix marginB30">

            <div class="col-md-12 col-sm-12 col-xs-12 dine-wrap">
                <ul>
                    <?php
                    $takeout_tables = @explode(",", $tables['Admin']['takeout_table_size']);
                    for ($i = 1; $i <= $tables['Admin']['no_of_takeout_tables']; $i++) {
                        ?>
                        <li class="clearfix">
                            <ul class="dropdown-menu">
                                <div class="arrow"></div>
                                <li <?php if (@$takeway_tables_status[$i] == 'P') echo 'class="disabled"'; ?>><a tabindex="-1" href="<?php
                                    if (@$takeway_tables_status[$i] <> 'P')
                                        echo $this->Html->url(array('controller' => 'homes', 'action' => 'order', 'table' => $i, 'type' => 'T'));
                                    else
                                        echo "javascript:void(0)";
                                    ?>">Order<br>点餐</a></li>
                                <li class="dropdown-submenu <?php if (!@$takeway_tables_status[$i]) echo 'disabled'; ?>">
                                    <a class="test" tabindex="-1" href="<?php
                                    if (@$takeway_tables_status[$i])
                                        echo $this->Html->url(array('controller' => 'homes', 'action' => 'changetable', 'table' => $i, 'type' => 'T'));
                                    else
                                        echo "javascript:void(0)";
                                    ?>">Change Table<br/>换桌</a>
                                       <?php
                                       if (@$takeway_tables_status[$i]) {
                                           ;
                                           ?>
                                        <ul class="dropdown-menu">
                                            <div class="customchangemenu clearfix">
                                                <div class="left-arrow"></div>
                                                <div class="col-md-12 col-sm-12 col-xs-12 text-center timetable">DINE IN 堂食</div>
                                                <?php
                                                for ($t = 1; $t <= $tables['Admin']['no_of_tables']; $t++) {
                                                    if (!@$orders_no[$t]['D']) {
                                                        ?>
                                                        <div class="col-md-6 col-sm-6 col-xs-6 text-center timetable"><a href="<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'move_order', 'table' => $t, 'type' => 'D', 'order_no' => @$orders_no[$i]['T'])); ?>"><?php echo $t; ?></a></div>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                                <div class="col-md-12 col-sm-12 col-xs-12 text-center timetable">TAKE OUT 外卖</div>
                                                <?php
                                                for ($t = 1; $t <= $tables['Admin']['no_of_takeout_tables']; $t++) {
                                                    if (!@$orders_no[$t]['T'] and $t <> $i) {
                                                        ?>
                                                        <div class="col-md-6 col-sm-6 col-xs-6 text-center timetable"><a href="<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'move_order', 'table' => $t, 'type' => 'T', 'order_no' => @$orders_no[$i]['T'])); ?>"><?php echo $t; ?></a></div>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                                <div class="col-md-12 col-sm-12 col-xs-12 text-center timetable">WAITING 等候</div>
                                                <?php
                                                for ($t = 1; $t <= $tables['Admin']['no_of_waiting_tables']; $t++) {
                                                    if (!@$orders_no[$t]['W']) {
                                                        ?>
                                                        <div class="col-md-6 col-sm-6 col-xs-6 text-center timetable"><a href="<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'move_order', 'table' => $t, 'type' => 'W', 'order_no' => @$orders_no[$i]['T'])); ?>"><?php echo $t; ?></a></div>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </div>
                                            </ul>
	                                        <?php }?>
	                                    </li>
                                        <li <?php if(@$takeway_tables_status[$i] <> 'N' and @$takeway_tables_status[$i] <> 'V')echo 'class="disabled"';?>><a tabindex="-1" href="<?php if(@$takeway_tables_status[$i] == 'N' OR @$takeway_tables_status[$i] == 'V')echo $this->Html->url(array('controller'=>'homes', 'action'=>'pay', 'table'=>$i, 'type'=>'T')); else echo "javascript:void(0)";?>">Pay<br/>结账</a></li>
	                                    <li <?php if(@$takeway_tables_status[$i] <> 'P')echo 'class="disabled"';?>><a tabindex="-1" href="<?php if(@$takeway_tables_status[$i] == 'P')echo $this->Html->url(array('controller'=>'homes', 'action'=>'makeavailable', 'table'=>$i, 'type'=>'T', 'order'=>@$orders_no[$i]['T'])); else echo "javascript:void(0)";?>">Make Available<br/>变空桌</a></li>
		                        	</ul>
	                                <div class="<?php if(isset($takeway_tables_status[$i])) echo $colors[$takeway_tables_status[$i]]; else echo 'availablebwrap'; ?> clearfix  dropdown-toggle" data-toggle="dropdown">
		                                <!-- <div class="takeout-txt">Takeout</div> -->
                                            <div class="number-txt for-dine">外卖<?php echo str_pad($i, 2, 0, STR_PAD_LEFT); ?></div>
                                            <?php 
                                            if(@$takeway_tables_status[$i]) {
                                            ?>
			                                <div class="order_no_box <?php if(isset($takeway_tables_status[$i])) echo "whitecolor"; else echo "lightcolor"; ?>">
			                                	<?php
                                                    echo @$orders_no[$i]['T'];                            	
			                                	?>
			                                </div>
			                                <div class="txt12 text-center <?php if(isset($takeway_tables_status[$i])) echo "whitecolor"; else echo "lightcolor"; ?>"><?php echo @$orders_time[$i]['T']?date("H:i", strtotime(@$orders_time[$i]['T'])):"" ?></div>
		                            	
                                            <?php }?>
		                            </div>
	                            </li>
                            <?php } ?>
                        </ul>
                    </div>
            </div>

        </div>


        <div class="clearfix dine-box">
            <div class="col-md-12">
                <button class="dinebtn"><?php echo $this->Html->image('dine-icon.png', array('alt' => 'POS', 'title' => 'Dine')); ?> Waiting Table 等待桌</button>
            </div>
        </div>

        <div class="clearfix marginB30">

            <div class="col-md-12 col-sm-12 col-xs-12 dine-wrap">
                <ul>
                    <?php
                    $wait_table = @explode(",", $tables['Admin']['waiting_table_size']);
                    for ($i = 1; $i <= $tables['Admin']['no_of_waiting_tables']; $i++) {
                        ?>
                        <li class="clearfix">
                            <ul class="dropdown-menu">
                                <div class="arrow"></div>
                                <li <?php if (@$waiting_tables_status[$i] == 'P') echo 'class="disabled"'; ?>><a tabindex="-1" href="<?php
                                    if (@$waiting_tables_status[$i] <> 'P')
                                        echo $this->Html->url(array('controller' => 'homes', 'action' => 'order', 'table' => $i, 'type' => 'W'));
                                    else
                                        echo "javascript:void(0)";
                                    ?>">Order<br/>点餐</a></li>

                                <li class="dropdown-submenu <?php if (!@$waiting_tables_status[$i]) echo 'disabled'; ?>">
                                    <a class="test" tabindex="-1" href="<?php
                                    if (@$waiting_tables_status[$i])
                                        echo $this->Html->url(array('controller' => 'homes', 'action' => 'changetable', 'table' => $i, 'type' => 'W'));
                                    else
                                        echo "javascript:void(0)";
                                    ?>">Change Table</br/>换桌</a>
                                       <?php
                                       if (@$waiting_tables_status[$i]) {
                                           ;
                                           ?>
                                        <ul class="dropdown-menu">
                                            <div class="customchangemenu clearfix">
                                                <div class="left-arrow"></div>
                                                <div class="col-md-12 col-sm-12 col-xs-12 text-center timetable"> DINE IN 堂食</div>
                                                <?php
                                                for ($t = 1; $t <= $tables['Admin']['no_of_tables']; $t++) {
                                                    if (!@$orders_no[$t]['D']) {
                                                        ?>
                                                        <div class="col-md-6 col-sm-6 col-xs-6 text-center timetable"><a href="<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'move_order', 'table' => $t, 'type' => 'D', 'order_no' => @$orders_no[$i]['W'])); ?>"><?php echo $t; ?></a></div>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                                <div class="col-md-12 col-sm-12 col-xs-12 text-center timetable">TAKE OUT 外卖</div>
                                                <?php
                                                for ($t = 1; $t <= $tables['Admin']['no_of_takeout_tables']; $t++) {
                                                    if (!@$orders_no[$t]['T']) {
                                                        ?>
                                                        <div class="col-md-6 col-sm-6 col-xs-6 text-center timetable"><a href="<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'move_order', 'table' => $t, 'type' => 'T', 'order_no' => @$orders_no[$i]['W'])); ?>"><?php echo $t; ?></a></div>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                                <div class="col-md-12 col-sm-12 col-xs-12 text-center timetable">WAITING 等候</div>
                                                <?php
                                                for ($t = 1; $t <= $tables['Admin']['no_of_waiting_tables']; $t++) {
                                                    if (!@$orders_no[$t]['W'] and $t <> $i) {
                                                        ?>
                                                        <div class="col-md-6 col-sm-6 col-xs-6 text-center timetable"><a href="<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'move_order', 'table' => $t, 'type' => 'W', 'order_no' => @$orders_no[$i]['W'])); ?>"><?php echo $t; ?></a></div>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </div>
                                            </ul>
                                        <?php }?>
                                    </li>
                                    <li <?php if(@$waiting_tables_status[$i] <> 'N' and @$waiting_tables_status[$i] <> 'V')echo 'class="disabled"';?>><a tabindex="-1" href="<?php if(@$waiting_tables_status[$i] == 'N' OR @$waiting_tables_status[$i] == 'V')echo $this->Html->url(array('controller'=>'homes', 'action'=>'pay', 'table'=>$i, 'type'=>'W')); else echo "javascript:void(0)";?>">Pay<br/>结账</a></li>
                                    <li <?php if(@$waiting_tables_status[$i] <> 'P')echo 'class="disabled"';?>><a tabindex="-1" href="<?php if(@$waiting_tables_status[$i] == 'P')echo $this->Html->url(array('controller'=>'homes', 'action'=>'makeavailable', 'table'=>$i, 'type'=>'W', 'order'=>@$orders_no[$i]['W'])); else echo "javascript:void(0)";?>">Make Available<br/>变空桌</a></li>
	                        	</ul>
	                            <div class="<?php if(isset($waiting_tables_status[$i])) echo $colors[$waiting_tables_status[$i]]; else echo 'availablebwrap'; ?> clearfix  dropdown-toggle" data-toggle="dropdown">
	                                <div class="number-txt for-dine">等候<?php echo str_pad($i, 2, 0, STR_PAD_LEFT); ?></div>
	                                <div class="order_no_box <?php if(isset($waiting_tables_status[$i])) echo "whitecolor"; else echo "lightcolor"; ?>">
	                                	<?php
	                                	if(!@$waiting_tables_status[$i]) 
                                            echo "&nbsp;";
                                		else
                                            echo @$orders_no[$i]['W'];                       	
	                                	?>
	                                </div>
	                                <div class="txt12 text-center <?php if(isset($waiting_tables_status[$i])) echo "whitecolor"; else echo "lightcolor"; ?>">
                                        <?php if(@$waiting_tables_status[$i]) {  ?> <?php echo @$orders_time[$i]['W']?date("H:i", strtotime(@$orders_time[$i]['W'])):""; } ?>
	                            </div>
	                        </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
                <!-- Scroll buttons -->
                <a href="#" class="scrollUp">Up</a>
                <a href="#" class="scrollDown">Down</a>
            </div>
         

   
<?php
echo $this->Html->script(array('jquery.min.js', 'bootstrap.min.js'));
echo $this->fetch('script');
?>
<script>
	$(document).ready(function () {
            
        $(window).scroll(function () {
            if ($(this).scrollTop() > 100) {
                $('.scrollUp').fadeIn();
            } else {
                $('.scrollUp').fadeOut();
            }

            if ($(this).scrollTop() + $(this).height() >= $(document).height() - 100) {
                $('.scrollDown').fadeOut();
            } else {
                $('.scrollDown').fadeIn();
            }
        });

        $('.scrollUp').click(function () {
            $("html, body").animate({
                scrollTop: 0
            }, 300);
            return false;
        });

        $('.scrollDown').click(function() {
            $('html, body').animate({ scrollTop: $(document).height() }, 300);
            return false;
        });

        $('.dropdown-submenu a.test').on("click", function (e) {
                $(this).next('ul').toggle();
                e.stopPropagation();
                e.preventDefault();
            });


$('.dropdown-toggle').on('click', function (e) {
                setTimeout(function () {
                    var ddmenu = $('.open').find('.dropdown-menu');
                    if (ddmenu) {
                        var position_x = ddmenu.offset().left;
                        var el_width = ddmenu.outerWidth();
                        var window_width = $(window).width();

                        if (position_x + el_width > window_width && !ddmenu.hasClass('dropdown-menu-right')) {
                            ddmenu.addClass('dropdown-menu-right');
                        }
                    }
                }, 1);
            });
        });

    $(window).load(function () {
        if ($(window).scrollTop() + $(window).height() >= $(document).height() - 100) {
            $('.scrollDown').fadeOut();
        } else {
            $('.scrollDown').fadeIn();
        }
    });
</script>

</body>

<!-- Modified by Yishou Liao @ Oct 13 2016. -->
<script type="text/javascript">
    function mergebill(tableId) {
        var table_merge = "";
        $('input[name="mergetable[]"]:checked').each(function () {
           table_merge += $(this).val()+",";
            $(this).attr("checked",false);
        });
        table_merge = table_merge.substring(0,(table_merge.length-1));
        
        document.location = "merge/table:"+tableId+"/tablemerge:"+table_merge+"/type:D";
    }
</script>
<!-- End. -->