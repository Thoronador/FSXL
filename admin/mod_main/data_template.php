<?php

$FSXL[title] = $FS_PHRASES[main_template_title];

if (!$_POST[style])
{
	$_POST[style] = 1;
}
else
{
	settype($_POST[style], 'integer');
}

// Styles auslesen
$index = @mysql_query("SELECT * FROM `$FSXL[tableset]_styles` ORDER BY `name`");
$styleoptions = "";
while ($styles = mysql_fetch_assoc($index))
{
	$styleoptions .= '<option value="'.$styles[id].'" '.($_POST[style] == $styles[id] ? "selected" : "").' onclick="document.styleform.submit();">'.$styles[name].'</option>';
	if ($_POST[style] == $styles[id])
	{
		$stylename = $styles[name];
	}
}

// Templates auslesen
$index = @mysql_query("SELECT * FROM `$FSXL[tableset]_templates` WHERE `styleid` = $_POST[style] ORDER BY `mod`, `name`");
$templateoptions = "";
while ($template = mysql_fetch_assoc($index))
{
	// Hintergrundfarbe wechseln
	if ($template[mod] != $prevmod)
	{
		if ($background == '#FFFFFF') $background = '#DDDDDD';
		else $background = '#FFFFFF';
		$prevmod = $template[mod];
	}
	if ($_POST[templateid]) $_POST[template] = $_POST[templateid];
	$templateoptions .= '<option value="'.$template[id].'" '.($_POST[template] == $template[id] ? "selected" : "").' style="background-color:'.$background.';">'.$template[name].'</option>';
}

