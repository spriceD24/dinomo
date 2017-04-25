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
	
	function getAllProjectsLite($clientID) {
		//check cache first
		$projects = CacheUtil::getProjectsList($clientID);
		if(empty($projects))
		{
			LogUtil::debug ( 'ProjectDelegate', 'Loading projects from DB' );
			$projects =  $this->projectDAO->getAllProjectsLite($clientID);
			CacheUtil::cacheProjectsList($clientID,$projects);
		}else{
			//LogUtil::debug ( 'ProjectDelegate', 'Loading project from Cache' );				
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
			//LogUtil::debug ( 'ProjectDelegate', 'Loading project from Cache' );
				
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
			//LogUtil::debug ( 'ProjectDelegate', 'Loading categories from Cache' );				
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
			//LogUtil::debug ( 'ProjectDelegate', 'Loading categories from Cache' );
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
			LogUtil::debug ( 'ProjectDelegate', 'Number of categories is: '.$categories->getNumObjects());
				
			CacheUtil::addCachedCategoryOptions($projectID, $categoryID, $categories);			
		}else{
			LogUtil::debug ( 'ProjectDelegate', 'Number of cached categories is: '.$categories->getNumObjects());
			//LogUtil::debug ( 'ProjectDelegate', 'Loading category options from Cache' );
		}
		return $categories;
	}
	
	function getCategoryOptionsNotInReport($projectID, $categoryID)
	{
		return 	$this->projectDAO->getCategoryOptionsNotInReport($projectID, $categoryID);
	}
	
	private function clearProjectCache()
	{
		CacheUtil::removeAllCachedProjects();
		CacheUtil::removeAllCachedCategories();
		CacheUtil::removeAllCachedCategoryOptions();
	}
	
	function saveProject($insertedByID,$project)
	{
		$ret = $this->projectDAO->saveProject($insertedByID, $project);
		$this->clearProjectCache();
		return $ret;
	}
	
	function saveCategory($insertedByID,$project,$category)
	{
		$ret = $this->projectDAO->saveCategory($insertedByID, $project, $category);
		$this->clearProjectCache();
		return $ret;
	}
	
	function saveCategoryOption($insertedByID,$project,$categoryID, $categoryOption)
	{
		$ret = $this->projectDAO->saveCategoryOption($insertedByID, $project, $categoryID, $categoryOption);
		$this->clearProjectCache();
		return $ret;
	}
	
	function deleteProject($projectID,$deletedBy) {
		$ret = $this->projectDAO->deleteProject($projectID,$deletedBy);
		$this->clearProjectCache();
		return $ret;		
	}
	
	function deleteCategory($projectID,$categoryID,$deletedBy) {
		$ret = $this->projectDAO->deleteCategory($projectID, $categoryID,$deletedBy);
		$this->clearProjectCache();
		return $ret;		
	}
	
	function deleteCategoryOptions($projectID,$categoryID,$categoryOptionID,$deletedBy) {
		$ret = $this->projectDAO->deleteCategoryOptions($projectID, $categoryID,$categoryOptionID,$deletedBy);
		$this->clearProjectCache();
		return $ret;		
	}	

	function activateProject($projectID,$activatedBy) {
		$ret = $this->projectDAO->activateProject($projectID,$activatedBy);
		$this->clearProjectCache();
		return $ret;		
	}
	
	function activateCategory($projectID,$categoryID,$activatedBy) {
		$ret = $this->projectDAO->activateCategory($projectID, $categoryID,$activatedBy);
		$this->clearProjectCache();
		return $ret;		
	}
	
	function activateCategoryOptions($projectID,$categoryID,$categoryOptionID,$activatedBy) {
		$ret = $this->projectDAO->activateCategoryOptions($projectID, $categoryID,$categoryOptionID,$activatedBy);
		$this->clearProjectCache();
		return $ret;		
	}	
	
	function updateProject($project,$updatedBy)
	{
		$ret = $this->projectDAO->updateProject($project, $updatedBy);
		$this->clearProjectCache();
		return $ret;		
	}
	
	function updateCategory($projectID,$category,$updatedBy)
	{
		$ret = $this->projectDAO->updateCategory($projectID, $category,$updatedBy);
		$this->clearProjectCache();
		return $ret;		
	}
	
	function backupCategoryOptions($projectID,$categoryID)
	{
		$this->projectDAO->backupCategoryOptions($projectID, $categoryID);
	}
	
	function updateCategoryOption($updatedByID,$projectID,$categoryID,$categoryOption)
	{
		$this->projectDAO->updateCategoryOption($updatedByID, $projectID, $categoryID, $categoryOption);
		$this->clearProjectCache();
	}
	
	function deepCopyProject($projectID,$insertedByID,$name)
	{
		//copy project
		$newProjectID = $this->projectDAO->copyProject($projectID, $insertedByID, $name);
		$categories = $this->getCategories($projectID);
		
		while ( $category = $categories->iterate () )
		{
			//copy category
			$newCategoryID = $this->projectDAO->copyCategory($insertedByID, $newProjectID, $projectID, $category->categoryID);
			$categoryOptions = $this->getCategoryOptions ( $projectID, $category->categoryID );
			$order = 0;
			while ( $categoryOption = $categoryOptions->iterate () )
			{
				$newCategoryOpyionID = $this->projectDAO->copyCategoryOption($insertedByID, $projectID, $category->categoryID, $categoryOption->categoryOptionID, $newProjectID, $newCategoryID);
			}
		}
		$this->clearProjectCache();
		
		return $newProjectID;
	}

	function getCategoriesIncludingDeleted($projectID) 
	{
		return $this->projectDAO->getCategoriesIncludingDeleted($projectID);		
	}
	
	function getAllProjectsLiteIncludingDeleted($clientID)
	{
		return $this->projectDAO->getAllProjectsLiteIncludingDeleted($clientID);		
	}
	
}

?>