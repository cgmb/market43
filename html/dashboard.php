<?php
	include('session_start.php');
	$userid = $_SESSION['userid'];
?>

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

	include('database_connect.php');
	include('queries.php');

	if (is_player_mod($userid)) {
		# handle submitted name changes
		if (!empty($_POST)) {
			$newname = mysql_real_escape_string($_POST['newname']);
			$playerid = mysql_real_escape_string($_POST['playerid']);
			$query = "UPDATE user SET NickName='$newname'
				WHERE UserId = '$playerid';"; 
			if (!mysql_query($query)) {
				echo '<br><div class="error-text">Failed to update name.</div>';
			}
		}
	}
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
		<th>time left</th>
	</tr>
<?php
	$query = "SELECT DISTINCT l.ListingId, timediff(l.ExpiryTimestamp, CURRENT_TIMESTAMP) TimeRemaining, i.Name, i.IconPath, b.Value
	FROM listing AS l, item_type AS i, item AS x, bid as b
	WHERE i.ItemTypeId = x.ItemType AND l.ListingId = b.Listing AND b.Bidder = '$userid'
	AND x.ItemId = l.ListedItemId AND l.Open <> 0
	ORDER BY l.ExpiryTimestamp;";

	$result = mysql_query($query) or die (mysql_error());
	$num = mysql_numrows($result);
	$i=0; while ($i < $num) { 
		$expiry = mysql_result($result, $i, 'TimeRemaining');
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
		<th>time left</th>
	</tr>
<?php
	$query = "SELECT MAX(b.Value) CurrentBid, l.ListingId, timediff(l.ExpiryTimestamp, CURRENT_TIMESTAMP) TimeRemaining, i.Name, i.IconPath
	FROM listing AS l, item_type AS i, item AS x, bid as b
	WHERE i.ItemTypeId = x.ItemType AND l.ListingId = b.Listing
	AND x.ItemId = l.ListedItemId
	AND l.ListingUserId = '$userid' AND l.Open <> 0
	GROUP BY l.ListingId
	ORDER BY CurrentBid DESC;";

	$result = mysql_query($query) or die (mysql_error());
	$num = mysql_numrows($result);
	$i=0; while ($i < $num) { 
		$currentbid = mysql_result($result, $i, 'CurrentBid');
		$expiry = mysql_result($result, $i, 'TimeRemaining');
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
?>
<?php
	$query = "SELECT l.ListingId, timediff(l.ExpiryTimestamp, CURRENT_TIMESTAMP) TimeRemaining, i.Name, i.IconPath
	FROM listing AS l, item_type AS i, item AS x
	WHERE i.ItemTypeId = x.ItemType AND l.ListingUserId = '$userid' 
	AND x.ItemId = l.ListedItemId
	AND l.ListingId NOT IN (SELECT Listing FROM bid) AND l.Open <> 0
	GROUP BY l.ListingId
	ORDER BY l.ExpiryTimestamp;";

	$result = mysql_query($query) or die (mysql_error());
	$num = mysql_numrows($result);
	$i=0; while ($i < $num) { 
		$expiry = mysql_result($result, $i, 'TimeRemaining');
		$name = mysql_result($result, $i, 'i.Name');
		$icon = mysql_result($result, $i, 'i.IconPath');
		$listing = mysql_result($result, $i, 'l.ListingId');
		echo "<tr>
			<td>N/A</td>
			<td><a href=\"listing.php?id=$listing\">
				<img class=\"item-icon\" src=\"$icon\">$name</a>
			</td>
			<td>$expiry</td>
		</tr>";
		$i++;
	}
?>
</table>

<?php
	if (is_player_mod($userid)) {
		# create interface for changing names
		echo '<H2>Name Moderation</H2>';
		echo '<table class="item-table">
			<colgroup>
				<col style="width: 35%" />
				<col style="width: 50%" />
			</colgroup>
			<tr>
				<th>current name</th>
				<th>new name</th>
				<th></th>
			</tr>';

		$query = "SELECT UserId, Nickname FROM user ORDER BY Nickname;";
		$result = mysql_query($query) or die (mysql_error());
		$num = mysql_numrows($result);
		$i=0; while ($i < $num) { 
			$playername = mysql_result($result, $i, 'Nickname');
			$playerid = mysql_result($result, $i, 'UserId');
			echo "<tr>
				<form action='dashboard.php' method='post'>
					<td>$playername</td>
					<td>
						<input type='text' name='newname' />
						<input type='hidden' name='playerid' value='$playerid' />
						<input type='submit' value='Submit'>
					</td>
				</form>
			</tr>";
			$i++;
		}
	}
?>

</body>
</html>
