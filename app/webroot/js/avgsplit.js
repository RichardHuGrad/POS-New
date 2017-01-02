



function areOrdersSame(order1, order2) {
	var same = false;
	var items1 = order1.items;
	var items2 = order2.items;

	if (items1.length != items2.length) {
		return false;
	} else {
		for (var i = 0; i < items1.length; ++i) {
				if (items1[i]['order_item_id'] != items2['order_item_id']) {
					changed = true
				}
			}
	}
}

class Order {
	constructor(order_no, items, suborderNum) {
		this.items = items || [];
		this.order_no = order_no;
		this.suborderNum = suborderNum || 0;
	}

	toJSON() {
		return {
			"items": this.items,
			"order_no": this.order_no,
			"suborderNum": this.suborderNum
		}
	}

	static fromJSON(obj) {

		if (typeof obj == "string") obj = JSON.parse(obj);
		var instance = new Order(obj.order_no);
		instance.suborderNum = obj.suborderNum;
		for (var i = 0; i < obj.items.length; ++i) {
			var tempItem = Item.fromJSON(obj.items[i]);
			instance.items.push(tempItem);
		}

		return instance;
	}



	addItem(item) {
		this.items.push(item);
	}



	getItem(item_id) {
		for (var i = 0; i < this.items.length; ++i) {
			if (item_id == this.items[i]["item_id"]) {
				return this.items[i];
			}
		}
	}

	setItemState(item_id, state) {
		this.getItem(item_id).state = state;
	}


	get json() {
		return {
			"items": this.items,
			"order_no": this.order_no
		}
	}

	get availableItemsNum() {
		var temp_items = this.items;
		var cnt = 0;
		for (var i = 0; i < temp_items.length; ++i) {
			if(temp_items[i]["state"] == "keep") {
				++cnt;
			}
		}

		return cnt;
	}

	// return not a reference
	get availableItems() {
		// var temp_items = this.items;
		var availableItems = [];
		for (var i = 0; i < this.items.length; ++i) {
			if(this.items[i]["state"] == "keep") { 
				availableItems.push(this.items[i]);
			}
		}

		return availableItems;
	}

	get assignedItemsNum() {
		var temp_items = this.items;
		var cnt = 0;
		for (var i = 0; i < temp_items.length; ++i) {
			if(temp_items[i]["state"] == "assigned") {
				++cnt;
			}
		}

		return cnt;
	}

	get sharedItemsNum() {
		var temp_items = this.items;
		var cnt = 0;
		for (var i = 0; i < temp_items.length; ++i) {
			if(temp_items[i]["state"] == "share") {
				++cnt;
			}
		}

		return cnt;
	}

}

class Suborders {
	constructor(suborders = []) {
		this.suborders = suborders;
		// this._length = this.suborders.length;
	}

	toJSON() {
		return {
			"suborders": this.suborders
		}
	}

	// restore from cookie first,
	// then bind the item with order items
	static fromJSON(order, obj) {
		if (!(order instanceof Order)) {
			return false;
		}
		if (typeof obj == "string") obj = JSON.parse(obj);
		/*var instance = new Suborders();
		for (var i = 0; i < obj.suborders.length; ++i) {
			// var tempItem = Item.fromJSON(obj.items[i]);
			var tempSuborder = Suborder.fromJSON(order, obj.suborders[i]);
			instance.suborders.push(tempSuborder);
		}

		return instance;*/
	}


	getSuborder(suborder_no) {
		for (var i = 0; i < this.suborders.length; ++i) {
			if (suborder_no == this.suborders[i].suborder_no) {
				return this.suborders[i]
			}
		}
	}

	pushEmptySuborder() {
		var suborder_no = this.length + 1;
		var temp_suborder = new Suborder(suborder_no);
		this.suborders.push(temp_suborder);
	}

	popSuborder() {
		if (this.suborders.length > 0) {
			return this.suborders.pop();
		} else {
			alert('no suborder to be removed');
		}
	}

