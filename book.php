<?php
error_reporting(0);
?>
<?php
?>
<html><head>
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="cache-control" content="no-cache">
<meta http-equiv="expires" content="-1">
<link rel="stylesheet" type="text/css" href="book.css" />
</head><body>
<?php
if ($_GET['filename'] <> '') { 
	$filename = stripslashes($_GET['filename']);
	$filepath = '';
} else {
	die("Error: no book selected!");
}

if ((preg_match('/cbr$/i',$filename)) || (preg_match('/rar$/i',$filename))) {
	$rar_file = rar_open($filepath.$filename) or die ("Failed to open Rar archive");
	$list = rar_list($rar_file);
	$filelist = array();
	foreach ($list as $file) {
		if (($file->unpacked_size > 0) && preg_match('/jpg|gif|png/i',$file->name)) {
			$filelist[] = $file->name;
		}
	};
	natcasesort($filelist);
	foreach ($filelist as $file) {
		print "<a href='view_page.php?page=".rawurlencode($file) ."&comic=".urlencode($filename)."' target='viewer' class='book'>".basename($file)."</a><br />\n";
	}
} elseif ((preg_match('/cbz$/i',$filename))||(preg_match('/zip$/i',$filename))) {
	$zip = new ZipArchive();
	$zip->open($filename) or die("cannot open $filename!\n");

	$filelist = array();

	for ($i = 0; $i < $zip->numFiles; $i++) {
		$entry = $zip->statIndex($i);
		$filelist[$entry[name]] = $i;
	}
//		$entry = $zip->statIndex($i);
	ksort($filelist);
//	print_r($filelist);
	foreach ($filelist as $entry => $i) {
//		print "$entry = $i<br />\n";
		if (preg_match('/(jpg|png|gif)$/i',$entry)) {
		print "<a href='view_page.php?comic=" . rawurlencode($filename) . "&index=" . $i . "' target='viewer' class='book'>".basename($entry)."</a><br />\n";
		}
	}
	$zip->close();
}

?>
<div id='clearfooter'></div>
<div id='footer'>
<a href="index.php?path=<?echo rawurlencode(preg_replace("#/[^/]*$#",'',$filename)) ?>" target="_top">Return to Index</a>
</div>
</body></html>
