<?php

// Artikel Detailansicht
if ($_GET[id])
{
	settype($_GET[id], 'integer');

	// Admin
	if ($_SESSION[user]->isadmin)
	{
		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_article` WHERE `id` = '$_GET[id]'");
	}
	// User
	else
	{
		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_article` WHERE `id` = '$_GET[id]' AND `datum` <= '$FSXL[time]'");
	}

	if (mysql_num_rows($index) > 0)
	{
		$article = mysql_fetch_assoc($index);

		// Nur für Benutzer und nicht eingelogt
		if ($article[regonly] == 1 && !$_SESSION[loggedin])
		{
			$FSXL[template] .= errorMsg('errorregonly');
		}

		// Artikel anzeigen
		else
		{
			// Pagetitle
			$FSXL[pgtitle] = $article[titel];

			// Template lesen
			$article_tpl = new template('article');
			$article_tpl->getItem('pagelink');

			// Artikel ist in einer Kategorie
			if ($article[cat] != 0)
			{
				$index = mysql_query("SELECT `id`, `name` FROM `$FSXL[tableset]_article_cat` WHERE `id` = '$article[cat]'");
				$cat = mysql_fetch_assoc($index);
				$article_tpl->replaceTplVar('{catname}', $cat[name]);
				$article_tpl->replaceTplVar('{caturl}', '?section=article&cat='.$cat[id]);
				$hascat = true;
			}
			$article_tpl->switchCondition('has_cat', $hascat);
			$article_tpl->switchCondition('show_user', ($article[showuser] == 1 ? true : false));

			// usernamen lesen
			$index = mysql_query("SELECT `name` FROM `$FSXL[tableset]_user` WHERE `id` = '$article[autor]'");
			$userdata = mysql_fetch_assoc($index);
			
			// Seiten
			if ($article[pages] > 1)
			{
				$pages = true;

				$article_tpl->newItemNode('pagelink');
				$article_tpl->replaceNodeVar('{pagelink}', 'index.php?section=article&id='.$article[id]);
				$article_tpl->replaceNodeVar('{pagenum}', '1');

				$i=2;
				$index = mysql_query("SELECT `id` FROM `$FSXL[tableset]_article_pages` WHERE `article` = '$article[id]' ORDER BY `id`");
				while ($page = mysql_fetch_assoc($index))
				{
					$article_tpl->newItemNode('pagelink');
					$article_tpl->replaceNodeVar('{pagelink}', 'index.php?section=article&id='.$article[id].'&page='.$i);
					$article_tpl->replaceNodeVar('{pagenum}', $i);
					$i++;
				}
				$article_tpl->replaceItem('pagelink');
				
				if ($_GET[page])
				{
					settype($_GET[page], 'integer');
					$index = mysql_query("SELECT * FROM `$FSXL[tableset]_article_pages` WHERE `article` = '$article[id]' ORDER BY `id` LIMIT ".($_GET[page]-2).", 1");
					if (mysql_num_rows($index) > 0)
					{
						$page = mysql_fetch_assoc($index);
						$index = mysql_query("SELECT COUNT(`id`) AS `num` FROM `$FSXL[tableset]_article_pages` WHERE `id` <= '$page[id]' AND `article` = '$article[id]'");
						$pagenum = mysql_result($index, 0, 'num')+1;
					}
					$article[text] = $page[text];
				}
				else
				{
					$pagenum = 1;
				}
			}
			else
			{
				$pages = false;
			}
			$article_tpl->switchCondition('pages', $pages);


			// Formatierung
			if ($article[type] == 1)
			{
				$article[text] = fscode($article[text]);
			}

			// Artikel Index Liste
			if (stristr($article[text], '{index}'))
			{
				$index_tpl = new template('articleheader');
				$index_tpl->getItem('article');

				$index = mysql_query("SELECT `id`, `titel`, `datum` FROM `$FSXL[tableset]_article` WHERE `cat` = '$article[cat]' AND `datum` <= '$FSXL[time]' ORDER BY `titel`");
				while ($artindex = mysql_fetch_assoc($index))
				{
					$index_tpl->newItemNode('article');
					$index_tpl->replaceNodeVar('{name}', $artindex[titel]);
					$index_tpl->replaceNodeVar('{date}', date($FSXL[config][dateformat], $artindex[datum]));
					$index_tpl->replaceNodeVar('{url}', '?section=article&id='.$artindex[id]);
				}
				$index_tpl->replaceItem('article');
				$article[text] = str_replace('{index}', $index_tpl->code, $article[text]);
				unset($index_tpl);
			}

			// Statische Variablen ersetzen
			$article_tpl->replaceTplVar('{currentpage}', $pagenum);
			$article_tpl->replaceTplVar('{title}', $article[titel]);
			$article_tpl->replaceTplVar('{text}', $article[text]);
			$article_tpl->replaceTplVar('{date}', date($FSXL[config][dateformat], $article[datum]));
			$article_tpl->replaceTplVar('{username}', $userdata[name]);

			// Template ausgeben
			$FSXL[template] .= $article_tpl->code;
			unset($article_tpl);
		}
	}

	// Artikel nicht gefunden oder noch nicht freigegeben
	else
	{
		$FSXL[template] .= errorMsg('errorfilenotfound');
	}
}

