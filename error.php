<!DOCTYPE html>
<html lang="en">
<?php include_once("util/LogUtil.php"); ?>
<?php

$errorMsg = "";
$errorNo = "";
$src = "";

if (isset ( $_GET ["errorMsg"] )) {
	$errorMsg = urldecode ( $_GET ["errorMsg"] );
}
if (isset ( $_GET ["errorNo"] )) {
	$errorNo = urldecode ( $_GET ["errorNo"] );
}
if (isset ( $_GET ["src"] )) {
	$src = urldecode ( $_GET ["src"] );
}

LogUtil::error ( "ERROR - ", "errorMsg = " . $errorMsg . ", errorNo = " . $errorNo . ", src = " . $src );

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

	<div class="container">

		<div class="row">

			<div class="span12">

				<div class="error-container">

					<h2>Something went wrong....</h2>

					<div class="error-details">
						<p>
							<b>Error Message: </b>
					<?=$errorMsg?>
				</p>
						<p>
							<b>Source File: </b>
					<?=$src?>
				</p>
						<p>
							<b>Error No: </b>
					<?=$errorNo?>
				</p>
						<p>Please report this to System Administrator.</p>
					</div>
					<!-- /error-details -->

					<div class="error-actions">
						<a href="index.html" class="btn btn-large btn-primary"> <i
							class="icon-chevron-left"></i> &nbsp; Back to Homepage
						</a>



					</div>
					<!-- /error-actions -->

				</div>
				<!-- /error-container -->

			</div>
			<!-- /span12 -->

		</div>
		<!-- /row -->

	</div>
	<!-- /container -->


	<script src="js/jquery-1.7.2.min.js"></script>
	<script src="js/bootstrap.js"></script>

</body>

</html>
