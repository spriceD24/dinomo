<!DOCTYPE html>
<html lang="en">
<?php include_once("util/LogUtil.php"); ?><?php include_once("util/CacheUtil.php"); ?><?php include_once("mobile_detect/Mobile_Detect.php");?><?php
$cacheMsg = "";$cacheItem = 0;$detect = new Mobile_Detect ();
if (isset ( $_GET ["cacheitem"] )) {	$cacheItem = intval ( $_GET ["cacheitem"] );}

if($cacheItem > 0)
{	LogUtil::debug ("cache_admin.php", "executing action number = ".$cacheItem);	if($cacheItem == 1)	{		LogUtil::debug ("cache_admin.php", "Flushing all Caches (except device)");		$cacheMsg = "Flushed All Caches (except Device)";		CacheUtil::removeAllCachedProjects();		CacheUtil::removeAllCachedCategories();		CacheUtil::removeAllCachedCategoryOptions();		CacheUtil::removeCachedUsers();	}	if($cacheItem == 2)	{		LogUtil::debug ("cache_admin.php",  "Flushing all Project Data");		$cacheMsg = "Flushed All Project Data";		CacheUtil::removeAllCachedProjects();	}	if($cacheItem == 3)	{		LogUtil::debug ("cache_admin.php",  "Flushing all Category Data");		$cacheMsg = "Flushed All Category Data";		CacheUtil::removeAllCachedCategories();		CacheUtil::removeAllCachedCategoryOptions();	}	if($cacheItem == 4)	{		LogUtil::debug ("cache_admin.php",  "Flushing all User Data");		$cacheMsg = "Flushed All User Data";		CacheUtil::removeCachedUsers();	}		if($cacheItem == 5)	{		LogUtil::debug ("cache_admin.php",  "Flushing all Device Data");		$cacheMsg = "Flushed All Device Data";		CacheUtil::removeAllCachedDeviceUser();	}	}?>

<head>
<meta charset="utf-8"><link rel="icon" type="image/png" href="img/favicon-32x32.png" sizes="32x32" /><title>Dinomo QA</title><meta name="viewport"	content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"><meta name="apple-mobile-web-app-capable" content="yes">
<script src="js/jquery-1.7.2.min.js"></script><script src="js/bootstrap.js"></script><script src="js/base.js"></script><link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" /><link href="css/bootstrap-responsive.min.css" rel="stylesheet"	type="text/css" /><link rel="stylesheet" type="text/css" href="css/dinamo.css"><link href="css/font-awesome.css" rel="stylesheet"><link	href="css/google-fonts.css"	rel="stylesheet">

<link href="css/style.css" rel="stylesheet" type="text/css"><link href="css/pages/signin.css" rel="stylesheet" type="text/css">
</head>
<body>	<div class="container">

		<div class="row">

			<div class="span12">

				<div class="error-container">

					<h2><?=$cacheMsg;?></h2>

					<div <?php
							if (!$detect->isMobile () ||  $detect->isTablet ()) {
								print " style='padding-left:40%'";
							}
							?>>
						<table>
							<tr>
								<td><a href="cache_admin.php?cacheitem=1">Flush ALL Caches (except device)</a><br/></td>
							</tr>
							<tr>
								<td><a href="cache_admin.php?cacheitem=2">Flush Project Caches</a><br/></td>
							</tr>
							<tr>
								<td><a href="cache_admin.php?cacheitem=3">Flush Category Caches</a><br/></td>
							</tr>
							<tr>
								<td><a href="cache_admin.php?cacheitem=4">Flush User Caches</a><br/></td>
							</tr>
							<tr>
								<td><a href="cache_admin.php?cacheitem=5">Flush Device Cache</a><br/></td>
							</tr>
						</table>
					</div>
<br/><br/>
					<div class="error-actions">					<!-- 
						<a href="index.html" class="btn btn-large btn-primary"> <i
							class="icon-chevron-left"></i> &nbsp; Back to Homepage
						</a> 					-->



					</div>
					<!-- /error-actions -->

				</div>
				<!-- /error-container -->

			</div>
			<!-- /span12 -->

		</div>
		<!-- /row -->

	</div>
	<!-- /container -->


	<script src="js/jquery-1.7.2.min.js"></script>
	<script src="js/bootstrap.js"></script>

</body>

</html>
