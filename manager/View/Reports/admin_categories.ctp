<?php echo $this->Html->script(array('Chart.min'), array('inline' => false)); ?>
<?php echo $this->Html->css(array('sweet-alert.css', 'ie9.css', 'toastr.min.css', 'select2.min.css', 'DT_bootstrap.css', 'bootstrap-datetimepicker'), null, array('inline' => false));
echo $this->Html->script(array('select2.min.js', 'jquery.dataTables.min.js', 'table-data.js', 'sweet-alert.min.js', 'ui-notifications.js', 'bootstrap-datepicker'), array('inline' => false));
$idx = array();
$idxdata = array();
$i = 0;
foreach ($records as $rc) {
	$idx[] = $rc['name_zh'];
	//$idx[] = $rc['OrderItem']['name_en'];
	//$idx[] = $rc['OrderItem']['item_id'];
	$idxdata[] = $rc[0]['total'];
	if ($i++ > 11) break;
}
$idxstr = join("','", $idx);
$idxdatastr = join("','", $idxdata);
?>

<script type="text/javascript">
    $(document).ready(function () {
        UINotifications.init();
        TableData.init();
        // Index.init();

        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            endDate: '0d'
        });

        $('#reset_button').click(function(){
            $('.reset-field').val('');
            $('#order_by').val('Order.created DESC');
        });

        $('#records_per_page').change(function(){
            $('#pageSizeForm').submit();
        });


        var data = {
            labels: ['<?php echo $idxstr; ?>'],
            datasets: [{
                label: 'Cousines Sale ',
                fillColor: 'rgba(0,0,220,0.8)',
                strokeColor: 'rgba(220,220,220,1)',
                pointColor: 'rgba(220,220,220,1)',
                pointStrokeColor: '#fff',
                pointHighlightFill: '#fff',
                pointHighlightStroke: 'rgba(220,220,220,1)',
                data: ['<?php echo $idxdatastr; ?>']
            }]
        };

        var options = {

            maintainAspectRatio: false,

            // Sets the chart to be responsive
            responsive: true,

            ///Boolean - Whether grid lines are shown across the chart
            scaleShowGridLines: true,

            //String - Colour of the grid lines
            scaleGridLineColor: 'rgba(0,0,0,.05)',

            //Number - Width of the grid lines
            scaleGridLineWidth: 1,

            //Boolean - Whether the line is curved between points
            bezierCurve: false,

            //Number - Tension of the bezier curve between points
            bezierCurveTension: 0.4,

            //Boolean - Whether to show a dot for each point
            pointDot: true,

            //Number - Radius of each point dot in pixels
            pointDotRadius: 4,

            //Number - Pixel width of point dot stroke
            pointDotStrokeWidth: 1,

            //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
            pointHitDetectionRadius: 20,

            //Boolean - Whether to show a stroke for datasets
            datasetStroke: true,

            //Number - Pixel width of dataset stroke
            datasetStrokeWidth: 2,

            //Boolean - Whether to fill the dataset with a colour
            datasetFill: true,

            // Function - on animation progress
            onAnimationProgress: function() {
            },

            // Function - on animation complete
            onAnimationComplete: function() {
            },

            //String - A legend template
            legendTemplate: '<ul class="tc-chart-js-legend"><% for (var i=0; i<datasets.length; i++){%><li><span style="background-color:<%=datasets[i].strokeColor%>"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>'
        };
        // Get context with jQuery - using jQuery's .get() method.
        var ctx = $("#chart1").get(0).getContext("2d");
        // This will get the first returned node in the jQuery collection.
        // var chart1 = new Chart(ctx).Line(data, options);
        var chart1 = new Chart(ctx).Bar(data, options);
        //generate the legend
        // var legend = chart1.generateLegend();
        //and append it to your page somewhere
        $('#chartLegend').append(chart1);
    });

</script>

<?php 
$search_txt = $status = $is_verified = $registered_from = $registered_till = '';
$search = @$this->Session->read('order_search');
$search_txt = @$search['search'];
$table_status = @$search['table_status'];
$paid_by = @$search['paid_by'];
$cooking_status = @$search['cooking_status'];

$registered_from = @$search['registered_from'];
$registered_till = @$search['registered_till'];

