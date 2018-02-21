<header class="product-header">
    <?php echo $this->element('navbar'); ?>
</header>
<div class="merge container-fluid">
    <div class="clearfix cartwrap-wrap">
    </div>
    <div class="order-wrap">
        <?php echo $this->Session->flash(); ?>
        <div class="col-md-3 col-sm-3 col-xs-12 order-left">
            <h2><?php echo __('Order No.'); ?><?php
                //Modified by Yishou Liao @ Oct 14 2016.
                for ($i = 0; $i < count($Order_detail); $i++) {
                    echo " " . $Order_detail[$i]['Order']['order_no'];
                };
                //End.
                ?>, <?php echo __('Table'); ?> <?php echo (($type == 'D') ? '[[Dinein]]' : (($type == 'T') ? '[[Takeout]]' : (($type == 'W') ? '[[Delivery]]' : ''))); ?>#<?php echo $table; ?>

                <?php
                echo __('and');
                for ($i = 0; $i < count($tablemerge); $i++) {
                    if ($i > 0) {
                        echo "#" . $tablemerge[$i] . " ";
                    };
                };
                echo __("merge");

                ?>

            </h2>

            <div class="paid-box">
                <div class="checkbox-btn">
                    <input type="checkbox" value="value-1" id="rc1" name="rc1" <?php
                //Modified by Yishou Liao @ Oct 14 2016.
                for ($i = 0; $i < count($Order_detail); $i++) {
                    if ($Order_detail[$i]['Order']['table_no'] == $table) {
                        $table_status = $Order_detail[$i]['Order']['table_status'];
                    };
                };
                if ($table_status == 'P')
                    echo "checked='checked'";
                //End.
                ?>/>
                    <label for="rc1" disabled><?php echo __('Paid'); ?></label>
                </div>
            </div>

            <div class="avoid-this text-center reprint"><button type="button" class="submitbtn"><?php echo __('Print Receipt'); ?></button></div>
            <div class="order-summary">
                <h3><?php echo __('Order Summary'); ?></h3>
                <div class="order-summary-indent clearfix">
                    <ul>
<?php
for ($x = 0; $x < count($Order_detail); $x++) {//MOdified by Yishou Liao @ Oct 14 2016.
    echo "#" . $Order_detail[$x]['Order']['table_no'] . " BILL";
    if (!empty($Order_detail[$x]['OrderItem'])) {
        foreach ($Order_detail[$x]['OrderItem'] as $key => $value) {
            # code...
            $selected_extras_name = [];
            // if ($value['all_extras']) {
            //     $extras = json_decode($value['all_extras'], true);
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
                                    <li class="clearfix">
                                        <div class="row">
                                            <div class="col-md-9 col-sm-8 col-xs-8">
                                                <div class="pull-left titlebox1">
                                                    <!-- to show name of item -->
                                                    <div class="less-title"><?php echo $value['name_en'] . "<br/>" . $value['name_xh']; ?></div>

                                                    <!-- to show the extras item name -->
                                                    <div class="less-txt"><?php echo implode(",", $selected_extras_name); ?></div>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-4 col-xs-4 text-right price-txt">
                                                $<?php echo ($value['price'] + $value['extras_amount']); ?><?php echo $value['qty'] > 1 ? "x" . $value['qty'] : "" ?>
                                            </div>
                                        </div>
                                    </li>
            <?php
        }
    }//Modified by Yishou Liao @ Oct 14 2016
}
?>
                    </ul>
                </div>

            </div>
        </div>
        <div class="col-md-3 col-md-3 col-xs-12" id="mid-section">

        </div>

        <div class="col-md-6 col-sm-6 col-xs-12 RIGHT-SECTION">
            <div class="clearfix total-payment">
                <ul>
                    <li class="clearfix">
                        <div class="row">
                            <div class="col-md-3 col-sm-4 col-xs-4 sub-txt"><?php echo __('Subtotal'); ?> <?php
							$table_discount_value = 0;
							for ($i = 0; $i < count($Order_detail); $i++) {
	                            $table_discount_value += $Order_detail[$i]['Order']['discount_value'];
                                };
							?> </div>
                            <div class="col-md-3 col-sm-4 col-xs-4 sub-price">$
                                <?php
                                $subtotal = 0;
                                for ($i = 0; $i < count($Order_detail); $i++) {
                                    $subtotal += $Order_detail[$i]['Order']['subtotal'];
                                };
                                echo round($subtotal, 2);
                                ?>
                            </div>

                                <?php
                                for ($i = 0; $i < count($Order_detail); $i++) {
                                    if ($Order_detail[$i]['Order']['table_no'] == $table) {
                                        $table_status = $Order_detail[$i]['Order']['table_status'];
                                        // $table_discount_value = $Order_detail[$i]['Order']['discount_value'];
                                    };
                                };
                                if ($table_status != 'P' and $table_discount_value == 0) {
                                    ?>
                            <?php } ?>
                        </div>
                    </li>


                    <?php
                    if ($table_discount_value) {                       
                        ?>
                        <li class="clearfix">
                            <div class="row">
                                    <?php
                                    // show discount code here
                                    ?>
                                <div class="col-md-3 col-sm-4 col-xs-4 sub-txt"><?php echo __('Discount'); ?></div>
                                <div class="col-md-3 col-sm-4 col-xs-4 sub-price">
                                    $<?php
                        //Modified by Yishou Liao @ Nov 19 2016.
						$table_discount_value = 0;
                        for ($i = 0; $i < count($Order_detail); $i++) {
                            //if ($Order_detail[$i]['Order']['table_no'] == $table) {//Modified by Yishou Liao @ Nov 19 2016
                                $table_discount_value += $Order_detail[$i]['Order']['discount_value'];
                            //}; //End
                        };
                        echo round($table_discount_value, 2);
                        ?>

                                </div>

                            </div>
                        </li>

<li class="clearfix">
    <div class="row">

        <div class="col-md-3 col-sm-4 col-xs-4 sub-txt"><?php echo __('After Discount'); ?>: </div>
        <div class="col-md-3 col-sm-4 col-xs-4 sub-price">$
<?php

$after_discount = 0;
for ($i = 0; $i < count($Order_detail); $i++) {
    $temp_after_discount = max($Order_detail[$i]['Order']['subtotal'] - $Order_detail[$i]['Order']['discount_value'], 0);

    $after_discount += $temp_after_discount;
};
echo round($after_discount, 2);

?></div>
</li>

                                    <?php
                                }
                                ?>

                    <li class="clearfix">
                        <div class="row">
                            <div class="col-md-3 col-sm-4 col-xs-4 sub-txt"><?php echo __('Tax'); ?> (
<?php
for ($i = 0; $i < count($Order_detail); $i++) {
    if ($Order_detail[$i]['Order']['table_no'] == $table) {
        $table_tax = $Order_detail[$i]['Order']['tax'];
    };
};
echo $table_tax;

?>%)
							</div>
							
                            <div class="col-md-3 col-sm-4 col-xs-4 sub-price">$
