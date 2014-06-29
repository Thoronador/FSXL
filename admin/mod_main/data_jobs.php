<?php

$FSXL[title] = $FS_PHRASES[main_jobs_title];

// Job abschließen
if ($_GET[id] && $_GET[done] == 1)
{
	settype($_GET[id], 'integer');
	$index = mysql_query("SELECT `state`, `user` FROM `$FSXL[tableset]_jobs` WHERE `id` = $_GET[id]");
	if (mysql_num_rows($index) > 0)
	{
		$job = mysql_fetch_assoc($index);
		if ($job[user] == $_SESSION[user]->userid && $job[state] == 2)
		{
			$chk = mysql_query("UPDATE `$FSXL[tableset]_jobs` SET `state` = 3, `cdate` = $FSXL[time] WHERE `id` = $_GET[id]");
			if ($chk) {
				$FSXL[content] = '<div style="padding:20px; text-align:center;">'.$FS_PHRASES[main_jobs_nowclosed].'</div>';
			}
		}
	}
	// Job nicht gefunden
	else {
		$FSXL[content] = '<div style="padding:20px; text-align:center;">'.$FS_PHRASES[main_jobs_notfound].'</div>';
	}
}

// Job beantworten
elseif ($_POST[action] == 'answerjob' && $_POST[mail] && $_POST[subject] && $_POST[text])
{
	sendMail($_POST[mail], $_POST[subject], $_POST[text]);
	$FSXL[content] = '<div style="padding:20px; text-align:center;">'.$FS_PHRASES[main_jobs_answered].'</div>';
}

// Job bearbeiten
elseif ($_POST[action] == 'editjob')
{
	settype($_POST[jobid], 'integer');

	// Löschen
	if ($_POST[del])
	{
		$chk = mysql_query("DELETE FROM `$FSXL[tableset]_jobs` WHERE `id` = $_POST[jobid]");
		if ($chk) {
			$FSXL[content] = '<div style="padding:20px; text-align:center;">'.$FS_PHRASES[main_jobs_deleted].'</div>';
		}
	}
	// zurücksetzen
	elseif ($_POST[reset] && $_POST[name] && $_POST[text])
	{
		$chk = mysql_query("UPDATE `$FSXL[tableset]_jobs` SET `name` = '$_POST[name]', `desc` = '$_POST[text]', `user` = 0, `edate` = 0, `state` = 1 WHERE `id` = $_POST[jobid]");
		if ($chk) {
			$FSXL[content] = '<div style="padding:20px; text-align:center;">'.$FS_PHRASES[main_jobs_resetted].'</div>';
		}
	}
	// bearbeiten
	elseif ($_POST[name] && $_POST[text])
	{
		$chk = mysql_query("UPDATE `$FSXL[tableset]_jobs` SET `name` = '$_POST[name]', `desc` = '$_POST[text]' WHERE `id` = $_POST[jobid]");
		if ($chk) {
			$FSXL[content] = '<div style="padding:20px; text-align:center;">'.$FS_PHRASES[main_jobs_edited].'</div>';
		}
	}
}

// Job annehmen
elseif ($_GET[id] && $_GET[accept] == 1)
{
	settype($_GET[id], 'integer');
	$index = mysql_query("SELECT `state` FROM `$FSXL[tableset]_jobs` WHERE `id` = $_GET[id]");
	if (mysql_num_rows($index) > 0)
	{
		$job = mysql_fetch_assoc($index);
		// Job annehmen
		if ($job[state] == 1)
		{
			$userid = $_SESSION[user]->userid;
			$chk = mysql_query("UPDATE `$FSXL[tableset]_jobs` SET `user` = $userid, `edate` = $FSXL[time], `state` = 2 WHERE `id` = $_GET[id]");
			if ($chk) {
				$FSXL[content] = '<div style="padding:20px; text-align:center;">'.$FS_PHRASES[main_jobs_accepted].'</div>';
			}
			else {
				$FSXL[content] = '<div style="padding:20px; text-align:center;">'.$FS_PHRASES[main_jobs_acceptfailed].'</div>';
			}
		}
		// Schon in bearbeitung
		elseif ($job[state] == 2) {
			$FSXL[content] = '<div style="padding:20px; text-align:center;">'.$FS_PHRASES[main_jobs_isprocessing].'</div>';
		}
		// Schon abgeschlossen
		elseif ($job[state] == 3) {
			$FSXL[content] = '<div style="padding:20px; text-align:center;">'.$FS_PHRASES[main_jobs_isdone].'</div>';
		}
	}
	// Job nicht gefunden
	else {
		$FSXL[content] = '<div style="padding:20px; text-align:center;">'.$FS_PHRASES[main_jobs_notfound].'</div>';
	}
}

