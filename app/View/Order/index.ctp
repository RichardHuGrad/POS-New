<html>
<head>
    <?php echo $this->Html->css(array('order', 'components/TastesComponent', 'summarypanel')); ?>
</head>


<body>

    <header class="product-header">

        <div class="home-logo">
            <a href="<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'dashboard')) ?>">
                <?php                      
                	if(substr($_SERVER['REQUEST_URI'],0,5)=='/skip')
                  		echo $this->Html->image("logo-skip.jpg", array('alt' => "SKIP"));               
                	else
                  		echo $this->Html->image("logo-home.jpg", array('alt' => "POS")); 
                ?>
            </a>

            <div class="HomeText text-left">
                <a href="<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'index')) ?>"><?php echo __('Home')?></a>
                <a href="javascript:void(0)" onclick="window.history.back()"><?php echo __('Back')?></a>
            </div>

        </div>

        <div class="logout"><a href="<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'logout')) ?>"><?php echo __('Logout')?></a></div>

        <ul class="nav nav-tabs text-center" style="margin-left:100px">
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
                <?php echo __('Order No.')?><?php echo @$Order_detail['Order']['order_no']; ?>, <?php echo __('Table No.')?><?php echo $table; ?><?php echo @$Order_detail['Order']['phone']!=''?(', Tel: '.$Order_detail['Order']['phone']):''; ?><?php echo @$Order_detail['Order']['message']!=''?(', '.$Order_detail['Order']['message']):''; ?><?php echo @$Order_detail['Order']['reason']!=''?(', '.$Order_detail['Order']['reason']):''; ?>
            <!-- End -->
            </div>
        </div>

        <div class="col-md-3 col-sm-4 col-xs-12">
            <div class="searchwrap">
                <label for="search-input"><i class="fa fa-search" aria-hidden="true"></i></label>
                <a class="fa fa-times-circle-o search-clear" aria-hidden="true"></a>
                <input id="search-input" class="form-control input-lg" placeholder=<?php echo __('Search')?>>
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
        	
            <div class="tab-content <?php if(@$Order_detail['Order']['table_status']=='P') echo 'hide'; ?>">

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
    
    <div class="col-md-12 col-sm-12 col-xs-12 " id="button-group">
    	<div class="col-md-8">
        <button id="batch-add-taste-btn" class="btn btn-info btn-lg" data-toggle="modal" data-target="#taste-component-modal"><strong><?php echo __('Batch Add Taste');?></strong></button>
        <button id="delete-btn" class="btn btn-lg btn-danger"><strong><?php echo __('Delete');?></strong></button>
        <button id="quantity-btn" class="btn btn-lg btn-warning" data-toggle="modal" data-target="#change-quantity-component-modal"><strong><?php echo __('Change Quantity');?></strong></button>
        <button id="take-out-btn" class="btn btn-lg btn-info"><strong><?php echo __('Takeout');?></strong></button>
        <button id="urge-btn" class="btn btn-lg btn-info"><strong><?php echo __('Urge');?></strong></button>
        <button id="change-price-btn" class="btn btn-lg btn-warning" data-toggle="modal" data-target="#change-price-component-modal"><strong><?php echo __('Change Price');?></strong></button>
        <!-- <button id="free-price-btn" class="btn btn-lg"><strong>免费</strong></button> -->
        <!-- <button id="add-discount-btn" class="btn btn-lg">Add Discount</button>  -->
        <button id="edit-phone-btn" class="btn btn-lg btn-info" data-toggle="modal" data-target="#edit-phone-component-modal"><strong><?php echo __('Edit Phone');?></strong></button>        
      </div>
      <div class="col-md-4">
        <button id="send-to-kitchen-btn" class="btn btn-xl btn-primary" disabled style="margin-left:auto"><strong><?php echo __('Send to Kitchen')?></strong></button>
        <button id="pay-btn" class="btn btn-xl btn-success"><strong><?php echo __('Pay')?></strong></button>
        <button id="add-taste-btn" class="btn btn-xl btn-info" data-toggle="modal" data-target="#single-extra-component-modal"><strong><?php echo __('CHange Taste');?></strong></button>
      </div>
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
                    <h4 class="modal-title"><?php echo __('Taste');?></h4>
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
                        <label class="pull-left" for="taste-component-special">Special Instructions: </label>
                        <input class="pull-left" id="taste-component-special" type="text" placeholder="e.g. no onions, no mayo" size="30" style="height:26px">
                    </div>
                    <div class="clearfix">
                         <button style="display:none;" type="button" id="taste-component-clear" class="pull-left btn btn-lg btn-danger"><?php echo __('Clear');?></button>
                        <button type="button" id="taste-component-save" class="pull-right btn btn-lg btn-success"><?php echo __('Save');?></button>
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
                        <?php echo __('Selected Taste');?>:
                    </div>
                    <ul id="single-selected-extra" class="clearfix">

                    </ul>
                    <div id="single-selected-combo-title" class="clearfix">
                        <?php echo __('Selected Combo');?>:
                    </div>
                    <ul id="single-selected-combo" data-combo-num="0" class="clearfix">

                    </ul>

                </div>
                <div class="modal-footer clearfix">
                    <div class="clearfix">
                        <label class="pull-left" for="single-extra-component-special">Special Instructions: </label>
                        <input class="pull-left" id="single-extra-component-special" type="text" placeholder="e.g. no onions, no mayo" size="30" style="height:26px">
                    </div>
                    <div class="clearfix">
                         <button style="display:none;" type="button" id="single-extra-component-clear" class="pull-left btn btn-lg btn-danger">Clear 清除</button>
                        <button type="button" id="single-extra-component-save" class="pull-right btn btn-lg btn-success">Save 保存</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</script>


