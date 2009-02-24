<?php
include_once('password.php');
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="css/main.css" />
<link rel="stylesheet" type="text/css" href="css/index.css" />
<title>PHP CBViewer - <?php
if ($_GET['path']) {
        if (preg_match(":$options[base]:",$_GET['path']))
        {
                $path = stripslashes($_GET['path']);
        } else {
                $path = $options['base'];
        }
} else {
        $path = $options['base'];
};
echo preg_replace('#'.$options['base'].'(/?)#','/',$path);
?></title>
</head><body>
<?php

if ($_GET['path']) {
	if (preg_match(":$options[base]:",$_GET['path']))
	{
		$path = stripslashes($_GET['path']);
	} else { 
		$path = $options['base'];
	}
} else {
	$path = $options['base'];
};

if (preg_match('/[.][.]/',$path)) {
	$path = preg_replace('#/[^/]*/[.][.]#','',$path);
	if (preg_match("#^$options[base]#",$path)) { }
	else { 
		$path = $options['base'];
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
              echo "<a href='book.php?filename=".rawurlencode($path)."/".rawurlencode($file)."' class='cbr'>$file</a><br />\n";
	} elseif ((preg_match('/txt$/i',$file))||(preg_match('/htm(l?)$/i',$file))) {
	      echo "<a href='view_page.php?page=".rawurlencode($path)."/".rawurlencode($file)."' class='file'>$file</a><br />\n";
	} else {
		echo "$file<br />\n";
	}
      };
}

?>
</body></html>
