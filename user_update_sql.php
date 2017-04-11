<!DOCTYPE html>
<html lang="en">
<?php include_once("util/LogUtil.php"); ?>
<?php include_once("mobile_detect/Mobile_Detect.php");?>
<?php include_once("delegate/UserDelegate.php"); ?>
<?php include_once("dao/UserDAO.php"); ?>

<?php
$webUtil = new WebUtil ();
$userDelegate = new UserDelegate ();

// $webUtil->srcPage = "user_update_test_sql.php";
// set_error_handler ( array (
// 		$webUtil,
// 		'handleError' 
// ) );

//$currentUser = $webUtil->getLoggedInUser ();

$userDAO = new UserDAO();


echo "Saving Users...";
$users = new Collection ();


$user = $userDelegate->getUserForClient(1, 2);
echo " name = ".$user->login,"<br/>";
$user->password = "pnoonan2";
$userDelegate->updateUser(1, $user);

 $users->add ( new User ( 3, 1, 'John Difrancesco', 'johndi', 'johndi@dinomoformwork.com.au', '+61400945426', '','user' ) );
 $users->add ( new User ( 4, 1, 'Dominic Difrancesco', 'dominic', 'dominic@dinomoformwork.com.au', '','', 'user' ) );
 $users->add ( new User ( 5, 1, 'Thomas Fletcher', 'fletchm', 'info@dinomoformwork.com.au', '','', 'user' ) );
 $users->add ( new User ( 6, 1, 'Arti Saren', 'sarena', 'info@dinomoformwork.com.au', '','', 'user' ) );
 $users->add ( new User ( 7, 1, 'Harry Saren', 'sarenh', 'info@dinomoformwork.com.au', '','', 'user' ) );
 $users->add ( new User ( 8, 1, 'Sebastion Debono', 'debonos', 'info@dinomoformwork.com.au', '','', 'user' ) );
 $users->add ( new User ( 9, 1, 'Erick Monslave', 'monslav', 'erick@dinomoformwork.com.au', '','', 'user' ) );
 $users->add ( new User ( 10, 1, 'Padraig Bergin', 'berginp', 'p.bergin@dinomoformwork.com.au', '','', 'user' ) );
 $users->add ( new User ( 11, 1, 'Monica Lemke', 'lemkem', 'monica@dinomoformwork.com.au', '','', 'user' ) );
 
while ( $user = $users->iterate () ) {
	//echo "Saving user - ".$user->name."<br/>";
	$user->password = $user->login.$user->userID;
	$response = $userDelegate->saveUser(1, $user);
	echo $user->name.",".$user->login.",".$user->password."<br/>";
	//echo $response."<br/>";	
}

?>


</html>