// Job betrachten
elseif ($_GET[view])
{
	settype($_GET[view], 'integer');
	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_jobs` WHERE `id` = $_GET[view]");

	if (mysql_num_rows($index) > 0)
	{
		$job = mysql_fetch_assoc($index);

		switch($job[state])
		{
			case 1:
				$state = $FS_PHRASES[main_jobs_open];
				break;
			case 2:
				$index2 = mysql_query("SELECT `name` FROM `$FSXL[tableset]_user` WHERE `id` = $job[user]");
				$userdat2 = mysql_fetch_assoc($index2);
				$state = $FS_PHRASES[main_jobs_processing].' '.$FS_PHRASES[main_jobs_from].' '.$userdat2[name];
				break;
			case 3:
				$state = $FS_PHRASES[main_jobs_done];
				break;
		}
		
		$FSXL[content] .= '
				<table border="0" cellpadding="2" cellspacing="1" width="90%" style="margin:0px auto;">
					<tr>
						<td colspan="2">
							<span style="font-size:12pt"><b>'.$FS_PHRASES[main_jobs_description].'</b></span><hr>
						</td>
					</tr>
					<tr>
						<td width="250"><b>'.$FS_PHRASES[main_style_name].':</b></td>
						<td>'.$job[name].'</td>
					</tr>
					<tr>
						<td valign="top"><b>'.$FS_PHRASES[main_jobs_description].':</b></td>
						<td><div style="width:350px;" class="textinput">'.str_replace("\n", "<br/>", $job[desc]).'</div></td>
					</tr>
					<tr>
						<td valign="top"><b>'.$FS_PHRASES[main_jobs_state].':</b></td>
						<td>'.$state.'</td>
					</tr>
				</table>
		';
		
		// Antwortformular
		if (preg_match('/([a-zA-Z0-9-_\.]*?)@([a-zA-Z0-9-_\.]*?)\.([a-zA-Z]{2,6})/', $job[desc], $treffer))
		{
			$FSXL[content] .= '
				<p/>
				<form action="?mod=main&go=jobs" method="post">
				<input type="hidden" name="action" value="answerjob">
				<input type="hidden" name="jobid" value="'.$job[id].'">
				<table border="0" cellpadding="2" cellspacing="1" width="90%" style="margin:0px auto;">
					<tr>
						<td colspan="2">
							<span style="font-size:12pt"><b>'.$FS_PHRASES[main_jobs_answerjob].'</b></span><hr>
							'.$FS_PHRASES[main_jobs_answer_notice].'
						</td>
					</tr>
					<tr>
						<td width="250"><b>'.$FS_PHRASES[main_profile_email].':</b></td>
						<td>
							<input class="textinput" name="mail" style="width:350px;" value="'.$treffer[0].'">
						</td>
					</tr>
					<tr>
						<td width="250"><b>'.$FS_PHRASES[main_jobs_subject].':</b></td>
						<td>
							<input class="textinput" name="subject" style="width:350px;">
						</td>
					</tr>
					<tr>
						<td valign="top"><b>'.$FS_PHRASES[main_jobs_answer].':</b></td>
						<td>
							<textarea name="text" class="textinput" style="width:350px; height:200px;"></textarea>
						</td>
					</tr>
					<tr>
						<td colspan="2" align="right">
							<input type="submit" class="button" value="'.$FS_PHRASES[global_send].'">
						</td>
					</tr>
				</table>
				</form>
			';
		}
	}
	// Job nicht gefunden
	else
	{
		$FSXL[content] = '<div style="padding:20px; text-align:center;">'.$FS_PHRASES[main_jobs_notfound].'</div>';
	}
}

// Job bearbeiten
elseif ($_GET[id])
{
	settype($_GET[id], 'integer');
	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_jobs` WHERE `id` = $_GET[id]");

	if (mysql_num_rows($index) > 0)
	{
		$job = mysql_fetch_assoc($index);

		switch($job[state])
		{
			case 1:
				$state = $FS_PHRASES[main_jobs_open];
				break;
			case 2:
				$index2 = mysql_query("SELECT `name` FROM `$FSXL[tableset]_user` WHERE `id` = $job[user]");
				$userdat2 = mysql_fetch_assoc($index2);
				$state = $FS_PHRASES[main_jobs_processing].' '.$FS_PHRASES[main_jobs_from].' '.$userdat2[name];
				break;
			case 3:
				$state = $FS_PHRASES[main_jobs_done];
				break;
		}
		
		$FSXL[content] .= '
				<form action="?mod=main&go=jobs" method="post">
				<input type="hidden" name="action" value="editjob">
				<input type="hidden" name="jobid" value="'.$job[id].'">
				<table border="0" cellpadding="2" cellspacing="1" width="90%" style="margin:0px auto;">
					<tr>
						<td colspan="2">
							<span style="font-size:12pt"><b>'.$FS_PHRASES[main_jobs_editjob].'</b></span><hr>
						</td>
					</tr>
					<tr>
						<td width="250"><b>'.$FS_PHRASES[main_style_name].':</b></td>
						<td>
							<input class="textinput" name="name" style="width:350px;" value="'.$job[name].'">
						</td>
					</tr>
					<tr>
						<td valign="top"><b>'.$FS_PHRASES[main_jobs_description].':</b></td>
						<td>
							<textarea name="text" class="textinput" style="width:350px; height:100px;">'.$job[desc].'</textarea>
						</td>
					</tr>
					<tr>
						<td valign="top"><b>'.$FS_PHRASES[main_jobs_state].':</b></td>
						<td>
							'.$state.'<br>
							'.$FS_PHRASES[main_jobs_reset].' <input type="checkbox" name="reset">
						</td>
					</tr>
					<tr>
						<td valign="top"><b>'.$FS_PHRASES[main_imgmanager_del].':</b></td>
						<td><input type="checkbox" name="del" id="delete" onClick="delMessage(\''.$FS_PHRASES[main_jobs_delmessage].'\');"></td>
					</tr>
					<tr>
						<td colspan="2" align="right">
							<input type="submit" class="button" value="'.$FS_PHRASES[global_send].'">
						</td>
					</tr>
				</table>
				</form>
		';
	}
	// Job nicht gefunden
	else
	{
		$FSXL[content] = '<div style="padding:20px; text-align:center;">'.$FS_PHRASES[main_jobs_notfound].'</div>';
	}
}

