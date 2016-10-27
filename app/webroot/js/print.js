//
// print queue ticket
//

        

// Settings
//var ipaddr1 = '192.168.0.188';
//var ipaddr2 = '192.168.0.188';
//var devid = 'local_printer';
var timeout = '60000';
var grayscale = false;
var layout = false;


function printTokitchen(order_no,order_type,table_no,ipaddr,devid,print_info,printer) {
var check_print_flag = false;
for (var i=0;i<print_info.length;i++){
	if ( print_info[i][(print_info[i].length-1)] == printer){
		check_print_flag = true;
	};
};

if (!check_print_flag){
	return ;
};

    //
    // build print data
    //

    // create print data builder object
    var builder = new epson.ePOSBuilder();

    // paper layout
    if (layout) {
        builder.addLayout(builder.LAYOUT_RECEIPT, 580);
    }

    // get current date
    var now = new Date();

    // initialize (ank mode, smoothing)
    builder.addTextLang('en').addTextSmooth(true);

	
	//Modified by Yishou Liao @ Oct 26 2016
	
	//Print order number and table number.
	Print_Str = "Order Number:" + order_no;
	builder.addTextAlign(builder.ALIGN_LEFT);
	builder.addTextSize(2, 1).addText(Print_Str);
    builder.addTextDouble(false, false).addText('\n');
	
	Print_Str = "Table:"+table_no;
	builder.addTextAlign(builder.ALIGN_LEFT);
	builder.addTextSize(2, 1).addText(Print_Str);
    builder.addTextDouble(false, false).addText('\n');
	
	Print_Str = "========================================";
	builder.addTextAlign(builder.ALIGN_LEFT);
	builder.addTextSize(1, 1).addText(Print_Str);
    builder.addTextDouble(false, false).addText('\n');
	
	Print_Str = "Qty       Name";
	builder.addTextSize(2, 1).addText(Print_Str);
	builder.addTextDouble(false, false).addText('\n');
	
	Print_Str = "========================================";
	builder.addTextAlign(builder.ALIGN_LEFT);
	builder.addTextSize(1, 1).addText(Print_Str);
    builder.addTextDouble(false, false).addText('\n');
	
	
	//Print order items
	for (var i=0;i<print_info.length;i++){
		if ( print_info[i][(print_info[i].length-1)] == printer){
			if (order_type == "T") {
				builder.addTextSize(2, 1).addText("外");
			};
			builder.addTextSize(2, 1).addText(FormatStr(print_info[i][7],6));
			builder.addTextSize(2, 1).addText(FormatStr(print_info[i][3],20));
			builder.addTextDouble(false, false).addText('\n');
			builder.addTextSize(2, 1).addText(FormatStr("",6));
			builder.addTextSize(2, 1).addText(FormatStr(print_info[i][4],20));
			builder.addTextDouble(false, false).addText('\n');
		};
	};
	//End.

    // append date and time
    builder.addText(now.toDateString() + ' ' + now.toTimeString().slice(0, 8) + '\n');
    builder.addFeedUnit(16);

    // append barcode
    builder.addBarcode(order_no.substr(0), builder.BARCODE_CODE39, builder.HRI_BELOW, builder.FONT_A, 2, 48);
    builder.addFeedLine(1);

    // append paper cutting
    builder.addCut();

    //
    // send print data
    //

    // create print object
    var url = 'http://' + ipaddr + '/cgi-bin/epos/service.cgi?devid=' + devid + '&timeout=' + timeout;
    var epos = new epson.ePOSPrint(url);

    // register callback function
    epos.onreceive = function (res) {
        // close print dialog
        $('#print').dialog('close');
        // print failure
        if (!res.success) {
            // show error message
            $('#receive').dialog('open');
        }
    }


    // send
    epos.send(builder.toString());

}


