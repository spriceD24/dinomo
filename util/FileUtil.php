<?php

	/**
	 * Manages file interactions
	 * @author Stef
	 *
	 */
	class FileUtil
	{
		/**
		 * Save text to file
		 */
		function saveHTMLToWebFile($html,$filename)
		{
			$configUtil = new ConfigUtil();
			$folder = $configUtil->getWebFolder();
			$file = $folder."/".$filename.".html";
			$myfile = fopen($file, "w");
			fwrite($myfile, $html);
			fclose($myfile);
			return $file;
		}
		
	}
?>