	// remove all items whose state is "keep"
	refreshSuborders() {
		for (var i = 0; i < this.suborders.length; ++i) {
			this.suborders[i].refreshItems();
		}
	}

	isAnySuborderPaid() {
		for (var i = 0; i < this.suborders.length; ++i) {
			if (this.suborders[i].received.total > 0) {
				return true;
			}
		}

		return false;
	}

	get length() {
		// this._length = this.suborders.length;
		return this.suborders.length;
	}
}

// constructor and fromJSON should be done 
// when the suborder detail finish
class Suborder {
	constructor(suborder_no) {
		this.items = [];
		this.suborder_no = suborder_no;
		// this._state = "unpaid";
		this._tax_rate = 0.13;

		this._received = {
			"cash": 0,
			"card": 0,
			"total": 0
		};
		this._tip = {
			// "type": "unknown", // card or cash
			"cash": 0,
			"card": 0,
			"amount": 0
		};
		this._discount = {
			"type": "unknown", // fixed or rate
			"amount": 0
		};

		// this.change = 0;
		// this.remaining = 0;
		// this._subtotal = 0;
		
	}


	toJSON() {
		return {
			// 'items': this.items,
			'suborder_no': this.suborder_no,
			// 'state': this.state,
			
			// 'discount': this.discount,
			// 'tax': this.tax,
			'received': this.received,
			'tip': this.tip
		}
	}

	// the suborders should be restored from order already
	// the function should not be static
	fromJSON(obj) {
		if (typeof obj == "string") obj = JSON.parse(obj);
		
		for (var i = 0; i < obj.suborders.length; ++i) {
			var temp_no = obj.suborders[i].suborder_no;
			if (temp_no == this.suborder_no) {
				this._received.cash = obj.suborders[i].received.cash;
				this._received.card = obj.suborders[i].received.card;
				this._tip.cash = obj.suborders[i].tip.cash;
				this._tip.card = obj.suborders[i].tip.card;
			}
		}
	}

	addItem(item) {
		this.items.push(item) 
	}


	// remove all item whose state is "keep"
	// iterator from the back to the front
	refreshItems() {
		for (var i = this.items.length - 1; i >= 0 ; --i) {
			if (this.items[i].state == "keep") {
				this.items.splice(i, 1);
			}
		}
	}

	// return float with 2 percision
	get subtotal() {
		var subtotal = 0;
		for (var i = 0; i < this.items.length; ++i) {
			var temp_item = this.items[i];

			subtotal += parseFloat(temp_item["price"]) + parseFloat(temp_item["extras_amount"]);
		}

		return round2(subtotal);
	}

	//  to do
	get discount() {
		return this._discount;
	}

	// return float with 2 precision
	get tax() {
		return {
			"tax": this._tax_rate,
			"amount": round2(this.subtotal * this._tax_rate)
		}
	}

	get total() {
		return round2(this.subtotal + this.tax.amount - this.discount.amount);
	}

	get received() {
		return  {
					"card": round2(this._received.card),
					"cash": round2(this._received.cash),
					"total": round2 (this._received.card + this._received.cash)
				}
	}

	get tip() {
		return {
					"card": round2(this._tip.card),
					"cash": round2(this._tip.cash),
					"amount": round2(this._tip.card + this._tip.cash)
				};
	}

	get remain() {
		return this.total > this.received.total ? round2(this.total - this.received.total) : 0;
	}

	get change() {
		return this.received.total > this.total ? round2(this.received.total - this.total) : 0;
	}

	// paid or unpaid
	get state() {
		if (this.remain == 0 && this.received.total > 0) {
			return "paid";
		} else if (this.remain == 0 && this.received.total == 0){
			return "no+item";
		} else if (this.remain > 0 && this.received.total > 0) {
			return "not+finish";
		} else if (this.remain > 0 && this.received.total == 0) {
			return "unpaid";
		} else {
			return "ERROR";
		}
	} 


}

