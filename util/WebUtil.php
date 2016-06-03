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
		$userDelegate = new UserDelegate ();
		if(self::isUseSessionTracking())
		{
			LogUtil::debug ( 'WebUtil.getLoggedInUser', 'Checking user in session' );
			if(session_id() == '') 
			{
				session_start();
			}
			if(isset($_SESSION[self::DINAMO_USER]))
			{
				$login = $_SESSION[self::DINAMO_USER];
				if (!empty( $login )) {
					$user = $userDelegate->getUserByLogin ( $login );
					if (! empty ( $user )) {
						LogUtil::debug ( 'WebUtil.getLoggedInUser', 'Found user for login - ' . $login );
						//check if we've timed out
						$timeout = ConfigUtil::getSessionTrackingTimeoutMins();
						if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $timeout)) {
							LogUtil::debug ( 'WebUtil.getLoggedInUser', 'User timed out - ' . $login.', last activity = '.$_SESSION['LAST_ACTIVITY'] );
							self::removeLoggedInUser();
						}else{
							LogUtil::debug ( 'WebUtil.getLoggedInUser', 'User NOT timed out - ' . $login );
							$_SESSION['LAST_ACTIVITY'] = time();					
							return $user;
						}
					}else{
						LogUtil::debug ( 'WebUtil.getLoggedInUser', 'old user in session');					
					}
				}else{
					LogUtil::debug ( 'WebUtil.getLoggedInUser', 'EMPTY user in session');
				}
			}
			LogUtil::debug ( 'WebUtil.getLoggedInUser', 'No user in session');
		}else{
			LogUtil::debug ( 'WebUtil.getLoggedInUser', 'Checking cookies' );
			if (isset ( $_COOKIE [self::DINAMO_USER] )) {
				LogUtil::debug ( 'WebUtil', 'Undecoded value is - ' . $_COOKIE [self::DINAMO_USER] );
				$login = trim ( StringUtils::decode ( $_COOKIE [self::DINAMO_USER] ) );
				LogUtil::debug ( 'WebUtil', 'Decoded value is - ' . $login );
				$user = $userDelegate->getUserByLogin ( $login );
				if (! empty ( $user )) {
					LogUtil::debug ( 'WebUtil.getLoggedInUser', 'Found user for login - ' . $login );
					return $user;
				}
				LogUtil::debug ( 'WebUtil.getLoggedInUser', 'No user found for login - ' . $login );
			} 
			$deviceID = $this->getDeviceIP();
			LogUtil::debug ( 'WebUtil.getLoggedInUser', 'No cookie set, checking cache for device '.$deviceID);
			$user = CacheUtil::getCachedDeviceUser($deviceID);
			if (! empty ( $user )) {
				LogUtil::debug ( 'WebUtil.getLoggedInUser', 'Found user for login in cache - ' . $user->login );
				return $user;
			}		
		}
		LogUtil::debug ( 'WebUtil.getLoggedInUser', 'No user found going to login');
			
		header ( "Location: login.php" );
		exit ();
	}
	
	function addLoggedInUser($user, $numDays) {
		if(self::isUseSessionTracking())
		{
			if(empty($user) || empty($user->login))
			{
				LogUtil::debug ( 'WebUtil.addLoggedInUser', 'Removing Session Details' );
				if(session_id() == '') 
				{
					session_start();
				}
				if(isset($_SESSION[self::DINAMO_USER])) 
				{
					LogUtil::debug ( 'WebUtil.addLoggedInUser', 'Setting empty session for User' );
					//reset the user
					unset($_SESSION[self::DINAMO_USER]);
				}
			}else{
				if(session_id() == '') 
				{
					session_start();
				}
				LogUtil::debug ( 'WebUtil.addLoggedInUser', 'Adding user to session' );
				$_SESSION[self::DINAMO_USER] = $user->login;
				$_SESSION['LAST_ACTIVITY'] = time();
			}
		}else{
			//add to cache/cookies
			$deviceID = $this->getDeviceIP();
			if(empty($user) || empty($user->login))
			{
				LogUtil::debug ( 'WebUtil.addLoggedInUser', 'Resetting cookie for device ' . $deviceID  . ', for ' . $numDays . ' days' );
				setcookie ( self::DINAMO_USER, "", time () + (86400 * $numDays), "/" );
				CacheUtil::removeCachedDeviceUser($deviceID);
			}else{
				LogUtil::debug ( 'WebUtil.addLoggedInUser', 'Setting NEW cookie for ' . $user->login . ', device = '.$deviceID .',  for ' . $numDays . ' days' );
				setcookie ( self::DINAMO_USER, StringUtils::encode ( strtolower ( trim ( $user->login ) ) ), time () + (86400 * $numDays), "/" );
				CacheUtil::addCachedDeviceUser($deviceID, $user);				
			}
		}
	}

	function removeLoggedInUser() {
		if(self::isUseSessionTracking())
		{
			LogUtil::debug ( 'WebUtil.removeLoggedInUser', 'Removing Session Details' );
			if(isset($_SESSION[self::DINAMO_USER])) 
			{
				LogUtil::debug ( 'WebUtil.removeLoggedInUser', 'Setting empty session for User' );
				//reset the user
				unset($_SESSION[self::DINAMO_USER]);
			}
		}else{
			LogUtil::debug ( 'WebUtil.removeLoggedInUser', 'Removing from cookies/cache' );
			setcookie ( self::DINAMO_USER, "", time () - 3600 );
			$deviceID = $this->getDeviceIP();
			CacheUtil::removeCachedDeviceUser($deviceID);
		}
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
			$exploded = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
			$ipAddress = array_pop($exploded);
		}
		return $ipAddress;
	}	
	
	function isUseSessionTracking()
	{
		$session = ConfigUtil::getSessionTracking();
		return $session == 'SESSION';
	}
}
?>