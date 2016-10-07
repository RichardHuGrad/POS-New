<?php  
if('' != $id){
    
    echo $this->Html->css(array('uploadfilemulti'), null, array('inline' => false));
    echo $this->Html->script(array('jquery.fileuploadmulti.min'), array('inline' => false));
    ?>
    
    <script type="text/javascript">
        $(document).ready(function()
        {
            
            var settings = {
                url: "<?php echo $this->Html->url(array('plugin' => false, 'controller' => 'products', 'action' => 'upload_images', 'admin' => true, base64_encode($id))); ?>",
                method: "POST",
                allowedTypes:"jpg,png,gif,jpeg",
                fileName: "image",
                multiple: true,
                onSuccess:function(files,data,xhr)
                {
                    data = $.parseJSON(data);
                    if(data.status) {
                        // append file to new
                        var html = '<div class="block_img" id="section_'+data.id+'"><div class="action-buttons"><div class="visible-md visible-lg hidden-sm hidden-xs"><a title="Delete Image" tooltip-placement="top" alt="'+data.id+'" class="delete_img btn btn-transparent btn-xs" href="javascript:void(0)"><i class="fa fa-trash"></i></a></div></div><div class="thumb-img-block"><img class="img-responsive" border="0" alt="" src="<?php echo PRODUCT_IMAGE_URL ?>'+data.image+'"></div></div>';
                        $("#old-images").prepend(html);
                    } else {
    
                    }
                    $("#status").html("<font color='green'>Upload is success</font>");
                },
                afterUploadAll:function()
                {
                    // alert("all images uploaded!!");
                },
                onError: function(files,status,errMsg)
                {
                    $("#status").html("<font color='red'>Sorry, upload is failed, please try after some time..</font>");
                }
            }
            $("#mulitplefileuploader").uploadFile(settings);
        });
    
        $(document).on("click",".delete_img", function() {
            var id = $(this).attr("alt");
            $.ajax({
                url: "<?php echo $this->Html->url("/"); ?>admin/products/deleteimage/"+id,
                method:"get",
                success:function() {
                    $("#section_"+id).remove();
                    $(".alert-success").show();
                    $(".alert-warning").hide();
                },
                beforeSend:function() {
                    $(".alert-success").hide();
                    $(".alert-warning").show();
                }
            })
        })

    </script>
<?php }

$option_status = array('A' => 'Active', 'I' => 'Inactive');
?>


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
                            <h1 class="mainTitle"><?php echo ('' == $id) ? 'Add' : 'Edit'; ?> Product</h1>
                        </div>
                    </div>
                </section>
                <!-- end: PAGE TITLE -->
                <!-- Global Messages -->
                <?php echo $this->Session->flash(); ?>
                <!-- Global Messages End -->
                <!-- start: FORM VALIDATION EXAMPLE 1 -->
                <div class="container-fluid container-fullw bg-white">
                    <div class="row">
                        <div class="col-md-12">
                            <?php echo $this->Form->create('Product', array('method' => 'post', 'class' => 'form', 'role' => 'form', 'autocomplete' => 'off', 'type' => 'file'));
                            echo $this->Form->input('id', array('type' => 'hidden', 'required' => false)); ?>
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group"> 
                                        <label class="control-label">Category<span class="symbol required"></span></label>
                                        <?php echo $this->Form->input('category_id', array('options' => $category, 'class' => 'form-control', 'empty' => '-- Select Category --', 'label' => false, 'div' => false, 'required' => true)); ?>
                                    </div>
                                </div>

                                <div class="clearfix"></div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Product Name<span class="symbol required"></span></label>
                                        <?php echo $this->Form->input('name', array('type' => 'text', 'maxlength' => '200', 'class' =>'form-control', 'div' => false, 'label' => false, 'required' => true)); ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Product Price<span class="symbol required"></span></label>
                                        <?php echo $this->Form->input('price', array('type' => 'text', 'maxlength' => '200', 'class' =>'form-control', 'div' => false, 'label' => false, 'required' => true)); ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Product Size<span class="symbol required"></span></label>
                                        <?php

                                        $options = array(
                                            'S'=>'Small',
                                            'M'=>'Medium',
                                            'L'=>'Large'
                                        );

                                         echo $this->Form->input('size', array('type' => 'select', 'options' => $options, 'class' =>'form-control', 'div' => false, 'label' => false, 'empty'=>'Select Size', 'required' => true)); ?>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Product Metal<span class="symbol required"></span></label>
                                        <?php echo $this->Form->input('metal', array('type' => 'text', 'maxlength' => '200', 'class' =>'form-control', 'div' => false, 'label' => false, 'required' => true)); ?>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Initials<span class="symbol required"></span></label>
                                        <?php

                                        $options = array(
                                            'Y'=>'Yes',
                                            'N'=>'No'
                                        );

                                        echo $this->Form->input('initials', array('type' => 'select', 'options' => $options, 'class' =>'form-control', 'div' => false, 'label' => false, 'empty'=>'Select Initial', 'required' => true)); ?>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Status</label>
                                        <?php echo $this->Form->input('status', array('options' => $option_status, 'class' => 'form-control', 'empty' => false, 'label' => false, 'div' => false)); ?>
                                    </div>
                                </div>

                                <div class="clearfix"></div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Description<span class="symbol required"></span></label>
                                        <?php echo $this->Form->textarea("description",array('class' => 'col-xs-12 col-sm-12 col-md-12 form-textarea', 'required' => true));
                                        echo $this->Form->error('description', array('wrap' => false));
                                        ?>
                                    </div>
                                </div>

                            </div>
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
                                    <?php echo $this->Form->button('Save <i class="fa fa-arrow-circle-right"></i>',array('class' => 'btn btn-primary btn-wide pull-left_form','type' => 'submit','id' => 'submit_button'));

                                    echo $this->Html->link('Cancel <i class="fa fa-times-circle"></i>',
                                        array('plugin' => false,'controller' => 'products','action' => 'index', 'admin' => true),
                                        array('class' => 'btn btn-primary btn-wide pull-right', 'escape' => false)
                                    );
                                    ?>
                                </div>
                            </div>
                            <?php echo $this->Form->end();

                            if('' != $id){ ?>
                            <div class="clearfix"></div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">Images</label>

                                        <div id="mulitplefileuploader">Upload</div>
                                        <div id="status"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">&nbsp;</label>
                                        <div class="description" id="old-images">
                                            <?php
                                            // old images goes here
                                            if(isset($this->request->data['ProductImage']) && !empty($this->request->data['ProductImage'])) {
                                                $images = $this->request->data['ProductImage'];
                                                foreach($images as $img) {
                                                    ?>
                                                    <div class="block_img" id="section_<?php echo $img['id']; ?>">

                                                        <div class="action-buttons">
                                                            <div class="visible-md visible-lg hidden-sm hidden-xs">
                                                                <a title="Delete Image" tooltip-placement="top" alt="<?php echo $img['id']; ?>" class="delete_img btn btn-transparent btn-xs" href="javascript:void(0)"><i class="fa fa-trash"></i></a>
                                                            </div>
                                                        </div>
                                                        <div class="thumb-img-block">
                                                            <?php echo $this->Html->image(PRODUCT_IMAGE_URL . $img['image'], array('border' => 0, 'class' => 'img-responsive')); ?>
                                                        </div>

                                                    </div>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </div>

                            <?php } ?>

                            
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