class Item {
	constructor(item_id, image, name_en, name_zh, selected_extras_name, price, extras_amount, quantity, order_item_id, state, shared_suborders, assigned_suborder) {
		this.item_id = item_id;
		this.image = image;
		this._name_en = name_en;
		this._name_zh = name_zh;
		this.selected_extras_name = selected_extras_name;
		this._price = price;
		this.extras_amount = extras_amount || 0;
		this.quantity= quantity;
		this.order_item_id = order_item_id;
		this._state = state ;
		this.shared_suborders = shared_suborders || [];
		this.assigned_suborder = assigned_suborder || 0;
	}

	toJSON() {
		return {
			"item_id": this.item_id,
			"image": this.image,
			"name_en": this._name_en,
			"name_zh": this._name_zh,
			"selected_extras_name": this.selected_extras_name,
			"price": this._price,
			"extras_amount": this.extras_amount,
			"quantity": this.quantity,
			"order_item_id": this.order_item_id,
			"state": this._state,
			"shared_suborders": this.shared_suborders,
			"assigned_suborder": this.assigned_suborder
		}
	}

	static fromJSON(obj) {
		if (typeof obj == "string") obj = JSON.parse(obj);
		var instance = new Item(obj.item_id, obj.image, obj.name_en, obj.name_zh, obj.selected_extras_name, obj.price, obj.extras_amount, obj.quantity, obj.order_item_id, obj.state, obj.shared_suborders, obj.assigned_suborder);
		return instance;
	}

	// if state is set to "keep"
	// the shared_suborders should be clear
	set state(state) {
		// state should be keep, assigned, share
		var stateList = Array("keep", "assigned", "share");
		if (stateList.indexOf(state) != -1) {
			if (state == "keep") {
				this.shared_suborders = [];
				this.assigned_suborder = 0;
			} else if (state == "assigned") {
				this.shared_suborders = [];
			} else if (state == "share") {
				this.assigned_suborder = 0;
			}
			this._state = state;
		} else {
			alert("State Errors: No existed state");
			return false;
		}
	}

	get state() {
		return this._state;
	}

	get price() {
		if (this.state == "share" && this.shared_suborders.length > 1) {
			return round2(this._price / this.shared_suborders.length)
		} else {
			return this._price;
		}
	}

	get name_en() {
		if (this.state == "share" && this.shared_suborders.length > 1) {
			var tempStr = this._name_en + ' shared by';
			for (var i = 0; i < this.shared_suborders.length; ++i) {
				tempStr += " " + String(this.shared_suborders[i])
			}

			return tempStr;
		} else {
			return this._name_en;
		}
	}

	get name_zh() {
		if (this.state == "share" && this.shared_suborders.length > 1) {
			var tempStr = this._name_zh + ' shared by';
			for (var i = 0; i < this.shared_suborders.length; ++i) {
				tempStr += " " + String(this.shared_suborders[i])
			}

			return tempStr;
		} else {
			return this._name_zh;
		}
	}
}

//  only draw item which state is "keep"
var OrderComponent = function(order, cfg) {
	var cfg = cfg || {};

	var orderComponent = $('<div id="order-component">');
	var orderUl = $('<ul>');
	var avgSplitButton = $('<button id="avg-split" class="btn btn-primary btn-lg">').text("Avg. Split");
	
	if (!suborders.isAnySuborderPaid()) {
		avgSplitButton.on('click', function () { avgSplit(); });
	} else {
		avgSplitButton.prop('disabled', true);
	}
	

	var items = order.items;
	for (var i = 0; i < items.length; ++i) {
		if (items[i]["state"] == "keep") {
			var temp_itemComponent = OrderItemComponent(items[i]);
			
			// console.log(items[i]);
			/*temp_itemComponent.on('click', function () {
				assignItem(order, items[i]["item_id"], suborders, current_suborder);
			});*/

			orderUl.append(temp_itemComponent);
		}
	}


	orderComponent.append(orderUl).append(avgSplitButton);

	return orderComponent;
}

