<?php include_once("util/ConfigUtil.php"); ?>
<?php include_once("util/WebUtil.php"); ?>
<?php include_once("dao/model/Category.php"); ?>
<?php include_once("dao/model/Project.php"); ?>
<?php include_once("delegate/ProjectDelegate.php"); ?>
<?php include_once("mobile_detect/Mobile_Detect.php");?>

<?php
    $webUtil = new WebUtil();
    $webUtil->srcPage = "select_qa.php";
    set_error_handler(array($webUtil, 'handleError'));
    
	$detect = new Mobile_Detect();
	
	$user = $webUtil->getLoggedInUser();
	//refresh the cookie
	$webUtil->addLoggedInUser($user->login, ConfigUtil::getCookieExpDays());
		
	//echo $fileUtil->getNumberOfUploadFiles()
	//echo realpath('.');
	//print_r($_SERVER);
	//$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	
	//echo $actual_link;
	//echo "$_SERVER[HTTP_HOST]";
	$projectDelegate = new ProjectDelegate ();
	$projects = $projectDelegate->getAllProjectsLite();
	$mobileDropDownStyle = ConfigUtil::getMobileDropDownStyle();
	
?>


<html>
<head>

<html lang="en">
  
<head>
    <meta charset="utf-8">
    <title>Dinomo QA</title>

	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes"> 
    
	<script src="js/jquery-1.7.2.min.js"></script>
	<script src="js/bootstrap.js"></script>
	<script src="js/base.js"></script>
	
	
	
	<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
	
	<link href="css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" type="text/css" href="css/dinamo.css">
	
	<link href="css/font-awesome.css" rel="stylesheet">
	    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600" rel="stylesheet">
	
	<link href="css/style.css" rel="stylesheet" type="text/css">
	
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
	}?>
	
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
	document.getElementById('projectID').selectedIndex = 0;
	<?php 
	while ( $project = $projects->iterate () ) 
	
	{							
				?>
	document.getElementById('project_<?=$project->projectID?>').selectedIndex=0;		
	try{
		$(".modal").hide();
	}catch(e){}						
				<?php 
	}?>
	
};

</script>	


<style>
	.modal
	{
		position: fixed;
		z-index: 999;
		height: 100%;
		width: 100%;
		top: 0;
		left: 0;
		background-color: Black;
		filter: alpha(opacity=60);
		opacity: 0.6;
		-moz-opacity: 0.8;
	}
	.center
	{
		z-index: 1000;
		margin: 300px auto;
		padding: 10px;
		width: 330px;
		background-color: White;
		border-radius: 10px;
		filter: alpha(opacity=100);
		opacity: 1;
		-moz-opacity: 1;
	}
	.center img
	{
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
			
</div> <!-- /navbar -->



<div class="account-container">
	
	<div class="content clearfix">
				
			<h3>Select QA Project/Report Type</h3>		
			
			<div class="login-fields">
				<p></p>
				<table style="font-size:14px; border 1px solid;padding-top:10px;padding-bottom:10px;width:100%">
					<tr>
						<td style="font-weight:bold;width:50%;padding-bottom:10px">Project:</td>
						<td>
							<select name="projectID" id="projectID" onchange="setCategoryDropdown()" <?php 
																if ($detect->isMobile() && !$detect->isTablet())
																{
																	print " style='".$mobileDropDownStyle."'";
																}
																?>>
								<option value="" selected></option>
							<?php 
							while ( $project = $projects->iterate () ) 
							
							{							
							?>
								<option value="<?=$project->projectID?>"><?=$project->projectName?></option>
							<?php 		
							}
							?>
							</select>
						</td>
					</tr>
							<?php 
							while ( $project = $projects->iterate () ) 
							
							{							
							?>
								<tr id="project_row_<?=$project->projectID?>" style="display:none">
									<td style="font-weight:bold;width:50%;padding-bottom:10px">Report Type:</td>
									<td align="right">
								<select name="project_<?=$project->projectID?>" id="project_<?=$project->projectID?>" onchange="selectCategory(<?=$project->projectID?>)" <?php 
																if ($detect->isMobile() && !$detect->isTablet())
																{
																	print " style='".$mobileDropDownStyle."'";
																}
																?>>
								<option value="" selected></option>
								<?php 
									while ( $category = $project->categories->iterate () ) 
									
									{							
									?>
										<option value="<?=$category->categoryID?>"><?=$category->categoryName?></option>
									<?php 		
									}
									?>		
									</select>			
									</td>
								</tr>
								
							<?php 		
							}
							?>					
				</table>
				<p></p>
				
			</div> <!-- /login-fields -->

	<div class="modal" style="display: none">
		<div class="center">
			<img alt="" src="img/loader.gif" />
		</div>
	</div>
				
	</div> <!-- /content -->
	
</div> <!-- /account-container -->


</body>

</html>
</html>