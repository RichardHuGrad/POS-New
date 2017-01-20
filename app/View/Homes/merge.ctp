<header class="product-header">

    <div style="display:none;">
        <canvas id="canvas" width="512" height="480"></canvas>
        <?php echo $this->Html->image("logo.png", array('alt' => "POS", 'id' => "logo")); ?>
    </div>

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
</header>
<div class="merge container-fluid">
    <div class="clearfix cartwrap-wrap">
    </div>
    <div class="order-wrap">
        <?php echo $this->Session->flash(); ?>
        <div class="col-md-4 col-sm-4 col-xs-12 order-left">
            <h2>Order 订单号 <?php
                //Modified by Yishou Liao @ Oct 14 2016.
                for ($i = 0; $i < count($Order_detail); $i++) {
                    echo " # " . $Order_detail[$i]['Order']['order_no'];
                };
                //End.
                ?>, Table 桌 <?php echo (($type == 'D') ? '[[Dinein]]' : (($type == 'T') ? '[[Takeout]]' : (($type == 'W') ? '[[Waiting]]' : ''))); ?>#<?php echo $table; ?>
                <!-- Modified by Yishou LIao @ Oct 14 2016. -->
                <?php
                echo "与";
                for ($i = 0; $i < count($tablemerge); $i++) {
                    if ($i > 0) {
                        echo "#" . $tablemerge[$i] . " ";
                    };
                };
                echo "合单";
                ?>
                <!-- End. -->
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
                    <label for="rc1" disabled>Paid 已付费</label>
                </div>
            </div>

            <div class="avoid-this text-center reprint"><button type="button" class="submitbtn">Print Receipt 打印收据</button></div>
            <div class="order-summary">
                <h3>Order Summary 订单摘要</h3>
                <div class="order-summary-indent clearfix">
                    <ul>
