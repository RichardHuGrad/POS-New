<header class="product-header">
  <div class="container">
      <div class="home-logo"><a href="<?php echo $this->Html->url(array('controller'=>'homes','action'=>'dashboard')) ?>">
          <?php echo $this->Html->image("logo-home.jpg", array('alt' => "POS")); ?>
      </a></div>
      <div class="logout"><a href="<?php echo $this->Html->url(array('controller'=>'homes','action'=>'logout')) ?>">Logout 登出</a></div>
  </div>
</header>
        <div class="container">
           <div class="clearfix cartwrap-wrap">
     <div class="row">       
       <div class="col-md-9 col-sm-8 col-xs-12 home-link">
        <div class="clearfix">
          <a href="<?php echo $this->Html->url(array('controller'=>'homes','action'=>'index')) ?>" class="submitbtn" >Home 家</a>
        </div>
       </div>
    </div>
  </div>
            <div class="order-wrap">
            <?php echo $this->Session->flash(); ?>
                <div class="col-md-4 col-sm-4 col-xs-12 order-left">
                    <h2>Order 订购 #<?php echo $Order_detail['Order']['order_no'] ?>, Table 表 #<?php echo $table; ?></h2>

                    <div class="paid-box">
                        <div class="checkbox-btn">
                            <input type="checkbox" value="value-1" id="rc1" name="rc1" <?php if($Order_detail['Order']['table_status'] == 'P') echo "checked='checked'"; ?>/>
                            <label for="rc1" disabled>Paid 付费</label>
                        </div>
                    </div>
                    <?php 
                    if($Order_detail['Order']['table_status']<> 'P') {
                    ?>
                      <div class="table-box dropdown"><a href="" class="dropdown-toggle"  data-toggle="dropdown">Change Table 更改表</a>
                        <ul class="dropdown-menu">
                            <div class="customchangemenu clearfix">
                            <div class="left-arrow"></div>
                            <div class="col-md-12 col-sm-12 col-xs-12 text-center timetable">DINE IN 在用餐</div>
                            <?php for($t = 1; $t <= DINEIN_TABLE; $t++) {
                                if(!@$orders_no[$t]['D']){ ?>
                               <div class="col-md-6 col-sm-6 col-xs-6 text-center timetable"><a href="<?php echo $this->Html->url(array('controller'=>'homes', 'action'=>'move_order', 'table'=>$t, 'type'=>'D', 'order_no'=>@$Order_detail['Order']['order_no'], 'ref'=>'pay' ));?>"><?php echo $t; ?></a></div>
                            <?php }}?>
                            <div class="col-md-12 col-sm-12 col-xs-12 text-center timetable">TAKE OUT 取出</div>
                            <?php for($t = 1; $t <= TAKEOUT_TABLE; $t++) {
                            if(!@$orders_no[$t]['T']){  ?>
                               <div class="col-md-6 col-sm-6 col-xs-6 text-center timetable"><a href="<?php echo $this->Html->url(array('controller'=>'homes', 'action'=>'move_order', 'table'=>$t, 'type'=>'T', 'order_no'=>@$Order_detail['Order']['order_no'], 'ref'=>'pay' ));?>"><?php echo $t; ?></a></div>
                            <?php } }?>
                            <div class="col-md-12 col-sm-12 col-xs-12 text-center timetable">Delivery 送餐</div>
                            <?php for($t = 1; $t <= WAITING_TABLE; $t++) {
                            if(!@$orders_no[$t]['W']){  ?>
                               <div class="col-md-6 col-sm-6 col-xs-6 text-center timetable"><a href="<?php echo $this->Html->url(array('controller'=>'homes', 'action'=>'move_order', 'table'=>$t, 'type'=>'W', 'order_no'=>@$Order_detail['Order']['order_no'], 'ref'=>'pay' ));?>"><?php echo $t; ?></a></div>
                            <?php } }?>
                        </div>
                      </ul>
                      </div>
                    <?php }?>
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
                    <div class="avoid-this text-center reprint"><button type="button" class="submitbtn">Print Receipt 打印收据</button></div>
                </div>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <div class="clearfix total-payment">
                        <ul>
                            <li class="clearfix">
                                <div class="row">
                                    <div class="col-md-3 col-sm-4 col-xs-4 sub-txt">Sub Total 小计 </div>
                                    <div class="col-md-3 col-sm-4 col-xs-4 sub-price">$<?php echo number_format($Order_detail['Order']['subtotal'], 2) ?></div>

                                  <?php 
                                  if($Order_detail['Order']['table_status'] <> 'P') {
                                  ?>
                                    <div class="col-md-6 col-sm-4 col-xs-4"><button type="button" class="addbtn pull-right"><i class="fa fa-plus-circle" aria-hidden="true"></i> Add Discount 加入折扣</button></div>
                                    <?php }?>
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
                                    <div class="col-md-3 col-sm-4 col-xs-4 sub-txt">Total 总</div>
                                    <div class="col-md-3 col-sm-4 col-xs-4 sub-price total_price" alt="<?php echo $Order_detail['Order']['total']; ?>">$<?php echo number_format($Order_detail['Order']['total'], 2) ?></div>
                                </div>
                            </li>
                            <?php 
                            if($Order_detail['Order']['table_status'] == 'P') {
                              ?>
                              <li class="clearfix">
                                  <div class="row">
                                      <div class="col-md-3 col-sm-4 col-xs-4 sub-txt">Receive 接收</div>
                                      <div class="col-md-3 col-sm-4 col-xs-4 sub-price received_price">$<?php echo $Order_detail['Order']['paid']; ?></div>
                                  </div>
                              </li>

                              <li class="clearfix">
                                  <div class="row">
                                      <div class="col-md-3 col-sm-4 col-xs-4 sub-txt">Change 更改</div>
                                      <div class="col-md-3 col-sm-4 col-xs-4 sub-price change_price">$<?php echo $Order_detail['Order']['change']; ?></div>
                                  </div>
                              </li>
                              <?php
                            } else {
                              ?>
                              <li class="clearfix">
                                  <div class="row">
                                      <div class="col-md-3 col-sm-4 col-xs-4 sub-txt">Receive 接收</div>
                                      <div class="col-md-3 col-sm-4 col-xs-4 sub-price received_price">$00.00</div>
                                  </div>
                              </li>

                              <li class="clearfix">
                                  <div class="row">
                                      <div class="col-md-3 col-sm-4 col-xs-4 sub-txt">Change 更改</div>
                                      <div class="col-md-3 col-sm-4 col-xs-4 sub-price change_price">$00.00</div>
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

                            <li class="clear-txt" id="Clear">Clear 明确</li>
                            <li>0</li>
                            <li class="enter-txt" id="Enter">Enter 输入</li>
                        </ul>
                    </div>

                    <div class="card-bot clearfix text-center">
                        <button type="button" class="btn btn-danger select_card" id="card"> <?php echo $this->Html->image("card.png", array('alt' => "card")); ?> Card 卡</button>
                        <button type="button" class="btn btn-warning select_card"  id="cash"><?php echo $this->Html->image("cash.png", array('alt' => "cash")); ?> Cash 现金</button>
                        <button type="button" class="btn btn-success card-ok"  id="submit"><?php echo $this->Html->image("right.png", array('alt' => "right")); ?> Ok 好</button>
                        <input type="hidden" id="selected_card" value="" />
                    </div>

                <?php }?>
                </div>
            </div>
        </div>
        <div style="display:none" >

