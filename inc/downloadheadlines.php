<?php

// Template lesen
$download_tpl = new template('downloadheadlines');
$download_tpl->getItem('download');
	
// Datenlesen
$index = mysql_query("SELECT `id`, `name`, `date` FROM `$FSXL[tableset]_dl` WHERE `date` <= '$FSXL[time]' AND `active` = 1 ORDER BY `date` DESC LIMIT ".$FSXL[config][download_headlines]);
while ($headline = mysql_fetch_assoc($index))
{
	$download_tpl->newItemNode('download');
	$download_tpl->replaceNodeVar('{url}', '?section=download&id='.$headline[id]);
	$download_tpl->replaceNodeVar('{title}', $headline[name]);
	$download_tpl->replaceNodeVar('{date}', date($FSXL[config][dateformat], $headline[date]));
}
$download_tpl->replaceItem('download');

// Template ausgeben
$downloadheadlinetpl = $download_tpl->code;
unset($download_tpl);


?>