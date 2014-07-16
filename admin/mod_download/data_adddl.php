<?php

$FSXL[title] = $FS_PHRASES[download_add_title];

// Download eintragen
if ($_POST[title])
{
	// Datum auswerten
	if ($_POST[day] && $_POST[month] && $_POST[year] && $_POST[hour] && $_POST[min]) {
		$date = mktime($_POST[hour], $_POST[min], 0, $_POST[month], $_POST[day], $_POST[year]);
	}
	else {
		$date = time();
	}

	settype($_POST[cat], 'integer');
	settype($_POST[age], 'integer');
	$regonly = $_POST[regonly] ? 1 : 0;
	$active = $_POST[active] ? 1 : 0;

	$index = mysql_query("INSERT INTO `$FSXL[tableset]_dl` (`id`, `catid`, `name`, `text`, `views`, `autor`, `date`, `regonly`, `active`, `autor_url`, `age`)
				VALUES (NULL, $_POST[cat], '$_POST[title]', '$_POST[text]', 0, '$_POST[autor]', '$date', '$regonly', '$active', '$_POST[autor_url]', '$_POST[age]')");

	$id = mysql_insert_id();

	// Suchindex
	updateSearchIndex($id, 'download', $_POST[text].' '.$_POST[title]);

	foreach ($_POST[linkname] as $key => $value)
	{
		if ($_POST[linkname][$key] && $_POST[linkurl][$key] && $_POST[linksize][$key] >= 0)
		{
			settype($_POST[linksize][$key], 'integer');
			$ltype = $_POST[linktype][$key] ? 1 : 0;

			mysql_query("INSERT INTO `$FSXL[tableset]_dl_links` (`id`, `dlid`, `name`, `url`, `count`, `target`, `size`)
					VALUES (NULL, $id, '".$_POST[linkname][$key]."', '".$_POST[linkurl][$key]."', 0, $ltype, ".$_POST[linksize][$key].")");
		}
	}

	$FSXL[content] = '
				<div style="padding:20px; text-align:center;">
					'.$FS_PHRASES[download_add_added].'<p>
					<form action="" method="get">
						<input type="hidden" name="mod" value="download">
						<input type="hidden" name="go" value="adddl">
						<input type="submit" class="button" value="'.$FS_PHRASES[global_ok].'">
					</form>
				</div>
	';
}

// Übersicht
else
{
	$FSXL[content] .= '
				<div>
				<form action="?mod=download&go=adddl" method="post" name="dlform" onSubmit="return chkDlAddForm()">
				<table border="0" cellpadding="0" cellspacing="0" align="center" width="95%">
					<tr>
						<td width="125"><b>'.$FS_PHRASES[download_cats_name].':</b></td>
						<td><input class="textinput" name="title" style="width:400px;"></td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[download_add_autor].':</b></td>
						<td>
							<input class="textinput" style="width:400px;" name="autor">
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[download_add_autor_url].':</b></td>
						<td>
							<input class="textinput" style="width:400px;" name="autor_url">
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[download_add_dldate].':</b><br>'.$FS_PHRASES[download_add_dateformat].'</td>
						<td valign="top">
							<input class="textinput" name="day" style="width:20px;" value="'.date("d").'">
							<input class="textinput" name="month" style="width:20px;" value="'.date("m").'">
							<input class="textinput" name="year" style="width:40px;" value="'.date("Y").'"> -
							<input class="textinput" name="hour" style="width:20px;" value="'.date("H").'">
							<input class="textinput" name="min" style="width:20px;" value="'.date("i").'">
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[download_add_cat].':</b></td>
						<td>
							<select name="cat" class="textinput" style="width:400px;">
	';

	// Kategorien auslesen
	$FSXL[content] .= '<option value="0">'.$FSXL[config][pagetitle].'</option>';
	$FSXL[content] .= dl_create_optionlist(0, 0, $_POST[cat], $db, $FSXL[tableset], 0);

	$FSXL[content] .= '
							</select>
						</td>
					</tr>
					<tr>
						<td valign="top"><b>'.$FS_PHRASES[download_add_text].':</b></td>
						<td><textarea name="text" class="textinput" style="width:400px; height:150px;"></textarea></td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[download_add_age].':</b></td>
						<td>
							<input name="age" class="textinput" style="width:20px;" value="0">
							'.$FS_PHRASES[download_add_years].'
						</td>
					</tr>
					<tr>
						<td valign="top"><b>'.$FS_PHRASES[download_add_regonly].':</b></td>
						<td><input type="checkbox" name="regonly"></td>
					</tr>
					<tr>
						<td valign="top"><b>'.$FS_PHRASES[download_add_active].':</b><br>'.$FS_PHRASES[download_add_active_sub].'</td>
						<td><input type="checkbox" name="active" checked></td>
					</tr>
					<tr>
						<td colspan="2" align="right">
							<input type="submit" class="button" value="'.$FS_PHRASES[global_send].'">
						</td>
					</tr>
				</table>
				<script type="text/javascript">var currentLinkIndex = 0;</script>
				<table border="0" cellpadding="0" cellspacing="1" align="center" width="95%">
					<tr>
						<td colspan="2" style="padding:0px;">
							<input type="hidden" name="numlinks" id="numlinks">
							<span style="font-size:12pt;"><b>'.$FS_PHRASES[download_add_links].'</b></span><hr>
						</td>
					</tr>
					<tr>
						<td class="alt1" style="padding:5px;">
							<table border="0" cellpadding="0" cellspacing="0" width="100%">
								<tr>
									<td width="200">
										<b>'.$FS_PHRASES[download_add_linkname].':</b>
										<input class="textinput" name="linkname[0]" id="linkname[0]" style="width:150px; margin-bottom:-2px;" onkeyup="addDlLink(this);">
									</td>
									<td style="padding-left:5px;">
										<b>'.$FS_PHRASES[download_add_linkurl].':</b>
										<input class="textinput" name="linkurl[0]" id="linkurl[0]" style="width:260px; margin-bottom:-2px;" onkeyup="addDlLink(this);">
										<input type="button" name="urlpre[0]" id="urlpre[0]" value="'.$FS_PHRASES[download_add_path].'" onClick="addDlPrefix(this);" class="button" style="margin-bottom:-2px;">
									</td>
								</tr>
									<td>
										<b>'.$FS_PHRASES[download_add_linksize].':</b>
										<input class="textinput" name="linksize[0]" id="linksize[0]" style="width:100px; margin-bottom:-2px;" onkeyup="addDlLink(this);">
									</td>
									<td style="padding-left:5px;">
										<b>'.$FS_PHRASES[download_add_newwindow].':</b>
										<input type="checkbox" name="linktype[0]" id="linktype[0]" style="margin-bottom:-2px;">
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

?>