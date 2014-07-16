<?php

$FSXL[title] = $FS_PHRASES[main_style_title];

// Style Importieren
if ($_POST[action] == 'import' && $_FILES[xml][tmp_name] && (($_POST[type] == 'new' && $_POST[name]) || ($_POST[type] == 'replace' && $_POST[style] != 0)))
{
	$xml = simplexml_load_file($_FILES[xml][tmp_name]);

	// Neuer style
	if ($_POST[type] == 'new')
	{
		$index = mysql_query("INSERT INTO `$FSXL[tableset]_styles` (`id`, `name`) VALUES (NULL , '$_POST[name]')");
		if ($index)
		{
			$id = mysql_insert_id();

			foreach($xml->item AS $value)
			{
				$shortcut = mysql_real_escape_string(utf8_decode($value->shortcut));
				$name = mysql_real_escape_string(utf8_decode($value->name));
				settype($value->mod, 'integer');
				$mod = $value->mod;
				$code = utf8_decode($value->code);
				$code = htmlspecialchars_decode($code);
				$code = mysql_real_escape_string($code);

				@mysql_query("INSERT INTO `$FSXL[tableset]_templates` (`id`, `shortcut`, `name`, `code`, `styleid`, `mod`)
						VALUES (NULL, '$shortcut', '$name', '$code', $id, $mod)");
			}

			$FSXL[content] = '<div style="padding:20px; text-align:center;">'.$FS_PHRASES[main_style_imported].'</div>';
		}
		else
		{
			$FSXL[content] = '<div style="padding:20px; text-align:center;">'.$FS_PHRASES[main_style_newfailed].'</div>';
		}
	}
	// Style ersetzen
	else
	{
		settype($_POST[style], 'integer');

		foreach($xml->item AS $value)
		{
			$shortcut = mysql_real_escape_string(utf8_decode($value->shortcut));
			$name = mysql_real_escape_string(utf8_decode($value->name));
			settype($value->mod, 'integer');
			$mod = $value->mod;
			$code = utf8_decode($value->code);
			$code = htmlspecialchars_decode($code);
			$code = mysql_real_escape_string($code);

			@mysql_query("UPDATE `$FSXL[tableset]_templates`
					SET `code` = '$code'
					WHERE `styleid` = $_POST[style] AND `shortcut` = '$shortcut'");

			// Chache löschen
			@unlink('../tpl/'.$_POST[style].'_'.$shortcut.'.tpl');
			if (function_exists('apc_cache_info')) {
				$apc_name = $FSXL[config][bez].'/tpl/'.$_POST[style].'_'.$shortcut.'.tpl';
				apc_delete($apc_name);
			}
		}

		$FSXL[content] = '<div style="padding:20px; text-align:center;">'.$FS_PHRASES[main_style_imported].'</div>';
	}
}


// Neuen Style erstellen
elseif ($_POST[action] == "newstyle" && $_POST[name])
{
	settype($_POST[copy], 'integer');

	// Style erzeugen
	$index = mysql_query("INSERT INTO `$FSXL[tableset]_styles` (`id`, `name`) VALUES (NULL , '$_POST[name]')");
	if ($index)
	{
		// Templates kopieren
		$id = mysql_insert_id();
		$index = @mysql_query("SELECT * FROM `$FSXL[tableset]_templates` WHERE `styleid` = $_POST[copy]");
		while ($template = mysql_fetch_assoc($index))
		{
			$code = mysql_real_escape_string($template[code]);
			@mysql_query("INSERT INTO `$FSXL[tableset]_templates` (`id`, `shortcut`, `name`, `code`, `styleid`, `mod`)
					VALUES (NULL, '$template[shortcut]', '$template[name]', '$code', $id, $template[mod])");
		}

		$FSXL[content] = '<div style="padding:20px; text-align:center;">'.$FS_PHRASES[main_style_donenewstyle].'</div>';
	}
	else
	{
		$FSXL[content] = '<div style="padding:20px; text-align:center;">'.$FS_PHRASES[main_style_newfailed].'</div>';
	}
}


// Style löschen
elseif ($_POST[action] == "delstyle")
{
	settype($_POST[delete], 'integer');
	if ($_POST[delete] != 1)
	{
		// Template Cache löschen
		$index = @mysql_query("SELECT `styleid`, `shortcut`, `id` FROM `$FSXL[tableset]_templates` WHERE `styleid` = $_POST[delete]");
		while ($tpl = mysql_fetch_assoc($index))
		{
			@unlink('../tpl/'.$tpl[styleid].'_'.$tpl[shortcut].'.tpl');
			mysql_query("DELETE FROM `$FSXL[tableset]_template_history` WHERE `tpl` = $tpl[id]");
		}

		$index = @mysql_query("UPDATE `$FSXL[tableset]_zones` SET `style` = 1 WHERE `style` = $_POST[delete]");
		$index = @mysql_query("UPDATE `$FSXL[tableset]_userdata` SET `style` = 0 WHERE `style` = $_POST[delete]");
		$index = @mysql_query("DELETE FROM `$FSXL[tableset]_styles` WHERE id = $_POST[delete]");
		$index = @mysql_query("DELETE FROM `$FSXL[tableset]_templates` WHERE styleid = $_POST[delete]");
		$FSXL[content] = '<div style="padding:20px; text-align:center;">'.$FS_PHRASES[main_style_deleted].'</div>';
	}
	else
	{
		$FSXL[content] = '<div style="padding:20px; text-align:center;">'.$FS_PHRASES[main_style_protected].'</div>';
	}
}

else
{
	// Styles auslesen zum kopieren
	$index = @mysql_query("SELECT * FROM `$FSXL[tableset]_styles` ORDER BY `name`");
	$styleoptions1 = "";
	while ($styles = mysql_fetch_assoc($index))
	{
		$styleoptions1 .= '<option value="'.$styles[id].'">'.$styles[name].'</option>';
	}

	// Styles auslesen zum löschen
	$index = @mysql_query("SELECT * FROM `$FSXL[tableset]_styles` ORDER BY `name`");
	$styleoptions2 = "";
	while ($styles = mysql_fetch_assoc($index))
	{
		if ($styles[id] != 1)
		{
			$styleoptions2 .= '<option value="'.$styles[id].'" '.($_POST[style] == $styles[id] ? "selected" : "").'>'.$styles[name].'</option>';
		}
	}

	$FSXL[content] .= '
				<form action="?mod=main&go=style" method="post">
				<input type="hidden" name="action" value="newstyle">
				<table border="0" cellpadding="2" cellspacing="1" width="90%" style="margin:0px auto;">
					<tr>
						<td colspan="2">
							<span style="font-size:12pt"><b>'.$FS_PHRASES[main_style_newstyle].'</b></span><hr>
						</td>
					</tr>
					<tr>
						<td width="250"><b>'.$FS_PHRASES[main_style_name].':</b></td>
						<td>
							<input class="textinput" name="name" style="width:300px;">
						</td>
					</tr>
					<tr>
						<td valign="top"><b>'.$FS_PHRASES[main_style_copyof].':</b><br>'.$FS_PHRASES[main_style_copyof_sub].'</td>
						<td>
							<select name="copy" class="textinput" style="width:305px;" size="3">
								'.$styleoptions1.'
							</select>
						</td>
					</tr>
					<tr>
						<td colspan="2" align="right">
							<input type="submit" class="button" value="'.$FS_PHRASES[main_style_create].'">
						</td>
					</tr>
				</table>
				</form>


				<form action="?mod=main&go=style" method="post" onsubmit="return confirmdel();">
				<input type="hidden" name="action" value="delstyle">
				<table border="0" cellpadding="2" cellspacing="1" width="90%" style="margin:0px auto;">
					<tr>
						<td colspan="2">
							<span style="font-size:12pt"><b>'.$FS_PHRASES[main_style_deletestyle].'</b></span><hr>
						</td>
					</tr>
					<tr>
						<td width="250" valign="top"><b>'.$FS_PHRASES[main_style_style].':</b><br>'.$FS_PHRASES[main_style_del_sub].'</td>
						<td>
							<script type="text/javascript">
								function confirmdel()
								{
									check = confirm("'.$FS_PHRASES[main_style_confirmdelete].'");
									return check;
								}
							</script>
							<select name="delete" class="textinput" style="width:305px;" size="4">
								'.$styleoptions2.'
							</select>
						</td>
					</tr>
					<tr>
						<td colspan="2" align="right">
							<input type="submit" class="button" value="'.$FS_PHRASES[main_style_delete].'">
						</td>
					</tr>
				</table>
				</form>


				<form action="mod_main/styleexport.php" method="post">
				<table border="0" cellpadding="2" cellspacing="1" width="90%" style="margin:0px auto;">
					<tr>
						<td colspan="2">
							<span style="font-size:12pt"><b>'.$FS_PHRASES[main_style_export].'</b></span><hr>
						</td>
					</tr>
					<tr>
						<td width="250" valign="top"><b>'.$FS_PHRASES[main_style_style].':</b><br>'.$FS_PHRASES[main_style_export_sub].'</td>
						<td>
							<select name="style" class="textinput" style="width:305px;" size="5">
								'.$styleoptions1.'
							</select>
						</td>
					</tr>
					<tr>
						<td colspan="2" align="right">
							<input type="submit" class="button" value="'.$FS_PHRASES[main_style_exportbt].'">
						</td>
					</tr>
				</table>
				</form>


				<form action="?mod=main&go=style" method="post" enctype="multipart/form-data">
				<input type="hidden" name="action" value="import">
				<table border="0" cellpadding="2" cellspacing="1" width="90%" style="margin:0px auto;">
					<tr>
						<td colspan="2">
							<span style="font-size:12pt"><b>'.$FS_PHRASES[main_style_import].'</b></span><hr>
						</td>
					</tr>
					<tr>
						<td width="250"><b>'.$FS_PHRASES[main_style_file].':</b><br>'.$FS_PHRASES[main_style_file_sub].'</td>
						<td><input type="file" name="xml" class="textinput" style="width:300px;"></td>
					</tr>
					<tr>
						<td>
							<input type="radio" name="type" value="new" checked>
							<b>'.$FS_PHRASES[main_style_newstyle].':</b><br>'.$FS_PHRASES[main_style_newstyle_sub].'
						</td>
						<td>
							<input class="textinput" name="name" style="width:300px;">
						</td>
					</tr>
					<tr>
						<td valign="top">
							<input type="radio" name="type" value="replace">
							<b>'.$FS_PHRASES[main_style_replace].':</b><br>'.$FS_PHRASES[main_style_replace_sub].'
						</td>
						<td>
							<select name="style" class="textinput" style="width:305px;" size="4">
								'.$styleoptions1.'
							</select>
						</td>
					</tr>
					<tr>
						<td colspan="2" align="right"><input type="submit" class="button" value="'.$FS_PHRASES[main_style_importbt].'"></td>
					<tr>
				</table>
				</form>
	';
}
?>