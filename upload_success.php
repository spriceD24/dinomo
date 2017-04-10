<?php include_once("util/ConfigUtil.php"); ?>
<?php include_once("util/WebUtil.php"); ?>
<?php include_once("util/LogUtil.php"); ?>
<?php include_once("dao/model/Category.php"); ?>
<?php include_once("dao/model/Project.php"); ?>
<?php include_once("delegate/ProjectDelegate.php"); ?>
<?php include_once("mobile_detect/Mobile_Detect.php");?>

<?php

$webUtil = new WebUtil ();
$webUtil->srcPage = "upload_success.php";
set_error_handler ( array (
		$webUtil,
		'handleError' 
) );
$currentUser = $webUtil->getLoggedInUser ();

$projectDelegate = new ProjectDelegate ();
$projectID = intval ( $_GET ["projectID"] );
$categoryID = intval ( $_GET ["categoryID"] );
// get the data
$project = $projectDelegate->getProject ( $projectID );
$currentCategory = $projectDelegate->getCategory ( $projectID, $categoryID );

LogUtil::debug ( "upload_success", "Upload success for project = " . $project->projectName . ", category = " . $currentCategory->categoryName . ", user = " . $currentUser->login );

// print_r($project);
$id = $_GET ['id'];
$webUrl = ConfigUtil::getWebFolder () . "/" . urlencode ( $id ) . ".html";
$pdfUrl = ConfigUtil::getPDFFolder () . "/" . urlencode ( $id ) . ".pdf";

$detect = new Mobile_Detect ();
$isMobile = ($detect->isMobile() && !$detect->isTablet());
$isTablet = $detect->isTablet();

if (isset ( $_GET ["isMobile"] )) {
	$isMobile = ( $_GET ["isMobile"] == "true" );
}
if (isset ( $_GET ["isTablet"] )) {
	$isTablet = ( $_GET ["isTablet"] == "true" );
}

?>


<html>

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
	href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600"
	rel="stylesheet">


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
					<ul class="nav">
						<li class="" style="padding-top: 30px"><a href="view_reports.php"
							style="padding: 0px 0px 0px 0px" class=""><u>View Submitted Reports</u>
						</a></li>
						<li class="" style="padding-top: 30px;padding-left:20px"><a href="view_preliminary_reports.php"
							style="padding: 0px 0px 0px 0px" class=""><u>View Saved Reports</u>
						</a></li>
					</ul>
				</div>

				<div class="nav-collapse">

					<ul class="nav pull-right">

						<li class="" style="float: none"><span
							style="color: white;">User: <?=$currentUser->name?> (<span
								style="font-style: italic"><a href="logout.php">logout</a></span>)
						</span></li>

						<li class="" style="padding-top: 10px"><a href="select_qa.php"
							style="padding: 0px 0px 0px 0px" class=""> <i
								class="icon-chevron-left"></i> Submit Another QA Report

						</a></li>
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

			<h3>QA Report Submitted Succesfully</h3>

			<div class="login-fields label-display">
				<p></p>
				<table
					style="font-size: 14px; border 1px solid; padding-top: 10px; padding-bottom: 10px; width: 80%">
					<tr>
						<td style="font-weight: bold" class="label-display">Project:</td>
						<td align="right" class="label-display"><?=$project->projectName;?></td>
					</tr>
					<tr>
						<td style="font-weight: bold" class="label-display">Report Type:</td>
						<td align="right" class="label-display"><?=$currentCategory->categoryName;?></td>
					</tr>
				</table>
				<p></p>
				<p style="font-size: 15px" class="label-display">
					Click <a href='<?=$pdfUrl?>' target='_blank'>here <img
						src="img/pdf.png" /></a> to view submitted report.
			
			</div>
			<!-- /login-fields -->

		</div>
		<!-- /content -->

	</div>
	<!-- /account-container -->


</body>

</html>