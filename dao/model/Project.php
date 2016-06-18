<?php
class Project {
	public $projectID;
	public $clientID;
	public $projectName;
	public $categories;
	function __construct($projectID, $clientID, $projectName, $categories = array()) {
		$this->projectID = $projectID;
		$this->clientID = $clientID;
		$this->projectName = $projectName;
		$this->categories = $categories;
	}
	
	function addCategory($category)
	{
		$this->categories->add($category);
	}
}

?>