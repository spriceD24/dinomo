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

$user = $webUtil->getLoggedInUser ();
// refresh the cookie
$webUtil->addLoggedInUser ( $user, ConfigUtil::getCookieExpDays () );

if(!$user->hasRole('admin'))
{
	header ( "Location: select_qa.php" );
	exit ();
}

$webUtil->srcPage = "submit_edit_user.php";
set_error_handler ( array (
		$webUtil,
		'handleError' 
) );

// get the login details
$login = $_POST ["login"];
$password = $_POST ["password"];
$name = $_POST ["name"];
$email = $_POST ["email"];
$roles = $_POST ["roles"];
$userID = intval($_POST ["userID"]);


$updatedUser =  $userDelegate->getUser($userID);
$updatedUser->login = $login;
$updatedUser->password = $password;
$updatedUser->name = $name;
$updatedUser->email = $email;
$updatedUser->roles = explode("|",$roles);


$userDelegate->updateUser($user->userID, $updatedUser);

// login OK, now add to cookies
LogUtil::debug ( "submit_edit_user", "Submit new user UPDATE success for = " .$login );
$url = "Location: manage_user.php?userID=".$userID."&message=Sucessfully Updated User '".$name."'";
header ( $url );
exit ();
?>
</body>
</html>