<?php

@include("../inc/config.inc.php");
@include("../inc/functions.inc.php");
@include("../inc/class.inc.php");

// Session starten und erhalten
@ini_set ("session.use_trans_sid","1");
@ini_set ("magic_quotes_gpc","off");
@session_start();

// Datanbank Verbindung aufbauen
@$db = new mysql($SQL[host], $SQL[user], $SQL[pass], $SQL[data]);
if (!$db->error[error])
{
	if ($_POST[style])
	{
		$xml = '<?xml version="1.0" encoding="UTF-8" ?>'."\r\n";
		$xml .= "<style>\r\n";

		settype($_POST[style], 'integer');

		// Style
		$index = mysql_query("SELECT * FROM $FSXL[tableset]_styles WHERE `id` = $_POST[style]");
		$style = mysql_fetch_assoc($index);
		$xml .= "\t<name>".$style[name]."</name>\r\n";

		// Template
		$index = mysql_query("SELECT * FROM $FSXL[tableset]_templates WHERE `styleid` = $_POST[style]");
		while ($template = mysql_fetch_assoc($index))
		{
			$xml .= "\t<item>\r\n";
			$xml .= "\t\t<shortcut>".$template[shortcut]."</shortcut>\r\n";
			$xml .= "\t\t<name>".$template[name]."</name>\r\n";
			$xml .= "\t\t<mod>".$template[mod]."</mod>\r\n";
			$template[code] = htmlspecialchars($template[code]);
			$xml .= "\t\t<code>".$template[code]."</code>\r\n";
			$xml .= "\t</item>\r\n";
		}

		$xml .= "</style>";

		// Ausgabe
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Type: application/force-download");
		header("Content-Description: File Transfer");
		header("Content-Disposition: attachment; filename=".$style[name].".xml");
		header("Content-Transfer-Encoding: binary");
		echo utf8_encode($xml);
	}
	else
	{
		echo $_SESSION[user]->access[main][style];
	}
}

// Datenbank Verbindung schließen
$db->close();

?>