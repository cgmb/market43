<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8"> 
	<title>Market 43 - Dashboard</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>

<?php
	include('navbar.php');
	echo create_navbar('dashboard.php');
?>

<div class="player-balance">
<?php
	session_start() or die('Failed to create session');
	include('database_connect.php');
	include('queries.php');
	echo 'Balance: ' . get_player_balance($_SESSION['userid']);
	mysql_close();
?>
</div>


<H2>Your Bids</H2>
<H2>Your Auctions</H2>

</body>
</html>
