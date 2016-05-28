<?php include_once("util/WebUtil.php"); ?>
<?php include_once("util/HTMLUtil.php"); ?>
<?php include_once("util/HTMLConst.php"); ?>
<?php include_once("util/StringUtils.php"); ?>
<?php include_once("util/ConfigUtil.php"); ?>
<?php include_once("util/LogUtil.php"); ?>
<?php include_once("util/CollectionsUtil.php"); ?>
<?php include_once("dao/model/Category.php"); ?>
<?php include_once("dao/model/CategoryOption.php"); ?>
<?php include_once("dao/model/Project.php"); ?>
<?php include_once("delegate/ProjectDelegate.php"); ?>
<?php include_once("dao/model/User.php"); ?>
<?php include_once("delegate/UserDelegate.php"); ?>
<?php include_once("mobile_detect/Mobile_Detect.php");?>

<?php

$webUtil = new WebUtil ();
$webUtil->srcPage = "upload_qa.php";
// set_error_handler(array($webUtil, 'handleError'));

$currentUser = $webUtil->getLoggedInUser ();

$htmlUtil = new HTMLUtil ();
$stringUtils = new StringUtils ();
$detect = new Mobile_Detect ();

$setCurrentProjectID = intval ( $_GET ["projectID"] );
$setCurrentCategoryID = intval ( $_GET ["categoryID"] );

LogUtil::debug ( "upload_qa", "Opening details for project ID = " . $setCurrentProjectID . ", category id = " . $setCurrentCategoryID . ", user = " . $currentUser->login );

if (empty ( $setCurrentProjectID ) || empty ( $setCurrentCategoryID )) {
	header ( "Location: select_qa.php" );
	exit ();
}


$isMobile = ($detect->isMobile() && !$detect->isTablet());
$isTablet = $detect->isTablet();

if (isset ( $_GET ["isMobile"] )) {
	$isMobile = ( $_GET ["isMobile"] == "true");
}
if (isset ( $_GET ["isTablet"] )) {
	$isTablet = ( $_GET ["isTablet"] == "true");
}

$setOptionPrefix = HTMLConst::STANDARD_OPT_ID_PREFIX;

// get project info

$projectDelegate = new ProjectDelegate ();

$project = $projectDelegate->getProject ( $setCurrentProjectID );
$currentCategory = $projectDelegate->getCategory ( $setCurrentProjectID, $setCurrentCategoryID );
$categoryOptions = $projectDelegate->getCategoryOptions ( $setCurrentProjectID, $setCurrentCategoryID );

LogUtil::debug ( "upload_qa", "Opening ALL details for project = " . $project->projectName . ", category = " . $currentCategory->categoryName . ", num options = " . $categoryOptions->getNumObjects () . ", user = " . $currentUser->login );

// get user info
$mobileTextStyle = ConfigUtil::getMobileTextStyle ();
$mobileLabelStyle = ConfigUtil::getMobileLabelStyle ();
$mobileTextAreaStyle = ConfigUtil::getMobileTextAreaStyle ();
$mobileDropDownStyle = ConfigUtil::getMobileDropDownStyle ();

$userDelegate = new UserDelegate ();
$users = $userDelegate->getAllUsers ();

$num_images = ConfigUtil::getNumberOfUploadFiles ();
?>



<!DOCTYPE html>

<html lang="en">



<head>

<meta charset="utf-8">

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

<script>
//submitForm();

function clearErrorDiv(id)
{
	//alert('clear - '+id);
	document.getElementById('div_'+id).style.border='0px solid white';
	document.getElementById('div_'+id).style.backgroundColor ='white';
	document.getElementById('error_'+id).style.display ='none';
	
}


function clearErrorDivText(id)
{
	var val = document.getElementById(id).value;
	if(val && val != '')
	{
		clearErrorDiv(id);
	}else{
		setErrorDiv(id);
	}
}

function clearErrorDivDrop(id)
{
	var val = getSelectedVaue(id);
	if(val && val != '')
	{
		clearErrorDiv(id);
	}else{
		setErrorDiv(id);
	}
}