<?php
$tax_amount = 0;
for ($i = 0; $i < count($Order_detail); $i++) {
    $tax_amount += $Order_detail[$i]['Order']['tax_amount'];
};
echo round($tax_amount, 2);
?>
							</div>
                        </div>
                    </li>

<!-- default tip -->
<?php if(@$Order_detail[0]['Order']['default_tip_rate']>0){    ?>
                    <li class="clearfix">
                        <div class="row">
                            <div class="col-md-3 col-sm-4 col-xs-4 sub-txt"><?php echo __('Tip'); ?> (
						<?php
						for ($i = 0; $i < count($Order_detail); $i++) {
						    if ($Order_detail[$i]['Order']['table_no'] == $table) {
						        $table_default_tip_rate = $Order_detail[$i]['Order']['default_tip_rate'];
						    };
						};
						echo $table_default_tip_rate;

						?>%)</div>
                            <div class="col-md-3 col-sm-4 col-xs-4 sub-price">$
						<?php
						$tip_amount = 0;
						for ($i = 0; $i < count($Order_detail); $i++) {
						    $tip_amount += $Order_detail[$i]['Order']['default_tip_amount'];
						};
						echo round($tip_amount, 2);
						?></div>
                        </div>
                    </li>
<?php }  ?>        
<!-- end default tip -->

                    <li class="clearfix">
                        <div class="row">
                            <div class="col-md-3 col-sm-4 col-xs-4 sub-txt"><?php echo __('Total'); ?></div>
                            <div class="col-md-3 col-sm-4 col-xs-4 sub-price total_price" alt="<?php

                                $total = 0;
                                for ($i = 0; $i < count($Order_detail); $i++) {
                                    $total += $Order_detail[$i]['Order']['total'];
                                };
                                echo round($total, 2);
                                ?>">$<?php

                                $total = 0;
                                for ($i = 0; $i < count($Order_detail); $i++) {
                                    $total += $Order_detail[$i]['Order']['total'];
                                };
                                echo round($total, 2);
                                ?></div>
                        </div>
                    </li>
                    <?php

                    for ($i = 0; $i < count($Order_detail); $i++) {
                        if ($Order_detail[$i]['Order']['table_no'] == $table) {
                            $table_status = $Order_detail[$i]['Order']['table_status'];
                        };
                    };
                    if ($table_status == 'P') {
                        ?>
                        <li class="clearfix">
                            <div class="row">
                                <div class="col-md-3 col-sm-3 col-xs-3 sub-txt"><?php echo __('Received'); ?></div>
                                <div class="col-md-2 col-sm-2 col-xs-2 sub-price received_price">$<?php echo $Order_detail['Order']['paid']; ?></div>

                                <div class="col-md-2 col-sm-2 col-xs-2 sub-price cash_price"><?php echo __('Cash'); ?>: $<?php echo $Order_detail['Order']['cash_val']; ?></div>
                                <div class="col-md-2 col-sm-2 col-xs-2 sub-price card_price"><?php echo __('Card'); ?>: $<?php echo $Order_detail['Order']['card_val']; ?></div>
                                <div class="col-md-3 col-sm-3 col-xs-3 sub-price membercard_price"><?php echo __('Member'); ?>: $<?php echo $Order_detail['Order']['membercard_val']; ?></div>
                            </div>
                        </li>

                <?php if ($Order_detail['Order']['change']) { ?>
                        <li class="clearfix">
                            <div class="row">
                                <div class="col-md-3 col-sm-4 col-xs-4 sub-txt change_price_txt"><?php echo __('Change'); ?></div>
                                <div class="col-md-3 col-sm-4 col-xs-4 sub-price change_price">$<?php echo $Order_detail['Order']['change']; ?></div>
                            </div>
                        </li>
                <?php } ?>

                        <li class="clearfix">
                            <div class="row">
                                <div class="col-md-3 col-sm-4 col-xs-4 sub-txt"><?php echo __('Tip'); ?>Tip 小费</div>
                                <div class="col-md-3 col-sm-4 col-xs-4 sub-price tip_price">$<?php echo $Order_detail['Order']['tip']; ?></div>
                            </div>
                        </li>
                        <?php
                    } else {
                        ?>
                        <li class="clearfix">
                            <div class="row">
                                <div class="col-md-3 col-sm-3 col-xs-3 sub-txt"><?php echo __('Received'); ?></div>
                                <div class="col-md-2 col-sm-2 col-xs-2 sub-price received_price">$00.00</div>

                                <div class="col-md-2 col-sm-2 col-xs-2 sub-price cash_price"><?php echo __('Cash'); ?>: $00.00</div>
                                <div class="col-md-2 col-sm-2 col-xs-2 sub-price card_price"><?php echo __('Card'); ?>: $00.00</div>
                                <div class="col-md-3 col-sm-3 col-xs-3 sub-price membercard_price"><?php echo __('Member'); ?>: $00.00</div>
                            </div>
                        </li>
                        <li class="clearfix">
                            <div class="row">
                                <div class="col-md-3 col-sm-4 col-xs-4 sub-txt change_price_txt"><?php echo __('Remaining'); ?></div>
                                <div class="col-md-3 col-sm-4 col-xs-4 sub-price change_price">$00.00</div>
                            </div>
                        </li>
                        <li class="clearfix">
                            <div class="row">
                                <div class="col-md-3 col-sm-4 col-xs-4 sub-txt"><?php echo __('Tip'); ?></div>
                                <div class="col-md-3 col-sm-4 col-xs-4 sub-price tip_price">$00.00</div>
                            </div>
                        </li>
                        <?php
                    }
                    ?>
                </ul>
            </div>


                <div class="card-bot clearfix text-center">
                    <button type="button" class="btn btn-danger select_card" id="membercard"> <?php echo $this->Html->image("card.png", array('alt' => "member card")); ?> <?php echo __('Member'); ?></button>
                    <button type="button" class="btn btn-danger select_card" id="card"> <?php echo $this->Html->image("card.png", array('alt' => "card")); ?> <?php echo __('Card'); ?></button>
                    <button type="button" class="btn btn-danger select_card"  id="cash"><?php echo $this->Html->image("cash.png", array('alt' => "cash")); ?> <?php echo __('Cash'); ?></button>

                    <!-- <button type="button" class="btn btn-warning select_card"  id="tip"><?php echo $this->Html->image("cash.png", array('alt' => "tip")); ?> Tip 小费</button> -->


                    <button type="button" class="btn btn-success card-ok"  id="submit"><?php echo $this->Html->image("right.png", array('alt' => "right")); ?> <?php echo __('Confirm'); ?></button>
                    <input type="hidden" id="selected_card" value="" />
                    <input type="hidden" id="membercard_id" name="membercard_id" value="" />
                    <input type="hidden" id="membercard_val" name="membercard_val" value="" />
                    <input type="hidden" id="card_val" name="card_val" value="" />
                    <input type="hidden" id="cash_val" name="cash_val" value="" />
                    <input type="hidden" id="tip_val"name="tip" value="" />
                    <input type="hidden" id="tip_paid_by"name="tip_paid_by" value="" />
                </div>


