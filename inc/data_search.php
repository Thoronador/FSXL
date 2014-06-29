<?php

// IP Sperre
mysql_query("DELETE FROM `$FSXL[tableset]_search_iplist` WHERE `date` <= '$FSXL[time]'");
$ip = $_SERVER[REMOTE_ADDR];
$index = mysql_query("SELECT * FROM `$FSXL[tableset]_search_iplist` WHERE `ip` = '$ip'");
if (mysql_num_rows($index) == 0)
{
	// Keyword säubern und array erzeugen
	$keywords = cleanText($_POST[keywords]);
	$keywords = explode(',', $keywords);

	// Query erzeugen
	$goodwords = array();
	$query = '';
	$i=0;
	foreach ($keywords AS $keyword)
	{
		if (strlen($keyword) > 3)
		{
			$query .= 'word = \''.$keyword.'\' OR ';
			array_push ($goodwords, $keyword);
			$i++;

			// Such Statistik
			$time = time();
			$index = @mysql_query("INSERT INTO `$FSXL[tableset]_search_words` (`word`, `hits`, `date`) VALUES ('$keyword', 1, '$time')");
			if (!$index)
			{
				@mysql_query("UPDATE `$FSXL[tableset]_search_words` SET `hits` = `hits`+1, `date` = '$time' WHERE `word` = '$keyword'");
			}
		}
		if ($i == $FSXL[config][search_maxwords])
		{
			break;
		}
	}
	$query = substr($query, 0, strlen($query)-3);

	// Suche kann beginnen
	if (count($goodwords) > 0)
	{
		// IP Sperre setzen
		@mysql_query("INSERT INTO `$FSXL[tableset]_search_iplist` (`ip`, `date`) VALUES ('$ip', ".($time + $FSXL[config][search_time]).")");

		// Template lesen
		$search_tpl = new template('search');
		$search_tpl->getItem('news');
		$search_tpl->getItem('article');
		$search_tpl->getItem('download');

		//////////
		// News //
		//////////

		$index = mysql_query("SELECT w.id AS wordid, n.id AS id, n.titel AS title, n.text AS text, n.datum AS date
					FROM $FSXL[tableset]_news n, $FSXL[tableset]_newsconnect c, (SELECT id FROM $FSXL[tableset]_wordindex WHERE $query) w
					WHERE c.word = w.id AND n.id = c.article AND n.datum <= '$time'");
		if (mysql_num_rows($index) > 0)
		{
			$result = array();
			while ($news = mysql_fetch_assoc($index))
			{
				if (!$result[$news[id]]) $result[$news[id]] = array();
				array_push($result[$news[id]], $news);
			}
			$newsarray = array();
			foreach($result AS $key => $value)
			{
				if (count($value) == count($goodwords))
				{
					array_push($newsarray, $value[0]);
				}
			}
			unset ($result);
		}

		if (count($newsarray) > 0)
		{
			foreach($newsarray AS $news)
			{
				foreach ($goodwords AS $word)
				{
					$news[text] = cleanText($news[text], false);
					$keylength = round($FSXL[config][search_previewlength]/2);
					$keystring = "/(\s.{0,$keylength}$word.{0,$keylength}\s)/is";
					preg_match($keystring, $news[text], $match);
					$match[1] = str_ireplace ($word, '<b>'.$word.'</b>', $match[1]);
					$result = $match[1] . ' ... ';
				}

				$search_tpl->newItemNode('news');
				$search_tpl->replaceNodeVar('{title}', $news[title]);
				$search_tpl->replaceNodeVar('{url}', '?section=newsdetail&id='.$news[id]);
				$search_tpl->replaceNodeVar('{text}', $result);
				$search_tpl->replaceNodeVar('{date}', date($FSXL[config][dateformat], $news[date]));
			}
			unset ($newsarray);
		}
		$search_tpl->replaceItem('news');
	
	
		/////////////
		// Artikel //
		/////////////
	
		$index = mysql_query("SELECT w.id AS wordid, n.id AS id, n.titel AS title, n.text AS text, n.datum AS date
					FROM $FSXL[tableset]_article n, $FSXL[tableset]_articleconnect c, (SELECT id FROM $FSXL[tableset]_wordindex WHERE $query) w
					WHERE c.word = w.id AND n.id = c.article AND n.datum <= '$time'");
		if (mysql_num_rows($index) > 0)
		{
			$result = array();
			while ($article = mysql_fetch_assoc($index))
			{
				if (!$result[$article[id]]) $result[$article[id]] = array();
				array_push($result[$article[id]], $article);
			}
			$articlearray = array();
			foreach($result AS $key => $value)
			{
				if (count($value) == count($goodwords))
				{
					array_push($articlearray, $value[0]);
				}
			}
			unset ($result);
		}
		
		if (count($articlearray) > 0)
		{
			foreach($articlearray AS $article)
			{
				foreach ($goodwords AS $word)
				{
					$article[text] = cleanText($article[text], false);
					$keylength = round($FSXL[config][search_previewlength]/2);
					$keystring = "/(\s.{0,$keylength}$word.{0,$keylength}\s)/is";
					preg_match($keystring, $article[text], $match);
					$match[1] = str_ireplace ($word, '<b>'.$word.'</b>', $match[1]);
					$result = $match[1] . ' ... ';
				}

				$search_tpl->newItemNode('article');
				$search_tpl->replaceNodeVar('{title}', $article[title]);
				$search_tpl->replaceNodeVar('{url}', '?section=article&id='.$article[id]);
				$search_tpl->replaceNodeVar('{text}', $result);
				$search_tpl->replaceNodeVar('{date}', date($FSXL[config][dateformat], $article[date]));
			}
			unset ($articlearray);
		}
		$search_tpl->replaceItem('article');
	
	
		///////////////
		// Downloads //
		///////////////
	
		$index = mysql_query("SELECT w.id AS wordid, n.id AS id, n.name AS title, n.text AS text, n.date AS date
					FROM $FSXL[tableset]_dl n, $FSXL[tableset]_downloadconnect c, (SELECT id FROM $FSXL[tableset]_wordindex WHERE $query) w
					WHERE c.word = w.id AND n.id = c.article AND n.date <= '$time' AND n.active = 1");
		if (mysql_num_rows($index) > 0)
		{
			$result = array();
			while ($download = mysql_fetch_assoc($index))
			{
				if (!$result[$download[id]]) $result[$download[id]] = array();
				array_push($result[$download[id]], $download);
			}
			$downloadarray = array();
			foreach($result AS $key => $value)
			{
				if (count($value) == count($goodwords))
				{
					array_push($downloadarray, $value[0]);
				}
			}
			unset ($result);
		}
		
		if (count($downloadarray) > 0)
		{
			foreach($downloadarray AS $download)
			{
				foreach ($goodwords AS $word)
				{
					$download[text] = cleanText($download[text], false);
					$keylength = round($FSXL[config][search_previewlength]/2);
					$keystring = "/(\s.{0,$keylength}$word.{0,$keylength}\s)/is";
					preg_match($keystring, $download[text], $match);
					$match[1] = str_ireplace ($word, '<b>'.$word.'</b>', $match[1]);
					$result = $match[1] . ' ... ';
				}

				$search_tpl->newItemNode('download');
				$search_tpl->replaceNodeVar('{title}', $download[title]);
				$search_tpl->replaceNodeVar('{url}', '?section=download&id='.$download[id]);
				$search_tpl->replaceNodeVar('{text}', $result);
				$search_tpl->replaceNodeVar('{date}', date($FSXL[config][dateformat], $download[date]));
			}
			unset ($downloadarray);
		}
		$search_tpl->replaceItem('download');

		// Template ausgeben
		$search_tpl->replaceTplVar('{searchstring}', implode(', ', $goodwords));
		$FSXL[template] .= $search_tpl->code;
		unset($search_tpl);
	}

	// Keine gültigen Keywords
	else
	{
		$FSXL[template] .= errorMsg('errorsearch');
	}
}

// Zu schnelle Suchanfragen
else
{
	// Template lesen
	$msg_tpl = new template('errorsearchtime');
	$frame_tpl = new template('errormsg');
	$frame_tpl->replaceTplVar('{message}', $msg_tpl->code);
	$frame_tpl->replaceTplVar('{seconds}', $FSXL[config][search_time]);

	// Template ausgeben
	$FSXL[template] .= $frame_tpl->code;
	unset($frame_tpl, $msg_tpl);
}

?>