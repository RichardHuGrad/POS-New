<header class="product-header">
  <div style="display:none;">
        <canvas id="canvas" width="512" height="480"></canvas>
        <?php echo $this->Html->image("logo.png", array('alt' => "POS",'id' => "logo")); ?>
    </div>
      

    <div class="home-logo">
        <a href="<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'dashboard')) ?>">
            <?php echo $this->Html->image("logo-home.jpg", array('alt' => "POS")); ?>
        </a>

        <div class="HomeText text-left">
            <a href="<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'index')) ?>">Home 主页</a>
            <a href="javascript:void(0)" onclick="window.history.back()">Back 返回</a>
        </div>

    </div>	  
    <div class="logout"><a href="<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'logout')) ?>">Logout 登出</a></div>

</header>
<div class="split container-fluid">
    <div class="clearfix cartwrap-wrap"></div>
    <div id="customer-select-alert" class="alert alert-info alert-dismissible fade in" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <strong>Customer # <span id="customer-number"></span></strong> selected
    </div>
    <div class="order-wrap no-padding">
        <?php echo $this->Session->flash(); ?>
        <div class="col-md-4 col-sm-4 col-xs-12 order-left">
            <h2>Order 订单号 #<?php echo $Order_detail['Order']['order_no'] ?><br/>Table 桌 <?php echo (($type == 'D') ? '[[堂食]]' : (($type == 'T') ? '[[外卖]]' : (($type == 'W') ? '[[等候]]' : ''))); ?>#<?php echo $table; ?></h2>
            <?php
            if ($Order_detail['Order']['table_status'] <> 'P') {
                ?>
                <!-- Modified by Yishou Liao @ Oct 17 2016. -->

                <div class="table-box dropdown">
                    <a href="" class="split dropdown-toggle"  data-toggle="dropdown">Average <?php
                        if ($split_method == 0) {
                            echo "平均分单";
                        } else {
                            echo "按人分单";
                        }
                        ?> <input type="text" class="text-center" readonly="" id="persons" name="persons" value="1" size="2"> 人</a>
                    <ul class="dropdown-menu">
                        <div class="customchangemenu clearfix">
                            <div class="left-arrow"></div>
                            <div class="col-md-12 col-sm-12 col-xs-12 text-center timetable">Persons 人数</div>
                            <?php for ($t = 1; $t <= 20; $t++) { ?>
                                <div class="col-md-6 col-sm-6 col-xs-6 text-center timetable"><a href="#" onclick="javascript:persons(<?php echo $t; ?>)"><?php echo $t; ?></a></div>
    <?php } ?>
                        </div>
                    </ul>
                </div>

                <!-- End. -->
<?php } ?>

            <div class="avoid-this text-center reprint"><button type="button" class="submitbtn">Print Receipt 打印收据</button></div>
            <!-- Modified by Yishou Liao @ Oct 17 2016. 
            <div class="avoid-this text-center reprint_2"><button type="button" class="submitbtn">Print Kitchen 打印后厨单</button></div>
            End. -->
            <div class="order-summary <?php if ($split_method == 0) echo 'avg'; ?>">
                <h3>Order Summary 订单摘要</h3>
                <div class="order-summary-indent clearfix" name="orderitem" id="orderitem">
                    <!-- Modified by Yishou Liao @ Oct 18 2016. -->
                </div>

            </div>

            <!-- Modified by Yishou Liao @ Oct 18 2016. -->
            <?php if ($split_method == 1) { ?>
                <div class="avoid-this text-center addperson"><button type="button" class="submitbtn">Add Persons 增加人</button></div>
                <div class="order-summary addperson">
                    <h3>Split Details 分单明细</h3>
                    <div class="order-summary-indent addperson clearfix" name="splitmenu" id="splitmenu">
                        
                    </div>
                </div>
            </div>
        <?php } ?>
        <!-- End. -->
    </div>

    <div class="col-md-8 col-sm-8 col-xs-12 RIGHT-SECTION">
        <ul class="nav nav-tabs" id="person-tab">
        </ul>
        <div class="clearfix total-payment" name="split_accounting_details" id="split_accounting_details">
            
        </div>

<?php
if ($Order_detail['Order']['table_status'] <> 'P') {
    ?>
            <div class="card-wrap"><input type="text" id="screen" buffer="0" maxlength="13"></div>
            <div class="card-indent clearfix">
                <ul>
                    <li>1</li>
                    <li>2</li>
                    <li>3</li>

                    <li>4</li>
                    <li>5</li>
                    <li>6</li>

                    <li>7</li>
                    <li>8</li>
                    <li>9</li>

                    <li class="clear-txt" id="Clear">Clear 清除</li>
                    <li>0</li>
                    <li class="enter-txt" id="Enter">Enter 输入</li>
                </ul>
            </div>

            <div class="card-bot clearfix text-center">
                <button type="button" class="btn btn-danger select_card" id="card"> <?php echo $this->Html->image("card.png", array('alt' => "card")); ?> Card 卡</button>
                <button type="button" class="btn btn-danger select_card"  id="cash"><?php echo $this->Html->image("cash.png", array('alt' => "cash")); ?> Cash 现金</button>

                <button type="button" class="btn btn-warning select_card"  id="tip"><?php echo $this->Html->image("cash.png", array('alt' => "tip")); ?> Tip 小费</button>

                <button type="button" class="btn btn-success card-ok"  id="submit"><?php echo $this->Html->image("right.png", array('alt' => "right")); ?> Confirm 确认</button>
                <input type="hidden" id="selected_card" value="" />
                <input type="hidden" id="card_val" name="card_val" value="" />
                <input type="hidden" id="cash_val" name="cash_val" value="" />
                <input type="hidden" id="tip_val"name="tip" value="" />
                <input type="hidden" id="tip_paid_by"name="tip_paid_by" value="" />
            </div>

<?php } ?>
    </div>
</div>
</div>
<div id="confirm" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <strong>Delete</strong> <span id="dish-to-be-deleted"></span>
            </div>
            <div class="modal-body">
                Are you sure?
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-primary" id="delete">Delete</button>
                <button type="button" data-dismiss="modal" class="btn">Cancel</button>
            </div>
        </div>
    </div>
