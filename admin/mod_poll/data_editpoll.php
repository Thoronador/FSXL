<?php

$FSXL[title] = $FS_PHRASES[poll_edit_title];

// Umfrage bearbeiten
if ($_POST[question] && $_POST[sday] != '' && $_POST[smonth] != '' && $_POST[syear] != '' && $_POST[shour] != '' && $_POST[smin] != '' && $_POST[eday] != '' && $_POST[emonth] != '' && $_POST[eyear] != '' && $_POST[ehour] != '' && $_POST[emin] != '')
{
	settype($_POST[editid], 'integer');

	// Poll löschen
	if ($_POST[del])
	{
		mysql_query("DELETE FROM `$FSXL[tableset]_poll` WHERE `id` = $_POST[editid]");
		mysql_query("DELETE FROM `$FSXL[tableset]_poll_answers` WHERE `poll` = $_POST[editid]");

		$FSXL[content] = '
				<div style="padding:20px; text-align:center;">'.$FS_PHRASES[poll_edit_deleted].'</div>
		';
	}

	// Poll editieren
	else
	{
		$startdate = mktime($_POST[shour], $_POST[smin], 0, $_POST[smonth], $_POST[sday], $_POST[syear]);
		$enddate = mktime($_POST[ehour], $_POST[emin], 0, $_POST[emonth], $_POST[eday], $_POST[eyear]);

		$_POST[multiselect] = $_POST[multiselect] ? 1 : 0;

		$index = mysql_query("UPDATE `$FSXL[tableset]_poll` SET
					`question` = '$_POST[question]',
					`startdate` = $startdate,
					`enddate` = $enddate,
					`multiselect` = $_POST[multiselect]
					WHERE `id` = '$_POST[editid]'");

		// Zonen
		mysql_query("DELETE FROM `$FSXL[tableset]_polltozone` WHERE `pollid` = '$_POST[editid]'");
		foreach ($_POST[zone] AS $zoneid => $value) {
			settype($zoneid, 'integer');
			mysql_query("INSERT INTO `$FSXL[tableset]_polltozone` (`pollid`, `zoneid`) VALUES ('$_POST[editid]', '$zoneid')");
		}

		if ($_POST[answer])
		{
			foreach($_POST[answer] as $key => $value)
			{
				if ($_POST[answerid][$key])
				{
					settype($_POST[answerid][$key], 'integer');
					// Antwort löschen
					if ($_POST[delete][$key])
					{
						mysql_query("DELETE FROM `$FSXL[tableset]_poll_answers` WHERE `id` = " . $_POST[answerid][$key]);
					}
					// Antwort bearbeiten
					elseif($_POST[answer][$key])
					{
						settype($_POST[hits][$key], 'integer');
						settype($_POST[position][$key], 'integer');
	
						mysql_query("UPDATE `$FSXL[tableset]_poll_answers` SET
							`answer` = '".$_POST[answer][$key]."',
							`position`= ".$_POST[position][$key].",
							`hits`= ".$_POST[hits][$key]."
							WHERE `id` = " . $_POST[answerid][$key]);
					}
				}
				// Antwort einfügen
				elseif ($_POST[answer][$key])
				{
					settype($_POST[position][$key], 'integer');
	
					mysql_query("INSERT INTO `$FSXL[tableset]_poll_answers` (`id`, `poll`, `answer`, `position`, `hits`)
							VALUES (NULL, $_POST[editid], '".$_POST[answer][$key]."', ".$_POST[position][$key].", 0)");
				}
			}
		}

		$FSXL[content] = '
				<div style="padding:20px; text-align:center;">'.$FS_PHRASES[poll_edit_editdone].'</div>
		';
	}
}

// Übersicht
elseif ($_GET[id])
{
	settype($_GET[id], 'integer');
	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_poll` WHERE `id` = $_GET[id]");
	$poll = mysql_fetch_assoc($index);

	$FSXL[content] .= '
				<div style="margin-bottom:20px;">
				<form action="?mod=poll&go=editpoll" method="post" name="pollform" onSubmit="return chkPollEditForm()" autocomplete="off">
				<input type="hidden" name="editid" value="'.$poll[id].'">
				<table border="0" cellpadding="2" cellspacing="1" width="90%" style="margin:0px auto;">
					<tr>
						<td colspan="4" style="padding:0px;"><span style="font-size:12pt;"><b>'.$FS_PHRASES[poll_add_question].'</b></span><hr></td>
					</tr>
					<tr>
						<td valign="top"><b>'.$FS_PHRASES[poll_add_question].':</b></td>
						<td><textarea class="textinput" name="question" style="width:400px; height:50px;">'.$poll[question].'</textarea></td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[poll_add_startdate].':</b><br><span class="small">'.$FS_PHRASES[poll_add_dateformat].'</span></td>
						<td valign="top">
							<input class="textinput" name="sday" style="width:20px;" value="'.date("d", $poll[startdate]).'">
							<input class="textinput" name="smonth" style="width:20px;" value="'.date("m", $poll[startdate]).'">
							<input class="textinput" name="syear" style="width:40px;" value="'.date("Y", $poll[startdate]).'"> -
							<input class="textinput" name="shour" style="width:20px;" value="'.date("H", $poll[startdate]).'">
							<input class="textinput" name="smin" style="width:20px;" value="'.date("i", $poll[startdate]).'">
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[poll_add_enddate].':</b><br><span class="small">'.$FS_PHRASES[poll_add_dateformat].'</span></td>
						<td valign="top">
							<input class="textinput" name="eday" style="width:20px;" value="'.date("d", $poll[enddate]).'">
							<input class="textinput" name="emonth" style="width:20px;" value="'.date("m", $poll[enddate]).'">
							<input class="textinput" name="eyear" style="width:40px;" value="'.date("Y", $poll[enddate]).'"> -
							<input class="textinput" name="ehour" style="width:20px;" value="'.date("H", $poll[enddate]).'">
							<input class="textinput" name="emin" style="width:20px;" value="'.date("i", $poll[enddate]).'">
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[poll_add_multiselect].':</b></td>
						<td>
							<input type="checkbox" name="multiselect" '.($poll[multiselect] == 1 ? "checked" : "").'>
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[poll_add_useronly].':</b></td>
						<td>'.($poll[useronly] == 1 ? $FS_PHRASES[poll_edit_yes] : $FS_PHRASES[poll_edit_no]).'</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[poll_edit_delete].':</b></td>
						<td>
							<input type="checkbox" name="del" id="delete" onClick="delMessage(\''.$FS_PHRASES[poll_edit_delmessage].'\');">
						</td>
					</tr>
					<tr>
						<td valign="top"><b>'.$FS_PHRASES[poll_add_inzones].':</b></td>
						<td>
	';

	// Zonen auflisten
	$index = @mysql_query("SELECT * FROM `$FSXL[tableset]_zones` ORDER BY `name`");
	while ($zone = mysql_fetch_assoc($index))
	{
		$index2 = mysql_query("SELECT * FROM `$FSXL[tableset]_polltozone` WHERE `pollid` = '$_GET[id]' AND `zoneid` = $zone[id]");
		if (mysql_num_rows($index2) == 0) {
			$select = "";
		} else {
			$select = "checked";
		}

		$FSXL[content] .= '
						<input type="checkbox" name="zone['.$zone[id].']" '.$select.'> '.$zone[name].'<br>
		';
	}

	$FSXL[content] .= '
						</td>
					</tr>
					<tr>
						<td colspan="2" align="right">
							<input type="submit" class="button" value="'.$FS_PHRASES[global_send].'">
						</td>
					</tr>
				</table>
				<script type="text/javascript">var currentAnswerIndex = 0;</script>
				<table border="0" cellpadding="2" cellspacing="1" width="90%" style="margin:0px auto;">
					<tr>
						<td colspan="4" style="padding:0px;"><span style="font-size:12pt;"><b>'.$FS_PHRASES[poll_add_answers].'</b></span><hr></td>
					</tr>
	';

	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_poll_answers` WHERE `poll` = $_GET[id] ORDER BY `position`");
	$FSXL[content] .= '<tr><td><script type="text/javascript">var currentAnswerIndex = '.mysql_num_rows($index).';</script></td></tr>';
	while ($answer = mysql_fetch_assoc($index))
	{
		$FSXL[content] .= '
					<tr>
						<td class="alt'.($i%2 == 0 ? 1 : 2).'" style="padding:5px;">
							<input type="hidden" name="answerid['.$i.']" value="'.$answer[id].'">
							<b>'.$FS_PHRASES[poll_add_answer].':</b>
							<input class="textinput" name="answer['.$i.']" value="'.$answer[answer].'" id="answer['.$i.']" style="width:300px; margin-bottom:-2px;">
							<b>'.$FS_PHRASES[poll_add_position].':</b>
							<input class="textinput" name="position['.$i.']" id="position['.$i.']" value="'.$answer[position].'" style="width:20px; margin-bottom:-2px;">
							<b>'.$FS_PHRASES[poll_edit_hits].':</b>
							<input class="textinput" name="hits['.$i.']" id="hits['.$i.']" value="'.$answer[hits].'" style="width:40px; margin-bottom:-2px;">
							<br>
							<b>'.$FS_PHRASES[poll_edit_delete].':</b>
							<input type="checkbox" name="delete['.$i.']">
						</td>
					</tr>
		';
		$i++;
		$posi = $answer[position];
	}

	$FSXL[content] .= '
					<tr>
						<td class="alt'.($i%2 == 0 ? 1 : 2).'" style="padding:5px;">
							<b>'.$FS_PHRASES[poll_add_answer].':</b>
							<input class="textinput" name="answer['.$i.']" id="answer['.$i.']" style="width:300px; margin-bottom:-2px;" onkeyup="addPollAnswer(this);">
							<b>'.$FS_PHRASES[poll_add_position].':</b>
							<input class="textinput" name="position['.$i.']" id="position['.$i.']" value="'.($posi+1).'" style="width:20px; margin-bottom:-2px;" onkeyup="addPollAnswer(this);">
						</td>
					</tr>
					<tr>
						<td>
							<br>
							<input type="submit" class="button" value="'.$FS_PHRASES[global_send].'" style="float:right;">
						</td>
					</tr>
				</table>
				</form>
				</div><p>
	';
}

// Liste ausgeben
else
{
	$FSXL[content] .= '
				<div>
				<table border="0" cellpadding="2" cellspacing="1" width="90%" style="margin:0px auto;">
					<tr>
						<td colspan="5" style="padding:0px;"><span style="font-size:12pt;"><b>'.$FS_PHRASES[poll_edit_select].'</b></span><hr></td>
					</tr>
					<tr>
						<td class="alt0"><b>'.$FS_PHRASES[poll_add_question].'</b></td>
						<td class="alt0" align="center"><b>'.$FS_PHRASES[poll_add_startdate].'</b></td>
						<td class="alt0" align="center"><b>'.$FS_PHRASES[poll_add_enddate].'</b></td>
						<td class="alt0" align="center"><b>'.$FS_PHRASES[poll_edit_fscode].'</b></td>
						<td class="alt0" align="center"><b>'.$FS_PHRASES[poll_edit_link].'</b></td>
					</tr>
	';

	// Liste
	$index = mysql_query("SELECT `id`, `question`, `startdate`, `enddate` FROM `$FSXL[tableset]_poll` ORDER BY `startdate` DESC");
	while ($poll = mysql_fetch_assoc($index))
	{
		$i++;
		$FSXL[content] .= '
					<tr>
						<td class="alt'.($i%2==0?1:2).'"><a href="?mod=poll&go=editpoll&id='.$poll[id].'">'.$poll[question].'</a></td>
						<td class="alt'.($i%2==0?1:2).'" align="center" valign="top">'.date("d.m.Y | H:i", $poll[startdate]).'</td>
						<td class="alt'.($i%2==0?1:2).'" align="center" valign="top">'.date("d.m.Y | H:i", $poll[enddate]).'</td>
						<td class="alt'.($i%2==0?1:2).'" align="center" valign="top">[poll]'.$poll[id].'[/poll]</td>
						<td class="alt'.($i%2==0?1:2).'" align="center"><a href="../index.php?section=pollarchiv&id='.$poll[id].'" target="_blank"><img border="0" src="images/'.$FSXL[style].'_link.gif" alt=""></a></td>
					</tr>
		';
	}

	$FSXL[content] .= '
				</table>
				</div>
	';
}


?>