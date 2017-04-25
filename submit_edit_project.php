<html>
<body>
<?php include_once("util/WebUtil.php"); ?>
<?php include_once("util/ConfigUtil.php"); ?>
<?php include_once("util/StringUtils.php"); ?>
<?php include_once("dao/model/Project.php"); ?>
<?php include_once("delegate/ProjectDelegate.php"); ?>
<?php include_once("util/LogUtil.php"); ?>
<?php

// print_r($_FILES);
$webUtil = new WebUtil ();
$projectDelegate = new ProjectDelegate ();

$user = $webUtil->getLoggedInUser ();
// refresh the cookie
$webUtil->addLoggedInUser ( $user, ConfigUtil::getCookieExpDays () );

if(!$user->hasRole('admin'))
{
	header ( "Location: select_qa.php" );
	exit ();
}

$webUtil->srcPage = "submit_edit_project.php";
set_error_handler ( array (
		$webUtil,
		'handleError' 
) );

// get the login details
$name = $_POST ["name"];
$projectID = intval($_POST ["projectID"]);


$updatedProject =  $projectDelegate->getProject($projectID);
$updatedProject->projectName = $name;

$projectDelegate->updateProject($updatedProject, $user->userID);

// login OK, now add to cookies
LogUtil::debug ( "submit_edit_project", "Submit UPDATE project for = " .$name );
$url = "Location: manage_project.php?projectID=".$projectID."&message=Sucessfully Updated Project '".$name."'";
header ( $url );
exit ();
?>
</body>
</html>