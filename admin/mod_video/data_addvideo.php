<?php

$FSXL[title] = $FS_PHRASES[video_add_title];


if ($_POST[action] == "add" && $_POST[name] && $_POST[url])
{
	// Datum auswerten
	if ($_POST[day] != '' && $_POST[month] != '' && $_POST[year] != '' && $_POST[hour] != '' && $_POST[min] != '')
		$date = mktime($_POST[hour], $_POST[min], 0, $_POST[month], $_POST[day], $_POST[year]);
	else
		$date = time();

	$regonly = $_POST[regonly] ? 1 : 0;
	settype($_POST[cat], 'integer');
	settype($_POST[age], 'integer');

	mysql_query("INSERT INTO `$FSXL[tableset]_videos` (`id`, `cat`, `date`, `name`, `url`, `text`, `regonly`, `age`) 
			VALUES (NULL, $_POST[cat], $date, '$_POST[name]', '$_POST[url]', '$_POST[text]', '$regonly', '$_POST[age]')");

	$FSXL[content] = '
				<div style="padding:20px; text-align:center;">'.$FS_PHRASES[video_add_added].'</div>
	';
}

else
{
	$FSXL[content] .= '
				<form action="?mod=video&go=addvideo" method="post" name="videoform" onSubmit="return chkVideoAddForm()">
				<input type="hidden" name="action" value="add">
				<table border="0" cellpadding="0" cellspacing="0" align="center" width="90%">
					<tr>
						<td><b>'.$FS_PHRASES[video_add_name].':</b></td>
						<td align="right">
							<div align="left" style="width:406px;"><input name="name" class="textinput" style="width:300px;"></div>
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
									<option value="'.$cat[id].'">'.$cat[name].'</option>
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
							<div align="left" style="width:406px;"><input name="url" class="textinput" style="width:300px;"></div>
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[video_add_date].':</b><br><span class="small">'.$FS_PHRASES[video_add_dateformat].'</span></td>
						<td valign="top" align="right">
							<div align="left" style="width:406px;">
								<input class="textinput" name="day" style="width:20px;" value="'.date("d").'">
								<input class="textinput" name="month" style="width:20px;" value="'.date("m").'">
								<input class="textinput" name="year" style="width:40px;" value="'.date("Y").'"> -
								<input class="textinput" name="hour" style="width:20px;" value="'.date("H").'">
								<input class="textinput" name="min" style="width:20px;" value="'.date("i").'">
							</div>
						</td>
					</tr>
					<tr>
						<td class="form"><b>'.$FS_PHRASES[video_add_text].':</b></td>
						<td align="right">
							<textarea name="text" class="textinput" style="width:400px; height:100px;"></textarea>
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[video_add_age].':</b></td>
						<td align="right">
							<div align="left" style="width:406px;">
								<input name="age" class="textinput" style="width:20px;" value="0">
								'.$FS_PHRASES[video_add_years].'
							</div>
						</td>
					</tr>
					<tr>
						<td class="form"><b>'.$FS_PHRASES[video_add_regonly].':</b></td>
						<td><input type="checkbox" name="regonly"></td>
					</tr>
					<tr>
						<td colspan="2" align="right"><input type="submit" class="button" value="'.$FS_PHRASES[global_send].'"></td>
					</tr>
				</table>
				</form>

	';
}

?>