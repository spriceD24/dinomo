<?php include_once("util/CacheUtil.php"); ?>

<body>

<?php

	//print_r($_SERVER);
    $webUtil = new WebUtil();
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
	while ( $cat = $categories->iterate () ) 
	echo "<BR/>FROM CACHE";
	CacheUtil::cacheCategoriesList($project->projectID,$categories);
	$cat2 = CacheUtil::getCategoriesList($project->projectID);
	while ( $cat = $cat2->iterate () ) 
	echo "<br/><br/>";
	echo "<BR/>USERS TEST";


</body>
</html>