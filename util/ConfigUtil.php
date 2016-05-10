<?php
	
	class ConfigUtil
	{
		private static $ini_array = array();
		/*
		 * returns max recommended width for PDF
		 */
		function getMaxPDFWidth()
		{
			$this->loadINIArray();
			$max_pdf_width = $this->ini_array["pdf_max_width"];
			return $max_pdf_width;
		}
		
		/*
		 * returns max recommended height for PDF
		 */
		function getMaxPDFHeight()
		{
			$this->loadINIArray();
			$max_pdf_height = $this->ini_array["pdf_max_height"];
			return $max_pdf_height;
		}
		
		function loadINIArray()
		{
			if(empty($this->ini_array))
			{
				$this->ini_array = parse_ini_file("config/dinamo.ini");
			}
		}
		
		/*
		 * returns number of images which can be uploaded to server
		 */
		function getNumberOfUploadFiles()
		{
			$this->loadINIArray();
			$numFiles = $this->ini_array["number_of_images"];
			return $numFiles;
		}
		/*
		 * returns image folder location
		 */
		function getImageFolder()
		{
			$this->loadINIArray();
			$image_folder = $this->ini_array["image_folder"];
			return $image_folder;
		}
		/*
		 * returns web folder location
		 */
		function getWebFolder()
		{
			$this->loadINIArray();
			$image_folder = $this->ini_array["web_folder"];
			return $image_folder;
		}
		
		/*
		 * returns image folder location
		 */
		function getPDFFolder()
		{
			$this->loadINIArray();
			$pdf_folder = $this->ini_array["pdf_folder"];
			return $pdf_folder;
		}

		function getMobileTextStyle()
		{
			$this->loadINIArray();
			$mobile_text = $this->ini_array["mobile_text"];
			return $mobile_text;
		}
		
		function getMobileLabelStyle()
		{
			$this->loadINIArray();
			$mobile_label = $this->ini_array["mobile_label"];
			return $mobile_label;
		}		

		function getMobileTextAreaStyle()
		{
			$this->loadINIArray();
			$mobile_textarea = $this->ini_array["mobile_textarea"];
			return $mobile_textarea;
		}	
		
			function getMobileDropDownStyle()
		{
			$this->loadINIArray();
			$mobile_dropdown = $this->ini_array["mobile_dropdown"];
			return $mobile_dropdown;
		}			
	}

?>