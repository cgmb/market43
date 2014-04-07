<?php
	$errortext = '';
	if (!empty($_POST)) {
		if (empty($_POST['nickname']) or empty($_POST['email']) or empty($_POST['password']) or empty($_POST['password2'])) {
			$errortext = 'Cannot create account. All fields are required.';
		}

		$nickname = mysql_real_escape_string($_POST['nickname']);
		$email = mysql_real_escape_string($_POST['email']);
		$pass = $_POST['password'];
		$pass2 = $_POST['password2'];

		include('database_connect.php');
		session_start() or die('Failed to create session');

		if (!empty($email) && !empty($pass)) {
			if ($pass == $pass2) {
				$query = "INSERT INTO user (Email, Nickname, AccountType) values (
					'$nickname', '$email', 1)";
				if (mysql_query($query)) {
					$query = "SELECT UserId FROM user WHERE Email = '$email'";
					$result = mysql_query($query) or die (mysql_error());
					(mysql_numrows($result) == 1) or die('Unexpected data!');
					$userid = mysql_result($result, 0, 'UserId');

					$options = array('cost' => 8);
					$hash = password_hash($pass, PASSWORD_BCRYPT, $options);

					$query = "INSERT INTO credential (SaltedHash, User, Active) values (
					'$hash', '$userid', true)";
					if (mysql_query($query)) {
						$_SESSION['userid'] = $userid;
						header('Location: dashboard.php');
						exit();
					} else {
						$errortext = 'Cannot create account with that password.';
					}
				} else {
					$errortext = 'Could not create account with that email.';
				}
			} else {
				$errortext = 'Passwords don\'t match.<br>Check your passwords and try again.';
			}
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
<H1>Registration</H1>
<form action='register.php' method='post'>
<?php
	echo "<div class='error-text'>$errortext</div>"
?>
	<span class="form-label">Nickname:</span>
	<input type="text" name="nickname" size=20>
	<br>	
	<span class="form-label">Email:</span>
	<input type="email" name="email" size=20>
	<br>	
	<span class="form-label">Password:</span>
	<input type="password" name="password" size=20>
	<br>
	<span class="form-label"></span>
	<input type="password" name="password2" size=20>
	<br>
	<br>
	<input style="width: 25em" type="submit" value="Submit">
</form>
</body>
</html>
