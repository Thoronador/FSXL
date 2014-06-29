<?php

// Template lesen
$potm_tpl = new template('potm');

// Daten lesen	
$index = mysql_query("SELECT * FROM `$FSXL[tableset]_gallery_potm` ORDER BY `id`");
if (mysql_num_rows($index) > 0)
{
	while ($potm = mysql_fetch_assoc($index))
	{
		$potm_tpl->newListItem();

		// Zeitbild
		if ($potm[gallery] == 0)
		{
			$index2 = mysql_query("SELECT `id`, `titel`, `date` FROM `$FSXL[tableset]_gallery_timed` WHERE `startdate` < '$FSXL[time]' ORDER BY `startdate` DESC LIMIT 1");
			if (mysql_num_rows($index2) > 0)
			{
				$pic = mysql_fetch_assoc($index2);
			}
			else
			{
				$nopotmpic = true;
			}
		}
		// Galerie
		else
		{
			// Zufallsbild lesen
			$index2 = mysql_query("SELECT `id`, `titel`, `date`, `galleryid` FROM `$FSXL[tableset]_gallerypics` 
									WHERE `galleryid` = '$potm[gallery]' AND `release` < $FSXL[time]");

			$rnd = rand(0, mysql_num_rows($index2)-1);
			$pic[id] = mysql_result($index2, $rnd, 'id');
			$pic[titel] = mysql_result($index2, $rnd, 'titel');
			$pic[date] = mysql_result($index2, $rnd, 'date');
			$pic[galleryid] = mysql_result($index2, $rnd, 'galleryid');
		}

		$hash = md5($pic[date].$pic[id]);

		// Linktyp auswählen
		if ($FSXL[config][gallery_potmsingle] == 1)
		{
			if ($potm[gallery] == 0)
				$img = '?section=timed&detail='.$pic[id];
			else
				$img = '?section=gallery&detail='.$pic[id];
		}
		else
		{
			if ($potm[gallery] == 0)
				$img = 'images/timed/'.$hash.'.jpg';
			else
				$img = 'images/gallery/'.$pic[galleryid].'/'.$hash.'.jpg';
		}

		if ($potm[gallery] == 0)
			$potm_tpl->replaceListVar('{thumb}', 'images/timed/'.$hash.'s.jpg');
		else
			$potm_tpl->replaceListVar('{thumb}', 'images/gallery/'.$pic[galleryid].'/'.$hash.'s.jpg');
		$potm_tpl->replaceListVar('{img}', $img);
		$potm_tpl->replaceListVar('{title}', $potm[title]);
		$potm_tpl->replaceListVar('{picname}', $pic[titel]);
		
		if ($nopotmpic)
		{
			array_pop($potm_tpl->list);
		}
	}

	// Template ausgeben
	$potm_tpl->collapseList();
	$potmtpl = $potm_tpl->code;
	unset($potm_tpl);
}

// Kein POTM
else
{
	$potmtpl = '';
}


?>