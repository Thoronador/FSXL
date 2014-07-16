<?php

$FSXL[title] = $FS_PHRASES[stat_gallery_title];

if ($_GET[year] || $_GET[month] || $_GET[day])
{
	settype($_GET[year], 'integer');
	settype($_GET[month], 'integer');
	settype($_GET[day], 'integer');

	if ($_GET[year] && $_GET[month] && $_GET[day])
	{
		$index = mysql_query("SELECT c.hits AS hits, a.name AS title
					FROM $FSXL[tableset]_counter_gallery c, $FSXL[tableset]_galleries a
					WHERE a.id = c.id AND c.year = $_GET[year] AND c.month = $_GET[month] AND c.day = $_GET[day]
					ORDER BY a.name");
		$breadcrump = '<b>> <a href="?mod=stat&go=gallery&year='.$_GET[year].'">'.$_GET[year].'</a> ><a href="?mod=stat&go=gallery&year='.$_GET[year].'&month='.$_GET[month].'">'.$FS_PHRASES[stat_page_monthnames][$_GET[month]].'</a> > '.$_GET[day].'</b>';
	}
	elseif ($_GET[year] && $_GET[month])
	{
		$index = mysql_query("SELECT SUM(c.hits) AS hits, a.name AS title
					FROM $FSXL[tableset]_counter_gallery c, $FSXL[tableset]_galleries a
					WHERE a.id = c.id AND c.year = $_GET[year] AND c.month = $_GET[month]
					GROUP BY c.id
					ORDER BY a.name");
		$breadcrump = '<b>> <a href="?mod=stat&go=gallery&year='.$_GET[year].'">'.$_GET[year].'</a> >'.$FS_PHRASES[stat_page_monthnames][$_GET[month]].'</b>';
	}
	else
	{
		$index = mysql_query("SELECT SUM(c.hits) AS hits, a.name AS title
					FROM $FSXL[tableset]_counter_gallery c, $FSXL[tableset]_galleries a
					WHERE a.id = c.id AND c.year = $_GET[year]
					GROUP BY c.id
					ORDER BY a.name");
		$breadcrump = '<b>> '.$_GET[year].'</b>';
	}

	$FSXL[content] .= '
				<table border="0" cellpadding="2" cellspacing="1" width="90%" style="margin:0px auto;">
					<tr>
						<td colspan="2" style="padding:0px;">
							<span style="font-size:12pt;"><b><a href="?mod=stat&go=gallery">'.$FS_PHRASES[stat_page_stat].'</a> '.$breadcrump.'</b></span><hr>
						</td>
					</tr>
					<tr>
						<td class="alt0"><b>'.$FS_PHRASES[stat_gallery_gallery].'</b></td>
						<td class="alt0" align="center"><b>'.$FS_PHRASES[stat_page_hits].'</b></td>
					</tr>
	';

	$average = mysql_num_rows($index) > 0 ? mysql_num_rows($index) : 1;
	$i = 0;
	$allhits = 0;
	while ($gallery = mysql_fetch_assoc($index))
	{
		$allhits += $gallery[hits];
		$FSXL[content] .= '
					<tr>
						<td class="alt'.($i%2 == 0 ? "1" : "2").'">'.$gallery[title].'</td>
						<td align="center" class="alt'.($i%2 == 0 ? "1" : "2").'">'.formatNumber($gallery[hits]).'</td>
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

// EInzelne Galerie
elseif ($_GET[id])
{
	settype($_GET[id], 'integer');
	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_galleries` WHERE `id` = $_GET[id]");
	if (mysql_num_rows($index) > 0)
	{
		$gallery = mysql_fetch_assoc($index);

		$FSXL[content] .= '
				<table border="0" cellpadding="2" cellspacing="1" width="90%" style="margin:0px auto;">
					<tr>
						<td colspan="3" style="padding:0px;">
							<span style="font-size:12pt;"><b><a href="?mod=stat&go=page">'.$FS_PHRASES[stat_page_stat].'</a> '.$breadcrump.'</b></span><hr>
						</td>
					</tr>
					<tr>
						<td class="alt0"><b>'.$FS_PHRASES[stat_gallery_pic].'</b></td>
						<td class="alt0" align="center"><b>'.$FS_PHRASES[stat_page_hits].'</b></td>
						<td class="alt0" align="center"><b>'.$FS_PHRASES[stat_gallery_link].'</b></td>
					</tr>
		';

		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_gallerypics` WHERE `galleryid` = $_GET[id] ORDER BY `position` ASC");
		$average = mysql_num_rows($index) > 0 ? mysql_num_rows($index) : 1;
		$i = 0;
		$allhits = 0;
		while ($pic = mysql_fetch_assoc($index))
		{
			$allhits += $pic[hits];
			$FSXL[content] .= '
					<tr>
						<td class="alt'.($i%2 == 0 ? "1" : "2").'">'.$pic[titel].'</td>
						<td align="center" class="alt'.($i%2 == 0 ? "1" : "2").'">'.formatNumber($pic[hits]).'</td>
						<td align="center" class="alt'.($i%2 == 0 ? "1" : "2").'"><a href="../index.php?section=gallery&detail='.$pic[id].'" target="_blank"><img border="0" src="images/'.$FSXL[style].'_link.gif" alt=""></a></td>
					</tr>
			';
			$i++;
		}

		$FSXL[content] .= '
					<tr>
						<td class="alt'.($i%2 == 0 ? "1" : "2").'"><b>'.$FS_PHRASES[stat_page_average].'</b></td>
						<td class="alt'.($i%2 == 0 ? "1" : "2").'" align="center"><b>'.formatNumber($allhits/$average).'</b></td>
						<td class="alt'.($i%2 == 0 ? "1" : "2").'"></td>
					</tr>
				</table>
		';
	}
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
						<td class="alt0"><a href="?mod=stat&go=gallery&order=name" style="color:#FFFFFF;"><b>'.$FS_PHRASES[stat_gallery_gallery].'</b></a></td>
						<td class="alt0" align="center"><a href="?mod=stat&go=gallery&order=hits" style="color:#FFFFFF;"><b>'.$FS_PHRASES[stat_page_hits].'</b></a></td>
						<td class="alt0" align="center"><b>'.$FS_PHRASES[stat_page_perday].'</b></td>
					</tr>
	';

	if ($_GET[order] == 'hits') 
	{
		$sqladd = 'ORDER BY `hits` DESC';
	}
	else
	{
		$sqladd = 'ORDER BY a.name';
	}

	$index = mysql_query("SELECT SUM(c.hits) AS hits, a.name AS title, a.id AS id
				FROM $FSXL[tableset]_counter_gallery c, $FSXL[tableset]_galleries a
				WHERE a.id = c.id
				GROUP BY c.id
				$sqladd");

	$average = mysql_num_rows($index) > 0 ? mysql_num_rows($index) : 1;
	$i = 0;
	$allhits = 0;
	$allperday = 0;
	while ($gallery = mysql_fetch_assoc($index))
	{
		$index2 = mysql_query("SELECT * FROM `$FSXL[tableset]_counter_gallery` WHERE `id` = $gallery[id] ORDER BY `year`, `month`, `day` LIMIT 1");
		$single = mysql_fetch_assoc($index2);
		$days = round((time() - mktime(0, 0, 0, $single[month], $single[day], $single[year])) / 86400);
		$perday = round($gallery[hits]/$days, 2);
		$allperday += $perday;

		$allhits += $gallery[hits];
		$FSXL[content] .= '
					<tr>
						<td class="alt'.($i%2 == 0 ? "1" : "2").'"><a href="?mod=stat&go=gallery&id='.$gallery[id].'">'.$gallery[title].'</a></td>
						<td align="center" class="alt'.($i%2 == 0 ? "1" : "2").'">'.formatNumber($gallery[hits]).'</td>
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