var OrderItemComponent = function(item, cfg) {

	var cfg = cfg || {};
	var item_id = item["item_id"];

	var orderItemComponent = $('<li class="order-item" id="order-item-' + item_id + '">');
	var nameDiv = $('<div class="col-md-9 col-sm-9 col-xs-8">').text(item["name_en"] + '\n' + item["name_zh"]);
	var priceDiv = $('<div class="col-md-3 col-sm-3 col-xs-4">').text('$' + item["price"]);

	orderItemComponent.append(nameDiv).append(priceDiv);

	// if any order paid, do not attach click event on it
	if (!suborders.isAnySuborderPaid()) {
		orderItemComponent.on("click", function() {
			assignItem(order, item_id, suborders, current_suborder);
		});
	} else {
		orderItemComponent.css('cursor', 'not-allowed');
	}
	
	return orderItemComponent;
}


var SuborderItemComponent = function (item, cfg) {
	var cfg = cfg || {};
	var item_id = item["item_id"];

	var suborderItemComponent = $('<li class="suborder-item" id="suborder-item-' + item_id + '" data-itemId="' + item_id + '">');
	var nameDiv = $('<div class="col-md-9 col-sm-9 col-xs-8">').text(item["name_en"] + '\n' + item["name_zh"]);
	var priceDiv = $('<div class="col-md-3 col-sm-3 col-xs-4">').text('$' + item["price"]);

	suborderItemComponent.append(nameDiv).append(priceDiv);

	if (!suborders.isAnySuborderPaid()) {
		suborderItemComponent.on('click', function() {
			returnItem(item_id);
		});
	} else {
		suborderItemComponent.css('cursor', 'not-allowed');
	}

	return suborderItemComponent;
}


//  should judge whether the suborder is paid
var SuborderListComponent = function(suborder, cfg) {
	var cfg = cfg || {};
	var suborderId = suborder.suborder_no;

	var suborderListComponent = $('<div class="suborder-list" id="suborder-"' + suborderId + '>');
	var suborderLabel = $('<label class="suborder-label">').attr("id", "suborder-label-" + suborderId).text("Customer #" + suborderId);
	var suborderUl = $('<ul>');

	var items = suborder.items;

	// in for loop can't pass variable to listener correctly
	// otherwise use $.each() async method
	for (var i = 0; i < items.length; ++i) {	
		suborderUl.append(SuborderItemComponent(items[i]));
	}
	
	suborderListComponent.append(suborderLabel).append(suborderUl);


	// to be concised
	//  in the compoenent, cannot use selector to select item
	if (!suborders.isAnySuborderPaid()) {
		suborderListComponent.on("click", function () {
			// set current person
			current_suborder = suborderId;
			// $(".suborder-label").css("background-color", "white");
			if ($(".suborder-label").hasClass('active')) {
				$(".suborder-label").removeClass('active');
			}

			// $(this).find("label").css("background-color", "red");
			$(this).find("label").addClass('active');
		});
		// set label css
		if (current_suborder == suborderId) {
			// console.log('#suborder-label-' + String(current_suborder));
			// suborderLabel.css("background-color", "red");
			suborderLabel.addClass('active');
		}
	} else {
		suborderListComponent.css('cursor', 'not-allowed');
	}


	return suborderListComponent; 
}

