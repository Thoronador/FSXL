<?php

$FSXL[title] = $FS_PHRASES[video_edit_title];


if ($_POST[editid] && $_POST[name] && $_POST[url] && $_POST[day] != '' && $_POST[month] != '' && $_POST[year] != '' && $_POST[hour] != '' && $_POST[min] != '')
{
	settype($_POST[editid], 'integer');

	// löschen
	if ($_POST[del])
	{
		mysql_query("DELETE FROM `$FSXL[tableset]_videos` WHERE `id` = $_POST[editid]");
		$FSXL[content] = '
				<div style="padding:20px; text-align:center;">'.$FS_PHRASES[video_edit_deleted].'</div>
		';
	}
	// bearbeiten
	else
	{
		$date = mktime($_POST[hour], $_POST[min], 0, $_POST[month], $_POST[day], $_POST[year]);
		$regonly = $_POST[regonly] ? 1 : 0;
		settype($_POST[cat], 'integer');
		settype($_POST[age], 'integer');

		mysql_query("UPDATE `$FSXL[tableset]_videos` SET `cat` = $_POST[cat], `date` = $date, `name` = '$_POST[name]', `url` = '$_POST[url]', 
				`text` = '$_POST[text]', `regonly` = '$regonly', `age` = '$_POST[age]' WHERE `id` = $_POST[editid]");

		$FSXL[content] = '
				<div style="padding:20px; text-align:center;">'.$FS_PHRASES[video_edit_edited].'</div>
		';
	}
}

elseif ($_GET[id])
{
	settype($_GET[id], 'integer');
	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_videos` WHERE `id` = $_GET[id]");
	$video = mysql_fetch_assoc($index);

	$FSXL[content] .= '
				<form action="?mod=video&go=editvideo" method="post" name="videoform" onSubmit="return chkVideoEditForm()">
				<input type="hidden" name="editid" value="'.$video[id].'">
				<table border="0" cellpadding="0" cellspacing="0" align="center" width="90%">
					<tr>
						<td><b>'.$FS_PHRASES[video_add_name].':</b></td>
						<td align="right">
							<div align="left" style="width:406px;"><input name="name" value="'.$video[name].'" class="textinput" style="width:300px;"></div>
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[video_add_cat].':</b></td>
						<td align="right">
							<div style="width:406px;" align="left">
								<select class="textinput" name="cat" style="width:300px;">
									<option value="0">'.$FS_PHRASES[video_cats_blank].'</option>
									<option value="0">---------------------------</option>
	';

	// Kategorien auflisten
	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_video_cat` ORDER BY `name`");
	while ($cat = mysql_fetch_assoc($index))
	{
		$FSXL[content] .= '
									<option value="'.$cat[id].'" '.($video[cat]==$cat[id]?"selected":"").'>'.$cat[name].'</option>
		';
	}

	$FSXL[content] .= '
								</select>
							</div>
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[video_add_url].':</b></td>
						<td align="right">
							<div align="left" style="width:406px;"><input name="url" value="'.$video[url].'" class="textinput" style="width:300px;"></div>
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[video_add_date].':</b><br><span class="small">'.$FS_PHRASES[video_add_dateformat].'</span></td>
						<td valign="top" align="right">
							<div align="left" style="width:406px;">
								<input class="textinput" name="day" style="width:20px;" value="'.date("d", $video[date]).'">
								<input class="textinput" name="month" style="width:20px;" value="'.date("m", $video[date]).'">
								<input class="textinput" name="year" style="width:40px;" value="'.date("Y", $video[date]).'"> -
								<input class="textinput" name="hour" style="width:20px;" value="'.date("H", $video[date]).'">
								<input class="textinput" name="min" style="width:20px;" value="'.date("i", $video[date]).'">
							</div>
						</td>
					</tr>
					<tr>
						<td class="form"><b>'.$FS_PHRASES[video_add_text].':</b></td>
						<td align="right">
							<textarea name="text" class="textinput" style="width:400px; height:100px;">'.$video[text].'</textarea>
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[video_add_age].':</b></td>
						<td align="right">
							<div align="left" style="width:406px;">
								<input name="age" class="textinput" style="width:20px;" value="'.$video[age].'">
								'.$FS_PHRASES[video_add_years].'
							</div>
						</td>
					</tr>
					<tr>
						<td class="form"><b>'.$FS_PHRASES[video_add_regonly].':</b></td>
						<td><input type="checkbox" name="regonly" '.($video[regonly] == 1 ? "checked" : "").'></td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[video_edit_delete].':</b></td>
						<td align="right">
							<div align="left" style="width:406px;"><input type="checkbox" name="del" id="delete" onClick="delMessage(\''.$FS_PHRASES[video_edit_delmsg].'\');"></div>
						</td>
					</tr>
					<tr>
						<td colspan="2" align="right"><input type="submit" class="button" value="'.$FS_PHRASES[global_send].'"></td>
					</tr>
				</table>
				</form>

	';
}

// Übersicht
else
{
	$FSXL[content] .= '
				<div>
				<table border="0" cellpadding="2" cellspacing="1" width="90%" style="margin:0px auto;">
					<tr>
						<td colspan="4"><span style="font-size:12pt;"><b>'.$FS_PHRASES[video_edit_select].'</b></span><hr></td>
					</tr>
					<tr>
						<td>
							<table border="0" cellpadding="2" cellspacing="1" width="100%">
								<tr>
									<td class="alt0"><b>'.$FS_PHRASES[video_add_name].'</b></td>
									<td class="alt0" align="center"><b>'.$FS_PHRASES[video_add_date].'</b></td>
									<td class="alt0" align="center"><b>'.$FS_PHRASES[video_edit_fscode].'</b></td>
									<td class="alt0" align="center"><b>'.$FS_PHRASES[video_edit_link].'</b></td>
								</tr>
	';

	// Liste
	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_videos` ORDER BY `date` DESC");
	while ($video = mysql_fetch_assoc($index))
	{
		$i++;
		$FSXL[content] .= '
					<tr>
						<td class="alt'.($i%2==0?1:2).'"><a href="?mod=video&go=editvideo&id='.$video[id].'">'.$video[name].'</a></td>
						<td class="alt'.($i%2==0?1:2).'" align="center">'.date($FSXL[config][dateformat], $video[date]).'</td>
						<td align="center" class="alt'.($i%2==0?1:2).'">[video]'.$video[id].'[/video]</td>
						<td class="alt'.($i%2==0?1:2).'" align="center"><a href="../index.php?section=video&id='.$video[id].'" target="_blank"><img border="0" src="images/'.$FSXL[style].'_link.gif" alt=""></a></td>
					</tr>
		';
	}

	$FSXL[content] .= '
							</td>
						</tr>
				</table>
				</div>
	';
}

?>