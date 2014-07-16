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
	// SQL Absichern
	mysql_secure_strings();
	
	// Wenn ID erhalten
	if ($_GET[id])
	{		
		settype($_GET[id], 'integer');

		// Konfiguration laden
		$FSXL[config] = createConfigArray();
		$FSXL[time] = time();
		
		// Wettbewerb auslesen
		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_contests` WHERE `id` = '$_GET[id]' AND `startdate` <= '$FSXL[time]' AND `type` = 2");
		if (mysql_num_rows($index) > 0)
		{
			$contest = mysql_fetch_assoc($index);
			echo '
				<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
				<html>
				<head>
					<title>'.$contest[title].'</title>
					<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
					<style>
						body {
							font-family:Arial;
							font-size:10pt;
						}
					</style>
				</head>
				<body>
					<h1>'.$contest[title].'</h1>
					'.fscode($contest[text]).'
					<p/><hr '.($_GET[float]==true?'':'style="page-break-after:always"').'/>
			';
			
			$index = mysql_query("SELECT * FROM `$FSXL[tableset]_contest_entries` WHERE `contest` = '$contest[id]' AND `active` = 1 ORDER BY `date`");
			while ($entry = mysql_fetch_assoc($index))
			{
				$index2 = mysql_query("SELECT `name` FROM `$FSXL[tableset]_user` WHERE `id` = '$entry[user]'");
				$name = mysql_result($index2, 0, 'name');
				echo '
					<b>Einsendug von: '.$name.'</b> ('.date("d.m.Y", $entry[date]).')
					<dir>
						<b>'.$entry[title].'</b><br/>
						'.fscode($entry[text]).'
					</dir>
					<hr '.($_GET[float]==true?'':'style="page-break-after:always"').'/>
				';
			}
			
			echo '
				</body>
				</html>
			';
		}
	}	

	// Datenbank Verbindung schließen
	$db->close();
}

?>