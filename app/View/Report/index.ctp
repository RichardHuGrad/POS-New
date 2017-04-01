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
            <li class="active"><a data-toggle="pill" href="#today-menu"><?php echo __('Today'); ?></a></li>
            <li><a data-toggle="pill" href="#yesterday-menu"><?php echo __('Yesterday'); ?></a></li>
            <li><a data-toggle="pill" href="#month-menu"><?php echo __('Current Month'); ?></a></li>
        </ul>
    </div>

    <div class="tab-content col-md-9 col-sm-9 col-xs-12 ">
        <div id="today-menu" class="tab-pane fade in active">
            <div class="button-group">
                <button class="btn btn-lg btn-info" type="button" name="view-amount" data-type="today"><?php echo __('Check Sales Total'); ?></button>
                <button class="btn btn-lg btn-info" type="button" name="print-amount" data-type="today"><?php echo __('Print Sales Total'); ?></button>
                <button class="btn btn-lg btn-info" type="button" name="view-items" data-type="today"><?php echo __('Print Sales Total'); ?></button>
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
        <div id="month-menu" class="tab-pane fade">
            <div class="button-group">
                <button class="btn btn-lg btn-info" type="button" name="view-amount" data-type="month"><?php echo __('Check Sales Total'); ?></button>
                <button class="btn btn-lg btn-info" type="button" name="print-amount" data-type="month"><?php echo __('Print Sales Total'); ?></button>
                <button class="btn btn-lg btn-info" type="button" name="view-items" data-type="month"><?php echo __('Print Sales Total'); ?></button>
                <button class="btn btn-lg btn-info" type="button" name="print-items" data-type="month"><?php echo __('Print Sales Items'); ?></button>
            </div>
        </div>
        <div class="report-content">
        </div>
    </div>
</div>

<div ng-app="reportApp" ng-controller="reportCtrl" class="container">
	<div id="calendar">
	</div>


	<div id="calendar-popup" class="modal fade">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title">Modal title</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
			<button class="btn btn-lg btn-info" type="button" ng-click="getSaleAmount()"><?php echo __('Check Sales Total'); ?></button>
			<button class="btn btn-lg btn-info" type="button" name="print-amount" data-type="today"><?php echo __('Print Sales Total'); ?></button>
			<button class="btn btn-lg btn-info" type="button" name="view-items" data-type="today"><?php echo __('Check Sales Items'); ?></button>
			<button class="btn btn-lg btn-info" type="button" name="print-items" data-type="today"><?php echo __('Print Sales Items'); ?></button>
			<div class="report-content">

			</div>
		  </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	      </div>
	    </div>
	  </div>
	</div>
</div>

<script id="amount-info" type="text/template">
    <div class="">
        <li class="col-md-6 col-sm-6 col-xs-6">{5}</li> <li class="col-md-6 col-sm-6 col-xs-6 tax">{6}</li>
        <li class="col-md-6 col-sm-6 col-xs-6"><?php echo __('Tax'); ?></li> <li class="col-md-6 col-sm-6 col-xs-6">{0}</li>
        <li class="col-md-6 col-sm-6 col-xs-6"><?php echo __('Total'); ?></li> <li class="col-md-6 col-sm-6 col-xs-6">{1}</li>
        <li class="col-md-6 col-sm-6 col-xs-6"><?php echo __('Received Total'); ?></li> <li class="col-md-6 col-sm-6 col-xs-6">{2}</li>
        <li class="col-md-6 col-sm-6 col-xs-6"><?php echo __('Received Cash'); ?></li> <li class="col-md-6 col-sm-6 col-xs-6">{3}</li>
        <li class="col-md-6 col-sm-6 col-xs-6"><?php echo __('Received Card'); ?></li> <li class="col-md-6 col-sm-6 col-xs-6">{4}</li>
    </div>
</script>
<script id="item-info" type="text/template">
  <div class="">
    <li class="col-md-6 col-sm-6 col-xs-6">{0}</li> <li class="col-md-6 col-sm-6 col-xs-6 tax">{1}</li>
  </div>
</script>



</body>

<?php
  echo $this->Html->css(array('report', 'lib/fullcalendar.min'));
  echo $this->Html->script(array('jquery.min.js', 'bootstrap.min.js', 'jquery.mCustomScrollbar.concat.min.js', 'barcode.js', 'fanticonvert.js', 'notify.min.js', 'flowtype.js', 'lib/moment.min.js','lib/fullcalendar.min.js', 'lib/angular.min.js','angular/reportApp.js', 'angular/controllers/report.js'));
 ?>



<script type="text/javascript">

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


$('.nav').on('click', function () {
    $('.report-content').empty();
});

$('button[name="view-amount"]').on('click', function(e) {
    console.log($(this).data("type"));
    $.ajax({
        url: "<?php echo $this->Html->url(array('controller' => 'report', 'action' => 'getAmountInfo')); ?>",
        method: "post",
        data: {
          type: $(this).data("type"),
        },
        success: function (json) {
            // console.log(JSON.parse(json));
            var objs = JSON.parse(json);
            $('.report-content').empty();
            // $('.report-content').append()

            $('.report-content').append(
              objs.map( (obj) => {
                  var startTimeStr = new Date(obj.start_time)
                  return $('#amount-info').html().format(obj.tax.toFixed(2), obj.total.toFixed(2), obj.real_total.toFixed(2), obj.paid_cash_total.toFixed(2), obj.paid_card_total.toFixed(2), getTimeStr(obj.start_time * 1000),getTimeStr(obj.end_time * 1000));
              })
            );
        }
    })
});

$('button[name="print-amount"]').on('click', function(e) {
    $.ajax({
        url: "<?php echo $this->Html->url(array('controller' => 'report', 'action' => 'printTodayOrders')); ?>",
        method: "post",
        // async: false,
        data:{
          type: $(this).data("type"),
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
    $.ajax({
        url: "<?php echo $this->Html->url(array('controller' => 'report', 'action' => 'getItemsInfo')); ?>",
        method: "post",
        data: {
          type: $(this).data("type"),
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
    $.ajax({
        url: "<?php echo $this->Html->url(array('controller' => 'report', 'action' => 'printTodayItems')); ?>",
        method: "post",
        // async: false,
        data:{
          type: $(this).data("type"),
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
