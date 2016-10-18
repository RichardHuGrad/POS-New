<header class="product-header">
	  <div class="home-logo">
                    <a href="<?php echo $this->Html->url(array('controller'=>'homes','action'=>'dashboard')) ?>">
                    <?php echo $this->Html->image("logo-home.jpg", array('alt' => "POS")); ?>
                    </a>

					<div class="HomeText text-left">
                        <a href="<?php echo $this->Html->url(array('controller'=>'homes','action'=>'index')) ?>">Home 主页</a>
                        <a href="javascript:void(0)" onclick="window.history.back()">Back 返回</a>
					</div>

            </div>
      <div class="logout"><a href="<?php echo $this->Html->url(array('controller'=>'homes','action'=>'logout')) ?>">Logout 登出</a></div>
</header>
<div class="container">
	<?php /*print_r($cashier_detail); echo'<br><br>'; var_dump($Order_detail); echo'<br><br>';var_dump($Order_detail); echo'<br><br>'; echo $this->Paginator->counter();
		echo "<br><br>";*/
		?>
	<div class="paginator col-xs-12">
		<?php
		echo $this->Paginator->prev('<< Prev', null, null, array('class'=>'disabled'));
		echo "&nbsp&nbsp"; ?>
		<span>
			<?php echo $this->Paginator->counter();?>
		</span>
		<?php
		echo "&nbsp&nbsp";
		echo $this->Paginator->next('Next >>', null, null, array('class'=>'disabled'));
		?>
	</div>
</div>

<div class="container">
	<div class="clearfix cartwrap-wrap"></div>
	
    <div class="order-wrap">
    <?php echo $this->Session->flash(); ?>
        <div class="col-md-4 col-sm-4 col-xs-12 order-left">
            <h2>Order 订单号 #<?php echo $Order_detail[0]['Order']['order_no'] ?>, Table 桌 [[堂食]] #<?php echo $table_no; ?>, @ <?php echo $today ?></h2>

            <div class="paid-box">
                <div class="checkbox-btn">
                    <input type="checkbox" value="value-1" id="rc1" name="rc1" <?php if($Order_detail[0]['Order']['table_status'] == 'P') echo "checked='checked'"; ?>/>
                    <label for="rc1" disabled>Paid 已付费</label>
                </div>
            </div>

            <div class="order-summary">
                <h3>Order Summary 订单摘要</h3>
                <div class="order-summary-indent clearfix">
                    <ul>
                      <?php
                      if(!empty($Order_detail[0]['OrderItem'])) {
                          foreach ($Order_detail[0]['OrderItem'] as $key => $value) {
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
            <div class="clearfix total-payment">
                <ul>
                    <li class="clearfix">
                        <div class="row">
                            <div class="col-md-3 col-sm-4 col-xs-4 sub-txt">Sub Total 小计 </div>
                            <div class="col-md-3 col-sm-4 col-xs-4 sub-price">$<?php echo number_format($Order_detail[0]['Order']['subtotal'], 2) ?></div>

                          <?php
                          if($Order_detail[0]['Order']['table_status'] <> 'P' and !$Order_detail[0]['Order']['discount_value']) {
                          ?>
                            <div class="col-md-6 col-sm-4 col-xs-4"><button type="button" class="addbtn pull-right add-discount"><i class="fa fa-plus-circle" aria-hidden="true"></i> Add Discount 加入折扣</button></div>
                            <?php }?>
                        </div>
                    </li>

                    <?php if(!$Order_detail[0]['Order']['discount_value']) { ?>
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

                    <li class="clearfix">
                        <div class="row">
                            <div class="col-md-3 col-sm-4 col-xs-4 sub-txt">Tax 税 (<?php echo $Order_detail[0]['Order']['tax'] ?>%)</div>
                            <div class="col-md-3 col-sm-4 col-xs-4 sub-price">$<?php echo number_format($Order_detail[0]['Order']['tax_amount'], 2) ?></div>
                        </div>
                    </li>


                <?php if($Order_detail[0]['Order']['discount_value'])  {
                  ?>
					<li class="clearfix">
                      <div class="row">
                          <?php
                          // show discount code here
                          ?>
                          <div class="col-md-3 col-sm-4 col-xs-4 sub-txt">Discount 折扣</div>
                          <div class="col-md-3 col-sm-4 col-xs-4 sub-price">
                              $<?php echo number_format($Order_detail[0]['Order']['discount_value'], 2) ;
                                      if($Order_detail[0]['Order']['percent_discount']) {
                                          echo "<span class='txt12'> ".$Order_detail[0]['Order']['promocode']." (".$Order_detail[0]['Order']['percent_discount']."%)</span>";
                                      }
                              ?>
                              <a aria-hidden="true" class="fa fa-times remove_discount" order_id="<?php echo $Order_detail[0]['Order']['id']; ?>" href="javascript:void(0)"></a>
                          </div>
                      </div>
					</li>
              <?php
              }
              ?>
                    <li class="clearfix">
                        <div class="row">
                            <div class="col-md-3 col-sm-4 col-xs-4 sub-txt">Total 总</div>
                            <div class="col-md-3 col-sm-4 col-xs-4 sub-price total_price" alt="<?php echo $Order_detail[0]['Order']['total']; ?>">$<?php echo number_format($Order_detail[0]['Order']['total'], 2) ?></div>
                        </div>
                    </li>
                    <?php
                    if($Order_detail[0]['Order']['table_status'] == 'P') {
                      ?>
                      <li class="clearfix">
                          <div class="row">
                              <div class="col-md-3 col-sm-4 col-xs-4 sub-txt">Receive 收到</div>
                              <div class="col-md-3 col-sm-4 col-xs-4 sub-price received_price">$<?php echo $Order_detail[0]['Order']['paid']; ?></div>


                              <div class="col-md-3 col-sm-4 col-xs-4 sub-price cash_price">Cash 现金: $<?php echo $Order_detail[0]['Order']['cash_val']; ?></div>
                              <div class="col-md-3 col-sm-4 col-xs-4 sub-price card_price">Card 卡: $<?php echo $Order_detail[0]['Order']['card_val']; ?></div>
                          </div>
                      </li>

                      <?php if($Order_detail[0]['Order']['change']) { ?>
                      <li class="clearfix">
                          <div class="row">
                              <div class="col-md-3 col-sm-4 col-xs-4 sub-txt change_price_txt">Change 找零</div>
                              <div class="col-md-3 col-sm-4 col-xs-4 sub-price change_price">$<?php echo $Order_detail[0]['Order']['change']; ?></div>
                          </div>
                      </li>
                      <?php }?>

                      <li class="clearfix">
                          <div class="row">
                              <div class="col-md-3 col-sm-4 col-xs-4 sub-txt">Tip 小费</div>
                              <div class="col-md-3 col-sm-4 col-xs-4 sub-price tip_price">$<?php echo $Order_detail[0]['Order']['tip']; ?></div>
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
        </div>
    </div>
</div>

<?php
echo $this->Html->script(array('jquery.min.js', 'bootstrap.min.js', 'jQuery.print.js'));
echo $this->fetch('script');
?>