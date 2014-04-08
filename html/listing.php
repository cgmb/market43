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
	include('queries.php');
	$errortext='';

	if (!empty($_GET)) {
		if (isset($_GET['id'])) {
			$listingid = mysql_real_escape_string($_GET['id']);

			if (!empty($_POST)) {
				$bidvalue = mysql_real_escape_string($_POST['bidvalue']);
				$balance = get_player_balance($userid);

        # validate!
				# check the player has enough funds overall for the bid
        if ($bidvalue > $balance) {
          $errortext = 'That bid is greater than your balance!';
					goto end_of_post; # there's gotta be a better way
        }

				# check the player has enough available funds for the bid
				$availablefunds = $balance - get_player_liabilities($userid);
        if ($bidvalue > $availablefunds) {
          $errortext = 'Not enough available funds!<br>They\'re tied up in other bids!';
					goto end_of_post;
        }

				# check the bid exceeds the minimum bid
				$query = "SELECT ListingUserId, MinimumBid
					FROM listing WHERE ListingId = '$listingid'";
				$result = mysql_query($query) or die(mysql_error());
				(mysql_numrows($result) >= 1) or die('Missing listing data!');
				$minbid = mysql_result($result, 0, 'MinimumBid');
				$listinguser = mysql_result($result, 0, 'ListingUserId');
        if ($bidvalue < $minbid) {
          $errortext = 'Bid is less than minimum bid!';
					goto end_of_post;
        }

				# check that the bidder doesn't own the listing
				# no take-backs!
        if ($userid == $listinguser) {
          $errortext = 'You can\'t bid on your own auction.<br>No takebacks!';
					goto end_of_post;
        }

				# check the bid exceeds the current bids
				$query = "SELECT MAX(Value) CurrentBid
					FROM bid WHERE Listing = '$listingid'";
				$result = mysql_query($query) or die(mysql_error());
				$curbid = mysql_result($result, 0, 'CurrentBid');
				if (empty($curbid)) {
					$curbid = 0;
				}
        if ($bidvalue <= $curbid) {
          $errortext = 'Bid is lower than existing bids!';
					goto end_of_post;
        }

				# post the bid
				$update = "INSERT INTO bid (Bidder, Listing, Value) values (
					$userid, $listingid, $bidvalue)
					ON DUPLICATE KEY UPDATE
						Bidder=values(Bidder),
						Listing=values(Listing),
						Value=values(Value);";
				$updateresult = mysql_query($update) or die (mysql_error());

				end_of_post:
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
				$minbid = $currentbid + 1;
				echo "<div class='error-text'>$errortext</div>";
				echo "Bid on <strong>$seller</strong>'s listing?<br>";

				echo "<form action='listing.php?id=$listingid' method='post'>
					<input type='number' name='bidvalue' min='$minbid'>
					<input type='submit' value='Place Bid'>
				</form><br>";
			}

			# find a nice description of the item
			$query = "SELECT t.Name, t.IconPath, t.Description, l.MinimumBid
				FROM listing as l
				INNER JOIN item as i ON l.ListedItemId = i.ItemId
				INNER JOIN item_type AS t ON i.ItemType = t.ItemTypeId
				WHERE l.ListingId = '$listingid'";
			$result = mysql_query($query) or die (mysql_error());
			(mysql_numrows($result) == 1) or die('Unexpected data!');
			$name = mysql_result($result, 0, 't.Name');
			$icon = mysql_result($result, 0, 't.IconPath');
			$description = mysql_result($result, 0, 't.Description');
			$minbid = mysql_result($result, 0, 'l.MinimumBid');

			echo "<H1><img class=\"item-icon\" src=\"$icon\">$name</H1>";
			echo "<em>$description</em>";
			echo "<br>Minimum bid: $minbid";

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
					<td><center>$bidder</center></td>
					<td><center>$bid</center></td>
					</tr>";
				$i++;
			}
			echo '</table>';
		}
	}
?>

</body>
</html>
