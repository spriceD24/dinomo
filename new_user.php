<!DOCTYPE html>
<html lang="en">
<?php include_once("util/WebUtil.php"); ?>
<?php include_once("dao/model/User.php"); ?>
<?php include_once("delegate/UserDelegate.php"); ?>
<?php include_once("util/StringUtils.php"); ?>
<?php include_once("util/LogUtil.php"); ?>
<?php include_once("util/ConfigUtil.php"); ?>
<?php include_once("util/CacheUtil.php"); ?>

<?php

$webUtil = new WebUtil ();

$user = $webUtil->getLoggedInUser ();
// refresh the cookie
$webUtil->addLoggedInUser ( $user, ConfigUtil::getCookieExpDays () );

if(!$user->hasRole('admin'))
{
	header ( "Location: select_qa.php" );
	exit ();
}

if (isset ( $_GET ["errorcode"] )) {

	$errorNum = intval ( $_GET ["errorcode"] );
	/*
	if ($errorNum == 1) {
		$error = "Please enter Login and Password";
	}
	if ($errorNum == 2) {
		$error = "Please enter Login";
	}
	if ($errorNum == 3) {
		$error = "Please enter Password";
	}
	if ($errorNum == 4) {
		$error = "Login does not exist";
	}
	if ($errorNum == 5) {
		$error = "Password is incorrect";
	}*/
}

$userDelegate = new UserDelegate();

$currentUsers = $userDelegate->getAllUsersForClient($user->clientID);

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
<link href="css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="css/dinamo.css">
<link href="css/style.css" rel="stylesheet" type="text/css">
<link href="css/pages/admin-signin.css" rel="stylesheet" type="text/css">	

<link href="css/font-awesome.css" rel="stylesheet">
<link
	href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600"
	rel="stylesheet">

</head>

<body>


	<div class="account-container" style="margin:40px">

		<div class="content clearfix">

			<form action="submit_new_user.php" method="post" id="submitUserForm" name="submitUserForm">

				<h1>New User</h1>

				<div class="login-fields">
				<?php
				if (! empty ( $error )) {
					?>
					<p style="font-style: italic; color: red; font-weight: bold"><?=$error;?></p>
				<?php
				} else {
					?>
				<p>Please provide user details</p>
				<?php
				}
				?>
					<div class="field">
						<label for="name"></label>Name
						<br/><input type="text" id="name"
							name="name" value="" placeholder=""
							class=""
							 />
					</div>
					<div class="field">
						<label for="login"></label>Login
						<br/><input type="text" id="login"
							name="login" value="" placeholder=""
							class="login username-field"
							 />
					</div>
					<!-- /field -->

					<div class="field">
						<label for="password"></label>Password<br/> <input type="password"
							id="password" name="password" value="" placeholder=""
							class="login password-field"
							/>
					</div>
					<!-- /password -->

					<div class="field">
						<label for="password"></label>Confirm Password<br/> <input type="password"
							id="passwordConfirm" name="passwordConfirm" value="" placeholder=""
							class="login password-field"
							/>
					</div>
					<div class="field">
						<label for="email"></label>Email
						<br/><input type="text" id="email"
							name="email" value="" placeholder=""
							class="login"
							 />
					</div>
					<div class="field">
						<label for="roles"></label>User Is Allowed To
						<br/>
						<table>
							<tr>
								<td><input type="checkbox" id="user"  name="user" value="true" style="width:10px" checked></td><td>Submit Reports</td>
							</tr>
							<tr>
								<td><input type="checkbox" name="recipient" id="recipient"   style="width:10px" ></td><td>Receive All Uploaded Report emails</td>
							</tr>
							<tr>
								<td><input type="checkbox" id="admin" name="admin"  style="width:10px"></td><td>Administer site (i.e. add users, projects etc.)</td>
							</tr>
						</table>
					</div>
				</div>
				<!-- /save-fields -->

				<div class="login-actions">

					<button style="float:left" class="button btn btn-success" type="button" onclick="checkSaveUser()">Save</button>
					<button class="button btn btn-info"  type="button"  onclick="resetDetails()">Reset</button>
				</div>
				<!-- .actions -->
				<input type="hidden" name="roles" id="roles" value="user"/>

			</form>

		</div>
		<!-- /content -->

	</div>
	<!-- /account-container -->

</body>

<script>
function checkSaveUser()
{
	if(document.getElementById("name").value.trim() == "")
	{
		alert("Please enter a Name");
		return;
	}	
	if(document.getElementById("login").value.trim() == "")
	{
		alert("Please enter a Login");
		return;
	}	
	if(document.getElementById("password").value.trim() == "")
	{
		alert("Please enter a Password");
		return;
	}	
	if(document.getElementById("email").value.trim() == "")
	{
		alert("Please enter an Email Address");
		return;
	}	
	if(document.getElementById("password").value.trim() != document.getElementById("passwordConfirm").value.trim())
	{
		alert("Password and Confirm password do not match");
		return;
	}	
	if(!validEmail(document.getElementById("email").value.trim()))
	{
		alert("Email address is not valid");
		return;
	}	
	<?php 
	while ( $currentUser= $currentUsers->iterate () )
	{
	?>
	if(document.getElementById("login").value.trim() == "<?=$currentUser->login?>")
	{
		alert("Login ["+document.getElementById("login").value.trim()+"] already exists");
		return;
	}	
	<?php 
	}
	?>
 	
	var roles = "";
	if(document.getElementById("user").checked)
	{	
		roles="user";
	}
	if(document.getElementById("recipient").checked)
	{	
		if(roles !="")
		{
			roles = roles+"|";
		}
		roles=roles+"recipient";
	}
	if(document.getElementById("admin").checked)
	{	
		if(roles !="")
		{
			roles = roles+"|";
		}
		roles=roles+"admin";
	}
	document.getElementById('roles').value=roles;	
	document.getElementById('submitUserForm').submit();
}


function validEmail(mail) 
{
 if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(mail))
  {
    return true;
  }
   return false;
}

function resetDetails()
{
	document.getElementById("name").value="";
	document.getElementById("login").value="";
	document.getElementById("password").value="";
	document.getElementById("email").value="";
	document.getElementById("passwordConfirm").value="";
	document.getElementById("user").checked=true;
	document.getElementById("recipient").checked=false;
	document.getElementById("admin").checked=false;
}
</script>
</html>
