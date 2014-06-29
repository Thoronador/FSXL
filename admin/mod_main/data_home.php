<?php

$FSXL[title] = $FS_PHRASES[main_home_title];

// Counter auslesen
$all = explode(',', implode('', file('../cache/counter.cch')));

$index = mysql_query("SELECT * FROM `$FSXL[tableset]_counter_stat` WHERE `year` = ".date("Y")." AND `month` = ".date("m")." AND `day` = ".date("d"));
$today = mysql_fetch_assoc($index);

$FS_PHRASES[main_home_stat] = str_replace("%va", formatNumber($all[0]), $FS_PHRASES[main_home_stat]);
$FS_PHRASES[main_home_stat] = str_replace("%ha", formatNumber($all[1]), $FS_PHRASES[main_home_stat]);
$FS_PHRASES[main_home_stat] = str_replace("%vt", formatNumber($today[visits]), $FS_PHRASES[main_home_stat]);
$FS_PHRASES[main_home_stat] = str_replace("%ht", formatNumber($today[hits]), $FS_PHRASES[main_home_stat]);

$text = '
			<div align="left">
				<span style="font-size:12pt;"><b>'.$FS_PHRASES[main_home_welcome].'</b></span><hr>
				<p>'.$FS_PHRASES[main_home_stat].'
			</div>
';

// News Einsendungen anzeigen
if (in_array($_SESSION[user]->userid, $FSXL[superadmin]) || ($_SESSION[user]->access[news][submit] && $_SESSION[user]->access[news][addnews]))
{
	$index = mysql_query("SELECT `id`, `title`, `date`, `user` FROM `$FSXL[tableset]_news_submit` ORDER BY `date` DESC");
	if (mysql_num_rows($index) > 0)
	{
		$text .= '
			<div align="left">
				<p>&nbsp;<p><span style="font-size:12pt"><b>'.$FS_PHRASES[news_submit_submittednews].'</b></span><hr>
				<table border="0" cellpadding="2" cellspacing="1" width="100%">
					<tr>
						<td class="alt0"><b>'.$FS_PHRASES[news_add_newstitle].'</b></td>
						<td class="alt0" align="center"><b>'.$FS_PHRASES[news_submit_date].'</b></td>
					</tr>
		';
	
		// Liste
		while ($news = mysql_fetch_assoc($index))
		{
			$i++;
			$text .= '
					<tr>
						<td class="alt'.($i%2==0?1:2).'"><a href="?mod=news&go=addnews&submit='.$news[id].'">'.$news[title].'</a></td>
						<td class="alt'.($i%2==0?1:2).'" align="center">'.date("d.m.Y | H:i", $news[date]).'</td>
					</tr>
			';
		}

		$text .= '
				</table>
			</div>
		';
	}
}

// Jobs anzeigen
if (in_array($_SESSION[user]->userid, $FSXL[superadmin]) || $_SESSION[user]->access[main][jobs])
{
	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_jobs` WHERE `state` < 3 ORDER BY `state` ASC, `date` DESC");
	if (mysql_num_rows($index) > 0)
	{
		$text .= '
			<div align="left">
				<p>&nbsp;<p><span style="font-size:12pt"><b>'.$FS_PHRASES[main_jobs_openjobs].'</b></span><hr>
				<table border="0" cellpadding="2" cellspacing="1" width="100%">
					<tr>
						<td class="alt0"><b>'.$FS_PHRASES[main_style_name].'</b></td>
						<td class="alt0" align="center"><b>'.$FS_PHRASES[main_jobs_created].'</b></td>
						<td class="alt0" align="center"><b>'.$FS_PHRASES[main_jobs_state].'</b></td>
						<td class="alt0" align="center"><b>'.$FS_PHRASES[main_jobs_action].'</b></td>
					</tr>
		';

		// Jobs auslesen
		while ($job = mysql_fetch_assoc($index))
		{
			$i++;
			$index2 = mysql_query("SELECT `name` FROM `$FSXL[tableset]_user` WHERE `id` = $job[autor]");
			$userdat = mysql_fetch_assoc($index2);
		
			switch($job[state])
			{
				case 1:
					$state = $FS_PHRASES[main_jobs_open];
					$action = '<a href="?mod=main&go=jobs&id='.$job[id].'&accept=1">['.$FS_PHRASES[main_jobs_accept].']</a>';
					break;
				case 2:
					$index2 = mysql_query("SELECT `name` FROM `$FSXL[tableset]_user` WHERE `id` = $job[user]");
					$userdat2 = mysql_fetch_assoc($index2);
					$state = $FS_PHRASES[main_jobs_processing].'<br>'.$FS_PHRASES[main_jobs_from].' '.$userdat2[name];
					if ($job[user] == $_SESSION[user]->userid) {
						$action = '<a href="?mod=main&go=jobs&id='.$job[id].'&done=1">['.$FS_PHRASES[main_jobs_close].']</a>';
					}
					else {
						$action = '';
					}
					break;
			}

			$job[name] = preg_replace("/(.*?)\((.*?)\)/i", "$1<br/><span style=\"font-size:7pt;\">($2)</span>", $job[name]);
			$text .= '
					<tr>
						<td class="alt'.($i%2==0?1:2).'">
							<div style="cursor:pointer;" onclick="toggleJob('.$job[id].')">
								<img border="0" src="images/'.$FSXL[style].'_arrow_bottom.gif" alt="" id="jobimg'.$job[id].'" style="margin-bottom:-3px;">
								'.$job[name].'
							</div>
						</td>
						<td class="alt'.($i%2==0?1:2).'" align="center" nowrap>
							'.date("d.m.Y | H:i", $job[date]).'<br>
							'.$FS_PHRASES[main_jobs_from].' '.$userdat[name].'
						</td>
						<td class="alt'.($i%2==0?1:2).'" align="center">'.$state.'</td>
						<td class="alt'.($i%2==0?1:2).'" align="center">
							'.$action.'
						</td>
					</tr>
					<tr>
						<td class="alt'.($i%2==0?1:2).'" colspan="4" style="padding-left:50px;">
							<div  id="job'.$job[id].'" style="display:none;">'.fscode($job[desc]).'</div>
						</td>
					</tr>
			';
		}
	
		$text .= '
				</table>
			</div>
		';
	}
}

// News holen
if (in_array($_SESSION[user]->userid, $FSXL[superadmin]) && $_SESSION[loggedin] && (!$_SESSION[user]->cookielogin || $FSXL[config][admin_cookielogin] == 1))
{
	$text .= '<div align="left">';
	$text .= '<p>&nbsp;<p><span style="font-size:12pt"><b>Frogsystem XL News</b></span><hr>';
	$text .= '<div id="fsxl_news">';
	$text .= '<div style="padding:30px;" align="center"><script type="text/javascript">setTimeout(\'RequestXLNews()\', 250);</script>Loading...</div>';
	$text .= '</div>';
	$text .= '</div>';
}

$tpl = new adminPage();
$tpl->newMsgBox($text);
$FSXL[content] = $tpl->code;

?>
