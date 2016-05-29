<?php
class ConfigUtil {
	private static $ini_array = array ();
	/*
	 * returns max recommended width for PDF
	 */
	static function getMaxPDFWidth() {
		self::loadINIArray ();
		$max_pdf_width = self::$ini_array ["pdf_max_width"];
		return $max_pdf_width;
	}
	
	/*
	 * returns max recommended height for PDF
	 */
	static function getMaxPDFHeight() {
		self::loadINIArray ();
		$max_pdf_height = self::$ini_array ["pdf_max_height"];
		return $max_pdf_height;
	}
	static function loadINIArray() {
		if (empty ( self::$ini_array )) {
			self::$ini_array = parse_ini_file ( "config/dinamo.ini" );
		}
	}
	
	/*
	 * returns number of images which can be uploaded to server
	 */
	static function getNumberOfUploadFiles() {
		self::loadINIArray ();
		$numFiles = self::$ini_array ["number_of_images"];
		return $numFiles;
	}
	/*
	 * returns image folder location
	 */
	static function getImageFolder() {
		self::loadINIArray ();
		$image_folder = self::$ini_array ["image_folder"];
		return $image_folder;
	}
	/*
	 * returns web folder location
	 */
	static function getWebFolder() {
		self::loadINIArray ();
		$image_folder = self::$ini_array ["web_folder"];
		return $image_folder;
	}
	
	/*
	 * returns image folder location
	 */
	static function getPDFFolder() {
		self::loadINIArray ();
		$pdf_folder = self::$ini_array ["pdf_folder"];
		return $pdf_folder;
	}
	static function getMobileTextStyle() {
		self::loadINIArray ();
		$mobile_text = self::$ini_array ["mobile_text"];
		return $mobile_text;
	}
	static function getMobileLabelStyle() {
		self::loadINIArray ();
		$mobile_label = self::$ini_array ["mobile_label"];
		return $mobile_label;
	}
	static function getMobileTextAreaStyle() {
		self::loadINIArray ();
		$mobile_textarea = self::$ini_array ["mobile_textarea"];
		return $mobile_textarea;
	}
	static function getMobileDropDownStyle() {
		self::loadINIArray ();
		$mobile_dropdown = self::$ini_array ["mobile_dropdown"];
		return $mobile_dropdown;
	}
	static function isCacheActive() {
		self::loadINIArray ();
		$cache_active = self::$ini_array ["cache_active"];
		return (! empty ( $cache_active ) && ($cache_active == "true" || $cache_active == true));
	}
	static function getCookieExpDays() {
		self::loadINIArray ();
		$cookie_exp_days = self::$ini_array ["cookie_exp_days"];
		return intval ( $cookie_exp_days );
	}
	static function getLogLevel() {
		self::loadINIArray ();
		$log_level = self::$ini_array ["log_level"];
		return intval ( $log_level );
	}
	static function getLogFolder() {
		self::loadINIArray ();
		$log_folder = self::$ini_array ["log_folder"];
		return $log_folder;
	}
	
	static function getDinamoFromEmail() {
		self::loadINIArray ();
		$email_dinamo_from_email = self::$ini_array ["email_dinamo_from_email"];
		return $email_dinamo_from_email;
	}

	static function getDinamoFromName() {
		self::loadINIArray ();
		$email_dinamo_from_name = self::$ini_array ["email_dinamo_from_name"];
		return $email_dinamo_from_name;
	}
	
	static function getCacheFolder()
	{
		self::loadINIArray();
		$cache_folder = self::$ini_array["cache_folder"];
		return $cache_folder;
	}	
	
	static function getDinamoSupportEmail() {
		self::loadINIArray ();
		$support_email = self::$ini_array ["support_email"];
		return $support_email;
	}
	
	static function getDBServer() {
		self::loadINIArray ();
		$prod_db_server = self::$ini_array ["prod_db_server"];
		return $prod_db_server;
	}
	
	static function getDBName() {
		self::loadINIArray ();
		$prod_db_db = self::$ini_array ["prod_db_db"];
		return $prod_db_db;
	}
	
	static function getDBUser() {
		self::loadINIArray ();
		$prod_db_user = self::$ini_array ["prod_db_user"];
		return $prod_db_user;
	}
	
	static function getLocalDBServer() {
		self::loadINIArray ();
		$local_db_server = self::$ini_array ["local_db_server"];
		return $local_db_server;
	}
	
	static function getLocalDBUser() {
		self::loadINIArray ();
		$local_db_user = self::$ini_array ["local_db_user"];
		return $local_db_user;
	}
	
	static function getLocalDBName() {
		self::loadINIArray ();
		$local_db_db = self::$ini_array ["local_db_db"];
		return $local_db_db;
	}
}

?>