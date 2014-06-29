<?php

$index = @mysql_query("SELECT * FROM `$FSXL[tableset]_mod` order by `position`");

while ($arr = mysql_fetch_assoc($index))
{
	if (file_exists('mod_'.$arr[name].'/menu.inc.php') && $arr[aktiv] == 1)
	{
		include('mod_'.$arr[name].'/menu.inc.php');
		$menu = createmenu($arr[name], $FSXL[mod][$arr[name]][menu]);
		echo $menu;
	}
}

?>