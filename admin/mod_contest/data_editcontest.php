<?php

$FSXL[title] = $FS_PHRASES[contest_edit_title];
$FSXL[content] = '';

// Bearbeiten
if($_POST[action] == 'edit' && $_POST[title] && $_POST[fp_code] && $_POST[winners])
{
	settype($_POST[editid], 'integer');
	$_SESSION[unset_tmptext] = true;
	
	// Löschen
	if ($_POST[del])
	{
		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_contests` WHERE `id` = $_POST[editid]");
		$contest = mysql_fetch_assoc($index);
		mysql_query("DELETE FROM `$FSXL[tableset]_contests` WHERE `id` = $_POST[editid]");
		
		if ($contest[type] == 1)
		{
			$index = mysql_query("SELECT `id`, `date` FROM `$FSXL[tableset]_contest_entries` WHERE `contest` = $_POST[editid]");
			while ($entry = mysql_fetch_assoc($index))
			{
				$hash = md5($entry[date].$entry[id]);
				unlink('../images/contests/'.$_POST[editid].'/'.$hash.'.jpg');
				unlink('../images/contests/'.$_POST[editid].'/'.$hash.'s.jpg');
			}
		}
		mysql_query("DELETE FROM `$FSXL[tableset]_contest_entries` WHERE `contest` = $_POST[editid]");
		mysql_query("DELETE FROM `$FSXL[tableset]_contest_votes` WHERE `contest` = $_POST[editid]");
		mysql_query("DELETE FROM `$FSXL[tableset]_contest_winner` WHERE `contest` = $_POST[editid]");

		if ($contest[type] == 1) rmdir('../images/contests/'.$_POST[editid]);
		$FSXL[content] = '
				<div style="padding:20px; text-align:center;">'.$FS_PHRASES[contest_edit_deleted].'</div>
		';
	}
	// Bearbeiten
	else
	{
		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_contests` WHERE `id` = $_POST[editid]");
		$contest = mysql_fetch_assoc($index);

		// Datum auswerten
		if ($_POST[sday] != '' && $_POST[smonth] != '' && $_POST[syear] != '' && $_POST[shour] != '' && $_POST[smin] != '')
			$startdate = mktime($_POST[shour], $_POST[smin], 0, $_POST[smonth], $_POST[sday], $_POST[syear]);
		else
			$startdate = $contest[startdate];
		if ($_POST[eday] != '' && $_POST[emonth] != '' && $_POST[eyear] != '' && $_POST[ehour] != '' && $_POST[emin] != '')
			$enddate = mktime($_POST[ehour], $_POST[emin], 0, $_POST[emonth], $_POST[eday], $_POST[eyear]);
		else
			$enddate = $contest[enddate];
		if ($_POST[xday] != '' && $_POST[xmonth] != '' && $_POST[xyear] != '' && $_POST[xhour] != '' && $_POST[xmin] != '')
			$xdate = mktime($_POST[xhour], $_POST[xmin], 0, $_POST[xmonth], $_POST[xday], $_POST[xyear]);
		else
			$xdate = $contest[votedate];

		settype($_POST[winners], 'integer');
		settype($_POST[analysis], 'integer');
		settype($_POST[secret], 'integer');
		$multiple = $_POST[multiple] ? 1 : 0;
		
		$chk = mysql_query("UPDATE `$FSXL[tableset]_contests` SET `title` = '$_POST[title]', `startdate` = $startdate,
							`enddate` = $enddate, `text` = '$_POST[fp_code]', `secret` = $_POST[secret], `multiple` = $multiple,
							`analysis` = $_POST[analysis], `votedate` = $xdate, `winner` = $_POST[winners] WHERE `id` = $_POST[editid]");

		$FSXL[content] = '
				<div style="padding:20px; text-align:center;">'.$FS_PHRASES[contest_edit_edited].'</div>
		';
	}
}

// Formular
elseif ($_GET[id])
{
	settype($_GET[id], 'integer');
	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_contests` WHERE `id` = $_GET[id]");
	$contest = mysql_fetch_assoc($index);
	
	$contest[title] = str_replace('"', '&quot;', $contest[title]);

	$FSXL[content] = '
				<form action="?mod=contest&go=editcontest" method="post" name="contestform" onSubmit="return chkContestAddForm('.$_SESSION[user]->editor.')">
				<input type="hidden" name="action" value="edit">
				<input type="hidden" name="editid" value="'.$contest[id].'">
				<table border="0" cellpadding="2" cellspacing="1" width="95%" style="margin:0px auto;">
					<tr>
						<td width="200"><b>'.$FS_PHRASES[contest_add_ctitle].':</b></td>
						<td><input class="textinput" name="title" style="width:350px;" value="'.$contest[title].'"></td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[contest_add_startdate].':</b><br><span class="small">'.$FS_PHRASES[contest_add_dateformat].'</span></td>
						<td valign="top">
							<input class="textinput" name="sday" style="width:20px;" value="'.date("d", $contest[startdate]).'">
							<input class="textinput" name="smonth" style="width:20px;" value="'.date("m", $contest[startdate]).'">
							<input class="textinput" name="syear" style="width:40px;" value="'.date("Y", $contest[startdate]).'"> -
							<input class="textinput" name="shour" style="width:20px;" value="'.date("H", $contest[startdate]).'">
							<input class="textinput" name="smin" style="width:20px;" value="'.date("i", $contest[startdate]).'">
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[contest_add_enddate].':</b><br><span class="small">'.$FS_PHRASES[contest_add_dateformat].'</span></td>
						<td valign="top">
							<input class="textinput" name="eday" style="width:20px;" value="'.date("d", $contest[enddate]).'">
							<input class="textinput" name="emonth" style="width:20px;" value="'.date("m", $contest[enddate]).'">
							<input class="textinput" name="eyear" style="width:40px;" value="'.date("Y", $contest[enddate]).'"> -
							<input class="textinput" name="ehour" style="width:20px;" value="'.date("H", $contest[enddate]).'">
							<input class="textinput" name="emin" style="width:20px;" value="'.date("i", $contest[enddate]).'">
						</td>
					</tr>
					<tr>
						<td valign="top"><b>'.$FS_PHRASES[contest_add_description].':</b></td>
						<td>
	';

	// Editor einbinden
	$FSXL[content] .= setEditor($_SESSION[user]->editor, 1, $contest[text]);
	if ($_SESSION[user]->editor == 0) include('frogpad/fpinclude.php');

	$FSXL[content] .= '<textarea name="html_code" id="html_code" class="htmlinput" style="display:none;"></textarea>';

	$FSXL[content] .= '
						</td>
					</tr>
					<tr>
						<td valign="top"><b>'.$FS_PHRASES[contest_add_secret].':</b><br>'.$FS_PHRASES[contest_add_secret_sub].'</td>
						<td>
							<input type="radio" name="secret" value="1" '.($contest[secret]==1?'checked':'').'> '.$FS_PHRASES[contest_add_secret_sub1].'<br>
							<input type="radio" name="secret" value="2" '.($contest[secret]==2?'checked':'').'> '.$FS_PHRASES[contest_add_secret_sub2].'<br>
							<input type="radio" name="secret" value="3" '.($contest[secret]==3?'checked':'').'> '.$FS_PHRASES[contest_add_secret_sub3].'
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[contest_add_multiple].':</b><br>'.$FS_PHRASES[contest_add_multiple_sub].'</td>
						<td valign="top"><input type="checkbox" name="multiple" '.($contest[multiple]==1?'checked':'').'></td>
					</tr>
					<tr>
						<td valign="top"><b>'.$FS_PHRASES[contest_add_analysis].':</b><br>'.$FS_PHRASES[contest_add_analysis_sub].'</td>
						<td>
							<input type="radio" name="analysis" value="1" '.($contest[analysis]==1?'checked':'').'> '.$FS_PHRASES[contest_add_lottery].'<br>
							<input type="radio" name="analysis" value="2" '.($contest[analysis]==2?'checked':'').'> '.$FS_PHRASES[contest_add_jury].'<br>
							<input type="radio" name="analysis" value="3" '.($contest[analysis]==3?'checked':'').'> '.$FS_PHRASES[contest_add_uservote].'
							<input class="textinput" name="xday" style="width:20px;" value="'.date("d", $contest[votedate]).'">
							<input class="textinput" name="xmonth" style="width:20px;" value="'.date("m", $contest[votedate]).'">
							<input class="textinput" name="xyear" style="width:40px;" value="'.date("Y", $contest[votedate]).'"> -
							<input class="textinput" name="xhour" style="width:20px;" value="'.date("H", $contest[votedate]).'">
							<input class="textinput" name="xmin" style="width:20px;" value="'.date("i", $contest[votedate]).'">
						</td>
					</tr>
					<tr>
						<td width="200"><b>'.$FS_PHRASES[contest_add_winners].':</b></td>
						<td><input class="textinput" name="winners" style="width:50px;" value="'.$contest[winner].'"></td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[contest_edit_delete].':</b></td>
						<td>
							<input type="checkbox" name="del" id="delete" onClick="delMessage(\''.$FS_PHRASES[contest_edit_delmessage].'\');">
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

// Übersicht
else
{
	$FSXL[content] .= '
				<table border="0" cellpadding="2" cellspacing="1" width="90%" style="margin:0px auto;">
					<tr>
						<td>
							<span style="font-size:12pt"><b>'.$FS_PHRASES[contest_edit_select].'</b></span><hr>
						</td>
					</tr>
					<tr>
						<td>
							<table border="0" cellpadding="2" cellspacing="1" width="100%">
								<tr>
									<td class="alt0"><b>'.$FS_PHRASES[contest_add_ctitle].'</b></td>
									<td class="alt0" align="center"><b>'.$FS_PHRASES[contest_add_startdate].'</b></td>
									<td class="alt0" align="center"><b>'.$FS_PHRASES[contest_add_enddate].'</b></td>
									<td class="alt0" align="center"><b>'.$FS_PHRASES[contest_edit_state].'</b></td>
									<td class="alt0" align="center"><b>'.$FS_PHRASES[contest_edit_link].'</b></td>
								</tr>
	';

	$index = mysql_query("SELECT `id`, `title`, `startdate`, `enddate`, `votedate`, `type`, `done` FROM `$FSXL[tableset]_contests` ORDER BY `startdate` DESC");
	$i=0;
	while ($contest = mysql_fetch_assoc($index))
	{
		$i++;
		
		if ($contest[startdate] < time() && time() < $contest[enddate])
			$title = '<b>' . $contest[title] . '</b>';
		else
			$title = $contest[title];
			
		// Status
		if ($contest[done] == 1)
			$state = $FS_PHRASES[contest_edit_closed];
		elseif ($contest[startdate] > time())
			$state = $FS_PHRASES[contest_edit_open];
		else
			$state = $FS_PHRASES[contest_edit_running];
		
		$FSXL[content] .= '
					<tr>
						<td class="alt'.($i%2==0?1:2).'"><a href="?mod=contest&go=editcontest&id='.$contest[id].'">'.$title.'</a></td>
						<td align="center" class="alt'.($i%2==0?1:2).'">'.date("d.m.Y | H:i", $contest[startdate]).'</td>
						<td align="center" class="alt'.($i%2==0?1:2).'">'.date("d.m.Y | H:i", $contest[enddate]).'</td>
						<td align="center" class="alt'.($i%2==0?1:2).'">'.$state.'</td>
						<td align="center" class="alt'.($i%2==0?1:2).'"><a href="../index.php?section=contest&id='.$contest[id].'" target="_blank"><img border="0" src="images/'.$FSXL[style].'_link.gif" alt=""></a></td>
					</tr>
		';
	}
}

?>