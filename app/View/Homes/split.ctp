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
	/* #whole-wrapper {
		margin-top: 20px;
	} */

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
<!--     	<div id="discount-component-placeholder" class="pull-right"></div> -->
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

    <div class="col-md-9 col-sm-8 col-xs-12" id="right-side">

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

	// variable for payment
	var table_id = '<?php echo $table ?>';
	var order_type = '<?php echo $type ?>';
	var order_no = <?php echo $Order_detail['Order']['order_no'] ?>;
	// var reorder_no = 


	// ajax path
	var home_page_url = "<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'index')) ?>"
	var store_suborder_url = "<?php echo $this->Html->url(array('controller' => 'split', 'action' => 'storeSuborder')); ?>";
	var countPopular_url = "<?php echo $this->Html->url(array('controller' => 'split', 'action' => 'addPopular')); ?>";
	var update_original_order_url = "<?php echo $this->Html->url(array('controller' => 'split', 'action' => 'updateOriginalOrder')); ?>";

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

			$('#suborders-wrapper').addClass('horizon');
			// store the state of toggle in suborders
		}, 
		function() {
			$('#right-side').show();
		    $('#left-side').addClass('col-md-3 col-sm-4 col-xs-12').removeClass('col-md-12 col-sm-12 col-xs-12');
			$('#order-wrapper').addClass('col-md-12 col-sm-12 col-xs-12').removeClass('col-md-3 col-sm-3 col-xs-12');
			$('#suborders-wrapper').addClass('col-md-12 col-sm-12 col-xs-12').removeClass('col-md-9 col-sm-9 col-xs-12');
			$('#suborders-wrapper').removeClass('horizon');
		}); 


	$('#customer-select-alert').hide();

    //  initialize

    var current_person = '0';
    var current_person_tab = '1';

	var split_method = parseInt(<?php echo $split_method ?>); // should be removed
	
	var orderCookie = order_no + '_split_order';
	var subordersCookie = order_no + '_split_suborder';
	var discountCookie = order_no + '_split_discount';

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
				// console.log(temp_no);
				suborders.getSuborder(temp_no).fromJSON(tempSuborders);
			}
		}

		// restore from the discount cookie
	}	

	function persistentOrder(callback) {
		Cookies.set(orderCookie, order);
		Cookies.set(subordersCookie, suborders);
		// Cookies.set(discountCookie, discount);

		if (typeof callback === "function") {
			callback();
		}
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
	function enterInput (callback) {
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
			$.notify("no suborder \n请分单",{ position: "top center", className:"warn"});
		} else {
			var currentSuborder = suborders.getSuborder(currentSuborderId);


			if (typeof payOrTip == "undefined") { 
				// notification
				$.notify("Please select payment or tip method \n请选择付款或者小费. ", { position: "top center", className:"warn"});
			} else if (typeof cardOrCash == "undefined") {
				// notification
				$.notify("Please select card or cash payment method \n请选择卡或现金付款方式. ",{ position: "top center", className:"warn"});
			} else { // payortip and cardorcash are both defined
				// console.log("input data")
				// change the received and tip value in order

				if (payOrTip == "pay") {
					if (cardOrCash == "card") {
						currentSuborder._received.card = inputNum;
						
					} else if (cardOrCash == "cash") {
						// give notification when the number is input into suborder
						currentSuborder._received.cash = inputNum;
			
					}

				} else if (payOrTip == "tip") {
					if (cardOrCash == "card") {
						currentSuborder._tip.card = inputNum;
				
					} else if (cardOrCash == "cash") {
						//  give notification when the number is input into suborder
						currentSuborder._tip.cash = inputNum;
						
					}

				}
				// console.log(inputNum);

							
			}
		}

		if (typeof callback === "function") {
			callback();
		}	
	}





	function drawUI(callback) {
		drawOrder();
		drawSubOrdersList();
		drawSubordersDetail();
		drawKeypadComponent();

		if (typeof callback === "function") {
			callback();
		}
	}

	function drawExceptKeypad(callback) {
		drawOrder();
		drawSubOrdersList();
		drawSubordersDetail();

		if (typeof callback === "function") {
			callback();
		}
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
		$('#input-placeholder').append(KeypadComponent(order, suborders, {"cardImg": cardImg, "cashImg": cashImg}, drawExceptKeypad, persistentOrder));
	}


	console.log("order_no");
	console.log(order_no);


	function loadOrder(order_no) {

		var tempOrder = new Order(order_no);
		<?php
			if (!empty($Order_detail['OrderItem'])) {
			?>
				var percent_discount = '<?php echo $Order_detail['Order']['percent_discount'] ;?>';
				var fix_discount = '<?php echo $Order_detail['Order']['fix_discount']; ?>';

				console.log(percent_discount);
				console.log(fix_discount);
				if (percent_discount != 0) {
					tempOrder.discount = {"type": "percent", "value": percent_discount}
					console.log(tempOrder.discount)
				} else if (fix_discount != 0) {
					tempOrder.discount = {"type": "fixed", "value": fix_discount}
				}
			<?php

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
	// should check whether discount change

	// discount should be seperate discuss
	function isOrderChanged () {
		var changed = false;
		var temp_order = loadOrder();

		if ((temp_order['items'].length != order['items'].length) || (temp_order.discount.type != order.discount.type || temp_order.discount.value != order.discount.value)) {
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


	// print accounding order and suborders
	function printReceipt() {
		for (var i = 0; i < suborders.suborders.length; ++i) {
			
		}

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


    //Modified by Yishou Liao @ Oct 19 2016.
    function print_receipt1() {
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
})

</script>