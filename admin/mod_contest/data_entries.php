<?php

$FSXL[title] = $FS_PHRASES[contest_entries_title];
$FSXL[content] = '';

// Bearbeiten
if($_POST[action] == 'edit')
{
	foreach($_POST[entry] AS $id)
	{
		settype($id, 'integer');
		$temptitle = $_POST[title][$id];
		$temptext = $_POST[text][$id];
		$tempblock = $_POST[block][$id] ? 0 : 1;
		
		mysql_query("UPDATE `$FSXL[tableset]_contest_entries` SET `title` = '$temptitle', `text` = '$temptext', `active` = $tempblock WHERE `id` = $id");
	}
	
	$FSXL[content] = '
			<div style="padding:20px; text-align:center;">'.$FS_PHRASES[contest_entries_edited].'</div>
	';	
}

// Formular
elseif ($_GET[id])
{
	settype($_GET[id], 'integer');
	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_contests` WHERE `id` = $_GET[id]");
	$contest = mysql_fetch_assoc($index);

	$FSXL[content] .= '
				<form action="?mod=contest&go=entries" method="post">
				<input type="hidden" name="action" value="edit">
				<table border="0" cellpadding="2" cellspacing="1" width="95%" style="margin:0px auto;">
	';
	
	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_contest_entries` WHERE `contest` = $contest[id] ORDER BY `date` DESC");
	while ($entry = mysql_fetch_assoc($index))
	{
		$index2 = mysql_query("SELECT `name` FROM `$FSXL[tableset]_user` WHERE `id` = $entry[user]");
		$userdat = mysql_fetch_assoc($index2);
		
		if ($contest[type] == 1)
		{
			$hash = md5($entry[date].$entry[id]);
			$FSXL[content] .= '
					<tr>
						<td width="170" rowspan="4" valign="top">
							<input type="hidden" name="entry['.$entry[id].']" value="'.$entry[id].'">
							<a href="../images/contests/'.$contest[id].'/'.$hash.'.jpg" target="_blank">
								<img border="0" src="../images/contests/'.$contest[id].'/'.$hash.'s.jpg" alt="">
							</a>
						</td>
						<td><b>'.$FS_PHRASES[contest_entries_user].':</b></td>
						<td>
							<span style="float:right;">'.date($FSXL[config][dateformat], $entry[date]).'</span>
							'.$userdat[name].' ('.$entry[ip].')
						</td>
					</tr>
					<tr>
						<td width="80"><b>'.$FS_PHRASES[contest_add_ctitle].':</b></td>
						<td><input class="textinput" name="title['.$entry[id].']" style="width:300px;" value="'.$entry[title].'"></td>
					</tr>
					<tr>
						<td valign="top"><b>'.$FS_PHRASES[contest_add_description].':</b></td>
						<td><textarea class="textinput" name="text['.$entry[id].']" style="width:300px; height:50px;">'.$entry[text].'</textarea></td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[contest_entries_block].':</b></td>
						<td><input type="checkbox" name="block['.$entry[id].']" '.($entry[active]==1?'':'checked').'></td>
					</tr>
					<tr><td colspan="3"><hr></td></tr>
			';
		}
		else
		{
			$FSXL[content] .= '
					<tr>
						<td width="150"><b>'.$FS_PHRASES[contest_entries_user].':</b></td>
						<td>
							<span style="float:right;">'.date($FSXL[config][dateformat], $entry[date]).'</span>
							'.$userdat[name].' ('.$entry[ip].')
						</td>
					</tr>
					<tr>
						<td valign="top"><b>'.$FS_PHRASES[contest_add_description].':</b></td>
						<td><textarea class="textinput" name="text['.$entry[id].']" style="width:400px; height:150px;">'.$entry[text].'</textarea></td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[contest_entries_block].':</b></td>
						<td><input type="checkbox" name="block['.$entry[id].']" '.($entry[active]==1?'':'checked').'></td>
					</tr>
					<tr><td colspan="3"><input type="hidden" name="entry['.$entry[id].']" value="'.$entry[id].'"><hr></td></tr>
			';
		}
	}

	$FSXL[content] .= '
					<tr>
						<td colspan="3" align="right"><input type="submit" class="button" value="'.$FS_PHRASES[global_send].'"></td>
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
									<td class="alt0" align="center"><b>'.$FS_PHRASES[contest_entries_entries].'</b></td>
									<td class="alt0" align="center"><b>'.$FS_PHRASES[contest_edit_state].'</b></td>
								</tr>
	';

	$index = mysql_query("SELECT `id`, `title`, `startdate`, `enddate`, `done` FROM `$FSXL[tableset]_contests` ORDER BY `startdate` DESC");
	$i=0;
	while ($contest = mysql_fetch_assoc($index))
	{
		$i++;
		
		if ($contest[startdate] < time() && time() < $contest[enddate])
			$title = '<b>'.$contest[title].'</b>';
		else
			$title = $contest[title];
			
		// Status
		if ($contest[done] == 1)
			$state = $FS_PHRASES[contest_edit_closed];
		elseif ($contest[startdate] > time())
			$state = $FS_PHRASES[contest_edit_open];
		else
			$state = $FS_PHRASES[contest_edit_running];
			
		// Einsendungen
		$index2 = mysql_query("SELECT COUNT(`id`) AS `num` FROM `$FSXL[tableset]_contest_entries` WHERE `contest` = $contest[id]");
		$entries = mysql_fetch_assoc($index2);
		
		$FSXL[content] .= '
					<tr>
						<td class="alt'.($i%2==0?1:2).'"><a href="?mod=contest&go=entries&id='.$contest[id].'">'.$title.'</a></td>
						<td align="center" class="alt'.($i%2==0?1:2).'">'.date($FSXL[config][dateformat], $contest[startdate]).'</td>
						<td align="center" class="alt'.($i%2==0?1:2).'">'.$entries[num].'</td>
						<td align="center" class="alt'.($i%2==0?1:2).'">'.$state.'</td>
					</tr>
		';
	}
}

?>