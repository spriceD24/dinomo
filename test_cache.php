<?php include_once("util/CacheUtil.php"); ?><?php include_once("util/WebUtil.php"); ?><?php include_once("delegate/ProjectDelegate.php"); ?><?php include_once("delegate/UserDelegate.php"); ?>
<html>
<body>

<?php

	//print_r($_SERVER);
    $webUtil = new WebUtil();	$deviceID = $webUtil->getDeviceIP();	echo "<BR/>DEVICE TESTS";
	echo "<br/>Val first is: ".CacheUtil::getCachedDeviceUser($deviceID);

	CacheUtil::addCachedDeviceUser($deviceID,'stefdog');

	echo "<br/>Val second is: ".CacheUtil::getCachedDeviceUser($deviceID);
	
	//CacheUtil::removeCachedDeviceUser($deviceID);

	echo "<br/>Val third is: ".CacheUtil::getCachedDeviceUser($deviceID);
	
	echo "<br/>Val third is: ".CacheUtil::getCachedDeviceUser($deviceID);
	
	echo "<BR/><BR/>LISTS TESTS";
	$projectDelegate = new ProjectDelegate ();
	$projects = $projectDelegate->getAllProjectsLite();
	while ( $project = $projects->iterate () ) 
	
	{							
		print "<br/>project name = ".$project->projectName;								
	}
		
	CacheUtil::cacheProjectsList($projects);
	echo "<BR/>FROM CACHE";
	$projects2 = CacheUtil::getProjectsList();
	while ( $project = $projects2->iterate () ) 
	
	{							
		print "<br/>project name = ".$project->projectName;								
	}
	
	echo "<BR/><BR/>INDIVIDUAL PRJECT TEST";
	$project = $projectDelegate->getProject(1);
	print "<br/>Project name = ".$project->projectName;								
	CacheUtil::addCachedProject($project->projectID,$project);
	$project2 = $projectDelegate->getProject(1);
	print "<br/>Cached Project name = ".$project->projectName;								
	
	echo "<BR/><BR/>CATEGORIES TEST";
	$categories = $projectDelegate->getCategories($project->projectID);
	while ( $cat = $categories->iterate () ) 	{									print "<br/>category name = ".$cat->categoryName;									}
	echo "<BR/>FROM CACHE";
	CacheUtil::cacheCategoriesList($project->projectID,$categories);
	$cat2 = CacheUtil::getCategoriesList($project->projectID);
	while ( $cat = $cat2->iterate () ) 	{									print "<br/>category name = ".$cat->categoryName;									}
	echo "<br/><br/>";	echo "<BR/><BR/>CATEGORY TEST";		$category = $projectDelegate->getCategory($project->projectID,1);		print "<br/>category name = ".$category->categoryName;			echo "<BR/>FROM CACHE";	CacheUtil::addCachedCategory($project->projectID,$category->categoryID,$category);	$category2 = CacheUtil::getCachedCategory($project->projectID,$category->categoryID);	print "<br/>category name = ".$category2->categoryName;		echo "<BR/><BR/>CATEGORY OPTIONS TEST";		$categoryOptions = $projectDelegate->getCategoryOptions($project->projectID,$category2->categoryID);		while ( $cat = $categoryOptions->iterate () ) 	{									print "<br/>category title = ".$cat->title;									}	echo "<BR/>FROM CACHE";	CacheUtil::addCachedCategoryOptions($project->projectID,$category->categoryID,$categoryOptions);	$categoryOptions2 = CacheUtil::getCachedCategoryOptions($project->projectID,$category2->categoryID);		while ( $cat = $categoryOptions2->iterate () )	{		print "<br/>category title = ".$cat->title;	}			echo "<br/><br/>";		echo "<br/><br/>";
	echo "<BR/>USERS TEST";	$userDelegate = new UserDelegate ();	$users = $userDelegate->getAllUsers();	while ( $user = $users->iterate () )	{		print "<br/>name = ".$user->name;	}
	CacheUtil::addCachedUsers($users);	$users2 = CacheUtil::getCachedUsers($users);	echo "<BR/>FROM CACHE";	while ( $user = $users2->iterate () )	{		print "<br/>name = ".$user->name;	}				?>

</body>
</html>