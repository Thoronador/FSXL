<?php

$FSXL[title] = $FS_PHRASES[stat_div_title];

$index = mysql_query("SELECT COUNT(`date`) AS `num` FROM `$FSXL[tableset]_counter_user`");
$total = mysql_fetch_assoc($index);
$index = mysql_query("SELECT COUNT(`date`) AS `num`, `agent` FROM `$FSXL[tableset]_counter_user` GROUP BY `agent` ORDER BY `num` DESC");

$FSXL[content] .= '
				<table border="0" cellpadding="2" cellspacing="1" width="90%" style="margin:0px auto;">
					<tr>
						<td colspan="2" style="padding:0px;">
							<span style="float:right;"><a href="?mod=stat&go=div&browserdetail=true">'.$FS_PHRASES[stat_div_detail].'</a></span>
							<span style="font-size:12pt;"><b>'.$FS_PHRASES[stat_div_agents].'</b></span><hr>
						</td>
					</tr>
					<tr>
						<td class="alt0"><b>'.$FS_PHRASES[stat_div_agent].'</b></td>
						<td class="alt0" align="center"><b>'.$FS_PHRASES[stat_div_percent].'</b></td>
					</tr>
';

// Detailierte Browser Ansicht
if ($_GET[browserdetail] == true)
{
	$i=0;
	while($agent = mysql_fetch_assoc($index))
	{
		$i++;
		$percent = round(($agent[num] / $total[num] * 100), 2);
		$FSXL[content] .= '
					<tr>
						<td class="alt'.($i%2==0?1:2).'" style="font-size:8pt;">'.$agent[agent].'</td>
						<td class="alt'.($i%2==0?1:2).'" align="center">'.$percent.'%</td>
					</tr>
		';
	}
}
// Normale Browser Ansicht
else
{
	$agents = array();
	$divagents = array();
	while($agent = mysql_fetch_assoc($index))
	{
		// Firefox
		if (preg_match("/firefox/i", $agent[agent]))
		{
			$agents["Firefox"] += $agent[num];
		}
		// Opera
		elseif (preg_match("/opera/i", $agent[agent]))
		{
			$agents["Opera"] += $agent[num];
		}
		// IE 7
		elseif (preg_match("/msie\s7\.0/i", $agent[agent]))
		{
			$agents["Internet Explorer 7"] += $agent[num];
		}
		// IE 6
		elseif (preg_match("/msie/i", $agent[agent]))
		{
			$agents["Internet Explorer 6"] += $agent[num];
		}
		// Safari
		elseif (preg_match("/safari/i", $agent[agent]))
		{
			$agents["Safari"] += $agent[num];
		}
		// SeaMonkey
		elseif (preg_match("/seamonkey/i", $agent[agent]))
		{
			$agents["Sea Monkey"] += $agent[num];
		}
		// Konqueror
		elseif (preg_match("/konqueror/i", $agent[agent]))
		{
			$agents["Konqueror"] += $agent[num];
		}
		// Netscape
		elseif (preg_match("/netscape/i", $agent[agent]))
		{
			$agents["Netscape"] += $agent[num];
		}
		// Google Chrome
		elseif (preg_match("/chrome/i", $agent[agent]))
		{
			$agents["Google Chrome"] += $agent[num];
		}
		else
		{
			$agents["Sonstige"] += $agent[num];
			array_push($divagents, $agent[agent]);
		}
	}

	arsort($agents);
	$i=0;
	foreach ($agents AS $agent => $value)
	{
		$i++;
		$percent = round(($value / $total[num] * 100), 2);
		$FSXL[content] .= '
					<tr>
						<td class="alt'.($i%2==0?1:2).'" style="font-size:8pt;">'.$agent.'</td>
						<td class="alt'.($i%2==0?1:2).'" align="center">'.$percent.'%</td>
					</tr>
		';
	}
}

$FSXL[content] .= '
					<!-- tr>
						<td colspan="2">'.implode("<br><hr>\n", $divagents).'</td>
					</tr -->
				</table>
				<p>
				<table border="0" cellpadding="2" cellspacing="1" width="90%" style="margin:0px auto;">
					<tr>
						<td colspan="2" style="padding:0px;">
							<span style="font-size:12pt;"><b>'.$FS_PHRASES[stat_div_langs].'</b></span><hr>
						</td>
					</tr>
					<tr>
						<td class="alt0"><b>'.$FS_PHRASES[stat_div_country].'</b></td>
						<td class="alt0" align="center"><b>'.$FS_PHRASES[stat_div_percent].'</b></td>
					</tr>
';

// Sprachen
$index = mysql_query("SELECT COUNT(`date`) AS `num`, `lang` FROM `$FSXL[tableset]_counter_user` GROUP BY `lang`");
$langs = array();
$divlangs = array();
while($lang = mysql_fetch_assoc($index))
{
	if (strpos($lang[lang], '-')) $short = strtoupper(substr($lang[lang], strpos($lang[lang], '-')+1, 2));
	else $short = strtoupper(substr($lang[lang], 0, 2));
	if ($FS_PHRASES[stat_div_countrys][$short])
	{
		$langs[$FS_PHRASES[stat_div_countrys][$short]] += $lang[num];
	}
	else
	{
		array_push($divlangs, $lang[lang]);
		$langs["Sonstige"] += $lang[num];
	}
}

arsort($langs);
$i=0;
foreach ($langs AS $lang => $value)
{
	$i++;
	$percent = round(($value / $total[num] * 100), 2);
	$FSXL[content] .= '
					<tr>
						<td class="alt'.($i%2==0?1:2).'" style="font-size:8pt;">'.$lang.'</td>
						<td class="alt'.($i%2==0?1:2).'" align="center">'.$percent.'%</td>
					</tr>
	';
}

$FSXL[content] .= '
					<!-- tr>
						<td colspan="2">'.implode("<br><hr>\n", $divlangs).'</td>
					</tr -->
				</table>
';


?>