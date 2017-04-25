<!DOCTYPE html><html lang="en"><?php include_once("util/LogUtil.php"); ?><?php include_once("util/DBUtil.php"); ?><?php include_once("delegate/ProjectDelegate.php"); ?><?php include_once("util/WebUtil.php"); ?><?php include_once("mobile_detect/Mobile_Detect.php");?><?php	$webUtil = new WebUtil ();	$webUtil->srcPage = "edit_category.php";	set_error_handler ( array (			$webUtil,			'handleError' 	) );		$detect = new Mobile_Detect ();	$isMobile = ($detect->isMobile() && !$detect->isTablet());	$isTablet = $detect->isTablet();		$currentUser = $webUtil->getLoggedInUser();	if(!$currentUser->hasRole('admin'))	{		header ( "Location: select_qa.php" );		exit ();	}		//$categoryDAO->saveReport($category);	$projectDelegate = new ProjectDelegate();	$projectID = intval($_GET ["projectID"]);	$categoryID = intval($_GET ["categoryID"]);	//$allCategories = $projectDelegate->getCategoriesIncludingDeleted($projectID);	$project = $projectDelegate->getProject($projectID);	$category = $projectDelegate->getCategory($projectID,$categoryID);	$editCategories = true;	$deleteCategories = true;	$message = "";	if (isset ( $_GET ["message"] )) 	{		$message = $_GET ["message"];	}	?>
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

	</div>
	<!-- /navbar -->	<div class="account-container" style="margin:40px">		<div class="content clearfix">			<form action="submit_edit_category.php" method="post" id="submitProjectForm" name="submitProjectForm">					<div class="field">						<label for="projname"></label>Project Name						<br/><input type="text" id="projname" readonly							name="projname" value="<?=$project->projectName?>" placeholder=""							class=""							 />						<label for="name"></label>Category Name						<br/><input type="text" id="name"							name="name" value="<?=$category->categoryName?>" placeholder=""							class=""							 />					</div>				<!-- /save-fields -->				<div class="login-actions">					<button style="float:left" class="button btn btn-success" type="button" onclick="checkSaveProject()">Update</button>				</div>				<!-- .actions -->				<div>					<a href="edit_project.php?projectID=<?=$projectID?>"><< Back to Projects</a>				</div>								<input type="hidden" name="projectID" id="projectID" value="<?=$projectID?>"/>				<input type="hidden" name="categoryID" id="categoryID" value="<?=$categoryID?>"/>			</form>		</div>		<!-- /content -->	</div>

	<div class="container" style="margin:40px">

		<div class="row">

			<div class="span12">
				<br/>			</div>
			<!-- /span12 -->

		</div>
		<!-- /row -->

	</div>
	<!-- /container -->


	<script src="js/jquery-1.7.2.min.js"></script>
	<script src="js/bootstrap.js"></script>

</body><script>
function checkSaveProject(){	if(document.getElementById("name").value.trim() == "")	{		alert("Please enter a Name");		return;	}		document.getElementById('submitProjectForm').submit();	}</script>
</html>