</div>
<div style="display:none" >

    <div class="order-summary" id="print_panel">
        <h3 class="dianming">嘿小面</h3>
        <h4 class="dianming">3700 Midland Ave. #108</h4>
        <h4 class="dianming">Scarborougn On M1V 0B3</h4>
        <h4 class="dianming">647-352-5333</h4>
        <h5></h5>
        <div class="order-summary-indent clearfix">
            <div>Order Number 订单号 #<?php echo $Order_detail['Order']['order_no'] ?>, Table 桌 <?php echo (($type == 'D') ? '[[堂食]]' : (($type == 'T') ? '[[外卖]]' : (($type == 'W') ? '[[等候]]' : ''))); ?>#<?php echo $table; ?></div><br>
        <!-- Modified by Yishou Liao @ Oct 19 2016. -->
        <div name="Order_print" id="Order_print"></div>

        <!-- End. -->
        </div>
        <hr>
        <div class="clearfix total-payment" style="background-color:#fff; border:none; box-shadow:none" name="print_account" id="print_account">
            
        </div>
        <div style="height:50px;"></div>
    </div>
    
    <!-- Print for kitchen -->
    <div class="order-summary" id="print_panel_2">
        <h3 class="dianming">去后厨的单子</h3>
        <h3><?php echo (($type == 'D') ? '[[堂食]]' : (($type == 'T') ? '[[外卖]]' : (($type == 'W') ? '[[等候]]' : ''))); ?> 桌号 #<?php echo $table; ?></h3>
        <h5></h5>
        <div class="order-summary-indent clearfix">
            <div>Order Number 订单号 #<?php echo $Order_detail['Order']['order_no'] ?></div><br>
            <ul>
                <?php
                if (!empty($Order_detail['OrderItem'])) {
                    foreach ($Order_detail['OrderItem'] as $key => $value) {
                        # code...
                        $selected_extras_name = [];
                        if ($value['all_extras']) {
                            $extras = json_decode($value['all_extras'], true);
                            $selected_extras = json_decode($value['selected_extras'], true);

                            // prepare extras string
                            $selected_extras_id = [];
                            if (!empty($selected_extras)) {
                                foreach ($selected_extras as $k => $v) {
                                    $enameArr = explode(" ", $v['name']);
                                    $selected_extras_name[] = array_pop($enameArr);
                                    // $selected_extras_name[] = $v['name'];
                                    $selected_extras_id[] = $v['id'];
                                }
                            }
                        }
                        ?>
                        <li class="clearfix">
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="pull-left avoid-this">
                                        <?php
                                        if ($value['image']) {
                                            echo $this->Html->image(TIMB_PATH . "timthumb.php?src=" . COUSINE_IMAGE_PATH . $value['image'] . "&h=42&w=62&&zc=4&Q=100", array('border' => 0, 'alt' => 'Product', 'class' => 'img-responsive'));
                                        } else {
                                            echo $this->Html->image(TIMB_PATH . "timthumb.php?src=" . TIMB_PATH . 'no_image.jpg' . "&h=42&w=62&&zc=4&Q=100", array('border' => 0, 'alt' => 'Product', 'class' => 'img-responsive'));
                                        }
                                        ?>
                                    </div>
                                    <div class="pull-left titlebox1">
                                        <!-- to show name of item -->
                                        <div class="less-title"><?php echo $value['name_xh']; ?></div>

                                        <!-- to show the extras item name -->
                                        <div class="less-txt"><?php echo implode(",", $selected_extras_name); ?></div>
                                    </div>
                                </div>
                            </div>
                        </li>
    <?php
    }
}
?>
            </ul>
        </div>
        <hr>
    </div>
