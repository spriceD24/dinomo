<!DOCTYPE html>
<html lang="en">
<?php include_once("util/LogUtil.php"); ?>
<?php include_once("mobile_detect/Mobile_Detect.php");?>
<?php include_once("delegate/ProjectDelegate.php"); ?>
<?php include_once("dao/ProjectDAO.php"); ?>

<?php
$webUtil = new WebUtil ();
$projectDelegate = new ProjectDelegate ();
$projectDAO = new ProjectDAO ();

/*
$webUtil->srcPage = "project_test_sql.php";
set_error_handler ( array (
		$webUtil,
		'handleError' 
) );*/

$currentUser = $webUtil->getLoggedInUser ();

$userDAO = new UserDAO();


//echo "Saving Projects...";
$projects = new Collection();
$projects->add ( new Project ( 1, 'Bellevue Hill PS', new Collection(), '' ) );
$projects->add ( new Project ( 2, 'Cranbrook Care', new Collection(), '' ) );
$projects->add ( new Project ( 3, 'WPM Block C', new Collection(), '' ) );

$categories = new Collection ();

// replace with call to Database
$categories->add ( new Category ( 1, 1, 'Deck Handover', new Collection () ) );
$categories->add ( new Category ( 1, 2, 'Lift & Stair Boxes', new Collection () ) );
$categories->add ( new Category ( 1, 3, 'Pre Pour Checklist', new Collection () ) );
$categories->add ( new Category ( 1, 4, 'Stairs', new Collection () ) );
$categories->add ( new Category ( 1, 5, 'Verticals', new Collection () ) );

while ( $project = $projects->iterate () ) {
	
	echo "Saving project - ".$project->projectName."<br/>";
	
	$pID = $projectDelegate->saveProject(1, $project);
	echo " new Proj ID = ".$pID."<br/>";	
	
	while ( $category = $categories->iterate () ) 
	{
		echo "Saving category - ".$category->categoryName."<br/>";		
		$catID = $projectDelegate->saveCategory(1, $pID, $category);
		echo " new Cat ID = ".$catID."<br/>";	
		$categoryOptions = $projectDAO->getCategoryOptionsFIXED(1, $category->categoryID);
		$order = 0;
		while ( $categoryOption = $categoryOptions->iterate () ) 
		{
			echo "Saving category option - ".$categoryOption->title."<br/>";	
			$categoryOption->projectID = $pID;
			$categoryOption->categoryID = $catID;
			$categoryOption->order = $order;
			$categoryID = $projectDelegate->saveCategoryOption(1, $pID, $catID, $categoryOption);
			echo " new Category ID = ".$categoryID."<br/>";	
			$order = $order+10;
		}
	}
}

?>


</html>
