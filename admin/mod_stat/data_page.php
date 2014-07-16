<?php

// Alte Daten löschen
$deltime = $FSXL[time] - ($FSXL[config][counter_savetime]*2592000);
mysql_query("DELETE FROM `$FSXL[tableset]_counter_user` WHERE `date` < $deltime");
mysql_query("OPTIMIZE TABLE `$FSXL[tableset]_counter_user`");

$FSXL[title] = $FS_PHRASES[stat_page_title];

	settype($_GET[year], 'integer');
	settype($_GET[month], 'integer');

	if ($_GET[year] && $_GET[month])
	{
		$index = mysql_query("SELECT day AS item, `visits`, `hits` FROM `$FSXL[tableset]_counter_stat` 
					WHERE `year` = $_GET[year] AND `month` = $_GET[month]");
		$breadcrump = '<b>> <a href="?mod=stat&go=page&year='.$_GET[year].'">'.$_GET[year].'</a> >'.$FS_PHRASES[stat_page_monthnames][$_GET[month]].'</b>';
		$title = $FS_PHRASES[stat_page_day];
		$url = '&year='.$_GET[year].'&month='.$_GET[month].'&day=';
		$from = 1;
		$to = date("t", mktime (0, 0, 0, $_GET[month], 1, $_GET[year]));
	}
	elseif ($_GET[year])
	{
		$index = mysql_query("SELECT month AS item, SUM(visits) AS visits, SUM(hits) AS hits FROM `$FSXL[tableset]_counter_stat` 
					WHERE `year` = $_GET[year] GROUP BY `month`");
		$breadcrump = '<b>> '.$_GET[year].'</b>';
		$title = $FS_PHRASES[stat_page_month];
		$url = '&year='.$_GET[year].'&month=';
		$from = 1;
		$to = 12;
	}
	else
	{
		$index = mysql_query("SELECT year AS item, SUM(visits) AS visits, SUM(hits) AS hits FROM `$FSXL[tableset]_counter_stat` GROUP BY `year`");
		$title = $FS_PHRASES[stat_page_year];
		$url = '&year=';
	}

	$average = mysql_num_rows($index) > 0 ? mysql_num_rows($index) : 1;
	$statarr = array();
	$allvisits = 0;
	$allhits = 0;
	while ($stat = mysql_fetch_assoc($index))
	{
		$statarr[$stat[item]] = $stat;
		$allvisits += $stat[visits];
		$allhits += $stat[hits];
	}

	$FSXL[content] .= '
				<table border="0" cellpadding="2" cellspacing="1" width="90%" style="margin:0px auto;">
					<tr>
						<td colspan="7" style="padding:0px;">
							<span style="font-size:12pt;"><b><a href="?mod=stat&go=page">'.$FS_PHRASES[stat_page_stat].'</a> '.$breadcrump.'</b></span><hr>
						</td>
					</tr>
	';
	if($to-$from > 2)
	{
		$FSXL[content] .= '
					<tr>
						<td colspan="7" align="center"><img border="0" src="mod_stat/statgfx.php?year='.$_GET[year].'&month='.$_GET[month].'" alt=""><p></td>
					</tr>
		';
	}
	$FSXL[content] .= '
					<tr>
						<td class="alt0"><b>'.$title.'</b></td>
						<td class="alt0" align="center"><b>'.$FS_PHRASES[stat_page_visits].'</b></td>
						<td class="alt0" align="center"><b>'.$FS_PHRASES[stat_page_hits].'</b></td>
						<td class="alt0"></td>
						<td class="alt0"></td>
						<td class="alt0"></td>
						<td class="alt0"></td>
					</tr>
	';

	if (!$_GET[year] && !$_GET[month])
	{
		$tmp = $statarr;
		$from = array_shift($tmp);
		$from = $from[item];
		$to = array_pop($tmp);
		$to = $to[item];
	}

	for ($i=$from; $i<=$to; $i++)
	{
		if ($_GET[year] && $_GET[month])
		{
			$day = date("w", mktime (0, 0, 0, $_GET[month], $i, $_GET[year]));
			$item = '<a href="?mod=stat&go=user&day='.$i.'&month='.$_GET[month].'&year='.$_GET[year].'">'.$i . '. ' . $FS_PHRASES[stat_page_daynames][$day].'</a>';
			$url2 = $i;
			$ref = '<a href="?mod=stat&go=referer&day='.$i.'&month='.$_GET[month].'&year='.$_GET[year].'">
					<img border="0" src="images/'.$FSXL[style].'_icon_referer.gif" alt="'.$FS_PHRASES[stat_page_referer].'"></a>';
		}
		elseif ($_GET[year])
		{
			$url2 = $i;
			$item = '<a href="?mod=stat&go=page'.$url.$url2.'">'.$FS_PHRASES[stat_page_monthnames][$i].'</a>';
		}
		else
		{
			$url2 = $i;
			$item = '<a href="?mod=stat&go=page'.$url.$url2.'">'.$i.'</a>';
		}

		$FSXL[content] .= '
					<tr>
						<td class="alt'.($i%2 == 0 ? "1" : "2").'">'.$item.'</td>
						<td align="center" class="alt'.($i%2 == 0 ? "1" : "2").'">'.formatNumber($statarr[$i][visits]).'</td>
						<td align="center" class="alt'.($i%2 == 0 ? "1" : "2").'">'.formatNumber($statarr[$i][hits]).'</td>
						<td align="center" class="alt'.($i%2 == 0 ? "1" : "2").'"><a href="?mod=stat&go=article'.$url.$url2.'"><img border="0" src="images/'.$FSXL[style].'_icon_article.gif" alt="'.$FS_PHRASES[stat_page_article].'"></a></td>
						<td align="center" class="alt'.($i%2 == 0 ? "1" : "2").'"><a href="?mod=stat&go=news'.$url.$url2.'"><img border="0" src="images/'.$FSXL[style].'_icon_news.gif" alt="'.$FS_PHRASES[stat_page_news].'"></a></td>
						<td align="center" class="alt'.($i%2 == 0 ? "1" : "2").'"><a href="?mod=stat&go=gallery'.$url.$url2.'"><img border="0" src="images/'.$FSXL[style].'_icon_gallery.gif" alt="'.$FS_PHRASES[stat_page_gallery].'"></a></td>
						<td align="center" class="alt'.($i%2 == 0 ? "1" : "2").'">'.$ref.'</td>
					</tr>
		';
	}

	$FSXL[content] .= '
					<tr>
						<td class="alt'.($i%2 == 0 ? "1" : "2").'"><b>'.$FS_PHRASES[stat_page_average].'</b></td>
						<td class="alt'.($i%2 == 0 ? "1" : "2").'" align="center"><b>'.formatNumber($allvisits/$average).'</b></td>
						<td class="alt'.($i%2 == 0 ? "1" : "2").'" align="center"><b>'.formatNumber($allhits/$average).'</b></td>
						<td class="alt'.($i%2 == 0 ? "1" : "2").'"></td>
						<td class="alt'.($i%2 == 0 ? "1" : "2").'"></td>
						<td class="alt'.($i%2 == 0 ? "1" : "2").'"></td>
						<td class="alt'.($i%2 == 0 ? "1" : "2").'"></td>
					</tr>
				</table>
	';

?>