<?php

// Einzelne Kategorie
if ($_GET[cat])
{
	settype($_GET[cat],'integer');
	$sqladd = "WHERE `id` = '$_GET[cat]'";
}

// Template lesen
$shop_tpl = new template('shopltlist');
$shop_tpl->getItem('cat');
$shop_tpl->getItem('article');

// Daten lesen
$index = mysql_query("SELECT * FROM `$FSXL[tableset]_shoplt_cat` $sqladd ORDER BY `name`");
if (mysql_num_rows($index) > 0)
{
	while ($cat = mysql_fetch_assoc($index))
	{
		$i++;
		$shop_tpl->newItemNode('cat');
		$shop_tpl->replaceNodeVar('{altnum}', $i%2==0?1:2);
		$shop_tpl->replaceNodeVar('{catname}', $cat[name]);
		$shop_tpl->replaceNodeVar('{catdescription}', fscode($cat[text]));
		$shop_tpl->replaceNodeVar('{caturl}', '?section=shoplt&cat='.$cat[id]);
		$shop_tpl->switchCondition('cat', $cat[id]!=0?true:false, true);

		$catname = $cat[name];
	}
}
$shop_tpl->replaceItem('cat');

// Variablen ersetzen
$shop_tpl->replaceTplVar('{catname}', $catname);

// Kategorie
if (!$_GET[cat]) $_GET[cat] = 0;
$order = $FSXL[config][shoplt_order] == 1 ? "`name`" : "`id` DESC";
$index = mysql_query("SELECT * FROM `$FSXL[tableset]_shoplt` WHERE `cat` = '$_GET[cat]' ORDER BY $order");
if (mysql_num_rows($index) > 0)
{
	while ($article = mysql_fetch_assoc($index))
	{
		$i++;
		$shop_tpl->newItemNode('article');

		// Thumbnail vorhanden ?
		if (file_exists('images/shoplt/'.$article[id].'s.jpg')) $isthumb = true;
		else $isthumb = false;
		$shop_tpl->switchCondition('thumb', $isthumb, true);

		$shop_tpl->replaceNodeVar('{altnum}', $i%2==0?1:2);
		$shop_tpl->replaceNodeVar('{name}', $article[name]);
		$shop_tpl->replaceNodeVar('{text}', fscode($article[text]));
		$shop_tpl->replaceNodeVar('{thumb}', 'images/shoplt/'.$article[id].'s.jpg');
		$shop_tpl->replaceNodeVar('{img}', 'images/shoplt/'.$article[id].'.jpg');
		$shop_tpl->replaceNodeVar('{price}', $article[price]);
		$shop_tpl->replaceNodeVar('{url}', $article[url]);
	}
}
$shop_tpl->replaceItem('article');

// Template ausgeben
$FSXL[template] .= $shop_tpl->code;
unset($shop_tpl);

?>