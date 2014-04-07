<?php
	if (!session_start()) {
		header('Location: index.php?sessionexpired');
		exit();
	}
	!empty($_SESSION['userid']) or die('Session lacks userid!');
?>
