<?php include_once("util/ConfigUtil.php"); ?>
<?php include_once("util/WebUtil.php"); ?>
<?php include_once("dao/model/Category.php"); ?>
<?php include_once("dao/model/Project.php"); ?>
<?php include_once("delegate/ProjectDelegate.php"); ?>
<?php include_once("mobile_detect/Mobile_Detect.php");?>

<?php
$webUtil = new WebUtil ();
$webUtil->srcPage = "select_qa.php";
set_error_handler ( array (
		$webUtil,
		'handleError' 
) );

$detect = new Mobile_Detect ();

$user = $webUtil->getLoggedInUser ();
// refresh the cookie
$webUtil->addLoggedInUser ( $user, ConfigUtil::getCookieExpDays () );


// echo $fileUtil->getNumberOfUploadFiles()
// echo realpath('.');
// print_r($_SERVER);
// $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

// echo $actual_link;
// echo "$_SERVER[HTTP_HOST]";
$projectDelegate = new ProjectDelegate ();
$projects = $projectDelegate->getAllProjectsLite ();
$mobileDropDownStyle = ConfigUtil::getMobileDropDownStyle ();


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
<meta name="apple-phone-web-app-capable" content="yes">

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
<?php 
	}
	else if($isTablet && !$isMobile)
	{
?>
	<link href="css/bootstrap-tablet.min.css" rel="stylesheet" type="text/css" />
	<link href="css/bootstrap-responsive-tablet.min.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" type="text/css" href="css/dinamo-tablet.css">
	<link href="css/style-tablet.css" rel="stylesheet" type="text/css">
<?php 
	}else{		
?>
	<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
	<link href="css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" type="text/css" href="css/dinamo.css">
	<link href="css/style.css" rel="stylesheet" type="text/css">
<?php 
	}
?>
	
<link href="css/font-awesome.css" rel="stylesheet">
<link
	href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600"
	rel="stylesheet">

<link href="css/pages/signin.css" rel="stylesheet" type="text/css">


<script>


function getSelectedVaue(id)
{
	var e = document.getElementById(id);
	return e.options[e.selectedIndex].value;
}

function setCategoryDropdown()
{
	hideAllCategories();
	var selected = getSelectedVaue('projectID');
	if(selected && selected != '')
	{
		document.getElementById('project_row_'+selected).style.display='';
	}
}

function hideAllCategories()
{
	<?php
	while ( $project = $projects->iterate () ) 

	{
		?>
	document.getElementById('project_row_<?=$project->projectID?>').style.display='none';								
				<?php
	}
	?>
	
}

function selectCategory(projectID)
{
	var selected = getSelectedVaue('project_'+projectID);
	try{
		$('.modal').css('margin',0);
		$(".modal").show();
	}catch(e){}
	window.location="upload_qa.php?projectID="+projectID+"&categoryID="+selected;
}

window.onload = function() {

	clearData();
	
};

function clearData()
{
	document.getElementById('projectID').selectedIndex = 0;
	<?php
	while ( $project = $projects->iterate () ) 
	
	{
		?>
	document.getElementById('project_<?=$project->projectID?>').selectedIndex=0;		
	try{
		$('.projectRow').hide();
		$(".modal").hide();
	}catch(e){}						
				<?php
	}
	?>
}

$(window).bind("pageshow", function(event) {
    if (event.originalEvent.persisted) {
    	clearData(); 
    }
});

</script>


<style>
.modal {
	position: fixed;
	z-index: 999;
	height: 100%;
	width: 100%;
	top: 0;
	left: 0;
	background-color: Black;
	filter: alpha(opacity = 60);
	opacity: 0.6;
	-moz-opacity: 0.8;
}

.center {
	z-index: 1000;
	margin: 300px auto;
	padding: 10px;
	width: 330px;
	background-color: White;
	border-radius: 10px;
	filter: alpha(opacity = 100);
	opacity: 1;
	-moz-opacity: 1;
}

.center img {
	height: 128px;
	width: 128px;
}
</style>


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
							style="padding: 0px 0px 0px 0px" class=""><u>View All Submitted QA Reports</u>
						</a></li>
					</ul>
				</div>

				<div class="nav-collapse">

					<ul class="nav pull-right">

						<li class="" style="float: none"></li>

						<li class="" style="padding-top: 30px"><span
							style="color: white;">User: <?=$user->name?> (<span
								style="font-style: italic"><a href="logout.php" class="controls-href">logout</a></span>)
						</span></li>


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

			<h3>Submit QA Report</h3>

			<div class="login-fields">
				<p></p>
				<table
					style="font-size: 14px; border 1px solid; padding-top: 10px; padding-bottom: 10px; width: 100%">
					<tr>
						<td style="font-weight: bold; width: 50%; padding-bottom: 10px" class="label-display">Project:</td>
						<td><select name="projectID" id="projectID"
							onchange="setCategoryDropdown()"
							<?php
							if ($isMobile && ! $isTablet) {
								print " style='" . $mobileDropDownStyle . "'";
							}
							?>
							class="controls-select">
								<option value="" selected></option>
							<?php
							while ( $project = $projects->iterate () ) 

							{
								?>
								<option value="<?=$project->projectID?>"><?=$project->projectName?></option>
							<?php
							}
							?>
							</select></td>
					</tr>
							<?php
							while ( $project = $projects->iterate () ) 

							{
								?>
								<tr id="project_row_<?=$project->projectID?>"
						style="display: none" class="projectRow controls-select">
						<td style="font-weight: bold; width: 50%; padding-bottom: 10px" class="label-display">Report
							Type:</td>
						<td align="right"><select name="project_<?=$project->projectID?>"
							id="project_<?=$project->projectID?>"
							onchange="selectCategory(<?=$project->projectID?>)"
							<?php
								if ($isMobile && ! $isTablet) {
									print " style='" . $mobileDropDownStyle . "'";
								}
								?> class="controls-select">
								<option value="" selected></option>
								<?php
								while ( $category = $project->categories->iterate () ) 

								{
									?>
										<option value="<?=$category->categoryID?>"><?=$category->categoryName?></option>
									<?php
								}
								?>		
									</select></td>
					</tr>
								
							<?php
							}
							?>					
				</table>
				<p></p>

			</div>
			<!-- /login-fields -->

			<div class="modal" style="display: none">
				<div class="center">
					<img alt="" src="img/loader.gif" />
				</div>
			</div>

		</div>
		<!-- /content -->

	</div>
	<!-- /account-container -->


</body>

</html>

