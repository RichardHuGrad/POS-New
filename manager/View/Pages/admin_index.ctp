<?php echo $this->Html->css(array('sweet-alert.css', 'ie9.css', 'toastr.min.css', 'select2.min.css', 'DT_bootstrap.css'), null, array('inline' => false));
echo $this->Html->script(array('select2.min.js', 'jquery.dataTables.min.js', 'table-data.js', 'sweet-alert.min.js', 'ui-notifications.js'), array('inline' => false)); ?>

<script type="text/javascript">
    jQuery(document).ready(function () {
        UINotifications.init();
        TableData.init();

        jQuery('#records_per_page').change(function(){
            jQuery('#pageSizeForm').submit();
        });

        jQuery('.delete-btn').click(function () {
            var this_href = $(this).attr('data-href');
            swal({
                    title: "Are you sure?",
                    text: "Page will be deleted permanently",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: '#DD6B55',
                    confirmButtonText: 'Yes, delete it!',
                    closeOnConfirm: false,
                },
                function () {
                    window.location.href = this_href;
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
                        <div class="col-sm-12 col-md-12">
                            <h1 class="mainTitle pull-left">Static Pages</h1>
                            <div class="row pull-right">
                                <?php
                                if($this->Common->checkAccess($privilage_data, 'Page', 'can_add')){
                                    echo $this->Html->link('Add Page <i class="fa fa-plus"></i>',
                                        array('plugin' => false, 'controller' => 'pages', 'action' => 'add_edit', 'admin' => true),
                                        array('class' => 'btn btn-green', 'escape' => false)
                                    );
                                } ?>
                            </div>
                        </div>
                    </div>
                </section>
                <?php echo $this->Session->flash(); ?>

                <div class="container-fluid container-fullw bg-white">

                    <?php echo $this->Form->create('PageSize', array(
                            'url' => array('controller' => 'pages', 'action' => 'index', 'admin' => true), 'class' => 'form', 'autocomplete' => 'off', 'id' => 'pageSizeForm')
                    ); ?>
                    <div class="form-group pull-left">
                        <label class="control-label">Records Per Page</label>
                        <?php echo $this->Form->input('records_per_page', array('options' => unserialize(PAGING_OPTIONS), 'value' => $limit, 'id' => 'records_per_page', 'class' => 'form-control', 'empty' => false, 'label' => false, 'div' => false)); ?>
                    </div>
                    <?php echo $this->Form->end(); ?>

                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-striped table-bordered table-hover table-full-width">
                                <thead>
                                <tr>
                                    <th>Page Name</th>
                                    <th>Slug</th>
                                    <th>Status</th>
                                    <th>Created On</th>
                                    <th class="hidden-xs">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if (!empty($page_list)) { ?>
                                    <?php foreach ($page_list as $k => $pData) { ?>
                                        <tr>
                                            <td><?php echo ucfirst($pData['Page']['name']); ?></td>
                                            <td><?php echo $pData['Page']['slug']; ?></td>
                                            <td> <?php
                                                if ($pData['Page']['status'] == 'A') {
                                                    echo $this->Html->image('/img/test-pass-icon.png', array('border' => 0, 'alt' => 'Active', 'title' => 'Active'));
                                                } else {
                                                    echo $this->Html->image('/img/cross.png', array('border' => 0, 'alt' => 'Inactive', 'title' => 'Inactive'));
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php echo date(DATETIME_FORMAT, strtotime($pData['Page']['created'])); ?>
                                            </td>
                                            <td><div class="visible-md visible-lg hidden-sm hidden-xs">
                                                    <?php
                                                    if($this->Common->checkAccess($privilage_data, 'Page', 'can_edit')) {
                                                        if ($pData['Page']['status'] == 'A') {
                                                            echo $this->Html->link('<i class="fa fa-check-circle"></i>', array('controller' => 'pages', 'action' => 'status', base64_encode($pData['Page']['id']), 'I', 'admin' => true), array('title' => 'Click here to inactive', 'escape' => false, 'class' => 'btn btn-transparent btn-xs'));
                                                        } else {
                                                            echo $this->Html->link('<i class="fa fa-times-circle"></i>', array('controller' => 'pages', 'action' => 'status', base64_encode($pData['Page']['id']), 'A', 'admin' => true), array('title' => 'Click here to active', 'escape' => false, 'class' => 'btn btn-transparent btn-xs'));
                                                        }

                                                        echo $this->Html->link('<i class="fa fa-pencil"></i>',
                                                            array('plugin' => false, 'controller' => 'pages', 'action' => 'add_edit', base64_encode($pData['Page']['id']), 'admin' => true),
                                                            array('class' => 'btn btn-transparent btn-xs', 'title' => 'Click here to edit page details', 'escape' => false)
                                                        );
                                                    }
                                                    if($this->Common->checkAccess($privilage_data, 'Page', 'can_view')) {
                                                        echo $this->Html->link('<i class="fa fa-eye" data-toggle = "modal" data-target = "#myModal"></i>',
                                                            '#myGallery',
                                                            array('class' => 'btn btn-transparent btn-xs', 'title' => 'Click here to view', 'escape' => false, 'data-slide-to' => $k)
                                                        );
                                                    }
                                                    if($this->Common->checkAccess($privilage_data, 'Page', 'can_delete')) {
                                                        $href_lnk = $this->Html->url(array('plugin' => false, 'controller' => 'pages', 'action' => 'delete', base64_encode($pData['Page']['id']), 'admin' => true));
                                                        echo $this->Html->link('<i class="fa fa-times"></i>',
                                                            'javascript:void(0);',
                                                            array('data-href' => $href_lnk, 'class' => 'delete-btn', 'title' => 'Click here to delete', 'escape' => false));
                                                    } ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    if('all' != $limit){ ?>
                                        <tr>
                                            <td colspan="5">
                                                <?php echo $this->element('pagination'); ?>
                                            </td>
                                        </tr>
                                    <?php }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="5">No Page here.</td>
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


<?php if(!empty($page_list)){ ?>
    <div aria-hidden="true" aria-labelledby="myModalLabel" class="modal fade" id="myModal" role="dialog" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Static Page</h4>
                </div>
                <div class="modal-body">
                    <div class="carousel slide" data-interval="false" id="myGallery">
                        <div class="carousel-inner">
                            <?php
                            foreach ($page_list as $k => $pData) {

                                $active = (0 == $k) ? 'active' : '';
                                ?>

                                <div class="item <?php echo $active; ?>">
                                    <?php echo $pData['Page']['body']; ?>
                                </div>
                            <?php }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-green add-row" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
<?php } ?>