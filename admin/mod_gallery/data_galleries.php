<?php

$FSXL[title] = $FS_PHRASES[gallery_galleries_title];

// Gallerie editieren/löschen
if ($_POST[action] == 'editgallery' && $_POST[name] && $_POST[cols] && $_POST[day] != '' && $_POST[month] != '' && $_POST[year] != '' && $_POST[hour] != '' && $_POST[min] != '')
{
	settype($_POST[id], 'integer');

	// Löschen
	if ($_POST[delete])
	{
		// Bilder löschen
		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_gallerypics` WHERE `galleryid` = $_POST[id]");
		if (mysql_num_rows($index) > 0)
		{
			while($pic = mysql_fetch_assoc($index))
			{
				$hash = md5($pic[date].$pic[id]);
				unlink('../images/gallery/'.$_POST[id].'/'.$hash.'.jpg');
				unlink('../images/gallery/'.$_POST[id].'/'.$hash.'s.jpg');
			}
			mysql_query("DELETE FROM `$FSXL[tableset]_gallerypics` WHERE `galleryid` = $_POST[id]");
		}
		mysql_query("DELETE FROM `$FSXL[tableset]_galleries` WHERE `id` = $_POST[id]");
		mysql_query("DELETE FROM `$FSXL[tableset]_gallery_potm` WHERE `gallery` = $_POST[id]");
		rmdir('../images/gallery/'.$_POST[id]);

		$FSXL[content] = '
				<div style="padding:20px; text-align:center;">
					'.$FS_PHRASES[gallery_galleries_deleted].'<p>
					<form action="" method="get">
						<input type="hidden" name="mod" value="gallery">
						<input type="hidden" name="go" value="galleries">
						<input type="submit" class="button" value="'.$FS_PHRASES[global_ok].'">
					</form>
				</div>
		';
	}

	// Gallerie bearbeiten
	else
	{
		$date = mktime($_POST[hour], $_POST[min], 0, $_POST[month], $_POST[day], $_POST[year]);
		settype($_POST[cols], 'integer');
		settype($_POST[type], 'integer');
		settype($_POST[cat], 'integer');
		settype($_POST[zone], 'integer');
		settype($_POST[age], 'integer');
		$regonly = $_POST[regonly] ? 1 : 0;
		$hidden = $_POST[hidden] ? 1 : 0;

		mysql_query("UPDATE `$FSXL[tableset]_galleries` SET `name` = '$_POST[name]', 
									`text` = '$_POST[text]', 
									`datum` = '$date', 
									`color` = '$_POST[color]', 
									`cols` = '$_POST[cols]', 
									`type` = '$_POST[type]', 
									`cat` = '$_POST[cat]',
									`zoneid` = '$_POST[zone]',
									`regonly` = '$regonly',
									`age` = '$_POST[age]',
									`hidden` = '$hidden'
									WHERE `id` = '$_POST[id]'");

		$FSXL[content] = '
				<div style="padding:20px; text-align:center;">
					'.$FS_PHRASES[gallery_galleries_edited].'<p>
					<form action="" method="get">
						<input type="hidden" name="mod" value="gallery">
						<input type="hidden" name="go" value="galleries">
						<input type="submit" class="button" value="'.$FS_PHRASES[global_ok].'">
					</form>
				</div>
		';
	}
}

// Gallerie bearbeiten
elseif ($_GET[edit])
{
	settype($_GET[edit], 'integer');
	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_galleries` WHERE `id` = $_GET[edit]");
	$gallery = mysql_fetch_assoc($index);

	$FSXL[content] .= '
				<div>
				<form action="?mod=gallery&go=galleries" method="post" name="galleryform" onSubmit="return chkGalleryEditForm()">
				<input type="hidden" name="action" value="editgallery">
				<input type="hidden" name="id" value="'.$gallery[id].'">
				<table border="0" cellpadding="2" cellspacing="1" width="90%" style="margin:0px auto;">
					<tr>
						<td width="250"><b>'.$FS_PHRASES[gallery_galleries_name].':</b></td>
						<td><input class="textinput" name="name" style="width:300px;" value="'.$gallery[name].'"></td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[gallery_galleries_cat].':</b></td>
						<td>
							<select class="textinput" name="cat" style="width:300px;">
								<option value="0">'.$FS_PHRASES[gallery_galleries_nocat].'</option>
								<option value="0">-----------------------</option>
	';
	
	$index = mysql_query("SELECT `id`, `name` FROM `$FSXL[tableset]_gallery_cat` ORDER BY `name`");
	while ($cat = mysql_fetch_assoc($index))
	{
		$FSXL[content] .= '<option value="'.$cat[id].'" '.($cat[id] == $gallery[cat] ? "selected" : "").'>'.$cat[name].'</option>';
	}

	$FSXL[content] .= '
							</select>
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[gallery_add_zone].':</b><br>'.$FS_PHRASES[gallery_add_zone_sub].'</td>
						<td>
							<select name="zone" class="textinput" style="width:300px;">
								<option value="0" style="font-style:italic;">'.$FS_PHRASES[gallery_add_nozone].'</option>
	';

	// Zonen auslesen
	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_zones` ORDER BY `name`");
	while ($zone = mysql_fetch_assoc($index))
	{
		$FSXL[content] .= '
								<option value="'.$zone[id].'" '.($zone[id] == $gallery[zoneid] ? "selected" : "").'>'.$zone[name].'</option>
		';
	}

	$FSXL[content] .= '
							</select>
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[gallery_galleries_date].':</b><br><span class="small">'.$FS_PHRASES[galery_add_dateformat].'</span></td>
						<td valign="top">
							<input class="textinput" name="day" style="width:20px;" value="'.date("d", $gallery[datum]).'">
							<input class="textinput" name="month" style="width:20px;" value="'.date("m", $gallery[datum]).'">
							<input class="textinput" name="year" style="width:40px;" value="'.date("Y", $gallery[datum]).'"> -
							<input class="textinput" name="hour" style="width:20px;" value="'.date("H", $gallery[datum]).'">
							<input class="textinput" name="min" style="width:20px;" value="'.date("i", $gallery[datum]).'">
						</td>
					</tr>
					<tr>
						<td valign="top"><b>'.$FS_PHRASES[gallery_galleries_description].':</b></td>
						<td><textarea class="textinput" name="text" style="width:300px; height:150px;">'.$gallery[text].'</textarea></td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[gallery_galleries_thumbsize].':</b></td>
						<td>
							'.$gallery[thumbx].' x '.$gallery[thumby].' '.$FS_PHRASES[gallery_galleries_pixel].'
						</td>
					</tr>
					<tr>
						<td>
							<b>'.$FS_PHRASES[gallery_galleries_color].':</b><br>
							('.$FS_PHRASES[gallery_galleries_hexcode].')
						</td>
						<td valign="top">
							<div id="colorsample" style="width:14px; height:14px; margin-right:10px; margin-top:3px; background-color:#'.$gallery[color].'; float:left;"></div>
							<input class="textinput" name="color" id="color" style="width:50px;" value="'.$gallery[color].'" onkeyup="insertColor(this.value)">
	';

	if ($FSXL[config][gallery_colors])
	{
		$FSXL[content] .= '
							<select class="textinput" onchange="insertColor(this.value)">
								<option>'.$FS_PHRASES[gallery_galleries_selectcolor].'</option>
								<option>-------------------------</option>
		';

		$FSXL[config][gallery_colors] = preg_replace("/(\n\r|\r\n|\r)/is", "\n", $FSXL[config][gallery_colors]);
		$colors = explode("\n", $FSXL[config][gallery_colors]);
		foreach($colors AS $color)
		{
			$data = explode(",", $color);
			if (preg_match("/([a-fA-f0-9]{6})/i", $data[1]))
			{
				$FSXL[content] .= '
								<option value="'.$data[1].'" '.($data[1]==$gallery[color]?"selected":"").'>'.$data[0].'</option>
				';
			}
		}

		$FSXL[content] .= '
							</select>
		';
	}
	$FSXL[content] .= '
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[gallery_galleries_cols].':</b><br></td>
						<td><input class="textinput" name="cols" style="width:50px;" value="'.$gallery[cols].'"></td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[gallery_galleries_type].':</b></td>
						<td>
							'.$FS_PHRASES[gallery_galleries_asc].'
							<input type="radio" name="type" value="1" '.($gallery[type] == 1 ? "checked" : "").'>
							'.$FS_PHRASES[gallery_galleries_desc].'
							<input type="radio" name="type" value="2" '.($gallery[type] == 2 ? "checked" : "").'>
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[gallery_galleries_regonly].':</b></td>
						<td><input type="checkbox" name="regonly" '.($gallery[regonly] ? "checked" : "").'></td>
					</tr>
					<tr>
						<td>
							<b>'.$FS_PHRASES[gallery_add_hidden].':</b><br>
							'.$FS_PHRASES[gallery_add_hidden_sub].'
						</td>
						<td><input type="checkbox" name="hidden" '.($gallery[hidden] ? "checked" : "").'></td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[gallery_add_age].':</b></td>
						<td>
							<input name="age" class="textinput" style="width:20px;" value="'.$gallery[age].'">
							'.$FS_PHRASES[gallery_add_years].'
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[gallery_galleries_delete].':</b></td>
						<td><input type="checkbox" id="delete" name="delete" onClick="delMessage(\''.$FS_PHRASES[gallery_edit_delmessage].'\');"></td>
					</tr>
					<tr>
						<td colspan="2" align="right">
							<input type="submit" class="button" value="'.$FS_PHRASES[global_send].'">
						</td>
					</tr>
				</table>
				</form>
				</div>
	';
}

// Neue Gallerie erstellen
elseif ($_POST[action] == 'newgallery' && $_POST[name] && $_POST[thumbx] && $_POST[thumby] && $_POST[color] && $_POST[cols])
{
	// Datum auswerten
	if ($_POST[day] != '' && $_POST[month] != '' && $_POST[year] != '' && $_POST[hour] != '' && $_POST[min] != '')
	{
		$date = mktime($_POST[hour], $_POST[min], 0, $_POST[month], $_POST[day], $_POST[year]);
	}
	else
	{
		$date = time();
	}
	settype($_POST[thumbx], 'integer');
	settype($_POST[thumby], 'integer');
	settype($_POST[cols], 'integer');
	settype($_POST[type], 'integer');
	settype($_POST[cat], 'integer');
	settype($_POST[zone], 'integer');
	settype($_POST[age], 'integer');
	$regonly = $_POST[regonly] ? 1 : 0;
	$hidden = $_POST[hidden] ? 1 : 0;

	$index = mysql_query("INSERT INTO `$FSXL[tableset]_galleries` (`id`, `name`, `text`, `datum`, `thumbx`, `thumby`, `color`, `cols`, `type`, `cat`, `zoneid`, `regonly`, `pics`, `age`, `hidden`)
				VALUES (NULL, '$_POST[name]', '$_POST[text]', $date, $_POST[thumbx], $_POST[thumby], '$_POST[color]', $_POST[cols], $_POST[type], $_POST[cat], $_POST[zone], '$regonly', 0, '$_POST[age]', '$hidden')");
	
	// Ordner anlegen
	$id = mysql_insert_id();
	mkdir('../images/gallery/'.$id, 0777);

	$FSXL[content] = '
				<div style="padding:20px; text-align:center;">
					'.$FS_PHRASES[gallery_galleries_added].'<p>
					<form action="" method="get">
						<input type="hidden" name="mod" value="gallery">
						<input type="hidden" name="go" value="galleries">
						<input type="submit" class="button" value="'.$FS_PHRASES[global_ok].'">
					</form>
				</div>
	';
}

// Übersicht
else
{
	$FSXL[content] .= '
				<form action="?mod=gallery&go=galleries" method="post" name="galleryform" onSubmit="return chkGalleryAddForm()">
				<input type="hidden" name="action" value="newgallery">
				<table border="0" cellpadding="2" cellspacing="1" width="90%" style="margin:0px auto;">
					<tr>
						<td colspan="2">
							<span style="font-size:12pt"><b>'.$FS_PHRASES[gallery_galleries_newgallery].'</b></span><hr>
						</td>
					</tr>
					<tr>
						<td width="250"><b>'.$FS_PHRASES[gallery_galleries_name].':</b></td>
						<td><input class="textinput" name="name" style="width:300px;"></td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[gallery_galleries_cat].':</b></td>
						<td>
							<select class="textinput" name="cat" style="width:300px;">
								<option value="0">'.$FS_PHRASES[gallery_galleries_nocat].'</option>
								<option value="0">-----------------------</option>
	';
	
	$index = mysql_query("SELECT `id`, `name` FROM `$FSXL[tableset]_gallery_cat` ORDER BY `name`");
	while ($cat = mysql_fetch_assoc($index))
	{
		$FSXL[content] .= '<option value="'.$cat[id].'">'.$cat[name].'</option>';
	}

	$FSXL[content] .= '
							</select>
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[gallery_add_zone].':</b><br>'.$FS_PHRASES[gallery_add_zone_sub].'</td>
						<td>
							<select name="zone" class="textinput" style="width:300px;">
								<option value="0" style="font-style:italic;">'.$FS_PHRASES[gallery_add_nozone].'</option>
	';

	// Zonen auslesen
	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_zones` ORDER BY `name`");
	while ($zone = mysql_fetch_assoc($index))
	{
		$FSXL[content] .= '
								<option value="'.$zone[id].'">'.$zone[name].'</option>
		';
	}

	$FSXL[content] .= '
							</select>
						</td>
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
						<td valign="top"><b>'.$FS_PHRASES[gallery_galleries_description].':</b></td>
						<td><textarea class="textinput" name="text" style="width:300px; height:150px;"></textarea></td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[gallery_galleries_thumbsize].':</b></td>
						<td>
							<input class="textinput" name="thumbx" style="width:30px;" value="'.$FSXL[config][gallery_thumbx].'">
							x
							<input class="textinput" name="thumby" style="width:30px;" value="'.$FSXL[config][gallery_thumby].'">
							'.$FS_PHRASES[gallery_galleries_pixel].'
						</td>
					</tr>
					<tr>
						<td>
							<b>'.$FS_PHRASES[gallery_galleries_color].':</b><br>
							('.$FS_PHRASES[gallery_galleries_hexcode].')
						</td>
						<td valign="top">
							<div id="colorsample" style="width:14px; height:14px; margin-right:10px; margin-top:3px; background-color:transparent; float:left;"></div>
							<input class="textinput" name="color" id="color" style="width:50px;" onkeyup="insertColor(this.value)">
	';

	if ($FSXL[config][gallery_colors])
	{
		$FSXL[content] .= '
							<select class="textinput" onchange="insertColor(this.value)">
								<option>'.$FS_PHRASES[gallery_galleries_selectcolor].'</option>
								<option>-------------------------</option>
		';

		$FSXL[config][gallery_colors] = preg_replace("/(\n\r|\r\n|\r)/is", "\n", $FSXL[config][gallery_colors]);
		$colors = explode("\n", $FSXL[config][gallery_colors]);
		foreach($colors AS $color)
		{
			$data = explode(",", $color);
			if (preg_match("/([a-fA-f0-9]{6})/i", $data[1]))
			{
				$FSXL[content] .= '
								<option value="'.$data[1].'">'.$data[0].'</option>
				';
			}
		}

		$FSXL[content] .= '
							</select>
		';
	}
	$FSXL[content] .= '
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[gallery_galleries_cols].':</b></td>
						<td>
							<input class="textinput" name="cols" style="width:50px;" value="'.$FSXL[config][gallery_cols].'">
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[gallery_galleries_type].':</b></td>
						<td>
							'.$FS_PHRASES[gallery_galleries_asc].'
							<input type="radio" name="type" value="1" checked>
							'.$FS_PHRASES[gallery_galleries_desc].'
							<input type="radio" name="type" value="2">
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[gallery_galleries_regonly].':</b></td>
						<td><input type="checkbox" name="regonly"></td>
					</tr>
					<tr>
						<td>
							<b>'.$FS_PHRASES[gallery_add_hidden].':</b><br>
							'.$FS_PHRASES[gallery_add_hidden_sub].'
						</td>
						<td><input type="checkbox" name="hidden"></td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[gallery_add_age].':</b></td>
						<td>
							<input name="age" class="textinput" style="width:20px;" value="0">
							'.$FS_PHRASES[gallery_add_years].'
						</td>
					</tr>
					<tr>
						<td colspan="2" align="right">
							<input type="submit" class="button" value="'.$FS_PHRASES[global_send].'">
						</td>
					</tr>
				</table>
				</form>


				<table border="0" cellpadding="2" cellspacing="1" width="90%" style="margin:0px auto;">
					<tr>
						<td>
							<span style="font-size:12pt"><b>'.$FS_PHRASES[gallery_galleries_editgallery].'</b></span><hr>
						</td>
					</tr>
					<tr>
						<td>
							<table border="0" cellpadding="2" cellspacing="1" width="100%">
								<tr>
									<td class="alt0"><b>'.$FS_PHRASES[gallery_galleries_name].'</b></td>
									<td class="alt0" align="center"><b>'.$FS_PHRASES[gallery_galleries_date].'</b></td>
									<td class="alt0" align="center"><b>'.$FS_PHRASES[gallery_galleries_pics].'</b></td>
									<td class="alt0" align="center"><b>'.$FS_PHRASES[gallery_galleries_fscode].'</b></td>
									<td class="alt0" align="center"><b>'.$FS_PHRASES[gallery_galleries_link].'</b></td>
								</tr>
	';

	// Gallerien auflisten
	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_galleries` ORDER BY `cat`, `datum` DESC");
	$currentcat = '';
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
						<td class="alt'.($i%2==0?1:2).'" colspan="5"><b>'.$cat[name].'</b></td>
					</tr>
			';
			$i++;
		}

		$FSXL[content] .= '
					<tr>
						<td class="alt'.($i%2==0?1:2).'" style="padding-left:20px;"><a href="?mod=gallery&go=galleries&edit='.$gallery[id].'">'.$gallery[name].'</a></td>
						<td class="alt'.($i%2==0?1:2).'" align="center">'.date("d.m.Y H:i", $gallery[datum]).'</td>
						<td class="alt'.($i%2==0?1:2).'" align="center">'.$gallery[pics].'</td>
						<td class="alt'.($i%2==0?1:2).'" align="center">[gallery]'.$gallery[id].'[/gallery]</td>
						<td class="alt'.($i%2==0?1:2).'" align="center"><a href="../index.php?section=gallery&id='.$gallery[id].'" target="_blank"><img border="0" src="images/'.$FSXL[style].'_link.gif" alt=""></a></td>
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