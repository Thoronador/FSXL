<?php

if ($_POST[action] == "edit")
{
	settype($_POST[galleryheadlines], 'integer');
	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = $_POST[galleryheadlines] WHERE `name` = 'gallery_headlines'");

	settype($_POST[thumbx], 'integer');
	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = $_POST[thumbx] WHERE `name` = 'gallery_thumbx'");

	settype($_POST[thumby], 'integer');
	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = $_POST[thumby] WHERE `name` = 'gallery_thumby'");

	settype($_POST[cols], 'integer');
	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = $_POST[cols] WHERE `name` = 'gallery_cols'");

	$_POST[potm] = $_POST[potm] ? 1 : 0;
	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = $_POST[potm] WHERE `name` = 'gallery_potmsingle'");

	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = '$_POST[colors]' WHERE `name` = 'gallery_colors'");

	settype($_POST[timed_thumby], 'integer');
	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = $_POST[timed_thumby] WHERE `name` = 'timed_ysize'");

	settype($_POST[timed_thumbx], 'integer');
	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = $_POST[timed_thumbx] WHERE `name` = 'timed_xsize'");

	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = '$_POST[bgcolor]' WHERE `name` = 'timed_color'");

	$_POST[showall] = $_POST[showall] ? 1 : 0;
	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = $_POST[showall] WHERE `name` = 'gallery_showall'");

	reloadPage('?mod=gallery&go=config');
}

$FSXL[title] = $FS_PHRASES[gallery_config_title];

$FSXL[content] .= '
				<form action="?mod=gallery&go=config" method="post">
				<input type="hidden" name="action" value="edit">
				<table border="0" cellpadding="2" cellspacing="1" width="90%" style="margin:0px auto;">
					<tr>
						<td colspan="2">
							<span style="font-size:12pt"><b>'.$FS_PHRASES[gallery_config_article].'</b></span><hr>
						</td>
					</tr>
					<tr>
						<td width="250"><b>'.$FS_PHRASES[gallery_config_headlines].':</b><br>'.$FS_PHRASES[gallery_config_headlines_sub].'</td>
						<td>
							<input name="galleryheadlines" class="textinput" style="width:50px;" value="'.$FSXL[config][gallery_headlines].'">
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[gallery_config_potm].':</b><br>'.$FS_PHRASES[gallery_config_potm_sub].'</td>
						<td>
							<input type="checkbox" name="potm" '.($FSXL[config][gallery_potmsingle] == 1 ? "checked" : "").'>
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[gallery_galleries_cols].':</b><br>'.$FS_PHRASES[gallery_galleries_cols_sub].'</td>
						<td>
							<input class="textinput" name="cols" style="width:50px;" value="'.$FSXL[config][gallery_cols].'">
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[gallery_galleries_thumbsize].':</b><br>'.$FS_PHRASES[gallery_galleries_thumbsize_sub].'</td>
						<td>
							<input class="textinput" name="thumbx" style="width:30px;" value="'.$FSXL[config][gallery_thumbx].'">
							x
							<input class="textinput" name="thumby" style="width:30px;" value="'.$FSXL[config][gallery_thumby].'">
							'.$FS_PHRASES[gallery_galleries_pixel].'
						</td>
					</tr>
					<tr>
						<td valign="top"><b>'.$FS_PHRASES[gallery_config_color].':</b><br>'.$FS_PHRASES[gallery_config_color_sub].'</td>
						<td>
							<textarea name="colors" class="textinput" style="width:300px; height:100px;">'.$FSXL[config][gallery_colors].'</textarea>
						</td>
					</tr>
					<tr>
						<td width="250"><b>'.$FS_PHRASES[gallery_config_showall].':</b><br>'.$FS_PHRASES[gallery_config_showall_sub].'</td>
						<td>
							<input type="checkbox" name="showall" '.($FSXL[config][gallery_showall]?"checked":"").'>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<span style="font-size:12pt"><b>'.$FS_PHRASES[gallery_config_timed].'</b></span><hr>
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[gallery_galleries_thumbsize].':</b><br>'.$FS_PHRASES[gallery_galleries_thumbsize_sub2].'</td>
						<td>
							<input class="textinput" name="timed_thumbx" style="width:30px;" value="'.$FSXL[config][timed_xsize].'">
							x
							<input class="textinput" name="timed_thumby" style="width:30px;" value="'.$FSXL[config][timed_ysize].'">
							'.$FS_PHRASES[gallery_galleries_pixel].'
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[gallery_config_bgcolor].':</b><br>'.$FS_PHRASES[gallery_config_bgcolor_sub].'</td>
						<td>
							<input class="textinput" name="bgcolor" style="width:50px;" value="'.$FSXL[config][timed_color].'">
						</td>
					</tr>
					<tr>
						<td colspan="2" align="right"><input type="submit" class="button" value="'.$FS_PHRASES[global_takeover].'"></td>
					</tr>
				</table>
				</form>

	';

?>