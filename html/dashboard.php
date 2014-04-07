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

	session_start() or die('Failed to create session');
	include('database_connect.php');
	include('queries.php');
	$userid = $_SESSION['userid'];
?>

<div class="player-name">
<?php
	echo get_player_name($userid);
?>
</div>
<div class="player-balance">
<?php
	echo 'Balance: ' . get_player_balance($userid);
?>
</div>
<div class="player-liabilities">
<?php
	echo 'Liabilities: ' . get_player_liabilities($userid);
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
	$query = "SELECT l.ExpiryTimestamp, l.ListingId, i.Name, i.IconPath, b.Value
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
		$listing = mysql_result($result, $i, 'l.ListingId');
		echo "<tr>
			<td>$bid</td>
			<td><a href=\"listing.php?id=$listing\">
				<img class=\"item-icon\" src=\"$icon\">$name</a>
			</td>
			<td>$expiry</td>
		</tr>";
		$i++;
	}
?>
</table>

<H2>Your Auctions</H2>
<table class="item-table">
	<colgroup>
		<col style="width: 15%" />
		<col style="width: 50%" />
		<col style="width: 35%" />
	</colgroup>
	<tr>
		<th>current bid</th>
		<th>listing</th>
		<th>close time</th>
	</tr>
<?php
	$query = "SELECT MAX(b.Value) CurrentBid, l.ListingId, l.ExpiryTimestamp, i.Name, i.IconPath
	FROM listing AS l, item_type AS i, item AS x, bid as b
	WHERE i.ItemTypeId = x.ItemType AND l.ListingId = b.Listing AND l.ListingUserId = '$userid'
	GROUP BY l.ListingId
	ORDER BY l.ExpiryTimestamp;";

	$result = mysql_query($query) or die (mysql_error());
	$num = mysql_numrows($result);
	$i=0; while ($i < $num) { 
		$currentbid = mysql_result($result, $i, 'CurrentBid');
		$expiry = mysql_result($result, $i, 'l.ExpiryTimestamp');
		$name = mysql_result($result, $i, 'i.Name');
		$icon = mysql_result($result, $i, 'i.IconPath');
		$listing = mysql_result($result, $i, 'l.ListingId');
		echo "<tr>
			<td>$currentbid</td>
			<td><a href=\"listing.php?id=$listing\">
				<img class=\"item-icon\" src=\"$icon\">$name</a>
			</td>
			<td>$expiry</td>
		</tr>";
		$i++;
	}

	mysql_close();
?>
</table>

</body>
</html>
