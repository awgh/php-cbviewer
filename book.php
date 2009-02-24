<?php
	include_once('password.php');
?>
<html><head>
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="cache-control" content="no-cache">
<meta http-equiv="expires" content="-1">
<link rel="stylesheet" type="text/css" href="css/main.css" />
<link rel="stylesheet" type="text/css" href="css/book.css" />
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/book.js"></script>
<?php if($options['fullwidth'])
{?>
<style = "text/css">
	.myimage{
		width:100%;
	}
</style>
<?php }?>
<title><?php
echo basename(stripslashes($_GET['filename']));	
?></title>
</head><body>
<div id="loading">
	<img src="img/ajax-loader.gif" />
</div>
<div id="comicbookpage">
	
</div>
<div id="prev_page"><a href="javascript:prev_page();">&lt; &lt;Prev Page</a></div>
<div id="next_page"><a href="javascript:next_page();">Next Page &gt; &gt;</a></div>
<div id="comic_options">
<?php
if ($_GET['filename'] <> '') { 
	$filename = stripslashes($_GET['filename']);
	$filepath = '';
} else {
	die("Error: no book selected!");
}

if ((preg_match('/cbr$/i',$filename)) || (preg_match('/rar$/i',$filename))) {
	print "<select name='page' id='pageinfo' onChange='get_page();'>";
	$rar_file = rar_open($filepath.$filename) or die ("Failed to open Rar archive");
	$list = rar_list($rar_file);
	sort($list);
	foreach ($list as $file) {
		if (($file->unpacked_size > 0) && preg_match('/jpg|gif|png/i',$file->name)) {
			print "<option class='pagename' value='".rawurlencode($file->name) ."'>".basename($file->name)."</option>";
		};
	}
} elseif ((preg_match('/cbz$/i',$filename))||(preg_match('/zip$/i',$filename))) {
	print "<select name='index' id='pageinfo' onChange='get_page();'/>";
	$zip = new ZipArchive();
	$zip->open($filename) or die("cannot open $filename!\n");
	$filelist = array();
	for ($i = 0; $i < $zip->numFiles; $i++) {
		$entry = $zip->statIndex($i);
		$filelist[$entry[name]] = $i;
	}
	ksort($filelist);
	
	foreach ($filelist as $entry => $i) {
		if (preg_match('/(jpg|png|gif)$/i',$entry)) {
		print "<option class='pagename' value='". $i."'>".basename($entry)."</option>";
		}
	}
	$zip->close();
}
	print '</select>';
	print '<input type="hidden" id="comic" name="comic" value="'.rawurlencode($filename).'"/>';
	print '<input type="hidden" id="windowsize" name="windowsize" value="1024" />'
?>
</div>
<div id='clearfooter'></div>
<div id='footer'>
<a href="index.php?path=<?echo rawurlencode(preg_replace("#/[^/]*$#",'',$filename)) ?>" target="_top">Return to Index</a>
</div>
</body>
</html>
