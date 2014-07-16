<?php

// Übersicht
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
		
		if ($contest[secret] == 1 || ($contest[secret] == 2 && $contest[enddate] < $FSXL[time]))
		{
			// Pagetitle
			$FSXL[pgtitle] = $contest[title];

			// Template lesen
			$contest_tpl = new template('contestentries');
			$script_tpl = new template('contestvotescript');
		
			// Statische Variablen ersetzen
			$contest_tpl->replaceTplVar('{contesttitle}', $contest[title]);
			$contest_tpl->replaceTplVar('{contestdescription}', fscode($contest[text]));

			// Votings
			if ($contest[analysis] == 3 && $contest[enddate] <= $FSXL[time] && $contest[votedate] >= $FSXL[time])
			{
				$vote = true;
				
				if ($_SESSION[user]->userid)
				{
					$index = mysql_query("SELECT * FROM `$FSXL[tableset]_contest_votes` WHERE `contest` = '$contest[id]' AND `user` = ".$_SESSION[user]->userid);
					$uservotes = array();
					while ($votedata = mysql_fetch_assoc($index))
					{
						$uservotes[$votedata[entry]] = $votedata[points];
					}
				}
			}
			else
			{
				$vote = false;
			}
			$contest_tpl->switchCondition('vote', $vote);
			
			// Wettbewerbsart
			$contest_tpl->switchCondition('img_contest', $contest[type]==1?true:false);
			
			// Pages
			$index = mysql_query("SELECT COUNT(`id`) AS `value` FROM `$FSXL[tableset]_contest_entries` WHERE `contest` = '$contest[id]'");
			if (mysql_num_rows($index) > 0) {
				$rows = mysql_result($index, 0, 'value');
				$pages = ceil($rows/$FSXL[config][contest_perpage]);
			} else {
				$pages = 1;
			}
			
			if (!$_GET[page]) {
				$_GET[page] = 1;
			}
			$start = ($_GET[page]-1) * $FSXL[config][contest_perpage];

			$contest_tpl->getItem('page');
			for ($i=1; $i<=$pages; $i++)
			{
				$contest_tpl->newItemNode('page');
				$contest_tpl->replaceNodeVar('{pagenum}', $i);
				$contest_tpl->replaceNodeVar('{pagelink}', '?section=contestentries&id='.$contest[id].'&page='.$i);
				$contest_tpl->switchCondition('currentpage', ($_GET[page]==$i?true:false), true);
			}
			$contest_tpl->replaceItem('page');

			// Einträge
			$contest_tpl->getItem('entry');
			$index = mysql_query("SELECT u.name AS `username`, e.title AS `title`, e.id AS `id`, e.date AS `date`, e.text AS `text`
									FROM `$FSXL[tableset]_contest_entries` e, `$FSXL[tableset]_user` u 
									WHERE u.id = e.user AND e.active = 1 AND e.contest = '$contest[id]' ORDER BY e.date
									LIMIT $start, ".$FSXL[config][contest_perpage]);

			while ($entry = mysql_fetch_assoc($index))
			{
				$contest_tpl->newItemNode('entry');
				if($contest[type] == 1)
				{
					$hash = md5($entry[date].$entry[id]);
					$contest_tpl->replaceNodeVar('{entrythumb}', 'images/contests/'.$contest[id].'/'.$hash.'s.jpg');
					$contest_tpl->replaceNodeVar('{entrytitle}', $entry[title]);
				}
				$contest_tpl->switchCondition('user_voted', ($uservotes[$entry[id]]?true:false), true);

				$contest_tpl->replaceNodeVar('{entrylink}', 'index.php?section=contestentry&id='.$entry[id]);
				$contest_tpl->replaceNodeVar('{entryuser}', $entry[username]);
				$contest_tpl->replaceNodeVar('{entryid}', $entry[id]);
				$contest_tpl->replaceNodeVar('{entrypoints}', $uservotes[$entry[id]]);

				$entry[text] = cleanText($entry[text], false);
				$entry[text] = preg_replace("/\[§(.*?)§\]/is", "", $entry[text]);
				preg_match("/(.{1,".$FSXL[config][contest_preview]."})(\s|$)/is", $entry[text], $match);
				$entry[text] = $match[1].' ...';

				$contest_tpl->replaceNodeVar('{entrydescription}', $entry[text]);
			}
			$contest_tpl->replaceItem('entry');

			// Template ausgeben
			$FSXL[template] .= $script_tpl->code;
			$FSXL[template] .= $contest_tpl->code;
			unset($script_tpl);
			unset($contest_tpl);
		}
		
		// Einsendungen noch unsichtbar
		else
		{
			$frame = new template('contestentrieshidden');
			$frame->replaceTplVar('{contesttitle}', $contest[title]);
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