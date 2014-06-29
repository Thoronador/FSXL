<?php

$FSXL[title] = $FS_PHRASES[download_edit_title];

// Download löschen
if ($_POST[del])
{
	mysql_query("DELETE FROM `$FSXL[tableset]_dl` WHERE `id` = $_POST[editid]");
	mysql_query("DELETE FROM `$FSXL[tableset]_dl_links` WHERE `dlid` = $_POST[editid]");
	mysql_query("DELETE FROM `$FSXL[tableset]_downloadconnect` WHERE `article` = $_POST[editid]");

	$FSXL[content] = '
				<div style="padding:20px; text-align:center;">
					'.$FS_PHRASES[download_edit_deleted].'<p>
					<form action="" method="get">
						<input type="hidden" name="mod" value="download">
						<input type="hidden" name="go" value="editdl">
						<input type="submit" class="button" value="'.$FS_PHRASES[global_ok].'">
					</form>
				</div>
	';
}

// Download bearbeiten
elseif ($_POST[title] && $_POST[hour] && $_POST[min] && $_POST[month] && $_POST[day] && $_POST[year])
{
	$date = mktime($_POST[hour], $_POST[min], 0, $_POST[month], $_POST[day], $_POST[year]);

	settype($_POST[cat], 'integer');
	settype($_POST[editid], 'integer');
	settype($_POST[age], 'integer');
	$regonly = $_POST[regonly] ? 1 : 0;
	$active = $_POST[active] ? 1 : 0;

	$index = mysql_query("UPDATE `$FSXL[tableset]_dl` SET
				`catid` = $_POST[cat],
				`name` = '$_POST[title]',
				`text` = '$_POST[text]',
				`autor` = '$_POST[autor]',
				`date` = $date,
				`regonly` = $regonly,
				`active` = $active,
				`autor_url` = '$_POST[autor_url]',
				`age` = '$_POST[age]'
				WHERE `id` = $_POST[editid]");


	// Suchindex
	updateSearchIndex($_POST[editid], 'download', $_POST[text].' '.$_POST[title]);

	foreach($_POST[linkname] as $key => $value)
	{
		if ($_POST[linkid][$key])
		{
			settype($_POST[linkid][$key], 'integer');
			// Link löschen
			if ($_POST[linkdel][$key])
			{
				mysql_query("DELETE FROM `$FSXL[tableset]_dl_links` WHERE `id` = " . $_POST[linkid][$key]);
			}
			// Link bearbeiten
			elseif($_POST[linkname][$key] && $_POST[linkurl][$key] && $_POST[linksize][$key] >= 0)
			{
				settype($_POST[linksize][$key], 'integer');
				$ltype = $_POST[linktype][$key] ? 1 : 0;

				mysql_query("UPDATE `$FSXL[tableset]_dl_links` SET
					`name` = '".$_POST[linkname][$key]."',
					`url`= '".$_POST[linkurl][$key]."',
					`target` = $ltype,
					`size` = ".$_POST[linksize][$key]."
					WHERE `id` = " . $_POST[linkid][$key]);
			}
		}
		// Link einfügen
		elseif ($_POST[linkname][$key] && $_POST[linkurl][$key] && $_POST[linksize][$key] >= 0)
		{
			settype($_POST[linksize][$key], 'integer');
			$ltype = $_POST[linktype][$key] ? 1 : 0;
	
			mysql_query("INSERT INTO `$FSXL[tableset]_dl_links` (`id`, `dlid`, `name`, `url`, `count`, `target`, `size`)
					VALUES (NULL, $_POST[editid], '".$_POST[linkname][$key]."', '".$_POST[linkurl][$key]."', 0, $ltype, ".$_POST[linksize][$key].")");
		}
	}

	$FSXL[content] = '
				<div style="padding:20px; text-align:center;">
					'.$FS_PHRASES[download_edit_edited].'<p>
					<form action="" method="get">
						<input type="hidden" name="mod" value="download">
						<input type="hidden" name="go" value="editdl">
						<input type="submit" class="button" value="'.$FS_PHRASES[global_ok].'">
					</form>
				</div>
	';
}

// Bearbtein Formular
elseif ($_GET[id])
{
	settype($_GET[id], 'integer');
	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_dl` WHERE `id` = $_GET[id]");
	$download = mysql_fetch_assoc($index);

	$FSXL[content] .= '
				<div>
				<form action="?mod=download&go=editdl&id='.$_GET[id].'" method="post" name="dlform" onSubmit="return chkDlEditForm()">
				<input type="hidden" name="editid" value="'.$_GET[id].'">
				<table border="0" cellpadding="0" cellspacing="0" align="center" width="95%">
					<tr>
						<td width="125"><b>'.$FS_PHRASES[download_cats_name].':</b></td>
						<td><input class="textinput" name="title" style="width:400px;" value="'.str_replace('"', '&quot;', $download[name]).'"></td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[download_add_autor].':</b></td>
						<td>
							<input class="textinput" style="width:400px;" name="autor" value="'.$download[autor].'">
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[download_add_autor_url].':</b></td>
						<td>
							<input class="textinput" style="width:400px;" name="autor_url" value="'.$download[autor_url].'">
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[download_add_dldate].':</b><br>'.$FS_PHRASES[download_add_dateformat].'</td>
						<td valign="top">
							<input class="textinput" name="day" style="width:20px;" value="'.date("d", $download[date]).'">
							<input class="textinput" name="month" style="width:20px;" value="'.date("m", $download[date]).'">
							<input class="textinput" name="year" style="width:40px;" value="'.date("Y", $download[date]).'"> -
							<input class="textinput" name="hour" style="width:20px;" value="'.date("H", $download[date]).'">
							<input class="textinput" name="min" style="width:20px;" value="'.date("i", $download[date]).'">
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[download_add_cat].':</b></td>
						<td>
							<select name="cat" class="textinput" style="width:400px;">
	';

	// Kategorien auslesen
	$FSXL[content] .= '<option value="0">'.$FSXL[config][pagetitle].'</option>';
	$FSXL[content] .= dl_create_optionlist(0, 0, $download[catid], $db, $FSXL[tableset], 0);


	if (!$_POST[type]) $_POST[type] = 1;
	$FSXL[content] .= '
							</select>
						</td>
					</tr>
					<tr>
						<td valign="top"><b>'.$FS_PHRASES[download_add_text].':</b></td>
						<td><textarea name="text" class="textinput" style="width:400px; height:150px;">'.$download[text].'</textarea></td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[download_add_age].':</b></td>
						<td>
							<input name="age" class="textinput" style="width:20px;" value="'.$download[age].'">
							'.$FS_PHRASES[download_add_years].'
						</td>
					</tr>
					<tr>
						<td width="125"><b>'.$FS_PHRASES[download_add_regonly].':</b></td>
						<td><input type="checkbox" name="regonly" '.($download[regonly] == 1 ? "checked" : "").'></td>
					</tr>
					<tr>
						<td valign="top"><b>'.$FS_PHRASES[download_add_active].':</b><br>'.$FS_PHRASES[download_add_active_sub].'</td>
						<td><input type="checkbox" name="active" '.($download[active] == 1 ? "checked" : "").'></td>
					</tr>
					<tr>
						<td width="125"><b>'.$FS_PHRASES[download_cats_delete].':</b></td>
						<td><input type="checkbox" name="del" id="delete" onClick="delMessage(\''.$FS_PHRASES[download_edit_delmsg].'\')"></td>
					</tr>
					<tr>
						<td colspan="2" align="right">
							<input type="submit" class="button" value="'.$FS_PHRASES[global_send].'">
						</td>
					</tr>
				</table>
				<table border="0" cellpadding="0" cellspacing="1" align="center" width="95%">
					<tr>
						<td colspan="2" style="padding:0px;">
							<input type="hidden" name="numlinks" id="numlinks">
							<span style="font-size:12pt;"><b>'.$FS_PHRASES[download_add_links].'</b></span><hr>
						</td>
					</tr>
	';

	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_dl_links` WHERE `dlid` = $_GET[id] ORDER BY `id`");
	$FSXL[content] .= '<tr><td><script type="text/javascript">var currentLinkIndex = '.mysql_num_rows($index).';</script></td></tr>';
	$i=0;
	while ($link = mysql_fetch_assoc($index))
	{
		$FSXL[content] .= '
					<tr>
						<td class="alt'.($i%2 == 0 ? 1 : 2).'" style="padding:5px;">
							<input type="hidden" name="linkid['.$i.']" value="'.$link[id].'">
							<table border="0" cellpadding="0" cellspacing="0" width="100%">
								<tr>
									<td width="200">
										<b>'.$FS_PHRASES[download_add_linkname].':</b>
										<input class="textinput" name="linkname['.$i.']" style="width:150px; margin-bottom:-2px;" value="'.$link[name].'">
									</td>
									<td style="padding-left:5px;">
										<b>'.$FS_PHRASES[download_add_linkurl].':</b>
										<input class="textinput" name="linkurl['.$i.']" style="width:290px; margin-bottom:-2px;" value="'.$link[url].'">
									</td>
								</tr>
									<td>
										<b>'.$FS_PHRASES[download_add_linksize].':</b>
										<input class="textinput" name="linksize['.$i.']" style="width:100px; margin-bottom:-2px;" value="'.$link[size].'">
									</td>
									<td style="padding-left:5px;">
										<div style="float:right;">
											<b>'.$FS_PHRASES[download_cats_delete].':</b>
											<input type="checkbox" name="linkdel['.$i.']" style="margin-bottom:-2px;">
										</div>
										<b>'.$FS_PHRASES[download_add_newwindow].':</b>
										<input type="checkbox" name="linktype['.$i.']" style="margin-bottom:-2px;" '.($link[target] == 1 ? "checked" : "").'>
									</td>
								</tr>
							</table>
						</td>
					</tr>
		';
		$i++;
	}
	$FSXL[content] .= '
					<tr>
						<td class="alt1" style="padding:5px;">
							<table border="0" cellpadding="0" cellspacing="0" width="100%">
								<tr>
									<td width="200">
										<b>'.$FS_PHRASES[download_add_linkname].':</b>
										<input class="textinput" name="linkname['.$i.']" id="linkname['.$i.']" style="width:150px; margin-bottom:-2px;" onkeyup="addDlLink(this);">
									</td>
									<td style="padding-left:5px;">
										<b>'.$FS_PHRASES[download_add_linkurl].':</b>
										<input class="textinput" name="linkurl['.$i.']" id="linkurl['.$i.']" style="width:260px; margin-bottom:-2px;" onkeyup="addDlLink(this);">
										<input type="button" name="urlpre['.$i.']" id="urlpre['.$i.']" value="'.$FS_PHRASES[download_add_path].'" onClick="addDlPrefix(this);" class="button" style="margin-bottom:-2px;">
									</td>
								</tr>
									<td>
										<b>'.$FS_PHRASES[download_add_linksize].':</b>
										<input class="textinput" name="linksize['.$i.']" id="linksize['.$i.']" style="width:100px; margin-bottom:-2px;" onkeyup="addDlLink(this);">
									</td>
									<td style="padding-left:5px;">
										<b>'.$FS_PHRASES[download_add_newwindow].':</b>
										<input type="checkbox" name="linktype['.$i.']" id="linktype['.$i.']" style="margin-bottom:-2px;">
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td>
							<br>
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
	$i=0;
	$FSXL[content] .= '
				<div>
				<table border="0" cellpadding="2" cellspacing="1" width="90%" style="margin:0px auto;">
					<tr>
						<td colspan="2" style="padding:0px;"><span style="font-size:12pt;"><b>'.$FS_PHRASES[download_edit_select].'</b></span><hr></td>
					</tr>
					<tr>
						<td class="alt0"><b>'.$FS_PHRASES[download_cats_name].'</b></td>
						<td class="alt0" align="center"><b>'.$FS_PHRASES[download_cats_link].'</b></td>
					</tr>
					<tr>
						<td class="alt'.($i%2==0?1:2).'" colspan="2">
							<img border="0" src="mod_download/root.png" alt="" style="float:left; margin-right:8px;">
							<b>'.$FSXL[config][pagetitle].'</b><br>
							'.$FS_PHRASES[download_cats_root].'
						</td>
					</tr>
	';
	$index2 = mysql_query("SELECT `id`, `name`, `active` FROM `$FSXL[tableset]_dl` WHERE `catid` = 0 ORDER BY `name`");
	while ($file = mysql_fetch_assoc($index2))
	{
		$i++;
		$yellow = $file[active]==0 ? 'background-color:#EEEE55;' : '';
		$FSXL[content] .= '
					<tr>
						<td class="alt'.($i%2==0?1:2).'" style="padding-left:40px; '.$yellow.'">
							<a href="?mod=download&go=editdl&id='.$file[id].'">
								<img border="0" src="mod_download/file.png" alt="" width="16" height="16" style="margin-bottom:-5px;">
								'.$file[name].'
							</a>
						</td>
						<td class="alt'.($i%2==0?1:2).'" align="center" style="'.$yellow.'"><a href="../index.php?section=download&id='.$file[id].'" target="_blank"><img border="0" src="images/'.$FSXL[style].'_link.gif" alt=""></a></td>
					</tr>
		';
	}

	$FSXL[content] .= dl_create_admin_overview(0, 0, true);

	$FSXL[content] .= '
				</table>
				</div>

	';
}


?>