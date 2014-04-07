<?php
	include('session_start.php');
	$userid = $_SESSION['userid'];
?>

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
		<col style="width: 33%" />
		<col style="width: 33%" />
		<col style="width: 33%" />
	</colgroup>
	<tr>
		<th>value</th>
		<th>type</th>
		<th>transaction time</th>
	</tr>

<?php 
	include('database_connect.php');
	$query="SELECT t.NetBalanceChange, t.TransactionType, t.TransactionTimestamp
		FROM transaction as t WHERE t.TransactionUser = '$userid';";
	$result=mysql_query($query) or die (mysql_error());
	$num=mysql_numrows($result);
	$i=0; while ($i < $num) { 
	$value=mysql_result($result, $i, 't.NetBalanceChange');
	$type=mysql_result($result, $i, 't.TransactionType');
	$timestamp=mysql_result($result, $i, 't.TransactionTimestamp');
	if ($type == 0) {
		$typename = 'trade';
	} else if ($type == 1) {
		$typename = 'credit';
	} else if ($type == 2) {
		$typename = 'craft';
	}

	echo "<tr class=\"transaction-$typename\">
		<td>$value</td>
		<td>$typename</td>
		<td>$timestamp</td>";
	$i++;}
 ?>

</table>
</body>
</html>
