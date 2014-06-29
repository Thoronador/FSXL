<?php

if ($_POST[action] == "edit")
{
	settype($_POST[thumbx], 'integer');
	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = '$_POST[thumbx]' WHERE `name` = 'shoplt_thumbx'");

	settype($_POST[thumby], 'integer');
	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = '$_POST[thumby]' WHERE `name` = 'shoplt_thumby'");

	settype($_POST[order], 'integer');
	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = '$_POST[order]' WHERE `name` = 'shoplt_order'");

	reloadPage('?mod=shoplt&go=config');
}

$FSXL[title] = $FS_PHRASES[gallery_config_title];

$FSXL[content] .= '
				<form action="?mod=shoplt&go=config" method="post">
				<input type="hidden" name="action" value="edit">
				<table border="0" cellpadding="2" cellspacing="1" width="90%" style="margin:0px auto;">
					<tr>
						<td colspan="2"><span style="font-size:12pt;"><b>'.$FS_PHRASES[shoplt_config_article].'</b></span><hr></td>
					</tr>
					<tr>
						<td class="form" width="200"><b>'.$FS_PHRASES[shoplt_config_thumbsize].':</b><br>'.$FS_PHRASES[shoplt_config_thumbsize_sub].'</td>
						<td>
							<input name="thumbx" class="textinput" style="width:50px;" value="'.$FSXL[config][shoplt_thumbx].'"> x
							<input name="thumby" class="textinput" style="width:50px;" value="'.$FSXL[config][shoplt_thumby].'">
						</td>
					</tr>
					<tr>
						<td class="form"><b>'.$FS_PHRASES[shoplt_config_order].':</b><br>'.$FS_PHRASES[shoplt_config_order_sub].'</td>
						<td>
							<input name="order" type="radio" value="1" '.($FSXL[config][shoplt_order]==1?"checked":"").'> '.$FS_PHRASES[shoplt_config_alpha].'<br>
							<input name="order" type="radio" value="2" '.($FSXL[config][shoplt_order]==2?"checked":"").'> '.$FS_PHRASES[shoplt_config_newest].'
						</td>
					</tr>
					<tr>
						<td colspan="2" align="right"><input type="submit" class="button" value="'.$FS_PHRASES[global_takeover].'"></td>
					</tr>
				</table>
				</form>

';

?>