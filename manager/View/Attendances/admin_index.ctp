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

        jQuery('#records_per_page').change(function(){
            jQuery('#pageSizeForm').submit();
        });
    });
</script>

<?php 
$search_txt = $userid = $from_day = $to_day = '';
$search = @$this->Session->read('attendance_search');
$search_txt = @$search['search'];

$from_day = @$search['from_day'];
$to_day   = @$search['to_day'];

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
                            <h1 class="mainTitle pull-left">Attendance</h1>
                        </div>                        
                    </div>
                </section>
                <?php echo $this->Session->flash(); ?>

                <div class="container-fluid container-fullw bg-white">
                    <!-- start: SEARCH FORM START -->
                    <div class="border-around margin-bottom-15 padding-10">
                        <?php echo $this->Form->create('Attendance', array(
                            'url' => array('controller' => 'Attendances', 'action' => 'index', 'admin' => true), 'class' => 'form', 'role' => 'search', 'autocomplete' => 'off')
                        ); ?>


                        <div class="col-md-9" style="margin-top: 15px;">
                            <div class="form-group">
                                <div class="col-md-4">
                                    <?php echo $this->Form->input('search', array('type' => 'text', 'value' => $search_txt, 'placeholder' => 'Search userid', 'class' =>'form-control reset-field', 'div' => false, 'label' => false, 'required' => false)); ?>
                                </div>
                                <div class="col-md-4" >
                                    <?php echo $this->Form->input('from_day', array('value' => $from_day, 'placeholder' => 'From day', 'type' => 'text', 'class' =>'form-control datepicker reset-field', 'div' => false, 'label' => false, 'required' => false)); ?>
                                </div>
                                <div class="col-md-4" >
                                    <?php echo $this->Form->input('to_day', array('value' => $to_day, 'placeholder' => 'To day', 'type' => 'text', 'class' =>'form-control datepicker reset-field', 'div' => false, 'label' => false, 'required' => false)); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3" style="margin-top:28px">
                            <?php
                            echo $this->Form->button('Search <i class="fa fa-arrow-circle-right"></i>',array('class' => 'btn btn-primary btn-wide pull-right margin-right-10','type' => 'submit','id' => 'submit_button')) ?>
                        </div>

                        

                        <?php echo $this->Form->end(); ?>
                        <div class="clearfix"></div>
                    </div>

                    <?php echo $this->Form->create('PageSize', array(
                            'url' => array('controller' => 'attendances', 'action' => 'index', 'admin' => true), 'class' => 'form', 'autocomplete' => 'off', 'id' => 'pageSizeForm')
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
                    
                    <div class="row">
                        <div class="col-md-12">                           
                            <table class="table table-striped table-bordered table-hover table-full-width">
                                <thead>
                                    <tr>
                                    	  <th>Select</th>
                                        <th><?php echo @$this->Paginator->sort('userid'); ?></th>
                                        <th><?php echo @$this->Paginator->sort('name'); ?></th>
                                        <th><?php echo @$this->Paginator->sort('checkin'); ?></th>
                                        <th><?php echo @$this->Paginator->sort('checkout'); ?></th>
                                        <th><?php echo @$this->Paginator->sort('working_hours'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $ids = [];
                                        if(!empty($records)) { 
                                    ?>
                                        <?php 
                                            foreach ($records as $row) { 
                                            	 $ids[] = $row['Attendance']['id'];
                                        ?>

                                                <?php 
                                                //if('Y' == $is_super_admin){
                                                ?>
                                                   <td><input value="<?php echo $row['Attendance']['id']; ?>"  type="checkbox" class="ids" /></td>
                                                <?php //} 
                                                ?>
                                            	
                                            	  <td><?php echo $row['Attendance']['userid']; ?>
                                                </td>
                                            	  <td><?php echo $row['Attendance']['name']; ?>
                                                </td>
                                                <td><?php echo $row['Attendance']['checkin']; ?></td>
                                                <td><?php echo $row['Attendance']['checkout']; ?></td>
                                                <td><?php echo $row['Attendance']['working_hours']; ?></td>
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
                                            <td colspan="8">No records here.</td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>


                            <div class="col-md-6" style="margin-top: 22px;">
                                <?php 
                                if('Y' == $is_super_admin){
                                ?>
                                <div class="pull-left advance_panel col-md-8">
                                    <div class="checkbox col-md-4" style="margin-left:0px; margin-top:0px">
                                        <label id="select_all">Select All</label>
                                    </div>  
                                    <div class="checkbox col-md-4" style="margin-left:0px; margin-top:0px">
                                        <label  id="unselect_all"> Unselect All</label>
                                    </div>          
                                    <!-- <div class="checkbox col-md-4" style="margin-left:0px; margin-top:0px">
                                        <label  id="delete_attendance"> Delete</label>
                                    </div>    -->
                                    <button class="btn btn-info" id="delete_attendance">Delete</button>                
                                </div>
                            <?php }?>

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
        $(".ids").prop("checked", true);
    })
    $("#unselect_all").click(function() {
        $(".ids").prop("checked",false);
    })
    <?php 
    if('Y' == $is_super_admin){
    ?>
        $(".advance_panel").hide();
    <?php }?>
    $("#advance_setting").click(function() {
        $(".advance_panel").show();
    })

    $('#delete_attendance').on('click', function () {
        var ids = [];
        $('.ids:checked').each(function() {
            ids.push($(this).val());
        });
        //console.log(ids);
        $.ajax({
            url:  "<?php echo $this->Html->url(array('controller' => 'attendances', 'action' => 'batch_delete', 'admin' => true)); ?>",
            method: "post",
            data: {"ids": ids},
            success: function(html) {
                window.location.reload();
            } 
        })
    })
})
</script>
