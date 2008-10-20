<?php
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="index.css" />
</head><body>
<?php
include('config.php');

if ($_GET['path']) {
	if (preg_match(":$base:",$_GET['path']))
	{
		$path = stripslashes($_GET['path']);
	} else { 
		$path = $base;
	}
} else {
	$path = $base;
};

if (preg_match('/[.][.]/',$path)) {
	$path = preg_replace('#/[^/]*/[.][.]#','',$path);
	if (preg_match("#^$base#",$path)) { }
	else { 
		$path = $base;
	}
}

$dir_handle = @opendir($path) or die("Unable to open $path");

echo "<h2>Directory Listing of $path</h2><br />\n";

$files = array();

while ($file = readdir($dir_handle))
{
	if($file == '.' || $file == '.DS_Store') continue;
	array_push($files,$file);

}

closedir($dir_handle);

natcasesort($files);
foreach($files as $file) {
	if (preg_match('/^[.][.]$/',$file)) {
		echo "<a href='index.php?path=".preg_replace('|/[^/]+$|','',$path) . "' class='prev'>Go Up</a><br />\n";
	} elseif (is_dir($path."/".$file)) {
		echo '<a  href="index.php?path='.rawurlencode($path).'/'.rawurlencode($file).'" class="dir">'.$file.'</a><br />'."\n";
	} elseif (preg_match('/^._/',$file)) {
		continue;
      } else {
	if ((preg_match('/[.]cb[rz]$/i',$file))||(preg_match('/[.]zip$/i',$file))||(preg_match('/[.]rar$/i',$file))) {
              echo "<a href='splitscreen.php?filename=".rawurlencode($path)."/".rawurlencode($file)."' class='cbr'>$file</a><br />\n";
//	} elseif (preg_match('/[.]cbz$/i',$file)) {
//		echo "<a href='splitzip.php?filename=".rawurlencode($path)."/".rawurlencode($file)."' class='cbz'>$file</a><br />\n";
	} else {
		echo "$file<br />\n";
	}
      };
}

?>
</body></html>
