<?php

// Template lesen
$poll_tpl = new template('pollbox');

// Umfragen ermitteln
$index = mysql_query("SELECT * FROM `$FSXL[tableset]_poll` p, `$FSXL[tableset]_polltozone` c
						WHERE c.zoneid = '".$FSXL[zone][id]."' AND p.id = c.pollid AND p.startdate <= '$FSXL[time]' AND p.enddate >= '$FSXL[time]' 
						ORDER BY p.startdate DESC");
if (mysql_num_rows($index) > 0)
{
	while ($poll = mysql_fetch_assoc($index))
	{
		$poll_tpl->newListItem();

		// Nur für Benutzer
		$poll_tpl->switchListCondition('useronly', ($poll[useronly] == 1 && !$_SESSION[loggedin] ? true : false));


		// Teilgenommen?
		if ($poll[useronly] == 1 && $_SESSION[loggedin])
		{
			$index2 = mysql_query("SELECT * FROM `$FSXL[tableset]_poll_userlist` WHERE `poll` = '$poll[id]' AND `user` = ".$_SESSION[user]->userid);
		}
		else
		{
			$index2 = mysql_query("SELECT * FROM `$FSXL[tableset]_poll_iplist` WHERE `poll` = '$poll[id]' AND `ip` = '$_SERVER[REMOTE_ADDR]'");
		}

		if (mysql_num_rows($index2) > 0 || $_COOKIE['fsxl_poll_'.$poll[id]])
		{
			$has_submit = true;
			$index2 = mysql_query("SELECT SUM(hits) AS hits FROM `$FSXL[tableset]_poll_answers` WHERE `poll` = '$poll[id]' GROUP BY `poll`");
			$total = mysql_fetch_assoc($index2);
		}
		else
		{
			$has_submit = false;
		}
		$poll_tpl->switchListCondition('has_submit', $has_submit);

		// Variablen ersetzen
		$poll_tpl->replaceListVar('{question}', $poll[question]);
		$poll_tpl->replaceListVar('{pollid}', $poll[id]);
		$poll_tpl->replaceListVar('{polltype}', ($poll[multiselect] == 1 ? "checkbox" : "radio"));

		if ($total[hits] == 0) $total[hits] = 1;

		// Antworten
		$poll_tpl->getItem('answer', true);
		$index2 = mysql_query("SELECT * FROM `$FSXL[tableset]_poll_answers` WHERE `poll` = '$poll[id]' ORDER BY `position`");
		if (mysql_num_rows($index2) > 0)
		{
			while ($answer = mysql_fetch_assoc($index2))
			{
				$poll_tpl->newItemNode('answer');
				$poll_tpl->replaceNodeVar('{answer}', $answer[answer]);
				$poll_tpl->replaceNodeVar('{hits}', $answer[hits]);
				$poll_tpl->replaceNodeVar('{percent}', round($answer[hits]*100/$total[hits]));

				if ($poll[multiselect] == 1)
				{
					$poll_tpl->replaceNodeVar('{answerid}', $answer[id]);
				}
				else
				{
					$poll_tpl->replaceNodeVar('[{answerid}]', '');
					$poll_tpl->replaceNodeVar('{answerid}', $answer[id]);
				}
			}
		}
		$poll_tpl->replaceListItem('answer');
	}

	// Template ausgeben
	$poll_tpl->collapseList();
	$polltpl = $poll_tpl->code;
	unset($poll_tpl);
}

// keine passende Umfrage gefunden
else
{
	$polltpl = '';
}


?>