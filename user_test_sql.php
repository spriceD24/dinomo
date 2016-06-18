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

//$currentUser = $webUtil->getLoggedInUser ();

$userDAO = new UserDAO();


echo "Saving Users...";
$users = new Collection ();

$users->add ( new User ( 1, 1, 'Stephen Price', 'sprice', 'stephen.price@credit-suisse.com', '+61424185814', '','admin|testuser' ) );
$users->add ( new User ( 2, 1, 'Patrick Noonan', 'pnoonan', 'patricknoonan@dinomoformwork.com.au', '+61433965870', '','user' ) );
$users->add ( new User ( 3, 1, 'John Difrancesco', 'johndi', 'johndi@dinomoformwork.com.au', '+61400945426', '','user' ) );
$users->add ( new User ( 4, 1, 'Dominic Difrancesco', 'dominic', 'dominic@dinomoformwork.com.au', '','', 'user' ) );

while ( $user = $users->iterate () ) {
	echo "Saving user - ".$user->name."<br/>";
	$user->password = $user->login;
	$response = $userDelegate->saveUser(1, $user);
	echo $response."<br/>";	
}

?>


</html>
