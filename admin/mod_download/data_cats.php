<?php

$FSXL[title] = $FS_PHRASES[download_cats_title];

// Ordner bearbeiten
if ($_POST[action] == "editfolder" && $_POST[name])
{
	// Ordner löschen
	if ($_POST[delete])
	{
		settype($_POST[id], 'integer');
		settype($_POST[newcat], 'integer');

		mysql_query("DELETE FROM `$FSXL[tableset]_dl_cat` WHERE `id` = $_POST[id]");
		mysql_query("UPDATE `$FSXL[tableset]_dl_cat` SET `parentid` = $_POST[newcat] WHERE `parentid` = $_POST[id]");
		mysql_query("UPDATE `$FSXL[tableset]_dl` SET `catid` = $_POST[newcat] WHERE `catid` = $_POST[id]");

		$FSXL[content] = '
				<div style="padding:20px; text-align:center;">
					'.$FS_PHRASES[download_cats_folderdeleted].'<p>
					<form action="" method="get">
						<input type="hidden" name="mod" value="download">
						<input type="hidden" name="go" value="cats">
						<input type="submit" class="button" value="'.$FS_PHRASES[global_ok].'">
					</form>
				</div>
		';
	}

	// Ordner bearbeiten
	else
	{
		settype($_POST[id], 'integer');
		settype($_POST[cat], 'integer');

		mysql_query("UPDATE `$FSXL[tableset]_dl_cat` SET
				`parentid` = $_POST[cat],
				`name` = '$_POST[name]',
				`desc` = '$_POST[desc]'
				WHERE `id` = $_POST[id]");

		$FSXL[content] = '
				<div style="padding:20px; text-align:center;">
					'.$FS_PHRASES[download_cats_folderedited].'<p>
					<form action="" method="get">
						<input type="hidden" name="mod" value="download">
						<input type="hidden" name="go" value="cats">
						<input type="submit" class="button" value="'.$FS_PHRASES[global_ok].'">
					</form>
				</div>
		';
	}
}


// Ordner bearbeiten Formular
elseif ($_GET[edit])
{
	settype($_GET[edit], 'integer');
	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_dl_cat` WHERE `id` = $_GET[edit]");
	$cat = mysql_fetch_assoc($index);

	$FSXL[content] = '
				<div>
				<form action="?mod=download&go=cats" method="post">
				<input type="hidden" name="action" value="editfolder">
				<input type="hidden" name="id" value="'.$cat[id].'">
				<table border="0" cellpadding="2" cellspacing="1" width="90%" style="margin:0px auto;">
					<tr>
						<td colspan="3" style="padding:0px;"><span style="font-size:12pt;"><b>'.$FS_PHRASES[download_cats_editfolder].'</b></span><hr></td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[download_cats_name].':</b></td>
						<td><input class="textinput" name="name" style="width:200px;" value="'.$cat[name].'"></td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[download_cats_subfolderof].':</b></td>
						<td>
							<select name="cat" class="textinput" style="width:350px;">
	';

	// Kategorien auslesen
	$FSXL[content] .= '<option value="0">'.$FSXL[config][pagetitle].'</option>';
	$FSXL[content] .= dl_create_optionlist(0, 0, $cat[parentid], $cat[id]);

	$FSXL[content] .= '
							</select>
						</td>
					</tr>
					<tr>
						<td valign="top"><b>'.$FS_PHRASES[download_cats_description].':</b></td>
						<td><textarea class="textinput" name="desc" style="width:350px; height:80px;">'.$cat[desc].'</textarea></td>
					</tr>
					<tr>
						<td colspan="2">
							<input type="submit" class="button" value="'.$FS_PHRASES[global_send].'" style="float:right;">
						</td>
					</tr>
					<tr>
						<td colspan="3" style="padding:0px;"><span style="font-size:12pt;"><b>'.$FS_PHRASES[download_cats_delete].'</b></span><hr></td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[download_cats_delete].':</b></td>
						<td><input type="checkbox" name="delete"></td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[download_cats_moveto].':</b></td>
						<td>
							<select name="newcat" class="textinput" style="width:350px;">
	';

	// Kategorien auslesen
	$FSXL[content] .= '<option value="0">'.$FSXL[config][pagetitle].'</option>';
	$FSXL[content] .= dl_create_optionlist(0, 0, 0, $cat[id]);

	$FSXL[content] .= '
							</select>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<input type="submit" class="button" value="'.$FS_PHRASES[global_send].'" style="float:right;">
						</td>
					</tr>
					<tr>
						<td colspan="3" style="padding:0px;"><span style="font-size:12pt;"><b>'.$FS_PHRASES[download_cats_files].'</b></span><hr></td>
					</tr>
					<tr>
						<td class="alt0"><b>'.$FS_PHRASES[download_cats_name].'</b></td>
						<td class="alt0" align="center"><b>'.$FS_PHRASES[download_cats_link].'</b></td>
					</tr>
	';

	$index = mysql_query("SELECT `id`, `name` FROM `$FSXL[tableset]_dl` WHERE `catid` = $cat[id] ORDER BY `name`");
	while ($file = mysql_fetch_assoc($index))
	{
		$i++;
		$FSXL[content] .= '
					<tr>
						<td class="alt'.($i%2==0?1:2).'"><a href="?mod=download&go=editdl&id='.$file[id].'">'.$file[name].'</a></td>
						<td class="alt'.($i%2==0?1:2).'" align="center"><a href="../index.php?section=download&id='.$file[id].'" target="_blank"><img border="0" src="images/'.$FSXL[style].'_link.gif" alt=""></a></td>
					</tr>
		';
	}

	$FSXL[content] .= '
				</table>
				</form>
				</div>
	';
}

// Ordner hinzufügen
elseif ($_POST[action] == "addfolder" && $_POST[name])
{
	settype($_POST[parent], 'integer');

	$index = mysql_query("INSERT INTO `$FSXL[tableset]_dl_cat` (`id`, `parentid`, `name` , `desc`)
				VALUES (NULL, $_POST[parent], '$_POST[name]', '$_POST[desc]');");

	$FSXL[content] = '
				<div style="padding:20px; text-align:center;">
					'.$FS_PHRASES[download_cats_folderadded].'<p>
					<form action="" method="get">
						<input type="hidden" name="mod" value="download">
						<input type="hidden" name="go" value="cats">
						<input type="submit" class="button" value="'.$FS_PHRASES[global_ok].'">
					</form>
				</div>
	';
}

// Ordner hinzufügen Formular
elseif ($_GET[newfolder])
{
	if ($_GET[newfolder] == "root") {
		$_GET[newfolder] = 0;
	}
	else
	{
		settype($_GET[newfolder], 'integer');
		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_dl_cat` WHERE `id` = $_GET[newfolder]");
		$folder_arr = mysql_fetch_assoc($index);
	}

	$FSXL[content] = '
				<div>
				<form action="?mod=download&go=cats" method="post">
				<input type="hidden" name="action" value="addfolder">
				<input type="hidden" name="parent" value="'.$_GET[newfolder].'">
				<table border="0" cellpadding="2" cellspacing="1" width="90%" style="margin:0px auto;">
					<tr>
						<td colspan="3" style="padding:0px;"><span style="font-size:12pt;"><b>'.$FS_PHRASES[download_cats_addfolder].'</b></span><hr></td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[download_cats_subfolderof].':</b></td>
						<td>
							<img border="0" src="mod_download/'.($_GET[newfolder] == 0 ? "root" : $FSXL[style]."_folder").'.png" alt="" width="16" height="16" style="margin-bottom:-3px;">
							'.($_GET[newfolder] == 0 ? $FSXL[config][pagetitle] : $folder_arr[name]).'
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[download_cats_name].':</b></td>
						<td><input class="textinput" name="name" style="width:200px;"></td>
					</tr>
					<tr>
						<td valign="top"><b>'.$FS_PHRASES[download_cats_description].':</b></td>
						<td><textarea class="textinput" name="desc" style="width:380px; height:80px;"></textarea></td>
					</tr>
					<tr>
						<td colspan="2">
							<input type="submit" class="button" value="'.$FS_PHRASES[global_send].'" style="float:right;">
						</td>
					</tr>
				</table>
				</form>
				</div>
	';
}