function printReceipt(order_no,table_no,ipaddr,devid,print_info) {

    //
    // build print data
    //

    // create print data builder object
    var builder = new epson.ePOSBuilder();

    // paper layout
    if (layout) {
        builder.addLayout(builder.LAYOUT_RECEIPT, 580);
    }

    // get current date
    var now = new Date();

    // initialize (ank mode, smoothing)
    builder.addTextLang('en').addTextSmooth(true);

	//Modified by Yishou Liao @ Oct 26 2016
	//Print restaurant information.
	var Print_Str = "3700 Midland Ave. #108";
	builder.addTextAlign(builder.ALIGN_CENTER);
	builder.addText(Print_Str);
    builder.addTextDouble(false, false).addText('\n');
	
	Print_Str = "Scarborough ON M1V 0B3";
	builder.addTextAlign(builder.ALIGN_CENTER);
	builder.addText(Print_Str);
    builder.addTextDouble(false, false).addText('\n');
	
	Print_Str = "647-352-5333";
	builder.addTextAlign(builder.ALIGN_CENTER);
	builder.addText(Print_Str);
    builder.addTextDouble(false, false).addText('\n\n');

	//Print order number and table number.
	Print_Str = "Order Number:" + order_no;
	builder.addTextAlign(builder.ALIGN_LEFT);
	builder.addTextSize(2, 1).addText(Print_Str);
    builder.addTextDouble(false, false).addText('\n');
	
	Print_Str = "Table:"+table_no;
	builder.addTextAlign(builder.ALIGN_LEFT);
	builder.addTextSize(2, 1).addText(Print_Str);
    builder.addTextDouble(false, false).addText('\n');
	
	Print_Str = "========================================";
	builder.addTextAlign(builder.ALIGN_LEFT);
	builder.addTextSize(1, 1).addText(Print_Str);
    builder.addTextDouble(false, false).addText('\n');
	
	Print_Str = "Qty       Name                Price";
	builder.addTextSize(1, 1).addText(Print_Str);
	builder.addTextDouble(false, false).addText('\n');
	
	Print_Str = "========================================";
	builder.addTextAlign(builder.ALIGN_LEFT);
	builder.addTextSize(1, 1).addText(Print_Str);
    builder.addTextDouble(false, false).addText('\n');
	
	
	//Print order items
	//builder.addTextSize(1, 1).addText(FormatStr(print_info[0][7],6));
	//builder.addTextSize(1, 1).addText(FormatStr(print_info[0][3],10));
	//builder.addTextSize(1, 1).addText(FormatStr(print_info[0][4],10));
	//builder.addTextSize(1, 1).addText("CAD$");
	//builder.addTextSize(1, 1).addText(FormatStr(print_info[0][6],6));
    //builder.addTextDouble(false, false).addText('\n');
	//End.


    // append date and time
    builder.addText(now.toDateString() + ' ' + now.toTimeString().slice(0, 8) + '\n');
    builder.addFeedUnit(16);

    // append barcode
    builder.addBarcode(order_no.substr(0), builder.BARCODE_CODE39, builder.HRI_BELOW, builder.FONT_A, 2, 48);
    builder.addFeedLine(1);

    // append paper cutting
    builder.addCut();

    //
    // send print data
    //

    // create print object
    var url = 'http://' + ipaddr + '/cgi-bin/epos/service.cgi?devid=' + devid + '&timeout=' + timeout;
    var epos = new epson.ePOSPrint(url);


    // register callback function
    epos.onreceive = function (res) {
        // close print dialog
        $('#print').dialog('close');
        // print failure
        if (!res.success) {
            // show error message
            $('#receive').dialog('open');
        }
    }
	
    // send
    epos.send(builder.toString());

}

function FormatStr(str,len){
	var mystr = str;
	mystr = mystr.replace(/(^s*)|(s*$) /g,"");
	
	if (mystr.length>len) {
		mystr = mystr.substr(0,len);
	};
	
	while(mystr.length<len){
		mystr += " ";
	};
	
	return mystr;
}
