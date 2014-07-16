<?php

$FSXL[title] = $FS_PHRASES[main_usergroups_title];


// Gruppe eintragen
if ($_POST[action] == 'addgroup' && $_POST[name])
{
	$index = mysql_query("INSERT INTO `$FSXL[tableset]_user_groups` (`id`, `name`) VALUES (NULL, '$_POST[name]')");
	if ($index)
	{
		$FSXL[content] = '
				<div style="padding:20px; text-align:center;">'.$FS_PHRASES[main_usergroups_added].'</div>
		';
	}
	else
	{
		$FSXL[content] = '
				<div style="padding:20px; text-align:center;">'.$FS_PHRASES[main_usergroups_addfailed].'</div>
		';
	}
}

// User editieren
elseif ($_POST[action] == edit && $_POST[editid] && $_POST[name])
{
	settype($_POST[editid], 'integer');
	
	// Löschen
	if ($_POST[del])
	{
		mysql_query("DELETE FROM `$FSXL[tableset]_user_groups` WHERE `id` = $_POST[editid]");
		mysql_query("DELETE FROM `$FSXL[tableset]_user_groupconnect` WHERE `group` = $_POST[editid]");
		mysql_query("DELETE FROM `$FSXL[tableset]_user_groupaccess` WHERE `group` = $_POST[editid]");
		$FSXL[content] = '
				<div style="padding:20px; text-align:center;">'.$FS_PHRASES[main_usergroups_deleted].'</div>
		';
	}

	else
	{
		$index = mysql_query("UPDATE `$FSXL[tableset]_user_groups` SET `name` = '$_POST[name]' WHERE `id` = $_POST[editid]");
		// Name existiert schon
		if (!$index)
		{
			$FSXL[content] = '
				<div style="padding:20px; text-align:center;">'.$FS_PHRASES[main_usergroups_editfailed].'</div>
			';
		}
		else
		{
			// Zugriffrechte
			for ($i=0; $i<count($_POST[access]); $i++)
			{
				// Zugang setzen
				if ($_POST[checks][$i])
				{
					@mysql_query("INSERT INTO `$FSXL[tableset]_user_groupaccess` (`group`, `mod`, `page`) VALUES ($_POST[editid], '".$_POST[mod][$i]."', '".$_POST[access][$i]."')");
				}
				// Zugang löschen
				else
				{
					@mysql_query("DELETE FROM `$FSXL[tableset]_user_groupaccess` WHERE `mod` = '".$_POST[mod][$i]."' AND `page` = '".$_POST[access][$i]."' AND `group` = $_POST[editid]");
				}
			}

			// Edit ausgeführt
			$FSXL[content] = '
				<div style="padding:20px; text-align:center;">'.$FS_PHRASES[main_usergroups_edited].'</div>
			';
		}
	}
}