// should add discounts
var SuborderDetailComponent = function (suborder, cfg) {
	var cfg = cfg || {};
	var suborderId = suborder.suborder_no;

	// var suborderTab = $('');
	var suborderDetailComponent = $('<ul class="suborder-detail">').attr("id", "suborder-detail-" + suborderId);
	// var suborderTabCompoenent = $('<a class="suborder-tab">');
	var titleComponent = $('<li class="suborder-title">').text("Suborder #" + order_no + '-' + suborder.suborder_no);

	// var stateComponent = $('<li class="suborder-state">').text("State :" + suborder.state);

	var subtotalComponent = $('<li class="suborder-subtotal">').text("Subtotal 小计:" + suborder.subtotal);

	var taxComponent = $('<li class="suborder-tax">').text("Tax 税 (13%): " + suborder.tax.amount);

	//  var discountComponent

	var totalComponent = $('<li class="suborder-total">').text("Total 总: " + suborder.total);

	var receivedComponent = $('<li class="suborder-received">').text("Received 收到: " + suborder.received.total + " Cash 现金: " + suborder.received.cash + " Card 卡: " + suborder.received.card);

	var remainComponenet = $('<li class="suborder-remain">').text("Remaining 其余: " + suborder.remain);

	var changeComponent = $('<li class="suborder-change">').text("Change 找零: " + suborder.change);

	var tipComponenet = $('<li class="suborder-tip">').text("Tip 小费: " + suborder.tip.amount + " Cash 现金:" + suborder.tip.cash + " Card 卡: " + suborder.tip.card);

	suborderDetailComponent.append(titleComponent).append(subtotalComponent).append(taxComponent).append(totalComponent).append(receivedComponent).append(remainComponenet).append(changeComponent).append(tipComponenet);

	// set css accounding to the state
	suborderDetailComponent.css("background-image", "url(https://dummyimage.com/600x200/ffffff/b4b5bf.png&text=" + suborder.state + ")");

	return suborderDetailComponent;
}


var SubordersListComponent = function (suborders, cfg) {
	var cfg = cfg || {};
	var subordersListComponent = $('<div id="suborders-list-component">');
	var addPersonButton = $('<button id="add-person" class="btn btn-lg btn-primary">').text('Add Person 增加人');
	var deletePersonButton = $('<button id="delete-person" class="btn btn-lg btn-danger">').text('Delete Person 删除人');
							
	if (!suborders.isAnySuborderPaid()) {
		addPersonButton.on('click', function() { addPerson();});
		deletePersonButton.on('click', function() { deletePerson(suborders); });
	} else {
		addPersonButton.prop('disabled', true);
		deletePersonButton.prop('disabled', true);
	}
	
	var itemsComponent = $('<div id="suborders-list-items">');

	var temp_suborders = suborders.suborders;

	for (var i = 0; i < temp_suborders.length; ++i) {
		// console.log(SuborderListComponent(temp_suborders[i]));
		itemsComponent.append(SuborderListComponent(temp_suborders[i]));
	}

	subordersListComponent.append(addPersonButton).append(deletePersonButton).append(itemsComponent);

	return subordersListComponent;
}

var SubordersDetailComponent = function (suborders, cfg) {
	var cfg = cfg || {};


	var subordersDetailComponent = $('<div>');
	var subordersComponent = $('<div>');
	var tabComponent = $('<ul id="suborders-detail-tab-component">');


	for (var i = 0; i < suborders.suborders.length; ++i) {
		var curSuborder = suborders.suborders[i];

		var tab = $('<li class="suborders-detail-tab">')
			.attr("id", "suborders-detail-tab-" + curSuborder.suborder_no)
			.attr("data-index", curSuborder.suborder_no)
			.text("tab #" + curSuborder.suborder_no)
			.on('click', function() {
					$(".suborder-detail").css("display", "none");
					var index = $(this).attr("data-index");
					$("#suborder-detail-" + index).css("display", "block")

					$('.suborders-detail-tab').each(function () {
						if ($(this).hasClass('active')) {
							$(this).removeClass('active');
						}
					});

					$(this).addClass('active');
				});

		tabComponent.append(tab);
	
		subordersComponent.append(SuborderDetailComponent(curSuborder).css("display", "none"));
	}

	subordersDetailComponent.append(tabComponent).append(subordersComponent);

	return subordersDetailComponent;
} 

var DiscountComponent = function (cfg) {

}



