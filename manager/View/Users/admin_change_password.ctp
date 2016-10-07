<?php
echo $this->Html->css(array('validationEngine.jquery', 'jquery-ui-1.8.22.custom', 'jquery-ui_new'));
echo $this->Html->script(array('jquery.validationEngine-en', 'jquery.validationEngine'));
?>
<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery("#UserChangeAdminPasswordForm").validationEngine();
    });
</script>
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th style="background-color: #EEEEEE;"> 
    <div class="row-fluid">
        <h2><?php echo __('Change Admin Password'); ?> </h2>
    </div>
</th>
</tr>
<tr>
    <td>
        <?php echo $this->Form->create('User', array('enctype' => 'multipart/form-data')); ?>
        <?php echo $this->Form->input('id', array('type' => 'hidden', ' value' => 1)); ?>
        <div class="row-fluid">
            <div class="span12"><?php echo $this->Session->Flash(); ?></div>
            <div class="row-fluid">
                <div class="span5">
                    <div class="control-group <?php echo ($this->Form->error('old_password')) ? 'error' : ''; ?>">
                        <?php echo $this->Form->label('User' . '.name', __('Old Password', true) . ' :<span class="required">*</span>', array('style' => "float:left;width:155px;")); ?>
                        <div class="input <?php echo ($this->Form->error('old_password')) ? 'error' : ''; ?>" style="margin-left:150px;" >
                            <?php echo $this->Form->password('User' . ".old_password", array('class' => 'textbox validate[required]')); ?>
                            <span class="help-inline" style="color: #B94A48;">
                                <?php echo $this->Form->error('User' . '.old_password', array('wrap' => false)); ?>
                            </span>
                        </div>
                    </div>
                </div>                
                <div class="span5" >
                    <div class="clearfx control-group <?php echo ($this->Form->error('user_password')) ? 'error' : ''; ?>">
                        <?php echo $this->Form->label('User' . '.user_password', __('New Password', true) . ' :<span class="required">*</span>', array('style' => "float:left;width:155px;")); ?>
                        <div class="input <?php echo ($this->Form->error('user_password')) ? 'error' : ''; ?>" style="margin-left:150px;" >
                            <?php echo $this->Form->password('User' . ".user_password", array('class' => 'textbox validate[required]')); ?>
                            <span class="help-inline" style="color: #B94A48;">
                                <?php echo $this->Form->error('User' . '.user_password', array('wrap' => false)); ?>
                            </span>
                        </div>
                    </div>
                </div>                    
            </div>
            <div class="row-fluid">
                <div class="span5" >
                    <div class="clearfx control-group <?php echo ($this->Form->error('confirm_password')) ? 'error' : ''; ?>">
                        <?php echo $this->Form->label('User' . '.confirm_password', __('Confirm Password', true) . ' :<span class="required">*</span>', array('style' => "float:left;width:155px;")); ?>
                        <div class="input <?php echo ($this->Form->error('confirm_password')) ? 'error' : ''; ?>" style="margin-left:150px;" >
                            <?php echo $this->Form->password('User' . ".confirm_password", array('class' => 'textbox validate[required,equals[UserUserPassword]')); ?>
                            <span class="help-inline" style="color: #B94A48;">
                                <?php echo $this->Form->error('User' . '.confirm_password', array('wrap' => false)); ?>
                            </span>
                        </div>
                    </div>
                </div>                
            </div> 
            <div class="form-actions">
                <div class="input" > <?php echo $this->Form->button(__d("users", "Save", true), array("class" => "btn btn-primary")); ?> <?php
                    echo $this->Html->link(__("Cancel", true), array("action" => "dashboard"), array("class" => "btn", "escape" => false));
                    ?>&nbsp;&nbsp;
                </div>
            </div>
        </div>
        <?php echo $this->Form->end(); ?>
    </td>
</tr>
</thead>
</table>

