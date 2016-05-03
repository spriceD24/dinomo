<?php include_once("util/CollectionsUtil.php"); ?>
<?php include_once("dao/model/Category.php"); ?>
<?php include_once("dao/model/CategoryOption.php"); ?>
<?php include_once("dao/model/Project.php"); ?>
<?php

	class ProjectDAO
	{
		function getAllProjectsLite()
		{
			$projects = new Collection;
			$categories = new Collection();
			
			//replace with call to Database
			$categories->add(new Category(1, 1,'Deck Handover', new Collection()));
			$categories->add(new Category(1, 2,'Lift & Stair Boxes', new Collection()));
			$categories->add(new Category(1, 3,'Pre Pour Checklist', new Collection()));
			$categories->add(new Category(1, 4,'Stairs', new Collection()));
			$categories->add(new Category(1, 4,'Verticals', new Collection()));
				
			$projects->add(new Project(1,'Zen Block B',$categories));
			
			return $projects;
		}
		
		function getProject($projectID)
		{
			//Not implemented yet
		}

		function getCategories($projectID)
		{
			//Not implemented yet					
		}

	
		function getCategory($categoryID)
		{
			//Not implemented yet
		}
	
		function getCategoryOptions($projectID, $categoryID)
		{
			$categoryOptions = new Collection;
			
			//TODO replace with call to DB
			if($categoryID == 1)
			{
				//Deck Handover
			}
		}
	}

?>