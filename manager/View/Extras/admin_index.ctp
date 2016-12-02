<?php echo $this->Html->css(array('sweet-alert.css', 'ie9.css', 'toastr.min.css', 'select2.min.css', 'DT_bootstrap.css', 'bootstrap-datetimepicker'), null, array('inline' => false));
echo $this->Html->script(array('select2.min.js', 'jquery.dataTables.min.js', 'table-data.js', 'sweet-alert.min.js', 'ui-notifications.js', 'bootstrap-datepicker'), array('inline' => false)); ?>

<script type="text/javascript">
    jQuery(document).ready(function () {
        UINotifications.init();
        TableData.init();

        jQuery('#reset_button').click(function(){
            jQuery('.reset-field').val('');
            jQuery('#order_by').val('Extra.name ASC');
        });

        jQuery('#records_per_page').change(function(){
            jQuery('#pageSizeForm').submit();
        });

    });
</script>

<?php $option_status = array('A' => 'Active', 'I' => 'Inactive');
$option_order = array(
    'Extra.name ASC' => 'Name Ascending',
    'Extra.name DESC' => 'Name Descending',
    'Extra.status ASC, Extra.name ASC' => 'Status Ascending',
    'Extra.status DESC, Extra.name ASC' => 'Status Descending',
    'Extra.created ASC' => 'Created On Ascending',
    'Extra.created DESC' => 'Created On Descending',
);

$search_txt = $status = '';
if($this->Session->check('Color_search')){
    $search = $this->Session->read('Color_search');
    $search_txt = $search['search'];
    $status = $search['status'];
}
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
                <section id="page-title">
                    <div class="row">
                        <div class="col-sm-12 col-md-12">
                            <h1 class="mainTitle pull-left">Extras List</h1>
                            <div class="row pull-right">
                                <?php
                                if($this->Common->checkAccess($privilage_data, 'Extra', 'can_add')) {
                                    echo $this->Html->link('Add Extra <i class="fa fa-plus"></i>',
									//Modified by Yishou Liao @ Dec 01 2016
                                        //array('plugin' => false, '?'=>array('id' => $id), 'controller' => 'extras', 'action' => 'add_edit', 'admin' => true),
										array('plugin' => false, 'controller' => 'extras', 'action' => 'add_edit', 'admin' => true),
										//End
                                        array('class' => 'btn btn-green', 'escape' => false)
                                    );
                                } ?>
                            </div>
                        </div>
                    </div>
                </section>
                <?php echo $this->Session->flash(); ?>

                <div class="container-fluid container-fullw bg-white">

                    
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-striped table-bordered table-hover table-full-width">
                                <thead>
                                <tr>
                                    <th>Cuisines Name</th>
                                    <th>Extra Name(EN)</th>
                                    <th>Extra Name(ZH)</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                    <th>Created On</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if (!empty($extras)) { ?>
                                    <?php foreach ($extras as $cat) { ?>
                                        <tr>
                                            <td><b><?php echo ucfirst($CousineLocal_data['CousineLocal']['name']); ?></b></td>
                                            <td><b><?php echo ucfirst($cat['Extra']['name']); ?></b></td>
                                            <td><b><?php echo ucfirst($cat['Extra']['name_zh']); ?></b></td>
                                            <td><b>$<?php echo number_format($cat['Extra']['price'], 2); ?></b></td>
                                            <td> <?php
                                                if ($cat['Extra']['status'] == 'A') {
                                                    echo $this->Html->image('/img/test-pass-icon.png', array('border' => 0, 'alt' => 'Active', 'title' => 'Active'));
                                                } else {
                                                    echo $this->Html->image('/img/cross.png', array('border' => 0, 'alt' => 'Inactive', 'title' => 'Inactive'));
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php echo date(DATETIME_FORMAT, strtotime($cat['Extra']['created'])); ?>
                                            </td>
                                            <td>
                                                <div>
                                                    <?php
                                                    if($this->Common->checkAccess($privilage_data, 'Extra', 'can_edit')) {
                                                        if ($cat['Extra']['status'] == 'A') {
                                                            echo $this->Html->link('<i class="fa fa-check-circle"></i>', array('controller' => 'extras', 'action' => 'status', base64_encode($cat['Extra']['id']), 'I', 'admin' => true), array('title' => 'Click here to inactive', 'escape' => false, 'class' => 'btn btn-transparent btn-xs'));
                                                        } else {
                                                            echo $this->Html->link('<i class="fa fa-times-circle"></i>', array('controller' => 'extras', 'action' => 'status', base64_encode($cat['Extra']['id']), 'A', 'admin' => true), array('title' => 'Click here to active', 'escape' => false, 'class' => 'btn btn-transparent btn-xs'));
                                                        }

                                                        echo $this->Html->link('<i class="fa fa-pencil"></i>',
														//Modified by Yishou Liao @ Dec 01 2016
                                                            //array('plugin' => false, 'controller' => 'extras', 'action' => 'add_edit', base64_encode($cat['Extra']['id']),'?'=>array('id'=>$id), 'admin' => true),
															array('plugin' => false, 'controller' => 'extras', 'action' => 'add_edit', 'admin' => true),
															//End
                                                            array('class' => 'btn btn-transparent btn-xs', 'title' => 'Click here to edit extra', 'escape' => false)
                                                        );
                                                        echo $this->Html->link('<i class="fa fa-trash"></i>',
														//Modified by Yishou Liao @ Dec 01 2016
                                                            //array('plugin' => false, 'controller' => 'extras', 'action' => 'delete', base64_encode($cat['Extra']['id']),'?'=>array('id'=>$id), 'admin' => true),
															array('plugin' => false, 'controller' => 'extras', 'action' => 'delete', 'admin' => true),
															//End
                                                            array('class' => 'btn btn-transparent btn-xs', 'title' => 'Click here to edit extra', 'escape' => false, "onclick"=>"return confirm('Are you sure you want to delete?')")
                                                        );
                                                    } ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    if('all' != $limit){ ?>
                                        <tr>
                                            <td colspan="10">
                                                <?php echo $this->element('pagination'); ?>
                                            </td>
                                        </tr>
                                    <?php }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="10">No Extra here.</td>
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