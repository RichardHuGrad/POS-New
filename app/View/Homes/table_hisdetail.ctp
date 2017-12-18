<header >
    <?php echo $this->element('navbar'); ?>
</header>

<div class="container">
	<div class="clearfix cartwrap-wrap"></div>

    <div class="order-wrap">
    <?php echo $this->Session->flash(); ?>
        <div class="col-md-4 col-sm-4 col-xs-12 order-left">
            <h2>Order 订单号 #<?php echo $Order_detail['Order']['order_no'] ?>, Table 桌 [[<?php echo $order_type=='D'?'Dinein':'Takeout'; ?>]]#<?php echo $table_no; ?>, @ <?php echo $today ?></h2>

            <div class="paid-box">
                <div class="checkbox-btn">
                    <input type="checkbox" value="value-1" id="rc1" name="rc1" <?php if($Order_detail['Order']['table_status'] == 'P') echo "checked='checked'"; ?>/>
                    <label for="rc1" disabled>Paid 已付费</label>
                </div>
            </div>

            <div class="order-summary">
                <h3>Order Summary 订单摘要</h3>
                <div class="order-summary-indent clearfix">
                    <ul>
                      <?php
                      if(!empty($Order_detail['OrderItem'])) {
                          foreach ($Order_detail['OrderItem'] as $key => $value) {
                              # code...
                              $selected_extras_name = [];
                              if ($value['all_extras']) {
                                  $extras = json_decode($value['all_extras'], true);
                                  $selected_extras = json_decode($value['selected_extras'], true);

                                  // prepare extras string
                                  $selected_extras_id = [];
                                  if(!empty($selected_extras)) {
                                      foreach($selected_extras as $k=>$v){
//							$enameArr = explode(" ", $v['name']);
//							$selected_extras_name[] = array_pop($enameArr);
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
                                            <div class="less-title"><?php echo $value['name_en']."<br/>".$value['name_xh']; ?></div>

                                            <!-- to show the extras item name -->
                                            <div class="less-txt"><?php echo implode(",", $selected_extras_name); ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-4 col-xs-4 text-right price-txt">
                                      $<?php echo ($value['price']+$value['extras_amount']); ?><?php echo $value['qty']>1?"x".$value['qty']:"" ?>
                                    </div>
                                </div>
                            </li>
                        <?php }
                    }?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-8 col-sm-8 col-xs-12 RIGHT-SECTION">
        <form>
        	<input type='hidden' name='order_id' id='order_id' value='<?php echo $Order_detail['Order']['id']; ?>'>
            <div class="clearfix total-payment">
                <ul>
                    <li class="clearfix">
                        <div class="row">
                            <div class="col-md-3 col-sm-4 col-xs-4 sub-txt">Sub Total 小计 </div>
                            <div class="col-md-3 col-sm-4 col-xs-4 sub-price" style='overflow: hidden; white-space: nowrap;'>$<input name='subtotal' id='subtotal' value='<?php echo number_format($Order_detail['Order']['subtotal'], 2) ?>'></div>
                        </div>
                    </li>
                    <li class="clearfix">
                        <div class="row">
                            <div class="col-md-3 col-sm-4 col-xs-4 sub-txt">Tax 税 (<?php echo $Order_detail['Order']['tax'] ?>%)</div>
                            <div class="col-md-3 col-sm-4 col-xs-4 sub-price">$<?php echo number_format($Order_detail['Order']['tax_amount'], 2) ?></div>
                        </div>
                    </li>
                    <li class="clearfix">
                        <div class="row">
                            <div class="col-md-3 col-sm-4 col-xs-4 sub-txt">Discount 折扣 </div>
                            <div class="col-md-3 col-sm-4 col-xs-4 sub-price" style='overflow: hidden; white-space: nowrap;'>$<input name='discount_value' id='discount_value' value='<?php echo number_format($Order_detail['Order']['discount_value'], 2) ?>'></div>
                        </div>
                    </li>
                    <li class="clearfix">
                        <div class="row">
                            <div class="col-md-3 col-sm-4 col-xs-4 sub-txt">Total 总</div>
                            <div class="col-md-3 col-sm-4 col-xs-4 sub-price" style='overflow: hidden; white-space: nowrap;'>$<input name='total' id='total' value='<?php echo number_format($Order_detail['Order']['total'], 2) ?>'></div>
                        </div>
                    </li>
                    <li class="clearfix">
                        <div class="row">
                            <div class="col-md-3 col-sm-4 col-xs-4 sub-txt">Receive 收到</div>
                            <div class="col-md-3 col-sm-4 col-xs-4 sub-price" style='overflow: hidden; white-space: nowrap;'>$<input name='paid' id='paid' value='<?php echo number_format($Order_detail['Order']['paid'], 2) ?>'></div>
                            <div class="col-md-3 col-sm-4 col-xs-4 sub-price cash_price" style='overflow: hidden; white-space: nowrap;'>Cash 现金: $<input name='cash_val' id='cash_val' value='<?php echo number_format($Order_detail['Order']['cash_val'], 2) ?>'></div>
                            <div class="col-md-3 col-sm-4 col-xs-4 sub-price card_price" style='overflow: hidden; white-space: nowrap;'>Card 卡: $<input name='card_val' id='card_val' value='<?php echo number_format($Order_detail['Order']['card_val'], 2) ?>'></div>
                        </div>
                    </li>
                    <li class="clearfix">
                        <div class="row">
                            <div class="col-md-3 col-sm-4 col-xs-4 sub-txt change_price_txt">Change 找零</div>
                            <div class="col-md-3 col-sm-4 col-xs-4 sub-price change_price" style='overflow: hidden; white-space: nowrap;'>$<input name='change' id='change' value='<?php echo number_format($Order_detail['Order']['change'], 2) ?>'></div>
                        </div>
                    </li>

                    <li class="clearfix">
                        <div class="row">
                            <div class="col-md-3 col-sm-4 col-xs-4 sub-txt">Tip 小费</div>
                            <div class="col-md-3 col-sm-4 col-xs-4 sub-price tip_price" style='overflow: hidden; white-space: nowrap;'>$<input name='tip' id='tip' value='<?php echo number_format($Order_detail['Order']['tip'], 2) ?>'></div>
                        </div>
                    </li>

                    <li class="clearfix">
                        <div class="row">
                            <div class="col-md-2 col-sm-2 col-xs-2 sub-txt">&nbsp;</div>
                            <?php if($dinein_table_status!='N'){ ?>
                              <div class="col-md-3 col-sm-3 col-xs-3 sub-txt restore" style='margin: 0; text-align: center; color:#fff; font-size:20px; font-weight: bold; border-radius: 15px; background-color: green;cursor:pointer;'>Restore 弹回</div>
                              <div class="col-md-2 col-sm-2 col-xs-2 sub-txt">&nbsp;</div>
                            <?php } ?>
                            <div class="col-md-3 col-sm-3 col-xs-3 sub-txt updatefee" style='margin: 0; text-align: center; color:#fff; font-size:20px; font-weight: bold; border-radius: 15px; background-color: #c30e23;cursor:pointer;'>Update 修改</div>
                            
                            <div class="col-md-2 col-sm-2 col-xs-2 sub-txt">&nbsp;</div>
                        </div>
                    </li>
                </ul>
            </div>
        </form>
        </div>
    </div>
</div>
<?php
echo $this->Html->script(array('jquery.min.js', 'bootstrap.min.js', 'jQuery.print.js','md5.js'));
echo $this->fetch('script');
?>

<script type="text/javascript">
(function ($) {})(jQuery);
$(document).on('click', ".updatefee", function () {
        var subtotal = $('#subtotal').val();
        var discount_value = $('#discount_value').val();
        var total = $('#total').val();
        var paid = $('#paid').val();
        var cash_val = $('#cash_val').val();
        var card_val = $('#card_val').val();
        var change = $('#change').val();
        var tip = $('#tip').val();
        $.ajax({
            url: "<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'tableHisupdate')); ?>",
            method: "post",
            data: {table_no: "<?php echo $table_no ?>", order_id: "<?php echo $order_id ?>", subtotal: subtotal, discount_value: discount_value, total: total, paid: paid, cash_val: cash_val, card_val: card_val, change: change, tip: tip},
            dataType: "json",
            success: function (json) {
            	if (json.ret == 1) {
            		window.location.href = "<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'tableHistory',  'table_no' => $table_no, 'order_type' => $order_type)); ?>";
            	} else {
            		alert(json.message);
            	}
            }
        })
    });

$(document).on('click', ".restore", function () {
        $.ajax({
            url: "<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'tableRestore')); ?>",
            method: "post",
            data: {order_id: "<?php echo $order_id ?>"},
            dataType: "json",
            success: function (json) {            	
            	if (json.ret == 1) {
            		window.location.href = json.url;
            	} else {
            		alert(json.message);
            	} 
            }
        })
    });

</script>
