<!DOCTYPE html><html lang="en"><?php include_once("util/LogUtil.php"); ?><?php include_once("util/DBUtil.php"); ?><?php include_once("delegate/UserDelegate.php"); ?><?php include_once("util/WebUtil.php"); ?><?php include_once("mobile_detect/Mobile_Detect.php");?><?php	$webUtil = new WebUtil ();	$webUtil->srcPage = "view_users.php";// 	set_error_handler ( array (// 			$webUtil,// 			'handleError' // 	) );		$detect = new Mobile_Detect ();	$isMobile = ($detect->isMobile() && !$detect->isTablet());	$isTablet = $detect->isTablet();		$currentUser = $webUtil->getLoggedInUser();	//$userDAO->saveReport($user);	$userDelegate = new UserDelegate();	$allUsers = $userDelegate->getAllUsersIncludingDeleted($currentUser->clientID);	$editUsers = true;	$deleteUsers = true;	$message = "";	if (isset ( $_GET ["action"] )) 	{		$action = $_GET ["action"];		if($action == "edit")		{			$message = "Select User To Edit";			$deleteUsers = false;		}		if($action == "remove")		{			$message = "Select User To Delete";			$editUsers = false;		}	}	if (isset ( $_GET ["message"] )) 	{		$message = $_GET ["message"];	}	?>
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
				<br/>				<h3><?=$message?></h3>				  <input type="radio" name="show" value="active" checked onclick="showActiveUsersOnly()"> Show Active Uers Only 				  <input type="radio" name="show" value="all" onclick="showDeletedUsers()"> Show All Users (including deleted) 
				<div class="error-container" style="margin-top:0;margin-bottom:0;text-align:left">					<?php 						if(count($allUsers) == 0)						{							?>							No Users exist					<?php 								}else{					?>						<br/>						<table style="padding-left:10px; border: 1px dashed black;background-color:white">									<tr class="tr-header">								<td>Name</td>								<td>Login</td>								<td>Email</td>								<?php 									if($editUsers)									{								?>									<td></td>								<?php 											}if($deleteUsers)									{								?>									<td></td>								<?php 											}								?>																											</tr>					<?php 								$userID = -1;							if (isset ( $_GET ["userID"] ))							{								$userID =  intval ($_GET ["userID"]) ;												}															$count = 1;							foreach ($allUsers as $user)							{					?>							<tr							<?php 							echo " id = '".$user->userID."' ";							$className = "";							if($count % 2 == 0)							{								$className = "tr-even";							}else{								$className = "tr-odd";							}							if($user->userID == $userID)							{								$className = "tr-updated";							}							if($user->deleteFlag == 1)							{								echo "  style='display:none' ";								$className = "tr-deleted";							}							echo " class='".$className."'";															?>							>								<td><?=$user->name?></td>								<td><?=$user->login?></td>								<td><?=$user->email?></td>								<?php 									if($editUsers)									{								?>									<td>										<button style="float:left;margin-bottom:15px" class="button btn btn-success btn-small" type="button" onclick="editUser(<?=$user->userID?>)">Edit</button>									</td>								<?php 											}if($deleteUsers && $user->deleteFlag == 0)									{								?>									<td>										<button style="float:left;margin-bottom:15px" class="button btn btn-danger btn-small" type="button" onclick="deleteUser(<?=$user->userID?>,'<?=str_replace("'"," ",$user->name)?>')">Delete</button>									</td>								<?php 											}if($deleteUsers && $user->deleteFlag == 1)									{								?>									<td>										<button style="float:left;margin-bottom:15px" class="button btn btn-warning btn-small" type="button" onclick="activateUser(<?=$user->userID?>,'<?=str_replace("'"," ",$user->name)?>')">Reactivate</button>									</td>								<?php 											}								?>																																			</tr>					<?php 											$count = $count+1;							}					?>						</table>							<?php						}					?>
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
function checkShowReports(){	val = $('input[name="show"]:checked').val();	if(val == 'me')	{		showMine();	}else{		showAll();	}}function showDeletedUsers(){	//alert('show all');	<?php 	foreach ($allUsers as $user)	{		echo " document.getElementById('".$user->userID."').style.display = ''\n";	}	?>}function showActiveUsersOnly(){	<?php 	foreach ($allUsers as $user)	{		if($user->deleteFlag == 1)		{			echo " document.getElementById('".$user->userID."').style.display = 'none'\n";		}	}	?>}function editUser(userID){	window.location = "edit_user.php?userID="+userID;}function deleteUser(userID, name){	var r = confirm("Are you want to delete user - '"+name+"'");	if (r == true) {		window.location = "submit_delete_user.php?action=delete&userID="+userID;	} }function activateUser(userID, name){	var r = confirm("Are you want to reactivate user - '"+name+"'");	if (r == true) {		window.location = "submit_delete_user.php?action=activate&userID="+userID;	} }</script>
</html>
