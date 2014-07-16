<?php

$FSXL[title] = $FS_PHRASES[contest_analysis_title];
$FSXL[content] = '';

// Bearbeiten
if($_POST[action] == 'edit')
{
	settype($_POST[contestid], 'integer');
	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_contests` WHERE `id` = $_POST[contestid]");
	$contest = mysql_fetch_assoc($index);
	
	if ($contest[done] != 1)
	{
		// Jury
		if ($contest[analysis] == 2)
		{
			$places = array();
			foreach($_POST[place] AS $anker => $position)
			{
				$places[$position] = $_POST[entryid][$anker];
			}
			$_POST[entryid] = $places;
		}

		foreach($_POST[entryid] AS $position => $id)
		{
			mysql_query("INSERT INTO `$FSXL[tableset]_contest_winner` (`contest`, `position`, `entry`)
							VALUES ($_POST[contestid], $position, $id)");
		}
		
		mysql_query("UPDATE `$FSXL[tableset]_contests` SET `done` = 1 WHERE `id` = $_POST[contestid]");

		$FSXL[content] = '
			<div style="padding:20px; text-align:center;">
				'.$FS_PHRASES[contest_analysis_saved].'<br>
				<a href="?mod=contest&go=winner&id='.$_POST[contestid].'">'.$FS_PHRASES[contest_analysis_show].'</a>
			</div>
		';	
	}
	else
	{
		$FSXL[content] = '
			<div style="padding:20px; text-align:center;">'.$FS_PHRASES[contest_analysis_alreadydone].'</div>
		';	
	}
}

// Formular
elseif ($_GET[id])
{
	settype($_GET[id], 'integer');
	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_contests` WHERE `id` = $_GET[id]");
	$contest = mysql_fetch_assoc($index);
	
	if ($contest[done] != 1)
	{
		$FSXL[content] .= '
				<form action="?mod=contest&go=analysis" method="post">
				<input type="hidden" name="action" value="edit">
				<input type="hidden" name="contestid" value="'.$contest[id].'">
				<table border="0" cellpadding="2" cellspacing="1" width="95%" style="margin:0px auto;">
					<tr>
						<td colspan="2">
							<span style="font-size:12pt;"><b>'.$FS_PHRASES[contest_analysis_contest].'</b></span><hr>
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[contest_add_ctitle].':</b></td>
						<td>'.$contest[title].'</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[contest_add_analysis].':</b></td>
						<td>
			';
			
			if ($contest[analysis] == 1)
				$FSXL[content] .= $FS_PHRASES[contest_add_lottery];
			elseif ($contest[analysis] == 2)
				$FSXL[content] .= $FS_PHRASES[contest_add_jury];
			else
				$FSXL[content] .= $FS_PHRASES[contest_add_uservote] . ' ' . date("d.m.Y H:i", $contest[votedate]);
			
			$FSXL[content] .= '
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[contest_add_winners].':</b></td>
						<td>'.$contest[winner].'</td>
					</tr>
					<tr>
						<td colspan="2">
							<p>&nbsp;<p>
							<span style="font-size:12pt;"><b>'.$FS_PHRASES[contest_analysis_winnersuggestion].'</b></span><hr>
						</td>
					</tr>
				</table>
				<table border="0" cellpadding="2" cellspacing="1" width="95%" style="margin:0px auto;">
		';
		
		// Auslosung
		if ($contest[analysis] == 1)
		{
			$index = mysql_query("SELECT * FROM `$FSXL[tableset]_contest_entries` WHERE `contest` = $contest[id] AND `active` = 1 ORDER BY rand() LIMIT $contest[winner]");
			$i=0;
			while ($entry = mysql_fetch_assoc($index))
			{
				$i++;
				$index2 = mysql_query("SELECT `name` FROM `$FSXL[tableset]_user` WHERE `id` = $entry[user]");
				$userdat = mysql_fetch_assoc($index2);

				$FSXL[content] .= '
					<tr>
						<td rowspan="2" valign="top" width="30">
							<input type="hidden" name="entryid['.$i.']" value="'.$entry[id].'">
							<span style="font-size:14pt;"><b>'.$i.':</b></span>
						</td>
						<td><b>'.$FS_PHRASES[contest_entries_user].':</b></td>
						<td>'.$userdat[name].' ('.$entry[ip].')</td>
					</tr>
					<tr>
						<td width="80" valign="top"><b>'.$FS_PHRASES[contest_analysis_entry].':</b></td>
						<td>
				';
					
				if ($contest[type] == 1)
				{
					$hash = md5($entry[date].$entry[id]);
					$FSXL[content] .= '
							<a href="../images/contests/'.$contest[id].'/'.$hash.'.jpg" target="_blank">
								<img border="0" src="../images/contests/'.$contest[id].'/'.$hash.'s.jpg" alt="" style="float:left; margin-right:5px; margin-bottom:5px;">
							</a>
					';
				}
					
				$FSXL[content] .= '
							<b>'.$entry[title].'</b>
							<p>
							'.$entry[text].'
						</td>
					</tr>
					<tr><td colspan="3"><hr></td></tr>
				';
			}
		}
		
		// Jury
		elseif ($contest[analysis] == 2)
		{
			$index = mysql_query("SELECT * FROM `$FSXL[tableset]_contest_entries` WHERE `contest` = $contest[id] AND `active` = 1 ORDER BY `date`");
			$i=0;
			while ($entry = mysql_fetch_assoc($index))
			{
				$i++;
				$index2 = mysql_query("SELECT `name` FROM `$FSXL[tableset]_user` WHERE `id` = $entry[user]");
				$userdat = mysql_fetch_assoc($index2);

				$FSXL[content] .= '
					<tr>
						<td rowspan="2" valign="top" width="30">
							<input type="hidden" name="entryid['.$i.']" value="'.$entry[id].'">
							<input class="textinput" name="place['.$i.']" style="width:20px;">
						</td>
						<td><b>'.$FS_PHRASES[contest_entries_user].':</b></td>
						<td>'.$userdat[name].' ('.$entry[ip].')</td>
					</tr>
					<tr>
						<td width="80" valign="top"><b>'.$FS_PHRASES[contest_analysis_entry].':</b></td>
						<td>
				';
					
				if ($contest[type] == 1)
				{
					$hash = md5($entry[date].$entry[id]);
					$FSXL[content] .= '
							<a href="../images/contests/'.$contest[id].'/'.$hash.'.jpg" target="_blank">
								<img border="0" src="../images/contests/'.$contest[id].'/'.$hash.'s.jpg" alt="" style="float:left; margin-right:5px; margin-bottom:5px;">
							</a>
					';
				}
					
				$FSXL[content] .= '
							<b>'.$entry[title].'</b>
							<p>
							'.$entry[text].'
						</td>
					</tr>
					<tr><td colspan="3"><hr></td></tr>
				';
			}
		}
		
		// Abstimmung
		else
		{
			$index = mysql_query("SELECT SUM(`points`) AS `totalpoints`, `entry`, `user` FROM `$FSXL[tableset]_contest_votes` 
									WHERE `contest` = $contest[id] GROUP BY `entry` ORDER BY `totalpoints` DESC");
			
			$i = 0;
			while ($entry = mysql_fetch_assoc($index))
			{
				$index2 = mysql_query("SELECT * FROM `$FSXL[tableset]_contest_entries` WHERE `id` = $entry[entry]");
				$entrydata = mysql_fetch_assoc($index2);
				
				if ($entrydata[active] == 1)
				{
					$i++;
					
					$index2 = mysql_query("SELECT `name` FROM `$FSXL[tableset]_user` WHERE `id` = $entrydata[user]");
					$userdat = mysql_fetch_assoc($index2);
		
					$FSXL[content] .= '
					<tr>
						<td rowspan="3" valign="top" width="30">
							<input type="hidden" name="entryid['.$i.']" value="'.$entry[entry].'">
							<span style="font-size:14pt;"><b>'.$i.':</b></span>
						</td>
						<td><b>'.$FS_PHRASES[contest_entries_user].':</b></td>
						<td>'.$userdat[name].' ('.$entrydata[ip].')</td>
					</tr>
					<tr>
						<td width="80"><b>'.$FS_PHRASES[contest_analysis_points].':</b></td>
						<td>'.$entry[totalpoints].'</td>
					</tr>
					<tr>
						<td width="80" valign="top"><b>'.$FS_PHRASES[contest_analysis_entry].':</b></td>
						<td>
					';
					
					if ($contest[type] == 1)
					{
						$hash = md5($entrydata[date].$entry[entry]);
						$FSXL[content] .= '
							<a href="../images/contests/'.$contest[id].'/'.$hash.'.jpg" target="_blank">
								<img border="0" src="../images/contests/'.$contest[id].'/'.$hash.'s.jpg" alt="" style="float:left; margin-right:5px; margin-bottom:5px;">
							</a>
						';
					}
					
					$FSXL[content] .= '
							<b>'.$entrydata[title].'</b>
							<p>
							'.$entrydata[text].'
						</td>
					</tr>
					<tr><td colspan="3"><hr></td></tr>
					';
				}
				
				if ($i == $contest[winner])
				{
					break;
				}
			}
		}
	
		$FSXL[content] .= '
					<tr>
						<td colspan="3" align="right"><input type="submit" class="button" value="'.$FS_PHRASES[contest_analysis_save].'"></td>
					</tr>
				</table>
				</form>
		';
	}
	else
	{
		$FSXL[content] = '
			<div style="padding:20px; text-align:center;">'.$FS_PHRASES[contest_analysis_alreadydone].'</div>
		';	
	}
}

// Übersicht
else
{
	$FSXL[content] .= '
				<table border="0" cellpadding="2" cellspacing="1" width="90%" style="margin:0px auto;">
					<tr>
						<td>
							<span style="font-size:12pt"><b>'.$FS_PHRASES[contest_analysis_open].'</b></span><hr>
						</td>
					</tr>
					<tr>
						<td>
							<table border="0" cellpadding="2" cellspacing="1" width="100%">
								<tr>
									<td class="alt0"><b>'.$FS_PHRASES[contest_add_ctitle].'</b></td>
									<td class="alt0" align="center"><b>'.$FS_PHRASES[contest_add_enddate].'</b></td>
									<td class="alt0" align="center"><b>'.$FS_PHRASES[contest_entries_entries].'</b></td>
								</tr>
	';

	$index = mysql_query("SELECT `id`, `title`, `enddate`, `votedate`, `analysis` FROM `$FSXL[tableset]_contests` WHERE `enddate` <= $FSXL[time] AND `done` = 0 ORDER BY `enddate` DESC");
	$i=0;
	while ($contest = mysql_fetch_assoc($index))
	{
		if (($contest[analysis] == 3 && $contest[votedate] < $FSXL[time]) || $contest[analysis] < 3)
		{
			$i++;
								
			// Einsendungen
			$index2 = mysql_query("SELECT COUNT(`id`) AS `num` FROM `$FSXL[tableset]_contest_entries` WHERE `contest` = $contest[id] AND `active` = 1");
			$entries = mysql_fetch_assoc($index2);
		
			$FSXL[content] .= '
					<tr>
						<td class="alt'.($i%2==0?1:2).'"><a href="?mod=contest&go=analysis&id='.$contest[id].'">'.$contest[title].'</a></td>
						<td align="center" class="alt'.($i%2==0?1:2).'">'.date("d.m.Y | H:i", $contest[enddate]).'</td>
						<td align="center" class="alt'.($i%2==0?1:2).'">'.$entries[num].'</td>
					</tr>
			';
		}
	}
}

?>