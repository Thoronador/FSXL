<?php

// Template lesen
$footer_tpl = new template('footer');

// Login Formular
$login_tpl = new template('loginform');
$footer_tpl->replaceTplVar('{loginform}', $login_tpl->code);
unset($login_tpl);

// Such Formular
$search_tpl = new template('searchform');
$footer_tpl->replaceTplVar('{search}', $search_tpl->code);
unset($search_tpl);

// POTM
if ($footer_tpl->match('{potm}'))
{
	include('inc/potm.php');
	$footer_tpl->replaceTplVar('{potm}', $potmtpl);
	unset($potmtpl);
}
// POLL
if ($footer_tpl->match('{poll}'))
{
	include('inc/pollbox.php');
	$footer_tpl->replaceTplVar('{poll}', $polltpl);
	unset($polltpl);
}
// Stat
if ($footer_tpl->match('{stat}'))
{
	include('inc/statbox.php');
	$footer_tpl->replaceTplVar('{stat}', $stattpl);
	unset($stattpl);
}
// Tag Cloud
if ($footer_tpl->match('{tagcloud}'))
{
	include('inc/tagcloudbox.php');
	$footer_tpl->replaceTplVar('{tagcloud}', $tagcloudtpl);
	unset($tagcloudtpl);
}
// Shoplt
if ($footer_tpl->match('{shoplt}'))
{
	include('inc/shopltbox.php');
	$footer_tpl->replaceTplVar('{shoplt}', $shoplttpl);
	unset($shoplttpl);
}
// Ticker
if ($footer_tpl->match('{ticker}'))
{
	include('inc/tickerbox.php');
	$footer_tpl->replaceTplVar('{ticker}', $tickertpl);
	unset($tickertpl);
}

// Copyright
$copyright= 'Copyright &copy; 2008-2009 by <a href="frogsystem.xl.frogspawn.de" target="_blank">Frogsystem XL</a> Version '.$FSXL[config][version];
$footer_tpl->replaceTplVar('{copyright}', $copyright);


// Template ausgeben
$FSXL[template] .= $footer_tpl->code;
unset($footer_tpl);

?>

