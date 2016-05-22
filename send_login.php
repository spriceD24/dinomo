<html>
<body>
<?php include_once("util/WebUtil.php"); ?>
<?php include_once("util/ConfigUtil.php"); ?>
<?php include_once("util/StringUtils.php"); ?>
<?php include_once("dao/model/User.php"); ?>
<?php include_once("delegate/UserDelegate.php"); ?>
<?php include_once("util/HTMLUtil.php"); ?>
<?php require_once('mail/PHPMailer.php');?>

<?php

// print_r($_FILES);
$webUtil = new WebUtil ();
$userDelegate = new UserDelegate ();

$webUtil->srcPage = "send_login.php";
set_error_handler ( array (
		$webUtil,
		'handleError' 
) );

// get the login details
$email = $_POST ["email"];

if (empty ( $email )) {
	header ( "Location: forgot_login.php?errorcode=1" );
	exit ();
}

if (! filter_var ( $email, FILTER_VALIDATE_EMAIL )) {
	header ( "Location: forgot_login.php?errorcode=1" );
	exit ();
}

$user = $userDelegate->getUserByEmail ( $email );
if (empty ( $user )) {
	header ( "Location: forgot_login.php?errorcode=2" );
	exit ();
}

// send email
$pass = $userDelegate->getUserPass ( $user->userID );

$htmlUtil = new HTMLUtil ();
LogUtil::debug ( "send_login", "user = " . $user->login . ", Generating Login/Pass Email to send to " . $user->email );
$emailHTML = $htmlUtil->generateForgotDetailsEmail ( $user->login, $pass );

$emailToSend = new PHPMailer ();
$emailToSend->From = ConfigUtil::getDinamoFromEmail ();
$emailToSend->FromName = ConfigUtil::getDinamoFromName ();
$emailToSend->Subject = 'Dinamo Login Details';
$emailToSend->Body = $emailHTML;
$emailToSend->IsHTML ( true );

$emailToSend->AddAddress ( $user->email );

if ($webUtil->isProduction ()) {
	$emailToSend->Send ();
} else {
	LogUtil::debug ( "send_login", "user = " . $user->login . ", Not Sending Credentials Email to " . $user->email . " as not in PRODUCTION" );
}

LogUtil::debug ( "send_login", "user = " . $user->login . ", Sent Credentials Email OK" );

header ( "Location: login.php?forgot=1" );
exit ();
?>
</body>
</html>