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

$option_categories = array();
foreach ($extrascategories as $category) {
	$option_categories[$category['Extrascategory']['id']] = $category['Extrascategory']['name'].'('.$category['Extrascategory']['name_zh'].')';
};

$option_order = array(
    'Extra.name ASC' => 'Name Ascending',
    'Extra.name DESC' => 'Name Descending',
    'Extra.status ASC, Extra.name ASC' => 'Status Ascending',
    'Extra.status DESC, Extra.name ASC' => 'Status Descending',
    'Extra.created ASC' => 'Created On Ascending',
    'Extra.created DESC' => 'Created On Descending',
);

$search_txt = $status = $category = '';
if($this->Session->check('Extras_search')){
    $search = $this->Session->read('Extras_search');
    $search_txt = $search['search'];
    $status = $search['status'];
	$category = $search['Categories'];
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
                	<!-- Modified by Yishou Liao @ Dec 04 2016 -->
                    <!-- start: SEARCH FORM START -->
                    <div class="border-around margin-bottom-15 padding-10">
                        <?php echo $this->Form->create('Extras', array(
                                'url' => array('controller' => 'extras', 'action' => 'index', 'admin' => true), 'class' => 'form', 'role' => 'search', 'autocomplete' => 'off')
                        ); ?>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Search</label>
                                <?php echo $this->Form->input('search', array('type' => 'text', 'value' => $search_txt, 'placeholder' => 'Search...', 'class' =>'form-control reset-field', 'div' => false, 'label' => false, 'required' => false)); ?>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Status</label>
                                <?php echo $this->Form->input('status', array('options' => $option_status, 'value' => $status, 'class' => 'form-control reset-field', 'empty' => 'All', 'label' => false, 'div' => false, 'required' => false)); ?>
                            </div>
                        </div>
                        <!-- Modified by Yishou Liao @ Dec 07 2016 -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Categories</label>
                                <?php echo $this->Form->input('Categories', array('options' => $option_categories, 'value' => $category, 'class' => 'form-control reset-field', 'empty' => 'All', 'label' => false, 'div' => false, 'required' => false)); ?>
                            </div>
                        </div>
                        <!-- End -->

                        <div class="col-md-12">
                            <?php
                            echo $this->Form->button('Reset <i class="fa fa-times-circle"></i>',array('class' => 'btn btn-primary btn-wide pull-right','type' => 'button','id' => 'reset_button'));
                            echo $this->Form->button('Search <i class="fa fa-arrow-circle-right"></i>',array('class' => 'btn btn-primary btn-wide pull-right margin-right-10','type' => 'submit','id' => 'submit_button')) ?>
                        </div>

                        <?php echo $this->Form->end(); ?>
                        <div class="clearfix"></div>
                    </div>
                    <?php echo $this->Form->create('PageSize', array(
                            'url' => array('controller' => 'extras', 'action' => 'index', 'admin' => true), 'class' => 'form', 'autocomplete' => 'off', 'id' => 'pageSizeForm')
                    ); ?>
                    <div class="form-group pull-left">
                        <label class="control-label">Records Per Page</label>
                        <?php echo $this->Form->input('records_per_page', array('options' => unserialize(PAGING_OPTIONS), 'value' => $limit, 'id' => 'records_per_page', 'class' => 'form-control', 'empty' => false, 'label' => false, 'div' => false)); ?>
                    </div>
                    <?php echo $this->Form->end(); ?>
                    <!-- End -->

                    
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-striped table-bordered table-hover table-full-width">
                                <thead>
                                <tr>
                                    <th><?php echo @$this->Paginator->sort('name','Extra Name(EN)'); ?></th>
                                    <th><?php echo @$this->Paginator->sort('name_zh','Extra Name(ZH)'); ?></th>
                                    <th><?php echo @$this->Paginator->sort('price','Price'); ?></th>
                                    <th><?php echo @$this->Paginator->sort('Extrascategory.name','Category'); ?></th>
                                    <th>Status</th>
                                    <th><?php echo @$this->Paginator->sort('created','Created On'); ?></th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if (!empty($extras)) { ?>
                                    <?php foreach ($extras as $cat) { ?>
                                        <tr>
                                            <td><b><?php echo ucfirst($cat['Extra']['name']); ?></b></td>
                                            <td><b><?php echo ucfirst($cat['Extra']['name_zh']); ?></b></td>
                                            <td><b>$<?php echo number_format($cat['Extra']['price'], 2); ?></b></td>
                                            <td><b><?php echo ucfirst($cat['Extrascategory']['name']).'('.ucfirst($cat['Extrascategory']['name_zh']).')'; ?></b></td>
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
															array('plugin' => false, 'controller' => 'extras', 'action' => 'add_edit', 'id' => base64_encode($cat['Extra']['id']), 'admin' => true),
															//End
                                                            array('class' => 'btn btn-transparent btn-xs', 'title' => 'Click here to edit extra', 'escape' => false)
                                                        );
                                                        echo $this->Html->link('<i class="fa fa-trash"></i>',
														//Modified by Yishou Liao @ Dec 18 2016
                                                            //array('plugin' => false, 'controller' => 'extras', 'action' => 'delete', base64_encode($cat['Extra']['id']),'?'=>array('id'=>$id), 'admin' => true),
															array('plugin' => false, 'controller' => 'extras', 'action' => 'delete', base64_encode($cat['Extra']['id']),'admin' => true),
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