// Template editieren
if ($_POST[templateid] && $_POST[templatecode])
{
	// History estellen
	$index = mysql_query("SELECT `code` FROM `$FSXL[tableset]_templates` WHERE `id` = $_POST[templateid]");
	$history = mysql_fetch_assoc($index);
	$time = time();
	mysql_query("INSERT INTO `$FSXL[tableset]_template_history` (`id`, `tpl`, `autor`, `date`, `code`) 
			VALUES (NULL, $_POST[templateid], ".$_SESSION[user]->userid.", $time, '$history[code]')");

	settype($_POST[templateid], 'integer');
	$_POST[templatecode] = str_replace('&lt;', '<', $_POST[templatecode]);
	$_POST[templatecode] = str_replace('&gt;', '>', $_POST[templatecode]);

	$index = @mysql_query("UPDATE `$FSXL[tableset]_templates` SET `code` = '$_POST[templatecode]' WHERE `id` = $_POST[templateid]");

	$cachecode = str_replace('\n', "\n", $_POST[templatecode]);
	$cachecode = str_replace('\r', "\r", $cachecode);
	
	// Template Cache aktualisieren
	$index = @mysql_query("SELECT `styleid`, `shortcut` FROM `$FSXL[tableset]_templates` WHERE `id` = $_POST[templateid]");
	$tpl = mysql_fetch_assoc($index);
	@unlink('../tpl/'.$tpl[styleid].'_'.$tpl[shortcut].'.tpl');
	$fp = fopen('../tpl/'.$tpl[styleid].'_'.$tpl[shortcut].'.tpl', 'w');
	fwrite($fp, stripslashes($cachecode));
	fclose($fp);

	// APC Cache aktualisieren
	if (function_exists('apc_cache_info'))
	{
		$apc_name = $FSXL[config][bez].'/tpl/'.$tpl[styleid].'_'.$tpl[shortcut].'.tpl';
		apc_store($apc_name, stripslashes($cachecode));

	}

	if ($index)
	{
		// History löschen
		$index = mysql_query("SELECT `id` FROM `$FSXL[tableset]_template_history` WHERE `tpl` = $_POST[templateid]");
		if (mysql_num_rows($index) > $FSXL[config][tpl_history_steps])
		{
			$limit = mysql_num_rows($index) - $FSXL[config][tpl_history_steps];
			mysql_query("DELETE FROM `$FSXL[tableset]_template_history` WHERE `tpl` = $_POST[templateid] ORDER BY `date` ASC LIMIT $limit");
		}

		$templatehtml = '
					<tr>
						<td>
							'.$FS_PHRASES[main_template_done].'
						</td>
					</tr>
		';
	}
	else
	{
		$templatehtml = '
					<tr>
						<td>
							'.$FS_PHRASES[main_template_notdone].'
						</td>
					</tr>
		';
	}
}

// Template anzeigen
if ($_POST[template] || $_POST[templateid])
{
	if ($_POST[templateid]) $_POST[template] = $_POST[templateid];
	settype($_POST[template], 'integer');
	$index = @mysql_query("SELECT * FROM `$FSXL[tableset]_templates` WHERE `id` = $_POST[template]");
	$template = mysql_fetch_assoc($index);

	// Modname lesen
	$index = mysql_query("SELECT `name` FROM `$FSXL[tableset]_mod` WHERE `id` = '$template[mod]'");
	$mod = mysql_fetch_assoc($index);
	@include('mod_'.$mod[name].'/info.inc.php');
	$modname = $FSXL[mod][$mod[name]][title];

	// verfügbare Variablen auslesen
	$index = @mysql_query("SELECT * FROM `$FSXL[tableset]_templatevars` WHERE `intemplate` = '$template[shortcut]'");
	$vars = "";
	while ($arr = mysql_fetch_assoc($index))
	{
		if (preg_match("/".$arr[name]."/", $template[code]))
		{
			$vars .= $arr[name] . ' ';
		}
		else
		{
			$vars .= '<i>' . $arr[name] . '</i> ';
		}
	}
	$template[code] = str_replace('<', '&lt;', $template[code]);
	$template[code] = str_replace('>', '&gt;', $template[code]);

	$templatehtml = $templatehtml;
	$templatehtml .= '
					<tr>
						<td>
							<form action="?mod=main&go=template" method="post">
							<input type="hidden" name="action" value="edit">
							<input type="hidden" name="style" value="'.$_POST[style].'">
							<input type="hidden" name="templateid" value="'.$_POST[template].'">
							<span style="float:right">'.$FS_PHRASES[main_template_partof].' <i>'.$modname.'</i></span>
							<b>'.$stylename.' > '.$template[name].':</b><br>
							'.$FS_PHRASES[main_template_vars].': '.$vars.'
							<textarea name="templatecode" id="templatecode" class="textinput" style="width:560px; height:500px;">'.$template[code].'</textarea><br>
							<input type="submit" class="button" value="'.$FS_PHRASES[global_send].'" style="float:right;">
							<input type="reset" class="button" value="'.$FS_PHRASES[main_template_reset].'" style="float:right; margin-right:4px;">
							</form>
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[main_template_history].':</b><hr></td>
					</tr>
	';

	// History lesen
	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_template_history` WHERE `tpl` = $_POST[template] ORDER BY `date` DESC");
	while ($history = mysql_fetch_assoc($index))
	{
		$history[code] = str_replace('<', '&lt;', $history[code]);
		$history[code] = str_replace('>', '&gt;', $history[code]);

		$index2 = mysql_query("SELECT `name` FROM `$FSXL[tableset]_user` WHERE `id` = $history[autor]");
		$userinfo = mysql_fetch_assoc($index2);
		$templatehtml .= '
					<tr>
						<td>
							'.$FS_PHRASES[main_template_changed].' '.date($FSXL[config][dateformat], $history[date]).'
							'.$FS_PHRASES[main_template_from].' '.$userinfo[name].'
							<input type="button" class="button" value="'.$FS_PHRASES[main_template_show].'" onClick="document.getElementById(\'hs'.$history[id].'\').style.display == \'inline\' ? document.getElementById(\'hs'.$history[id].'\').style.display = \'none\' : document.getElementById(\'hs'.$history[id].'\').style.display = \'inline\'">
							<input type="button" class="button" value="'.$FS_PHRASES[main_template_copy].'" onClick="document.getElementById(\'templatecode\').value = document.getElementById(\'hs'.$history[id].'\').value">
							<br>
							<textarea class="textinput" id="hs'.$history[id].'" style="width:555px; height:200px; display:none;">'.$history[code].'</textarea>
						</td>
					</tr>
		';
	}
}

$FSXL[content] .= '
				<div>
				<table border="0" cellpadding="0" cellspacing="0" width="95%" align="center">
					<tr>
						<td>
							<form action="?mod=main&go=template" method="post" name="styleform">
							<input type="hidden" name="action" value="choosestyle">
							<b>'.$FS_PHRASES[main_template_choosestyle].':</b>
							<select name="style" class="textinput" style="width:300px;">
								'.$styleoptions.'
							</select>
							<input type="submit" class="button" value="OK">
							</form>
						</td>
					</tr>
					<tr>
						<td>
							<form action="?mod=main&go=template" method="post">
							<input type="hidden" name="action" value="choosetemplate">
							<input type="hidden" name="style" value="'.$_POST[style].'">
							<b>'.$FS_PHRASES[main_template_choosetemplate].' ('.$stylename.'):</b><br>
							<select name="template" class="textinput" size="8" style="width:100%;">
								'.$templateoptions.'
							</select><br>
							<input type="submit" class="button" value="OK" style="float:right;">
							</form>
						</td>
					</tr>
					'.$templatehtml.'
				</table>
				</div>
';

?>