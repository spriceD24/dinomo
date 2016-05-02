<?php

	class CategoryOption
	{
		public $projectID;
		public $categoryID;
		public $categoryOptionID;
		public $order;
		public $formType;
		public $formOptions;
		public $isRequired;
		public $errorMessage;
		public $styleClass;
		
		function __construct($projectID, $categoryID, $categoryOptionID, $order, $formType, $formOptions, $isRequired, $errorMessage, $styleClass) 
		{	
			$this->projectID = $projectID;	
			$this->categoryID = $categoryID;	
			$this->categoryOptionID = $categoryOptionID;
			$this->order = $order;
			$this->formType = $formType;
			$this->formOptions = $formOptions;
			$this->isRequired = $isRequired;
			$this->errorMessage = $errorMessage;
			$this->styleClass = $styleClass;
		} 
	}

?>