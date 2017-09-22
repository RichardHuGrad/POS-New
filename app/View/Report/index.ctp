<header >
   <?php echo $this->element('navbar'); ?>
</header>

<body>

<div class="container clearfix" >
  <div class="text-center">
    <h2><?php echo __('Admin Report Functions'); ?></h2>
  </div>
    <div class="col-md-3 col-sm-3 col-xs-12">
        <ul class="nav nav-pills nav-stacked">
            <li class="active" report-type='today'><a data-toggle="pill" href="#today-menu"><?php echo __('Today'); ?></a></li>
            <li report-type='yesterday'><a data-toggle="pill" href="#yesterday-menu"><?php echo __('Yesterday'); ?></a></li>
            <li report-type='period'><a data-toggle="pill" href="#period-menu"><?php echo __('Period'); ?></a></li>
        </ul>
        
        <div id='period-input' style='display:none'>
            <div style='margin-top:10px;'>
                <input placeholder="From date" class="form-control datepicker reset-field" type="text" id="from_date"/>  
            </div>
            <div style='margin-top:10px;'>
                <input placeholder="To date" class="form-control datepicker reset-field" type="text" id="to_date"/>  
            </div>
        </div>
        
    </div>

    <div class="tab-content col-md-9 col-sm-9 col-xs-12 ">
        <div id="today-menu" class="tab-pane fade in active">
            <div class="button-group">
                <button class="btn btn-lg btn-info" type="button" name="view-amount" data-type="today"><?php echo __('Check Sales Total'); ?></button>
                <button class="btn btn-lg btn-info" type="button" name="print-amount" data-type="today"><?php echo __('Print Sales Total'); ?></button>
                <button class="btn btn-lg btn-info" type="button" name="view-items" data-type="today"><?php echo __('Check Sales Items'); ?></button>
                <button class="btn btn-lg btn-info" type="button" name="print-items" data-type="today"><?php echo __('Print Sales Items'); ?></button>
            </div>
        </div>
        <div id="yesterday-menu" class="tab-pane fade">
            <div class="button-group">
                <button class="btn btn-lg btn-info" type="button" name="view-amount" data-type="yesterday"><?php echo __('Check Sales Total'); ?></button>
                <button class="btn btn-lg btn-info" type="button" name="print-amount" data-type="yesterday"><?php echo __('Print Sales Total'); ?></button>
                <button class="btn btn-lg btn-info" type="button" name="view-items" data-type="yesterday"><?php echo __('Check Sales Items'); ?></button>
                <button class="btn btn-lg btn-info" type="button" name="print-items" data-type="yesterday"><?php echo __('Print Sales Items'); ?></button>
            </div>
        </div>
        <div id="period-menu" class="tab-pane fade">
            <div class="button-group">
                <button class="btn btn-lg btn-info" type="button" name="view-amount" data-type="period"><?php echo __('Check Sales Total'); ?></button>
                <button class="btn btn-lg btn-info" type="button" name="print-amount" data-type="period"><?php echo __('Print Sales Total'); ?></button>
                <button class="btn btn-lg btn-info" type="button" name="view-items" data-type="period"><?php echo __('Check Sales Items'); ?></button>
                <button class="btn btn-lg btn-info" type="button" name="print-items" data-type="period"><?php echo __('Print Sales Items'); ?></button>
            </div>
        </div>
        <div class="report-content" style="margin-top:10px">
        </div>
    </div>
</div>


<script id="amount-info" type="text/template">
    <div class="">
        <li class="col-md-6 col-sm-6 col-xs-6" style="background-color:#E0E0E0;">{5}</li>
        <li class="col-md-6 col-sm-6 col-xs-6 tax" style="background-color:#E0E0E0;">{6}</li>
        <li class="col-md-6 col-sm-6 col-xs-6"><?php echo __('Received Cash'); ?></li> <li class="col-md-6 col-sm-6 col-xs-6">{3}</li>
        <li class="col-md-6 col-sm-6 col-xs-6"><?php echo __('Received Card'); ?></li> <li class="col-md-6 col-sm-6 col-xs-6">{4}</li>
        <li class="col-md-6 col-sm-6 col-xs-6"><?php echo __('Tips by Card'); ?></li> <li class="col-md-6 col-sm-6 col-xs-6">{7}</li>

        <li class="col-md-6 col-sm-6 col-xs-6"><?php echo __('Default tips by card'); ?></li> <li class="col-md-6 col-sm-6 col-xs-6">{8}</li>
        <li class="col-md-6 col-sm-6 col-xs-6"><?php echo __('Default tips by cash'); ?></li> <li class="col-md-6 col-sm-6 col-xs-6">{9}</li>

        <li class="col-md-6 col-sm-6 col-xs-6"><?php echo __('Tax'); ?></li> <li class="col-md-6 col-sm-6 col-xs-6">{0}</li>
        <li class="col-md-6 col-sm-6 col-xs-6"><?php echo __('Total'); ?></li> <li class="col-md-6 col-sm-6 col-xs-6">{1}</li>
        <li class="col-md-6 col-sm-6 col-xs-6"><?php echo __('Received Total'); ?></li> <li class="col-md-6 col-sm-6 col-xs-6">{2}</li>
    </div>
</script>
<script id="item-info" type="text/template">
  <div class="">
    <li class="col-md-6 col-sm-6 col-xs-6">{0}</li> <li class="col-md-6 col-sm-6 col-xs-6 tax">{1}</li>
  </div>
</script>

</body>

