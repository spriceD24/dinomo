<!DOCTYPE html>
<html lang="en">
<?php include_once("util/WebUtil.php"); ?>
<?php include_once("util/logging/Logger.php"); ?>
<?php include_once("util/LogUtil.php"); ?>
<?php include_once("mobile_detect/Mobile_Detect.php");?>
<?php

$webUtil = new WebUtil ();
$webUtil->srcPage = "send_login.php";
set_error_handler ( array (
		$webUtil,
		'handleError' 
) );

LogUtil::debug ( 'forgot_login.php', 'Opening Login Page' );

$login = "";
$error = "";

if (isset ( $_GET ["login"] )) {
	$login = $_GET ["login"];
}

if (isset ( $_GET ["errorcode"] )) {
	$errorNum = intval ( $_GET ["errorcode"] );
	
	if ($errorNum == 1) {
		$error = "Please enter valid email address";
	}
	if ($errorNum == 2) {
		$error = "No users matching this email address in the system";
	}
}
$detect = new Mobile_Detect ();
$mobileDropDownStyle = ConfigUtil::getMobileDropDownStyle ();

$isMobile = ($detect->isMobile() && !$detect->isTablet());
$isTablet = $detect->isTablet();

if (isset ( $_GET ["isMobile"] )) {
	$isMobile = ( $_GET ["isMobile"] == "true" );
}
if (isset ( $_GET ["isTablet"] )) {
	$isTablet = ( $_GET ["isTablet"] == "true" );
}

?>

  
  
<head>
<meta charset="utf-8">
<link rel="icon" type="image/png" href="img/favicon-32x32.png" sizes="32x32" />
<title>Dinomo QA</title>

<meta name="viewport"
	content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="apple-mobile-web-app-capable" content="yes">

<script src="js/jquery-1.7.2.min.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/base.js"></script>

<?php 
	if($isMobile && !$isTablet)
	{
?>
	<link href="css/bootstrap-phone.min.css" rel="stylesheet" type="text/css" />
	<link href="css/bootstrap-responsive-phone.min.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" type="text/css" href="css/dinamo-phone.css">
	<link href="css/style-phone.css" rel="stylesheet" type="text/css">
	<link href="css/pages/signin-phone.css" rel="stylesheet" type="text/css">	
<?php 
	}
	else if($isTablet && !$isMobile)
	{
?>
	<link href="css/bootstrap-tablet.min.css" rel="stylesheet" type="text/css" />
	<link href="css/bootstrap-responsive-tablet.min.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" type="text/css" href="css/dinamo-tablet.css">
	<link href="css/style-tablet.css" rel="stylesheet" type="text/css">
	<link href="css/pages/signin-tablet.css" rel="stylesheet" type="text/css">	
<?php 
	}else{		
?>
	<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
	<link href="css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" type="text/css" href="css/dinamo.css">
	<link href="css/style.css" rel="stylesheet" type="text/css">
	<link href="css/pages/signin.css" rel="stylesheet" type="text/css">	
<?php 
	}
?>

<link href="css/font-awesome.css" rel="stylesheet">
<link
	href="css/google-fonts.css"
	rel="stylesheet">

<link href="css/style.css" rel="stylesheet" type="text/css">

</head>

<body>

	<div class="navbar navbar-fixed-top">

		<div class="navbar-inner">

			<div class="container" style="background-color: #181717">

				<a class="btn btn-navbar" data-toggle="collapse"
					data-target=".nav-collapse"> <span class="icon-bar"></span> <span
					class="icon-bar"></span> <span class="icon-bar"></span>

				</a> <a class="brand" href="index.html"> <img
					src="img/dinamo_small.png" />

				</a>



				<div class="nav-collapse">

					<ul class="nav pull-right">

						<li class=""></li>

						<li class="" style="padding-top: 20px"></li>

					</ul>



				</div>
				<!--/.nav-collapse -->



			</div>
			<!-- /container -->

		</div>
		<!-- /navbar-inner -->

	</div>
	<!-- /navbar -->


	<div class="account-container">

		<div class="content clearfix">

			<form action="send_login.php" method="post">

				<h1>Email Address</h1>

				<div class="login-fields">
				<?php
				if (! empty ( $error )) {
					?>
					<p style="font-style: italic; color: red; font-weight: bold"><?=$error;?></p>
				<?php
				} else {
					?>
				<p>Please provide your email address</p>
				<?php
				}
				?>
				<div class="field">
						<label for="login">Email</label> <input type="text" id="email"
							name="email" value="<?=$login;?>" placeholder="Email"
							class="login username-field"
							<?php
							if ($detect->isMobile () && ! $detect->isTablet ()) {
								print " style='" . $mobileDropDownStyle . "'";
							}
							?> />
					</div>
					<!-- /field -->

				</div>
				<!-- /login-fields -->

				<div class="login-actions">

					<button class="button btn btn-success btn-large">Submit</button>

				</div>
				<!-- .actions -->



			</form>

		</div>
		<!-- /content -->

	</div>
	<!-- /account-container -->


</body>

</html>
