<?php
    echo $this->Html->css(array('navbar'));
    // echo $this->Html->script(array('jquery', 'bootstrap.min.js'));
?>

<div id="custom-bootstrap-menu" class="navbar navbar-default navbar-static-top" role="navigation">
    <div class="container-fluid ">
        <!-- brand -->
        <div class="navbar-header">
            <a href="<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'dashboard')) ?>">
            <?php echo $this->Html->image("logo-home.jpg", array('alt' => "POS", 'class' => 'logo-img')); ?>
            </a>
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-menubuilder"><span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span>
            </button>
        </div>
        <div class="collapse navbar-collapse navbar-menubuilder">
            <ul class="nav navbar-nav navbar-left">
                <li>
                    <a href="<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'index')) ?>"><?php echo __('Home'); ?></a>
                </li>
                <li>
                    <a id="admin-link" href="#"><?php echo __('Admin Functions'); ?></a>
                </li>
                <!-- <li><div id='print-today-all' class="pull-left paid-txt">打印总单 </div></li>
                  <li><div id='print-today-items' class="pull-left paid-txt">打印销量</div></li> -->

                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#"><?php echo __('More'); ?>
    <span class="caret"></span></a>
                    <ul class="dropdown-menu">

                        <li>
                            <a href="<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'inquiry')) ?>">
                                <div class="inquery-brn clearfix">
                                    <span class="doc-order"><?php echo $this->Html->image('inquery-icon.png', array('alt' => 'Inquiry', 'title' => 'Inquiry')); ?></span>
                                    <span class="inquiry-txt"><?php echo __('Order Search'); ?></span>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'dashboard')) ?>">
                                <div class="inquery-brn clearfix">
                                    <span class="doc-order"><?php echo $this->Html->image('order-list.png', array('alt' => 'Order', 'title' => 'Order')); ?></span>
                                    <span class="order-txt"><?php echo __('Order'); ?></span>
                                </div>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#"><?php echo __('Languages'); ?>
    <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="#" data-lang="eng" class="switch-lang">
                                English
                            </a>
                        </li>
                        <li>
                            <a href="#" data-lang="zho" class="switch-lang">
                                中文
                            </a>
                        </li>
                    </ul>
                </li>




            </ul>

            <ul class="nav navbar-nav navbar-right">

                <li>
                    <div class="clearfix marginB15">
                        <div class="pull-left notpaid"></div>
                        <div class="pull-left paid-txt"><?php echo __('On-going'); ?></div>

                    </div>
                    <div class="clearfix marginB15">
                        <div class="pull-left availableb"></div>
                        <div class="pull-left paid-txt"><?php echo __('Available'); ?></div>
                    </div>
                </li>
                <li>
                    <a href="<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'logout')) ?>"><?php echo __('Logout'); ?></a>
                </li>

            </ul>

        </div>
    </div>
</div>

<?php echo $this->Html->script(array('jquery.min.js', 'bootstrap.min.js')); ?>
<script type="text/javascript">

    $('.switch-lang').on('click', function() {
        $.ajax({
            url: "<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'switchLang')); ?>",
            method: "post",
            data: {
                lang: $(this).data('lang')
            },
            success: function(html) {
                // reload the page
                location.reload();
            }
        })
        // console.log("click");
    });



</script>
