<?php

// Template lesen
$gallery_tpl = new template('galleryheadlines');
$gallery_tpl->getItem('gallery');

// Daten einlesen
if (!$_SESSION[loggedin] && $FSXL[config][showregonly] != 1)
{
	$sqladd = 'AND `regonly` = 0';
}
$index = mysql_query("SELECT `id`, `name`, `datum`, `zoneid` FROM `$FSXL[tableset]_galleries` WHERE `datum` <= '$FSXL[time]' $sqladd ORDER BY `datum` DESC LIMIT ".$FSXL[config][gallery_headlines]);

// Template füllen
while ($headline = mysql_fetch_assoc($index))
{
	$gallery_tpl->newItemNode('gallery');
	if ($headline[zoneid] > 0) {
		$url = $FSXL[zones][$headline[zoneid]][url] . '/';
	} else {
		$url = '';
	}
	$gallery_tpl->replaceNodeVar('{url}', $url.'?section=gallery&id='.$headline[id]);
	$gallery_tpl->replaceNodeVar('{title}', $headline[name]);
	$gallery_tpl->replaceNodeVar('{date}', date($FSXL[config][dateformat], $headline[datum]));
}
$gallery_tpl->replaceItem('gallery');

// Template ausgeben
$galleryheadlinetpl = $gallery_tpl->code;
unset($gallery_tpl);

?>