<?php

include('../admin/inc/class.inc.php');
include('../admin/inc/config.inc.php');
include('../admin/inc/functions.inc.php');

session_start();

// Datanbank Verbindung aufbauen
@$db = new mysql($SQL[host], $SQL[user], $SQL[pass], $SQL[data]);
if (!$db->error[error])
{	
	if ($_POST[entryid] && $_POST[points] && $_SESSION[user]->userid)
	{
		settype($_POST[entryid], 'integer');
		$index = mysql_query("SELECT c.id AS `contestid`, c.votedate AS `votedate`, c.enddate AS `enddate`, c.analysis AS `analysis`
								FROM `$FSXL[tableset]_contest_entries` e, `$FSXL[tableset]_contests` c
								WHERE e.id = '$_POST[entryid]' AND c.id = e.contest");
		// Eintrag vorhanden?
		if (mysql_num_rows($index) > 0)
		{
			$contest = mysql_fetch_assoc($index);
			// Zum Voten geöffnet?
			if ($contest[enddate] <= time() && $contest[votedate] >= time() && $contest[analysis] == 3)
			{
				settype($_POST[points], 'integer');
				$chk = mysql_query("INSERT INTO `$FSXL[tableset]_contest_votes` (`contest`, `entry`, `user`, `points`)
									VALUES ('$contest[contestid]', '$_POST[entryid]', ".$_SESSION[user]->userid.", '$_POST[points]')");
				if ($chk)
				{
					echo $_POST[entryid] . '_' . $_POST[points];
				}
			}
		}
	}
	
	// Datenbank Verbindung schließen
	$db->close();
}

?>