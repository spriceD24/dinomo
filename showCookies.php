<?php include_once("util/WebUtil.php"); ?>
<?php include_once("util/StringUtils.php"); ?>

<html>
<body>

<?php

$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);

$stringUtil = new StringUtils();


$cookie_name = WebUtil::DINAMO_USER;

if(!isset($_COOKIE[$cookie_name])) {
    echo "Cookie named '" . $cookie_name . "' is not set!";
} else {
    echo "Cookie '" . $cookie_name . "' is set!<br>";
    echo "Value is: " . $_COOKIE[$cookie_name];
}

echo "<br/><br/>";
print_r($_SERVER);
?>

</body>
</html>