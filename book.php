<?php
include_once('password.php');
include_once('util.php');

$mimetype = minimime($path);
?>
<!DOCTYPE html PUBLIC"-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="cache-control" content="no-cache">
<meta http-equiv="expires" content="-1">
<link rel="stylesheet" type="text/css" href="css/main.css" />
<link rel="stylesheet" type="text/css" href="css/book.css" />
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/jquery.transform2d.js"></script>
<script type="text/javascript">
    var pageIndex = 0;
    var pageTable = [];
<?php
    // Unzip the file and load the pages
    $pageCount = 0;

    if ($mimetype == 'application/x-rar-compressed') {
        $rar_file = rar_open($path) or die ("Failed to open Rar archive");
        $list = rar_list($rar_file);
        usort($list, "cmp_rar_obj");
        foreach ($list as $file) {
            if (($file->getUnpackedSize() > 0) && preg_match('/jp(e?)g|gif|png/i',$file->getName())) {
                $lhs = rawurlencode($file->getName());
                $rhs = basename($file->getName());
                print "pageTable.push([\"".$lhs."\",\"".$rhs. "\"]);";
                $pageCount++;
            };
        }

    } elseif ($mimetype == 'application/zip') {
        $zip = new ZipArchive();
        $zip->open($path) or die("cannot open $relpath!\n");
        $filelist = array();

        for ($i = 0; $i < $zip->numFiles; $i++) {
            $entry = $zip->statIndex($i);
            $filelist[$entry['name']] = $i;
        }
        ksort($filelist);

        foreach ($filelist as $entry => $i) {
            if (preg_match('/(jp(e?)g|png|gif)$/i',$entry)) {
                $lhs = ""+$i;
                $rhs = basename($entry);
                print "pageTable.push([\"".$lhs."\",\"".$rhs. "\"]);";
                $pageCount++;
            }
        }
        $zip->close();
    }

    // Populate the next comic link
    $dir_handle = @opendir(dirname($path)) or die("Unable to open ".dirname($relpath));
    $files = array();

    while ($file = readdir($dir_handle))
    {
	    if($file == '.' || $file == '.DS_Store') continue;
	    array_push($files,$file);
    }
    closedir($dir_handle);
    natcasesort($files);

    $next = "index.php?path=".rawurlencode(preg_replace("#/[^/]*$#",'',$relpath));
    $foundMe = false;
    for ($i = 0; $i < sizeof($files); $i++) {
        $file = array_values($files)[$i];

        if($foundMe) {
            $next = "book.php?path=".rawurlencode(dirname($relpath).DIRECTORY_SEPARATOR.$file);
            break;
        }

        if (strcmp(basename($file), $basepath) == 0)
        {
            $foundMe = true;
        }
    }
?>
    var relative_path = "<?php echo rawurlencode($relpath);?>";
</script>
<script type="text/javascript" src="js/book.js"></script>

<?php if($options['fullwidth'])
{?>
<style = "text/css">
	.myimage{
		width:100%;
	}
</style>
<?php }?>
<title><?php echo $basepath; ?></title>
</head><body>
<div id="loading">
	<img src="img/ajax-loader.gif" />
</div>

<div class="floater">
<div id="return_to_index" onclick="window.location='index.php?path=<?php
echo rawurlencode(preg_replace("#/[^/]*$#",'',$relpath));
?>';"></div>

<div id="comic_options" class="gradient">
    <input id="pageInput" class="gradient" type="number" min="1" max="<?php echo $pageCount; ?>" step="1" />
    of <?php echo $pageCount; ?>
</div>
<div id="rotate_page" onclick="rotate_page();"></div>
<div id="next_comic" onclick="window.location='<?php echo $next; ?>'"></div>
<div id="prev_page" onclick="prev_page();"></div>
<div id="next_page" onclick="next_page();"></div>

<input type="hidden" id="windowsize" name="windowsize" value="1024" />

</div>

<div id="comicbookpage"></div>

<div id='clearfooter'></div>
<div id='footer'></div>
</body>
</html>
