<?php

	/**
	 * String utilities 
	 * @author Stef
	 *
	 */
	class StringUtils
	{
		function commaSeperatedValuesToArray($string)
		{
			return explode(',', $string);
		}
		
		function cleanString($string)
		{
			if(!empty($string))
			{
				$string = str_replace(' ', '_', $string);
				$string = str_replace('&', '_', $string);
				$string = str_replace('__', '', $string);
			}
			return $string;
		}
		
	}
?>