<?php

// Ticker anzeigen
if ($_GET[id])
{
	settype($_GET[id], 'integer');
	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_ticker` WHERE `id` = '$_GET[id]'");
	if (mysql_num_rows($index) > 0)
	{
		$ticker = mysql_fetch_assoc($index);

		// Pagetitle
		$FSXL[pgtitle] = $ticker[name];

		// Template lesen
		$ticker_tpl = new template('tickerdetail');
		$ticker_tpl->getItem('entry');

		// Einträge auflisten
		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_ticker_text` WHERE `ticker` = '$ticker[id]' ORDER BY `date` DESC");
		while ($entry = mysql_fetch_assoc($index))
		{
			$i++;
			$ticker_tpl->newItemNode('entry');
			$ticker_tpl->replaceNodeVar('{altnum}', $i%2==0?1:2);
			$ticker_tpl->replaceNodeVar('{date}', date($FSXL[config][dateformat], $entry[date]));
			$ticker_tpl->replaceNodeVar('{text}', fscode($entry[text]));
		}
		$ticker_tpl->replaceItem('entry');

		// Variablen ersetzen
		$ticker_tpl->replaceTplVar('{name}', $ticker[name]);
		$ticker_tpl->replaceTplVar('{description}', fscode($ticker[text]));

		// Ticker noch aktiv?
		if ($ticker[active] == 1)
		{
			$sid = $_GET[PHPSESSID] ? '&PHPSESSID='.$_GET[PHPSESSID] : '';
			$script = '<meta http-equiv="refresh" content="'.$ticker[interval].'; URL=./?section=ticker&id='.$ticker[id].$sid.'">';
		}
		else $script = '';
		$ticker_tpl->replaceTplVar('{script}', $script);

		// Template ausgeben
		$FSXL[template] .= $ticker_tpl->code;
		unset($ticker_tpl);
	}

	// Ticker nicht gefunden
	else
	{
		$FSXL[template] .= errorMsg('errorfilenotfound');
	}
}

// Ticker Übersicht
else
{
	// Template lesen
	$ticker_tpl = new template('tickerlist');
	$ticker_tpl->getItem('ticker');

	// Daten lesen
	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_ticker` ORDER BY `id` DESC");
	while ($ticker = mysql_fetch_assoc($index))
	{
		$i++;
		// Einträge vorhanden?
		$index2 = mysql_query("SELECT `date` FROM `$FSXL[tableset]_ticker_text` WHERE `ticker` = '$ticker[id]' ORDER BY `date` DESC LIMIT 1");
		$text = mysql_fetch_assoc($index2);
		if ($text[date]) $date = date($FSXL[config][dateformat], $text[date]);
		else $date = '-';
		
		$url = $ticker[url] ? $ticker[url] : '?section=ticker&id='.$ticker[id];

		$ticker_tpl->newItemNode('ticker');
		$ticker_tpl->switchCondition('active', ($ticker[active] == 1 ? true : false), true);
		$ticker_tpl->replaceNodeVar('{altnum}', $i%2==0?1:2);
		$ticker_tpl->replaceNodeVar('{name}', $ticker[name]);
		$ticker_tpl->replaceNodeVar('{description}', fscode($ticker[text]));
		$ticker_tpl->replaceNodeVar('{url}', $url);
		$ticker_tpl->replaceNodeVar('{lastentry}', $date);
	}
	$ticker_tpl->replaceItem('ticker');

	// Template ausgeben
	$FSXL[template] .= $ticker_tpl->code;
	unset($ticker_tpl);
}

?>