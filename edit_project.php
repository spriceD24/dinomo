<!DOCTYPE html><html lang="en"><?php include_once("util/LogUtil.php"); ?><?php include_once("util/DBUtil.php"); ?><?php include_once("delegate/ProjectDelegate.php"); ?><?php include_once("util/WebUtil.php"); ?><?php include_once("mobile_detect/Mobile_Detect.php");?><?php	$webUtil = new WebUtil ();	$webUtil->srcPage = "edit_project.php";	set_error_handler ( array (			$webUtil,			'handleError' 	) );		$detect = new Mobile_Detect ();	$isMobile = ($detect->isMobile() && !$detect->isTablet());	$isTablet = $detect->isTablet();		$currentUser = $webUtil->getLoggedInUser();	if(!$currentUser->hasRole('admin'))	{		header ( "Location: select_qa.php" );		exit ();	}		//$categoryDAO->saveReport($category);	$projectDelegate = new ProjectDelegate();	$projectID = intval($_GET ["projectID"]);	$allCategories = $projectDelegate->getCategoriesIncludingDeleted($projectID);	$project = $projectDelegate->getProject($projectID);	$editCategories = true;	$deleteCategories = true;	$message = "";	if (isset ( $_GET ["message"] )) 	{		$message = $_GET ["message"];	}	?>
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
	href="css/google-fonts.css"
	rel="stylesheet">

<link href="css/style.css" rel="stylesheet" type="text/css">

<link href="css/pages/signin.css" rel="stylesheet" type="text/css">
<style>.tr-odd td{	font-size:14px;	padding-left:20px;}.tr-even td{	padding-left:20px;	font-size:14px;	background-color:#f5f5f0;}.tr-updated td{	padding-left:20px;	font-size:14px;	background-color:yellow;}.tr-deleted td{	padding-left:20px;	font-size:14px;	background-color:#FFA07A;}.tr-header td{	padding-left:20px;	font-weight:bold;	font-size:14px;	background-color:#ccebff;}</style>
</head>

<body>

	<!-- /navbar -->	<div class="account-container" style="margin:40px">		<div class="content clearfix">			<form action="submit_edit_project.php" method="post" id="submitProjectForm" name="submitProjectForm">					<div class="field">						<label for="name"></label>Project Name						<br/><input type="text" id="name"							name="name" value="<?=$project->projectName?>" placeholder=""							class=""							 />					</div>				<!-- /save-fields -->				<div class="login-actions">					<button style="float:left" class="button btn btn-success" type="button" onclick="checkSaveProject()">Update</button>				</div>				<!-- .actions -->				<div>					<a href="manage_project.php"><< Back to Projects</a>				</div>								<input type="hidden" name="projectID" id="projectID" value="<?=$projectID?>"/>			</form>		</div>		<!-- /content -->	</div>

	<div class="container" style="margin:40px">

		<div class="row">

			<div class="span12">
				<br/>				<h3><?=$message?></h3>				  <input type="radio" name="show" value="active" checked onclick="showActiveCategoriesOnly()"> Show Active Report Types Only 				  <input type="radio" name="show" value="all" onclick="showDeletedCategories()"> Show All Report Types (including deleted) 
				<div class="error-container" style="margin-top:0;margin-bottom:0;text-align:left">					<?php 						if(count($allCategories) == 0)						{							?>							No Report Types exist					<?php 								}else{					?>						<br/>						<table style="padding-left:10px; border: 1px dashed black;background-color:white;width:400px">									<tr class="tr-header">								<td>Name</td>								<?php 									if($editCategories)									{								?>									<td></td>								<?php 											}if($deleteCategories)									{								?>									<td></td>								<?php 											}								?>																											</tr>					<?php 								$categoryID = -1;							if (isset ( $_GET ["categoryID"] ))							{								$categoryID =  intval ($_GET ["categoryID"]) ;												}															$count = 1;							while ( $category = $allCategories->iterate () )							{														?>							<tr							<?php 							echo " id = '".$category->categoryID."' ";							$className = "";							if($count % 2 == 0)							{								$className = "tr-even";							}else{								$className = "tr-odd";							}							if($category->categoryID == $categoryID)							{								$className = "tr-updated";							}							if($category->deleteFlag == 1)							{								echo "  style='display:none' ";								$className = "tr-deleted";							}							echo " class='".$className."'";															?>							>								<td><?=$category->categoryName?></td>								<?php 									if($editCategories)									{								?>									<td>										<button style="float:left;margin-bottom:15px" class="button btn btn-success btn-small" type="button" onclick="editCategory(<?=$category->categoryID?>)">Edit</button>									</td>								<?php 											}if($deleteCategories && $category->deleteFlag == 0)									{								?>									<td>										<button style="float:left;margin-bottom:15px" class="button btn btn-danger btn-small" type="button" onclick="deleteCategory(<?=$category->categoryID?>,'<?=str_replace("'"," ",$category->categoryName)?>')">Delete</button>									</td>								<?php 											}if($deleteCategories && $category->deleteFlag == 1)									{								?>									<td style="padding-right:15px">										<button style="float:left;margin-bottom:15px" class="button btn btn-warning btn-small" type="button" onclick="activateCategory(<?=$category->categoryID?>,'<?=str_replace("'"," ",$category->categoryName)?>')">Reactivate</button>									</td>								<?php 											}								?>																																			</tr>					<?php 											$count = $count+1;							}					?>						</table>							<?php						}					?>
				<div>					<a href="manage_project.php"><< Back to Projects</a>				</div>				</div>
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
function showDeletedCategories(){	//alert('show all');	<?php 	while ( $category = $allCategories->iterate () )	{		echo " document.getElementById('".$category->categoryID."').style.display = ''\n";	}	?>}function showActiveCategoriesOnly(){	<?php 	while ( $category = $allCategories->iterate () )	{		if($category->deleteFlag == 1)		{			echo " document.getElementById('".$category->categoryID."').style.display = 'none'\n";		}	}	?>}function checkSaveProject(){	if(document.getElementById("name").value.trim() == "")	{		alert("Please enter a Name");		return;	}		document.getElementById('submitProjectForm').submit();	}function editCategory(categoryID){	window.location = "edit_category.php?categoryID="+categoryID+"&projectID=<?=$projectID?>";}function deleteCategory(categoryID, name){	var r = confirm("Are you want to delete report type - '"+name+"'");	if (r == true) {		window.location = "submit_delete_category.php?action=delete&categoryID="+categoryID+"&projectID=<?=$projectID?>";	} }function activateCategory(categoryID, name){	var r = confirm("Are you want to reactivate report type - '"+name+"'");	if (r == true) {		window.location = "submit_delete_category.php?action=activate&categoryID="+categoryID+"&projectID=<?=$projectID?>";	} }</script>
</html>
