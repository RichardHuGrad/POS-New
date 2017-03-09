<!doctype html>
<html>
    <head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate, max-stale=0, post-check=0, pre-check=0" />
	<meta http-equiv="Pragma" content="no-cache" />
	<meta http-equiv="Expires" content="-1" />
	<meta http-equiv="Vary" content="*" />
	<title>POS - InStore Experience - <?php echo $title_for_layout; ?></title>
	<?php
		echo $this->Html->css(array('bootstrap.css', 'font-awesome.min.css', 'styles.css', 'responsive.css', 'navbar.css','font-awesome.min.css'));
        echo $this->fetch('css');
	?>

    </head>

    <body>
        <?php echo $this->Html->script(array('lib/jquery.min.js', 'lib/bootstrap.min.js', 'lib/jquery.mCustomScrollbar.concat.min.js', 'lib/notify.min.js', 'lib/md5.js','lib/vue.js', 'lib/vue-resource.min.js')); ?>
        <header>
            <?php echo $this->element('navbar'); ?>
        </header>
		<?php echo $this->fetch('content');
		?>
    </body>
</html>


<?php
    echo $this->fetch('script');
?>
