<?php echo $this->Html->css(array('sweet-alert.css', 'ie9.css', 'toastr.min.css', 'select2.min.css', 'DT_bootstrap.css', 'bootstrap-datetimepicker'), null, array('inline' => false));
echo $this->Html->script(array('select2.min.js', 'jquery.dataTables.min.js', 'table-data.js', 'sweet-alert.min.js', 'ui-notifications.js', 'bootstrap-datepicker'), array('inline' => false)); ?>

<script type="text/javascript">
    $(document).ready(function () {
        UINotifications.init();
        TableData.init();

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
                            <h1 class="mainTitle pull-left">Reports List</h1>
                        </div>                        
                    </div>
                </section>
                <?php echo $this->Session->flash(); ?>

                <div class="container-fluid container-fullw bg-white">
                    <!-- start: SEARCH FORM START -->
                    <!-- start: SEARCH FORM START -->
                    <div class="border-around margin-bottom-15 padding-10">
                        <?php echo $this->Form->create('Order', array(
                            'url' => array('controller' => 'reports', 'action' => 'index', 'admin' => true), 'class' => 'form', 'role' => 'search', 'autocomplete' => 'off')
                        ); ?>

                        

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Date From</label>
                                <?php echo $this->Form->input('registered_from', array('value' => $registered_from, 'type' => 'text', 'class' =>'form-control datepicker reset-field', 'div' => false, 'label' => false, 'required' => false)); ?>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Date To</label>
                                <?php echo $this->Form->input('registered_till', array('value' => $registered_till, 'type' => 'text', 'class' =>'form-control datepicker reset-field', 'div' => false, 'label' => false, 'required' => false)); ?>
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


                    <?php echo $this->Form->create('PageSize', array(
                            'url' => array('controller' => 'reports', 'action' => 'index', 'admin' => true), 'class' => 'form', 'autocomplete' => 'off', 'id' => 'pageSizeForm')
                    ); ?>
                    <div class="form-group pull-right" style="margin-left:10px">
                        <label class="control-label">Records Per Page</label>
                        <?php echo $this->Form->input('records_per_page', array('options' => unserialize(PAGING_OPTIONS), 'value' => $limit, 'id' => 'records_per_page', 'class' => 'form-control', 'empty' => false, 'label' => false, 'div' => false)); ?>
                    </div>
                    <?php echo $this->Form->end(); ?>
                    
                    <?php
                    $table_status = array(
                        'P'=>'PAID',
                        'N'=>'UNPAID',
                        'V'=>'VOID',
                        );
                    ?>
                    <?php echo $this->Form->create('Reorder', array(
                            'url' => array('controller' => 'orders', 'action' => 'reorder', 'admin' => true), 'class' => 'form', 'autocomplete' => 'off', 'id' => 'reorder')
                    ); ?>
                    <div class="row">
                        <div class="col-md-12">                           
                            <table class="table table-striped table-bordered table-hover table-full-width">
                                <thead>
                                    <tr>
                                        <th>Counter Id</th>
                                        <th>Name</th>
                                        <th>Total number of orders</th>
                                        <th>Total Money</th>
                                        <th>Total Tax</th>
                                        <th>Total Tips </th>
                                        <th>Card Total </th>
                                        <th>Cash Total </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $total = 0;
                                    $tips = 0;
                                    $ids = [];
                                    if (!empty($records)) { ?>
                                        <?php foreach ($records as $customer) {
                                            ?>
                                            <tr>
                                                <td><?php echo $customer['Order']['counter_id']; ?></td>
                                                <td><?php echo $customer['Cashier']['firstname']." ".$customer['Cashier']['lastname']; ?></td>
                                                <td><?php echo $customer[0]['counter']; ?></td>
                                                <td>$<?php echo number_format($customer[0]['total'], 2); ?></td>
                                                <td>$<?php echo number_format($customer[0]['total_tax_amount'], 2); ?></td>
                                                <td>$<?php echo number_format($customer[0]['total_tip'], 2); ?></td>
                                                <td>$<?php echo number_format($customer[0]['total_card_val'], 2); ?></td>
                                                <td>$<?php echo number_format($customer[0]['total_cash_val'], 2); ?></td>
                                            </tr>
                                            <?php
                                        }
                                            if('all' != $limit){ ?>
                                                <tr>
                                                    <td colspan="8">
                                                        <?php echo $this->element('pagination'); ?>
                                                    </td>
                                                </tr>
                                        <?php }
                                        } else {
                                        ?>
                                        <tr>
                                            <td colspan="8">No Records Here.</td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    
                    </div>
                    <?php echo $this->Form->end(); ?>
                </div>
            </div>
        </div>
    </div>
    <!-- start: FOOTER -->
    <?php echo $this->element('footer'); ?>
    <!-- end: FOOTER -->
</div>