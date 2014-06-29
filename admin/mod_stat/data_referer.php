<?php

$FSXL[title] = $FS_PHRASES[stat_referer_title];


$FSXL[content] .= '
				<table border="0" cellpadding="2" cellspacing="1" width="90%" style="margin:0px auto;">
';

// Tagesanzeige
if ($_GET[day] && $_GET[month] && $_GET[year])
{
	settype($_GET[day], 'integer');
	settype($_GET[month], 'integer');
	settype($_GET[year], 'integer');
	$date = mktime(0, 0, 0, $_GET[month], $_GET[day], $_GET[year]);

	$FS_PHRASES[stat_referer_day] = str_replace('%d', $_GET[day], $FS_PHRASES[stat_referer_day]);
	$FS_PHRASES[stat_referer_day] = str_replace('%m', $_GET[month], $FS_PHRASES[stat_referer_day]);
	$FS_PHRASES[stat_referer_day] = str_replace('%y', $_GET[year], $FS_PHRASES[stat_referer_day]);

	$index = mysql_query("SELECT COUNT(`date`) AS `num`, `referer` FROM `$FSXL[tableset]_counter_user` WHERE `date` = $date GROUP BY `referer` ORDER BY `num` DESC");

	$FSXL[content] .= '
					<tr>
						<td colspan="2" style="padding:0px;">
							<span style="font-size:12pt;"><b>'.$FS_PHRASES[stat_referer_day].'</b></span><hr>
						</td>
					</tr>
	';
}

// Normale Anzeige
else
{
	$index = mysql_query("SELECT COUNT(`date`) AS `num`, `referer` FROM `$FSXL[tableset]_counter_user` GROUP BY `referer` ORDER BY `num` DESC LIMIT 50");
	$FSXL[content] .= '
					<tr>
						<td colspan="2" style="padding:0px;">
							<span style="font-size:12pt;"><b>'.$FS_PHRASES[stat_referer_top50].'</b></span><hr>
						</td>
					</tr>
	';
}

$FSXL[content] .= '
					<tr>
						<td class="alt0"><b>'.$FS_PHRASES[stat_referer_referer].'</b></td>
						<td class="alt0" align="center"><b>'.$FS_PHRASES[stat_referer_hits].'</b></td>
					</tr>
';

$i=0;
while($referer = mysql_fetch_assoc($index))
{
	$i++;
	if (!$referer[referer])
	{
		$FSXL[content] .= '
					<tr>
						<td class="alt'.($i%2==0?1:2).'">'.$FS_PHRASES[stat_referer_direct].'</td>
						<td class="alt'.($i%2==0?1:2).'" align="center">'.$referer[num].'</td>
					</tr>
		';
	}
	else
	{
			$FSXL[content] .= '
					<tr>
						<td class="alt'.($i%2==0?1:2).'"><a href="'.$referer[referer].'" target="_blank">'.$referer[referer].'</a></td>
						<td class="alt'.($i%2==0?1:2).'" align="center">'.$referer[num].'</td>
					</tr>
		';
	}
}

$FSXL[content] .= '
				</table>
';


?>