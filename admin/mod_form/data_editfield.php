<?php

$FSXL[title] = $FS_PHRASES[form_editfield_title];


// Bearbeiten
if ($_POST[action] == "edit" && $_POST[name])
{
	settype($_POST[id], 'integer');
	settype($_POST[form], 'integer');
	settype($_POST[type], 'integer');
		
	// löschen
	if ($_POST[del])
	{
		mysql_query("DELETE FROM `$FSXL[tableset]_form_fields` WHERE `id` = '$_POST[id]'");
	}
	else
	{
		// Text erzeugen
		switch ($_POST[type])
		{
			case 1: // Radio
				foreach ($_POST[opt] AS $key => $option) {
					if ($_POST[optchk][$key]) {
						$_POST[opt][$key] = "this->" . $_POST[opt][$key];
					}
				}
				$text = implode("/boundary/", $_POST[opt]);
				break;
			default:
				$text = $_POST[text];
		}
					
		mysql_query("UPDATE `$FSXL[tableset]_form_fields` SET `title` = '$_POST[name]', `text` = '$text' WHERE `id` = '$_POST[id]'");
	}
			
	reloadPage("?mod=form&go=editform&id=$_POST[form]");
}

// Formular
else
{
	settype($_GET[id], 'integer');
	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_form_fields` WHERE `id` = '$_GET[id]'");
	$field = @mysql_fetch_assoc($index);
	
	$FSXL[content] .= '
				<form action="?mod=form&go=editfield" method="post">
				<input type="hidden" name="action" value="edit">
				<input type="hidden" name="form" value="'.$field[form].'">
				<input type="hidden" name="type" value="'.$field[type].'">
				<input type="hidden" name="id" value="'.$field[id].'">
				<table border="0" cellpadding="0" cellspacing="0" align="center" width="90%">
					<tr>
						<td width="150"><b>'.$FS_PHRASES[form_edit_type].':</b></td>
						<td>'.$FS_PHRASES[form_edit_field][$field[type]].'</td>
					</tr>
	';
	
	switch($field[type])
	{
		case 1: // Radio
			$opts = explode("/boundary/", $field[text]);
			$checks = array();
			$FSXL[content] .= '
					<tr>
						<td width="150"><b>'.$FS_PHRASES[form_addfield_name].':</b></td>
						<td><input name="name" class="textinput" style="width:400px;" value="'.$field[title].'"></td>
					</tr>
			';
			for ($i=1; $i<=10; $i++){
				if (preg_match("/^this->(.*)/", $opts[$i-1], $treffer)) {
					$opts[$i-1] = $treffer[1];
					$checks[$i] = 'checked';
				}
				$FSXL[content] .= '
					<tr>
						<td><b>'.$FS_PHRASES[form_addfield_option].' '.$i.':</b></td>
						<td>
							<input name="opt['.$i.']" class="textinput" style="width:350px;" value="'.$opts[$i-1].'">
							<input name="optchk['.$i.']" type="checkbox" '.$checks[$i].'>
						</td>
					</tr>
				';
			}
			break;

		case 2: // Trenner
			$FSXL[content] .= '
					<tr>
						<td width="150"><b>'.$FS_PHRASES[form_addfield_name].':</b></td>
						<td><input name="name" class="textinput" style="width:400px;" value="'.$field[title].'"></td>
					</tr>
					<tr>
						<td valign="top"><b>'.$FS_PHRASES[form_add_desc].':</b></td>
						<td align="right"><textarea name="text" class="textinput" style="width:400px; height:150px;">'.$field[text].'</textarea></td>
					</tr>
			';
			break;

		case 3: // Eingabefeld
			$FSXL[content] .= '
					<tr>
						<td width="150"><b>'.$FS_PHRASES[form_addfield_name].':</b></td>
						<td><input name="name" class="textinput" style="width:400px;" value="'.$field[title].'"></td>
					</tr>
			';
			break;
	}

	$FSXL[content] .= '
					<tr>
						<td><b>'.$FS_PHRASES[form_edit_delete].':</b></td>
						<td align="right">
							<div align="left" style="width:406px;"><input type="checkbox" name="del" id="delete" onClick="delMessage(\''.$FS_PHRASES[form_editfield_delmsg].'\');"></div>
						</td>
					</tr>
					<tr>
						<td colspan="2" align="right"><input type="submit" class="button" value="'.$FS_PHRASES[global_send].'"></td>
					</tr>
				</table>
				</form>
	';
}

?>