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
		
		
		function getFilename($user,$prefix)
		{
			$configUtil = new ConfigUtil();
			$folder = $configUtil->getWebFolder();
			for ($x = 0; $x <= 10; $x++) {
				$uniqueID = urlencode($this->getUniqueName($user,$prefix,$x));
				if(!file_exists($folder."/".$uniqueID.".html"))
				{
					return $uniqueID;
				}
			} 
			
			return round(microtime(true));
		}
		
		function getUniqueName($user,$prefix,$add)
		{
			$stringUtil = new StringUtils();
			str_replace(' ', '-', $string);
			return $user->login.'_'.$stringUtil->cleanString($prefix).'_'.(rand()+$add);
		}
		
	}
?>