<script id="change-price-component" type="text/template">
    <div class="modal fade clearfix" id="change-price-component-modal" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content clearfix">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4>{0}</h4>
                </div>
                <div class="modal-body clearfix">
                    New price: <input id="change-price-component-price" type="number" min="0" step="1" placeholder="eg. 0.00" style="height:30px">
                </div>
                <div class="modal-footer clearfix">                  
                    <button type="button" id="change-price-component-save" class="pull-right btn btn-lg btn-success">Save 保存</button>
                </div>
            </div>
        </div>
    </div>
</script>

<script id="change-quantity-component" type="text/template">
    <div class="modal fade clearfix" id="change-quantity-component-modal" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content clearfix">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4>{0}</h4>
                </div>
                <div class="modal-body clearfix">
                    New quantity: <input name="quantity" type="number" min="1" step="1" style="height:30px">
                </div>
                <div class="modal-footer clearfix">                  
                    <button type="button" id="change-quantity-component-save" class="pull-right btn btn-lg btn-success">Save 保存</button>
                </div>
            </div>
        </div>
    </div>
</script>

<div class="modal fade clearfix" id="edit-phone-component-modal" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content clearfix">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4>Input Phone</h4>
                </div>
                <div class="modal-body clearfix">
                    Phone: <input name="phone" type="text" style="height:30px" value="<?php echo @$Order_detail['Order']['phone']; ?>">
                </div>
                <div class="modal-footer clearfix">
                    
                    <button type="button" id="edit-phone-component-save" class="pull-right btn btn-lg btn-success">Save 保存</button>
                </div>
            </div>
        </div>
</div>


<!-- <script id="taste-component" type="text/template"></script> -->
<script id="selected-extra-item-component" type="text/template">
    <li class="selected-extra-item clearfix" data-extra-id="{0}" data-extra-category-id="{1}">
        <button type="button" class="close pull-right">&times;</button>
        <div class="selected-extra-item-name">{2}</div>
        <div class="selected-extra-item-price">{3}</div>
    </li>
</script>