function clearErrorDivCheck(id)
{
	var val = document.getElementById(id).checked;
	if(val && val == true)
	{
		clearErrorDiv(id);
	}else{
		setErrorDiv(id);
	}
}

function setErrorDiv(id)
{
	document.getElementById('div_'+id).style.border='1px solid red';
	document.getElementById('div_'+id).style.backgroundColor ='#FBFB4F';
	document.getElementById('error_'+id).style.display ='';
	
	//alert('set - '+id);
}

function showNextDate(id)
{
	document.getElementById(id).style.display ='';
}

function getSelectedVaue(id)
{
	var e = document.getElementById(id);
	return e.options[e.selectedIndex].value;
}

function setDate(id)
{
	var day = getSelectedVaue("day_"+id);
	var month = getSelectedVaue("month_"+id);
	var year = parseInt(getSelectedVaue("year_"+id));

	var time = getSelectedVaue("time_"+id);
	document.getElementById(id).value = day+"-"+month+"-"+year+" "+time;
	validDate(id);
}

function checkTime(id)
{
	var idx = document.getElementById(id).selectedIndex;
	if(idx == 0)
	{
		setErrorDiv(id);
	}
	else{
		clearErrorDiv(id);
	}
	
}


function validDate(id)
{
	var day =  parseInt(getSelectedVaue("day_"+id));
	var month = getSelectedVaue("month_"+id);
	var year = parseInt(getSelectedVaue("year_"+id));
	if(day > 30
			&& (month == 'Sep' || month == 'Apr' || month == 'Jun' ||  month == 'Nov' ||  month == 'Feb'))
	{
		setErrorDiv(id);
		return false;		
	}
	if(day > 28	&& month == 'Feb' && !leapYear(year))
	{
		setErrorDiv(id);
		return false;		
	}		 
	clearErrorDiv(id);
	return true;
}

function leapYear(year)
{
	var isLeap = new Date(year, 1, 29).getMonth() == 1;
	;
	return isLeap;
}

