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

      <ul class="nav nav-tabs text-center">
          <!--<li class="active"><a data-toggle="tab" href="#Popular">Popular<br/>流行</a></li>-->
          <?php
          if (!empty($records)) {
              foreach ($records as $key => $category) {
                  ?>
                  <li <?php if($key == 0) echo "class='active'" ?>><a data-toggle="tab" href="#tab<?php echo $category['Category']['id']; ?>"><?php echo $category['Category']['eng_name']."<br/>".$category['Category']['zh_name']; ?></a></li>
                  <?php
              }
          }
          ?>
      </ul>

</header>


  <div class="clearfix cartwrap-wrap">      
       <div class="col-md-9 col-sm-8 col-xs-12 home-link">
        <div class="cart-txt">
          Order 订单号 #<?php echo @$Order_detail['Order']['order_no'] ?>, Table 桌 #<?php echo $table;  ?>
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
    
	
	<div class="clearfix cart-wrap">
        <div class="col-md-4 col-sm-5 col-xs-12 summary_box">
            <div class="clearfix marginB15 cashierbox" style="display:none">
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
		
		
        <div class="col-md-8 col-sm-7 col-xs-12 products-panel">
            <div class="tab-content">

                <!--<div id="Popular" class="tab-pane fade in active">
                  <div class="clearfix">
                      <div class="clearfix row productbox">
                        <?php //if(!empty($populars)) { ?>
                          <ul>
                            <?php 
                            //foreach($populars as $items) {
                              ?>
                              <li class="col-md-3 col-sm-4 col-xs-6 add_items" alt="<?php //echo $items['Cousine']['id']; ?>" title="Add to Cart">
                                  <div class="">
                                    <center>
                                      <?php 
                                      /*if ($items['Cousine']['image']) { 
                                        echo $this->Html->image(TIMB_PATH."timthumb.php?src=".COUSINE_IMAGE_PATH . $items['Cousine']['image']."&h=184&w=220&&zc=4&Q=100", array('border' => 0, 'alt'=>'Product', 'class'=>'img-responsive'));
                                      } else {
                                        echo $this->Html->image(TIMB_PATH."timthumb.php?src=".TIMB_PATH . 'no_image.jpg'."&h=184&w=220&&zc=4&Q=100", array('border' => 0, 'alt'=>'Product', 'class'=>'img-responsive')); 
                                      } */
                                      ?>
                                  </center>
                                </div>
                                <div class="clearfix padding10 row">
                                    <div class="txt13 pull-left col-md-8 col-sm-7 col-xs-7 padding"><div class="name-title"><?php //echo $items['Cousine']['eng_name']."<br/>".$items['Cousine']['zh_name']; ?></div></div>
                                    <div class="pull-right txt15 col-md-4 col-sm-5 col-xs-5">$<?php //echo number_format($items['Cousine']['price'], 2); ?></div>
                                </div>
                              </li>
                              <?php
                            //}
                            ?>
                          </ul>
                        <?php //} else {
                          //echo "<div class='noitems'>No Items Available</div>";
                        //}?>
                      </div>
                  </div>
                </div>-->  
                <?php
                if (!empty($records)) {
                    $count = 0;
                    foreach ($records as $key => $category) {
                      $count++;
                        ?>
                        <div id="tab<?php echo $category['Category']['id']; ?>" class="tab-pane fade in <?php if($key == 0) echo "active" ?>">
                          <div class="clearfix">
                              <div class="clearfix row productbox">
                                <?php if(!empty($category['Cousine'])) { ?>
                                  <ul>
                                    <?php 
                                    foreach($category['Cousine'] as $items) {
                                      ?>
                                      <li class="col-md-3 col-sm-4 col-xs-6 add_items" alt="<?php echo $items['id']; ?>" title="Add to Cart">
                                          <!--<div class="">
                                            <center>
                                              <?php 
                                              /*if ($items['image']) { 
                                                echo $this->Html->image(TIMB_PATH."timthumb.php?src=".COUSINE_IMAGE_PATH . $items['image']."&h=184&w=220&&zc=4&Q=100", array('border' => 0, 'alt'=>'Product', 'class'=>'img-responsive'));
                                              } else {
                                                echo $this->Html->image(TIMB_PATH."timthumb.php?src=".TIMB_PATH . 'no_image.jpg'."&h=184&w=220&&zc=4&Q=100", array('border' => 0, 'alt'=>'Product', 'class'=>'img-responsive')); 
                                              }*/
                                              ?>
                                          </center>
                                        </div>-->
                                        <div class="clearfix padding10 row">
                                            <div class="dish-title txt13 pull-left col-md-8 col-sm-7 col-xs-7"><div class="name-title"><strong><?php echo $items['eng_name']."<br/>".$items['zh_name']; ?></strong></div></div>
                                            <div class="dish-price pull-right txt15 col-md-4 col-sm-5 col-xs-5">$<?php echo number_format($items['price'], 2); ?></div>
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

<?php
echo $this->Html->script(array('jquery.min.js', 'bootstrap.min.js', 'jquery.mCustomScrollbar.concat.min.js', 'epos-print-5.0.0'));
echo $this->fetch('script');
?>
<script>
    (function ($) {
        $(window).on("load", function () {
            // $(".productbox").mCustomScrollbar({
            //     setHeight: 770,
            //     theme: "dark-3"
            // });
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
                $(".order-summary-indent").scrollTop($(".order-summary-indent ul").height());
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
      var item_id = $(this).attr("item_id");
      var price = $(this).attr("price");
      var name = $(this).attr("name");
      var extra_id = $(this).attr("alt");

      var html = '<div class="extras_inner" alt="'+extra_id+'"><span>'+name+'</span><span>'+(price!=0?price:"")+'</span><a class="fa fa-times remove_extra" href="javascript:void(0)"> </a></div>';

      $("#block"+item_id).append(html);

    })



    $(document).on('click', ".remove_extra", function() {
      $(this).parent(".extras_inner").remove();

    })



    $(document).on("click", '.sub-items', function(e) {
        e.stopPropagation();
    })

    $(document).on("click", "#submit", function(){

      // print receipts
      var builder = new epson.ePOSBuilder();
      builder.addTextLang('en');
      builder.addTextSmooth(true);
      builder.addTextFont(builder.FONT_A);
      builder.addTextSize(3, 3);
      builder.addText('Hello,\tKitchen printer!\n');
      builder.addCut(builder.CUT_FEED);
      var request = builder.toString();

      //Set the end point address
      var address = 'http://asd/cgi-bin/epos/service.cgi?devid=dfgdfg&timeout=100' ;
      //Create an ePOS-Print object
      var epos = new epson.ePOSPrint(address);
      //Set a response receipt callback function
      epos.onreceive = function (res) {
        alert("ok");
      //When the printing is not successful, display a message
        if (!res.success) {
          alert('A print error occurred');
        }
      }
      //Send the print document
      epos.send(request);
      

      // update order message here
      var order_id = $(this).attr("alt");
      $.ajax({
         url: "<?php echo $this->Html->url(array('controller'=>'homes', 'action'=>'updateordermessage')); ?>",
         method:"post",
         data:{order_id: order_id, message:$("#Message").val(), is_kitchen:"Y"},
         success:function(html) {
            //window.location = "<?php echo $this->Html->url(array('controller'=>'homes', 'action'=>'dashboard')); ?>";
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
        $("#block"+id+" div.extras_inner").each(function(){
          array.push($(this).attr('alt')); 
        });
        var input_value = array.toString();
        var element = $(this).parent("ul.dropdown-menu");

        $.ajax({
             url: "<?php echo $this->Html->url(array('controller'=>'homes', 'action'=>'add_extras')); ?>",
             method:"post",
             data:{item_id:id, extras:input_value, table: "<?php echo $table ?>", type: "<?php echo $type ?>"},
             success:function(html) {
                $(".summary_box").html(html);
                $(".products-panel").removeClass('load1 csspinner');
                $(".clearfix.cart-wrap").removeClass("csspinner");
             },
             beforeSend:function() {
                // $(".products-panel").addClass('load1 csspinner');
                element.addClass('load1 csspinner');
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

                    var id = $row.find(".name-title").text();//alert(id+" "+id.indexOf(value) );

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
    });

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
             data:{fix_discount:fix_discount, discount_percent:discount_percent, promocode:promocode, order_id:$("#pay").attr("alt")},
             success:function(html) {
              if(html.error) {
                alert(html.message);
                $(".discount_section").val("").removeAttr("disabled");                
                $(".products-panel").removeClass('load1 csspinner');
                $(".summary_box").removeClass('load1 csspinner');
                return false;
              } else {
                $.ajax({
                     url: "<?php echo $this->Html->url(array('controller'=>'homes', 'action'=>'summarypanel', $table, $type)); ?>",
                     method:"post",
                     success:function(html) {
                        $(".summary_box").html(html);                        
                        $(".products-panel").removeClass('load1 csspinner');
                        $(".summary_box").removeClass('load1 csspinner');
                     }
                })
              }
             },
             beforeSend:function() {
                $(".products-panel").addClass('load1 csspinner');
                $(".summary_box").addClass('load1 csspinner');
             }
        })


      } else {
        alert("Please add discount first.");
        return false;
      }
    })

    $(document).on('click', ".remove_discount", function() {
        var order_id = $(this).attr("order_id");
        var message = $("#Message").val();
        $.ajax({
             url: "<?php echo $this->Html->url(array('controller'=>'homes', 'action'=>'remove_discount')); ?>",
             method:"post",
             data:{order_id:order_id},
             success:function(html) {
                $(".summary_box").html(html);
                $(".summary_box").removeClass('load1 csspinner');
             },
             beforeSend:function() {
                $(".summary_box").addClass('load1 csspinner');
             }
        })
    })
    $(document).on('click', ".add-discount", function() {
      $(".discount_view").toggle();
    });
    $(document).on("click", '.dropdown-toggle', function (){
      if($(this).attr("aria-expanded") == 'true'){
        $(".clearfix.cart-wrap").addClass("csspinner");
      } else {
        $(".clearfix.cart-wrap").removeClass("csspinner");
      }
      dropDownFixPosition($(this),$(".dropdown-menu"));
    }); 
    function dropDownFixPosition(button,dropdown){
      var dropDownTop = button.position().top; //alert(dropDownTop);
      var left = $(document).width() - dropdown.width(); 
      var top = $(document).height() - dropdown.height(); 
      dropdown.css('left', left/2+"px");
      dropdown.css('top', "30%");
    }


  $(document).on('click', "body", function() {
   $(".clearfix.cart-wrap").removeClass("csspinner");
  });

  $(document).on('click', ".dropdown-menu", function(event) {
   event.stopPropagation();
  });
    
</script>

<style type="text/css">
.dropdown-menu{
  position:fixed;
}
.show_extras {
    display: block;
    float: left;
    margin-bottom: 15px;
    margin-top: 14px;
    width: 100%;
}
.extras_inner{
    border: 1px solid #eee;
    border-radius: 14px;
    display: inline-block;
    font-size: 16px;
    margin: 4px;
    padding: 8px;
}
.extras_inner > span {
    margin-right: 10px;
}
.fa.fa-times.remove_extra {
    color: rgb(195, 14, 35);
    font-size: 19px;
}.fa.fa-times.remove_extra:hover {
    color: #23527c;
    font-size: 19px;
}
</style>