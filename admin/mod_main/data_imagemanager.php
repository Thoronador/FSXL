<?php

$img_types = array('JPG' => 1, 'PNG' => 2, 'GIF' => 3, 'BMP' => 4);

// Ordner umbenennen
if ($_POST[action] == 'renamefolder' && $_POST[name])
{
	settype($_POST[folderid], 'integer');
	$index = @mysql_query("UPDATE `$FSXL[tableset]_imgcat` SET `name` = '$_POST[name]' WHERE `id` = $_POST[folderid]");
	if (!$index)
	{
		$errmsg = '<br><b><i>'.$FS_PHRASES[main_imgmanager_renamefailed].'.</i></b>';
	}
}

// Ordner erstellen
if ($_POST[action] == 'addfolder' && $_POST[name])
{
	$index = @mysql_query("INSERT INTO `$FSXL[tableset]_imgcat` (`id`, `name`, `pics`) VALUES (NULL, '$_POST[name]', 0)");
	if (!$index)
	{
		$errmsg = '<br><b><i>'.$FS_PHRASES[main_imgmanager_createfailed].'.</i></b>';
	}
}

// Bilder verschieben / löschen
if ($_POST[action] == 'subfiles')
{
	settype($_POST[folderid], 'integer');
	if ($_POST[delete])
	{
		foreach($_POST[pic] AS $key => $value)
		{
			settype($key, 'integer');
			mysql_query("DELETE FROM `$FSXL[tableset]_images` WHERE `id` = $key");
			mysql_query("UPDATE `$FSXL[tableset]_imgcat` SET `pics` = `pics` - 1 WHERE `id` = $_POST[folderid]");
			@unlink('../images/imgmanager/'.$key.'s.jpg');
			@unlink('../images/imgmanager/'.$key.'s.png');
			if (file_exists('../images/imgmanager/'.$key.'.png'))
			{
				unlink('../images/imgmanager/'.$key.'.png');
			}
			elseif (file_exists('../images/imgmanager/'.$key.'.gif'))
			{
				unlink('../images/imgmanager/'.$key.'.gif');
			}
			else
			{
				unlink('../images/imgmanager/'.$key.'.jpg');
			}
		}
	}
	elseif($_POST[tofolder])
	{
		// Verschieben
		settype($_POST[tofolder], 'integer');
		if ($_POST[pic])
		{
			foreach($_POST[pic] AS $key => $value)
			{
				settype($key, 'integer');
				mysql_query("UPDATE `$FSXL[tableset]_images` SET `cat` = $_POST[tofolder], `lastmod` = $FSXL[time] WHERE `id` = $key");
				mysql_query("UPDATE `$FSXL[tableset]_imgcat` SET `pics` = `pics` - 1 WHERE `id` = $_POST[folderid]");
				mysql_query("UPDATE `$FSXL[tableset]_imgcat` SET `pics` = `pics` + 1 WHERE `id` = $_POST[tofolder]");
			}
		}
	}
	else
	{
		// Name ändern
		if ($_POST[name])
		{
			foreach($_POST[name] AS $id => $name)
			{
				mysql_query("UPDATE `$FSXL[tableset]_images` SET `title` = '$name' WHERE `id` = $id");
			}
		}
		// Bild ersetzen
		if ($_FILES)
		{
			foreach($_FILES AS $key => $source)
			{
				$id = substr($key, 7);
				settype($id, 'integer');
				$img = new imgConvert();
				if ($img->readIMG($source))
				{
					$imgdata = $img->saveIMG('../images/imgmanager/', $id, 'COPY');
					$imgdata[3] = $img_types[$imgdata[3]];

					$img->scaleIMG(128, 128, 'RESIZE', '000000', true);
					$img->saveIMG('../images/imgmanager/', $id.'s', 'png');

					$source[name] = mysql_real_escape_string($source[name]);
					mysql_query("UPDATE `$FSXL[tableset]_images` SET `height` = $imgdata[1], `width` = $imgdata[0], `size` = $imgdata[2], `type` = $imgdata[3], `lastmod` = $FSXL[time], `filename` = '$source[name]' WHERE `id` = $id");
				}
			}
		}
	}
	reloadPage('?mod=main&go=imagemanager&folder='.$_GET[folder]);
}

