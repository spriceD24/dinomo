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
$categoryID = intval($_POST ["categoryID"]);

$updatedCategory =  $projectDelegate->getCategory($projectID,$categoryID);
$updatedCategory->categoryName = $name;

$projectDelegate->updateCategory($projectID, $updatedCategory, $user->userID);

// login OK, now add to cookies
LogUtil::debug ( "submit_edit_category", "Submit UPDATE project for = " .$name );
$url = "Location: edit_project.php?projectID=".$projectID."&categoryID=".$categoryID."&message=Sucessfully Updated Report Type '".$name."'";
header ( $url );
exit ();
?>
</body>
</html>