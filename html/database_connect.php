<?php 
	$username="root";
	$password="your_password";
	$database="market43";

	mysql_connect('localhost', $username, $password);
	@mysql_select_db($database) or die(mysql_error());
 ?>
