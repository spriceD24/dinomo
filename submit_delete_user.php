<html>
<body>
<?php include_once("util/WebUtil.php"); ?>
<?php include_once("util/ConfigUtil.php"); ?>
<?php include_once("util/StringUtils.php"); ?>
<?php include_once("dao/model/User.php"); ?>
<?php include_once("delegate/UserDelegate.php"); ?>
<?php include_once("util/LogUtil.php"); ?>
<?php

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

$webUtil->srcPage = "submit_delete_user.php";
set_error_handler ( array (
		$webUtil,
		'handleError' 
) );

// get the login details
$userID = intval($_GET ["userID"]);
$action = $_GET ["action"];
$deleteUser = $userDelegate->getUser($userID);

if($action == "activate")
{
	$userDelegate->activateUser($userID, $user->userID);
	
	// login OK, now add to cookies
	LogUtil::debug ( "submit_delete_user", "Activate User success for id= " .$userID );
	$url = "Location: manage_user.php?userID=".$userID."&message=Sucessfully Reactivate User";
	
}else if($action == "delete"){
	$userDelegate->deleteUser($userID, $user->userID);
	
	// login OK, now add to cookies
	LogUtil::debug ( "submit_delete_user", "Delete User success for = " .$deleteUser->name );
	$url = "Location: manage_user.php?message=Sucessfully Deleted User '".$deleteUser->name."'";
	
}
header ( $url );
exit ();
?>
</body>
</html>