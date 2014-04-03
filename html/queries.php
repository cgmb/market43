<?php

	function get_player_balance($userid) {
		$query = "SELECT SUM(t.NetBalanceChange) balance " .
			"FROM transaction AS t WHERE t.TransactionUser = $userid;";
		$result = mysql_query($query) or die (mysql_error());
		(mysql_numrows($result) == 1) or die('Balance query failed.');
		return mysql_result($result, $i, 'balance');
	}

?>
