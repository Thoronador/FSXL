<?php

$FSXL[title] = $FS_PHRASES[gallery_pics_title];

function createGalleryPic($imgfile, $id, $position, $title, $text, $release)
{
	global $FSXL;

	settype($id, 'integer');
	settype($position, 'integer');
	$index = mysql_query("INSERT INTO `$FSXL[tableset]_gallerypics` (`id`, `galleryid`, `titel`, `text`, `position`, `date`, `hits`, `release`)
							VALUES (NULL, '$id', '$title', '$text', '$position', $FSXL[time], 0, '$release')");
	if ($index)
	{
		$picid = mysql_insert_id();
		$hash = md5($FSXL[time].$picid);

		// Gallerydaten auslesen
		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_galleries` WHERE `id` = $id");
		$gallery = mysql_fetch_assoc($index);

		$img = new imgConvert();
		if ($img->readIMG($imgfile))
		{
			$img->saveIMG('../images/gallery/'.$id.'/', $hash, 'jpg');

			$img->scaleIMG($gallery[thumbx], $gallery[thumby], 'LETTERBOX', $gallery[color]);
			$img->saveIMG('../images/gallery/'.$id.'/', $hash.'s', 'jpg');

			return true;
		}
		else
		{
			mysql_query("DELETE FROM `$FSXL[tableset]_gallerypics` WHERE `id` = $picid");
			return false;
		}
	}
	else
	{
		return false;
	}
}

// Auto Fill
if ($_POST[action] == 'autofill' && $_POST[autofill])
{
	// Zip Datei
	if (substr($_POST[autofill], strlen($_POST[autofill])-4, 4) == '.zip')
	{
		if (function_exists('zip_open'))
		{
			@copy($_POST[autofill], 'mod_gallery/tmp/tmp.zip');

			// Entpacken
			$ZipPointer = zip_open(getcwd() . '/mod_gallery/tmp/tmp.zip');
			if($ZipPointer)
			{
				$FSXL[content] = '';
				while($GezippteDatei = zip_read($ZipPointer))
				{
					$endung = substr(zip_entry_name($GezippteDatei), strlen(zip_entry_name($GezippteDatei))-4, 4);
					if ($endung == '.jpg' || $endung == '.gif' || $endung == '.png')
					{
						if(zip_entry_open($ZipPointer, $GezippteDatei, "r"))
						{
							$FilePointer = fopen('mod_gallery/tmp/'.zip_entry_name($GezippteDatei), "w");
							fwrite($FilePointer, zip_entry_read($GezippteDatei, zip_entry_filesize($GezippteDatei)));
							fclose($FilePointer);
    
							zip_entry_close($GezippteDatei);
						}
					}
				}
				zip_close($ZipPointer);
			}
			else
			{
				$FSXL[content] = '
				<div style="padding:20px; text-align:center;">
					'.$FS_PHRASES[gallery_pics_unzipfailed].'<p>
					<form action="" method="get">
						<input type="hidden" name="mod" value="gallery">
						<input type="hidden" name="go" value="pics">
						<input type="hidden" name="galleryid" value="'.$_POST[id].'">
						<input type="submit" class="button" value="'.$FS_PHRASES[global_ok].'">
					</form>
				</div>
				';
			}	
		}
		else
		{
			$FSXL[content] = '
				<div style="padding:20px; text-align:center;">
					'.$FS_PHRASES[gallery_pics_nozipinstalled].'<p>
					<form action="" method="get">
						<input type="hidden" name="mod" value="gallery">
						<input type="hidden" name="go" value="pics">
						<input type="hidden" name="galleryid" value="'.$_POST[id].'">
						<input type="submit" class="button" value="'.$FS_PHRASES[global_ok].'">
					</form>
				</div>
			';
		}
	}
	else
	{
		$page = @file($_POST[autofill]);
		$html = @implode ('', $page);

		preg_match_all("/href=\"(.*?)\.(jpg|png|gif)\"/is", $html, $matches);

		if ($matches)
		{
			foreach($matches[1] AS $key => $value)
			{
				$filename = $matches[1][$key]. '.' . $matches[2][$key];
				@copy($_POST[autofill].$filename, 'mod_gallery/tmp/'.$filename);
			}
		}
	}

	// Galerie füllen
	settype($_POST[id], 'integer');

	// Nächste Position ermitteln
	$index = mysql_query("SELECT `position` FROM `$FSXL[tableset]_gallerypics` WHERE `galleryid` = $_POST[id] ORDER BY `position` DESC LIMIT 1");
	if (mysql_num_rows($index) == 0) $pos = 1;
	else $pos = mysql_result($index, 0, 'position') + 1;

	$pfad = 'mod_gallery/tmp/';
	$verz = opendir($pfad);
	$godpics = 0;
	$badpics = 0;
	while ($file = readdir($verz))
	{
		$endung = substr($pfad.$file, strlen($pfad.$file)-3, 3);
		if ($endung == 'jpg' || $endung == 'gif' || $endung == 'png')
		{
			$file_arr = array();
			$file_arr[tmp_name] = $file;
			$file_arr[type] = 'image/'.$endung;
			$file_arr[path] = $pfad;
			$chk = createGalleryPic($file_arr, $_POST[id], $pos, '', '', $FSXL[time]);
			if ($chk)
			{
				$pos++;
				$godpics++;
			}
			else $badpics++;
		}
		if (filetype($pfad.$file) != "dir") unlink($pfad.$file);
	}
	closedir($verz);

	if ($godpics != 0)
	{
		$index = mysql_query("SELECT COUNT(`id`) AS `value` FROM `$FSXL[tableset]_gallerypics` WHERE `galleryid` = '$_POST[id]' AND `release` < '$FSXL[time]'");
		$numpics = mysql_fetch_assoc($index);
		mysql_query("UPDATE `$FSXL[tableset]_galleries` SET `pics` = '$numpics[value]' WHERE `id` = $_POST[id]");
		$FSXL[content] = '
				<div style="padding:20px; text-align:center;">
					'.$godpics.'/'.($godpics+$badpics).' '.$FS_PHRASES[gallery_pics_autoadded].'<p>
					<form action="" method="get">
						<input type="hidden" name="mod" value="gallery">
						<input type="hidden" name="go" value="pics">
						<input type="hidden" name="galleryid" value="'.$_POST[id].'">
						<input type="submit" class="button" value="'.$FS_PHRASES[global_ok].'">
					</form>
				</div>
		';
	}
	elseif (!$FSXL[content])
	{
		$FSXL[content] = '
				<div style="padding:20px; text-align:center;">
					'.$FS_PHRASES[gallery_pics_nodata].'<p>
					<form action="" method="get">
						<input type="hidden" name="mod" value="gallery">
						<input type="hidden" name="go" value="pics">
						<input type="hidden" name="galleryid" value="'.$_POST[id].'">
						<input type="submit" class="button" value="'.$FS_PHRASES[global_ok].'">
					</form>
				</div>
		';
	}
}

// Bilder editieren
elseif ($_POST[action] == 'edit')
{
	foreach ($_POST[picid] as $picid)
	{
		settype($picid, 'integer');
		settype($_POST[id], 'integer');

		// Löschen
		if ($_POST[delete][$picid])
		{
			$index = mysql_query("SELECT * FROM `$FSXL[tableset]_gallerypics` WHERE `id` = $picid");
			$delimg = mysql_fetch_assoc($index);
			$hash = md5($delimg[date].$delimg[id]);
			mysql_query("DELETE FROM `$FSXL[tableset]_gallerypics` WHERE `id` = $picid");
			if (file_exists('../images/gallery/'.$delimg[galleryid].'/'.$hash.'s.jpg')) unlink('../images/gallery/'.$delimg[galleryid].'/'.$hash.'s.jpg');
			if (file_exists('../images/gallery/'.$delimg[galleryid].'/'.$hash.'.jpg')) unlink('../images/gallery/'.$delimg[galleryid].'/'.$hash.'.jpg');
		}

		// Wenn alle nötigen Daten vorhanden
		elseif ($_POST[position][$picid])
		{
			settype($_POST[position][$picid], 'integer');
			
			// Release Datum
			if (preg_match("/([0-9]{2})\.([0-9]{2})\.([0-9]{4})\s-\s([0-9]{2}):([0-9]{2})/i", $_POST[release][$picid], $match)) {
				$date = mktime($match[4], $match[5], 0, $match[2], $match[1], $match[3]);
				$datestring = ", `release` = '$date'";
			}
			else {
				$datestring = '';
			}

			mysql_query("UPDATE `$FSXL[tableset]_gallerypics` SET
					`titel` = '".$_POST[title][$picid]."',
					`text` = '".$_POST[text][$picid]."',
					`position` = ".$_POST[position][$picid]." $datestring
					WHERE `id` = $picid");
		}
	}

	// Anzahl updaten
	$index = mysql_query("SELECT COUNT(`id`) AS `value` FROM `$FSXL[tableset]_gallerypics` WHERE `galleryid` = '$_POST[id]' AND `release` < '$FSXL[time]'");
	$numpics = mysql_fetch_assoc($index);
	mysql_query("UPDATE `$FSXL[tableset]_galleries` SET `pics` = '$numpics[value]' WHERE `id` = $_POST[id]");

	$FSXL[content] = '
				<div style="padding:20px; text-align:center;">
					'.$FS_PHRASES[gallery_pics_picsedited].'<p>
					<form action="" method="get">
						<input type="hidden" name="mod" value="gallery">
						<input type="hidden" name="go" value="pics">
						<input type="hidden" name="galleryid" value="'.$_POST[id].'">
						<input type="submit" class="button" value="'.$FS_PHRASES[global_ok].'">
					</form>
				</div>
	';
}

// Bild formatieren
elseif ($_POST[action] == 'newpic' && ($_FILES[file][tmp_name] || $_POST[fileurl]) && $_POST[position])
{
	// Datum auswerten
	if ($_POST[day] != '' && $_POST[month] != '' && $_POST[year] != '' && $_POST[hour] != '' && $_POST[min] != '') {
		$date = mktime($_POST[hour], $_POST[min], 0, $_POST[month], $_POST[day], $_POST[year]);
	}
	else {
		$date = time();
	}

	// Hochladen
	if ($_FILES[file][tmp_name])
	{
		$chk = createGalleryPic($_FILES[file], $_POST[id], $_POST[position], $_POST[title], $_POST[text], $date);
	}
	// Von URL laden
	else
	{
		$endung = substr($_POST[fileurl], strlen($_POST[fileurl])-3, 3);
		if ($endung == 'jpg' || $endung == 'gif' || $endung == 'png')
		{
			@copy($_POST[fileurl], 'mod_gallery/tmp/tmp.'.$endung);
			$file_arr = array();
			$file_arr[tmp_name] = 'mod_gallery/tmp/tmp.'.$endung;
			$file_arr[type] = 'image/'.$endung;
			$file_arr[path] = 'mod_gallery/tmp/';
			$chk = createGalleryPic($file_arr, $_POST[id], $_POST[position], $_POST[title], $_POST[text], $date);
			@unlink('mod_gallery/tmp/tmp.'.$endung);
		}
	}

	if ($chk)
	{
		settype($_POST[id], 'integer');
		mysql_query("UPDATE `$FSXL[tableset]_galleries` SET `pics` = `pics` + 1 WHERE `id` = $_POST[id]");
		$FSXL[content] = '
				<div style="padding:20px; text-align:center;">
					'.$FS_PHRASES[gallery_pics_picadded].'<p>
					<form action="" method="get">
						<input type="hidden" name="mod" value="gallery">
						<input type="hidden" name="go" value="pics">
						<input type="hidden" name="galleryid" value="'.$_POST[id].'">
						<input type="submit" class="button" value="'.$FS_PHRASES[global_ok].'">
					</form>
				</div>
		';
	}
	else
	{
		$FSXL[content] = '
				<div style="padding:20px; text-align:center;">
					'.$FS_PHRASES[gallery_pics_nopic].'<p>
					<form action="" method="get">
						<input type="hidden" name="mod" value="gallery">
						<input type="hidden" name="go" value="pics">
						<input type="hidden" name="galleryid" value="'.$_POST[id].'">
						<input type="submit" class="button" value="'.$FS_PHRASES[global_ok].'">
					</form>
				</div>
		';
	}
}

// Bilder hinzufügen/bearbeiten
elseif ($_GET[galleryid])
{
	// Gallerienamen lesen
	settype($_GET[galleryid], 'integer');
	$index = mysql_query("SELECT `name` FROM `$FSXL[tableset]_galleries` WHERE `id` = $_GET[galleryid]");
	$gallery = mysql_fetch_assoc($index);
	$FSXL[title] = $FS_PHRASES[gallery_pics_title] . ' ('.$gallery[name].')';

	// Nächste Position ermitteln
	$index = mysql_query("SELECT `position` FROM `$FSXL[tableset]_gallerypics` WHERE `galleryid` = $_GET[galleryid] ORDER BY `position` DESC LIMIT 1");
	if (mysql_num_rows($index) == 0)
	{
		$pos = 1;
	}
	else
	{
		$pos = mysql_result($index, 0, 'position') + 1;
	}

	$FSXL[content] = '
				<form action="?mod=gallery&go=pics" method="post" enctype="multipart/form-data">
				<input type="hidden" name="action" value="newpic">
				<input type="hidden" name="id" value="'.$_GET[galleryid].'">
				<table border="0" cellpadding="2" cellspacing="1" width="90%" style="margin:0px auto;">
					<tr>
						<td colspan="2">
							<span style="font-size:12pt"><b>'.$FS_PHRASES[gallery_pics_newpic].'</b></span><hr>
						</td>
					</tr>
					<tr>
						<td width="250"><b>'.$FS_PHRASES[gallery_pics_file].':</b><br>'.$FS_PHRASES[gallery_pics_file_sub].'</td>
						<td><input type="file" class="textinput" name="file" style="width:300px;"></td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[gallery_pics_fileurl].':</b><br>'.$FS_PHRASES[gallery_pics_fileurl_sub].'</td>
						<td><input class="textinput" name="fileurl" style="width:300px;"></td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[gallery_pics_pictitle].':</b></td>
						<td><input class="textinput" name="title" style="width:300px;"></td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[gallery_galleries_date].':</b><br><span class="small">'.$FS_PHRASES[galery_add_dateformat].'</span></td>
						<td valign="top">
							<input class="textinput" name="day" style="width:20px;" value="'.date("d").'">
							<input class="textinput" name="month" style="width:20px;" value="'.date("m").'">
							<input class="textinput" name="year" style="width:40px;" value="'.date("Y").'"> -
							<input class="textinput" name="hour" style="width:20px;" value="'.date("H").'">
							<input class="textinput" name="min" style="width:20px;" value="'.date("i").'">
						</td>
					</tr>
					<tr>
						<td valign="top"><b>'.$FS_PHRASES[gallery_pics_text].':</b></td>
						<td><textarea class="textinput" name="text" style="width:300px; height:100px;"></textarea></td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[gallery_pics_position].':</b><br>'.$FS_PHRASES[gallery_pics_position_sub].'</td>
						<td><input class="textinput" name="position" style="width:50px;" value="'.$pos.'"></td>
					</tr>
					<tr>
						<td colspan="2" align="right">
							<input type="submit" class="button" value="'.$FS_PHRASES[global_send].'">
						</td>
					</tr>
				</table>
				</form>


				<form action="?mod=gallery&go=pics" method="post">
				<input type="hidden" name="action" value="edit">
				<input type="hidden" name="id" value="'.$_GET[galleryid].'">
				<table border="0" cellpadding="2" cellspacing="1" width="90%" style="margin:0px auto;">
					<tr>
						<td colspan="2">
							<span style="font-size:12pt"><b>'.$FS_PHRASES[gallery_pics_editpics].'</b></span><hr>
						</td>
					</tr>
	';

	// Bilder auflisten
	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_gallerypics` WHERE `galleryid` = $_GET[galleryid] ORDER BY `position`");
	while ($pic = mysql_fetch_assoc($index))
	{
		$hash = md5($pic[date].$pic[id]);
		$FSXL[content] .= '
					<tr>
						<td width="170" valign="top">
							<a href="../index.php?section=gallery&detail='.$pic[id].'" target="_blank">
							<img border="0" width="160" src="../images/gallery/'.$pic[galleryid].'/'.$hash.'s.jpg" alt="">
							</a>
						</td>
						<td valign="top">
							<input type="hidden" name="picid['.$pic[id].']" value="'.$pic[id].'">
							<table border="0" cellpadding="0" cellspacing="0" align="center" width="100%">
								<tr>
									<td width="100"><b>'.$FS_PHRASES[gallery_pics_pictitle].':</b></td>
									<td><input class="textinput" name="title['.$pic[id].']" style="width:250px;" value="'.$pic[titel].'"></td>
								</tr>
								<tr>
									<td valign="top"><b>'.$FS_PHRASES[gallery_pics_text].':</b></td>
									<td><textarea class="textinput" name="text['.$pic[id].']" style="width:250px; height:60px;">'.$pic[text].'</textarea></td>
								</tr>
								<tr>
									<td><b>'.$FS_PHRASES[gallery_pics_position].':</b></td>
									<td>
										<div style="float:right;">
											<b>'.$FS_PHRASES[gallery_galleries_delete].':</b>
											<input type="checkbox" name="delete['.$pic[id].']">
										</div>
										<input class="textinput" name="position['.$pic[id].']" style="width:30px;" value="'.$pic[position].'">
										<input class="textinput" name="release['.$pic[id].']" style="width:100px;" value="'.date("d.m.Y - H:i", $pic[release]).'">
									</td>
								</tr>
							</table>
						</td>
					</tr>
		';
	}

	$FSXL[content] .= '
					<tr>
						<td colspan="2" align="right">
							<input type="submit" class="button" value="'.$FS_PHRASES[global_send].'">
						</td>
					</tr>
				</table>
				</form>


				<!--form action="?mod=gallery&go=pics" method="post">
				<input type="hidden" name="action" value="autofill">
				<input type="hidden" name="id" value="'.$_GET[galleryid].'">
				<table border="0" cellpadding="2" cellspacing="1" width="90%" style="margin:0px auto;">
					<tr>
						<td colspan="2">
							<span style="font-size:12pt"><b>'.$FS_PHRASES[gallery_pics_autofill].'</b></span><hr>
						</td>
					</tr>
					<tr>
						<td width="250">
							<b>'.$FS_PHRASES[gallery_pics_url].':</b><br>'.$FS_PHRASES[gallery_pics_url_sub].'
						</td>
						<td align="right">
							<input name="autofill" class="textinput" style="width:300px;">
						</td>
					</tr>
					<tr>
						<td colspan="2" align="right">
							<input type="submit" class="button" value="'.$FS_PHRASES[global_send].'">
						</td>
					</tr>
				</table>
				</form-->
	';
}

// Gallerie Übersicht
else
{
	$FSXL[content] .= '
				<table border="0" cellpadding="2" cellspacing="1" width="90%" style="margin:0px auto;">
					<tr>
						<td>
							<span style="font-size:12pt"><b>'.$FS_PHRASES[gallery_pics_choosegallery].'</b></span><hr>
						</td>
					</tr>
					<tr>
						<td>
							<table border="0" cellpadding="2" cellspacing="1" width="100%">
								<tr>
									<td class="alt0"><b>'.$FS_PHRASES[gallery_galleries_name].'</b></td>
									<td class="alt0" align="center"><b>'.$FS_PHRASES[gallery_galleries_date].'</b></td>
									<td class="alt0" align="center"><b>'.$FS_PHRASES[gallery_galleries_pics].'</b></td>
								</tr>
	';

	// Gallerien auflisten
	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_galleries` ORDER BY `cat`, `datum` DESC");
	$i=0;
	while ($gallery = mysql_fetch_assoc($index))
	{
		$i++;
		if ($currentcat != $gallery[cat])
		{
			$currentcat = $gallery[cat];
			if($currentcat == 0)
			{
				$cat[name] = $FS_PHRASES[gallery_galleries_nocat];
			}
			else
			{
				$index2 = mysql_query("SELECT `name` FROM `$FSXL[tableset]_gallery_cat` WHERE `id` = $gallery[cat]");
				$cat = mysql_fetch_assoc($index2);
			}
			$FSXL[content] .= '
					<tr>
						<td class="alt'.($i%2==0?1:2).'" colspan="3"><b>'.$cat[name].'</b></td>
					</tr>
			';
			$i++;
		}
		$FSXL[content] .= '
					<tr>
						<td class="alt'.($i%2==0?1:2).'" style="padding-left:20px;"><a href="?mod=gallery&go=pics&galleryid='.$gallery[id].'">'.$gallery[name].'</a></td>
						<td class="alt'.($i%2==0?1:2).'" align="center">'.date("d.m.Y H:i", $gallery[datum]).'</td>
						<td class="alt'.($i%2==0?1:2).'" align="center">'.$gallery[pics].'</td>
					</tr>
		';
	}

	$FSXL[content] .= '
							</table>
						</td>
					</tr>
				</table>
	';
}
?>