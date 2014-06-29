<?php

// Detailansicht
if ($_GET[detail])
{
	settype($_GET[detail], 'integer');

	// Admin
	if ($_SESSION[user]->isadmin)
	{
		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_gallery_timed` WHERE `id` = '$_GET[detail]'");
	}
	// User
	else
	{
		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_gallery_timed` WHERE `id` = '$_GET[detail]' AND `startdate` < '$FSXL[time]'");
	}

	// Falls Bild vorhanden
	if (mysql_num_rows($index) > 0)
	{
		$pic = mysql_fetch_assoc($index);

		// Bilder Anzahl
		$index = mysql_query("SELECT COUNT(`id`) AS value FROM `$FSXL[tableset]_gallery_timed` WHERE `startdate` <= '$pic[startdate]'");
		$currentpic = mysql_fetch_assoc($index);

		$index = mysql_query("SELECT COUNT(`id`) AS value FROM `$FSXL[tableset]_gallery_timed` WHERE `startdate` <= '$FSXL[time]'");
		$totalpics = mysql_fetch_assoc($index);

		// Pagetitle
		$FSXL[pgtitle] = $pic[titel] . ' - (' . $currentpic[value] . '/' . $totalpics[value] . ')';

		// Next Link erzeugen
		if ($currentpic[value] == $totalpics[value])
		{
			$index = mysql_query("SELECT `id` FROM `$FSXL[tableset]_gallery_timed` WHERE `startdate` < '$FSXL[time]' ORDER BY `startdate` ASC LIMIT 1");
			$nextid = mysql_result($index, 0, 'id');
		}
		else
		{
			$index = mysql_query("SELECT `id` FROM `$FSXL[tableset]_gallery_timed` WHERE `startdate` > '$pic[startdate]' AND `startdate` < '$FSXL[time]' ORDER BY `startdate` ASC LIMIT 1");
			$nextid = mysql_result($index, 0, 'id');
		}
		$nextlink = '?section=timed&detail=' . $nextid;

		// Prev link erzeugen
		if ($currentpic[value] == 1)
		{
			$index = mysql_query("SELECT `id` FROM `$FSXL[tableset]_gallery_timed` WHERE `startdate` < '$FSXL[time]' ORDER BY `startdate` DESC LIMIT 1");
			$previd = mysql_result($index, 0, 'id');
		}
		else
		{
			$index = mysql_query("SELECT `id` FROM `$FSXL[tableset]_gallery_timed` WHERE `startdate` < '$pic[startdate]' AND `startdate` < '$FSXL[time]' ORDER BY `startdate` DESC LIMIT 1");
			$previd = mysql_result($index, 0, 'id');
		}
		$prevlink = '?section=timed&detail=' . $previd;

		$hash = md5($pic[date].$pic[id]);

		// Template lesen
		$gallery_tpl = new template('gallery_detail');

		// Template füllen
		$gallery_tpl->replaceTplVar('{gallerydate}', date($FSXL[config][dateformat], $pic[startdate]));
		$gallery_tpl->replaceTplVar('{title}', $pic[titel]);
		$gallery_tpl->replaceTplVar('{piclink}', 'images/timed/'.$hash.'.jpg');
		$gallery_tpl->replaceTplVar('{text}', fscode($pic[text]));
		$gallery_tpl->replaceTplVar('{prevlink}', $prevlink);
		$gallery_tpl->replaceTplVar('{nextlink}', $nextlink);
		$gallery_tpl->replaceTplVar('{totalpics}', $totalpics[value]);
		$gallery_tpl->replaceTplVar('{currentpic}', $currentpic[value]);
		
		$gallery_tpl->switchCondition('gallery', false);

		// Template ausgeben
		$FSXL[template] .= $gallery_tpl->code;
		unset($gallery_tpl);
	}

	// Bild nicht gefunden oder nicht freigegeben
	else
	{
		$FSXL[template] .= errorMsg('errorfilenotfound');
	}
}

// Kein Bild angegeben
else
{
	$FSXL[template] .= errorMsg('errorfilenotfound');
}

?>