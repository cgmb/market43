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
	$userid = $_SESSION['userid'];
	echo 'Balance: ' . get_player_balance($userid);
?>
</div>


<H2>Your Bids</H2>
<table class="item-table">
	<colgroup>
		<col style="width: 15%" />
		<col style="width: 50%" />
		<col style="width: 35%" />
	</colgroup>
	<tr>
		<th>bid</th>
		<th>listing</th>
		<th>close time</th>
	</tr>
<?php
	$query = "SELECT l.ExpiryTimestamp, i.Name, i.IconPath, b.Value
	FROM listing AS l, item_type AS i, item AS x, bid as b
	WHERE i.ItemTypeId = x.ItemType AND l.ListingId = b.Listing AND b.Bidder = '$userid'
	ORDER BY l.ExpiryTimestamp;";

	$result = mysql_query($query) or die (mysql_error());
	$num = mysql_numrows($result);
	$i=0; while ($i < $num) { 
	$expiry = mysql_result($result, $i, 'l.ExpiryTimestamp');
	$name = mysql_result($result, $i, 'i.Name');
	$icon = mysql_result($result, $i, 'i.IconPath');
	$bid = mysql_result($result, $i, 'b.Value');
	echo "<tr>
		<td>$bid</td>
		<td><img class=\"item-icon\" src=\"$icon\">$name</td>
		<td>$expiry</td>
	</tr>";
	$i++;}

	mysql_close();
?>
</table>

<H2>Your Auctions</H2>

</body>
</html>
