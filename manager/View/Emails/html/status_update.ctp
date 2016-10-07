<div style="background:#1a2732; border:1px solid #06365f; color: #fff; padding:15px 10px; min-height: 200px;">
    Dear <?php echo ucfirst($name); ?>,
    <br/>
    <br/>

    <?php if('approve' == $type){
        echo 'Your profile has been approved by the administrator.';
    }
    elseif('active' == $type){
        echo 'Your profile has been activated by the administrator.';
    }
    elseif('inactive' == $type){
        echo 'Your profile has been deactivated by the administrator.';
    } ?>
</div>
