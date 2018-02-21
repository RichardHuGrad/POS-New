class KeypadComponent {
	constructor(order, suborders, cfg, drawFunction, persistentFunction) {
		this.order = order;
		this.suborders = suborders;
		this.cfg = cfg;
		this.drawFunction = drawFunction;
		this.persistentFunction = persistentFunction;

		this.component = {};
	}

	get template() {
		return 
		(`<div id="input-keypad">
			    <div id="input-key-screen-wrapper">
			        <input type="text" id="input-screen" data-buffer="0" data-maxlength="13" value="00.00">
			        <ul id="input-key-list">
			            <li data-num="1">1</li>
			            <li data-num="2">2</li>
			            <li data-num="3">3</li>
			            <li data-num="4">4</li>
			            <li data-num="5">5</li>
			            <li data-num="6">6</li>
			            <li data-num="7">7</li>
			            <li data-num="8">8</li>
			            <li data-num="9">9</li>
			            <li id="input-clear">Clear 清除</li>
			            <li data-num="0">0</li>
			            <li id="input-enter">Enter 输入</li>
			        </ul>
			    </div>
			    <div>
			        <div id="paid-or-tip-group" class="form-group">
			            <label>
			                <input type="radio" id="pay-select" name="pay-or-tip" data-type="pay">Payment</label>
			            <label>
			                <input type="radio" id="tip-select" name="pay-or-tip" data-type="tip">Tip</label>
			        </div>
			        <div id="card-or-cash-group" class="form-group">
			            <label>
			                <input type="radio" id="pay-card" name="pay" data-type="card"><img src="/pos-new/img/card.png" alt="card">Card 卡</label>
			            <label>
			                <input type="radio" id="pay-cash" name="pay" data-type="cash"><img src="/pos-new/img/cash.png" alt="cash">Cash 现金</label>
			        </div>
			        <button class="btn btn-success btn-lg" id="input-submit">Submit 提交</button>
			    </div>
			</div>`)
	}


	createDom() {
		var component = this.component;


		var keypadComponent = $(this.template);

		component.keyComponent = keypadComponent.find('#input-key-list');
		component.clearScreen = keypadComponent.find('#input-clear');
		component.enterScreen = keypadComponent.find('#input-enter');
		component.payOrTipGroup = keypadComponent.find('#paid-or-tip-group');

		var payOrTipGroup = $('#paid-or-tip-group');
		var paySelect= $('<label><input type="radio" id="pay-select" name="pay-or-tip" data-type="pay">Payment</label>');
		var tipSelect = $('<label><input type="radio" id="tip-select" name="pay-or-tip" data-type="tip">Tip</label>');
		
		// maybe change the name, is used for select card or cash
		component.typeGroup = $('<div id="input-type-group" class="form-group">');


		component.payCardButton = $('<label><input type="radio" id="pay-card" name="pay" data-type="card">' + cfg.cardImg + 'Card 卡</label>');							
		component.payMemberCardButton = $('<label><input type="radio" id="pay-card" name="pay" data-type="membercard">' + cfg.cardImg + 'Member Card 卡</label>');							
		component.payCashButton = $('<label><input type="radio" id="pay-cash" name="pay" data-type="cash">' + cfg.cashImg + 'Cash 现金</label>');
		// payForm.append(payCardButton).append(payCashButton);

		component.tipCardButton = $('<label><input type="radio" id="tip-card" name="tip" data-type="card">' + cfg.cardImg + 'Card 卡</label>');
		component.tipMemberCardButton = $('<label><input type="radio" id="tip-card" name="tip" data-type="membercard">' + cfg.cardImg + 'Member Card 卡</label>');
		component.tipCashButton = $('<label><input type="radio" id="tip-cash" name="tip" data-type="cash">' + cfg.cashImg + 'Cash 现金</label>');

		// confirm: write the input into the suborder detail
		var confirmButton = $('<button class="btn btn-success btn-lg" id="input-confirm">').text('Confirm 确定');

		component.submitButton = $('#input-submit');

		buttonGroup.append(payOrTipGroup).append(typeGroup).append(submitButton);

		// build the keypad
		var keyComponent = $('#input-key-list');
		var screenClear = $('#input-clear');
		var screenEnter = $('#input-enter');
	}

	bindEvent(order, suborders) {
		// bind submit event
		if (order.availableItems.length == 0) { 
			this.component.submitButton.on('click', function() {
				submitOrder();
			});
		} else {
			submitButton.prop('disabled', true);
		}

		// input value
		this.component.keyComponent.find('li').each(function() {
			var attr = $(this).attr('data-num')
			if (typeof attr !== typeof undefined && attr !== false) {
				$(this).on('click', function () {
					// var value = $('#input-screen').val() ? parseFloat($('#input-screen').val() : 0;
					var buffer = $('#input-screen').attr("data-buffer") + $(this).attr('data-num');
					$('#input-screen').attr("data-buffer", buffer);
					var value = buffer / 100;
					value = value.toFixed(2);

					$('#input-screen').val(value);
				});
			}
		})


		// clear screen
		this.component.clearScreen.on('click', function() {
			$('#input-screen').attr("data-buffer", "0")
			$('#input-screen').val("00.00");
		});

		// enter screen
		this.component.enterScreen.on('click', function() {
			$(document).queue(enterInput).queue(persistentFunction).queue(drawFunction);
		})


		this.component.payOrTipGroup.find("input").on('change', function() {
			if ($(this).is(':checked') && $(this).attr('id') == "pay-select") {
				// enable payment buttons
				this.component.typeGroup.empty();
				this.component.typeGroup.append(this.component.payCardButton).append(this.component.payMemberCardButton).append(this.component.payCashButton);

				console.log("payment is selected");
			} else if ($(this).is(':checked') && $(this).attr('id') == "tip-select") {
				// enable tip buttons
				this.component.typeGroup.empty();
				this.component.typeGroup.append(this.component.tipCardButton).append(this.component.tipMemberCardButton).append(this.component.tipCashButton);
				
				console.log("tip is selected");
			} else {
				console.log('error');
			}
		});

	}


	submitOrder(order, suborder) {
		if (suborders.isAllSuborderPaid()) {
			
			
			// iterator all suborder
			for (var i = 0; i < suborders.suborders.length; ++i) {
				var tempSuborder = suborders.suborders[i];

				$.ajax({
					url: store_suborder_url,
					method: "post",
					data: {
						"table_no": table_id,
		                "order_no": order_no,
		                "suborder_no": tempSuborder.suborder_no,
	                	"subtotal": tempSuborder.subtotal,
	                	// "discount_type": tempSuborder.discount.type.toUpperCase(),
		                "discount_value": tempSuborder.discount.value,
	                	"tax": tempSuborder.tax.tax * 100,
	                	"tax_amount": tempSuborder.tax.amount,
		                "total": tempSuborder.total,
		                "paid_membercard": tempSuborder.received.membercard,
		                "paid_card": tempSuborder.received.card,
		                "paid_cash": tempSuborder.received.cash,
		                "tip_membercard": tempSuborder.tip.membercard,
		                "tip_card": tempSuborder.tip.card,
		                "tip_cash": tempSuborder.tip.cash,
		                "change": tempSuborder.change,
		                "items": JSON.stringify(tempSuborder.items),
					}
				}).done(function() {
					console.log("succuess");
				}).fail(function() {
					alert("fail");
				});
			}


			// update original order
			var sendData = suborders.count
			$.ajax({
				url: update_original_order_url,
				method: "post",
				data: {
	    			"order_no": order_no,
	    			// "table_no": table_id,
	    			// "tax": sendData.tax,
	    			// "tax_amount": sendData.tax_amount,
	    			// "subtotal": sendData.subtotal,
	    			// "total": sendData.total,
	    			"membercard_val": sendData.membercard_val,
	    			"card_val": sendData.card_val,
	    			"cash_val": sendData.cash_val,
	    			"tip": sendData.tip,
	    			"tip_paid_by": sendData.tip_paid_by.toUpperCase(),
	    			"paid": sendData.tip_paid,
	    			"change": sendData.change,
	    			"paid_by": sendData.paid_by.toUpperCase(),
	    			// "fix_discount": sendData.fix_discount,
	    			// "percent_discount": sendData.percent_discount,
	    			// "discount_value": sendData.discount_value
				}
			}).fail(function() {
					alert("fail");
			}).done(
				function() {
					window.location.replace(home_page_url);
				}
			);

		} else {
			if (suborders.suborders.length == 0) {
				$.notify("there is no suborder to submit");
			} else {
				var tempStr = suborders.unpaidSuborders.join();
				$.notify("please check the following suborders " + tempStr);
			}
		}
		
	}

	bindTypeButton() {
		// var payGroup = $()
		
	}
}