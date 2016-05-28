<!DOCTYPE html>
<html lang="en">
<?php include_once("util/LogUtil.php"); ?>
$cacheMsg = "";
if (isset ( $_GET ["cacheitem"] )) 

if($cacheItem > 0)
{

<head>
<meta charset="utf-8">
<script src="js/jquery-1.7.2.min.js"></script>

<link href="css/style.css" rel="stylesheet" type="text/css">
</head>
<body>

				<div class="nav-collapse">
			</div>
			<!-- /container -->
		<!-- /navbar-inner -->
	<!-- /navbar -->

	<div class="container">

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
					<div class="error-actions">
						<a href="index.html" class="btn btn-large btn-primary"> <i
							class="icon-chevron-left"></i> &nbsp; Back to Homepage
						</a>



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