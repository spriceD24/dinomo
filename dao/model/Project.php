<?php
class Project {
	public $projectID;
	public $projectName;
	public $categories;
	function __construct($projectID, $projectName, $categories) {
		$this->projectID = $projectID;
		$this->projectName = $projectName;
		$this->categories = $categories;
	}
}

?>