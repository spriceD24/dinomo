<?php include_once("util/ConfigUtil.php"); ?>
<?php include_once("util/WebUtil.php"); ?>
<?php include_once("util/DateUtil.php"); ?>
<?php

	class DBUtil
	{
		function getDBConnection()
		{
			$webUtil = new WebUtil();
			$server = "";
			$user = "";
			$db="";
			if($webUtil->isProduction())
			{
				$server = ConfigUtil::getDBServer();
				$user = ConfigUtil::getDBUser();
				$db =  ConfigUtil::getDBName();
			}else{
				$server = ConfigUtil::getLocalDBServer();
				$user = ConfigUtil::getLocalDBUser();				
				$db =  ConfigUtil::getLocalDBName();
			}
			
			// Create connection
			$conn = new mysqli($server, $user, "dinomo_D24", $db);
			// Check connection
			if ($conn->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			
			return $conn;
				
		}
		
		
	}
	
?>