function submitForm()
{
	var selectedRadio = 0;
	var selectedVal= '';
	var errorAnchor = '';
	var checkVal = false;
	var hasError = false;
	var hasError = false;
	<?php
	
	while ( $categoryOption = $categoryOptions->iterate () ) 

	{
		
		if ($categoryOption->isRequired) 

		{
			if ($categoryOption->formType == 'RADIO') 

			{
				?>	
			selectedRadio = $("input[name=<?=$setOptionPrefix.$categoryOption->categoryOptionID?>]:checked").length;
	 		if(selectedRadio == 0)
	 		{
		 		//alert('Select - <?=$categoryOption->title?>');
		 		if(errorAnchor == '')
		 		{
		 			errorAnchor = 'anchor_<?=$setOptionPrefix.$categoryOption->categoryOptionID?>';	
		 			hasError = true;
		 		}
		 		setErrorDiv('<?=$setOptionPrefix.$categoryOption->categoryOptionID?>')
		 		//return false;
	 		}
 		<?php
			}
			if ($categoryOption->formType == 'TEXT') 

			{
				?>
			selectedVal = document.getElementById("<?=$setOptionPrefix.$categoryOption->categoryOptionID?>").value;
	 		if(!selectedVal || selectedVal == '')
	 		{
		 		//alert('Select - <?=$categoryOption->title?>');
		 		if(errorAnchor == '')
		 		{
		 			errorAnchor = 'anchor_<?=$setOptionPrefix.$categoryOption->categoryOptionID?>';	
		 			hasError = true;
		 		}
		 		setErrorDiv('<?=$setOptionPrefix.$categoryOption->categoryOptionID?>')
		 		//return false;
	 		}
 		<?php
			}
			if ($categoryOption->formType == 'TEXTAREA') 

			{
				?>
			selectedVal = document.getElementById("<?=$setOptionPrefix.$categoryOption->categoryOptionID?>").value;
	 		if(!selectedVal || selectedVal == '')
	 		{
		 		//alert('Select - <?=$categoryOption->title?>');
		 		setErrorDiv('<?=$setOptionPrefix.$categoryOption->categoryOptionID?>')
		 		if(errorAnchor == '')
		 		{
		 			errorAnchor = 'anchor_<?=$setOptionPrefix.$categoryOption->categoryOptionID?>';	
		 			hasError = true;
		 		}
		 		//return false;
	 		}
 		<?php
			}
			if ($categoryOption->formType == 'CONFIRM') 

			{
				?>
			checkedVal = document.getElementById("<?=$setOptionPrefix.$categoryOption->categoryOptionID?>").checked;
	 		if(!checkedVal || checkedVal == false)
	 		{
		 		//alert('Select - <?=$categoryOption->title?>');
		 		setErrorDiv('<?=$setOptionPrefix.$categoryOption->categoryOptionID?>')
		 		if(errorAnchor == '')
		 		{
		 			errorAnchor = 'anchor_<?=$setOptionPrefix.$categoryOption->categoryOptionID?>';	
		 			hasError = true;
		 		}
		 		//return false;
	 		}
 		<?php
			}
			if ($categoryOption->formType == 'DATETIME' || $categoryOption->formType == 'DATE') 

			{
				?>
			
	 		if(!validDate('<?=$setOptionPrefix.$categoryOption->categoryOptionID?>'))
	 		{
		 		//alert('Select - <?=$categoryOption->title?>');
		 		if(errorAnchor == '')
		 		{
		 			errorAnchor = 'anchor_<?=$setOptionPrefix.$categoryOption->categoryOptionID?>';	
		 			hasError = true;
		 		}
		 		//return false;
	 		}
 		<?php
			}
			if ($categoryOption->formType == 'TIME') 

			{
				?>
			checkedVal = document.getElementById("<?=$setOptionPrefix.$categoryOption->categoryOptionID?>").selectedIndex;
	 		if(!checkedVal || checkedVal == 0)
	 		{
		 		//alert('Select - <?=$categoryOption->title?>');
		 		setErrorDiv('<?=$setOptionPrefix.$categoryOption->categoryOptionID?>')
		 		if(errorAnchor == '')
		 		{
		 			errorAnchor = 'anchor_<?=$setOptionPrefix.$categoryOption->categoryOptionID?>';	
		 			hasError = true;
		 		}
		 		//return false;
	 		}
 		<?php
			}
			if ($categoryOption->formType == 'USERLIST')
			{
				?>
	
				checkedVal = document.getElementById("<?=$setOptionPrefix.$categoryOption->categoryOptionID?>").selectedIndex;
	
		 		if(!checkedVal || checkedVal == 0)
	
		 		{
	
			 		//alert('Select - <?=$categoryOption->title?>');
			 		setErrorDiv('<?=$setOptionPrefix.$categoryOption->categoryOptionID?>')
			 		if(errorAnchor == '')
			 		{
			 			errorAnchor = 'anchor_<?=$setOptionPrefix.$categoryOption->categoryOptionID?>';	
			 			hasError = true;
			 		}
			 		//return false;
		 		}
	 		<?php
			}			
		}
	}
	
	?>
		//alert(errorAnchor);
	if(errorAnchor != '')
	{
		window.location.href = "#"+errorAnchor;
	}		

	if(!hasError)
	{
		//submit form
		try{
			$('.modal').css('margin',0);
			$('.modal').show();
		}catch(e){}
		//document.getElementById("submitSpan").style.display='';
		//$("#id").css("display", "none");
		$('#submitButton').attr("disabled", true); 
		$('#submitButton').prop("disabled", true); 
		$('#uploadform').submit();
	}	 	
}

//Extra Javascript goes here

$(window).bind("pageshow", function(event) {
    if (event.originalEvent.persisted) {
    	$('.modal').hide();
    	$('#submitButton').attr("disabled", false); 
		$('#submitButton').prop("disabled",false);
    }
});

