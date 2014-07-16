<?php

if ($_POST[action] == "edit")
{
	settype($_POST[interval], 'integer');
	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = $_POST[interval] WHERE `name` = 'ticker_interval'");

	reloadPage('?mod=ticker&go=config');
}

$FSXL[title] = $FS_PHRASES[ticker_config_title];

$FSXL[content] .= '
				<form action="?mod=ticker&go=config" method="post">
				<input type="hidden" name="action" value="edit">
				<table border="0" cellpadding="0" cellspacing="0" align="center" width="90%">
					<tr>
						<td colspan="2">
							<span style="font-size:12pt"><b>'.$FS_PHRASES[ticker_config_ticker].'</b></span><hr>
						</td>
					</tr>
					<tr>
						<td class="form"><b>'.$FS_PHRASES[ticker_config_interval].':</b><br>'.$FS_PHRASES[ticker_config_interval_sub].'</td>
						<td>
							<input name="interval" class="textinput" style="width:50px;" value="'.$FSXL[config][ticker_interval].'">
						</td>
					</tr>
					<tr>
						<td colspan="2" align="right"><input type="submit" class="button" value="'.$FS_PHRASES[global_takeover].'"></td>
					</tr>
				</table>
				</form>

';

?>