<?php

$FSXL[title] = $FS_PHRASES[link_edit_title];


if ($_POST[editid] && $_POST[name] && $_POST[day] != '' && $_POST[month] != '' && $_POST[year] != '' && $_POST[hour] != '' && $_POST[min] != '')
{
	settype($_POST[editid], 'integer');

	// löschen
	if ($_POST[del])
	{
		mysql_query("DELETE FROM `$FSXL[tableset]_link` WHERE `id` = $_POST[editid]");
		$FSXL[content] = '
				<div style="padding:20px; text-align:center;">'.$FS_PHRASES[link_edit_deleted].'</div>
		';
	}
	// bearbeiten
	else
	{
		$date = mktime($_POST[hour], $_POST[min], 0, $_POST[month], $_POST[day], $_POST[year]);
		settype($_POST[cat], 'integer');
		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_link_subcat` WHERE `id` = $_POST[subcat]");
		$cat = mysql_fetch_assoc($index);

		mysql_query("UPDATE `$FSXL[tableset]_link` SET `cat` = $_POST[cat], `subcat` = $_POST[subcat], `date` = $date, `name` = '$_POST[name]', 
				`url` = '$_POST[url]', `text` = '$_POST[text]', `tag` = '$cat[tag]' WHERE `id` = $_POST[editid]");

		$FSXL[content] = '
				<div style="padding:20px; text-align:center;">'.$FS_PHRASES[link_edit_edited].'</div>
		';
	}
}

elseif ($_GET[id])
{
	settype($_GET[id], 'integer');
	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_link` WHERE `id` = $_GET[id]");
	$link = mysql_fetch_assoc($index);

	$FSXL[content] .= '
				<form action="?mod=link&go=editlink" method="post" name="linkform" onSubmit="return chkLinkEditForm()">
				<input type="hidden" name="editid" value="'.$link[id].'">
				<table border="0" cellpadding="0" cellspacing="0" width="90%" style="margin:0px auto;">
					<tr>
						<td><b>'.$FS_PHRASES[link_add_name].':</b></td>
						<td align="right">
							<div align="left" style="width:406px;"><input name="name" value="'.$link[name].'" class="textinput" style="width:300px;"></div>
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[link_add_cat].':</b></td>
						<td>
							<select name="cat" class="textinput" style="width:400px;">
								<option value="0">'.$FS_PHRASES[link_cats_blank].'</option>
								<option value="0">------------------------------</option>
	';

	// Kategorien auslesen
	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_link_cat` ORDER BY `name`");
	while ($cat = mysql_fetch_assoc($index))
	{
		$FSXL[content] .= '
								<option value="'.$cat[id].'" '.($cat[id] == $link[cat] ? "selected" : "").'>'.$cat[name].'</option>
		';
	}

	if (!$_POST[type]) $_POST[type] = 1;
	$FSXL[content] .= '
							</select>
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[link_add_subcat].':</b></td>
						<td>
							<select name="subcat" class="textinput" style="width:400px;">
								<option value="0">'.$FS_PHRASES[link_sub_blank].'</option>
								<option value="0">------------------------------</option>
	';

	// Kategorien auslesen
	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_link_subcat` ORDER BY `name`");
	while ($cat = mysql_fetch_assoc($index))
	{
		$FSXL[content] .= '
								<option value="'.$cat[id].'" '.($cat[id] == $link[subcat] ? "selected" : "").'>'.$cat[name].'</option>
		';
	}

	if (!$_POST[type]) $_POST[type] = 1;
	$FSXL[content] .= '
							</select>
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[link_add_url].':</b></td>
						<td align="right">
							<div align="left" style="width:406px;"><input name="url" value="'.$link[url].'" class="textinput" style="width:400px;"></div>
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[link_add_date].':</b><br><span class="small">'.$FS_PHRASES[link_add_dateformat].'</span></td>
						<td valign="top" align="right">
							<div align="left" style="width:406px;">
								<input class="textinput" name="day" style="width:20px;" value="'.date("d", $link[date]).'">
								<input class="textinput" name="month" style="width:20px;" value="'.date("m", $link[date]).'">
								<input class="textinput" name="year" style="width:40px;" value="'.date("Y", $link[date]).'"> -
								<input class="textinput" name="hour" style="width:20px;" value="'.date("H", $link[date]).'">
								<input class="textinput" name="min" style="width:20px;" value="'.date("i", $link[date]).'">
							</div>
						</td>
					</tr>
					<tr>
						<td class="form"><b>'.$FS_PHRASES[link_add_text].':</b></td>
						<td align="right">
							<textarea name="text" class="textinput" style="width:400px; height:100px;">'.$link[text].'</textarea>
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[link_edit_delete].':</b></td>
						<td align="right">
							<div align="left" style="width:406px;"><input type="checkbox" name="del" id="delete" onClick="delMessage(\''.$FS_PHRASES[link_edit_delmsg].'\');"></div>
						</td>
					</tr>
					<tr>
						<td colspan="2" align="right"><input type="submit" class="button" value="'.$FS_PHRASES[global_send].'"></td>
					</tr>
				</table>
				</form>

	';
}

// Übersicht
else
{
	$FSXL[content] .= '
				<div>
				<table border="0" cellpadding="2" cellspacing="1" width="90%" style="margin:0px auto;">
					<tr>
						<td colspan="2" style="padding:0px;"><span style="font-size:12pt;"><b>'.$FS_PHRASES[link_edit_select].'</b></span><hr></td>
					</tr>
					<tr>
						<td class="alt0"><b>'.$FS_PHRASES[link_add_name].'</b></td>
						<td class="alt0" align="center"><b>'.$FS_PHRASES[link_add_date].'</b></td>
					</tr>
	';

	// Liste
	$currentcat = '';
	$currentsubcat = '';
	$index = mysql_query("SELECT `id`, `cat`, `subcat`, `name`, `date` FROM `$FSXL[tableset]_link` ORDER BY `cat`, `subcat`, `date` DESC");
	while ($link = mysql_fetch_assoc($index))
	{
		$i++;
		if ($currentcat != $link[cat])
		{
			$currentcat = $link[cat];
			if ($currentcat != 0)
			{
				$index2 = mysql_query("SELECT `name` FROM `$FSXL[tableset]_link_cat` WHERE `id` = $link[cat]");
				$cat = mysql_fetch_assoc($index2);
			}
			else
			{
				$cat[name] = $FS_PHRASES[link_cats_blank];
			}
			$FSXL[content] .= '
					<tr>
						<td colspan="2" class="alt'.($i%2==0?1:2).'"><b>'.$cat[name].'</b></td>
					</tr>
			';
			$i++;
			$currentsubcat = '';
		}
		if ($currentsubcat != $link[subcat])
		{
			$currentsubcat = $link[subcat];
			if ($currentsubcat != 0)
			{
				$index2 = mysql_query("SELECT `name` FROM `$FSXL[tableset]_link_subcat` WHERE `id` = $link[subcat]");
				$cat = mysql_fetch_assoc($index2);
			}
			else
			{
				$cat[name] = $FS_PHRASES[link_sub_blank];
			}
			$FSXL[content] .= '
					<tr>
						<td colspan="2" class="alt'.($i%2==0?1:2).'" style="padding-left:20px;"><b>'.$cat[name].'</b></td>
					</tr>
			';
			$i++;
		}
		$FSXL[content] .= '
					<tr>
						<td class="alt'.($i%2==0?1:2).'" style="padding-left:40px;"><a href="?mod=link&go=editlink&id='.$link[id].'">'.$link[name].'</a></td>
						<td align="center" class="alt'.($i%2==0?1:2).'">'.date($FSXL[config][dateformat], $link[date]).'</td>
					</tr>
		';
	}

	$FSXL[content] .= '
				</table>
				</div>
	';
}

?>