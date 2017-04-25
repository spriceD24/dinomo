<!DOCTYPE html>
<html lang="en">
<?php include_once("util/LogUtil.php"); ?>
<?php include_once("util/DBUtil.php"); ?>
<?php include_once("delegate/ProjectDelegate.php"); ?>
<?php include_once("util/WebUtil.php"); ?>
<?php include_once("mobile_detect/Mobile_Detect.php");?>

<?php
	$webUtil = new WebUtil ();
	$webUtil->srcPage = "edit_project.php";
	set_error_handler ( array (
			$webUtil,
			'handleError' 
	) );
	
	$detect = new Mobile_Detect ();
	$isMobile = ($detect->isMobile() && !$detect->isTablet());
	$isTablet = $detect->isTablet();
	
	$currentUser = $webUtil->getLoggedInUser();
	if(!$currentUser->hasRole('admin'))
	{
		header ( "Location: select_qa.php" );
		exit ();
	}
	
	//$categoryDAO->saveReport($category);
	$projectDelegate = new ProjectDelegate();
	$allProjects = $projectDelegate->getAllProjectsLite($currentUser->clientID);
	$editCategories = true;
	$deleteCategories = true;
	$message = "";
	if (isset ( $_GET ["message"] )) 
	{
		$message = $_GET ["message"];
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

<style>

.tr-odd td
{
	font-size:14px;
	padding-left:20px;
}

.tr-even td
{
	padding-left:20px;
	font-size:14px;
	background-color:#f5f5f0;
}

.tr-updated td
{
	padding-left:20px;
	font-size:14px;
	background-color:yellow;
}

.tr-deleted td
{
	padding-left:20px;
	font-size:14px;
	background-color:#FFA07A;
}

.tr-header td
{
	padding-left:20px;
	font-weight:bold;
	font-size:14px;
	background-color:#ccebff;
}

</style>

</head>



<body>


	<!-- /navbar -->

	<div class="account-container" style="margin:40px;width:500px">

		<div class="content clearfix" style="width:500px">

			<form action="submit_new_project.php" method="post" id="submitProjectForm" name="submitProjectForm">
					<div class="field">
						<label for="name"></label>Project Name
						<br/><input type="text" id="name"
							name="name" value="" placeholder=""
							class=""
							 />
					</div>
					<div class="field">
						<label for="name"></label>Project Details
						<br/>
				  <input type="radio" name="projectDetails" id="projectDetails" value="copy" onclick="showProjects()"> Copy QA Reports From Existing Projct (<span style="color:red">*Recommended</span>)<br/>
 				  <input type="radio" name="projectDetails" id="projectDetails" value="new" onclick="hideProjects()"> Don't copy QA Reports, I'll create myself  
					</div>
					<div class="field" id="projectFields" name="projectFields" style="display:none">
						<br/>
						<label for="name"></label>Select Project To Copy
						<br/>
						<?php 
							while ( $project = $allProjects->iterate () )
							{
							?>
				  <input type="radio" name="projectCopy" id="projectCopy" value="<?=$project->projectID?>"><?=$project->projectName?><br/>
							<?php 
							}
							?>
							<br/>
						</div>	
				<!-- /save-fields -->

				<div class="login-actions">
					<button style="float:left" class="button btn btn-success" type="button" onclick="checkSaveProject()">Save</button>
				</div>
				<!-- .actions -->
				<div>
					<a href="manage_project.php"><< Back to Projects</a>
				</div>
			</form>

		</div>
		<!-- /content -->

	</div>



	<div class="container" style="margin:40px">



	</div>

	<!-- /container -->





	<script src="js/jquery-1.7.2.min.js"></script>

	<script src="js/bootstrap.js"></script>



</body>

<script>

function checkSaveProject()
{
	if(document.getElementById("name").value.trim() == "")
	{
		alert("Please enter a Name");
		return;
	}	
	<?php 
	while ( $project = $allProjects->iterate () )
	{
	?>
	if(document.getElementById("name").value.trim() == "<?=$project->projectName?>")
	{
		alert("Project Name ["+document.getElementById("login").value.trim()+"] already exists");
		return;
	}	
	<?php 
	}
	?>
	var copyType = $('input[name="projectDetails"]:checked').val()
	if(!copyType || copyType == "")
	{
		alert('Please specify if you wany to copy existing Project or create new');
		return;
	}
	if(copyType == "copy")
	{
		var projectCopy = $('input[name="projectCopy"]:checked').val();
		if(!projectCopy || projectCopy == "")
		{
			alert('Please select Project you want to copy');
			return;
		}
	}
	document.getElementById('submitProjectForm').submit();	
}

function showProjects()
{
	//val = $('input[name="projectDetails"]:checked').val();
	document.getElementById("projectFields").style.display='';
}

function hideProjects()
{
	document.getElementById("projectFields").style.display='none';
}
</script>

</html>

