<?php

// POTM bearbeiten
if ($_POST[title])
{
	foreach ($_POST[title] AS $key => $value)
	{
		settype($_POST[id][$key], 'integer');
		settype($_POST[gallery][$key], 'integer');
		// Löschen
		if ($_POST[del][$key])
		{
			mysql_query("DELETE FROM `$FSXL[tableset]_gallery_potm` WHERE `id` = " . $_POST[id][$key]);
		}
		else
		{
			// Bearbeiten
			if ($_POST[id][$key])
			{
				mysql_query("UPDATE `$FSXL[tableset]_gallery_potm` SET `title` = '$value', `gallery` = ".$_POST[gallery][$key]." WHERE `id` = " . $_POST[id][$key]);
			}
			// Hinzufügen
			else
			{
				if ($value != '')
				{
					mysql_query("INSERT INTO `$FSXL[tableset]_gallery_potm` (`id`, `title`, `gallery`) VALUES (NULL, '$value', ".$_POST[gallery][$key].")");
				}
			}
		}
	}
}

$FSXL[title] = $FS_PHRASES[gallery_potm_title];

$FSXL[content] .= '
		<form action="?mod=gallery&go=potm" method="post">
		<table border="0" cellpadding="0" cellspacing="0" align="center" width="90%">
';

$index = mysql_query("SELECT * FROM `$FSXL[tableset]_gallery_potm` ORDER BY `id`");
$i = 0;
while ($potm = mysql_fetch_assoc($index))
{
	$FSXL[content] .= '
			<tr>
				<td width="30"><b>'.($i+1).':</b><input type="hidden" name="id['.$i.']" value="'.$potm[id].'"></td>
				<td>'.$FS_PHRASES[gallery_potm_name].'</td>
				<td><input name="title['.$i.']" class="textinput" style="width:150px;" value="'.$potm[title].'"></td>
				<td>'.$FS_PHRASES[gallery_potm_gallery].'</td>
				<td>
					<select name="gallery['.$i.']" class="textinput">
	';

	$index2 = mysql_query("SELECT `id`, `name` FROM `$FSXL[tableset]_galleries` ORDER BY `name`");
	$FSXL[content] .= '<option value="0" '.($potm[gallery] == 0 ? "selected" : "").' style="font-style:italic">'.$FS_PHRASES[gallery_potm_timed].'</option>';
	while ($gallery = mysql_fetch_assoc($index2))
	{
		$FSXL[content] .= '<option value="'.$gallery[id].'" '.($potm[gallery] == $gallery[id] ? "selected" : "").'>'.$gallery[name].'</option>';
	}

	$FSXL[content] .= '
					</select>
				<td>'.$FS_PHRASES[gallery_potm_delete].'<input type="checkbox" name="del['.$i.']"></td>
				</td>
			</tr>
	';
	$i++;
}

$FSXL[content] .= '
			<tr>
				<td width="30"><b>'.($i+1).':</b></td>
				<td>'.$FS_PHRASES[gallery_potm_name].'</td>
				<td><input name="title['.$i.']" class="textinput" style="width:150px;" value="'.$potm[title].'"></td>
				<td>'.$FS_PHRASES[gallery_potm_gallery].'</td>
				<td>
					<select name="gallery['.$i.']" class="textinput">
						<option value="0" style="font-style:italic">'.$FS_PHRASES[gallery_potm_timed].'</option>
';

$index2 = mysql_query("SELECT `id`, `name` FROM `$FSXL[tableset]_galleries` ORDER BY `name`");
while ($gallery = mysql_fetch_assoc($index2))
{
	$FSXL[content] .= '<option value="'.$gallery[id].'">'.$gallery[name].'</option>';
}

$FSXL[content] .= '
					</select>
				</td>
				<td></td>
			</tr>
			<tr>
				<td colspan="6" align="right"><input type="submit" class="button" value="'.$FS_PHRASES[global_send].'"></td>
			</tr>
		</table>
		</form>

';

?>