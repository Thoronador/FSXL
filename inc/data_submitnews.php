<?php

if ($FSXL[config][submitnews] == 1)
{
	// News eintragen
	if ($_POST[action] == 'submit' && $_POST[title] && $_POST[text] && $_POST[source] && $_SESSION[user]->userid)
	{
		// Doppelten Eintrag überprüfen
		$index = mysql_query("SELECT `id` FROM `$FSXL[tableset]_news_submit` WHERE `text` = '$_POST[text]'");
		if (mysql_num_rows($index) == 0)
		{
			// News eintragen
			if (!$_POST[email] && ($_SESSION[submittime] > 0) && ($_SESSION[submittime] < $FSXL[time]-6))
			{
				unset($_SESSION[submittime]);
				$chk = mysql_query("INSERT INTO `$FSXL[tableset]_news_submit` (`id`, `title`, `text`, `source`, `date`, `user`, `ip`)
									VALUES (NULL, '$_POST[title]', '$_POST[text]', '$_POST[source]', '$FSXL[time]', ".$_SESSION[user]->userid.", '$_SERVER[REMOTE_ADDR]')");
			}
			
			// Mail versenden
			if ($FSXL[config][newssubmitmail] == 1)
			{
				$id = mysql_insert_id();
				@include ('admin/mod_news/lang_'.$FSXL[config][syslanguage].'.php');
				$mailbody = str_replace('%s', $FSXL[config][pagetitle], $FS_PHRASES[news_submit_mail_body]);
				$content = $_POST[title] . "\n" . $_POST[text] . "\n" . $_POST[source];
				$mailbody = str_replace('%t', stripslashes(str_replace('\r\n', "\n", $content)), $mailbody);
				$mailbody .= "\n\n";
				$mailbody .= $_SESSION[user]->username . "\n\n";
				$mailbody .= 'http://'.$_SERVER["SERVER_NAME"].substr($_SERVER["SCRIPT_NAME"], 0, strlen($_SERVER["SCRIPT_NAME"])-9).'admin/?mod=news&go=addnews&submit='.$id;
				
				$mailsubject = str_replace('%s', $FSXL[config][pagetitle], $FS_PHRASES[news_submit_mail_subject]);
				
				sendPermissionMail('news/submit', $mailsubject, $mailbody);
			}

			// Template lesen
			$submit_tpl = new template('newssubmitted');
			$submit_tpl->switchCondition('submitted', $chk);
		}
		// Doppelt eingesendet
		else
		{
			// Template lesen
			$submit_tpl = new template('newssubmitted');
			$submit_tpl->switchCondition('submitted', false);
		}

		// Template ausgeben
		$FSXL[template] .= $submit_tpl->code;
		unset($submit_tpl);
	}

	// Formular
	else
	{
		$_SESSION[submittime] = $FSXL[time];

		// Template lesen
		$submit_tpl = new template('newssubmit');

		// Template ausgeben
		$FSXL[template] .= $submit_tpl->code;
		unset($submit_tpl);
	}
}
else
{
	$FSXL[template] .= errorMsg('errorfilenotfound');
}

?>