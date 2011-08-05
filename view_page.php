<?php
	header("pragma: no-store,no-cache");
	header("cache-control: no-cache, no-store,must-revalidate, max-age=-1");
	header("expires: Sat, 26 Jul 1997 05:00:00 GMT");
//error_reporting(E_ALL);
//ini_set('display_errors', '1');

	include_once('password.php');
date_default_timezone_set('America/Chicago'); 
/**
 * Resizes an image if width of image is bigger than the maximum width
 * @return array the imageinfo of the resized image
 * @param $filename String The path where the image is located
 * @param $maxwidth String[optional] The maximum width the image is allowed to be
 */
function resize($filename,$maxwidth="1024")
{
	$inputfunctions = array('image/jpeg'=>'imagecreatefromjpeg',
		'image/png'=>'imagecreatefrompng',
		'image/gif'=>'imagecreatefromgif');
	$outputfunctions = array('image/jpeg'=>'imagejpeg','image/png'=>'imagepng','image/gif'=>'imagegif');
	$imageinfo = getimagesize($filename);
	$currentheight = $imageinfo[1];
	$currentwidth = $imageinfo[0];
	if($imageinfo[0] < $maxwidth)
	{
		return $imageinfo;
	}
	$img = $inputfunctions[$imageinfo['mime']]($filename);
	$newwidth = $maxwidth;
	$newheight = ($currentheight/$currentwidth)*$newwidth;
	$newimage = imagecreatetruecolor($newwidth,$newheight);
	imagecopyresampled($newimage,$img,0,0,0,0,$newwidth,$newheight,$currentwidth,$currentheight);
	$outputfunctions[$imageinfo['mime']]($newimage,$filename);
	return getimagesize($filename);
}

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

SureRemoveDir($options['cachepath'],false);

if ($_GET['comic']) {$comicname = urldecode(stripslashes($_GET['comic']));}
else {
	$comicname = $_GET['page'];
};

$faketmp = date('U');
//print "<a href='#' onClick='next_page();'>";
if ((preg_match('/cbr$/i',$comicname))||(preg_match('/rar$/i',$comicname))) {
	$filename = urldecode(stripslashes($_GET['page']));
	$rar_file = rar_open($comicname);
	$cachepathname=$options['cachepath'].'/'.$faketmp.basename($filename);
	$entry = rar_entry_get($rar_file,$filename);
	$entry->extract(false,$cachepathname);
	rar_close($rar_file);
	$size = resize($cachepathname,$_GET['maxwidth']);
	//$joe = imagecreatefromjpeg($options['cachepath']."/".$faketmp.basename($filename));
//	print "<img width='".$size[0]."' height='".$size[1]."' class='myimage' src='" . $options['webcache'] .'/'. rawurlencode($faketmp.basename($filename))."' /><br />\n";
		echo "{
	width:'$size[0]',
	height:'$size[1]',
	src:'" . $options['webcache'].'/'.$faketmp.rawurlencode(basename($filename))."'
}";
} elseif ((preg_match('/cbz$/i',$comicname))||(preg_match('/zip$/i',$comicname))) {
	$index = $_GET['index'];
	$zip = new ZipArchive();
	if ($zip->open($comicname) === TRUE) {
		$entry = $zip->statIndex($index);
		$cachepathname = $options['cachepath']."/".$faketmp.basename($entry['name']);
		file_put_contents($cachepathname,$zip->getFromIndex($index));
		$zip->close();
		$size = resize($cachepathname,$_GET['maxwidth']);
		//print "<img width='".$size[0]."' height='".$size[1]."' class='myimage' src='" . $options['webcache'].'/'.$faketmp.rawurlencode(basename($entry['name']))."'  /><br />\n";
		echo "{
'width':'$size[0]',
'height':'$size[1]',
'src':'" . $options['webcache'].'/'.$faketmp.rawurlencode(basename($entry['name']))."'
}";
	} else {
		echo 'failed to extract page from zip.';
	}
	
} elseif (preg_match('/txt$/i',$comicname)){
	$index = $_GET['index'];
	echo "<PRE>";
	readfile($comicname);
	echo "</PRE>";
}

?>