<?php
for ($x = 0; $x < count($Order_detail); $x++) {//MOdified by Yishou Liao @ Oct 14 2016.
    echo "#" . $Order_detail[$x]['Order']['table_no'] . " BILL";
    if (!empty($Order_detail[$x]['OrderItem'])) {
        foreach ($Order_detail[$x]['OrderItem'] as $key => $value) {
            # code...
            $selected_extras_name = [];
            if ($value['all_extras']) {
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
            }
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
        <div class="col-md-8 col-sm-8 col-xs-12 RIGHT-SECTION">
            <div class="clearfix total-payment">
                <ul>
                    <li class="clearfix">
                        <div class="row">
                        	<!-- Modified by Yishou Liao @ Nov 25 2016 -->
                            <div class="col-md-3 col-sm-4 col-xs-4 sub-txt">Sub Total <?php
							$table_discount_value = 0;
							for ($i = 0; $i < count($Order_detail); $i++) {
	                            $table_discount_value += $Order_detail[$i]['Order']['discount_value'];
                                }; 
							if($table_discount_value) { ?>小计(原价)<?php } else { ?> 小计 <?php } ?> </div>
                            <div class="col-md-3 col-sm-4 col-xs-4 sub-price">$
<?php
//Modified by Yishou Liao @ Oct 14 2016.
$subtotal = 0;
for ($i = 0; $i < count($Order_detail); $i++) {
    $subtotal += $Order_detail[$i]['Order']['subtotal'];
};
echo number_format($subtotal+$table_discount_value, 2);
//End.
//End of Nov 25 2016
?></div>

                                <?php
                                //Modified by Yishou Liao @ Oct 14 2016.
                                for ($i = 0; $i < count($Order_detail); $i++) {
                                    if ($Order_detail[$i]['Order']['table_no'] == $table) {
                                        $table_status = $Order_detail[$i]['Order']['table_status'];
                                        $table_discount_value = $Order_detail[$i]['Order']['discount_value'];
                                    };
                                };
                                if ($table_status <> 'P' and ! $table_discount_value) {
                                    ?>
                                <div class="col-md-6 col-sm-4 col-xs-4"><button type="button" class="addbtn pull-right add-discount"><i class="fa fa-plus-circle" aria-hidden="true"></i> Add Discount 加入折扣</button></div>
                            <?php } //End.   ?>
                        </div>
                    </li>

                            <?php
//Modified by Yishou Liao @ Oct 14 2016.
                            for ($i = 0; $i < count($Order_detail); $i++) {
                                if ($Order_detail[$i]['Order']['table_no'] == $table) {
                                    $table_discount_value = $Order_detail[$i]['Order']['discount_value'];
                                };
                            };
                            if (!$table_discount_value) {
//End.
                                ?>
                        <li class="clearfix discount_view" style="display:none;">
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
                                        <label for="AdminTableSize" style="width:100%">&nbsp;</label>
                                        <a class="btn btn-primary btn-wide" id="apply-discount" href="javascript:void(0)">Apply <i class="fa fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>

                            </div>
                        </li>
<?php } ?>


                                <?php
                                //Modified by Yishou Liao @ Oct 14 2016.
                                for ($i = 0; $i < count($Order_detail); $i++) {
                                    if ($Order_detail[$i]['Order']['table_no'] == $table) {
                                        $table_discount_value = $Order_detail[$i]['Order']['discount_value'];
                                    };
                                };
                                if ($table_discount_value) {
                                    //End.
                                    ?>
                        <li class="clearfix">
                            <div class="row">
                                    <?php
                                    // show discount code here
                                    ?>
                                <div class="col-md-3 col-sm-4 col-xs-4 sub-txt">Discount 折扣</div>
                                <div class="col-md-3 col-sm-4 col-xs-4 sub-price">
                                    $<?php
                        //Modified by Yishou Liao @ Nov 19 2016.
						$table_discount_value = 0;
                        for ($i = 0; $i < count($Order_detail); $i++) {
                            //if ($Order_detail[$i]['Order']['table_no'] == $table) {//Modified by Yishou Liao @ Nov 19 2016
                                $table_discount_value += $Order_detail[$i]['Order']['discount_value'];
                            //}; //End
                        };
                        echo number_format($table_discount_value, 2);

                        for ($i = 0; $i < count($Order_detail); $i++) {
                            if ($Order_detail[$i]['Order']['table_no'] == $table) {
                                $table_percent_discount = $Order_detail[$i]['Order']['percent_discount'];
                                $table_promocode = $Order_detail[$i]['Order']['promocode'];
                            };
                        };
                        if ($table_percent_discount) {
                            //echo "<span class='txt12'> " . $table_percent_discount . " (" . $table_percent_discount . "%)</span>";
							echo "<span class='txt12'> " . " (" . $table_percent_discount . "%)</span>";
                        }
                        //End.
                        ?> 
                                    <a aria-hidden="true" class="fa fa-times remove_discount" order_id="
                                    <?php
                                    for ($i = 0; $i < count($Order_detail); $i++) {
                                        if ($Order_detail[$i]['Order']['table_no'] == $table) {
                                            $order_id = $Order_detail[$i]['Order']['id'];
                                        };
                                    };
                                    echo $order_id;
                                    ?>" href="javascript:void(0)"></a>                       
                                </div>

                            </div>
                        </li>

<!-- Modified by Yishou Liao @ Nov 25 2016 -->
<li class="clearfix">
                        <div class="row">
                        	<!-- Modified by Yishou Liao @ Nov 25 2016 -->
                            <div class="col-md-3 col-sm-4 col-xs-4 sub-txt">After Discount 打折后: </div>
                            <div class="col-md-3 col-sm-4 col-xs-4 sub-price">$
<?php
//Modified by Yishou Liao @ Oct 14 2016.
$subtotal = 0;
for ($i = 0; $i < count($Order_detail); $i++) {
    $subtotal += $Order_detail[$i]['Order']['subtotal'];
};
echo number_format($subtotal, 2);
//End.
//End of Nov 25 2016
?></div>
</li>
<!-- End -->
             
                                    <?php
                                }
                                ?>

					<!-- Modified by Yishou Liao @ Nov 25 2016 -->
                    <li class="clearfix">
                        <div class="row">
                            <div class="col-md-3 col-sm-4 col-xs-4 sub-txt">Tax 税 (
<?php
//Modified by Yishou Liao @ Oct 14 2016.
for ($i = 0; $i < count($Order_detail); $i++) {
    if ($Order_detail[$i]['Order']['table_no'] == $table) {
        $table_tax = $Order_detail[$i]['Order']['tax'];
    };
};
echo $table_tax;
//End.
?>%)</div>
                            <div class="col-md-3 col-sm-4 col-xs-4 sub-price">$
<?php
//Modified by Yishou Liao @ Oct 14 2016.
$tax_amount = 0;
for ($i = 0; $i < count($Order_detail); $i++) {
    $tax_amount += $Order_detail[$i]['Order']['tax_amount'];
};
echo number_format($tax_amount, 2);
//End.
?></div>
                        </div>
                    </li>
                    <!-- End -->

                    <li class="clearfix">
                        <div class="row">
                            <div class="col-md-3 col-sm-4 col-xs-4 sub-txt">Total 总</div>
                            <div class="col-md-3 col-sm-4 col-xs-4 sub-price total_price" alt="<?php
                                //Modified by Yishou Liao @ Oct 14 2016.
                                $total = 0;
                                for ($i = 0; $i < count($Order_detail); $i++) {
                                    $total += $Order_detail[$i]['Order']['total'];
                                };
                                echo number_format($total, 2)
                                ?>">$<?php
                                //Modified by Yishou Liao @ Oct 14 2016.
                                $total = 0;
                                for ($i = 0; $i < count($Order_detail); $i++) {
                                    $total += $Order_detail[$i]['Order']['total'];
                                };
                                echo number_format($total, 2)
                                ?></div>
                        </div>
                    </li>
                    <?php
//Modified by Yishou Liao @ Oct 14 2016.
                    for ($i = 0; $i < count($Order_detail); $i++) {
                        if ($Order_detail[$i]['Order']['table_no'] == $table) {
                            $table_status = $Order_detail[$i]['Order']['table_status'];
                        };
                    };
                    if ($table_status == 'P') {
                        ?>
                        <li class="clearfix">
                            <div class="row">
                                <div class="col-md-3 col-sm-4 col-xs-4 sub-txt">Receive 收到</div>
                                <div class="col-md-3 col-sm-4 col-xs-4 sub-price received_price">$<?php echo $Order_detail['Order']['paid']; ?></div>


                                <div class="col-md-3 col-sm-4 col-xs-4 sub-price cash_price">Cash 现金: $<?php echo $Order_detail['Order']['cash_val']; ?></div>
                                <div class="col-md-3 col-sm-4 col-xs-4 sub-price card_price">Card 卡: $<?php echo $Order_detail['Order']['card_val']; ?></div>
                            </div>
                        </li>

                                         <?php if ($Order_detail['Order']['change']) { ?>
                            <li class="clearfix">
                                <div class="row">
                                    <div class="col-md-3 col-sm-4 col-xs-4 sub-txt change_price_txt">Change 找零</div>
                                    <div class="col-md-3 col-sm-4 col-xs-4 sub-price change_price">$<?php echo $Order_detail['Order']['change']; ?></div>
                                </div>
                            </li>
                                    <?php } ?>

                        <li class="clearfix">
                            <div class="row">
                                <div class="col-md-3 col-sm-4 col-xs-4 sub-txt">Tip 小费</div>
                                <div class="col-md-3 col-sm-4 col-xs-4 sub-price tip_price">$<?php echo $Order_detail['Order']['tip']; ?></div>
                            </div>
                        </li>
                        <?php
                    } else {
                        ?>
                        <li class="clearfix">
                            <div class="row">
                                <div class="col-md-3 col-sm-4 col-xs-4 sub-txt">Receive 收到</div>
                                <div class="col-md-3 col-sm-4 col-xs-4 sub-price received_price">$00.00</div>

                                <div class="col-md-3 col-sm-4 col-xs-4 sub-price cash_price">Cash 现金: $00.00</div>
                                <div class="col-md-3 col-sm-4 col-xs-4 sub-price card_price">Card 卡: $00.00</div>
                            </div>
                        </li>
                        <li class="clearfix">
                            <div class="row">
                                <div class="col-md-3 col-sm-4 col-xs-4 sub-txt change_price_txt">Remaining 其余</div>
                                <div class="col-md-3 col-sm-4 col-xs-4 sub-price change_price">$00.00</div>
                            </div>
                        </li>  
                        <li class="clearfix">
                            <div class="row">
                                <div class="col-md-3 col-sm-4 col-xs-4 sub-txt">Tip 小费</div>
                                <div class="col-md-3 col-sm-4 col-xs-4 sub-price tip_price">$00.00</div>
                            </div>
                        </li>                              
                        <?php
                    }
                    ?>                            
                </ul>
            </div>

<?php
//Modified by Yishou Liao @ Oct 14 2016.
for ($i = 0; $i < count($Order_detail); $i++) {
    if ($Order_detail[$i]['Order']['table_no'] == $table) {
        $table_status = $Order_detail[$i]['Order']['table_status'];
    };
};
if ($table_status <> 'P') {
//End.
    ?>
                <div class="card-wrap"><input type="text" id="screen" buffer="0" maxlength="13"></div>
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

                        <li class="clear-txt" id="Clear">Clear 清除</li>
                        <li>0</li>
                        <li class="enter-txt" id="Enter">Enter 输入</li>
                    </ul>
                </div>

                <div class="card-bot clearfix text-center">
                    <button type="button" class="btn btn-danger select_card" id="card"> <?php echo $this->Html->image("card.png", array('alt' => "card")); ?> Card 卡</button>
                    <button type="button" class="btn btn-danger select_card"  id="cash"><?php echo $this->Html->image("cash.png", array('alt' => "cash")); ?> Cash 现金</button>

                    <button type="button" class="btn btn-warning select_card"  id="tip"><?php echo $this->Html->image("cash.png", array('alt' => "tip")); ?> Tip 小费</button>

                    <button type="button" class="btn btn-success card-ok"  id="submit"><?php echo $this->Html->image("right.png", array('alt' => "right")); ?> Confirm 确认</button>
                    <input type="hidden" id="selected_card" value="" />
                    <input type="hidden" id="card_val" name="card_val" value="" />
                    <input type="hidden" id="cash_val" name="cash_val" value="" />
                    <input type="hidden" id="tip_val"name="tip" value="" />
                </div>

<?php } ?>
        </div>
    </div>
</div>

<?php
echo $this->Html->script(array('jquery.min.js', 'bootstrap.min.js', 'jquery.mCustomScrollbar.concat.min.js', 'barcode.js', 'epos-print-5.0.0.js', 'fanticonvert.js', 'notify.min.js'));
echo $this->fetch('script');
?>

<script>
            $(document).on('click', '.reprint', function () {
                    //Print ele4 with custom options

            //Modified by Yishou Liao @ Oct 27 2016.
            var Order_print = new Array();
            var oder_no = ""; 			<?php
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

            <!--  Modified by Yishou Liao @ Nov 16 2016. -->
                    var   merge_str = '<?php
echo " and ";
for ($i = 0; $i < count($tablemerge); $i++) {
    if ($i > 0) {
        echo "#" . $tablemerge[$i] . " ";
    };
};
echo "merge";
?>'
<!-- End. -- >

                    var  subtotal  =  '<?php
//Modified by Yishou Liao @ Oct 16 2016.
$subtotal = 0;
for ($i = 0; $i < count($Order_detail); $i++) {
    $subtotal += $Order_detail[$i]['Order']['subtotal'];
};
echo number_format($subtotal, 2);
//End
?>';

//Modified by Yishou Liao @ Nov 29 2016
                    var  discount  =  '<?php
//Modified by Yishou Liao @ Oct 16 2016.
$discount_value = 0;
for ($i = 0; $i < count($Order_detail); $i++) {
    $discount_value += $Order_detail[$i]['Order']['discount_value'];
};
echo number_format($discount_value, 2);
//End
?>';
//End

                    var tax_amount = '<?php
//Modified by Yishou Liao @ Oct 16 2016.
$tax_amount = 0;
for ($i = 0; $i < count($Order_detail); $i++) {
    $tax_amount += $Order_detail[$i]['Order']['tax_amount'];
};
echo number_format($tax_amount, 2);
//End.
?>';
                    var total = '<?php
//Modified by Yishou Liao @ Oct 16 2016.
$total = 0;
for ($i = 0; $i < count($Order_detail); $i++) {
    $total += $Order_detail[$i]['Order']['total'];
};
echo number_format($total, 2)
?>';
                    //Modified by Yishou Liao @ Nov 08 2016.
                    $.ajax({
                    url: "<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'printMergeReceipt', (($type == 'D') ? '[[堂食]]' : (($type == 'T') ? '[[外卖]]' : (($type == 'W') ? '[[等候]]' : ''))) . ' #' . $table, $cashier_detail['Admin']['service_printer_device'],1)); ?>",
                            method:"post",
                            data:{
                            logo_name:"../webroot/img/logo.bmp",
                                    Print_Item:Order_print,
                                    subtotal:(parseFloat(subtotal)+parseFloat(discount)).toFixed(2),
									discount:discount,
									after_discount:subtotal,
									//Modified by Yishou Liao @ Nov 29 2016
									tax_Amount: tax_amount,
									paid: $(".received_price").attr("amount"),
									change: $(".change_price").attr("amount"),
									//End
                                    total:total,
                                    order_no:oder_no,
                                    merge_str:merge_str,
                            },
                            success:function(html) {

                            }
                    })
                    //End.

            });
                    $(document).on('click', '.reprint_2', function () {
            //Print ele4 with custom options
            $("#print_panel_2").print({
            //Use Global styles
            globalStyles: false,
                    //Add link with attrbute media=print
                    mediaPrint: true,
                    //Custom stylesheet
                    stylesheet: "<?php echo Router::url('/', true) ?>css/styles.css",
                    //Print in a hidden iframe
                    iframe: false,
                    //Don't print this
                    noPrintSelector: ".avoid-this",
                    //Add this at top
            });
            });
                    $(document).ready(function () {
            $(".select_card").click(function () {
            $(".select_card").removeClass("active")
                    $(this).addClass("active")
                    var type = $(this).attr("id");
                    if (type == 'card') {
            $("#cash").removeClass("active");
                    var card_val = $("#card_val").val() ? parseFloat($("#card_val").val()) * 100 : 0;
                    $("#screen").attr('buffer', card_val);
                    $("#screen").val($("#card_val").val());
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
                                    url: "<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'donemergepayment', $table, $type)); ?>",
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
                                        order_id: "<?php
                                                    $order_id = "";
                                                    for ($i = 0; $i < count($Order_detail); $i++) {
                                                        $order_id .= $Order_detail[$i]['Order']['id'] . ",";
                                                    };
                                                    $order_id = substr($order_id, 0, (strlen($order_id) - 1));
                                                    echo $order_id;
                                                    ?>",
                                        card_val: $("#card_val").val(),
                                        cash_val: $("#cash_val").val(),
                                        tip_val: $("#tip_val").val(),
                                    },
                                    success: function (html) {
                                        $(".alert-warning").hide();
                                        $(".reprint").trigger("click");
                                        window.location = "<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'dashboard')); ?>";
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

            if ($(this).hasClass("clear-txt") || $(this).hasClass("enter-txt"))
                    return false;
                    var digit = parseInt($(this).html());
                    var nums = $("#screen").attr('buffer') + digit;
                    // store buffer value
                    $("#screen").attr('buffer', nums);
                    nums = nums / 100;
                    nums = nums.toFixed(2);
                    if (nums.length < 12)
                    $("#screen").val(nums).focus();
                    else
                    $("#screen").focus();
            })
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
            if ($("#selected_card").val() == 'tip') {
            $("#tip_val").val(amount.toFixed(2));
                    $(".tip_price").html("$" + amount.toFixed(2));
            }

            var cash_val = $("#cash_val").val() ? parseFloat($("#cash_val").val()) : 0;
                    var card_val = $("#card_val").val() ? parseFloat($("#card_val").val()) : 0;
                    amount = cash_val + card_val;
                    if (amount) {
            $(".received_price").html("$" + amount.toFixed(2));
                    $(".received_price").attr('amount', amount.toFixed(2));
                    $(".change_price").html("$" + (amount - total_price).toFixed(2));
                    $(".change_price").attr('amount', (amount - total_price).toFixed(2));
                    if ((amount - total_price) < 0) {
            $(".change_price_txt").html("Remaining 其余");
                    $(".change_price").html("$" + (total_price - amount).toFixed(2));
            } else {
            $(".change_price_txt").html("Change 找零");
            }

            } else {
                return false;
            }
            })
                    $("#rc1").click(function (E) {
            E.preventDefault();
            })
                    $("#Clear").click(function () {

            var selected_card = $("#selected_card").val();
                    var total_price = parseFloat($(".total_price").attr("alt"));
                    if (selected_card == 'cash') {
            var amount = $("#cash_val").val();
                    $(".cash_price").html("Cash 现金: $00.00");
                    $("#cash_val").val(0);
                    var received_price = parseFloat($(".received_price").attr('amount'));
					//Modified by Yishou Liao @ Dec 08 2016
					if (typeof($(".received_price").attr('amount')) == "undefined") {
						received_price = 0;
					};
					//End @ Dec 08 2016
                    var remaining = received_price - amount;
                    $(".received_price").html("$" + remaining.toFixed(2));
                    $(".received_price").attr('amount', remaining.toFixed(2));
                    if ((remaining - total_price) < 0) {
            $(".change_price_txt").html("Remaining 其余");
                    $(".change_price").html("$" + (total_price - remaining).toFixed(2));
            } else {
            $(".change_price_txt").html("Change 找零");
            }
            }

            if (selected_card == 'card') {
            var amount = $("#card_val").val();
                    $(".card_price").html("Card 卡: $00.00");
                    $("#card_val").val(0);
                    var received_price = parseFloat($(".received_price").attr('amount'));
					//Modified by Yishou Liao @ Dec 08 2016
					if (typeof($(".received_price").attr('amount')) == "undefined") {
						received_price = 0;
					};
					//End @ Dec 08 2016
                    var remaining = received_price - amount;
                    $(".received_price").html("$" + remaining.toFixed(2));
                    $(".received_price").attr('amount', remaining.toFixed(2));
                    if ((remaining - total_price) < 0) {
            $(".change_price_txt").html("Remaining 其余");
                    $(".change_price").html("$" + (total_price - remaining).toFixed(2));
            } else {
            $(".change_price_txt").html("Change 找零");
            }
            }
            if (selected_card == 'tip') {

            $("#tip_val").val(0.00);
                    $(".tip_price").html("$0.00");
            }

            $("#screen").attr('buffer', 0);
                    $("#screen").val("");
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

                    //Modified by Yishou Liao @ Oct 16 2016.
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
                    data: {
						fix_discount: fix_discount,
						discount_percent: discount_percent,
						promocode: promocode,
//Modified by Yishou Liao @ Nov 18 2016
						mainorder_id: "<?php
                                        for ($i = 0; $i < count($Order_detail); $i++) {
                                            if ($Order_detail[$i]['Order']['table_no'] == $table) {
                                                $order_id = $Order_detail[$i]['Order']['id'];
                                            };
                                        };
                                        echo $order_id;
                                        ?>",
                                        //End
						order_id: "<?php
                                    //Modified by Yishou Liao @ Nov 18 2016
                                    $order_id = "";
                                    for ($i = 0; $i < count($Order_detail); $i++) {
                                        $order_id .= $Order_detail[$i]['Order']['id'].',';
                                    };
                                    $order_id = substr($order_id,0,strlen($order_id)-1);
                                    echo $order_id;
                                    //End
                                    ?>",
                    },
                    success: function (html) {
                    if (html.error) {
                    // alert(html.message);
                    $.notify(html.message, {
                                position: "top center", 
                                className:"warn"
                            });
                            $(".discount_section").val("").removeAttr("disabled");
                            $(".RIGHT-SECTION").removeClass('load1 csspinner');
                            return false;
                    } else {
                    window.location.reload();
                    }
                    },
                    beforeSend: function () {
                    $(".RIGHT-SECTION").addClass('load1 csspinner');
                    }
            })
            } else {
                $.notify("Please add discount first.", {
                                position: "top center", 
                                className:"warn"
                            });
            // alert("Please add discount first.");
                    return false;
            }
            })
                    //End.
                    $(document).on("keyup", ".discount_section", function () {
            if ($(this).val()) {
            $(".discount_section").attr("disabled", "disabled");
                    $(this).removeAttr("disabled");
            } else {
            $(".discount_section").removeAttr("disabled");
            }
            })
                    //Modified by Yishou @ Oct 16 2016.
                    $(document).on('click', ".remove_discount", function () {
            var order_id = "<?php
                            //Modified by Yishou Liao @ Nov 18 2016
                            $order_id = "";
                            for ($i = 0; $i < count($Order_detail); $i++) {
                            	$order_id .= $Order_detail[$i]['Order']['id'] . ',';
                            };
                            echo $order_id;
                            //End
                            ?>";
                    var message = $("#Message").val();
                    $.ajax({
                    url: "<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'remove_discount')); ?>",
                            method: "post",
    data: {order_id: order_id},
                success: function (html) {
                    window.location.reload();
                },
                beforeSend: function () {
                    $(".RIGHT-SECTION").addClass('load1 csspinner');
                }
            });
        });
        //End.
        $(document).on('click', ".add-discount", function () {
            $(".discount_view").toggle();
        });
    });
</script>