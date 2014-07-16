<?php

$FSXL[title] = $FS_PHRASES[stat_user_title];

$FSXL[content] .= '
				<table border="0" cellpadding="2" cellspacing="1" width="90%" style="margin:0px auto;">
';

if ($_GET[day] && $_GET[month] && $_GET[year])
{
	settype($_GET[day], 'integer');
	settype($_GET[month], 'integer');
	settype($_GET[year], 'integer');
	$date = mktime(0, 0, 0, $_GET[month], $_GET[day], $_GET[year]);

	$FS_PHRASES[stat_user_day] = str_replace('%d', $_GET[day], $FS_PHRASES[stat_user_day]);
	$FS_PHRASES[stat_user_day] = str_replace('%m', $_GET[month], $FS_PHRASES[stat_user_day]);
	$FS_PHRASES[stat_user_day] = str_replace('%y', $_GET[year], $FS_PHRASES[stat_user_day]);

	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_counter_user` WHERE `date` = $date ORDER BY `enddate` DESC");

	$FSXL[content] .= '
					<tr>
						<td colspan="6" style="padding:0px;">
							<span style="font-size:12pt;"><b>'.$FS_PHRASES[stat_user_day].'</b></span><hr>
						</td>
					</tr>
	';
}
else
{
	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_counter_user` ORDER BY `enddate` DESC LIMIT 50");

	$FSXL[content] .= '
					<tr>
						<td colspan="6" style="padding:0px;">
							<span style="font-size:12pt;"><b>'.$FS_PHRASES[stat_user_last50].'</b></span><hr>
						</td>
					</tr>
	';
}

$FSXL[content] .= '
					<tr>
						<td class="alt0" align="center"><b>'.$FS_PHRASES[stat_user_date].'</b></td>
						<td class="alt0" align="center"><b>'.$FS_PHRASES[stat_user_first].'</b></td>
						<td class="alt0" align="center"><b>'.$FS_PHRASES[stat_user_last].'</b></td>
						<td class="alt0" align="center"><b>'.$FS_PHRASES[stat_user_hits].'</b></td>
						<td class="alt0" align="center"><b>'.$FS_PHRASES[stat_user_ip].'</b></td>
						<td class="alt0" align="center"><b>'.$FS_PHRASES[stat_user_lang].'</b></td>
					</tr>
';

$i=0;
while($userdata = mysql_fetch_assoc($index))
{
	$i++;
	if ($userdata[ip] == $_SERVER[REMOTE_ADDR])
	{
		$opener = '<b>';
		$closer = '</b>';
	}
	else
	{
		$opener = $closer = '';
	}
	if (strpos($userdata[lang], '-')) $short = strtoupper(substr($userdata[lang], strpos($userdata[lang], '-')+1, 2));
	else $short = strtoupper(substr($userdata[lang], 0, 2));
	$lang = $FS_PHRASES[stat_div_countrys][$short];
	$FSXL[content] .= '
					<tr>
						<td class="alt'.($i%2==0?1:2).'" align="center">'.$opener.date("d.m.Y", $userdata[date]).$closer.'</td>
						<td class="alt'.($i%2==0?1:2).'" align="center">'.$opener.date("H:i", $userdata[startdate]).$closer.'</td>
						<td class="alt'.($i%2==0?1:2).'" align="center">'.$opener.date("H:i", $userdata[enddate]).$closer.'</td>
						<td class="alt'.($i%2==0?1:2).'" align="center">'.$opener.$userdata[views].$closer.'</td>
						<td class="alt'.($i%2==0?1:2).'" align="center">'.$opener.$userdata[ip].$closer.'</td>
						<td class="alt'.($i%2==0?1:2).'" align="center">'.$opener.$lang.$closer.'</td>
					</tr>
	';
}

$FSXL[content] .= '
				</table>
';


?>