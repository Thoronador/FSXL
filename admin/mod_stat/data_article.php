<?php

$FSXL[title] = $FS_PHRASES[stat_article_title];

if ($_GET[year] || $_GET[month] || $_GET[day])
{
	settype($_GET[year], 'integer');
	settype($_GET[month], 'integer');
	settype($_GET[day], 'integer');

	if ($_GET[year] && $_GET[month] && $_GET[day])
	{
		$index = mysql_query("SELECT c.hits AS hits, a.titel AS title
					FROM $FSXL[tableset]_counter_article c, $FSXL[tableset]_article a
					WHERE a.id = c.id AND c.year = $_GET[year] AND c.month = $_GET[month] AND c.day = $_GET[day]
					ORDER BY a.titel");
		$breadcrump = '<b>> <a href="?mod=stat&go=article&year='.$_GET[year].'">'.$_GET[year].'</a> ><a href="?mod=stat&go=article&year='.$_GET[year].'&month='.$_GET[month].'">'.$FS_PHRASES[stat_page_monthnames][$_GET[month]].'</a> > '.$_GET[day].'</b>';
	}
	elseif ($_GET[year] && $_GET[month])
	{
		$index = mysql_query("SELECT SUM(c.hits) AS hits, a.titel AS title
					FROM $FSXL[tableset]_counter_article c, $FSXL[tableset]_article a
					WHERE a.id = c.id AND c.year = $_GET[year] AND c.month = $_GET[month]
					GROUP BY c.id
					ORDER BY a.titel");
		$breadcrump = '<b>> <a href="?mod=stat&go=article&year='.$_GET[year].'">'.$_GET[year].'</a> >'.$FS_PHRASES[stat_page_monthnames][$_GET[month]].'</b>';
	}
	else
	{
		$index = mysql_query("SELECT SUM(c.hits) AS hits, a.titel AS title
					FROM $FSXL[tableset]_counter_article c, $FSXL[tableset]_article a
					WHERE a.id = c.id AND c.year = $_GET[year]
					GROUP BY c.id
					ORDER BY a.titel");
		$breadcrump = '<b>> '.$_GET[year].'</b>';
	}

	$FSXL[content] .= '
				<table border="0" cellpadding="2" cellspacing="1" width="90%" style="margin:0px auto;">
					<tr>
						<td colspan="2" style="padding:0px;">
							<span style="font-size:12pt;"><b><a href="?mod=stat&go=article">'.$FS_PHRASES[stat_page_stat].'</a> '.$breadcrump.'</b></span><hr>
						</td>
					</tr>
					<tr>
						<td class="alt0"><b>'.$FS_PHRASES[stat_article_article].'</b></td>
						<td class="alt0" align="center"><b>'.$FS_PHRASES[stat_page_hits].'</b></td>
					</tr>
	';

	$average = mysql_num_rows($index) > 0 ? mysql_num_rows($index) : 1;
	$i = 0;
	$allhits = 0;
	while ($article = mysql_fetch_assoc($index))
	{
		$allhits += $article[hits];
		$FSXL[content] .= '
					<tr>
						<td class="alt'.($i%2 == 0 ? "1" : "2").'">'.$article[title].'</td>
						<td align="center" class="alt'.($i%2 == 0 ? "1" : "2").'">'.formatNumber($article[hits]).'</td>
					</tr>
		';
		$i++;
	}

	$FSXL[content] .= '
					<tr>
						<td class="alt'.($i%2 == 0 ? "1" : "2").'"><b>'.$FS_PHRASES[stat_page_average].'</b></td>
						<td class="alt'.($i%2 == 0 ? "1" : "2").'" align="center"><b>'.formatNumber($allhits/$average).'</b></td>
					</tr>
				</table>
	';
}

else
{
	$FSXL[content] .= '
				<table border="0" cellpadding="2" cellspacing="1" width="90%" style="margin:0px auto;">
					<tr>
						<td colspan="3" style="padding:0px;">
							<span style="font-size:12pt;"><b><a href="?mod=stat&go=page">'.$FS_PHRASES[stat_page_stat].'</a> '.$breadcrump.'</b></span><hr>
						</td>
					</tr>
					<tr>
						<td class="alt0"><a href="?mod=stat&go=article&order=name" style="color:#FFFFFF;"><b>'.$FS_PHRASES[stat_article_article].'</b></a></td>
						<td class="alt0" align="center"><a href="?mod=stat&go=article&order=hits" style="color:#FFFFFF;"><b>'.$FS_PHRASES[stat_page_hits].'</b></a></td>
						<td class="alt0" align="center"><b>'.$FS_PHRASES[stat_page_perday].'</b></td>
					</tr>
	';

	if ($_GET[order] == 'hits') 
	{
		$sqladd = 'ORDER BY `hits` DESC';
	}
	else
	{
		$sqladd = 'ORDER BY a.titel';
	}

	$index = mysql_query("SELECT SUM(c.hits) AS hits, a.titel AS title, a.id AS id
				FROM $FSXL[tableset]_counter_article c, $FSXL[tableset]_article a
				WHERE a.id = c.id
				GROUP BY c.id
				$sqladd");

	$average = mysql_num_rows($index) > 0 ? mysql_num_rows($index) : 1;
	$i = 0;
	$allhits = 0;
	$allperday = 0;
	while ($article = mysql_fetch_assoc($index))
	{
		$index2 = mysql_query("SELECT * FROM `$FSXL[tableset]_counter_article` WHERE `id` = $article[id] ORDER BY `year`, `month`, `day` LIMIT 1");
		$single = mysql_fetch_assoc($index2);
		$days = round((time() - mktime(0, 0, 0, $single[month], $single[day], $single[year])) / 86400);
		$perday = round($article[hits]/$days, 2);
		$allperday += $perday;

		$allhits += $article[hits];
		$FSXL[content] .= '
					<tr>
						<td class="alt'.($i%2 == 0 ? "1" : "2").'">'.$article[title].'</td>
						<td align="center" class="alt'.($i%2 == 0 ? "1" : "2").'">'.formatNumber($article[hits]).'</td>
						<td align="center" class="alt'.($i%2 == 0 ? "1" : "2").'">'.$perday.'</td>
					</tr>
		';
		$i++;
	}

	$FSXL[content] .= '
					<tr>
						<td class="alt'.($i%2 == 0 ? "1" : "2").'"><b>'.$FS_PHRASES[stat_page_average].'</b></td>
						<td class="alt'.($i%2 == 0 ? "1" : "2").'" align="center"><b>'.formatNumber($allhits/$average).'</b></td>
						<td class="alt'.($i%2 == 0 ? "1" : "2").'" align="center"><b>'.round($allperday/$average, 2).'</b></td>
					</tr>
				</table>
	';
}

?>