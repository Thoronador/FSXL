<?php

if ($_POST[action] == "edit")
{
	settype($_POST[articleheadlines], 'integer');
	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = $_POST[articleheadlines] WHERE `name` = 'article_headlines'");

	settype($_POST[previewlength], 'integer');
	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = $_POST[previewlength] WHERE `name` = 'article_previewlength'");
	
	$_POST[showall] = $_POST[showall] ? 1 : 0;
	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = $_POST[showall] WHERE `name` = 'article_showall'");

	reloadPage('?mod=article&go=config');
}

$FSXL[title] = $FS_PHRASES[article_config_title];

$FSXL[content] .= '
				<form action="?mod=article&go=config" method="post">
				<input type="hidden" name="action" value="edit">
				<table border="0" cellpadding="2" cellspacing="1" width="90%" style="margin:0px auto;">
					<tr>
						<td colspan="2">
							<span style="font-size:12pt"><b>'.$FS_PHRASES[article_config_article].'</b></span><hr>
						</td>
					</tr>
					<tr>
						<td width="250"><b>'.$FS_PHRASES[article_config_headlines].':</b><br>'.$FS_PHRASES[article_config_headlines_sub].'</td>
						<td>
							<input name="articleheadlines" class="textinput" style="width:50px;" value="'.$FSXL[config][article_headlines].'">
						</td>
					</tr>
					<tr>
						<td width="250"><b>'.$FS_PHRASES[article_config_previewlength].':</b><br>'.$FS_PHRASES[article_config_previewlength_sub].'</td>
						<td>
							<input name="previewlength" class="textinput" style="width:50px;" value="'.$FSXL[config][article_previewlength].'">
						</td>
					</tr>
					<tr>
						<td width="250"><b>'.$FS_PHRASES[article_config_showall].':</b><br>'.$FS_PHRASES[article_config_showall_sub].'</td>
						<td>
							<input type="checkbox" name="showall" '.($FSXL[config][article_showall]?"checked":"").'>
						</td>
					</tr>
					<tr>
						<td colspan="2" align="right"><input type="submit" class="button" value="'.$FS_PHRASES[global_takeover].'"></td>
					</tr>
				</table>
				</form>
';

?>