<?php
//Modified by Yishou Liao @ Oct 14 2016.
for ($i = 0; $i < count($Order_detail); $i++) {
    if ($Order_detail[$i]['Order']['table_no'] == $table) {
        $table_status = $Order_detail[$i]['Order']['table_status'];
    };
};
if ($table_status != 'P') {
//End.
    ?>
                <div class="card-wrap"><input type="text" id="screen" buffer="" lastinput="" maxlength="13" readonly></div>
                <div class="card-indent clearfix">
                    <ul>
                        <li>1</li>
                        <li>2</li>
                        <li>3</li>

                        <li>4</li>
                        <li>5</li>
                        <li>6</li>

                        <li>7</li>
                        <li>8</li>
                        <li>9</li>

                        <li>0</li>
                        <li id="Dot">.</li>
                        <li class="txt" id="Back">Back</li>
                        
                        <li class="txt" id="Default"><?php echo __('Default'); ?></li>
                        <li class="clear-txt" id="Clear"><?php echo __('Clear'); ?></li>
                        <li class="enter-txt" id="Enter"><?php echo __('Enter'); ?></li>
                    </ul>
                </div>

<?php } ?>
        </div>
    </div>
</div>

<?php
echo $this->Html->css(array('merge.css'));
echo $this->Html->script(array('jquery.min.js', 'bootstrap.min.js', 'jquery.mCustomScrollbar.concat.min.js', 'barcode.js', 'epos-print-5.0.0.js', 'fanticonvert.js', 'notify.min.js'));
echo $this->fetch('script');
?>

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

            $(document).on('click', '.reprint', function () {
                    //Print ele4 with custom options
            var Order_print = new Array();
            var oder_no = "";
            <?php
                for ($i = 0; $i < count($Order_detail); $i++) {
                    ?>

                                oder_no += "<?php echo '#' . $Order_detail[$i]['Order']['order_no'] ?>" + " ";
                    <?php
                };
                //End.
                ?>
            var y = 0;
            <?php
            for ($x = 0; $x < count($Order_detail); $x++) {//Modified by Yishou Liao @ Oct 16 2016.
                if (!empty($Order_detail[$x]['OrderItem'])) {
                    ?>
                                Order_print.push(Array());
                    <?php
                    foreach ($Order_detail[$x]['OrderItem'] as $key => $value) {
                        ?>
                                    Order_print[y].push('<?php echo implode("*", $value) . "*" . $Order_detail[$x]["Order"]["table_no"] . " BILL"; ?>'.split("*"));
                    <?php }; ?>
                                y++;
                <?php };
            }; ?>


                    var order_ids = [];
                    <?php
                        foreach ($order_id_merge as $o) {
                    ?>
                            order_ids.push(parseInt('<?php echo $o?>'));
                    <?php
                        }
                     ?>
                    $.ajax({
                        url: "<?php echo $this->Html->url(array('controller' => 'merge', 'action' => 'printBill')); ?>",
                        method:"post",
                        data:{
                            type: "<?php echo $type; ?>",
                            logo_name:"../webroot/img/logo.bmp",
                            order_ids: order_ids,
                        },
                        success:function(html) {

                        }
                    });
                    //End.

            });



            $(document).ready(function () {
                $(".select_card").click(function () {
                	$(".select_card").removeClass("active")
                	$(this).addClass("active")
                	var type = $(this).attr("id");
					if (type == 'card') {
						var card_val = $("#card_val").val() ? parseFloat($("#card_val").val()) * 100 : 0;
						$("#screen").attr('buffer', card_val);
						$("#screen").val($("#card_val").val());
					} else if (type == 'membercard') {
						var card_val = $("#membercard_val").val() ? parseFloat($("#membercard_val").val()) * 100 : 0;
						$("#screen").attr('buffer', card_val);
						$("#screen").val($("#membercard_val").val());
		                $("#member_search_next").val('mbm_pay_select');
		                $('#modal_member_search').modal('show');

		                $('#mbm_pay_order_paid').val($(".received_price").attr("amount"));
		                $('#mbm_pay_order_total').val($(".total_price").attr("alt"));
					} else if (type == 'cash') {
						var cash_val = $("#cash_val").val() ? parseFloat($("#cash_val").val()) * 100 : 0;
						$("#screen").attr('buffer', cash_val);
						$("#screen").val($("#cash_val").val());
					} else {
						var tip_val = $("#tip_val").val() ? parseFloat($("#tip_val").val()) * 100 : 0;
						$("#screen").attr('buffer', tip_val);
						$("#screen").val($("#tip_val").val());
					}
					
					$("#selected_card").val(type);
				})
            
                $(".select_tip").click(function () {
                    $(".select_card").removeClass("active");
                        $(this).toggleClass("active");
                        var val = $("#tip_val").val() ? parseFloat($("#tip_val").val()) * 100 : 0;
                        $("#screen").attr('buffer', val);
                        $("#screen").val($("#tip_val").val());
             });
                    
                //Modified by Yishou Liao @ Oct 16 2016
                $("#submit").click(function () {
                    if ($("#selected_card").val()) {
                        if (parseFloat($(".change_price").attr("amount")) >= 0) {
                            // submit form for complete payment process
                            $.ajax({
                                url: "<?php echo $this->Html->url(array('controller' => 'merge', 'action' => 'completeMergeOrder', $table, $type)); ?>",
                                method: "post",
                                data: {
                                    pay: $(".received_price").attr("amount"),
                                    paid_by: $("#selected_card").val(),
                                    change: $(".change_price").attr("amount"),
                                    table: "<?php echo $table ?>",
                                    table_merge: "<?php echo implode(",", $tablemerge); ?>",
                                    type: "<?php echo $type ?>",
                                    main_order_id:"<?php
                                                    $main_order_id = "";
                                                    for ($i = 0; $i < count($Order_detail); $i++) {
                                                        if ($Order_detail[$i]['Order']['table_no'] == $table) {
                                                            $main_order_id = $Order_detail[$i]['Order']['id'];
                                                        };
                                                    };
                                                    echo $main_order_id;
                                                    ?>",
                                    order_ids: "<?php
                                                $order_id = "";
                                                for ($i = 0; $i < count($Order_detail); $i++) {
                                                    $order_id .= $Order_detail[$i]['Order']['id'] . ",";
                                                };
                                                $order_id = substr($order_id, 0, (strlen($order_id) - 1));
                                                echo $order_id;
                                                ?>",
                                    membercard_id: $("#membercard_id").val(),
                                    membercard_val: $("#membercard_val").val(),
                                    card_val: $("#card_val").val(),
                                    cash_val: $("#cash_val").val(),
                                    tip_val: $("#tip_val").val(),
                                    tip_paid_by: $("#tip_paid_by").val()
                                },
                                success: function (html) {
                                    $(".alert-warning").hide();
                                    // $(".reprint").trigger("click");

                                    var order_ids = [];
                                    <?php
                                        foreach ($order_id_merge as $o) {
                                    ?>
                                            order_ids.push(parseInt('<?php echo $o?>'));
                                    <?php
                                        }
                                     ?>
                                    $.ajax({
                                        url: "<?php echo $this->Html->url(array('controller' => 'merge', 'action' => 'printReceipt')); ?>",
                                        method:"post",
                                        data:{
                                            type: "<?php echo $type; ?>",
                                            logo_name:"../webroot/img/logo.bmp",
                                            order_ids: order_ids,
                                        },
                                        success:function(html) {
                                            window.location = "<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'dashboard')); ?>";
                                        }
                                    });
                                },
                                beforeSend: function () {
                                    $(".RIGHT-SECTION").addClass('load1 csspinner');
                                    $(".alert-warning").show();
                                }
                            });
                        } else {
                            $.notify("Invalid amount, please check and verfy again \n 金额无效，请检查并再次验证.", { position: "top center", className:"warn"});
                                return false;
                        }
                    } else {
                        $.notify("Please select card or cash payment method \n 请选择卡或现金付款方式. ", { position: "top center", className:"warn"});
                        return false;
                    }
                })
                //End.

                 $(".card-indent li").click(function () {
                    
                      if (!$("#selected_card").val() && !$(".select_tip").hasClass("active")) {
                          $.notify("Please select payment type cash/card or select tip.", {
                                          position: "top center",
                                          className:"warn"
                                      });
                      // alert("Please select payment type cash/card or select tip.");
                              return false;
                      }
                      
                      
                      if ($(this).hasClass("clear-txt") || $(this).hasClass("enter-txt") || $(this).attr("id") == "Back")
                          return false;
                      
                      var new_value;
                      
                      if ($(this).attr("id") == "Default"){
                      	new_value= $(".total_price").attr("alt");
                      	$('#screen').attr("buffer", new_value);	
                      	$('#screen').val(new_value);
                      	return true;
                      }


				              var buffer = $('#screen').attr("buffer");
				              if(Number(buffer) == 0) buffer = '';
				              
				              new_value = buffer + $(this).html();										
                                  
				              $('#screen').attr("buffer", new_value);		
                      
                      $('#screen').attr("lastinput", $('#screen').val() );
				              
				              $('#screen').val(new_value);

                })


            function recalculateAmount(cash_val, card_val, tip, total_price, membercard_val) {
                $("#tip_val").val(tip);

                var card_extra_tip = 0;

                var amount = cash_val + card_val + membercard_val;
                var card_amount = card_val + membercard_val;

                $(".received_price").html("$" + amount.toFixed(2));
                $(".received_price").attr('amount', amount.toFixed(2));


                if (card_amount >= total_price) {
                    card_extra_tip = card_amount - total_price;
                    tip += card_extra_tip;

                    $(".change_price_txt").html("Change 找零");
                    $(".change_price").html("$" + cash_val.toFixed(2));
                    $(".change_price").attr('amount', (cash_val).toFixed(2));

                    $(".tip_price").html("$" + card_extra_tip.toFixed(2));
                    $("#tip_val").val(card_extra_tip.toFixed(2));
                    if (card_extra_tip > 0) {
                        $("#tip_paid_by").val("CARD");
                    }
                } else { // card_val < total_price

                    $(".change_price").html("$" + Math.abs(amount - total_price).toFixed(2));
                    $(".change_price").attr('amount', (amount - total_price).toFixed(2));

                    if (amount < total_price) {
                        $(".change_price_txt").html("Remaining 其余");
                    } else { // amount >= total_price
                        $(".change_price_txt").html("Change 找零");
                    }

                    $(".tip_price").html("$" + (0).toFixed(2));
                    $("#tip_val").val((0).toFixed(2));
                }
            }

            $("#Enter").click(function () {
                if (!$("#selected_card").val()) {
                    $.notify("Please select payment type card/cash.", {
                                    position: "top center",
                                    className:"warn"
                                });
                // alert("Please select payment type card/cash.");
                        return false;
                }
                var amount = $("#screen").val() ? parseFloat($("#screen").val()) : 0;
                var total_price = parseFloat($(".total_price").attr("alt"));

                if ($("#selected_card").val() == 'cash') {
                    $("#cash_val").val(amount.toFixed(2));
                    $(".cash_price").html("Cash 现金: $" + amount.toFixed(2));
                }
                if ($("#selected_card").val() == 'card') {
                    $("#card_val").val(amount.toFixed(2));
                    $(".card_price").html("Card 卡: $" + amount.toFixed(2));
                }
                if ($("#selected_card").val() == 'membercard') {
                    $("#membercard_val").val(amount.toFixed(2));
                    $(".membercard_price").html("Member会员: $" + amount.toFixed(2));
                }
                if ($("#selected_card").val() == 'tip') {
                    $("#tip_val").val(amount.toFixed(2));
                    // $(".tip_price").html("$" + amount.toFixed(2));
                }

                var cash_val = $("#cash_val").val() ? parseFloat($("#cash_val").val()) : 0;
                var card_val = $("#card_val").val() ? parseFloat($("#card_val").val()) : 0;
                var membercard_val = $("#membercard_val").val() ? parseFloat($("#membercard_val").val()) : 0;
                var tip_val = $("#tip_val").val() ? parseFloat($("#tip_val").val()) : 0;


                recalculateAmount(cash_val, card_val, tip_val, total_price, membercard_val);
            })
            $("#rc1").click(function (E) {
                E.preventDefault();
            })


            $("#Clear").click(function () {
                var selected_card = $("#selected_card").val();
                var total_price = parseFloat($(".total_price").attr("alt"));
                if (selected_card == 'cash') {
                    $("#cash_val").val(0);
                    $(".cash_price").html("Cash 现金: $" + (0).toFixed(2));
                }

                if (selected_card == 'card') {
                    $("#card_val").val(0);
                    $(".card_price").html("Card 卡: $" + (0).toFixed(2));

                }
                if (selected_card == 'membercard') {
                    $("#membercard_val").val(0);
                    $(".membercard_price").html("Member会员: $" + (0).toFixed(2));

                }
                if (selected_card == 'tip') {
                    $("#tip_val").val(0);
                }

                var cash_val = $("#cash_val").val() ? parseFloat($("#cash_val").val()) : 0;
                var card_val = $("#card_val").val() ? parseFloat($("#card_val").val()) : 0;
                var membercard_val = $("#membercard_val").val() ? parseFloat($("#membercard_val").val()) : 0;
                var tip_val = $("#tip_val").val() ? parseFloat($("#tip_val").val()) : 0;

                recalculateAmount(cash_val, card_val, tip_val, total_price, membercard_val);

                $("#screen").attr('buffer', '');
                $("#screen").val("");
                $("#screen").focus();
            })


            $("#Back").click(function () {

				       var current_val = $('#screen').val();
				       var new_val = current_val.substring(0,current_val.length-1);		
               
				       $('#screen').val(new_val);
               $('#screen').attr("buffer", new_val);		
               
               $("#screen").focus();
            })


            //Modified by Yishou Liao @ Oct 16 2016
            $("#screen").keydown(function (e) {
            // Allow: backspace, delete, tab, escape, enter and .
               if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== - 1 ||
                 // Allow: Ctrl+A, Command+A
                         (e.keyCode == 65 && (e.ctrlKey === true || e.metaKey === true)) ||
                         // Allow: home, end, left, right, down, up
                                 (e.keyCode >= 35 && e.keyCode <= 40)) {
                 // let it happen, don't do anything
                 return;
                 }
                 // Ensure that it is a number and stop the keypress
                 if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                 e.preventDefault();
               }
           });
                    //End.
    });



    var OrderComponent = (function() {
        var template = `
            <ul class="order-component col-md-12 col-sm-12 col-xs-12 clearfix" id="order-component-id-{0}">
                <li class="order-component-title"><?php echo __('Order No.'); ?> {1}, <?php echo __('Table No.'); ?> {2}, <?php echo __('Type'); ?> {3}</li>
                <li class="order-component-subtotal clearfix">
                    <div class="col-md-6 col-sm-6 col-xs-12"><?php echo __('Subtotal'); ?> </div>
                    <div class="col-md-6 col-sm-6 col-xs-12">$ {4}</div>
                </li>
                <li class="order-component-discount clearfix">
                    <div class="col-md-6 col-sm-6 col-xs-12"><?php echo __('Discount'); ?> </div>
                    <div class="col-md-6 col-sm-6 col-xs-12">$ {5}<div class="order-component-discount-type">{6}</div></div>
                </li>
                <li class="order-component-after-discount clearfix">
                    <div class="col-md-6 col-sm-6 col-xs-12"><?php echo __('After Discount'); ?> </div>
                    <div class="col-md-6 col-sm-6 col-xs-12">$ {7} </div>
                </li>
                <li class="order-component-tax clearfix">
                    <div class="col-md-6 col-sm-6 col-xs-12"><?php echo __('Tax'); ?>({8}%)</div>
                    <div class="col-md-6 col-sm-6 col-xs-12">$ {9}</div>
                </li>
                <li class="order-component-tax clearfix">
                    <div class="col-md-6 col-sm-6 col-xs-12"><?php echo __('Tip'); ?>({11}%)</div>
                    <div class="col-md-6 col-sm-6 col-xs-12">$ {12}</div>
                </li>
                <li class="order-component-total clearfix">
                    <div class="col-md-6 col-sm-6 col-xs-12"><?php echo __('Total'); ?> </div>
                    <div class="col-md-6 col-sm-6 col-xs-12">$ {10} </div>
                </li>
            </ul>
        `;

        var discountInputTemplate = `
            <div class="col-md-12 col-sm-12 col-xs-12" id="discount-input-group={0}" data-id="{0}">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <label for="fix-discount-{0}"><?php echo __('Fix Discount'); ?></label>
                    <input type="number" id="fix-discount-{0}">
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <label for="percent-discount-{0}"><?php echo __('Discount in %'); ?></label>
                    <input type="number" id="percent-discount-{0}">
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <label for="promo-discount-{0}"><?php echo __('Promo Code'); ?></label>
                    <input type="text" id="promo-discount-{0}">
                </div>
                <button class="btn btn-info apply-discount-btn" data-id="{0}"><?php echo __('Apply'); ?></button>
            </div>
        `;

        var addDiscountTemplate = `
            <button class="add-discount-component btn btn-success" data-id="{0}"><?php echo __('Add Discount'); ?></button>
        `;

        var removeDiscountTemplate = `
            <button class="remove-discount-component btn btn-danger" data-id="{0}"><?php echo __('Delete Discount'); ?></button>
        `;

        var createDom = function(id, order_no, table_no, type, subtotal, after_discount, fix_discount, percent_discount, discount_value, tax_rate, tax_amount, total,default_tip_rate,default_tip_amount) {
            if (percent_discount > 0) {
                discount_type_str = "(" + percent_discount + "%)";
            } else {
                discount_type_str = "";
            }
            console.log(template.format(id, order_no, table_no, type, subtotal, discount_value, discount_type_str, after_discount, tax_rate, tax_amount, total,default_tip_rate,default_tip_amount));

            var orderComponent = $(template.format(id, order_no, table_no, type, subtotal, discount_value, discount_type_str, after_discount, tax_rate, tax_amount, total,default_tip_rate,default_tip_amount));
            var discountInputGroup = $(discountInputTemplate.format(id));
            var addDiscountBtn = $(addDiscountTemplate.format(id));
            var removeDiscountBtn = $(removeDiscountTemplate.format(id));

            orderComponent.append(addDiscountBtn).append(removeDiscountBtn).append(discountInputGroup);

            addDiscountBtn.on('click', function() {
                // $(this).hide();
                discountInputGroup.toggle();
            })

            // discountInputGroup.on()

            discountInputGroup.find('input').each(function() {
                $(this).on('keyup', function() {
                    var val = $(this).val();

                    if (val != '') {
                        discountInputGroup.find('input').not($(this)).prop('disabled', true)
                    } else {
                        discountInputGroup.find('input').prop('disabled', false);
                    }
                });

            });

            discountInputGroup.find('.apply-discount-btn').on('click', function() {
                var fix_discount = $("#fix-discount-" + id).val();
                var discount_percent = $("#percent-discount-" + id).val();
                var promocode = $("#promo-discount-" + id).val();
                
                console.log("#promo-discount-" + id);
                console.log(fix_discount);
                
                var discountType, discountValue;
                if(fix_discount){
                  discountType  = 'fix_discount';
                  discountValue = fix_discount;
                }else if(discount_percent){
                  discountType  = 'percent_discount';
                  discountValue = discount_percent;                
                }else if(promocode){
                  discountType  = 'promocode';
                  discountValue = promocode;                
                }else{
                  alert("Please add discount first.");
                  return false;                
                }


                $.ajax({
                    url: "<?php echo $this->Html->url(array('controller' => 'discount', 'action' => 'addDiscount')); ?>",
                    method: "post",
                    dataType: "json",
                    data: {discountType: discountType, discountValue: discountValue, order_no: order_no},
                    success: function (res) {
                        if (res.error) {
                           alert(res.message);
                        } else {
                           window.location.reload();
                        }
                    }
                });

            });

            removeDiscountBtn.on('click', function() {
                $.ajax({
                    url: "<?php echo $this->Html->url(array('controller' => 'discount', 'action' => 'removeDiscount')); ?>",
                    method: "post",
                    data: {order_no:  order_no},
                    success: function (html) {
                        window.location.reload();
                    }
                });
            });


            if (discount_value == 0) {
                orderComponent.find('.order-component-discount').hide();
                orderComponent.find('.order-component-after-discount').hide();
                removeDiscountBtn.hide();
                discountInputGroup.hide();
            } else {
                discountInputGroup.hide();
                addDiscountBtn.hide();
            }

            return orderComponent
        }

        var bindEvent = function() {

        }

        var init = function(id, order_no, table_no, type, subtotal, after_discount, fix_discount, percent_discount, discount_value, tax_rate, tax_amount, total,default_tip_rate,default_tip_amount) {
            return createDom(id, order_no, table_no, type, subtotal, after_discount, fix_discount, percent_discount, discount_value, tax_rate, tax_amount, total,default_tip_rate,default_tip_amount);
        }

        return {
            init: init
        }
    })();




    function loadOrders() {
        var order_ids = [];
        <?php
            foreach ($order_id_merge as $o) {
        ?>
                order_ids.push(parseInt('<?php echo $o?>'));
        <?php
            }
         ?>

        $.ajax({
            url: "<?php echo $this->Html->url(array('controller' => 'merge', 'action' => 'getOrdersAmount')); ?>",
            method: "post",
            data: {order_ids: order_ids},
            success: function (json) {
                var orders = JSON.parse(json);
                console.log(orders);

                $('#mid-section').empty();

                for (var i = 0; i < orders.length; ++i) {
                    var order_no = orders[i]['order_no'];
                    var table_no = orders[i]['table_no'];
                    var type = orders[i]['order_type'];
                    var id = orders[i]['id'];
                    var subtotal = round2(parseFloat(orders[i]['subtotal']));
                    var total = round2(parseFloat(orders[i]['total']));
                    var fix_discount = round2(orders[i]['fix_discount'] ? orders[i]['fix_discount'] : 0);
                    var discount_value = round2(orders[i]['discount_value'] ? orders[i]['discount_value']  : 0);
                    var percent_discount = round2(orders[i]['percent_discount'] ? orders[i]['percent_discount'] : 0);

                    var after_discount = round2(subtotal - discount_value);
                    var tax_rate = parseFloat(orders[i]['tax']);
                    var tax_amount = round2(parseFloat(orders[i]['tax_amount']));

                    var default_tip_rate = parseFloat(orders[i]['default_tip_rate']);
                    var default_tip_amount = round2(parseFloat(orders[i]['default_tip_amount']));

                    var orderComponent = OrderComponent.init(id, order_no, table_no, type,subtotal, after_discount, fix_discount, percent_discount, discount_value, tax_rate, tax_amount, total,default_tip_rate,default_tip_amount);

                    $('#mid-section').append(orderComponent);
                }

            }
        });
    }

    loadOrders();

    function round2(number) {
        return Math.round(number * 100) / 100
    }

</script>
