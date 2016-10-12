<?php
echo $this->Html->css(array('slick.css'));
?>
<body>
    <header class="product-header">
        <div class="container">
            <div class="home-logo"><a href="<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'dashboard')) ?>">
                    <?php echo $this->Html->image("logo-home.jpg", array('alt' => "POS")); ?>
                </a>
            </div>
            <div class="logout"><a href="<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'logout')) ?>">Logout 登出</a></div>
            <div class="otherwrap clearfix text-center">
                <?php
                if (!empty($items_array)) {
                    ?>
                    <ul class="multiple-items-cooking">
                        <?php
                        $count = 0;
                        foreach ($items_array as $key => $value) {
                            $count++;
                            $category = explode("|||", $key);
                            ?>
                            <li>
                                <div class="checkbox-btn">
                                    <input type="checkbox" <?php if ($count == 1) echo 'checked="checked"'; ?> value="<?php echo $category[2]; ?>" class="category_name" id="rc<?php echo $category[2]; ?>" name="rc<?php echo $category[2]; ?>"/>
                                    <label for="rc<?php echo $category[2]; ?>" onclick><?php echo $category[0] . "<br/>" . $category[1] ?></label>
                                </div>
                            </li>
                        <?php } ?>
                    </ul>
                <?php } ?>
            </div>
        </div>        
    </header>

    
    <div class="container otherpage">
        <div class="clearfix othertopwrap">
            <div class="row">
                <div class="col-md-10 col-sm-6 col-xs-12 marginB15">
                </div>

                <div class="col-md-2 col-sm-6 col-xs-12">
                    <div class="checkbox-btn change_orders" alt="active_orders">
                        <input type="checkbox" <?php if (!$type) echo 'checked'; ?>  value="value-1" id="rc" name="rc"/>
                        <label for="rc">Active Order<br/>当前订单</label>
                    </div>
                    <div class="checkbox-btn change_orders"  alt="finished_orders">
                        <input type="checkbox"  <?php if ($type) echo 'checked'; ?>  value="value-1" id="rcm" name="rcm"/>
                        <label for="rcm">Finished Orders<br/>成品订单</label>
                    </div>
                </div>

            </div>
        </div>

        <div class="clearfix product-wrap">
            <div class="tab-content">
                <div id="Order1" class="tab-pane fade in active">
                    <div class="slider multiple-items">
                        <?php
                        if (!empty($items_array)) {
                            $count = 0;
                            foreach ($items_array as $key => $records) {
                                $count++;
                                $category = explode("|||", $key);
                                foreach ($records as $order_id => $value) {
                                    if ($count == 1) {
                                        ?>
                                        <div class="marginB30 rc<?php echo $category[2]; ?> column<?php echo $order_id . $category[2]; ?>" >
                                            <div class="avoid-this text-center reprint" alt="column<?php echo $order_id . $category[2]; ?>"><button type="button" class="submitbtn">Reprint 重印</button></div>
                                            <div class="product-indent clearfix">
                                                <ul class="get_height">
                                                    <li class="clearfix padding smallcontent">
                                                        <div class="col-md-2 col-sm-3 text-center padding10 redbdr">
                                                            Table 桌 <?php echo $value[0]['order_type'] . str_pad($value[0]['table_no'], 2, 0, STR_PAD_LEFT) ?>
                                                        </div>
                                                        <div class="col-md-3 col-sm-3 text-center padding10 redbdr"><?php echo $value[0]['category_name_en'] . "<br/>" . $value[0]['category_name_zh'] ?>  </div>
                                                        <div class="col-md-3 col-sm-3 text-center padding10 redbdr">
                                                            Order 订单号 #<?php echo $value[0]['order_no']; ?>
                                                        </div>
                                                        <div class="col-md-4 col-sm-3 text-center padding10">
                                                            <?php echo  "Order Time 点餐 时 " . date('h:ia<br/> m/d/Y', strtotime($value[0]['order_created']))?>  
                                                        </div>
                                                    </li>
                                                    <?php
                                                    $ids = [];
                                                    foreach ($value as $item) {
                                                        $ids[] = $item['id'];
                                                        $selected_extras_name = [];
                                                        if ($item['selected_extras']) {
                                                            $selected_extras = json_decode($item['selected_extras'], true);

                                                            // prepare extras string
                                                            if(!empty($selected_extras)) {
                                                                foreach($selected_extras as $k=>$v){
                                                                    $selected_extras_name[] = $v['name'];
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                        <li alt="<?php echo $item['id']; ?>"  class="sub_teams sub_item_row<?php echo $item['id']; ?> clearfix <?php if ($item['is_done'] == 'Y') echo "already_done"; ?>">
                                                            <div class="col-md-12 text-center">
                                                                <!-- <span class='pull-left'> -->
                                                                    <?php echo $item['name_en'] . "M<br/>" . $item['name_xh'] ?>
                                                                <!-- </span> -->
                                                                
                                                                    <!-- <span class='pull-right span<?php echo $item['id']; ?>'>
                                                                        <button class="donebtn avoid-this" type="button" alt="<?php echo $item['id']; ?>">
                                                                            <?php if ($item['is_done'] == 'N') echo "Done 完成"; else echo "Undone 未完成"; ?>
                                                                        </button>
                                                                    </span> -->
                                                            </div>
                                                            <?php 
                                                            if(!empty($selected_extras_name)) {
                                                                ?>
                                                                <div class="clearfix"></div>
                                                                <div class="less-txt-extras"><?php echo implode(", ", $selected_extras_name); ?></div>
                                                                <?php
                                                            }
                                                            ?>
                                                        </li>
                                                    <?php } ?>
                                                </ul>
                                                <?php
                                                if($value[0]['message']) {
                                                    ?>
                                                    <div class="col-md-12 text-center message-section">
                                                        <?php echo $value[0]['message']; ?>
                                                    </div>
                                                    <?php
                                                }
                                                 ?>
                                                <?php
                                                if ($type == 'finished') {
                                                    ?>
                                                    <div class="col-md-12 text-center finished-section">
                                                        <i class="fa fa-check-circle"></i> FINISHED 完成  
                                                    </div>
                                                <?php } ?>
                                            </div>
                                            <?php
                                            if ($type == 'finished') {
                                                ?>
                                                <div class="avoid-this clearfix">
                                                    <button class="recookbtn finish<?php echo $order_id . $category[2]; ?>" row_id="<?php echo $order_id . $category[2]; ?>" alt="<?php echo implode(",", $ids); ?>" type="button">Recook 重新烘焙</button>
                                                </div>
                                                <?php
                                            } else {
                                                ?>
                                                <div class="avoid-this clearfix">
                                                    <button class="finishbtn finish<?php echo $order_id . $category[2]; ?>" row_id="<?php echo $order_id . $category[2]; ?>" alt="<?php echo implode(",", $ids); ?>" type="button">Finish 完成</button>
                                                </div>
                                                <div class="text-center timeing"><?php echo $value[0]['time_ago']; ?></div>
                                                <?php
                                            }
                                            ?>                                            
                                        </div>
                                        <?php
                                    }
                                }
                            }
                        } else {
                            ?>
                            <h2 style="text-align:center"> No Orders Available</h2>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <?php
        echo $this->Html->script(array('jquery.min.js', 'bootstrap.min.js', 'slick.js', 'jQuery.print.js'));
        echo $this->fetch('script');
        ?>
        <script type="text/javascript">

            $(document).on('click', '.reprint', function () {
                var id = $(this).attr("alt");
                //Print ele4 with custom options
                $("." + id).print({
                    //Use Global styles
                    globalStyles: false,
                    //Add link with attrbute media=print
                    mediaPrint: true,
                    //Custom stylesheet
                    stylesheet: "<?php echo Router::url('/', true) ?>css/print.css",
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

            $(document).on("click", ".change_orders", function (e) {
                $(".tab-content").addClass('load1 csspinner');
                e.preventDefault();
                var type = $(this).attr("alt");
                if (type == 'finished_orders') {
                    window.location = "<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'cookings', 'type' => 'finished')); ?>";
                } else {
                    window.location = "<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'cookings')); ?>";
                }
            })

            $(document).on("click", ".sub_teams", function () {
                var id = $(this).attr("alt");
                // update item to be done
                $.ajax({
                    url: "<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'doneitem')); ?>",
                    method: "post",
                    data: {item_id: id},
                    dataType: "json",
                    success: function (data) {
                        if(data.done) {
                            $(".sub_item_row" + id).addClass('already_done');
                            // $(".span" + id+" button").text('Undone 未完成');
                        } else {
                            $(".sub_item_row" + id).removeClass('already_done');
                            // $(".span" + id+" button").text('Done 完成');
                        }
                        // $(".sub_item_row" + id + " span").removeClass('pull-left');
                        $(".tab-content").removeClass('load1 csspinner');
                    },
                    beforeSend: function () {
                        $(".tab-content").addClass('load1 csspinner');
                    }
                })
            })

            $(document).on("click", ".recookbtn", function () {
                var all_ids = $(this).attr("alt");
                var row_id = $(this).attr("row_id");
                var row_ids = all_ids.split(',');
                // update item to be done
                $.ajax({
                    url: "<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'recookallitem')); ?>",
                    method: "post",
                    data: {item_id: all_ids},
                    success: function (html) {

                        // remove column and reset the slider
                        $('.multiple-items').slick('unslick');
                        $(".column" + row_id).remove();

                        // reset slider again
                        $('.multiple-items').slick({
                            infinite: false,
                            slidesToShow: 3,
                            slidesToScroll: 3
                        })

                        $(".tab-content").removeClass('load1 csspinner');
                    },
                    beforeSend: function () {
                        $(".tab-content").addClass('load1 csspinner');
                    }
                })
            })



            $(document).on("click", ".finishbtn", function () {
                var all_ids = $(this).attr("alt");
                var row_id = $(this).attr("row_id");
                var row_ids = all_ids.split(',');
                // update item to be done
                $.ajax({
                    url: "<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'doneallitem')); ?>",
                    method: "post",
                    data: {item_id: all_ids},
                    success: function (html) {
                        for (var i = 0; i < row_ids.length; ++i) {
                            var id = row_ids[i];
                            $(".sub_item_row" + id).addClass('already_done');
                            $(".span" + id).remove();
                            $(".sub_item_row" + id + " span").removeClass('pull-left');
                        }
                        $(".finish".row_id).text("Recook");
                        $(".column" + row_id).addClass("finished");

                        // remove column and reset the slider
                        $('.multiple-items').slick('unslick');
                        $(".column" + row_id).remove();

                        // reset slider again
                        $('.multiple-items').slick({
                            infinite: false,
                            slidesToShow: 3,
                            slidesToScroll: 3
                        })

                        $(".tab-content").removeClass('load1 csspinner');
                    },
                    beforeSend: function () {
                        $(".tab-content").addClass('load1 csspinner');
                    }
                })
            })

            $(document).ready(function () {
                 $('.multiple-items-cooking').slick({
                  infinite: false,
                  slidesToShow: 3,
                  slidesToScroll: 3
                });
                var height = new Array();
                $(".get_height").map(function() {
                    height.push($(this).height());
                })
                var maxValueInArray = Math.max.apply(Math, height); 
                $(".get_height").css("height", maxValueInArray+"px")

                $(".category_name").click(function () {
                    var count = 0;
                    $(".category_name").map(function () {
                        if ($(this).is(":checked"))
                            count++;
                    })
                    if (count == 0)
                        return false;

                    var html = "";

                    $('.multiple-items').slick('unslick');
                    $(".multiple-items").html("");
                    $(".category_name").map(function () {
                        var id = $(this).attr("id");
                        if ($(this).is(":checked")) {
                            html = $(".hidden-elements ." + id).clone();
                            $(".multiple-items").append(html);
                        }
                    });

                    $('.multiple-items').slick({
                        infinite: false,
                        slidesToShow: 3,
                        slidesToScroll: 3
                    })
                })
<?php if (!empty($items_array)) { ?>
                    $('.multiple-items').slick({
                        infinite: false,
                        slidesToShow: 3,
                        slidesToScroll: 3
                    });
<?php } ?>
            })
        </script>


        <div style="display:none" class="hidden-elements">
            <?php
            if (!empty($items_array)) {
                $count = 0;
                foreach ($items_array as $key => $records) {
                    $count++;
                    $category = explode("|||", $key);
                    foreach ($records as $value) {
                        ?>
                         <div class="marginB30 rc<?php echo $category[2]; ?> column<?php echo $order_id . $category[2]; ?>" >
                                            <div class="avoid-this text-center reprint" alt="column<?php echo $order_id . $category[2]; ?>"><button type="button" class="submitbtn">Reprint 重印</button></div>
                                            <div class="product-indent clearfix">
                                                <ul class="get_height">
                                                    <li class="clearfix padding smallcontent">
                                                        <div class="col-md-2 col-sm-3 text-center padding10 redbdr">
                                                            Table 桌 <?php echo $value[0]['order_type'] . str_pad($value[0]['table_no'], 2, 0, STR_PAD_LEFT) ?>
                                                        </div>
                                                        <div class="col-md-3 col-sm-3 text-center padding10 redbdr"><?php echo $value[0]['category_name_en'] . "<br/>" . $value[0]['category_name_zh'] ?>  </div>
                                                        <div class="col-md-3 col-sm-3 text-center padding10 redbdr">
                                                            Order 订单号 #<?php echo $value[0]['order_no']; ?>
                                                        </div>
                                                        <div class="col-md-4 col-sm-3 text-center padding10">
                                                            <?php echo  "Order Time 点餐 时 " . date('h:ia<br/> m/d/Y', strtotime($value[0]['order_created']))?>  
                                                        </div>
                                                    </li>
                                                    <?php
                                                    $ids = [];
                                                    foreach ($value as $item) {
                                                        $ids[] = $item['id'];
                                                        $selected_extras_name = [];
                                                        if ($item['selected_extras']) {
                                                            $selected_extras = json_decode($item['selected_extras'], true);

                                                            // prepare extras string
                                                            if(!empty($selected_extras)) {
                                                                foreach($selected_extras as $k=>$v){
                                                                    $selected_extras_name[] = $v['name'];
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                        <li alt="<?php echo $item['id']; ?>"  class="sub_teams sub_item_row<?php echo $item['id']; ?> clearfix <?php if ($item['is_done'] == 'Y') echo "already_done"; ?>">
                                                            <div class="col-md-12 text-center">
                                                                <!-- <span class='pull-left'> -->
                                                                    <?php echo $item['name_en'] . "M<br/>" . $item['name_xh'] ?>
                                                                <!-- </span> -->
                                                                
                                                                    <!-- <span class='pull-right span<?php echo $item['id']; ?>'>
                                                                        <button class="donebtn avoid-this" type="button" alt="<?php echo $item['id']; ?>">
                                                                            <?php if ($item['is_done'] == 'N') echo "Done 完成"; else echo "Undone 未完成"; ?>
                                                                        </button>
                                                                    </span> -->
                                                            </div>
                                                            <?php 
                                                            if(!empty($selected_extras_name)) {
                                                                ?>
                                                                <div class="clearfix"></div>
                                                                <div class="less-txt-extras"><?php echo implode(", ", $selected_extras_name); ?></div>
                                                                <?php
                                                            }
                                                            ?>
                                                        </li>
                                                    <?php } ?>
                                                </ul>
                                                <?php
                                                if($value[0]['message']) {
                                                    ?>
                                                    <div class="col-md-12 text-center message-section">
                                                        <?php echo $value[0]['message']; ?>
                                                    </div>
                                                    <?php
                                                }
                                                 ?>
                                                <?php
                                                if ($type == 'finished') {
                                                    ?>
                                                    <div class="col-md-12 text-center finished-section">
                                                        <i class="fa fa-check-circle"></i> FINISHED 完成  
                                                    </div>
                                                <?php } ?>
                                            </div>
                                            <?php
                                            if ($type == 'finished') {
                                                ?>
                                                <div class="avoid-this clearfix">
                                                    <button class="recookbtn finish<?php echo $order_id . $category[2]; ?>" row_id="<?php echo $order_id . $category[2]; ?>" alt="<?php echo implode(",", $ids); ?>" type="button">Recook 重新烘焙</button>
                                                </div>
                                                <?php
                                            } else {
                                                ?>
                                                <div class="avoid-this clearfix">
                                                    <button class="finishbtn finish<?php echo $order_id . $category[2]; ?>" row_id="<?php echo $order_id . $category[2]; ?>" alt="<?php echo implode(",", $ids); ?>" type="button">Finish 完成</button>
                                                </div>
                                                <div class="text-center timeing"><?php echo $value[0]['time_ago']; ?></div>
                                                <?php
                                            }
                                            ?>                                            
                                        </div>
                        <?php
                    }
                }
            }
            ?>
        </div>
</body>