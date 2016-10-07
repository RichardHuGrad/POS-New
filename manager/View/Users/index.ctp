<?php echo $this->Html->css(array('sweet-alert.css', 'ie9.css', 'toastr.min.css', 'select2.min.css', 'DT_bootstrap.css'), null, array('inline' => false)); ?>
<?php echo $this->Html->script(array('select2.min.js', 'jquery.dataTables.min.js', 'table-data.js', 'select2.min.css', 'sweet-alert.min.js', 'ui-notifications.js'), array('inline' => false)); ?>
<script type="text/javascript">
    jQuery(document).ready(function () {
        UINotifications.init();
        TableData.init();
        jQuery("#add_new_beacon").click(function () {
            window.location.href = '<?php echo $this->Html->url(array('plugin' => false, 'controller' => 'users', 'action' => 'add')); ?>';
        });
        jQuery('a[id ^= delete_customer_]').click(function () {
            var thisID = $(this).attr('id');
            var breakID = thisID.split('_');
            var record_id = breakID[2];
            swal({
                title: "Are you sure?",
                text: "User will be deleted permanently",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes, delete it!',
                closeOnConfirm: false,
            },
                    function () {
                        $.ajax({
                            type: 'get',
                            url: '<?php echo $this->Html->url(array('plugin' => false, 'controller' => 'users', 'action' => 'delete')) ?>',
                            data: 'id=' + record_id,
                            dataType: 'json',
                            success: function (data) {
                                if (data.succ == '1') {
                                    swal({
                                        title: "Deleted!",
                                        text: data.msg,
                                        type: "success",
                                        showCancelButton: false,
                                        confirmButtonColor: '#d6e9c6',
                                        confirmButtonText: 'OK',
                                        closeOnConfirm: false,
                                    }, function () {
                                        window.location.reload();
                                    });
                                } else {
                                    swal({
                                        title: "Error!",
                                        text: data.msg,
                                        type: "error",
                                        showCancelButton: false,
                                        confirmButtonColor: '#d6e9c6',
                                        confirmButtonText: 'OK',
                                        closeOnConfirm: false,
                                    }, function () {
                                        window.location.reload();
                                    });
                                }
                            }
                        });
                    });
        });
    });
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
                <section id="page-title">
                    <div class="row">
                        <div class="col-sm-8">
                            <h1 class="mainTitle">Users List</h1>                            
                        </div>                        
                    </div>
                </section>
                <?php echo $this->Session->flash(); ?>
                <div class="container-fluid container-fullw bg-white">
                    <div class="row">
                        <div class="col-md-12 space20">
                            <button class="btn btn-green add-row" id='add_new_beacon'>
                                Add User <i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">                           
                            <table class="table table-striped table-bordered table-hover table-full-width" <?php echo (!empty($restaurants_list)) ? 'id="sample_1"' : '' ?>>
                                <thead>
                                    <tr>
                                        <th class="hidden-xs">Image</th>
                                        <th>Name </th>
                                        <th>Email</th>
                                        <th class="hidden-xs">Status</th>
                                        <th class="hidden-xs">Created On</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($restaurants_list)) { ?>
                                        <?php foreach ($restaurants_list as $restaurants) { ?>  
                                            <tr>
                                                <td width="10%">
												
                                                    <?php 
													
													if ($restaurants['User']['image'] != "" && $restaurants['User']['image'] != NULL) { ?>
                                                        <?php echo $this->Html->image('/uploads/profile_pic/' . $restaurants['User']['image'], array('border' => 0, 'width' => 50)); ?>
                                                    <?php } else { ?>
                                                        <?php echo $this->Html->image('/img/no_image.jpg', array('border' => 0, 'width' => 50)); ?>
                                                    <?php } ?>
                                                </td>
                                                <td><?php echo $restaurants['User']['name']; ?></td>
                                                <td><?php echo $restaurants['User']['email']; ?></td>
                                                <td> <?php
                                                    if ($restaurants['User']['status'] == 'A') {
                                                        echo $this->Html->image('/img/test-pass-icon.png', array('border' => 0, 'alt' => 'activated', 'title' => 'activated'));
                                                    } else {
                                                        echo $this->Html->image('/img/cross.png', array('border' => 0, 'alt' => 'activated', 'title' => 'activated'));
                                                    }
                                                    ?>
                                                </td>
                                                <td><?php echo $this->Time->format("M d, Y H:i:s A", $restaurants['User']['created']); ?></td>
                                                <td><div class="visible-md visible-lg hidden-sm hidden-xs">
                                                        <?php
                                                        if ($restaurants['User']['status'] == 'A') {
                                                            echo $this->Html->link('<i class="fa fa-check-circle"></i>', array('controller' => 'users', 'action' => 'status', 'id' => $restaurants['User']['id'], 'status' => 'D'), array('title' => 'Click here to inactive.', 'escape' => false, 'class' => 'btn btn-transparent btn-xs', 'tooltip-placement' => 'top', 'tooltip' => 'Click here to inactive.'));
														
                                                        } else {
                                                            echo $this->Html->link('<i class="fa fa-times-circle"></i>', array('controller' => 'users', 'action' => 'status', 'id' => $restaurants['User']['id'], 'status' => 'A'), array('title' => 'Click here to active.', 'escape' => false, 'class' => 'btn btn-transparent btn-xs', 'tooltip-placement' => 'top', 'tooltip' => 'Click here to active.'));
                                                        }
															echo $this->Html->link('<i class="fa fa-pencil"></i>', array('plugin' => false, 'controller' => 'users', 'action' => 'edit', '?' => array('id' => $restaurants['User']['id'])), array('class' => 'btn btn-transparent btn-xs', 'tooltip-placement' => 'top', 'tooltip' => 'Edit', 'escape' => false));
														
														
															echo $this->Html->link('<i class="fa fa-key"></i>', array('plugin' => false, 'controller' => 'users', 'action' => 'changepassword', '?' => array('id' => $restaurants['User']['id'])), array('class' => 'btn btn-transparent btn-xs', 'tooltip-placement' => 'top', 'tooltip' => 'Change Password', 'escape' => false));
														
														
														
															echo $this->Html->link('<i class="fa fa-trash-o"></i>
															', 'javascript:void(0)', array('class' => 'btn btn-transparent btn-xs tooltips', 'tooltip-placement' => 'top', 'tooltip' => 'Remove', 'id' => 'delete_customer_' . $restaurants['User']['id'], 'escape' => false));
                                                        ?>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <tr>
                                            <td colspan="6">No User here.</td>
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
