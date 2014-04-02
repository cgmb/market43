<?php

function create_navbar($current_page) {
	$result = "<img class=\"banner\" src=\"title.png\">\n";

	$pages = [
		'Dashboard' => 'dashboard.php',
		'Search' => 'search.php',
		'Inventory' => 'inventory.php',
		'History' => 'history.php',
		'Credits' => 'credits.php',
		'Logout' => 'index.php',
	];

	$result .= "<nav>\n\t<ul>\n";

	foreach ($pages as $key => $value) {
		if ($current_page == $value) {
			$result .= "\t\t<li class=\"current-item\">$key</li>\n";
		} else {
			$result .= "\t\t<li><a href=\"$value\">$key</a></li>\n";
		}
	}

	$result .= "\t</ul>\n</nav>";
	return $result;
}
?>
