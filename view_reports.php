<!DOCTYPE html><html lang="en"><?php include_once("util/LogUtil.php"); ?><?php include_once("util/DBUtil.php"); ?><?php include_once("dao/model/Report.php"); ?><?php include_once("dao/ReportDAO.php"); ?><?php include_once("util/DBUtil.php"); ?><?php include_once("delegate/UserDelegate.php"); ?><?php include_once("delegate/ProjectDelegate.php"); ?><?php include_once("delegate/UserDelegate.php"); ?><?php include_once("util/WebUtil.php"); ?><?php include_once("mobile_detect/Mobile_Detect.php");?><?php	$webUtil = new WebUtil ();	$webUtil->srcPage = "view_reports.php";	set_error_handler ( array (			$webUtil,			'handleError' 	) );		$detect = new Mobile_Detect ();	$isMobile = ($detect->isMobile() && !$detect->isTablet());	$isTablet = $detect->isTablet();		$currentUser = $webUtil->getLoggedInUser();	$reportDAO = new ReportDAO();	$reports = $reportDAO->getAllReports();	//$reportDAO->saveReport($report);	$userDelegate = new UserDelegate();	$projectDelegate = new ProjectDelegate();	$userDelegate = new UserDelegate();	?>
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
<style>.tr-odd td{	font-size:14px;	padding-left:20px;}.tr-even td{	padding-left:20px;	font-size:14px;	background-color:#f5f5f0;}.tr-header td{	padding-left:20px;	font-weight:bold;	font-size:14px;	background-color:#ccebff;}</style>
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
					<ul class="nav pull-right">						<li class="" style="float: none"><span							style="color: white; ">User: <?=$currentUser->name?> (<span								style="font-style: italic"><a href="logout.php">logout</a></span>)						</span></li>						<li class="" style="padding-top: 10px"><a href="select_qa.php"							style="padding: 0px 0px 0px 0px" class=""> <i								class="icon-chevron-left"></i> Submit QA Report						</a></li>					</ul>



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
				<br/>				<h3>All Uploaded Reports</h3>
				<div class="error-container" style="margin-top:0;margin-bottom:0;text-align:left">					<?php 						if($reports->getNumObjects() == 0)						{							?>							No QA Reports Uploaded Yet					<?php 								}else{					?>						<br/>						<table style="padding-left:10px; border: 1px dashed black;background-color:white">									<tr class="tr-header">								<td>Project</td>								<td>QA Report</td>								<td>Submitted By</td>								<td>Submitted On</td>								<td>View Report</td>								<?php									if(!$isMobile && !$isTablet)									{								?>									<td>Download</td>								<?php									}								?>															</tr>					<?php 									$count = 1;							while ( $report = $reports->iterate () )							{								$project = $projectDelegate->getProject($report->projectID);								$category = $projectDelegate->getCategory($report->projectID,$report->categoryID);					?>							<tr							<?php 							if($count % 2 == 0)							{								echo " class='tr-even' ";							}else{								echo " class='tr-odd' ";							}							?>							>								<td><?=$project->projectName?></td>								<td><?=$category->categoryName?></td>								<td><?php 										$user = $userDelegate->getUser($report->uploadedBy);										$val = $user->name;										if($report->uploadedBy != $report->uploadedForUser)										{											$userFor = $userDelegate->getUser($report->uploadedForUser);											$val = $val." (on behalf of ".$userFor->name.") ";										}										echo $val;									?>								</td>								<td><?=$report->uploadedDateString?></td>								<td><a href='<?=$report->pdfURL?>' target='_blank'><img										src="img/pdf.png" /></a></td>								<?php									if(!$isMobile && !$isTablet)									{								?>								<td><a href='download_pdf.php?report=<?=urlencode($report->reportKey)?>' target='_blank'><img										src="img/download.png" /></a></td>								<?php									}								?>							</tr>					<?php 											$count = $count+1;							}					?>						</table>							<?php						}					?>
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
