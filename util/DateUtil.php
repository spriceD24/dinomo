<?php

	class DateUtil
	{
		function getCurrentDateTimeString()
		{
			date_default_timezone_set('Australia/Sydney');
			return date("j-M-Y h:iA");
		}
		
		function getCurrentDateString()
		{
			date_default_timezone_set('Australia/Sydney');
			return date("j_M_Y");
		}
		
	}	
?>