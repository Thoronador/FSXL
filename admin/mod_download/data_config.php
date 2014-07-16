<?php

if ($_POST[action] == "edit")
{
	settype($_POST[headlines], 'integer');
	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = $_POST[headlines] WHERE `name` = 'download_headlines'");

	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = '$_POST[prefix]' WHERE `name` = 'dl_prefix'");

	reloadPage('?mod=download&go=config');
}

$FSXL[title] = $FS_PHRASES[download_config_title];

$FSXL[content] .= '
				<form action="?mod=download&go=config" method="post">
				<input type="hidden" name="action" value="edit">
				<table border="0" cellpadding="2" cellspacing="1" width="90%" style="margin:0px auto;">
					<tr>
						<td colspan="2">
							<span style="font-size:12pt"><b>'.$FS_PHRASES[download_config_download].'</b></span><hr>
						</td>
					</tr>
					<tr>
						<td class="form"><b>'.$FS_PHRASES[download_config_headlines].':</b><br>'.$FS_PHRASES[download_config_headlines_sub].'</td>
						<td>
							<input name="headlines" class="textinput" style="width:50px;" value="'.$FSXL[config][download_headlines].'">
						</td>
					</tr>
					<tr>
						<td class="form"><b>'.$FS_PHRASES[download_config_prefix].':</b><br>'.$FS_PHRASES[download_config_prefix_sub].'</td>
						<td>
							<input name="prefix" class="textinput" style="width:300px;" value="'.$FSXL[config][dl_prefix].'">
						</td>
					</tr>
					<tr>
						<td colspan="2" align="right"><input type="submit" class="button" value="'.$FS_PHRASES[global_takeover].'"></td>
					</tr>
				</table>
				</form>

';

?>