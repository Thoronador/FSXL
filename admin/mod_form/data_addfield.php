<?php

$FSXL[title] = $FS_PHRASES[form_addfield_title];


// Hinzufügen
if ($_POST[action] == "add" && $_POST[name])
{
	settype($_POST[form], 'integer');
	settype($_POST[type], 'integer');
	
	// Position ermitteln
	$index = mysql_query("SELECT `pos` FROM `$FSXL[tableset]_form_fields` WHERE `form` = '$_POST[form]' ORDER BY `pos` DESC LIMIT 1");
	if (mysql_num_rows($index) > 0) {
		$nextpos = mysql_result($index, 0, 'pos') + 1;
	} else {
		$nextpos = 1;
	}
	
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
				
	mysql_query("INSERT INTO `$FSXL[tableset]_form_fields` (`id`, `form`, `type`, `title`, `text`, `pos`) 
			VALUES (NULL, '$_POST[form]', '$_POST[type]', '$_POST[name]', '$text', '$nextpos')");
			
	reloadPage("?mod=form&go=editform&id=$_POST[form]");
}

// Formular
else
{
	$FSXL[content] .= '
				<form action="?mod=form&go=addfield" method="post">
				<input type="hidden" name="action" value="add">
				<input type="hidden" name="form" value="'.$_POST[id].'">
				<input type="hidden" name="type" value="'.$_POST[type].'">
				<table border="0" cellpadding="0" cellspacing="0" align="center" width="90%">
					<tr>
						<td width="150"><b>'.$FS_PHRASES[form_edit_type].':</b></td>
						<td>'.$FS_PHRASES[form_edit_field][$_POST[type]].'</td>
					</tr>
	';
	
	switch($_POST[type])
	{
		case 1: // Radio
			$FSXL[content] .= '
					<tr>
						<td width="150"><b>'.$FS_PHRASES[form_addfield_name].':</b></td>
						<td><input name="name" class="textinput" style="width:400px;"></td>
					</tr>
			';
			for ($i=1; $i<=10; $i++){
				$FSXL[content] .= '
					<tr>
						<td><b>'.$FS_PHRASES[form_addfield_option].' '.$i.':</b></td>
						<td>
							<input name="opt['.$i.']" class="textinput" style="width:350px;">
							<input name="optchk['.$i.']" type="checkbox">
						</td>
					</tr>
				';
			}
			break;

		case 2: // Trenner
			$FSXL[content] .= '
					<tr>
						<td width="150"><b>'.$FS_PHRASES[form_addfield_name].':</b></td>
						<td><input name="name" class="textinput" style="width:400px;"></td>
					</tr>
					<tr>
						<td valign="top"><b>'.$FS_PHRASES[form_add_desc].':</b></td>
						<td align="right"><textarea name="text" class="textinput" style="width:400px; height:150px;"></textarea></td>
					</tr>
			';
			break;

		case 3: // Eingabetext
			$FSXL[content] .= '
					<tr>
						<td width="150"><b>'.$FS_PHRASES[form_addfield_name].':</b></td>
						<td><input name="name" class="textinput" style="width:400px;"></td>
					</tr>
			';
			break;

		default:
			reloadPage("?mod=form&go=editform&id=$_POST[id]");
	}

	$FSXL[content] .= '
					<tr>
						<td colspan="2" align="right"><input type="submit" class="button" value="'.$FS_PHRASES[global_send].'"></td>
					</tr>
				</table>
				</form>
	';
}

?>