<?php
    echo $this->Html->css(array('navbar'));
    // echo $this->Html->script(array('jquery', 'bootstrap.min.js'));
?>

<div id="custom-bootstrap-menu" class="navbar navbar-default navbar-static-top" role="navigation">
    <div class="container-fluid ">
        <!-- brand -->
        <div class="navbar-header">
            <a href="<?php echo $this->Html->url(array('controller' => 'homes', 'action' => 'dashboard')) ?>">
            <?php 
                if(substr($_SERVER['REQUEST_URI'],0,5)=='/skip')
                  echo $this->Html->image("logo-skip.jpg", array('alt' => "SKIP", 'class' => 'logo-img'));                 
                else
                  echo $this->Html->image("logo-home.jpg", array('alt' => "POS", 'class' => 'logo-img')); 
            ?>
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
             <!--
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
             -->
             
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

                <li>
                    <a id="checkout-link" href="#" data-toggle="modal" data-target="#modal_member_search"><?php echo __('Member'); ?></a>
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

   <div class="modal fade clearfix" id="modal_member_search" role="dialog">
       <div class="modal-dialog modal-lg" style="width:400px">
           <div class="modal-content clearfix">
               <div class="modal-header">
                   <button type="button" data-dismiss="modal" class="member_btn_close"><?php echo __('Close'); ?></button>
                   <h4><?php echo __('Member'); ?></h4>
                   <button type="button" data-dismiss="modal" id="member_btn_add"><?php echo __('Add'); ?></button>
               </div>
               <div class="modal-body clearfix">
               		<div class='row'>
               			<div class='col-sm-12'>
		                    <input id="member_search_input" type="text" style="font-size:25px;height:38px" placeholder='Card Number/ID/Name/Phone Number' />
		                    <input id="member_search_next" type="hidden" value="" />
               			</div>
               		</div>
               		<div class='row'>
               			<div class='col-sm-4'><?php echo __('Card Number'); ?></div>
               			<div class='col-sm-4'><?php echo __('ID'); ?></div>
               			<div class='col-sm-4'><?php echo __('Amount'); ?></div>
               		</div>
               		<div class='row' id='mbm_sch_list'>
               			<div class='col-sm-12'>
               			</div>
               		</div>
               </div>
           </div>
       </div>
   </div>

   <div class="modal fade clearfix" id="modal_member" role="dialog">
       <div class="modal-dialog modal-lg" style="width:800px">
           <div class="modal-content clearfix">
               <div class="modal-header">
                   <button type="button" data-dismiss="modal" class="member_btn_close"><?php echo __('Close'); ?></button>
                   <h4><?php echo __('Member Info'); ?></h4>
               </div>
               <div class="modal-body clearfix">
					<input id="mbm_info_member_id" type="hidden" name='id' />
					<div class='row'>
						<div class='col-sm-6'>
							<?php echo __('Card Number'); ?> : <span id='mbm_info_cardnumber'></span>
						</div>
						<div class='col-sm-6'>
							<?php echo __('ID'); ?> : <span id='mbm_info_id'></span>
						</div>
						<div class='col-sm-6'>
							<?php echo __('Name'); ?> : <span id='mbm_info_name'></span>
						</div>
						<div class='col-sm-6'>
							<?php echo __('Phone'); ?> : <span id='mbm_info_phone'></span>
						</div>
						<div class='col-sm-12'>
							<?php echo __('Notes'); ?> : <span id='mbm_info_notes'></span>
						</div>
						<div class='col-sm-4'>
							<?php echo __('Total Paid'); ?> : <span id='mbm_info_total_paid'></span>
						</div>
						<div class='col-sm-4'>
							<?php echo __('Total Charged'); ?> : <span id='mbm_info_total_charged'></span>
						</div>
						<div class='col-sm-4'>
							<?php echo __('Current Amount'); ?> : <span id='mbm_info_total_amount'></span>
						</div>
					</div>
					<div class='row'>
						<div class='col-sm-4'>
							<?php echo __('Amount'); ?>
							<input id="mbm_info_amount" type="text" name='amount'/>
						</div>
						<div class='col-sm-4'>
							<button type="button" id="mbm_info_card"><?php echo __('Fill By Credit Card'); ?></button>
						</div>
						<div class='col-sm-4'>
							<button type="button" id="mbm_info_cash"><?php echo __('Fill By Cash'); ?></button>
						</div>
					</div>
					<div class='row' id='mbm_info_trans_div'>
						<div class='col-sm-12'>
						</div>
					</div>
               </div>
           </div>
       </div>
   </div>

  <div class="modal fade clearfix" id="modal_member_pay" role="dialog">
       <div class="modal-dialog modal-lg" style="width:800px">
           <div class="modal-content clearfix">
               <div class="modal-header">
                   <button type="button" data-dismiss="modal" class="member_btn_close"><?php echo __('Close'); ?></button>
                   <h4><?php echo __('Select Tips'); ?></h4>
               </div>
               <div class="modal-body clearfix">
					<div class='row'>
						<input id="mbm_pay_order_total" type="hidden" name='mbm_pay_order_total'/>
						<input id="mbm_pay_order_paid" type="hidden" name='mbm_pay_order_paid'/>
						<input id="mbm_pay_balance" type="hidden" name='mbm_pay_balance'/>
						<input id="mbm_pay_memberid" type="hidden" name='mbm_pay_memberid'/>
						<div class='col-sm-6'>
							<?php echo __('Card Number'); ?> : <span id='mbm_pay_cardnumber'></span>
						</div>
						<div class='col-sm-6'>
							<?php echo __('Balance'); ?> : <span id='mbm_pay_card_balance'></span>
						</div>
						<div class='col-sm-12'>
							<span id='mbm_pay_no_tips' class="mbm_pay_input"><?php echo __('No Tips'); ?></span>
						</div>
						<div class='col-sm-12'>
							<span id='mbm_pay_tip10' class="mbm_pay_input">10 %</span>
						</div>
						<div class='col-sm-12'>
							<span id='mbm_pay_tip15' class="mbm_pay_input">15 %</span>
						</div>
						<div class='col-sm-12'>
							<span id='mbm_pay_tip20' class="mbm_pay_input">20 %</span>
						</div>
						<div class='col-sm-12'>
							<span id='mbm_pay_tip_input' class="mbm_pay_input"><?php echo __('Input Amount'); ?></span>
						</div>
					</div>
               </div>
           </div>
       </div>
   </div>

   <div class="modal fade clearfix" id="modal_member_edit" role="dialog">
       <div class="modal-dialog modal-lg" style="width:800px">
           <div class="modal-content clearfix">
               <div class="modal-header">
                   <button type="button" data-dismiss="modal" class="member_btn_close"><?php echo __('Close'); ?></button>
                   <h4><?php echo __('Member Edit'); ?></h4>
               </div>
               <div class="modal-body clearfix">
					<input id="mbm_edt_member_id" type="hidden" name='id' />
					<div class='row'>
						<div class='col-sm-4'>
							<?php echo __('Card Number'); ?>
						</div>
						<div class='col-sm-4'>
							<input id="mbm_edt_cardnumber" type="text" name= 'cardnumber' style="font-size:25px;height:38px" />
						</div>
					</div>
					<div class='row'>
						<div class='col-sm-4'>
							<?php echo __('Name'); ?>
						</div>
						<div class='col-sm-4'>
							<input id="mbm_edt_name" type="text" name= 'name' style="font-size:25px;height:38px" />
						</div>
					</div>
					<div class='row'>
						<div class='col-sm-4'>
							<?php echo __('Phone Number'); ?>
						</div>
						<div class='col-sm-4'>
							<input id="mbm_edt_phone" type="text" name= 'phone' style="font-size:25px;height:38px" />
						</div>
					</div>
					<div class='row'>
						<div class='col-sm-4'>
							<?php echo __('Notes'); ?>
						</div>
						<div class='col-sm-4'>
							<input id="mbm_edt_notes" type="text" name= 'notes' style="font-size:25px;height:38px" />
						</div>
					</div>
               </div>
               <div class="modal-footer clearfix">                   
                   <button type="button" id="mbm_add_btn" class="pull-right btn btn-lg btn-success" data-dismiss="modal">OK 确认</button>
               </div>
           </div>
       </div>
   </div>

