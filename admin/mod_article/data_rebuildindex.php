<?php

$FSXL[title] = 'REBUILD ARTICLE SEARCH INDEX';

$perpage = 5;
$index = mysql_query("SELECT COUNT(`id`) AS `value` FROM `$FSXL[tableset]_article`");
$value = mysql_result($index, 0, 'value');

// Start festlegen
if (!$_GET[start]) {
	$_GET[start] = 0;
}
settype($_GET[start], 'integer');

// Artikel auslesen
$index = mysql_query("SELECT `id`, `text`, `invisible` FROM `$FSXL[tableset]_article` ORDER BY `id` LIMIT $_GET[start], $perpage");
while($article = mysql_fetch_assoc($index))
{
	// Suchindex
	if ($article[invisible] == 1) {
		updateSearchIndex($article[id], 'article', ' ');
	} else {
		updateSearchIndex($article[id], 'article', $article[text]);
	}
}

// Alle fertig
if (mysql_num_rows($index) == 0) {
	$FSXL[content] = '
		<div style="padding:20px; text-align:center;">
			The searchindex of <b>'.$value.'</b> article had been rebuild.
		</div>
	';
}

// Nächsten
else {
	$percent = round(100/$value*($_GET[start]+$perpage));

	$FSXL[content] = '
		<div style="padding:20px; text-align:center;">
			<table style="width:400px; margin:0px auto;" cellpadding="0" cellspacing="0">
				<tr>
					<td style="height:20px; width:'.($percent*4).'px; border:1px solid #000000; background-color:#666666;"></td>
					<td style="height:20px; width:'.(400-($percent*4)).'px; border-top:1px solid #000000; border-right:1px solid #000000; border-bottom:1px solid #000000;"></td>
				</tr>
			</table>
			<p>
			<div><b>Status:</b> '.$percent.'% rebuild</div>
		</div>
		<meta http-equiv="refresh" content="0; URL=index.php?mod=article&go=rebuildindex&start='.($_GET[start]+$perpage).'">
	';
}

?>