<?php echo $this->Html->css(array('sweet-alert.css', 'ie9.css', 'toastr.min.css', 'select2.min.css', 'DT_bootstrap.css', 'bootstrap-datetimepicker'), null, array('inline' => false));
echo $this->Html->script(array('select2.min.js', 'jquery.dataTables.min.js', 'table-data.js', 'sweet-alert.min.js', 'ui-notifications.js', 'bootstrap-datepicker'), array('inline' => false)); ?>

<script type="text/javascript">
    jQuery(document).ready(function () {
        UINotifications.init();
        TableData.init();

        jQuery('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            endDate: '0d'
        });

        jQuery('#reset_button').click(function(){
            jQuery('.reset-field').val('');
            jQuery('#order_by').val('Order.created DESC');
        });

        jQuery('#records_per_page').change(function(){
            jQuery('#pageSizeForm').submit();
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
                            <h1 class="mainTitle pull-left">Orders List</h1>
                        </div>                        
                    </div>
                </section>
                <?php echo $this->Session->flash(); ?>

                <div class="container-fluid container-fullw bg-white">
                    <!-- start: SEARCH FORM START -->
                    <div class="border-around margin-bottom-15 padding-10">
                        <?php echo $this->Form->create('Order', array(
                            'url' => array('controller' => 'orders', 'action' => 'index', 'admin' => true), 'class' => 'form', 'role' => 'search', 'autocomplete' => 'off')
                        ); ?>


                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label">Payment Status</label>
                                <?php 
                                $options = array(
                                        'P'=>'PAID',
                                        'N'=>'UNPAID'
                                    );
                                echo $this->Form->input('table_status', array( 
                                    'multiple' => 'checkbox',
                                    'div' => false, 
                                    'options' =>  $options,
                                    'label' => false,
                                    "legend" => false,               'value' => $table_status,                             
                                   ), array('class'=>'validate[minCheckbox[1]] checkbox')
                                );   
                                ?>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label">Payment Type</label>
                                <?php 
                                $options = array(
                                        'CASH'=>'CASH',
                                        'CARD'=>'CARD',
                                        'MIXED'=>'MIXED'
                                    );
                                echo $this->Form->input('paid_by', array( 
                                    'multiple' => 'checkbox',
                                    'div' => false, 
                                    'options' =>  $options,
                                    'label' => false,
                                    "legend" => false,      'value' => $paid_by,                                      
                                   ), array('class'=>'validate[minCheckbox[1]] checkbox')
                                );   
                                ?>
                            </div>
                        </div>

                        <div class="col-md-8" style="margin-top: 30px;">
                            <div class="form-group">
                                <div class="col-md-4">
                                    <?php echo $this->Form->input('search', array('type' => 'text', 'value' => $search_txt, 'placeholder' => 'Search order number', 'class' =>'form-control reset-field', 'div' => false, 'label' => false, 'required' => false)); ?>
                                </div>
                                <div class="col-md-4" >
                                    <?php echo $this->Form->input('registered_from', array('value' => $registered_from, 'placeholder' => 'From date', 'type' => 'text', 'class' =>'form-control datepicker reset-field', 'div' => false, 'label' => false, 'required' => false)); ?>
                                </div>
                                <div class="col-md-4" >
                                    <?php echo $this->Form->input('registered_till', array('value' => $registered_till, 'placeholder' => 'To date', 'type' => 'text', 'class' =>'form-control datepicker reset-field', 'div' => false, 'label' => false, 'required' => false)); ?>
                                </div>
                            </div>
                            <div class="col-md-12" style="margin-top:28px">
                                <?php
                                echo $this->Form->button('Search <i class="fa fa-arrow-circle-right"></i>',array('class' => 'btn btn-primary btn-wide pull-right margin-right-10','type' => 'submit','id' => 'submit_button')) ?>
                            </div>
                        </div>

                        

                        <?php echo $this->Form->end(); ?>
                        <div class="clearfix"></div>
                    </div>

                    <?php echo $this->Form->create('PageSize', array(
                            'url' => array('controller' => 'orders', 'action' => 'index', 'admin' => true), 'class' => 'form', 'autocomplete' => 'off', 'id' => 'pageSizeForm')
                    ); ?>
                    <?php 
                    if('Y' ==  $is_super_admin){
                    ?>
                        <div class="form-group pull-left">
                            <label class="control-label">
                                <div class="checkbox">
                                    <input type="radio" id="advance_setting" />
                                    <label for="advance_setting">Advanced Setting</label>
                                </div>
                            </label>                           
                        </div>
                    <?php }?>
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
                                        <?php 
                                        if('Y' <> $is_super_admin){
                                        ?>
                                            <th><?php echo $this->Paginator->sort('order_no'); ?></th>
                                        <?php } else {
                                            ?>
                                            <th class="advance_panel">Display</th>
                                            <th><?php echo @$this->Paginator->sort('order_no'); ?></th>
                                            <th class="advance_panel">Reorder Number</th>
                                            <?php
                                        }?>
                                        <th><?php echo @$this->Paginator->sort('created'); ?></th>
                                        <th><?php echo @$this->Paginator->sort('total'); ?></th>
                                        <th><?php echo @$this->Paginator->sort('tip'); ?></th>
                                        <th><?php echo @$this->Paginator->sort('card_val', 'Card'); ?> </th>
                                        <th><?php echo @$this->Paginator->sort('cash_val', 'Cash'); ?> </th>
                                        <th><?php echo @$this->Paginator->sort('table_status', 'Status'); ?></th>
                                        <th>Payment Type</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $total = 0;
                                    $tips = 0;
                                    $ids = [];
                                    if (!empty($records)) { 
                                      foreach ($records as $customer) { 
                                        	$ids[] = $customer['Order']['id'];
                                            /* if($customer['Order']['is_hide'] == 'N') { */
                                          $total += $customer['Order']['total'];
					                                      
					                                $order_tips = $customer['Order']['tip'];
                                          $tips += $order_tips;
                                          /*
                                          if(($customer['Order']['paid_by'] == 'CARD') && !empty($order_tips)) {
						                                 $order_tips = "\$" . number_format($order_tips, 2);
                                          } else {
                                             $order_tips = " ";
                                          } */
					                             /* } */
                                            ?>
                                            <tr>
                                                <?php 
                                                if('Y' <> $is_super_admin){
                                                ?>
                                                   <td class="advance_panel">
                                                       <a href="<?php echo $this->Html->url(array('controller' => 'orders', 'action' => 'vieworder', 'admin' => true, base64_encode($customer['Order']['id']))) ?>"><u> <?php 
                                                         echo $customer['Order']['reorder_no']?str_pad($customer['Order']['reorder_no'], 4, 0, STR_PAD_LEFT):""; 
                                                       ?>
                                                   </a>
                                                    </td>
                                                <?php } else {
                                                    ?>
                                                    <!-- <td class="advance_panel"><input <?php if($customer['Order']['is_hide'] == 'N')echo "checked"; ?> value="<?php echo $customer['Order']['id']; ?>"  type="checkbox" name="data[Reorder][display][]" class="display" /></td> -->
                                                    <td class="advance_panel"><input value="<?php echo $customer['Order']['id']; ?>"  type="checkbox" name="data[Reorder][display][]" class="display" /></td>
                                                    <td><a href="<?php echo $this->Html->url(array('controller' => 'orders', 'action' => 'vieworder', 'admin' => true, base64_encode($customer['Order']['id']))) ?>"><u class="order_no"><?php echo $customer['Order']['order_no']; ?></u></a></td>
                                                    <td class="advance_panel">
                                                        <?php 
                                                        if($customer['Order']['is_hide'] == 'N') 
                                                            echo $customer['Order']['reorder_no']?str_pad($customer['Order']['reorder_no'], 4, 0, STR_PAD_LEFT):""; 
                                                        else    
                                                            echo $customer['Order']['hide_no']?"H".str_pad($customer['Order']['hide_no'], 4, 0, STR_PAD_LEFT):""; 
                                                        ?>
                                                    </td>
                                                    <?php
                                                }
                                                    ?>
                                                <td>
                                                    <?php echo date('Y/m/d h:i a', strtotime($customer['Order']['created'])); ?>
                                                </td>
                                                <td>$<?php echo number_format($customer['Order']['total'], 2); ?></td>
                                                <td>$<?php echo number_format($customer['Order']['tip'], 2); ?></td>
                                                <td>$<?php echo number_format($customer['Order']['card_val'], 2); ?></td>
                                                <td>$<?php echo number_format($customer['Order']['cash_val'], 2); ?></td>
                                                <td><?php echo @$table_status[$customer['Order']['table_status']]; ?></td>
                                                <td><?php echo $customer['Order']['paid_by']?$customer['Order']['paid_by']:"N/A"; ?></td>
                                                <td class="actions">
                                                    <?php echo $this->Html->link(__('Edit'), array('action' => 'edit', 'admin' => true,$customer['Order']['id'])); ?>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                            if('all' != $limit){ ?>
                                                <tr>
                                                    <td colspan="12">
                                                        <?php echo $this->element('pagination'); ?>
                                                    </td>
                                                </tr>
                                        <?php }
                                        } else {
                                        ?>
                                        <tr>
                                            <td colspan="8">No Orders here.</td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>


                            <div class="col-md-8" style="margin-top: 22px;">
                                <?php 
                                if('Y' == $is_super_admin){
                                ?>
                                <div class="pull-left advance_panel col-md-12">
                                    <div class="checkbox col-md-4" style="margin-left:0px; margin-top:0px">
                                        <label id="select_all">Select All</label>
                                    </div>  
                                    <div class="checkbox col-md-5" style="margin-left:0px; margin-top:0px">
                                        <label  id="unselect_all"> Unselect All</label>
                                    </div>          
									
                                    <button type="button" class="btn btn-info" id="delete_order">Delete</button>
                                                
                                </div>
                                <input type="hidden" name="data[Reorder][ids]" value=<?php echo implode(",", $ids); ?> />
                                <?php
                                //echo $this->Form->button('Reorder <i class="fa fa-refresh"></i>',array('class' => 'btn btn-primary btn-wide pull-right margin-right-10 advance_panel','type' => 'submit','id' => 'reorder_button')) ?>
                            <?php }?>

                            </div>
                            <div class="col-md-4" style="margin-top: 22px;">
                                <span class="btn btn-primary btn-wide pull-left margin-right-10">Total: $<?php echo number_format($total, 2); ?></span>
                                <span class="btn btn-primary btn-wide pull-left margin-right-10">Total Tips: $<?php echo number_format($tips, 2); ?></span>
                            </div>
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
<script>
$(document).ready(function() {

    $("#select_all").click(function() {
        $(".display").prop("checked", true);
    })
    $("#unselect_all").click(function() {
        $(".display").prop("checked",false);
    })
    <?php 
    if('Y' == $is_super_admin){
    ?>
        $(".advance_panel").hide();
    <?php }?>
    $("#advance_setting").click(function() {
        $(".advance_panel").show();
    })

    $('#delete_order').on('click', function () {
        var order_nos = [];
        $('.display:checked').parent().parent().find('.order_no').each(function() {
            order_nos.push($(this).text());
        });
        console.log(order_nos);
        $.ajax({
            url:  "<?php echo $this->Html->url(array('controller' => 'orders', 'action' => 'batch_delete', 'admin' => true)); ?>",
            method: "post",
            data: {order_nos: order_nos}, 
            success: function(response) {
                window.location.reload();
            } 
        })
    })
})
</script>
