// TODO: restrict the input of discount value
var DiscountComponent = function (cfg) {
	var cfg = cfg || {};

	var fixDiscountVal;
	var percentDiscountVal;
	var promoCodeVal;

	var discountComponent = $('<div id="discount-component">');

	var discountOptionButton = $('<button class="btn btn-lg btn-success pull-right" id="discount-option"><span>');
	discountOptionButton.find('span').text('Discount 折扣');
	discountOptionButton.on('click', function() {
		hideComponent.toggle();
    });


	var hideComponent = $('<div id="discount-wrapper" class="pull-left">').css("display", "none");

	var fixDiscountLabel = $('<label for="fix-discount">').text("Fix Discount");
	var fixDiscountInput = $('<input id="fix-discount">');
	fixDiscountInput.on('keyup', function() {
		fixDiscountVal = $(this).val();
		if (fixDiscountVal != '') {
			percentDiscountInput.prop('disabled', true);
			promoCodeInput.prop('disabled', true);
		} else {
			percentDiscountInput.prop('disabled', false);
			promoCodeInput.prop('disabled', false);
		}
	});
	var fixDiscountComponent = $('<div>');
	fixDiscountComponent.append(fixDiscountLabel).append(fixDiscountInput);

	var percentDiscountLabel = $('<label for="percent-discount">').text("Discount in %");
	var percentDiscountInput = $('<input id="percent-discount">');
	percentDiscountInput.on('keyup', function() {
		percentDiscountVal = $(this).val();
		if (percentDiscountVal != '') {
			fixDiscountInput.prop('disabled', true);
			promoCodeInput.prop('disabled', true);
		} else {
			fixDiscountInput.prop('disabled', false);
			promoCodeInput.prop('disabled', false);
		}
	});
	var percentDiscountComponent  = $('<div>');
	percentDiscountComponent.append(percentDiscountLabel).append(percentDiscountInput);

	var promoCodeLabel = $('<label for="promo-code-discount">').text("Promo Code");
	var promoCodeInput = $('<input id="promo-code-discount">');

	promoCodeInput.on('keyup', function() {
		promoCodeVal = $(this).val();
		if (promoCodeVal != '') {
			fixDiscountInput.prop('disabled', true);
			percentDiscountInput.prop('disabled', true);
		} else {
			fixDiscountInput.prop('disabled', false);
			percentDiscountInput.prop('disabled', false);
		}
	});
	var promoCodeComponent = $('<div>');
	promoCodeComponent.append(promoCodeLabel).append(promoCodeInput);

	var isNum = function (val) { return /^[0-9]+(\.)?[0-9]$/.test(val); }
	var addDiscountButton = $('<button class="btn btn-primary" id="add-discount"><span class="glyphicon glyphicon-plus">');
	addDiscountButton.find('span').text("Apply");
	addDiscountButton.on('click', function() {
		// should only affect discount, should not change suborder
		if (fixDiscountVal || percentDiscountVal || promoCodeVal) {
			/*if (fixDiscountVal) {
				// restrict fixDiscountVal
				if (isNum(fixDiscountVal)) {
					discount = 
						{
							"type": "fixed",
							"value": fixDiscountVal
						}
				} else {
					alert('Please input valid number')					
				}

			 	
			} else if (percentDiscountVal) {
				if (isNum(percentDiscountVal) && parseFloat(percentDiscountVal) < 100) {
					discount = 
						{
							"type": "percent",
							"value": percentDiscountVal
						}
				} else {
					alert('Please input valid number && percentage should be less than 100')					
				}
			} else if (promoCodeVal) {

			}*/
			fixDiscountVal = fixDiscountVal || '';
			percentDiscountVal = percentDiscountVal || '';
			promoCodeVal = promoCodeVal || '';
			$.ajax({
				url: add_discount_url,
				method: "post",
				dataType: "json",
				data: {fix_discount: fixDiscountVal, discount_percent: percentDiscountVal, promocode: promoCodeVal, order_id: order_no},
				success: function (html) {
					if (html.error) {
						console.log(html.error);
						$.notify(html.message, {
	                        position: "top center", 
	                        className:"warn"
	                    });
						return false;
					} else {
						// save discount info
						// Cookie.set()
						console.log(html.discount_type);
						console.log(html.discount_value);

					}
				},
				beforeSend: function () {
					// todo: add load transfer 
				}
			});
			
		} else {
			alert("please input valid discount information");
		}

		// modify suborders discount

	})

	var removeDiscountButton = $('<button class="btn btn-danger" id="remove-discount"><span class="glyphicon glyphicon-minus">');
    removeDiscountButton.find('span').text('Remove');
    removeDiscountButton.on('click', function() {
    	// remove discount
    });

	



	hideComponent.append(fixDiscountComponent).append(percentDiscountComponent).append(promoCodeComponent).append(addDiscountButton).append(removeDiscountButton);

	discountComponent.append(discountOptionButton).append(hideComponent);

	return discountComponent;
}