<?php

$FSXL[title] = 'REBUILD DOWNLOAD SEARCH INDEX';

$perpage = 20;
$index = mysql_query("SELECT COUNT(`id`) AS `value` FROM `$FSXL[tableset]_dl`");
$value = mysql_result($index, 0, 'value');

// Start festlegen
if (!$_GET[start]) {
	$_GET[start] = 0;
}
settype($_GET[start], 'integer');

// Download auslesen
$index = mysql_query("SELECT `id`, `text`, `name` FROM `$FSXL[tableset]_dl` ORDER BY `id` LIMIT $_GET[start], $perpage");
while($dl = mysql_fetch_assoc($index))
{
	// Suchindex
	updateSearchIndex($dl[id], 'download', $dl[text].' '.$dl[name]);
}

// Alle fertig
if (mysql_num_rows($index) == 0) {
	$FSXL[content] = '
		<div style="padding:20px; text-align:center;">
			The searchindex of <b>'.$value.'</b> downloads had been rebuild.
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
		<meta http-equiv="refresh" content="0; URL=index.php?mod=download&go=rebuildindex&start='.($_GET[start]+$perpage).'">
	';
}

?>