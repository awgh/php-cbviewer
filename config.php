<?php
$options = array();
$options['sitename'] = "Comic Books, Yo";
$options['base'] = "/mnt/media/Comics";
$options['webcache'] = 'cache';
$options['cachepath'] =  pathinfo( realpath("config.php"), PATHINFO_DIRNAME)
                        . DIRECTORY_SEPARATOR . $options['webcache'];

//Leave Empty for no password, password stored unencrypted
$options['password']="comics";
$options['whitelist_subnet'] = "192.168.1.1/24";

$options['fullwidth']=true;

header("pragma: no-store,no-cache");
header("cache-control: no-cache, no-store,must-revalidate, max-age=-1");
header("expires: Sat, 26 Jul 1997 05:00:00 GMT");
error_reporting(E_ALL);
ini_set('display_errors', '1');
date_default_timezone_set('America/Chicago');
?>
