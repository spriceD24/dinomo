<!DOCTYPE html>
<html lang="en">
<?php include_once("util/WebUtil.php"); ?>
<?php include_once("util/logging/Logger.php"); ?>
<?php include_once("util/LogUtil.php"); ?>
<?php include_once("mobile_detect/Mobile_Detect.php");?>
<?php

$webUtil = new WebUtil ();
$webUtil->srcPage = "login.php";
set_error_handler ( array (
		$webUtil,
		'handleError' 
) );

LogUtil::debug ( 'login.php', 'Opening Login Page' );

$login = "";
$error = "";

if (isset ( $_GET ["login"] )) {
	$login = $_GET ["login"];
}

if (isset ( $_GET ["errorcode"] )) {
	$errorNum = intval ( $_GET ["errorcode"] );
	
	if ($errorNum == 1) {
		$error = "Please enter Login and Password";
	}
	if ($errorNum == 2) {
		$error = "Please enter Login";
	}
	if ($errorNum == 3) {
		$error = "Please enter Password";
	}
	if ($errorNum == 4) {
		$error = "Login does not exist";
	}
	if ($errorNum == 5) {
		$error = "Password is incorrect";
	}
}

if (isset ( $_GET ["forgot"] )) {
	$error = "EMAIL SENT !!<br/>You should receive an email with your Username/Password details";
}

$detect = new Mobile_Detect ();
$mobileDropDownStyle = ConfigUtil::getMobileDropDownStyle ();
?>

  
  
<head>
<meta charset="utf-8">
<title>Dinomo QA</title>

<meta name="viewport"
	content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="apple-mobile-web-app-capable" content="yes">

<script src="js/jquery-1.7.2.min.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/base.js"></script>



<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />

<link href="css/bootstrap-responsive.min.css" rel="stylesheet"
	type="text/css" />
<link rel="stylesheet" type="text/css" href="css/dinamo.css">

<link href="css/font-awesome.css" rel="stylesheet">
<link
	href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600"
	rel="stylesheet">

<link href="css/style.css" rel="stylesheet" type="text/css">

<link href="css/pages/signin.css" rel="stylesheet" type="text/css">

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

			<form action="submit_login.php" method="post">

				<h1>Login</h1>

				<div class="login-fields">
				<?php
				if (! empty ( $error )) {
					?>
					<p style="font-style: italic; color: red; font-weight: bold"><?=$error;?></p>
				<?php
				} else {
					?>
				<p>Please provide your details</p>
				<?php
				}
				?>
				<div class="field">
						<label for="login">Username</label> <input type="text" id="login"
							name="login" value="<?=$login;?>" placeholder="Username"
							class="login username-field"
							<?php
							if ($detect->isMobile () && ! $detect->isTablet ()) {
								print " style='" . $mobileDropDownStyle . "'";
							}
							?> />
					</div>
					<!-- /field -->

					<div class="field">
						<label for="password">Password:</label> <input type="password"
							id="password" name="password" value="" placeholder="Password"
							class="login password-field"
							<?php
							if ($detect->isMobile () && ! $detect->isTablet ()) {
								print " style='" . $mobileDropDownStyle . "'";
							}
							?> />
					</div>
					<!-- /password -->

				</div>
				<!-- /login-fields -->

				<div class="login-actions">

					<button class="button btn btn-success btn-large">Sign In</button>

				</div>
				<!-- .actions -->



			</form>

		</div>
		<!-- /content -->

	</div>
	<!-- /account-container -->



	<div class="login-extra">
		<a href="forgot_login.php">Forgot Username/Password</a>
	</div>
	<!-- /login-extra -->

</body>

</html>