// get val of $('#screen')
// get type by whether button is active
var KeypadComponent = function (cfg) {
	var cfg = cfg || {};

	var keypadComponent = $('<div id="input-keypad">');
	
	var keyScreenWrapper = $('<div id="input-key-screen-wrapper">');

	var screenComponent = $('<div><input type="text" id="input-screen" data-buffer="0" data-maxlength="13" value="00.00">');


	var buttonGroup = $('<div>');

	var payOrTipGroup = $('<div class="form-group">')
	var paySelect= $('<label><input type="radio" id="pay-select" name="pay-or-tip" data-type="pay">Payment</label>');
	var tipSelect = $('<label><input type="radio" id="tip-select" name="pay-or-tip" data-type="tip">Tip</label>');
	
	// maybe change the name, is used for select card or cash
	var typeGroup = $('<div id="input-type-group" class="form-group">');


	// var payGroup = $()
	var payCardButton = $('<label><input type="radio" id="pay-card" name="pay" data-type="card">Card 卡</label>');							
	var payCashButton = $('<label><input type="radio" id="pay-cash" name="pay" data-type="cash">Cash 现金</label>');
	// payForm.append(payCardButton).append(payCashButton);

	var tipCardButton = $('<label><input type="radio" id="tip-card" name="tip" data-type="card">Card 卡</label>');
	var tipCashButton = $('<label><input type="radio" id="tip-cash" name="tip" data-type="cash">Cash 现金</label>');

	// confirm: write the input into the suborder detail
	var confirmButton = $('<button class="btn btn-success btn-lg card-ok" id="input-confirm">').text('Confirm 确定');

	var submitButton = $('<button class="btn btn-success btn-lg card-ok" id="input-submit">').text('Submit 提交');


	payOrTipGroup.append(paySelect).append(tipSelect).find("input").on("change", function () {
		if ($(this).is(':checked') && $(this).attr('id') == "pay-select") {
			// enable payment buttons
			typeGroup.empty();
			typeGroup.append(payCardButton).append(payCashButton);
			
			// clear the screen
			// screenClear.trigger('click');
			
			// store the type in the buffer
			// payOrTipBuffer.text('pay');

			console.log("payment is selected");
		} else if ($(this).is(':checked') && $(this).attr('id') == "tip-select") {
			// enable tip buttons
			typeGroup.empty();
			typeGroup.append(tipCardButton).append(tipCashButton);

			// clear the screen
			// screenClear.trigger('click');
			
			// store the type in the buffer
			// payOrTipBuffer.text('tip');
			
			console.log("tip is selected");
		} else {
			console.log('error');
		}
	});


	buttonGroup.append(payOrTipGroup).append(typeGroup);
	// buttonGroup.append(payCardButton).append(payCashButton).append(tipCardButton).append(tipCashButton).append(confirmButton).append(submitButton);

	// construct keypad
	var keyComponent = $('<ul id="input-key-list">');
	for (var i = 1; i <= 9; ++i){
		keyComponent.append('<li data-num=' + i + '>' + i + '</li>' );
	}

	var screenClear = $('<li id="input-clear">').text("Clear 清除")
												.on('click', function() {
													// var value = $('#input-screen').val().slice(0, -1);
													$('#input-screen').attr("data-buffer", "0")
													$('#input-screen').val("00.00");
												});

    // should be changed
    // should not change the suborder state directly
	var screenEnter = $('<li id="input-enter">').text("Enter 输入")
												.on('click', function() {
													enterInput();
												});


	keyComponent.append(screenClear).append('<li data-num=0 >0</li>').append(screenEnter);

	//  to be fixed
	//  add restriction of num length
	keyComponent.find('li').each(function() {
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
	});



	keyScreenWrapper.append(screenComponent).append(keyComponent);

	keypadComponent.append(keyScreenWrapper).append(buttonGroup);

	return keypadComponent;
}


function round2(number) {
	return Math.round(number * 100) / 100
}