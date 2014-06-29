<?php

$FSXL[title] = $FS_PHRASES[gallery_cats_title];
$FSXL[content] = '';

// Kategorie erstellen
if ($_POST[action] == 'newcat' && $_POST[name])
{
	$index = mysql_query("INSERT INTO `$FSXL[tableset]_gallery_cat` (`id`, `name`, `text`) VALUES (NULL, '$_POST[name]', '$_POST[text]')");

	if ($index)
	{
		$FSXL[content] .= '
				<div style="padding:20px; text-align:center;">'.$FS_PHRASES[gallery_cats_catcreated].'</div><p>
		';
		unset ($_POST[name]);
		unset ($_POST[text]);
	}
	// Name schon vergeben
	else
	{
		$FSXL[content] .= '
				<div style="padding:20px; text-align:center;">'.$FS_PHRASES[gallery_cats_failedcreate].'</div><p>
		';
	}
}

// Kategorie editieren
if ($_POST[action] == 'editcat' && $_POST[name])
{
	settype($_POST[editid], 'integer');
	settype($_POST[newcat], 'integer');
	if($_POST[del])
	{
		mysql_query("UPDATE `$FSXL[tableset]_galleries` SET `cat` = $_POST[newcat] WHERE `cat` = $_POST[editid]");
		mysql_query("DELETE FROM `$FSXL[tableset]_gallery_cat` WHERE `id` = $_POST[editid]");
		$FSXL[content] = '
				<div style="padding:20px; text-align:center;">'.$FS_PHRASES[gallery_cats_deleted].'</div>
		';
	}
	else
	{
		$index = mysql_query("UPDATE `$FSXL[tableset]_gallery_cat` SET `name` = '$_POST[name]', `text` = '$_POST[text]' WHERE `id` = $_POST[editid]");
		if ($index)
		{
			$FSXL[content] = '
				<div style="padding:20px; text-align:center;">'.$FS_PHRASES[gallery_cats_cateditdone].'</div>
			';
		}
		else
		{
			$FSXL[content] = '
				<div style="padding:20px; text-align:center;">'.$FS_PHRASES[gallery_cats_editfailed].'</div>
			';
		}
	}
}

// Kategorie editieren (formular)
elseif ($_GET[id])
{
	settype($_GET[id], 'integer');
	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_gallery_cat` WHERE `id` = $_GET[id]");
	$cat = mysql_fetch_assoc($index);

	$FSXL[content] = '
				<form action="?mod=gallery&go=cats" method="post">
				<input type="hidden" name="action" value="editcat">
				<input type="hidden" name="editid" value="'.$cat[id].'">
				<table border="0" cellpadding="2" cellspacing="1" width="90%" style="margin:0px auto;">
					<tr>
						<td width="250"><b>'.$FS_PHRASES[gallery_cats_name].':</b></td>
						<td>
							<input class="textinput" name="name" style="width:300px;" value="'.$cat[name].'">
						</td>
					</tr>
					<tr>
						<td valign="top"><b>'.$FS_PHRASES[gallery_cats_text].':</b></td>
						<td><textarea class="textinput" name="text" style="width:300px; height:100px;">'.$cat[text].'</textarea></td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[gallery_galleries_delete].':</b></td>
						<td>
							<input type="checkbox" name="del">
							'.$FS_PHRASES[gallery_cats_move].':<br>
							<select name="newcat" class="textinput" style="width:305px;">
								<option value="0">'.$FS_PHRASES[gallery_cats_blank].'</option>
								<option value="0">-----------------------------</option>
	';
	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_gallery_cat` ORDER BY `name`");
	while ($cat2 = mysql_fetch_assoc($index))
	{
		if ($cat2[id] != $cat[id])
		{
			$FSXL[content] .= '
								<option value="'.$cat2[id].'">'.$cat2[name].'</option>
			';
		}
	}
	$FSXL[content] .= '
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
	$FSXL[content] .= '
				<form action="?mod=gallery&go=cats" method="post">
				<input type="hidden" name="action" value="newcat">
				<table border="0" cellpadding="2" cellspacing="1" width="90%" style="margin:0px auto;">
					<tr>
						<td colspan="2">
							<span style="font-size:12pt"><b>'.$FS_PHRASES[gallery_cats_newcat].'</b></span><hr>
						</td>
					</tr>
					<tr>
						<td width="250"><b>'.$FS_PHRASES[gallery_cats_name].':</b></td>
						<td>
							<input class="textinput" name="name" style="width:300px;">
						</td>
					</tr>
					<tr>
						<td valign="top"><b>'.$FS_PHRASES[gallery_cats_text].':</b></td>
						<td><textarea class="textinput" name="text" style="width:300px; height:100px;">'.$_POST[text].'</textarea></td>
					</tr>
					<tr>
						<td colspan="2" align="right"><input type="submit" class="button" value="'.$FS_PHRASES[global_send].'"></td>
					</tr>
				</table>
				</form>


				<table border="0" cellpadding="2" cellspacing="1" width="90%" style="margin:0px auto;">
					<tr>
						<td>
							<span style="font-size:12pt"><b>'.$FS_PHRASES[gallery_cats_editcat].'</b></span><hr>
						</td>
					</tr>
					<tr>
						<td>
							<table border="0" cellpadding="2" cellspacing="1" width="100%">
								<tr>
									<td class="alt0"><b>'.$FS_PHRASES[gallery_cats_name].'</b></td>
									<td class="alt0" align="center"><b>'.$FS_PHRASES[gallery_galleries_link].'</b></td>
								</tr>
	';

	// Kategorien auflisten
	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_gallery_cat` ORDER BY `name`");
	$i=0;
	while ($cat = mysql_fetch_assoc($index))
	{
		$i++;
		$FSXL[content] .= '
					<tr>
						<td class="alt'.($i%2==0?1:2).'"><a href="?mod=gallery&go=cats&id='.$cat[id].'">'.$cat[name].'</a></td>
						<td class="alt'.($i%2==0?1:2).'" align="center"><a href="../index.php?section=gallery&cat='.$cat[id].'" target="_blank"><img border="0" src="images/'.$FSXL[style].'_link.gif" alt=""></a></td>
					</tr>
		';
	}

	$FSXL[content] .= '
							</table>
						</td>
					</tr>
				</table>
	';
}

?>