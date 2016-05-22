<?php include_once("util/LogUtil.php"); ?>
<?php

/**
 * Manages file interactions
 * 
 * @author Stef
 *        
 */
class FileUtil {
	/**
	 * Save text to file
	 */
	function saveHTMLToWebFile($html, $filename) {
		$folder = ConfigUtil::getWebFolder ();
		$file = $folder . "/" . $filename . ".html";
		$myfile = fopen ( $file, "w" );
		fwrite ( $myfile, $html );
		fclose ( $myfile );
		return $file;
	}
	function getFilename($user, $prefix) {
		LogUtil::debug ( "FileUtil", "Getting file name for " . $user->login . ", prefix = " . $prefix );
		$folder = ConfigUtil::getWebFolder ();
		for($x = 0; $x <= 10; $x ++) {
			$uniqueID = urlencode ( $this->getUniqueName ( $user, $prefix, $x ) );
			if (! file_exists ( $folder . "/" . $uniqueID . ".html" )) {
				LogUtil::debug ( "FileUtil", "returning ID = " . $uniqueID );
				return $uniqueID;
			}
		}
		
		return round ( microtime ( true ) );
	}
	function getUniqueName($user, $prefix, $add) {
		return $user->login . '_' . StringUtils::cleanString ( $prefix ) . '_' . (rand () + $add);
	}
}
?>