<?php
$options = array();
$options['base'] = "/opt/Comics";
$options['webcache'] = 'cache';
$options['cachepath'] =  pathinfo(realpath("config.php"),PATHINFO_DIRNAME) . "/" . $options['webcache'];
//Leave Empty for no password, password stored unencrypted
$options['password']="";
?>
