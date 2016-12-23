<header class="product-header">
    <div style="display:none;">
        <canvas id="canvas" width="512" height="480"></canvas>
        <?php echo $this->Html->image("logo.png", array('alt' => "POS", 'id' => "logo")); ?>
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
            <h2>Order 订单号 #<?php echo $Order_detail['Order']['order_no'] ?><br/>Table 桌 <?php echo (($type == 'D') ? '[[Dinein]]' : (($type == 'T') ? '[[Takeout]]' : (($type == 'W') ? '[[Waiting]]' : ''))); ?>#<?php echo $table; ?></h2>
            <?php
            if ($Order_detail['Order']['table_status'] <> 'P') {
                ?>
                <!-- Modified by Yishou Liao @ Oct 17 2016. -->

                <div class="table-box dropdown">
                    <!-- Modified by Yishou Liao @ Nov 10 2016 -->
                    <?php
                    if ($split_method == 0) {
                        ?>
                        <a href="" class="split dropdown-toggle"  data-toggle="dropdown">Split 平均分单 <input type="text" class="text-center" readonly="readonly" id="av_persons" name="av_persons" value="1" size="2"> 人</a>
                        <?php
                    } else {
                        ?>
                        <a class="split dropdown-toggle">Split 按人分单</a>
                        <?php
                    }
                    ?>
                    <!-- End -->

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
            <div class="order-summary <?php if ($split_method == 0) echo 'avg'; ?>">
                <h3>Order Summary 订单摘要</h3>
                <div class="order-summary-indent clearfix" name="orderitem" id="orderitem">
                    <!-- Modified by Yishou Liao @ Oct 18 2016. -->
                </div>
				<button class="btn btn-lg btn-primary avoid-this" id="avgSplit">Avg. Split</button>
            </div>

            <!-- Modified by Yishou Liao @ Oct 18 2016. -->
				<div>
					<div id="addperson" class="avoid-this text-center addperson ">
	                	<button type="button" class="btn btn-primary btn-lg">Add Person 增加人</button>
	                </div>
	                <div id="deleteperson" class="avoid-this text-center deleteperson">
	                	<button class="btn btn-danger btn-lg" onclick="deletePerson()">Delete Person 删除人</button>
	                </div>
				</div>
               
                <div id="person_details" class="order-summary addperson" style="display:none"> <!-- Modified by Yishou Liao @ Nov 10 2016 -->
                    <h3>Split Details 分单明细</h3>
                    <div class="order-summary-indent addperson clearfix" name="splitmenu" id="splitmenu"></div>
                </div>
            </div>
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
                <button type="button" class="btn btn-success card-ok"  id="pay-confirm"><?php echo $this->Html->image("right.png", array('alt' => "right")); ?> Confirm 确认</button>

                <button type="button" class="btn btn-success card-ok"  id="submit"><?php echo $this->Html->image("right.png", array('alt' => "right")); ?> Submit</button>

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

<?php
echo $this->Html->script(array('jquery.min.js', 'bootstrap.min.js', 'jquery.mCustomScrollbar.concat.min.js', 'barcode.js', 'epos-print-5.0.0.js', 'fanticonvert.js', "notify.min.js", 'avgsplit.js'));
echo $this->fetch('script');
?>
<script>
	$('#customer-select-alert').hide();
    //Modified by Yishou Liao @ Oct 18 2016.
    var person_No = 0; // only addPerson and deletePerson can modify this variable
    //  initialize

    var current_person = '0';
    var current_person_tab = '1';
    var person_menu = new Array();  // [0]: person_id  [8]: item_id
    var order_menu = new Array();
	var discount = 0;
	var split_method = parseInt(<?php echo $split_method ?>);
	var order_no = <?php echo $Order_detail['Order']['order_no'] ?>;
	var person_paid = new Array(); // person who have paid money
	var suborder_detail = new Array();
	var paid_info = new Array();

	console.log("order_no");
	console.log(order_no);
	// load data from cookie



	if (checkCookie("persons_" + order_no) && getCookie("persons_" + order_no) != "undefined") {
		console.log("line 171");
		person_No = getCookie("persons_" + order_no);
		console.log(person_No);
	}

	if (checkCookie("person_menu_" + order_no) ) {
		
		var personarray = getCookie("person_menu_" + order_no);
		
	    if (personarray != "") {
		    person_menu = strtoarr(personarray);
	    }
	}


	// load order_menu
	// if order changed, all other cookie record will be deleted
	if (checkCookie("order_menu" + order_no)) {
		var orderarray = getCookie("order_menu" + order_no);
		if (orderarray != ""){
		    order_menu = strtoarr(orderarray);
	    }

	    // if order changed, clear all the cookie 
	    if (isOrderChanged()) {
	    	order_menu = loadOrderMenu();
	    	person_menu = new Array();
	    	person_No = 0;

	    	deleteCookie("person_menu_" + order_no);
	    	deleteCookie("persons_" + order_no);
	    	deleteCookie("order_menu" + order_no);

	    	setCookie("order_menu" + order_no, arrtostr(order_menu), 1);
	    }
	} else {
		order_menu = loadOrderMenu();
		setCookie("order_menu" + order_no, arrtostr(order_menu), 1);
	}


	
	drawAllPage();
	
	// $('#split_accounting_details').props("background", "url('https://dummyimage.com/600x200/ffffff/bfb0bf.png&text=Paid')");
	// check paid person and paid info
	if (checkCookie("persons_sele_" + order_no) && getCookie("persons_sele_" + order_no) != "undefined") {
		person_paid = getPaidPerson();
		if (person_paid.length > 0) {
			disableSubOrderModify();
			console.log("line 240");
			if (checkCookie("paid_info_" + order_no) && getCookie("paid_info_" + order_no) != "undefined") {
				console.log("line 242");
				paid_info = strtoarr(getCookie("paid_info_" + order_no));
			}
		}
	}

	console.log("person paid");
	console.log(person_paid);





	function drawAllPage() {
	    //  add customer # label
	    drawPersonNumList();

	   	// show order details
	   	drawOrderSummary();

	    //  add existed menu item from person_menu
	    drawPersonSummary();

	    drawPersonTab();

	    drawPersonTabDetail();
	}

	// judge whether orer is paid by person_sele_ cookie
	// TODO
	// if part of order is paid disable all other buttons
	function getPaidPerson () {
		var paidPerson = new Array();

		var paidPersonStr = "";
		if (checkCookie("persons_sele_" + order_no)){
			paidPersonStr = getCookie("persons_sele_" + order_no);
		};

		if (paidPersonStr != "" && paidPersonStr != "undefined") {
			paidPerson = paidPersonStr.split(',')
		}
		
		return paidPerson; // ['1', '2', '3']
	}

	// build order summary by order_menu
	function drawOrderSummary () {
	    var outhtml_str = "<ul>";
	    for (var i = 0; i < order_menu.length; i++) {
	    	// console.log("line 270");
	    	if (order_menu[i][9] == 'keep') {

			    outhtml_str += '<li class="clearfix" onclick=\'javascript:addMenuItem(' + String(order_menu[i][0]) + ', "' + order_menu[i][1] + '", "' + order_menu[i][2] + '", "' + order_menu[i][3] + '","' + order_menu[i][4] + '","' + order_menu[i][5] + '","' + order_menu[i][6] + '","' + order_menu[i][7] + '", "' + order_menu[i][8] + '", "' + 'assigned"' + ' );\'>';
			    outhtml_str += '<div class="row"><div class="col-md-9 col-sm-8 col-xs-8"><div class="pull-left titlebox1">';
			    outhtml_str += '<div class="less-title">' + order_menu[i][2] + '<br/>' + order_menu[i][3] + '</div><div class="less-txt">' + order_menu[i][4] + '</div></div></div><div class="col-md-3 col-sm-4 col-xs-4 text-right price-txt">$';
				//Modified by Yishou Liao @ Dec 16 2016
				if (order_menu[i][6]!=""){
					outhtml_str += (parseFloat(order_menu[i][5],2) + parseFloat(order_menu[i][6],2)) + order_menu[i][7] + '</div></div></li>'
				}else{
					outhtml_str += order_menu[i][5] + order_menu[i][6] + order_menu[i][7] + '</div></div></li>'
				}
				//End @ Dec 16 2016
			}
	    }
	    outhtml_str += "</ul>";
	    $('#orderitem').html(outhtml_str);
	} 

	// build person no. list by person_No
	function drawPersonNumList () {
		$('#person_details').css('display', 'block');
		$('#splitmenu').empty();
		for (var i = 1; i <= person_No; ++i) {
		    $('#splitmenu').append("<br /><label class='person-label' id='person-label" + i + "'" + " onclick='javascript:setCurrentPerson(" + i + ");'>Customer # " + i + "</label><ul>"); // TODO
	    }
	}

	// update person tab
	// all paid orders are stored in person_paid
	function drawPersonTab() {
		// $('#person-tab').empty()
		person_tab_Str = "";
		for (var i = 0; i < person_No; i++) {
			if (i == 0) {
				person_tab_Str += '<li name="account_no[]" data-tabIdx="' + (i + 1) + '" id="account_no_' + i + '" onclick="tabSelected(' + (i + 1) + ');" class="active"><a data-toggle="tab"># ' + (i + 1) + '</a></li>';
			}
			else {
				person_tab_Str += '<li name="account_no[]" data-tabIdx="' + (i + 1) + '" id="account_no_' + i + '" onclick="tabSelected(' + (i + 1) + ');"><a data-toggle="tab"># ' + (i + 1) + '</a></li>';
			}
			
		}

		$('#person-tab').html(person_tab_Str);



		/*for (var i = 0; i < person_No; i++) {
			if (i == 0) {
				if (selepersonstr.indexOf(i + 1) != - 1){
					person_tab_Str += '<li name="account_no[]" data-tabIdx="' + (i + 1) + '" id="account_no_' + i + '" class="disabled"><a data-toggle="tab" class="disabled"># ' + (i + 1) + '</a></li>';

				}else{
					person_tab_Str += '<li name="account_no[]" data-tabIdx="' + (i + 1) + '" id="account_no_' + i + '" onclick="tabSelected(' + (i + 1) + ');" class="active"><a data-toggle="tab"># ' + (i + 1) + '</a></li>';
				};
			} else{
				if (selepersonstr.indexOf(i + 1) != - 1){
					person_tab_Str += '<li name="account_no[]" data-tabIdx="' + (i + 1) + '" id="account_no_' + i + '" class="disabled"><a data-toggle="tab" class="disabled"># ' + (i + 1) + '</a></li>';
				}else{
					person_tab_Str += '<li name="account_no[]" data-tabIdx="' + (i + 1) + '" id="account_no_' + i + '" onclick="tabSelected(' + (i + 1) + ');"><a data-toggle="tab"># ' + (i + 1) + '</a></li>';
				};
			};
		};
		
		$('#person-tab').html(person_tab_Str);*/

	}

	// build person no. tab by person_No and acountingDetail
	function drawPersonTabDetail() {
		showAcountingDetails();
	}

	// build person summary by person_menu
	function drawPersonSummary () {

		$('#person-label' + personId).next('ul').empty();


	    for (var i = 0; i < person_menu.length; i++) {

	    	var personId = person_menu[i][10];
	    	var itemId = person_menu[i][0];
	    	// console.log(personId);

	    	addpersonStr = "<li class='clearfix' onclick='javascript:delMenuItem(" + itemId + "," + person_menu[i][8] + ");'><div class='row'><div class='col-md-9 col-sm-8 col-xs-8'><div class='pull-left titlebox1'><div class='less-title'>" + person_menu[i][2] + "<br />" + person_menu[i][3] + "</div><div class='less-txt'> </div></div></div><div class='col-md-3 col-sm-4 col-xs-4 text-right price-txt'>$";
	    	
	    	if (person_menu[i][6]!="") {
	    		addpersonStr += parseFloat(person_menu[i][5],2) + parseFloat(person_menu[i][6],2) + person_menu[i][7] + "</div></div></li>";
    		}else{
				addpersonStr += person_menu[i][5] + person_menu[i][6] + person_menu[i][7] + "</div></div></li>";
			}

			// console.log(addpersonStr);

			$('#person-label' + personId).next('ul').append(addpersonStr);
		}
	}

	function countAvailableOrderMenu () {
		var cnt = 0;
		for (var i = 0; i < order_menu.length; ++i) {
			if (order_menu[i][9] == "keep") {
				++cnt;
			}
		}
		console.log(cnt);
		return cnt;
	}

	// addPerson: ++person_No, rebuild person no. list add all person_menu items
	function addPerson () {

		$("#person_details").css("display", "block");
		console.log("line 365");


		if (countAvailableOrderMenu() > 0) {
			++person_No;

			setCurrentPerson(String(person_No));

			drawAllPage();

			setCookie("persons_" + order_no, person_No, 1);
		} else {
			alert("There is no avaliable item in order summary.")
		}

		
	}

	// deletePerson: --person_No, rebuild person no. list, remove items in person_menu equals to last person id

	function deletePerson () {
		if (person_No > 0) {
			$("#person_details").css("display", "block");


			deletePersonFromPersonMenu(person_No);

			--person_No;

			setCurrentPerson(String(person_No));

			
			drawAllPage();

			setCookie("persons_" + order_no, person_No, 1);
		} else {
			alert("No person to be deleted");
		}
		
	}

	// addMenuItem: push array of item to person_menu with current_person id, remove item from order_menu
	// rebuild all

	// deleteMenuItem: remove item from person_menu and add item to order_menu
	// rebuild all

	// addAvgMenuItem: push array of item to person_menu of all person_id, remove item from order_menu
	// use flag to tag whether item is shared
	// rebuild all

	// deleteMenuItem: remove all shared item from person_menu by flag, add item to order_menu
	// rebuild all

	// important
	// when doing add delete operation, should reset cookie


	// think about delete shared item
	function deletePersonFromPersonMenu (person_id) {
		person_id = String(person_id)
		var deletedItemId = new Array();
		var processedItems = new Array();

		// delete item, from end to start
		for (var i = person_menu.length - 1; i >= 0; --i) {
			console.log("deletePersonFromPersonMenu");
			if (person_menu[i][10] == person_id) {
				deletedItemId.push(person_menu[i][0]);
			}
		}

		for (var i = 0; i < deletedItemId.length; ++i) {
			var item_id = deletedItemId[i];

			deleteItemFromPersonMenu(item_id);
		}

		updateAllCookies();
	}

	function deleteItemFromPersonMenu (item_id) {
		for (var i = person_menu.length - 1; i >= 0; --i) {
			if (person_menu[i][0] == item_id) {
				person_menu.splice(i, 1);
			}
		}

		setOrderMenuState(item_id, "keep");

		updateAllCookies();
	}

	function addItemToPersonMenu (person_id = current_person, item) {
		person_menu.push(tempItem);
		person_menu.sort(function(x, y){return x[0] - y[0]});

		deleteCookie("person_menu" + order_no);
		setCookie("person_menu" + order_no, arrtostr(person_menu), 1);
	}

	function addItemToOrderMenu (item) {
		order_menu.push(item);
		order_menu.sort(function(x, y){return x[0] - y[0]}); //二维数组排序

		deleteCookie("order_menu" + order_no);
		setCookie("order_menu" + order_no, arrtostr(order_menu), 1);
	}

	function deleteItemFromOrderMenu (person_id = current_person, item_id) {
		
		if (String(person_id) != '0') {
			var deletedItem = new Array();
			var item_no = 0;
			for (var i = 0; i < order_menu.length; ++i) {
				if (parseInt(item_id) == order_menu[i][0]) {
					deletedItem = order_menu[i];
					item_no = String(deltedItem[0]);
					deletedItem[0] = String(person_id);
					deletedItem.push(item_no);

					order_menu.splice(i, 1);
				}
			}


			deleteCookie("order_menu" + order_no);
			setCookie("order_menu" + order_no, arrtostr(order_menu), 1);

			addItemToPersonMenu (person_id, deletedItem)
			//  add deleted item to person_menu
		} else {
			alert("function deleteItemFromOrderMenu Please Select Person");
		}
	}

	function loadOrderMenu() {
		var temp_menu = new Array();

		<?php
		if (!empty($Order_detail['OrderItem'])) {
		    $i = 0;
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

	            temp_menu.push(Array(
	            	'<?php echo $i ?>', //0
		            '<?php
				        if ($value['image']) {
				            echo $value['image'];
				        } else {
				            echo 'no_image.jpg';
				        };
				    ?>',  //1
				    '<?php echo $value['name_en']; ?>', //2 
				    '<?php echo $value['name_xh']; ?>', //3
				    '<?php echo implode(",", $selected_extras_name); ?>', //4 
				    '<?php echo $value['price'] ?>', //5
				    '<?php echo $value['extras_amount'] ?>', //6
				    '<?php echo $value['qty'] > 1 ? "x" . $value['qty'] : "" ?>', //7
				    '<?php echo $value['id'] ?>', //8
				    'keep' //9
			    ));
	    <?php
			   	$i++;
		 	} // line 563 foreach
	    ?>

    <?php 
		} // line 561 if  
	?>
		return temp_menu;
	}

	function isOrderChanged () {
		var changed = false;
		var temp_menu = loadOrderMenu();

		if (temp_menu.length != order_menu.length) {
			return true;
		} else {
			for (var i = 0; i < temp_menu.length; ++i) {
				if (order_menu[i][8] != temp_menu[i][8]) {
					changed = true
				}
			}
		}

		return changed;
	}

	// to be changed
	function setOrderMenuState (item_id, state) {
		var stateList = Array("keep", "assigned", "share");
		if (stateList.indexOf(state) != -1) {
			order_menu[item_id][9] = state;
		} else {
			alert("State Errors: No existed state");
		}
		
	}


	function addMenuItem(item_id, image, name_en, name_xh, selected_extras_name, price, extras_amount, qty, order_item_id, state) {

		if (String(current_person) != '0') {
			// console.log()
			person_menu.push( Array(
					item_id,//0
					image, //1
					name_en, //2
					name_xh, //3
					selected_extras_name, //4 
					price, //5
					extras_amount, //6 
					qty, //7
					order_item_id, //8
					"assigned", //9
					String(current_person) //10
				));

			setOrderMenuState(item_id, "assigned");

			person_menu.sort(function(x, y){return x[10] - y[10]});

			drawAllPage();

			updateAllCookies();
		} else {
			alert("Select current person");
		}
	}

	function updateAllCookies() {
		deleteCookie("order_menu" + order_no);
		deleteCookie("person_menu_" + order_no);
		deleteCookie("persons_" + order_no);
		setCookie("order_menu" + order_no, arrtostr(order_menu), 1);
		setCookie("person_menu_" + order_no, arrtostr(person_menu), 1);
		setCookie("persons_" + order_no, person_No, 1);
	}


	function disableSubOrderModify() {
		$('#avgSplit').prop('disabled', true);
		$('#addperson button').prop('disabled', true);
		$('#deleteperson button').prop('disabled', true);
		$('.person-label').removeAttr('onclick');
		$('.person-label + ul li').removeAttr('onclick');
	}

	// calculate the suborder based on person_menu
	function calculateSubOrder() {
		suborder_detail = new Array ();

		for (var i = 0; i < person_No; ++i) {
			suborder_detail.push({
				'subtotal': 0,
				'tax': 0,
				'discount': 0,
				'total': 0
			});
		}
		
		for (var i = 0; i < person_menu.length; ++i) {

			var person_id = parseInt(person_menu[i][10]) - 1;
			var price = round2(parseFloat(person_menu[i][5]));

			suborder_detail[person_id]['subtotal'] += price;

		}

		for (var i = 0; i < suborder_detail.length; ++i) {
			suborder_detail[i]['subtotal'] = round2(suborder_detail[i]['subtotal']);
			suborder_detail[i]['tax'] = round2(suborder_detail[i]['subtotal'] * 0.13) ;
			suborder_detail[i]['total'] = round2(suborder_detail[i]['subtotal'] + suborder_detail[i]['tax'] - suborder_detail[i]['discount']);
		}

		// store the information in the cookie
	}


	console.log("initailize person_No");
	console.log(person_No);


	console.log("initialize order_menu");
    console.log(order_menu);
    console.log("initialize person_menu");
    console.log(person_menu);


	// console.log(order_no);

	// order_menu
	// 0: id
	// 1: img
	// 2: en name
	// 3: zh name
	// 4:
	// 5: price
	// 6
	// 7
	// 8
	$('#avgSplit').on('click', function() {
		//  order_menu
		//  person_No
		// person_No = $('#splitmenu .person-label').length;
		if (person_No > 1 && countAvailableOrderMenu() > 0) {
			for (var i = 0; i < order_menu.length; ++i) {
				var current_menu = order_menu[i];
				if (current_menu[9] == 'keep') {
					var item_id = current_menu[0];
					setOrderMenuState(item_id, 'share');
					
					
					for (var j = 1; j <= person_No; ++j) {
						current_person = String(j);
						var avg_price = (current_menu[5] / person_No).toFixed(2);
						var avg_name_en = current_menu[2] + ' /' + person_No;
						var avg_name_zh = current_menu[3] + ' /' + person_No;

						console.log(avg_price);
						console.log(avg_name_en);
						console.log(avg_name_zh);


						addMenuItem(item_id, current_menu[1], avg_name_en, avg_name_zh, current_menu[4], avg_price ,current_menu[6], current_menu[7], current_menu[8], 'share', current_person);
					}
				}
			}
		} else {
			alert("Please make sure you have more than two people to share, or more than one item to be shared.");
		}

		
	});


	$('#revokeAvgSplit').on('click', function () {
		for (var i = 0; i < person_menu.length; ++i) {
			item_id = person_menu[i][0];
		}
	});

	$('#addperson').on('click', function () {
		addPerson();
	});

	function setCurrentPerson (currentPerson) {
		current_person = currentPerson;
		
		$('#customer-select-alert').alert();
		$('#customer-select-alert #customer-number').html(current_person);
		$('#customer-select-alert').fadeTo(500, 500).fadeOut(500, function() {});
	}
	
    function setCurrentPerson1(currentPerson){
		//Modified by Yishou Liao @ Oct 21 2016.
		var selepersonstr = "";
		if (checkCookie("persons_sele_" +<?php echo $Order_detail['Order']['order_no'] ?>)){
			selepersonstr = getCookie("persons_sele_" +<?php echo $Order_detail['Order']['order_no'] ?>);
		};
		if (selepersonstr.indexOf(currentPerson) != - 1){
			return;
		};
		//End.
		current_person = currentPerson;
		//Modified by Yishou Liao @ Oct 19 2016.
		var sele_person = "account_no_" + (current_person - 1);
		document.getElementById(sele_person).checked = true;


		// calculate order details
		showAcountingDetails();
		//End.
	
		$('#customer-select-alert').alert();
		$('#customer-select-alert #customer-number').html(current_person);
		$('#customer-select-alert').fadeTo(500, 500).fadeOut(500, function() {});
    }

    function delMenuItem(item_id, order_item_id) {
    	$('#confirm #dish-to-be-deleted').html(person_menu[item_id][2]);
    	$('#confirm').modal('show').one('click', '#delete', function() {
    		deleteItemFromPersonMenu(item_id);

    		drawAllPage();

    		updateAllCookies();
    	});

    }


    $(document).on('click', '.reprint', function () {
    	print_receipt(); //Modified by Yishou @ Nov 08 2016.
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

    $(document).ready(function () {
    	
    	


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
	    });

	    $(".select_tip").click(function () {
		    $(".select_card").removeClass("active");
		    $(this).toggleClass("active");
		    var val = $("#tip_val").val() ? parseFloat($("#tip_val").val()) * 100 : 0;
		    $("#screen").attr('buffer', val);
		    $("#screen").val($("#tip_val").val());
	    });




	//  once user want to, all modification operation will be disabled
	$("#pay-confirm").click(function () {
		if (countAvailableOrderMenu() > 0) {
    		$.notify("请将所有订单分单完毕以后再付账。", {
	                        position: "top center", 
	                        className:"warn"
	                    });
    		return false;
    	} else {
    		disableSubOrderModify();
    	}

    	for (var i = 0; i < person_paid.length; ++i) {
    		if (person_paid[i] != current_person_tab) {
    			person_paid.push(current_person_tab);
    		}
    	}
    	
    	deleteCookie("persons_sele_" + order_no);
    	setCookie("persons_sele_" + order_no, person_paid.join(','), 1);


    	if (paid_info.length < person_No) {
    		for (var i = paid_info.length; i < person_No; ++i) {
    			paid_info.push(new Array(10));
	    	}
    	}

    	storeOrderDetail();

    	// whether all tab is paid?
    		// yes jump to the first unpaid tab
    		// no 
	});

	// store order detail in paid_info
	// todo
	// 
	function storeOrderDetail() {

		calculateSubOrder();

		var tempArray = [];
		// current_person_tab = 1;
		// var tab_id = parseInt(current_person_tab) - 1;
		tempArray[0] = suborder_detail[tab_id]['subtotal']; // subtotal 0
		tempArray[1] = suborder_detail[tab_id]['tax']; // tax 1
		tempArray[2] = suborder_detail[tab_id]['discount']; // discount 2
		tempArray[3] = suborder_detail[tab_id]['total']; // total 3
		tempArray[4] = $(".received_price").attr("amount") ? parseFloat($(".received_price").attr("amount")) : 0; //receive 4
		tempArray[5] = $("#cash_val").val() ? parseFloat($("#cash_val").val()) : 0; // received_cash 5
		tempArray[6] = $("#card_val").val() ? parseFloat($("#card_val").val()) : 0; // received_card 6
		tempArray[7] = $(".change_price").attr("amount") ? parseFloat($(".change_price").attr("amount")) : 0; // change 7
		tempArray[8] = $("#tip_val").val() ? parseFloat($("#tip_val").val()) : 0; //tip 8
		tempArray[9] = $("#tip_paid_by").val() ? parseFloat($("#tip_paid_by").val()) : 0;

		deleteCookie('paid_info_' + order_no);

		setCookie('paid_info_' + order_no, arrtostr(paid_info), 1);
	}



	// suubmit all info to the database
	// it should iterator all sub-orders and send them to the database
    $("#submit").click(function () {

	    if (countAvailableOrderMenu() > 0) {
    		$.notify("请将所有订单分单完毕以后再付账。", {
	                        position: "top center", 
	                        className:"warn"
	                    });
    		return false;
    	}


    if ($("#selected_card").val()) {
	    if (parseFloat($(".change_price").attr("amount")) >= 0) {

		    // check tip type(card/cash) if exists
		    if (parseFloat($("#tip_val").val())) {
			    if (!$("#tip_paid_by").val()) {
			    	$.notify("Please select tip payment method card or cash \n 请选择提示付款方式卡或现金. ", {
			                        position: "top center", 
			                        className:"warn"
			                    });
				    return false;
			    }
		    }
	    //Modified by Yishou Liao @ Oct 19 2016.
	    var radio_click = 0;
	    radio_click = parseInt($('#person-tab').find('.active').attr('data-tabIdx'));
	    //End.

	    //Modified by Yishou Liao @ Oct 21 2016.
	    /*if (checkCookie("persons_sele_" +<?php echo $Order_detail['Order']['order_no'] ?>)){
	    var seletmp = "" + getCookie("persons_sele_" +<?php echo $Order_detail['Order']['order_no'] ?>) + "," + radio_click;
	    } else{
	    var seletmp = "" + radio_click;
	    };
	    setCookie("persons_sele_" +<?php echo $Order_detail['Order']['order_no'] ?>, seletmp, 1);
	    //End.*/

	    //Modified by Yishou Liao @ Oct 20 2016.
	    var item_detail_id = "";
	    for (var i = 0; i < person_menu.length; i++){
		    if (radio_click == person_menu[i][0]){
			    item_detail_id += person_menu[i][9] + ",";
		    }
	    }
	    item_detail_id = item_detail_id.substr(0, (item_detail_id.length - 1));
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
	                order_detail:item_detail_id,
					discount:discount,
	        },
	        success: function (html) {
	            $(".alert-warning").hide();
	            $(".reprint").trigger("click");
	            //Modified by Yishou Liao @ Oct 20 2016.
	            
	                //Modified by Yishou Liao @ Oct 21 2016.
	                /*setCookie("order_menu" +<?php echo $Order_detail['Order']['order_no'] ?>, arrtostr(order_menu), 1);
	                setCookie("person_menu_" +<?php echo $Order_detail['Order']['order_no'] ?>, arrtostr(person_menu), 1);
	                setCookie("persons_" +<?php echo $Order_detail['Order']['order_no'] ?>, $("#persons").val(), 1);

	                
	                if (checkCookie("persons_sele_" +<?php echo $Order_detail['Order']['order_no'] ?>)){
	                var seletmp = getCookie("persons_sele_" +<?php echo $Order_detail['Order']['order_no'] ?>);
	                };
	                //End.
	                if (seletmp.split(",").length == $('#account_no').attr('length')){//Modified by Yishou Liao @ Oct 21 2016.
		                deleteCookie("order_menu" +<?php echo $Order_detail['Order']['order_no'] ?>);
		                deleteCookie("person_menu_" +<?php echo $Order_detail['Order']['order_no'] ?>);
		                deleteCookie("persons_" +<?php echo $Order_detail['Order']['order_no'] ?>);
		                deleteCookie("persons_sele_" +<?php echo $Order_detail['Order']['order_no'] ?>);
						//Modified by Yishou Liao @ Nov 19 2016
						deleteCookie("fix_discount_"+<?php echo $Order_detail['Order']['order_no'] ?>);
						deleteCookie("discount_percent_"+<?php echo $Order_detail['Order']['order_no'] ?>);
						deleteCookie("promocode_"+<?php echo $Order_detail['Order']['order_no'] ?>);
						deleteCookie("discount_type_" +<?php echo $Order_detail['Order']['order_no'] ?>);
						deleteCookie("discount_value_" +<?php echo $Order_detail['Order']['order_no'] ?>);
						//End
		                window.location = "<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'dashboard')); ?>";
	                } else{//Modified by Yishou Liao @ Oct 21 2016.
	                window.location.reload();
	                }; //End. */


	                // store payment info in the cookie

	                window.location.reload();

	            //End.
	            },
	        beforeSend: function () {
	            $(".RIGHT-SECTION").addClass('load1 csspinner');
	            $(".alert-warning").show();
	            }
	    });
	    } else {
	    	$.notify("Invalid amount, please check and verfy again 金额无效，请检查并再次验证.", {
	                        position: "top center", 
	                        className:"warn"
	                    });
		    return false;
	    }
    } else {
    	$.notify("Please select card or cash payment method 请选择卡或现金付款方式. ", {
                        position: "top center", 
                        className:"warn"
                    });
	    return false;
    }
    })

            $(".card-indent li").click(function () {
    if (!$("#selected_card").val() && !$(".select_tip").hasClass("active")) {
    	$.notify("Please select payment type cash/card or select tip.", {
                        position: "top center", 
                        className:"warn"
                    });
    // alert("Please select payment type cash/card or select tip.");
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
    	$.notify("Please select payment type card/cash.", {
                        position: "top center", 
                        className:"warn"
                    });
    // alert("Please select payment type card/cash.");
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

            $(".split .RIGHT-SECTION ul li a").click(function (E) {
    if ($(this).hasClass('disabled')) {
    E.stopPropagation();
    E.preventDefault();
    }
    });
    $("#Clear").click(function () {

    var selected_card = $("#selected_card").val();
    var total_price = parseFloat($(".total_price").attr("alt"));
    if (selected_card == 'cash') {
    var amount = $("#cash_val").val();
    $(".cash_price").html("Cash 现金: $00.00");
    $("#cash_val").val(0);
    var received_price = parseFloat($(".received_price").attr('amount'));
	//Modified by Yishou Liao @ Dec 08 2016
	if (typeof($(".received_price").attr('amount')) == "undefined") {
		received_price = 0;
	};
	//End @ Dec 08 2016
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
    var received_price = parseFloat($(".received_price").attr('amount')); //Modified by Yishou Liao @ Dec 08 2016
	//Modified by Yishou Liao @ Dec 08 2016
	if (typeof($(".received_price").attr('amount')) == "undefined") {
		received_price = 0;
	};
	//End @ Dec 08 2016
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
    if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== - 1 ||
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
		
		//Modified by Yishou Liao @ Nov 19 2016
		setCookie("fix_discount_"+<?php echo $Order_detail['Order']['order_no'] ?>, fix_discount, 1);
		setCookie("discount_percent_"+<?php echo $Order_detail['Order']['order_no'] ?>, discount_percent, 1);
		setCookie("promocode_"+<?php echo $Order_detail['Order']['order_no'] ?>, promocode, 1);
		//End
		if (fix_discount || discount_percent || promocode) {
			// apply promocode here
			$.ajax({
			url: "<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'add_discount')); ?>",
					method: "post",
					dataType: "json",
					data: {fix_discount: fix_discount, discount_percent: discount_percent, promocode: promocode, order_id: "<?php echo $Order_detail['Order']['id'] ?>"},
					success: function (html) {
						if (html.error) {
							$.notify(html.message, {
		                        position: "top center", 
		                        className:"warn"
		                    });
							// alert(html.message);
							$(".discount_section").val("").removeAttr("disabled");
							$(".RIGHT-SECTION").removeClass('load1 csspinner');
							return false;
						} else {
							//Modified by Yishou Liao @ Oct 21 2016.
							setCookie("order_menu" +<?php echo $Order_detail['Order']['order_no'] ?>, arrtostr(order_menu), 1);
							setCookie("person_menu_" +<?php echo $Order_detail['Order']['order_no'] ?>, arrtostr(person_menu), 1);
							setCookie("persons_" +<?php echo $Order_detail['Order']['order_no'] ?>, $("#persons").val(), 1);
							//End.
							
							//Modified by Yishou Liao @ Nov 19 2016
							setCookie("discount_type_" +<?php echo $Order_detail['Order']['order_no'] ?>, html.discount_type, 1);
							setCookie("discount_value_" +<?php echo $Order_detail['Order']['order_no'] ?>, html.discount_value, 1);
							//End
							window.location.reload();
						}
					},
					beforeSend: function () {
						$(".RIGHT-SECTION").addClass('load1 csspinner');
					}
			})
		} else {
			$.notify("Please add discount first.", {
                position: "top center", 
                className:"warn"
            });
			// alert("Please add discount first.");
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
            setCookie("order_menu" +<?php echo $Order_detail['Order']['order_no'] ?>, arrtostr(order_menu), 1);
            setCookie("person_menu_" +<?php echo $Order_detail['Order']['order_no'] ?>, arrtostr(person_menu), 1);
            setCookie("persons_" +<?php echo $Order_detail['Order']['order_no'] ?>, $("#persons").val(), 1);
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
    $("#av_persons").val(persons);
    //Modified by Yishou Liao @ Oct 18 2016.
<?php if ($split_method == 0) { ?>
        $("#aver_total").val((((<?php echo $Order_detail['Order']['total']; ?>) / parseInt(persons)).toFixed(2)).toString());
        $("#aver_total_print").val((((<?php echo $Order_detail['Order']['total']; ?>) / parseInt(persons)).toFixed(2)).toString());
<?php } ?>
    }
    //End.
    function tabSelected(i) {
	    var person_tab_idx = parseInt($('#person-tab').find('.active').attr('data-tabIdx'));
	    current_person_tab = parseInt($('#person-tab').find('.active').attr('data-tabIdx'));

	    showAcountingDetails(i);
	    /*if (i !== person_tab_idx) {
		    showAcountingDetails(i);
	    }*/
    }
    //Modified by Yishou Liao @ Oct 19 2016.
    function showAcountingDetails(i = '0') {
	    var radio_click = i;
	    var subTotal = 0;
		var keepsubTotal = 0;
		
	    var Tax = <?php echo $Order_detail['Order']['tax'] ?>;
	    if (i === '0') {
		    i = String($('#person-tab').find('.active').attr('data-tabIdx'));
		    radio_click = i;
	    }

	    var split_accounting_str = "";
		
			for (var i = 0; i < person_menu.length; i++){
				if (person_menu[i][10] == radio_click){
					if (person_menu[i][6]!=""){
						keepsubTotal +=parseFloat(person_menu[i][5])+parseFloat(person_menu[i][6]);
						subTotal += parseFloat(person_menu[i][5])+parseFloat(person_menu[i][6]);
					}else{
						keepsubTotal +=parseFloat(person_menu[i][5]);
						subTotal += parseFloat(person_menu[i][5]);
					};
				};
			};
		
			<?php 
			if ($Order_detail['Order']['discount_value']) { 
			?>
				//Modified by Yishou Liao @ Nov 19 2016
				if (getCookie("fix_discount_" +<?php echo $Order_detail['Order']['order_no'] ?>)!=""){
					subTotal -= parseFloat(getCookie("fix_discount_" +<?php echo $Order_detail['Order']['order_no'] ?>));
				};
				if (getCookie("discount_percent_" +<?php echo $Order_detail['Order']['order_no'] ?>)!=""){
					subTotal -= subTotal*parseInt(getCookie("discount_percent_" +<?php echo $Order_detail['Order']['order_no'] ?>))/100;
				};
				if (getCookie("promocode_" +<?php echo $Order_detail['Order']['order_no'] ?>)!=""){
					//Modified by Yishou Liao @ Nov 19 2016
					if (getCookie("discount_type_" +<?php echo $Order_detail['Order']['order_no'] ?>)==1) {
						subTotal -= subTotal*parseFloat(getCookie("discount_value_" +<?php echo $Order_detail['Order']['order_no'] ?>))/100;
					} else {
						subTotal -= parseFloat(getCookie("discount_value_" +<?php echo $Order_detail['Order']['order_no'] ?>));
					};
					//End

				};
				//End
			
			<?php 
			}
			?>
    
	

	    split_accounting_str = '<ul>';
		//Modified by Yishou Liao @ Nov 25 2016
	    split_accounting_str += '<li class="clearfix"><div class="row"><div class="col-md-3 col-sm-4 col-xs-4 sub-txt">Sub Total ';
		split_accounting_str += <?php if($Order_detail['Order']['discount_value']) { ?> "小计(原价):" <?php } else { ?> "小计:" <?php }; ?>;
		split_accounting_str += '</div>';
		//End
	
		//Modified by Yishou Liao @ Nov 28 2016
		<?php 
		if (!$Order_detail['Order']['discount_value']) { 
		?>
		//End
	    split_accounting_str += '<div class="col-md-3 col-sm-4 col-xs-4 sub-price">$ ' + subTotal.toFixed(2)+ '</div>';
	
		<?php 
		if ($Order_detail['Order']['table_status'] <> 'P' and ! $Order_detail['Order']['discount_value']) { 
		?>
	        split_accounting_str += '<div class="col-md-6 col-sm-4 col-xs-4"><button type="button" class="addbtn pull-right add-discount"><i class="fa fa-plus-circle" aria-hidden="true"></i> Add Discount 加入折扣</button></div>'
		<?php 
		} 
		?>
	    
	    split_accounting_str += '</div></li>';
		
	<?php 
		if (!$Order_detail['Order']['discount_value']) { 
	?>
	        split_accounting_str += '<li class="clearfix discount_view" style="display:none;"><div class="row"><div class="col-md-3"><div class="form-group">';
	        split_accounting_str += '<label for="fix_discount" style="font-size:11px;">Fix Discount</label>';
	        split_accounting_str += '<input type="text" id="fix_discount" required="required" class="form-control discount_section" maxlength="5"  name="fix_discount"></div></div>';
	        split_accounting_str += '<div class="col-md-3"><div class="form-group"><label for="discount_percent" style="font-size:11px;">Discount in %</label><input type="text" id="discount_percent" required="required" class="form-control discount_section" maxlength="5"   name="discount_percent"></div></div>';
	        split_accounting_str += '<div class="col-md-3"><div class="form-group"><label for="promocode" style="font-size:11px;">Promo Code</label>';
	        split_accounting_str += '<input type="text" id="promocode" required="required" class="form-control discount_section" maxlength="200" name="promocode"></div></div>';
	        split_accounting_str += '<div class="col-md-3"><div class="form-group"><label for="AdminTableSize" style="width:100%">&nbsp;</label>';
	        split_accounting_str += '<a class="btn btn-primary btn-wide" id="apply-discount" href="javascript:void(0)">Apply <i class="fa fa-arrow-circle-right"></i></a></div></div></div></li>';
	<?php 
		};
	};//Modified by Yishou Liao @ Nov 28 2016 (Add }; ) 
	?>

	<?php 
		if ($Order_detail['Order']['discount_value']) { 
	?>
			//Modified by Yishou Liao @ Nov 19 2016
			if (checkCookie("fix_discount_" +<?php echo $Order_detail['Order']['order_no'] ?>)) {
				discount = parseFloat(getCookie("fix_discount_" +<?php echo $Order_detail['Order']['order_no'] ?>)).toFixed(2);
			};

			if (checkCookie("discount_percent_" +<?php echo $Order_detail['Order']['order_no'] ?>)) {
				discount = (parseFloat(keepsubTotal)*parseInt(getCookie("discount_percent_" +<?php echo $Order_detail['Order']['order_no'] ?>))/100).toFixed(2);
			};

			if (getCookie("promocode_" +<?php echo $Order_detail['Order']['order_no'] ?>)!="") {
				//Modified by Yishou Liao @ Nov 19 2016
				if (getCookie("discount_type_" +<?php echo $Order_detail['Order']['order_no'] ?>)==1) {
					discount = (parseFloat(keepsubTotal)*parseFloat(getCookie("discount_value_" +<?php echo $Order_detail['Order']['order_no'] ?>))/100).toFixed(2);
				} else {
					discount = parseFloat(getCookie("discount_value_" +<?php echo $Order_detail['Order']['order_no'] ?>)).toFixed(2);
				}
				//End
			}
			//End
		
			//Modified by Yishou Liao @ Nov 28 2016
			split_accounting_str += '<div class="col-md-3 col-sm-4 col-xs-4 sub-price">$ ' + (parseFloat(subTotal)+parseFloat(discount)).toFixed(2) + '</div>';
			//End
	        split_accounting_str += '<li class="clearfix"><div class="row"><div class="col-md-3 col-sm-4 col-xs-4 sub-txt">Discount 折扣:</div><div class="col-md-3 col-sm-4 col-xs-4 sub-price">$ ';//Modified by Yishou Liao @ Nov 28 2016
	        split_accounting_str += discount;
        
	    <?php 
	    	if ($Order_detail['Order']['percent_discount']) { 
	    ?>
	            split_accounting_str += '<span class="txt12">';
	            split_accounting_str += '<?php echo $Order_detail['Order']['promocode']; ?>';
	            split_accounting_str += ' (';
	            split_accounting_str += '<?php $Order_detail['Order']['percent_discount']; ?>';
	            split_accounting_str += '%)</span>';
	    <?php 
			} 
		?>
	        split_accounting_str += '<a aria-hidden="true" class="fa fa-times remove_discount" order_id="' +<?php echo $Order_detail['Order']['id']; ?> + '" href="javascript:void(0)"></a></div></div></li>';
			
			//Modified by Yishou Liao @ Nov 25 2016
		    split_accounting_str += '<li class="clearfix"><div class="row"><div class="col-md-3 col-sm-4 col-xs-4 sub-txt">After Discount 打折后:</div>';
		    split_accounting_str += '<div class="col-md-3 col-sm-4 col-xs-4 sub-price">$ ' + subTotal.toFixed(2)+ '</div></li>';
			//End
		
	<?php 
		} 
	?>

		//Modified by Yishou Liao @ Nov 28 2016
		split_accounting_str += '<li class="clearfix"><div class="row">';
	    split_accounting_str += '<div class="col-md-3 col-sm-4 col-xs-4 sub-txt">Tax 税 (' + Tax + '%):</div>';
	    var Tax_Amount = subTotal * Tax / 100;
	    split_accounting_str += '<div class="col-md-3 col-sm-4 col-xs-4 sub-price">$' + Tax_Amount.toFixed(2) + '</div>';
	    split_accounting_str += '</div></li>';
		//End
	    split_accounting_str += '<li class="clearfix"><div class="row"><div class="col-md-3 col-sm-4 col-xs-4 sub-txt">Total 总:</div>';
	    split_accounting_str += '<div class="col-md-3 col-sm-4 col-xs-4 sub-price total_price" alt="';
	    var Total_Amount = subTotal + (subTotal * Tax / 100);
	    split_accounting_str += Total_Amount.toFixed(2); //Modified by Yishou Liao @ Oct 21 2016.
	    split_accounting_str += '">$ ';
	    split_accounting_str += Total_Amount.toFixed(2); //Modified by Yishou Liao @ Oct 21 2016.
	    split_accounting_str += '</div>';
	    split_accounting_str += '</div></li>';
	<?php 
		if ($Order_detail['Order']['table_status'] == 'P') { 
	?>
	        split_accounting_str += '<li class="clearfix"><div class="row"><div class="col-md-3 col-sm-4 col-xs-4 sub-txt">Receive 收到</div>';
	        split_accounting_str += '<div class="col-md-3 col-sm-4 col-xs-4 sub-price received_price">$ ';
	        split_accounting_str += <?php echo $Order_detail['Order']['paid']; ?>;
	        split_accounting_str += '</div><div class="col-md-3 col-sm-4 col-xs-4 sub-price cash_price">Cash 现金: $ ';
	        split_accounting_str += <?php echo $Order_detail['Order']['cash_val']; ?>;
	        split_accounting_str += '</div><div class="col-md-3 col-sm-4 col-xs-4 sub-price card_price">Card 卡: $ ';
	        split_accounting_str += <?php echo $Order_detail['Order']['card_val']; ?>;
	        split_accounting_str += '</div></div></li>';
	    
	    <?php 
	    	if ($Order_detail['Order']['change']) { 
	    ?>
	            split_accounting_str += '<li class="clearfix"><div class="row"><div class="col-md-3 col-sm-4 col-xs-4 sub-txt change_price_txt">Change 找零</div>';
	            split_accounting_str += '<div class="col-md-3 col-sm-4 col-xs-4 sub-price change_price">$ ';
	            split_accounting_str += <?php echo $Order_detail['Order']['change']; ?>;
	            split_accounting_str += '</div></div></li>';
	    <?php 
			} 
		?>
	        split_accounting_str += '<li class="clearfix"><div class="row"><div class="col-md-3 col-sm-4 col-xs-4 sub-txt">Tip 小费</div>';
	        split_accounting_str += '<div class="col-md-3 col-sm-4 col-xs-4 sub-price tip_price">$ ';
	        split_accounting_str += <?php echo $Order_detail['Order']['tip']; ?>;
	        split_accounting_str += '</div></div></li>';
	<?php 
		} else { 
	?>
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
	<?php 
		} 
	?>

	    split_accounting_str += '</ul>';
	    $('#split_accounting_details').html(split_accounting_str);
	}
    //End.

    //Modified by Yishou Liao @ Oct 19 2016.
    function print_receipt() {
	    var radio_click = 0;
	    var print_String = "";
	    var account_String = "";
	    var sub_total = 0;
	    var Tax =<?php echo $Order_detail['Order']['tax'] ?>;
	    var person_menu_print = Array(); //Modified by Yishou Liao @ Oct 27 2016.

	    //Modified by Yishou Liao @ Oct 27 2016.
		<?php 
			if ($split_method == 0) { 
		?>
		        for (var i = 0; i < order_menu.length; i++){
		        sub_total += parseFloat(order_menu[i][5]);
		        person_menu_print.push(Array("", "", "", order_menu[i][2], order_menu[i][3], "", order_menu[i][5], "1")); //Modified by Yishou Liao @ Oct 27 2016.

		        };
		<?php 
			} else { 
		?>

		        radio_click = parseInt($('#person-tab').find('.active').attr('data-tabIdx'));
		        //Modified by Yishou Liao @ Oct 20 2016.
		        if (person_menu.length == 0){
		        for (var i = 0; i < order_menu.length; i++){
		        person_menu.push(Array(order_menu[i][0], order_menu[i][1], order_menu[i][2], order_menu[i][3], order_menu[i][4], order_menu[i][5], order_menu[i][6], order_menu[i][7], order_menu[i][8], radio_click));
		        };
		        }
		        //End.

		        for (var i = 0; i < person_menu.length; i++){
		        if (person_menu[i][0] == radio_click){
		        sub_total += parseFloat(person_menu[i][5]);
		        person_menu_print.push(Array("", "", "", person_menu[i][2], person_menu[i][3], "", person_menu[i][5], "1")); //Modified by Yishou Liao @ Oct 27 2016.
		        };
		        };
		<?php 
			} 
		?>


		//Modified by Yishou Liao @ Nov 29 2016
		var discount=0;
		if (checkCookie("fix_discount_" +<?php echo $Order_detail['Order']['order_no'] ?>)) {
			discount = parseFloat(getCookie("fix_discount_" +<?php echo $Order_detail['Order']['order_no'] ?>)).toFixed(2);
		};
		if (checkCookie("discount_percent_" +<?php echo $Order_detail['Order']['order_no'] ?>)){
			//Modified by Yishou Liao @ Dec 15 2016
			discount = (parseFloat(sub_total)*parseInt(getCookie("discount_percent_" +<?php echo $Order_detail['Order']['order_no'] ?>))/100).toFixed(2);
			//End Yishou Liao @ Dec 15 2016
		};
		if (getCookie("promocode_" +<?php echo $Order_detail['Order']['order_no'] ?>)!=""){
			//Modified by Yishou Liao @ Nov 19 2016
			if (getCookie("discount_type_" +<?php echo $Order_detail['Order']['order_no'] ?>)==1) {
				//Modified by Yishou Liao @ Dec 15 2016
				discount = (parseFloat(sub_total)*parseFloat(getCookie("discount_value_" +<?php echo $Order_detail['Order']['order_no'] ?>))/100).toFixed(2);
				//End Yishou Liao @ Dec 15 2016
			} else {
				discount = parseFloat(getCookie("discount_value_" +<?php echo $Order_detail['Order']['order_no'] ?>)).toFixed(2);
			};
			//End
		};
//End


	    var tax_amount = ((sub_total-discount) * Tax / 100).toFixed(2);
	    var total = (parseFloat(sub_total-discount) + parseFloat(tax_amount)).toFixed(2);
	    //Modified by Yishou Liao @ Nov 08 2016.
	    $.ajax({
		    url: "<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'printReceipt', $Order_detail['Order']['order_no'], (($type == 'D') ? '[[Dinein]]' : (($type == 'T') ? '[[Takeout]]' : (($type == 'W') ? '[[Waiting]]' : ''))) . ' #' . $table, $cashier_detail['Admin']['service_printer_device'],1)); ?>",
	        method:"post",
	        data:{
		        logo_name:"../webroot/img/logo.bmp",
	            Print_Item:person_menu_print,
	            subtotal:sub_total,
				discount:discount,
				after_discount: (sub_total-discount),
	            tax:Tax,
				//Modified by Yishou Liao @ Nov 29 2016
				tax_Amount: tax_amount,
				//paid_by: $("#selected_card").val(),
				paid: $(".received_price").attr("amount"),
				change: $(".change_price").attr("amount"),
				//End
	            total:total,
	            split_no:radio_click,

	        },
	        success:function(html) {

	        }
	    });
            //End.
    }
    //End.

    //Modified by Yishou Liao @ Oct 21 2016.
    function setCookie(c_name, value, expiredays) {
	    var exdate = new Date()
        exdate.setDate(exdate.getDate() + expiredays)
        document.cookie = c_name + "=" + escape(value) +
        ((expiredays == null) ? "" : ";expires=" + exdate.toGMTString())
    }

    function getCookie(c_name) {
	    if (document.cookie.length > 0) {
		    c_start = document.cookie.indexOf(c_name + "=")
            if (c_start != - 1) {
			    c_start = c_start + c_name.length + 1
	            c_end = document.cookie.indexOf(";", c_start)
	            if (c_end == - 1) c_end = document.cookie.length
	            
	            return unescape(document.cookie.substring(c_start, c_end))
		    }
	    }
	    return ""
    }

    function checkCookie(c_name) {
	    if (getCookie(c_name) != null && getCookie(c_name) != ""){
	    	return true; 
	    }
	    else {
	    	return false; 
	    }
    }

    function deleteCookie(c_name) {
    	setCookie(c_name, "", - 1);
    }

    function arrtostr(c_array){//将二维数组转换为字符串。
	    var strarray = Array();
	    var restr = "";
	    for (var i = 0; i < c_array.length; i++){
		    strarray.push(c_array[i].join("~"));
	    };
	    restr = strarray.join("^");
	    return restr;
    }

    function strtoarr(c_string){//将字符串转换为二维数组。
	    var strarray;
	    var rearr = Array();
	    strarray = c_string.split("^");
	    for (var i = 0; i < strarray.length; i++){
		    rearr.push(strarray[i].split("~"));
	    };
	    return rearr;
    }
    //End.

    function round2(number) {
    	return Math.round(number * 100) / 100
    }
</script>