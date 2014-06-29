<?php

if ($_POST[pollid] && $_POST[pollanswer])
{
	$time = time();
	settype($_POST[pollid], 'integer');
	$index = @mysql_query("SELECT * FROM `$FSXL[tableset]_poll` WHERE `id` = '$_POST[pollid]' AND `startdate` <= '$time' AND `enddate` >= '$time'");
	if (mysql_num_rows($index) > 0)
	{
		$poll = mysql_fetch_assoc($index);
		if ($poll[useronly] == 1 && $_SESSION[loggedin])
		{
			$index = @mysql_query("INSERT INTO `$FSXL[tableset]_poll_userlist` (`poll`, `user`) VALUES ('$poll[id]', ".$_SESSION[user]->userid.")");
			// User kann teilnehmen
			if ($index)
			{
				$dopoll = true;
			}
		}
		else
		{
			$index = @mysql_query("INSERT INTO `$FSXL[tableset]_poll_iplist` (`poll`, `ip`) VALUES ('$poll[id]', '$_SERVER[REMOTE_ADDR]')");
			// Besucher kann teilnehmen
			if ($index)
			{
				@setcookie('fsxl_poll_'.$poll[id], true, $poll[enddate]);
				$dopoll = true;
			}
		}
	}

	// Poll bearbeiten
	if ($dopoll == true)
	{
		if ($poll[multiselect] == 1)
		{
			foreach($_POST[pollanswer] AS $key => $value)
			{
				settype($key, 'integer');
				@mysql_query("UPDATE `$FSXL[tableset]_poll_answers` SET `hits` = `hits` + 1 WHERE `id` = '$key' AND `poll` = '$poll[id]'");
			}
		}
		else
		{
			settype($_POST[pollanswer], 'integer');
			@mysql_query("UPDATE `$FSXL[tableset]_poll_answers` SET `hits` = `hits` + 1 WHERE `id` = '$_POST[pollanswer]' AND `poll` = '$poll[id]'");
		}
	}
}

?>