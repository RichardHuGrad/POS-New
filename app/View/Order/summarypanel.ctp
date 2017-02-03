<div id="Order_id" style="display:none"><?php echo $Order_detail['Order']['id']; ?></div>

 <div class="clearfix marginB15 cashierbox" style="display:none">
    <!-- <div class="pull-left marginR5">
        <?php if ($cashier_detail['Cashier']['image']) { ?>
            <?php echo $this->Html->image(TIMB_PATH."timthumb.php?src=".CASHIER_IMAGE_PATH . $cashier_detail['Cashier']['image']."&h=60&w=60&&zc=4&Q=100", array('class'=>'img-circle img-responsive')); ?>
        <?php } else { ?>
            <?php echo $this->Html->image(TIMB_PATH."timthumb.php?src=".TIMB_PATH . 'no_image.jpg'."&h=60&w=60&&zc=4&Q=100", array('class'=>'img-circle img-responsive'));  ?>
        <?php } ?>
    </div> -->
    <!-- <div class="pull-left marginL5 clearfix">
        <div class="txt16 marginB5 marginT5"><?php echo ucfirst($cashier_detail['Cashier']['firstname'])." ".$cashier_detail['Cashier']['lastname']; ?></div>
        <div class="txt15"><?php echo str_pad($cashier_detail['Cashier']['id'], 4, 0, STR_PAD_LEFT); ?></div>
    </div> -->
</div>

<div class="clearfix marginB15 cashierbox">
    <div class="order-summary-indent clearfix">
    </div>

    <div class="clearfix">
        <button class="btn btn-info" id="select-all"><strong>全选</strong></button>
        <button class="btn btn-info" id="select-unprint"><strong>未送厨</strong></button>
        <button class="btn btn-info" id="select-printed"><strong>已送厨</strong></button>
        <button class="btn btn-info" id="select-revert"><strong>反选</strong></button>
    </div>
</div>

<div class="bgwhite clearfix">
    <?php if(empty($Order_detail) OR !$Order_detail['Order']['discount_value']) { ?>
        <div class="padding10 adddoscount">
            Add Discount 加入折扣  <i class="fa fa-plus-circle pull-right add-discount <?php
if(empty($Order_detail) or empty(@$Order_detail['OrderItem'])) echo 'disabled'
    ?>" aria-hidden="true"></i>
        </div>
    <?php }?>

    <?php if(!empty($Order_detail) and !$Order_detail['Order']['discount_value']) { ?>
    <div class="subtotalwrap discount_view" style="display:none;">
        <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="fix_discount" style="font-size:11px;">Fix Discount</label>
                        <input type="text" id="fix_discount" required="required" class="form-control discount_section" maxlength="5"  name="fix_discount">                                                    
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="discount_percent" style="font-size:11px;">Discount in %</label>
                        <input type="text" id="discount_percent" required="required" class="form-control discount_section" maxlength="5"   name="discount_percent">                                                    
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="promocode" style="font-size:11px;">Promo Code</label>
                        <input type="text" id="promocode" required="required" class="form-control discount_section" maxlength="200" name="promocode">                                                    
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="AdminTableSize">&nbsp;</label>
                            <a class="btn btn-primary btn-wide pull-right" id="apply-discount" href="javascript:void(0)">Apply <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                
        </div>
    </div>
    <?php } ?>
    <div class="subtotalwrap">
        <div class="row">
        	<!-- Modified by Yishou Liao @ Nov 25 2016 -->
            <div class="col-xs-8 col-sm-8 col-md-8">Subtotal 小计 </div>
            <div class="col-xs-4 col-sm-4 col-md-4 text-right"><strong>$<?php 

            if(!empty($Order_detail) and !empty(@$Order_detail['OrderItem'] )) {
                echo number_format($Order_detail['Order']['subtotal'], 2);
            } else {
                echo '0.00';
            }
			?>
            </strong>
            <!-- End -->
            </div>
		</div><!-- Modified by Yishou Liao @ Nov 25 2016 -->
    </div>
    
    <?php if(!empty($Order_detail) and $Order_detail['Order']['discount_value'])  {
            ?>
            <div class="subtotalwrap">
                <div class="row">
                    <div class="col-xs-8 col-sm-8 col-md-8">Discount 折扣</div>
                    <div class="col-xs-4 col-sm-4 col-md-4 text-right">
                    <strong>
                        $<?php echo number_format($Order_detail['Order']['discount_value'], 2) ;
                                if($Order_detail['Order']['percent_discount']) {
                                    echo "<br/><span class='txt12'> ".$Order_detail['Order']['promocode']." (".$Order_detail['Order']['percent_discount']."%)</span>";
                                }
                        ?> 
                        <a aria-hidden="true" class="fa fa-times pull-right remove_discount" order_id="<?php echo $Order_detail['Order']['id']; ?>" href="javascript:void(0)"></a>                       
                    </strong></div>
                            
                </div>
            </div>
            
            <div class="subtotalwrap"><!-- Modified by Yishou Liao @ Nov 25 2016 -->
                <div class="row"><!-- Modified by Yishou Liao @ Nov 25 2016 -->
                    <div class="col-xs-8 col-sm-8 col-md-8">After Discount 打折后: </div><div class="col-xs-4 col-sm-4 col-md-4 text-right"><strong>$<?php if(!empty($Order_detail) and !empty(@$Order_detail['OrderItem'] )) echo number_format(max($Order_detail['Order']['subtotal'] - $Order_detail['Order']['discount_value'], 0), 2); else echo '0.00'; ?></strong> </div>
        
                </div>
            </div><!-- Modified by Yishou Liao @ Nov 25 2016 -->
    
        <?php
        }
        ?>

    <div class="subtotalwrap"><!-- Modified by Yishou Liao @ Nov 25 2016 -->
    	<div class="row"><!-- Modified by Yishou Liao @ Nov 25 2016 -->
            <div class="col-xs-8 col-sm-8 col-md-8">Taxes 税 (<?php if(!empty($Order_detail) and !empty(@$Order_detail['Order'] )) echo $Order_detail['Order']['tax'] ?>%): </div><div class="col-xs-4 col-sm-4 col-md-4 text-right"><strong>$<?php if(!empty($Order_detail) and !empty(@$Order_detail['OrderItem'] )) echo number_format($Order_detail['Order']['tax_amount'], 2); else echo '0.00'; ?></strong> </div>

        </div>
    </div>
    <div class="subtotalwrap">
        <div class="row">
            <div class="col-xs-8 col-sm-8 col-md-8">Total 总额: </div><div class="col-xs-4 col-sm-4 col-md-4 text-right"><strong>$<?php if(!empty($Order_detail) and !empty(@$Order_detail['OrderItem'] )) echo number_format($Order_detail['Order']['total'], 2); else echo '0.00'; ?></strong></div>

          <!--   <div class="col-xs-6 col-sm-6 col-md-6">
                <textarea name="" cols="" rows="" class="form-control" placeholder="Message" id="Message"><?php if(!empty($Order_detail) and !empty(@$Order_detail['OrderItem'] )) echo $Order_detail['Order']['message'] ?></textarea>
            </div> -->
        </div>
    </div>

    <div class="clearfix subtotal-btn-wrap">

    </div>
