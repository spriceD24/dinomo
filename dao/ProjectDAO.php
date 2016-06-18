<?php include_once("util/CollectionsUtil.php"); ?>
<?php include_once("dao/model/Category.php"); ?>
<?php include_once("dao/model/CategoryOption.php"); ?>
<?php include_once("dao/model/Project.php"); ?>
<?php include_once("util/DBUtil.php"); ?>
<?php include_once("util/StringUtils.php"); ?>
<?php include_once("util/DateUtil.php"); ?>
<?php include_once("util/LogUtil.php"); ?>
<?php

class ProjectDAO {

	function getAllProjectsLite($clientID) 
	{
		$projects = new Collection ();
		$dbUtil = new DBUtil ();
		$conn = $dbUtil->getDBConnection();
		$project;
		$query = "SELECT * FROM Project where DeleteFlag = 0 and ClientID = ".$clientID." order by Name ";
		$result = $conn->query($query);
		LogUtil::debug ( 'ProjectDAO', 'getAllProjectsLite: Executing '.$query.', num results = '.$result->num_rows);
		if ($result->num_rows > 0) {
			// output data of each row
			while($row = $result->fetch_assoc())
			{
				$project = new Project ( $row["ID"], $row["ClientID"], $row["Name"] );
				$project->categories = $this->getCategories($project->projectID);
				$projects->add($project);
			}
		}
		$conn->close();
		return $projects;
	}
	
	function getProject($projectID) {
		$dbUtil = new DBUtil ();
		$conn = $dbUtil->getDBConnection();
		$max = 1;
		$project;
		$query = "SELECT * FROM Project where DeleteFlag = 0 and ID = ".$projectID;
		$result = $conn->query($query);
		LogUtil::debug ( 'ProjectDAO', 'getProject: Executing '.$query.', num results = '.$result->num_rows);
		if ($result->num_rows > 0) {
			// output data of each row
			while($row = $result->fetch_assoc())
			{
				$project = new Project ( $row["ID"], $row["ClientID"], $row["Name"] );
			}
		}
		$conn->close();
		return $project;
	}
	
	
	function getCategories($projectID) {
		$categories = new Collection ();
		$dbUtil = new DBUtil ();
		$conn = $dbUtil->getDBConnection();
		$max = 1;
		$project;
		$query = "SELECT * FROM Category where DeleteFlag = 0 and ProjectID = ".$projectID." order by Name ";
		$result = $conn->query($query);
		LogUtil::debug ( 'ProjectDAO', 'getCategories: Executing '.$query.', num results = '.$result->num_rows);
		if ($result->num_rows > 0) {
			// output data of each row
			while($row = $result->fetch_assoc())
			{
				$categories->add(new Category ($projectID,$row["ID"], $row["Name"],new Collection()));
			}
		}
		$conn->close();
		return $categories;
	}
	
	function getCategory($projectID, $categoryID) {
		$category;
		$dbUtil = new DBUtil ();
		$conn = $dbUtil->getDBConnection();
		$max = 1;
		$project;
		$query = "SELECT * FROM Category where DeleteFlag = 0 and ProjectID = ".$projectID." and ID =  ".$categoryID;
		$result = $conn->query($query);
		LogUtil::debug ( 'ProjectDAO', 'getCategory: Executing '.$query.', num results = '.$result->num_rows);
		if ($result->num_rows > 0) {
			// output data of each row
			while($row = $result->fetch_assoc())
			{
				$category = new Category ($projectID,$row["ID"], $row["Name"],new Collection());
			}
		}
		$conn->close();
		return $category;
	}
	
	function getCategoryOptions($projectID, $categoryID) {
		$categoryOptions = new Collection ();
		$dbUtil = new DBUtil ();
		$conn = $dbUtil->getDBConnection();
		$max = 1;
		$project;
		$query = "SELECT * FROM CategoryOption where DeleteFlag = 0 and ProjectID = ".$projectID." and CategoryID = ".$categoryID." order by CategoryOrder ";
		$result = $conn->query($query);
		LogUtil::debug ( 'ProjectDAO', 'getCategoryOptions: Executing '.$query.', num results = '.$result->num_rows);
		if ($result->num_rows > 0) {
			// output data of each row
			while($row = $result->fetch_assoc())
			{
				$optionSettings=array();
				if(!empty($row["OptionSettings"]) && isset($row["OptionSettings"]))
				{
					$optionSettings=json_decode($row["OptionSettings"],true);
				}
				
				$categoryOptions->add(new CategoryOption($projectID,$categoryID,$row["ID"],$row["CategoryOrder"],$row["Title"],$row["FormType"],$row["IsRequired"] == 1,$optionSettings));
			}
		}
		$conn->close();
		return $categoryOptions;
	}
	
	function saveProject($insertedByID,$project)
	{
		LogUtil::debug ( 'ProjectDAO', 'Executing Save Project');
		$nextProjectID = $this->getNextProjectID();
		$dbUtil = new DBUtil ();
		$conn = $dbUtil->getDBConnection();
		//mysql_real_escape_string(
		date_default_timezone_set ( 'Australia/Sydney' );
		$dateUtil = new DateUtil();
	
		$sql = "insert into Project(ID,ClientID,Name,RecordDate,CreatedBy,LastUpdated,LastUpdatedBy,DeleteFlag) ";
		$sql = $sql." values (".$nextProjectID.",".$project->clientID.",'".StringUtils::escapeDB($project->projectName)."',now(),".$insertedByID.",now(),".$insertedByID.",0) ";
		LogUtil::debug ( 'ProjectDAO', 'Saving project sql = '.$sql);
		if ($conn->query($sql) === TRUE) {
			LogUtil::debug ( 'ProjectDAO', 'New record created successfully');
		} else {
			LogUtil::debug ( 'ProjectDAO',"Error: " . $sql . "<br>" . $conn->error);
		}	
		$conn->close();
		return $nextProjectID;
	}
	
