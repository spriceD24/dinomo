<?php include_once("util/CacheUtil.php"); ?><?php include_once("util/WebUtil.php"); ?><?php include_once("delegate/ProjectDelegate.php"); ?><?php include_once("delegate/UserDelegate.php"); ?>
<html>
<body>

<?php
	CacheUtil::clearCacheProjectsList();	CacheUtil::removeAllCachedCategories();	CacheUtil::removeAllCachedCategoryOptions();	CacheUtil::removeAllCachedDeviceUser();	CacheUtil::removeAllCachedProjects();	CacheUtil::removeCachedUsers();	print "<br/>Cleared Cache...<br/>";?>

</body>
</html>