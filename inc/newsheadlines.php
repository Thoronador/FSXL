<?php

// Template lesen
$news_tpl = new template('newsheadlines');
$news_tpl->getItem('headline');

// News IDs auslesen
$index = mysql_query("SELECT `newsid` FROM `$FSXL[tableset]_newstozone`
						WHERE `zoneid` = ".$FSXL[zone][id]." AND `date` <= '$FSXL[time]'
						GROUP BY `newsid` ORDER BY `date` DESC LIMIT ".$FSXL[config][news_headlines]);
$query = "SELECT `id`, `titel`, `datum` FROM `$FSXL[tableset]_news` WHERE ";
while ($connect = mysql_fetch_assoc($index)) {
	$query .= "`id` = '$connect[newsid]' OR ";
}
$query = substr($query, 0, -3) . ' ORDER BY `datum` DESC';

// Daten lesen
$index = @mysql_query($query);
$i = 1;
while ($headline = mysql_fetch_assoc($index))
{
	if ($i <= $FSXL[config][news_perpage]) $url = '#news_'.$headline[id];
	else $url = '?section=newsdetail&id='.$headline[id];

	$news_tpl->newItemNode('headline');
	$news_tpl->replaceNodeVar('{url}', $url);
  $headline[titel]=(strlen($headline[titel])>25)?substr($headline[titel], 0, 25)."...":$headline[titel];
	$news_tpl->replaceNodeVar('{title}', $headline[titel]);
	$news_tpl->replaceNodeVar('{date}', date($FSXL[config][dateformat], $headline[datum]));

	$i++;
}
$news_tpl->replaceItem('headline');

// Template ausgeben
$headlinetemplate = $news_tpl->code;
unset($news_tpl);


?>