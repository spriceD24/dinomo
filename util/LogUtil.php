<?php include_once("util/logging/Logger.php"); ?>
<?php include_once("util/logging/LogLevel.php"); ?>
<?php

class LogUtil {
	private static $logger;
	public static function getLogger() {
		if (! isset ( self::$logger )) {
			self::$logger = new Logger ();
		}
		
		return self::$logger;
	}
	public static function debug($classname, $message, array $context = array()) {
		self::getLogger ()->log ( LogLevel::DEBUG, $classname . " - " . $message, $context );
	}
	public static function info($classname, $message, array $context = array()) {
		self::getLogger ()->log ( LogLevel::INFO, $classname . " - " . $message, $context );
	}
	public static function error($classname, $message, array $context = array()) {
		self::getLogger ()->log ( LogLevel::ERROR, $classname . " - " . $message, $context );
	}
}

?>