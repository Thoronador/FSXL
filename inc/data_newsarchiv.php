<?php

// Newsarchiv Rewrite
if ($_GET[year] && $_GET[month] && !$_GET[mr] && $FSXL[config][use_safe_links] == 1)
{
	reloadPage('newsarchiv_'.$_GET[month].'_'.$_GET[year].'.htm');
}

// Template lesen
$archiv_tpl = new template('newsarchiv');

settype($_GET[year], 'integer');
settype($_GET[month], 'integer');

// Select Options für Jahre
$index = mysql_query("SELECT `datum` FROM `$FSXL[tableset]_news` ORDER BY `datum` LIMIT 1");
$news = mysql_fetch_assoc($index);
$startyear = date("Y", $news[datum]);
$endyear = date("Y");
$yearoptions = '';
for ($startyear; $startyear<$endyear+1; $startyear++)
{
	$yearoptions .= '<option value="'.$startyear.'" '.($_GET[year] == $startyear ? "selected" : "").'>'.$startyear.'</option>';
}

$archiv_tpl->replaceTplVar('{yearoptions}', $yearoptions);
$archiv_tpl->replaceTplVar('value="'.$_GET[month].'"', 'value="'.$_GET[month].'" selected');


// News anzeigen
if ($_GET[month] && $_GET[year])
{
	// Query erzeugen
	$startdate = mktime(0, 0, 0, $_GET[month], 1, $_GET[year]);
	if ($startdate > time()) $startdate = $FSXL[time];
	$enddate = mktime(0, 0, 0, $_GET[month]+1, 1, $_GET[year]);
	if ($enddate > time()) $enddate = $FSXL[time];

	// Pages
	$index = mysql_query("SELECT COUNT(`id`) AS `value` FROM `$FSXL[tableset]_news` WHERE `datum` > '$startdate' AND `datum` < '$enddate'");
	if (mysql_num_rows($index) > 0) {
		$rows = mysql_result($index, 0, 'value');
		$pages = ceil($rows/$FSXL[config][news_perpage]);
	} else {
		$pages = 1;
	}

	if (!$_GET[page]) {
		$_GET[page] = 1;
	}
	$start = ($_GET[page]-1) * $FSXL[config][news_perpage];

	// Pages		
	$archiv_tpl->getItem('page');
	for ($i=1; $i<=$pages; $i++)
	{
		$archiv_tpl->newItemNode('page');
		$archiv_tpl->replaceNodeVar('{pagenum}', $i);
		$archiv_tpl->replaceNodeVar('{pagelink}', '?section=newsarchiv&month='.$_GET[month].'&year='.$_GET[year].'&page='.$i);
		$archiv_tpl->switchCondition('currentpage', ($_GET[page]==$i?true:false), true);
	}
	$archiv_tpl->replaceItem('page');

	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_news` WHERE `datum` > '$startdate' AND `datum` < '$enddate'
							ORDER BY `datum` DESC LIMIT $start, ".$FSXL[config][news_perpage]);

	if (mysql_num_rows($index) > 0)
	{
		// Template lesen
		$news_tpl = new template('news_body');
		$news_tpl->getItem('link');

		while ($news = mysql_fetch_assoc($index))
		{
			// Template Kopie erzeugen
			$news_tpl->newListItem();

			// Links auslesen
			$index2 = mysql_query("SELECT * FROM `$FSXL[tableset]_news_links` WHERE `newsid` = '$news[id]'");
			if (mysql_num_rows($index2) > 0)
			{
				while ($link = mysql_fetch_assoc($index2))
				{
					$news_tpl->newItemNode('link');
					$news_tpl->replaceNodeVar('{linkname}', $link[name]);
					$news_tpl->replaceNodeVar('{linkurl}', $link[url]);
					$news_tpl->replaceNodeVar('{linktarget}', ($link[type] == 1 ? '_blank' : '_self'));
				}
				$news_tpl->replaceListItem('link');
				$links = true;
			}
			else
			{
				$links = false;
			}
	
			// Formatierung
			if ($news[type] == 1)
			{
				$news[text] = fscode($news[text]);
			}

			// Kategorie Lesen
			if (!$FSXL[news_cat][$news[catid]])
			{
				$index2 = mysql_query("SELECT `name` FROM `$FSXL[tableset]_news_cat` WHERE `id` = '$news[catid]'");
				$cat = mysql_fetch_assoc($index2);
				$FSXL[news_cat][$news[catid]] = $cat[name];
			}
			else
			{
				$cat[name] = $FSXL[news_cat][$news[catid]];
			}

			// User Lesen
			if (!$FSXL[user_name][$news[autor]])
			{
				$index2 = mysql_query("SELECT `name` FROM `$FSXL[tableset]_user` WHERE `id` = '$news[autor]'");
				$userdata = mysql_fetch_assoc($index2);
				$FSXL[user_name][$news[autor]] = $userdata[name];
			}
			else
			{
				$userdata[name] = $FSXL[user_name][$news[autor]];
			}

			// Statische Variablen ersetzen
			$cat[name] = preg_replace("/([a-zA-Z0-9]*?)_(.*)/i", "$2", $cat[name]);
			$news_tpl->replaceListVar('{catname}', $cat[name]);
			$news_tpl->replaceListVar('{catid}', $news[catid]);
			$news_tpl->replaceListVar('{title}', $news[titel]);
			$news_tpl->replaceListVar('{text}', $news[text]);
			$news_tpl->replaceListVar('{date}', date($FSXL[config][dateformat], $news[datum]));
			$news_tpl->replaceListVar('{username}', $userdata[name]);
			$news_tpl->replaceListVar('{comments}', $news[numcomments]);

			// Komentare anzeigen oder nicht
			$news_tpl->switchListCondition('comments', ($news[comments] == 1 ? true : false));
			$news_tpl->switchListCondition('vB', ($news[vbnews] == 1 ? true : false));
			if ($news[vbnews] == 1)	{
				$news_tpl->replaceListVar('{commentlink}', $FSXL[config][vb_url].'showthread.php?p='.$news[postid].'#post'.$news[postid]);
			}
			else {
				$news_tpl->replaceListVar('{commentlink}', '?section=newsdetail&id=' . $news[id]);
			}

			// Links anzeigen oder nicht
			$news_tpl->switchListCondition('links', $links);
		}
		$news_tpl->collapseList();
	}
}
else
{
	$archiv_tpl->code = preg_replace("/<-- page -->(.*?)<-- \/page -->/is", "", $archiv_tpl->code);
}

// Template ausgeben
$archiv_tpl->replaceTplVar('{news}', $news_tpl->code);
unset($news_tpl);
$FSXL[template] .= $archiv_tpl->code;
unset($archiv_tpl);

?>