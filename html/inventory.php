<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8"> 
	<title>Market 43 - Inventory</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>

<img class="banner" src="title.png">

<nav>
	<ul>
		<li><a href="dashboard.html">Dashboard</li>
		<li><a href="search.html">Search</a></li>
		<li class="current-item">Inventory</li>
		<li><a href="history.html">History</a></li>
		<li><a href="index.html">Logout</a></li>	
	</ul>
</nav>


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
	$username="root";
	$password="your_password";
	$database="market43";

	mysql_connect('localhost', $username, $password);
	@mysql_select_db($database) or die(mysql_error());
	$query="SELECT DISTINCT(i.ItemId), t.Name, t.IconPath, t.Rarity, t.Description FROM item AS i INNER JOIN item_type AS t ON i.ItemType = t.ItemTypeId;";
	$result=mysql_query($query) or die (mysql_error());
	$num=mysql_numrows($result);
	$i=0; while ($i < $num) { 
	$field1=mysql_result($result, $i, "t.Name");
	$field2=mysql_result($result, $i, "t.IconPath");
	$field3=mysql_result($result, $i, "t.Description");
	echo "<tr>
		<th></th>
		<td><img class=\"item-icon\" src=\"$field2\">$field1</td>
		<td>$field3</td>
		<td></td>";
	$i++;}

	mysql_close();
 ?>

</table>
</body>
</html>
