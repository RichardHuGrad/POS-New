<html>
<head>
    <?php echo $this->Html->css(array('order', 'components/TastesComponent')); ?>
</head>
    

<body>

    <header class="product-header">

        <div class="home-logo">
            <a href="<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'dashboard')) ?>">
                <?php echo $this->Html->image("logo-home.jpg", array('alt' => "POS")); ?>
            </a>

            <div class="HomeText text-left">
                <a href="<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'index')) ?>">Home 主页</a>
                <a href="javascript:void(0)" onclick="window.history.back()">Back 返回</a>
            </div>

        </div>



        <div class="logout"><a href="<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'logout')) ?>">Logout 登出</a></div>

        <ul class="nav nav-tabs text-center">
            <?php
            if (!empty($records)) {
                foreach ($records as $key => $category) {
                    ?>
                    <li <?php if ($key == 0) echo "class='active'" ?>><a data-toggle="tab" href="#tab<?php echo $category['Category']['id']; ?>"><?php echo $category['Category']['eng_name'] . "<br/>" . $category['Category']['zh_name']; ?></a></li>
                    <?php
                }
            }
            ?>
        </ul>

        
    </header>
    <div class="clearfix cartwrap-wrap col-md-12 col-sm-12 col-xs-12">      
        <div class="col-md-9 col-sm-8 col-xs-12 home-link">
            <div class="cart-txt" id="order_no_display">
            <!-- Modified by Yishou Liao @ Dec 09 2016 -->
                Order 订单号 #<?php echo @$Order_detail['Order']['order_no']; ?>, Table 桌 #<?php echo $table; ?>
            <!-- End -->
            </div>
        </div>

        <div class="col-md-3 col-sm-4 col-xs-12">
            <div class="searchwrap">
                <label for="search-input"><i class="fa fa-search" aria-hidden="true"></i></label>
                <a class="fa fa-times-circle-o search-clear" aria-hidden="true"></a>
                <input id="search-input" class="form-control input-lg" placeholder="Search 搜索">
            </div>
        </div>

    </div>


    <div class="clearfix cart-wrap col-md-12 col-sm-12 col-xs-12">
        <div class="col-md-4 col-sm-5 col-xs-12 summary_box">
            <div class="clearfix marginB15 cashierbox" style="display:none">
                <div class="pull-left marginR5">
                    <?php if ($cashier_detail['Cashier']['image']) { ?>
                        <?php echo $this->Html->image(TIMB_PATH . "timthumb.php?src=" . CASHIER_IMAGE_PATH . $cashier_detail['Cashier']['image'] . "&h=60&w=60&&zc=4&Q=100", array('class' => 'img-circle img-responsive')); ?>
                    <?php } else { ?>
                        <?php echo $this->Html->image(TIMB_PATH . "timthumb.php?src=" . TIMB_PATH . 'no_image.jpg' . "&h=60&w=60&&zc=4&Q=100", array('class' => 'img-circle img-responsive')); ?>
                    <?php } ?>
                </div>
                <div class="pull-left marginL5 clearfix">
                    <div class="txt16 marginB5 marginT5"><?php echo ucfirst($cashier_detail['Cashier']['firstname']) . " " . $cashier_detail['Cashier']['lastname']; ?></div>
                    <div class="txt15"><?php echo str_pad($cashier_detail['Cashier']['id'], 4, 0, STR_PAD_LEFT); ?></div>
                </div>
            </div>
        </div>


        <div class="col-md-8 col-sm-7 col-xs-12 products-panel">
            <div class="tab-content">
                <!-- <?php print_r ($records); ?> -->
                <?php
                if (!empty($records)) {
                    $count = 0;
                    foreach ($records as $key => $category) {
                        $count++;
                        ?>
                        <div id="tab<?php echo $category['Category']['id']; ?>" class="tab-pane fade in <?php if ($key == 0) echo "active" ?>">
                            <div class="clearfix">
                                <div class="clearfix row productbox">
                                    <?php if (!empty($category['Cousine'])) { ?>
                                        <ul>
                                            <?php
                                            foreach ($category['Cousine'] as $items) {
                                                ?>
                                                <li class="col-md-3 col-sm-6 col-xs-6 add_items" alt="<?php echo $items['id']; ?>" title="Add to Cart">
                                                    <div class="item-wrapper">
                                                        <div class="clearfixrow">
                                                            <div class="dish-price">$<?php echo number_format($items['price'], 2); ?></div>
                                                            <div class="dish-title"><div class="name-title"><strong><?php echo $items['zh_name'] . "<br/>" . $items['eng_name']; ?></strong></div></div>
                                                        </div>
                                                    </div>
                                                </li>
                                                <?php
                                            }
                                            ?>
                                        </ul>
                                    <?php
                                    } else {
                                        echo "<div class='noitems'>No Items Available</div>";
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>                
                        <?php
                    }
                }
                ?>
            </div>
        </div>

        
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12" id="button-group">
        <button id="send-to-kitchen-btn" class="btn btn-lg">Send to Kitchen</button>
        <button id="pay-btn" class="btn btn-lg">Pay</button>
        <button id="batch-add-taste-btn" class="btn btn-info btn-lg" data-toggle="modal" data-target="#taste-component-modal">Batch Add Taste</button>
        <button id="add-taste-btn" class="btn btn-lg btn-danger" data-toggle="modal" data-target="#single-extra-component-modal">Add Taste</button>
        <button id="delete-btn" class="btn btn-lg">Delete</button>
        <button id="quantity-btn" class="btn btn-lg">Quantity</button>
        <button id="take-out-btn" class="btn btn-lg">Take Out</button> 
        <!-- <button id="add-discount-btn" class="btn btn-lg">Add Discount</button>  -->
    </div>

  
</div>

<div id="single-extra-component-modal-placeholder">
    
</div>

</body>
</html>

<script id="taste-component" type="text/template">
    <div class="modal fade clearfix" id="taste-component-modal" role="dialog">
        <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content clearfix">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Taste(味道)</h4>
                </div>
                <div class="modal-body clearfix">
                    <ul id="taste-component-items" class="clearfix">
                        
                    </ul>
                    <div class="clearfix">
                        已选:
                    </div>
                    <ul id="selected-extra" class="clearfix">
                        
                    </ul>
                </div>
                <div class="modal-footer clearfix">
                    <div class="clearfix">
                        <label class="pull-left" for="taset-component-special">Special Instructions:</label>
                        <input class="pull-left" id="taset-component-special" type="text" placeholder="e.g. no onions, no mayo" size="30">
                    </div>
                    <div class="clearfix">
                         <button type="button" id="taste-component-clear" class="pull-left btn btn-lg btn-danger">Clear 清除</button>
                        <button type="button" id="taste-component-save" class="pull-right btn btn-lg btn-success">Save 保存</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</script>


<script id="single-extra-component" type="text/template">
    <div class="modal fade clearfix" id="single-extra-component-modal" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content clearfix">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <ul id="single-extra-component-categories" class="clearfix">
                    </ul>
                    
                </div>
                <div class="modal-body clearfix">
                    <ul id="single-extra-component-items" class="clearfix">
                        
                    </ul>

                    
                    <div id="single-selected-extra-title" class="clearfix">
                        口味已选:
                    </div>
                    <ul id="single-selected-extra" class="clearfix">
                        
                    </ul>
                    <div id="single-selected-combo-title" class="clearfix">
                        拼盘已选:
                    </div>
                    <ul id="single-selected-combo" data-combo-num="0" class="clearfix">
                        
                    </ul>

                </div>
                <div class="modal-footer clearfix">
                    <div class="clearfix">
                        <label class="pull-left" for="single-extra-component-special">Special Instructions:</label>
                        <input class="pull-left" id="single-extra-component-special" type="text" placeholder="e.g. no onions, no mayo" size="30">
                    </div>
                    <div class="clearfix">
                         <button type="button" id="single-extra-component-clear" class="pull-left btn btn-lg btn-danger">Clear 清除</button>
                        <button type="button" id="single-extra-component-save" class="pull-right btn btn-lg btn-success">Save 保存</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</script>


<!-- <script id="taste-component" type="text/template"></script> -->
<script id="selected-extra-item-component" type="text/template">
    <li class="selected-extra-item clearfix" data-extra-id="{0}" data-extra-category-id="{1}">
        <button type="button" class="close pull-right">&times;</button>
        <div class="selected-extra-item-name">{2}</div>
        <div class="selected-extra-item-price">{3}</div>
    </li>
</script>

<?php
echo $this->Html->script(array('jquery.min.js', 'bootstrap.min.js', 'jquery.mCustomScrollbar.concat.min.js', 'barcode.js', 'epos-print-5.0.0.js', 'fanticonvert.js', 'jquery.kinetic.min.js', 'flowtype.js', 'avgsplit.js'));
echo $this->fetch('script');
?>

<script type="text/javascript">

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

    var Order_Item_Printer = Array();


    $(".add_items").on("click", function () {
        var item_id = $(this).attr("alt");
        var message = $("#Message").val();

        $.ajax({
            url: "<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'addItem')); ?>",
            method: "post",
            data: {item_id: item_id, table: "<?php echo $table ?>", type: "<?php echo $type ?>"},
            success: function (html) {
                // console.log(html);
                $(".summary_box").html(html);
                $(".order-summary-indent").scrollTop($(".order-summary-indent ul").height());

                /*$('.less-title').flowtype({
                    // fontRatio : 15,
                    // minimum: 500,
                    minFont: 13,
                    maxFont: 20
                });*/


                $(".products-panel").removeClass('load1 csspinner');

                $("#order_no_display").html("Order 订单号 #" + $("#Order_no").val() + ", Table 桌 #<?php echo $table; ?>");
 
                Order_Item_Printer = Array();
                
                if ($('#Order_Item').val() != ""){
                    var arrtmp = $('#Order_Item').val().split("#");
                }

                for (var i = 0; i < arrtmp.length; i++) {
                    Order_Item_Printer.push(arrtmp[i].split("*"));
                }
                

                // if ($("#show_extras_flag").val() ==  true) {
                //     $(".dropdown-toggle").trigger("click");
                // }

            },
            beforeSend: function () {
                $(".products-panel").addClass('load1 csspinner');
            }
        });
    });

    $('#delete-btn').on('click', function () {
        var selected_item_id_list = getSelectedItem();

        if (selected_item_id_list.length == 0) {
            alert("No item selected");
            return false;
        }

        $.ajax({
            url: "<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'removeitem')); ?>",
            method: "post",
            data: {selected_item_id_list: selected_item_id_list, table: "<?php echo $table ?>", type: "<?php echo $type ?>", order_no: $("#Order_no").val()},
            success: function(html) {
                // console.log(html);
                $(".summary_box").html(html);
                // $(".summary_box").removeClass('load1 csspinner');

                // TODO
                // add printer array
            }
        });

    });
    //get order_item_id of all selected items
    var getSelectedItem = function () {
        var order_item_id_list = [];

        $('#order-component .order-item.selected').each(function() {
            order_item_id_list.push(parseInt($(this).attr('data-order-item-id')));
            // console.log($(this).attr('data-order-item-id'));
        });

        return order_item_id_list;
    }

    var getSelectedItemDetails = function () {
        var selectedDetails = [];

        $('#order-component .order-item.selected').each(function() {
            var temp = {
                "selected-extras": $(this).attr('data-selected-extras'),
                "combo_id": $(this).attr('data-comb-id'),
            }
            // console.log($(this).attr('data-order-item-id'));

            selectedDetails.push(temp);
        });

        return selectedDetails;
    }



    $(document).on('click', ".waimai-link", function () {
        var item_id = $(this).attr("alt");
        var order_id = $(this).attr("order_id");
        var message = $("#Message").val();
        $.ajax({
            url: "<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'waimaiitem')); ?>",
            method: "post",
            data: {item_id: item_id, order_id: order_id, table: "<?php echo $table ?>", type: "<?php echo $type ?>"},
            success: function (html) {
                $(".summary_box").html(html);
                $(".summary_box").removeClass('load1 csspinner');
				Order_Item_Printer = Array();
				if ($('#Order_Item').val() != ""){
	                var arrtmp = $('#Order_Item').val().split("#");
				};
                for (var i = 0; i < arrtmp.length; i++) {
                    Order_Item_Printer.push(arrtmp[i].split("*"));
                }
            },
            beforeSend: function () {
                $(".summary_box").addClass('load1 csspinner');
            }
        })
    })

    $(document).on('click', ".add_extras", function () {
        var item_id = $(this).attr("item_id");
        var price = $(this).attr("price");
        var name = $(this).attr("name");
        var extra_id = $(this).attr("alt");
		//MOdified by Yishou Liao @ Dec 15 2016
		var category_id = $(this).attr("category_id");

		var numcomb = $("#numofcomb").val().split(",");
		var str_search = $("#block" + item_id).html();
		var current_num = 0;
		var count_num=0;
		while (current_num != -1){
			current_num = str_search.indexOf('category_id=\"'+numcomb[0]+'\"',(current_num+1));
			if (current_num!=-1){count_num++;};
		};
		if (count_num<parseInt(numcomb[1])||category_id!=numcomb[0]) {
		//End @ Dec 15 2016
		
        var html = '<div class="extras_inner" category_id="' + category_id + '" alt="' + extra_id + '"><span>' + name + '</span><span>' + (price != 0 ? price : "") + '</span><a class="fa fa-times remove_extra" href="javascript:void(0)"> </a></div>';

        $("#block" + item_id).append(html);
		};//MOdified by Yishou Liao @ Dec 15 2016

    })



    $(document).on('click', ".remove_extra", function () {
        $(this).parent(".extras_inner").remove();

    })



    $(document).on("click", '.sub-items', function (e) {
        e.stopPropagation();
    })

    function getCurrentItems () {
        var current_items = []; // store order-item-id
        $('#order-component li').each(function() {
            current_items.push($(this).attr('data-order-item-id'));
        });

        return current_items;
    }

    $("#send-to-kitchen-btn").on('click', function() {
        $.ajax({
            url: "<?php echo $this->Html->url(array('controller' => 'print', 'action' => 'printToKitchen',1,0)); ?>",
            method: "post",
            data: {
                current_items: getCurrentItems(),
                Printer: {"K": "<?php echo $cashier_detail['Admin']['kitchen_printer_device']; ?>", "C": "<?php echo $cashier_detail['Admin']['service_printer_device']; ?>"},
                order_no: $("#Order_no").val(),//Modified by Yishou Liao @ Dec 12 2016
                order_type: '<?php echo isset($Order_detail['Order']['order_type']) ? $Order_detail['Order']['order_type'] : "" ?>',
                table_no: '<?php echo (($type == 'D') ? '[[堂食]]' : (($type == 'T') ? '[[外卖]]' : (($type == 'W') ? '[[等候]]' : ''))) . ' #' . $table ?>',
                table: '<?php echo $table ?>',
            },
        }); 
    });

    $(document).on("click", "#submit", function () {
        //Modified by Yishou Liao @ Oct 26 2016.
        var ext_arr;
        for (var i = 0; i < Order_Item_Printer.length; i++) {
            if (Order_Item_Printer[i][10] != "") {
                ext_arr = JSON.parse(Order_Item_Printer[i][10]);
                Order_Item_Printer[i][10] = "";
                for (var j = 0; j < ext_arr.length; j++) {
					//Modified by Yishou Liao @ Nov 16 2016
					if (ext_arr[j]['price'] == "-1") {
						Order_Item_Printer[i][16] = "#T#";
					}else{
	                    Order_Item_Printer[i][10] += ext_arr[j]['name'] + "  ";
					};
					//End
                };
            };
        };

        //Modified by Yishou Liao @ Nov 01 2016.
        $.ajax({
            url: "<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'printTokitchen',1,0)); ?>",
            method: "post",
            data: {
                Print_Item: Order_Item_Printer,
                Printer: {"K": "<?php echo $cashier_detail['Admin']['kitchen_printer_device']; ?>", "C": "<?php echo $cashier_detail['Admin']['service_printer_device']; ?>"},
                order_no: $("#Order_no").val(),//Modified by Yishou Liao @ Dec 12 2016
                order_type: '<?php echo isset($Order_detail['Order']['order_type']) ? $Order_detail['Order']['order_type'] : "" ?>',
                table_no: '<?php echo (($type == 'D') ? '[[堂食]]' : (($type == 'T') ? '[[外卖]]' : (($type == 'W') ? '[[等候]]' : ''))) . ' #' . $table ?>',
                table: '<?php echo $table ?>',
            },
            dataType: "html",
            async: false,
            success: function (html) {
                // update order message here
                if (!$(this).hasClass('disabled')) {
                    var order_id = $(this).attr("alt");
                    $.ajax({
                        url: "<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'updateordermessage')); ?>",
                        method: "post",
                        data: {order_id: order_id, table: "<?php echo $table ?>", type: "<?php echo $type ?>", message: $("#Message").val(), is_kitchen: "Y"},
                        success: function (html) {
                            window.location = "<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'dashboard')); ?>";
                        },
                        beforeSend: function () {
                            $(".summary_box").addClass('load1 csspinner');
                        }
                    })
                }
            },
            error: function (html) {
                alert("error");
            }
        })
        //End.
    });
    $(document).on("click", "#pay", function () {
		//Modified by Yishou Liao @ Jan 07 2017
		$("#submit").trigger("click");
		//End @ Jan 07 2017
		
        // update order message here
        if (!$(this).hasClass('disabled')) {
            var order_id = $(this).attr("alt");
            $.ajax({
                url: "<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'updateordermessage')); ?>",
                method: "post",
                data: {order_id: order_id, table: "<?php echo $table ?>", type: "<?php echo $type ?>", message: $("#Message").val(), is_kitchen: "N"},
                success: function (html) {
                    window.location = "<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'pay', 'table' => $table, 'type' => $type)); ?>";
                },
                beforeSend: function () {
                    $(".summary_box").addClass('load1 csspinner');
                }
            })
        }
    });
	
	//Modified by Yishou Liao @ Nov 10 2016
	$(document).on("click", ".clearbtn", function () {
		var item_id = $(this).attr("alt");
		$("#ext_memo"+item_id).val("");
	});
	$(document).on("click", "#extnobtn", function () {
		var item_id = $(this).attr("alt");
		$("#ext_memo"+item_id).val($("#ext_memo"+item_id).val()+"No ");
	});
	$(document).on("click", "#extmorebtn", function () {
		var item_id = $(this).attr("alt");
		$("#ext_memo"+item_id).val($("#ext_memo"+item_id).val()+"More ");
	});
	$(document).on("click", "#extlessbtn", function () {
		var item_id = $(this).attr("alt");
		$("#ext_memo"+item_id).val($("#ext_memo"+item_id).val()+"Less ");
	});
	//End

    $(document).on("click", ".savebtn", function () {
        var id = $(this).attr("alt");
        var message = $("#Message").val();
        var array = new Array();

        // get all selected extras items of menu
        $("#block" + id + " div.extras_inner").each(function () {
            array.push($(this).attr('alt'));
        });
        var input_value = array.toString();
        var element = $(this).parent("ul.dropdown-menu");

		var var_inputext;
		if ($("#ext_memo").val()!=""){
			if (input_value!=""){
				var_inputext = input_value+","+$("#ext_memo"+id).val();
			}else{
				var_inputext = $("#ext_memo"+id).val();
			};
		}else{
			var_inputext = input_value;
		};
        $.ajax({
            url: "<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'add_extras')); ?>",
            method: "post",
            data: {item_id: id, extras: var_inputext, table: "<?php echo $table ?>", type: "<?php echo $type ?>"},
            success: function (html) {
                $(".summary_box").html(html);
                $(".products-panel").removeClass('load1 csspinner');
                $(".clearfix.cart-wrap").removeClass("csspinner");

                // Modified by Yishou Liao @ Oct 28 2016.
                Order_Item_Printer = Array();
				// Modified by Yishou Liao @ Nov 16 2016.
				if ($('#Order_Item').val() != ""){
	                var arrtmp = $('#Order_Item').val().split("#");
				};
				//End
                for (var i = 0; i < arrtmp.length; i++) {
                    Order_Item_Printer.push(arrtmp[i].split("*"));
                };
                //End.

            },
            beforeSend: function () {
                element.addClass('load1 csspinner');
            }
        })
    })

    $(document).ready(function () {

        $(".search-clear").click(function () {
            $("#search-input").val('');
            $("#search-input").focus();
            $(".add_items").show();
        })

        $("#search-input").on("keyup", function () {
            var value = $(this).val();

            $(".add_items").each(function (index) {
                $row = $(this);

                var id = $row.find(".name-title").text();

                if (id.toLowerCase().indexOf(value) < 0) {
                    $row.hide();
                } else {
                    $row.show();
                }
            });
        });

        $.ajax({
            // async: false, 
            url: "<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'summarypanel', $table, $type)); ?>",
            method: "post",
            success: function (html) {
                $(".summary_box").html(html);
                $(".products-panel").removeClass('load1 csspinner');

                // Modified by Yishou Liao @ Nov 16 2016.
				if ($('#Order_Item').val() != ""){
	                var arrtmp = $('#Order_Item').val().split("#");
					for (var i = 0; i < arrtmp.length; i++) {
						Order_Item_Printer.push(arrtmp[i].split("*"));
					};
				};
            },
            beforeSend: function () {
                $(".products-panel").addClass('load1 csspinner');
            }
        })
    });

    $(document).on("keyup", ".discount_section", function () {
        if ($(this).val()) {
            $(".discount_section").attr("disabled", "disabled");
            $(this).removeAttr("disabled");
			$(this).focus();
        } else {
            $(".discount_section").removeAttr("disabled");
        }
    })

    $(document).on("click", "#apply-discount", function () {

        var fix_discount = $("#fix_discount").val();
        var discount_percent = $("#discount_percent").val();
        var promocode = $("#promocode").val();

        if (fix_discount || discount_percent || promocode) {
            // apply promocode here
            $.ajax({
                url: "<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'add_discount')); ?>",
                method: "post",
                dataType: "json",
                data: {fix_discount: fix_discount, discount_percent: discount_percent, promocode: promocode, order_id: $("#pay").attr("alt")},
                success: function (html) {
                    if (html.error) {
                        alert(html.message);
                        $(".discount_section").val("").removeAttr("disabled");
                        $(".products-panel").removeClass('load1 csspinner');
                        $(".summary_box").removeClass('load1 csspinner');
                        return false;
                    } else {
                        $.ajax({
                            url: "<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'summarypanel', $table, $type)); ?>",
                            method: "post",
                            success: function (html) {
                                $(".summary_box").html(html);
                                $(".products-panel").removeClass('load1 csspinner');
                                $(".summary_box").removeClass('load1 csspinner');
                            }
                        })
                    }
                },
                beforeSend: function () {
                    $(".products-panel").addClass('load1 csspinner');
                    $(".summary_box").addClass('load1 csspinner');
                }
            })


        } else {
            alert("Please add discount first.");
            return false;
        }
    })

    $(document).on('click', ".remove_discount", function () {
        var order_id = $(this).attr("order_id");
        var message = $("#Message").val();
        $.ajax({
            url: "<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'remove_discount')); ?>",
            method: "post",
            data: {order_id: order_id},
            success: function (html) {
                $(".summary_box").html(html);
                $(".summary_box").removeClass('load1 csspinner');
            },
            beforeSend: function () {
                $(".summary_box").addClass('load1 csspinner');
            }
        })
    })
    $(document).on('click', ".add-discount", function () {
        if (!$(this).hasClass('disabled')) {
            $(".discount_view").toggle();
        }
    });
    $(document).on("click", '.dropdown-toggle', function () {
        if ($(this).attr("aria-expanded") == 'true') {
            $(".clearfix.cart-wrap").addClass("csspinner");
        } else {
            $(".clearfix.cart-wrap").removeClass("csspinner");
        }
        dropDownFixPosition($(this), $(".dropdown-menu"));
    });

    // notice
    // change style, should be move to style.css in the future
    function dropDownFixPosition(button, dropdown) {
        var dropDownTop = button.position().top;
        var left = $(document).width() - dropdown.width();
        var top = $(document).height() - dropdown.height();
        dropdown.css('left', left / 2 + "px");
        dropdown.css('top', "20%");
    }


    $(document).on('click', "html", function () {
        $(".clearfix.cart-wrap").removeClass("csspinner");
    });

    $(document).on('click', ".dropdown-menu", function (event) {
        event.stopPropagation();
    });


   

    // todo
    $('#add-discount-btn').on('click', function() {
        // console.log(order);

        // if (order.discount.type == "unknown") {
        //     // allow discount
        //     console.log("no discount");
        // } else {
        //     // show discount information
        //     console.log("has discount");
        // }

        // use ajax update page information
    });


    $('#batch-add-taste-btn').on('click', function() {
        var selected_item_id_list = getSelectedItem();

        if (selected_item_id_list.length == 0) {
            alert("No item selected");
            return false;
        } else {
            $("#selected-extra").empty();
        }

    });




    


    $('#take-out-btn').on('click', function() {
        var selected_item_id_list = getSelectedItem();

        if (selected_item_id_list.length == 0) {
            alert("No item selected");
            return false;
        }

        $.ajax({
            url: "<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'takeout')); ?>",
            method: "post",
            data: {selected_item_id_list: selected_item_id_list, table: "<?php echo $table ?>", type: "<?php echo $type ?>"},
            success: function (html) {
                $(".summary_box").html(html);
                // $(".summary_box").removeClass('load1 csspinner');
            },
        });
    });


    var TastesComponent = (function() {
        var tastesComponent = $($('#taste-component').html());


        var createDom = function (tastes) {
            var itemsUl = tastesComponent.find('#taste-component-items');
            
            for (var i = 0; i < tastes.length; ++i) {

                var taste = tastes[i];

                if (taste.category_id == 1) {
                    // build item with jquery
                    var itemComponent = $('<li class="taste-item-component" data-extra-id="{0}" data-extra-category-id="{1}"><div class="taste-item-name">{2}</div><div class="taste-item-price">{3}</div></li>'.format(taste.id, taste.category_id, taste.name_zh, taste.price));
                    // itemComponent.find('.taste-item-name').text(taste.name_zh);
                    // itemComponent.find('.taste-item-price').text(taste.price);

                    if (parseFloat(taste.price) == 0) {
                        itemComponent.find('.taste-item-price').hide();
                    }

                    itemsUl.append(itemComponent);
                } else {
                    continue;
                }

                
            }
        }

        var bindEvent = function(SelectedExtraItemComponent) {
            tastesComponent.find('.taste-item-component').each(function() {
                // console.log($(this));
                $(this).on('click', function() {
                    var extra_id = $(this).attr('data-extra-id'),
                        extra_category_id = $(this).attr('data-extra-category-id'),
                        name = $(this).find('.taste-item-name').text(),
                        price = $(this).find('.taste-item-price').text();

                    var selectedItem = SelectedExtraItemComponent.init(extra_id, extra_category_id, name, price);
                    selectedItem.find('button').on('click', function() {
                        selectedItem.remove();
                    })

                    tastesComponent.find('#selected-extra').append(selectedItem);
                });
            });
            // clear button
            
        }

        var init = function(tastes, SelectedExtraItemComponent) {
            createDom(tastes);
            bindEvent(SelectedExtraItemComponent);

            return tastesComponent;
        }

        

        return {
            init: init
        }
    })();


    var SingleExtraComponent = (function() {
        
        var createDom = function (tastes, categories, combo_id, selected_extras, SelectedExtraItemComponent) {

            var singleExtraComponent = $($('#single-extra-component').html());
            
            // build title list
            var titleUl = singleExtraComponent.find('#single-extra-component-categories');
            for (var i = 0; i < categories.length; ++i) {

                var category = categories[i];

                // category_id 1 means the tastes id
                if (category.category_id == combo_id || category.category_id == "1") {
                    var categoryComponent = $('<li data-extra-category-id="{0}" data-extra-combo-num="{1}">{2}({3})</li>'.format(category.category_id, category.combo_num,category.name_en, category.name_zh));
                    titleUl.append(categoryComponent);
                }
            }

            // build item list
            var itemsUl = singleExtraComponent.find('#single-extra-component-items');
            
            for (var i = 0; i < tastes.length; ++i) {
                var taste = tastes[i];
                // category_id 1 means the tastes id
                // other category
                if (taste.category_id == combo_id || taste.category_id == "1") {
                    // build item with jquery
                    var itemComponent = $('<li class="taste-item-component" data-extra-id="{0}" data-extra-category-id="{1}"><div class="taste-item-name">{2}</div><div class="taste-item-price">{3}</div></li>'.format(taste.id, taste.category_id, taste.name_zh, taste.price));

                    if (parseFloat(taste.price) == 0) {
                        itemComponent.find('.taste-item-price').hide();
                    }

                    itemsUl.append(itemComponent);
                }
            }


            // build selected item list
            var selectedExtraUl = singleExtraComponent.find('#single-selected-extra');
            selectedExtraUl.empty();

            var selectedComboUl = singleExtraComponent.find('#single-selected-combo');
            selectedComboUl.empty();

            $.each(selected_extras, function(i) {
                var tempItem = SelectedExtraItemComponent.init(selected_extras[i]['id'], selected_extras[i]['category_id'], selected_extras[i]['name'], selected_extras[i]['price']);

                if (selected_extras[i]['category_id'] == '1') {
                    selectedExtraUl.append(tempItem);
                } else {
                    selectedComboUl.append(tempItem);
                }
            });

            if (combo_id == '0') {
                singleExtraComponent.find('#single-selected-combo-title').hide();
                selectedComboUl.hide();
            }


            // build up selected items based on the history data
            return singleExtraComponent;

        }


        var bindEvent = function(singleExtraComponent, categories, combo_id, SelectedExtraItemComponent) {
            // add event for select extra
            singleExtraComponent.find('.taste-item-component').each(function() {
                // console.log($(this));
                $(this).on('click', function() {
                    var extra_id = $(this).attr('data-extra-id'),
                        extra_category_id = $(this).attr('data-extra-category-id'),
                        name = $(this).find('.taste-item-name').text(),
                        price = $(this).find('.taste-item-price').text();
                    var selectedItem = SelectedExtraItemComponent.init(extra_id, extra_category_id, name, price);

                    var combo_num = 0;
                    for (var i = 0; i < categories.length; ++i) {
                        if(categories[i].category_id == combo_id) {
                            combo_num = categories[i].combo_num;
                        }
                    }
                    // console.log(combo_num);

                    if (extra_category_id == '1') {
                        singleExtraComponent.find('#single-selected-extra').append(selectedItem);
                    } else {
                        // restriction of combo
                        if (singleExtraComponent.find('#single-selected-combo li').length < combo_num) {
                            singleExtraComponent.find('#single-selected-combo').append(selectedItem);
                        }                           
                    }
                    
                });
            });

            // bind event for categories, different category bind different items
            singleExtraComponent.find('#single-extra-component-categories li ').each(function() {
                $(this).on('click', function() {
                    // hide all extra
                    singleExtraComponent.find('#single-extra-component-items li').hide();
                    var category_id = $(this).attr("data-extra-category-id");

                    singleExtraComponent.find('#single-extra-component-items li').each(function() {
                        if ($(this).attr("data-extra-category-id") == category_id) {
                            $(this).show();
                        }
                    })
                })
            });
            
            singleExtraComponent.find('#single-extra-component-categories li').first().click();
            return singleExtraComponent;
        }

        var init = function(tastes, categories, combo_id, selected_extras, SelectedExtraItemComponent) {
            var singleExtraComponent = createDom(tastes, categories, combo_id, selected_extras, SelectedExtraItemComponent);
            singleExtraComponent = bindEvent(singleExtraComponent, categories, combo_id, SelectedExtraItemComponent);

            return singleExtraComponent;
        }

        return {
            init: init,
            bindEvent: bindEvent
        }
    })();



    var SelectedExtraItemComponent = (function() {
        var createDom = function (extra_id, extra_category_id, name, price) {
            var selectedItem = $($('#selected-extra-item-component').html().format(extra_id, extra_category_id, name, price));
            if (parseFloat(price) == 0) {
                selectedItem.find('.selected-extra-item-price').hide();
            }

            return selectedItem;
        }

        var bindEvent = function(selectedItem) {
            selectedItem.find('button').on('click', function() {
                selectedItem.remove();
            })

            return selectedItem;
        }

        var init = function(extra_id, extra_category_id, name, price) {
            var selectedItem = createDom(extra_id, extra_category_id, name, price);
            selectedItem = bindEvent(selectedItem);

            return selectedItem;
        }

        return {
            init: init
        }
    })()



    class Extra {
        constructor(id, cousine_id, name_en, name_zh, price, category_id) {
            this.id = id;
            this.cousine_id = cousine_id;
            this.name_en = name_en;
            this.name_zh = name_zh;
            this.price = price;
            this.category_id = category_id;
        }
    }

    class ExtraCategory {
        constructor(category_id, name_en, name_zh, combo_num) {
            this.category_id = category_id;
            this.name_en = name_en;
            this.name_zh = name_zh;
            this.combo_num = combo_num;
        }
    }

// load extra based on category id
    var loadExtras = function() {
        var extras = [];

        <?php
            if (!empty($extras)) {
                $i = 0;
                foreach ($extras as $extra) {
            ?>
                    var temp_extra = new Extra(
                            id = '<?php echo $extra["id"]; ?>',
                            cousine_id = '<?php echo $extra["cousine_id"]; ?>',
                            name_en = '<?php echo $extra["name"]; ?>',
                            name_zh = '<?php echo $extra["name_zh"]; ?>',
                            price = '<?php echo $extra["price"]; ?>',
                            category_id = '<?php echo $extra["category_id"]; ?>');

                    extras.push(temp_extra);
            <?php
                }
            ?>

        <?php 
            }
        ?>
        return extras;
    }

    var loadExtraCategories = function() {
        var categories = [];

        <?php 
            if (!empty($extra_categories)) {
                $i = 0;
                foreach ($extra_categories as $category) {
         ?>         
                    var tempCategory = new ExtraCategory(
                            category_id = '<?php echo $category["id"] ?>',
                            name_en = '<?php echo $category["name"] ?>',
                            name_zh = '<?php echo $category["name_zh"] ?>',
                            combo_num = '<?php echo $category["extras_num"] ?>'
                        );

                    categories.push(tempCategory);
         <?php 
                }
            }
          ?>   
               
        return categories;
    }

    var extras = loadExtras();
    var extraCategories = loadExtraCategories();

    var tastesComponent = TastesComponent.init(extras, SelectedExtraItemComponent);



    $('body').append(tastesComponent);



    // save button, send ajax to the backend and store the data in database
    $('body').on('click', '#taste-component-save', function() {
        console.log($('#selected-extra li'));
        // save all selected-extra to all selected items
        var selected_item_id_list = getSelectedItem();
        var selected_extras_id = [];
        $('#selected-extra li').each(function() {
            selected_extras_id.push($(this).attr('data-extra-id'));
            // selected_extras_amount += parseFloat($(this).find(".selected-extra-item-price").text());
        });
        // selected_extras_id = selected_extras_id.join(',');
        console.log(selected_extras_id);
        console.log(selected_item_id_list);
        // console.log(selected_extras_amount);

        $.ajax({
            url: "<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'batchAddExtras')); ?>",
            method: "post",
            data: {selected_item_id_list: selected_item_id_list, selected_extras_id: selected_extras_id, table: "<?php echo $table ?>", type: "<?php echo $type ?>"},
            success: function (html) {
                $(".summary_box").html(html);
                $('.modal-header .close').trigger('click');
            }
        });
    });


    
    $('body').on('click', '#add-taste-btn' ,function() {
        var selected_item_id_list = getSelectedItem();

        if (selected_item_id_list.length == 0) {
            alert("No item selected");
            return false;
        } else if (selected_item_id_list.length > 1) {
            alert("Please select only one item");
            return false;
        }



        var selected_item_id = parseInt(selected_item_id_list[0]);

        var selected_extras = [];
        if (getSelectedItemDetails()[0]['selected-extras']) {
            selected_extras = JSON.parse(getSelectedItemDetails()[0]['selected-extras']);
        }

        console.log(selected_extras);


        // combo_id = 0 mean no combo
        // other combo_id means the different extra.category_id
        var combo_id = getSelectedItemDetails()[0]['combo_id'];

        // remove existing modal
        $('#single-extra-component-modal').modal('hide').remove();
        var singleExtraComponent = SingleExtraComponent.init(extras, extraCategories, combo_id, selected_extras, SelectedExtraItemComponent);
        $('body').append(singleExtraComponent);

        
    });

    $('body').on('click', '#single-extra-component-save', function() {

        var selected_item_id = getSelectedItem()[0];
        var selected_extras_id = [];
        $('#single-selected-extra li').each(function() {
            selected_extras_id.push($(this).attr('data-extra-id'));
        });

        $('#single-selected-combo li').each(function() {
            selected_extras_id.push($(this).attr('data-extra-id'));
        });

        console.log(selected_extras_id);
        console.log(selected_item_id);

        $.ajax({
            url: "<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'addExtras')); ?>",
            method: "post",
            data: {selected_item_id: selected_item_id, selected_extras_id: selected_extras_id, table: "<?php echo $table ?>", type: "<?php echo $type ?>"},
            success: function(html) {
                $(".summary_box").html(html);
                $('.modal-header .close').trigger('click');
            }
        })
    }); 

    // when part of selected items are printed, only allow delete action
    $('body').on('click', '#order-component li',function() {
        console.log('click');
        if ($('#order-component li.selected.is-print').length > 0) {
            $('#button-group .btn').not('#delete-btn').attr('disabled', true);
        } else {
            $('#button-group .btn').not('#delete-btn').attr('disabled', false);
        }
    });





</script>