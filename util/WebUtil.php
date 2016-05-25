<?php include_once("dao/model/User.php"); ?>
<?php include_once("delegate/UserDelegate.php"); ?>
<?php include_once("util/StringUtils.php"); ?>
<?php include_once("util/LogUtil.php"); ?>
<?php include_once("util/ConfigUtil.php"); ?>
<?php include_once("util/CacheUtil.php"); ?>
<?php

class WebUtil {
	const DINAMO_USER = "dinamo_user";
	public $srcPage;
	
	function getBaseURI() {
		$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		return substr ( $url, 0, strrpos ( $url, '/' ) );
	}
	
	function isProduction() {
		$host = $_SERVER ["HTTP_HOST"];
		$pos = strpos ( $host, "localhost" );
		
		if ($pos !== false) {
			return 0;
		}
		return 1;
	}
	
	function getLoggedInUser() {
		LogUtil::debug ( 'WebUtil', 'Checking cookies' );
		if (isset ( $_COOKIE [self::DINAMO_USER] )) {
			LogUtil::debug ( 'WebUtil', 'Undecoded value is - ' . $_COOKIE [self::DINAMO_USER] );
			$login = trim ( StringUtils::decode ( $_COOKIE [self::DINAMO_USER] ) );
			LogUtil::debug ( 'WebUtil', 'Decoded value is - ' . $login );
			$userDelegate = new UserDelegate ();
			$user = $userDelegate->getUserByLogin ( $login );
			if (! empty ( $user )) {
				LogUtil::debug ( 'WebUtil', 'Found user for login - ' . $login );
				return $user;
			}
			LogUtil::debug ( 'WebUtil', 'No user found for login - ' . $login );
		} 
		$deviceID = $this->getDeviceIP();
		LogUtil::debug ( 'WebUtil', 'No cookie set, checking cache for device '.$deviceID);
		$user = CacheUtil::getCachedDeviceUser($deviceID);
		if (! empty ( $user )) {
			LogUtil::debug ( 'WebUtil', 'Found user for login in cache - ' . $user->login );
			return $user;
		}		
		LogUtil::debug ( 'WebUtil', 'No user found going to login');
			
		header ( "Location: login.php" );
		exit ();
	}
	
	function addLoggedInUser($user, $numDays) {
		//add to cache
		$deviceID = $this->getDeviceIP();
		if(empty($user) || empty($user->login))
		{
			LogUtil::debug ( 'WebUtil', 'Resetting cookie for device ' . $deviceID  . ', for ' . $numDays . ' days' );
			setcookie ( self::DINAMO_USER, "", time () + (86400 * $numDays), "/" );
			CacheUtil::removeCachedDeviceUser($deviceID);
		}else{
			LogUtil::debug ( 'WebUtil', 'Setting NEW cookie for ' . $user->login . ', device = '.$deviceID .',  for ' . $numDays . ' days' );
			setcookie ( self::DINAMO_USER, StringUtils::encode ( strtolower ( trim ( $user->login ) ) ), time () + (86400 * $numDays), "/" );
			CacheUtil::addCachedDeviceUser($deviceID, $user);				
		}
	}

	function removeLoggedInUser() {
		setcookie ( self::DINAMO_USER, "", time () - 3600 );
	}
	
	function handleError($errno, $errstr) {
		// add logging
		header ( "Location: error.php?errorNo=" . $errno . "&src=" . $this->srcPage . "&errorMsg=" . urlencode ( $errstr ) );
		exit ();
	}
	
	function getDeviceIP()
	{
		$ipAddress = $_SERVER['REMOTE_ADDR'];
		if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {
			$ipAddress = array_pop(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']));
		}
		return $ipAddress;
	}	
}
?>