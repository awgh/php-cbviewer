<?php
include_once('password.php');
include_once('util.php');

/**
 * Resizes an image if width of image is bigger than the maximum width
 * @return array the imageinfo of the resized image
 * @param $filename String The path where the image is located
 * @param $maxwidth String[optional] The maximum width the image is allowed to be
 */
function resize($filename, $maxwidth="1024")
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

function CleanCacheDir($dir, $secondsOld) {
	if(!$dh = @opendir($dir)) return;
	while (false !== ($obj = readdir($dh))) {
		if($obj=='.' || $obj=='..') continue;
                $file = $dir.DIRECTORY_SEPARATOR.$obj;
                if ((time()-filectime($file)) > $secondsOld) {  
		    @unlink($file);
                }
	}
	closedir($dh);
}

CleanCacheDir($options['cachepath'],300);

$mimetype = minimime($path);

if ($mimetype == 'application/x-rar-compressed') {
    $filename = rawurldecode(stripslashes($_GET['page']));
    $rar_file = rar_open($path);
    $cachepathname=tempnam($options['cachepath'], "cbr");
    $entry = rar_entry_get($rar_file, $filename);
    $entry->extract(false,$cachepathname);
    rar_close($rar_file);

    $size = resize($cachepathname,intval($_GET['maxwidth']));

    echo '{'."\n\r";
    echo '  "width" : "'.$size[0].'",'."\n\r";
    echo '  "height" : "'.$size[1].'",'."\n\r";
    echo '  "src" : "'.$options['webcache'].'/'.basename($cachepathname).'"'."\n\r";
    echo '}';

  } elseif ($mimetype == 'application/zip') {
	$index = 0;
    if (isset($_GET['page']))
        $index = intval($_GET['page']);
	$zip = new ZipArchive();

	if ($zip->open($path) === TRUE) {
	    $entry = $zip->statIndex($index);
            $cachepathname=tempnam($options['cachepath'], "cbz");
            file_put_contents($cachepathname, $zip->getFromIndex($index));
	    $zip->close();
            $size = resize($cachepathname,  intval($_GET['maxwidth']));

            echo '{'."\n\r";
            echo '  "width" : "'.$size[0].'",'."\n\r";
            echo '  "height" : "'.$size[1].'",'."\n\r";
            echo '  "src" : "'.$options['webcache'].'/'.basename($cachepathname).'"'."\n\r";
            echo '}';
	} else {
	    echo 'failed to extract page from zip.';
	}
} elseif (preg_match('/txt$/i',$basepath)){
	echo "<PRE>";
	readfile($path);
	echo "</PRE>";
} elseif (preg_match('/pdf$/i',$basepath)){
    header('Content-Type: application/pdf');
    readfile($path);
}

?>
