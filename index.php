<?php

//$mt_starttime = microtime();

include('admin/inc/class.inc.php');
include('admin/inc/config.inc.php');
include('admin/inc/functions.inc.php');

// Session starten und erhalten
@session_set_cookie_params(3600);
@ini_set ("session.use_trans_sid", "1");
@ini_set ("magic_quotes_gpc","off");
@session_start();

// Datanbank Verbindung aufbauen
@$db = new mysql($SQL[host], $SQL[user], $SQL[pass], $SQL[data]);
if ($db->error[error]) // Aufbau nicht möglich
{
	echo'Datenbankverbindung derzeit nicht möglich.';
}
else
{
	// SQL Absichern
	mysql_secure_strings();

	include("admin/inc/counter.inc.php");
	include('inc/pollsubmit.php');

	// History
	$_SESSION[lastpage] = $_SESSION[currentpage];
	$_SESSION[currentpage] = $_SERVER["QUERY_STRING"];

	// Konfiguration laden
	$FSXL[config] = createConfigArray();
	$FSXL[time] = time();
	
	// Ticker updaten
	include ('inc/tickerupdate.php');

	// Cronjobs ausführen
	include ('inc/cronjobs.php');

	// Logindaten überprüfen
	login();

	// Zonen
	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_zones`");
	while ($zdata = mysql_fetch_assoc($index)) {
		$FSXL[zones][$zdata[id]] = array('id' => $zdata[id], 'name' => $zdata[name], 'style' => $zdata[style], 'url' => $zdata[url], 'page' => $zdata[page]);
	}
	if ($_GET[zone]) {
		settype($_GET[zone], 'integer');
		$_SESSION[zone] = $_GET[zone];
	}
	elseif (!$_SESSION[zone]) {
		$_SESSION[zone] = $FSXL[config][defaultzone];
	}
	if ($_SESSION[zone])
	{
		if ($FSXL[zones][$_SESSION[zone]]) {
			$FSXL[zone] = $FSXL[zones][$_SESSION[zone]];
		}
		else {
			$FSXL[zone] = $FSXL[zones][$FSXL[config][defaultzone]];
		}
	}

	// Styles
	// Vom Benutzer wählbarer Style
	if ($FSXL[config][user_select_style] == 1)
	{
		// Eingeloggt
		if ($_SESSION[user]->style && $_SESSION[user]->style != 0)
		{
			$FSXL[style] = $_SESSION[user]->style;
		}
		else
		{
			$FSXL[style] = $FSXL[config][stdstyle];
		}
	}
	// Vorgegebener Style
	else
	{
		$FSXL[style] = $FSXL[zone][style];
	}

	// Template
	$FSXL[template] = '';

	// Header erzeugen
	include('inc/header.inc.php');

	// Inhalt einbinden
	include('inc/middle.inc.php');

	// Footer erzeugen
	include('inc/footer.inc.php');

	// Template erzeugen
	$template = new template($FSXL[template], true);
	unset($FSXL[template]);


	// Variablen
	parseTplVars();

	// Seitentitel erzeugen
	$pagetitle = $FSXL[config][pagetitle];
	if ($FSXL[config][showzonename])
	{
		$pagetitle .= ' - ' . $FSXL[zone][name];
	}
	if ($FSXL[pgtitle])
	{
		$pagetitle .= ' - '.$FSXL[pgtitle];
	}


	// Statische Variablen ersetzen
	$template->switchCondition('user_loggedin', $_SESSION[loggedin]);
	$template->switchCondition('user_isadmin', $_SESSION[user]->isadmin);
	$template->replaceTplVar('{pagetitle}', $pagetitle);

	// Suchmaschienen LInks generieren
	if($FSXL[config][use_safe_links])
	{
		genSafeLinks();
	}
	
	// Zonen Links erzeugen
	genZoneLinks();

	// Template ausgeben
	echo $template->code;
	unset($template);

	if (!$_SESSION[firstpage]) $_SESSION[firstpage] = true;	

	// Datenbank Verbindung schließen
	$db->close();
	
	//echo (microtime() - $mt_starttime);
}

?>