<div class="order-summary" id="print_panel">
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
                        <div class="clearfix total-payment" style="background-color:#fff; border:none; box-shadow:none">
                        <ul>
                            <li class="clearfix">
                                <div class="row">
                                    <div class="col-md-6 col-sm-4 col-xs-4 sub-txt">Sub Total 小计 </div>
                                    <div class="col-md-3 col-sm-4 col-xs-4 sub-price">$<?php echo number_format($Order_detail['Order']['subtotal'], 2) ?></div>
                                </div>
                            </li>

                            <li class="clearfix">
                                <div class="row">
                                    <div class="col-md-6 col-sm-4 col-xs-4 sub-txt">Tax 税 (<?php echo $Order_detail['Order']['tax'] ?>%)</div>
                                    <div class="col-md-3 col-sm-4 col-xs-4 sub-price">$<?php echo number_format($Order_detail['Order']['tax_amount'], 2) ?></div>
                                </div>
                            </li>

                            <li class="clearfix">
                                <div class="row">
                                    <div class="col-md-6 col-sm-4 col-xs-4 sub-txt">Total 总</div>
                                    <div class="col-md-3 col-sm-4 col-xs-4 sub-price total_price" alt="<?php echo $Order_detail['Order']['total']; ?>">$<?php echo number_format($Order_detail['Order']['total'], 2) ?></div>
                                </div>
                            </li>
                            <?php 
                            if($Order_detail['Order']['table_status'] == 'P') {
                              ?>
                              <li class="clearfix">
                                  <div class="row">
                                      <div class="col-md-6 col-sm-4 col-xs-4 sub-txt">Receive 接收</div>
                                      <div class="col-md-3 col-sm-4 col-xs-4 sub-price received_price">$<?php echo $Order_detail['Order']['paid']; ?></div>
                                  </div>
                              </li>

                              <li class="clearfix">
                                  <div class="row">
                                      <div class="col-md-6 col-sm-4 col-xs-4 sub-txt">Change 更改</div>
                                      <div class="col-md-3 col-sm-4 col-xs-4 sub-price change_price">$<?php echo $Order_detail['Order']['change']; ?></div>
                                  </div>
                              </li>
                              <?php
                            } else {
                              ?>
                              <li class="clearfix">
                                  <div class="row">
                                      <div class="col-md-6 col-sm-4 col-xs-4 sub-txt">Receive 接收</div>
                                      <div class="col-md-3 col-sm-4 col-xs-4 sub-price received_price">$00.00</div>
                                  </div>
                              </li>

                              <li class="clearfix">
                                  <div class="row">
                                      <div class="col-md-6 col-sm-4 col-xs-4 sub-txt">Change 更改</div>
                                      <div class="col-md-3 col-sm-4 col-xs-4 sub-price change_price">$00.00</div>
                                  </div>
                              </li>                              
                              <?php
                            }
                            ?>                            
                        </ul>
                    </div>
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
$(document).ready(function(){

  $(".select_card"). click(function() {
    $(this).addClass("active")
    var type = $(this).attr("id");
    if(type == 'card')
        $("#cash").removeClass("active")
    else
        $("#card").removeClass("active")

    $("#selected_card").val(type);
  })

  $("#submit").click(function() {
    if($("#selected_card").val()) {
      if(parseFloat($(".change_price").attr("amount")) > 0) {

        // submit form for complete payment process
        $.ajax({
             url: "<?php echo $this->Html->url(array('controller'=>'homes', 'action'=>'donepayment', $table, $type)); ?>",
             method:"post",
             data:{pay:$(".received_price").attr("amount"), paid_by:$("#selected_card").val() , change:$(".change_price").attr("amount"), table: "<?php echo $table ?>", type: "<?php echo $type ?>", order_id: "<?php echo $Order_detail['Order']['id'] ?>"},
             success:function(html) {
                $(".alert-warning").hide();
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
    var amount = $("#screen").val()?parseFloat($("#screen").val()):0;
    var total_price = parseFloat($(".total_price").attr("alt"));
    if(amount) {
      $(".received_price").html("$"+amount.toFixed(2));
      $(".received_price").attr('amount', amount.toFixed(2));
      $(".change_price").html("$"+(amount - total_price).toFixed(2));
      $(".change_price").attr('amount', (amount - total_price).toFixed(2));
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
</script>