

if (!String.prototype.format) {
  String.prototype.format = function() {
    var args = arguments;
    return this.replace(/{(\d+)}/g, function(match, number) { 
      return typeof args[number] != 'undefined'
        ? args[number]
        : match
      ;
    });
  };
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
	constructor(order_no, items, suborderNum, discount) {
		this.items = items || [];
		this.order_no = order_no;
		this.suborderNum = suborderNum || 0;
		this.discount = discount || {"type": "unknown", "value": 0};
	}

	toJSON() {
		return {
			"items": this.items,
			"order_no": this.order_no,
			"suborderNum": this.suborderNum,
			"discount": this.discount
		}
	}

	static fromJSON(obj) {

		if (typeof obj == "string") obj = JSON.parse(obj);
		var instance = new Order(obj.order_no);
		instance.discount = obj.discount;
		instance.suborderNum = obj.suborderNum;
		for (var i = 0; i < obj.items.length; ++i) {
			var tempItem = Item.fromJSON(obj.items[i]);
			instance.items.push(tempItem);
		}

		return instance;
	}

	get billInfo() {
		var items = [];

		for (var i = 0; i < this.items.length; ++i) {
			items.push(this.items[i].originalPrintInfo);
		}

		return {
			"items": items,
			"subtotal": this.subtotal,
			"discount_type": this.discount.type,
			"discount_value": this.discount.value,
			"discount_amount": this.discountAmount,
			"after_discount": this.afterDiscount,
			"tax_rate": tax_rate,
			"tax_amount": this.tax.tax_amount,
			'default_tip_rate'  : this.default_tip.rate,
			'default_tip_amount': this.default_tip.amount,
			"total": this.total
		}
	}

	get receiptInfo() {

	}

	get subtotal() {

		var subtotal = 0;
		for (var i = 0; i < this.items.length; ++i) {
			var temp_item = this.items[i].originalPrintInfo;

			subtotal += parseFloat(temp_item.price) + parseFloat(temp_item.extras_amount);
		}

		return round2(subtotal);
	}

	/*get discount() {
		if (this.discount.type == "")
	}*/

	get discountAmount() {
		if (this.discount.type == "unknown") {
			return 0;
		} else if (this.discount.type == "fixed") {
			return this.discount.value;
		} else if (this.discount.type == "percent") {
			return round2(this.subtotal * this.discount.value / 100);
		}
	}

	get afterDiscount() {
		return this.subtotal - this.discountAmount;
	}

	get tax() {
		return {
			"tax_rate": tax_rate,
			"tax_amount": round2(tax_rate * this.afterDiscount/100),
		}
	}

	get default_tip() {
		return {
			"rate": default_tip_rate,
			"amount": round2((this.afterDiscount+this.tax.tax_amount)*default_tip_rate/100)
		}
	}

	get total() {
		//return round2 (parseFloat(this.subtotal) - parseFloat(this.discountAmount) + parseFloat(this.tax.tax_amount));
		return round2 (this.afterDiscount+this.tax.tax_amount+this.default_tip.amount);
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


// rely on order
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

	get billInfo() {
		var suborders = [];

		for (var i = 0; i < this.suborders.length; ++i) {
			suborders.push(this.suborders[i].billInfo);
		}

		return suborders;
	}

	get receiptInfo() {
		var suborders = [];

		for (var i = 0; i < this.suborders.length; ++i) {
			suborders.push(this.suborders[i].receiptInfo);
		}

		return suborders;
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
			// alert('no suborder to be removed');
			$.notify("no suborder to be removed \n没有可删除的子单",{ position: "top center", className:"warn"});
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

	isAllSuborderPaid() {
		for (var i = 0; i < this.suborders.length; ++i) {
			if (this.suborders[i].state == 'paid') {
				continue;
			} else {
				return false;
			}
		}

		return this.suborders.length > 0 
	}

	get unpaidSuborders() {
		var list = []
		for (var i = 0; i < this.suborders.length; ++i) {
			if (this.suborders[i].state == 'paid') {
				continue;
			} else {
				list.push(this.suborders[i].suborder_no);
			}
		}

		return list;
	}

	get length() {
		// this._length = this.suborders.length;
		return this.suborders.length;
	}

	get count() {
		var tax_amount = 0;
		var subtotal = 0;
		var total = 0;
		var membercard_val = 0;
		var card_val = 0;
		var cash_val = 0;
		var tip = 0;
		var tip_paid_by; 
		var tip_paid_by_set = new Set();
		var paid = 0;
		var paid_by;
		var change = 0;
		var fix_discount = 0;
		var percent_discount = 0;
		var discount_value = 0;

		for (var i = 0; i < this.suborders.length; ++i) {
			var tempSuborder = this.suborders[i];
			tax_amount += tempSuborder.tax.amount;
			subtotal += tempSuborder.subtotal;
			total += tempSuborder.total;
			membercard_val += tempSuborder.received.membercard;
			card_val += tempSuborder.received.card;
			cash_val += tempSuborder.received.cash;
			tip += tempSuborder.tip.card + tempSuborder.tip.membercard + tempSuborder.tip.cash;
			// to be changed 
			// if (tempSuborder.tip.type != "no tip")
			// 	tip_paid_by_set.add(tempSuborder.tip.type);
			// if (tip_paid_by_set.size == 1) {
			// 	tip_paid_by = tempSuborder.tip.type
			// } else {
			// 	tip_paid_by = "mixed";
			// }
			paid += tempSuborder.received.card + tempSuborder.received.membercard + tempSuborder.received.cash;
			change += tempSuborder.change;
		}

		// to be delete
		if (tip > 0) {
			tip_paid_by = 'CARD';
		} else {
			tip_paid_by = 'NO TIP';
		}

		if (order.discount.type == "fixed") {
			fix_discount = order.discount.value;
			discount_value = order.discount.value;
		} else if (order.discount.type == "percent") {
			percent_discount = order.discount.value;
			discount_value = round2(parseFloat(subtotal) * parseFloat(order.discount.value));
		}

		if ((card_val > 0 && cash_val > 0) || (membercard_val > 0 && cash_val > 0) || (membercard_val > 0 && card_val > 0)) {
			paid_by = "MIXED";
		} else if (membercard_val > 0 ) {
			paid_by = "MEMBERCARD";
		} else if (card_val > 0 ) {
			paid_by = "CARD";
		} else {
			paid_by = "CASH";
		}


		return {
			"tax": tax_rate,
			"tax_amount": tax_amount,
			"subtotal": subtotal,
			"total": total,
			"membercard_val": membercard_val,
			"card_val": card_val,
			"cash_val": cash_val,
			"tip": tip,
			"tip_paid_by": tip_paid_by,
			"paid": paid,
			"paid_by": paid_by,
			"change": change,
			"fix_discount": fix_discount,
			"percent_discount": percent_discount,
			"discount_value": discount_value,
		}

		
		/*order.discount.type
		order.discount.value*/
	}
}

// constructor and fromJSON should be done 
// when the suborder detail finish
class Suborder {
	constructor(suborder_no, discount) {
		this.items = [];
		this.suborder_no = suborder_no;
		// this._state = "unpaid";
		this._tax_rate = tax_rate;

		this._default_tip_rate = default_tip_rate;
		this._default_tip_amount = 0;

		this._received = {
			"cash": 0,
			"card": 0,
			"membercard": 0,
			"memberid": 0,
			"total": 0
		};
		this._tip = {
			// "type": "unknown", // card or cash
			"cash": 0,
			"card": 0,
			"membercard": 0,
			"amount": 0
		};
		// discount info should come from order
		this._discount = discount || { "type": "unknown",  /*fixed or percent*/"value": 0};

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
				this._received.membercard = obj.suborders[i].received.membercard;
				this._received.memberid = obj.suborders[i].received.memberid;
				this._tip.cash = obj.suborders[i].tip.cash;
				this._tip.card = obj.suborders[i].tip.card;
				this._tip.membercard = obj.suborders[i].tip.membercard;
			}
		}
	}


	get billInfo() { //do not include the received, tip, 
		var items = [];
		for (var i = 0; i < this.items.length; ++i) {
			items.push(this.items[i].printInfo);
		}
		return {
			'suborder_no': this.suborder_no,
			'subtotal': this.subtotal,
			'discount_type': this.discount.type,
			'discount_value': this.discount.value,
			'tax_rate': tax_rate,
			'tax_amount': this.tax.amount,
			'default_tip_rate'  : this.default_tip.rate,
			'default_tip_amount': this.default_tip.amount,
			'total': this.total,
			'items': items
		}
	}

	get receiptInfo() {

		var items = [];
		for (var i = 0; i < this.items.length; ++i) {
			items.push(this.items[i].printInfo);
		}
		return {
			'suborder_no': this.suborder_no,
			'subtotal': this.subtotal,
			'discount_type': this.discount.type,
			'discount_value': this.discount.value,
			'discount_amount': this.discountAmount,
			'after_discount': this.afterDiscount,
			'tax_rate': tax_rate,
			'tax_amount': this.tax.amount,
			'default_tip_rate'  : this.default_tip.rate,
			'default_tip_amount': this.default_tip.amount,
			'total': this.total,
			'received_memberid': this.received.memberid,
			'received_membercard': this.received.membercard,
			'received_card': this.received.card,
			'received_cash': this.received.cash,
			'received_total': this.received.total,
			'tip_membercard': this.tip.membercard,
			'tip_card': this.tip.card,
			'tip_cash': this.tip.cash,
			'tip_amount': this.tip.amount,
			'change': this.change,
			'items': items
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
	// should include extra amount
	get subtotal() {
		var subtotal = 0;
		for (var i = 0; i < this.items.length; ++i) {
			var temp_item = this.items[i];

			subtotal += parseFloat(temp_item["price"]);
		}

		return round2(subtotal);
	}


	//  to do
	get discount() {
		if (order.discount.type == "fixed") {
			return {
				"type": "fixed",
				"value": round2(order.discount.value / order.suborderNum)
			}
		} else {
			return order.discount;
		}
	}

	get discountAmount() {
		var discountAmount;
		if (this.discount.type == "unknown") {
			discountAmount = 0;
		} else if (this.discount.type == "fixed") {
			discountAmount = parseFloat(this.discount.value);
		} else if (this.discount.type == "percent") {
			discountAmount = round2(parseFloat(this.subtotal) * parseFloat(this.discount.value) / 100);
		}

		return discountAmount;
	}

	get afterDiscount() {

		var discountAmount;
		if (this.discount.type == "unknown") {
			discountAmount = 0;
		} else if (this.discount.type == "fixed") {
			discountAmount = parseFloat(this.discount.value);
		} else if (this.discount.type == "percent") {
			discountAmount = round2(this.subtotal * parseFloat(this.discount.value) / 100);
		}

		return round2(this.subtotal - discountAmount) > 0 ? round2(this.subtotal - discountAmount) : 0;
	}

	// return float with 2 precision
	get tax() {
		return {
			"tax": tax_rate,
			"amount": round2(this.afterDiscount * tax_rate/100)
		}
	}

	get default_tip() {
		return {
			"rate": this._default_tip_rate,
			"amount": round2((this.afterDiscount + this.tax.amount)*this._default_tip_rate/100)
		}
	}


	// todo
	// notice the discount, which should be seperate by multiple people
	get total() {
		return round2(this.afterDiscount + this.tax.amount + this.default_tip.amount);
	}

	get received() {
		return  {
					"memberid": round2(this._received.memberid),
					"membercard": round2(this._received.membercard),
					"card": round2(this._received.card),
					"cash": round2(this._received.cash),
					"total": round2 (this._received.card + this._received.cash + this._received.membercard)
				}
	}

	get tip() {
		// var tip_card;
		// if (round2(this._received.card) > round2(this.total)) {
		// 	tip_card = round2(this._tip.card) + round2(this._received.card) - round2(this.total);
		// } else {
		// 	tip_card = round2(this._tip.card);
		// }
		var type;
		if ((this._tip.card > 0 && this._tip.cash) || (this._tip.membercard > 0 && this._tip.cash)) {
			type = "MIXED";
		} else if (this._tip.card > 0) {
			type = "CARD";
		} else if (this._tip.membercard > 0) {
			type = "MEMBERCARD";
		} else if (this._tip.cash) {
			type = "CASH";
		} else {
			type = "no tip";
		}
		return {
					"membercard": round2(this._tip.membercard),
					"card": round2(this._tip.card),
					"cash": round2(this._tip.cash),
					"amount": round2(this._tip.card + this._tip.membercard + this._tip.cash),
					"type": type 
				};
	}

	get remain() {
		if (this.received.card >= this.total) {
			return 0;
		} else {
			return this.total > this.received.total ? round2(this.total - this.received.total) : 0;
		}
	}

	get change() {

		if ((this.received.card + this.received.mastercard) >= this.total) {
			return round2(this.received.cash);
		} else {
			return this.received.total > this.total ? round2(this.received.total - this.total) : 0;
		}
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
	constructor(item_id, image, name_en, name_zh, selected_extras_name, price, extras_amount, quantity, order_item_id, state, shared_suborders, assigned_suborder, is_takeout, comb_id=0, selected_extras_json="", is_print='Y', special="", cousine_id=0) {
		this.item_id = item_id;
		this.image = image;
		this._name_en = name_en;
		this._name_zh = name_zh;
		this.selected_extras_name = selected_extras_name;
		this._price = parseFloat(price);
		this._extras_amount = parseFloat(extras_amount) || 0;
		this.quantity= quantity;
		this.order_item_id = order_item_id;
		this._state = state ;
		this.shared_suborders = shared_suborders || [];
		this.assigned_suborder = assigned_suborder || 0;
		this.is_takeout = is_takeout || 'N';
		this.comb_id = comb_id;
		this.selected_extras_json = selected_extras_json;
		this.is_print = is_print;
		this.special = special;
		this.cousine_id = cousine_id;
	}

	toJSON() {
		return {
			"item_id": this.item_id,
			"image": this.image,
			"name_en": this._name_en,
			"name_zh": this._name_zh,
			"selected_extras_name": this.selected_extras_name,
			"price": this._price,
			"extras_amount": this._extras_amount,
			"quantity": this.quantity,
			"order_item_id": this.order_item_id,
			"state": this._state,
			"shared_suborders": this.shared_suborders,
			"assigned_suborder": this.assigned_suborder,
			"cousine_id": this.cousine_id
		}
	}

	static fromJSON(obj) {
		if (typeof obj == "string") obj = JSON.parse(obj);
		var instance = new Item(obj.item_id, obj.image, obj.name_en, obj.name_zh, obj.selected_extras_name, obj.price, obj.extras_amount, obj.quantity, obj.order_item_id, obj.state, obj.shared_suborders, obj.assigned_suborder);
		return instance;
	}

	get printInfo() {
		return {
			"selected_extras_name": this.selected_extras_name,
			"extras_amount": this._extras_amount,
			"name_zh": this.print_name_zh,
			"name_en": this.print_name_en,
			"price": this.price,
			"quantity": this.quantity,
		}
	}

	get originalPrintInfo() {
		return {
			"name_zh": this._name_zh,
			"name_en": this._name_en,
			"price": this._price,
			"selected_extras_name": this.selected_extras_name,
			"extras_amount": this._extras_amount,
			"quantity": this.quantity,
		}
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
			return round2((this._price + this._extras_amount) / this.shared_suborders.length)
		} else {
			return this._price + this._extras_amount;
		}
	}

	get print_name_en () {
		if (this.state == "share" && this.shared_suborders.length > 1) {
			var tempStr = this._name_en + ' s' + this.shared_suborders.length;
			return tempStr;
		} else {
			return this._name_en;
		}
	}

	get print_name_zh () {
		if (this.state == "share" && this.shared_suborders.length > 1) {
			var tempStr = this._name_zh + ' s' + this.shared_suborders.length;

			return tempStr;
		} else {
			return this._name_zh;
		}
	}

	get name_en() {
		var tempStr = this._name_en;


		if (this.state == "share" && this.shared_suborders.length > 1) {
			tempStr += ' shared by';
			for (var i = 0; i < this.shared_suborders.length; ++i) {
				tempStr += " " + String(this.shared_suborders[i]);
			}
		} 

		if(this.is_takeout == 'Y') {
			tempStr = "(Take Out)" + tempStr;
		}

		return tempStr;
	}

	get name_zh() {
		var tempStr = this._name_zh

		if (this.state == "share" && this.shared_suborders.length > 1) {
			tempStr += ' shared by';
			for (var i = 0; i < this.shared_suborders.length; ++i) {
				tempStr += " " + String(this.shared_suborders[i])
			}
		}

		if(this.is_takeout == 'Y') {
			tempStr = "(外卖)" + tempStr;
		}

		return tempStr;
	}
}

//  only draw item which state is "keep"
var OrderComponent = function(order, cfg) {
	var cfg = cfg || {};

	var orderComponent = $('<div id="order-component">');
	var orderUl = $('<ul>');
	var avgSplitButton = $('<button id="avg-split" class="btn btn-primary btn-lg">').text("Avg. Split 平均分单");
	
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

	var template = `
		<li class="order-item" id="{0}">
			<div class="col-md-9 col-sm-9 col-xs-8">
				<div class="col-md-12 col-sm-12 col-xs-12">{1}</div>
				<div class="col-md-12 col-sm-12 col-xs-12 os-extra">{2}</div>
			</div>
			<div class="col-md-3 col-sm-3 col-xs-4 os-price">{3}</div>
		</li>
	`;

	var cfg = cfg || {};
	var item_id = item["item_id"];

	var orderItemComponent = $(template.format('order-item-' + item_id, item["name_en"] + '\n' + item["name_zh"], item["selected_extras_name"], '$' + item["price"]))

	// var orderItemComponent = $('<li class="order-item" id="order-item-' + item_id + '">');
	// var nameDiv = $('<div class="col-md-9 col-sm-9 col-xs-8">').text(item["name_en"] + '\n' + item["name_zh"]);
	// var extraDiv = $('<div class="col-md-9 col-sm-9 col-xs-8 os-extra">').text(item["selected_extras_name"]);
	// var priceDiv = $('<div class="col-md-3 col-sm-3 col-xs-4 os-price">').text('$' + item["price"]);
	
	// TODO merge nameDiv and extraDiv
	// var nameAndExtraDiv = $('<div class="col-md-9 col-sm-9 col-xs-8">');

	if (item["selected_extras_name"] == "") {
		orderItemComponent.find('.os-extra').hide();
	} 

	// orderItemComponent.append(nameDiv).append(extraDiv).append(priceDiv);

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
	var extraDiv = $('<div class="col-md-9 col-sm-9 col-xs-8 os-extra">').text(item["selected_extras_name"]); 
	var priceDiv = $('<div class="col-md-3 col-sm-3 col-xs-4 os-price">').text('$' + item["price"]);

	if (item["selected_extras_name"] != "") {
		suborderItemComponent.append(nameDiv).append(priceDiv).append(extraDiv);
	} else {
		suborderItemComponent.append(nameDiv).append(priceDiv);
	}


	// suborderItemComponent.append(nameDiv).append(extraDiv).append(priceDiv);

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

	var template = `
		<ul class="suborder-detail" id="suborder-detail-{0}">
		   <li class="suborder-title">Suborder # {1}</li>
		   <li class="suborder-subtotal">Subtotal 小计: $ {2}</li>
		   <li class="suborder-discount">{3}</li>
		   <li class="suborder-after-discount">After Discount 打折后: $ {4}</li>
		   <li class="suborder-tax">Tax 税 ({5}%): $ {6}</li>
		   <li class="suborder-default-tip">Tip 缺省小费({16}%): $ {17}</li>
		   <li class="suborder-total">Total 总: $ <span class="span-total">{7}</span></li>
		   <li class="suborder-received">Received 收到: $ {8} Cash 现金: $ {9} Card 卡: $ {10} Member Card 卡: $ {18}</li>
		   <li class="suborder-remain">Remaining 其余: $ {11}</li>
		   <li class="suborder-change">Change 找零: $ {12}</li>
		   <li class="suborder-tip">Tip 小费: $ {13} Cash 现金: $ {14} Card 卡: $ {15} Member Card 卡: $ {19}</li>
		</ul>
	`;



	var discountText = function(type, value) { 
		var discountAmount;
		if (type == "unknown") {
			discountAmount = 0;
			return "Discount 折扣: {0} Type 类型: {1}".format(discountAmount, "N/A 不打折");
		} else if (type == "fixed") {
			discountAmount = parseFloat(value);
			return "Discount 折扣: {0} Type 类型: {1}".format(discountAmount, "Fixed 固定折扣");
		} else if (type == "percent") {
			discountAmount = round2(suborder.subtotal * parseFloat(value) / 100);
			return "Discount 折扣: {0} ({1}%) Type 类型: {2}".format(discountAmount, value, "Percentage 百分比");
		}
		
	}


	var suborderDetailComponent = $(template.format(
		suborderId, 
		order_no + '-' + suborder.suborder_no, 
		suborder.subtotal, 
		discountText(suborder.discount.type, suborder.discount.value),
		suborder.afterDiscount,
		suborder.tax.tax,
		suborder.tax.amount,
		suborder.total,
		suborder.received.total,
		suborder.received.cash,
		suborder.received.card,
		suborder.remain,
		suborder.change,
		suborder.tip.amount,
		suborder.tip.cash,
		suborder.tip.card,
		suborder.default_tip.rate,
		suborder.default_tip.amount,
		suborder.received.membercard,
		suborder.tip.membercard,
		suborder.received.memberid
	));

	if (suborder.remain == 0) {
		suborderDetailComponent.find(".suborder-remain").hide();
	} else {
		suborderDetailComponent.find(".suborder-change").hide();
	}


	// set css accounding to the state
	suborderDetailComponent.css("background-image", "url(" + imgPath + suborder.state + ")");
	return suborderDetailComponent;
}


var SubordersListComponent = function (suborders, cfg) {
	var cfg = cfg || {};
	var subordersListComponent = $('<div id="suborders-list-component">');
	var addPersonButton = $('<button id="add-person" class="btn btn-lg btn-primary">').text('增加人');
	var deletePersonButton = $('<button id="delete-person" class="btn btn-lg btn-danger">').text('删除人');
							
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

	subordersListComponent.append(deletePersonButton).append(addPersonButton).append(itemsComponent);

	return subordersListComponent;
}

var SubordersDetailComponent = function (suborders, cfg) {
	var cfg = cfg || {};


	var subordersDetailComponent = $('<div id="suborders-detail-component">');
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



// get val of $('#screen')
// get type by whether button is active
// use inverse of control to decouple the order and suborder
var KeypadComponent = function (cfg, drawFunction, persistentFunction) {
	var cfg = cfg || {};
	/*var order = order;
	var suborders = suborders;*/
	var drawFunction = drawFunction;
	var persistentFunction = persistentFunction;

	var keypadComponent = $('<div id="input-keypad">');
	
	var keyScreenWrapper = $('<div id="input-key-screen-wrapper">');

	var screenComponent = $('<input type="text" id="input-screen" data-buffer="" data-lastinput="" data-maxlength="13" value="00.00" readonly>');
	// restrict the input type of screen by keyboard
	screenComponent.keydown(function(e) {
		// Allow: backspace, delete, tab, escape, enter and .
	    if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== - 1 ||
	            // Allow: Ctrl+A, Command+A
                    (e.keyCode == 65 && (e.ctrlKey === true || e.metaKey === true)) ||
                    // Allow: home, end, left, right, down, up
                            (e.keyCode >= 35 && e.keyCode <= 40)) {
	        
	        // enter button should trigger enter button
	    	if (e.keyCode == 13) {
	    		$('#input-enter').trigger('click');
	    	}
	        return;
        }
            // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });

	var buttonGroup = $('<div>');

	var payOrTipGroup = $('<div class="form-group p-method">')
	//var paySelect= $('<label class="p-pay"><input type="radio" id="pay-select" name="pay-or-tip" data-type="pay">Payment</label>');
	var paySelect= $('<input type="radio" id="pay-select" name="pay-or-tip" data-type="pay"><label for="pay-select" class="p-pay">Payment<br/>付款</label>');
	var tipSelect = $('<input type="radio" id="tip-select" name="pay-or-tip" data-type="tip"><label for="tip-select" class="p-tip">Tip<br/>小费</label>');
	
	// maybe change the name, is used for select card or cash
	var typeGroup = $('<div id="input-type-group" class="form-group">');


	// var payGroup = $()
	var payCardButton = $('<input type="radio" id="pay-card" name="pay" data-type="card"><label for="pay-card" class="pp-card">' + cfg.cardImg + 'Card 卡</label>');							
	var payMemberCardButton = $('<input type="radio" id="pay-membercard" name="pay" data-type="membercard"><label for="pay-membercard" class="pp-card">' + cfg.cardImg + 'Member Card 卡</label>').on('click', function() {
        $("#member_search_next").val('mbm_pay_select');
        $('#modal_member_search').modal('show');

        var currentSuborderId = $('.suborders-detail-tab.active').attr('data-index');
        var currentSuborder = suborders.getSuborder(currentSuborderId);
        $('#mbm_pay_order_paid').val(currentSuborder.received.total);
        $('#mbm_pay_order_total').val(currentSuborder.total);
	});							
	var payCashButton = $('<input type="radio" id="pay-cash" name="pay" data-type="cash"><label for="pay-cash" class="pp-cash">' + cfg.cashImg + 'Cash 现金</label>');
	var payMemberHidden = $('<input type="hidden" id="membercard_id" name="membercard_id" /><input type="hidden" id="membercard_val" name="membercard_val" />');							
	// payForm.append(payCardButton).append(payCashButton);

	var tipCardButton = $('<input type="radio" id="tip-card" name="tip" data-type="card"><label for="tip-card" class="pp-card">' + cfg.cardImg + 'Card 卡</label>');
	var tipMemberCardButton = $('<input type="radio" id="tip-membercard" name="tip" data-type="membercard"><label for="tip-membercard" class="pp-card">' + cfg.cardImg + 'Member Card 卡</label>');
	var tipCashButton = $('<input type="radio" id="tip-cash" name="tip" data-type="cash"><label for="tip-cash" class="pp-cash">' + cfg.cashImg + 'Cash 现金</label>');

	// confirm: write the input into the suborder detail
	var confirmButton = $('<button class="btn btn-success btn-lg" id="input-confirm">').text('Confirm 确定');

	var submitButton = $('<button class="btn btn-success btn-lg" id="input-submit">').text('Submit 提交');

	if (order.availableItems.length == 0) {
		submitButton.on('click', function(){
			// submit to the backend
			if (suborders.isAllSuborderPaid()) {

				// iterator all suborder
				for (var i = 0; i < suborders.suborders.length; ++i) {
					var tempSuborder = suborders.suborders[i];

					$.ajax({
						url: store_suborder_url,
						method: "post",
						async: false,
						data: {
							"table_no": table_id,
			                "order_no": order_no,
			                "suborder_no": tempSuborder.suborder_no,
		                	"subtotal": tempSuborder.subtotal,
		                	// "discount_type": tempSuborder.discount.type.toUpperCase(),
			                "discount_value": tempSuborder.discount.value,
		                	"tax": tempSuborder.tax.tax,
		                	"tax_amount": tempSuborder.tax.amount,
		                	"default_tip_rate": tempSuborder.default_tip.rate,
		                	"default_tip_amount": tempSuborder.default_tip.amount,
			                "total": tempSuborder.total,
			                "membercard_id": tempSuborder.received.memberid,
			                "paid_membercard": tempSuborder.received.membercard,
			                "paid_card": tempSuborder.received.card,
			                "paid_cash": tempSuborder.received.cash,
			                "tip_card": tempSuborder.tip.card,
			                "tip_cash": tempSuborder.tip.cash,
			                "change": tempSuborder.change,
			                "items": JSON.stringify(tempSuborder.items),
						}
					}).done(function(data) {
						//console.log("succuess");
						//console.log(data);
					}).fail(function(jqXHR, textStatus) {
						alert("Fail :" + textStatus );
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
		    			"membercard_id": 0,
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
				}).fail(function(jqXHR, textStatus) {
						alert("Fail: " + textStatus );
				}).done(function(data) {
						// delete cookie
						printSplitReceipt(order, suborders);
						deleteAllCookies();
						window.location.replace(home_page_url);
					}
				);

				

			} else {
				if (suborders.suborders.length == 0) {
					$.notify("there is no suborder to submit \n请分单后再提交",{ position: "top center", className:"warn"});
				} else {
					var tempStr = suborders.unpaidSuborders.join();
					$.notify("please check the following suborders \n请检查以下分单" + tempStr,{ position: "top center", className:"warn"});
				}
			}
		});
	} else {
		submitButton.prop('disabled', true);
	}
	


	// payOrTipGroup.append(paySelect).append(tipSelect).find("input").on("change", function () {
	payOrTipGroup.append(paySelect).find("input").on("change", function () {
		if ($(this).is(':checked') && $(this).attr('id') == "pay-select") {
			// enable payment buttons
			typeGroup.empty();
			typeGroup.append(payMemberCardButton).append(payCardButton).append(payCashButton).append(payMemberHidden);

			console.log("payment is selected");
		} else if ($(this).is(':checked') && $(this).attr('id') == "tip-select") {
			// enable tip buttons
			typeGroup.empty();
			// typeGroup.append(tipCardButton).append(tipCashButton);
			// typeGroup.append(tipCashButton);
			// tipCashButton.trigger('click');
			
			console.log("tip is selected");
		} else {
			console.log('error');
		}
	});

	// submit button
	// only be abled when all suborder's states are paidls


	buttonGroup.append(payOrTipGroup).append(typeGroup).append(submitButton);
	// buttonGroup.append(payCardButton).append(payCashButton).append(tipCardButton).append(tipCashButton).append(confirmButton).append(submitButton);

	// construct keypad
	var keyComponent = $('<ul id="input-key-list">');												
	
	for (var i = 1; i <= 9; ++i){
		keyComponent.append('<li data-num=' + i + '>' + i + '</li>' );
	}

	keyComponent.append('<li data-num=' + 0 + '>' + 0 + '</li>' );
	keyComponent.append('<li data-num=".">.</li>' );

	var screenBack = $('<li id="input-back">').text("Back")
			.on('click', function() {
			
			var current_val = $('#input-screen').val();
			var new_val = current_val.substring(0,current_val.length-1);		
      
			$('#input-screen').val(new_val);
      $('#input-screen').attr("data-buffer", new_val);		
			if($('#input-screen').attr("data-buffer")=='00.00'){
			   $('#input-screen').attr("data-buffer",'');
			}
               
	});

  keyComponent.append(screenBack);
  keyComponent.append('<li data-num="Default">Default</li>' );
  
	var screenClear = $('<li id="input-clear">').text("Clear 清除")
												.on('click', function() {
													// var value = $('#input-screen').val().slice(0, -1);
													$('#input-screen').attr("data-buffer", "")
													$('#input-screen').val("00.00");
												});

	keyComponent.append(screenClear);
	

    // should be changed
    // should not change the suborder state directly
	var screenEnter = $('<li id="input-enter">').text("Enter 输入")
												.on('click', function() {
													enterInput();
													screenClear.trigger('click');
												});

	keyComponent.append(screenEnter);
	

	//  to be fixed
	//  add restriction of num length
	keyComponent.find('li').each(function() {
		var attr = $(this).attr('data-num')
		if (typeof attr !== typeof undefined && attr !== false) {
			
			$(this).on('click', function () {
				
				// var value = $('#input-screen').val() ? parseFloat($('#input-screen').val() : 0;

				var new_value;
				
				if($(this).attr('data-num')== "Default"){  //default to suborder total
           var idx = $("#suborders-detail-tab-component li.active").attr("data-index");
           new_value= $("#suborder-detail-"+ idx + " .span-total").html();
           
           $('#input-screen').attr("data-buffer", new_value);	
           $('#input-screen').val(new_value);
           return true;
								
				}else{
					new_value = $('#input-screen').attr("data-buffer") + $(this).attr('data-num');
				}
								
				$('#input-screen').attr("data-buffer", new_value);				

        $('#input-screen').attr("data-lastinput", $('#input-screen').val() );
				
				//var value = buffer / 100;
				//value = value.toFixed(2);
				$('#input-screen').val(new_value);
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