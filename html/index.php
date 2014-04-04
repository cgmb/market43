<?php
	$errortext = '';
	if (!empty($_GET)) {
		if (isset($_GET['logout'])) {
			session_start() or die('Failed to create session');
			session_unset();
			session_destroy();
		}

		if (isset($_GET['authfailed'])) {
			$errortext = "Authentication failed.<br>Check your email and password.";
		}
	}

	if (!empty($_POST)) {
		$email = mysql_real_escape_string($_POST['email']);
		$pass = $_POST['password'];
		if (!empty($email) && !empty($pass)) {
			include('database_connect.php');
			session_start() or die('Failed to create session');
			$_SESSION = array();
			$query="SELECT u.UserId, c.SaltedHash
				FROM user AS u INNER JOIN credential AS c ON c.User = u.UserId
				WHERE u.Email = '$email' AND c.Active <> 0;";
			$result = mysql_query($query) or die (mysql_error());
			$rows = mysql_numrows($result);
			$i=0; while ($i < $rows) { 
				$userid = mysql_result($result, $i, 'u.UserId');
				$hash = mysql_result($result, $i, 'c.SaltedHash');
				if (password_verify($pass, $hash)) {
					$_SESSION['userid'] = $userid;
					header('Location: dashboard.php');
					exit();
				}
			$i++;
			}
			header('Location: index.php?authfailed');
			exit();
		}
	}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8"> 
	<title>Market 43</title>
	<link rel="stylesheet" href="style.css">
</head>
<br><br>
<center>
	<img src="title.png">
</center>
<br><br><br><br><br>
<form action='index.php' method='post' class="login-form">
<?php
	echo "<div class='error-text'>$errortext</div>"
?>
	<span class="form-label">Email:</span>
	<input type="text" name="email" size=20>
	<br>	
	<span class="form-label">Password:</span>
	<input type="password" name="password" size=20>
	<br>
	<br>
	<input type="submit" value="Login">
	<input type="button" value="Register" onClick="location.href='dashboard.php';">
</form>
</body>
</html>
