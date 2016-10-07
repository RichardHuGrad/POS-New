<?php
echo $this->Html->css(array('validationEngine.jquery', 'jquery-ui-1.8.22.custom', 'jquery-ui_new'));
echo $this->Html->script(array('jquery.validationEngine-en', 'jquery.validationEngine'));
?>
<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery("#AdminAdminChangePasswordForm").validationEngine();
    });
</script>
<?php echo $this->Html->script(array('Chart.min','index'), array('inline' => false)); ?>
<?php echo $this->Html->scriptBlock("
        jQuery(document).ready(function () {
        Index.init();
    });  ", array('inline' => false)); ?>
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
                <div class="container-fluid container-fullw bg-white">
                    <div class="row">
                        <div class="panel panel-white no-radius">
                            <div class="panel-body">

                                <table class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th style=" background-color: #EEEEEE;">
                                            <div class="row-fluid">
                                                <h2><?php echo __('Change Password');
                                                    if(!empty($user_data)) {
                                                      echo  ': ' . $user_data['Admin']['firstname'] . ' ' . $user_data['Admin']['lastname'];
                                                    } ?> </h2>
                                            </div>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tr>
                                        <td>
                                            <div class="help-block text-center"><?php echo $this->Session->Flash(); ?></div>

                                            <?php echo $this->Form->create('Admin', array('autocomplete' => 'off'));
                                            echo $this->Form->input('id', array('type' => 'hidden', 'value' => (!empty($id)) ? $id : $this->Session->read('Admin.id'))); ?>

                                            <div class="row-fluid">

                                                <?php if(empty($id)) { ?>
                                                    <div class="col-md-12 margin-bottom-15">
                                                        <div class="col-md-6 text-center">
                                                            <?php echo $this->Form->label('Admin.old_password', 'Old Password' . '<span class="required">*</span> :'); ?>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="pull-left" >
                                                                <?php echo $this->Form->password('old_password', array('class' => 'textbox validate[required]'));
                                                                echo $this->Form->error('old_password', array('wrap' => false)); ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } ?>

                                                <div class="col-md-12 margin-bottom-15">
                                                    <div class="col-md-6 text-center">
                                                        <?php echo $this->Form->label('Admin.password', 'New Password' . '<span class="required">*</span> :'); ?>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="pull-left" >
                                                            <?php echo $this->Form->password('password', array('class' => 'textbox validate[required]'));
                                                            echo $this->Form->error('password', array('wrap' => false)); ?>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12 margin-bottom-15">
                                                    <div class="col-md-6 text-center">
                                                        <?php echo $this->Form->label('Admin.confirm_password', 'Confirm Password' . '<span class="required">*</span> :'); ?>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="pull-left" >
                                                            <?php echo $this->Form->password('confirm_password', array('class' => 'textbox validate[required,equals[UserUserPassword]'));
                                                            echo $this->Form->error('confirm_password', array('wrap' => false)); ?>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <?php
                                                        if(empty($id)) {
                                                            echo $this->Html->link('Cancel <i class="fa fa-times-circle"></i>', array('controller' => 'admins', 'action' => 'dashboard', 'admin' => true), array('class' => 'btn btn-primary btn-wide pull-right', 'escape' => false));
                                                        }else{
                                                            echo $this->Html->link('Cancel <i class="fa fa-times-circle"></i>', array('controller' => 'admins', 'action' => 'users', 'admin' => true), array('class' => 'btn btn-primary btn-wide pull-right', 'escape' => false));
                                                        }
                                                        echo $this->Form->button('Save <i class="fa fa-arrow-circle-right"></i>',array('class' => 'btn btn-primary btn-wide pull-right margin-right-10','type' => 'submit','id' => 'submit_button'));
                                                        ?>
                                                    </div>
                                                </div>
                                                <div class="clearfix"></div>
                                            </div>
                                            <?php echo $this->Form->end(); ?>
                                        </td>
                                    </tr>
                                </table>

                            </div>
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



