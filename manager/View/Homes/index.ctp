<!doctype html>
<html>
    <head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<title>:: POS :: InStore Experience</title>
	<link href="<?php echo WEBSITE_FRONT_CSS; ?>bootstrap.css" rel="stylesheet" type="text/css"/>
	<link href="<?php echo WEBSITE_FRONT_CSS; ?>font-awesome.min.css" rel="stylesheet" type="text/css"/>
	<link href="<?php echo WEBSITE_FRONT_CSS; ?>styles.css" rel="stylesheet" type="text/css"/>
	<link href="<?php echo WEBSITE_FRONT_CSS; ?>responsive.css" rel="stylesheet" type="text/css"/>

    </head>

    <body>
	<div class="container">
	    <div class="loginpage clearfix">

		<div class="login-logo"><center><img src="<?php echo WEBSITE_FRONT_IMAGE; ?>login-logo.jpg" class="img-responsive" alt="POS"></center></div>

		<form role="form">
		    <div class="form-group">
			<div class="form-round"><i class="fa fa-user" aria-hidden="true"></i></div>
			<input type="text" class="form-control" placeholder="User Name" required>
		    </div>
		    <div class="form-group">
			<div class="form-round"><i class="fa fa-lock" aria-hidden="true"></i></div>
			<input type="password" class="form-control" placeholder="Password" required>
		    </div>
		    <div class="text-center"><button type="submit" class="btn">Sign in</button></div>
		    <div class="text-center forget-txt"><a href="#">Forget your password?</a></div>
		</form>
	    </div>
	</div>
	<script type="text/javascript" src="<?php echo WEBSITE_FRONT_JS; ?>jquery.min.js"></script> 
	<script type="text/javascript" src="<?php echo WEBSITE_FRONT_JS; ?>bootstrap.min.js"></script> 
    </body>
</html>


