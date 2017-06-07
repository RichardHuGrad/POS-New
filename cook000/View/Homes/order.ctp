<header class="product-header">
  <div class="container">
      <div class="home-logo"><a href="<?php echo $this->Html->url(array('controller'=>'homes','action'=>'dashboard')) ?>">
          <?php echo $this->Html->image("logo-home.jpg", array('alt' => "POS")); ?>
      </a></div>
      <div class="logout"><a href="<?php echo $this->Html->url(array('controller'=>'homes','action'=>'logout')) ?>">Logout 登出</a></div>

      <ul class="nav nav-tabs text-center">
          <li class="active"><a data-toggle="tab" href="#Popular">Popular<br/>流行</a></li>
          <?php
          if (!empty($records)) {
              foreach ($records as $category) {
                  ?>                
                  <li><a data-toggle="tab" href="#tab<?php echo $category['Category']['id']; ?>"><?php echo $category['Category']['eng_name']."<br/>".$category['Category']['zh_name']; ?></a></li>
                  <?php
              }
          }
          ?>
      </ul>
  </div>
</header>

<div class="container">
  <div class="clearfix cartwrap-wrap">
     <div class="row">
       
       <div class="col-md-9 col-sm-8 col-xs-12 home-link">
        <div class="clearfix marginB10">
          <a href="<?php echo $this->Html->url(array('controller'=>'homes','action'=>'index')) ?>" class="submitbtn" >Home 家</a>
          <a href="<?php echo $this->Html->url(array('controller'=>'homes','action'=>'dashboard')) ?>" class="submitbtn" >Back 背部</a>
        </div>

        <div class="cart-txt">
          Order 订购 #<?php echo @$Order_detail['Order']['order_no'] ?>, Table 表 #<?php echo $table;  ?>
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
  </div>
    <div class="clearfix cart-wrap">
        <div class="col-md-4 col-sm-4 col-xs-12 summary_box">
            <div class="clearfix marginB15 cashierbox">
                <div class="pull-left marginR5">
                    <?php if ($cashier_detail['Cashier']['image']) { ?>
                        <?php echo $this->Html->image(TIMB_PATH."timthumb.php?src=".CASHIER_IMAGE_PATH . $cashier_detail['Cashier']['image']."&h=60&w=60&&zc=4&Q=100", array('class'=>'img-circle img-responsive')); ?>
                    <?php } else { ?>
                        <?php echo $this->Html->image(TIMB_PATH."timthumb.php?src=".TIMB_PATH . 'no_image.jpg'."&h=60&w=60&&zc=4&Q=100", array('class'=>'img-circle img-responsive'));  ?>
                    <?php } ?>
                </div>
                <div class="pull-left marginL5 clearfix">
                    <div class="txt16 marginB5 marginT5"><?php echo ucfirst($cashier_detail['Cashier']['firstname'])." ".$cashier_detail['Cashier']['lastname']; ?></div>
                    <div class="txt15"><?php echo str_pad($cashier_detail['Cashier']['id'], 4, 0, STR_PAD_LEFT); ?></div>
                </div>
            </div>
        </div>
        <div class="col-md-8 col-sm-8 col-xs-12 products-panel">
            <div class="tab-content">

                <div id="Popular" class="tab-pane fade in active">
                  <div class="clearfix">
                      <div class="clearfix row productbox">
                        <?php if(!empty($populars)) { ?>
                          <ul>
                            <?php 
                            foreach($populars as $items) {
                              ?>
                              <li class="col-md-4 col-sm-4 col-xs-6 add_items" alt="<?php echo $items['Cousine']['id']; ?>" title="Add to Cart">
                                  <div class="">
                                    <center>
                                      <?php 
                                      if ($items['Cousine']['image']) { 
                                        echo $this->Html->image(TIMB_PATH."timthumb.php?src=".COUSINE_IMAGE_PATH . $items['Cousine']['image']."&h=184&w=220&&zc=4&Q=100", array('border' => 0, 'alt'=>'Product', 'class'=>'img-responsive'));
                                      } else {
                                        echo $this->Html->image(TIMB_PATH."timthumb.php?src=".TIMB_PATH . 'no_image.jpg'."&h=184&w=220&&zc=4&Q=100", array('border' => 0, 'alt'=>'Product', 'class'=>'img-responsive')); 
                                      } 
                                      ?>
                                  </center>
                                </div>
                                <div class="clearfix padding10 row">
                                    <div class="txt16 pull-left col-md-8 col-sm-7 col-xs-7"><div class="name-title"><strong><?php echo $items['Cousine']['eng_name']."<br/>".$items['Cousine']['zh_name']; ?></strong></div></div>
                                    <div class="pull-right txt15 col-md-4 col-sm-5 col-xs-5">$<?php echo number_format($items['Cousine']['price'], 2); ?></div>
                                </div>
                              </li>
                              <?php
                            }
                            ?>
                          </ul>
                        <?php } else {
                          echo "<div class='noitems'>No Items Available</div>";
                        }?>
                      </div>
                  </div>
                </div>  
                <?php
                if (!empty($records)) {
                    $count = 0;
                    foreach ($records as $category) {
                      $count++;
                        ?>
                        <div id="tab<?php echo $category['Category']['id']; ?>" class="tab-pane fade in">
                          <div class="clearfix">
                              <div class="clearfix row productbox">
                                <?php if(!empty($category['Cousine'])) { ?>
                                  <ul>
                                    <?php 
                                    foreach($category['Cousine'] as $items) {
                                      ?>
                                      <li class="col-md-4 col-sm-4 col-xs-6 add_items" alt="<?php echo $items['id']; ?>" title="Add to Cart">
                                          <div class="">
                                            <center>
                                              <?php 
                                              if ($items['image']) { 
                                                echo $this->Html->image(TIMB_PATH."timthumb.php?src=".COUSINE_IMAGE_PATH . $items['image']."&h=184&w=220&&zc=4&Q=100", array('border' => 0, 'alt'=>'Product', 'class'=>'img-responsive'));
                                              } else {
                                                echo $this->Html->image(TIMB_PATH."timthumb.php?src=".TIMB_PATH . 'no_image.jpg'."&h=184&w=220&&zc=4&Q=100", array('border' => 0, 'alt'=>'Product', 'class'=>'img-responsive')); 
                                              } 
                                              ?>
                                          </center>
                                        </div>
                                        <div class="clearfix padding10 row">
                                            <div class="txt16 pull-left col-md-8 col-sm-7 col-xs-7"><div class="name-title"><strong><?php echo $items['eng_name']."<br/>".$items['zh_name']; ?></strong></div></div>
                                            <div class="pull-right txt15 col-md-4 col-sm-5 col-xs-5">$<?php echo number_format($items['price'], 2); ?></div>
                                        </div>
                                      </li>
                                      <?php
                                    }
                                    ?>
                                  </ul>
                                <?php } else {
                                  echo "<div class='noitems'>No Items Available</div>";
                                }?>
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
</div>
<?php
echo $this->Html->script(array('jquery.min.js', 'bootstrap.min.js', 'jquery.mCustomScrollbar.concat.min.js'));
echo $this->fetch('script');
?>
<script>
    (function ($) {
        $(window).on("load", function () {
            $(".productbox").mCustomScrollbar({
                setHeight: 770,
                theme: "dark-3"
            });
        });
    })(jQuery);
    $(document).on('click', ".add_items", function() {
        var item_id = $(this).attr("alt");
        var message = $("#Message").val();
        $.ajax({
             url: "<?php echo $this->Html->url(array('controller'=>'homes', 'action'=>'additems')); ?>",
             method:"post",
             data:{item_id:item_id, table: "<?php echo $table ?>", type: "<?php echo $type ?>"},
             success:function(html) {
                $(".summary_box").html(html);
                $(".products-panel").removeClass('load1 csspinner');
             },
             beforeSend:function() {
                $(".products-panel").addClass('load1 csspinner');
             }
        })
    })
    
    $(document).on('click', ".close-link", function() {
        var item_id = $(this).attr("alt");
        var order_id = $(this).attr("order_id");
        var message = $("#Message").val();
        $.ajax({
             url: "<?php echo $this->Html->url(array('controller'=>'homes', 'action'=>'removeitem')); ?>",
             method:"post",
             data:{item_id:item_id, order_id:order_id, table: "<?php echo $table ?>", type: "<?php echo $type ?>"},
             success:function(html) {
                $(".summary_box").html(html);
                $(".summary_box").removeClass('load1 csspinner');
             },
             beforeSend:function() {
                $(".summary_box").addClass('load1 csspinner');
             }
        })
    })

    $(document).on('click', ".add_extras", function() {
        $(this).toggleClass("active");
    })

    $(document).on("click", '.sub-items', function(e) {
        e.stopPropagation();
    })

    $(document).on("click", "#submit", function(){
      // update order message here
      var order_id = $(this).attr("alt");
      $.ajax({
             url: "<?php echo $this->Html->url(array('controller'=>'homes', 'action'=>'updateordermessage')); ?>",
             method:"post",
             data:{order_id: order_id, message:$("#Message").val(), is_kitchen:"Y"},
             success:function(html) {
                window.location = "<?php echo $this->Html->url(array('controller'=>'homes', 'action'=>'index')); ?>";
             },
             beforeSend:function() {
                $(".summary_box").addClass('load1 csspinner');
             }
        })

    });
    $(document).on("click", "#pay", function(){
      // update order message here
      var order_id = $(this).attr("alt");
      $.ajax({
             url: "<?php echo $this->Html->url(array('controller'=>'homes', 'action'=>'updateordermessage')); ?>",
             method:"post",
             data:{order_id: order_id, message:$("#Message").val(), is_kitchen:"N"},
             success:function(html) {
              window.location = "<?php echo $this->Html->url(array('controller'=>'homes', 'action'=>'pay', 'table'=>$table, 'type'=>$type)); ?>";
             },
             beforeSend:function() {
                $(".summary_box").addClass('load1 csspinner');
             }
        })
        
    });

    $(document).on("click", ".savebtn", function(){
        var id = $(this).attr("alt");
        var message = $("#Message").val();
        var array = new Array();

        // get all selected extras items of menu
        $("#sub_"+id+" li a.active").each(function(){
          array.push($(this).attr('alt')); 
        });
        var input_value = array.toString();

        $.ajax({
             url: "<?php echo $this->Html->url(array('controller'=>'homes', 'action'=>'add_extras')); ?>",
             method:"post",
             data:{item_id:id, extras:input_value, table: "<?php echo $table ?>", type: "<?php echo $type ?>"},
             success:function(html) {
                $(".summary_box").html(html);
                $(".products-panel").removeClass('load1 csspinner');
             },
             beforeSend:function() {
                $(".products-panel").addClass('load1 csspinner');
             }
        })
    })
    
    $(document).ready(function() {

        $(".search-clear").click(function(){
            $("#search-input").val('');
            $("#search-input").focus();
            $(".add_items").show();
        })

        $("#search-input").on("keyup", function() {
            var value = $(this).val();

            $(".add_items").each(function(index) {
                // if (index !== 0) {

                    $row = $(this);

                    var id = $row.find("strong").text();//alert(id+" "+id.indexOf(value) );

                    if (id.toLowerCase().indexOf(value) < 0) {
                        $row.hide();
                    }
                    else {
                        $row.show();
                    }
                // }
            });
        });

        $.ajax({
             url: "<?php echo $this->Html->url(array('controller'=>'homes', 'action'=>'summarypanel', $table, $type)); ?>",
             method:"post",
             success:function(html) {
                $(".summary_box").html(html);
                $(".products-panel").removeClass('load1 csspinner');
             },
             beforeSend:function() {
                $(".products-panel").addClass('load1 csspinner');
             }
        })
    })
    


</script>