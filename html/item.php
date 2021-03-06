<?php
	include('session_start.php');
	$userid = $_SESSION['userid'];
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8"> 
	<title>Market 43 - Item</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>

<?php
	include('navbar.php');
	echo create_navbar('item.php');

	include('database_connect.php');
	include('queries.php');

	if (!empty($_GET)) {
		if (isset($_GET['typeid'])) {
			$itemtype = mysql_real_escape_string($_GET['typeid']);

			# handle request to post listing
			$errortext = '';
			if (!empty($_POST)) {
				if (!empty($_POST['minbid']) && $_POST['minbid'] > 0) {
					$minbid = mysql_real_escape_string($_POST['minbid']);

					# pick a random available item to be the subject of the auction
					$availableresult = get_available_items($userid, $itemtype);
					$available = count($availableresult);
					if ($available > 0) {
						# php is such a hacky language :\
						# reset returns the first element of the array
						# first reset for row, second for column
						$itemid = reset(reset($availableresult));
						$query = "INSERT INTO listing (
							MinimumBid, ExpiryTimestamp, ListedItemId, ListingUserId) values(
							'$minbid', NOW() + INTERVAL 2 HOUR, '$itemid', '$userid');";
						if (!mysql_query($query)) {
							$errortext = 'Failed to post listing.';
						}
					} else {
						$errortext = 'No available items to list!';
					}
				} else {
					$errortext = 'All fields are required.';
				}
			}

			# find the number of owned items of type
			$result = get_owned_items($userid, $itemtype);
			$owned = count($result);

			$availableresult = get_available_items($userid, $itemtype);
			$available = count($availableresult);

			# find a nice description of the item
			$query = "SELECT t.Name, t.IconPath, t.Description
				FROM item as i 
				INNER JOIN item_type AS t ON i.ItemType = t.ItemTypeId
				WHERE '$itemtype' = i.ItemType";
			$result = mysql_query($query) or die (mysql_error());
			(mysql_numrows($result) >= 1) or die('Unexpected data!');
			$name = mysql_result($result, 0, 't.Name');
			$icon = mysql_result($result, 0, 't.IconPath');
			$description = mysql_result($result, 0, 't.Description');

			echo "<H1><img class=\"item-icon\" src=\"$icon\">$name</H1>";
			echo "<em>$description</em>";
			echo "<br><br>Number owned: $owned<br>";
			echo "Number available: $available<br>";

			# display the UI to post a listing
			if ($available > 0) {
				echo '<H2>Post Listing</H2>';
				echo "<div class='error-text'>$errortext</div>";
				echo "<form action='item.php?typeid=$itemtype' method='post'>
					Minimum Bid: <input type='number' name='minbid'>
					<br><br>
					<input type='submit' value='Sell Item'>
				</form>";
			} else if (!empty($errortext)) {
				echo "<div class='error-text'>$errortext</div>";
			}
		}
	}
?>

<H2>Related Items</H2>
<table class="item-table">
	<colgroup>
<!--		<col style="width: 15%" /> -->
		<col style="width: 35%" />
		<col style="width: 35%" />
	</colgroup>
	<tr>
<!--		<th>craft</th> -->
		<th>item</th>
		<th>description</th>
	</tr>

<?php 
  $query = "SELECT t.Name, t.IconPath, t.Description
		FROM recipe_input AS i
		INNER JOIN recipe_output AS o ON i.Recipe = o.Recipe
		INNER JOIN item_type AS t ON t.ItemTypeId = o.OutputItemType
		WHERE i.InputItemType = '$itemtype'";

	$result = mysql_query($query) or die (mysql_error());
	$rows = mysql_numrows($result);
	$i=0; while ($i < $rows) { 
		$name=mysql_result($result, $i, "t.Name");
		$icon=mysql_result($result, $i, "t.IconPath");
		$description=mysql_result($result, $i, "t.Description");
		echo "<form action='item?typeid=$itemtype' method='post'>
			<tr>" .
#			<td><input type='button' value='craft' /></td>
			"<td><img class=\"item-icon\" src=\"$icon\">$name</td>
			<td>$description</td>
			<td></td>
		</tr>
		</form>";
		$i++;
	}
 ?>

</table>
</body>
</html>
