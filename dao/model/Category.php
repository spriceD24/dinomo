<?php
class Category {
	public $projectID;
	public $categoryID;
	public $categoryName;
	public $categoryOptions;
	public $deleteFlag;
	
	
	function __construct($projectID, $categoryID, $categoryName, $categoryOptions) {
		$this->projectID = $projectID;
		$this->categoryID = $categoryID;
		$this->categoryName = $categoryName;
		$this->categoryOptions = $categoryOptions;
	}
}
?>
