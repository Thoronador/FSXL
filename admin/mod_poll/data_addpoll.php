<?php

$FSXL[title] = $FS_PHRASES[poll_add_title];

// Poll eintragen
if ($_POST[question])
{
	// Datum auswerten
	if ($_POST[sday] != '' && $_POST[smonth] != '' && $_POST[syear] != '' && $_POST[shour] != '' && $_POST[smin] != '')
		$startdate = mktime($_POST[shour], $_POST[smin], 0, $_POST[smonth], $_POST[sday], $_POST[syear]);
	else
		$startdate = time();
	if ($_POST[eday] != '' && $_POST[emonth] != '' && $_POST[eyear] != '' && $_POST[ehour] != '' && $_POST[emin] != '')
		$enddate = mktime($_POST[ehour], $_POST[emin], 0, $_POST[emonth], $_POST[eday], $_POST[eyear]);
	else
		$enddate = time()+2592000;

	$_POST[multiselect] = $_POST[multiselect] ? 1 : 0;
	$_POST[useronly] = $_POST[useronly] ? 1 : 0;

	$index = mysql_query("INSERT INTO `$FSXL[tableset]_poll` (`id`, `question`, `startdate`, `enddate`, `multiselect`, `useronly`)
				VALUES (NULL, '$_POST[question]', $startdate, $enddate, $_POST[multiselect], $_POST[useronly])");
	$id = mysql_insert_id();
	
	// Zonen
	foreach ($_POST[zone] AS $zoneid => $value) {
		settype($zoneid, 'integer');
		mysql_query("INSERT INTO `$FSXL[tableset]_polltozone` (`pollid`, `zoneid`) VALUES ('$id', '$zoneid')");
	}

	// Antworten
	foreach ($_POST[answer] as $key => $value)
	{
		if ($_POST[answer][$key])
		{
			settype($_POST[position][$key], 'integer');

			mysql_query("INSERT INTO `$FSXL[tableset]_poll_answers` (`id`, `poll`, `answer`, `position`, `hits`)
					VALUES (NULL, $id, '".$_POST[answer][$key]."', ".$_POST[position][$key].", 0)");
		}
	}

	$FSXL[content] = '
				<div style="padding:20px; text-align:center;">'.$FS_PHRASES[poll_add_added].'</div>
	';
}

// Formular
else
{
	$FSXL[content] .= '
				<div>
				<form action="?mod=poll&go=addpoll" method="post" name="pollform" onSubmit="return chkPollAddForm()" autocomplete="off">
				<table border="0" cellpadding="2" cellspacing="1" width="90%" style="margin:0px auto;">
					<tr>
						<td colspan="2" style="padding:0px;"><span style="font-size:12pt;"><b>'.$FS_PHRASES[poll_add_question].'</b></span><hr></td>
					</tr>
					<tr>
						<td valign="top"><b>'.$FS_PHRASES[poll_add_question].':</b></td>
						<td><textarea class="textinput" name="question" style="width:400px; height:50px;"></textarea></td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[poll_add_startdate].':</b><br><span class="small">'.$FS_PHRASES[poll_add_dateformat].'</span></td>
						<td valign="top">
							<input class="textinput" name="sday" style="width:20px;" value="'.date("d").'">
							<input class="textinput" name="smonth" style="width:20px;" value="'.date("m").'">
							<input class="textinput" name="syear" style="width:40px;" value="'.date("Y").'"> -
							<input class="textinput" name="shour" style="width:20px;" value="'.date("H").'">
							<input class="textinput" name="smin" style="width:20px;" value="'.date("i").'">
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[poll_add_enddate].':</b><br><span class="small">'.$FS_PHRASES[poll_add_dateformat].'</span></td>
						<td valign="top">
							<input class="textinput" name="eday" style="width:20px;" value="'.date("d", time()+2592000).'">
							<input class="textinput" name="emonth" style="width:20px;" value="'.date("m", time()+2592000).'">
							<input class="textinput" name="eyear" style="width:40px;" value="'.date("Y", time()+2592000).'"> -
							<input class="textinput" name="ehour" style="width:20px;" value="'.date("H", time()+2592000).'">
							<input class="textinput" name="emin" style="width:20px;" value="'.date("i", time()+2592000).'">
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[poll_add_multiselect].':</b></td>
						<td>
							<input type="checkbox" name="multiselect">
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[poll_add_useronly].':</b></td>
						<td><input type="checkbox" name="useronly"></td>
					</tr>
					<tr>
						<td valign="top"><b>'.$FS_PHRASES[poll_add_inzones].':</b></td>
						<td>
	';

	// Zonen auflisten
	$index = @mysql_query("SELECT * FROM `$FSXL[tableset]_zones` ORDER BY `name`");
	while ($zone = mysql_fetch_assoc($index))
	{
		$FSXL[content] .= '
							<input type="checkbox" name="zone['.$zone[id].']"> '.$zone[name].'<br>
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
						<td colspan="2" style="padding:0px;"><span style="font-size:12pt;"><b>'.$FS_PHRASES[poll_add_answers].'</b></span><hr></td>
					</tr>
					<tr>
						<td class="alt1" style="padding:5px;">
							<b>'.$FS_PHRASES[poll_add_answer].':</b>
							<input class="textinput" name="answer[0]" id="answer[0]" style="width:300px; margin-bottom:-2px;" onkeyup="addPollAnswer(this);">
							<b>'.$FS_PHRASES[poll_add_position].':</b>
							<input class="textinput" name="position[0]" id="position[0]" value="1" style="width:20px; margin-bottom:-2px;" onkeyup="addPollAnswer(this);">
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

?>