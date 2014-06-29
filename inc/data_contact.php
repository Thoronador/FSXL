<?php

// Email versenden
if ($_POST[action] == 'submit')
{
	// Bot Verdacht
	if (($_POST[time] > $FSXL[time]-6) || $_POST[mail])
	{
		$FSXL[template] .= errorMsg('errorbotdetect');
	}
	else
	{
		// POST Variablen auflisten
		$content = '';
		foreach($_POST AS $key => $value)
		{
			if ($key != 'action' && $key != 'PHPSESSID' && $key != 'time' && $key != 'mail')
			{
				$content .= $key . ":\n" . $value . "\n\n";
				if (preg_match("/(betreff|subject)/i", $key) && !$subject) {
					$subject = $value;
				}
			}
		}

		// Job erstellen
		if ($FSXL[config][contacttojob] == 1)
		{
			@include ('admin/mod_main/lang_'.$FSXL[config][syslanguage].'.php');
			$subject = $FS_PHRASES[main_jobs_contact] . ' (' . $subject . ')';
			
			$chk = mysql_query("INSERT INTO `$FSXL[tableset]_jobs` (`id`, `name`, `desc`, `date`, `autor`, `edate`, `user`, `state`, `cdate`)
								VALUES (NULL, '$subject', '$content', '$FSXL[time]', 1, 0, 0, 1, 0)");

			if ($chk)
			{
				// Mail versenden
				if ($FSXL[config][jobmail] == 1)
				{
					$id = mysql_insert_id();
					$mailbody = str_replace('%s', $FSXL[config][pagetitle], $FS_PHRASES[main_jobs_mail_body]);
					$mailbody = str_replace('%t', stripslashes(str_replace('\r\n', "\n", $content)), $mailbody);
					$mailbody .= "\n\n";
					$mailbody .= 'http://'.$_SERVER["SERVER_NAME"].substr($_SERVER["SCRIPT_NAME"], 0, strlen($_SERVER["SCRIPT_NAME"])-9).'admin';
					
					$mailsubject = str_replace('%s', $FSXL[config][pagetitle], $FS_PHRASES[main_jobs_mail_subject]);;
					
					sendPermissionMail('main/jobs', $mailsubject, $mailbody);
				}
			}
		}
		// verschicke die E-Mail
		else
		{
			sendMail($FSXL[config][kontaktmail], 'contact@'.$FSXL[config][pagetitle], $content);
		}

		// Template lesen
		$contact_tpl = new template('contactsend');

		// Template ausgeben
		$FSXL[template] .= $contact_tpl->code;
		unset($contact_tpl);
	}
}

// Formular
else
{
	// Template lesen
	$contact_tpl = new template('contact');
	$contact_tpl->replaceTplVar('{time}', $FSXL[time]);

	// Template ausgeben
	$FSXL[template] .= $contact_tpl->code;
	unset($contact_tpl);
}

?>