<?php
  echo $this->Html->css(array('report','datepicker'));
  echo $this->Html->script(array('jquery.min.js', 'bootstrap.min.js', 'jquery.mCustomScrollbar.concat.min.js','md5.js', 'barcode.js', 'fanticonvert.js', 'notify.min.js', 'flowtype.js','bootstrap-datepicker'));
 ?>

<script type="text/javascript">

 jQuery(document).ready(function () {

    jQuery('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
        endDate: '0d'
    });

    var firstday = new Date();
    firstday.setMonth(firstday.getMonth() , 1);
        
    $('#from_date').datepicker('setDate',firstday );
    $('#to_date').datepicker('setDate', 'today');

 });


if (!String.prototype.format) {
  String.prototype.format = function() {
    var args = arguments;
    return this.replace(/{(\d+)}/g, function(match, number) {
      return typeof args[number] != 'undefined'
        ? args[number]
        : match
      ;
    });
  };
}

function getTimeStr(timeStamp) {
  var result = "";
  var year = new Date(timeStamp).getFullYear();
  var month = new Date(timeStamp).getMonth() + 1;
  var date = new Date(timeStamp).getDate();
  var hour = ("0" + new Date(timeStamp).getHours()).slice(-2);
  var minute = ("0" + new Date(timeStamp).getMinutes()).slice(-2);

  result = "{0}-{1}-{2} {3}:{4}".format(year, month, date, hour, minute);

  return result;
}


$('.nav-pills li').on('click', function () {
    $('.report-content').empty();
    var report_type = $(this).attr('report-type');
    
    if(report_type == 'period'){
    	$("#period-input").css('display','block'); 
    }else{
    	$("#period-input").css('display','none'); 
    }
});

$('button[name="view-amount"]').on('click', function(e) {

    var from_date = $("#from_date").val();
    var to_date   = $("#to_date").val();
    if($(this).data("type")=='period' && (from_date=='' || to_date=='')){
    	alert("Please input date range!");
    	from_date.focus(); 
    	return;
    } 
       
    $.ajax({
        url: "<?php echo $this->Html->url(array('controller' => 'report', 'action' => 'getAmountInfo')); ?>",
        method: "post",
        data: {
          type     : $(this).data("type"),
          from_date: from_date,
          to_date  : to_date,          
        },
        success: function (json) {
            // console.log(JSON.parse(json));
            var objs = JSON.parse(json);
            $('.report-content').empty();
            // $('.report-content').append()

            $('.report-content').append(
              objs.map( (obj) => {
                  var startTimeStr = new Date(obj.start_time)
                  return $('#amount-info').html().format(obj.tax.toFixed(2), obj.total.toFixed(2), obj.real_total.toFixed(2), obj.paid_cash_total.toFixed(2), obj.paid_card_total.toFixed(2), getTimeStr(obj.start_time * 1000),getTimeStr(obj.end_time * 1000), obj.card_tip_total.toFixed(2),obj.default_tip_card.toFixed(2),obj.default_tip_cash.toFixed(2));
              })
            );
        }
    })
});

$('button[name="print-amount"]').on('click', function(e) 
{
    var from_date = $("#from_date").val();
    var to_date   = $("#to_date").val();

    if($(this).data("type")=='period' && (from_date=='' || to_date=='')){
    	alert("Please input date range!");
    	from_date.focus(); 
    	return;
    } 

    $.ajax({
        url: "<?php echo $this->Html->url(array('controller' => 'report', 'action' => 'printTodayOrders')); ?>",
        method: "post",
        // async: false,
        data:{
          type: $(this).data("type"),
          from_date: from_date,
          to_date  : to_date,          
        },
        success: function (html) {
            alert("Finished");
        },
        error: function (html) {
            alert("error");
        }
    });
});

$('button[name="view-items"]').on('click', function(e) {

    var from_date = $("#from_date").val();
    var to_date   = $("#to_date").val();

    if($(this).data("type")=='period' && (from_date=='' || to_date=='')){
    	alert("Please input date range!");
    	from_date.focus(); 
    	return;
    } 

    $.ajax({
        url: "<?php echo $this->Html->url(array('controller' => 'report', 'action' => 'getItemsInfo')); ?>",
        method: "post",
        cache:false,
        data: {
          type: $(this).data("type"),
          from_date: from_date,
          to_date  : to_date,          
        },
        success: function (json) {
            // console.log(json);
            var objs = JSON.parse(json);
            var obj = objs[0];

            $('.report-content').empty();
            $('.report-content').append(
              obj.items.map((item)=> {
                return '<li class="col-md-6 col-sm-6 col-xs-6">{0}</li> <li class="col-md-6 col-sm-6 col-xs-6 tax">{1}</li>'.format(item.name_xh, item.qty_sum);
              })
            );
        }
    });
});

$('button[name="print-items"]').on('click', function(e) {

    var from_date = $("#from_date").val();
    var to_date   = $("#to_date").val();

    if($(this).data("type")=='period' && (from_date=='' || to_date=='')){
    	alert("Please input date range!");
    	from_date.focus(); 
    	return;
    } 

    $.ajax({
        url: "<?php echo $this->Html->url(array('controller' => 'report', 'action' => 'printTodayItems')); ?>",
        method: "post",
        // async: false,
        data:{
          type: $(this).data("type"),
          from_date: from_date,
          to_date  : to_date,          
        },
        success: function (html) {
            alert("Finished");
        },
        error: function (html) {
            alert("error");
        }
    });
});

</script>