<?php
echo $this->Html->script(array('jquery.min.js', 'bootstrap.min.js', 'jquery.mCustomScrollbar.concat.min.js','barcode.js','epos-print-5.0.0.js','print.js','fanticonvert.js'));
echo $this->fetch('script');
?>
    <script>
		//Modified by Yishou Liao @ Oct 18 2016.
		var person_No = 0;
		var current_person = 0;
		var person_menu=new Array();
		var order_menu=new Array();
		
		$(document).on('click', '.addperson', function () {
			if (person_No < $('#persons').val()){
				person_No++;
				var addpersonStr=$('#splitmenu').html();
				addpersonStr += "<br /><label class='person-label' onclick='javascript:setCurrentPerson("+person_No+");'>Customer # "+ person_No + "</label>";
				$('#splitmenu').html(addpersonStr);
				current_person = person_No;
				
				var sele_person = "account_no_"+(current_person-1);
				document.getElementById(sele_person).checked = true;
				showAcountingDetails()
			};
        });
		
		function setCurrentPerson(currentPerson){
			//Modified by Yishou Liao @ Oct 21 2016.
			var selepersonstr = "";
			if (checkCookie("persons_sele_"+<?php echo $Order_detail['Order']['order_no'] ?>)){
				selepersonstr = getCookie("persons_sele_"+<?php echo $Order_detail['Order']['order_no'] ?>);
			};
			if (selepersonstr.indexOf(currentPerson)!=-1){
				return;
			};
			//End.
			current_person = currentPerson;
			
			//Modified by Yishou Liao @ Oct 19 2016.
			var sele_person = "account_no_"+(current_person-1);
			document.getElementById(sele_person).checked = true;
			showAcountingDetails();
			//End.
			
			//alert("当前添加给#"+current_person);
            $('#customer-select-alert').alert();
            $('#customer-select-alert #customer-number').html(current_person);
            $('#customer-select-alert').fadeTo(500, 500).fadeOut(500, function() {
            });
		}
		
		function addMenuItem(item_no,image,name_en,name_xh,selected_extras_name,price,extras_amount,qty,item_id,order_item_id){
			//Modified by Yishou Liao @ Oct 21 2016..
			var selepersonstr = "";
			if (checkCookie("persons_sele_"+<?php echo $Order_detail['Order']['order_no'] ?>)){
				selepersonstr = getCookie("persons_sele_"+<?php echo $Order_detail['Order']['order_no'] ?>);
			};
			if (selepersonstr.indexOf(current_person)!=-1){
				return;
			};
			//End.
			
			if (current_person != 0){
				person_menu.push(Array(current_person,image,name_en,name_xh,selected_extras_name,price,extras_amount,qty,item_id,order_item_id));
				person_menu.sort(function(x,y){return x[0]-y[0]});//二维数组排序
				
				 //addpersonStr=$('#splitmenu').html()
				var addpersonStr ="";
				var curtmp = 0;
				for (var i=0;i<person_menu.length;i++){
					if (curtmp != parseInt(person_menu[i][0])){
						if (curtmp != 0){addpersonStr += "</ul>";};
						//Modified by Yishou Liao @ 19 2016l
						for (curtmp;curtmp<person_menu[i][0];curtmp++){//Modified by Yishou Liao @ Oct 21 2016.
							tmpNo = curtmp+1;
							addpersonStr += "<br /><label class='person-label' onclick='javascript:setCurrentPerson("+tmpNo+");'>Customer # "+ tmpNo + "</label><ul>";
						}//End.
						curtmp = parseInt(person_menu[i][0]);
					};
					addpersonStr += "<li class='clearfix' onclick='javascript:delMenuItem("+i+","+person_menu[i][8]+");'><div class='row'><div class='col-md-9 col-sm-8 col-xs-8'><div class='pull-left titlebox1'><div class='less-title'>"+person_menu[i][2]+"<br />"+person_menu[i][3]+"</div><div class='less-txt'> </div></div></div><div class='col-md-3 col-sm-4 col-xs-4 text-right price-txt'>$"+person_menu[i][5]+person_menu[i][6]+person_menu[i][7]+"</div></div></li>";
				};
				
				$('#splitmenu').html(addpersonStr);
				
				order_menu.splice(item_no,1);
				addOrderItem();
				
				//Modified by Yishou Liao @ Oct 19 2016.
				showAcountingDetails();
				//End.
				
				//Modified by Yishou Liao @ Oct 21 2016.
				deleteCookie("order_menu"+<?php echo $Order_detail['Order']['order_no'] ?>);
				deleteCookie("person_menu_"+<?php echo $Order_detail['Order']['order_no'] ?>);
				
				setCookie("order_menu"+<?php echo $Order_detail['Order']['order_no'] ?>,arrtostr(order_menu),1);
				setCookie("person_menu_"+<?php echo $Order_detail['Order']['order_no'] ?>,arrtostr(person_menu),1);
				setCookie("persons_"+<?php echo $Order_detail['Order']['order_no'] ?>,$("#persons").val(),1);
				//End.
			};
		}
		
		function delMenuItem(item_no,orderitem_no){
			//Modified by Yishou Liao @ Oct 21 2016.
			var selepersonstr = "";
			if (checkCookie("persons_sele_"+<?php echo $Order_detail['Order']['order_no'] ?>)){
				selepersonstr = getCookie("persons_sele_"+<?php echo $Order_detail['Order']['order_no'] ?>);
			};
			if (selepersonstr.indexOf(person_menu[item_no][0])!=-1){
				return;
			};
			//End.

            $('#confirm #dish-to-be-deleted').html(person_menu[item_no][2]);
            $('#confirm').modal('show').one('click', '#delete', function() {
                person_menu[item_no].splice(8,1);
                order_menu.push(person_menu[item_no]);
                order_menu[order_menu.length-1][0] = orderitem_no;
                order_menu.sort(function(x,y){return x[0]-y[0]});//二维数组排序
                addOrderItem(orderitem_no);
                
                person_menu.splice(item_no,1);
                person_menu.sort(function(x,y){return x[0]-y[0]});//二维数组排序
                
                //Modified by Yisho Liao @ Oct 22 2016.
                if (person_menu.length==0) { person_No = 0;};
                //End.
                
                var addpersonStr ="";
                var curtmp = 0;
                for (var i=0;i<person_menu.length;i++){
                    if (curtmp != parseInt(person_menu[i][0])){
                        if (curtmp != 0){addpersonStr += "</ul>";};
                        for (curtmp;curtmp<person_menu[i][0];curtmp++){//Modified by Yishou Liao @ Oct 21 2016.
                            tmpNo = curtmp+1;
                            addpersonStr += "<br /><label class='person-label' onclick='javascript:setCurrentPerson("+tmpNo+");'>Customer # "+ tmpNo + "</label><ul>";
                        };
                        curtmp = parseInt(person_menu[i][0]);
                    }
                    addpersonStr += "<li class='clearfix' onclick='javascript:delMenuItem("+i+","+person_menu[i][8]+");'><div class='row'><div class='col-md-9 col-sm-8 col-xs-8'><div class='pull-left titlebox1'><div class='less-title'>"+person_menu[i][2]+"<br />"+person_menu[i][3]+"</div><div class='less-txt'> </div></div></div><div class='col-md-3 col-sm-4 col-xs-4 text-right price-txt'>$"+person_menu[i][4]+person_menu[i][5]+person_menu[i][6]+"</div></div></li>";
                };
                
                $('#splitmenu').html(addpersonStr);
                
                showAcountingDetails() //Modified by Yishou Liao @ Oct 19 2016.
                
                //Modified by Yishou Liao @ Oct 21 2016.
                deleteCookie("order_menu"+<?php echo $Order_detail['Order']['order_no'] ?>);
                deleteCookie("person_menu_"+<?php echo $Order_detail['Order']['order_no'] ?>);


                setCookie("order_menu"+<?php echo $Order_detail['Order']['order_no'] ?>,arrtostr(order_menu),1);
                setCookie("person_menu_"+<?php echo $Order_detail['Order']['order_no'] ?>,arrtostr(person_menu),1);
                setCookie("persons_"+<?php echo $Order_detail['Order']['order_no'] ?>,$("#persons").val(),1);
                //End.
            })
		};
		//End.
		
        $(document).on('click', '.reprint', function () {
			print_receipt();//Modified by Yishou @ Oct 19 2016.
			
        });
        $(document).on('click', '.reprint_2', function () {
            //Print ele4 with custom options
            $("#print_panel_2").print({
                //Use Global styles
                globalStyles: false,
                //Add link with attrbute media=print
                mediaPrint: true,
                //Custom stylesheet
                stylesheet: "<?php echo Router::url('/', true) ?>css/styles.css",
                //Print in a hidden iframe
                iframe: false,
                //Don't print this
                noPrintSelector: ".avoid-this",
                //Add this at top
                // prepend : "<h2></h2>",
                //Add this on bottom
                // append : "<br/>Buh Bye!"
            });
        });
		
		//Modified by Yishou Liao @ Oct 18 2016.
		function addOrderItem(orderitem_no=null){
			var outhtml_str = "<ul>";
			for (var i=0;i<order_menu.length;i++){
				outhtml_str += '<li class="clearfix" onclick=\'javascript:addMenuItem( '+i+',"'+order_menu[i][1]+'", "'+order_menu[i][2]+'", "'+order_menu[i][3]+'","'+order_menu[i][4]+'","'+order_menu[i][5]+'","'+order_menu[i][6]+'","'+order_menu[i][7]+'",'+ order_menu[i][0]+','+order_menu[i][8]+' );\'>';
				
				outhtml_str += '<div class="row"><div class="col-md-9 col-sm-8 col-xs-8"><div class="pull-left titlebox1">';
				outhtml_str += '<div class="less-title">'+order_menu[i][2]+'<br/>'+order_menu[i][3]+'</div><div class="less-txt">'+order_menu[i][4]+'</div></div></div><div class="col-md-3 col-sm-4 col-xs-4 text-right price-txt">$';
				outhtml_str += order_menu[i][5]+order_menu[i][6]+order_menu[i][7]+'</div></div></li>'
			};
			
			outhtml_str += "</ul>";
			$('#orderitem').html(outhtml_str);
		}
		//End.
		
        $(document).ready(function () {
            $('#customer-select-alert').hide();

			//Modified by Yishou Liao @ Oct 21 2016.
			var addorder_menu = true;

			if (checkCookie("person_menu_"+<?php echo $Order_detail['Order']['order_no'] ?>)){
				var orderarray = getCookie("order_menu"+<?php echo $Order_detail['Order']['order_no'] ?>);
				var personarray = getCookie("person_menu_"+<?php echo $Order_detail['Order']['order_no'] ?>);
				
				$("#persons").val(getCookie("persons_"+<?php echo $Order_detail['Order']['order_no'] ?>));
				
				if (orderarray!=""){
					order_menu = strtoarr(orderarray);
				};
				if (personarray != "") {
					person_menu = strtoarr(personarray);
				}
				
				var order_detail_length = <?php echo count($Order_detail['OrderItem']); ?>;
				if (person_menu.length == order_detail_length){
					addorder_menu = false;
				};
			};
			//End.
			
			//Modified by Yishou Liao @ Oct 18 2016.
			<?php
            if (!empty($Order_detail['OrderItem'])) {
				$i=0;
				foreach ($Order_detail['OrderItem'] as $key => $value) {
					# code...
					$selected_extras_name = [];
					if ($value['all_extras']) {
						$extras = json_decode($value['all_extras'], true);
						$selected_extras = json_decode($value['selected_extras'], true);
	
						// prepare extras string
						$selected_extras_id = [];
						if (!empty($selected_extras)) {
							foreach ($selected_extras as $k => $v) {
								$selected_extras_name[] = $v['name'];
								$selected_extras_id[] = $v['id'];
							}
						}
					}
			?>
			
			if (addorder_menu) {//Modified by Yishou Liao @ Oct 21 2016.
				var order_id = <?php echo $value['id'] ?>;
				var addmenu = true;
				
				for (var j=0;j<person_menu.length;j++){
					if (person_menu[j][9] == order_id){ addmenu = false;}
				};
				for (var j=0;j<order_menu.length;j++){
					if (order_menu[j][8] == order_id){ addmenu = false;}
				};
				if (addmenu){
					order_menu.push(Array(<?php echo $i ?>,'<?php if ($value['image']) {echo $value['image'];}else{echo 'no_image.jpg';}; ?>', '<?php echo $value['name_en']; ?>', '<?php echo $value['name_xh']; ?>','<?php echo implode(",", $selected_extras_name); ?>','<?php echo $value['price'] ?>','<?php echo $value['extras_amount'] ?>','<?php echo $value['qty'] > 1 ? "x" . $value['qty'] : "" ?>',<?php echo $value['id'] ?>));//Modified by Yishou Liao @ Oct 20 2016. Added $value['id']. 
				};
			};//End.
			
			<?php
				$i++;
				};
			?>
			addOrderItem();
			<?php
			};
			?>

			//Modified by Yishou Liao @ Oct 21 2016.
			var selepersonstr = "";
			if (checkCookie("persons_sele_"+<?php echo $Order_detail['Order']['order_no'] ?>)){
				selepersonstr = getCookie("persons_sele_"+<?php echo $Order_detail['Order']['order_no'] ?>);
			};

            var person_tab_Str = "";
			var checkflag = false;
			for (var i=0;i<$('#persons').val(); i++){
				if (i==0) {
					if (selepersonstr.indexOf(i+1)!=-1){
                        person_tab_Str += '<li name="'+(i+1)+'" id="account_no_'+i+'" onclick="tabSelected('+(i+1)+');" disabled><a data-toggle="tab"># '+(i+1)+'</a></li>';
					}else{
                        person_tab_Str += '<li name="'+(i+1)+'" id="account_no_'+i+'" onclick="tabSelected('+(i+1)+');" class="active"><a data-toggle="tab"># '+(i+1)+'</a></li>';
						checkflag = true;
					};
				}else{
					if (selepersonstr.indexOf(i+1)!=-1){
                        person_tab_Str += '<li name="'+(i+1)+'" id="account_no_'+i+'" onclick="tabSelected('+(i+1)+');" disabled><a data-toggle="tab"># '+(i+1)+'</a></li>';
					}else{
						if (checkflag == false){
                            person_tab_Str += '<li name="'+(i+1)+'" id="account_no_'+i+'" onclick="tabSelected('+(i+1)+');" class="active"><a data-toggle="tab"># '+(i+1)+'</a></li>';
							checkflag = true;
						}else{
                            person_tab_Str += '<li name="'+(i+1)+'" id="account_no_'+i+'" onclick="tabSelected('+(i+1)+');"><a data-toggle="tab"># '+(i+1)+'</a></li>';
						};
					};
				}
			}

            $('#person-tab').html(person_tab_Str);
			//End.
			
			//MOdified by Yishou Liao @ Oct 19 2016.
			showAcountingDetails();
			//End.
			
			
			//Modified by Yishou Liao @ Oct 21 2016.
			var addpersonStr ="";
				var curtmp = 0;
				for (var i=0;i<person_menu.length;i++){
					if (curtmp != parseInt(person_menu[i][0])){
						if (curtmp != 0){addpersonStr += "</ul>";};
						for (curtmp;curtmp<person_menu[i][0];curtmp++){//Modified by Yishou Liao @ Oct 21 2016.
							tmpNo = curtmp+1;
							addpersonStr += "<br /><label class='person-label' onclick='javascript:setCurrentPerson("+tmpNo+");'>Customer # "+ tmpNo + "</label><ul>";
						};
						curtmp = parseInt(person_menu[i][0]);
					};
					addpersonStr += "<li class='clearfix' onclick='javascript:delMenuItem("+i+","+person_menu[i][8]+");'><div class='row'><div class='col-md-9 col-sm-8 col-xs-8'><div class='pull-left titlebox1'><div class='less-title'>"+person_menu[i][2]+"<br />"+person_menu[i][3]+"</div><div class='less-txt'> </div></div></div><div class='col-md-3 col-sm-4 col-xs-4 text-right price-txt'>$"+person_menu[i][5]+person_menu[i][6]+person_menu[i][7]+"</div></div></li>";
				};
				
				$('#splitmenu').html(addpersonStr);
			//End.
			
			
            $(".select_card").click(function () {
                $(".select_card").removeClass("active")
                $(this).addClass("active")
                var type = $(this).attr("id");
                if (type == 'card') {
                    $("#cash").removeClass("active");
                    var card_val = $("#card_val").val() ? parseFloat($("#card_val").val()) * 100 : 0;
                    $("#screen").attr('buffer', card_val);
                    $("#screen").val($("#card_val").val());
                } else if (type == 'cash') {
                    var cash_val = $("#cash_val").val() ? parseFloat($("#cash_val").val()) * 100 : 0;
                    $("#screen").attr('buffer', cash_val);
                    $("#screen").val($("#cash_val").val());
                } else {
                    var tip_val = $("#tip_val").val() ? parseFloat($("#tip_val").val()) * 100 : 0;
                    $("#screen").attr('buffer', tip_val);
                    $("#screen").val($("#tip_val").val());
                }
                $("#selected_card").val(type);
            })



            $(".select_tip").click(function () {
                $(".select_card").removeClass("active");
                $(this).toggleClass("active");
                var val = $("#tip_val").val() ? parseFloat($("#tip_val").val()) * 100 : 0;
                $("#screen").attr('buffer', val);
                $("#screen").val($("#tip_val").val());
            })



            $("#submit").click(function () {
                if ($("#selected_card").val()) {
                    if (parseFloat($(".change_price").attr("amount")) >= 0) {

                        // check tip type(card/cash) if exists
                        if (parseFloat($("#tip_val").val())) {
                            if (!$("#tip_paid_by").val()) {
                                alert("Please select tip payment method card or cash 请选择提示付款方式卡或现金. ");
                                return false;
                            }
                        };
						
						//Modified by Yishou Liao @ Oct 19 2016.
						var radio_click = 0;
						/*$('input[name="account_no[]"]:checked').each(function () {
						   radio_click = $(this).val();
						});*/
                        radio_click = parseInt($('#person-tab').find('.active').attr('name'));
						//End.

						//Modified by Yishou Liao @ Oct 21 2016.
						if (checkCookie("persons_sele_"+<?php echo $Order_detail['Order']['order_no'] ?>)){
							var seletmp = ""+getCookie("persons_sele_"+<?php echo $Order_detail['Order']['order_no'] ?>)+","+ radio_click;
						}else{
							var seletmp = ""+radio_click;
							};
						setCookie("persons_sele_"+<?php echo $Order_detail['Order']['order_no'] ?>,seletmp,1);
						//End.
						
						//Modified by Yishou Liao @ Oct 20 2016.
						var item_detail_id = "";
						for (var i=0;i<person_menu.length;i++){
							if (radio_click == person_menu[i][0]){
								item_detail_id += person_menu[i][9]+",";
							}
						};
						item_detail_id = item_detail_id.substr(0,(item_detail_id.length-1));
						//End.
						
                        // submit form for complete payment process
                        $.ajax({
                            url: "<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'averdonepayment', $table, $type)); ?>",
                            method: "post",
                            data: {
                                pay: $(".received_price").attr("amount"),
                                paid_by: $("#selected_card").val(),
                                change: $(".change_price").attr("amount"),
                                table: "<?php echo $table ?>",
                                type: "<?php echo $type ?>",
                                order_id: "<?php echo $Order_detail['Order']['id'] ?>",
                                split_method: "<?php echo $split_method ?>",
                                card_val: $("#card_val").val(),
                                cash_val: $("#cash_val").val(),
                                tip_val: $("#tip_val").val(),
                                tip_paid_by: $("#tip_paid_by").val(),
								account_no: radio_click,
								order_detail:item_detail_id
                            },
                            success: function (html) {
                                $(".alert-warning").hide();
								$(".reprint").trigger("click");

								//Modified by Yishou Liao @ Oct 20 2016.
								<?php if ($split_method == 0) { ?>
								//Modified by Yishou Liao @ Oct 21 2016.
								deleteCookie("order_menu"+<?php echo $Order_detail['Order']['order_no'] ?>);
								deleteCookie("person_menu_"+<?php echo $Order_detail['Order']['order_no'] ?>);
								deleteCookie("persons_"+<?php echo $Order_detail['Order']['order_no'] ?>);
								deleteCookie("persons_sele_"+<?php echo $Order_detail['Order']['order_no'] ?>);
								//End.
                                window.location = "<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'dashboard')); ?>";
								<?php }else{ ?>
								//Modified by Yishou Liao @ Oct 21 2016.
								setCookie("order_menu"+<?php echo $Order_detail['Order']['order_no'] ?>,arrtostr(order_menu),1);
								setCookie("person_menu_"+<?php echo $Order_detail['Order']['order_no'] ?>,arrtostr(person_menu),1);
								setCookie("persons_"+<?php echo $Order_detail['Order']['order_no'] ?>,$("#persons").val(),1);
								
								if (checkCookie("persons_sele_"+<?php echo $Order_detail['Order']['order_no'] ?>)){
									var seletmp = getCookie("persons_sele_"+<?php echo $Order_detail['Order']['order_no'] ?>);
								};
								//End.
								if (seletmp.split(",").length == $('#account_no').attr('length')){//Modified by Yishou Liao @ Oct 21 2016.
									deleteCookie("order_menu"+<?php echo $Order_detail['Order']['order_no'] ?>);
									deleteCookie("person_menu_"+<?php echo $Order_detail['Order']['order_no'] ?>);
									deleteCookie("persons_"+<?php echo $Order_detail['Order']['order_no'] ?>);
									deleteCookie("persons_sele_"+<?php echo $Order_detail['Order']['order_no'] ?>);
									
									window.location = "<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'dashboard')); ?>";
								}else{//Modified by Yishou Liao @ Oct 21 2016.
									window.location.reload();
								};//End.
								<?php } ?>
								//End.
                            },
                            beforeSend: function () {
                                $(".RIGHT-SECTION").addClass('load1 csspinner');
                                $(".alert-warning").show();
                            }
                        })
                    } else {
                        alert("Invalid amount, please check and verfy again 金额无效，请检查并再次验证.");
                        return false;
                    }
                } else {
                    alert("Please select card or cash payment method 请选择卡或现金付款方式. ");
                    return false;
                }
            })

            $(".card-indent li").click(function () {
                if (!$("#selected_card").val() && !$(".select_tip").hasClass("active")) {
                    alert("Please select payment type cash/card or select tip.");
                    return false;
                }

                if ($(this).hasClass("clear-txt") || $(this).hasClass("enter-txt"))
                    return false;

                var digit = parseInt($(this).html());
                var nums = $("#screen").attr('buffer') + digit;

                // store buffer value
                $("#screen").attr('buffer', nums);
                nums = nums / 100;
                nums = nums.toFixed(2);
                if (nums.length < 12)
                    $("#screen").val(nums).focus();
                else
                    $("#screen").focus();
            })

            $("#Enter").click(function () {
                if (!$("#selected_card").val()) {
                    alert("Please select payment type card/cash.");
                    return false;
                }
                var amount = $("#screen").val() ? parseFloat($("#screen").val()) : 0;
                var total_price = parseFloat($(".total_price").attr("alt"));

                if ($("#selected_card").val() == 'cash') {
                    $("#cash_val").val(amount.toFixed(2));
                    $(".cash_price").html("Cash 现金: $" + amount.toFixed(2));
                }
                if ($("#selected_card").val() == 'card') {
                    $("#card_val").val(amount.toFixed(2));
                    $(".card_price").html("Card 卡: $" + amount.toFixed(2));
                }
                if ($("#selected_card").val() == 'tip') {
                    $("#tip_val").val(amount.toFixed(2));
                    $(".tip_price").html("$" + amount.toFixed(2));
                }

                var cash_val = $("#cash_val").val() ? parseFloat($("#cash_val").val()) : 0;
                var card_val = $("#card_val").val() ? parseFloat($("#card_val").val()) : 0;


                amount = cash_val + card_val;
                if (amount) {
                    $(".received_price").html("$" + amount.toFixed(2));
                    $(".received_price").attr('amount', amount.toFixed(2));
                    $(".change_price").html("$" + (amount - total_price).toFixed(2));
                    $(".change_price").attr('amount', (amount - total_price).toFixed(2));

                    if ((amount - total_price) < 0) {
                        $(".change_price_txt").html("Remaining 其余");
                        $(".change_price").html("$" + (total_price - amount).toFixed(2));
                    } else {
                        $(".change_price_txt").html("Change 找零");
                    }

                } else {
                    return false;
                }
            })

            $("#rc1").click(function (E) {
                E.preventDefault();
            })

            $("#Clear").click(function () {

                var selected_card = $("#selected_card").val();
                var total_price = parseFloat($(".total_price").attr("alt"));
                if (selected_card == 'cash') {
                    var amount = $("#cash_val").val();
                    $(".cash_price").html("Cash 现金: $00.00");
                    $("#cash_val").val(0);

                    var received_price = parseFloat($(".received_price").attr('amount'));
                    var remaining = received_price - amount;

                    $(".received_price").html("$" + remaining.toFixed(2));
                    $(".received_price").attr('amount', remaining.toFixed(2));

                    if ((remaining - total_price) < 0) {
                        $(".change_price_txt").html("Remaining 其余");
                        $(".change_price").html("$" + (total_price - remaining).toFixed(2));
                    } else {
                        $(".change_price_txt").html("Change 找零");
                    }
                }

                if (selected_card == 'card') {
                    var amount = $("#card_val").val();
                    $(".card_price").html("Card 卡: $00.00");
                    $("#card_val").val(0);

                    var received_price = parseFloat($(".received_price").attr('amount'));
                    var remaining = received_price - amount;

                    $(".received_price").html("$" + remaining.toFixed(2));
                    $(".received_price").attr('amount', remaining.toFixed(2));

                    if ((remaining - total_price) < 0) {
                        $(".change_price_txt").html("Remaining 其余");
                        $(".change_price").html("$" + (total_price - remaining).toFixed(2));
                    } else {
                        $(".change_price_txt").html("Change 找零");
                    }
                }
                if (selected_card == 'tip') {

                    $("#tip_val").val(0.00);
                    $(".tip_price").html("$0.00");

                }

                $("#screen").attr('buffer', 0);
                $("#screen").val("");
                $("#screen").focus();
            })

            $("#screen").keydown(function (e) {
                // Allow: backspace, delete, tab, escape, enter and .
                if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
                        // Allow: Ctrl+A, Command+A
                                (e.keyCode == 65 && (e.ctrlKey === true || e.metaKey === true)) ||
                                // Allow: home, end, left, right, down, up
                                        (e.keyCode >= 35 && e.keyCode <= 40)) {
                            // let it happen, don't do anything
                            return;
                        }
                        // Ensure that it is a number and stop the keypress
                        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                            e.preventDefault();
                        }
                    });
        })

        $(document).on("keyup", ".discount_section", function () {
            if ($(this).val()) {
                $(".discount_section").attr("disabled", "disabled");
                $(this).removeAttr("disabled");
            } else {
                $(".discount_section").removeAttr("disabled");
            }
        })

        $(document).on("click", "#apply-discount", function () {

            var fix_discount = $("#fix_discount").val();
            var discount_percent = $("#discount_percent").val();
            var promocode = $("#promocode").val();

            if (fix_discount || discount_percent || promocode) {
                // apply promocode here
                $.ajax({
                    url: "<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'add_discount')); ?>",
                    method: "post",
                    dataType: "json",
                    data: {fix_discount: fix_discount, discount_percent: discount_percent, promocode: promocode, order_id: "<?php echo $Order_detail['Order']['id'] ?>"},
                    success: function (html) {
                        if (html.error) {
                            alert(html.message);
                            $(".discount_section").val("").removeAttr("disabled");
                            $(".RIGHT-SECTION").removeClass('load1 csspinner');
                            return false;
                        } else {
							//Modified by Yishou Liao @ Oct 21 2016.
							setCookie("order_menu"+<?php echo $Order_detail['Order']['order_no'] ?>,arrtostr(order_menu),1);
							setCookie("person_menu_"+<?php echo $Order_detail['Order']['order_no'] ?>,arrtostr(person_menu),1);
							setCookie("persons_"+<?php echo $Order_detail['Order']['order_no'] ?>,$("#persons").val(),1);
							//End.
                            window.location.reload();
                        }
                    },
                    beforeSend: function () {
                        $(".RIGHT-SECTION").addClass('load1 csspinner');
                    }
                })


            } else {
                alert("Please add discount first.");
                return false;
            }
        })

        $(document).on('click', ".remove_discount", function () {
            var order_id = "<?php echo $Order_detail['Order']['id'] ?>";
            var message = $("#Message").val();
            $.ajax({
                url: "<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'remove_discount')); ?>",
                method: "post",
                data: {order_id: order_id},
                success: function (html) {
					//Modified by Yishou Liao @ Oct 21 2016.
					setCookie("order_menu"+<?php echo $Order_detail['Order']['order_no'] ?>,arrtostr(order_menu),1);
					setCookie("person_menu_"+<?php echo $Order_detail['Order']['order_no'] ?>,arrtostr(person_menu),1);
					setCookie("persons_"+<?php echo $Order_detail['Order']['order_no'] ?>,$("#persons").val(),1);
					//End.
                    window.location.reload();
                },
                beforeSend: function () {
                    $(".RIGHT-SECTION").addClass('load1 csspinner');
                }
            })
        })
        $(document).on('click', ".add-discount", function () {
            $(".discount_view").toggle();
        });


        $(document).on('click', ".tip_paid_by", function () {
            $("#tip_paid_by").val($(this).val());
        });

        //Modified by Yishou Liao @ Oct 17 2016.
        function persons(persons) {
            $("#persons").val(persons);
            //Modified by Yishou Liao @ Oct 18 2016.
<?php if ($split_method == 0) { ?>
                $("#aver_total").val((((<?php echo $Order_detail['Order']['total']; ?>) / parseInt(persons)).toFixed(2)).toString());
                $("#aver_total_print").val((((<?php echo $Order_detail['Order']['total']; ?>) / parseInt(persons)).toFixed(2)).toString());
<?php } ?>
			
			

            var person_tab_Str = "";
			
			<?php if ($split_method != 0) { ?> //Modified by Yishou Liao @ Oct 20 2016.
			for (var i=0;i<$('#persons').val(); i++){
				if (i==0) {
                    person_tab_Str += '<li name="'+(i+1)+'" id="account_no_'+i+'" onclick="tabSelected('+(i+1)+');" class="active"><a data-toggle="tab"># '+(i+1)+'</a></li>';
				}else{
                    person_tab_Str += '<li name="'+(i+1)+'" id="account_no_'+i+'" onclick="tabSelected('+(i+1)+');"><a data-toggle="tab"># '+(i+1)+'</a></li>';
				};
			}
			<?php }else{ ?>
				var i = 0;
                person_tab_Str += '<li name="'+(i+1)+'" id="account_no_'+i+'" onclick="tabSelected('+(i+1)+');" class="active"><a data-toggle="tab"># '+(i+1)+'</a></li>';
			<?php } ?> //End.
			
			//End. person_id_Str += '</div>';;
            $('#person-tab').html(person_tab_Str);
            //End.
        }
        //End.
		function tabSelected(i) {
            var person_tab_idx = parseInt($('#person-tab').find('.active').attr('name'));
            if (i !== person_tab_idx) {
                showAcountingDetails(i);
            }
        }
		//Modified by Yishou Liao @ Oct 19 2016.
		function showAcountingDetails(i = 0){
			var radio_click=i;
			var subTotal = 0;
			var Tax = <?php echo $Order_detail['Order']['tax'] ?>;

            if (i === 0) {
                i = parseInt($('#person-tab').find('.active').attr('name'));
                radio_click = i;
            }

			var split_accounting_str = "";

			if (person_menu.length == 0 && current_person == 0) {
				for (var i=0; i<order_menu.length;i++){
					subTotal += parseFloat(order_menu[i][5]);
				};
			} else{
				for (var i=0; i<person_menu.length;i++){
					if (person_menu[i][0] == radio_click){
						subTotal += parseFloat(person_menu[i][5]);
					};
				};
			};
			
			split_accounting_str = '<ul>';
			
			split_accounting_str += '<li class="clearfix"><div class="row"><div class="col-md-3 col-sm-4 col-xs-4 sub-txt">Sub Total 小计 </div>';
			split_accounting_str += '<div class="col-md-3 col-sm-4 col-xs-4 sub-price">$ ' + subTotal.toFixed(2)+'</div>';
			<?php if ($Order_detail['Order']['table_status'] <> 'P' and ! $Order_detail['Order']['discount_value']) { ?>
			split_accounting_str += '<div class="col-md-6 col-sm-4 col-xs-4"><button type="button" class="addbtn pull-right add-discount"><i class="fa fa-plus-circle" aria-hidden="true"></i> Add Discount 加入折扣</button></div>'
            <?php } ?>
			split_accounting_str += '</div></li>';
			<?php if (!$Order_detail['Order']['discount_value']) { ?>
			split_accounting_str +='<li class="clearfix discount_view" style="display:none;"><div class="row"><div class="col-md-3"><div class="form-group">';
			split_accounting_str +='<label for="fix_discount" style="font-size:11px;">Fix Discount</label>';
            split_accounting_str +='<input type="text" id="fix_discount" required="required" class="form-control discount_section" maxlength="5"  name="fix_discount"></div></div>';
			split_accounting_str +='<div class="col-md-3"><div class="form-group"><label for="discount_percent" style="font-size:11px;">Discount in %</label><input type="text" id="discount_percent" required="required" class="form-control discount_section" maxlength="5"   name="discount_percent"></div></div>';
			split_accounting_str += '<div class="col-md-3"><div class="form-group"><label for="promocode" style="font-size:11px;">Promo Code</label>';
            split_accounting_str += '<input type="text" id="promocode" required="required" class="form-control discount_section" maxlength="200" name="promocode"></div></div>';
			split_accounting_str += '<div class="col-md-3"><div class="form-group"><label for="AdminTableSize" style="width:100%">&nbsp;</label>';
            split_accounting_str += '<a class="btn btn-primary btn-wide" id="apply-discount" href="javascript:void(0)">Apply <i class="fa fa-arrow-circle-right"></i></a></div></div></div></li>';
			<?php } ?>
			split_accounting_str += '<li class="clearfix"><div class="row">';
            split_accounting_str += '<div class="col-md-3 col-sm-4 col-xs-4 sub-txt">Tax 税 ('+Tax+'%)</div>';
			
			var Tax_Amount = subTotal*Tax/100;
			
            split_accounting_str += '<div class="col-md-3 col-sm-4 col-xs-4 sub-price">$'+Tax_Amount.toFixed(2)+'</div>';
            split_accounting_str += '</div></li>';
			
			var discount=0;//Modified by Yishou Liao @ Oct 21 2016.
			
			<?php if ($Order_detail['Order']['discount_value']) { ?>
			split_accounting_str += '<li class="clearfix"><div class="row"><div class="col-md-3 col-sm-4 col-xs-4 sub-txt">Discount 折扣</div><div class="col-md-3 col-sm-4 col-xs-4 sub-price">$ ';
			split_accounting_str += '<?php echo number_format($Order_detail['Order']['discount_value'],2); ?>';
			discount = <?php echo number_format($Order_detail['Order']['discount_value'],2); ?>;//Modified by Yishou Liao @ Oct 21 2016.
			<?php if ($Order_detail['Order']['percent_discount']) { ?>
			split_accounting_str += '<span class="txt12">';
			split_accounting_str += '<?php echo $Order_detail['Order']['promocode']; ?>';
			split_accounting_str += ' (';
			split_accounting_str += '<?php $Order_detail['Order']['percent_discount']; ?>';
			split_accounting_str += '%)</span>';
			<?php }; ?>;
			split_accounting_str += '<a aria-hidden="true" class="fa fa-times remove_discount" order_id="'+<?php echo $Order_detail['Order']['id']; ?>+'" href="javascript:void(0)"></a></div></div></li>';
			<?php } ?>
			
			split_accounting_str += '<li class="clearfix"><div class="row"><div class="col-md-3 col-sm-4 col-xs-4 sub-txt">Total 总</div>';
            split_accounting_str += '<div class="col-md-3 col-sm-4 col-xs-4 sub-price total_price" alt="';
			
			var Total_Amount = subTotal + (subTotal*Tax/100);
			
			split_accounting_str += (Total_Amount-discount).toFixed(2);//Modified by Yishou Liao @ Oct 21 2016.
			split_accounting_str += '">$ ';
			split_accounting_str += (Total_Amount-discount).toFixed(2);//Modified by Yishou Liao @ Oct 21 2016.
			split_accounting_str += '</div>';
			<?php if ($split_method == 0) { ?>
            split_accounting_str += '<div class="col-md-3 col-sm-4 col-xs-4 sub-txt">Average 人均:</div>';
            split_accounting_str += '<div class="col-md-3 col-sm-4 col-xs-4 sub-price total_price">$<input type="text" class="text-center" id="aver_total" name="aver_total" value="';
			split_accounting_str += '<?php echo number_format($Order_detail['Order']['total'], 2) ?>';
			split_accounting_str += '" readonly="true" size="5">/人</div>';
            <?php } ?>
			split_accounting_str += '</div></li>';
			<?php if ($Order_detail['Order']['table_status'] == 'P') { ?>
			split_accounting_str += '<li class="clearfix"><div class="row"><div class="col-md-3 col-sm-4 col-xs-4 sub-txt">Receive 收到</div>';
            split_accounting_str += '<div class="col-md-3 col-sm-4 col-xs-4 sub-price received_price">$ ';
			split_accounting_str += <?php echo $Order_detail['Order']['paid']; ?>;
			split_accounting_str += '</div><div class="col-md-3 col-sm-4 col-xs-4 sub-price cash_price">Cash 现金: $ ';
			split_accounting_str += <?php echo $Order_detail['Order']['cash_val']; ?>;
			split_accounting_str += '</div><div class="col-md-3 col-sm-4 col-xs-4 sub-price card_price">Card 卡: $ ';
			split_accounting_str += <?php echo $Order_detail['Order']['card_val']; ?>;
			split_accounting_str += '</div></div></li>';
			
			<?php if ($Order_detail['Order']['change']) { ?>
			split_accounting_str += '<li class="clearfix"><div class="row"><div class="col-md-3 col-sm-4 col-xs-4 sub-txt change_price_txt">Change 找零</div>';
            split_accounting_str += '<div class="col-md-3 col-sm-4 col-xs-4 sub-price change_price">$ ';
			split_accounting_str += <?php echo $Order_detail['Order']['change']; ?>;
			split_accounting_str += '</div></div></li>';
			<?php } ?>
			split_accounting_str += '<li class="clearfix"><div class="row"><div class="col-md-3 col-sm-4 col-xs-4 sub-txt">Tip 小费</div>';
            split_accounting_str += '<div class="col-md-3 col-sm-4 col-xs-4 sub-price tip_price">$ ';
			split_accounting_str += <?php echo $Order_detail['Order']['tip']; ?>;
			split_accounting_str += '</div></div></li>';
			<?php } else { ?>
			split_accounting_str += '<li class="clearfix"><div class="row"><div class="col-md-3 col-sm-4 col-xs-4 sub-txt">Receive 收到</div>';
            split_accounting_str += '<div class="col-md-3 col-sm-4 col-xs-4 sub-price received_price">$00.00</div>';
			split_accounting_str += '<div class="col-md-3 col-sm-4 col-xs-4 sub-price cash_price">Cash 现金: $00.00</div>';
            split_accounting_str += '<div class="col-md-3 col-sm-4 col-xs-4 sub-price card_price">Card 卡: $00.00</div></div></li>';
            split_accounting_str += '<li class="clearfix"><div class="row"><div class="col-md-3 col-sm-4 col-xs-4 sub-txt change_price_txt">Remaining 其余</div>';
            split_accounting_str += '<div class="col-md-3 col-sm-4 col-xs-4 sub-price change_price">$00.00</div></div></li>';
			split_accounting_str += '<li class="clearfix"><div class="row"><div class="col-md-3 col-sm-4 col-xs-4 sub-txt">Tip 小费</div>';
            split_accounting_str += '<div class="col-md-3 col-sm-4 col-xs-4 sub-price tip_price">$00.00</div><div class="col-md-6">';
            split_accounting_str += '<div class="form-group"><div class="control-label col-md-4 sub-txt">Paid by:</div>';
            split_accounting_str += '<div class="col-md-8"><label class="control-label">Card  卡 <input name="tip_paid_by"  class="tip_paid_by" value="CARD" type="radio"></label>&nbsp;&nbsp;&nbsp;<label class="control-label">Cash 现金 <input name="tip_paid_by"  class="tip_paid_by" value="CASH" type="radio"></label></div></div></div></div></li>';
			<?php } ?>                   
			
			split_accounting_str += '</ul>';
			
			$('#split_accounting_details').html(split_accounting_str);
		}
		//End.
		
		//Modified by Yishou Liao @ Oct 19 2016.
		function print_receipt(){
			var radio_click=0;
			var print_String="";
			var account_String="";
			var sub_total=0;
			var Tax=<?php echo $Order_detail['Order']['tax'] ?>;
			var person_menu_print = Array();//Modified by Yishou Liao @ Oct 27 2016.
			
			//Modified by Yishou Liao @ Oct 27 2016.
			<?php if ($split_method == 0) { ?>
			for (var i=0;i<order_menu.length;i++){
				sub_total += parseFloat(order_menu[i][5]);
				person_menu_print.push(Array("","","",order_menu[i][2],order_menu[i][3],"",order_menu[i][5],"1")); //Modified by Yishou Liao @ Oct 27 2016.

			};
			<?php }else{ ?>
			/*$('input[name="account_no[]"]:checked').each(function () {
	           radio_click = $(this).val();
        	});*/
            radio_click = parseInt($('#person-tab').find('.active').attr('name'));
			
			//radio_click=1;
			//Modified by Yishou Liao @ Oct 20 2016.
			if (person_menu.length == 0){
				for (var i=0; i<order_menu.length; i++){
					person_menu.push(Array(order_menu[i][0],order_menu[i][1],order_menu[i][2],order_menu[i][3],order_menu[i][4],order_menu[i][5],order_menu[i][6],order_menu[i][7],order_menu[i][8],radio_click));
				};
			}
			//End.
			
			for (var i=0;i<person_menu.length;i++){
				if (person_menu[i][0] == radio_click){
					sub_total += parseFloat(person_menu[i][5]);
					person_menu_print.push(Array("","","",person_menu[i][2],person_menu[i][3],"",person_menu[i][5],"1")); //Modified by Yishou Liao @ Oct 27 2016.
				};
			};
			<?php } ?>

			var tax_amount = (sub_total*Tax/100).toFixed(2);
			var total=(parseFloat(sub_total)+parseFloat(tax_amount)).toFixed(2);
			var memo = "";
			<?php if ($split_method == 0) { ?>
				memo = "Average 人均: CAD$ " + $('#aver_total').val() +"/人";
			<?php } ?>
			
			printReceipt('<?php echo $Order_detail['Order']['order_no'] ?>',"<?php echo  (($type=='D') ? '[[堂食]]' : (($type=='T') ? '[[外卖]]' : (($type=='W') ? '[[等候]]' : ''))); ?>#<?php echo $table; ?>",'192.168.0.188','local_printer',person_menu_print,''+sub_total.toFixed(2),Tax,''+total,memo);
			
		}
		//End.
		
		//Modified by Yishou Liao @ Oct 21 2016.
		function setCookie(c_name,value,expiredays)
		{
			var exdate=new Date()
			exdate.setDate(exdate.getDate()+expiredays)
			document.cookie=c_name+ "=" +escape(value)+
			((expiredays==null) ? "" : ";expires="+exdate.toGMTString())
		}
		
		function getCookie(c_name)
		{
			if (document.cookie.length>0)
			  {
			  c_start=document.cookie.indexOf(c_name + "=")
			  if (c_start!=-1)
				{ 
					c_start=c_start + c_name.length+1 
					c_end=document.cookie.indexOf(";",c_start)
					if (c_end==-1) c_end=document.cookie.length
					return unescape(document.cookie.substring(c_start,c_end))
				} 
			  }
			return ""
		}
		
		function checkCookie(c_name)
		{
			if (getCookie(c_name)!=null && getCookie(c_name)!="")
			  {return true;}
			else 
			  {return false;}
		}
		
		function deleteCookie(c_name)
		{
			setCookie(c_name, "", -1);
		}
		
		function arrtostr(c_array){//将二维数组转换为字符串。
			var strarray = Array();
			var restr = "";
			
			for (var i=0;i<c_array.length;i++){
				strarray.push(c_array[i].join("-"));
			};
			
			restr = strarray.join(",");
			
			return restr;
		}
		
		function strtoarr(c_string){//将字符串转换为二维数组。
			var strarray;
			var rearr = Array();
			
			strarray = c_string.split(",");
			
			for (var i=0;i<strarray.length;i++){
				rearr.push(strarray[i].split("-"));
			};
			
			return rearr;
		}
		//End.
    </script>