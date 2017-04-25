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

$webUtil->srcPage = "submit_new_project.php";
set_error_handler ( array (
		$webUtil,
		'handleError' 
) );

// get the login details
$name = $_POST ["name"];
$projectDetails = $_POST ["projectDetails"];

if($projectDetails == "new")
{
	LogUtil::debug ( "submit_edit_project", "Save new project for = " .$name);
	$project = new Project ( 0, $user->clientID,$name, new Collection(), '' );
	$projectID = $projectDelegate->saveProject($user->userID, $project);
}else if($projectDetails == "copy")
{
	$copyProjectID = intval($_POST ["projectCopy"]);
	LogUtil::debug ( "submit_edit_project", "Deep copy project for = " .$name.", original id = ".$copyProjectID);
	$projectID = $projectDelegate->deepCopyProject($copyProjectID, $user->userID, $name);	
}

// login OK, now add to cookies
LogUtil::debug ( "submit_edit_project", "Finished Submit NEW project for = " .$name );
$url = "Location: manage_project.php?projectID=".$projectID."&message=Sucessfully Added Project '".$name."'";
header ( $url );
exit ();
?>
</body>
</html>