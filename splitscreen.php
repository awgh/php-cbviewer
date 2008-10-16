<?php
?>
<html><head>
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="cache-control" content="no-cache">
<meta http-equiv="expires" content="-1">
<title><?echo basename($_GET['filename'])?></title></head>
<frameset cols=10%,*>
	<?
//	if (preg_match('/cb[rz]$/i',$_GET['filename'])) {
		print "<frame src='book.php?filename=".rawurlencode(stripslashes($_GET['filename']))."' name='book'>\n";
//	}
	?>
	<frame src='' name='viewer'>
</frameset>
