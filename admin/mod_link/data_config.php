<?php

if ($_POST[action] == "edit")
{
	settype($_POST[articleheadlines], 'integer');
	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = $_POST[linkheadlines] WHERE `name` = 'link_headlines'");

	reloadPage('?mod=link&go=config');
}

$FSXL[title] = $FS_PHRASES[link_config_title];

$FSXL[content] .= '
				<form action="?mod=link&go=config" method="post">
				<input type="hidden" name="action" value="edit">
				<table border="0" cellpadding="2" cellspacing="1" width="90%" style="margin:0px auto;">
					<tr>
						<td colspan="2">
							<span style="font-size:12pt"><b>'.$FS_PHRASES[link_config_links].'</b></span><hr>
						</td>
					</tr>
					<tr>
						<td width="250"><b>'.$FS_PHRASES[link_config_headlines].':</b><br>'.$FS_PHRASES[link_config_headlines_sub].'</td>
						<td>
							<input name="linkheadlines" class="textinput" style="width:50px;" value="'.$FSXL[config][link_headlines].'">
						</td>
					</tr>
					<tr>
						<td colspan="2" align="right"><input type="submit" class="button" value="'.$FS_PHRASES[global_takeover].'"></td>
					</tr>
				</table>
				</form>
';

?>