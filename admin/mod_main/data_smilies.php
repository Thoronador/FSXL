<?php

$FSXL[title] = $FS_PHRASES[main_smilies_title];

if ($_POST[action] == "edit")
{
	// Hinzufügen
	if ($_FILES[pic][tmp_name] && $_POST[code])
	{
		switch ($_FILES[pic][type])
		{
			case 'image/gif':
				$index = mysql_query("INSERT INTO `$FSXL[tableset]_smilies` (`id`, `code`) VALUES (NULL, '$_POST[code]')");
				if ($index)
				{
					$id = mysql_insert_id();
					move_uploaded_file($_FILES[pic][tmp_name], '../images/smilies/'.$id.'.gif');
				}
				else
				{
					$FSXL[content] = '<div style="padding:20px; text-align:center;">'.$FS_PHRASES[main_smilies_addfailed].'</div>';
				}
				break;
			default:
				$FSXL[content] = '<div style="padding:20px; text-align:center;">'.$FS_PHRASES[main_smilies_wrongtype].'</div>';
		}
	}

	// Bearbeiten
	if ($_POST[editid])
	{
		foreach($_POST[editid] AS $key => $id)
		{
			settype($id, 'integer');
			// Löschen
			if ($_POST[delete][$key])
			{
				mysql_query("DELETE FROM `$FSXL[tableset]_smilies` WHERE `id` = $id");
				unlink("../images/smilies/$id.gif");
			}
			elseif ($_POST[newcode][$key])
			{
				mysql_query("UPDATE `$FSXL[tableset]_smilies` SET `code` = '".$_POST[newcode][$key]."' WHERE `id` = $id");
			}
		}
	}

	if (!$FSXL[content]) reloadPage('?mod=main&go=smilies');
}

else
{
	$FSXL[content] .= '
				<form action="?mod=main&go=smilies" method="post" enctype="multipart/form-data">
				<input type="hidden" name="action" value="edit">
				<table border="0" cellpadding="0" cellspacing="0" align="center" width="90%">
					<tr>
						<td colspan="2"><span style="font-size:12pt"><b>'.$FS_PHRASES[main_smilies_add].'</b></span><hr></td>
					</tr>
					<tr>
						<td width="250"><b>'.$FS_PHRASES[main_smilies_pic].':</b><br>'.$FS_PHRASES[main_smilies_pic_sub].'</td>
						<td><input type="file" class="textinput" name="pic" style="width:300px;"></td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[main_smilies_code].':</b><br>'.$FS_PHRASES[main_smilies_code_sub].'</td>
						<td><input style="width:50px;" name="code" class="textinput"></td>
					</tr>
					<tr>
						<td colspan="2" align="right"><input type="submit" class="button" value="'.$FS_PHRASES[global_send].'"></td>
					</tr>
				</table>
				<table border="0" cellpadding="0" cellspacing="1" align="center" width="90%">
					<tr>
						<td colspan="3"><span style="font-size:12pt"><b>'.$FS_PHRASES[main_smilies_edit].'</b></span><hr></td>
					</tr>
					<tr>
						<td>
							<table border="0" cellpadding="2" cellspacing="1" width="100%">
								<tr>
									<td class="alt0" align="center"><b>'.$FS_PHRASES[main_smilies_pic].'</b></td>
									<td class="alt0" align="center"><b>'.$FS_PHRASES[main_smilies_code].'</b></td>
									<td class="alt0" align="center"><b>'.$FS_PHRASES[main_tplvars_delete].'</b></td>
								</tr>
	';

	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_smilies`");
	$i = 1;
	while ($smilie = mysql_fetch_assoc($index))
	{
		$FSXL[content] .= '

					<tr>
						<td class="alt'.($i%2 == 0 ? 1 : 2).'" align="center">
							<input type="hidden" name="editid['.$i.']" value="'.$smilie[id].'">
							<img border="0" src="../images/smilies/'.$smilie[id].'.gif" alt="">
						</td>
						<td class="alt'.($i%2 == 0 ? 1 : 2).'" align="center"><input style="width:50px;" name="newcode['.$i.']" value="'.$smilie[code].'" class="textinput"></td>
						<td class="alt'.($i%2 == 0 ? 1 : 2).'" align="center"><input type="checkbox" name="delete['.$i.']"></td>
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