<?php

// Template lesen
$link_tpl = new template('linkheadlines');
$link_tpl->getItem('link');

// Daten einlesen
$index = mysql_query("SELECT `cat`, `subcat`, `name`, `date`, `tag` FROM `$FSXL[tableset]_link` WHERE `date` <= '$FSXL[time]' ORDER BY `date` DESC LIMIT ".$FSXL[config][link_headlines]);

// Template füllen
while ($headline = mysql_fetch_assoc($index))
{
	if ($headline[subcat] != 0) {
		$link = '?section=links&cat='.$headline[cat].'&sub='.$headline[subcat];
	} else {
		$link = '?section=links&cat='.$headline[cat];
	}
	$link_tpl->newItemNode('link');
	$link_tpl->replaceNodeVar('{url}', $link);
	$link_tpl->replaceNodeVar('{title}', $headline[name]);
	$link_tpl->replaceNodeVar('{tag}', $headline[tag]);
	$link_tpl->replaceNodeVar('{date}', date($FSXL[config][dateformat], $headline[date]));
	$link_tpl->switchCondition('tag', $headline[tag]?true:false, true);
}
$link_tpl->replaceItem('link');

// Template ausgeben
$linkheadlinetpl = $link_tpl->code;
unset($link_tpl);

?>