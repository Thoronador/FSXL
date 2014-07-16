<?php

if ($_POST[action] == "edit")
{
	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = '$_POST[color]' WHERE `name` = 'video_color'");

	$_POST[showall] = $_POST[showall] ? 1 : 0;
	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = $_POST[showall] WHERE `name` = 'video_showall'");

	$_POST[showplay] = $_POST[showplay] ? 1 : 0;
	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = $_POST[showplay] WHERE `name` = 'video_showplay'");

	$_POST[showstop] = $_POST[showstop] ? 1 : 0;
	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = $_POST[showstop] WHERE `name` = 'video_showstop'");

	$_POST[showseek] = $_POST[showseek] ? 1 : 0;
	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = $_POST[showseek] WHERE `name` = 'video_showseek'");

	$_POST[showtime] = $_POST[showtime] ? 1 : 0;
	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = $_POST[showtime] WHERE `name` = 'video_showtime'");

	$_POST[showvolbar] = $_POST[showvolbar] ? 1 : 0;
	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = $_POST[showvolbar] WHERE `name` = 'video_showvolbar'");

	$_POST[showmute] = $_POST[showmute] ? 1 : 0;
	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = $_POST[showmute] WHERE `name` = 'video_showmute'");

	$_POST[showfullscreen] = $_POST[showfullscreen] ? 1 : 0;
	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = $_POST[showfullscreen] WHERE `name` = 'video_showfullscreen'");

	reloadPage('?mod=video&go=config');
}

$FSXL[title] = $FS_PHRASES[video_config_title];

$FSXL[content] .= '
				<form action="?mod=video&go=config" method="post">
				<input type="hidden" name="action" value="edit">
				<table border="0" cellpadding="0" cellspacing="0" align="center" width="90%">
					<tr>
						<td colspan="2">
							<span style="font-size:12pt"><b>'.$FS_PHRASES[video_config_video].'</b></span><hr>
						</td>
					</tr>
					<tr>
						<td width="250"><b>'.$FS_PHRASES[video_config_showall].':</b><br>'.$FS_PHRASES[video_config_showall_sub].'</td>
						<td>
							<input type="checkbox" name="showall" '.($FSXL[config][video_showall]?"checked":"").'>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<br><br>
							<span style="font-size:12pt"><b>'.$FS_PHRASES[video_config_videoplayer].'</b></span><hr>
						</td>
					</tr>
					<tr>
						<td class="form"><b>'.$FS_PHRASES[video_config_color].':</b><br>'.$FS_PHRASES[video_config_color_sub].'</td>
						<td>
							<input name="color" class="textinput" style="width:50px;" value="'.$FSXL[config][video_color].'">
						</td>
					</tr>
					<tr>
						<td class="form"><b>'.$FS_PHRASES[video_config_showplay].':</b></td>
						<td>
							<input name="showplay" type="checkbox" '.($FSXL[config][video_showplay] == 1 ? "checked" : "").'>
						</td>
					</tr>
					<tr>
						<td class="form"><b>'.$FS_PHRASES[video_config_showstop].':</b></td>
						<td>
							<input name="showstop" type="checkbox" '.($FSXL[config][video_showstop] == 1 ? "checked" : "").'>
						</td>
					</tr>
					<tr>
						<td class="form"><b>'.$FS_PHRASES[video_config_showseek].':</b></td>
						<td>
							<input name="showseek" type="checkbox" '.($FSXL[config][video_showseek] == 1 ? "checked" : "").'>
						</td>
					</tr>
					<tr>
						<td class="form"><b>'.$FS_PHRASES[video_config_showtime].':</b></td>
						<td>
							<input name="showtime" type="checkbox" '.($FSXL[config][video_showtime] == 1 ? "checked" : "").'>
						</td>
					</tr>
					<tr>
						<td class="form"><b>'.$FS_PHRASES[video_config_showvolbar].':</b></td>
						<td>
							<input name="showvolbar" type="checkbox" '.($FSXL[config][video_showvolbar] == 1 ? "checked" : "").'>
						</td>
					</tr>
					<tr>
						<td class="form"><b>'.$FS_PHRASES[video_config_showmute].':</b></td>
						<td>
							<input name="showmute" type="checkbox" '.($FSXL[config][video_showmute] == 1 ? "checked" : "").'>
						</td>
					</tr>
					<tr>
						<td class="form"><b>'.$FS_PHRASES[video_config_showfullscreen].':</b></td>
						<td>
							<input name="showfullscreen" type="checkbox" '.($FSXL[config][video_showfullscreen] == 1 ? "checked" : "").'>
						</td>
					</tr>
					<tr>
						<td colspan="2" align="right"><input type="submit" class="button" value="'.$FS_PHRASES[global_takeover].'"></td>
					</tr>
				</table>
				</form>

';

?>