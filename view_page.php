<?php
	header("pragma: no-store,no-cache");
	header("cache-control: no-cache, no-store,must-revalidate, max-age=-1");
	header("expires: Sat, 26 Jul 1997 05:00:00 GMT");

?>
<html><head>
</head><body>
<?php

include('config.php');

function SureRemoveDir($dir, $DeleteMe) {
	if(!$dh = @opendir($dir)) return;
	while (false !== ($obj = readdir($dh))) {
		if($obj=='.' || $obj=='..') continue;
		if(!@unlink($dir.'/'.$obj)) SureRemoveDir($dir.'/'.$obj, true);
	}

	closedir($dh);
	if($DeleteMe) {
		@rmdir($dir);
	}
}

SureRemoveDir($cachepath,false);

$comicname = urldecode(stripslashes($_GET['comic']));

$faketmp = date('U');

if ((preg_match('/cbr$/i',$comicname))||(preg_match('/rar$/i',$comicname))) {
	$filename = urldecode(stripslashes($_GET['page']));
	$rar_file = rar_open($comicname);
	$entry = rar_entry_get($rar_file,$filename);
	$entry->extract(false,$cachepath.'/'.$faketmp.basename($filename));
	rar_close($rar_file);
	print "<img src='" . $webcache .'/'. rawurlencode($faketmp.basename($filename))."' width='100%' /><br />\n";
} elseif ((preg_match('/cbz$/i',$comicname))||(preg_match('/zip$/i',$comicname))) {
	$index = $_GET['index'];
	$zip = new ZipArchive();
	if ($zip->open($comicname) === TRUE) {
		$entry = $zip->statIndex($index);
		file_put_contents($cachepath."/".$faketmp.basename($entry['name']),$zip->getFromIndex($index));
		$zip->close();
		print "<img src='" . $webcache.'/'.$faketmp.rawurlencode(basename($entry['name']))."' width='100%' /><br />\n";
	} else {
		echo 'failed to extract page from zip.';
	}
	
}

?></body>
</html>
