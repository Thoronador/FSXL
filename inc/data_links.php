<?php

// Kategorie
if ($_GET[cat])
{
	settype($_GET[cat], 'integer');

	// Template lesen
	$link_tpl = new template('link_cat');
	$link_tpl->getItem('subcat');
	$link_tpl->clearItem('subcat');
	$link_tpl->getItem('link');

	// Unterkategorie
	if ($_GET[sub]) {
		settype($_GET[sub], 'integer');
		$sqladd = 'AND `subcat` = '.$_GET[sub];
	}
	
	// Daten lesen
	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_link` WHERE `cat` = '$_GET[cat]' $sqladd ORDER BY `date` DESC");
	if (mysql_num_rows($index) > 0)
	{
		$currentcat = '';
		while ($link = mysql_fetch_assoc($index))
		{
			$i++;
			// Neue Kategorie
			if ($currentcat != $link[subcat])
			{
				$currentcat = $link[subcat];
				// Hat eine Kategorie
				if ($currentcat != 0)
				{
					$index2 = mysql_query("SELECT * FROM `$FSXL[tableset]_link_subcat` WHERE `id` = '$link[subcat]'");
					$cat = @mysql_fetch_assoc($index2);
				}

				$link_tpl->newItemNode('link', 'subcat');
				$link_tpl->replaceNodeVar('{altnum}', $i%2==0?1:2);
				$link_tpl->replaceNodeVar('{subcatname}', $cat[name]);
				$link_tpl->replaceNodeVar('{subcaturl}', '?section=links&cat='.$link[cat].'&sub='.$cat[id]);
				$link_tpl->switchCondition('subcat', $link[subcat]==0?false:true, true);
				$i++;
			}
			
			// Links
			$link_tpl->newItemNode('link');
			$link_tpl->replaceNodeVar('{altnum}', $i%2==0?1:2);
			$link_tpl->replaceNodeVar('{name}', $link[name]);
			$link_tpl->replaceNodeVar('{date}', date($FSXL[config][dateformat], $link[date]));
			$link_tpl->replaceNodeVar('{description}', fscode($link[text]));
			$link_tpl->replaceNodeVar('{url}', $link[url]);
			$link_tpl->switchCondition('url', $link[url]?true:false, true);
		}
		$link_tpl->replaceItem('link');
	}
	else
	{
		$link_tpl->replaceItem('link');
	}

	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_link_cat` WHERE `id` = '$_GET[cat]'");
	$cat = mysql_fetch_assoc($index);

	$link_tpl->replaceTplVar('{catdescription}', fscode($cat[text]));
	$link_tpl->replaceTplVar('{catname}', $cat[name]);

	// Template ausgeben
	$FSXL[template] .= $link_tpl->code;
	unset($link_tpl);
}

// Link Übersicht
else
{
	// Template lesen
	$link_tpl = new template('link_list');
	$link_tpl->getItem('cat');

	// Daten lesen
	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_link_cat` ORDER BY `name`");
	if (mysql_num_rows($index) > 0)
	{
		while ($cat = mysql_fetch_assoc($index))
		{
			$i++;
			$link_tpl->newItemNode('cat');
			$link_tpl->replaceNodeVar('{altnum}', $i%2==0?1:2);
			$link_tpl->replaceNodeVar('{name}', $cat[name]);
			$link_tpl->replaceNodeVar('{description}', fscode($cat[text]));
			$link_tpl->replaceNodeVar('{url}', '?section=links&cat='.$cat[id]);
		}
		$link_tpl->replaceItem('cat');

		// Template ausgeben
		$FSXL[template] .= $link_tpl->code;
		unset($link_tpl);
	}

	// Keine Kategorien gefunden
	else
	{
		$FSXL[template] .= errorMsg('errorfilenotfound');
	}
}

?>