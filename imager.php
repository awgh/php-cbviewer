<?php

$imgname = $_GET['imagename'];

$inputfunctions = array('image/jpeg'=>'imagecreatefromjpeg',
	'image/png'=>'imagecreatefrompng',
	'image/gif'=>'imagecreatefromgif');
$outputfunctions = array('image/jpeg'=>'imagejpeg','image/png'=>'imagepng','image/gif'=>'imagegif');
$mimetype = $imageinfo['mime'];
$imageinfo = getimagesize($imgname);
$im = $inputfunctions[$imageinfo['mime']]($imgname);
if(!$im)
{
	/* Create a black image */
        $im  = imagecreatetruecolor(150, 30);
        $bgc = imagecolorallocate($im, 255, 255, 255);
        $tc  = imagecolorallocate($im, 0, 0, 0);

        imagefilledrectangle($im, 0, 0, 150, 30, $bgc);

        /* Output an error message */
        imagestring($im, 1, 5, 5, 'Error loading ' . $imgname, $tc);
}

$contenttype = 'Content-Type: ' . $imageinfo['mime'];

header($contenttype);

$outputfunctions[$imageinfo['mime']]($im);
imagedestroy($img);
?>