// Job hinzufügen
elseif ($_POST[action] == 'addjob' && $_POST[name] && $_POST[text])
{
	$autor = $_SESSION[user]->userid;
	$chk = mysql_query("INSERT INTO `$FSXL[tableset]_jobs` (`id`, `name`, `desc`, `date`, `autor`, `edate`, `user`, `state`, `cdate`)
						VALUES (NULL, '$_POST[name]', '$_POST[text]', $FSXL[time], $autor, 0, 0, 1, 0)");
	if ($chk)
	{
		// Mail versenden
		if ($FSXL[config][jobmail] == 1)
		{
			$id = mysql_insert_id();
			$mailbody = str_replace('%s', $FSXL[config][pagetitle], $FS_PHRASES[main_jobs_mail_body]);
			$mailbody = str_replace('%t', stripslashes(str_replace('\r\n', "\n", $_POST[text])), $mailbody);
			$mailbody .= "\n\n";
			$mailbody .= 'http://'.$_SERVER["SERVER_NAME"].substr($_SERVER["SCRIPT_NAME"], 0, strlen($_SERVER["SCRIPT_NAME"])-9);
			
			$mailsubject = str_replace('%s', $FSXL[config][pagetitle], $FS_PHRASES[main_jobs_mail_subject]);;
			
			sendPermissionMail('main/jobs', $mailsubject, $mailbody);
		}

		$FSXL[content] = '<div style="padding:20px; text-align:center;">'.$FS_PHRASES[main_jobs_added].'</div>';
	}
	else
	{
		$FSXL[content] = '<div style="padding:20px; text-align:center;">'.$FS_PHRASES[main_jobs_addfailed].'</div>';
	}
}

// Übersicht
else
{
	$FSXL[content] .= '
				<form action="?mod=main&go=jobs" method="post">
				<input type="hidden" name="action" value="addjob">
				<table border="0" cellpadding="2" cellspacing="1" width="90%" style="margin:0px auto;">
					<tr>
						<td colspan="2">
							<span style="font-size:12pt"><b>'.$FS_PHRASES[main_jobs_addjob].'</b></span><hr>
						</td>
					</tr>
					<tr>
						<td width="250"><b>'.$FS_PHRASES[main_style_name].':</b></td>
						<td>
							<input class="textinput" name="name" style="width:350px;">
						</td>
					</tr>
					<tr>
						<td valign="top"><b>'.$FS_PHRASES[main_jobs_description].':</b></td>
						<td>
							<textarea name="text" class="textinput" style="width:350px; height:100px;"></textarea>
						</td>
					</tr>
					<tr>
						<td colspan="2" align="right">
							<input type="submit" class="button" value="'.$FS_PHRASES[global_send].'">
						</td>
					</tr>
				</table>
				</form>

				<table border="0" cellpadding="2" cellspacing="1" width="90%" style="margin:0px auto;">
					<tr>
						<td colspan="2">
							<span style="font-size:12pt"><b>'.$FS_PHRASES[main_jobs_selectjob].'</b></span><hr>
						</td>
					</tr>
					<tr>
						<td>
							<table border="0" cellpadding="2" cellspacing="1" width="100%">
								<tr>
									<td class="alt0"><b>'.$FS_PHRASES[main_style_name].'</b></td>
									<td class="alt0" align="center"><b>'.$FS_PHRASES[main_jobs_created].'</b></td>
									<td class="alt0" align="center"><b>'.$FS_PHRASES[main_jobs_state].'</b></td>
									<td class="alt0" align="center"><b>'.$FS_PHRASES[main_jobs_action].'</b></td>
								</tr>
	';
	
	if (!$_GET[start]) $_GET[start] = 0;
	settype($_GET[start], 'integer');
	$index = mysql_query("SELECT COUNT(`id`) AS `jobs` FROM `$FSXL[tableset]_jobs`");
	$count = mysql_fetch_assoc($index);
	$index = mysql_query("SELECT `id`, `name`, `date`, `autor`, `state`, `user` FROM `$FSXL[tableset]_jobs` ORDER BY `state` ASC, `date` DESC LIMIT $_GET[start], 50");
	while ($job = mysql_fetch_assoc($index))
	{
		$i++;
		$index2 = mysql_query("SELECT `name` FROM `$FSXL[tableset]_user` WHERE `id` = $job[autor]");
		$userdat = mysql_fetch_assoc($index2);
		
		switch($job[state])
		{
			case 1:
				$state = $FS_PHRASES[main_jobs_open];
				$action = '<a href="?mod=main&go=jobs&id='.$job[id].'&accept=1">['.$FS_PHRASES[main_jobs_accept].']</a><br>';
				break;
			case 2:
				$index2 = mysql_query("SELECT `name` FROM `$FSXL[tableset]_user` WHERE `id` = $job[user]");
				$userdat2 = mysql_fetch_assoc($index2);
				$state = $FS_PHRASES[main_jobs_processing].'<br>'.$FS_PHRASES[main_jobs_from].' '.$userdat2[name];
				if ($job[user] == $_SESSION[user]->userid) {
					$action = '<a href="?mod=main&go=jobs&id='.$job[id].'&done=1">['.$FS_PHRASES[main_jobs_close].']</a><br>';
				}
				else {
					$action = '';
				}
				break;
			case 3:
				$state = $FS_PHRASES[main_jobs_done];
				break;
		}

		$job[name] = preg_replace("/(.*?)\((.*?)\)/i", "$1<br/><span style=\"font-size:7pt;\">($2)</span>", $job[name]);
		$FSXL[content] .= '
					<tr>
						<td class="alt'.($i%2==0?1:2).'"><a href="?mod=main&go=jobs&view='.$job[id].'">'.$job[name].'</a></td>
						<td class="alt'.($i%2==0?1:2).'" align="center" nowrap>
							'.date("d.m.Y | H:i", $job[date]).'<br>
							'.$FS_PHRASES[main_jobs_from].' '.$userdat[name].'
						</td>
						<td class="alt'.($i%2==0?1:2).'" align="center">'.$state.'</td>
						<td class="alt'.($i%2==0?1:2).'" align="center">
							'.$action.'
							<a href="?mod=main&go=jobs&id='.$job[id].'">['.$FS_PHRASES[main_jobs_edit].']</a>
						</td>
					</tr>
		';
	}
	
	// Seitenanzeige
	$FSXL[content] .= '
					<tr>
						<td colspan="4" style="padding-top:20px;">
	';
	if ($_GET[start]+50 < $count[jobs])
	{
		$offset = $_GET[start] + 50;
		$FSXL[content] .= '<span style="float:right;"><a href="?mod=main&go=jobs&start='.$offset.'"><b>'.$FS_PHRASES[main_jobs_older].' ></b></a></span>';
	}
	if ($_GET[start] > 0)
	{
		if ($_GET[start] > 50) $offset = $_GET[start] - 50;
		else $offset = 0;
		$FSXL[content] .= '<a href="?mod=main&go=jobs&start='.$offset.'"><b>< '.$FS_PHRASES[main_jobs_newer].'</b></a>';
	}

	$FSXL[content] .= '
							</table>
						</td>
					</tr>
				</table>
	';
}
?>