// Ordner löschen
if ($_POST[action] == 'delfolder' && $_POST[folderid] != 1)
{
	settype($_POST[folderid], 'integer');
	settype($_POST[newfolder], 'integer');
	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_imgcat` WHERE `id` = $_POST[folderid]");
	$cat = mysql_fetch_assoc($index);
	mysql_query("UPDATE `$FSXL[tableset]_images` SET `cat` = $_POST[newfolder] WHERE `cat` = $_POST[folderid]");
	mysql_query("UPDATE `$FSXL[tableset]_imgcat` SET `pics` = `pics` + $cat[pics] WHERE `id` = $_POST[newfolder]");
	mysql_query("DELETE FROM `$FSXL[tableset]_imgcat` WHERE `id` = $_POST[folderid]");
}

// Bild hochladen
if ($_POST[action] == 'addimage' && $_FILES[img])
{
	settype($_POST[folder], 'integer');
	$_FILES[img]['name'] = mysql_real_escape_string($_FILES[img]['name']);
	$index = @mysql_query("INSERT INTO `$FSXL[tableset]_images` (`id`, `cat`, `title`, `filename`, `height`, `width`, `size`, `type`, `date`, `lastmod`, `autor`) 
				VALUES (NULL, $_POST[folder], '$_POST[title]', '".$_FILES[img]['name']."', 0, 0, 0, 0, $FSXL[time], $FSXL[time], ".$_SESSION[user]->userid.")");
	if ($index)
	{
		$id = mysql_insert_id();

		$img = new imgConvert();
		if ($img->readIMG($_FILES[img]))
		{
			settype($_POST[width], 'integer');
			settype($_POST[height], 'integer');
			
			// Bild neu skalieren
			if ($_POST[width] > 0 && $_POST[height] > 0)
			{
				$img->scaleIMG($_POST[width], $_POST[height], 'RESIZE', '000000', true);
				$imgdata = $img->saveIMG('../images/imgmanager/', $id);
			}
			// Bild kopieren
			else {
				$imgdata = $img->saveIMG('../images/imgmanager/', $id, 'COPY');
			}
			$imgdata[3] = $img_types[$imgdata[3]];
			mysql_query("UPDATE `$FSXL[tableset]_images` SET `height` = $imgdata[1], `width` = $imgdata[0], `size` = $imgdata[2], `type` = $imgdata[3] WHERE `id` = $id");
			mysql_query("UPDATE `$FSXL[tableset]_imgcat` SET `pics` = `pics` + 1 WHERE `id` = $_POST[folder]");

			$img->scaleIMG(128, 128, 'RESIZE', '000000', true);
			$img->saveIMG('../images/imgmanager/', $id.'s', 'png');
		}
		else
		{
			mysql_query("DELETE FROM `$FSXL[tableset]_images` WHERE `id` = $id");
			$errmsg = '<br><b><i>'.$FS_PHRASES[main_imgmanager_wrongformat].'.</i></b>';
		}
	}
	else
	{
		$errmsg = '<br><b><i>'.$FS_PHRASES[main_imgmanager_uploadfailed].'</i></b>';
	}

	reloadPage('?mod=main&go=imagemanager&folder='.$_GET[folder]);
}


$FSXL[title] = $FS_PHRASES[main_imgmanager_title];

$FSXL[content] = '';
if ($FSXL[config][infotext])
{
	$FSXL[content] .= $FS_PHRASES[main_imgmanager_infotext] . '<p>';
}

$FSXL[content] .= '
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td valign="top" width="50%">
				<form action="?mod=main&go=imagemanager'.($_GET[folder] ? "&folder=$_GET[folder]" : "").'" method="post"  enctype="multipart/form-data">
				<input type="hidden" name="action" value="addimage">
				<b>'.$FS_PHRASES[main_imgmanager_uploadpic].':</b><br>
				<table border="0" cellpadding="2" cellspacing="0" width="100%">
					<tr>
						<td width="80">'.$FS_PHRASES[main_imgmanager_file].':</td>
						<td align="left"><input type="file" name="img" class="textinput" style="width:230px;"></td>
					</tr>
					<tr>
						<td>'.$FS_PHRASES[main_imgmanager_folder].':</td>
						<td align="left">
							<select name="folder" class="textinput" style="width:230px;">
';

$index = mysql_query("SELECT * FROM `$FSXL[tableset]_imgcat` ORDER BY `name`");
while ($folder = mysql_fetch_assoc($index))
{
	$FSXL[content] .= '<option value="'.$folder[id].'" '.($folder[id] == $_GET[folder] ? "selected" : "").'>'.$folder[name].'</option>';
}

$FSXL[content] .= '
							</select>
						</td>
					</tr>
					<tr>
						<td>'.$FS_PHRASES[main_imgmanager_name].'*:</td>
						<td align="left"><input name="title" class="textinput" style="width:224px;"></td>
					</tr>
					<tr>
						<td>'.$FS_PHRASES[main_imgmanager_newsize].'*:</td>
						<td align="left">
							<input style="float:right;" type="submit" value="'.$FS_PHRASES[main_imgmanager_upload].'" class="button">
							<input name="width" class="textinput" style="width:30px;"> x
							<input name="height" class="textinput" style="width:30px;">
						</td>
					</tr>
				</table>
				</form>
			</td>
			<td valign="top" width="50%" style="padding-left:20px;">								
				<form action="?mod=main&go=imagemanager" method="post">
				<input type="hidden" name="action" value="addfolder">
				<b>'.$FS_PHRASES[main_imgmanager_newfolder].':</b>
				<table border="0" cellpadding="2" cellspacing="0" width="100%">
					<tr>
						<td>'.$FS_PHRASES[main_imgmanager_name].':</td>
						<td align="right"><input class="textinput" name="name" style="width:210px;"></td>
					</tr>
					<tr>
						<td align="right" colspan="2"><input type="submit" value="'.$FS_PHRASES[main_imgmanager_create].'" class="button"></td>
					</tr>
				</table>
				</form>
			</td>
		</tr>
	</table>
	'.$errmsg.'
	<hr>
';

// Ordner
if ($_GET[folder])
{
	settype($_GET[folder], 'integer');
	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_imgcat` WHERE `id` = $_GET[folder]");
	if (mysql_num_rows($index) > 0)
	{
		$folder = mysql_fetch_assoc($index);
		if ($folder[pics] > 0 && $folder[id] == 1) $img = $FSXL[style].'_folder_default_full.png';
		if ($folder[pics] == 0 && $folder[id] == 1) $img = $FSXL[style].'_folder_default.png';
		if ($folder[pics] > 0 && $folder[id] != 1) $img = $FSXL[style].'_folder_full.png';
		if ($folder[pics] == 0 && $folder[id] != 1) $img = $FSXL[style].'_folder.png';

		$FSXL[content] .= '
					<div style="float:right; padding-bottom:5px; padding-right:10px;" align="right">
						<a href="?mod=main&go=imagemanager"><u>'.$FS_PHRASES[main_imgmanager_overview].'</u></a>
						<p>
						<form>
						FS-Code <input type="radio" name="pretype" id="pretype1" value="fscode" style="margin-bottom:-1px;" onclick="switchImgManagerType()" checked>
						HTML <input type="radio" name="pretype" id="pretype2" style="margin-bottom:-1px;" value="html" onclick="switchImgManagerType()">
						</form>
					</div>
					<div style="height:40px;">
						<form action="?mod=main&go=imagemanager&folder='.$folder[id].'" method="post">
						<input type="hidden" name="action" value="renamefolder">
						<input type="hidden" name="folderid" value="'.$folder[id].'">
						<img border="0" src="images/'.$img.'" width="32" alt="" style="float:left; margin-right:10px;">
						<input class="textinput" name="name" value="'.$folder[name].'" style="width:100px;">
						<input type="submit" class="button" value="'.$FS_PHRASES[main_imgmanager_rename].'">
						</form>
						<br>
						<div style="padding-top:5px;">'.$FS_PHRASES[main_imgmanager_images].': '.$folder[pics].'</div>
					</div>
					
		';
	}
	$imgtype = array(1=>'', 2=>'.png', 3=>'.gif');
	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_images` WHERE `cat` = $_GET[folder]");
	if (mysql_num_rows($index) > 0)
	{
		$FSXL[content] .= '
			<form action="?mod=main&go=imagemanager&folder='.$_GET[folder].'" method="post" enctype="multipart/form-data">
			<input type="hidden" name="action" value="subfiles">
			<input type="hidden" name="folderid" value="'.$_GET[folder].'">
			<div class="container">
			<table border="0" cellpadding="0" cellspacing="2" width="100%">
		';
		$i=0;
		while ($img = mysql_fetch_assoc($index))
		{
			$index2 = mysql_query("SELECT `name` FROM `$FSXL[tableset]_user` WHERE `id` = $img[autor]");
			$userinfo = mysql_fetch_assoc($index2);
			
			if (file_exists('../images/imgmanager/'.$img[id].'s.jpg')) {
				$thumbsource = '../images/imgmanager/'.$img[id].'s.jpg';
			}
			else {
				$thumbsource = '../images/imgmanager/'.$img[id].'s.png';
			}

			if ($i == 0) $FSXL[content] .= '<tr>';
			$FSXL[content] .= '
				<td width="33%" align="center" class="alt1" valign="top">
					&nbsp;<b>'.$img[title].'</b>
					<div class="mthumb">
						<img border="0" src="'.$thumbsource.'" alt="" class="thumb" onClick="toggleTooltip('.$img[id].')" style="cursor:pointer;">
					</div>
					<div id="imgtooltip'.$img[id].'" style="display:none;">
						<table border="0" cellpadding="2" cellspacing="0" width="100%">
							<tr>
								<td align="left">'.$FS_PHRASES[main_imgmanager_name].':</td>
								<td align="left"><input class="textinput" name="name['.$img[id].']" value="'.$img[title].'" style="width:100px;"></td>
							</tr>
							<tr>
								<td align="left">'.$FS_PHRASES[main_imgmanager_resolution].':</td>
								<td align="left">'.$img[width].'x'.$img[height].'</td>
							</tr>
							<tr>
								<td align="left">'.$FS_PHRASES[main_imgmanager_size].':</td>
								<td align="left">'.(round($img[size]/1024)).' KB</td>
							</tr>
							<tr>
								<td align="left">'.$FS_PHRASES[main_imgmanager_filename].':</td>
								<td align="left">'.$img[filename].'</td>
							</tr>
							<tr>
								<td align="left">'.$FS_PHRASES[main_imgmanager_created].':</td>
								<td align="left">'.date($FSXL[config][dateformat], $img[date]).'</td>
							</tr>
							<tr>
								<td align="left">'.$FS_PHRASES[main_imgmanager_lastmodified].':</td>
								<td align="left">'.date($FSXL[config][dateformat], $img[lastmod]).'</td>
							</tr>
							<tr>
								<td align="left">'.$FS_PHRASES[main_imgmanager_autor].':</td>
								<td align="left">'.$userinfo[name].'</td>
							</tr>
							<tr>
								<td align="left" colspan="2">
									'.$FS_PHRASES[main_imgmanager_replace].':<br>
									<input type="file" name="newimg_'.$img[id].'" class="textinput" style="width:100%;">
								</td>
							</tr>
						</table>
						<p>
					</div>
					<input class="textinput" id="codebox'.$img[id].'" value="[IMG]'.$img[id].$imgtype[$img[type]].'[/IMG]" style="width:100px;">
					<input type="checkbox" name="pic['.$img[id].']" style="margin-bottom:-0px;">
				</td>
			';
			$i++;
			if ($i == 3)
			{
				$i=0;
				$FSXL[content] .= '</tr>';
			}
		}
		if ($i == 1) $FSXL[content] .= '<td></td><td></td></tr>';
		if ($i == 2) $FSXL[content] .= '<td></td></tr>';
		$FSXL[content] .= '</table></div>';
		
		$FSXL[content] .= '
			<div style="margin-top:20px;">
				'.$FS_PHRASES[main_imgmanager_movepics].':
				<select name="tofolder" class="textinput" style="width:160px;">
		';

		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_imgcat` ORDER BY `name`");
		while ($folder = mysql_fetch_assoc($index))
		{
			if ($folder[id] != $_GET[folder])
			{
				$FSXL[content] .= '<option value="'.$folder[id].'">'.$folder[name].'</option>';
			}
		}

		$FSXL[content] .= '
				</select>
				<input type="submit" value="'.$FS_PHRASES[main_imgmanager_move].'" class="button">
				<br>
				'.$FS_PHRASES[main_imgmanager_delpics].':
				<input type="checkbox" name="delete" style="margin-bottom:-0px;">
				<input type="submit" value="'.$FS_PHRASES[main_imgmanager_del].'" class="button">
				<br>
		';
		
		$FSXL[content] .= '
			</div>
			</form>
		';
	}

	//$FSXL[content] .= '<div style="clear:both;"></div>';
	if ($_GET[folder] != 1)
	{
		$FSXL[content] .= '
			<form action="?mod=main&go=imagemanager" method="post" onSubmit="return suredelfolder();">
			<input type="hidden" name="action" value="delfolder">
			<input type="hidden" name="folderid" value="'.$_GET[folder].'">
				'.$FS_PHRASES[main_imgmanager_delfolder].':
				<select name="newfolder" class="textinput" style="width:160px;">
		';

		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_imgcat` ORDER BY `name`");
		while ($folder = mysql_fetch_assoc($index))
		{
			if ($folder[id] != $_GET[folder])
			{
				$FSXL[content] .= '<option value="'.$folder[id].'">'.$folder[name].'</option>';
			}
		}

		$FSXL[content] .= '
				</select>
				<input type="submit" value="'.$FS_PHRASES[main_imgmanager_go].'" class="button">
				</form>
		';
	}
}

// Ordner Übersicht
else
{
	$FSXL[content] .= '
		<b>'.$FS_PHRASES[main_imgmanager_openfolder].'</b><p>
		<table border="0" cellpadding="2" cellspacing="0" width="100%">
	';

	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_imgcat` ORDER BY `name`");
	$i=0;
	while ($folder = mysql_fetch_assoc($index))
	{
		if ($folder[pics] > 0 && $folder[id] == 1) $img = $FSXL[style].'_folder_default_full.png';
		if ($folder[pics] == 0 && $folder[id] == 1) $img = $FSXL[style].'_folder_default.png';
		if ($folder[pics] > 0 && $folder[id] != 1) $img = $FSXL[style].'_folder_full.png';
		if ($folder[pics] == 0 && $folder[id] != 1) $img = $FSXL[style].'_folder.png';
	
		if ($i == 0) $FSXL[content] .= '<tr>';
		$FSXL[content] .= '
			<td width="25%" align="center">
				<a href="?mod=main&go=imagemanager&folder='.$folder[id].'"><img border="0" src="images/'.$img.'" alt=""></a><br>
				<b>'.$folder[name].'</b><br>
				'.$FS_PHRASES[main_imgmanager_images].': '.$folder[pics].'
			</td>
		';
		if ($i == 3)
		{
			$FSXL[content] .= '</tr>';
			$i=0;
		}
		else $i++;
	}
	if ($i > 0 && $i < 3) $FSXL[content] .= '</tr>';

	$FSXL[content] .= '</table>';
}

?>