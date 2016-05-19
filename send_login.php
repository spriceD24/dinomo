<html>
<body>
<?php include_once("util/WebUtil.php"); ?>
<?php include_once("util/ConfigUtil.php"); ?>
<?php include_once("util/StringUtils.php"); ?>
<?php include_once("dao/model/User.php"); ?>
<?php include_once("delegate/UserDelegate.php"); ?>

<?php

	//print_r($_FILES);
    $webUtil = new WebUtil();
	$userDelegate = new UserDelegate ();

	$webUtil->srcPage = "send_login.php";
	set_error_handler(array($webUtil, 'handleError'));
	
	//get the login details
	$email = $_POST["email"]; 
	
	if(empty($email))
	{
		header("Location: forgot_login.php?errorcode=1");
		exit;
	}
	
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
	{
		header("Location: forgot_login.php?errorcode=1");
		exit;
	}
	
	$user = $userDelegate->getUserByEmail($email);
	if(empty($user))
	{
		header("Location: forgot_login.php?errorcode=2");
		exit;
	}
	
	
	//send email
	
	header("Location: login.php?forgot=1");
	exit;
?>
</body>
</html><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body>
<script type="text/javascript">
    parent.processForm('&ftpAction=openFolder');
</script>
</body>
</html>
