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
                    <a id="admin-link" href="#" data-toggle="modal" data-target="#modal_input_password"><?php echo __('Admin Functions'); ?></a>
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

                <li>
                    <a id="checkin-link" href="#" data-toggle="modal" data-target="#modal_checkin"><?php echo __('Checkin'); ?></a>
                </li>

                <li>
                    <a id="checkout-link" href="#" data-toggle="modal" data-target="#modal_checkout"><?php echo __('Checkout'); ?></a>
                </li>

            </ul>

            <ul class="nav navbar-nav navbar-right">
                <li>
                    <a href="<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'logout')) ?>"><?php echo __('Logout'); ?></a>
                </li>

            </ul>

        </div>
    </div>
</div>

   <div class="modal fade clearfix" id="modal_input_password" role="dialog">
       <div class="modal-dialog modal-lg" style="width:400px">
           <div class="modal-content clearfix">
               <div class="modal-header">
                   <button type="button" class="close" data-dismiss="modal">&times;</button>
                   <h4>Input your password</h4>
               </div>
               <div class="modal-body clearfix">
                    <input id="admin-link-input-pwd" type="password" style="height:30px">
               </div>
               <div class="modal-footer clearfix">                   
                   <button type="button" id="admin-link-confirm-pwd" class="pull-right btn btn-lg btn-success">OK 确认</button>
               </div>
           </div>
       </div>
   </div>

   <div class="modal fade clearfix" id="modal_checkin" role="dialog">
       <div class="modal-dialog modal-lg" style="width:400px">
           <div class="modal-content clearfix">
               <div class="modal-header">
                   <button type="button" class="close" data-dismiss="modal">&times;</button>
                   <h4>Checkin - Input your id</h4>
               </div>
               <div class="modal-body clearfix">
                    <input id="checkin-id" type="text" style="font-size:25px;height:38px" />
               </div>
               <div class="modal-footer clearfix">                   
                   <button type="button" id="btn-checkin" class="pull-right btn btn-lg btn-success" data-dismiss="modal">OK 确认</button>
               </div>
           </div>
       </div>
   </div>

   <div class="modal fade clearfix" id="modal_checkout" role="dialog">
       <div class="modal-dialog modal-lg" style="width:400px">
           <div class="modal-content clearfix">
               <div class="modal-header">
                   <button type="button" class="close" data-dismiss="modal">&times;</button>
                   <h4>Checkout - Input your id</h4>
               </div>
               <div class="modal-body clearfix">
                    <input id="checkout-id" type="text" style="font-size:25px;height:38px" />
               </div>
               <div class="modal-footer clearfix">                   
                   <button type="button" id="btn-checkout" class="pull-right btn btn-lg btn-success" data-dismiss="modal">OK 确认</button>
               </div>
           </div>
       </div>
   </div>


<?php echo $this->Html->script(array('jquery.min.js', 'bootstrap.min.js', 'jquery.mCustomScrollbar.concat.min.js' )); ?>

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


      $("#modal_input_password").on('shown.bs.modal', function () {
          $("#admin-link-input-pwd").focus();
      }) ; 
      $("#modal_checkin").on('shown.bs.modal', function () {
          $("#checkin-id").focus();
      }) ; 
      $("#modal_checkout").on('shown.bs.modal', function () {
          $("#checkout-id").focus();
      }) ; 

      $("#admin-link-confirm-pwd").on('click', function() {
          var pass = $("#admin-link-input-pwd").val();
          pass = hex_md5(pass); 
          if (pass == "<?php echo $admin_passwd[0]['admins']['password']?>") {  
              window.location.assign('<?php echo $this->Html->url(array('controller' => 'report', 'action' => 'index')) ?>');
          }else{
          	alert("Error admin password!")
          }
      });

    $('#btn-checkin').on('click', function() {
        $.ajax({
            url: "<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'checkin')); ?>",
            method: "post",
            data: { userid: $("#checkin-id").val() },
            success: function(html){ 
            	alert(html);
            	$("#checkin-id").val("");
            }
        })
    });

    $('#btn-checkout').on('click', function() {
        $.ajax({
            url: "<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'checkout')); ?>",
            method: "post",
            data: { userid: $("#checkout-id").val() },
            success: function(html){ 
            	alert(html);
            	$("#checkout-id").val("");
            }
        })
    });

        
</script>

