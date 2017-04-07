<?php include_once("util/StringUtils.php"); ?>
<?php

// Include and instantiate the class.
require_once 'mobile_detect/Mobile_Detect.php';
$detect = new Mobile_Detect ();

echo 'Current PHP version: ' . phpversion().'<br/>';
echo ' Version greater = '.version_compare(PHP_VERSION, '5.6.0') .'<br/>';
echo ' Pass = '.StringUtils::padKey('stef').'<br/>';
echo ' Pass = '.strlen(StringUtils::padKey('stef')).'<br/>';
echo ' Pass = '.StringUtils::encode('stef').'<br/>';

//strlen().

// Any mobile device (phones or tablets).
if ($detect->isMobile ()) {
	echo "Is Mobile<br/>";
}

// Any tablet device.
if ($detect->isTablet ()) {
	echo "Is Tablet<br/>";
}

// Exclude tablets.
if ($detect->isMobile () && ! $detect->isTablet ()) {
	echo "Is Mobile Only<br/>";
}

if (! $detect->isMobile () && ! $detect->isTablet ()) {
	echo "Is Desktop<br/>";
}

// Check for a specific platform with the help of the magic methods:
if ($detect->isiOS ()) {
	echo "Is Apple<br/>";
}

if ($detect->isAndroidOS ()) {
	echo "Is Andriod<br/>";
}
?>
