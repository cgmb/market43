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

<H1>Item listings</H1>

<form action='search.php' method='post'>
	Search: <input type="text" name="itemname">
	<input type="submit" value="Search">
</form>

<H2>Recently posted</H2>
<table>
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
	</tr>
<?php
	$search = "Money";
#	if (isset($search)) {
		include('database_connect.php');
		session_start() or die('Failed to create session');
		$userid=$_SESSION['userid'];
		!empty($userid) or die('Session lacks userid!');
		$query = "SELECT t.Name, t.IconPath, t.Description, u.Nickname, MAX(b.Value) CurrentBid
			FROM item AS i INNER JOIN item_type AS t ON i.ItemType = t.ItemTypeId
	  	INNER JOIN listing as l ON i.ItemId = l.ListedItemId
			INNER JOIN user AS u ON l.ListingUserId = u.UserId
			INNER JOIN bid AS b ON l.ListingId = b.Listing
			WHERE t.Name LIKE '%$search%' AND l.ListingUserId <> '$userid'
			GROUP BY l.ListingId;";
		$result = mysql_query($query) or die (mysql_error());
		$rows = mysql_numrows($result);
		$i=0; while ($i < $rows) {
			$name = mysql_result($result, $i, 't.Name');
			$icon = mysql_result($result, $i, 't.IconPath');
			$description = mysql_result($result, $i, 't.Description');
			$seller = mysql_result($result, $i, 'u.Nickname');
			$bid = mysql_result($result, $i, 'CurrentBid');
			echo "<tr>
			<td>$bid</td>
			<td><img class=\"item-icon\" src=\"$icon\">$name</td>
			<td>$description</td>
			<td>$seller</td>
			</tr>";
			$i++;
		}
#	}
?>
</table>

</body>
</html>
