<?php

// Passwort wiederherstellen
if ($_POST[email])
{
	$msg = new template('pwrecovered');

	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_userdata` WHERE `email` = '$_POST[email]'");
	// Gefunden
	if (mysql_num_rows($index) > 0)
	{
		$userdat = mysql_fetch_assoc($index);
		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_user` WHERE `id` = '$userdat[userid]'");
		
		$newpass = createRandomString(8);
		$salt = mysql_result($index, 0, 'salt');
		$username = mysql_result($index, 0, 'name');
		$md5pass = md5($newpass.$salt);
		
		$chk = mysql_query("UPDATE `$FSXL[tableset]_user` SET `password` = '$md5pass' WHERE `id` = '$userdat[userid]'");
		if ($chk)
		{
			$mail_tpl = new template('pwrecovermail');
			$mail_tpl->replaceTplVar('{pagename}', $FSXL[config][pagetitle]);			
			$mail_tpl->replaceTplVar('{username}', $username);
			$mail_tpl->replaceTplVar('{password}', $newpass);			
			
			sendMail($_POST[email], 'Password recovery@'.$FSXL[config][pagetitle], $mail_tpl->code);
		}
		
		$msg->switchCondition('recovered', true);
	}
	// Nicht gefunden
	else
	{
		$msg->switchCondition('recovered', false);
	}

	// Template ausgeben
	$FSXL[template] .= $msg->code;

}
// Formular ausgeben
else
{
	$form = new template('pwrecover');

	// Template ausgeben
	$FSXL[template] .= $form->code;
}

?>