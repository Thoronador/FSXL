<?php

// Beitrag auswerten
if ($_POST[contestid])
{
	unset($_GET[id]);
	settype($_POST[contestid], 'integer');
	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_contests` WHERE `id` = '$_POST[contestid]' AND `startdate` <= '$FSXL[time]' AND `enddate` >= '$FSXL[time]'");
	
	if (mysql_num_rows($index) > 0)
	{
		$contest = mysql_fetch_assoc($index);
		
		if ($_SESSION[loggedin])
		{
			// Prüfen ob User schon teilgenommen hat
			$index = mysql_query("SELECT `id` FROM `$FSXL[tableset]_contest_entries` WHERE `contest` = '$_POST[contestid]' AND `user` = ".$_SESSION[user]->userid);
			if (($contest[multiple] == 0 && mysql_num_rows($index) == 0) || $contest[multiple] == 1)
			{
				// Bildwetbewerb
				if ($contest[type] == 1 && $_FILES[img][tmp_name])
				{
					$ip = $_SERVER[REMOTE_ADDR];

					$chk = mysql_query("INSERT INTO `$FSXL[tableset]_contest_entries` (`id`, `contest`, `title`, `user`, `date`, `text`, `active`, `ip`)
										VALUES (NULL, '$contest[id]', '$_POST[title]', ".$_SESSION[user]->userid.", '$FSXL[time]', '$_POST[text]', 1, '$ip')");
												
					if ($chk)
					{
						$id = mysql_insert_id();
						$hash = md5($FSXL[time].$id);
						
						$img = new imgConvert();
						if ($img->readIMG($_FILES[img]))
						{
							$img->saveIMG('images/contests/'.$contest[id].'/', $hash, 'jpg');

							$img->scaleIMG($FSXL[config][contest_thumbx], $FSXL[config][contest_thumby], 'LETTERBOX', $FSXL[config][contest_thumbcolor]);
							$img->saveIMG('images/contests/'.$contest[id].'/', $hash.'s', 'jpg');

							$frame = new template('contestsubmitmsg');
							$frame->replaceTplVar('{contesttitle}', $contest[title]);
							$FSXL[template] .= $frame->code;
						}
						else
						{
							// Fehler ausgeben, Falsches Format
							mysql_query("DELETE FROM `$FSXL[tableset]_contest_entries` WHERE `id` = $id");
							$FSXL[template] .= errorMsg('error_wrongimg');
						}
					}
				}
				// Textwettbewerb
				elseif ($contest[type] == 2 && $_POST[text])
				{
					$ip = $_SERVER[REMOTE_ADDR];
					$chk = mysql_query("INSERT INTO `$FSXL[tableset]_contest_entries` (`id`, `contest`, `title`, `user`, `date`, `text`, `active`, `ip`)
										VALUES (NULL, '$contest[id]', '', ".$_SESSION[user]->userid.", '$FSXL[time]', '$_POST[text]', 1, '$ip')");

					if ($chk)
					{
						$frame = new template('contestsubmitmsg');
						$frame->replaceTplVar('{contesttitle}', $contest[title]);
						$FSXL[template] .= $frame->code;
					}
				}
				else
				{
					$_GET[id] = $_POST[contestid];
				}
			}
			else
			{
				$_GET[id] = $_POST[contestid];
			}
		}
		else
		{
			$_GET[id] = $_POST[contestid];
		}
	}
	// Contest nicht gefunden oder noch nicht freigegeben
	else
	{
		$FSXL[template] .= errorMsg('errorfilenotfound');
	}
}

// Artikel Detailansicht
if ($_GET[id])
{
	settype($_GET[id], 'integer');

	// Admin
	if ($_SESSION[user]->isadmin)
	{
		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_contests` WHERE `id` = '$_GET[id]'");
	}
	// User
	else
	{
		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_contests` WHERE `id` = '$_GET[id]' AND `startdate` <= '$FSXL[time]'");
	}

	if (mysql_num_rows($index) > 0)
	{
		$contest = mysql_fetch_assoc($index);

		// Pagetitle
		$FSXL[pgtitle] = $contest[title];

		// Template lesen
		$contest_tpl = new template('contestbody');
		
		// Contest offen?
		if ($contest[startdate] <= $FSXL[time]&& $contest[enddate] >= $FSXL[time])
		{
			$ctopen = true;

			if ($_SESSION[loggedin])
			{
				// Prüfen ob User schon teilgenommen hat
				if ($contest[multiple] == 0)
				{
					$index = mysql_query("SELECT `id` FROM `$FSXL[tableset]_contest_entries` WHERE `contest` = '$_GET[id]' AND `user` = ".$_SESSION[user]->userid);
					if (mysql_num_rows($index) > 0)
						$submitted = true;
					else
						$submitted = false;				
				}
				else $submitted = false;
			
				// Schon teilgenommen?
				$contest_tpl->switchCondition('user_submitted', $submitted);
		
				// Contest Typ
				$contest_tpl->switchCondition('img_contest', $contest[type]==1?true:false);
			}
		}
		else
		{
			$ctopen = false;
		}
		$contest_tpl->switchCondition('contest_open', $ctopen);
		
		
		// Einsendungen Link
		if ($contest[secret] == 1 || ($contest[secret] == 2 && $contest[enddate] < $FSXL[time]))
			$showentries = true;
		else
			$showentries = false;
		$contest_tpl->switchCondition('entries', $showentries);

		// Statische Variablen ersetzen
		$contest_tpl->replaceTplVar('{title}', $contest[title]);
		$contest_tpl->replaceTplVar('{description}', fscode($contest[text]));
		$contest_tpl->replaceTplVar('{startdate}', date($FSXL[config][dateformat], $contest[startdate]));
		$contest_tpl->replaceTplVar('{enddate}', date($FSXL[config][dateformat], $contest[enddate]));
		$contest_tpl->replaceTplVar('{contestid}', $contest[id]);

		// Template ausgeben
		$FSXL[template] .= $contest_tpl->code;
		unset($contest_tpl);
	}

	// Contest nicht gefunden oder noch nicht freigegeben
	else
	{
		$FSXL[template] .= errorMsg('errorfilenotfound');
	}
}

// Übersicht
elseif (!$_POST[contestid])
{
	$FSXL[template] .= errorMsg('errorfilenotfound');
}

?>