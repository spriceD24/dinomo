<html>
<body>
<?php include_once("util/WebUtil.php"); ?>
<?php include_once("util/ConfigUtil.php"); ?>
<?php include_once("util/StringUtils.php"); ?>
<?php include_once("dao/model/Category.php"); ?>
<?php include_once("dao/model/CategoryOption.php"); ?>
<?php include_once("dao/model/Project.php"); ?>
<?php include_once("delegate/ProjectDelegate.php"); ?>
<?php include_once("util/LogUtil.php"); ?>
<?php

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

$webUtil->srcPage = "submit_delete_project.php";
set_error_handler ( array (
		$webUtil,
		'handleError' 
) );

// get the login details
$projectID = intval($_GET ["projectID"]);
$action = $_GET ["action"];


if($action == "activate")
{
	LogUtil::debug ( "submit_delete_project", "Activating Project");
	$projectDelegate->activateProject($projectID, $user->userID);
	
	// login OK, now add to cookies
	LogUtil::debug ( "submit_delete_project", "Activate Project success for id= " .$projectID );
	$url = "Location: manage_project.php?projectID=".$projectID."&message=Sucessfully Reactivate Project";
	
}else if($action == "delete"){
	$project = $projectDelegate->getProject($projectID);
	LogUtil::debug ( "submit_delete_project", "Deleting Project");
	$projectDelegate->deleteProject($projectID, $user->userID);
	
	// login OK, now add to cookies
	LogUtil::debug ( "submit_delete_project", "Delete Project success for = " .$user->name );
	$url = "Location: manage_project.php?message=projectID=".$projectID."&message=Sucessfully Deleted Project '".$project->projectName."'";
	
}
header ( $url );
exit ();
?>
</body>
</html>