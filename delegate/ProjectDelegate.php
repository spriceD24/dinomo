<?php include_once("util/CollectionsUtil.php"); ?>
<?php include_once("util/CacheUtil.php"); ?>
<?php include_once("dao/model/Category.php"); ?>
<?php include_once("dao/model/CategoryOption.php"); ?>
<?php include_once("dao/model/Project.php"); ?>
<?php include_once("dao/ProjectDAO.php"); ?>
<?php

class ProjectDelegate {
	private $projectDAO;
	function __construct() {
		$this->projectDAO = new ProjectDAO ();
	}
	function getAllProjectsLite() {
		//check cache first
		$projects = CacheUtil::getProjectsList();
		if(empty($projects))
		{
			LogUtil::debug ( 'ProjectDelegate', 'Loading projects from DB' );
			$projects =  $this->projectDAO->getAllProjectsLite ();
			CacheUtil::cacheProjectsList($projects);
		}else{
			LogUtil::debug ( 'ProjectDelegate', 'Loading project from Cache' );				
		}
		return $projects;
	}
	
	function getProject($projectID) {
		$project = CacheUtil::getCachedProject($projectID);
		if(empty($project))
		{
			LogUtil::debug ( 'ProjectDelegate', 'Loading project from DB' );
			$project = $this->projectDAO->getProject ( $projectID );
			CacheUtil::addCachedProject($projectID, $project);
		}else{
			LogUtil::debug ( 'ProjectDelegate', 'Loading project from Cache' );
				
		}
		return $project;
	}
	
	function getCategories($projectID) 
	{
		$categories = CacheUtil::getCategoriesList($projectID);
		if(empty($categories))
		{
			LogUtil::debug ( 'ProjectDelegate', 'Loading categories from DB' );
			$categories = $this->projectDAO->getCategories ( $projectID );
			CacheUtil::cacheCategoriesList($projectID, $categories);
		}else{
			LogUtil::debug ( 'ProjectDelegate', 'Loading categories from Cache' );				
		}
		return $categories;
	}
	
	function getCategory($projectID, $categoryID) 
	{
		$category = CacheUtil::getCachedCategory($projectID, $categoryID);
		if(empty($category))
		{
			LogUtil::debug ( 'ProjectDelegate', 'Loading category from DB' );				
			$category = $this->projectDAO->getCategory ( $projectID, $categoryID );
			CacheUtil::addCachedCategory($projectID, $categoryID, $category);
		}else{
			LogUtil::debug ( 'ProjectDelegate', 'Loading categories from Cache' );
		}
		return $category;
	}
	
	function getCategoryOptions($projectID, $categoryID) 
	{
		$categories = CacheUtil::getCachedCategoryOptions($projectID, $categoryID);
		if(empty($categories))
		{
			LogUtil::debug ( 'ProjectDelegate', 'Loading category options from DB' );
			$categories = $this->projectDAO->getCategoryOptions ( $projectID, $categoryID );
			CacheUtil::addCachedCategoryOptions($projectID, $categoryID, $categories);			
		}else{
			LogUtil::debug ( 'ProjectDelegate', 'Loading category options from Cache' );
		}
		return $categories;
	}
}

?>