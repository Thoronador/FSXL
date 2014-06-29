<?php

include('../admin/inc/class.inc.php');
include('../admin/inc/config.inc.php');
include('../admin/inc/functions.inc.php');
include('risendb_functions.php');

// Datanbank Verbindung aufbauen
@$db = new mysql($SQL[host], $SQL[user], $SQL[pass], $SQL[data]);
if (!$db->error[error])
{
	// Item ausgeben
	if ($_POST[id]) {
		settype($_POST[id], 'integer');
		$item = genItem($_POST[id]);
		echo utf8_encode($item[html]);
	}
	
	// Datenbank Verbindung schließen
	$db->close();
}

?>