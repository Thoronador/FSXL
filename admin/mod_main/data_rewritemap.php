<?php

$FSXL[title] = $FS_PHRASES[main_rewrite_title];

if ($_POST[action] == "edit")
{
	// Hinzufügen
	if ($_POST[addfrom] && $_POST[addto])
	{
		settype($_POST[addzone], 'integer');
		$index = mysql_query("INSERT INTO `$FSXL[tableset]_rewritemap` (`id`, `from`, `to`, `zone`)
								VALUES (NULL, '$_POST[addfrom]', '$_POST[addto]', $_POST[addzone])");
		if ($index) {
			$FSXL[content] = '<div style="padding:20px; text-align:center;">'.$FS_PHRASES[main_rewrite_added].'</div>';
		}
		else {
			$FSXL[content] = '<div style="padding:20px; text-align:center;">'.$FS_PHRASES[main_rewrite_addfailed].'</div>';
		}
	}

	// Bearbeiten
	if ($_POST[editid])
	{
		foreach($_POST[editid] AS $id => $value)
		{
			settype($id, 'integer');
			// Löschen
			if ($_POST[delete][$id]) {
				mysql_query("DELETE FROM `$FSXL[tableset]_rewritemap` WHERE `id` = $id");
			}
			elseif ($_POST[from][$id] && $_POST[to][$id])
			{
				settype($_POST[zone][$id], 'integer');
				mysql_query("UPDATE `$FSXL[tableset]_rewritemap` SET 
								`from` = '".$_POST[from][$id]."', 
								`to` = '".$_POST[to][$id]."',
								`zone` = ".$_POST[zone][$id]."
								WHERE `id` = $id");
			}
		}
	}

	if (!$FSXL[content]) reloadPage('?mod=main&go=rewritemap');
}

else
{
	$FSXL[content] .= '
				<form action="?mod=main&go=rewritemap" method="post">
				<input type="hidden" name="action" value="edit">
				<table border="0" cellpadding="0" cellspacing="0" align="center" width="90%">
					<tr>
						<td colspan="2"><span style="font-size:12pt"><b>'.$FS_PHRASES[main_rewrite_add].'</b></span><hr></td>
					</tr>
					<tr>
						<td width="250"><b>'.$FS_PHRASES[main_rewrite_url].':</b><br>'.$FS_PHRASES[main_rewrite_url_sub].'</td>
						<td><input class="textinput" name="addfrom" style="width:300px;"></td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[main_rewrite_to].':</b><br>'.$FS_PHRASES[main_rewrite_to_sub].'</td>
						<td><input style="width:300px;" name="addto" class="textinput"></td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[main_rewrite_zone].':</b><br>'.$FS_PHRASES[main_rewrite_zone_sub].'</td>
						<td>
							<select style="width:300px;" name="addzone" class="textinput">
								<option value="0" style="font-style:italic;">'.$FS_PHRASES[main_rewrite_allzones].'</option>
	';
	
	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_zones` ORDER BY `name`");
	while ($zone = mysql_fetch_assoc($index))
	{
		$FSXL[content] .= '<option value="'.$zone[id].'">'.$zone[name].'</option>';
	}
	
	$FSXL[content] .= '
							</select>
						</td>
					</tr>
					<tr>
						<td colspan="2" align="right"><input type="submit" class="button" value="'.$FS_PHRASES[global_send].'"></td>
					</tr>
				</table>
				<table border="0" cellpadding="0" cellspacing="1" align="center" width="90%">
					<tr>
						<td colspan="3"><span style="font-size:12pt"><b>'.$FS_PHRASES[main_rewrite_edit].'</b></span><hr></td>
					</tr>
					<tr>
						<td>
							<table border="0" cellpadding="2" cellspacing="1" width="100%">
								<tr>
									<td class="alt0" align="center"><b>'.$FS_PHRASES[main_rewrite_url].'</b></td>
									<td class="alt0" align="center"><b>'.$FS_PHRASES[main_rewrite_to].'</b></td>
									<td class="alt0" align="center"><b>'.$FS_PHRASES[main_rewrite_zone].'</b></td>
									<td class="alt0" align="center"><b>'.$FS_PHRASES[main_rewrite_delete].'</b></td>
								</tr>
	';

	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_rewritemap` ORDER BY `from`");
	$i = 1;
	while ($map = mysql_fetch_assoc($index))
	{
		$FSXL[content] .= '

					<tr>
						<td class="alt'.($i%2 == 0 ? 1 : 2).'" align="center">
							<input type="hidden" name="editid['.$map[id].']" value="'.$map[id].'">
							<input class="textinput" name="from['.$map[id].']" style="width:130px;" value="'.$map[from].'">
						</td>
						<td class="alt'.($i%2 == 0 ? 1 : 2).'" align="center">
							<input style="width:130px;" name="to['.$map[id].']" value="'.$map[to].'" class="textinput">
						</td>
						<td class="alt'.($i%2 == 0 ? 1 : 2).'" align="center">
							<select style="width:130px;" name="zone['.$map[id].']" class="textinput">
								<option value="0" style="font-style:italic;">'.$FS_PHRASES[main_rewrite_allzones].'</option>
	';
	
	$index2 = mysql_query("SELECT * FROM `$FSXL[tableset]_zones` ORDER BY `name`");
	while ($zone = mysql_fetch_assoc($index2))
	{
		$FSXL[content] .= '<option value="'.$zone[id].'" '.($map[zone]==$zone[id]?"selected":"").'>'.$zone[name].'</option>';
	}
	
	$FSXL[content] .= '
							</select>
						</td>
						<td class="alt'.($i%2 == 0 ? 1 : 2).'" align="center"><input type="checkbox" name="delete['.$map[id].']"></td>
					</tr>
		';
		$i++;
	}

	$FSXL[content] .= '
							</table>
						</td>
					</tr>
					<tr>
						<td colspan="3" align="right"><input type="submit" class="button" value="'.$FS_PHRASES[global_send].'"></td>
					</tr>
				</table>
				</form>

	';
}

?>