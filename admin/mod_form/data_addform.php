<?php

$FSXL[title] = $FS_PHRASES[form_add_title];


// Hinzufügen
if ($_POST[action] == "add" && $_POST[name])
{
	// Datum auswerten
	$sdate = mktime($_POST[shour], $_POST[smin], 0, $_POST[smonth], $_POST[sday], $_POST[syear]);
	$edate = mktime($_POST[ehour], $_POST[emin], 0, $_POST[emonth], $_POST[eday], $_POST[eyear]);

	mysql_query("INSERT INTO `$FSXL[tableset]_forms` (`id`, `title`, `desc`, `start`, `end`) 
			VALUES (NULL, '$_POST[name]', '$_POST[desc]', '$sdate', '$edate')");

	$FSXL[content] = '<div style="padding:20px; text-align:center;">'.$FS_PHRASES[form_add_added].'</div>';
}

// Formular
else
{
	$FSXL[content] .= '
				<form action="?mod=form&go=addform" method="post">
				<input type="hidden" name="action" value="add">
				<table border="0" cellpadding="0" cellspacing="0" align="center" width="90%">
					<tr>
						<td><b>'.$FS_PHRASES[form_add_name].':</b></td>
						<td align="right"><input name="name" class="textinput" style="width:400px;"></td>
					</tr>
					<tr>
						<td valign="top"><b>'.$FS_PHRASES[form_add_desc].':</b></td>
						<td align="right"><textarea name="desc" class="textinput" style="width:400px; height:150px;"></textarea></td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[form_add_startdate].':</b><br><span class="small">'.$FS_PHRASES[form_add_dateformat].'</span></td>
						<td valign="top" align="right">
							<div align="left" style="width:406px;">
								<input class="textinput" name="sday" style="width:20px;" value="'.date("d").'">
								<input class="textinput" name="smonth" style="width:20px;" value="'.date("m").'">
								<input class="textinput" name="syear" style="width:40px;" value="'.date("Y").'"> -
								<input class="textinput" name="shour" style="width:20px;" value="'.date("H").'">
								<input class="textinput" name="smin" style="width:20px;" value="'.date("i").'">
							</div>
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[form_add_enddate].':</b><br><span class="small">'.$FS_PHRASES[form_add_dateformat].'</span></td>
						<td valign="top" align="right">
							<div align="left" style="width:406px;">
								<input class="textinput" name="eday" style="width:20px;" value="'.date("d", time()+604800).'">
								<input class="textinput" name="emonth" style="width:20px;" value="'.date("m", time()+604800).'">
								<input class="textinput" name="eyear" style="width:40px;" value="'.date("Y", time()+604800).'"> -
								<input class="textinput" name="ehour" style="width:20px;" value="'.date("H", time()+604800).'">
								<input class="textinput" name="emin" style="width:20px;" value="'.date("i", time()+604800).'">
							</div>
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