<header class="product-header">
    <?php echo $this->element('navbar'); ?>
</header>

<script type='text/javascript'> 
  var tax_rate = <?php echo !empty(@$Order_detail['Order']['tax'])?@$Order_detail['Order']['tax']:13; ?>; 
  var default_tip_rate = <?php echo !empty(@$Order_detail['Order']['default_tip_rate'])?@$Order_detail['Order']['default_tip_rate']:0; ?>; 
</script>

<div class="col-md-12 col-sm-12 col-xs-12" id="whole-wrapper">
    <div id="customer-select-alert" class="alert alert-info alert-dismissible fade in" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <!-- <strong>Customer # <span id="customer-number"></span></strong> selected -->
    </div>


    <?php echo $this->Session->flash(); ?>


    <div class="col-md-12 col-sm-12 col-xs-12" style="margin-top: 20px;">
    	<div class="row">
    	  <div class="col-sm-7">
	    	<h2>
		    	<span class="split-p-order">Order 订单号 #<?php echo $Order_detail['Order']['order_no'] ?></span>
		    		<?php
			        if ($Order_detail['Order']['table_status'] != 'P') {
			            ?>

			            <span class="table-box dropdown">
			                <a class="split dropdown-toggle order-status">Split 分单</a>
			            </span>

			        <?php } ?>


		    	<br/>
		    	<span class="split-p-table">Table 桌 <?php echo (($type == 'D') ? '[[堂食]]' : (($type == 'T') ? '[[外卖]]' : (($type == 'W') ? '[[等候]]' : ''))); ?>#<?php echo $table; ?>

		    	</span>
	    	</h2>

	      </div>
	      <!-- <div class="col-sm-5 text-right">
	        <div class="avoid-this text-center reprint pull-right"><button type="button" class="submitbtn">Print Receipt 打印收据</button></div>
	      </div>   -->

	        <button class="btn btn-lg btn-primary pull-right" id="print-split-bill">Print Split Bill <b>分单账单</b></button>
			<!-- <button class="btn btn-lg btn-primary pull-right" id="print-split-receipt">Print Split Receipt <b>分单收据</b></button> -->
			<button class="btn btn-lg btn-primary pull-right" id="print-original-bill">Print Original Bill <b>原账单</b></button>

			<div class="row">
			  <div class="col-sm-12" style="margin-bottom: 15px;">
				<button class="btn btn-lg btn-success pull-right" id="sidebar-button"><b>切换</b></button>
			<!--     	<div id="discount-component-placeholder" class="pull-right"></div> -->		 </div>
			</div>

			<div id="dangerous-notice">
				<p></p>
			</div>
        </div>
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

	    <div class="clearfix col-md-6 col-sm-6 col-xs-12" id="suborders-detail-component-placeholder"></div>

	    <div class="clearfix col-md-6 col-sm-6 col-xs-12" id="input-placeholder"> </div>
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

echo $this->Html->css(array('components/KeypadComponent', 'components/OrderComponent', 'components/SubordersListComponent', 'components/SubordersDetailComponent', 'split'));
echo $this->Html->script(array('jquery.min.js', 'bootstrap.min.js', 'jquery.mCustomScrollbar.concat.min.js', 'barcode.js', 'epos-print-5.0.0.js', 'fanticonvert.js', "notify.min.js", 'js.cookie.js', 'avgsplit.js', 'print.js'));


