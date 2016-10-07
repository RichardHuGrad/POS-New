<script type="text/javascript">
    jQuery(document).ready(function() {

        jQuery('.pcheckbox').on('click', function() {
            var getfield = jQuery(this).attr('data-field');
            if (jQuery(this).is(':checked')) {
                jQuery('.' + getfield).prop('checked', true);
            } else {
                jQuery('.' + getfield).prop('checked', false);

            }
        });

        jQuery('.fcheckbox').on('click', function() {
            var getfield = jQuery(this).attr('data-ref');
            if (jQuery(this).is(':checked')) {

                var ref_field = jQuery('.' + getfield).attr('data-field');

                var total_count  = 0;
                var checked_count = 0;
                jQuery('.' + ref_field).each(function() {
                    total_count++;
                    if (jQuery(this).is(':checked')) {
                        checked_count++;
                    }

                });

                if(total_count == checked_count){
                    jQuery('.' + getfield).prop('checked', true);
                }

            } else {
                jQuery('.' + getfield).prop('checked', false);

            }
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
                <!-- start: PAGE TITLE -->
                <section id="page-title">
                    <div class="row">
                        <div class="col-sm-8">
                            <h1 class="mainTitle">
                                Assign Permissions : <?php echo $name; ?>
                        </div>
                    </div>
                </section>
                <!-- end: PAGE TITLE -->
                <!-- Global Messages -->
                <?php echo $this->Session->flash(); ?>
                <!-- Global Messages End -->
                <div class="container-fluid container-fullw bg-white">
                    <div class="row">
                        <div class="col-md-12">
                            <?php echo $this->Form->create('AdminPrivilage', array('class' => 'form', 'role' => 'form', 'autocomplete' => 'off')); ?>
                            <table class="table text-center">
                                <tr>
                                    <td></td>
                                    <td>All</td>
                                    <td>View</td>
                                    <td>Add</td>
                                    <td>Edit</td>
                                    <td>Delete</td>
                                </tr>

                                <tr>
                                    <?php $field = 'Admin'; ?>
                                    <td class="text-left"><a href="javascript:void(0);" class='clicklink' title='Admin'>Admin</a></td>
                                    <td>
                                        <?php echo $this->Form->checkbox($field . '.all',
                                            array('class' => $field . '_all pcheckbox', 'title' => 'All', 'data-field' => $field . '_field', 'legend' => false, 'label' => false, 'hiddenField' => false)); ?>
                                    </td>
                                    <td>
                                        <?php echo $this->Form->checkbox($field . '.view',
                                            array('class' => $field . '_field fcheckbox', 'data-ref' => $field . '_all', 'title' => 'Can View', 'legend' => false, 'label' => false, 'hiddenField' => false)); ?>
                                    </td>
                                    <td>
                                        <?php echo $this->Form->checkbox($field . '.add',
                                            array('class' => $field . '_field fcheckbox', 'data-ref' => $field . '_all', 'title' => 'Can Add', 'legend' => false, 'label' => false, 'hiddenField' => false)); ?>
                                    </td>
                                    <td>
                                        <?php echo $this->Form->checkbox($field . '.edit',
                                            array('class' => $field . '_field fcheckbox', 'data-ref' => $field . '_all', 'title' => 'Can Edit', 'legend' => false, 'label' => false, 'hiddenField' => false)); ?>
                                    </td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <?php $field = 'Customer'; ?>
                                    <td class="text-left"><a href="javascript:void(0);" class='clicklink' title='Customer'>Customer</a></td>
                                    <td>
                                        <?php echo $this->Form->checkbox($field . '.all',
                                            array('class' => $field . '_all pcheckbox', 'title' => 'All', 'data-field' => $field . '_field', 'legend' => false, 'label' => false, 'hiddenField' => false)); ?>
                                    </td>
                                    <td>
                                        <?php echo $this->Form->checkbox($field . '.view',
                                            array('class' => $field . '_field fcheckbox', 'data-ref' => $field . '_all', 'title' => 'Can View', 'legend' => false, 'label' => false, 'hiddenField' => false)); ?>
                                    </td>
                                    <td>
                                        <?php echo $this->Form->checkbox($field . '.add',
                                            array('class' => $field . '_field fcheckbox', 'data-ref' => $field . '_all', 'title' => 'Can Add', 'legend' => false, 'label' => false, 'hiddenField' => false)); ?>
                                    </td>
                                    <td>
                                        <?php echo $this->Form->checkbox($field . '.edit',
                                            array('class' => $field . '_field fcheckbox', 'data-ref' => $field . '_all', 'title' => 'Can Edit', 'legend' => false, 'label' => false, 'hiddenField' => false)); ?>
                                    </td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <?php $field = 'Vendor'; ?>
                                    <td class="text-left"><a href="javascript:void(0);" class='clicklink' title='Vendor'>Vendor</a></td>
                                    <td>
                                        <?php echo $this->Form->checkbox($field . '.all',
                                            array('class' => $field . '_all pcheckbox', 'title' => 'All', 'data-field' => $field . '_field', 'legend' => false, 'label' => false, 'hiddenField' => false)); ?>
                                    </td>
                                    <td>
                                        <?php echo $this->Form->checkbox($field . '.view',
                                            array('class' => $field . '_field fcheckbox', 'data-ref' => $field . '_all', 'title' => 'Can View', 'legend' => false, 'label' => false, 'hiddenField' => false)); ?>
                                    </td>
                                    <td>
                                        <?php echo $this->Form->checkbox($field . '.add',
                                            array('class' => $field . '_field fcheckbox', 'data-ref' => $field . '_all', 'title' => 'Can Add', 'legend' => false, 'label' => false, 'hiddenField' => false)); ?>
                                    </td>
                                    <td>
                                        <?php echo $this->Form->checkbox($field . '.edit',
                                            array('class' => $field . '_field fcheckbox', 'data-ref' => $field . '_all', 'title' => 'Can Edit', 'legend' => false, 'label' => false, 'hiddenField' => false)); ?>
                                    </td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <?php $field = 'Category'; ?>
                                    <td class="text-left"><a href="javascript:void(0);" class='clicklink' title='Category'>Category</a></td>
                                    <td>
                                        <?php echo $this->Form->checkbox($field . '.all',
                                            array('class' => $field . '_all pcheckbox', 'title' => 'All', 'data-field' => $field . '_field', 'legend' => false, 'label' => false, 'hiddenField' => false)); ?>
                                    </td>
                                    <td>
                                        <?php echo $this->Form->checkbox($field . '.view',
                                            array('class' => $field . '_field fcheckbox', 'data-ref' => $field . '_all', 'title' => 'Can View', 'legend' => false, 'label' => false, 'hiddenField' => false)); ?>
                                    </td>
                                    <td>
                                        <?php echo $this->Form->checkbox($field . '.add',
                                            array('class' => $field . '_field fcheckbox', 'data-ref' => $field . '_all', 'title' => 'Can Add', 'legend' => false, 'label' => false, 'hiddenField' => false)); ?>
                                    </td>
                                    <td>
                                        <?php echo $this->Form->checkbox($field . '.edit',
                                            array('class' => $field . '_field fcheckbox', 'data-ref' => $field . '_all', 'title' => 'Can Edit', 'legend' => false, 'label' => false, 'hiddenField' => false)); ?>
                                    </td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <?php $field = 'Brand'; ?>
                                    <td class="text-left"><a href="javascript:void(0);" class='clicklink' title='Brand'>Brand</a></td>
                                    <td>
                                        <?php echo $this->Form->checkbox($field . '.all',
                                            array('class' => $field . '_all pcheckbox', 'title' => 'All', 'data-field' => $field . '_field', 'legend' => false, 'label' => false, 'hiddenField' => false)); ?>
                                    </td>
                                    <td>
                                        <?php echo $this->Form->checkbox($field . '.view',
                                            array('class' => $field . '_field fcheckbox', 'data-ref' => $field . '_all', 'title' => 'Can View', 'legend' => false, 'label' => false, 'hiddenField' => false)); ?>
                                    </td>
                                    <td>
                                        <?php echo $this->Form->checkbox($field . '.add',
                                            array('class' => $field . '_field fcheckbox', 'data-ref' => $field . '_all', 'title' => 'Can Add', 'legend' => false, 'label' => false, 'hiddenField' => false)); ?>
                                    </td>
                                    <td>
                                        <?php echo $this->Form->checkbox($field . '.edit',
                                            array('class' => $field . '_field fcheckbox', 'data-ref' => $field . '_all', 'title' => 'Can Edit', 'legend' => false, 'label' => false, 'hiddenField' => false)); ?>
                                    </td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <?php $field = 'Product'; ?>
                                    <td class="text-left"><a href="javascript:void(0);" class='clicklink' title='Product'>Product</a></td>
                                    <td>
                                        <?php echo $this->Form->checkbox($field . '.all',
                                            array('class' => $field . '_all pcheckbox', 'title' => 'All', 'data-field' => $field . '_field', 'legend' => false, 'label' => false, 'hiddenField' => false)); ?>
                                    </td>
                                    <td>
                                        <?php echo $this->Form->checkbox($field . '.view',
                                            array('class' => $field . '_field fcheckbox', 'data-ref' => $field . '_all', 'title' => 'Can View', 'legend' => false, 'label' => false, 'hiddenField' => false)); ?>
                                    </td>
                                    <td>
                                        <?php echo $this->Form->checkbox($field . '.add',
                                            array('class' => $field . '_field fcheckbox', 'data-ref' => $field . '_all', 'title' => 'Can Add', 'legend' => false, 'label' => false, 'hiddenField' => false)); ?>
                                    </td>
                                    <td>
                                        <?php echo $this->Form->checkbox($field . '.edit',
                                            array('class' => $field . '_field fcheckbox', 'data-ref' => $field . '_all', 'title' => 'Can Edit', 'legend' => false, 'label' => false, 'hiddenField' => false)); ?>
                                    </td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <?php $field = 'ProductCsv'; ?>
                                    <td class="text-left"><a href="javascript:void(0);" class='clicklink' title='Product'>Product CSV Import/Export</a></td>
                                    <td>
                                        <?php echo $this->Form->checkbox($field . '.all',
                                            array('class' => $field . '_all pcheckbox', 'title' => 'All', 'data-field' => $field . '_field', 'legend' => false, 'label' => false, 'hiddenField' => false)); ?>
                                    </td>
                                    <td>
                                        <?php echo $this->Form->checkbox($field . '.view',
                                            array('class' => $field . '_field fcheckbox', 'data-ref' => $field . '_all', 'title' => 'Export CSV', 'legend' => false, 'label' => false, 'hiddenField' => false)); ?>
                                    </td>
                                    <td>
                                        <?php echo $this->Form->checkbox($field . '.add',
                                            array('class' => $field . '_field fcheckbox', 'data-ref' => $field . '_all', 'title' => 'Import CSV', 'legend' => false, 'label' => false, 'hiddenField' => false)); ?>
                                    </td>
                                    <td></td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <?php $field = 'Order'; ?>
                                    <td class="text-left"><a href="javascript:void(0);" class='clicklink' title='Order'>Order</a></td>
                                    <td>
                                        <?php echo $this->Form->checkbox($field . '.all',
                                            array('class' => $field . '_all pcheckbox', 'title' => 'All', 'data-field' => $field . '_field', 'legend' => false, 'label' => false, 'hiddenField' => false)); ?>
                                    </td>
                                    <td>
                                        <?php echo $this->Form->checkbox($field . '.view',
                                            array('class' => $field . '_field fcheckbox', 'data-ref' => $field . '_all', 'title' => 'Can View', 'legend' => false, 'label' => false, 'hiddenField' => false)); ?>
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <?php $field = 'Faq'; ?>
                                    <td class="text-left"><a href="javascript:void(0);" class='clicklink' title='FAQ'>FAQ</a></td>
                                    <td>
                                        <?php echo $this->Form->checkbox($field . '.all',
                                            array('class' => $field . '_all pcheckbox', 'title' => 'All', 'data-field' => $field . '_field', 'legend' => false, 'label' => false, 'hiddenField' => false)); ?>
                                    </td>
                                    <td>
                                        <?php echo $this->Form->checkbox($field . '.view',
                                            array('class' => $field . '_field fcheckbox', 'data-ref' => $field . '_all', 'title' => 'Can View', 'legend' => false, 'label' => false, 'hiddenField' => false)); ?>
                                    </td>
                                    <td>
                                        <?php echo $this->Form->checkbox($field . '.add',
                                            array('class' => $field . '_field fcheckbox', 'data-ref' => $field . '_all', 'title' => 'Can Add', 'legend' => false, 'label' => false, 'hiddenField' => false)); ?>
                                    </td>
                                    <td>
                                        <?php echo $this->Form->checkbox($field . '.edit',
                                            array('class' => $field . '_field fcheckbox', 'data-ref' => $field . '_all', 'title' => 'Can Edit', 'legend' => false, 'label' => false, 'hiddenField' => false)); ?>
                                    </td>
                                    <td>
                                        <?php echo $this->Form->checkbox($field . '.delete',
                                            array('class' => $field . '_field fcheckbox', 'data-ref' => $field . '_all', 'title' => 'Can Delete', 'legend' => false, 'label' => false, 'hiddenField' => false)); ?>
                                    </td>
                                </tr>

                                <tr>
                                    <?php $field = 'Page'; ?>
                                    <td class="text-left"><a href="javascript:void(0);" class='clicklink' title='Static pages'>Static Pages</a></td>
                                    <td>
                                        <?php echo $this->Form->checkbox($field . '.all',
                                            array('class' => $field . '_all pcheckbox', 'title' => 'All', 'data-field' => $field . '_field', 'legend' => false, 'label' => false, 'hiddenField' => false)); ?>
                                    </td>
                                    <td>
                                        <?php echo $this->Form->checkbox($field . '.view',
                                            array('class' => $field . '_field fcheckbox', 'data-ref' => $field . '_all', 'title' => 'Can View', 'legend' => false, 'label' => false, 'hiddenField' => false)); ?>
                                    </td>
                                    <td>
                                        <?php echo $this->Form->checkbox($field . '.add',
                                            array('class' => $field . '_field fcheckbox', 'data-ref' => $field . '_all', 'title' => 'Can Add', 'legend' => false, 'label' => false, 'hiddenField' => false)); ?>
                                    </td>
                                    <td>
                                        <?php echo $this->Form->checkbox($field . '.edit',
                                            array('class' => $field . '_field fcheckbox', 'data-ref' => $field . '_all', 'title' => 'Can Edit', 'legend' => false, 'label' => false, 'hiddenField' => false)); ?>
                                    </td>
                                    <td>
                                        <?php echo $this->Form->checkbox($field . '.delete',
                                            array('class' => $field . '_field fcheckbox', 'data-ref' => $field . '_all', 'title' => 'Can Delete', 'legend' => false, 'label' => false, 'hiddenField' => false)); ?>
                                    </td>
                                </tr>

                                <tr>
                                    <?php $field = 'AdminPrivilage'; ?>
                                    <td class="text-left"><a href="javascript:void(0);" class='clicklink' title='Manage Admin Privilage'>Manage Admin Privilage</a></td>
                                    <td>
                                        <?php echo $this->Form->checkbox($field . '.all',
                                            array('class' => $field . '_all pcheckbox', 'title' => 'All', 'data-field' => $field . '_field', 'legend' => false, 'label' => false, 'hiddenField' => false)); ?>
                                    </td>
                                    <td></td>
                                    <td>
                                        <?php echo $this->Form->checkbox($field . '.add',
                                            array('class' => $field . '_field fcheckbox', 'data-ref' => $field . '_all', 'title' => 'Allow Manage Privilages', 'legend' => false, 'label' => false, 'hiddenField' => false)); ?>
                                    </td>
                                    <td></td>
                                    <td></td>
                                </tr>

                            </table>


                            <div class="row">
                                <div class="col-md-7">
                                </div>
                                <div class="col-md-5">
                                    <?php echo $this->Form->button('Save <i class="fa fa-arrow-circle-right"></i>',array('class' => 'btn btn-primary btn-wide pull-left_form','type' => 'submit','id' => 'submit_button'));
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