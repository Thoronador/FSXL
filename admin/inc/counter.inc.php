<?php

$countervars = array();
$FSXL[time] = time();
$countervars[year] = date("Y");		// Jahr
$countervars[month] = date("m");	// Monat
$countervars[day] = date("d");		// Tag
$countervars[Yyear] = date("Y", $FSXL[time]-86400);		// Jahr von gestern
$countervars[Ymonth] = date("m", $FSXL[time]-86400);	// Monat von gestern
$countervars[Yday] = date("d", $FSXL[time]-86400);		// Tag von gestern
$countervars[cachetime] = 300;		// Cache Zeit in Sekunden
$date = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
$Ydate = mktime(0, 0, 0, date("m", $FSXL[time]-86400), date("d", $FSXL[time]-86400), date("Y", $FSXL[time]-86400));


$_SERVER[HTTP_USER_AGENT] = mysql_real_escape_string($_SERVER[HTTP_USER_AGENT]);

// Suchmaschinen Bot ?
if (preg_match("/(bot|yahoo|crawler|slurp|scooter|partner|archiver|spider)/is", $_SERVER[HTTP_USER_AGENT]))
{
	@mysql_query("INSERT INTO `$FSXL[tableset]_counter_bots` (`date`, `startdate`, `ip`, `agent`) 
			VALUES ('$date', '$FSXL[time]', '$_SERVER[REMOTE_ADDR]', '$_SERVER[HTTP_USER_AGENT]')");
}

// Kein Bot
else
{
	if (@filemtime('cache/counter.cch') < $FSXL[time]-$countervars[cachetime]) // Neue Daten schreiben
	{
		//Falls nötig, neuen Tag anlegen
		$index = @mysql_query("INSERT INTO `$FSXL[tableset]_counter_stat` (`year`, `month`, `day`, `visits`, `hits`) VALUES ('$countervars[year]', '$countervars[month]', '$countervars[day]', 0, 0)");

		// Vorherigen Tag aktualisieren
		if ($index)
		{
			$index = mysql_query("SELECT COUNT(`date`) AS `visits`, SUM(`views`) AS `hits` FROM `$FSXL[tableset]_counter_user` WHERE `date` = '$Ydate'");
			$counter = mysql_fetch_assoc($index);

			//Hits zählen
			mysql_query("UPDATE `$FSXL[tableset]_counter_stat` set `hits` = '$counter[hits]' WHERE `year` = '$countervars[Yyear]' AND `month` = '$countervars[Ymonth]' AND `day` = '$countervars[Yday]'");

			// Visits zählen
		        mysql_query("UPDATE `$FSXL[tableset]_counter_stat` SET `visits` = '$counter[visits]' WHERE `year` = '$countervars[Yyear]' AND `month` = '$countervars[Ymonth]' AND `day` = '$countervars[Yday]'");
		}

		$index = mysql_query("SELECT COUNT(`date`) AS `visits`, SUM(`views`) AS `hits` FROM `$FSXL[tableset]_counter_user` WHERE `date` = '$date'");
		$counter = mysql_fetch_assoc($index);

		//Hits zählen
		mysql_query("UPDATE `$FSXL[tableset]_counter_stat` set `hits` = '$counter[hits]' WHERE `year` = '$countervars[year]' AND `month` = '$countervars[month]' AND `day` = '$countervars[day]'");

		// Visits zählen
	        mysql_query("UPDATE `$FSXL[tableset]_counter_stat` SET `visits` = '$counter[visits]' WHERE `year` = '$countervars[year]' AND `month` = '$countervars[month]' AND `day` = '$countervars[day]'");
	}

	// Daten Updaten
	$index = @mysql_query("UPDATE `$FSXL[tableset]_counter_user` SET `enddate` = '$FSXL[time]', `views` = `views`+1 WHERE `date` = '$date' AND `ip` = '$_SERVER[REMOTE_ADDR]'");
	if(mysql_affected_rows() == 0)
	{
		// User Stat hinzufügen
		$referer = mysql_real_escape_string(preg_replace("/[0-9a-fA-F]{32}/is", '', $_SERVER[HTTP_REFERER]));
		$index = @mysql_query("INSERT INTO `$FSXL[tableset]_counter_user` (`date`, `startdate`, `enddate`, `views`, `referer`, `ip`, `agent`, `lang`)
					VALUES('$date', '$FSXL[time]', '$FSXL[time]', 1, '$referer', '$_SERVER[REMOTE_ADDR]', '$_SERVER[HTTP_USER_AGENT]', '$_SERVER[HTTP_ACCEPT_LANGUAGE]')");
	}

	// Einzelstatistik
	settype($_GET[id], 'integer');

	// Artikel
	if($_GET[section] == 'article' && $_GET[id])
	{
		$index = @mysql_query("SELECT `id` FROM `$FSXL[tableset]_article` WHERE `id` = '$_GET[id]'");
		if (mysql_num_rows($index) > 0)
		{
			@mysql_query("INSERT INTO `$FSXL[tableset]_counter_article` (`year`, `month`, `day`, `id`, `hits`) VALUES ('$countervars[year]', '$countervars[month]', '$countervars[day]', '$_GET[id]', 0)");
			@mysql_query("UPDATE `$FSXL[tableset]_counter_article` set `hits` = `hits`+ 1 WHERE `year` = '$countervars[year]' AND `month` = '$countervars[month]' AND `day` = '$countervars[day]' AND `id` = '$_GET[id]'");
		}
	}
	// News
	if($_GET[section] == 'newsdetail' && $_GET[id])
	{
		$index = @mysql_query("SELECT `id` FROM `$FSXL[tableset]_news` WHERE `id` = '$_GET[id]'");
		if (mysql_num_rows($index) > 0)
		{
			@mysql_query("INSERT INTO `$FSXL[tableset]_counter_news` (`year`, `month`, `day`, `id`, `hits`) VALUES ('$countervars[year]', '$countervars[month]', '$countervars[day]', '$_GET[id]', 0)");
			@mysql_query("UPDATE `$FSXL[tableset]_counter_news` set `hits` = `hits`+ 1 WHERE `year` = '$countervars[year]' AND `month` = '$countervars[month]' AND `day` = '$countervars[day]' AND `id` = '$_GET[id]'");
		}
	}
	// Gallery
	if($_GET[section] == 'gallery' && $_GET[id])
	{
		$index = @mysql_query("SELECT `id` FROM `$FSXL[tableset]_galleries` WHERE `id` = '$_GET[id]'");
		if (mysql_num_rows($index) > 0)
		{
			@mysql_query("INSERT INTO `$FSXL[tableset]_counter_gallery` (`year`, `month`, `day`, `id`, `hits`) VALUES ('$countervars[year]', '$countervars[month]', '$countervars[day]', '$_GET[id]', 0)");
			@mysql_query("UPDATE `$FSXL[tableset]_counter_gallery` set `hits` = `hits`+ 1 WHERE `year` = '$countervars[year]' AND `month` = '$countervars[month]' AND `day` = '$countervars[day]' AND `id` = '$_GET[id]'");
		}
	}
}


// Counter Cache
if (file_exists('cache/counter.cch')) // Datei vorhanden
{
	// Datum überprüfen
	if (filemtime('cache/counter.cch') < $FSXL[time]-$countervars[cachetime]) // Zu alt
	{
		$createcounter = true;
	}
}
else // Datei nicht vorhanden
{
	$createcounter = true;
}
if ($createcounter)
{
	$index = mysql_query("SELECT SUM(`visits`) AS `visits`, SUM(`hits`) AS `hits` FROM `$FSXL[tableset]_counter_stat`");
	$stat = mysql_fetch_assoc($index);
	$index = mysql_query("SELECT `visits`, `hits` FROM `$FSXL[tableset]_counter_stat` WHERE `day` = '$countervars[day]' AND `month` = '$countervars[month]' AND `year` = '$countervars[year]'");
	$today = mysql_fetch_assoc($index);
	$index = mysql_query("SELECT COUNT(`date`) AS `num` FROM `$FSXL[tableset]_counter_user` WHERE `enddate` >= $FSXL[time]-$countervars[cachetime]");
	$online = mysql_fetch_assoc($index);
	$code = $stat[visits].','.$stat[hits].','.$today[visits].','.$today[hits].','.$online[num];

	// Cache schreiben
	$fp = @fopen('cache/counter.cch', 'w');
	@fwrite($fp, $code);
	@fclose($fp);
}

?>