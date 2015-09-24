<?php
include_once('password.php');

?>
<!DOCTYPE html PUBLIC"-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="css/main.css" />
<link rel="stylesheet" type="text/css" href="css/index.css" />
<title><?php echo $options['sitename']." - ".$relpath;?></title>
</head><body>

<?php
echo "<h2>".$options['sitename']." - ".$relpath."</h2><br />\n";

$dir_handle = @opendir($path) or die("Unable to open $relpath");
$files = array();

while ($file = readdir($dir_handle))
{
	if($file == '.' || $file == '.DS_Store') continue;
	array_push($files,$file);
}
closedir($dir_handle);
natcasesort($files);

for ($i = 0; $i < sizeof($files); $i++) {
    $file = array_values($files)[$i];
	if (preg_match('/^[.][.]$/',$file)) {
		echo "<a href='index.php?path=".rawurlencode(preg_replace('|/[^/]+$|','',$relpath)) . "' class='prev'>Go Up</a><br />\n";
	} elseif (is_dir($path."/".$file)) {
		echo '<a  href="index.php?path='.rawurlencode($relpath.'/'.$file).'" class="dir">'.$file.'</a><br />'."\n";
	} elseif (preg_match('/^._/',$file)) {
		continue;
      } else {
	if ((preg_match('/[.]cb[rz]$/i',$file))||(preg_match('/[.]zip$/i',$file))||(preg_match('/[.]rar$/i',$file))) {
        if($i+1 < sizeof($files))
            echo "<a href='book.php?path="
                .rawurlencode($relpath.DIRECTORY_SEPARATOR.$file)."' class='cbr'>$file</a><br />\n";
        else
            echo "<a href='book.php?path="
                .rawurlencode($relpath.DIRECTORY_SEPARATOR.$file)."' class='cbr'>$file</a><br />\n";
    } elseif ((preg_match('/txt$/i',$file))||(preg_match('/htm(l?)$/i',$file))) {
	      echo "<a href='view_page.php?path=".rawurlencode($relpath.DIRECTORY_SEPARATOR.$file)."' class='file'>$file</a><br />\n";
    } elseif (preg_match('/pdf$/i',$file)) {
        echo "<a href='view_page.php?path=".rawurlencode($relpath.DIRECTORY_SEPARATOR.$file)."' class='file'>$file</a><br />\n";
	} else {
		echo "$file<br />\n";
	}
      };
}

?>
</body></html>