<?php
echo $this->Html->script(array('jquery.min.js', 'bootstrap.min.js', 'jquery.mCustomScrollbar.concat.min.js', 'barcode.js', 'epos-print-5.0.0.js', 'fanticonvert.js', 'jquery.kinetic.min.js', 'flowtype.js', 'avgsplit.js', 'notify.min.js'));
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



    $(".add_items").on("click", function () {
        var item_id = $(this).attr("alt");
        var message = $("#Message").val();

        $.ajax({
            url: "<?php echo $this->Html->url(array('controller' => 'order', 'action' => 'addItem')); ?>",
            method: "post",
            data: {item_id: item_id, table: "<?php echo $table ?>", type: "<?php echo $type ?>"},
            success: function (json) {
                // console.log(html);

                // $(".order-summary-indent").scrollTop($(".order-summary-indent ul").height());
                $("#order_no_display").html("Order 订单号 #" + $("#Order_no").val() + ", Table 桌 #<?php echo $table; ?>");
                $(".products-panel").removeClass('load1 csspinner');

                var obj = JSON.parse(json);
				//  {"extra_categories":["15","16"],"order_item_id":"4143","comb_id":"0","comb_num":"0"}
                renderOrder(function() {
                    if (obj.comb_id != 0) {
                        $("#order-component li[data-order-item-id=" + obj.order_item_id + "]").trigger("click");
                        $("#add-taste-btn").trigger("click");
                    }

                });
            },
            beforeSend: function () {
                $(".products-panel").addClass('load1 csspinner');
            }
        });
    });

    $('#delete-btn').on('click', function () {
        var selected_item_id_list = getSelectedItem();

        if (selected_item_id_list.length == 0) {
            // alert("No item selected 没有选择菜");
             $.notify("No item selected 没有选择菜",  { position: "top center", className:"warn"});
            return false;
        }

        $.ajax({
            url: "<?php echo $this->Html->url(array('controller' => 'order', 'action' => 'removeitem')); ?>",
            method: "post",
            data: {selected_item_id_list: selected_item_id_list, table: "<?php echo $table ?>", type: "<?php echo $type ?>", order_no: $("#Order_no").val()},
            success: function(html) {
                renderOrder();
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
                "special": $(this).attr('data-special'),
                "selected-extras": $(this).attr('data-selected-extras'),
                "combo_id": $(this).attr('data-comb-id'),
                "cousine_id": $(this).attr('data-cousine-id'),
            }
            // console.log($(this).attr('data-order-item-id'));

            selectedDetails.push(temp);
        });

        return selectedDetails;
    }


    function getCurrentItems () {
        var current_items = []; // store order-item-id
        $('#order-component li').each(function() {
            current_items.push($(this).attr('data-order-item-id'));
        });

        return current_items;
    }

    $("#send-to-kitchen-btn").on('click', function() {
        $.ajax({
            url: "<?php echo $this->Html->url(array('controller' => 'order', 'action' => 'printToKitchen')); ?>",
            method: "post",
            data: {
                order_no: $("#Order_no").val(),
                type: "<?php echo $type ?>",
                table: '<?php echo $table ?>',
            },
            success: function(html) {
                // $(".summary_box").html(html);
                // renderOrder();
                <?php
                if($type == 'D')
                  echo "window.location = '{$this->Html->url(array('controller' => 'homes', 'action' => 'dashboard'))} ' ;";
                else
                  echo "window.location = window.location;"
                ?>              
                
            },
            beforeSend: function () {
                $(".summary_box").addClass('load1 csspinner');
            }
        });
    });

    $(document).ready(function () {
    	
        $('#edit-phone-component-modal').on('shown.bs.modal', function () {
            $( "input[name='phone']").focus();
        })  

        $('#change-price-component-modal').on('shown.bs.modal', function () {
            $( "input[type='number']").focus();
        })  

        $('#change-quantity-component-modal').on('shown.bs.modal', function () {
            $( "input[name='quantity']").focus();
        })  
           	

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

        renderOrder();
        
        //hide some buttons for online orders
        <?php 
           if(@$Order_detail['Order']['table_status']=='P'){
           	 echo "$('#pay-btn,#delete-btn,#quantity-btn,#change-price-btn,#edit-phone-btn').hide();";
           }              
        ?>
        
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
        var percent_discount = $("#percent_discount").val();
        var promocode = $("#promocode").val();

        
        if (fix_discount || percent_discount || promocode) {

            var discountType  = $("input.discount_section:enabled").attr('id');
            var discountValue = $("input.discount_section:enabled").val();
       
            $.ajax({
                url: "<?php echo $this->Html->url(array('controller' => 'discount', 'action' => 'addDiscount')); ?>",
                method: "post",
                dataType: "json",
                data: {"discountType":discountType,"discountValue":discountValue,"order_no": $("#Order_no").val()},
                success: function (res) {
                    renderOrder();

                    if (res.ret === 0) {
                        alert(res.message);
                    }
                }
            })

        } else {
            // alert("Please add discount first. 请加入折扣。");
            $.notify("Please add discount first. 请加入折扣。",  { position: "top center", className:"warn"});
            return false;
        }
    })

    $(document).on('click', ".remove_discount", function () {
        var message = $("#Message").val();
        $.ajax({
            url: "<?php echo $this->Html->url(array('controller' => 'discount', 'action' => 'removeDiscount')); ?>",
            method: "post",
            data: {order_no:  $("#Order_no").val()},
            success: function (html) {
                renderOrder();
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



    $('#batch-add-taste-btn').on('click', function() {
        var selected_item_id_list = getSelectedItem();

        if (selected_item_id_list.length == 0) {
            // alert("No item selected 没有选择菜");
             $.notify("No item selected 没有选择菜",  { position: "top center", className:"warn"});
            return false;
        } else {
            $("#selected-extra").empty();
        }

    });


    $('#take-out-btn').on('click', function() {
        var selected_item_id_list = getSelectedItem();

        if (selected_item_id_list.length == 0) {
            // alert("No item selected");
            $.notify("No item selected 没有选择菜",  { position: "top center", className:"warn"});
            return false;
        }

        $.ajax({
            url: "<?php echo $this->Html->url(array('controller' => 'order', 'action' => 'takeout')); ?>",
            method: "post",
            data: {selected_item_id_list: selected_item_id_list, table: "<?php echo $table ?>", type: "<?php echo $type ?>"},
            success: function (html) {
                renderOrder();
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

        var createDom = function (tastes, categories, combo_id, selected_extras, SelectedExtraItemComponent, special, cousineExtraCategory) {

            var singleExtraComponent = $($('#single-extra-component').html());

            // build title list
            var titleUl = singleExtraComponent.find('#single-extra-component-categories');
            for (var i = 0; i < categories.length; ++i) {

                var category = categories[i];

                // category_id 1 means the tastes id
                /*
                if (category.category_id == combo_id || category.category_id == "1") {
                    var categoryComponent = $('<li data-extra-category-id="{0}" data-extra-combo-num="{1}">{2}({3})</li>'.format(category.category_id, category.combo_num,category.name_en, category.name_zh));
                    titleUl.append(categoryComponent);
                }
                */
                if (cousineExtraCategory.indexOf(category.category_id) >= 0 || category.category_id == "1") {
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
                /*
                if (taste.category_id == combo_id || taste.category_id == "1") {
                    // build item with jquery
                    var itemComponent = $('<li class="taste-item-component" data-extra-id="{0}" data-extra-category-id="{1}"><div class="taste-item-name">{2}</div><div class="taste-item-price">{3}</div></li>'.format(taste.id, taste.category_id, taste.name_zh, taste.price));

                    if (parseFloat(taste.price) == 0) {
                        itemComponent.find('.taste-item-price').hide();
                    }

                    itemsUl.append(itemComponent);
                }
                */
                if (cousineExtraCategory.indexOf(taste.category_id) >= 0 || taste.category_id == "1") {
                    // build item with jquery
                    var itemComponent = $('<li class="taste-item-component" data-extra-id="{0}" data-extra-category-id="{1}"><div class="taste-item-name">{2}</div><div class="taste-item-price">{3}</div></li>'.format(taste.id, taste.category_id, taste.name_zh, taste.price));

                    if (parseFloat(taste.price) == 0) {
                        itemComponent.find('.taste-item-price').hide();
                    }

                    itemsUl.append(itemComponent);
                }
            }

            singleExtraComponent.find('#single-extra-component-special').val(special);


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

            singleExtraComponent.find('#single-extra-component-categories li').last().click();
            return singleExtraComponent;
        }

        var init = function(tastes, categories, combo_id, selected_extras, SelectedExtraItemComponent, special, cousineExtraCategory) {
            var singleExtraComponent = createDom(tastes, categories, combo_id, selected_extras, SelectedExtraItemComponent, special, cousineExtraCategory);
            singleExtraComponent = bindEvent(singleExtraComponent, categories, combo_id, SelectedExtraItemComponent);

            return singleExtraComponent;
        }

        return {
            init: init,
            bindEvent: bindEvent
        }
    })();

    var ChangePriceComponent = (function() {
        var createDom = function() {
            var changePriceComponent = $($('#change-price-component').html().format('改价格'));

            return changePriceComponent;
        }

        var init = function() {
            var changePriceComponent = createDom();

            return changePriceComponent;
        }

        return {
            init: init
        }
    })();

    var ChangeQuantityComponent = (function() {
        var createDom = function() {
            var changeQuantityComponent = $($('#change-quantity-component').html().format('改数量'));

            return changeQuantityComponent;
        }

        var init = function() {
            var changeQuantityComponent = createDom();

            return changeQuantityComponent;
        }

        return {
            init: init
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

	class CousineExtraCategory {
    	constructor(cousine_id, category_ids) {
            this.cousine_id = cousine_id;
            this.category_ids = category_ids;
        }
    }

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
                    var status = '<?php echo $extra["status"]; ?>';
                    if (status == 'A') {
                        var temp_extra = new Extra(
                            id = '<?php echo $extra["id"]; ?>',
                            cousine_id = '<?php echo $extra["cousine_id"]; ?>',
                            name_en = '<?php echo $extra["name"]; ?>',
                            name_zh = '<?php echo $extra["name_zh"]; ?>',
                            price = '<?php echo $extra["price"]; ?>',
                            category_id = '<?php echo $extra["category_id"]; ?>');

                        extras.push(temp_extra);
                    }

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

    var loadCousineExtraCategories = function() {
        var cousine_categories = [];
		var cousine_id;
        var category = [];

        <?php
            if (!empty($records)) {
                foreach ($records as $rc1) {
					foreach ($rc1['Cousine'] as $rc2) {
         ?>
         			cousine_id = '<?php echo $rc2['id']; ?>';
                    category = [];
                    <?php if ($rc2['extrascategories']) { 
						foreach ($rc2['extrascategories'] as $cid) { ?>
							category.push('<?php echo $cid; ?>');
					<?php	}
						}
					 ?>
                     cousine_categories[cousine_id] = category;
         <?php
                }
            }
			}
          ?>

        return cousine_categories;
    }


    var extras = loadExtras();
    var extraCategories = loadExtraCategories();
    var cousineExtraCategories = loadCousineExtraCategories();

    var tastesComponent = TastesComponent.init(extras, SelectedExtraItemComponent);



    $('body').append(tastesComponent);


    // save button, send ajax to the backend and store the data in database
    $('body').on('click', '#taste-component-save', function() {
        // console.log($('#selected-extra li'));
        // save all selected-extra to all selected items
        var selected_item_id_list = getSelectedItem();
        var selected_extras_id = [];
        $('#selected-extra li').each(function() {
            selected_extras_id.push($(this).attr('data-extra-id'));
            // selected_extras_amount += parseFloat($(this).find(".selected-extra-item-price").text());
        });
        // selected_extras_id = selected_extras_id.join(',');
        // console.log(selected_extras_id);
        // console.log(selected_item_id_list);
        // console.log(selected_extras_amount);
        
        if(selected_extras_id.length == 0){
        	$.notify("No taste selected 没有选择口味",  { position: "top center", className:"warn"});
         return false;
        }
        
        $.ajax({
            url: "<?php echo $this->Html->url(array('controller' => 'order', 'action' => 'batchAddExtras')); ?>",
            method: "post",
            data: {selected_item_id_list: selected_item_id_list, selected_extras_id: selected_extras_id, table: "<?php echo $table ?>", type: "<?php echo $type ?>", special: $("#taste-component-special").val()},
            success: function (html) {
                renderOrder();
                
                $('#taste-component-special').val('');
                $('#taste-component-modal .close').trigger('click');
            }
        });
    });



    $('body').on('click', '#add-taste-btn' ,function() {
    	
        var selected_item_id_list = getSelectedItem();
        
        if (selected_item_id_list.length == 0) { 
        	           
            // $.notify("No item selected 没有选择菜",{position:"top center",className:"warn"}); return false;
            
            //default select the first item
            $("#order-component li:first").click();
            
            selected_item_id_list = getSelectedItem();

        } else if (selected_item_id_list.length > 1) {
            // alert("Please select only one item");
            $.notify("Please select only one item 请只选择一个菜。",  { position: "top center", className:"warn"});
            return false;
        }


        var selected_item_id = parseInt(selected_item_id_list[0]);

        var selected_extras = [];
        if (getSelectedItemDetails()[0]['selected-extras']) {
            selected_extras = JSON.parse(getSelectedItemDetails()[0]['selected-extras']);
        }

        // combo_id = 0 mean no combo
        // other combo_id means the different extra.category_id
        var combo_id = getSelectedItemDetails()[0]['combo_id'];
        var cousine_id = getSelectedItemDetails()[0]['cousine_id'];
        var special = getSelectedItemDetails()[0]['special'];
        
        var cousineExtraCategory = [];
        if (typeof cousineExtraCategories[cousine_id] !== 'undefined') {
        	cousineExtraCategory = cousineExtraCategories[cousine_id];
        }
        
        // remove existing modal
        $('#single-extra-component-modal').modal('hide').remove();
        var singleExtraComponent = SingleExtraComponent.init(extras, extraCategories, combo_id, selected_extras, SelectedExtraItemComponent, special, cousineExtraCategory);
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

        var combo_id = $("#order-component li[data-order-item-id=" + selected_item_id + "]").attr("data-comb-id");
        // console.log(combo_id);

        if (combo_id != 0) {
            var combo_num = 0;
            for (var i = 0; i < extraCategories.length; ++i) {
                if(extraCategories[i].category_id == combo_id) {
                    combo_num = extraCategories[i].combo_num;
                }
            }
            //  must match the combo_num then can save the page
            if ( $("#single-selected-combo li").length != combo_num ) {
                $(this).notify("Please selected {0} combo. 请选择{0}种拼盘".format(combo_num), {position: "top center"});
                return false;
            }

        }

        if(selected_extras_id.length == 0) selected_extras_id="";
        
        $.ajax({
            url: "<?php echo $this->Html->url(array('controller' => 'order', 'action' => 'addExtras')); ?>",
            method: "post",
            data: {selected_item_id: selected_item_id, selected_extras_id: selected_extras_id, table: "<?php echo $table ?>", type: "<?php echo $type ?>", special: $("#single-extra-component-special").val()},
            success: function(html) {
                renderOrder();
                $('.modal-header .close').trigger('click');
            }
        })
    });

    // listen to ajax send
    ! function () {
        var ulContent;
        $(document).ajaxStop(function () {
            var $ul = $('#order-component');
            if(ulContent !== $ul.html()){
                ulContent = $ul.html();
                $ul.trigger('contentChanged');
            }
        });
    }();
    
    // when part of selected items are printed, only allow delete action
    $('body').on('click contentChanged','#order-component, #select-all',function() {
        // console.log('click');
        ChangeBtnDisabled(['#delete-btn, #change-price-btn' , '#urge-btn']);
    });

    function ChangeBtnDisabled(selectors) {
        
        //var selectorStr = selectors.join(',');
        if ($('#order-component li.selected.is-print').length > 0) {
            $.notify("If you want to modify items which have been sent to kitchen, please delete it and readd it. \n 已选项中包含已送厨菜品，若要修改已送厨菜品，请删除后重新添加",{ position: "top center", className:"info"});
            $('#button-group .btn').not(selectors[0]).attr('disabled', true);
        } else {
            $('#button-group .btn').not(selectors[0]).attr('disabled', false);
        }

        // only enable when all selected items are printed
        if ( $('#order-component li.selected').length > 0 && $('#order-component li.selected').length == $('#order-component li.selected.is-print').length) {
            $(selectors[1]).attr('disabled', false);
        } else {
            $(selectors[1]).attr('disabled', true);
        }
    }


    $('#pay-btn').on('click', function () {
        // if message exist save message
        // $('#send-to-kitchen-btn').trigger('click');

        window.location = "<?php echo $this->Html->url(array('controller' => 'pay', 'action' => 'index', 'table' => $table, 'type' => $type)); ?>";
    });


    $('#change-price-btn').on('click', function() {
        var selected_item_id_list = getSelectedItem();

        if (selected_item_id_list.length == 0) {
            // alert("No item selected");
             $.notify("No item selected 没有选择菜",  { position: "top center", className:"warn"});
            return false;
        }

        //popup an input for new price
        $('#change-price-component-modal').modal('hide').remove();
        var changePriceComponent = ChangePriceComponent.init();
        $('body').append(changePriceComponent);

    });


    $('body').on('click', '#change-price-component-save', function() {
        var selected_item_id_list = getSelectedItem();

        var price = $('#change-price-component-price').val();
        price = Math.round(parseFloat(price) * 100) / 100;

        $.ajax({
            url: "<?php echo $this->Html->url(array('controller' => 'order', 'action' => 'changePrice')); ?>",
            method: "post",
            data: {
                selected_item_id_list: selected_item_id_list,
                price: price,
                table: "<?php echo $table ?>",
                type: "<?php echo $type ?>",
                order_no: $("#Order_no").val()
            },
            success: function(html) {
                // $(".summary_box").html(html);
                renderOrder();
                $('#change-price-component-modal .close').trigger('click');
            }
        });
    });

    $('body').on('click', '#urge-btn', function() {
        var selected_item_id_list = getSelectedItem();

        if (selected_item_id_list.length == 0) {
            // alert("No item selected");
             $.notify("No item selected 没有选择菜",  { position: "top center", className:"warn"});
            return false;
        }

        $.ajax({
            url: "<?php echo $this->Html->url(array('controller' => 'order', 'action' => 'urgeItem')); ?>",
            method: "post",
            data: {
                selected_item_id_list: selected_item_id_list,
                table: "<?php echo $table ?>",
                type: "<?php echo $type ?>",
                order_no: $("#Order_no").val()
            },
            success: function(html) {
                // $(".summary_box").html(html);
                renderOrder();
            }
        });
    });


    $('body').on('click', '#quantity-btn', function() {
        var selected_item_id_list = getSelectedItem();

        if (selected_item_id_list.length == 0) {
            // alert("No item selected");
            $.notify("No item selected 没有选择菜",{ position: "top center",className:"warn" });
            return false;
        }

        $('#change-quantity-component-modal').modal('hide').remove();
        var changeQuantityComponent = ChangeQuantityComponent.init();
        $('body').append(changeQuantityComponent);
    });

    $('body').on('click', '#change-quantity-component-save', function() {

        var quantity = $('input[name="quantity"]').val();
        if(quantity == ''){
        	alert("Please input quantity!");
        	$('input[name="quantity"]').focus();
        	return;
        }
        quantity = Math.round(parseInt(quantity));
    	  
        var selected_item_id_list = getSelectedItem();

        $.ajax({
            url: "<?php echo $this->Html->url(array('controller' => 'order', 'action' => 'changeQuantity')); ?>",
            method: "post",
            data: {
                selected_item_id_list: selected_item_id_list,
                quantity: quantity,
                table: "<?php echo $table ?>",
                type: "<?php echo $type ?>",
                order_no: $("#Order_no").val()
            },
            success: function(html) {
                // $(".summary_box").html(html);
                $('#change-quantity-component-modal .close').trigger('click');
                renderOrder();
            }
        });
    });

    $('body').on('click', '#edit-phone-component-save', function() {

        var phone = $('input[name="phone"]').val();

        $.ajax({
            url: "<?php echo $this->Html->url(array('controller' => 'order', 'action' => 'editPhone')); ?>",
            method: "post",
            data: {
                phone: phone,
                order_no: $("#Order_no").val()
            },
            success: function(html) {
                $('#edit-phone-component-modal .close').trigger('click');
                //renderOrder();
                window.location.reload();
            }
        });
    });


    function renderOrder(callback) {
        $.ajax({
            url: "<?php echo $this->Html->url(array('controller' => 'order', 'action' => 'summarypanel', $table, $type)); ?>",
            method: "post",
            success: function(html) {

                $(".summary_box").html(html);
                $(".summary_box").removeClass('load1 csspinner');
                // $('#change-quantity-component-modal .close').trigger('click');
                if (typeof callback == "function") {
                    callback();
                }
            },
            beforeSend: function () {
                $(".summary_box").addClass('load1 csspinner');
            }
        })
    }


    $('body').on('click', '.selected-extra-item', function() {
    	
    	if($(this).has('button').length){
        $(this).remove();
      }
       
    });
    

</script>
