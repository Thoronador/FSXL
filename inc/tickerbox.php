<?php

// Template lesen
$ticker_tpl = new template('tickerbox');

// Daten lesen
$index = mysql_query("SELECT * FROM `$FSXL[tableset]_ticker` WHERE `show` = 1 ORDER BY `id` DESC");
while ($ticker = mysql_fetch_assoc($index))
{
	$index2 = mysql_query("SELECT * FROM `$FSXL[tableset]_ticker_text` WHERE `ticker` = '$ticker[id]' ORDER BY `date` DESC LIMIT 1");
	if (mysql_num_rows($index2) > 0) {
		$tickertext = mysql_fetch_assoc($index2);
	}
	$url = $ticker[url] ? $ticker[url] : '?section=ticker&id='.$ticker[id];
	
	$ticker_tpl->newListItem();
	$ticker_tpl->replaceListVar('{name}', $ticker[name]);
	$ticker_tpl->replaceListVar('{description}', fscode($ticker[text]));
	$ticker_tpl->replaceListVar('{url}', $url);
	$ticker_tpl->replaceListVar('{lastentry}', fscode($tickertext[text]));
}

// Template ausgeben
$ticker_tpl->collapseList();
$tickertpl = $ticker_tpl->code;
unset($ticker_tpl);

?>