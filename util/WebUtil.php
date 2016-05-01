<?php

	class WebUtil
	{
		function getBaseURI()
		{
			$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			return substr($url, 0, strrpos( $url, '/'));
		}
		
		function isProduction()
		{
			$host = $_SERVER["HTTP_HOST"];
			$pos = strpos($host,"localhost");
			
			if ($pos !== false) 
			{
				return 0;	
			}
			return 1;
		}
		
		
	}	
?>