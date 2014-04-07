<?php
	include('session_start.php');
	$userid = $_SESSION['userid'];
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8"> 
	<title>Market 43 - Listing</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>

<?php
	include('navbar.php');
	echo create_navbar('listing.php');

	include('database_connect.php');

	if (!empty($_GET)) {
		if (isset($_GET['id'])) {
			$listingid = mysql_real_escape_string($_GET['id']);

			if (!empty($_POST)) {
#				todo: check that the user actually has available funds
#				$balancequery = "SELECT Balance";
#				$balanceresult = mysql_query($balancequery) or die (mysql_error());
#				(mysql_numrows($balanceresult) == 1) or die('Unexpected user data!');
#				$usablebalance = mysql_result($result, 0, 'UsableBalance');

				# post the bid
				$bidvalue = mysql_real_escape_string($_POST['bidvalue']);
				$update = "INSERT INTO bid (Bidder, Listing, Value) values (
					$userid, $listingid, $bidvalue)
					ON DUPLICATE KEY UPDATE
						Bidder=values(Bidder),
						Listing=values(Listing),
						Value=values(Value);";
				$updateresult = mysql_query($update) or die (mysql_error());
			}

			# find the seller and the max bid
			$query2 = "SELECT u.UserId, u.Nickname, MAX(b.Value) CurrentBid
		  	FROM listing as l
				INNER JOIN user AS u ON l.ListingUserId = u.UserId
				INNER JOIN bid AS b ON l.ListingId = b.Listing
				WHERE l.ListingId = '$listingid';";
			$result2 = mysql_query($query2) or die (mysql_error());
			(mysql_numrows($result2) == 1) or die('Unexpected seller data!');
			$seller = mysql_result($result2, 0, 'u.Nickname');
			$sellerid = mysql_result($result2, 0, 'u.UserId');
			$currentbid = mysql_result($result2, 0, 'CurrentBid');

			if ($sellerid != $userid) {
				$minbid = $currentbid + 5;
				echo "Bid on <strong>$seller</strong>'s listing?<br>";

				echo "<form action='listing.php?id=$listingid' method='post'>
					<input type='number' name='bidvalue' min='$minbid'>
					<input type='submit' value='Place Bid'>
				</form><br>";
			}

			# find a nice description of the item
			$query = "SELECT t.Name, t.IconPath, t.Description
				FROM listing as l
				INNER JOIN item as i ON l.ListedItemId = i.ItemId
				INNER JOIN item_type AS t ON i.ItemType = t.ItemTypeId
				WHERE l.ListingId = '$listingid'";
			$result = mysql_query($query) or die (mysql_error());
			(mysql_numrows($result) == 1) or die('Unexpected data!');
			$name = mysql_result($result, 0, 't.Name');
			$icon = mysql_result($result, 0, 't.IconPath');
			$description = mysql_result($result, 0, 't.Description');

			echo "<H1><img class=\"item-icon\" src=\"$icon\">$name</H1>";
			echo "<em>$description</em>";

			echo '<H2>Current Bids:</H2>
			<table>
				<colgroup>
					<col style="width: 15%" />
					<col style="width: 15%" />
				</colgroup>
				<tr>
					<th>bidder</th>
					<th>value</th>
				</tr>';
			$query3 = "SELECT u.Nickname, b.Value
				FROM bid AS b
				INNER JOIN user AS u ON b.Bidder = u.UserId
				WHERE b.Listing = '$listingid'";
			$result3 = mysql_query($query3) or die (mysql_error());
			$rows = mysql_numrows($result3);
			$i=0; while ($i < $rows) {
				$bidder = mysql_result($result3, $i, 'u.Nickname');
				$bid = mysql_result($result3, $i, 'b.Value');
				echo "<tr>
					<td>$bidder</td>
					<td>$bid</td>
					</tr>";
				$i++;
			}
			echo '</table>';
		}
	}
?>

</body>
</html>
