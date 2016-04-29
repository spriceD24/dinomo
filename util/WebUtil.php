<?php

	class WebUtil
	{
		function getBaseURI()
		{
			$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			return substr($url, 0, strrpos( $url, '/'));
		}
	}	
?>