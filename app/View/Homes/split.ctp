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

<style>
	#whole-wrapper {
		margin-top: 20px;
	}

</style>


<div class="col-md-12 col-sm-12 col-xs-12" id="whole-wrapper">
    <div id="customer-select-alert" class="alert alert-info alert-dismissible fade in" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <!-- <strong>Customer # <span id="customer-number"></span></strong> selected -->
    </div>


    <?php echo $this->Session->flash(); ?>

    <div class="col-md-12 col-sm-12 col-xs-12">
    	<h2>Order 订单号 #<?php echo $Order_detail['Order']['order_no'] ?><br/>Table 桌 <?php echo (($type == 'D') ? '[[Dinein]]' : (($type == 'T') ? '[[Takeout]]' : (($type == 'W') ? '[[Waiting]]' : ''))); ?>#<?php echo $table; ?></h2>
        <?php
        if ($Order_detail['Order']['table_status'] != 'P') {
            ?>

            <div class="table-box dropdown">
                <a class="split dropdown-toggle">Split 分单</a>
            </div>

        <?php } ?>

        <div class="avoid-this text-center reprint"><button type="button" class="submitbtn">Print Receipt 打印收据</button></div>

        <button class="btn btn-lg btn-success pull-right" id="sidebar-button">Test</button>
    </div>

    <div class="col-md-3 col-sm-4 col-xs-12 order-left" id="left-side">
        

        <div class="order-summary col-md-12 col-sm-12 col-xs-12" id="order-wrapper">
            <h3>Order Summary 订单摘要</h3>
			<div class="clearfix" id="order-component-placeholder"></div>
        </div>
             
		<div class="order-summary col-md-12 col-sm-12 col-xs-12" id="suborders-wrapper">
            <h3>Split Details 分单明细</h3>

            <div class="clearfix" id="suborders-list-component-placeholder"></div>
        </div>
    </div>

    <div class="col-md-9 col-sm-8 col-xs-12 RIGHT-SECTION" id="right-side">
    	
        <ul class="nav nav-tabs" id="person-tab">
        </ul>

	    <div class="clearfix" id="suborders-detail-component-placeholder"></div>

	    <div id="input-placeholder" class="clearfix"> </div>
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

echo $this->Html->css(array('components/KeypadComponent', 'components/OrderComponent', 'components/SubordersListComponent', 'components/SubordersDetailComponent'));
echo $this->Html->script(array('jquery.min.js', 'bootstrap.min.js', 'jquery.mCustomScrollbar.concat.min.js', 'barcode.js', 'epos-print-5.0.0.js', 'fanticonvert.js', "notify.min.js", 'js.cookie.js', 'avgsplit.js'));

