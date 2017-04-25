<?php
class CategoryOption {
	public $projectID;
	public $categoryID;
	public $categoryOptionID;
	public $order;
	public $title;
	public $formType;
	public $isRequired;
	public $optionSettings;
	public $deleteFlag;
	
	
	function __construct($projectID, $categoryID, $categoryOptionID, $order, $title, $formType, $isRequired, $optionSettings = array()) {
		$this->projectID = $projectID;
		$this->categoryID = $categoryID;
		$this->categoryOptionID = $categoryOptionID;
		$this->order = $order;
		$this->title = $title;
		$this->formType = $formType;
		$this->isRequired = $isRequired;
		$this->optionSettings = $optionSettings;
	}
	
	function getSetting($settingName)
	{
		if(!empty($this->optionSettings) && isset($this->optionSettings[$settingName]))
		{
			return $this->optionSettings[$settingName];
		}
		return '';
	}
}

?>