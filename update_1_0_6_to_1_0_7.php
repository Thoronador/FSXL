<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>Frogsystem XL Update - 1.0.6 zu 1.0.7</title>
	<link rel="stylesheet" href="admin/green.css" type="text/css" media="screen">
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
</head>
<body>
	<div id="main">
		<div id="header"></div>
		<div id="topmenu"></div>
		<ul>

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
else
{
	// Konfiguration laden
	$FSXL[config] = createConfigArray();
	
	// Version Überprüfen
	if ($FSXL[config][version] == "1.0.6")
	{
		// Updates ausführen
		
		echo'<p/><li><b>Galerie Tabelle wird aktualisiert:</b></li>';
		$chk = mysql_query("ALTER TABLE `$FSXL[tableset]_galleries` ADD `hidden` TINYINT NOT NULL DEFAULT '0'");
		if ($chk) {
			echo '<span style="color:#00AA00;">-> Galerie Tabelle wurde aktualisiert</span>';
		} else {
			echo '<span style="color:#FF0000;">-> Galerie Tabelle konnte nicht aktualisiert werden!</span>';
		}

		echo'<p/><li><b>Zonen Tabelle wird aktualisiert:</b></li>';
		$chk = mysql_query("ALTER TABLE `$FSXL[tableset]_zones` ADD `single` TINYINT NOT NULL DEFAULT '0'");
		$chk = mysql_query("ALTER TABLE `$FSXL[tableset]_zones` ADD `headlines` TINYINT NOT NULL DEFAULT '0'");
		if ($chk) {
			echo '<span style="color:#00AA00;">-> Zonen Tabelle wurde aktualisiert</span>';
		} else {
			echo '<span style="color:#FF0000;">-> Zonen Tabelle konnte nicht aktualisiert werden!</span>';
		}
	
		echo'<p/><li><b>Headlines für Home Zone werden aktiviert:</b></li>';
		$chk = mysql_query("UPDATE `$FSXL[tableset]_zones` SET `headlines` = 1 WHERE `id` = ".$FSXL[config][defaultzone]);
		if ($chk) {
			echo '<span style="color:#00AA00;">-> Headlines für Home Zone wurden aktiviert</span>';
		} else {
			echo '<span style="color:#FF0000;">-> Headlines für Home Zone konnten nicht aktiviert werden!</span>';
		}

		echo'<p/><li><b>Versionsnummer wird aktualisiert</b></li>';
		$chk = mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = '1.0.7' WHERE `name` = 'version';");
		if ($chk) {
			echo '<span style="color:#00AA00;">-> Versionsnummer wurde aktualisiert</span>';
		} else {
			echo '<span style="color:#FF0000;">-> Versionsnummer konnte nicht aktualisiert werden!</span>';
		}

		echo'<p/><li><b>Konfiguration wird erweitert</b></li>';
		$chk = mysql_query("INSERT INTO `$FSXL[tableset]_config` (`name` ,`value`) VALUES ('siteurl', '')");
		if ($chk) {
			echo '<span style="color:#00AA00;">-> Konfiguration wurde erweitert</span>';
		} else {
			echo '<span style="color:#FF0000;">-> Konfiguration konnte nicht erweitert werden!</span>';
		}

		echo'<p/><li><b>Das Datenbankupdate ist beendet. Bitte lösche diese Datei hier.</b></li>';
	}
	
	// Falsche Version
	else
	{
		echo'<p/><li><b>Das Update kann nicht durchgeführt werden, da die Frogsystem Version nicht 1.0.6 entspricht.</b></li>';
	}

	// Datenbank Verbindung schließen
	$db->close();
}

?>

	</ul>
	</div>
</body>
</html>