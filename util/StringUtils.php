<?php

	/**
	 * String utilities 
	 * @author Stef
	 *
	 */
	class StringUtils
	{
		const CIPHER			=	"dmo_stef";
		const DINAMO_IV			=	"\"Ÿ`Zú¾‰•W?ªõþ	K#";
		
		static function commaSeperatedValuesToArray($string)
		{
			return explode(',', $string);
		}
		
		static function equals($str1,$str2)
		{
			if(!empty($str1) && !empty($str2))
			{
				return (strcmp(trim($str1), trim($str2)) == 0);
			}
			return false;
		}
		
		static function equalsCaseInsensitive($str1,$str2)
		{
			if(!empty($str1) && !empty($str2))
			{
				return (strcasecmp(trim($str1), trim($str2)) == 0);
			}
			return false;
		}
		
		static function cleanString($string)
		{
			if(!empty($string))
			{
				$string = str_replace(' ', '_', $string);
				$string = str_replace('&', '_', $string);
				$string = str_replace('__', '', $string);
			}
			return $string;
		}
		
		
		static function encode($string)
		{
			return mcrypt_encrypt(MCRYPT_RIJNDAEL_128, self::CIPHER, $string, MCRYPT_MODE_CBC,self::DINAMO_IV);
		}

		static function decode($string)
		{
			return mcrypt_decrypt(MCRYPT_RIJNDAEL_128, self::CIPHER, $string, MCRYPT_MODE_CBC,self::DINAMO_IV);
		}
	}
?>