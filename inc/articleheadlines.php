<?php

// Template lesen
$article_tpl = new template('articleheadlines');
$article_tpl->getItem('article');

// Daten einlesen
$sqladd = '';
if (!$_SESSION[loggedin] && $FSXL[config][showregonly] != 1) {
	$sqladd = 'AND `regonly` = 0 ';
}
if ($_SESSION[zone] == $FSXL[config][defaultzone]) {
	$issubzone = false;
	$sqladd .= 'AND `zoneid` IN ('.implode(",", $FSXL[currentzones]).')';
} else {
	$issubzone = true;
	$sqladd .= "AND `zoneid` = '$_SESSION[zone]' ";
}
$index = mysql_query("SELECT `id`, `titel`, `zoneid`, `datum` FROM `$FSXL[tableset]_article` WHERE `datum` <= '$FSXL[time]' $sqladd AND `invisible` = 0 ORDER BY `datum` DESC LIMIT ".$FSXL[config][article_headlines]);

// Template füllen
while ($headline = mysql_fetch_assoc($index))
{
	$article_tpl->newItemNode('article');
	if ($headline[zoneid] > 0 && $issubzone == false) {
		$url = $FSXL[zones][$headline[zoneid]][url] . '/';
	} else {
		$url = '';
	}
	$article_tpl->replaceNodeVar('{url}', $url.'?section=article&id='.$headline[id]);
	$article_tpl->replaceNodeVar('{title}', $headline[titel]);
	$article_tpl->replaceNodeVar('{date}', date($FSXL[config][dateformat], $headline[datum]));
}
$article_tpl->replaceItem('article');

// Template ausgeben
$articleheadlinetpl = $article_tpl->code;
unset($article_tpl);

?>