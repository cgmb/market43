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

<?php
	if (!empty($_POST)) {
		$email = $_POST['email'];
		if (!empty($email)) {
			include('database_connect.php');
			session_start() or die('Failed to create session');
			$query="SELECT u.UserId FROM user AS u WHERE u.Email = '$email';";
			$result=mysql_query($query) or die (mysql_error());
			if (mysql_numrows($result) == 1) {
				$_SESSION['userid'] = mysql_result($result, 0, 'u.UserId');
			} else {
				echo "No such user: $email";
			}
		}
	}
?>

<form action='index.php' method='post' class="login-form">
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
