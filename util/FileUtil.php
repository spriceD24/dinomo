<?php

	/**
	 * Manages file interactions
	 * @author Stef
	 *
	 */
	class FileUtil
	{
		/*
		 * returns number of images which can be uploaded to server
		 */
		function getNumberOfUploadFiles() 
		{ 
			$ini_array = parse_ini_file("config/dinamo.ini");
			$numFiles = $ini_array["number_of_images"];
			return $numFiles;
		}
		/*
		 * returns image folder location
		 */
		function getImageFolder()
		{
			$ini_array = parse_ini_file("config/dinamo.ini");
			$image_folder = $ini_array["image_folder"];
			return $image_folder;
		}
		/*
		 * returns web folder location
		 */
		function getWebFolder()
		{
			$ini_array = parse_ini_file("config/dinamo.ini");
			$image_folder = $ini_array["web_folder"];
			return $image_folder;
		}		
		
		/**
		 * Save text to file
		 */
		function saveHTMLToWebFile($html,$filename)
		{
			$folder = $this->getWebFolder();
			$file = $folder."/".$filename.".html";
			$myfile = fopen($file, "w");
			fwrite($myfile, $html);
			fclose($myfile);
			return $file;
		}
	}
?>