<?php

// Übersicht
if ($_GET[id])
{
	settype($_GET[id], 'integer');

	// Admin
	if ($_SESSION[user]->isadmin)
	{
		$index = mysql_query("SELECT c.id AS `cid`, c.title AS `ctitle`, c.type AS `ctype`, c.enddate AS `enddate`, 
								c.secret AS `secret`, c.votedate AS `votedate`, c.analysis AS `analysis`, 
								e.title AS `etitle`, e.user AS `user`, e.text AS `text`, e.date AS `date`
								FROM `$FSXL[tableset]_contest_entries` e, `$FSXL[tableset]_contests` c
								WHERE e.id = '$_GET[id]' AND c.id = e.contest AND e.active = 1");
	}
	// User
	else
	{
		$index = mysql_query("SELECT c.id AS `cid`, c.title AS `ctitle`, c.type AS `ctype`, c.enddate AS `enddate`,
								c.secret AS `secret`, c.votedate AS `votedate`, c.analysis AS `analysis`,
								e.title AS `etitle`, e.user AS `user`, e.text AS `text`, e.date AS `date`
								FROM `$FSXL[tableset]_contest_entries` e, `$FSXL[tableset]_contests` c
								WHERE e.id = '$_GET[id]' AND c.id = e.contest AND e.active = 1 AND c.startdate <= '$FSXL[time]'");
	}

	if (mysql_num_rows($index) > 0)
	{
		$entry = mysql_fetch_assoc($index);
		
		if ($entry[secret] == 1 || ($entry[secret] == 2 && $entry[enddate] < $FSXL[time]))
		{
			// Pagetitle
			$FSXL[pgtitle] = $entry[ctitle] . ' - ' . $entry[etitle];

			// Template lesen
			$entry_tpl = new template('contestentry');
			$script_tpl = new template('contestvotescript');
			
			// User auslesen
			$index = mysql_query("SELECT `name` FROM `$FSXL[tableset]_user` WHERE `id` = '$entry[user]'");
			if (mysql_num_rows($index) > 0) $cuserdata = mysql_fetch_assoc($index);
			
			if ($entry[ctype] == 2)
			{
				$entry[etitle] = $cuserdata[name];
			}
			else
			{
				$hash = md5($entry[date].$_GET[id]);
			}

			// Statische Variablen ersetzen
			$entry_tpl->replaceTplVar('{contesttitle}', $entry[ctitle]);
			$entry_tpl->replaceTplVar('{entrytitle}', $entry[etitle]);
			$entry_tpl->replaceTplVar('{entrydescription}', fscode($entry[text]));
			$entry_tpl->replaceTplVar('{contestlink}', 'index.php?section=contestentries&id='.$entry[cid]);
			$entry_tpl->replaceTplVar('{image}', 'images/contests/'.$entry[cid].'/'.$hash.'.jpg');
			$entry_tpl->replaceTplVar('{username}', $cuserdata[name]);
			$entry_tpl->replaceTplVar('{entryid}', $_GET[id]);
			
			// Zahlen und Links
			$index = mysql_query("SELECT COUNT(`id`) AS `value` FROM `$FSXL[tableset]_contest_entries` 
									WHERE `date` <= '$entry[date]' AND `contest` = '$entry[cid]' ORDER BY `date`");
			$current = mysql_fetch_assoc($index);
			$entry_tpl->replaceTplVar('{currententry}', $current[value]);

			$index = mysql_query("SELECT COUNT(`id`) AS `value` FROM `$FSXL[tableset]_contest_entries` 
									WHERE `contest` = '$entry[cid]'");
			$total = mysql_fetch_assoc($index);
			$entry_tpl->replaceTplVar('{totalentries}', $total[value]);
			
			if ($current[value] == $total[value])
			{
				$nextlink = 'index.php?section=contestentries&id='.$entry[cid];
			}
			else
			{
				$index = mysql_query("SELECT `id` FROM `$FSXL[tableset]_contest_entries` 
										WHERE `date` > '$entry[date]' AND `contest` = '$entry[cid]' ORDER BY `date` LIMIT 1");
				$next = mysql_fetch_assoc($index);
				$nextlink = 'index.php?section=contestentry&id='.$next[id];
			}
			$entry_tpl->replaceTplVar('{nextlink}', $nextlink);

			if ($current[value] == 1)
			{
				$prevlink = 'index.php?section=contestentries&id='.$entry[cid];
			}
			else
			{
				$index = mysql_query("SELECT `id` FROM `$FSXL[tableset]_contest_entries` 
										WHERE `date` < '$entry[date]' AND `contest` = '$entry[cid]' ORDER BY `date` DESC LIMIT 1");
				$prev = mysql_fetch_assoc($index);
				$prevlink = 'index.php?section=contestentry&id='.$prev[id];
			}
			$entry_tpl->replaceTplVar('{prevlink}', $prevlink);

			// Wettbewerbsart
			$entry_tpl->switchCondition('img_contest', $entry[ctype]==1?true:false);

			// Votings
			if ($entry[analysis] == 3 && $entry[enddate] <= $FSXL[time] && $entry[votedate] >= $FSXL[time])
			{
				$vote = true;

				if ($_SESSION[user]->userid)
				{
					$index = mysql_query("SELECT * FROM `$FSXL[tableset]_contest_votes` WHERE `entry` = '$_GET[id]' AND `user` = ".$_SESSION[user]->userid);
					if (mysql_num_rows($index) > 0)
					{
						$votedata = mysql_fetch_assoc($index);
					}
				}

				$entry_tpl->replaceTplVar('{entrypoints}', $votedata[points]);
				$entry_tpl->switchCondition('user_voted', $votedata?true:false);
			}
			else
			{
				$vote = false;
			}
			$entry_tpl->switchCondition('vote', $vote);

			// Template ausgeben
			$FSXL[template] .= $script_tpl->code;
			$FSXL[template] .= $entry_tpl->code;
			unset($script_tpl);
			unset($entry_tpl);
		}
		
		// Einsendung noch unsichtbar
		else
		{
			$frame = new template('contestentrieshidden');
			$frame->replaceTplVar('{contesttitle}', $entry[ctitle]);
			$FSXL[template] .= $frame->code;
		}
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