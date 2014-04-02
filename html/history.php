<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8"> 
	<title>Market 43 - History</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>

<?php
	include('navbar.php');
	echo create_navbar('history.php');
?>

<H1>Transaction History</H1>
<table class="transaction-table">
	<colgroup>
		<col style="width: 25%" />
		<col style="width: 25%" />
		<col style="width: 25%" />
		<col style="width: 25%" />
	</colgroup>
	<tr>
		<th>date</th>
		<th>time</th>
		<th>value</th>
		<th>type</th>
	</tr>

<?php 
	include('database_connect.php');
	$query="SELECT t.NetBalanceChange, t.TransactionType FROM transaction as t;";
	$result=mysql_query($query) or die (mysql_error());
	$num=mysql_numrows($result);
	$i=0; while ($i < $num) { 
//	opps. these don't exist
//	$date=mysql_result($result, $i, "t.date");
//	$time=mysql_result($result, $i, "t.time");
	$value=mysql_result($result, $i, 't.NetBalanceChange');
	$type=mysql_result($result, $i, 't.TransactionType');
	if ($type == 0) {
		$typename = 'trade';
	} else if ($type == 1) {
		$typename = 'credit';
	} else if ($type == 2) {
		$typename = 'craft';
	}

	echo "<tr class=\"transaction-$typename\">
		<th></th>
		<td></td>
		<td>$value</td>
		<td>$typename</td>";
	$i++;}

	mysql_close();
 ?>

</table>
</body>
</html>
