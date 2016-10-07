<?php echo $this->Html->css(array('sweet-alert.css', 'ie9.css', 'toastr.min.css', 'select2.min.css', 'DT_bootstrap.css'), null, array('inline' => false));
echo $this->Html->script(array('select2.min.js', 'jquery.dataTables.min.js', 'table-data.js', 'sweet-alert.min.js', 'ui-notifications.js'), array('inline' => false)); ?>

<script type="text/javascript">
    jQuery(document).ready(function () {
        UINotifications.init();
        TableData.init();

        jQuery('#reset_button').click(function(){
            jQuery('.reset-field').val('');
            jQuery('#order_by').val('Product.name ASC');
        });

        jQuery('#records_per_page').change(function(){
            jQuery('#pageSizeForm').submit();
        });

    });
</script>

<?php $option_status = array('A' => 'Active', 'I' => 'Inactive');
$option_order = array(
    'Product.name ASC' => 'Name Ascending',
    'Product.name DESC' => 'Name Descending',
    'Category.name ASC, Product.name ASC' => 'Category Ascending',
    'Category.name DESC, Product.name ASC' => 'Category Descending',
    'Product.status ASC, Product.name ASC' => 'Status Ascending',
    'Product.status DESC, Product.name ASC' => 'Status Descending',
    'Product.created ASC' => 'Created On Ascending',
    'Product.created DESC' => 'Created On Descending',
);

$search_txt = $status = '';
if($this->Session->check('product_search')){
    $search = $this->Session->read('product_search');
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
                            <h1 class="mainTitle pull-left">Product List</h1>
                            <div class="row pull-right">
                                <?php
                                if($this->Common->checkAccess($privilage_data, 'Product', 'can_add')) {
                                    echo $this->Html->link('Add Product <i class="fa fa-plus"></i>',
                                        array('plugin' => false, 'controller' => 'products', 'action' => 'add_edit', 'admin' => true),
                                        array('class' => 'btn btn-green', 'escape' => false)
                                    );
                                }
                                /*if($this->Common->checkAccess($privilage_data, 'ProductCsv', 'can_view')) {
                                    echo $this->Html->link('Export CSV <i class="fa fa-cloud-download"></i>',
                                        array('plugin' => false, 'controller' => 'products', 'action' => 'export', 'admin' => true),
                                        array('class' => 'btn btn-green margin-left-20', 'escape' => false)
                                    );
                                }*/
                                ?>
                            </div>
                        </div>
                    </div>
                </section>
                <?php echo $this->Session->flash(); ?>

                <div class="container-fluid container-fullw bg-white">

                    <!-- start: SEARCH FORM START -->
                    <div class="border-around margin-bottom-15 padding-10">
                        <?php echo $this->Form->create('Product', array(
                                'url' => array('controller' => 'products', 'action' => 'index', 'admin' => true), 'class' => 'form', 'role' => 'search', 'autocomplete' => 'off')
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

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Order By</label>
                                <?php echo $this->Form->input('order_by', array('options' => $option_order, 'value' => $order, 'id' => 'order_by', 'class' => 'form-control', 'empty' => false, 'label' => false, 'div' => false, 'required' => false)); ?>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <?php echo $this->Form->button('Reset <i class="fa fa-times-circle"></i>',array('class' => 'btn btn-primary btn-wide pull-right','type' => 'button','id' => 'reset_button'));
                            echo $this->Form->button('Search <i class="fa fa-arrow-circle-right"></i>',array('class' => 'btn btn-primary btn-wide pull-right margin-right-10','type' => 'submit','id' => 'submit_button')) ?>
                        </div>

                        <?php echo $this->Form->end(); ?>
                        <div class="clearfix"></div>
                    </div>

                    <?php echo $this->Form->create('PageSize', array(
                            'url' => array('controller' => 'products', 'action' => 'index', 'admin' => true), 'class' => 'form', 'autocomplete' => 'off', 'id' => 'pageSizeForm')
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
                                    <th>Product Name</th>
                                    <th>Category</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Created On</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if (!empty($products)) { ?>
                                    <?php foreach ($products as $product_data) { ?>
                                        <tr>
                                            <td><?php echo ucfirst($product_data['Product']['name']); ?></td>
                                            <td><?php echo ucfirst($product_data['Category']['name']); ?></td>
                                            <td><?php echo nl2br($this->Common->textLimit(ucfirst($product_data['Product']['description']), 50)); ?></td>
                                            <td>
                                                <?php
                                                if ($product_data['Product']['status'] == 'A') {
                                                    echo $this->Html->image('/img/test-pass-icon.png', array('border' => 0, 'alt' => 'Active', 'title' => 'Active'));
                                                } else {
                                                    echo $this->Html->image('/img/cross.png', array('border' => 0, 'alt' => 'Inactive', 'title' => 'Inactive'));
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php echo date(DATETIME_FORMAT, strtotime($product_data['Product']['created'])); ?>
                                            </td>
                                            <td>
                                                <div>
                                                    <?php
                                                    if($this->Common->checkAccess($privilage_data, 'Product', 'can_edit')) {
                                                        if ($product_data['Product']['status'] == 'A') {
                                                            echo $this->Html->link('<i class="fa fa-check-circle"></i>', array('controller' => 'products', 'action' => 'status', base64_encode($product_data['Product']['id']), 'I', 'admin' => true), array('title' => 'Click here to inactive', 'escape' => false, 'class' => 'btn btn-transparent btn-xs'));
                                                        } else {
                                                            echo $this->Html->link('<i class="fa fa-times-circle"></i>', array('controller' => 'products', 'action' => 'status', base64_encode($product_data['Product']['id']), 'A', 'admin' => true), array('title' => 'Click here to active', 'escape' => false, 'class' => 'btn btn-transparent btn-xs'));
                                                        }

                                                        echo $this->Html->link('<i class="fa fa-pencil"></i>',
                                                            array('plugin' => false, 'controller' => 'products', 'action' => 'add_edit', base64_encode($product_data['Product']['id']), 'admin' => true),
                                                            array('class' => 'btn btn-transparent btn-xs', 'title' => 'Click here to edit product', 'escape' => false)
                                                        );
                                                    }
                                                    ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    if('all' != $limit){ ?>
                                        <tr>
                                            <td colspan="7">
                                                <?php echo $this->element('pagination'); ?>
                                            </td>
                                        </tr>
                                    <?php }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="7">No Product here.</td>
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