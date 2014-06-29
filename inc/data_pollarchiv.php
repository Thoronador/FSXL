<?php

// Umfrage anzeigen
if ($_GET[id])
{
	settype($_GET[id], 'integer');
	$index = mysql_query("SELECT p.id AS id, p.startdate AS startdate, p.enddate AS enddate, p.question AS question, p.multiselect AS multiselect, p.useronly AS useronly, SUM(a.hits) AS hits
				FROM $FSXL[tableset]_poll p, $FSXL[tableset]_poll_answers a 
				WHERE p.startdate <= '$FSXL[time]' AND p.id = '$_GET[id]' AND a.poll = p.id
				GROUP BY a.poll");

	if (mysql_num_rows($index) > 0)
	{
		$poll = mysql_fetch_assoc($index);
		if ($poll[hits] == 0) $poll[hits] = 1;

		// Pagetitle
		$FSXL[pgtitle] = $poll[question];

		// Template lesen
		$poll_tpl = new template('polldetail');
		$poll_tpl->getItem('answer');

		// Variablen ersetzen
		$poll_tpl->replaceTplVar('{question}', $poll[question]);
		$poll_tpl->replaceTplVar('{pollid}', $poll[id]);
		$poll_tpl->replaceTplVar('{startdate}', date($FSXL[config][dateformat], $poll[startdate]));
		$poll_tpl->replaceTplVar('{enddate}', date($FSXL[config][dateformat], $poll[enddate]));
		$poll_tpl->switchCondition('multiselect', ($poll[multiselect] == 1 ? true : false));
		$poll_tpl->switchCondition('useronly', ($poll[useronly] == 1 ? true : false));

		if ($_GET[order] == 'hits') $order = '`hits` DESC';
		else $order = '`position`';

		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_poll_answers` WHERE `poll` = '$poll[id]' ORDER BY $order");
		if (mysql_num_rows($index) > 0)
		{
			while ($answer = mysql_fetch_assoc($index))
			{
				$i++;
				$poll_tpl->newItemNode('answer');
				$poll_tpl->replaceNodeVar('{altnum}', $i%2==0?1:2);
				$poll_tpl->replaceNodeVar('{answer}', $answer[answer]);
				$poll_tpl->replaceNodeVar('{hits}', $answer[hits]);
				$poll_tpl->replaceNodeVar('{percent}', round($answer[hits]*100/$poll[hits]));
			}
		}
		$poll_tpl->replaceItem('answer');

		// Template ausgeben
		$FSXL[template] .= $poll_tpl->code;
		unset($poll_tpl);
	}

	// Umfrage nicht gefunden oder nicht freigegeben
	else
	{
		$FSXL[template] .= errorMsg('errorfilenotfound');
	}
}

// Umfragen Übersicht
else
{
	$index = mysql_query("SELECT p.id AS id, p.startdate AS startdate, p.enddate AS enddate, p.question AS question, SUM(a.hits) AS hits
				FROM $FSXL[tableset]_poll p, $FSXL[tableset]_poll_answers a 
				WHERE p.startdate <= '$FSXL[time]' AND a.poll = p.id
				GROUP BY a.poll
				ORDER BY `startdate` DESC");

	// Template lesen
	$poll_tpl = new template('polllist');
	$poll_tpl->getItem('poll');

	// Template füllen
	if (mysql_num_rows($index) > 0)
	{
		while ($poll = mysql_fetch_assoc($index))
		{
			$i++;
			$poll_tpl->newItemNode('poll');
			$poll_tpl->replaceNodeVar('{altnum}', $i%2==0?1:2);
			$poll_tpl->replaceNodeVar('{question}', $poll[question]);
			$poll_tpl->replaceNodeVar('{startdate}', date($FSXL[config][dateformat], $poll[startdate]));
			$poll_tpl->replaceNodeVar('{enddate}', date($FSXL[config][dateformat], $poll[enddate]));
			$poll_tpl->replaceNodeVar('{url}', '?section=pollarchiv&id='.$poll[id]);
			$poll_tpl->replaceNodeVar('{hits}', $poll[hits]);
		}
	}
	$poll_tpl->replaceItem('poll');

	// Template ausgeben
	$FSXL[template] .= $poll_tpl->code;
	unset($poll_tpl);
}


?>