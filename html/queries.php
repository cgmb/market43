<?php

	function get_player_name($userid) {
		$query = "SELECT Nickname 
			FROM user
			WHERE UserId = '$userid';";
		$result = mysql_query($query) or die (mysql_error());
		(mysql_numrows($result) == 1) or die('Name query failed.');
		return mysql_result($result, 0, 'Nickname');
	}

	function get_player_balance($userid) {
		$query = "SELECT SUM(t.NetBalanceChange) Balance 
			FROM transaction AS t WHERE t.TransactionUser = '$userid';";
		$result = mysql_query($query) or die (mysql_error());
		(mysql_numrows($result) == 1) or die('Balance query failed.');
		return mysql_result($result, 0, 'Balance');
	}

	function get_player_liabilities($userid) {
		$query = "SELECT SUM(b.Value) AS Liabilities
			FROM bid AS b, listing AS l
			WHERE b.Bidder = '$userid'
			AND l.ExpiryTimestamp > CURRENT_TIMESTAMP
			AND b.Listing = l.ListingId";
		$result = mysql_query($query) or die (mysql_error());
		$liabilities = mysql_result($result, 0, 'Liabilities');
		if (empty($liabilities)) {
			$liabilities = 0;
		}
		return $liabilities;
	}

?>
