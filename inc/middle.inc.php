<?php

rewriteMap();

$doarticle = false;

// GET Seite
if ($_GET[section] || $_GET[go])
{
	if ($_GET[go]) $_GET[section] = $_GET[go];
	$FSXL[section] = $_GET[section];

	// Datei einbinden
	if (file_exists('inc/data_'.$_GET[section].'.php'))
	{
		include('data_'.$_GET[section].'.php');
	}
	// Artikel einbinden
	else {
		$doarticle = true;
	}
}
// Defaultseite
else
{
	// Zonen Startseite einbinden
	if ($FSXL[zones][$_SESSION[zone]][page])
	{
		$FSXL[section] = $FSXL[zones][$_SESSION[zone]][page];
		
		// Datei einbinden
		if (file_exists('inc/data_'.$FSXL[zones][$_SESSION[zone]][page].'.php')) {
			$FSXL[config][startpage] = $FSXL[zones][$_SESSION[zone]][page];
		}
		// Artikel einbinden
		else {
			$_GET[section] = $FSXL[zones][$_SESSION[zone]][page];
			$doarticle = true;
		}
	}
	// Default Startseite
	elseif (!$FSXL[config][startpage]) {
		$FSXL[config][startpage] = 'news';
		$FSXL[section] = $FSXL[config][startpage];
	}
	
	if ($doarticle == false) {
		include('data_'.$FSXL[config][startpage].'.php');
	}
}

// Artikel einbinden
if ($doarticle == true)
{
	$index = mysql_query("SELECT `id` FROM `$FSXL[tableset]_article` WHERE `short` = '$_GET[section]'");
	if (mysql_num_rows($index) > 0)
	{
		$article = mysql_fetch_assoc($index);
		$_GET[id] = $article[id];
		include('data_article.php');
	}
	// Artikel nicht gefunden
	else
	{
		$FSXL[template] .= errorMsg('errorfilenotfound');
	}
	$doarticle = false;
}



?>