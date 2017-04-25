<?php include_once("util/CollectionsUtil.php"); ?><?php include_once("util/LogUtil.php"); ?><?php include_once("util/FileUtil.php"); ?><?php include_once("util/ConfigUtil.php"); ?><?php include_once("mobile_detect/Mobile_Detect.php");?>
<?php
$fileUtil = new FileUtil();$detect = new Mobile_Detect ();$path = realpath ( '.' );$logDirectory = $path . '/' . ConfigUtil::getLogFolder ();$files = $fileUtil->sortFiles($logDirectory);
?>

<html lang="en">
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

<link href="css/font-awesome.css" rel="stylesheet"><link	href="css/google-fonts.css"	rel="stylesheet">
<link href="css/style.css" rel="stylesheet" type="text/css"><link href="css/pages/signin.css" rel="stylesheet" type="text/css"></head>
<body>
	<div class="container">		<div class="row">			<div class="span12">				<div class="error-container">					<h3>Select Log file</h3>					<div <?php							if (!$detect->isMobile () ||  $detect->isTablet ()) {								print " style='padding-left:40%'";							}							?>>							<table>					<?php						$index = 0;						foreach ($files as $file) 						{							if($index == 0)							{     							echo "<tr><td><a href='view_log.php?filename=".urlencode($file)."'>" . $file ."</a> <font style='color:red;font-style:italic'>&nbsp;(Latest)</font><br/></td></tr>";     							$index++;							}else{								echo "<tr><td><a href='view_log.php?filename=".urlencode($file)."'>" . $file ."</a><br/></td></tr>";														}    						 						}	 						?>						</table>					</div><br/><br/>
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
