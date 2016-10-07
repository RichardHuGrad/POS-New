<?php echo $this->Html->css(array('sweet-alert.css', 'ie9.css', 'toastr.min.css', 'select2.min.css', 'DT_bootstrap.css'), null, array('inline' => false)); ?>
<?php echo $this->Html->script(array('select2.min.js', 'jquery.dataTables.min.js', 'table-data.js', 'select2.min.css', 'sweet-alert.min.js', 'ui-notifications.js'), array('inline' => false)); ?>
<script type="text/javascript">
    jQuery(document).ready(function () {
        UINotifications.init();
        TableData.init();
        jQuery("#add_new_beacon").click(function () {
            window.location.href = '<?php echo $this->Html->url(array('plugin' => false, 'controller' => 'pages', 'action' => 'add')); ?>';
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
                            <h1 class="mainTitle">Page List</h1>                            
                        </div>                        
                    </div>
                </section>
                <?php echo $this->Session->flash(); ?>
                <div class="container-fluid container-fullw bg-white">
                    <div class="row">
                        <div class="col-md-12 space20">
                            <button class="btn btn-green add-row" id='add_new_beacon'>
                                Add Page <i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">                           
                            <table class="table table-striped table-bordered table-hover table-full-width" <?php echo (!empty($pages_list)) ? 'id="sample_1"' : '' ?>>
                                <thead>
                                    <tr>
                                       
                                        <th>Name </th>
                                        <th>Discription</th>
                                        <th class="hidden-xs">Status</th>
                                        <th class="hidden-xs">Created On</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($pages_list)) { ?>
                                        <?php foreach ($pages_list as $pages) { ?>  
                                            <tr>
                                                
                                                <td><?php echo $pages['Page']['name']; ?></td>
                                                <td><?php echo substr($pages['Page']['discription'],0 ,50); ?></td>
                                                <td> <?php
                                                    if ($pages['Page']['status'] == 'A') {
                                                        echo $this->Html->image('/img/test-pass-icon.png', array('border' => 0, 'alt' => 'activated', 'title' => 'activated'));
                                                    } else {
                                                        echo $this->Html->image('/img/cross.png', array('border' => 0, 'alt' => 'activated', 'title' => 'activated'));
                                                    }
                                                    ?>
                                                </td>
                                                <td><?php echo $this->Time->format("M d, Y H:i:s A", $pages['Page']['created']); ?></td>
                                                <td><div class="visible-md visible-lg hidden-sm hidden-xs">
                                                        <?php
                                                        if ($pages['Page']['status'] == 'A') {
                                                            echo $this->Html->link('<i class="fa fa-check-circle"></i>', array('controller' => 'pages', 'action' => 'status', 'id' => $pages['Page']['id'], 'status' => 'D'), array('title' => 'Click here to inactive.', 'escape' => false, 'class' => 'btn btn-transparent btn-xs', 'tooltip-placement' => 'top', 'tooltip' => 'Click here to inactive.'));
														
                                                        } else {
                                                            echo $this->Html->link('<i class="fa fa-times-circle"></i>', array('controller' => 'pages', 'action' => 'status', 'id' => $pages['Page']['id'], 'status' => 'A'), array('title' => 'Click here to active.', 'escape' => false, 'class' => 'btn btn-transparent btn-xs', 'tooltip-placement' => 'top', 'tooltip' => 'Click here to active.'));
                                                        }
															echo $this->Html->link('<i class="fa fa-pencil"></i>', array('plugin' => false, 'controller' => 'pages', 'action' => 'edit', '?' => array('id' => $pages['Page']['id'])), array('class' => 'btn btn-transparent btn-xs', 'tooltip-placement' => 'top', 'tooltip' => 'Edit', 'escape' => false));
														
														
															/*echo $this->Html->link('<i class="fa fa-trash-o"></i>
															', 'javascript:void(0)', array('class' => 'btn btn-transparent btn-xs tooltips', 'tooltip-placement' => 'top', 'tooltip' => 'Remove', 'id' => 'delete_customer_' . $pages['Page']['id'], 'escape' => false));*/
                                                        ?>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <tr>
                                            <td colspan="6">No Page here.</td>
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
