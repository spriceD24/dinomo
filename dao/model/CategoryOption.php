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
		public $labelStyleClass;
		
		function __construct($projectID, $categoryID, $categoryOptionID, $order, $title, $pdfTitle, $formType, $formOptions, $isRequired, $errorMessage, $styleClass, $labelStyleClass) 
		{	
			$this->projectID = $projectID;	
			$this->categoryID = $categoryID;	
			$this->categoryOptionID = $categoryOptionID;
			$this->order = $order;
			$this->title = $title;
			$this->pdfTitle = $pdfTitle;
			$this->formType = $formType;
			$this->formOptions = $formOptions;
			$this->isRequired = $isRequired;
			$this->errorMessage = $errorMessage;
			$this->styleClass = $styleClass;
			$this->labelStyleClass = $labelStyleClass;
		} 
	}

?>