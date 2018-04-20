<?php echo $this->Html->css(array('sweet-alert.css', 'ie9.css', 'toastr.min.css', 'select2.min.css', 'DT_bootstrap.css', 'bootstrap-datetimepicker'), null, array('inline' => false));
echo $this->Html->script(array('select2.min.js', 'jquery.dataTables.min.js', 'table-data.js', 'sweet-alert.min.js', 'ui-notifications.js', 'bootstrap-datepicker'), array('inline' => false)); ?>

<script type="text/javascript">
    jQuery(document).ready(function () {
        UINotifications.init();
        TableData.init();

        jQuery('#reset_button').click(function(){
            jQuery('.reset-field').val('');
            jQuery('#order_by').val('CategoryLocale.name ASC');
        });

        jQuery('#records_per_page').change(function(){
            jQuery('#pageSizeForm').submit();
        });

    });
</script>

<?php
$option_status = array('A' => 'Active', 'I' => 'Inactive');
$search_txt = $status =  $search_lang = '';
/*$option_order = array(
    'CategoryLocale.name ASC' => 'Name Ascending',
    'CategoryLocale.name DESC' => 'Name Descending',
    'Category.status ASC, CategoryLocale.name ASC' => 'Status Ascending',
    'Category.status DESC, CategoryLocale.name ASC' => 'Status Descending',
    //'Category.created ASC' => 'Created On Ascending',
    //'Category.created DESC' => 'Created On Descending',
);
*/

if($this->Session->check('Extracategory_search')){
    $search = $this->Session->read('Extracategory_search');
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
                            <h1 class="mainTitle pull-left">Extra Category List</h1>
                            <div class="row pull-right">
                                <?php
                                echo $this->Html->link('Add Extracategory <i class="fa fa-plus"></i>',
                                    array('plugin' => false, 'controller' => 'extracate', 'action' => 'add_edit', 'admin' => true),
                                    array('class' => 'btn btn-green', 'escape' => false)
                                );
                                ?>
                            </div>
                        </div>
                    </div>
                </section>
                <?php echo $this->Session->flash(); ?>

                <div class="container-fluid container-fullw bg-white">

                    <!-- start: SEARCH FORM START -->
                    <div class="border-around margin-bottom-15 padding-10">
                        <?php echo $this->Form->create('Extracategory', array(
                                'url' => array('controller' => 'extracate', 'action' => 'index', 'admin' => true), 'class' => 'form', 'role' => 'search', 'autocomplete' => 'off')
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

                        <div class="col-md-12">
                            <?php
                            if ($has_web) echo $this->Form->button('Sync From Web <i class="fa fa-download-circle-right"></i>',array('class' => 'btn btn-primary btn-wide margin-left-10','type' => 'submit', 'name'=>'get_web', 'vaule'=>'get_web','id' => 'get_web'));
                            echo $this->Form->button('Reset <i class="fa fa-times-circle"></i>',array('class' => 'btn btn-primary btn-wide pull-right','type' => 'button','id' => 'reset_button'));
                            echo $this->Form->button('Search <i class="fa fa-arrow-circle-right"></i>',array('class' => 'btn btn-primary btn-wide pull-right margin-right-10','type' => 'submit','id' => 'submit_button')) ?>
                        </div>

                        <?php echo $this->Form->end(); ?>
                        <div class="clearfix"></div>
                    </div>

                    <?php echo $this->Form->create('PageSize', array(
                            'url' => array('controller' => 'extracate', 'action' => 'index', 'admin' => true), 'class' => 'form', 'autocomplete' => 'off', 'id' => 'pageSizeForm')
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
                                    <th><?php echo @$this->Paginator->sort('name', 'Extra Category Name(EN)') ?></th>
                                    <th><?php echo @$this->Paginator->sort('name_zh', 'Extra Category Name(ZH)') ?></th>
                                    <th><?php echo @$this->Paginator->sort('status', 'Status') ?></th>
                                    <!-- <th><?php echo @$this->Paginator->sort('modified', 'Updated On') ?></th> -->
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if (!empty($extrascategory)) { ?>
                                    <?php foreach ($extrascategory as $cat) { ?>
                                        <tr>
                                            <td><?php echo ucfirst($cat['Extrascategory']['name']); ?></td>
                                            <td><?php echo ucfirst($cat['Extrascategory']['name_zh']); ?></td>
                                            <td> <?php
                                                if ($cat['Extrascategory']['status'] == 'A') {
                                                    echo $this->Html->image('/img/test-pass-icon.png', array('border' => 0, 'alt' => 'Active', 'title' => 'Active'));
                                                } else {
                                                    echo $this->Html->image('/img/cross.png', array('border' => 0, 'alt' => 'Inactive', 'title' => 'Inactive'));
                                                }
                                                ?>
                                            </td>
                                            <!--
                                            <td>
                                                <?php echo date(DATETIME_FORMAT, $cat['Extrascategory']['modified']); ?>
                                            </td>
                                            -->
                                            <td>
                                                <div>
                                                    <?php
                                                    if ($cat['Extrascategory']['status'] == 'A') {
                                                        echo $this->Html->link('<i class="fa fa-check-circle"></i>', array('controller' => 'extracate', 'action' => 'status', base64_encode($cat['Extrascategory']['id']), 'I', 'admin' => true), array('title' => 'Click here to inactive', 'escape' => false, 'class' => 'btn btn-transparent btn-xs'));
                                                    } else {
                                                        echo $this->Html->link('<i class="fa fa-times-circle"></i>', array('controller' => 'extracate', 'action' => 'status', base64_encode($cat['Extrascategory']['id']), 'A', 'admin' => true), array('title' => 'Click here to active', 'escape' => false, 'class' => 'btn btn-transparent btn-xs'));
                                                    }

                                                    echo $this->Html->link('<i class="fa fa-pencil"></i>',
                                                        array('plugin' => false, 'controller' => 'extracate', 'action' => 'add_edit', 'id' => base64_encode($cat['Extrascategory']['id']), 'admin' => true),
                                                        array('class' => 'btn btn-transparent btn-xs', 'title' => 'Click here to edit category', 'escape' => false)
                                                    );

                                                    echo $this->Html->link('<i class="fa fa-trash"></i>',
                                                            array('plugin' => false, 'controller' => 'extracate', 'action' => 'delete',  base64_encode($cat['Extrascategory']['id']), 'admin' => true),
                                                            array('class' => 'btn btn-transparent btn-xs', 'title' => 'Click here to delete category', "onclick"=>"return confirm('Are you sure you want to delete this category?')", 'escape' => false)
                                                        );
                                                     ?>
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
                                        <td colspan="5" class="text-center">Category Not Available !</td>
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