?>
<style>
.radio, .checkbox {
    margin-left: 22px;
}
.checkbox label{
  background-color: #7E7E7E;
  border-color: #7E7E7E;
  color: #ffffff;
  transition: all 0.3s ease 0s !important;
  background-image: none !important;
  box-shadow: none !important;
  outline: none !important;
  position: relative;
  display: inline-block;
  padding: 6px 12px;
  margin-bottom: 0;
  font-size: 14px;
  font-weight: 400;
  line-height: 1.42857143;
  text-align: center;
  white-space: nowrap;
  vertical-align: middle;
  -ms-touch-action: manipulation;
  touch-action: manipulation;
  cursor: pointer;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
  background-image: none;
  border: 1px solid transparent;
  border-radius: 4px;
}
</style>


<div id="app">
    <!-- sidebar -->
    <?php echo $this->element('sidebar'); ?>

    <!-- / sidebar -->
    <div class="app-content">
        <!-- start: TOP NAVBAR -->
        <?php echo $this->element('header'); ?>
        <!-- end: TOP NAVBAR -->
        <div class="main-content" >
            <div class="wrap-content container" id="container">
                <section id="page-title">
                    <div class="row">
                        <div class="col-sm-12 col-md-12">
                            <h1 class="mainTitle pull-left">Categories Reports List</h1>
                        </div>                        
                    </div>
                </section>
                <?php echo $this->Session->flash(); ?>

                <div class="container-fluid container-fullw bg-white">
                    <!-- start: SEARCH FORM START -->
                    <!-- start: SEARCH FORM START -->
                    <div class="border-around margin-bottom-15 padding-10">
                        <?php echo $this->Form->create('Order', array('url' => array('controller' => 'reports', 'action' => 'categories', 'admin' => true), 'class' => 'form', 'role' => 'search', 'autocomplete' => 'off', 'type'=>'get')); ?>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">From</label>
                                <?php echo $this->Form->input('dtfrom', array('value' => $dtfrom, 'type' => 'text', 'class' =>'form-control datepicker reset-field', 'div' => false, 'label' => false, 'required' => false)); ?>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">To</label>
                                <?php echo $this->Form->input('dtto', array('value' => $dtto, 'type' => 'text', 'class' =>'form-control datepicker reset-field', 'div' => false, 'label' => false, 'required' => false)); ?>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                    <label class="control-label col-md-12">&nbsp;</label>
                                <?php echo $this->Form->button('Reset <i class="fa fa-times-circle"></i>',array('class' => 'btn btn-primary btn-wide pull-right','type' => 'button','id' => 'reset_button'));
                                echo $this->Form->button('Search <i class="fa fa-arrow-circle-right"></i>',array('class' => 'btn btn-primary btn-wide pull-right margin-right-10','type' => 'submit','id' => 'submit_button')) ?>
                            </div>
                        </div>
                        <?php echo $this->Form->end(); ?>
                        <div class="clearfix"></div>
                    </div>
                    <?php echo $this->Form->end(); ?>
                    
                    <div class="panel panel-white no-radius" id="visits">
                        <div class="panel-heading border-light">
                            <h1 class="panel-title"> Categories Sales </h1>
                        </div>
                        <div collapse="visits" class="panel-wrapper">
                            <div class="panel-body">
                                <div class="height-350">
                                    <canvas id="chart1" class="full-width"></canvas>
                                    <div class="margin-top-20">
                                        <div class="inline pull-left">
                                            <div id="chartLegend" class="chart-legend"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-striped table-bordered table-hover table-full-width">
                                <thead>
                                <tr>
                                    <!-- th>ID #</th -->
                                    <th>Name</th>
                                    <th>Totals</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if (!empty($records)) { ?>
                                    <?php foreach ($records as $rc) { ?>
                                        <tr>
                                            <!-- td><?php echo $rc['OrderItem']['category_id']; ?></td -->
                                            <td><?php echo ucfirst($rc['name_zh']); ?> ( <?php echo ucfirst($rc['name_en']); ?> )</td>
                                            <td><?php echo $rc[0]['total']; ?></td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="10">No Order here.</td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
               </div>
            </div>
        </div>
    </div>
    <!-- start: FOOTER -->
    <?php echo $this->element('footer'); ?>
    <!-- end: FOOTER -->
</div>