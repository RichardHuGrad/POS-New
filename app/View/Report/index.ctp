<?php
  echo $this->Html->css(array('report'));
 ?>

<div id="app" class="container clearfix">
    <div class="text-center">
      <h2><?php echo __('Admin Report Functions'); ?></h2>
    </div>
    <div class="col-md-3 col-sm-3 col-xs-12">
        <ul class="nav nav-pills nav-stacked">
            <li><a data-toggle="pill" href="#today-menu"><?php echo __('Today'); ?></a></li>
            <li><a data-toggle="pill" href="#yesterday-menu"><?php echo __('Yesterday'); ?></a></li>
            <li><a data-toggle="pill" href="#month-menu"><?php echo __('Current Month'); ?></a></li>
        </ul>
    </div>

    <div class="tab-content col-md-9 col-sm-9 col-xs-12">
        <template id="" v-for="type in types">
            <report-button-group :type="type"></report-button-group>
        </template>
    </div>
</div>
<!-- <div class="container clearfix" >
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
</div> -->






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


<template id="amount">
    <div class="">
        <li class="col-md-6 col-sm-6 col-xs-6">{{start_time}}</li> <li class="col-md-6 col-sm-6 col-xs-6 tax">{{end_time}}</li>
        <li class="col-md-6 col-sm-6 col-xs-6"><?php echo __('Tax'); ?></li> <li class="col-md-6 col-sm-6 col-xs-6">{{tax}}</li>
        <li class="col-md-6 col-sm-6 col-xs-6"><?php echo __('Total'); ?></li> <li class="col-md-6 col-sm-6 col-xs-6">{{total}}</li>
        <li class="col-md-6 col-sm-6 col-xs-6"><?php echo __('Received Total'); ?></li> <li class="col-md-6 col-sm-6 col-xs-6">{{received_total}}</li>
        <li class="col-md-6 col-sm-6 col-xs-6"><?php echo __('Received Cash'); ?></li> <li class="col-md-6 col-sm-6 col-xs-6">{{received_cash}}</li>
        <li class="col-md-6 col-sm-6 col-xs-6"><?php echo __('Received Card'); ?></li> <li class="col-md-6 col-sm-6 col-xs-6">{{received_card}}</li>
    </div>
</template>

<template id="report-button-group">
    <div :id="type + '-menu'" class="tab-pane fade">
        <div class="button-group">
            <button class="btn btn-lg btn-info" type="button" v-on:click="view_amount"><?php echo __('Check Sales Total'); ?></button>
            <button class="btn btn-lg btn-info" type="button" v-on:click="print_amount"><?php echo __('Print Sales Total'); ?></button>
            <button class="btn btn-lg btn-info" type="button" v-on:click="view_items"><?php echo __('Check Sales Items'); ?></button>
            <button class="btn btn-lg btn-info" type="button" v-on:click="print_items"><?php echo __('Print Sales Items'); ?></button>
        </div>
        <div class="report-content">
            {{type}}
        </div>
    </div>
</template>