// User daten ausgeben
elseif ($_GET[id])
{
	settype($_GET[id], 'integer');
	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_user_groups` WHERE `id` = $_GET[id]");
	$group = mysql_fetch_assoc($index);

	$FSXL[content] .= '
				<form action="?mod=main&go=usergroups" method="post">
				<input type="hidden" name="action" value="edit">
				<input type="hidden" name="editid" value="'.$group[id].'">
				<table border="0" cellpadding="2" cellspacing="1" width="90%" style="margin:0px auto;">
					<tr>
						<td width="250"><b>'.$FS_PHRASES[main_usergroups_name].':</b></td>
						<td>
							<input class="textinput" name="name" style="width:300px;" value="'.$group[name].'">
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[news_edit_delete].':</b></td>
						<td>
							<input type="checkbox" name="del" id="delete" onClick="delMessage(\''.$FS_PHRASES[main_usergroups_delmessage].'\');">
						</td>
					</tr>
					<tr>
						<td colspan="2" align="right"><input type="submit" class="button" value="'.$FS_PHRASES[global_send].'"></td>
					</tr>
				</table>


				<table border="0" cellpadding="2" cellspacing="1" width="90%" style="margin:0px auto;">
					<tr>
						<td colspan="2">
							<span style="font-size:12pt"><b>'.$FS_PHRASES[main_user_accessof].' '.$group[name].'</b></span><hr>
						</td>
					</tr>
					<tr>
						<td>
							<table border="0" cellpadding="2" cellspacing="1" width="100%">
	';

	$index = @mysql_query("SELECT * FROM `$FSXL[tableset]_mod` order by `position`");
	$j = 0;
	while ($accesscat = mysql_fetch_assoc($index))
	{
		if (file_exists('mod_'.$accesscat[name].'/info.inc.php') && $accesscat[aktiv] == 1)
		{
			include('mod_'.$accesscat[name].'/info.inc.php');
			$FSXL[content] .= '
					<tr>
						<td colspan="2" class="alt0"><b>'.$FSXL[mod][$accesscat[name]][title].':</b></td>
					</tr>
			';
			for ($i=0; $i<count($FSXL[mod][$accesscat[name]][access]); $i++)
			{
				$index2 = mysql_query("SELECT * FROM `$FSXL[tableset]_user_groupaccess` WHERE `group` = $_GET[id] AND `mod` = '$accesscat[name]' AND `page` = '".$FSXL[mod][$accesscat[name]][access][$i]."'");
				if (mysql_num_rows($index2) == 0)
				{
					$checked = '';
				}
				else
				{
					$checked = 'checked';
				}

				$title = $accesscat[name] . '_menu_' . $FSXL[mod][$accesscat[name]][access][$i];
				$FSXL[content] .= '
					<tr>
						<td class="alt'.($j%2 == 0 ? 1 : 2).'" style="padding-left:50px;">
							'.$FS_PHRASES[$title].':
							<input type="hidden" name="mod['.$j.']" value="'.$accesscat[name].'">
							<input type="hidden" name="access['.$j.']" value="'.$FSXL[mod][$accesscat[name]][access][$i].'">
						</td>
						<td class="alt'.($j%2 == 0 ? 1 : 2).'" align="center">
							<input type="checkbox" name="checks['.$j.']" '.$checked.'>
						</td>
					</tr>
				';
				$j++;
			}
		}

	}

	$FSXL[content] .= '
							</table>
						</td>
					</tr>
					<tr>
						<td colspan="2" align="right">
							<input type="submit" class="button" value="'.$FS_PHRASES[global_send].'">
							</form>
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
				<form action="?mod=main&go=usergroups" method="post">
				<input type="hidden" name="action" value="addgroup">
				<table border="0" cellpadding="2" cellspacing="1" width="90%" style="margin:0px auto;">
					<tr>
						<td colspan="2">
							<span style="font-size:12pt"><b>'.$FS_PHRASES[main_usergroups_add].'</b></span><hr>
						</td>
					</tr>
					<tr>
						<td width="250"><b>'.$FS_PHRASES[main_usergroups_name].':</b></td>
						<td>
							<input class="textinput" name="name" style="width:300px;">
						</td>
					</tr>
					<tr>
						<td align="right" colspan="2">
							<input type="submit" class="button" value="'.$FS_PHRASES[global_send].'">
						</td>
					</tr>
				</table>
				</form>


				<table border="0" cellpadding="2" cellspacing="1" width="90%" style="margin:0px auto;">
					<tr>
						<td colspan="2">
							<span style="font-size:12pt"><b>'.$FS_PHRASES[main_usergroups_edit].'</b></span><hr>
						</td>
					</tr>
					<tr>
						<td>
							<table border="0" cellpadding="2" cellspacing="1" width="100%">
								<tr>
									<td class="alt0"><b>'.$FS_PHRASES[main_usergroups_name].'</b></td>
									<td class="alt0" align="center"><b>'.$FS_PHRASES[main_usergroups_user].'</b></td>
								</tr>
	';

	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_user_groups` ORDER BY `name`");
	$i=0;
	while ($group = mysql_fetch_assoc($index))
	{
		$i++;
		$index2 = mysql_query("SELECT * FROM `$FSXL[tableset]_user_groupconnect` WHERE `group` = $group[id]");
		$FSXL[content] .= '
					<tr>
						<td class="alt'.($i%2==0?1:2).'"><a href="?mod=main&go=usergroups&id='.$group[id].'">'.$group[name].'</a></td>
						<td class="alt'.($i%2==0?1:2).'" align="center">'.mysql_num_rows($index2).'</b></td>
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