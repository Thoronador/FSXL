<?php

// Profil editieren
if ($_POST[action] == edit)
{
	if ($_POST[email])
	{
		if ($_POST[pass])
		{
			if ($_POST[pass] == $_POST[pass2])
			{
				$salt = genSalt();
				$_POST[pass] = md5($_POST[pass].$salt);
				$index = mysql_query("UPDATE `$FSXL[tableset]_user` SET `password` = '$_POST[pass]', `salt` = '$salt' WHERE `id` = " . $_SESSION[user]->userid);
				$break = false;
				@setcookie('username', $username, time());
				@setcookie('password', $userpass, time());
			}
			// Passwörter stimmen nicht überein
			else
			{
				$FSXL[template] .= errorMsg('errorpassnotmatch');
				$break = true;
			}
		}

		if ($break == false)
		{
			$index = mysql_query("UPDATE `$FSXL[tableset]_userdata` SET
						`email` = '$_POST[email]',
						`homepage` = '$_POST[homepage]',
						`icq` = '$_POST[icq]',
						`msn` = '$_POST[msn]'
						 WHERE `userid` = " . $_SESSION[user]->userid);

			if ($FSXL[config][user_select_style])
			{
				settype($_POST[style], 'integer');
				$index = mysql_query("UPDATE `$FSXL[tableset]_userdata` SET `style` = '$_POST[style]' WHERE `userid` = " . $_SESSION[user]->userid);
			}

			// Userdaten aktualisieren
			$_SESSION[user]->setUserdata();

			// Template ausgeben
			$profile_tpl = new template('profileeditdone');
			$FSXL[template] .= $profile_tpl->code;
			unset($profile_tpl);
		}
	}
	// Nicht alles ausgefüllt
	else
	{
		$FSXL[template] .= errorMsg('errornotfilled');
	}
}

// Formular ausgeben
else
{
	if ($_SESSION[loggedin])
	{
		// Template lesen
		$profile_tpl = new template('profile');

		// Styles lesen
		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_styles` ORDER BY `name`");
		$styleoptions = '<option value="0">default</option>';
		while ($style = mysql_fetch_assoc($index))
		{
			$styleoptions .= '<option value="'.$style[id].'" '.($style[id] == $_SESSION[user]->style ? "selected" : "").'>'.$style[name].'</option>';
		}

		// Statische Variablen ersetzen
		$profile_tpl->switchCondition('style_selectable', $FSXL[config][user_select_style]);
		$profile_tpl->replaceTplVar('{styleoptions}', $styleoptions);
		$profile_tpl->replaceTplVar('{username}', $_SESSION[user]->username);
		$profile_tpl->replaceTplVar('{email}', $_SESSION[user]->email);
		$profile_tpl->replaceTplVar('{homepage}', $_SESSION[user]->homepage);
		$profile_tpl->replaceTplVar('{icq}', $_SESSION[user]->icq);
		$profile_tpl->replaceTplVar('{msn}', $_SESSION[user]->msn);
		$profile_tpl->replaceTplVar('{regdate}', date($FSXL[config][dateformat], $_SESSION[user]->regdate));

		// Template ausgeben
		$FSXL[template] .= $profile_tpl->code;
		unset($profile_tpl);
	}
	else
	{
		$FSXL[template] .= errorMsg('errorfilenotfound');
	}
}

?>