<script type="text/javascript">
    Vue.component('amount-info', {
        template: '#amount',
        data: function() {
            return {
                start_time: '1',
                end_time: '1',
                tax: '2',
                total: '1',
                received_total: '1',
                received_cash: '1',
                received_card: '1',
            }
        }
    })

    Vue.component('cousine-info', {
        template: `  <div class="">
            <li class="col-md-6 col-sm-6 col-xs-6">{{name}}</li> <li class="col-md-6 col-sm-6 col-xs-6 tax">{{number}}</li>
          </div>`,

        data: function() {
            return {
                name: 'test',
                number: '12'
            }
        }

    })

    Vue.component('report-button-group', {
        template: '#report-button-group',
        props: ['type'],
        methods: {
            view_amount: function() {
                this.$http.post('<?php echo $this->Html->url(array('controller' => 'report', 'action' => 'getAmountInfo')); ?>',
                                {type: this.type},
                                {emulateJSON: true})
                            .then(response => {
                                    console.log(response.body)
                                });
            },
            print_amount: function() {
                this.$http.post("<?php echo $this->Html->url(array('controller' => 'report', 'action' => 'printTodayOrders')); ?>",
                                {type: this.type},
                                {emulateJSON: true})
                            .then(response => {
                                    console.log(response.body)
                                });
            },
            view_items: function() {
                this.$http.post("<?php echo $this->Html->url(array('controller' => 'report', 'action' => 'getItemsInfo')); ?>",
                                {type: this.type},
                                {emulateJSON: true})
                            .then(response => {
                                    console.log(response.body)
                                });
            },
            print_items: function() {
                this.$http.post( "<?php echo $this->Html->url(array('controller' => 'report', 'action' => 'printTodayItems')); ?>",
                                {type: this.type},
                                {emulateJSON: true}
                            ).then(response => {
                                    console.log(response.body)
                                });
            }
        }
    })

    var app = new Vue({
        el: '#app',
        data: {
            types: ['today', 'yesterday', 'month']
        },
        methods: {
        }
    })
</script>

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


// $('.nav').on('click', function () {
//     $('.report-content').empty();
// });

// $('button[name="view-amount"]').on('click', function(e) {
//     console.log($(this).data("type"));
//     $.ajax({
//         url: "<?php echo $this->Html->url(array('controller' => 'report', 'action' => 'getAmountInfo')); ?>",
//         method: "post",
//         data: {
//           type: $(this).data("type"),
//         },
//         success: function (json) {
//             // console.log(JSON.parse(json));
//             var objs = JSON.parse(json);
//             $('.report-content').empty();
//             // $('.report-content').append()
//
//             $('.report-content').append(
//               objs.map( (obj) => {
//                   var startTimeStr = new Date(obj.start_time)
//                   return $('#amount-info').html().format(obj.tax.toFixed(2), obj.total.toFixed(2), obj.real_total.toFixed(2), obj.paid_cash_total.toFixed(2), obj.paid_card_total.toFixed(2), getTimeStr(obj.start_time * 1000),getTimeStr(obj.end_time * 1000));
//               })
//             );
//         }
//     })
// });
//
// $('button[name="print-amount"]').on('click', function(e) {
//     $.ajax({
//         url: "<?php echo $this->Html->url(array('controller' => 'report', 'action' => 'printTodayOrders')); ?>",
//         method: "post",
//         // async: false,
//         data:{
//           type: $(this).data("type"),
//         },
//         success: function (html) {
//             alert("Finished");
//         },
//         error: function (html) {
//             alert("error");
//         }
//     });
// });
//
// $('button[name="view-items"]').on('click', function(e) {
//     $.ajax({
//         url: "<?php echo $this->Html->url(array('controller' => 'report', 'action' => 'getItemsInfo')); ?>",
//         method: "post",
//         data: {
//           type: $(this).data("type"),
//         },
//         success: function (json) {
//             // console.log(json);
//             var objs = JSON.parse(json);
//             var obj = objs[0];
//
//             $('.report-content').empty();
//             $('.report-content').append(
//               obj.items.map((item)=> {
//                 return '<li class="col-md-6 col-sm-6 col-xs-6">{0}</li> <li class="col-md-6 col-sm-6 col-xs-6 tax">{1}</li>'.format(item.name_xh, item.qty_sum);
//               })
//             );
//         }
//     });
// });
//
// $('button[name="print-items"]').on('click', function(e) {
//     $.ajax({
//         url: "<?php echo $this->Html->url(array('controller' => 'report', 'action' => 'printTodayItems')); ?>",
//         method: "post",
//         // async: false,
//         data:{
//           type: $(this).data("type"),
//         },
//         success: function (html) {
//             alert("Finished");
//         },
//         error: function (html) {
//             alert("error");
//         }
//     });
// });
</script>