</div>
<!-- Modified by Yishou Liao -->
<!-- <input name="Order_Item" id="Order_Item" value="" /> -->
<!-- End -->
<!-- Modified by Yishou Liao @ Dec 12 2016 -->
<input type="hidden" name="Order_no" id="Order_no" value="<?php echo @$Order_detail['Order']['order_no']; ?>" />


<script id="item-component" type="text/template">
    <li class="col-md-12 col-sm-12 col-xs-12 order-item" id="{0}" data-order-item-id="{1}" data-comb-id="{2}" data-selected-extras='{3}' data-is-print='{4}' data-special='{5}'>
        <div class="col-md-1 col-sm-1 col-xs-1 item-quantity">{6}</div>
        <div class="col-md-9 col-sm-9 col-xs-8">
            <div class="col-md-12 col-sm-12 col-xs-12 item-name-en">{7}</div>
            <div class="col-md-12 col-sm-12 col-xs-12 item-name-zh">{8}</div>
            <div class="col-md-12 col-sm-12 col-xs-12 item-selected-extra">{9}</div>
            <div class="col-md-12 col-sm-12 col-xs-12 item-special">Special: {5}</div>
        </div>
        <div class="col-md-2 col-sm-2 col-xs-3 item-price">{10}</div>
    </li>
</script>


