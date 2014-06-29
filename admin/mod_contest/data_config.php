<?php

if ($_POST[action] == "edit")
{
	settype($_POST[contest_thumby], 'integer');
	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = $_POST[contest_thumby] WHERE `name` = 'contest_thumby'");

	settype($_POST[contest_thumbx], 'integer');
	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = $_POST[contest_thumbx] WHERE `name` = 'contest_thumbx'");

	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = '$_POST[bgcolor]' WHERE `name` = 'contest_thumbcolor'");

	settype($_POST[preview], 'integer');
	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = '$_POST[preview]' WHERE `name` = 'contest_preview'");

	settype($_POST[perpage], 'integer');
	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = '$_POST[perpage]' WHERE `name` = 'contest_perpage'");

	reloadPage('?mod=contest&go=config');
}

$FSXL[title] = $FS_PHRASES[contest_config_title];

$FSXL[content] .= '
				<form action="?mod=contest&go=config" method="post">
				<input type="hidden" name="action" value="edit">
				<table border="0" cellpadding="2" cellspacing="1" width="90%" style="margin:0px auto;">
					<tr>
						<td colspan="2">
							<span style="font-size:12pt"><b>'.$FS_PHRASES[contest_config_thumbnail].'</b></span><hr>
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[contest_config_thumbsize].':</b><br>'.$FS_PHRASES[contest_config_thumbsize_sub].'</td>
						<td>
							<input class="textinput" name="contest_thumbx" style="width:30px;" value="'.$FSXL[config][contest_thumbx].'">
							x
							<input class="textinput" name="contest_thumby" style="width:30px;" value="'.$FSXL[config][contest_thumby].'">
							'.$FS_PHRASES[contest_config_pixel].'
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[contest_config_bgcolor].':</b><br>'.$FS_PHRASES[contest_config_bgcolor_sub].'</td>
						<td>
							<input class="textinput" name="bgcolor" style="width:50px;" value="'.$FSXL[config][contest_thumbcolor].'">
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<p>&nbsp;<p><span style="font-size:12pt"><b>'.$FS_PHRASES[contest_config_contest].'</b></span><hr>
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[contest_config_preview].':</b><br>'.$FS_PHRASES[contest_config_preview_sub].'</td>
						<td>
							<input class="textinput" name="preview" style="width:50px;" value="'.$FSXL[config][contest_preview].'">
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[contest_config_perpage].':</b><br>'.$FS_PHRASES[contest_config_perpage_sub].'</td>
						<td>
							<input class="textinput" name="perpage" style="width:50px;" value="'.$FSXL[config][contest_perpage].'">
						</td>
					</tr>
					<tr>
						<td colspan="2" align="right"><input type="submit" class="button" value="'.$FS_PHRASES[global_takeover].'"></td>
					</tr>
				</table>
				</form>

	';

?>