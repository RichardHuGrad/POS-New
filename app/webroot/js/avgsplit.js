// write by Yu Dec 19, 2016
// rely on jquery

// order_menu
// 0: id
// 1: img
// 2: en name
// 3: zh name
// 4:
// 5: price
// 6
// 7
// 8


//  
function avgSplit() {
	//  order_menu
	//  person_No
	person_No = $('#splitmenu .person-label').length;
	for (var i = 0; i < order_menu.length; ++i) {
		
		var current_menu = order_menu[i]
		for (var j = 1; j <= person_No; ++j) {
			current_person = j;
			var avg_price = (current_menu[5] / person_No).toFixed(2);
			var avg_name_en = current_menu[2] + ' /' + person_No;
			var avg_name_zh = current_menu[3] + ' /' + person_No;

			console.log(avg_price);
			console.log(avg_name_en);
			console.log(avg_name_zh);


			addMenuItem(i, current_menu[1], avg_name_en, avg_name_zh, current_menu[4], 
				avg_price ,current_menu[6], current_menu[7], current_menu[8]);
		}
		
	}
}

