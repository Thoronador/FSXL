<?php

// Detailansicht
if ($_GET[detail])
{
	settype($_GET[detail], 'integer');

	// Admin
	if ($_SESSION[user]->isadmin)
	{
		$index = mysql_query("SELECT p.titel AS titel, p.text AS text, p.position AS position, p.galleryid AS galleryid, 
						p.date AS date, g.name AS galleryname, g.regonly AS regonly, g.pics AS pics, p.id AS id, g.datum AS gallerydate, g.type AS gallerytype, g.age AS age, g.hidden as hidden
					FROM $FSXL[tableset]_gallerypics p, $FSXL[tableset]_galleries g
					WHERE p.id = '$_GET[detail]' AND g.id = p.galleryid");
	}
	// User
	else
	{
		$index = mysql_query("SELECT p.titel AS titel, p.text AS text, p.position AS position, p.galleryid AS galleryid, 
						p.date AS date, g.name AS galleryname, g.regonly AS regonly, g.pics AS pics, p.id AS id, g.datum AS gallerydate, g.type AS gallerytype, g.age AS age, g.hidden as hidden
					FROM $FSXL[tableset]_gallerypics p, $FSXL[tableset]_galleries g
					WHERE p.id = '$_GET[detail]' AND g.id = p.galleryid AND g.datum <= '$FSXL[time]' AND p.release <= '$FSXL[time]'");
	}

	// Falls Bild vorhanden
	if (mysql_num_rows($index) > 0)
	{
		$pic = mysql_fetch_assoc($index);

		// Alterscheck
		$agecheck = agecheck($gallery[age]);
		if ($agecheck[0] || $_SESSION[user]->isadmin)
		{
			mysql_query("UPDATE `$FSXL[tableset]_gallerypics` SET `hits` = `hits` + 1 WHERE `id` = '$pic[id]'");

			// Nur für Benutzer und nicht eingelogt
			if ($pic[regonly] == 1 && !$_SESSION[loggedin])
			{
				$FSXL[template] .= errorMsg('errorregonly');
			}

			// Bild anzeigen
			else
			{
				// Bilder Anzahl
				$index = mysql_query("SELECT COUNT(`id`) AS value FROM `$FSXL[tableset]_gallerypics` WHERE `galleryid` = '$pic[galleryid]' 
										AND `position` <= '$pic[position]' AND `release` <= '$FSXL[time]' ORDER BY `position`");
				$currentpic = mysql_fetch_assoc($index);
				$index = mysql_query("SELECT COUNT(`id`) AS value FROM `$FSXL[tableset]_gallerypics` WHERE `galleryid` = '$pic[galleryid]' 
										AND `release` <= '$FSXL[time]' ORDER BY `position`");
				$totalpics = mysql_fetch_assoc($index);

				// Pagetitle
				$FSXL[pgtitle] = $pic[galleryname] . ' - ' . $pic[titel] . ' - (' . $currentpic[value] . '/' . $pic[pics] . ')';

				// Next Link erzeugen
				$index = mysql_query("SELECT `id` FROM `$FSXL[tableset]_gallerypics` WHERE `galleryid` = '$pic[galleryid]' 
										AND `position` > '$pic[position]' AND `release` <= '$FSXL[time]' ORDER BY `position` ASC LIMIT 1");
				if (mysql_num_rows($index) > 0)
				{
					$nextid = mysql_result($index, 0, 'id');
					$nextlink = '?section=gallery&detail=' . $nextid;
				}
				else
				{
					$nextlink = '?section=gallery&id=' . $pic[galleryid];
				}

				// Prev link erzeugen
				$index = mysql_query("SELECT `id` FROM `$FSXL[tableset]_gallerypics` WHERE `galleryid` = '$pic[galleryid]' 
										AND `position` < '$pic[position]' AND `release` <= '$FSXL[time]' ORDER BY `position` DESC LIMIT 1");
				if (mysql_num_rows($index) > 0)
				{
					$previd = mysql_result($index, 0, 'id');
					$prevlink = '?section=gallery&detail=' . $previd;
				}
				else
				{
					$prevlink = '?section=gallery&id=' . $pic[galleryid];
				}
				
				// Links tauschen
				if ($pic[gallerytype] == 2) {
					$tmp = $nextlink;
					$nextlink = $prevlink;
					$prevlink = $tmp;
				}


				$hash = md5($pic[date].$pic[id]);

				// Template lesen
				$gallery_tpl = new template('gallery_detail');

				// Template füllen
				$gallery_tpl->replaceTplVar('{gallerydate}', date($FSXL[config][dateformat], $pic[gallerydate]));
				$gallery_tpl->replaceTplVar('{galleryname}', $pic[galleryname]);
				$gallery_tpl->replaceTplVar('{title}', $pic[titel]);
				$gallery_tpl->replaceTplVar('{piclink}', 'images/gallery/'.$pic[galleryid].'/'.$hash.'.jpg');
				$gallery_tpl->replaceTplVar('{text}', fscode($pic[text]));
				$gallery_tpl->replaceTplVar('{prevlink}', $prevlink);
				$gallery_tpl->replaceTplVar('{nextlink}', $nextlink);
				$gallery_tpl->replaceTplVar('{gallerylink}', '?section=gallery&id='.$pic[galleryid]);
				$gallery_tpl->replaceTplVar('{totalpics}', $totalpics[value]);
				$gallery_tpl->replaceTplVar('{currentpic}', $currentpic[value]);

				$gallery_tpl->switchCondition('not_hidden', ($pic[hidden]==0?true:false));
				$gallery_tpl->switchCondition('gallery', true);

				// Template ausgeben
				$FSXL[template] .= $gallery_tpl->code;
				unset($gallery_tpl);
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

	// Bild nicht gefunden oder nicht freigegeben
	else
	{
		$FSXL[template] .= errorMsg('errorfilenotfound');
	}
}

// Bilder Übersicht
elseif ($_GET[id])
{
	settype($_GET[id], 'integer');

	// Admin
	if ($_SESSION[user]->isadmin)
	{
		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_galleries` WHERE `id` = '$_GET[id]'");
	}
	// User
	else
	{
		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_galleries` WHERE `id` = '$_GET[id]' AND `hidden` = 0 AND `datum` <= '$FSXL[time]'");
	}

	// Falls Gallerie vorhanden
	if (mysql_num_rows($index) > 0)
	{
		$gallery = mysql_fetch_assoc($index);

		// Alterscheck
		$agecheck = agecheck($gallery[age]);
		if ($agecheck[0] || $_SESSION[user]->isadmin)
		{
			// Nur für Benutzer und nicht eingelogt
			if ($gallery[regonly] == 1 && !$_SESSION[loggedin])
			{
				$FSXL[template] .= errorMsg('errorregonly');
			}

			// Galerie anzeigen
			else
			{
				// Pagetitle
				$FSXL[pgtitle] = $gallery[name];

				// Template lesen
				$gallery_tpl = new template('gallery');
				$thumb_tpl = new template('gallery_thumb');

				// Thumbnails erzeugen
				$thumb_html = '<table border="0" cellpadding="2" cellspacing="0" width="100%">';

				// Bilder auslesen
				if ($gallery[type] == 1) $dest = 'ASC';
				if ($gallery[type] == 2) $dest = 'DESC';
				$index = mysql_query("SELECT * FROM `$FSXL[tableset]_gallerypics` WHERE `galleryid` = '$gallery[id]' AND `release` <= '$FSXL[time]' 
										ORDER BY `position` $dest");
				$rowcount = 1;
				while ($pic = mysql_fetch_assoc($index))
				{
					if ($rowcount == 1) $thumb_html .= '<tr>';

					$hash = md5($pic[date].$pic[id]);

					// Thumbtemplate kopieren und Variablen ersetzen
					$tempthumb = $thumb_tpl->code;
					$tempthumb = str_replace ("{detaillink}", '?section=gallery&detail='.$pic[id], $tempthumb);
					$tempthumb = str_replace ("{thumblink}", 'images/gallery/'.$pic[galleryid].'/'.$hash.'s.jpg', $tempthumb);
					$tempthumb = str_replace ("{title}", $pic[titel], $tempthumb);
					$tempthumb = str_replace ("{text}", $pic[text], $tempthumb);

					$thumb_html .= '<td>' . $tempthumb . '</td>';
					unset($tempthumb);
		
					if ($rowcount == $gallery[cols])
					{
						$thumb_html .= '</tr>';
						$rowcount = 0;
					}
					$rowcount++;
				}

				// Tabelle auffüllen
				if ($rowcount < 3)
				{
					do
					{
						$thumb_htmle .= '<td></td>';
						$rowcount++;
					}
					while ($rowcount < $gallery[cols]);
					$thumb_html .= '</tr>';
				}
				$thumb_html .= '</table>';

				// Template füllen
				$gallery_tpl->replaceTplVar('{description}', fscode($gallery[text]));
				$gallery_tpl->replaceTplVar('{thumbs}', $thumb_html);
				$gallery_tpl->replaceTplVar('{name}', $gallery[name]);
				$gallery_tpl->replaceTplVar('{date}', date($FSXL[config][dateformat], $gallery[datum]));
				$gallery_tpl->replaceTplVar('{numpics}', mysql_num_rows($index));
				unset($thumb_html);

				// Template ausgeben
				$FSXL[template] .= $gallery_tpl->code;
				unset($gallery_tpl);
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

	// Galerie nicht gefunden oder nicht freigegeben
	else
	{
		$FSXL[template] .= errorMsg('errorfilenotfound');
	}
}

// Galerie Übersicht
else
{
	// Übersicht verbergen
	if ($FSXL[config][gallery_showall] == 0 && !$_GET[cat])
	{
		$FSXL[template] .= errorMsg('errorfilenotfound');
	}
	else
	{
		// Einzelne Kategorie
		if ($_GET[cat])
		{
			settype($_GET[cat],'integer');
			$sqladd = "AND `cat` = $_GET[cat]";
		}

		// Template lesen
		$gallery_tpl = new template('gallerylist');
		$gallery_tpl->getItem('cat');
		$gallery_tpl->clearItem('cat');
		$gallery_tpl->getItem('gallery');


		// Nur für Benutzer und nicht eingelogt
		if (!$_SESSION[loggedin] && $FSXL[config][showregonly] != 1)
		{
			$sqladd2 = 'AND `regonly` = 0';
		}
		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_galleries` WHERE `datum` <= $FSXL[time] AND `hidden` = 0 $sqladd $sqladd2 ORDER BY `cat`, `datum` DESC");

		// Template füllen
		if (mysql_num_rows($index) > 0)
		{
			$currentcat = '';
			while ($gallery = mysql_fetch_assoc($index))
			{
				$i++;
				// Neue Kategorie
				if ($currentcat != $gallery[cat])
				{
					$currentcat = $gallery[cat];
					// Hat eine Kategorie
					if ($currentcat != 0)
					{
						$index2 = mysql_query("SELECT * FROM `$FSXL[tableset]_gallery_cat` WHERE `id` = '$gallery[cat]'");
						$cat = @mysql_fetch_assoc($index2);
					}

					$gallery_tpl->newItemNode('gallery', 'cat');
					$gallery_tpl->replaceNodeVar('{altnum}', $i%2==0?1:2);
					$gallery_tpl->replaceNodeVar('{catname}', $cat[name]);
					$gallery_tpl->replaceNodeVar('{caturl}', '?section=gallery&cat='.$cat[id]);
					$gallery_tpl->replaceNodeVar('{catdescription}', fscode($cat[text]));
					$gallery_tpl->switchCondition('cat', $gallery[cat]==0?false:true, true);
					$i++;
				}

				// Galerie Daten
				$gallery_tpl->newItemNode('gallery');
				$gallery_tpl->replaceNodeVar('{altnum}', $i%2==0?1:2);
				$gallery_tpl->replaceNodeVar('{gallery}', $gallery[name]);
				$gallery_tpl->replaceNodeVar('{galleryurl}', '?section=gallery&id='.$gallery[id]);
				$gallery_tpl->replaceNodeVar('{date}', date($FSXL[config][dateformat], $gallery[datum]));
				$gallery_tpl->replaceNodeVar('{numpics}', $gallery[pics]);
				$gallery_tpl->replaceNodeVar('{preview}', fscode($gallery[text]));
			}
			$gallery_tpl->replaceItem('gallery');

			// Einzelne Kategorie
			if ($_GET[cat])
			{
				$gallery_tpl->replaceTplVar('{catname}', $cat[name]);
			}

			// Template ausgeben
			$FSXL[template] .= $gallery_tpl->code;
			unset($gallery_tpl);
		}

		// Keine Galerien Gefunden
		else
		{
			$FSXL[template] .= errorMsg('errorfilenotfound');
		}
	}
}

?>