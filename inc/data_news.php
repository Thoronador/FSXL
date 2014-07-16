<?php

// News IDs auslesen
$index = mysql_query("SELECT `newsid` FROM `$FSXL[tableset]_newstozone`
						WHERE `zoneid` = ".$FSXL[zone][id]." AND `date` <= '$FSXL[time]'
						GROUP BY `newsid` ORDER BY `date` DESC LIMIT ".$FSXL[config][news_perpage]);
$query = "SELECT * FROM `$FSXL[tableset]_news` WHERE ";
while ($connect = mysql_fetch_assoc($index)) {
	$query .= "`id` = '$connect[newsid]' OR ";
}
$query = substr($query, 0, -3) . ' ORDER BY `datum` DESC';

// Daten lesen
$index = @mysql_query($query);

// News vorhanden
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

		// Shortlink einfügen
		$tmpcode = array_pop($news_tpl->list);
		$tmpcode = '<a href="" name="'.$news[id].'"></a>' . $tmpcode;
		array_push($news_tpl->list, $tmpcode);
	}

	// Template ausgeben
	$news_tpl->collapseList();
	$FSXL[template] .= $news_tpl->code;
	unset($news_tpl);
}


?>