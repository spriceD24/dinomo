<?php

/**
 * String utilities 
 * @author Stef
 *
 */
class StringUtils {
	const CIPHER = "dmo_stef";
	const DINAMO_IV = "\"Ÿ`Zú¾‰•W?ªõþ	K#";
	
	static function commaSeperatedValuesToArray($string) {
		return explode ( ',', $string );
	}
	
	static function equals($str1, $str2) {
		if (! empty ( $str1 ) && ! empty ( $str2 )) {
			return (strcmp ( trim ( $str1 ), trim ( $str2 ) ) == 0);
		}
		return false;
	}
	
	static function equalsCaseInsensitive($str1, $str2) {
		if (! empty ( $str1 ) && ! empty ( $str2 )) {
			return (strcasecmp ( trim ( $str1 ), trim ( $str2 ) ) == 0);
		}
		return false;
	}
	
	static function cleanString($string) {
		if (! empty ( $string )) {
			$string = str_replace ( ' ', '_', $string );
			$string = str_replace ( '&', '_', $string );
			$string = str_replace ( '__', '', $string );
		}
		return $string;
	}
	
	static function encode($string) {
		if (version_compare(PHP_VERSION, '5.6.0') >= 0) 
		{
			return $string;
			//return mcrypt_encrypt ( MCRYPT_RIJNDAEL_128, self::CIPHER, self::padKey($string), MCRYPT_MODE_CBC, self::DINAMO_IV );
		}
		return mcrypt_encrypt ( MCRYPT_RIJNDAEL_128, self::CIPHER, $string, MCRYPT_MODE_CBC, self::DINAMO_IV );
	}
	
	static function decode($string) {
		if (version_compare(PHP_VERSION, '5.6.0') >= 0) 
		{
			//return mcrypt_decrypt ( MCRYPT_RIJNDAEL_128, self::CIPHER, self::padKey($string), MCRYPT_MODE_CBC, self::DINAMO_IV );
			return $string;
		}
		return mcrypt_decrypt ( MCRYPT_RIJNDAEL_128, self::CIPHER, $string, MCRYPT_MODE_CBC, self::DINAMO_IV );
	}
	
	static function padKey($key){
		// key is too large
		if(strlen($key) > 32) return false;
	
		// set sizes
		$sizes = array(16,24,32);
	
		// loop through sizes and pad key
		foreach($sizes as $s){
			while(strlen($key) < $s) $key = $key."\0";
			if(strlen($key) == $s) break; // finish if the key matches a size
		}
	
		// return
		return $key;
	}
	
	static function replace($search, $replace, $value)
	{
		if(!empty($string) && !empty($replace) && !empty($value))
		{
			return str_replace($search, $replace, $value);
		}
		return $value;
	}

	static function escapeDB($value)
	{
		return str_replace("'", "''", $value);
	}
}
?>