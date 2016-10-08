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
           <div class="clearfix cartwrap-wrap">
          </div>
            <div class="order-wrap">
            <?php echo $this->Session->flash(); ?>
                <div class="col-md-4 col-sm-4 col-xs-12 order-left">
                    <h2>Order 订单号 #<?php echo $Order_detail['Order']['order_no'] ?>, Table 桌 <?php echo  (($type=='D') ? '[[堂食]]' : (($type=='T') ? '[[外卖]]' : (($type=='W') ? '[[等候]]' : ''))); ?>#<?php echo $table; ?></h2>

                    <div class="paid-box">
                        <div class="checkbox-btn">
                            <input type="checkbox" value="value-1" id="rc1" name="rc1" <?php if($Order_detail['Order']['table_status'] == 'P') echo "checked='checked'"; ?>/>
                            <label for="rc1" disabled>Paid 已付费</label>
                        </div>
                    </div>
                    <?php 
                    if($Order_detail['Order']['table_status']<> 'P') {
                    ?>
                      <div class="table-box dropdown"><a href="" class="dropdown-toggle"  data-toggle="dropdown">Change Table 换桌</a>
                        <ul class="dropdown-menu">
                            <div class="customchangemenu clearfix">
                            <div class="left-arrow"></div>
                            <div class="col-md-12 col-sm-12 col-xs-12 text-center timetable">DINE IN 堂食</div>
                            <?php for($t = 1; $t <= DINEIN_TABLE; $t++) {
                                if(!@$orders_no[$t]['D']){ ?>
                               <div class="col-md-6 col-sm-6 col-xs-6 text-center timetable"><a href="<?php echo $this->Html->url(array('controller'=>'homes', 'action'=>'move_order', 'table'=>$t, 'type'=>'D', 'order_no'=>@$Order_detail['Order']['order_no'], 'ref'=>'pay' ));?>">D<?php echo $t; ?></a></div>
                            <?php }}?>
                            <div class="col-md-12 col-sm-12 col-xs-12 text-center timetable">TAKE OUT 外卖 </div>
                            <?php for($t = 1; $t <= TAKEOUT_TABLE; $t++) {
                            if(!@$orders_no[$t]['T']){  ?>
                               <div class="col-md-6 col-sm-6 col-xs-6 text-center timetable"><a href="<?php echo $this->Html->url(array('controller'=>'homes', 'action'=>'move_order', 'table'=>$t, 'type'=>'T', 'order_no'=>@$Order_detail['Order']['order_no'], 'ref'=>'pay' ));?>">T<?php echo $t; ?></a></div>
                            <?php } }?>
                            <div class="col-md-12 col-sm-12 col-xs-12 text-center timetable">WAITING 等候</div>
                            <?php for($t = 1; $t <= WAITING_TABLE; $t++) {
                            if(!@$orders_no[$t]['W']){  ?>
                               <div class="col-md-6 col-sm-6 col-xs-6 text-center timetable"><a href="<?php echo $this->Html->url(array('controller'=>'homes', 'action'=>'move_order', 'table'=>$t, 'type'=>'W', 'order_no'=>@$Order_detail['Order']['order_no'], 'ref'=>'pay' ));?>">W<?php echo $t; ?></a></div>
                            <?php } }?>
                        </div>
                      </ul>
                      </div>
                    <?php }?>
                    
                    <div class="avoid-this text-center reprint"><button type="button" class="submitbtn">Print Receipt 打印收据</button></div>
                    <div class="avoid-this text-center reprint_2"><button type="button" class="submitbtn">Print Kitchen 打印后厨单</button></div>
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
                                                <div class="pull-left">
                                                  <?php 
                                                  if ($value['image']) { 
                                                    echo $this->Html->image(TIMB_PATH."timthumb.php?src=".COUSINE_IMAGE_PATH . $value['image']."&h=42&w=62&&zc=4&Q=100", array('border' => 0, 'alt'=>'Product', 'class'=>'img-responsive'));
                                                  } else {
                                                    echo $this->Html->image(TIMB_PATH."timthumb.php?src=".TIMB_PATH . 'no_image.jpg'."&h=42&w=62&&zc=4&Q=100", array('border' => 0, 'alt'=>'Product', 'class'=>'img-responsive')); 
                                                  } 
                                                  ?>
                                                </div>
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
                                    <div class="col-md-3 col-sm-4 col-xs-4 sub-price">$<?php echo number_format($Order_detail['Order']['subtotal'], 2) ?></div>

                                  <?php 
                                  if($Order_detail['Order']['table_status'] <> 'P' and !$Order_detail['Order']['discount_value']) {
                                  ?>
                                    <div class="col-md-6 col-sm-4 col-xs-4"><button type="button" class="addbtn pull-right add-discount"><i class="fa fa-plus-circle" aria-hidden="true"></i> Add Discount 加入折扣</button></div>
                                    <?php }?>
                                </div>
                            </li>

                            <?php if(!$Order_detail['Order']['discount_value']) { ?>
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
                                    <div class="col-md-3 col-sm-4 col-xs-4 sub-txt">Tax 税 (<?php echo $Order_detail['Order']['tax'] ?>%)</div>
                                    <div class="col-md-3 col-sm-4 col-xs-4 sub-price">$<?php echo number_format($Order_detail['Order']['tax_amount'], 2) ?></div>
                                </div>
                            </li>


                        <?php if($Order_detail['Order']['discount_value'])  {
                          ?>
                          <li class="clearfix">
                              <div class="row">
                                  <?php
                                  // show discount code here
                                  ?>
                                  <div class="col-md-3 col-sm-4 col-xs-4 sub-txt">Discount 折扣</div>
                                  <div class="col-md-3 col-sm-4 col-xs-4 sub-price">
                                      $<?php echo number_format($Order_detail['Order']['discount_value'], 2) ;
                                              if($Order_detail['Order']['percent_discount']) {
                                                  echo "<span class='txt12'> ".$Order_detail['Order']['promocode']." (".$Order_detail['Order']['percent_discount']."%)</span>";
                                              }
                                      ?> 
                                      <a aria-hidden="true" class="fa fa-times remove_discount" order_id="<?php echo $Order_detail['Order']['id']; ?>" href="javascript:void(0)"></a>                       
                                  </div>
                                          
                              </div>
                          </li>
                      <?php
                      }
                      ?>



                            <li class="clearfix">
                                <div class="row">
                                    <div class="col-md-3 col-sm-4 col-xs-4 sub-txt">Total 总</div>
                                    <div class="col-md-3 col-sm-4 col-xs-4 sub-price total_price" alt="<?php echo $Order_detail['Order']['total']; ?>">$<?php echo number_format($Order_detail['Order']['total'], 2) ?></div>
                                </div>
                            </li>
                            <?php 
                            if($Order_detail['Order']['table_status'] == 'P') {
                              ?>
                              <li class="clearfix">
                                  <div class="row">
                                      <div class="col-md-3 col-sm-4 col-xs-4 sub-txt">Receive 收到</div>
                                      <div class="col-md-3 col-sm-4 col-xs-4 sub-price received_price">$<?php echo $Order_detail['Order']['paid']; ?></div>


                                      <div class="col-md-3 col-sm-4 col-xs-4 sub-price cash_price">Cash 现金: $<?php echo $Order_detail['Order']['cash_val']; ?></div>
                                      <div class="col-md-3 col-sm-4 col-xs-4 sub-price card_price">Card 卡: $<?php echo $Order_detail['Order']['card_val']; ?></div>
                                  </div>
                              </li>

                              <?php if($Order_detail['Order']['change']) { ?>
                              <li class="clearfix">
                                  <div class="row">
                                      <div class="col-md-3 col-sm-4 col-xs-4 sub-txt change_price_txt">Change 找零</div>
                                      <div class="col-md-3 col-sm-4 col-xs-4 sub-price change_price">$<?php echo $Order_detail['Order']['change']; ?></div>
                                  </div>
                              </li>
                              <?php }?>

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
                if($Order_detail['Order']['table_status'] <> 'P') {
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

                        <button type="button" class="btn btn-success card-ok"  id="submit"><?php echo $this->Html->image("right.png", array('alt' => "right")); ?> Ok 好</button>
                        <input type="hidden" id="selected_card" value="" />
                        <input type="hidden" id="card_val" name="card_val" value="" />
                        <input type="hidden" id="cash_val" name="cash_val" value="" />
                        <input type="hidden" id="tip_val"name="tip" value="" />
                    </div>

                <?php }?>
                </div>
            </div>
        </div>
        <div style="display:none" >

<div class="order-summary" id="print_panel">
                        <h3 class="dianming">嘿小面</h3>
                        <h4 class="dianming">3700 Midland Ave. #108</h4>
                        <h4 class="dianming">Scarborougn On M1V 0B3</h4>
                        <h4 class="dianming">647-352-5333</h4>
                        <h5></h5>
                        <div class="order-summary-indent clearfix">
                        <div>Order Number 订单号 #<?php echo $Order_detail['Order']['order_no'] ?>, Table 桌 <?php echo  (($type=='D') ? '[[堂食]]' : (($type=='T') ? '[[外卖]]' : (($type=='W') ? '[[等候]]' : ''))); ?>#<?php echo $table; ?></div><br>
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
                                                <div class="pull-left avoid-this">
                                                  <?php 
                                                  if ($value['image']) { 
                                                    echo $this->Html->image(TIMB_PATH."timthumb.php?src=".COUSINE_IMAGE_PATH . $value['image']."&h=42&w=62&&zc=4&Q=100", array('border' => 0, 'alt'=>'Product', 'class'=>'img-responsive'));
                                                  } else {
                                                    echo $this->Html->image(TIMB_PATH."timthumb.php?src=".TIMB_PATH . 'no_image.jpg'."&h=42&w=62&&zc=4&Q=100", array('border' => 0, 'alt'=>'Product', 'class'=>'img-responsive')); 
                                                  } 
                                                  ?>
                                                </div>
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
                        <hr>
                        <div class="clearfix total-payment" style="background-color:#fff; border:none; box-shadow:none">
						
					   <table style="width:100%;">
                            <tr>
                              <td width="60%" class="sub-txt">Sub Total 小计 </td>
                              <td width="40%" class="text-right sub-txt">$<?php echo number_format($Order_detail['Order']['subtotal'], 2) ?></td>
                            </<tr>

                            <tr>
                              <td class=" sub-txt">Tax 税 (<?php echo $Order_detail['Order']['tax'] ?>%)</td>
                              <td class="sub-price">$<?php echo number_format($Order_detail['Order']['tax_amount'], 2) ?></td>
                            </tr>

                            <tr>
                              <td class="sub-txt">Total 总</td>
                              <td class="sub-price total_price" alt="<?php echo $Order_detail['Order']['total']; ?>">$<?php echo number_format($Order_detail['Order']['total'], 2) ?></td>
                            </tr>
                                             
                        </table >
                    </div>
                    <div style="height:50px;"></div>
        </div>
        <!-- Print for kitchen -->
        <div class="order-summary" id="print_panel_2">
                        <h3 class="dianming">去后厨的单子</h3>
                        <h3><?php echo  (($type=='D') ? '[[堂食]]' : (($type=='T') ? '[[外卖]]' : (($type=='W') ? '[[等候]]' : ''))); ?> 桌号 #<?php echo $table; ?></h3>
                        <h5></h5>
                        <div class="order-summary-indent clearfix">
                        <div>Order Number 订单号 #<?php echo $Order_detail['Order']['order_no'] ?></div><br>
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
              $enameArr = explode(" ", $v['name']);
              $selected_extras_name[] = array_pop($enameArr);
                                                 // $selected_extras_name[] = $v['name'];
                                                  $selected_extras_id[] = $v['id'];
                                              }
                                          }
                                      }
                                    ?>
                                    <li class="clearfix">
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <div class="pull-left avoid-this">
                                                  <?php 
                                                  if ($value['image']) { 
                                                    echo $this->Html->image(TIMB_PATH."timthumb.php?src=".COUSINE_IMAGE_PATH . $value['image']."&h=42&w=62&&zc=4&Q=100", array('border' => 0, 'alt'=>'Product', 'class'=>'img-responsive'));
                                                  } else {
                                                    echo $this->Html->image(TIMB_PATH."timthumb.php?src=".TIMB_PATH . 'no_image.jpg'."&h=42&w=62&&zc=4&Q=100", array('border' => 0, 'alt'=>'Product', 'class'=>'img-responsive')); 
                                                  } 
                                                  ?>
                                                </div>
                                                <div class="pull-left titlebox1">
                                                    <!-- to show name of item -->
                                                    <div class="less-title"><?php echo $value['name_xh']; ?></div>

                                                    <!-- to show the extras item name -->
                                                    <div class="less-txt"><?php echo implode(",", $selected_extras_name); ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                <?php }
                            }?>
                            </ul>
                        </div>
                        <hr>
        </div>
