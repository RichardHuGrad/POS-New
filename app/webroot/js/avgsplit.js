



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
	constructor(order_no, items=[]) {
		this.items = items;
		this.order_no = order_no;
	}

	toJSON() {
		return {
			"items": this.items,
			"order_no": this.order_no
		}
	}

	static fromJSON(obj) {
		if (typeof obj == "string") obj = JSON.parse(obj);
		var instance = new Order(obj.order_no);
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
		this._state = "unpaid";
		this._tax_rate = 0.13;

		this._received = {
			"cash": 0,
			"card": 0,
			"total": 0
		};
		this._tip = {
			"type": "unknown", // card or cash
			"amount": 0
		};
		this._discount = {
			"type": "unknown", // fixed or rate
			"amount": 0
		};
		
	}


	toJSON() {
		return {
			'items': this.items,
			'suborder_no': this.suborder_no,
			'state': this._state,
		}
	}

	// the items should come from Order instance
	// other information comes from obj
		// restore from cookie first,
	// then bind the item with order items
	static fromJSON(order, obj) {
		if (!(order instanceof Order)) {
			return false;
		}
		if (typeof obj == "string") obj = JSON.parse(obj);
		/*
		var instance = new Suborder(obj.suborder_no);
		for (var i = 0; i < obj.items.length; ++i) {
			var tempItem = Item.fromJSON(obj.items[i]);
			instance.items.push(tempItem);
		}

		return instance;*/
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
			"amount": round2(subtotal * this._tax_rate)
		}
	}

	get total() {
		return round2(this.subtotal + this.tax - this.discount);
	}

	get received() {
		return this._received;
	}

	get tip() {
		return this._tip;
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
		this.extras_amount = extras_amount;
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

	var orderComponent = $('<div>');
	var orderUl = $('<ul>');
	// var avgSplitButton = $('<button id="avg-split" class="btn btn-primary btn-lg">').text("Avg. Split");

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


	orderComponent.append(orderUl);

	return orderComponent;
}

var OrderItemComponent = function(item, cfg) {

	var cfg = cfg || {};
	var item_id = item["item_id"];

	var orderItemComponent = $('<li class="order-item" id="order-item-' + item_id + '">');
	var nameDiv = $('<div class="col-md-9 col-sm-9 col-xs-8">').text(item["name_en"] + '\n' + item["name_zh"]);
	var priceDiv = $('<div class="col-md-3 col-sm-3 col-xs-4">').text('$' + item["price"]);

	orderItemComponent.append(nameDiv).append(priceDiv);

	orderItemComponent.on("click", function() {
		assignItem(order, item_id, suborders, current_suborder);
	});

	return orderItemComponent;
}


var SuborderItemComponent = function (item, cfg) {
	var cfg = cfg || {};
	var item_id = item["item_id"];

	var suborderItemComponent = $('<li class="suborder-item" id="suborder-item-' + item_id + '" data-itemId="' + item_id + '">');
	var nameDiv = $('<div class="col-md-9 col-sm-9 col-xs-8">').text(item["name_en"] + '\n' + item["name_zh"]);
	var priceDiv = $('<div class="col-md-3 col-sm-3 col-xs-4">').text('$' + item["price"]);

	suborderItemComponent.append(nameDiv).append(priceDiv);

	suborderItemComponent.on('click', function() {
		returnItem(item_id);
	});


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
	suborderListComponent.on("click", function () {
		// set current person
		current_suborder = suborderId;
		$(".suborder-label").css("background-color", "white");
		$(this).find("label").css("background-color", "red");
	});
	// set label css
	if (current_suborder == suborderId) {

		// console.log('#suborder-label-' + String(current_suborder));
		suborderLabel.css("background-color", "red");
	}



	return suborderListComponent; 
}

var SuborderDetailComponent = function (suborder, cfg) {
	var cfg = cfg || {};
	var suborderId = suborder.suborder_no;

	// var suborderTab = $('');
	var suborderDetailComponent = $('<div class="suborder-detail">').attr("id", suborderId);
	// var suborderTabCompoenent = $('<a class="suborder-tab">');
	var subtotalComponent = $('<>');


}


var SubordersListComponent = function (suborders, cfg) {
	var cfg = cfg || {};
	var subordersListComponent = $('<div id="suborders">');
/*	var addPersonButton = $('<div id="add-person" class="btn btn-lg btn-primary">');
	var deletePersonButton = $('<div id="delete-person" class="btn btn-lg btn-danger">');
	subordersListComponent.append(addPersonButton).append(deletePersonButton);
*/
	var temp_suborders = suborders.suborders;

	for (var i = 0; i < temp_suborders.length; ++i) {
		// console.log(SuborderListComponent(temp_suborders[i]));
		subordersListComponent.append(SuborderListComponent(temp_suborders[i]));
	}


	return subordersListComponent;
}

var SubordersDetailComponent = function (suborders, cfg) {
	var cfg = cfg || {};

} 



var NumberKeyComponent = function (cfg) {
	var cfg = cfg || {};

	var numberKeyComponent = $('<div id="number-key">');
	var keyComponent = $('<ul>');
	var screenComponent = $('<div><input type="text" id="number-screen" buffer="0" maxlength="13"></div>')
	var clearButton = $('<button id="number-clear">');
	var enterButton = $('<button id="number-enter">');

	for (var i = 1; i <= 9; ++i){
		keyComponent.append('<li>' + i + '</li>' );
	}

	keyComponent.append(clearButton).append('<li>0</li>').append(enterButton);
}


function round2(number) {
	return Math.round(number * 100) / 100
}