<?php

$FSXL[title] = $FS_PHRASES[main_fscode_title];

// Bearbeiten / Löschen
if ($_POST[action] == 'editcode' && $_POST[name] && $_POST[code])
{
	settype($_POST[tagid], 'integer');
	
	// Löschen
	if ($_POST[del])
	{
		$chk = mysql_query("DELETE FROM `$FSXL[tableset]_fscodes` WHERE `id` = $_POST[tagid]");
		if ($chk) {
			mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = `value`-1 WHERE `name` = 'fscodes'");
			$FSXL[content] .= '<div style="padding:20px; text-align:center;">'.$FS_PHRASES[main_fscode_deleted].'</div>';
		}
	}
	// Bearbeiten
	else
	{
		mysql_query("UPDATE `$FSXL[tableset]_fscodes` SET `name` = '$_POST[name]', `code` = '$_POST[code]' WHERE `id` = $_POST[tagid]");
		$FSXL[content] .= '<div style="padding:20px; text-align:center;">'.$FS_PHRASES[main_fscode_edited].'</div>';		
	}
}

// Bearbeiten Formular
elseif ($_GET[id])
{
	settype($_GET[id], 'integer');
	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_fscodes` WHERE `id` = $_GET[id]");
	if (mysql_num_rows($index) > 0)
	{
		$fscode = mysql_fetch_assoc($index);
	
		$FSXL[content] .= '
				<form action="?mod=main&go=fscode" method="post">
				<input type="hidden" name="action" value="editcode">
				<input type="hidden" name="tagid" value="'.$_GET[id].'">
				<table border="0" cellpadding="2" cellspacing="1" width="90%" style="margin:0px auto;">
					<tr>
						<td colspan="2">
							<span style="font-size:12pt"><b>'.$FS_PHRASES[main_fscode_select].'</b></span><hr>
						</td>
					</tr>
					<tr>
						<td width="250"><b>'.$FS_PHRASES[main_fscode_tag].':</b><br>'.$FS_PHRASES[main_fscode_tag_sub].'</td>
						<td><input class="textinput" name="name" style="width:300px;" value="'.$fscode[name].'"></td>
					</tr>
					<tr>
						<td valign="top"><b>'.$FS_PHRASES[main_fscode_code].':</b><br>'.$FS_PHRASES[main_fscode_code_sub].'</td>
						<td><textarea class="textinput" name="code" style="width:300px; height:150px;">'.$fscode[code].'</textarea></td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[main_tplvars_delete].':</b></td>
						<td>
							<input type="checkbox" name="del" id="delete" onClick="delMessage(\''.$FS_PHRASES[main_fscode_delmessage].'\');">
						</td>
					</tr>
					<tr>
						<td colspan="2" align="right">
							<input type="submit" class="button" value="'.$FS_PHRASES[global_send].'">
						</td>
					</tr>
				</table>
				</form>
		';
	}
}

// Code hinzufügen
elseif ($_POST[action] == 'addcode' && $_POST[name] && $_POST[code])
{
	$chk = mysql_query("INSERT INTO `$FSXL[tableset]_fscodes` (`id`, `name`, `code`)
						VALUES (NULL, '$_POST[name]', '$_POST[code]')");
	if ($chk) {
		mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = `value`+1 WHERE `name` = 'fscodes'");
		$FSXL[content] .= '<div style="padding:20px; text-align:center;">'.$FS_PHRASES[main_fscode_added].'</div>';
	}
	else {
		$FSXL[content] .= '<div style="padding:20px; text-align:center;">'.$FS_PHRASES[main_fscode_addfailed].'</div>';
	}
}

// Übersicht
else
{
	$FSXL[content] .= '
				<form action="?mod=main&go=fscode" method="post">
				<input type="hidden" name="action" value="addcode">
				<table border="0" cellpadding="2" cellspacing="1" width="90%" style="margin:0px auto;">
					<tr>
						<td colspan="2">
							<span style="font-size:12pt"><b>'.$FS_PHRASES[main_fscode_addcode].'</b></span><hr>
						</td>
					</tr>
					<tr>
						<td width="250"><b>'.$FS_PHRASES[main_fscode_tag].':</b><br>'.$FS_PHRASES[main_fscode_tag_sub].'</td>
						<td><input class="textinput" name="name" style="width:300px;"></td>
					</tr>
					<tr>
						<td valign="top"><b>'.$FS_PHRASES[main_fscode_code].':</b><br>'.$FS_PHRASES[main_fscode_code_sub].'</td>
						<td><textarea class="textinput" name="code" style="width:300px; height:150px;"></textarea></td>
					</tr>
					<tr>
						<td colspan="2" align="right">
							<input type="submit" class="button" value="'.$FS_PHRASES[global_send].'">
						</td>
					</tr>
				</table>
				</form>
				<table border="0" cellpadding="2" cellspacing="1" width="90%" style="margin:0px auto;">
					<tr>
						<td colspan="2" style="padding:0px;"><span style="font-size:12pt;"><b>'.$FS_PHRASES[main_fscode_select].'</b></span><hr></td>
					</tr>
					<tr>
						<td class="alt0"><b>'.$FS_PHRASES[main_fscode_tag].'</b></td>
					</tr>
	';

	$index = mysql_query("SELECT `id`, `name` FROM `$FSXL[tableset]_fscodes` ORDER BY `name`");
	while ($fscode = mysql_fetch_assoc($index))
	{
		$i++;
		$FSXL[content] .= '
					<tr>
						<td class="alt'.($i%2==0?1:2).'"><a href="?mod=main&go=fscode&id='.$fscode[id].'">'.$fscode[name].'</a></td>
					</tr>
		';
	}

	$FSXL[content] .= '
				</table>
	';
}

?>