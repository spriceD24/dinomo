<!DOCTYPE html><html lang="en"><?php include_once("util/LogUtil.php"); ?><?php include_once("util/DBUtil.php"); ?><?php include_once("delegate/ProjectDelegate.php"); ?><?php include_once("util/WebUtil.php"); ?><?php include_once("mobile_detect/Mobile_Detect.php");?><?php	$webUtil = new WebUtil ();	$webUtil->srcPage = "manage_projects.php";	set_error_handler ( array (			$webUtil,			'handleError' 	) );		$detect = new Mobile_Detect ();	$isMobile = ($detect->isMobile() && !$detect->isTablet());	$isTablet = $detect->isTablet();		$currentUser = $webUtil->getLoggedInUser();	if(!$currentUser->hasRole('admin'))	{		header ( "Location: select_qa.php" );		exit ();	}		//$projectDAO->saveReport($project);	$projectDelegate = new ProjectDelegate();	$allProjects = $projectDelegate->getAllProjectsLiteIncludingDeleted($currentUser->clientID);		$editProjects = true;	$deleteProjects = true;	$message = "";	if (isset ( $_GET ["message"] )) 	{		$message = $_GET ["message"];	}	?>
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
<style>.tr-odd td{	font-size:14px;	padding-left:20px;}.tr-even td{	padding-left:20px;	font-size:14px;	background-color:#f5f5f0;}.tr-updated td{	padding-left:20px;	font-size:14px;	background-color:yellow;}.tr-deleted td{	padding-left:20px;	font-size:14px;	background-color:#FFA07A;}.tr-header td{	padding-left:20px;	font-weight:bold;	font-size:14px;	background-color:#ccebff;}</style>
</head>

<body>

	</div>
	<!-- /navbar -->

	<div class="container">

		<div class="row">

			<div class="span12">
				<br/>				<h3><?=$message?></h3>				  <input type="radio" name="show" value="active" checked onclick="showActiveProjectsOnly()"> Show Active Projects Only 				  <input type="radio" name="show" value="all" onclick="showDeletedProjects()"> Show All Projects (including deleted) 
				<div class="error-container" style="margin-top:0;margin-bottom:0;text-align:left">					<?php 						if(count($allProjects) == 0)						{							?>							No Projects exist					<?php 								}else{					?>						<br/>						<table style="padding-left:10px; border: 1px dashed black;background-color:white;width:400px">									<tr class="tr-header">								<td>Name</td>								<?php 									if($editProjects)									{								?>									<td></td>								<?php 											}if($deleteProjects)									{								?>									<td></td>								<?php 											}								?>																											</tr>					<?php 								$projectID = -1;							if (isset ( $_GET ["projectID"] ))							{								$projectID =  intval ($_GET ["projectID"]) ;												}															$count = 1;							while ( $project = $allProjects->iterate () )							{														?>							<tr							<?php 							echo " id = '".$project->projectID."' ";							$className = "";							if($count % 2 == 0)							{								$className = "tr-even";							}else{								$className = "tr-odd";							}							if($project->projectID == $projectID)							{								$className = "tr-updated";							}							if($project->deleteFlag == 1)							{								echo "  style='display:none' ";								$className = "tr-deleted";							}							echo " class='".$className."'";															?>							>								<td><?=$project->projectName?></td>								<?php 									if($editProjects)									{								?>									<td>										<button style="float:left;margin-bottom:15px" class="button btn btn-success btn-small" type="button" onclick="editProject(<?=$project->projectID?>)">Edit</button>									</td>								<?php 											}if($deleteProjects && $project->deleteFlag == 0)									{								?>									<td>										<button style="float:left;margin-bottom:15px" class="button btn btn-danger btn-small" type="button" onclick="deleteProject(<?=$project->projectID?>,'<?=str_replace("'"," ",$project->projectName)?>')">Delete</button>									</td>								<?php 											}if($deleteProjects && $project->deleteFlag == 1)									{								?>									<td style="padding-right:15px">										<button style="float:left;margin-bottom:15px" class="button btn btn-warning btn-small" type="button" onclick="activateProject(<?=$project->projectID?>,'<?=str_replace("'"," ",$project->projectName)?>')">Reactivate</button>									</td>								<?php 											}								?>																																			</tr>					<?php 											$count = $count+1;							}					?>						</table>							<?php						}					?>
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

</body><script>
function checkShowReports(){	val = $('input[name="show"]:checked').val();	if(val == 'me')	{		showMine();	}else{		showAll();	}}function showDeletedProjects(){	//alert('show all');	<?php 	while ( $project = $allProjects->iterate () )	{		echo " document.getElementById('".$project->projectID."').style.display = ''\n";	}	?>}function showActiveProjectsOnly(){	<?php 	while ( $project = $allProjects->iterate () )	{		if($project->deleteFlag == 1)		{			echo " document.getElementById('".$project->projectID."').style.display = 'none'\n";		}	}	?>}function editProject(projectID){	window.location = "edit_project.php?projectID="+projectID;}function deleteProject(projectID, name){	var r = confirm("Are you want to delete project - '"+name+"'");	if (r == true) {		window.location = "submit_delete_project.php?action=delete&projectID="+projectID;	} }function activateProject(projectID, name){	var r = confirm("Are you want to reactivate project - '"+name+"'");	if (r == true) {		window.location = "submit_delete_project.php?action=activate&projectID="+projectID;	} }</script>
</html>