// Übersicht
else
{
	$FSXL[content] .= '
				<div>
				<table border="0" cellpadding="2" cellspacing="1" width="90%" style="margin:0px auto;">
					<tr>
						<td colspan="3" style="padding:0px;"><span style="font-size:12pt;"><b>'.$FS_PHRASES[download_cats_select].'</b></span><hr></td>
					</tr>
					<tr>
						<td class="alt0"><b>'.$FS_PHRASES[download_cats_name].'</b></td>
						<td class="alt0" align="center"><b>'.$FS_PHRASES[download_cats_options].'</b></td>
						<td class="alt0" align="center"><b>'.$FS_PHRASES[download_cats_link].'</b></td>
					</tr>
					<tr>
						<td class="alt'.($i%2==0?1:2).'">
							<img border="0" src="mod_download/root.png" alt="" style="float:left; margin-right:8px;">
							<b>'.$FSXL[config][pagetitle].'</b><br>
							'.$FS_PHRASES[download_cats_root].'
						</td>
						<td align="center" class="alt'.($i%2==0?1:2).'"><a href="?mod=download&go=cats&newfolder=root">'.$FS_PHRASES[download_cats_addfolder].'</a></td>
						<td class="alt'.($i%2==0?1:2).'" align="center"><a href="../index.php?section=download&cat=0" target="_blank"><img border="0" src="images/'.$FSXL[style].'_link.gif" alt=""></a></td>
					</tr>
	';

	$i = 0;
	$FSXL[content] .= dl_create_admin_overview(0, 0);

	$FSXL[content] .= '
				</table>
				</div>

	';
}
?>