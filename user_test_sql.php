<!DOCTYPE html>
<html lang="en">
<?php include_once("util/LogUtil.php"); ?>
<?php include_once("mobile_detect/Mobile_Detect.php");?>
<?php include_once("delegate/UserDelegate.php"); ?>
<?php include_once("dao/UserDAO.php"); ?>

<?php
$webUtil = new WebUtil ();
$userDelegate = new UserDelegate ();

$webUtil->srcPage = "user_test_sql.php";
set_error_handler ( array (
		$webUtil,
		'handleError' 
) );

$userDAO = new UserDAO();


echo "Saving Users...";
$allUsers = $userDAO->getAllUsersFixed();
while ( $user = $allUsers->iterate () ) {
	echo "Saving user - ".$user->name."<br/>";
	$user->password = $user->login;
	$response = $userDelegate->saveUser(1, $user);
	echo $response."<br/>";	
}

?>


</html>
