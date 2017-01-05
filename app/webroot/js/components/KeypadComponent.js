class KeypadComponent {
	constructor(order, suborders, cfg, drawFunction, persistentFunction) {
		this.order = order;
		this.suborders = suborders;
		this.cfg = cfg;
		this.drawFunction = drawFunction;
		this.persistentFunction = persistentFunction;

		this.component = {};
	}


	createDom() {
		var component = this.component;

		var htmls = {
			'<div id="input-keypad">
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
			        <div class="form-group">
			            <label>
			                <input type="radio" id="pay-select" name="pay-or-tip" data-type="pay">Payment</label>
			            <label>
			                <input type="radio" id="tip-select" name="pay-or-tip" data-type="tip">Tip</label>
			        </div>
			        <div id="input-type-group" class="form-group">
			            <label>
			                <input type="radio" id="pay-card" name="pay" data-type="card"><img src="/pos-new/img/card.png" alt="card">Card 卡</label>
			            <label>
			                <input type="radio" id="pay-cash" name="pay" data-type="cash"><img src="/pos-new/img/cash.png" alt="cash">Cash 现金</label>
			        </div>
			        <button class="btn btn-success btn-lg" id="input-submit">Submit 提交</button>
			    </div>
			</div>'
		}

		var keypadComponent = $('<div id="input-keypad">');
	
		var keyScreenWrapper = $('<div id="input-key-screen-wrapper">');

		var screenComponent = $('<input type="text" id="input-screen" data-buffer="0" data-maxlength="13" value="00.00">');

		var buttonGroup = $('<div>');

		var payOrTipGroup = $('<div class="form-group">')
		var paySelect= $('<label><input type="radio" id="pay-select" name="pay-or-tip" data-type="pay">Payment</label>');
		var tipSelect = $('<label><input type="radio" id="tip-select" name="pay-or-tip" data-type="tip">Tip</label>');
		
		// maybe change the name, is used for select card or cash
		var typeGroup = $('<div id="input-type-group" class="form-group">');


		// var payGroup = $()
		var payCardButton = $('<label><input type="radio" id="pay-card" name="pay" data-type="card">' + cfg.cardImg + 'Card 卡</label>');							
		var payCashButton = $('<label><input type="radio" id="pay-cash" name="pay" data-type="cash">' + cfg.cashImg + 'Cash 现金</label>');
		// payForm.append(payCardButton).append(payCashButton);

		var tipCardButton = $('<label><input type="radio" id="tip-card" name="tip" data-type="card">' + cfg.cardImg + 'Card 卡</label>');
		var tipCashButton = $('<label><input type="radio" id="tip-cash" name="tip" data-type="cash">' + cfg.cashImg + 'Cash 现金</label>');

		// confirm: write the input into the suborder detail
		var confirmButton = $('<button class="btn btn-success btn-lg" id="input-confirm">').text('Confirm 确定');

		var submitButton = $('<button class="btn btn-success btn-lg" id="input-submit">').text('Submit 提交');

		buttonGroup.append(payOrTipGroup).append(typeGroup).append(submitButton);

		// build the keypad
		var keyComponent = $('<ul id="input-key-list">');
		for (var i = 1; i <= 9; ++i){
			keyComponent.append('<li data-num=' + i + '>' + i + '</li>' );
		}
		var screenClear = $('<li id="input-clear">').text("Clear 清除");
		var screenEnter = $('<li id="input-enter">').text("Enter 输入");
		keyComponent.append(screenClear).append('<li data-num=0 >0</li>').append(screenEnter);


	}

	bindEvent() {
		var 
	}
}