echo $this->fetch('script');
?>
<script>
	// image path for component
	var rightImg = '<?php echo $this->Html->image("right.png", array('alt' => "right")); ?>';
	var cardImg = '<?php echo $this->Html->image("card.png", array('alt' => "card")); ?>';
	var cashImg = '<?php echo $this->Html->image("cash.png", array('alt' => "cash")); ?>';


	jQuery.fn.clickToggle = function(a,b) {
		var ab = [b,a];
		return this.on("click", function(){ ab[this._tog^=1].call(this); });
	};


	// add a class for .suborder-list
	// todo
	$("#sidebar-button").clickToggle(
		function() { 
		  	$('#right-side').hide();
			$('#left-side').removeClass('col-md-3 col-sm-4 col-xs-12').addClass('col-md-12 col-sm-12 col-xs-12');
			$('#order-wrapper').removeClass('col-md-12 col-sm-12 col-xs-12').addClass('col-md-3 col-sm-3 col-xs-12');
			$('#suborders-wrapper').removeClass('col-md-12 col-sm-12 col-xs-12').addClass('col-md-9 col-sm-9 col-xs-12');

			$('.suborder-list').addClass('horizon');
		}, 
		function() {
			$('#right-side').show();
		    $('#left-side').addClass('col-md-3 col-sm-4 col-xs-12').removeClass('col-md-12 col-sm-12 col-xs-12');
			$('#order-wrapper').addClass('col-md-12 col-sm-12 col-xs-12').removeClass('col-md-3 col-sm-3 col-xs-12');
			$('#suborders-wrapper').addClass('col-md-12 col-sm-12 col-xs-12').removeClass('col-md-9 col-sm-9 col-xs-12');
		}); 


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
	var orderCookie = order_no + '_split_order';
	var subordersCookie = order_no + '_split_suborder';
	var person_paid = new Array(); // person who have paid money
	var suborder_detail = new Array();
	var paid_info = new Array();

	var order = new Order(order_no);
	// var suborder = new Suborder('1');
	var suborders = new Suborders();
	var current_suborder = 0;
	// order = loadOrder(order_no);

	restoreFromCookie();

	// if order changed, delete all cookies
	if (isOrderChanged()) {
		console.log('order has changed');
		order = loadOrder(order_no);
		suborders = new Suborders();

		Cookies.remove(orderCookie);
		Cookies.remove(subordersCookie);
	}

	drawUI();


	// construct suborders by order
	function restoreFromCookie() {

		// check whether cookie exist
		var tempOrder = Cookies.getJSON(orderCookie); 

		if (tempOrder != undefined) {
			order = Order.fromJSON(tempOrder);
			suborders = new Suborders();
			// construct suborder

			for (var i = 0; i < order.suborderNum; ++i) {
				suborders.pushEmptySuborder();
			}

			// restore suborder from order
			for (var i = 0; i < order.items.length; ++i) {
				if (order.items[i].state == 'keep') {
					continue;
				} else if (order.items[i].state == 'assigned') { // restore based on assigned_suborder
					suborders.getSuborder(order.items[i].assigned_suborder).addItem(order.items[i])
				} else if (order.items[i].state == 'share') { //restore based on shared_suborders
					for (var j = 0; j < order.items[i].shared_suborders.length; ++j) {
						suborders.getSuborder(order.items[i].shared_suborders[j]).addItem(order.items[i]);
					}
				}
			}
		}

		var tempSuborders = Cookies.getJSON(subordersCookie);
		if (tempSuborders != undefined) {
			for (var i = 0; i < tempSuborders.suborders.length; ++i) {
				var temp_no = tempSuborders.suborders[i].suborder_no;
				console.log(temp_no);
				suborders.getSuborder(temp_no).fromJSON(tempSuborders);
			}
		}
	}	

	function persistentOrder() {
		Cookies.set(orderCookie, order);
		Cookies.set(subordersCookie, suborders);
	}


	// assign item to suborder
	// notice deepcopy or shallowcopy
	// assign item by item_id from order to suborders
	// here change state use reference
	function assignItem(order, item_id, suborders, suborder_no) {
		if (suborder_no != 0) {
			var item = order.getItem(item_id);
			var suborder = suborders.getSuborder(suborder_no);
			
			item.state = "assigned";
			item.assigned_suborder = suborder_no;
			suborder.addItem(item);


			persistentOrder();
			// should be moved outside
			drawUI();
			// return suborder;
		} else {
			alert("Please indicate suborder id");
		}
	}

	// share one item to all existed suborder
	/*function shareItem(order, item_id, suborders, suborder_no) {
		var availableItems = order.availableItems;

		for (var i = 0; i < availableItems.length; ++i) {
			
		}
	}*/

	// return item to order
	function returnItem(item_id) {
		order.setItemState(item_id, "keep");
		suborders.refreshSuborders();

		persistentOrder();
		drawUI();
	}

	


	// todo !!!
	function avgSplit() {
		if (suborders.length > 1 && order.availableItemsNum > 0) {

			var tempAvailableItems = order.availableItems;


			for (var i = 0; i < tempAvailableItems.length; ++i) {
				tempAvailableItems[i].state = "share";
				for (var j = 1; j <= suborders.length; ++j) {
					suborders.getSuborder(j).addItem(tempAvailableItems[i]);
					tempAvailableItems[i].shared_suborders.push(j);
				}
			}

			persistentOrder();
			drawUI();
		} else {
			alert("Please make sure you have more than two people to share, or more than one item to be shared.");
		}
	}


	// add suborder to the end of suborders
	function addPerson() {
		// suborders.length;
		suborders.pushEmptySuborder();
		current_suborder = suborders.length;

		++order.suborderNum;

		persistentOrder();
		drawUI()
	}

	// delete the last suborder of suborders
	// todo think about share item
	function deletePerson(suborders) {

		if (suborders.length > 0) {
			--order.suborderNum;

			// var n = suborders.length;
			var deletedSuborder = suborders.popSuborder();

			// move items back to order
			for (var i = 0; i < deletedSuborder.items.length; ++i) {
				var item_id = deletedSuborder.items[i]["item_id"];
				order.setItemState(item_id, "keep");
			}


			current_suborder = suborders.length;

			// remove all items from suborders whose state is "keep"
			suborders.refreshSuborders();

			persistentOrder();
			drawUI();

			return deletedSuborder;
		} else {
			alert("No person to be deleted");
		}
		
	}


	// enter the number
	// change the suborder based on the suborders tab
	// only pay when order is totally split
	// once is paid, the order and suborder cannot be modified any more
	function enterInput () {
		// only when order items are totally assigned, the enter will react
		if (order.availableItems.length > 0) {
			alert("You should assign all items of order to suborders");
			return;
		}

		var payOrTip = $('input[name="pay-or-tip"]:checked').attr('data-type');
		var cardOrCash = $('#input-type-group input:checked').attr('data-type');

		var currentSuborderId = $('.suborders-detail-tab.active').attr('data-index');

		var inputNum = parseFloat($('#input-screen').val());
		console.log(payOrTip);
		console.log(cardOrCash);
		console.log(currentSuborderId);
		console.log(inputNum);

		if (typeof currentSuborderId == "undefined") { // make sure has suborder first
			alert("no suborder");
		} else {
			var currentSuborder = suborders.getSuborder(currentSuborderId);


			if (typeof payOrTip == "undefined") { 
				// notification
				console.log("pay or tip error");
			} else if (typeof cardOrCash == "undefined") {
				// notification
				console.log("card or cash error");
			} else { // payortip and cardorcash are both defined
				// console.log("input data")
				// change the received and tip value in order

				if (payOrTip == "pay") {
					if (cardOrCash == "card") {
						currentSuborder._received.card = inputNum;
						
					} else if (cardOrCash == "cash") {
						currentSuborder._received.cash = inputNum;
			
					}

				} else if (payOrTip == "tip") {
					if (cardOrCash == "card") {
						currentSuborder._tip.card = inputNum;
				
					} else if (cardOrCash == "cash") {
						currentSuborder._tip.cash = inputNum;
						
					}

				}
				// console.log(inputNum);

				persistentOrder();
				// drawSubordersDetail();
				drawUI();
			}
		}
	}



	function drawUI() {
		drawOrder();
		drawSubOrdersList();
		drawSubordersDetail();
		drawKeypadComponent();
	}


	function drawOrder() {
		$("#order-component-placeholder").empty();
		$("#order-component-placeholder").append(OrderComponent(order));
	}

	function drawSubOrdersList() {
		$("#suborders-list-component-placeholder").empty();
		$("#suborders-list-component-placeholder").append(SubordersListComponent(suborders));
	}

	function drawSubordersDetail() {
		var activeIndex = $('.suborders-detail-tab.active').attr('data-index');

		$("#suborders-detail-component-placeholder").empty();
		$("#suborders-detail-component-placeholder").append(SubordersDetailComponent(suborders));
		
		// TODO

		if (typeof activeIndex != "undefined") {
			$('#suborders-detail-tab-' + activeIndex).trigger('click');
		} else if (order.suborderNum > 0) {
			$('#suborders-detail-tab-1').trigger('click');
		}
	}

	function drawKeypadComponent() {
		$('#input-placeholder').empty();
		$('#input-placeholder').append(KeypadComponent());
	}


	console.log("order_no");
	console.log(order_no);


	function loadOrder(order_no) {
		var tempOrder = new Order(order_no);

		<?php
			if (!empty($Order_detail['OrderItem'])) {
			    $i = 0;
			    foreach ($Order_detail['OrderItem'] as $key => $value) {

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

	        		var temp_item = new Item(item_id = '<?php echo $i ?>',
	        			image= '<?php if ($value['image']) { echo $value['image']; } else { echo 'no_image.jpg';};?>',
	        			name_en = '<?php echo $value['name_en']; ?>',
	        			name_zh = '<?php echo $value['name_xh']; ?>',
	        			selected_extras_name = '<?php echo implode(",", $selected_extras_name); ?>', // can be extend to json object
	        			price = '<?php echo $value['price'] ?>',
	        			extras_amount = '<?php echo $value['extras_amount'] ?>',
	        			quantity = '<?php echo $value['qty'] > 1 ? "x" . $value['qty'] : "" ?>',
	        			order_item_id = '<?php echo $value['id'] ?>',
	        			state = "keep");

	        		tempOrder.addItem(temp_item);

		    <?php
				   	$i++;
			 	} // line 563 foreach
		    ?>

	    <?php 
			} // line 561 if  
		?>
		return tempOrder;
	}

	// to be improved as more robust
	function isOrderChanged () {
		var changed = false;
		var temp_order = loadOrder();

		if (temp_order['items'].length != order['items'].length) {
			return true;
		} else {
			for (var i = 0; i < temp_order['items'].length; ++i) {
				if (temp_order['items'][i]['order_item_id'] != order['items'][i]['order_item_id']) {
					changed = true
				}
			}
		}

		return changed;
	}



	console.log("initailize person_No");
	console.log(person_No);


	console.log("initialize order_menu");
    console.log(order_menu);
    console.log("initialize person_menu");
    console.log(person_menu);


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
    	
	//  once user want to, all modification operation will be disabled
	/*$("#pay-confirm").click(function () {
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
	});*/


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
		                window.location.reload();
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
	}
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

</script>