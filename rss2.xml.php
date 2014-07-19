<?php

include('admin/inc/class.inc.php');
include('admin/inc/config.inc.php');
include('admin/inc/functions.inc.php');

// Zone?
if ($_GET['zone'])
{
	settype($_GET['zone'], 'integer');
	$addstring = '_' . $_GET['zone'];
}

// Cache laden
if (file_exists('cache/rss2'.$addstring.'.cch')) // Datei vorhanden
{
	// Datum überprüfen
	if (filemtime('cache/rss2'.$addstring.'.cch') < time()-($FSXL['rss_cache_time']*60)) // Zu alt
	{
		$create = true;
	}
	else // Datei OK -> augeben
	{
		$code = implode('', file('cache/rss2'.$addstring.'.cch'));
	}
}
else // Datei nicht vorhanden
{
	$create = true;
}

// Neuen Feed erzeugen
if ($create)
{
	// Datanbank Verbindung aufbauen
	@$db = new mysql($SQL['host'], $SQL['user'], $SQL['pass'], $SQL['data']);
	if (!$db->error['error'])
	{
		// Konfiguration laden
		$FSXL['config'] = createConfigArray();

		$code = '<?xml version="1.0" encoding="ISO-8859-1"?>
		<rss version="2.0">
			<channel>
				<title>'.$FSXL['config']['pagetitle'].'</title>
				<link>http://'.$_SERVER['SERVER_NAME'].substr($_SERVER['SCRIPT_NAME'], 0, strlen($_SERVER['SCRIPT_NAME'])-12).'</link>
				<description>'.$FSXL['config']['news_rssdesc'].'</description>
				<language>de-de</language>
		';

		$time = time();
		// Einzelne Zone
		if ($_GET['zone']) {
			$index = mysql_query("SELECT `newsid` FROM `$FSXL[tableset]_newstozone`
									WHERE `zoneid` = '$_GET[zone]' AND `date` <= '$time'
									GROUP BY `newsid` ORDER BY `date` DESC LIMIT ".$FSXL[config][news_rssnum]);
			$query = "SELECT * FROM `$FSXL[tableset]_news` WHERE ";
			if (mysql_num_rows($index) > 0) {
				while ($connect = mysql_fetch_assoc($index)) {
					$query .= "`id` = '$connect[newsid]' OR ";
				}
				$query = substr($query, 0, -3) . ' ORDER BY `datum` DESC';
				$index = mysql_query($query);
			}
			// Keine News gefunden
			else {
				die('');
			}
		}
		// Alle News
		else {
			$index = mysql_query("SELECT * FROM `$FSXL[tableset]_news` WHERE `datum` <= '$time' ORDER BY `datum` DESC LIMIT ".$FSXL[config][news_rssnum]);
		}

		while($news = mysql_fetch_assoc($index))
		{
			$url = 'http://'.$_SERVER['SERVER_NAME'].substr($_SERVER['SCRIPT_NAME'], 0, strlen($_SERVER['SCRIPT_NAME'])-12).'?section=newsdetail&amp;id='.$news['id'];
			$text = cleanText($news['text'], false);
			$text = preg_replace("/\[§(.*?)§\]/is", '', $text);
			preg_match("/(.{1,".$FSXL['config']['news_rsslen']."})(\s|$)/is", $text, $match);
			$text = $match[1].' ...';

			$code .= '
				<item>
					<title>'.$news['titel'].'</title>
					<description>'.$text.'</description>
					<link>'.$url.'</link>
					<pubDate>'.date('D, j M Y H:i:s T', $news['datum']).'</pubDate>
				</item>
			';
		}

		$code .= '
			</channel>
		</rss>
		';

		// Cache schreiben
		$fp = @fopen('cache/rss2'.$addstring.'.cch', 'w');
		@fwrite($fp, $code);
		@fclose($fp);

		// Datenbank Verbindung schließen
		$db->close();
	}
}

// Datei ausgeben
header ('Content-type: application/rss+xml');

echo $code;

?>
