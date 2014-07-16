<?php

$FSXL[title] = $FS_PHRASES[link_sub_title];
$FSXL[content] = '';

// Kategorie erstellen
if ($_POST[action] == 'newcat' && $_POST[name])
{
	$index = mysql_query("INSERT INTO `$FSXL[tableset]_link_subcat` (`id`, `name`, `tag`) 
							VALUES (NULL, '$_POST[name]', '$_POST[tag]')");

	if ($index)
	{
		$FSXL[content] .= '
				<div style="padding:20px; text-align:center;">'.$FS_PHRASES[link_cats_catcreated].'</div><p>
		';
		unset ($_POST[name]);
		unset ($_POST[tag]);
	}
	else
	{
		$FSXL[content] .= '
				<div style="padding:20px; text-align:center;">'.$FS_PHRASES[link_cats_failedcreate].'</div><p>
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
		mysql_query("DELETE FROM `$FSXL[tableset]_link_subcat` WHERE `id` = $_POST[editid]");
		$FSXL[content] = '
				<div style="padding:20px; text-align:center;">'.$FS_PHRASES[link_cats_deleted].'</div>
		';
	}
	else
	{
		$index = mysql_query("UPDATE `$FSXL[tableset]_link_subcat` SET `name` = '$_POST[name]', `tag` = '$_POST[tag]'
							WHERE `id` = $_POST[editid]");
		if ($index)
		{
			mysql_query("UPDATE `$FSXL[tableset]_link` SET `tag` = '$_POST[tag]' WHERE `subcat` = $_POST[editid]");
			$FSXL[content] = '
				<div style="padding:20px; text-align:center;">'.$FS_PHRASES[link_cats_cateditdone].'</div>
			';
		}
		else
		{
			$FSXL[content] = '
				<div style="padding:20px; text-align:center;">'.$FS_PHRASES[link_cats_editfailed].'</div>
			';
		}
	}
}

// Kategorie editieren (formular)
elseif ($_GET[id])
{
	settype($_GET[id], 'integer');
	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_link_subcat` WHERE `id` = $_GET[id]");
	$cat = mysql_fetch_assoc($index);

	$FSXL[content] = '
				<div>
				<form action="?mod=link&go=subcats" method="post">
				<input type="hidden" name="action" value="editcat">
				<input type="hidden" name="editid" value="'.$cat[id].'">
				<table border="0" cellpadding="0" cellspacing="0" width="90%" style="margin:0px auto;">
					<tr>
						<td colspan="2"><span style="font-size:12pt;"><b>'.$FS_PHRASES[link_sub_editcat].'</b></span><hr></td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[link_cats_name].':</b></td>
						<td align="right">
							<div style="width:406px;" align="left"><input class="textinput" name="name" style="width:300px;" value="'.$cat[name].'"></div>
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[link_cats_tag].':</b><br>'.$FS_PHRASES[link_cats_tag_sub].'</td>
						<td align="right">
							<div style="width:406px;" align="left"><input class="textinput" name="tag" style="width:300px;" value="'.$cat[tag].'"></div>
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[link_cats_delete].':</b></td>
						<td align="right">
							<div style="width:406px;" align="left">
								<input type="checkbox" name="del">
							</div>
						</td>
					</tr>
					<tr>
						<td colspan="2" align="right">
							<input type="submit" class="button" value="'.$FS_PHRASES[global_send].'">
						</td>
					</tr>
				</table>
				</form>
				</div>
	';
}

// Übersicht
else
{
	$FSXL[content] .= '
				<div>
				<form action="?mod=link&go=subcats" method="post">
				<input type="hidden" name="action" value="newcat">
				<table border="0" cellpadding="0" cellspacing="0" width="90%" style="margin:0px auto;">
					<tr>
						<td colspan="2"><span style="font-size:12pt;"><b>'.$FS_PHRASES[link_sub_newcat].'</b></span><hr></td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[link_cats_name].':</b></td>
						<td align="right">
							<div style="width:406px;" align="left"><input class="textinput" name="name" style="width:300px;"></div>
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[link_cats_tag].':</b><br>'.$FS_PHRASES[link_cats_tag_sub].'</td>
						<td align="right">
							<div style="width:406px;" align="left"><input class="textinput" name="tag" style="width:300px;"></div>
						</td>
					</tr>
					<tr>
						<td colspan="2" align="right"><input type="submit" class="button" value="'.$FS_PHRASES[global_send].'"></td>
					</tr>
				</table>
				</form>
				<table border="0" cellpadding="2" cellspacing="1" width="90%" style="margin:0px auto;">
					<tr>
						<td colspan="2" style="padding:0px;"><span style="font-size:12pt;"><b>'.$FS_PHRASES[link_sub_editcat].'</b></span><hr></td>
					</tr>
					<tr>
						<td class="alt0"><b>'.$FS_PHRASES[link_cats_name].'</b></td>
						<td class="alt0" align="center"><b>'.$FS_PHRASES[link_cats_tag].'</b></td>
					</tr>
	';

	// Kategorien auflisten
	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_link_subcat` ORDER BY `name`");
	while ($cat = mysql_fetch_assoc($index))
	{
		$i++;
		$FSXL[content] .= '
					<tr>
						<td class="alt'.($i%2==0?1:2).'"><a href="?mod=link&go=subcats&id='.$cat[id].'">'.$cat[name].'</a></td>
						<td class="alt'.($i%2==0?1:2).'" align="center">'.$cat[tag].'</td>
					</tr>
		';
	}

	$FSXL[content] .= '
				</table>
				</div>

	';
}

?>