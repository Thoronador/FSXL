<?php

// Template lesen
$shop_tpl = new template('shopltbox');
$shop_tpl->getItem('article');

$index = mysql_query("SELECT `id`, `url`, `name`, `price` FROM `$FSXL[tableset]_shoplt` WHERE `shortcut` = 1 ORDER BY `name`");
if (mysql_num_rows($index) > 0)
{
	while ($article = mysql_fetch_assoc($index))
	{
		$shop_tpl->newItemNode('article');

		// Thumbnail vorhanden?
		$shop_tpl->switchCondition('thumb', (file_exists('images/shoplt/'.$article[id].'s.jpg') ? true : false), true);

		$shop_tpl->replaceNodeVar('{url}', $article[url]);
		$shop_tpl->replaceNodeVar('{thumb}', 'images/shoplt/'.$article[id].'s.jpg');
		$shop_tpl->replaceNodeVar('{name}', $article[name]);
		$shop_tpl->replaceNodeVar('{price}', $article[price]);
	}
	$shop_tpl->replaceItem('article');

	// Template ausgeben
	$shoplttpl = $shop_tpl->code;
	unset($shop_tpl);
}

// Keine Artikel
else
{
	$shoplttpl = '';
}


?>