<?php
	include('session_start.php');
	$userid = $_SESSION['userid'];
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8"> 
	<title>Market 43 - Search</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>

<?php
	include('navbar.php');
	echo create_navbar('search.php');
?>

<H1>Item Listings</H1>

<form action='search.php' method='post'>
	Search: <input type="text" name="itemsearch">
	<input type="submit" value="Search">
</form>

<?php
	include('database_connect.php');
	if (!empty($_POST)) {
		$search = mysql_real_escape_string($_POST['itemsearch']);

		echo '<H2>Search Results:</H2>
		<table class="item-table">
			<colgroup>
				<col style="width: 15%" />
				<col style="width: 35%" />
				<col style="width: 35%" />
				<col style="width: 15%" />
			</colgroup>
			<tr>
				<th>bid</th>
				<th>item</th>
				<th>description</th>
				<th>seller</th>
			</tr>';

		$query = "SELECT t.Name, t.IconPath, t.Description, u.Nickname, l.ListingId,
			MAX(b.Value) CurrentBid
			FROM item AS i INNER JOIN item_type AS t ON i.ItemType = t.ItemTypeId
	  	INNER JOIN listing as l ON i.ItemId = l.ListedItemId
			INNER JOIN user AS u ON l.ListingUserId = u.UserId
			LEFT OUTER JOIN bid AS b ON (b.Listing IS NULL OR l.ListingId = b.Listing)
			WHERE t.Name LIKE '%$search%' AND l.ListingUserId <> '$userid' AND l.Open <> 0
			GROUP BY l.ListingId;";
		$result = mysql_query($query) or die (mysql_error());
		$rows = mysql_numrows($result);
		$i=0; while ($i < $rows) {
			$name = mysql_result($result, $i, 't.Name');
			$icon = mysql_result($result, $i, 't.IconPath');
			$description = mysql_result($result, $i, 't.Description');
			$seller = mysql_result($result, $i, 'u.Nickname');
			$bid = mysql_result($result, $i, 'CurrentBid');
			if (empty($bid)) {
				$bid = 0;
			}
			$listing = mysql_result($result, $i, 'l.ListingId');
			echo "<tr>
				<td>$bid</td>
				<td><a href=\"listing.php?id=$listing\">
					<img class=\"item-icon\" src=\"$icon\">$name</a>
				</td>
				<td>$description</td>
				<td>$seller</td>
				</tr>";
			$i++;
		}
	echo '</form>';
	}
?>
</table>

<?php
	echo '<H2>Recent Listings:</H2>
		<table class="item-table">
			<colgroup>
				<col style="width: 15%" />
				<col style="width: 35%" />
				<col style="width: 35%" />
				<col style="width: 15%" />
			</colgroup>
			<tr>
				<th>bid</th>
				<th>item</th>
				<th>description</th>
				<th>seller</th>
			</tr>';
		$query = "SELECT t.Name, t.IconPath, t.Description, u.Nickname, l.ListingId,
			MAX(b.Value) CurrentBid
			FROM item AS i INNER JOIN item_type AS t ON i.ItemType = t.ItemTypeId
	  	INNER JOIN listing as l ON i.ItemId = l.ListedItemId
			INNER JOIN user AS u ON l.ListingUserId = u.UserId
			LEFT OUTER JOIN bid AS b ON (b.Listing IS NULL OR l.ListingId = b.Listing)
			WHERE l.ListingUserId <> '$userid' AND l.Open <> 0
			GROUP BY l.ListingId
			ORDER BY l.ExpiryTimestamp
			LIMIT 10;";
		$result = mysql_query($query) or die (mysql_error());
		$rows = mysql_numrows($result);
		$i=0; while ($i < $rows) {
			$name = mysql_result($result, $i, 't.Name');
			$icon = mysql_result($result, $i, 't.IconPath');
			$description = mysql_result($result, $i, 't.Description');
			$seller = mysql_result($result, $i, 'u.Nickname');
			$bid = mysql_result($result, $i, 'CurrentBid');
			if (empty($bid)) {
				$bid = 0;
			}
			$listing = mysql_result($result, $i, 'l.ListingId');
			echo "<tr>
				<td>$bid</td>
				<td><a href=\"listing.php?id=$listing\">
					<img class=\"item-icon\" src=\"$icon\">$name</a>
				</td>
				<td>$description</td>
				<td>$seller</td>
				</tr>";
			$i++;
		}
?>
</table>

</body>
</html>
