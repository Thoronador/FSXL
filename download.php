<?php

include('admin/inc/class.inc.php');
include('admin/inc/config.inc.php');
include('admin/inc/functions.inc.php');

// Datanbank Verbindung aufbauen
@$db = new mysql($SQL[host], $SQL[user], $SQL[pass], $SQL[data]);
if ($db->error[error]) // Aufbau nicht möglich
{
	echo'Datenbankverbindung derzeit nicht möglich.';
}
elseif ($_GET[id])
{
	settype($_GET[id], 'integer');
	$index = @mysql_query("SELECT * FROM `$FSXL[tableset]_dl_links` WHERE `id` = '$_GET[id]'");
	if (@mysql_num_rows($index) > 0 )
	{
		$link = mysql_fetch_assoc($index);
		@mysql_query("UPDATE `$FSXL[tableset]_dl_links` SET `count` = `count` + 1 WHERE `id` = '$link[id]'");
		header('Location:' . $link[url]);
	}

	// Datenbank Verbindung schließen
	$db->close();
}

?>