// Artikel Übersicht
else
{
	// Übersicht verbergen
	if ($FSXL[config][article_showall] == 0 && !$_GET[cat])
	{
		$FSXL[template] .= errorMsg('errorfilenotfound');
	}
	else
	{
		$sqladd = '';
		// Einzelne Kategorie
		if ($_GET[cat])
		{
			settype($_GET[cat],'integer');
			$sqladd .= " AND `cat` = $_GET[cat]";
		}

		// Template lesen
		$article_tpl = new template('articlelist');
		$article_tpl->getItem('cat');
		$article_tpl->clearItem('cat');
		$article_tpl->getItem('article');

		// Daten lesen
		if (!$_SESSION[loggedin] && $FSXL[config][showregonly] != 1)
		{
			$sqladd .= ' AND `regonly` = 0';
		}
		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_article` WHERE `datum` <= '$FSXL[time]' $sqladd AND `invisible` = 0 ORDER BY `cat`, `datum` DESC");

		// Artikel vorhanden
		if (mysql_num_rows($index) > 0)
		{
			$currentcat = '';

			// Template füllen
			while ($article = mysql_fetch_assoc($index))
			{
				$i++;
				// Neue Kategorie
				if ($currentcat != $article[cat])
				{
					$currentcat = $article[cat];
					if ($currentcat != 0)
					{
						$index2 = mysql_query("SELECT * FROM `$FSXL[tableset]_article_cat` WHERE `id` = '$article[cat]'");
						$cat = mysql_fetch_assoc($index2);
					}				
					$article_tpl->newItemNode('article', 'cat');
					$article_tpl->replaceNodeVar('{altnum}', $i%2==0?1:2);
					$article_tpl->replaceNodeVar('{catname}', $cat[name]);
					$article_tpl->replaceNodeVar('{caturl}', '?section=article&cat='.$cat[id]);
					$article_tpl->replaceNodeVar('{catdescription}', $cat[text]);
					$article_tpl->switchCondition('cat', $article[cat]==0?false:true, true);
					$i++;
				}

				// Artikel
				$article_tpl->newItemNode('article');
				$article_tpl->replaceNodeVar('{altnum}', $i%2==0?1:2);
				$article_tpl->replaceNodeVar('{article}', $article[titel]);
				$article_tpl->replaceNodeVar('{articleurl}', '?section=article&id='.$article[id]);
				$article_tpl->replaceNodeVar('{date}', date($FSXL[config][dateformat], $article[datum]));

				$article[text] = cleanText($article[text], false);
				$article[text] = preg_replace("/(.{10,".$FSXL[config][article_previewlength]."})(\s|\.|$)(.*)/is", "$1...", $article[text]);
				$article_tpl->replaceNodeVar('{preview}', $article[text]);
			}
			if ($_GET[cat])
			{
				$article_tpl->replaceTplVar('{catname}', $cat[name]);
			}
			$article_tpl->replaceItem('article');

			// Template ausgeben
			$FSXL[template] .= $article_tpl->code;
			unset($article_tpl);
		}

		// Keine Artikel vorhanden
		else
		{
			$FSXL[template] .= errorMsg('errorfilenotfound');
		}
	}
}

?>