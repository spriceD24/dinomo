<?php
class Project {
	public $projectID;
	public $projectName;
	public $categories;
	function __construct($projectID, $projectName, $categories = array()) {
		$this->projectID = $projectID;
		$this->projectName = $projectName;
		$this->categories = $categories;
	}
	
	function addCategory($category)
	{
		$this->$categories->add($category);
	}
}

?>