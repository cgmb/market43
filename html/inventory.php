<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8"> 
	<title>Market 43 - Inventory</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>

<?php
	include('navbar.php');
	echo create_navbar('inventory.php');
?>

<H1>Inventory</H1>
<table class="item-table">
	<colgroup>
		<col style="width: 15%" />
		<col style="width: 35%" />
		<col style="width: 35%" />
		<col style="width: 15%" />
	</colgroup>
	<tr>
		<th>count</th>
		<th>item</th>
		<th>description</th>
		<th>craftables</th>
	</tr>

<?php 
	include('database_connect.php');
	session_start() or die('Failed to create session');
	$userid=$_SESSION['userid'];
	!empty($userid) or die('Session lacks userid!');
	$query="SELECT COUNT(DISTINCT(i.ItemId)) Count, t.ItemTypeId, t.Name, t.IconPath, t.Rarity, t.Description
		FROM item AS i
		INNER JOIN item_type AS t ON i.ItemType = t.ItemTypeId
		INNER JOIN user ON i.OwnerUserId = '$userid';";

	$result=mysql_query($query) or die (mysql_error());
	$num=mysql_numrows($result);
	$i=0; while ($i < $num) { 
		$count=mysql_result($result, $i, "Count");
		$typeid=mysql_result($result, $i, "t.ItemTypeId");
		$name=mysql_result($result, $i, "t.Name");
		$icon=mysql_result($result, $i, "t.IconPath");
		$description=mysql_result($result, $i, "t.Description");
		echo "<tr>
			<td>$count</td>
			<td><a href=\"item.php?typeid=$typeid\"><img class=\"item-icon\" src=\"$icon\">$name</a></td>
			<td>$description</td>
			<td></td>
		</tr>";
		$i++;
	}
 ?>

</table>
</body>
</html>
