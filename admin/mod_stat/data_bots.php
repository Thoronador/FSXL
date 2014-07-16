<?php

$FSXL[title] = $FS_PHRASES[stat_bots_title];

$index = mysql_query("SELECT * FROM `$FSXL[tableset]_counter_bots` ORDER BY `startdate` DESC LIMIT 50");

$FSXL[content] .= '
				<table border="0" cellpadding="2" cellspacing="1" width="90%" style="margin:0px auto;">
					<tr>
						<td colspan="4" style="padding:0px;">
							<span style="font-size:12pt;"><b>'.$FS_PHRASES[stat_bots_timeline].'</b></span><hr>
						</td>
					</tr>
					<tr>
						<td class="alt0"><b>'.$FS_PHRASES[stat_user_date].'</b></td>
						<td class="alt0" align="center"><b>'.$FS_PHRASES[stat_user_first].'</b></td>
						<td class="alt0" align="center"><b>'.$FS_PHRASES[stat_user_ip].'</b></td>
						<td class="alt0" align="center"><b>'.$FS_PHRASES[stat_bots_agent].'</b></td>
					</tr>
';

$i=0;
while($bot = mysql_fetch_assoc($index))
{
	$i++;
	$FSXL[content] .= '
					<tr>
						<td class="alt'.($i%2==0?1:2).'">'.date("d.m.Y", $bot[date]).'</td>
						<td class="alt'.($i%2==0?1:2).'" align="center">'.date("H:i", $bot[startdate]).'</td>
						<td class="alt'.($i%2==0?1:2).'">'.$bot[ip].'</td>
						<td class="alt'.($i%2==0?1:2).'">'.$bot[agent].'</td>
					</tr>
	';
}

$index = mysql_query("SELECT COUNT(`date`) AS `visits`, `agent` FROM `$FSXL[tableset]_counter_bots` GROUP BY `agent` ORDER BY `agent`");

$FSXL[content] .= '
				</table>
				<p>
				<table border="0" cellpadding="2" cellspacing="1" width="90%" style="margin:0px auto;">
					<tr>
						<td colspan="2" style="padding:0px;">
							<span style="font-size:12pt;"><b>'.$FS_PHRASES[stat_bots_bybot].'</b></span><hr>
						</td>
					</tr>
					<tr>
						<td class="alt0"><b>'.$FS_PHRASES[stat_bots_agent].'</b></td>
						<td class="alt0" align="center"><b>'.$FS_PHRASES[stat_user_hits].'</b></td>
					</tr>
';

$i=0;
while($bot = mysql_fetch_assoc($index))
{
	$i++;
	$FSXL[content] .= '
					<tr>
						<td class="alt'.($i%2==0?1:2).'">'.$bot[agent].'</td>
						<td class="alt'.($i%2==0?1:2).'" align="center">'.$bot[visits].'</td>
					</tr>
	';
}

$FSXL[content] .= '
				</table>
';


?>