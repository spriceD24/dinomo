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
			$categories->add(new Category(1, 5,'Verticals', new Collection()));
				
			$categories2 = new Collection();
				
			//replace with call to Database
			$categories2->add(new Category(2, 6,'Deck Handover', new Collection()));
			$categories2->add(new Category(2, 7,'Lift & Stair Boxes', new Collection()));
			$categories2->add(new Category(2, 8,'Pre Pour Checklist', new Collection()));
			$categories2->add(new Category(2, 9,'Stairs', new Collection()));
			$categories2->add(new Category(2, 10,'Verticals', new Collection()));
			
			$projects->add(new Project(2,'WPM Block G',$categories2,'Rhodes'));
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
			if($projectID == 2)
			{
				//Zen Project
				return new Project(2, 'WPM Block G', new Collection());
			}		}

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
			if($categoryID == 6)
			{
				//Deck Handover
				return new Category(2, 6,'Deck Handover', new Collection());
			}
			if($categoryID == 7)
			{
				//Lift & Stair Boxes
				return new Category(2, 7,'Lift & Stair Boxes', new Collection());
			}
			if($categoryID == 8)
			{
				return new Category(2, 8,'Pre Pour Checklist', new Collection());
			}
			if($categoryID == 9)
			{
				return new Category(2, 9,'Stairs', new Collection());
			}
			if($categoryID == 10)
			{
				return new Category(2, 10,'Verticals', new Collection());
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
				$categoryOptions->add(new CategoryOption(1,1,21,21,'I confirm the deck has been inspected to ensure that all required actions and controls have been implemented.','The person who has submitted this report has confirmed the deck has been inspected to ensure that all required actions and controls have been implemented.','CONFIRM','',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,1,22,22,'Verified By','','TEXT','',true,'','',''));
				//$categoryOptions->add(new CategoryOption(1,1,23,23,'Verified On','','DATETIME','',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,1,24,24,'Photos','','PHOTOS','',true,'','',''));
			}
			if($categoryID == 2)
			{
				//Lift and Stair Boxes
				$categoryOptions->add(new CategoryOption(1,2,30,1,'Location','','TEXT','',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,2,31,2,'Submitted By','','USERLIST','',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,2,32,3,'Length','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,2,33,4,'Width','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,2,34,5,'Fillets','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,2,35,6,'Position','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,2,36,7,'Conduits','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,2,37,8,'Nail Heights','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,2,38,9,'Penetrations for services','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,2,39,10,'Squareness','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,2,40,11,'Working Platform','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,2,41,12,'Manholes','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,2,42,13,'Props','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,2,43,14,'Z Bars','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,2,44,15,'Access Ladders','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,2,45,16,'Foam-Filler required','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,2,46,17,'Doors Nailed','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,2,47,18,'Braces','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,2,48,19,'Pockets','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,2,49,20,'Greased/Sprayed','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,2,50,21,'Safety Caps','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,2,51,22,'Comments','','TEXTAREA','',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,1,52,23,'Photos','','PHOTOS','',true,'','',''));
			}
			if($categoryID == 3)
			{
				//Pre Pour Checklist
				$categoryOptions->add(new CategoryOption(1,3,60,1,'Location','','TEXT','',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,3,61,2,'Submitted By','','USERLIST','',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,3,63,3,'Pour No','','TEXT','',true,'','width:100px',''));
				$categoryOptions->add(new CategoryOption(1,3,64,4,'Pour Date','','DATE','',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,3,65,5,'Temperature','','TEXT','',true,'','width:100px',''));
				$categoryOptions->add(new CategoryOption(1,3,66,6,'Start of Pour','','TIME','',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,3,67,7,'Finish of Pour','','TIME','',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,3,68,8,'CAST INS','','LABEL_AREA','',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,3,69,9,'Hob for post fix spandrals and cast ins','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,3,70,10,'Construction joints / dowels','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,3,71,11,'Wet area and balcony stepdowns','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,3,72,12,'RL Deck / Edgeboard','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,3,73,13,'Overflow blockouts','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,3,74,14,'Penetrations for services','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,3,75,15,'Bond breaker applied','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,3,76,16,'Ableflex fixed','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,3,77,17,'Sheer Keys','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,3,78,18,'Pegs Removed','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,3,79,19,'Drip groove - Pre cast fillets','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,3,80,20,'Precast fillet','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,3,81,21,'CJ fillet','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,3,82,22,'Foam filler to CJ','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,3,83,23,'RI on all external hob heights marked','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,3,84,24,'PRE-CONSTRUCTION','','LABEL','DRAWING NO.',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,3,88,28,'Architects GA/RCP','','TEXT','',true,'','width:150px',''));
				$categoryOptions->add(new CategoryOption(1,3,89,29,'Architects CS/WS','','TEXT','',true,'','width:150px',''));
				$categoryOptions->add(new CategoryOption(1,3,90,30,'Structural Outline','','TEXT','',true,'','width:150px',''));
				$categoryOptions->add(new CategoryOption(1,3,91,31,'Formwork Certificate','','TEXT','',true,'','width:150px',''));
				$categoryOptions->add(new CategoryOption(1,3,92,32,'Comments','','TEXTAREA','',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,3,93,33,'Verified By','','TEXT','',true,'','',''));
				//$categoryOptions->add(new CategoryOption(1,1,23,23,'Verified On','','DATETIME','',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,3,94,34,'Photos','','PHOTOS','',true,'','',''));
			}
			if($categoryID == 4)
			{
				//Stairs
				$categoryOptions->add(new CategoryOption(1,4,100,1,'Location','','TEXT','',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,4,101,2,'Submitted By','','USERLIST','',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,4,103,3,'Pour No','','TEXT','',true,'','width:100px',''));
				$categoryOptions->add(new CategoryOption(1,4,104,4,'Stairmaster correct position','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,4,105,5,'Edge boards braced','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,4,106,6,'Centre of stairmaster flights propped','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,4,107,7,'Pins and props','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,4,108,8,'Z Bars in walls tight','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,4,109,9,'Stairs cleaned','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,4,110,10,'I confirm that the specified stairs are safe to use and are structurally adequate to support site personnel and design and working loads (structural certificate to be provided by structural engineer prior to pour).','The submitter of this report has confirmed that the specified stairs are safe to use and are structurally adequate to support site personnel and design and working loads (structural certificate to be provided by structural engineer prior to pour).','CONFIRM','',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,4,112,11,'I have inspected the Stairs and confirm that all required actions and controls have been implemented.','The person who has submitted this report has inspected the Stairs and confirms that all required actions and controls have been implemented.','CONFIRM','',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,4,113,12,'Verified By','','TEXT','',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,4,114,13,'Photos','','PHOTOS','',true,'','',''));
			}
			if($categoryID == 5)
			{
				//Stairs
				$categoryOptions->add(new CategoryOption(1,5,120,1,'Location','','TEXT','',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,5,121,2,'Submitted By','','USERLIST','',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,5,122,3,'Length','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,5,123,4,'Width','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,5,124,5,'Fillets','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,5,125,6,'Spacers','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,5,126,7,'Nail heights sprayed','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,5,127,8,'Penetrations for services','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,5,128,9,'Bond breaker applied','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,5,129,10,'Plumb','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,5,130,11,'Post pour plumb','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,5,131,12,'Rebates','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,5,132,13,'Bracing','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,5,133,14,'Props','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,5,134,15,'She-Bolts tied off','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,5,135,16,'Working platform','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,5,136,17,'Access Ladder','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,5,137,18,'Foam filler required','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,5,138,19,'Z bars tied off','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,5,139,20,'Stop end nailed','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,5,140,12,'Verified By','','TEXT','',true,'','',''));
				$categoryOptions->add(new CategoryOption(1,5,141,13,'Photos','','PHOTOS','',true,'','',''));
			}
			
			if($categoryID == 6)
			{
				//Deck Handover
				$categoryOptions->add(new CategoryOption(2,6,111,1,'Location','','TEXT','',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,6,112,2,'Submitted By','','USERLIST','',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,6,113,3,'Formwork deck fully complete with no gaps or holes','','RADIO','Yes,No,N/A',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,6,114,4,'All decking panels and formwork sheets firmly secured','','RADIO','Yes,No,N/A',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,6,115,5,'Edge boards complete and firmly secured','','RADIO','Yes,No,N/A',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,6,116,6,'Edge protection in place and adequate','','RADIO','Yes,No,N/A',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,6,117,7,'Scaffold and perimeter safety screens in place with no gaps','','RADIO','Yes,No,N/A',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,6,118,8,'Deck fully extends to edge of scaffold','','RADIO','Yes,No,N/A',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,6,119,9,'Hand railing complete, firmly fixed and of adequate strength','','RADIO','Yes,No,N/A',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,6,1110,10,'Steel safety mesh installed in all service penetrations ','','RADIO','Yes,No,N/A',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,6,1111,11,'All penetrations covered, secured and clearly stencilled ','','RADIO','Yes,No,N/A',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,6,1112,12,'Soffit surveyed','','RADIO','Yes,No,N/A',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,6,1113,13,'Fencing/cordons in place in areas not ready for handover','','RADIO','Yes,No,N/A',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,6,1114,14,'Required signage in place in areas not ready for handover','','RADIO','Yes,No,N/A',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,6,1115,15,'Safe access available for other trades at entry points of deck','','RADIO','Yes,No,N/A',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,6,1116,16,'Slip/trip hazards removed (excess materials, timber etc.)','','RADIO','Yes,No,N/A',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,6,1117,17,'Deck swept and cleaned of all debris','','RADIO','Yes,No,N/A',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,6,1118,18,'Engineer Sign off','','RADIO','Yes,No,N/A',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,6,1119,19,'Comments','','TEXTAREA','',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,6,1120,20,'I confirm that the specified deck is safe to use and is structurally adequate to support site personnel and design working loads (structural certificate to be provided by structural engineer prior to pour).','The submitter of this report has confirmed that the specified deck is safe to use and is structurally adequate to support site personnel and design working loads (structural certificate to be provided by structural engineer prior to pour).','CONFIRM','',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,6,1121,21,'I confirm the deck has been inspected to ensure that all required actions and controls have been implemented.','The person who has submitted this report has confirmed the deck has been inspected to ensure that all required actions and controls have been implemented.','CONFIRM','',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,6,1122,22,'Verified By','','TEXT','',true,'','',''));
				//$categoryOptions->add(new CategoryOption(2,6,1123,23,'Verified On','','DATETIME','',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,6,1124,24,'Photos','','PHOTOS','',true,'','',''));
			}
			if($categoryID == 7)
			{
				//Lift and Stair Boxes
				$categoryOptions->add(new CategoryOption(2,7,1130,1,'Location','','TEXT','',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,7,1131,2,'Submitted By','','USERLIST','',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,7,1132,3,'Length','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,7,1133,4,'Width','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,7,1134,5,'Fillets','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,7,1135,6,'Position','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,7,1136,7,'Conduits','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,7,1137,8,'Nail Heights','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,7,1138,9,'Penetrations for services','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,7,1139,10,'Squareness','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,7,1140,11,'Working Platform','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,7,1141,12,'Manholes','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,7,1142,13,'Props','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,7,1143,14,'Z Bars','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,7,1144,15,'Access Ladders','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,7,1145,16,'Foam-Filler required','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,7,1146,17,'Doors Nailed','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,7,1147,18,'Braces','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,7,1148,19,'Pockets','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,7,1149,20,'Greased/Sprayed','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,7,1150,21,'Safety Caps','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,7,1151,22,'Comments','','TEXTAREA','',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,6,1152,23,'Photos','','PHOTOS','',true,'','',''));
			}
			if($categoryID == 8)
			{
				//Pre Pour Checklist
				$categoryOptions->add(new CategoryOption(2,8,1160,1,'Location','','TEXT','',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,8,1161,2,'Submitted By','','USERLIST','',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,8,1163,3,'Pour No','','TEXT','',true,'','width:100px',''));
				$categoryOptions->add(new CategoryOption(2,8,1164,4,'Pour Date','','DATE','',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,8,1165,5,'Temperature','','TEXT','',true,'','width:100px',''));
				$categoryOptions->add(new CategoryOption(2,8,1166,6,'Start of Pour','','TIME','',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,8,1167,7,'Finish of Pour','','TIME','',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,8,1168,8,'CAST INS','','LABEL_AREA','',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,8,1169,9,'Hob for post fix spandrals and cast ins','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,8,1170,10,'Construction joints / dowels','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,8,1171,11,'Wet area and balcony stepdowns','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,8,1172,12,'RL Deck / Edgeboard','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,8,1173,13,'Overflow blockouts','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,8,1174,14,'Penetrations for services','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,8,1175,15,'Bond breaker applied','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,8,1176,16,'Ableflex fixed','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,8,1177,17,'Sheer Keys','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,8,1178,18,'Pegs Removed','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,8,1179,19,'Drip groove - Pre cast fillets','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,8,1180,20,'Precast fillet','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,8,1181,21,'CJ fillet','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,8,1182,22,'Foam filler to CJ','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,8,1183,23,'RI on all external hob heights marked','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,8,1184,24,'PRE-CONSTRUCTION','','LABEL','DRAWING NO.',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,8,1188,28,'Architects GA/RCP','','TEXT','',true,'','width:150px',''));
				$categoryOptions->add(new CategoryOption(2,8,1189,29,'Architects CS/WS','','TEXT','',true,'','width:150px',''));
				$categoryOptions->add(new CategoryOption(2,8,1190,30,'Structural Outline','','TEXT','',true,'','width:150px',''));
				$categoryOptions->add(new CategoryOption(2,8,1191,31,'Formwork Certificate','','TEXT','',true,'','width:150px',''));
				$categoryOptions->add(new CategoryOption(2,8,1192,32,'Comments','','TEXTAREA','',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,8,1193,33,'Verified By','','TEXT','',true,'','',''));
				//$categoryOptions->add(new CategoryOption(2,6,1123,23,'Verified On','','DATETIME','',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,8,1194,34,'Photos','','PHOTOS','',true,'','',''));
			}
			if($categoryID == 9)
			{
				//Stairs
				$categoryOptions->add(new CategoryOption(2,9,11100,1,'Location','','TEXT','',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,9,11101,2,'Submitted By','','USERLIST','',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,9,11103,3,'Pour No','','TEXT','',true,'','width:100px',''));
				$categoryOptions->add(new CategoryOption(2,9,11104,4,'Stairmaster correct position','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,9,11105,5,'Edge boards braced','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,9,11106,6,'Centre of stairmaster flights propped','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,9,11107,7,'Pins and props','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,9,11108,8,'Z Bars in walls tight','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,9,11109,9,'Stairs cleaned','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,9,11110,10,'I confirm that the specified stairs are safe to use and are structurally adequate to support site personnel and design and working loads (structural certificate to be provided by structural engineer prior to pour).','The submitter of this report has confirmed that the specified stairs are safe to use and are structurally adequate to support site personnel and design and working loads (structural certificate to be provided by structural engineer prior to pour).','CONFIRM','',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,9,11112,11,'I have inspected the Stairs and confirm that all required actions and controls have been implemented.','The person who has submitted this report has inspected the Stairs and confirms that all required actions and controls have been implemented.','CONFIRM','',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,9,11113,12,'Verified By','','TEXT','',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,9,11114,13,'Photos','','PHOTOS','',true,'','',''));
			}
			if($categoryID == 10)
			{
				//Stairs
				$categoryOptions->add(new CategoryOption(2,10,11120,1,'Location','','TEXT','',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,10,11121,2,'Submitted By','','USERLIST','',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,10,11122,3,'Length','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,10,11123,4,'Width','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,10,11124,5,'Fillets','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,10,11125,6,'Spacers','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,10,11126,7,'Nail heights sprayed','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,10,11127,8,'Penetrations for services','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,10,11128,9,'Bond breaker applied','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,10,11129,10,'Plumb','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,10,11130,11,'Post pour plumb','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,10,11131,12,'Rebates','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,10,11132,13,'Bracing','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,10,11133,14,'Props','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,10,11134,15,'She-Bolts tied off','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,10,11135,16,'Working platform','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,10,11136,17,'Access Ladder','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,10,11137,18,'Foam filler required','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,10,11138,19,'Z bars tied off','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,10,11139,20,'Stop end nailed','','RADIO','Yes,No',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,10,11140,12,'Verified By','','TEXT','',true,'','',''));
				$categoryOptions->add(new CategoryOption(2,10,11141,13,'Photos','','PHOTOS','',true,'','',''));
			}
				
				
			return $categoryOptions;
		}
	}

?>