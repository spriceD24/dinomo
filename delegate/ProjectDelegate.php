<?php include_once("util/CollectionsUtil.php"); ?>
<?php include_once("dao/model/Category.php"); ?>
<?php include_once("dao/model/CategoryOption.php"); ?>
<?php include_once("dao/model/Project.php"); ?>
<?php include_once("dao/ProjectDAO.php"); ?>
<?php

	class ProjectDelegate
	{
		
		private $projectDAO;
		
		function __construct()
		{
			$this->projectDAO = new ProjectDAO();
		}
		
		function getAllProjectsLite()
		{
			return $this->projectDAO->getAllProjectsLite();
		}
		
		function getProject($projectID)
		{
			return $this->projectDAO->getProject($projectID);
		}

		function getCategories($projectID)
		{	
			return $this->projectDAO->getCategories($projectID);
		}

	
		function getCategory($projectID,$categoryID)
		{
			return $this->projectDAO->getCategory($projectID, $categoryID);
		}
	
		function getCategoryOptions($projectID, $categoryID)
		{
			return $this->projectDAO->getCategoryOptions($projectID, $categoryID);
		}
	}

?>