<?php

if ($_SESSION[loginerror])
{
	switch($_SESSION[loginerror])
	{
		case 'error_login_timeout':
			$type = 'errortimeout';
			break;
		case 'error_login_nouser':
			$type = 'errornouser';
			break;
		case 'error_login_wrongpass':
			$type = 'errorwrongpass';
			break;
	}

	// Template lesen
	$msg = new template($type);
	$frame = new template('errormsg');
	$frame->replaceTplVar('{message}', $msg->code);
	$frame->replaceTplVar('{trys}', $FSXL[config][login_attempts] - $_SESSION[user]->logins - 1);
	$frame->replaceTplVar('{minutes}', $FSXL[config][login_blocktime]);

	// Template ausgeben
	$FSXL[template] .= $frame->code;
}
else
{
	reloadPage();
}

?>