<?php

// Template lesen
$tagcloud_tpl = new template('tagcloud');
$tagcloud_tpl->getItem('tag');

$index = mysql_query("SELECT `hits`, `word` FROM `$FSXL[tableset]_search_words` ORDER BY `date` DESC LIMIT ".$FSXL[config][tagcloud_words]);
$tags = array();
$max = 0;
$i=0;
while($tag = mysql_fetch_assoc($index))
{
	$tags[$i][word] = $tag[word];
	$tags[$i][hits] = $tag[hits];
	if ($tag[hits] > $max) $max = $tag[hits];
	$i++;
}
foreach($tags AS $tag)
{
	$size = (($tag[hits] / $max) * ($FSXL[config][tagcloud_maxsize] - $FSXL[config][tagcloud_minsize])) + $FSXL[config][tagcloud_minsize];

	$tagcloud_tpl->newItemNode('tag');
	$tagcloud_tpl->replaceNodeVar('{word}', $tag[word]);
	$tagcloud_tpl->replaceNodeVar('{size}', $size);
}
$tagcloud_tpl->replaceItem('tag');

// Template ausgeben
$tagcloudtpl = $tagcloud_tpl->code;
unset($tagcloud_tpl);

?>