//Extra Javascript ENDS here
window.onload = function() {
	try{
		//$(".modal").hide();
		document.getElementById("submitSpan").style.display='none';
	}catch(e){}						
};

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

					<ul class="nav pull-right">

						<li class="" style="float: none"><span
							style="color: white; ">User: <?=$currentUser->name?> (<span
								style="font-style: italic"><a href="logout.php">logout</a></span>)
						</span></li>

						<li class="" style="padding-top: 10px"><a href="select_qa.php"
							style="padding: 0px 0px 0px 0px" class=""> <i
								class="icon-chevron-left"></i> Select Different Project/QA Report

						</a></li>

					</ul>



				</div>
				<!--/.nav-collapse -->



			</div>
			<!-- /container -->



		</div>
		<!-- /navbar-inner -->



	</div>



	<div class="main">



		<div class="main-inner">
			<div class="container">
				<div class="row">
					<div class="span12">
						<div class="widget ">
							<div class="widget-header">
								<i class="icon-list-alt"></i>
								<h3 style="font-size: 20px"><?=$project->projectName ?></h3>
							</div>
							<!-- /widget-header -->


							<div class="widget-content">
								<div class="tabbable">
									<ul class="nav nav-tabs">
										<li class="active label-display"><a
											href="#<?='cat_'.$currentCategory->categoryID?>"
											data-toggle="tab"><?=$currentCategory->categoryName?></a></li>
									</ul>



									<br>

									<div class="tab-content">
										<div class="tab-pane active"
											id="<?='cat_'.$currentCategory->categoryID?>">

											<form action="submit_qa.php" id="uploadform"
												name="uploadform" method="post" class="form-horizontal"
												enctype="multipart/form-data">

												<!-- standard data -->
												<input type="hidden" name="uploadedBy" id="uploadedBy"
													value="<?=$currentUser->userID?>" /> <input type="hidden"
													name="projectID" id="projectID"
													value="<?=$setCurrentProjectID?>" /> <input type="hidden"
													name="categoryID" id="categoryID"
													value="<?=$setCurrentCategoryID?>" />

												<fieldset>

										

									<?php
									
									while ( $categoryOption = $categoryOptions->iterate () ) {
										
										if ($categoryOption->formType == 'TEXT') 

										{
											
											?>

									

											<a
														name="anchor_<?=$setOptionPrefix.$categoryOption->categoryOptionID;?>"></a>
													<div class="control-group"
														id="div_<?= $setOptionPrefix.$categoryOption->categoryOptionID;?>">

														<label class="control-label"
															for="<?=$setOptionPrefix.$categoryOption->categoryOptionID;?>">
															<?=$categoryOption->title;?>
												<div
																id="error_<?=$setOptionPrefix.$categoryOption->categoryOptionID;?>"
																class="errorLabel" style="display: none">* Required
																Field</div>
														</label>

														<div class="controls">

															<input type="text" class="span6 disabled"
																id="<?=$setOptionPrefix.$categoryOption->categoryOptionID;?>"
																name="<?=$setOptionPrefix.$categoryOption->categoryOptionID;?>"
																value=""
																<?php
											
if ($categoryOption->isRequired) {
												print ' onkeyup="clearErrorDivText(\'' . $setOptionPrefix . $categoryOption->categoryOptionID . '\')" ';
											}
											?>
																<?php
											if ((! $isMobile || $isTablet) && ! empty ( $categoryOption->styleClass )) {
												print " style='$categoryOption->styleClass'";
											}
											
											if ($isMobile && ! $isTablet) {
												print " style='" . $mobileTextStyle . "'";
											}
											
											?>>

														</div>

													</div>

											<?php
										}
										if ($categoryOption->formType == 'LABEL_AREA') 

										{
											
											?>
								<div class="control-group"
														id="div_<?= $setOptionPrefix.$categoryOption->categoryOptionID;?>">
														<input type="hidden"
															id="<?=$setOptionPrefix.$categoryOption->categoryOptionID?>"
															name="<?=$setOptionPrefix.$categoryOption->categoryOptionID?>"
															value="<?=$categoryOption->title;?>" /> <label
															class="control-label"> </label>

														<div class="controls">
															<hr />
															<span style="font-size: 15px"><?=$categoryOption->title;?></span>
															<hr />
														</div>

													</div>

								<?php
										}
										
										if ($categoryOption->formType == 'RADIO') 

										{
											
											?>

										<a
														name="anchor_<?=$setOptionPrefix.$categoryOption->categoryOptionID;?>"></a>
													<div class="control-group"
														id="div_<?= $setOptionPrefix.$categoryOption->categoryOptionID;?>">

														<div class="controls"
															<?php
											if ($isMobile && ! $isTablet) {
												print " style='" . $mobileLabelStyle . "'";
											} else {
												print 'style="font-weight: bold"';
											}
											?>>
												<?=$categoryOption->title;?>
												</div>

														<label class="control-label"><div
																id="error_<?=$setOptionPrefix.$categoryOption->categoryOptionID;?>"
																class="errorLabel" style="display: none">* Required
																Field</div> </label>

														<div class="controls">

												<?php
											
											$radioOptions = $stringUtils->commaSeperatedValuesToArray ( $categoryOption->formOptions );
											
											foreach ( $radioOptions as $radioOption ) {
												
												?>

													<label class="radio inline"> <input type="radio"
																name="<?=$setOptionPrefix.$categoryOption->categoryOptionID;?>"
																id="<?= $setOptionPrefix.$categoryOption->categoryOptionID;?>"
																value="<?=$radioOption?>"
																onchange="clearErrorDiv('<?= $setOptionPrefix.$categoryOption->categoryOptionID;?>')"> <?=$radioOption;?>
													</label>

												<?php
											}
											
											?>

											  </div>

													</div>

											<?php
										}
										
										if ($categoryOption->formType == 'TEXTAREA') 

										{
											
											?>

															<a
														name="anchor_<?=$setOptionPrefix.$categoryOption->categoryOptionID;?>"></a>
													<div class="control-group"
														id="div_<?= $setOptionPrefix.$categoryOption->categoryOptionID;?>">

														<label class="control-label"
															for="<?=$setOptionPrefix.$categoryOption->categoryOptionID;?>">
															<?=$categoryOption->title;?>														
												<div
																id="error_<?=$setOptionPrefix.$categoryOption->categoryOptionID;?>"
																class="errorLabel" style="display: none">* Required
																Field</div>
														</label>

														<div class="controls">

															<textarea class="controls-textarea"
																id="<?=$setOptionPrefix.$categoryOption->categoryOptionID;?>"
																name="<?=$setOptionPrefix.$categoryOption->categoryOptionID;?>"


																<?php
											if (! empty ( $categoryOption->styleClass )) {
												print " style='$categoryOption->styleClass'";
											}else{
												print " rows=5 ";
											}
if ($categoryOption->isRequired) {
												print ' onkeyup="clearErrorDivText(\'' . $setOptionPrefix . $categoryOption->categoryOptionID . '\')" ';
											}
											if ($isMobile && !$isTablet) {
												print " style='" . $mobileLabelStyle . "'";
											}
											?>></textarea>

														</div>

													</div>

											<?php
										}
										
										if ($categoryOption->formType == 'USERLIST') 
										{
											?>
												<a
											name="anchor_<?=$setOptionPrefix.$categoryOption->categoryOptionID;?>"></a>
								<div class="control-group" id="div_<?= $setOptionPrefix.$categoryOption->categoryOptionID;?>">
											<label class="control-label"
												for="<?=$setOptionPrefix.$categoryOption->categoryOptionID;?>">
												<?=$categoryOption->title;?>														

									<div
											id="error_<?=$setOptionPrefix.$categoryOption->categoryOptionID;?>"
													class="errorLabel" style="display: none">* Required
													Field</div>
											</label>
											<div class="controls">
												<select
													id="<?=$setOptionPrefix.$categoryOption->categoryOptionID;?>"
													name="<?=$setOptionPrefix.$categoryOption->categoryOptionID;?>"
												<?php
												if ($isMobile && ! $isTablet) {
													print " style='" . $mobileDropDownStyle . "'";
												}
												if ($categoryOption->isRequired) {
													print ' onchange="clearErrorDivDrop(\'' . $setOptionPrefix . $categoryOption->categoryOptionID . '\')" ';
												}
												?>
													class="controls-select">

													<option value=""></option>
													<?php
											
											while ( $user = $users->iterate () ) 
											{
												if(!$user->hasRole('user'))
												{
														continue;
												}
												?>

														<option value="<?=$user->userID;?>"
																	<?php
												
												if ($user->userID == $currentUser->userID) 

												{
													
													print 'selected';
												}
												?>><?=$user->name;?>

														</option>

												<?php
											}
											?>

													</select>

														</div>

													</div>

											<?php
										}
										
										if ($categoryOption->formType == 'USERLIST_OPEN') 

										{
											
											?>

											<div class="control-group">

														<label class="control-label"
															for="<?=$setOptionPrefix.$categoryOption->categoryOptionID;?>"><?=$categoryOption->title;?></label>

														<div class="controls">

															<select
																id="<?=$setOptionPrefix.$categoryOption->categoryOptionID;?>"
																name="<?=$setOptionPrefix.$categoryOption->categoryOptionID;?>"
																<?php
											
if ($isMobile && ! $isTablet) {
												print " style='" . $mobileDropDownStyle . "'";
											}
											?>
												class="controls-select">

																<option value=""></option>

													<?php
											
											while ( $user = $users->iterate () ) {
												
												?>

														<option value="<?=$user->userID;?>" <?=$user->name;?>></option>

												<?php
											}
											?>

													</select>

														</div>

													</div>

											<?php
										}
										
										if ($categoryOption->formType == 'CONFIRM') 

										{
											
											?>

											

 									<a
														name="anchor_<?=$setOptionPrefix.$categoryOption->categoryOptionID;?>"></a>
													<div class="control-group"
														id="div_<?= $setOptionPrefix.$categoryOption->categoryOptionID;?>">

														<label class="control-label">
															<div
																id="error_<?=$setOptionPrefix.$categoryOption->categoryOptionID;?>"
																class="errorLabel" style="display: none">* Required
																Field</div>
														</label>



														<div class="controls"
															<?php
											if ($isMobile && ! $isTablet) {
												print " style='" . $mobileLabelStyle . "'";
											}
											?>>

															<label class="checkbox inline"> <input type="checkbox"
																id="<?=$setOptionPrefix.$categoryOption->categoryOptionID;?>"
																name="<?=$setOptionPrefix.$categoryOption->categoryOptionID;?>"
																<?php
if ($categoryOption->isRequired) {
												print ' onclick="clearErrorDivCheck(\'' . $setOptionPrefix . $categoryOption->categoryOptionID . '\')" ';
											}
											?>> <?=$categoryOption->title;?>

                                            </label>

														</div>

													</div> 

											

											<?php
										}
										
										if ($categoryOption->formType == 'PHOTOS') 

										{
											
											for($x = 1; $x <= $num_images; $x ++) {
												
												?>

											

											<div class="control-group"
														id="file_display_<?=$setOptionPrefix.$categoryOption->categoryOptionID.'_'.$x?>"
														name="file_display_<?=$setOptionPrefix.$categoryOption->categoryOptionID.'_'.$x?>"
														<?php
												
												if ($x > 1) 

												{
													
													print " style='display:none'";
												}
												
												?>>

														<label class="control-label"
															for="file_<?=$setOptionPrefix.$categoryOption->categoryOptionID.'_'.$x?>"><?php
												
												if ($x > 1) 

												{
													
													print "Photo " . $x;
												} else {
													
													print "Photo";
												}
												
												?>
										&nbsp;<i class="icon-camera"></i> </label>

														<div class="controls">

															<input type="file"
																id="file_<?=$setOptionPrefix.$categoryOption->categoryOptionID.'_'.$x;?>"
																name="file_<?=$setOptionPrefix.$categoryOption->categoryOptionID.'_'.$x;?>"
																placeholder="Select Photo" style="width: 250px"
																<?php
												if ($x < $num_images) {
													print ' onchange="showNextDate(\'file_display_' . $setOptionPrefix . $categoryOption->categoryOptionID . '_' . ($x + 1) . '\')" ';
												}
												?> />

														</div>

													</div>

											

											<?php
											}
										}
										
										if ($categoryOption->formType == 'DATETIME') 

										{
											
											?>
								
											<a
														name="anchor_<?=$setOptionPrefix.$categoryOption->categoryOptionID;?>"></a>
													<div class="control-group"
														id="div_<?= $setOptionPrefix.$categoryOption->categoryOptionID;?>">

														<label class="control-label"
															for="<?=$setOptionPrefix.$categoryOption->categoryOptionID;?>"><?=$categoryOption->title;?>
												<div
																id="error_<?=$setOptionPrefix.$categoryOption->categoryOptionID;?>"
																class="errorLabel" style="display: none">* Invalid Date
															</div>															
															<?php
											$day = date ( "j" );
											$month = date ( "M" );
											$year = date ( 'Y' );
											?>
															</label> <input type="hidden"
															id="<?=$setOptionPrefix.$categoryOption->categoryOptionID?>"
															name="<?=$setOptionPrefix.$categoryOption->categoryOptionID?>"
															value="<?=$day.'-'.$month.'-'.$year?>" />
														<div class="controls">
														<?php
											$dayDrop = $htmlUtil->getDayDropdown ( $setOptionPrefix . $categoryOption->categoryOptionID );
											$monthDrop = $htmlUtil->getMonthDropdown ( $setOptionPrefix . $categoryOption->categoryOptionID );
											$yearDrop = $htmlUtil->getYearDropdown ( $setOptionPrefix . $categoryOption->categoryOptionID );
											
											print $dayDrop . "&nbsp;" . $monthDrop . "&nbsp;" . $yearDrop;
											?>	
															<span style="padding-left: 15px">Time:</span>	

													<?php
											
											$timeDrop = $htmlUtil->getTimeDropdown ( $setOptionPrefix . $categoryOption->categoryOptionID );
											
											print $timeDrop;
											
											?>

												</div>
													</div>

											

											<?php
										}
										
										if ($categoryOption->formType == 'DATE') 

										{
											
											?>

											
											<a
														name="anchor_<?=$setOptionPrefix.$categoryOption->categoryOptionID;?>"></a>
													<div class="control-group"
														id="div_<?= $setOptionPrefix.$categoryOption->categoryOptionID;?>">

														<label class="control-label"
															for="<?=$setOptionPrefix.$categoryOption->categoryOptionID;?>"><?=$categoryOption->title;?>
												<div
																id="error_<?=$setOptionPrefix.$categoryOption->categoryOptionID;?>"
																class="errorLabel" style="display: none">* Invalid Date
															</div>															
															<?php
											$day = date ( "j" );
											$month = date ( "M" );
											$year = date ( 'Y' );
											?>
															</label> <input type="hidden"
															id="<?=$setOptionPrefix.$categoryOption->categoryOptionID?>"
															name="<?=$setOptionPrefix.$categoryOption->categoryOptionID?>"
															value="<?=$day.'-'.$month.'-'.$year?>" />
														<div class="controls">
														<?php
											$dayDrop = $htmlUtil->getDayDropdown ( $setOptionPrefix . $categoryOption->categoryOptionID );
											$monthDrop = $htmlUtil->getMonthDropdown ( $setOptionPrefix . $categoryOption->categoryOptionID );
											$yearDrop = $htmlUtil->getYearDropdown ( $setOptionPrefix . $categoryOption->categoryOptionID );
											
											print $dayDrop . "&nbsp;" . $monthDrop . "&nbsp;" . $yearDrop;
											?>	
	

												</div>
													</div>

											<?php
										}
										
										if ($categoryOption->formType == 'TIME') 

										{
											
											?>
								
											<a
														name="anchor_<?=$setOptionPrefix.$categoryOption->categoryOptionID;?>"></a>
													<div class="control-group"
														id="div_<?= $setOptionPrefix.$categoryOption->categoryOptionID;?>">

														<label class="control-label"
															for="<?=$setOptionPrefix.$categoryOption->categoryOptionID;?>"><?=$categoryOption->title;?>
												<div
																id="error_<?=$setOptionPrefix.$categoryOption->categoryOptionID;?>"
																class="errorLabel" style="display: none">* Required
																Field</div> </label>
														<div class="controls">
													<?php
											
											$timeDrop = $htmlUtil->getTimeDropdownOnly ( $setOptionPrefix . $categoryOption->categoryOptionID );
											
											print $timeDrop;
											
											?>

												</div>
													</div>

											

											<?php
										}
										
										if ($categoryOption->formType == 'LABEL') 

										{
											
											?>
											<div class="control-group"
														id="div_<?= $setOptionPrefix.$categoryOption->categoryOptionID;?>">
														<input type="hidden"
															id="<?=$setOptionPrefix.$categoryOption->categoryOptionID?>"
															name="<?=$setOptionPrefix.$categoryOption->categoryOptionID?>"
															value="<?=$categoryOption->formOptions;?>" />

											<?php
											if ($isMobile && !$isTablet) {
												?>
														<label class="control-label" style="padding-top: 2px"
															for="<?=$setOptionPrefix.$categoryOption->categoryOptionID;?>">
														</label>

														<div class="controls">
															<span class="dinamo-label"
																style="font-weight: bold; font-style: italic">
															<?=$categoryOption->title;?>/<?=$categoryOption->formOptions;?>
															</span>
														</div>
											<?php }else{?>
																
														<label class="control-label" style="padding-top: 2px"
															for="<?=$setOptionPrefix.$categoryOption->categoryOptionID;?>">
															<span style="font-weight: bold; font-style: italic"><?=$categoryOption->title;?></span>
														</label>

														<div class="controls">
															<span class="dinamo-label"
																style="font-weight: bold; font-style: italic">
															<?=$categoryOption->formOptions;?>
															</span>
														</div>
											<?php } ?>
											</div>
											

											<?php
										}
									}
									
									?>										

											

										<div class="form-actions">

														<button type="button" class="btn btn-primary"
															id="submitButton" name="submitButton"
															onclick="submitForm()">Submit</button>

														<!-- <button class="btn">Cancel</button>-->
														<span id="submitSpan" name="submitSpan"
															style="display: none"><img src="img/squares.gif" /></span>
													</div>
													<!-- /form-actions -->

												</fieldset>

											</form>

										</div>




									</div>





								</div>











							</div>
							<!-- /widget-content -->



						</div>
						<!-- /widget -->



					</div>
					<!-- /span8 -->









				</div>
				<!-- /row -->



			</div>
			<!-- /container -->



		</div>
		<!-- /main-inner -->



	</div>
	<!-- /main -->









	<div class="extra">



		<div class="extra-inner">



			<div class="container"></div>
			<!-- /container -->



		</div>
		<!-- /extra-inner -->



	</div>
	<!-- /extra -->









	<div class="footer">



		<div class="footer-inner">



			<div class="container">



				<div class="row">



					<div class="span12"></div>
					<!-- /span12 -->



				</div>
				<!-- /row -->



			</div>
			<!-- /container -->


			<div class="modal" style="display: none" id="loader" name="loader">
				<div class="center">
					<img alt="" src="img/loader.gif" />
				</div>
			</div>

		</div>
		<!-- /footer-inner -->



	</div>
	<!-- /footer -->





	<script>




//Custom Validation starts here







<?php


/*
 *
 * var selectedVal = $j("input[name=companyMeeting]:checked").val()
 *
 * if(selectedVal != true && selectedVal != 'true' && selectedVal != false && selectedVal != 'false')
 *
 * {
 *
 * alert('Please indicate whether you spoke with the Company');
 *
 * return false;
 *
 * }
 *
 */

?>
 
</script>



</body>

</html>
