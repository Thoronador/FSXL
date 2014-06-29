<?php

// Video Detailansicht
if ($_GET[id])
{
	// Daten einlesen
	settype($_GET[id], 'integer');

	// Admin
	if ($_SESSION[user]->isadmin)
	{
		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_videos` WHERE `id` = '$_GET[id]'");
	}
	// User
	else
	{
		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_videos` WHERE `id` = '$_GET[id]' AND `date` <= '$FSXL[time]'");
	}

	if (mysql_num_rows($index) > 0)
	{
		$video = mysql_fetch_assoc($index);

		// Alterscheck
		$agecheck = agecheck($video[age]);
		if ($agecheck[0] || $_SESSION[user]->isadmin)
		{
			// Nur für Benutzer / nicht eingelogt
			if ($video[regonly] == 1 && !$_SESSION[loggedin])
			{
				$FSXL[template] .= errorMsg('errorregonly');
			}
			// Video ausgeben
			else
			{
				// Pagetitle
				$FSXL[pgtitle] = $video[name];

				// Template lesen
				$video_tpl = new template('video_detail');

				// Variablen ersetzen
				$video_tpl->replaceTplVar('{date}', date($FSXL[config][dateformat], $video[date]));
				$video_tpl->replaceTplVar('{description}', fscode($video[text]));
				$video_tpl->replaceTplVar('{name}', $video[name]);
				$video_tpl->replaceTplVar('{video}', $video[url]);
				$video_tpl->replaceTplVar('{color}', $FSXL[config][video_color]);

				// Video Player Style
				$style = $FSXL[config][video_showplay] . ',';
				$style .= $FSXL[config][video_showstop] . ',';
				$style .= $FSXL[config][video_showseek] . ',';
				$style .= $FSXL[config][video_showtime] . ',';
				$style .= $FSXL[config][video_showvolbar] . ',';
				$style .= $FSXL[config][video_showmute] . ',';
				$style .= $FSXL[config][video_showfullscreen];
				$video_tpl->replaceTplVar('{style}', $style);
				unset($style);

				// Template ausgeben
				$FSXL[template] .= $video_tpl->code;
			}
		}
		// Uhrzeit nicht passend für Alter
		else
		{
			$age_tpl = new template('ageblocker');
			$age_tpl->replaceTplVar('{time}', $agecheck[1]);
			$FSXL[template] .= $age_tpl->code;
		}
	}
	// Video nicht gefunden oder noch nicht freigegeben
	else
	{
		$FSXL[template] .= errorMsg('errorfilenotfound');
	}
}

// Video Übersicht
else
{
	// Übersicht verbergen
	if ($FSXL[config][video_showall] == 0 && !$_GET[cat])
	{
		$FSXL[template] .= errorMsg('errorfilenotfound');
	}
	else
	{
		$sqladd = '';
		// Einzelne Kategorie
		if ($_GET[cat])
		{
			settype($_GET[cat],'integer');
			$sqladd .= " AND `cat` = $_GET[cat]";
		}

		// Template lesen
		$video_tpl = new template('video_list');
		$video_tpl->getItem('cat');
		$video_tpl->clearItem('cat');
		$video_tpl->getItem('video');

		// Daten einlesen
		if (!$_SESSION[loggedin] && $FSXL[config][showregonly] != 1)
		{
			$sqladd .= 'AND `regonly` = 0';
		}
		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_videos` WHERE `date` <= '$FSXL[time]' $sqladd ORDER BY `date` DESC");

		// Artikel vorhanden
		if (mysql_num_rows($index) > 0)
		{
			$currentcat = '';

			// Template füllen
			while ($video = mysql_fetch_assoc($index))
			{
				$i++;
				// Neue Kategorie
				if ($currentcat != $video[cat])
				{
					$currentcat = $video[cat];
					if ($currentcat != 0)
					{
						$index2 = mysql_query("SELECT * FROM `$FSXL[tableset]_video_cat` WHERE `id` = '$video[cat]'");
						$cat = mysql_fetch_assoc($index2);
					}				
					$video_tpl->newItemNode('video', 'cat');
					$video_tpl->replaceNodeVar('{altnum}', $i%2==0?1:2);
					$video_tpl->replaceNodeVar('{catname}', $cat[name]);
					$video_tpl->replaceNodeVar('{caturl}', '?section=video&cat='.$cat[id]);
					$video_tpl->replaceNodeVar('{catdescription}', fscode($cat[text]));
					$video_tpl->switchCondition('cat', $video[cat]==0?false:true, true);
					$i++;
				}
				
				// Video
				$video_tpl->newItemNode('video');
				$video_tpl->replaceNodeVar('{altnum}', $i%2==0?1:2);
				$video_tpl->replaceNodeVar('{name}', $video[name]);
				$video_tpl->replaceNodeVar('{description}', fscode($video[text]));
				$video_tpl->replaceNodeVar('{url}', '?section=video&id='.$video[id]);
				$video_tpl->replaceNodeVar('{date}', date($FSXL[config][dateformat], $video[date]));
			}
			if ($_GET[cat])
			{
				$video_tpl->replaceTplVar('{catname}', $cat[name]);
			}
			$video_tpl->replaceItem('video');

			// Template ausgeben
			$FSXL[template] .= $video_tpl->code;
			unset($video_tpl);
		}

		// Keine Videos vorhanden
		else
		{
			$FSXL[template] .= errorMsg('errorfilenotfound');
		}
	}
}

?>