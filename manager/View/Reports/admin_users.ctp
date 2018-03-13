<?php echo $this->Html->script(array('Chart.min'), array('inline' => false)); ?>
<?php echo $this->Html->css(array('sweet-alert.css', 'ie9.css', 'toastr.min.css', 'select2.min.css', 'DT_bootstrap.css', 'bootstrap-datetimepicker'), null, array('inline' => false));
echo $this->Html->script(array('select2.min.js', 'jquery.dataTables.min.js', 'table-data.js', 'sweet-alert.min.js', 'ui-notifications.js', 'bootstrap-datepicker'), array('inline' => false));
?>

<script type="text/javascript">
    $(document).ready(function () {
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
                            <h1 class="mainTitle pull-left">Customers Reports List</h1>
                        </div>                        
                    </div>
                </section>
                <?php echo $this->Session->flash(); ?>

                <div class="container-fluid container-fullw bg-white">
                    <!-- start: SEARCH FORM START -->
                    <!-- start: SEARCH FORM START -->
                    <div class="border-around margin-bottom-15 padding-10">
                        <?php echo $this->Form->create('Order', array('url' => array('controller' => 'reports', 'action' => 'customers', 'admin' => true), 'class' => 'form', 'role' => 'search', 'autocomplete' => 'off', 'type'=>'get')); ?>
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
                    
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-striped table-bordered table-hover table-full-width">
                                <thead>
                                <tr>
                                    <th>ID #</th>
                                    <th>Name</th>
                                    <th>Orders</th>
                                    <th>Total Amount</th>
                                    <th>Paid Amount</th>
                                    <th>Tip Amount</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if (!empty($records)) { ?>
                                    <?php foreach ($records as $rc) { ?>
                                        <tr>
                                            <td><?php echo $rc['c']['id']; ?></td>
                                            <td><?php echo $rc['c']['firstname'] . " " . $rc['c']['lastname']; ?></td>
                                            <td><?php echo $rc[0]['cnt']; ?></td>
                                            <td><?php echo number_format($rc[0]['total'], 2); ?></td>
                                            <td><?php echo number_format($rc[0]['paid'], 2); ?></td>
                                            <td><?php echo number_format($rc[0]['tip'], 2); ?></td>
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