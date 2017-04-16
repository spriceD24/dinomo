<!DOCTYPE html><html lang="en"><?php include_once("util/LogUtil.php"); ?><?php include_once("util/CacheUtil.php"); ?><?php include_once("mobile_detect/Mobile_Detect.php");?><?php$filename = "";
if (isset ( $_GET ["filename"] )) {	$filename = urldecode( $_GET ["filename"] );}else{	header ( "Location: log_admin.php" );	exit ();	}$path = realpath ( '.' );$fileToOpen = $path . '/' . ConfigUtil::getLogFolder ()."/".$filename;?>
<head>
<meta charset="utf-8"><link rel="icon" type="image/png" href="img/favicon-32x32.png" sizes="32x32" />
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

	<div class="container">

		<div class="row">

			<div class="span12">
				<h3><?=$filename?></h3>
				<div class="error-container" style="background-color: white;margin-top:0;margin-bottom:0;text-align:left">
				<?php 				$file = file($fileToOpen);				$file = array_reverse($file);				foreach($file as $f){				    echo $f."<br />";				}				?>
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
