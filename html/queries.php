<?php
	# some commonly used SQL queries are made available here

	function get_player_name($userid) {
		$query = "SELECT Nickname 
			FROM user
			WHERE UserId = '$userid';";
		$result = mysql_query($query) or die (mysql_error());
		(mysql_numrows($result) == 1) or die('Name query failed.');
		return mysql_result($result, 0, 'Nickname');
	}

	# sum of all a given player's transactions
	function get_player_balance($userid) {
		$query = "SELECT SUM(t.NetBalanceChange) Balance 
			FROM transaction AS t WHERE t.TransactionUser = '$userid';";
		$result = mysql_query($query) or die (mysql_error());
		$balance = mysql_result($result, 0, 'Balance');
		if (empty($balance)) {
			$balance = 0;
		}
		return $balance;
	}

	# is player a moderator with special privilages?
	function is_player_mod($userid) {
		$query = "SELECT AccountType FROM user WHERE UserId = '$userid';";
		$result = mysql_query($query) or die (mysql_error());
		if (mysql_numrows($result) == 1) {
			return (mysql_result($result, 0, 'AccountType') == 1);
		} else {
			return false;
		}
	}

	# sum of all a given player's bids
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

	# find the number of owned items of type
	function get_owned_items($userid, $itemtype) {
		$query = "SELECT DISTINCT(i.ItemId)
			FROM item AS i
			INNER JOIN item_type AS t ON i.ItemType = '$itemtype'
			INNER JOIN user ON i.OwnerUserId = '$userid';";
		$result = mysql_query($query) or die (mysql_error());
		$data = array();
		while ( $row = mysql_fetch_assoc($result) ) {
			$data[] = $row;
		}
		return $data;
	}

	# find the number of owned and unlisted items of type
	function get_available_items($userid, $itemtype) {
		$query = "SELECT DISTINCT(i.ItemId)
			FROM item AS i
			INNER JOIN item_type AS t ON i.ItemType = '$itemtype'
			INNER JOIN user ON i.OwnerUserId = '$userid'
			WHERE i.ItemId NOT IN (
				SELECT m.ItemId FROM item AS m
				INNER JOIN listing AS l ON l.ListedItemId = m.ItemId
				WHERE l.ExpiryTimestamp > CURRENT_TIMESTAMP
			);";
		$result = mysql_query($query) or die (mysql_error());
		$data = array();
		while ( $row = mysql_fetch_assoc($result) ) {
			$data[] = $row;
		}
		return $data;
	}

?>