<script>

	

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

        function loadOrder(order_no) {

            var tempOrder = new Order(order_no);
            <?php
                if (!empty($Order_detail['OrderItem'])) {
                ?>
                    var percent_discount = '<?php echo $Order_detail['Order']['percent_discount'] ;?>';
                    var fix_discount = '<?php echo $Order_detail['Order']['fix_discount']; ?>';

                    // console.log(percent_discount);
                    // console.log(fix_discount);
                    if (percent_discount != 0) {
                        tempOrder.discount = {"type": "percent", "value": percent_discount}
                        // console.log(tempOrder.discount)
                    } else if (fix_discount != 0) {
                        tempOrder.discount = {"type": "fixed", "value": fix_discount}
                    }
                <?php

                    $i = 0;
                    foreach ($Order_detail['OrderItem'] as $key => $value) {

                        $selected_extras_name = [];
                    // if ($value['all_extras']) {
                        $extras = json_decode($value['all_extras'], true);
                        $selected_extras = json_decode($value['selected_extras'], true);

                        // prepare extras string
                        $selected_extras_id = [];
                        if (!empty($selected_extras)) {
                            foreach ($selected_extras as $k => $v) {
                                $selected_extras_name[] = $v['name'];
                                $selected_extras_id[] = $v['id'];
                            }
                        }
                    // }
                ?>
                        var temp_item = new Item(
                                item_id = '<?php echo $i ?>',
                                image= '<?php if ($value['image']) { echo $value['image']; } else { echo 'no_image.jpg';};?>',
                                name_en = '<?php echo $value['name_en']; ?>',
                                name_zh = '<?php echo $value['name_xh']; ?>',
                                selected_extras_name = '<?php echo implode(",", $selected_extras_name); ?>', // can be extend to json object
                                price = '<?php echo $value['price'] ?>',
                                extras_amount = '<?php echo $value['extras_amount'] ?>',
                                quantity = '<?php echo $value['qty'] > 1 ? intval($value['qty']) : 1 ?>',
                                order_item_id = '<?php echo $value['id'] ?>',
                                state = "keep", 
                                shared_suborders = null,
                                assigned_suborder = null,
                                is_takeout = '<?php echo $value["is_takeout"] ?>',
                                comb_id = '<?php echo $value["comb_id"] ?>',
                                selected_extras_json = '<?php echo $value['selected_extras'] ?>',
                                is_print = '<?php echo $value['is_print']?>',
                                special = '<?php echo  $value["special_instruction"]?>');

                        tempOrder.addItem(temp_item);
                <?php
                        $i++;
                    }
                ?>

            <?php 
                }
            ?>
            return tempOrder;
        }
        



        var ItemComponent = (function() {

            var createDom = function(item) {
                var itemComponent = $($("#item-component").html().format('order-item-' + item.item_id, item.order_item_id, item.comb_id, item.selected_extras_json, item.is_print, item.special, item.quantity, item.name_en,item.name_zh, item.selected_extras_name, item.price));

                // console.log(item);
                if (item.is_print == 'Y') {
                    itemComponent.addClass('is-print');
                }

                if (!$.trim(item.special)) {
                    itemComponent.find(".item-special").hide();
                }

                return itemComponent;
            }

            var bindEvent = function(itemComponent) {
                itemComponent.on('click', function () {
                    $(this).toggleClass('selected');
                    // alert('test');
                    
                });
            }

            var init = function(item) {
                // createDom(item);
                var itemComponent = createDom(item)
                bindEvent(itemComponent);

                return itemComponent;
            }

            return {
                init: init
            }; 
        })();


        var OrderComponent = (function() {
            var template = `
                <ul class="col-md-12 col-sm-12 col-xs-12" id="order-component">
                </ul>
            `;
            var orderComponent = $(template);

            var createDom = function(order, ItemComponent) {
                for (var i = 0; i < order.items.length; ++i) {
                    var item = order.items[i];
                    orderComponent.append(ItemComponent.init(item))
                }
            }

            var init = function (order, ItemComponent) {
                createDom(order, ItemComponent);

                return orderComponent
            }

            return {
                init: init
            }
        })();



        var order;

        <?php if (isset($Order_detail['Order']['order_no'])) { ?>

    		var order_no = <?php echo $Order_detail['Order']['order_no'] ?>;
    		order = loadOrder(order_no);

    		// console.log(order);

    		$('.order-summary-indent').append(OrderComponent.init(order, ItemComponent))

        <?php } ?>

        
        $('#select-all').on('click', function() {
            $('#order-component li').each(function() {
                if(!$(this).hasClass('selected')) {
                    $(this).addClass('selected');
                }
            });
        });

        $('#select-revert').on('click', function() {
            $('#order-component li').each(function() {
                if($(this).hasClass('selected')) {
                    $(this).removeClass('selected');
                } else {
                    $(this).addClass('selected');
                }
            });
        });

        $('#select-printed').on('click', function() {
            $('#order-component li.is-print').each(function() {
                if(!$(this).hasClass('selected')) {
                    $(this).addClass('selected');
                }
            });
        });


        $('#select-unprint').on('click', function() {
            $('#order-component li').each(function() {
                if(!$(this).hasClass('is-print') && !$(this).hasClass('selected')) {
                    $(this).addClass('selected');
                }
            });
        });

	

</script>