	function saveCategory($insertedByID,$projectID,$category)
	{
		LogUtil::debug ( 'ProjectDAO', 'Executing Save Category');
		$nextCategoryID = $this->getNextCategoryID($projectID);
		$dbUtil = new DBUtil ();
		$conn = $dbUtil->getDBConnection();
		//mysql_real_escape_string(
		date_default_timezone_set ( 'Australia/Sydney' );
		$dateUtil = new DateUtil();
	
		$sql = "insert into Category(ID,ProjectID,Name,RecordDate,CreatedBy,LastUpdated,LastUpdatedBy,DeleteFlag) ";
		$sql = $sql." values (".$nextCategoryID.",".$projectID.",'".StringUtils::escapeDB($category->categoryName)."',now(),".$insertedByID.",now(),".$insertedByID.",0) ";
		LogUtil::debug ( 'ProjectDAO', 'Saving category sql = '.$sql);
		//now()
		if ($conn->query($sql) === TRUE) {
			LogUtil::debug ( 'ProjectDAO', 'New record created successfully');
		} else {
			LogUtil::debug ( 'ProjectDAO',"Error: " . $sql . "<br>" . $conn->error);
		}	
		$conn->close();
		return $nextCategoryID;
	}
	
	function saveCategoryOption($insertedByID,$projectID,$categoryID, $categoryOption)
	{
		LogUtil::debug ( 'ProjectDAO', 'Executing Save Category Option');
		$nextCategoryID = $this->getNextCategoryOptionID($projectID, $categoryID);
		$dbUtil = new DBUtil ();
		$conn = $dbUtil->getDBConnection();
		//mysql_real_escape_string(
		date_default_timezone_set ( 'Australia/Sydney' );
		$dateUtil = new DateUtil();
		$required = 0;
		if($categoryOption->isRequired)
		{
			$required = 1;
		}
		$options = '';
		if(!empty($categoryOption->optionSettings))
		{
			$options = StringUtils::escapeDB(json_encode($categoryOption->optionSettings));
		}
		$sql = "insert into CategoryOption(ID,CategoryID,ProjectID,CategoryOrder,Title,FormType,IsRequired,OptionSettings,RecordDate,CreatedBy,LastUpdated,LastUpdatedBy,DeleteFlag) ";
		$sql = $sql." values (".$nextCategoryID.",".$categoryID.",".$projectID.",".$categoryOption->order.",'".StringUtils::escapeDB($categoryOption->title);
		$sql = $sql."','".$categoryOption->formType."',".$required.",'".$options."',now(),".$insertedByID.",now(),".$insertedByID.",0) ";
		LogUtil::debug ( 'ProjectDAO', 'Saving category option sql = '.$sql);
		//now()
		if ($conn->query($sql) === TRUE) {
			LogUtil::debug ( 'ProjectDAO', 'New record created successfully');
		} else {
			LogUtil::debug ( 'ProjectDAO',"Error: " . $sql . "<br>" . $conn->error);
		}	
		$conn->close();
		return $nextCategoryID;
	}
	
	private function getNextProjectID()
	{
		$dbUtil = new DBUtil ();
		$conn = $dbUtil->getDBConnection();
		$max = 1;
		$result = $conn->query("SELECT MAX(ID) AS max_id FROM Project");
		if ($result->num_rows > 0) {
			// output data of each row
			while($row = $result->fetch_assoc())
			{
				$max= intval($row["max_id"])+1;
			}
		}
		$conn->close();
		return $max;
	}

	private function getNextCategoryID($projectID)
	{
		$dbUtil = new DBUtil ();
		$conn = $dbUtil->getDBConnection();
		$max = 1;
		$result = $conn->query("SELECT MAX(ID) AS max_id FROM Category where ProjectID = ".$projectID);
		if ($result->num_rows > 0) {
			// output data of each row
			while($row = $result->fetch_assoc())
			{
				$max= intval($row["max_id"])+1;
			}
		}
		$conn->close();
		return $max;
	}	

	private function getNextCategoryOptionID($projectID,$categoryID)
	{
		$dbUtil = new DBUtil ();
		$conn = $dbUtil->getDBConnection();
		$max = 1;
		$result = $conn->query("SELECT MAX(ID) AS max_id FROM CategoryOption where ProjectID = ".$projectID." and CategoryID = ".$categoryID);
		if ($result->num_rows > 0) {
			// output data of each row
			while($row = $result->fetch_assoc())
			{
				$max= intval($row["max_id"])+1;
			}
		}
		$conn->close();
		return $max;
	}	
	function deleteProject($projectID) {
		// replace with call to DB
		LogUtil::debug ( 'ProjectDAO', 'Delete Project - project ID = '.$projectID);
		$dbUtil = new DBUtil ();
		$conn = $dbUtil->getDBConnection();
		$conn->query("Update Project set DeleteFlag = 1 where ID = ".$projectID);
		$conn->query("Update Category set DeleteFlag = 1 where ProjectID = ".$projectID);
		$conn->query("Update CategoryOption set DeleteFlag = 1 where ProjectID = ".$projectID);
		$conn->close();
		return true;
	}	

	function deleteCategory($projectID,$categoryID) {
		// replace with call to DB
		LogUtil::debug ( 'ProjectDAO', 'Delete Project - project ID = '.$projectID.', category id = '.$categoryID);
		$dbUtil = new DBUtil ();
		$conn = $dbUtil->getDBConnection();
		$conn->query("Update CategoryOption set DeleteFlag = 1 where ProjectID = ".$projectID." and CategoryID = ".$categoryID);
		$conn->close();
		return true;
	}

