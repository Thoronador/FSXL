<?php

$FS_PHRASES[main_profile_title] = str_replace('%u', strtoupper($_SESSION[user]->username), $FS_PHRASES[main_profile_title]);
$FSXL[title] = $FS_PHRASES[main_profile_title];

// User bearbeiten
if ($_POST[action] == "edit")
{
	// Neues Passwort
	if ($_POST[oldpass] && $_POST[newpass] && $_POST[newpass2])
	{
		// Bestätigungspasswort falsch
		if ($_POST[newpass] != $_POST[newpass2])
		{
			$FSXL[error] = true;
			$FSXL[msg] = $FS_PHRASES[main_profile_error_differentpass];
			$FSXL[title] = $FS_PHRASES[main_profile_error_title];
		}
		else
		{
			$index = mysql_query("SELECT `salt` FROM `$FSXL[tableset]_user` WHERE `id` = " . $_SESSION[user]->userid);
			$salt = @mysql_result($index, 0, 'salt');
			// Altes Passwort inkorrekt
			if (md5($_POST[oldpass].$salt) != $_SESSION[user]->password)
			{
				$FSXL[error] = true;
				$FSXL[msg] = $FS_PHRASES[main_profile_error_wrongpass];
				$FSXL[title] = $FS_PHRASES[main_profile_error_title];
			}
			else
			{
				$salt = genSalt();
				$pass = md5($_POST[newpass].$salt);
				$index = mysql_query("UPDATE `$FSXL[tableset]_user`
							SET `password` = '$pass', `salt` = '$salt'
							WHERE `id` = " . $_SESSION[user]->userid);
				$relog = "logout";
			}
		}
	}
	// kein neues Passwort
	elseif ($_POST[newpass] == "" && $_POST[newpass2] == "")
	{
	}
	// unvollständiges neues Passwort
	else
	{
		$FSXL[error] = true;
		$FSXL[msg] = $FS_PHRASES[main_profile_error_formnotfilled];
		$FSXL[title] = $FS_PHRASES[main_profile_error_title];
	}

	if (!$FSXL[error])
	{
		// restliche Daten aktualisieren
		settype($_POST[adminstyle], "integer");
		settype($_POST[editor], "integer");

		$index = mysql_query("UPDATE `$FSXL[tableset]_userdata`
					SET `email` = '$_POST[email]',
						`adminstyle` = '$_POST[adminstyle]',
						`editor` = '$_POST[editor]',
						`homepage` = '$_POST[homepage]',
						`icq` = '$_POST[icq]',
						`msn` = '$_POST[msn]',
						`adminlang` = '$_POST[adminlang]'
					WHERE `userid` = " . $_SESSION[user]->userid);
		if (!$relog) $relog = "reload";
	}
	if ($relog == "logout")
	{
		reloadPage("logout.php");
	}
	elseif ($relog == "reload")
	{
		$_SESSION[user]->setUserdata();
		reloadPage("?mod=main&go=profil&done=1");
	}
}

// Änderung erfolgreich
elseif ($_GET[done])
{
	$tpl = new adminPage();
	$tpl->newMsgBox($FS_PHRASES[main_profile_editdone]);
	$FSXL[content] = $tpl->code;
}

// Eingabeformular
else
{
	// Neues Admin Template erzeugen
	$tpl = new adminPage();

	// Formular beginnen
	$tpl->openForm('?mod=main&go=profil');
	$tpl->newHiddenInput('action', 'edit');
	$tpl->openTable();

	// Benutzerdaten
	$tpl->newTblHeadline($FS_PHRASES[main_profile_userdata]);
	$tpl->newTblText($FS_PHRASES[main_profile_username], $_SESSION[user]->username);
	$tpl->newTblPassword($FS_PHRASES[main_profile_oldpass], 'oldpass');
	$tpl->newTblPassword($FS_PHRASES[main_profile_newpass], 'newpass');
	$tpl->newTblPassword($FS_PHRASES[main_profile_confirmpass], 'newpass2');
	$tpl->newTblInput($FS_PHRASES[main_profile_email], 'email', $_SESSION[user]->email);

	// Zusätzliche Daten
	$tpl->newTblSpacer();
	$tpl->newTblHeadline($FS_PHRASES[main_profile_extradata]);
	$tpl->newTblInput($FS_PHRASES[main_profile_homepage], 'homepage', $_SESSION[user]->homepage);
	$tpl->newTblInput($FS_PHRASES[main_profile_icq], 'icq', $_SESSION[user]->icq);
	$tpl->newTblInput($FS_PHRASES[main_profile_msn], 'msn', $_SESSION[user]->msn);
	$tpl->openTblSelect($FS_PHRASES[main_profile_adminstyle], 'adminstyle', 150);
	$tpl->newSelectOption(1, 'FrogGreen', ($_SESSION[user]->adminstyle == 1 ? true : false));
	$tpl->newSelectOption(2, 'FrogRed', ($_SESSION[user]->adminstyle == 2 ? true : false));
	$tpl->newSelectOption(3, 'FrogBlue', ($_SESSION[user]->adminstyle == 3 ? true : false));
	$tpl->closeTblSelect();
	$tpl->openTblSelect($FS_PHRASES[main_profile_editor], 'editor', 150);
	$tpl->newSelectOption(0, $FS_PHRASES[main_profile_frogpad], ($_SESSION[user]->editor == 0 ? true : false));
	$tpl->newSelectOption(1, $FS_PHRASES[main_profile_frogedit], ($_SESSION[user]->editor == 1 ? true : false));
	$tpl->newSelectOption(2, $FS_PHRASES[main_profile_textfield], ($_SESSION[user]->editor == 2 ? true : false));
	$tpl->closeTblSelect();
	$tpl->openTblSelect($FS_PHRASES[main_profile_language], 'adminlang', 150);
	$FS_PHRASES[main_profile_default] = str_replace('%l', $FSXL[languages][$FSXL[config][syslanguage]][1], $FS_PHRASES[main_profile_default]);
	$tpl->newSelectOption(0, $FS_PHRASES[main_profile_default], ($_SESSION[user]->adminlang == 0 ? true : false));
	foreach ($FSXL[languages] AS $key => $lang)
	{
		$tpl->newSelectOption($lang[0], $lang[1], ($_SESSION[user]->adminlangid == $lang[0] ? true : false));
	}
	$tpl->closeTblSelect();

	// Formular schließen
	$tpl->newTblSubmitButton($FS_PHRASES[global_send]);
	$tpl->closeTable();
	$tpl->closeForm();

	$FSXL[content] = $tpl->code;
}

?>