echo $this->fetch('script');
?>
<script>
	// image path for component
	var rightImg = '<?php echo $this->Html->image("right.png", array('alt' => "right")); ?>';
	var cardImg = '<?php echo $this->Html->image("card.png", array('alt' => "card")); ?>';
	var cashImg = '<?php echo $this->Html->image("cash.png", array('alt' => "cash")); ?>';

	var imgPath = '<?php echo $this->webroot ?>' + 'img/';

	// variable for payment
	var table_id = '<?php echo $table ?>';
	var order_type = '<?php echo $type ?>';
	var order_no = '<?php echo $Order_detail['Order']['order_no'] ?>';
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


	var KVStorage = (function() {

		function set(key, value, cfg={}) {
			// Cookies.set(key, value, cfg);
			$.ajax({
				url: "<?php echo $this->Html->url(array('controller' => 'split', 'action' => 'setCookie')); ?>",
	            method: "post",
	            data: {
	            	key: key,
	            	value: JSON.stringify(value)
	            },
	            success: function (data) {
                    drawUI();
	            }
			})

			// return false;
		}

		function get(key) {

			$.ajax({
				url: "<?php echo $this->Html->url(array('controller' => 'split', 'action' => 'getCookie')); ?>",
	            method: "post",
	            data: {
	            	key: key
	            },
                async: false,
	            success: function (value) {
                    drawUI();
	            	if (value.trim()) {
	            		return JSON.parse(value.trim());
	            	} else {
	            		return {};
	            	}

	            }
			})

			// return false;
			// return Cookies.getJSON(key);
		}

		function remove(key, cfg={}) {
			$.ajax({
				url: "<?php echo $this->Html->url(array('controller' => 'split', 'action' => 'removeCookie')); ?>",
	            method: "post",
	            data: {
	            	key: key
	            },
	            success: function (value) {
                    drawUI();
	            }
			})

			// return false;

			// Cookies.remove(key, cfg);
		}

		return {
			set: set,
			get: get,
			remove: remove
		}
	})()

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



	// if order changed, delete all cookies

	init();

	function init() {
		restoreFromCookie();

		if (isOrderChanged()) {
			console.log('order has changed');
			// alert("由于订单修改，请重新分菜");

			if (suborders.isAnySuborderPaid()) {
				var info = "由于订单改变，且有部分子单支付, 请重新录入已付款信息\n";

				for (var i = 0; i < suborders.suborders.length; ++i) {
					var tempSuborderInfo = suborders.suborders[i].receiptInfo;
					var tempInfo = "子单号:" + tempSuborderInfo.suborder_no + ",  总计:" + tempSuborderInfo.total + ", 实收 卡:" + tempSuborderInfo.received_card + " 现金:" + tempSuborderInfo.received_cash + " , 小费: " + tempSuborderInfo.tip_amount + ", 找零:" + tempSuborderInfo.change;

					var tempItemInfo = " 菜:";
					for (var j = 0; j < tempSuborderInfo.items.length; ++j) {
						tempItemInfo += tempSuborderInfo.items[j].name_zh;
						tempItemInfo += ' ' + tempSuborderInfo.items[j].selected_extras_name;
					}
					tempInfo += tempItemInfo;

					info += tempInfo + '\n';
				}

				$('#dangerous-notice').text(info);
				$('#dangerous-notice').html($('#dangerous-notice').html().replace(/\n/g,'<br/>'));
			}

			order = loadOrder(order_no);
			suborders = new Suborders();


			KVStorage.remove(orderCookie, { path: '' });
			KVStorage.remove(subordersCookie, { path: '' });
		}

		drawUI();
	}





	// construct suborders by order
	function restoreFromCookie(orderObj, suborderObj) {

		// check whether cookie exist
		// var tempOrder = KVStorage.get(orderCookie);
        var tempOrder;
        $.ajax({
            url: "<?php echo $this->Html->url(array('controller' => 'split', 'action' => 'getCookie')); ?>",
            method: "post",
            async: false,
            data: {
                key: orderCookie
            },
            success: function (value) {
                if (value.trim()) {
                    tempOrder = JSON.parse(value.trim());
                } else {
                    tempOrder = undefined;
                }

                if (tempOrder != undefined) {
                    console.log(tempOrder)
        			order = Order.fromJSON(tempOrder);
                    console.log(order)
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

            var tempSuborders

        		$.ajax({
        			url: "<?php echo $this->Html->url(array('controller' => 'split', 'action' => 'getCookie')); ?>",
                    method: "post",
                    data: {
                    	key: subordersCookie
                    },
                    async: false,
                    success: function (value) {
                        // drawUI();
                    	if (value.trim()) {
                    		tempSuborders =  JSON.parse(value.trim());
                    	} else {
                    		tempSuborders = undefined;
                    	}

                        if (tempSuborders != undefined) {
                			for (var i = 0; i < tempSuborders.suborders.length; ++i) {
                				var temp_no = tempSuborders.suborders[i].suborder_no;
                				// console.log(temp_no);
                				suborders.getSuborder(temp_no).fromJSON(tempSuborders);
                			}
                		}
                        drawUI();
                    }
        		})


                console.log('tempOrder');
        		console.log(tempOrder);


            }
        })


		// if (tempOrder != undefined) {
		// 	order = Order.fromJSON(tempOrder);
		// 	suborders = new Suborders();
		// 	// construct suborder
        //
		// 	for (var i = 0; i < order.suborderNum; ++i) {
		// 		suborders.pushEmptySuborder();
		// 	}
        //
		// 	// restore suborder from order
		// 	for (var i = 0; i < order.items.length; ++i) {
		// 		if (order.items[i].state == 'keep') {
		// 			continue;
		// 		} else if (order.items[i].state == 'assigned') { // restore based on assigned_suborder
		// 			suborders.getSuborder(order.items[i].assigned_suborder).addItem(order.items[i])
		// 		} else if (order.items[i].state == 'share') { //restore based on shared_suborders
		// 			for (var j = 0; j < order.items[i].shared_suborders.length; ++j) {
		// 				suborders.getSuborder(order.items[i].shared_suborders[j]).addItem(order.items[i]);
		// 			}
		// 		}
		// 	}
		// }
        // var tempSuborders
        //
		// $.ajax({
		// 	url: "<?php echo $this->Html->url(array('controller' => 'split', 'action' => 'getCookie')); ?>",
        //     method: "post",
        //     data: {
        //     	key: subordersCookie
        //     },
        //     success: function (value) {
        //         // drawUI();
        //     	if (value.trim()) {
        //     		tempSuborders =  JSON.parse(value.trim());
        //     	} else {
        //     		tempSuborders = undefined;
        //     	}
        //
        //         if (tempSuborders != undefined) {
        // 			for (var i = 0; i < tempSuborders.suborders.length; ++i) {
        // 				var temp_no = tempSuborders.suborders[i].suborder_no;
        // 				// console.log(temp_no);
        // 				suborders.getSuborder(temp_no).fromJSON(tempSuborders);
        // 			}
        // 		}
        //         drawUI();
        //     }
		// })

			// return false;
			// return Cookies.getJSON(key);

		// var tempSuborders = KVStorage.get(subordersCookie);
		// if (tempSuborders != undefined) {
		// 	for (var i = 0; i < tempSuborders.suborders.length; ++i) {
		// 		var temp_no = tempSuborders.suborders[i].suborder_no;
		// 		// console.log(temp_no);
		// 		suborders.getSuborder(temp_no).fromJSON(tempSuborders);
		// 	}
		// }

		// restore from the discount cookie
	}

	function deleteAllCookies () {
		KVStorage.remove(orderCookie, { path: '' });
		KVStorage.remove(subordersCookie, { path: '' });
	}

    //reduce the ajax set request
    var timeout = 500;
    var scheduler = new Scheduler(setCookiesAjax, timeout);

    function Scheduler(callback, timeout){

        this.callback = callback;
        this.timeout = timeout;
        this.updateDataTimeout = null;

        this.updateDataInAWhile = function(){

            if(this.updateDataTimeout){
                clearTimeout(this.updateDataTimeout);
            }

            var self = this;
            this.updateDataTimeout = setTimeout(function(){
                self.callCallback();
            }, this.timeout);
        };

        this.callCallback = function(){
            if($.isFunction(this.callback)){
                this.callback();
            }
        }
    }
    function setCookiesAjax() {
        console.log(orderCookie)
        console.log(subordersCookie)
        KVStorage.set(orderCookie, order, { expires: 3, path: '' });
		KVStorage.set(subordersCookie, suborders, { expires: 3, path: '' });
    }

	function persistentOrder(callback) {
        scheduler.updateDataInAWhile();
		// KVStorage.remove(orderCookie, { path: '' });
		// KVStorage.remove(subordersCookie, { path: '' });
        // KVStorage.set(orderCookie, order, { expires: 3, path: '' });
		// KVStorage.set(subordersCookie, suborders, { expires: 3, path: '' });
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
			// alert("Please indicate suborder id");
			$.notify("Please indicate suborder id \n请指定子单号",{ position: "top center", className:"warn"});
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
			// alert("Please make sure you have more than two people to share, or more than one item to be shared.");

			$.notify("Please make sure you have more than two people to share, or more than one item to be shared. \n请确定至少两人来分单或至少有个一个菜",{ position: "top center", className:"warn"});
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
			// alert("No person to be deleted");
			$.notify("No person to be deleted. \n无人可删",{ position: "top center", className:"warn"});
		}

	}

		// enter the number
	// change the suborder based on the suborders tab
	// only pay when order is totally split
	// once is paid, the order and suborder cannot be modified any more
	function enterInput (callback) {
		// only when order items are totally assigned, the enter will react
		if (order.availableItems.length > 0) {
			// alert("You should assign all items of order to suborders");
			$.notify("You should assign all items of order to suborders. \n请分完所有的菜",{ position: "top center", className:"warn"});
			return;
		}

		var payOrTip = $('input[name="pay-or-tip"]:checked').attr('data-type');
		var cardOrCash = $('#input-type-group input:checked').attr('data-type');

		var currentSuborderId = $('.suborders-detail-tab.active').attr('data-index');

		var inputNum = parseFloat($('#input-screen').val());
		var member_id = $('#membercard_id').val();
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

						if (inputNum > currentSuborder.total) {
							currentSuborder._tip.card = inputNum - currentSuborder.total;
						} else {
							currentSuborder._tip.card = 0;
						}
					} else if (cardOrCash == "membercard") {
						// give notification when the number is input into suborder
						currentSuborder._received.membercard = inputNum;
						currentSuborder._received.memberid = member_id;
						if (inputNum > currentSuborder.total) {
							currentSuborder._tip.membercard = inputNum - currentSuborder.total;
						} else {
							currentSuborder._tip.membercard = 0;
						}
					} else if (cardOrCash == "cash") {
						// give notification when the number is input into suborder
						currentSuborder._received.cash = inputNum;

					}

				} else if (payOrTip == "tip") {
					if (cardOrCash == "card") {
						currentSuborder._tip.card = inputNum;

					} else if (cardOrCash == "membercard") {
						currentSuborder._tip.membercard = inputNum;
					} else if (cardOrCash == "cash") {
						//  give notification when the number is input into suborder
						currentSuborder._tip.cash = inputNum;

					}

				}
				// console.log(inputNum);

				// drawUI();

				persistentOrder();
				drawExceptKeypad();

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
		$('#input-placeholder').append(KeypadComponent( {"cardImg": cardImg, "cashImg": cashImg}, drawExceptKeypad, persistentOrder));
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
				var cnt = 0;
			<?php

			    $i = 0;
			    foreach ($Order_detail['OrderItem'] as $key => $value) {

			        $selected_extras_name = [];
			        // if ($value['all_extras']) {
			        //     $extras = json_decode($value['all_extras'], true);
			            $selected_extras = json_decode($value['selected_extras'], true);

			            // prepare extras string
			            $selected_extras_id = [];
			            if (!empty($selected_extras)) {
			                foreach ($selected_extras as $k => $v) {
			                    $selected_extras_name[] = $v['name'];
			                    $selected_extras_id[] = $v['id'];
			                }
			            }
			        // }
	        ?>
	        		var qty = parseInt('<?php echo $value['qty'] > 1 ? intval($value['qty']) : 1 ?>');
	        		// console.log("quantity");
	        		// console.log(qty);

	        		for (var i = 0; i < qty; ++i) {
	        			var temp_item = new Item(
		        			item_id = cnt++,
		        			image= '<?php if ($value['image']) { echo $value['image']; } else { echo 'no_image.jpg';};?>',
		        			name_en = '<?php echo $value['name_en']; ?>',
		        			name_zh = '<?php echo $value['name_xh']; ?>',
		        			selected_extras_name = '<?php echo implode(",", $selected_extras_name); ?>', // can be extend to json object
		        			price = '<?php echo $value['price'] ?>',
		        			extras_amount = '<?php echo $value['extras_amount'] ?>',
		        			quantity = 1,
		        			order_item_id = '<?php echo $value['id'] ?>',
		        			state = "keep",
		        			shared_suborders = null,
	                        assigned_suborder = null,
	                        is_takeout = '<?php echo $value["is_takeout"] ?>',
	                        comb_id = '<?php echo $value["comb_id"] ?>',
	                        selected_extras_json = '<?php echo $value['selected_extras'] ?>',
	                        is_print = '<?php echo $value['is_print']?>');

		        		tempOrder.addItem(temp_item);
	        		}



		    <?php
				   	// $i++;
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
        console.log(temp_order);
        console.log(order)

		if (temp_order.discount.type != order.discount.type || temp_order.discount.value != order.discount.value) {
			order.discount.type = temp_order.discount.type;
			order.discount.value = temp_order.discount.value;
		}

		if ((temp_order['items'].length != order['items'].length)) {
			changed = true;
		} else {
			for (var i = 0; i < temp_order['items'].length; ++i) {
				if (temp_order['items'][i]['order_item_id'] != order['items'][i]['order_item_id']) {
					changed = true
				}
			}
		}
    
    console.log(changed)

		return changed;
	}


	$('#print-split-receipt').on('click', function() {
		printSplitReceipt(order, suborders);
	});
	
	// print accounding order and suborders
	function printSplitReceipt(order, suborders) {

		var order = order;
		var suborders = suborders;

		for (var i = 0; i < suborders.suborders.length; ++i) {
			var tempSuborder = suborders.suborders[i];
			$.ajax({
				url: '<?php echo $this->Html->url(array("controller" => "print", "action" => "printSplitReceipt", $Order_detail["Order"]["order_no"],$table, $type, $cashier_detail["Admin"]["service_printer_device"], true, true));?>',
				type: 'POST',
				async: false,
				data: {
					suborder: tempSuborder.receiptInfo,
					logo_name: '../webroot/img/logo.bmp',
				},
				dataType :"text",
        error: function(XMLHttpRequest, textStatus, errorThrown) {
        	 console.log("printSplitReceipt error! "+"status: "+XMLHttpRequest.status + "readyState: "+XMLHttpRequest.readyState);
        },
        success: function(msg){
          console.log("printSplitReceipt successfully!");
        } 				
			});

		}

	}



	$('#print-split-bill').on('click', function() {
		printSplitBill(order, suborders);
	});
	
	function printSplitBill(order, suborders) {

		var order = order;
		var suborders = suborders;

		for (var i = 0; i < suborders.suborders.length; ++i) {
			var tempSuborder = suborders.suborders[i];
			$.ajax({
				url: '<?php echo $this->Html->url(array("controller" => "print", "action" => "printSplitReceipt", $Order_detail["Order"]["order_no"],$table, $type, $cashier_detail["Admin"]["service_printer_device"], true, false));?>',
				method: 'post',
				data: {
					suborder: tempSuborder.receiptInfo,
					logo_name: '../webroot/img/logo.bmp',
				}
			});

		}

	}

	$('#print-original-bill').on('click', function() {
		printOriginalBill();
	})

	function printOriginalBill() {

		var  a = '<?php $cashier_detail["Admin"]["service_printer_device"]; ?>'
		$.ajax({
			url: '<?php echo $this->Html->url(array("controller" => "print", "action" => "printOriginalBill", $Order_detail["Order"]["order_no"], $table, $type, $cashier_detail["Admin"]["service_printer_device"]));?>',
			method: 'post',
			data: {
				order: order.billInfo,
				logo_name: '../webroot/img/logo.bmp',
			}
		})
	}


</script>
