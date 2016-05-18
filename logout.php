<html>
<body>
<?php include_once("util/WebUtil.php"); ?>
<?php

    $webUtil = new WebUtil();
	$webUtil->srcPage = "logout.php";
	set_error_handler(array($webUtil, 'handleError'));

	echo "Before = ".$_COOKIE[WebUtil::DINAMO_USER]."<br/>";
	//setcookie(WebUtil::DINAMO_USER, "XX", time());
	$webUtil->addLoggedInUser("",1);
	echo "After = ".$_COOKIE[WebUtil::DINAMO_USER]."<br/>";
	
	//header("Location: login.p,1\hp");
	//exit;
	
?>
</body>
</html>