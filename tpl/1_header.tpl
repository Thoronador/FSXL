<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>{pagetitle}</title>
	<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
	<meta name="description" content="Das Frogsystem XL ist ein CMS speziell für Community Seiten">
	<meta name="author" content="Kermit">
        <base href="http://localhost/fsxl/">
	<meta name="keywords" content="CMS, Community, Frogsystem">
	<meta name="date" content="2008-08-01T08:00:00+02:00">
	<link rel="stylesheet" type="text/css" href=".{css}">
	<link rel="alternate" type="application/rss+xml" title="News-Feed" href="./rss2.xml_1.php">
</head>
<body>
	<div id="header">
		<img border="0" src="images/styles/froggreen/green_logo.jpg" alt="">
        </div>
	<div id="topmenu">
		<div class="topmenuitem"><a href="./?zone=1">Home</a></div>
		<div class="trenner"></div>
	<-- if user_isadmin -->
		<div class="topmenuitem"><a href="./admin/">Admin</a></div>
		<div class="trenner"></div>
	<-- /if user_isadmin -->
	<-- if user_loggedin -->
		<div class="topmenuitem" style="float:right;"><a href="logout.php">Logout</a></div>
		<div class="trenner" style="float:right;"></div>
		<div class="topmenuitem" style="float:right;"><a href="?section=profile">Profil</a></div>
	<-- else user_loggedin -->
		<div class="topmenuitem" style="float:right;"><a href="?section=register">Registrieren</a></div>
	<-- /if user_loggedin -->
	</div>
	<table border="0" cellpadding="0" cellspacing="0" width="100%" id="container">
		<tr>
			<td style="padding-left:10px; padding-top:15px; width:190px;" valign="top">
				<div class="menucat">ALLGEMEIN</div>
				<div class="menuitem"><a href="?section=news">News</a></div>
				<div class="menuitem"><a href="?section=newsarchiv">Newsarchiv</a></div>
				<div class="menuitem"><a href="?section=submitnews">News einsenden</a></div>
				<div class="menuitem"><a href="?section=article">Artikel</a></div>
				<div class="menuitem"><a href="?section=gallery">Galerien</a></div>
				<div class="menuitem"><a href="?section=download">Downloads</a></div>
				<div class="menuitem"><a href="?section=pollarchiv">Umfragen Archiv</a></div>
				<div class="menuitem"><a href="?section=shoplt">Shop</a></div>
				<div class="menuitem"><a href="?section=ticker">LiveTicker Archiv</a></div>
				<div class="menuitem"><a href="?section=video">Videos</a></div>
				<div class="menuitem"><a href="?section=links">Links</a></div>
				<div class="menuitem"><a href="?section=contact">Kontakt</a></div>
				<p>
				{search}
			</td>
			<td style="padding-top:15px;" valign="top">
	<-- if home -->
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td width="50%" style="padding-right:5px;" valign="top">{headlines}</td>
				<td width="50%" valign="top">{articleheadlines}</td>
			</tr>
			<tr>
				<td width="50%" style="padding-right:5px;" valign="top">{galleryheadlines}</td>
				<td width="50%" valign="top">{downloadheadlines}</td>
			</tr>
			<tr>
				<td width="50%" style="padding-right:5px;" valign="top">{linkheadlines}</td>
				<td width="50%" valign="top"></td>
			</tr>
		</table>
	<-- /if home -->