	function deleteCategoryOptions($projectID,$categoryID) {
		LogUtil::debug ( 'ProjectDAO', 'Delete Project - project ID = '.$projectID.', category id = '.$categoryID);
		$dbUtil = new DBUtil ();
		$conn = $dbUtil->getDBConnection();
		$conn->query("Update Category set DeleteFlag = 1 where ProjectID = ".$projectID." and CategoryID = ".$categoryID);
		$conn->query("Update CategoryOption set DeleteFlag = 1 where ProjectID = ".$projectID." and CategoryID = ".$categoryID);
		$conn->close();
		return true;
	}
	
	//temp function
	function getCategoryOptionsFIXED($projectID, $categoryID) {
		$categoryOptions = new Collection ();
	
		// TODO replace with call to DB
		if ($categoryID == 1) {
			// Deck Handover
			$categoryOptions->add ( new CategoryOption ( 1, 1, 1, 1, 'Core No', 'TEXT', true, array("style"=>"width:150px")));
			$categoryOptions->add ( new CategoryOption ( 1, 1, 2, 2, 'Level No', 'TEXT', true, array("style"=>"width:150px")));
			$categoryOptions->add ( new CategoryOption ( 1, 1, 3333, 33333, 'Is it full deck handover', 'RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No","commentOn"=>true,"commentTitle"=>"Provide Details Of Handover")))));
			$categoryOptions->add ( new CategoryOption ( 1, 1, 43333, 433333, 'All props/pins checked', 'RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No","cannotProceedOn"=>true)))));
			$categoryOptions->add ( new CategoryOption ( 1, 1, 3, 3, 'Formwork deck fully complete with no gaps or holes', 'RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 1, 4, 4, 'All decking panels and formwork sheets firmly secured', 'RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 1, 5, 5, 'Edge boards complete and firmly secured', 'RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 1, 6, 6, 'Edge protection in place and adequate', 'RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 1, 7, 7, 'Scaffold and/or perimeter safety screens in place with no gaps', 'RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No","cannotProceedOn"=>true)))));
			$categoryOptions->add ( new CategoryOption ( 1, 1, 8, 8, 'Deck fully extends to edge of scaffold', 'RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No","commentOn"=>true,"commentTitle"=>"Please provide more details"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 1, 9, 9, 'Hand railing complete, firmly fixed, of adequate strength and nailed or screwed', 'RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No","cannotProceedOn"=>true)))));
			$categoryOptions->add ( new CategoryOption ( 1, 1, 10, 10, 'Steel safety mesh installed in all service penetrations ', 'RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 1, 11, 11, 'All penetrations covered, screwed down and clearly stencilled ', 'RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No","cannotProceedOn"=>true)))));
			$categoryOptions->add ( new CategoryOption ( 1, 1, 12, 12, 'Soffit surveyed', 'RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 1, 13, 13, 'Fencing/cordons in place in areas not ready for handover', 'RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 1, 14, 14, 'Required signage in place in areas not ready for handover', 'RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 1, 15, 15, 'Safe access available for other trades at entry points of deck', 'RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 1, 16, 16, 'Slip/trip hazards removed (excess materials, timber etc.)', 'RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 1, 17, 17, 'Deck swept and cleaned of all debris', 'RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 1, 18, 18, 'Engineer Sign off', 'RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No","commentOn"=>true,"commentTitle"=>"Why has sign-off not been completed"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 1, 170101, 170101, 'Is formwork surrounding columns backpropped', 'RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No","cannotProceedOn"=>true)))));
			$categoryOptions->add ( new CategoryOption ( 1, 1, 170199, 170199, 'Is the deck completely backpropped for landing material', 'RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No","cannotProceedOn"=>true)))));
			$categoryOptions->add ( new CategoryOption ( 1, 1, 11700, 11700, 'Highlighted Drawings attached','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 1, 11701, 11701, 'Check beam/stepdowns set out','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 1, 11702, 11702, 'Safe access to the deck','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 1, 11184, 11124, 'CONSTRUCTION', 'LABEL', true, array("label"=>"DRAWING NO.")) );
			$categoryOptions->add ( new CategoryOption ( 1, 1, 11188, 11128, 'Architects GA/RCP', 'TEXT', true, array("style"=>"width:150px") ) );
			$categoryOptions->add ( new CategoryOption ( 1, 1, 11189, 11129, 'Architects CS/WS', 'TEXT', true, array("style"=>"width:150px") ) );
			$categoryOptions->add ( new CategoryOption ( 1, 1, 11190, 11130, 'Structural Outline', 'TEXT', true, array("style"=>"width:150px") ) );
			$categoryOptions->add ( new CategoryOption ( 1, 1, 11191, 11131, 'Formwork Certificate', 'TEXT', true, array("style"=>"width:150px") ) );
			$categoryOptions->add ( new CategoryOption ( 1, 1, 19, 19, 'Comments', 'TEXTAREA', true ) );
			$categoryOptions->add ( new CategoryOption ( 1, 1, 20, 20, 'I confirm that the specified deck is safe to use and is structurally adequate to support site personnel and design working loads (structural certificate to be provided by structural engineer prior to pour).', 'CONFIRM',  true,  array("pdfTitle"=>"The submitter of this report has confirmed that the specified deck is safe to use and is structurally adequate to support site personnel and design working loads (structural certificate to be provided by structural engineer prior to pour).") ) );
			$categoryOptions->add ( new CategoryOption ( 1, 1, 21, 21, 'I confirm the deck has been inspected to ensure that all required actions and controls have been implemented.','CONFIRM', true,  array("pdfTitle"=>"The submitter of this report has confirmed the deck has been inspected to ensure that all required actions and controls have been implemented.")) );
			// $categoryOptions->add(new CategoryOption(1,1,23,23,'Verified On','','DATETIME','',true,'','',''));
			$categoryOptions->add ( new CategoryOption ( 1, 1, 24, 24, 'Photos', 'PHOTOS', true ) );
		}
		if ($categoryID == 2) {
			// Lift and Stair Boxes
			$categoryOptions->add ( new CategoryOption ( 1, 2, 30, 1, 'Core No', 'TEXT', true, array("style"=>"width:150px")));
			$categoryOptions->add ( new CategoryOption ( 1, 2, 31, 2, 'Level No', 'TEXT', true, array("style"=>"width:150px")));
			$categoryOptions->add ( new CategoryOption ( 1, 2, 32, 3, 'Length Correct','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 2, 33, 4, 'Width Correct','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 2, 34, 5, 'Fillets Correct Position','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 2, 35, 6, 'Is Box in Position','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No","commentOn"=>true,"commentTitle"=>"Please provide more details"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 2, 36, 7, 'Conduits','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 2, 37, 8, 'Height Nails','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 2, 38, 9, 'Penetrations for services','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 2, 39, 10, 'Is the Box Square','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 2, 40, 11, 'Working Platform Installed and Safe','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 2, 41, 12, 'Manholes Are Covered','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 2, 42, 13, 'Props in Place','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 2, 43, 14, 'Z Bars','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 2, 44, 15, 'Access Ladders Installed and Secured','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 2, 45, 16, 'Foam-Filler required','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 2, 46, 17, 'Doors Nailed','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 2, 47, 18, 'Braces','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 2, 48, 19, 'Pockets in Correct Position','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 2, 49, 20, 'Greased/Sprayed','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 2, 50, 21, 'Safety Caps','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 2, 52, 22, 'Set out of grid lines','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 2, 1184, 24, 'CONSTRUCTION', 'LABEL', true, array("label"=>"DRAWING NO.")) );
			$categoryOptions->add ( new CategoryOption ( 1, 2, 1188, 28, 'Architects GA/RCP', 'TEXT', true, array("style"=>"width:150px") ) );
			$categoryOptions->add ( new CategoryOption ( 1, 2, 1189, 29, 'Architects CS/WS', 'TEXT', true, array("style"=>"width:150px") ) );
			$categoryOptions->add ( new CategoryOption ( 1, 2, 1190, 30, 'Structural Outline', 'TEXT', true, array("style"=>"width:150px") ) );
			$categoryOptions->add ( new CategoryOption ( 1, 2, 1191, 31, 'Formwork Certificate', 'TEXT', true, array("style"=>"width:150px") ) );
			$categoryOptions->add ( new CategoryOption ( 1, 2, 53, 23, 'Comments', 'TEXTAREA', true ) );
			$categoryOptions->add ( new CategoryOption ( 1, 1, 54, 24, 'Photos', 'PHOTOS', true ) );
		}
		if ($categoryID == 3) {
			// Pre Pour Checklist
			$categoryOptions->add ( new CategoryOption ( 1, 3, 60, 1, 'Core No', 'TEXT', true, array("style"=>"width:150px")));
			$categoryOptions->add ( new CategoryOption ( 1, 3, 61, 2, 'Level No', 'TEXT', true, array("style"=>"width:150px")));
			$categoryOptions->add ( new CategoryOption ( 1, 3, 63, 3, 'Pour No', 'TEXT', true, array("style"=>"width:150px") ) );
			$categoryOptions->add ( new CategoryOption ( 1, 3, 64, 4, 'Pour Date', 'DATE', true) );
			$categoryOptions->add ( new CategoryOption ( 1, 3, 65, 5, 'Temperature', 'TEXT', true, array("style"=>"width:100px") ) );
			$categoryOptions->add ( new CategoryOption ( 1, 3, 66, 6, 'Start of Pour', '', 'TIME', '', true, '', '', '' ) );
			$categoryOptions->add ( new CategoryOption ( 1, 3, 67, 7, 'Finish of Pour', '', 'TIME', '', true, '', '', '' ) );
			$categoryOptions->add ( new CategoryOption ( 1, 3, 743333,7433333, 'Are all props/pins checked', 'RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No","cannotProceedOn"=>true)))));
			$categoryOptions->add ( new CategoryOption ( 1, 3, 68, 8, 'CAST INS', '', 'LABEL_AREA', '', true, '', '', '' ) );
			$categoryOptions->add ( new CategoryOption ( 1, 3, 69, 9, 'Hob for post fix spandrals and cast ins','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 3, 70, 10, 'Have you checked Construction Joints details','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No","cannotProceedOn"=>true)))));
			$categoryOptions->add ( new CategoryOption ( 1, 3, 71, 11, 'Wet area and balcony stepdowns in correct position and are they correct','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 3, 7221, 12123, 'Is RL of the Deck correct','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 3, 7215, 124, 'Is Edgeboard in correct position','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 3, 73, 13, 'Overflow blockouts installed in correct position','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 3, 74, 14, 'Are all Penetrations installed','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 3, 75, 15, 'Bond breaker applied','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 3, 76, 16, 'Ableflex fixed','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 3, 77, 17, 'Are correct Sheer Keys installed','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 3, 78, 18, 'Any excess plywood or timber removed','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 3, 79, 19, 'Drip groove installed on balconies','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 3, 80, 20, 'Precast fillet','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 3, 81, 21, 'CJ fillet','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 3, 82, 22, 'Foam filler to CJ','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 3, 83, 23, 'RI on all external hob heights marked','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 3, 84, 24, 'CONSTRUCTION', 'LABEL', true, array("label"=>"DRAWING NO.")) );
			$categoryOptions->add ( new CategoryOption ( 1, 3, 88, 28, 'Architects GA/RCP', 'TEXT', true, array("style"=>"width:150px") ) );
			$categoryOptions->add ( new CategoryOption ( 1, 3, 89, 29, 'Architects CS/WS', 'TEXT', true, array("style"=>"width:150px") ) );
			$categoryOptions->add ( new CategoryOption ( 1, 3, 90, 30, 'Structural Outline', 'TEXT', true, array("style"=>"width:150px") ) );
			$categoryOptions->add ( new CategoryOption ( 1, 3, 91, 31, 'Formwork Certificate', 'TEXT', true, array("style"=>"width:150px") ) );
			$categoryOptions->add ( new CategoryOption ( 1, 3, 92, 32, 'Comments', 'TEXTAREA', true ) );
			// $categoryOptions->add(new CategoryOption(1,1,23,23,'Verified On','','DATETIME','',true,'','',''));
			$categoryOptions->add ( new CategoryOption ( 1, 3, 94, 34, 'Photos', 'PHOTOS', true ) );
		}
		if ($categoryID == 4) {
			// Stairs
			$categoryOptions->add ( new CategoryOption ( 1, 4, 100, 1, 'Core No', 'TEXT', true, array("style"=>"width:150px")));
			$categoryOptions->add ( new CategoryOption ( 1, 4, 101, 2, 'Level No', 'TEXT', true, array("style"=>"width:150px")));
			$categoryOptions->add ( new CategoryOption ( 1, 4, 103, 3, 'Pour No', 'TEXT', true, array("style"=>"width:150px") ) );
			$categoryOptions->add ( new CategoryOption ( 1, 4, 104, 4, 'Stairmaster correct position','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 4, 10511, 511, 'Is the bottom and top riser in correct position','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 4, 105171, 5711, 'Checked stairmaster/handrail description and confimed is intalled in the correct location','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 4, 105, 5, 'Edge boards braced','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 4, 106, 6, 'Centre of stairmaster flights installed','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 4, 107, 7, 'Pins and props','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 4, 108, 8, 'Z Bars in walls tight','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 4, 109, 9, 'Stairs cleaned','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 4, 1184, 24, 'CONSTRUCTION', 'LABEL', true, array("label"=>"DRAWING NO.")) );
			$categoryOptions->add ( new CategoryOption ( 1, 4, 1188, 28, 'Architects GA/RCP', 'TEXT', true, array("style"=>"width:150px") ) );
			$categoryOptions->add ( new CategoryOption ( 1, 4, 1189, 29, 'Architects CS/WS', 'TEXT', true, array("style"=>"width:150px") ) );
			$categoryOptions->add ( new CategoryOption ( 1, 4, 1190, 30, 'Structural Outline', 'TEXT', true, array("style"=>"width:150px") ) );
			$categoryOptions->add ( new CategoryOption ( 1, 4, 1191, 31, 'Formwork Certificate', 'TEXT', true, array("style"=>"width:150px") ) );
			$categoryOptions->add ( new CategoryOption ( 1, 4, 110, 10, 'I confirm that the specified stairs are safe to use and are structurally adequate to support site personnel and design and working loads (structural certificate to be provided by structural engineer prior to pour).', 'CONFIRM', true,  array("pdfTitle"=>"The submitter of this report has confirmed that the specified stairs are safe to use and are structurally adequate to support site personnel and design and working loads (structural certificate to be provided by structural engineer prior to pour).")) );
			$categoryOptions->add ( new CategoryOption ( 1, 4, 112, 11, 'I have inspected the Stairs and confirm that all required actions and controls have been implemented.','CONFIRM', true, array("pdfTitle"=>"The submitter of this report has inspected the Stairs and confirms that all required actions and controls have been implemented.") ) );
			$categoryOptions->add ( new CategoryOption ( 1, 4, 113, 12, 'Comments', 'TEXTAREA', true ) );
			$categoryOptions->add ( new CategoryOption ( 1, 4, 115, 14, 'Photos', 'PHOTOS', true ) );
		}
		if ($categoryID == 5) {
			// Verticals
			$categoryOptions->add ( new CategoryOption ( 1, 5, 120, 1, 'Core No', 'TEXT', true, array("style"=>"width:150px")));
			$categoryOptions->add ( new CategoryOption ( 1, 5, 121, 2, 'Level No', 'TEXT', true, array("style"=>"width:150px")));
			$categoryOptions->add ( new CategoryOption ( 1, 5, 122, 3, 'Length Correct','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 5, 123, 4, 'Width Correct','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 5, 124, 5, 'Fillets Correct Position','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 5, 125, 6, 'Spacers','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 5, 126, 7, 'Height Nails sprayed','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 5, 127, 8, 'Penetrations for services','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 5, 128, 9, 'Bond breaker applied','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 5, 129, 10, 'Plumb','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No","commentOn"=>true,"commentTitle"=>"Please provide more details"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 5, 130, 11, 'Post pour plumb','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 5, 131, 12, 'Rebates','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 5, 132, 13, 'Bracing','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 5, 133, 14, 'Props','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 5, 134, 15, 'She-Bolts tied off','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 5, 135, 16, 'Working platform built correctly/safely','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 5, 136, 17, 'Secured top/bottom of Access Ladder','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 5, 137, 18, 'Foam filler required','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 5, 138, 19, 'Z bars tied off','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 5, 139, 20, 'Stop end nailed','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 5, 81139, 8120, 'All Walls formed','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 5, 81149, 8820, 'All Columns formed','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 5, 81159, 8920, 'Highlighted Drawings attached','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 5, 1184, 24, 'CONSTRUCTION', 'LABEL', true, array("label"=>"DRAWING NO.")) );
			$categoryOptions->add ( new CategoryOption ( 1, 5, 1188, 28, 'Architects GA/RCP', 'TEXT', true, array("style"=>"width:150px") ) );
			$categoryOptions->add ( new CategoryOption ( 1, 5, 1189, 29, 'Architects CS/WS', 'TEXT', true, array("style"=>"width:150px") ) );
			$categoryOptions->add ( new CategoryOption ( 1, 5, 1190, 30, 'Structural Outline', 'TEXT', true, array("style"=>"width:150px") ) );
			$categoryOptions->add ( new CategoryOption ( 1, 5, 1191, 31, 'Formwork Certificate', 'TEXT', true, array("style"=>"width:150px") ) );
			$categoryOptions->add ( new CategoryOption ( 1, 5, 140, 21, 'Comments', 'TEXTAREA', true ) );
			$categoryOptions->add ( new CategoryOption ( 1, 5, 142, 23, 'Photos', 'PHOTOS', true ) );
		}
	
		if ($categoryID == 6) {
			// Deck Handover
			$categoryOptions->add ( new CategoryOption ( 2, 6, 111, 1, 'Core No', 'TEXT', true, array("style"=>"width:150px")));
			$categoryOptions->add ( new CategoryOption ( 2, 6, 112, 2, 'Level No', 'TEXT', true, array("style"=>"width:150px")));
			$categoryOptions->add ( new CategoryOption ( 2, 6, 113, 3, 'Formwork deck fully complete with no gaps or holes', 'RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 6, 114, 4, 'All decking panels and formwork sheets firmly secured', 'RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 6, 115, 5, 'Edge boards complete and firmly secured', 'RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 6, 116, 6, 'Edge protection in place and adequate', 'RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 6, 117, 7, 'Scaffold and/or perimeter safety screens in place with no gaps', 'RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 6, 118, 8, 'Deck fully extends to edge of scaffold', 'RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 6, 119, 9, 'Hand railing complete, firmly fixed and of adequate strength', 'RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 6, 1110, 10, 'Steel safety mesh installed in all service penetrations ', 'RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 6, 1111, 11, 'All penetrations covered, secured and clearly stencilled ', 'RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 6, 1112, 12, 'Soffit surveyed', 'RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 6, 1113, 13, 'Fencing/cordons in place in areas not ready for handover', 'RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 6, 1114, 14, 'Required signage in place in areas not ready for handover', 'RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 6, 1115, 15, 'Safe access available for other trades at entry points of deck', 'RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 6, 1116, 16, 'Slip/trip hazards removed (excess materials, timber etc.)', 'RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 6, 1117, 17, 'Deck swept and cleaned of all debris', 'RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 6, 1118, 18, 'Engineer Sign off', 'RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 6, 11700, 11700, 'Highlighted Drawings attached','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 6, 11701, 11701, 'Check beam/stepdowns set out','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 6, 11702, 11702, 'Safe access to the deck','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 6, 11184, 11124, 'CONSTRUCTION', 'LABEL', true, array("label"=>"DRAWING NO.")) );
			$categoryOptions->add ( new CategoryOption ( 2, 6, 11188, 11128, 'Architects GA/RCP', 'TEXT', true, array("style"=>"width:150px") ) );
			$categoryOptions->add ( new CategoryOption ( 2, 6, 11189, 11129, 'Architects CS/WS', 'TEXT', true, array("style"=>"width:150px") ) );
			$categoryOptions->add ( new CategoryOption ( 2, 6, 11190, 11130, 'Structural Outline', 'TEXT', true, array("style"=>"width:150px") ) );
			$categoryOptions->add ( new CategoryOption ( 2, 6, 11191, 11131, 'Formwork Certificate', 'TEXT', true, array("style"=>"width:150px") ) );
			$categoryOptions->add ( new CategoryOption ( 2, 6, 1119, 19, 'Comments', 'TEXTAREA', true ) );
			$categoryOptions->add ( new CategoryOption ( 2, 6, 1120, 20, 'I confirm that the specified stairs are safe to use and are structurally adequate to support site personnel and design and working loads (structural certificate to be provided by structural engineer prior to pour).', 'CONFIRM', true,  array("pdfTitle"=>"The submitter of this report has confirmed that the specified stairs are safe to use and are structurally adequate to support site personnel and design and working loads (structural certificate to be provided by structural engineer prior to pour).")) );
			$categoryOptions->add ( new CategoryOption ( 2, 6, 1121, 21, 'I have inspected the Stairs and confirm that all required actions and controls have been implemented.','CONFIRM', true, array("pdfTitle"=>"The submitter of this report has inspected the Stairs and confirms that all required actions and controls have been implemented.") ) );
			// $categoryOptions->add(new CategoryOption(2,6,1123,23,'Verified On','','DATETIME','',true,'','',''));
			$categoryOptions->add ( new CategoryOption ( 2, 6, 1124, 24, 'Photos', 'PHOTOS', true ) );
		}
		if ($categoryID == 7) {
			// Lift and Stair Boxes
			$categoryOptions->add ( new CategoryOption ( 2, 7, 1130, 1, 'Core No', 'TEXT', true, array("style"=>"width:150px")));
			$categoryOptions->add ( new CategoryOption ( 2, 7, 1131, 2, 'Level No', 'TEXT', true, array("style"=>"width:150px")));
			$categoryOptions->add ( new CategoryOption ( 2, 7, 1132, 3, 'Length','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 7, 1133, 4, 'Width','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 7, 1134, 5, 'Fillets','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 7, 1135, 6, 'Position','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 7, 1136, 7, 'Conduits','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 7, 1137, 8, 'Nail Heights','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 7, 1138, 9, 'Penetrations for services','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 7, 1139, 10, 'Squareness','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 7, 1140, 11, 'Working Platform','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 7, 1141, 12, 'Manholes','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 7, 1142, 13, 'Props','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 7, 1143, 14, 'Z Bars','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 7, 1144, 15, 'Access Ladders','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 7, 1145, 16, 'Foam-Filler required','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 7, 1146, 17, 'Doors Nailed','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 7, 1147, 18, 'Braces','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 7, 1148, 19, 'Pockets','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 7, 1149, 20, 'Greased/Sprayed','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 7, 1150, 21, 'Safety Caps','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 7, 1152, 22, 'Set out of grid lines','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 7, 1152, 23, 'Comments', 'TEXTAREA', true ) );
			$categoryOptions->add ( new CategoryOption ( 2, 7, 1153, 24, 'Photos', 'PHOTOS', true ) );
		}
		if ($categoryID == 8) {
			// Pre Pour Checklist
			$categoryOptions->add ( new CategoryOption ( 2, 8, 1163, 1, 'Core No', 'TEXT', true, array("style"=>"width:150px")));
			$categoryOptions->add ( new CategoryOption ( 2, 8, 1164, 2, 'Level No', 'TEXT', true, array("style"=>"width:150px")));
			$categoryOptions->add ( new CategoryOption ( 2, 8, 1165, 5, 'Temperature', 'TEXT', true, array("style"=>"width:100px") ) );
			$categoryOptions->add ( new CategoryOption ( 2, 8, 1166, 6, 'Start of Pour', '', 'TIME', '', true, '', '', '' ) );
			$categoryOptions->add ( new CategoryOption ( 2, 8, 1167, 7, 'Finish of Pour', '', 'TIME', '', true, '', '', '' ) );
			$categoryOptions->add ( new CategoryOption ( 2, 8, 1168, 8, 'CAST INS', '', 'LABEL_AREA', '', true, '', '', '' ) );
			$categoryOptions->add ( new CategoryOption ( 2, 8, 1169, 9, 'Hob for post fix spandrals and cast ins','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 8, 1170, 10, 'Construction joints / dowels','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 8, 1171, 11, 'Wet area and balcony stepdowns','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 8, 1172, 12, 'RL Deck / Edgeboard','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 8, 1173, 13, 'Overflow blockouts','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 8, 1174, 14, 'Penetrations for services','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 8, 1175, 15, 'Bond breaker applied','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 8, 1176, 16, 'Ableflex fixed','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 8, 1177, 17, 'Sheer Keys','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 8, 1178, 18, 'Pegs Removed','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 8, 1179, 19, 'Drip groove - Pre cast fillets','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 8, 1180, 20, 'Precast fillet','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 8, 1181, 21, 'CJ fillet','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 8, 1182, 22, 'Foam filler to CJ','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 8, 1183, 23, 'RI on all external hob heights marked','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 8, 1184, 24, 'CONSTRUCTION', 'LABEL', true, array("label"=>"DRAWING NO.")) );
			$categoryOptions->add ( new CategoryOption ( 2, 8, 1188, 28, 'Architects GA/RCP', 'TEXT', true, array("style"=>"width:150px") ) );
			$categoryOptions->add ( new CategoryOption ( 2, 8, 1189, 29, 'Architects CS/WS', 'TEXT', true, array("style"=>"width:150px") ) );
			$categoryOptions->add ( new CategoryOption ( 2, 8, 1190, 30, 'Structural Outline', 'TEXT', true, array("style"=>"width:150px") ) );
			$categoryOptions->add ( new CategoryOption ( 2, 8, 1191, 31, 'Formwork Certificate', 'TEXT', true, array("style"=>"width:150px") ) );
			$categoryOptions->add ( new CategoryOption ( 2, 8, 1192, 32, 'Comments', 'TEXTAREA', true ) );
			// $categoryOptions->add(new CategoryOption(2,6,1123,23,'Verified On','','DATETIME','',true,'','',''));
			$categoryOptions->add ( new CategoryOption ( 2, 8, 1194, 34, 'Photos', 'PHOTOS', true ) );
		}
		if ($categoryID == 9) {
			// Stairs
			$categoryOptions->add ( new CategoryOption ( 2, 9, 11100, 1, 'Core No', 'TEXT', true, array("style"=>"width:150px")));
			$categoryOptions->add ( new CategoryOption ( 2, 9, 11101, 2, 'Level No', 'TEXT', true, array("style"=>"width:150px")));
			$categoryOptions->add ( new CategoryOption ( 2, 9, 11103, 3, 'Pour No', 'TEXT', true, array("style"=>"width:150px") ) );
			$categoryOptions->add ( new CategoryOption ( 2, 9, 11104, 4, 'Conventional/Stairmaster correct position','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 9, 11105, 5, 'Edge boards braced','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 9, 11106, 6, 'Centre of Conventional/Stairmaster flights installed','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 9, 11107, 7, 'Pins and props','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 9, 11108, 8, 'Z Bars in walls tight','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 9, 11109, 9, 'Stairs cleaned','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 9, 11110, 10, 'I confirm that the specified stairs are safe to use and are structurally adequate to support site personnel and design and working loads (structural certificate to be provided by structural engineer prior to pour).', 'CONFIRM', true,  array("pdfTitle"=>"The submitter of this report has confirmed that the specified stairs are safe to use and are structurally adequate to support site personnel and design and working loads (structural certificate to be provided by structural engineer prior to pour).")) );
			$categoryOptions->add ( new CategoryOption ( 2, 9, 11112, 11, 'I have inspected the Stairs and confirm that all required actions and controls have been implemented.','CONFIRM', true, array("pdfTitle"=>"The submitter of this report has inspected the Stairs and confirms that all required actions and controls have been implemented.") ) );
			$categoryOptions->add ( new CategoryOption ( 2, 9, 11113, 12, 'Comments', 'TEXTAREA', true ) );
			$categoryOptions->add ( new CategoryOption ( 2, 9, 11115, 14, 'Photos', 'PHOTOS', true ) );
		}
		if ($categoryID == 10) {
			// Verticals
			$categoryOptions->add ( new CategoryOption ( 2, 10, 11120, 1, 'Core No', 'TEXT', true, array("style"=>"width:150px")));
			$categoryOptions->add ( new CategoryOption ( 2, 10, 11121, 2, 'Level No', 'TEXT', true, array("style"=>"width:150px")));
			$categoryOptions->add ( new CategoryOption ( 2, 10, 11122, 3, 'Length','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 10, 11123, 4, 'Width','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 10, 11124, 5, 'Fillets','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 10, 11125, 6, 'Spacers','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 10, 11126, 7, 'Nail heights sprayed','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 10, 11127, 8, 'Penetrations for services','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 10, 11128, 9, 'Bond breaker applied','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 10, 11129, 10, 'Plumb','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 10, 11130, 11, 'Post pour plumb','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 10, 11131, 12, 'Rebates','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 10, 11132, 13, 'Bracing','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 10, 11133, 14, 'Props','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 10, 11134, 15, 'She-Bolts tied off','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 10, 11135, 16, 'Working platform','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 10, 11136, 17, 'Access Ladder','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 10, 11137, 18, 'Foam filler required','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 10, 11138, 19, 'Z bars tied off','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 10, 11139, 20, 'Stop end nailed','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 10, 841139, 84120, 'All Walls formed','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 10, 841149, 84820, 'All Columns formed','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 10, 841159, 84920, 'Highlighted Drawings attached','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 10, 11140, 21, 'Comments', 'TEXTAREA', true ) );
			$categoryOptions->add ( new CategoryOption ( 2, 10, 11142, 23, 'Photos', 'PHOTOS', true ) );
		}
		if ($categoryID == 20) {
			// POST CONSTRUCTION POUR
			$categoryOptions->add ( new CategoryOption ( 1, 20, 1300, 1300, 'Core No', 'TEXT', true, array("style"=>"width:150px")));
			$categoryOptions->add ( new CategoryOption ( 1, 20, 1302, 1302, 'Level No', 'TEXT', true, array("style"=>"width:150px")));
			$categoryOptions->add ( new CategoryOption ( 1, 20, 1303, 1303, 'PT Initial Stress','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 20, 1304, 1304, 'PT Final Stress','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 20, 1305, 1305, 'PT Grouting','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 20, 130115, 130115, 'Stripping of Formwork','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 20, 1306, 1306, 'Installation of Backpropping','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 20, 1307, 1307, 'Removal of Backpropping','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 20, 1308, 1308, 'Removal of Formwork material from floor','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 20, 1309, 1309, 'Prepare Defect List','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 20, 1310, 1310, 'FRP Post pour items i.e. hobs','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 20, 1311, 1311, 'Defects Complete','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 20, 1312, 1312, 'Deck handed over','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 1, 20, 2292, 2232, 'Comments', 'TEXTAREA', true ) );
			$categoryOptions->add ( new CategoryOption ( 1, 20, 13024, 13024, 'Photos', 'PHOTOS', true ) );
		}
		if ($categoryID == 21) {
			// POST CONSTRUCTION POUR
			$categoryOptions->add ( new CategoryOption ( 2, 21, 21300, 1300, 'Core No', 'TEXT', true, array("style"=>"width:150px")));
			$categoryOptions->add ( new CategoryOption ( 2, 21, 21302, 1302, 'Level No', 'TEXT', true, array("style"=>"width:150px")));
			$categoryOptions->add ( new CategoryOption ( 2, 21, 21303, 1303, 'PT Initial Stress','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 21, 21304, 1304, 'PT Final Stress','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 21, 2133305, 13035, 'PT Grouting','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 21, 21305, 1305, 'Stripping of Formwork','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 21, 21306, 1306, 'Installation of Backpropping','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 21, 21307, 1307, 'Removal of Backpropping','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 21, 21308, 1308, 'Removal of Formwork material from floor','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 21, 21309, 1309, 'Prepare Defect List','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 21, 21310, 1310, 'FRP Post pour items i.e. hobs','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 21, 21311, 1311, 'Defects Complete','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 21, 21312, 1312, 'Deck handed over','RADIO', true,  array("radioOptions"=>array(array("radioOption"=>"Yes","radioOptionTitle"=>"Yes"),array("radioOption"=>"No","radioOptionTitle"=>"No"),array("radioOption"=>"N/A","radioOptionTitle"=>"N/A","commentOn"=>true,"commentTitle"=>"Why is this N/A")))));
			$categoryOptions->add ( new CategoryOption ( 2, 21, 12292, 12232, 'Comments', 'TEXTAREA', true ) );
			$categoryOptions->add ( new CategoryOption ( 2, 21, 213024, 13024, 'Photos', 'PHOTOS', true ) );
		}
	
		return $categoryOptions;
	}
	
}

?>