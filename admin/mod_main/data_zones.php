<?php

$FSXL[title] = $FS_PHRASES[main_zone_title];


// Neue Zone erstellen
if ($_POST[action] == "newzone" && $_POST[name])
{
	settype($_POST[style], 'integer');
	$single = $_POST[single] ? 1 : 0;
	$headlines = $_POST[headlines] ? 1 : 0;

	// Zone erzeugen
	$index = mysql_query("INSERT INTO `$FSXL[tableset]_zones` (`id`, `name`, `style`, `url`, `page`, `single`, `headlines`) 
							VALUES (NULL, '$_POST[name]', $_POST[style], '$_POST[url]', '$_POST[page]', '$single', '$headlines')");
	if ($index) {
		$FSXL[content] = '<div style="padding:20px; text-align:center;">'.$FS_PHRASES[main_zone_donenewzone].'</div>';
		updateHtaccess();
	}
	else {
		$FSXL[content] = '<div style="padding:20px; text-align:center;">'.$FS_PHRASES[main_zone_addfailed].'</div>';
	}
}


// Zonen editieren
elseif ($_POST[action] == "editzone" && $_POST[name] && $_POST[style])
{
	settype($_POST[zoneid], 'integer');
	settype($_POST[style], 'integer');
	$single = $_POST[single] ? 1 : 0;
	$headlines = $_POST[headlines] ? 1 : 0;
	
	$index = mysql_query("UPDATE `$FSXL[tableset]_zones` SET `name` = '$_POST[name]', `style` = $_POST[style], 
							`url` = '$_POST[url]', `page` = '$_POST[page]', `single` = '$single', `headlines` = '$headlines' WHERE `id` = $_POST[zoneid]");
							
	if ($index) {
		$FSXL[content] = '<div style="padding:20px; text-align:center;">'.$FS_PHRASES[main_zone_edited].'</div>';
		updateHtaccess();
	}
	else {
		$FSXL[content] = '<div style="padding:20px; text-align:center;">'.$FS_PHRASES[main_zone_editfailed].'</div>';
	}
}

// Edit FOrmular
elseif ($_GET[id])
{
	settype($_GET[id], 'integer');
	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_zones` WHERE `id` = $_GET[id]");
	$zone = mysql_fetch_assoc($index);

	// Styles auslesen zum erstellen
	$index = @mysql_query("SELECT * FROM `$FSXL[tableset]_styles` ORDER BY `name`");
	$styleoptions1 = "";
	while ($styles = mysql_fetch_assoc($index)) {
		$styleoptions1 .= '<option value="'.$styles[id].'" '.($styles[id]==$zone[style]?"selected":"").'>'.$styles[name].'</option>';
	}

	$FSXL[content] .= '
				<form action="?mod=main&go=zones" method="post">
				<input type="hidden" name="action" value="editzone">
				<input type="hidden" name="zoneid" value="'.$zone[id].'">
				<table border="0" cellpadding="2" cellspacing="1" width="90%" style="margin:0px auto;">
					<tr>
						<td colspan="2" style="padding:0px;">
							<span style="font-size:12pt"><b>'.$FS_PHRASES[main_zone_editzone].'</b></span><hr>
						</td>
					</tr>
					<tr>
						<td width="250"><b>'.$FS_PHRASES[main_zone_name].':</b></td>
						<td>
							<input class="textinput" name="name" style="width:300px;" value="'.$zone[name].'">
						</td>
					</tr>
	';
	if ($zone[id] != 1) {
		$FSXL[content] .= '
					<tr>
						<td width="250"><b>'.$FS_PHRASES[main_zone_url].':</b><br>'.$FS_PHRASES[main_zone_url_sub].'</td>
						<td><input class="textinput" name="url" style="width:300px;" value="'.$zone[url].'"></td>
					</tr>
		';
	}
	$FSXL[content] .= '
					<tr>
						<td width="250"><b>'.$FS_PHRASES[main_config_startpage].':</b><br>'.$FS_PHRASES[main_config_startpage_sub2].'</td>
						<td>
							<input class="textinput" name="page" style="width:300px;" value="'.$zone[page].'">
						</td>
					</tr>
					<tr>
						<td width="250"><b>'.$FS_PHRASES[main_zone_single].':</b><br>'.$FS_PHRASES[main_zone_single_sub].'</td>
						<td>
							<input type="checkbox" name="single" '.($zone[single]==1?"checked":"").' />
						</td>
					</tr>
					<tr>
						<td width="250"><b>'.$FS_PHRASES[main_zone_headlines].':</b><br>'.$FS_PHRASES[main_zone_headlines_sub].'</td>
						<td>
							<input type="checkbox" name="headlines" '.($zone[headlines]==1?"checked":"").' />
						</td>
					</tr>
					<tr>
						<td width="250" valign="top"><b>'.$FS_PHRASES[main_zone_style].':</b><br>'.$FS_PHRASES[main_zone_style_sub].'</td>
						<td>
							<select name="style" class="textinput" style="width:305px;" size="3">
								'.$styleoptions1.'
							</select>
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

// Übersicht
else
{
	// Styles auslesen zum erstellen
	$index = @mysql_query("SELECT * FROM `$FSXL[tableset]_styles` ORDER BY `name`");
	$styleoptions1 = "";
	while ($styles = mysql_fetch_assoc($index))
	{
		$styleoptions1 .= '<option value="'.$styles[id].'">'.$styles[name].'</option>';
	}

	$FSXL[content] .= '
				<form action="?mod=main&go=zones" method="post">
				<input type="hidden" name="action" value="newzone">
				<table border="0" cellpadding="2" cellspacing="1" width="90%" style="margin:0px auto;">
					<tr>
						<td colspan="2" style="padding:0px;">
							<span style="font-size:12pt"><b>'.$FS_PHRASES[main_zone_newzone].'</b></span><hr>
						</td>
					</tr>
					<tr>
						<td width="250"><b>'.$FS_PHRASES[main_zone_name].':</b></td>
						<td>
							<input class="textinput" name="name" style="width:300px;">
						</td>
					</tr>
					<tr>
						<td width="250"><b>'.$FS_PHRASES[main_zone_url].':</b><br>'.$FS_PHRASES[main_zone_url_sub].'</td>
						<td>
							<input class="textinput" name="url" style="width:300px;">
						</td>
					</tr>
					<tr>
						<td width="250"><b>'.$FS_PHRASES[main_config_startpage].':</b><br>'.$FS_PHRASES[main_config_startpage_sub2].'</td>
						<td>
							<input class="textinput" name="page" style="width:300px;">
						</td>
					</tr>
					<tr>
						<td width="250"><b>'.$FS_PHRASES[main_zone_single].':</b><br>'.$FS_PHRASES[main_zone_single_sub].'</td>
						<td>
							<input type="checkbox" name="single" />
						</td>
					</tr>
					<tr>
						<td width="250"><b>'.$FS_PHRASES[main_zone_headlines].':</b><br>'.$FS_PHRASES[main_zone_headlines_sub].'</td>
						<td>
							<input type="checkbox" name="headlines" />
						</td>
					</tr>
					<tr>
						<td width="250" valign="top"><b>'.$FS_PHRASES[main_zone_style].':</b><br>'.$FS_PHRASES[main_zone_style_sub].'</td>
						<td>
							<select name="style" class="textinput" style="width:305px;" size="3">
								'.$styleoptions1.'
							</select>
						</td>
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
						<td colspan="4" style="padding:0px;">
							<span style="font-size:12pt"><b>'.$FS_PHRASES[main_zone_editzone].'</b></span><hr>
						</td>
					</tr>
					<tr>
						<td class="alt0"><b>'.$FS_PHRASES[main_zone_name].'</b></td>
						<td class="alt0" align="center"><b>'.$FS_PHRASES[main_zone_style].'</b></td>
						<td class="alt0" align="center"><b>'.$FS_PHRASES[main_zone_link].'</b></td>
					</tr>
	';

	// Zonen auslesen
	$index = @mysql_query("SELECT * FROM `$FSXL[tableset]_zones` ORDER BY `name`");
	while ($zone = mysql_fetch_assoc($index))
	{
		$i++;
		$index2 = mysql_query("SELECT * FROM `$FSXL[tableset]_styles` WHERE `id` = $zone[style]");
		$style = mysql_fetch_assoc($index2);
		$FSXL[content] .= '
					<tr>
						<td class="alt'.($i%2==0?1:2).'"><a href="?mod=main&go=zones&id='.$zone[id].'">'.$zone[name].'</a></td>
						<td align="center" class="alt'.($i%2==0?1:2).'">'.$style[name].'</td>
						<td align="center" class="alt'.($i%2==0?1:2).'"><a href="../index.php?zone='.$zone[id].'" target="_blank"><img border="0" src="images/'.$FSXL[style].'_link.gif" alt=""></a></td>
					</tr>
		';
	}

	$FSXL[content] .= '
					<tr>
						<td colspan="4">
							<input style="float:right;" type="submit" class="button" value="'.$FS_PHRASES[global_takeover].'">
						</td>
					</tr>
					'.$extrahtml.'
				</table>
	';
}

?>