
<?php include_once("util/Cache.php"); ?>
<?php include_once("util/ConfigUtil.php"); ?>
<?php include_once("util/LogUtil.php"); ?>
    
<?php

	/**
	class CacheUtil
	{
		//Project cache details
		//Category cache details
		//Category option cache details
		//Device cache details
		//Cached project details
		static function cacheProjectsList($projects)
		static function clearCacheProjectsList()
		static function getCachedProject($projectID)
		private static function getCachedCategoryKey($projectID,$categoryID)
		//Cached device details