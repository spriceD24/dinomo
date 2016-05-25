<html>
<body>
<?php include_once("util/WebUtil.php"); ?>
<?php include_once("util/ConfigUtil.php"); ?>
<?php include_once("util/StringUtils.php"); ?>
<?php include_once("dao/model/User.php"); ?>
<?php include_once("delegate/UserDelegate.php"); ?>
<?php include_once("util/LogUtil.php"); ?>
<?php

// print_r($_FILES);
$webUtil = new WebUtil ();
$userDelegate = new UserDelegate ();

$webUtil->srcPage = "submit_login.php";
set_error_handler ( array (
		$webUtil,
		'handleError' 
) );

// get the login details
$login = $_POST ["login"];
$password = $_POST ["password"];

LogUtil::debug ( "submit_login", "Checking login for =  " . $login );

if (empty ( $login ) && empty ( $password )) {
	header ( "Location: login.php?errorcode=1" );
	exit ();
}

if (empty ( $login )) {
	header ( "Location: login.php?errorcode=2&login=" . $login );
	exit ();
}

if (empty ( $password )) {
	header ( "Location: login.php?errorcode=3&login=" . $login );
	exit ();
}

$user = $userDelegate->getUserByLogin ( $login );
if (empty ( $user )) {
	header ( "Location: login.php?errorcode=4&login=" . $login );
	exit ();
}

if (! $userDelegate->isValidLogin ( $login, $password )) {
	header ( "Location: login.php?errorcode=5&login=" . $login );
	exit ();
}

// login OK, now add to cookies
LogUtil::debug ( "submit_login", "LOGIN success, setting cookies for = " .$user->login );

$webUtil->addLoggedInUser ( $user, ConfigUtil::getCookieExpDays () );

header ( "Location: select_qa.php" );
exit ();
?>
</body>
</html>