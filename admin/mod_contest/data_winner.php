<?php

$FSXL[title] = $FS_PHRASES[contest_winner_title];
$FSXL[content] = '';

// Gewinner löschen
if ($_POST[contestid] && $_POST[del])
{
	settype($_POST[contestid], 'integer');
	mysql_query("DELETE FROM `$FSXL[tableset]_contest_winner` WHERE `contest` = $_POST[contestid]");
	mysql_query("UPDATE `$FSXL[tableset]_contests` SET `done` = 0 WHERE `id` = $_POST[contestid]");

	$FSXL[content] = '
			<div style="padding:20px; text-align:center;">
				'.$FS_PHRASES[contest_winner_deleted].'
			</div>
	';	
}

// Formular
elseif ($_GET[id])
{
	settype($_GET[id], 'integer');
	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_contests` WHERE `id` = $_GET[id]");
	$contest = mysql_fetch_assoc($index);
	
	$FSXL[content] .= '
				<form action="?mod=contest&go=winner" method="post">
				<input type="hidden" name="action" value="delete">
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
						<td><b>'.$FS_PHRASES[contest_winner_delwinners].':</b></td>
						<td>
							<input type="checkbox" name="del" id="delete" onClick="delMessage(\''.$FS_PHRASES[contest_winner_delmessage].'\');">
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<p>&nbsp;<p>
							<span style="font-size:12pt;"><b>'.$FS_PHRASES[contest_winner_winner].'</b></span><hr>
						</td>
					</tr>
				</table>
				<table border="0" cellpadding="2" cellspacing="1" width="95%" style="margin:0px auto;">
		';
		
		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_contest_winner` WHERE `contest` = $contest[id] ORDER BY `position`");
		while ($winner = mysql_fetch_assoc($index))
		{
			$index2 = mysql_query("SELECT `title`, `id`, `ip`, `user` FROM `$FSXL[tableset]_contest_entries` WHERE `id` = $winner[entry]");
			$entry = mysql_fetch_assoc($index2);

			$index2 = mysql_query("SELECT u.name AS `name`, d.email AS `email`
									FROM `$FSXL[tableset]_user` u, `$FSXL[tableset]_userdata` d
									WHERE u.id = $entry[user] AND d.userid = u.id");
			$userdat = mysql_fetch_assoc($index2);

			$FSXL[content] .= '
					<tr>
						<td rowspan="3" valign="top" width="30">
							<span style="font-size:14pt;"><b>'.$winner[position].':</b></span>
						</td>
						<td><b>'.$FS_PHRASES[contest_entries_user].':</b></td>
						<td>'.$userdat[name].' ('.$entry[ip].')</td>
					</tr>
					<tr>
						<td width="80" valign="top"><b>'.$FS_PHRASES[contest_winner_email].':</b></td>
						<td>'.$userdat[email].'</td>
					</tr>
					<tr>
						<td width="80" valign="top"><b>'.$FS_PHRASES[contest_add_ctitle].':</b></td>
						<td>
							<a href="../index.php?section=contestentry&id='.$entry[id].'" target="_blank">'.$entry[title].'
							<img border="0" src="images/'.$FSXL[style].'_link.gif" alt=""></a>
						</td>
					</tr>
					<tr><td colspan="3"><hr></td></tr>
			';
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
									<td class="alt0" align="center"><b>'.$FS_PHRASES[contest_add_enddate].'</b></td>
									<td class="alt0" align="center"><b>'.$FS_PHRASES[contest_winner_winner].'</b></td>
								</tr>
	';

	$index = mysql_query("SELECT `id`, `title`, `enddate` FROM `$FSXL[tableset]_contests` WHERE `done` = 1 ORDER BY `enddate` DESC");
	$i=0;
	while ($contest = mysql_fetch_assoc($index))
	{
		$i++;
								
		// Einsendungen
		$index2 = mysql_query("SELECT COUNT(`contest`) AS `num` FROM `$FSXL[tableset]_contest_winner` WHERE `contest` = $contest[id]");
		$winner = mysql_fetch_assoc($index2);
		
		$FSXL[content] .= '
					<tr>
						<td class="alt'.($i%2==0?1:2).'"><a href="?mod=contest&go=winner&id='.$contest[id].'">'.$contest[title].'</a></td>
						<td align="center" class="alt'.($i%2==0?1:2).'">'.date("d.m.Y | H:i", $contest[enddate]).'</td>
						<td align="center" class="alt'.($i%2==0?1:2).'">'.$winner[num].'</td>
					</tr>
		';
	}
}

?>