<?php
echo $this->Html->script(array('jquery.min.js', 'bootstrap.min.js', 'jQuery.print.js'));
echo $this->fetch('script');
?>
<script>
$(document).on('click', '.reprint', function () {
    //Print ele4 with custom options
    $("#print_panel").print({
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
        // prepend : "<h2></h2>",
        //Add this on bottom
        // append : "<br/>Buh Bye!"
    });
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
        // prepend : "<h2></h2>",
        //Add this on bottom
        // append : "<br/>Buh Bye!"
    });
});
$(document).ready(function(){

   $(".select_card"). click(function() {
    $(".select_card").removeClass("active")
    $(this).addClass("active")
    var type = $(this).attr("id");
    if(type == 'card') {
        var cash_val = $("#cash_val").val()?parseFloat($("#cash_val").val())*100:0;
        $("#cash").removeClass("active");
        $("#screen").attr('buffer', cash_val);
        $("#screen").val($("#cash_val").val());
    }
    else if(type == 'cash') {
        var card_val = $("#card_val").val()?parseFloat($("#card_val").val())*100:0;
        $("#screen").attr('buffer', card_val);
        $("#screen").val($("#card_val").val());
    } else {
        var tip_val = $("#tip_val").val()?parseFloat($("#tip_val").val())*100:0;
        $("#screen").attr('buffer', tip_val);
        $("#screen").val($("#tip_val").val());
    }
    $("#selected_card").val(type);
  })



$(".select_tip"). click(function() {
    $(".select_card").removeClass("active");
    $(this).toggleClass("active");
    var val = $("#tip_val").val()?parseFloat($("#tip_val").val())*100:0;
    $("#screen").attr('buffer', val);
    $("#screen").val($("#tip_val").val());
})



  $("#submit").click(function() {
    if($("#selected_card").val()) {
      if(parseFloat($(".change_price").attr("amount")) >= 0) {

        // submit form for complete payment process
        $.ajax({
             url: "<?php echo $this->Html->url(array('controller'=>'homes', 'action'=>'donepayment', $table, $type)); ?>",
             method:"post",
             data:{
                pay:$(".received_price").attr("amount"),
                paid_by:$("#selected_card").val() ,
                change:$(".change_price").attr("amount"),
                table: "<?php echo $table ?>",
                type: "<?php echo $type ?>",
                order_id: "<?php echo $Order_detail['Order']['id'] ?>",

                card_val:$("#card_val").val(),
                cash_val:$("#cash_val").val(),
                tip_val:$("#tip_val").val(),
              },
             success:function(html) {
                $(".alert-warning").hide();
                $(".reprint").trigger("click");
                window.location = "<?php echo $this->Html->url(array('controller'=>'homes', 'action'=>'dashboard')); ?>";
             },
             beforeSend:function() {
                $(".alert-warning").show();
             }
        })
      } else {
        alert("Invalid amount, please check and verfy again 金额无效，请检查并再次验证.");
        return false;
      }
    } else {
      alert("Please select card or cash payment method 请选择卡或现金付款方式. ");
      return false;
    }
  })

  $(".card-indent li").click(function() {
    if(!$("#selected_card").val() && !$(".select_tip").hasClass("active")) {
      alert("Please select payment type cash/card or select tip.");
      return false;
    }

    if($(this).hasClass("clear-txt") || $(this).hasClass("enter-txt"))
      return false;

    var digit =  parseInt($(this).html());
    var nums = $("#screen").attr('buffer')+digit;

    // store buffer value
    $("#screen").attr('buffer', nums);
    nums = nums/100;
    nums = nums.toFixed(2);
    if(nums.length < 12)
      $("#screen").val(nums).focus();
    else
      $("#screen").focus();
  })

  $("#Enter").click(function() {
    if(!$("#selected_card").val()) {
      alert("Please select payment type card/cash.");
      return false;
    }
    var amount = $("#screen").val()?parseFloat($("#screen").val()):0;
    var total_price = parseFloat($(".total_price").attr("alt"));

    if($("#selected_card").val() == 'cash') {
      $("#card_val").val(amount.toFixed(2));
      $(".cash_price").html("Cash 现金: $"+amount.toFixed(2));
    }if($("#selected_card").val() == 'card') {
      $("#cash_val").val(amount.toFixed(2));
      $(".card_price").html("Card 卡: $"+amount.toFixed(2));
    }
    if($("#selected_card").val() == 'tip') { 
      $("#tip_val").val(amount.toFixed(2));
      $(".tip_price").html("$"+amount.toFixed(2));
    }

    var cash_val = $("#cash_val").val()?parseFloat($("#cash_val").val()):0;
    var card_val = $("#card_val").val()?parseFloat($("#card_val").val()):0;


    amount = cash_val + card_val;
    if(amount) {
      $(".received_price").html("$"+amount.toFixed(2));
      $(".received_price").attr('amount', amount.toFixed(2));
      $(".change_price").html("$"+(amount - total_price).toFixed(2));
      $(".change_price").attr('amount', (amount - total_price).toFixed(2));

      if((amount - total_price)<0){
        $(".change_price_txt").html("Remaining 其余");
        $(".change_price").html("$"+(total_price-amount).toFixed(2));
      }
      else{
        $(".change_price_txt").html("Change 找零");
      }

    } else {
      return false;
    }
  })

  $("#rc1").click(function(E) {
    E.preventDefault();
  })

$("#Clear").click(function() {

  $("#screen").val("");
  $("#screen").focus();
  $("#screen").attr('buffer', 0);

  $(".received_price").html("$00.00");
  $(".received_price").attr('amount', 0);
  $(".change_price").html("$00.00");
  $(".change_price").attr('amount', 0);

  $(".cash_price").html("Cash 现金: $00.00");
  $(".card_price").html("Card 卡: $00.00");

  $("#cash_val").val(0);
  $("#card_val").val(0);
})

  $("#screen").keydown(function (e) {
      // Allow: backspace, delete, tab, escape, enter and .
      if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
           // Allow: Ctrl+A, Command+A
          (e.keyCode == 65 && ( e.ctrlKey === true || e.metaKey === true ) ) || 
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
})

    $(document).on("keyup", ".discount_section", function() {
      if($(this).val()) {
        $(".discount_section").attr("disabled", "disabled");
        $(this).removeAttr("disabled");
      } else {
        $(".discount_section").removeAttr("disabled");
      }
    })

    $(document).on("click", "#apply-discount", function() {

      var fix_discount = $("#fix_discount").val();
      var discount_percent = $("#discount_percent").val();
      var promocode = $("#promocode").val(); 

      if(fix_discount || discount_percent || promocode) {
        // apply promocode here
        $.ajax({
             url: "<?php echo $this->Html->url(array('controller'=>'homes', 'action'=>'add_discount')); ?>",
             method:"post",
             dataType:"json",
             data:{fix_discount:fix_discount, discount_percent:discount_percent, promocode:promocode, order_id:"<?php echo $Order_detail['Order']['id'] ?>"},
             success:function(html) {
              if(html.error) {
                alert(html.message);
                $(".discount_section").val("").removeAttr("disabled");
                $(".RIGHT-SECTION").removeClass('load1 csspinner');
                return false;
              } else {
                window.location.reload();
              }
             },
             beforeSend:function() {
                $(".RIGHT-SECTION").addClass('load1 csspinner');
             }
        })


      } else {
        alert("Please add discount first.");
        return false;
      }
    })

    $(document).on('click', ".remove_discount", function() {
        var order_id = "<?php echo $Order_detail['Order']['id'] ?>";
        var message = $("#Message").val();
        $.ajax({
             url: "<?php echo $this->Html->url(array('controller'=>'homes', 'action'=>'remove_discount')); ?>",
             method:"post",
             data:{order_id:order_id},
             success:function(html) {
                window.location.reload();
             },
             beforeSend:function() {
                $(".RIGHT-SECTION").addClass('load1 csspinner');
             }
        })
    })
    $(document).on('click', ".add-discount", function() {
      $(".discount_view").toggle();
    });
</script>
