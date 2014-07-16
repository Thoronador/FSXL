<?php

$FSXL[title] = $FS_PHRASES[form_edit_title];

// Fragebogen bearbeiten
if ($_POST[action] == 'edit' && $_POST[name])
{
	settype($_POST[id], 'integer');

	// löschen
	if ($_POST[del])
	{
		mysql_query("DELETE FROM `$FSXL[tableset]_forms` WHERE `id` = '$_POST[id]'");
		mysql_query("DELETE FROM `$FSXL[tableset]_form_fields` WHERE `form` = '$_POST[id]'");
		mysql_query("DELETE FROM `$FSXL[tableset]_form_results` WHERE `form` = '$_POST[id]'");
		$FSXL[content] = '<div style="padding:20px; text-align:center;">'.$FS_PHRASES[form_edit_deleted].'</div>';
	}
	// bearbeiten
	else
	{
		$sdate = mktime($_POST[shour], $_POST[smin], 0, $_POST[smonth], $_POST[sday], $_POST[syear]);
		$edate = mktime($_POST[ehour], $_POST[emin], 0, $_POST[emonth], $_POST[eday], $_POST[eyear]);

		mysql_query("UPDATE `$FSXL[tableset]_forms` SET `title` = '$_POST[name]', `desc` = '$_POST[desc]', `start` = '$sdate', `end` = '$edate'
					WHERE `id` = $_POST[id]");

		$FSXL[content] = '<div style="padding:20px; text-align:center;">'.$FS_PHRASES[form_edit_edited].'</div>';
	}
}

// Positionen aktualisieren
elseif ($_POST[action] == 'updatepos')
{
	settype($_POST[id], 'integer');
	foreach($_POST[pos] AS $id => $value) {
		settype($id, 'integer');
		settype($value, 'integer');
		mysql_query("UPDATE `$FSXL[tableset]_form_fields` SET `pos` = '$value' WHERE `id` = '$id'");
	}
	reloadPage("?mod=form&go=editform&id=$_POST[id]");
}

// Formular anzeigen
elseif ($_GET[id])
{
	settype($_GET[id], 'integer');
	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_forms` WHERE `id` = $_GET[id]");
	$form = mysql_fetch_assoc($index);

	$FSXL[content] .= '
				<form action="?mod=form&go=editform" method="post">
				<input type="hidden" name="action" value="edit">
				<input type="hidden" name="id" value="'.$form[id].'">
				<table border="0" cellpadding="0" cellspacing="0" align="center" width="90%">
					<tr>
						<td><b>'.$FS_PHRASES[form_add_name].':</b></td>
						<td align="right"><input name="name" class="textinput" style="width:400px;" value="'.$form[title].'"></td>
					</tr>
					<tr>
						<td valign="top"><b>'.$FS_PHRASES[form_add_desc].':</b></td>
						<td align="right"><textarea name="desc" class="textinput" style="width:400px; height:150px;">'.$form[desc].'</textarea></td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[form_add_startdate].':</b><br><span class="small">'.$FS_PHRASES[form_add_dateformat].'</span></td>
						<td valign="top" align="right">
							<div align="left" style="width:406px;">
								<input class="textinput" name="sday" style="width:20px;" value="'.date("d", $form[start]).'">
								<input class="textinput" name="smonth" style="width:20px;" value="'.date("m", $form[start]).'">
								<input class="textinput" name="syear" style="width:40px;" value="'.date("Y", $form[start]).'"> -
								<input class="textinput" name="shour" style="width:20px;" value="'.date("H", $form[start]).'">
								<input class="textinput" name="smin" style="width:20px;" value="'.date("i", $form[start]).'">
							</div>
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[form_add_enddate].':</b><br><span class="small">'.$FS_PHRASES[form_add_dateformat].'</span></td>
						<td valign="top" align="right">
							<div align="left" style="width:406px;">
								<input class="textinput" name="eday" style="width:20px;" value="'.date("d", $form[end]).'">
								<input class="textinput" name="emonth" style="width:20px;" value="'.date("m", $form[end]).'">
								<input class="textinput" name="eyear" style="width:40px;" value="'.date("Y", $form[end]).'"> -
								<input class="textinput" name="ehour" style="width:20px;" value="'.date("H", $form[end]).'">
								<input class="textinput" name="emin" style="width:20px;" value="'.date("i", $form[end]).'">
							</div>
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[form_edit_delete].':</b></td>
						<td align="right">
							<div align="left" style="width:406px;"><input type="checkbox" name="del" id="delete" onClick="delMessage(\''.$FS_PHRASES[form_edit_delmsg].'\');"></div>
						</td>
					</tr>
					<tr>
						<td colspan="2" align="right"><input type="submit" class="button" value="'.$FS_PHRASES[global_send].'"></td>
					</tr>
				</table>
				</form>
				<form action="?mod=form&go=addfield" method="post">
				<input type="hidden" name="id" value="'.$form[id].'">
				<table border="0" cellpadding="0" cellspacing="0" align="center" width="90%">
					<tr>
						<td colspan="2"><span style="font-size:12pt;"><b>'.$FS_PHRASES[form_edit_fields].'</b></span><hr></td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[form_edit_addfield].':</b></td>
						<td align="right">
							<input type="submit" class="button" style="float:right; margin-left:5px;" value="'.$FS_PHRASES[form_edit_add].'">
							<select name="type" class="textinput" style="width:300px;">
								<option value="0">'.$FS_PHRASES[form_edit_field_select].'</option>
								<option value="0">---------------------------------</option>
	';	
	foreach ($FS_PHRASES[form_edit_field] AS $key => $value) {
		$FSXL[content] .= '<option value="'.$key.'">'.$value.'</option>';
	}
	$FSXL[content] .= '
							</select>
						</td>
					</tr>
				</table>
				</form>
				</table>
				</form>
				<p/>
				<form action="?mod=form&go=editform" method="post">
				<input type="hidden" name="action" value="updatepos">
				<input type="hidden" name="id" value="'.$form[id].'">
				<table border="0" cellpadding="2" cellspacing="1" align="center" width="90%">
					<tr>
						<td class="alt0" align="center"><b>'.$FS_PHRASES[form_edit_position].'</b></td>
						<td class="alt0" align="center"><b>'.$FS_PHRASES[form_addfield_name].'</b></td>
						<td class="alt0" align="center"><b>'.$FS_PHRASES[form_edit_type].'</b></td>
					</tr>
	';
	
	// Felder auslesen
	$index = mysql_query("SELECT `id`, `title`, `pos`, `type` FROM `$FSXL[tableset]_form_fields` WHERE `form` = '$_GET[id]' ORDER BY `pos` ASC");
	while($field = mysql_fetch_assoc($index))
	{
		$i++;
		$FSXL[content] .= '
					<tr>
						<td class="alt'.($i%2==0?1:2).'" width="50" align="center">
							<input class="textinput" name="pos['.$field[id].']" style="width:30px;" value="'.$field[pos].'">
						</td>
						<td class="alt'.($i%2==0?1:2).'"><a href="?mod=form&go=editfield&id='.$field[id].'">'.$field[title].'</a></td>
						<td class="alt'.($i%2==0?1:2).'">'.$FS_PHRASES[form_edit_field][$field[type]].'</td>
					</tr>
		';
	}
	
	$FSXL[content] .= '
					<tr>
						<td colspan="3" align="right"><input type="submit" class="button" value="'.$FS_PHRASES[global_send].'"></td>
					</tr>
				</table>
				</form>
	';
}

// Übersicht
else
{
	$FSXL[content] .= '
				<div>
				<table border="0" cellpadding="2" cellspacing="1" width="90%" style="margin:0px auto;">
					<tr>
						<td colspan="4"><span style="font-size:12pt;"><b>'.$FS_PHRASES[form_edit_select].'</b></span><hr></td>
					</tr>
					<tr>
						<td>
							<table border="0" cellpadding="2" cellspacing="1" width="100%">
								<tr>
									<td class="alt0"><b>'.$FS_PHRASES[form_add_name].'</b></td>
									<td class="alt0" align="center"><b>'.$FS_PHRASES[form_add_startdate].'</b></td>
									<td class="alt0" align="center"><b>'.$FS_PHRASES[form_add_enddate].'</b></td>
									<td class="alt0" align="center"><b>'.$FS_PHRASES[form_edit_link].'</b></td>
								</tr>
	';

	// Liste
	$index = mysql_query("SELECT `id`, `title`, `start`, `end` FROM `$FSXL[tableset]_forms` ORDER BY `start` DESC");
	while ($form = mysql_fetch_assoc($index))
	{
		$i++;
		$FSXL[content] .= '
					<tr>
						<td class="alt'.($i%2==0?1:2).'"><a href="?mod=form&go=editform&id='.$form[id].'">'.$form[title].'</a></td>
						<td class="alt'.($i%2==0?1:2).'" align="center">'.date($FSXL[config][dateformat], $form[start]).'</td>
						<td class="alt'.($i%2==0?1:2).'" align="center">'.date($FSXL[config][dateformat], $form[end]).'</td>
						<td class="alt'.($i%2==0?1:2).'" align="center"><a href="../index.php?section=form&id='.$form[id].'" target="_blank"><img border="0" src="images/'.$FSXL[style].'_link.gif" alt=""></a></td>
					</tr>
		';
	}

	$FSXL[content] .= '
							</td>
						</tr>
				</table>
				</div>
	';
}

?>