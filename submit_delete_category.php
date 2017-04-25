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

$webUtil->srcPage = "submit_delete_category.php";
set_error_handler ( array (
		$webUtil,
		'handleError' 
) );

// get the login details
$projectID = intval($_GET ["projectID"]);
$categoryID = intval($_GET ["categoryID"]);
$action = $_GET ["action"];

if($action == "activate")
{
	$projectDelegate->activateCategory($projectID, $categoryID, $user->userID);
	
	// login OK, now add to cookies
	LogUtil::debug ( "submit_delete_category", "Activate Category success for id= " .$user->userID );
	$url = "Location: edit_project.php?projectID=".$projectID."&categoryID=".$categoryID."&message=Sucessfully Reactivate Report Type";
	
}else if($action == "delete"){
	$category = $projectDelegate->getCategory($projectID,$categoryID);
	$projectDelegate->deleteCategory($projectID, $categoryID, $user->userID);
	
	// login OK, now add to cookies
	LogUtil::debug ( "submit_delete_category", "Delete Category success for = " .$user->name );
	$url = "Location: edit_project.php?projectID=".$projectID."&message=Sucessfully Deleted Report Type '".$category->categoryName."'";
	
}
header ( $url );
exit ();
?>
</body>
</html>