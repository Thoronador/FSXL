<?php

@include("inc/config.inc.php");
@include("inc/functions.inc.php");
@include("inc/class.inc.php");

// Session starten und erhalten
@session_set_cookie_params(3600);
@ini_set ("session.use_trans_sid", "1");
@ini_set ("magic_quotes_gpc","off");
@session_start();

// History
$_SESSION[lastpage] = $_SESSION[currentpage];
$_SESSION[currentpage] = $_SERVER["QUERY_STRING"];

// Datanbank Verbindung aufbauen
@$db = new mysql($SQL[host], $SQL[user], $SQL[pass], $SQL[data]);
if ($db->error[error]) // Aufbau nicht möglich
{
	include('inc/lang_'.$FSXL[defaultlanguage].'.php');
	$FSXL[error] = true;
	$FSXL[msg] = $FS_PHRASES[$db->error[msg]];
	$FSXL[title] = $FS_PHRASES[error_db_title];
}
else
{
	// SQL Absichern
	mysql_secure_strings();

	// Menu öffnen/schließen
	if ($_GET[close])
	{
		@setcookie($_GET[close], 'close', time()+2592000);
		reloadPage('?'.$_SESSION[lastpage]);
	}
	if ($_GET[open])
	{
		@setcookie($_GET[open], 'open', time()+2592000);
		reloadPage('?'.$_SESSION[lastpage]);
	}

	// Konfiguration laden
	$FSXL[config] = createConfigArray();
	$FSXL[time] = time();
	$FSXL[content] = '';

	// Logindaten überprüfen
	login(true);
	if ($_SESSION[user]->adminlang) {
		include('inc/lang_'.$_SESSION[user]->adminlang.'.php');
	} else {
		include('inc/lang_'.$FSXL[defaultlanguage].'.php');
	}

	// Temp Text
	if ($_SESSION[unset_tmptext] == true)
	{
		$_SESSION[unset_tmptext] = false;
		$_SESSION[tmptext] = '';
	}

	// Phrases und Funktionen der Mods einbinden
	$index = @mysql_query("SELECT `name` FROM `$FSXL[tableset]_mod`");
	while ($arr = mysql_fetch_assoc($index))
	{
		// Funktionen
		if (file_exists('mod_'.$arr[name].'/functions.inc.php'))
		{
			include('mod_'.$arr[name].'/functions.inc.php');
		}

		// Phrasen
		if (file_exists('mod_'.$arr[name].'/lang_'.$_SESSION[user]->adminlang.'.php'))
		{
			include('mod_'.$arr[name].'/lang_'.$_SESSION[user]->adminlang.'.php');
		}
		// Falls Sprache nicht vorhanden default versuchen zu laden
		elseif (file_exists('mod_'.$arr[name].'/lang_'.$FSXL[defaultlanguage].'.php'))
		{
			include('mod_'.$arr[name].'/lang_'.$FSXL[defaultlanguage].'.php');
		}
		// Ansonsten Fehlermeldung ausgeben
		else
		{
			include('mod_'.$arr[name].'/info.inc.php');
			$FSXL[error] = true;
			$FSXL[msg] = $FS_PHRASES[error_lang_mod] . ' <i><b>' . $FSXL[mod][$arr[name]][title] . '</b></i><br>' . $FS_PHRASES[error_lang_nolang];
			$FSXL[title] = $FS_PHRASES[error_file_title];
		}
	}
	
	// Sprachen Zeichen kodieren
	include ("inc/langconvert.inc.php");

	//Style auswählen
	switch ($_SESSION[user]->adminstyle)
	{
		// Rot
		case 2:
			$FSXL[style] = "red";
			break;
		// blau
		case 3:
			$FSXL[style] = "blue";
			break;
		// Grün
		default:
			$FSXL[style] = "green";
	}

	// Unterseite einbinden oder Startseite anzeigen
	if ($_GET[go] && $_SESSION[loggedin] && !$FSXL[error] && $_SESSION[user]->isadmin && (!$_SESSION[user]->cookielogin || $FSXL[config][admin_cookielogin] == 1))
	{
		if (($_SESSION[user]->access[$_GET[mod]][$_GET[go]]) || ($_GET[go] == 'profil') || (in_array($_SESSION[user]->userid, $FSXL[superadmin])))
		{
			$file = "mod_" . $_GET[mod] . "/data_" . $_GET[go] . ".php";
			if (file_exists($file))
			{
				include($file);
			}
			else
			{
				$FSXL[error] = true;
				$FSXL[msg] = $FS_PHRASES[error_file_msg];
				$FSXL[title] = $FS_PHRASES[error_file_title];
			}
		}
		else
		{
			$FSXL[error] = true;
			$FSXL[msg] = $FS_PHRASES[error_access_noaccess];
			$FSXL[title] = $FS_PHRASES[error_access_title];
		}
	}
	elseif(!$FSXL[error])
	{
		include('mod_main/data_home.php');
	}
}

echo'
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>Frogsystem XL</title>
	<link rel="stylesheet" type="text/css" href="frogpad/frogpad.css">
	<link rel="stylesheet" href="'.$FSXL[style].'.css" type="text/css" media="screen">
	<script src="frogpad/frogpad.js" type="text/javascript"></script>
	<script src="inc/functions.js.php" type="text/javascript"></script>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
</head>
<body onunload="saveText('.$_SESSION[user]->editor.')">
	<div id="main">
		<div id="header"></div>
		<div id="topmenu">
';

// Top Menü
include("inc/topmenu.inc.php");

echo'
		</div>
		<div id="menucontainer">
';

// Loginformular
if (!$_SESSION[loggedin] || !$_SESSION[user]->isadmin || ($_SESSION[user]->cookielogin && $FSXL[config][admin_cookielogin] == 0))
{
	echo'
			<div class="menucat">LOGIN</div>
			<div class="menulogin">
				<form action="" method="post">
					'.$FS_PHRASES[login_username].':
					<input class="textinput" name="username" style="width:150px;">
					'.$FS_PHRASES[login_password].':
					<input class="textinput" name="userpass" type="password" style="width:150px; margin-bottom:5px;">
					<input type="submit" class="button" value="login" style="width:50px; margin-left:105px; margin-bottom:5px;">
				</form>
			</div>
			<p>
	';
}

// Menü
if ($_SESSION[loggedin] && $_SESSION[user]->isadmin && (!$_SESSION[user]->cookielogin || $FSXL[config][admin_cookielogin] == 1))
{
	include("inc/menu.inc.php");
}

echo'
		</div>
		<div id="contentcontainer">
			<div id="contentheader">'.$FSXL[title].'</div>
			<div id="contentwindow">
';

// Inhalt
if ($FSXL[error])
{
	createErrorMsg($FSXL[msg]);
}
else
{
	echo $FSXL[content];
}

echo'
			</div>
		</div>
        </div>
</body>
</html>
';

// Datenbank Verbindung schließen
$db->close();

?>