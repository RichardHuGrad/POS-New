<?php
echo $this->Html->css(array('slick.css'));
?>
<body>
    <header class="product-header">
        
            <div class=" text-center slider multiple-items">
                <?php
                if(!empty($final_orders)) {
                    $count = 0;
                    foreach ($final_orders as $key => $value) {
                        $count++;
                        # code...
                        ?>
                        <div <?php if($count == 1) { echo 'class="active"';} ?>><a data-toggle="tab" href="#Order<?php echo $value['Order']['id'] ?>">Order 订购 #<?php echo $value['Order']['order_no'].", <br/>".$value['Order']['order_created'] ?></a></div>                        
                        <?php
                    }
                }
                ?>
            </div>
            <div class="home-logo">
                    <a href="<?php echo $this->Html->url(array('controller'=>'homes','action'=>'dashboard')) ?>">
                    <?php echo $this->Html->image("logo-home.jpg", array('alt' => "POS")); ?>
                    </a>
					
					<div class="HomeText text-left">
                        <a href="<?php echo $this->Html->url(array('controller'=>'homes','action'=>'index')) ?>">Home 主页</a>
                        <a href="javascript:void(0)" onclick="window.history.back()">Back 返回</a>
					</div>
					
            </div>
			
			
            <div class="logout"><a href="<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'logout')) ?>">Logout 登出</a></div>
               
    </header>

    <div class="container">
        <div class="clearfix product-wrap">

            <div class="row marginB15">       
               <div class="col-md-9 col-sm-8 col-xs-12 home-link">
                <div class="clearfix marginB15">
                  <a href="javascript:void(0)" class="submitbtn reprint" >Reprint 重印</a>
                </div>
               </div>
            </div>

            <div class="tab-content">
                <?php
                if(!empty($final_orders)) {
                    $count = 0;
                    foreach ($final_orders as $key => $value) {
                        $count++;
                        ?>

                        <div id="Order<?php echo $value['Order']['id'] ?>" class="tab-pane fade in <?php if($count == 1) { echo 'active';} ?>">

                            <?php 

                            foreach($value['categories'] as $category_name=>$category_items) {    
                            ?>
                                <div class="col-md-4 col-sm-4 col-xs-12"  style="page-break-before: always;">
                                    <div class="product-indent clearfix">
                                        <div class="product-top text-center">
                                            <?php
                                                $category_name = explode("|||", $category_name);
                                                echo "<span>".$category_name[1]."</span>";
                                            ?>, 表 #<?php echo $value['Order']['table_no'] ?>
                                        </div>
                                        <ul>
                                            <?php
                                            if(!empty($category_items)) {
                                                foreach ($category_items as $k => $i) {

                                                    $selected_extras_name = [];
                                                    if ($i['selected_extras']) {
                                                        $selected_extras = json_decode($i['selected_extras'], true);

                                                        // prepare extras string
                                                        if(!empty($selected_extras)) {
                                                            foreach($selected_extras as $k=>$v){
                                                                $selected_extras_name[] = $v['name'];
                                                            }
                                                        }
                                                    }
                                                    # code...
                                                    ?>
                                                    <li class="clearfix">
                                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                                            <?php echo $i['name_xh']; ?>
                                                        </div>
                                                        <?php 
                                                        if(!empty($selected_extras_name)) {
                                                            ?>
                                                            <div class="less-txt-extras"><?php echo implode(", ", $selected_extras_name); ?></div>
                                                            <?php
                                                        }
                                                        ?>
                                                        <!-- <div class="col-md-3 col-sm-4 col-xs-4 text-right"><?php echo $i['qty']; ?></div> -->
                                                    </li>
                                                    <?php
                                                }
                                            }
                                            ?>
                                            
                                        </ul>
                                        <?php if($value['Order']['message']) { ?>
                                        <div class="order_message">
                                            <?php echo nl2br($value['Order']['message']); ?>
                                        </div>
                                        <?php }?>
                                    </div>

                                </div>
                            <?php }?>

                        </div>
                <?php }
                }
                ?>
            </div>


        </div>
    </div>

    <?php
    echo $this->Html->script(array('jquery.min.js', 'bootstrap.min.js', 'slick.js', 'jQuery.print.js'));
    echo $this->fetch('script');
    ?>
    <script type="text/javascript">
    $(document).ready(function(){
        $('.multiple-items').slick({
          infinite: false,
          slidesToShow: 3,
          slidesToScroll: 3
        });

        $(".slick-slide").click(function() {
            $(".slick-slide").removeClass("active");
            $(this).addClass("active");
        })
    })
    $(document).on('click', '.reprint', function () {
        //Print ele4 with custom options
        $(".tab-pane.active").print({
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
    </script>
</body>
