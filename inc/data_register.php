<?php

if ($_POST[action] == 'reg')
{
	// Bot Verdacht
	if (($_POST[formdate] > $FSXL[time]-6) || $_POST[myemail])
	{
		$FSXL[template] .= errorMsg('errorbotdetect');
	}
	else
	{
		if ($_POST[name] && $_POST[pass] && $_POST[pass2] && $_POST[email])
		{
			if ($_POST[pass] == $_POST[pass2])
			{
				$salt = genSalt();
				$_POST[pass] = md5($_POST[pass].$salt);
				$ip = $_SERVER[REMOTE_ADDR];

				$index = mysql_query("INSERT INTO `$FSXL[tableset]_user` (`id`, `name`, `password`, `salt`)
							VALUES (NULL, '$_POST[name]', '$_POST[pass]', '$salt')");
				if ($index)
				{
					$id = mysql_insert_id();
					$index = mysql_query("INSERT INTO `$FSXL[tableset]_userdata` (`userid`, `email`, `adminstyle`, `style`, `homepage`, `icq`, `msn`, `regdate`, `regip`, `editor`, `adminlang`)
								VALUES ($id, '$_POST[email]', 1, 1, '$_POST[homepage]', '$_POST[icq]', '$_POST[msn]', '$FSXL[time]', '$ip', 1, 0)");
					if ($index)
					{
						// Template ausgeben
						$register_tpl = new template('regdone');
						$register_tpl->replaceTplVar('{username}', $_POST[name]);
						$FSXL[template] .= $register_tpl->code;
						unset($register_tpl);
					}
					// E-Mail existiert schon
					else
					{
						$FSXL[template] .= errorMsg('erroruserexists');
					}
				}
				// Benutzername existiert schon
				else
				{
					$FSXL[template] .= errorMsg('erroruserexists');
				}
			}
			// Passwörter stimmen nicht überein
			else
			{
				$FSXL[template] .= errorMsg('errorpassnotmatch');
			}
		}
		// Nicht alles ausgefüllt
		else
		{
			$FSXL[template] .= errorMsg('errornotfilled');
		}
	}
}


// Formular ausgeben
else
{
	// Template lesen
	$register_tpl = new template('regform');

	// Statische Variablen ersetzen
	$register_tpl->replaceTplVar('{time}', $FSXL[time]);

	// Template ausgeben
	$FSXL[template] .= $register_tpl->code;
	unset($register_tpl);
}

?>