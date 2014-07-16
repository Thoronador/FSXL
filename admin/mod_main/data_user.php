<?php

$FSXL[title] = $FS_PHRASES[main_user_title];


// User editieren
if ($_POST[action] == edit && $_POST[name] && $_POST[email])
{
	settype($_POST[userid], 'integer');
	// Kein Superadmin
	if (in_array($_POST[userid], $FSXL[superadmin], true) && !in_array($_SESSION[user]->userid, $FSXL[superadmin]))
	{
		$FSXL[content] = '
				<div style="padding:20px; text-align:center;">'.$FS_PHRASES[main_user_onlysuperadmin].'</div>
		';
	}
	else
	{
		// Gruppe hinzufügen
		if ($_POST[addgroup] != 0)
		{
			settype($_POST[addgroup], 'integer');
			mysql_query("INSERT INTO `$FSXL[tableset]_user_groupconnect` (`user`, `group`) VALUES ($_POST[userid], $_POST[addgroup])");
		}
		// Gruppelöschen
		if ($_POST[delgroup])
		{
			foreach ($_POST[delgroup] AS $key => $value)
			{
				settype($key, 'integer');
				mysql_query("DELETE FROM `$FSXL[tableset]_user_groupconnect` WHERE `user` = $_POST[userid] AND `group` = $key");
			}
		}

		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_user` WHERE `name` = '$_POST[name]' AND `id` != $_POST[userid]");
		// Name existiert schon
		if (mysql_num_rows($index) >= 1)
		{
			$FSXL[content] = '
				<div style="padding:20px; text-align:center;">'.$FS_PHRASES[main_user_nameexists].'</div>
			';
		}
		else
		{
			// Mit neuem Passwort
			if ($_POST[newpass])
			{
				$userindex = mysql_query("SELECT * FROM `$FSXL[tableset]_user` WHERE `id` = '$_POST[userid]'");
				$salt = mysql_result($userindex, 0, 'salt');
				$md5pass = md5($_POST[newpass].$salt);
				$index = mysql_query("UPDATE `$FSXL[tableset]_user` SET `name` = '$_POST[name]', `password` = '$md5pass' WHERE `id` = '$_POST[userid]'");
			}
			// Ohne Passwort
			else
			{
				$index = mysql_query("UPDATE `$FSXL[tableset]_user` SET `name` = '$_POST[name]' WHERE `id` = $_POST[userid]");
			}

			$index = mysql_query("UPDATE `$FSXL[tableset]_userdata` SET
						`email` = '$_POST[email]',
						`homepage` = '$_POST[homepage]',
						`icq` = '$_POST[icq]',
						`msn` = '$_POST[msn]'
						WHERE `userid` = $_POST[userid]");

			// Zugriffrechte
			for ($i=0; $i<count($_POST[access]); $i++)
			{
				// Zugang setzen
				if ($_POST[checks][$i])
				{
					mysql_query("INSERT INTO `$FSXL[tableset]_useraccess` (`userid`, `mod`, `page`) VALUES ($_POST[userid], '".$_POST[mod][$i]."', '".$_POST[access][$i]."')");
				}
				// Zugang löschen
				else
				{
					mysql_query("DELETE FROM `$FSXL[tableset]_useraccess` WHERE `mod` = '".$_POST[mod][$i]."' AND `page` = '".$_POST[access][$i]."' AND `userid` = $_POST[userid]");
				}
			}

			// Edit ausgeführt
			$FSXL[content] = '
				<div style="padding:20px; text-align:center;">'.$FS_PHRASES[main_user_editdone].'</div>
			';
		}
	}
}


// User daten ausgeben
elseif ($_GET[id])
{
	settype($_GET[id], 'integer');
	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_user` WHERE `id` = $_GET[id]");
	$userinfo = mysql_fetch_assoc($index);
	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_userdata` WHERE `userid` = $_GET[id]");
	$userdata = mysql_fetch_assoc($index);

	$FSXL[content] .= '
				<form action="?mod=main&go=user" method="post">
				<input type="hidden" name="action" value="edit">
				<input type="hidden" name="userid" value="'.$_GET[id].'">
				<table border="0" cellpadding="2" cellspacing="1" width="90%" style="margin:0px auto;">
					<tr>
						<td colspan="2">
							<span style="font-size:12pt"><b>'.$FS_PHRASES[main_user_dataof].' '.$userinfo[name].'</b></span><hr>
						</td>
					</tr>
	';

	// Kein Superadmin
	if (in_array($_GET[id], $FSXL[superadmin], true) && !in_array($_SESSION[user]->userid, $FSXL[superadmin]))
	{
		$FSXL[content] .= '
					<tr>
						<td colspan="2"><b>'.$FS_PHRASES[main_user_onlysuperadmin].'</b></td>
					</tr>
		';
	}
	else
	{
		$userinfo[name] = str_replace('"',"&quot;", $userinfo[name]);
		$FSXL[content] .= '
					<tr>
						<td width="250"><b>'.$FS_PHRASES[main_profile_username].':</b></td>
						<td><input class="textinput" name="name" style="width:300px;" value="'.$userinfo[name].'"></td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[main_profile_newpass].':</b></td>
						<td><input class="textinput" type="password" name="newpass" style="width:300px;"></td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[main_profile_email].':</b></td>
						<td><input class="textinput" name="email" style="width:300px;" value="'.$userdata[email].'"></td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[main_profile_homepage].':</b></td>
						<td><input class="textinput" name="homepage" style="width:300px;" value="'.$userdata[homepage].'"></td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[main_profile_icq].':</b></td>
						<td><input class="textinput" name="icq" style="width:300px;" value="'.$userdata[icq].'"></td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[main_profile_msn].':</b></td>
						<td><input class="textinput" name="msn" style="width:300px;" value="'.$userdata[msn].'"></td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[main_user_regdate].':</b></td>
						<td>'.date("d.m.Y | H:i", $userdata[regdate]).'</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[main_user_regip].':</b></td>
						<td>'.$userdata[regip].'</td>
					</tr>
					<tr>
						<td colspan="2" align="right">
							<input type="submit" class="button" value="'.$FS_PHRASES[global_send].'">
						</td>
					</tr>
		';
	}
	$FSXL[content] .= '
					<tr>
						<td colspan="2">
							<span style="font-size:12pt"><b>'.$FS_PHRASES[main_user_groupsof].' '.$userinfo[name].'</b></span><hr>
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[main_user_addgroup].':</b></td>
						<td>
							<select name="addgroup" class="textinput" style="width:305px;">
								<option value="0">'.$FS_PHRASES[main_user_selectgroup].'</option>
								<option value="0">-------------------------------</option>
	';

	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_user_groups` ORDER BY `name`");
	while ($group = mysql_fetch_assoc($index))
	{
		$FSXL[content] .= '
								<option value="'.$group[id].'">'.$group[name].'</option>
		';
	}

	$FSXL[content] .= '
							</select>
						</td>
					</tr>
				</table>
				<table border="0" cellpadding="2" cellspacing="1" width="90%" style="margin:0px auto;">
	';

	$index = mysql_query("SELECT g.name AS name, g.id AS id
				FROM $FSXL[tableset]_user_groups g, $FSXL[tableset]_user_groupconnect c
				WHERE g.id = c.group AND c.user = $userinfo[id] ORDER BY g.name");
	$i = 0;
	while ($group = mysql_fetch_assoc($index))
	{
		$FSXL[content] .= '
					<tr>
						<td class="alt'.($i%2 == 0 ? 1 : 2).'" style="padding-left:5px;">
							'.$group[name].'
						</td>
						<td class="alt'.($i%2 == 0 ? 1 : 2).'" align="center">
							'.$FS_PHRASES[main_imgmanager_del].'
							<input type="checkbox" name="delgroup['.$group[id].']" style="margin-bottom:-2px;">
						</td>
					</tr>
		';
		$i++;
	}

	$FSXL[content] .= '
					<tr>
						<td colspan="2" align="right">
							<input type="submit" class="button" value="'.$FS_PHRASES[global_send].'">
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<span style="font-size:12pt"><b>'.$FS_PHRASES[main_user_accessof].' '.$userinfo[name].'</b></span><hr>
						</td>
					</tr>
				</table>
				<table border="0" cellpadding="0" cellspacing="1" align="center" width="90%" style="margin:0px auto;">
					<tr>
						<td>
							<table border="0" cellpadding="2" cellspacing="1" width="100%">
	';

	// Superadmin
	if (in_array($_GET[id], $FSXL[superadmin], true))
	{
			$FSXL[content] .= '
					<tr>
						<td colspan="2"><b>'.$FS_PHRASES[main_user_accessnotedit].'</b></td>
					</tr>
			';
	}

	// Kein Superadmin
	else
	{
	$index = @mysql_query("SELECT * FROM `$FSXL[tableset]_mod` order by `position`");
	$j = 0;
	while ($accesscat = mysql_fetch_assoc($index))
	{
		if (file_exists('mod_'.$accesscat[name].'/info.inc.php') && $accesscat[aktiv] == 1)
		{
			include('mod_'.$accesscat[name].'/info.inc.php');
			$FSXL[content] .= '
					<tr>
						<td class="alt0" colspan="2"><b>'.$FSXL[mod][$accesscat[name]][title].':</b></td>
					</tr>
			';
			for ($i=0; $i<count($FSXL[mod][$accesscat[name]][access]); $i++)
			{
				$index2 = mysql_query("SELECT * FROM `$FSXL[tableset]_useraccess` WHERE `userid` = $_GET[id] AND `mod` = '$accesscat[name]' AND `page` = '".$FSXL[mod][$accesscat[name]][access][$i]."'");
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
						<td class="alt'.($j%2 == 0 ? 1 : 2).'" align="center"><input type="checkbox" name="checks['.$j.']" '.$checked.'></td>
					</tr>
				';
				$j++;
			}
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
						</td>
					</tr>
				</table>
				</form>
	';
}

// Übersicht
else
{
	// Suchergebisse ausgeben
	if ($_POST[search])
	{
		$index = mysql_query("SELECT `id`, `name` FROM `$FSXL[tableset]_user` WHERE `name` LIKE '%$_POST[search]%' ORDER BY `name`");
		if (mysql_num_rows($index) == 1)
		{
			$userinfo = mysql_fetch_assoc($index);
			reloadPage('?mod=main&go=user&id=' . $userinfo[id]);
		}
		else
		{
			$extrahtml = '
				<p>
				<table border="0" cellpadding="2" cellspacing="1" width="90%" style="margin:0px auto;">
					<tr>
						<td>
							<span style="font-size:12pt"><b>'.$FS_PHRASES[main_user_searchresult].'</b></span><hr>
						</td>
					</tr>
					<tr>
						<td>
			';
			while ($userinfo = mysql_fetch_assoc($index))
			{
				$extrahtml .= '<a href="?mod=main&go=user&id=' . $userinfo[id] . '">' . $userinfo[name] . '</a>, ';
			}
			$extrahtml = substr($extrahtml, 0, strlen($extrahtml)-2); 
			$extrahtml .= '
						</td>
					</tr>
				</table>
			';
		}
	}

	$FSXL[content] .= '
				<form action="?mod=main&go=user" method="post">
				<table border="0" cellpadding="2" cellspacing="1" width="90%" style="margin:0px auto;">
					<tr>
						<td colspan="2">
							<span style="font-size:12pt"><b>'.$FS_PHRASES[main_user_finduser].'</b></span><hr>
						</td>
					</tr>
					<tr>
						<td width="250"><b>'.$FS_PHRASES[main_usergroups_name].':</b><br>'.$FS_PHRASES[main_user_finduser_sub].'</td>
						<td>
							<input class="textinput" name="search" style="width:300px;">
						</td>
					</tr>
					<tr>
						<td colspan="2" align="right">
							<input style="float:right;" type="submit" class="button" value="'.$FS_PHRASES[global_search].'">
						</td>
					</tr>
				</table>
				</form>
				'.$extrahtml.'
	';

	// Benutzergruppen
	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_user_groups` ORDER BY `name`");
	while ($group = mysql_fetch_assoc($index))
	{
		$FSXL[content] .= '
				<p>
				<table border="0" cellpadding="2" cellspacing="1" width="90%" style="margin:0px auto;">
					<tr>
						<td>
							<span style="font-size:12pt"><b>'.$group[name].'</b></span><hr>
						</td>
					</tr>
					<tr>
						<td>
		';

		$index2 = mysql_query("SELECT u.name AS name, u.id AS id
					FROM $FSXL[tableset]_user_groupconnect c, $FSXL[tableset]_user u
					WHERE u.id = c.user AND c.group = $group[id]");
		while ($userdata = mysql_fetch_assoc($index2))
		{
			$FSXL[content] .= '<a href="?mod=main&go=user&id=' . $userdata[id] . '">' . $userdata[name] . '</a>, ';
		}

		$FSXL[content] .= '
						</td>
					</tr>
				</table>
		';
	}

	$FSXL[content] .= '
				<p>
				<table border="0" cellpadding="2" cellspacing="1" width="90%" style="margin:0px auto;">
					<tr>
						<td>
							<span style="font-size:12pt"><b>'.$FS_PHRASES[main_user_adminuser].'</b></span><hr>
						</td>
					</tr>
					<tr>
						<td>
	';

	// Adminuser
	$index = mysql_query("SELECT u.name AS name, u.id AS id
				FROM $FSXL[tableset]_useraccess a, $FSXL[tableset]_user u
				WHERE u.id = a.userid GROUP BY a.userid ORDER BY u.name");
	while ($userdata = mysql_fetch_assoc($index))
	{
		$FSXL[content] .= '<a href="?mod=main&go=user&id=' . $userdata[id] . '">' . $userdata[name] . '</a>, ';
	}

	$FSXL[content] .= '
						</td>
					</tr>
				</table>
	';
}

?>