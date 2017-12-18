<?php
$option_status = array('A' => 'Active', 'I' => 'Inactive');
?>
<!-- <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script> -->
 <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<!-- <script src="<?php echo WEBSITE_URL; ?>pos/manager/js/jquery-collision.min.js"></script> -->
<script>
  $( function() {

    $( ".draggable" ).draggable({ 
        obstacle:".butNotHere",
        containment: "#containment-wrapper", scroll: true,
        preventCollision: true,
        start: function(event,ui) {
            // $(this).removeClass('butNotHere');

        },
        stop: function( event, ui ) {
             var l = ( 100 * parseFloat($(this).position().left) / parseFloat($(this).parent().width()) )+ "%" ;
             var t = ( 100 * parseFloat($(this).position().top) / parseFloat($(this).parent().height()) )+ "%" ;
             $(this).css("left" , l);
             $(this).css("top" , t);

             var class_name = $(this).attr("id");
             $("."+class_name).val($(this).attr("style"));
        } 
    });

    $("ul#containment-wrapper li").map(function(){
         var l = ( 100 * parseFloat($(this).position().left) / parseFloat($(this).parent().width()) )+ "%" ;
         var t = ( 100 * parseFloat($(this).position().top) / parseFloat($(this).parent().height()) )+ "%" ;
         $(this).css("left" , l);
         $(this).css("top" , t);

        var class_name = $(this).attr("id");
        $("."+class_name).val($(this).attr("style"));
    })
     $("ul#containment-wrapper li").css("position", 'absolute');
  });
  </script>
  <style>
  .clearfix.draggable    
  {
    background-color: #ccc;margin-bottom: 20px;margin-right: 2%;float:left;
    width:85px; 
    height:55px; 
    border:1px solid #ccc;  
    /*width: 11%;*/
    transition: background-color 300ms linear 0s;
    cursor: pointer;
  }

  .ui-draggable-dragging {
    background: #007aff none repeat scroll 0 0 !important;
    color: #fff;
    transition: background-color 300ms linear 0s;
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
                <!-- start: PAGE TITLE -->
                <section id="page-title">
                    <div class="row">
                        <div class="col-sm-8">
                            <h1 class="mainTitle">
                            <?php 
                                echo 'Update Table Order';?>
                            </h1>
                        </div>                        
                    </div>

                </section>
                <!-- end: PAGE TITLE -->
                <!-- Global Messages -->
                <?php echo $this->Session->flash(); ?>
                <!-- Global Messages End -->
                <div class="container-fluid container-fullw bg-white">
                    <div class="row">
                        <div class="col-md-12" style="padding:0px">   
                            <?php echo $this->Form->create('Admin', array('method' => 'post', 'class' => 'form', 'role' => 'form', 'autocomplete' => 'off', 'type' => 'file'));
                                echo $this->Form->input('id', array('type' => 'hidden', 'required' => false)); 
                            ?>
                            <ul class="col-md-12 col-sm-12 col-xs-12" style="list-style:none; height:auto; overflow:auto; min-height:480px; padding:0"  id="containment-wrapper">                            
                                <?php
                                    $tables = $this->request->data['Admin']['table_order']?json_decode(@$this->request->data['Admin']['table_order'], true):array();

                                    for($i = 1; $i <= $this->request->data['Admin']['no_of_tables']; $i++) {
                                        ?>
                                        <li class="clearfix draggable" style="<?php echo @$tables[$i-1]; ?>"  id="style<?php echo $i ?>">
                                            <div class="form-group">
                                                <?php
                                                echo "<b>Table #".$i."</b>";
                                                ?>
                                            </div>
                                        </li>
                                        <input type="hidden" required="required" class="style<?php echo $i ?>" maxlength="200" value="<?php echo @$tables[$i-1]; ?>" multiple="multiple" name="data[Admin][table_order][]">
                                        <?php
                                    }
                                 ?>
                            </ul>


                            <div class="row">
                                <div class="col-md-12">
                                    <div>
                                        <span class="symbol required"></span>Required Fields
                                        <hr>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-7">                                        
                                </div>
                                <div class="col-md-5">
                                    <?php 
                                    echo $this->Form->button('Save <i class="fa fa-arrow-circle-right"></i>',array('class' => 'btn btn-primary btn-wide pull-left_form','type' => 'submit','id' => 'submit_button'));
                                    echo $this->Html->link('Cancel <i class="fa fa-times-circle"></i>',
                                            array('plugin' => false,'controller' => 'admins','action' => 'users', 'admin' => true),
                                            array('class' => 'btn btn-primary btn-wide pull-right', 'escape' => false)
                                        );
                                    ?>
                                </div>
                            </div>
                            <?php echo $this->Form->end(); ?>
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