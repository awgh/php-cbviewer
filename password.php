<?php
include_once('config.php');
if(!empty($options['password']))
{
	session_start();
	if(isset($_POST['login_password']))
	{
		if($_POST['login_password']==$options['password'])
		{
			session_register('Logged_In');
			$_SESSION['Logged_In']="yes";
			
		}
		else
		{
			$error = "An error occurred. Password incorrect.";
		}
	}
	if($_SESSION['Logged_In']!="yes")
	{
		
?>
<html>
	<head>
		<link rel="stylesheet" href="main.css">
		<title>Area Resitricted</title>
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
?>
