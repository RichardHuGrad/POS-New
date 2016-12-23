


// assign item to suborder
function assignItem(suborder_no, item_id) {
	var item = order.getItem(item_id);

}

// share item to all existed suborder
function shareItem(item_id) {

}

// return item to order
function returnItem(item_id) {

}

// add suborder
function addSuborder() {

}

// delete suborder
function deleteSuborder() {

}




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

	get items() {
		return this.items;
	}

	get order_no() {
		return this.order_no;
	}

}

class Suborders {
	constructor(suborders = []) {
		this.suborders = suborders;
	}

	get suborders() {
		return this.suborders;
	}

	getSuborder(suborder_no) {
		for (var i = 0; i < this.suborders.length; ++i) {
			if (suborder_no == this.suborders[i].suborder_no) {
				return this.suborders[i]
			}
		}
	}
}


class Suborder {
	constructor(suborder_no) {
		this.items = [];
		this.suborder_no = suborder_no;
	}
	

	get suborder_no() {
		return suborder_no;
	}

	get items() {
		return items;
	}

	get suborder() {
		return {
			'items': this.items,
			'suborder_no': this.suborder_no
		}
	}
}

class Item {
	constructor(item_id, image, name_en, name_zh, selected_extras_name, price, extras_amount, quantity, order_item_id, state) {
		this.item_id = item_id;
		this.image = image;
		this.name_en = name_en;
		this.name_zh = name_zh;
		this.selected_extras_name = selected_extras_name;
		this.price = price;
		this.extras_amount = extras_amount;
		this.quantity: quantity;
		this.order_item_id = order_item_id;
		this.state = state ;
	}

	get item () {
		return {
			"item_id": this.item_id,
			"image": this.image,
			"name_en": this.name_en,
			"name_zh": this.name_zh,
			"selected_extras_name": this.selected_extras_name,
			"price": this.price,
			"extras_amount": this.extras_amount,
			"quantity": this.quantity,
			"order_item_id": this.order_item_id,
			"state": this.state
		}
	}

	set state(state) {
		// state should be keep, assigned, share
		var stateList = Array("keep", "assigned", "share");
		if (stateList.indexOf(state) != -1) {
			this.state = state;
		} else {
			alert("State Errors: No existed state");
			return false;
		}
	}

	get item_id() {
		return this.item_id;
	} 
	get image() {
		return this.image;
	}

	get name_en() {
		return this.name_en;
	}

	get name_zh() {
		return this.name_zh;
	}

	get selected_extras_name() {
		return this.selected_extras_name;
	}

	get price() {
		return this.price;
	}

	get extras_amount() {
		return this.extras_amount;
	}

	get quantity() {
		return this.quantity;
	}

	get order_item_id() {
		return this.order_item_id;
	}

	get state() {
		return this.state;
	}
}