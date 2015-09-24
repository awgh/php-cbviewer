<?php
include_once('config.php');
include_once('util.php');
session_start();
$error = "";

if(!empty($options['whitelist_subnet']))
{
    $subnet = $options['whitelist_subnet'];
    $ipaddress = $_SERVER['REMOTE_ADDR'];
    if( cidr_match($ipaddress, $subnet) ) {
        $_SESSION['Logged_In'] = "yes";
    }
}

if(!empty($options['password']))
{
	if(isset($_POST['login_password']))
	{
		if($_POST['login_password']==$options['password'])
		{
            if (!isset($_SESSION['Logged_In']))
                $_SESSION['Logged_In'] = "yes";
		}
		else
		{
			$error = "An error occurred. Password incorrect.";
		}
	}
	if(!isset($_SESSION['Logged_In']) || $_SESSION['Logged_In']!="yes")
	{

?>
    <!DOCTYPE html PUBLIC"-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="main.css">
    <title>Area Restricted</title>
	</head>
	<body>
		<h1>Please Log In</h1>
		<?=$error; ?>
		<form action="" method="post">
			<table>
				<tr>
					<td>Password:</td>
					<td><input type="password" name="login_password" /></td>
				</tr>
				<tr>
					<td colspan="2">
						<input type="submit" value="Login" />
					</td>
				</tr>
			</table>
		</form>
	</body>
</html>
<?php
	exit(0);
	}
}


function validFilename($filename)
{
    global $options;

    $path = $options['base']
            . DIRECTORY_SEPARATOR
            . rawurldecode($filename);

    return realpath($path);
}

if( isset($_GET['path']) )
    $path = validFilename($_GET['path']);
else $path = validFilename('');
$relpath = substr($path, strlen($options['base']));
$basepath = basename($path);
?>