<?php echo $this->Html->script(array('jquery.min.js', 'bootstrap.min.js','md5.js', 'jquery.mCustomScrollbar.concat.min.js' )); ?>

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
          if (pass == "<?php echo @$admin_passwd[0]['admins']['password']?>") {  
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

	function get_member_info() {
        $.ajax({
            url: "<?php echo $this->Html->url(array('controller' => 'member', 'action' => 'member')); ?>",
            method: "post",
            dataType: 'json',
            data: {member_id: $("#mbm_info_member_id").val()},
			dataType: 'json',
			success: function(data){
				if (data.status == "OK") {
					$('#mbm_info_cardnumber').html(data.member.cardnumber);
					$('#mbm_info_id').html(data.member.id);
					$('#mbm_info_name').html(data.member.name);
					$('#mbm_info_phone').html(data.member.phone);
					$('#mbm_info_notes').html(data.member.notes);
					$('#mbm_info_total_paid').html(parseFloat(data.member.paid).toFixed(2));
					$('#mbm_info_total_charged').html(parseFloat(data.member.filled).toFixed(2));
					$('#mbm_info_total_amount').html(parseFloat(data.member.amount).toFixed(2));
					var html = '';
					for (var i = 0; i < data.trans.length; i++) {
						if (data.trans[i].opt == 'Pay') {
							html += "<div class='col-sm-3'>Bill#:" + data.trans[i].order_number + "</div>";
							html += "<div class='col-sm-3'>Date:" + data.trans[i].tm + "</div>";
							html += "<div class='col-sm-2'>Bill:" + data.trans[i].bill_amount + "</div>";
							html += "<div class='col-sm-2'>Paid:" + parseFloat(data.trans[i].amount).toFixed(2) + "</div>";
							html += "<div class='col-sm-2'>Balance:" + parseFloat(data.trans[i].total).toFixed(2) + "</div>";
						} else {
							html += "<div class='col-sm-3'>Recharge with " + data.trans[i].opt + "</div>";
							html += "<div class='col-sm-3'>Date:" + data.trans[i].tm + "</div>";
							html += "<div class='col-sm-2'></div>";
							html += "<div class='col-sm-2'>Amount:" + parseFloat(data.trans[i].amount).toFixed(2) + "</div>";
							html += "<div class='col-sm-2'>Balance:" + parseFloat(data.trans[i].total).toFixed(2) + "</div>";
						}
					}
					$('#mbm_info_trans_div').html(html);
				} else {
					alert(data.message);
				}
			}
		})
	}
	$("#modal_member").on('show.bs.modal', function () {
		get_member_info();
	});

	function add_fund(type) {
        $.ajax({
            url: "<?php echo $this->Html->url(array('controller' => 'member', 'action' => 'add_fund')); ?>",
            method: "post",
            dataType: 'json',
            data: {
				member_id: $("#mbm_info_member_id").val(), 
				amount : $("#mbm_info_amount").val(),
				opt : type,
			},
			dataType: 'json',
			success: function(data){
				if (data.status == "OK") {
					get_member_info();
				} else {
					alert(data.message);
				}
			}
		})
	}
	
    $('#mbm_info_card').on('click', function() {
    	add_fund('Credit Card');
	});
	
    $('#mbm_info_cash').on('click', function() {
    	add_fund('Cash');
	});
	
    $('#mbm_add_btn').on('click', function() {
        $.ajax({
            url: "<?php echo $this->Html->url(array('controller' => 'member', 'action' => 'edit')); ?>",
            method: "post",
            dataType: 'json',
            data: {
				member_id: $("#mbm_edt_member_id").val(), 
				cardnumber : $("#mbm_edt_cardnumber").val(),
				name : $("#mbm_edt_name").val(),
				phone : $("#mbm_edt_phone").val(),
				notes : $("#mbm_edt_notes").val()
			},
			dataType: 'json',
			success: function(data){
				if (data.status == "OK") {
					alert(data.message);
			        $('#mbm_info_member_id').val(data.member.id);
			        $('#modal_member').modal('show');
				} else {
					alert(data.message);
				}
			}
		})
	});
    
    $('.mbm_pay_input').on('click', function() {
        var type = $(this).attr("id");
        var order_total = parseFloat($('#mbm_pay_order_total').val());
        var card_amount = parseFloat($('#mbm_pay_balance').val());
        var order_paid = 0;
        if ($('#mbm_pay_order_paid').val()) {
            order_paid = parseFloat($('#mbm_pay_order_paid').val());
        }
        $("#mbm_pay_percent").val('');
        if (type == 'mbm_pay_tip10') {
        	order_total *= 1.1;
        } else if (type == 'mbm_pay_tip15') {
        	order_total *= 1.15;
        } else if (type == 'mbm_pay_tip20') {
        	order_total *= 1.2;
        } else if (type == 'mbm_pay_tip_input') {
        	order_total = 0;
        }
        if (order_total > 0) {
	        if ((order_total - order_paid) <= card_amount) {
	        	card_amount = order_total - order_paid;
	        }
        } else {
        	card_amount = 0;
        }
        if (card_amount < 0) card_amount = 0;
        
        card_amount = parseFloat(card_amount).toFixed(2)
        $("#membercard_id").val($('#mbm_info_member_id').val());
        $("#membercard_val").val(card_amount);
        if (card_amount > 0) {
	        if ($('#screen').length) {
		        $("#screen").attr('buffer', card_amount);
		        $("#screen").val(card_amount);
	        } else {
		        $("#input-screen").attr('data-buffer', card_amount);
		        $("#input-screen").val(card_amount);
	        }
        }
        $('.modal').modal('hide');
	});
    
    $('#member_btn_add').on('click', function() {
        $('.modal').modal('hide');
        $("#mbm_edt_member_id").val('');
        $("#mbm_edt_cardnumber").val('');
        $("#mbm_edt_name").val('');
        $("#mbm_edt_phone").val('');
        $("#mbm_edt_notes").val('');
        $('#modal_member_edit').modal('show');
    });
    
    $('.member_btn_close').on('click', function() {
    	$('.modal').modal('hide');
    });
    
	$('#member_search_input').on('input', function() {
		var str = $('#member_search_input').val();
		if (str.length > 2) {
			$.ajax({
				url: "<?php echo $this->Html->url(array('controller' => 'member', 'action' => 'search')); ?>",
				method: "post",
				data: { search: str },
				dataType: 'json',
				success: function(data) {
					var html = '';
					if (data.status == 'OK') {
						for (var i = 0; i < data.members.length; i++) {
							var amt = 0;
							if (data.members[i].Member.amount) {
								amt = data.members[i].Member.amount;
							}
							html += "<div class='col-sm-12 member_search_select' data_member_id='" + data.members[i].Member.id + "' data_member_cardnumber='" + data.members[i].Member.cardnumber + "' data_member_balance='" + parseFloat(amt).toFixed(2) + "'>"
							html += "<div class='col-sm-4'>" + data.members[i].Member.cardnumber + "</div>";
							html += "<div class='col-sm-4'>" + data.members[i].Member.id + "</div>";
							html += "<div class='col-sm-4'>" + parseFloat(amt).toFixed(2) + "</div>";
							html += "</div>";
						} 
					}
					$('#mbm_sch_list').html(html);
				    $('.member_search_select').on('click', function() {
				    	var member_id = $(this).attr('data_member_id');
				    	var go_next = $("#member_search_next").val();
				        $('.modal').modal('hide');
				        $('#mbm_info_member_id').val($(this).attr('data_member_id'));
				        if (go_next == 'mbm_pay_select') {
				        	$("#member_search_next").val('');
				        	$("#mbm_pay_balance").val($(this).attr('data_member_balance'));
				        	$("#mbm_pay_cardnumber").html($(this).attr('data_member_cardnumber'));
				        	$("#mbm_pay_card_balance").html(parseFloat($(this).attr('data_member_balance')).toFixed(2));
				        	$('#modal_member_pay').modal('show');
				        } else {
				        	$('#modal_member').modal('show');
				        }
				    });
				}
			})
		}
	});

        
</script>

