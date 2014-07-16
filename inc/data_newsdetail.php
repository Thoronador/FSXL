<?php

// Kommetar eintragen
if ($_POST[text])
{
	$_SESSION[commenttext] = $_POST[text];

	// Alte Spamblocker löschen
	$deltime = $FSXL[time] - $FSXL[config][news_spamtime];
	mysql_query("DELETE FROM `$FSXL[tableset]_news_spamblock` WHERE `date` <= '$deltime'");

	// Spamblocker
	$ip = $_SERVER[REMOTE_ADDR];
	$index = mysql_query("SELECT `ip` FROM `$FSXL[tableset]_news_spamblock` WHERE `ip` = '$ip'");
	if ((mysql_num_rows($index) == 0) || $_SESSION[user]->isadmin)
	{
		// Bot Verdacht
		if (($_POST[formdate] > $FSXL[time]-6) || $_POST[email])
		{
			$FSXL[template] .= errorMsg('errorbotdetect');
			$_SESSION[commenttext] = '';
		}

		// Kommentar eintragen
		else
		{
			settype($_POST[newsid], 'integer');
			$userid = $_SESSION[user]->userid ? $_SESSION[user]->userid : 0;

			// Kommentarnummer
			$index = mysql_query("SELECT `num` FROM `$FSXL[tableset]_news_comments` WHERE newsid = '$_POST[newsid]' ORDER BY `num` DESC LIMIT 1");
			if (mysql_num_rows($index) == 0)
			{
				$num = 1;
			}
			else
			{
				$num = mysql_result($index, 0 , 'num') + 1;
			}

			$_POST[text] = preg_replace("/\\*/is", "\\", $_POST[text]);
			mysql_query("INSERT INTO `$FSXL[tableset]_news_comments` (`id`, `newsid`, `userid`, `datum`, `text`, `num`)
					VALUES(NULL, '$_POST[newsid]', '$userid', '$FSXL[time]', '$_POST[text]', '$num')");

			// Spamblock einfügen
			mysql_query("INSERT INTO `$FSXL[tableset]_news_spamblock` (`ip`, `date`) VALUES ('$ip', '$date')");

			// News Kommentare erhöhen
			mysql_query("UPDATE `$FSXL[tableset]_news` SET `numcomments` = `numcomments` + 1 WHERE `id` = '$_POST[newsid]'");

			// Variablen löschen
			unset($_SESSION[commenttext]);

			// Template lesen und ausgeben
			$comment_tpl = new template('comment_done');
			$FSXL[template] .= $comment_tpl->code;
			unset($comment_tpl);
		}
	}

	// Zu schnell gepostet
	else
	{
			$msg_tpl = new template('errorspamblock');
			$frame_tpl = new template('errormsg');
			$frame_tpl->replaceTplvar('{message}', $msg_tpl->code);
			$frame_tpl->replaceTplvar('{seconds}', $FSXL[config][news_spamtime]);

			$FSXL[template] .= $frame_tpl->code;
			unset($frame_tpl, $msg_tpl);
	}
}

// News und Kommentare anzeigen
else
{
	if ($_POST[newsid]) $_GET[id] = $_POST[newsid];
	settype($_GET[id], 'integer');

	// Admin
	if ($_SESSION[user]->isadmin)
	{
		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_news` WHERE `id` = '$_GET[id]'");
	}
	// User
	else
	{
		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_news` WHERE `id` = '$_GET[id]' AND `datum` <= '$FSXL[time]'");
	}

	if (mysql_num_rows($index) > 0)
	{
		$news = mysql_fetch_assoc($index);

		// Pagetitle
		$FSXL[pgtitle] = $news[titel];

		//Templates lesen
		$news_tpl = new template('news_body');
		$site_tpl = new template('news_detail');
		$news_tpl->getItem('link');

		// Links auslesen
		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_news_links` WHERE `newsid` = '$news[id]'");
		if (mysql_num_rows($index) > 0)
		{
			$links = true;
			while ($link = mysql_fetch_assoc($index))
			{
				$news_tpl->newItemNode('link');
				$news_tpl->replaceNodeVar('{linkname}', $link[name]);
				$news_tpl->replaceNodeVar('{linkurl}', $link[url]);
				$news_tpl->replaceNodeVar('{linktarget}', ($link[type] == 1 ? '_blank' : '_self'));
			}
			$news_tpl->replaceItem('link');
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
		$index2 = mysql_query("SELECT `name` FROM `$FSXL[tableset]_news_cat` WHERE `id` = '$news[catid]'");
		$cat = mysql_fetch_assoc($index2);

		// User Lesen
		$index2 = mysql_query("SELECT `name` FROM `$FSXL[tableset]_user` WHERE `id` = '$news[autor]'");
		$userdata = mysql_fetch_assoc($index2);

		// Statische Variablen ersetzen
		$cat[name] = preg_replace("/([a-zA-Z0-9]*?)_(.*)/i", "$2", $cat[name]);
		$news_tpl->replaceTplVar('{catname}', $cat[name]);
		$news_tpl->replaceTplVar('{catid}', $news[catid]);
		$news_tpl->replaceTplVar('{title}', $news[titel]);
		$news_tpl->replaceTplVar('{text}', $news[text]);
		$news_tpl->replaceTplVar('{date}', date($FSXL[config][dateformat], $news[datum]));
		$news_tpl->replaceTplVar('{username}', $userdata[name]);
		$news_tpl->replaceTplVar('{commentlink}', '');

		// Bedingungen
		$news_tpl->switchCondition('comments', false);
		$news_tpl->switchCondition('links', $links);


		// Kommentare anzeigen
		if ($news[comments] == 1 && !$news[vbnews])
		{
			// Kommentare aktivieren
			$site_tpl->switchCondition('comments', true);

			// Editor Variablen ersetzen
			$site_tpl->replaceTplVar('{newsid}', $news[id]);
			$site_tpl->replaceTplVar('{text}', $_SESSION[commenttext]);

			// Gastkommentare erlauben
			$postpermission = false;
			if (($FSXL[config][news_guestcomments] == 1) || ($_SESSION[user]->username))
			{
				$postpermission = true;
			}
			$site_tpl->switchCondition('postpermission', $postpermission);

			// Username einsetzen
			if ($_SESSION[loggedin])
			{
				$site_tpl->replaceTplVar('{username}', $_SESSION[user]->username);
				$site_tpl->switchCondition('user', true);
			}
			else
			{
				$site_tpl->switchCondition('user', false);
			}

			// Seitenazeige erzeugen
			if (!$_GET[page]) $_GET[page] = 1;
			if ($news[numcomments] > $FSXL[config][news_commentsperpage])
			{
				$pages = ceil($news[numcomments] / $FSXL[config][news_commentsperpage]);
				$pageselect = '';

				for ($i=1; $i<$pages+1; $i++)
				{
					if ($_GET[page] == $i)
					{
						$pageselect .= '['.$i.'] ';
					}
					else
					{
						$pageselect .= '<a href="?section=newsdetail&id='.$_GET[id].'&page='.$i.'"><b>['.$i.']</b></a> ';
					}
				}
			}

			// Kommentar Template lesen
			$site_tpl->getItem('commentbody');

			// Kommentare auslesen
			$limit = $FSXL[config][news_commentsperpage];
			$start = ($_GET[page]-1) * $limit;
			$order = $FSXL[config][news_comment_order] == 1 ? 'DESC' : 'ASC';
			$index = mysql_query("SELECT * FROM `$FSXL[tableset]_news_comments` WHERE `newsid` = '$news[id]' ORDER BY `datum` $order LIMIT $start, $limit");
			if (mysql_num_rows($index) > 0)
			{
				while ($comment = mysql_fetch_assoc($index))
				{
					$site_tpl->newItemNode('commentbody');

					// Wortfilter
					$comment[text] = replaceSpam($comment[text], $FSXL[config][news_spamfilter]);
					$comment[text] = fscode($comment[text], true);
					$comment[text] = preg_replace("/\[§(.*?)§\]/is", "$1", $comment[text]);
					$comment[text] = preg_replace("/\<--(.*?)-->/is", "", $comment[text]);

					// Username lesen
					if ($comment[userid] != 0)
					{
						$index2 = mysql_query("SELECT `name` FROM `$FSXL[tableset]_user` WHERE `id` = '$comment[userid]'");
						$username = mysql_result($index2, 0, 'name');
						$site_tpl->replaceNodeVar('{commentautor}', $username);
						$isuser = true;
					}
					else
					{
						$isuser = false;
					}

					$site_tpl->replaceNodeVar('{commentnum}', $comment[num]);
					$site_tpl->replaceNodeVar('{commenttext}', $comment[text]);
					$site_tpl->replaceNodeVar('{commentdate}', date($FSXL[config][dateformat], $comment[datum]));
					$site_tpl->switchCondition('commentuser', $isuser, true);
				}
			}
			$site_tpl->replaceItem('commentbody');
		}

		// Keine Kommentare aktiviert
		else
		{
			$site_tpl->switchCondition('comments', false);
		}

		// Smilies
		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_smilies`");
		$smiliecode = '<table border="0" cellpadding="2" cellspacing="0" width="100%">';
		$cols = 1;
		while ($smilie = mysql_fetch_assoc($index))
		{
			if ($cols == 1) $smiliecode .= '<tr>';
			$smiliecode .= '<td align="center"><img border="0" src="images/smilies/'.$smilie[id].'.gif" alt="" onClick="insertSmilie(\''.$smilie[code].'\')"></td>';
			$cols++;
			if ($cols == 4) { $smiliecode .= '</tr>'; $cols = 1; }
		}
		$smiliecode .= '</table>';

		// Variablen ersetzen
		$site_tpl->replaceTplVar('{smilies}', $smiliecode);
		$site_tpl->replaceTplVar('{time}', $FSXL[time]);
		$site_tpl->replaceTplVar('{news}', $news_tpl->code);
		$site_tpl->replaceTplVar('{pageselect}', $pageselect);

		// Template ausgeben
		$FSXL[template] .= $site_tpl->code;
		unset($smiliecode, $news_tpl, $site_tpl);
	}

	// News nicht gefunden oder nicht freigegeben
	else
	{
		$FSXL[template] .= errorMsg('errorfilenotfound');
	}
}


?>