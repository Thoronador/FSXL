<?php

// Template lesen
$header_tpl = new template('header');

// Login Formular
$login_tpl = new template('loginform');
$header_tpl->replaceTplVar('{loginform}', $login_tpl->code);
unset($login_tpl);

// Such Formular
$search_tpl = new template('searchform');
$header_tpl->replaceTplVar('{search}', $search_tpl->code);
unset($search_tpl);

// CSS
$header_tpl->replaceTplVar('{css}', '/css.php?style='.$FSXL[style]);

// POTM
if ($header_tpl->match('{potm}'))
{
	include('inc/potm.php');
	$header_tpl->replaceTplVar('{potm}', $potmtpl);
	unset($potmtpl);
}
// POLL
if ($header_tpl->match('{poll}'))
{
	include('inc/pollbox.php');
	$header_tpl->replaceTplVar('{poll}', $polltpl);
	unset($polltpl);
}
// Stat
if ($header_tpl->match('{stat}'))
{
	include('inc/statbox.php');
	$header_tpl->replaceTplVar('{stat}', $stattpl);
	unset($stattpl);
}
// Tag Cloud
if ($header_tpl->match('{tagcloud}'))
{
	include('inc/tagcloudbox.php');
	$header_tpl->replaceTplVar('{tagcloud}', $tagcloudtpl);
	unset($tagcloudtpl);
}
// Shoplt
if ($header_tpl->match('{shoplt}'))
{
	include('inc/shopltbox.php');
	$header_tpl->replaceTplVar('{shoplt}', $shoplttpl);
	unset($shoplttpl);
}
// Ticker
if ($header_tpl->match('{ticker}'))
{
	include('inc/tickerbox.php');
	$header_tpl->replaceTplVar('{ticker}', $tickertpl);
	unset($tickertpl);
}

// Home?
if (!$_GET[section] && !$_GET[go] && $_SESSION[zone] == $FSXL[config][defaultzone])
{
	$ishome = true;
	// Newsheadlines
	if ($header_tpl->match('{headlines}'))
	{
		include('inc/newsheadlines.php');
		$header_tpl->replaceTplVar('{headlines}', $headlinetemplate);
		unset($headlinetemplate);
	}
	// Artikel headlines
	if ($header_tpl->match('{articleheadlines}'))
	{
		include('inc/articleheadlines.php');
		$header_tpl->replaceTplVar('{articleheadlines}', $articleheadlinetpl);
		unset($articleheadlinetpl);
	}
	// Gallery headlines
	if ($header_tpl->match('{galleryheadlines}'))
	{
		include('inc/galleryheadlines.php');
		$header_tpl->replaceTplVar('{galleryheadlines}', $galleryheadlinetpl);
		unset($galleryheadlinetpl);
	}
	// Download headlines
	if ($header_tpl->match('{downloadheadlines}'))
	{
		include('inc/downloadheadlines.php');
		$header_tpl->replaceTplVar('{downloadheadlines}', $downloadheadlinetpl);
		unset($downloadheadlinetpl);
	}
	// Link headlines
	if ($header_tpl->match('{linkheadlines}'))
	{
		include('inc/linkheadlines.php');
		$header_tpl->replaceTplVar('{linkheadlines}', $linkheadlinetpl);
		unset($linkheadlinetpl);
	}
}
else $ishome = false;
$header_tpl->switchCondition('home', $ishome);

// Template ausgeben
$FSXL[template] .= $header_tpl->code;
unset($header_tpl);

?>