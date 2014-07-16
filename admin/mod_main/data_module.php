<?php

$FSXL[title] = $FS_PHRASES[main_mods_title];

if ($_POST[edit])
{
	for ($i=0; $i<count($_POST[mod]); $i++)
	{
		if ($_POST[mod][$i] == "main") $_POST[aktiv][$i] = 1;

		settype($_POST[aktiv][$i], "integer");
		settype($_POST[position][$i], "integer");
		$name = $_POST[mod][$i];

		$index = @mysql_query("UPDATE `$FSXL[tableset]_mod` SET
					`aktiv` = ".$_POST[aktiv][$i].",
					`position` = ".$_POST[position][$i]."
					WHERE `name` = '$name'");
	}
	$FSXL[content] = '
		<div style="padding:20px; text-align:center;">'.$FS_PHRASES[main_mods_updated].'</div>
	';
}


// Eingabe Formular und Übesicht
else
{
	$FSXL[content] .= '&nbsp;
			<form action="?mod=main&go=module" method="post">
			<input type="hidden" name="edit" value="true">
			<table border="0" cellpadding="0" cellspacing="0" width="450" align="center">
	';

	$index = @mysql_query("SELECT * FROM `$FSXL[tableset]_mod` ORDER BY `position`");
	$checkarr = array();
	$i = 0;
	while ($arr = mysql_fetch_assoc($index))
	{
		$checkarr[$arr[name]] = $arr[name];

		$aktiv = ($arr[aktiv] == 1) ? "checked" : "";
		$disabled = ($arr[name] == "main") ? "disabled" : "";

		@include('mod_'.$arr[name].'/info.inc.php');
		$FSXL[content] .= '
				<tr>
					<td width="300">
						<b>'.$FSXL[mod][$arr[name]][title].'</b><br>
						'.$FS_PHRASES[main_mods_version].': '.$FSXL[mod][$arr[name]][version].'<br>
						'.$FS_PHRASES[main_mods_autor].': '.$FSXL[mod][$arr[name]][autor].'<br>
					</td>
					<td valign="top">
						'.$FS_PHRASES[main_mods_aktivated].':
						<input type="hidden" name="mod['.$i.']" value="'.$arr[name].'">
						<input type="checkbox" name="aktiv['.$i.']" '.$aktiv.' '.$disabled.' value="1">
					</td>
					<td valign="top" align="right">
						'.$FS_PHRASES[main_mods_position].':
						<input class="textinput" name="position['.$i.']" style="width:30px;" value="'.$arr[position].'">
					</td>
				</tr>
				<tr>
					<td colspan="3">
						'.$FSXL[mod][$arr[name]][description].'
						<hr>
					</td>
				</tr>
		';
		$i++;
	}

	// Verzeichnisse überprüfen
	$dir = opendir(".");
	while (($file = readdir($dir)) !== false)
	{
		$modname = substr($file, 4);
		if (substr_count($file, "mod_") && !in_array($modname, $checkarr))
		{
			@include('mod_'.$modname.'/info.inc.php');
			$FSXL[content] .= '
				<tr>
					<td width="300">
						<b>'.$FSXL[mod][$modname][title].'</b><br>
						'.$FS_PHRASES[main_mods_version].': '.$FSXL[mod][$modname][version].'<br>
						'.$FS_PHRASES[main_mods_autor].': '.$FSXL[mod][$modname][autor].'<br>
					</td>
					<td valign="top" colspan="2" align="right">
						'.$FS_PHRASES[main_mods_install].'
					</td>
				</tr>
				<tr>
					<td colspan="3">
						'.$FSXL[mod][$modname][description].'
						<hr>
					</td>
				</tr>
			';
		}
	}
	closedir($dir);

	$FSXL[content] .= '
				<tr>
					<td colspan="3" align="right"><input type="submit" class="button" value="'.$FS_PHRASES[global_send].'"></td>
				</tr>
			</table>
			</form>
			<p>
	';
}

?>