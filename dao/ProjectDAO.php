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
				
			$projects->add(new Project(1,'Zen Block B',$categories,'Rhodes'));
			
			return $projects;
		}
		
		function getProject($projectID)
		{
			//replace with call to Database
			if($projectID == 1)
			{
				//Zen Project
				return new Project(1, 'Zen Plan B', new Collection());
			}
		}

		function getCategories($projectID)
		{	
			$categories = new Collection();
			if($projectID == 1)
			{
				
				//replace with call to Database
				$categories->add(new Category(1, 1,'Deck Handover', new Collection()));
				$categories->add(new Category(1, 2,'Lift & Stair Boxes', new Collection()));
				$categories->add(new Category(1, 3,'Pre Pour Checklist', new Collection()));
				$categories->add(new Category(1, 4,'Stairs', new Collection()));
				$categories->add(new Category(1, 5,'Verticals', new Collection()));
			}			
			
			return $categories;
		}

	
		function getCategory($projectID,$categoryID)
		{
			//Not implemented yet
			//TODO replace with call to DB
			if($categoryID == 1)
			{
				//Deck Handover
				return new Category(1, 1,'Deck Handover', new Collection());
			}
			if($categoryID == 2)
			{
				//Lift & Stair Boxes
				return new Category(1, 2,'Lift & Stair Boxes', new Collection());
			}
			if($categoryID == 3)
			{
				return new Category(1, 3,'Pre Pour Checklist', new Collection());
			}
			if($categoryID == 4)
			{
				return new Category(1, 4,'Stairs', new Collection());
			}
			if($categoryID == 5)
			{
				return new Category(1, 5,'Verticals', new Collection());
			}
				
		}
	
		function getCategoryOptions($projectID, $categoryID)
		{
			$categoryOptions = new Collection;
			
			//TODO replace with call to DB
			if($categoryID == 1)
			{
				//Deck Handover
				$categoryOptions->add(new CategoryOption(1,1,1,1,'Location','','TEXT','',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,1,2,2,'Submitted By','','USERLIST','',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,1,3,3,'Formwork deck fully complete with no gaps or holes','','RADIO','Yes,No,N/A',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,1,4,4,'All decking panels and formwork sheets firmly secured','','RADIO','Yes,No,N/A',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,1,5,5,'Edge boards complete and firmly secured','','RADIO','Yes,No,N/A',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,1,6,6,'Edge protection in place and adequate','','RADIO','Yes,No,N/A',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,1,7,7,'Scaffold and perimeter safety screens in place with no gaps','','RADIO','Yes,No,N/A',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,1,8,8,'Deck fully extends to edge of scaffold','','RADIO','Yes,No,N/A',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,1,9,9,'Hand railing complete, firmly fixed and of adequate strength','','RADIO','Yes,No,N/A',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,1,10,10,'Steel safety mesh installed in all service penetrations ','','RADIO','Yes,No,N/A',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,1,11,11,'All penetrations covered, secured and clearly stencilled ','','RADIO','Yes,No,N/A',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,1,12,12,'Soffit surveyed','','RADIO','Yes,No,N/A',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,1,13,13,'Fencing/cordons in place in areas not ready for handover','','RADIO','Yes,No,N/A',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,1,14,14,'Required signage in place in areas not ready for handover','','RADIO','Yes,No,N/A',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,1,15,15,'Safe access available for other trades at entry points of deck','','RADIO','Yes,No,N/A',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,1,16,16,'Slip/trip hazards removed (excess materials, timber etc.)','','RADIO','Yes,No,N/A',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,1,17,17,'Deck swept and cleaned of all debris','','RADIO','Yes,No,N/A',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,1,18,18,'Engineer Sign off','','RADIO','Yes,No,N/A',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,1,19,19,'Comments','','TEXTAREA','',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,1,20,20,'I confirm that the specified deck is safe to use and is structurally adequate to support site personnel and design working loads (structural certificate to be provided by structural engineer prior to pour).','The submitter of this report has confirmed that the specified deck is safe to use and is structurally adequate to support site personnel and design working loads (structural certificate to be provided by structural engineer prior to pour).','CONFIRM','',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,1,21,21,'I confirm the deck has been inspected to ensure that all required actions and controls have been implemented.','The person who has verified this report has confirmed the deck has been inspected to ensure that all required actions and controls have been implemented.','CONFIRM','',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,1,22,22,'Verified By','','TEXT','',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,1,23,23,'Verified On','','DATETIME','',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,1,24,24,'Photos','','PHOTOS','',true,'','',''));
			}
			
			return $categoryOptions;
		}
	}

?>