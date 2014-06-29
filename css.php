<?php
error_reporting(0);
include('admin/inc/class.inc.php');
include('admin/inc/config.inc.php');
include('admin/inc/functions.inc.php');

// Datanbank Verbindung aufbauen
@$db = new mysql($SQL['host'], $SQL['user'], $SQL['pass'], $SQL['data']);
if ($db->error['error']) // Aufbau nicht möglich
{
	echo'Datenbankverbindung derzeit nicht möglich.';
}
elseif ($_GET['style'])
{
	settype($_GET['style'], 'integer');
	$FSXL['style'] = $_GET['style'];
	$css_tpl = new template('css');
	
	header("Content-type: text/css");
	echo $css_tpl->code;

	// Datenbank Verbindung schließen
	$db->close();
}

?>