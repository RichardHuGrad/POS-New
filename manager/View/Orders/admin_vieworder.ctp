<style>
.radio, .checkbox {
    margin-left: 22px;
}
.radio label{
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
<script>
function valid() {
    if($("#OrderTableStatusV").is(":checked") && !$("#OrderReason").val()){
        alert("Please fill a reason");
        return false;
    }
    else {
        return true;
    }

}
</script>
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
                <!-- start: PAGE TITLE -->
                <section id="page-title">
                    <div class="row">
                        <div class="col-sm-8">
                            <h1 class="mainTitle">View Order Details</h1>                            
                        </div>                        
                    </div>
                </section>
                <!-- end: PAGE TITLE -->
                <!-- Global Messages -->
                <?php echo $this->Session->flash(); ?>
                <!-- Global Messages End -->
                <!-- start: FORM VALIDATION EXAMPLE 1 -->
                <div class="container-fluid container-fullw bg-white" style="min-height: 550px;">
                    <div class="row">
                        <div class="col-md-12">   
                            
                            <div style="clear:both;"></div>
                            <div class="box-footer" style="margin-bottom: 18px;">
                                <?php echo $this->Form->button('Back', array('type' => 'button', 'class' => 'btn btn-default', 'onclick' => 'window.history.back()')); ?>
                             </div>
                             <div class="col-md-4 all_submenu">  
                                <div class="col-md-12 sub_items_heading">
                                    Table <?php echo $Order_detail['Order']['order_type'].$Order_detail['Order']['table_no']; ?>
                                </div>
                                <div class="col-md-12 sub_items_heading">
                                    Order #<?php 
                                    $is_super_admin = $this->Session->read('Admin.is_super_admin');      

                                    echo ('Y' <> $is_super_admin)?str_pad($Order_detail['Order']['reorder_no'], 4, 0, STR_PAD_LEFT):$Order_detail['Order']['order_no'].", ".date('h:i a', strtotime($Order_detail['Order']['created'])); ?>
                                </div>
                                <!-- show all menu items here -->
                                <?php
                                if (!empty(@$Order_detail['OrderItem'])) {
                                    foreach ($Order_detail['OrderItem'] as $key => $value) {
                                        ?>
                                        <!-- to show name of item -->
                                        <div class="col-md-12 sub_items" ><?php echo $value['name_en']; ?></div>

                                        <!-- to show the extras item name -->
                                        <!-- <div class="col-md-12"><?php //echo implode(",", $selected_extras_name); ?></div> -->
                                        <?php
                                    }
                                ?>
                                <?php } else {
                                    echo "No items selected";
                                }?>
                             </div>
                              <?php echo $this->Form->create('Order', array('onsubmit'=>'return valid()')); ?>
                             <div class="col-md-8">  
                                <div class="col-md-12"> 
                                    <?php echo $this->Form->input('reason', array('type' => 'textarea', 'placeholder' => 'Reason Message', 'class' =>'form-control reset-field', 'div' => false, 'label' => false, 'required' => false)); ?>
                                </div>
                                <div class="col-md-12" style="margin-top: 23px;"> 
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <h4><label class="control-label">Payment Status</label></h4>
                                            <?php 
                                            $options = array(
                                                    'P'=>'PAID',
                                                    'N'=>'UNPAID',
                                                    'V'=>'VOID'
                                                );
                                            echo $this->Form->input('table_status', array( 'type' => 'radio',
                                                 'separator'=> '</div><div class="radio">',
                                                 'before' => '<div class="radio">',
                                                 'after' => '</div>',
                                                 'div' => false, 
                                                 'options' =>  $options,
                                                 'label' => true,
                                                 "legend" => false,
                                                 'required'=>true,
                                                 'class'=>' validate[required]'
                                               )
                                            );    
                                            ?>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                             <h4><label class="control-label">Payment Type</label></h4>
                                            <?php 
                                            $options = array(
                                                    'CASH'=>'CASH',
                                                    'CARD'=>'CARD',
                                                    'MIXED'=>'MIXED'
                                                );
                                            echo $this->Form->input('paid_by', array( 'type' => 'radio',
                                                 'separator'=> '</div><div class="radio">',
                                                 'before' => '<div class="radio">',
                                                 'after' => '</div>',
                                                 'div' => false, 
                                                 'options' =>  $options,
                                                 'label' => true,
                                                 "legend" => false,
                                                 'required'=>false,
                                                 'class'=>' validate[required]'
                                               )
                                            );    
                                            ?>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                             <h4><label class="control-label">Cooking Status</label></h4>
                                            <?php 
                                            $options = array(
                                                    'COOKED'=>'COOKED',
                                                    'UNCOOKED'=>'UNCOOKED',
                                                );
                                            echo $this->Form->input('cooking_status', array( 'type' => 'radio',
                                                 'separator'=> '</div><div class="radio">',
                                                 'before' => '<div class="radio">',
                                                 'after' => '</div>',
                                                 'div' => false, 
                                                 'options' =>  $options,
                                                 'label' => true,
                                                 "legend" => false,
                                                 'required'=>true,
                                                 'class'=>' validate[required]'
                                               )
                                            );   
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12" style="">
                                    <?php
                                    echo $this->Form->button('Update',array('class' => 'btn btn-primary btn-wide pull-right margin-right-10 advance_panel','type' => 'submit','id' => 'reorder_button')) ?>
                                </div>
                             </div>
                             <?php echo $this->Form->end(); ?>
                        </div>
                    </div>
                </div>
                <!-- end: FORM VALIDATION EXAMPLE 1 -->
            </div>
        </div>
    </div>
    <!-- start: FOOTER -->
    <?php echo $this->element('footer'); ?>
    <!-- end: FOOTER -->
</div>