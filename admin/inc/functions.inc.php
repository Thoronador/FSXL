<?php

// Alterscheck
function agecheck($age)
{
	global $FSXL;
	
	$agecheck = true;

	if ($age != 0) {
		$hour = date("H", $FSXL[time]);
		$levels = explode(',', $FSXL[config][ageratings]);
		
		foreach ($levels AS $level) {
			$values = explode('>', $level);
			if ($age < $values[0]) {
				break;
			}
			else {
				if ($hour > 6 && $hour < $values[1]) {
					$agecheck = false;
					$time = $values[1];
				}
			}
		}
	}
	
	return array($agecheck, $time);
}

// Rewrite Map
function rewriteMap()
{
	global $FSXL, $_GET, $db;
	
	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_rewritemap` WHERE `from` = '$_GET[go]'");
	if (mysql_num_rows($index)) {
		while ($map = mysql_fetch_assoc($index))
		{
			if ($FSXL[zone][id] == $map[zone] || $map[zone] == 0)
			{
				$map[to] = str_replace('?', '&', $map[to]);
				$map[group] = explode('&', $map[to]);
				foreach($map[group] AS $value) {
					$value = explode('=', $value);
					$_GET[$value[0]] = $value[1];
				}
				break;
			}
		}
		unset($map);
	}
}

// Permission Mail senden
function sendPermissionMail($section, $subject, $body)
{
	global $FSXL, $db;
	
	$section = explode('/', $section);
	$userids = array();

	// Einzelberechtigungen
	$index = mysql_query("SELECT `userid` FROM `$FSXL[tableset]_useraccess` WHERE `mod` = '$section[0]' AND `page` = '$section[1]'");
	while ($access = mysql_fetch_assoc($index))
	{
		array_push($userids, $access[userid]);
	}
	
	// Gruppenberechtigungen
	$index = mysql_query("SELECT `group` FROM `$FSXL[tableset]_user_groupaccess` WHERE `mod` = '$section[0]' AND `page` = '$section[1]'");
	while ($group = mysql_fetch_assoc($index))
	{
		$index2 = mysql_query("SELECT `user` FROM `$FSXL[tableset]_user_groupconnect` WHERE `group` = '$group[group]'");
		while ($access = mysql_fetch_assoc($index2))
		{
			array_push($userids, $access[user]);
		}
	}
	
	// Emails
	if (count($userids) > 0)
	{
		foreach($userids AS $id) {
			$query .= ' OR `userid` = ' . $id;
		}
		$query = substr($query, 3);
		$index = mysql_query("SELECT `email` FROM `$FSXL[tableset]_userdata` WHERE $query");
		while ($userdat = mysql_fetch_assoc($index))
		{
			sendMail($userdat[email], $subject, $body);
		}
	}
}

// Mail senden
function sendMail($mail, $subject, $text)
{
	global $FSXL;
	
	$header .= "MIME-Version: 1.0\r\n";
	$header .= "Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
	$header .= "Content-Transfer-Encoding: 8bit\r\n";
	$header .= "From: ".$FSXL[config][pagetitle]." <".$FSXL[config][systemmail].">\r\n";
	$header .= "Reply-To: ".$FSXL[config][pagetitle]." <".$FSXL[config][systemmail].">";
	
	$subject = urlencode($subject);
	$subject = str_replace('%', '=', $subject);
	$subject = str_replace('+', ' ', $subject);
	$subject = "=?ISO-8859-1?Q?$subject?=";
	
	@mail ($mail, utf8_encode($subject), $text, $header);
}

// Strings etwerten
function mysql_secure_strings()
{
	global $_POST, $_GET, $_COOKIE, $db;
	
	if (get_magic_quotes_gpc())
	{
		foreach($_POST AS $key => $value) {
			if (!is_array($value)) $_POST[$key] = mysql_real_escape_string(stripslashes($value));
			else foreach($value AS $key2 => $value2) {
				$_POST[$key][$key2] = mysql_real_escape_string(stripslashes($value2));
			}
		}
		foreach($_GET AS $key => $value) {
			if (!is_array($value)) $_GET[$key] = mysql_real_escape_string(stripslashes($value));
			else foreach($value AS $key2 => $value2) {
				$_GET[$key][$key2] = mysql_real_escape_string(stripslashes($value2));
			}
		}
		foreach($_COOKIE AS $key => $value) {
			if (!is_array($value)) $_COOKIE[$key] = mysql_real_escape_string(stripslashes($value));
			else foreach($value AS $key2 => $value2) {
				$_COOKIE[$key][$key2] = mysql_real_escape_string(stripslashes($value2));
			}
		}
	}
	else
	{
		foreach($_POST AS $key => $value) {
			if (!is_array($value)) $_POST[$key] = mysql_real_escape_string($value);
			else foreach($value AS $key2 => $value2) {
				$_POST[$key][$key2] = mysql_real_escape_string($value2);
			}
		}
		foreach($_GET AS $key => $value) {
			if (!is_array($value)) $_GET[$key] = mysql_real_escape_string($value);
			else foreach($value AS $key2 => $value2) {
				$_GET[$key][$key2] = mysql_real_escape_string($value2);
			}
		}
		foreach($_COOKIE AS $key => $value) {
			if (!is_array($value)) $_COOKIE[$key] = mysql_real_escape_string($value);
			else foreach($value AS $key2 => $value2) {
				$_COOKIE[$key][$key2] = mysql_real_escape_string($value2);
			}
		}
	}
}

// Login
function login($admin=false)
{
	global $_SESSION, $_POST, $FSXL, $_COOKIE, $FS_PHRASES;

	if (!$_SESSION[loggedin] || ($_SESSION[user]->cookielogin && $admin == true && $FSXL[config][admin_cookielogin] == 0))
	{
		if (($_POST[username] && $_POST[userpass]) || ($_COOKIE[username] && $_COOKIE[password] && ($admin == false || $FSXL[config][admin_cookielogin] == 1)))
		{
			$username = $_POST[username] ? $_POST[username] : $_COOKIE[username];
			$userpass = $_POST[userpass] ? $_POST[userpass] : $_COOKIE[password];
			$_SESSION[user] = new user($username, $userpass, ($_POST[userpass] ? false : true), $admin);
			if ($_SESSION[user]->error[error])
			{
				@setcookie('username');
				@setcookie('password');
				if ($admin == true)
				{
					$FSXL[error] = true;
					$FSXL[msg] = $FS_PHRASES[$_SESSION[user]->error[msg]];
					$FSXL[msg] = str_replace('%t', $FSXL[config][login_attempts] - $_SESSION[user]->logins - 1, $FSXL[msg]);
					$FSXL[msg] = str_replace('%m', $FSXL[config][login_blocktime], $FSXL[msg]);
					$FSXL[title] = $FS_PHRASES[error_login_title];
				}
				else
				{
					$_SESSION[loginerror] = $_SESSION[user]->error[msg];
					reloadPage('?section=login');
				}
			}
			else
			{
				$_SESSION[loggedin] = true;
				if ($_POST[staylogged])
				{
					@setcookie('username', $username, time()+2592000);
					@setcookie('password', $_SESSION[user]->password, time()+2592000);
				}
				if (!$_COOKIE[username]) reloadPage('?'.$_SESSION[lastpage]);
			}
			unset($username);
			unset($userpass);
		}
	}
}

// Salt generieren
function genSalt()
{
	$shaker = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', '0', '1', '2', '3', '4', '5', '6', '7', '8', '-', '_', '%');
	$salt = '';
	for ($i=0; $i<16; $i++)
	{
		$salt .= $shaker[rand(0, count($shaker)-1)];
	}

	return $salt;
}

// Zonen Links erzeugen
function genZoneLinks()
{
	global $FSXL,$template;
	$template->code = preg_replace_callback("/\?zone=([0-9]*)/i", "zoneUrl", $template->code);
	
	if ($FSXL[zone][id] > 1) {
		$folder = substr($_SERVER["SCRIPT_NAME"], 0, strlen($_SERVER["SCRIPT_NAME"])-9);
		$template->code = preg_replace("/([^\/])images\//i", "$1".$folder."images/", $template->code);
		$template->code = preg_replace("/([^\/])inc\//i", "$1".$folder."inc/", $template->code);
	}
}
function zoneUrl ($treffer)
{
	global $FSXL;
	if ($FSXL[zone][id] > 1) {
		if ($treffer[1] == 1) {
			return '../';
		}
		elseif ($FSXL[zone][id] != $treffer[1]) {
			return '../'.$FSXL[zones][$treffer[1]][url].'/';
		}
		else {
			return './';
		}
	}
	else {
		if ($treffer[1] == 1) {
			return './';
		}
		else {
			return $FSXL[zones][$treffer[1]][url].'/';
		}
	}
}

// Htacces Zonen eintragen
function updateHtaccess()
{
	global $FSXL;

	$data = "# <-- zones -->\r\n";
	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_zones` WHERE `id` != 1");
	while ($zone = mysql_fetch_assoc($index)) {
		$data .= 'RewriteRule ^'.$zone[url].'/(index\.php){0,1}([^/\?]*)$ index.php?zone='.$zone[id].'$2&%{QUERY_STRING}'."\r\n";
	}
	$data .= "# <-- /zones -->";

	$htaccess = file_get_contents('../.htaccess');
	$htaccess = preg_replace("/\# <-- zones -->(.*?)\# <-- \/zones -->/is", "{include}", $htaccess);
	$htaccess = str_replace("{include}", $data, $htaccess);
	
	$file = fopen('../.htaccess', "w+");
	fwrite($file, $htaccess);
	fclose($file);
}

// Suchmaschienen Links erzeugen
function genSafeLinks()
{
	global $template;

	// Article
	$template->code = preg_replace("/href=\"([a-z0-9\/]*?)(index.php){0,1}\?section=article&(amp;){0,1}cat=([0-9]*)/i", "href=\"$1article_cat$4.htm", $template->code);
	$template->code = preg_replace("/href=\"([a-z0-9\/]*?)(index.php){0,1}\?section=article&(amp;){0,1}id=([0-9]*)&(amp;){0,1}page=([0-9]*)/i", "href=\"$1article_$4_$6.htm", $template->code);
	$template->code = preg_replace("/href=\"([a-z0-9\/]*?)(index.php){0,1}\?section=article&(amp;){0,1}id=([0-9]*)/i", "href=\"$1article_$4.htm", $template->code);

	// News
	$template->code = preg_replace("/href=\"([a-z0-9\/]*?)(index.php){0,1}\?section=newsdetail&(amp;){0,1}id=([0-9]*)/i", "href=\"$1news_$4.htm", $template->code);
	$template->code = preg_replace("/href=\"([a-z0-9\/]*?)(index.php){0,1}\?section=newsarchiv&(amp;){0,1}month=([0-9]*)&(amp;){0,1}year=([0-9]*)&(amp;){0,1}page=([0-9]*)/i", "href=\"$1newsarchiv_$4_$6_$8.htm", $template->code);
	$template->code = preg_replace("/href=\"([a-z0-9\/]*?)(index.php){0,1}\?section=newsarchiv&(amp;){0,1}month=([0-9]*)&(amp;){0,1}year=([0-9]*)/i", "href=\"$1newsarchiv_$4_$6.htm", $template->code);

	// Galerien
	$template->code = preg_replace("/href=\"([a-z0-9\/]*?)(index.php){0,1}\?section=gallery&(amp;){0,1}cat=([0-9]*)/i", "href=\"$1gallery_cat$4.htm", $template->code);
	$template->code = preg_replace("/href=\"([a-z0-9\/]*?)(index.php){0,1}\?section=gallery&(amp;){0,1}id=([0-9]*)/i", "href=\"$1gallery_$4.htm", $template->code);
	$template->code = preg_replace("/href=\"([a-z0-9\/]*?)(index.php){0,1}\?section=gallery&(amp;){0,1}detail=([0-9]*)/i", "href=\"$1gallery_pic$4.htm", $template->code);
	$template->code = preg_replace("/href=\"([a-z0-9\/]*?)(index.php){0,1}\?section=timed&(amp;){0,1}detail=([0-9]*)/i", "href=\"$1timed_pic$4.htm", $template->code);
	
	// Contests
	$template->code = preg_replace("/href=\"([a-z0-9\/]*?)(index.php){0,1}\?section=contest&(amp;){0,1}id=([0-9]*)/i", "href=\"$1contest_$4.htm", $template->code);
	$template->code = preg_replace("/href=\"([a-z0-9\/]*?)(index.php){0,1}\?section=contestentries&(amp;){0,1}id=([0-9]*)&(amp;){0,1}page=([0-9]*)/i", "href=\"$1contestentries_$4_$6.htm", $template->code);
	$template->code = preg_replace("/href=\"([a-z0-9\/]*?)(index.php){0,1}\?section=contestentries&(amp;){0,1}id=([0-9]*)/i", "href=\"$1contestentries_$4.htm", $template->code);
	$template->code = preg_replace("/href=\"([a-z0-9\/]*?)(index.php){0,1}\?section=contestentry&(amp;){0,1}id=([0-9]*)/i", "href=\"$1contestentry_$4.htm", $template->code);

	// Downloads
	$template->code = preg_replace("/href=\"([a-z0-9\/]*?)(index.php){0,1}\?section=download&(amp;){0,1}id=([0-9]*)/i", "href=\"$1download_$4.htm", $template->code);
	$template->code = preg_replace("/href=\"([a-z0-9\/]*?)(index.php){0,1}\?section=download&(amp;){0,1}folder=([0-9]*)/i", "href=\"$1download_folder$4.htm", $template->code);

	// Umfragen
	$template->code = preg_replace("/href=\"([a-z0-9\/]*?)(index.php){0,1}\?section=pollarchiv&(amp;){0,1}id=([0-9]*)/i", "href=\"$1pollarchiv_$4.htm", $template->code);

	// SHop LT
	$template->code = preg_replace("/href=\"([a-z0-9\/]*?)(index.php){0,1}\?section=shoplt&(amp;){0,1}cat=([0-9]*)/i", "href=\"$1shoplt_cat$4.htm", $template->code);

	// Ticker
	$template->code = preg_replace("/href=\"([a-z0-9\/]*?)(index.php){0,1}\?section=ticker&(amp;){0,1}id=([0-9]*)/i", "href=\"$1ticker_$4.htm", $template->code);

	// Videos
	$template->code = preg_replace("/href=\"([a-z0-9\/]*?)(index.php){0,1}\?section=video&(amp;){0,1}cat=([0-9]*)/i", "href=\"$1video_cat$4.htm", $template->code);
	$template->code = preg_replace("/href=\"([a-z0-9\/]*?)(index.php){0,1}\?section=video&(amp;){0,1}id=([0-9]*)/i", "href=\"$1video_$4.htm", $template->code);

	// Links
	$template->code = preg_replace("/href=\"([a-z0-9\/]*?)(index.php){0,1}\?section=links&(amp;){0,1}cat=([0-9]*)&(amp;){0,1}sub=([0-9]*)/i", "href=\"$1links_cat$4_$6.htm", $template->code);
	$template->code = preg_replace("/href=\"([a-z0-9\/]*?)(index.php){0,1}\?section=links&(amp;){0,1}cat=([0-9]*)/i", "href=\"$1links_cat$4.htm", $template->code);

	// Suche
	$template->code = preg_replace("/action=\"([a-z0-9\/]*?)(index.php){0,1}\?section=search/i", "action=\"$1search.htm", $template->code);

	// Shortcuts
	$template->code = preg_replace("/href=\"([a-z0-9\/]*?)(index.php){0,1}\?section=([a-zA-Z0-9\-\_]*)/i", "href=\"$1$3.htm", $template->code);
}

// Template Variablen einsetzen
function includeVar($include)
{
	ob_start();
	include($include);
	$code = ob_get_contents();
	ob_end_clean();
	return $code;
}
function parseTplVars()
{
	global $FSXL, $template, $db;

	$varindex = mysql_query("SELECT * FROM `$FSXL[tableset]_tplvars`");
	while ($var = mysql_fetch_assoc($varindex))
	{
		$show = false;
		$code = '';

		// Richtiger Zeitraum
		if (($var[startdate] == 0 || $var[startdate] <= $FSXL[time]) && ($var[enddate] == 0 || $var[enddate] >= $FSXL[time]) && $template->match('[§'.$var[name].'§]'))
		{
			// Richtige Section
			if ($var[section] == $FSXL[section] || $var[section] == '')
			{
				// Immer
				if ($var[display] == 1)
				{
					$show = true;
				}
				// Zone
				elseif  ($var[display] == 5 && $var[zone] == $_SESSION[zone])
				{
					$show = true;
				}
				// Erster Seitenaufruf
				elseif ($var[display] == 3 && !$_SESSION[firstpage])
				{
					$show = true;
				}
				// Startseite
				elseif ($var[display] == 4 && !$_GET[section] && !$_GET[go])
				{
					$show = true;
				}
				// Zufällig
				elseif  ($var[display] == 2 && rand(1, $var[interval]) == 1)
				{
					$show = true;
				}
			}
		}

		// Anzeigen
		if ($show == true)
		{
			// Include
			if ($var[type] == 3)
			{
				$code = includeVar($var['include']);
			}
			// Text
			else
			{
				if ($FSXL[config][use_tplvar_cache])
				{
					// APC Cache lesen
					if (function_exists('apc_cache_info'))
					{
						$apc_name = $FSXL[config][bez].'/cache/tplvar_'.$var[id].'.cch';
						$code = apc_fetch($apc_name);

					}

					// Datei
					if (strlen($code) == 0 && file_exists('cache/tplvar_'.$var[id].'.cch'))
					{
						$code = implode('', file('cache/tplvar_'.$var[id].'.cch'));
					}
				}
				if (!$FSXL[config][use_tplvar_cache] || strlen($code) == 0 || $var[type] == 2)
				{
					$index2 = mysql_query("SELECT `code` FROM `$FSXL[tableset]_tplvars_code` WHERE `var` = '$var[id]'");
					$code = mysql_result($index2, rand(0, mysql_num_rows($index2)-1), 'code');

					if ($var[type] != 2)
					{
						// APC Cache schreiben
						if (function_exists('apc_cache_info'))
						{
							$apc_name = $FSXL[config][bez].'/cache/tplvar_'.$var[id].'.cch';
							apc_store($apc_name, $code);

						}

						// Cache schreiben
						if ($FSXL[config][use_tplvar_cache] && !file_exists('cache/tplvar_'.$var[id].'.cch'))
						{
							$fp = fopen('cache/tplvar_'.$var[id].'.cch', 'w');
							fwrite($fp, $code);
							fclose($fp);
						}
					}
				}
			}

			$template->replaceTplVar('[§'.$var[name].'§]', $code);
		}
		// Nicht anzeigen
		else
		{
			$template->replaceTplVar('[§'.$var[name].'§]', '');
		}
	}
}

// Editor auswählen
function setEditor($editor, $type, $text='')
{
	global $FSXL;

	$code = '';

	switch($editor)
	{
		// Frogpad
		case 0:
			if ($type == 1) // FS Code
			{
				$text = str_replace("'", "\'", $text);
				$text = preg_replace("/(\n\r|\r\n|\n|\r)/is", "{[n]}", $text);

				$code .= generateImgBar();
				$code .= '<script type="text/javascript">var dbcode = \''.$text.'\'; var invi = \'nein\';</script>';
				$code .= '<textarea name="html_code" id="html_code" class="htmlinput" style="display:none;"></textarea>';
			}
			else // HTML Code
			{
				$code .= '<script type="text/javascript">var invi = \'ja\'</script>';
				$code .= '<textarea name="html_code" id="html_code" class="htmlinput">'.$text.'</textarea>';
			}
			break;
		// Frogedit
		case 1:
			if ($type == 1) // FS Code
			{
				include('frogedit/frogedit.php');
				$code .= $frogedit_code;
			}
			else // HTML Code
			{
				$code .= '<div id="wysiwyg_container" style="display:none;"><textarea name="fp_code" id="fp_code" class="textinput" style="display:inline; width:424px; height:492px;"></textarea></div>';
				$code .= '<textarea name="html_code" id="html_code" class="htmlinput">'.$text.'</textarea>';
			}
			break;
		// Textfeld
		case 2:
			if ($type == 1) // FS Code
			{
				$code .= '<div id="wysiwyg_container"><textarea name="fp_code" id="fp_code" class="textinput" style="display:inline; width:424px; height:492px;">'.$text.'</textarea></div>';
				$code .= '<textarea name="html_code" id="html_code" class="htmlinput" style="display:none;"></textarea>';
			}
			else // HTML Code
			{
				$code .= '<div id="wysiwyg_container" style="display:none;"><textarea name="fp_code" id="fp_code" class="textinput" style="display:inline; width:424px; height:492px;"></textarea></div>';
				$code .= '<textarea name="html_code" id="html_code" class="htmlinput">'.$text.'</textarea>';
			}
			break;
	}

	return $code;
}

// KOnfigurations Array erzeugen
function createConfigArray()
{
	global $FSXL;
	$config = array();

	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_config`");
	while ($arr = mysql_fetch_assoc($index))
	{
		$config[$arr[name]] = $arr[value];
	}

	return $config;
}


// Error Message erzeugen
function errorMsg($type)
{
	// Template lesen
	$msg = new template($type);
	$frame = new template('errormsg');
	$frame->replaceTplVar('{message}', $msg->code);

	// Template ausgeben
	return $frame->code;
}

function formatNumber($num)
{
	return number_format($num, 0, '', '.');
}

function generateImgBar()
{
	global $db, $FSXL;

	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_images` ORDER BY `id` DESC LIMIT 10");
	if (mysql_num_rows($index) > 0)
	{
		$imghtml = '<table border="0" cellpadding="1" cellspacing="0"><tr>';
		while($img = mysql_fetch_assoc($index))
		{
			if (file_exists('../images/imgmanager/'.$img[id].'.png'))
			{
				$imghtml .= '<td valign="bottom"><img width="32" border="0" title="'.$img[title].'" src="../images/imgmanager/'.$img[id].'s.jpg" alt="" class="fp_thumb" style="cursor:pointer;" onclick="addimage(\\\''.$img[id].'.png\\\')"></td>';
			}
			elseif (file_exists('../images/imgmanager/'.$img[id].'.gif'))
			{
				$imghtml .= '<td valign="bottom"><img width="32" border="0" title="'.$img[title].'" src="../images/imgmanager/'.$img[id].'s.jpg" alt="" class="fp_thumb" style="cursor:pointer;" onclick="addimage(\\\''.$img[id].'.gif\\\')"></td>';
			}
			else
			{
				$imghtml .= '<td valign="bottom"><img width="32" border="0" title="'.$img[title].'" src="../images/imgmanager/'.$img[id].'s.jpg" alt="" class="fp_thumb" style="cursor:pointer;" onclick="addimage(\\\''.$img[id].'.jpg\\\')"></td>';
			}
		}
		$imghtml .= '</tr></table>';
	}
	return '<script type="text/javascript">var imgbar = \''.$imghtml.'\';</script>';
}

// Clean
function cleanText($text, $hard=true)
{
	$text = preg_replace("/\[age(.*?)\](.*?)\[\/age\]/i", " ", $text);
	$text = preg_replace("/\[img(.*?)\](.*?)\[\/img\]/i", " ", $text);
	$text = preg_replace("/\[gallery(.*?)\](.*?)\[\/gallery\]/i", " ", $text);
	$text = preg_replace("/\[video(.*?)\](.*?)\[\/video\]/i", " ", $text);
	$text = preg_replace("/\[poll\]([0-9]*)\[\/poll\]/i", " ", $text);
	$text = preg_replace("/(\{index\})/i", " ", $text);
	$text = preg_replace("/([a-zA-Z0-9-_\.]+?)@([a-zA-Z0-9-_\.]+?)\.([a-zA-Z]{2,4})/i", " ", $text);
	$text = preg_replace("/\[(.*?)\]/i", " ", $text);

	$text = strip_tags($text);

	if ($hard)
	{
		$text = preg_replace("/\W/", " ", $text);
		$text = preg_replace("/(^|\s)\w{1,3}(\s|$)/i", " ", $text);
		$text = preg_replace("/(^|\s)\w{1,3}(\s|$)/i", " ", $text);
		$text = preg_replace("/^\s+/i", "", $text);
		$text = preg_replace("/\s+$/i", "", $text);
		$text = preg_replace("/\s+/i", ",", $text);
	}
	return $text;
}

// News für vB konvertieren
function vBText ($text)
{
	$script = preg_replace("/(.*?)\/(admin){0,1}(\/){0,1}(index\.php){0,1}/i", "$1", $_SERVER[SCRIPT_NAME]);
	$url = 'http://'.$_SERVER[SERVER_NAME].$script.'/images/imgmanager/';
	$text = preg_replace("/\[age(.*?)\](.*?)\[\/age\]/i", " ", $text);
	$text = preg_replace("/\[img(.*?)\]([0-9]*)\[\/img\]/i", "[img]$url$2.jpg[/img]", $text);
	$text = preg_replace("/\[img(.*?)\]([0-9]*)\.(png|gif)\[\/img\]/i", "[img]$url$2.$3[/img]", $text);
	
	$text = preg_replace("/\[gallery(.*?)\](.*?)\[\/gallery\]/i", " ", $text);
	$text = preg_replace("/\[video(.*?)\](.*?)\[\/video\]/i", " ", $text);
	$text = preg_replace("/\[poll\](.*?)\[\/poll\]/i", " ", $text);
	$text = preg_replace("/\[floatleft\](.*?)\[\/floatleft\]/i", "$1", $text);
	$text = preg_replace("/\[floatright\](.*?)\[\/floatright\]/i", "$1", $text);
	
	$text = preg_replace("/\[dir\]/i", "[indent]", $text);
	$text = preg_replace("/\[\/dir\]/i", "[/indent]", $text);
	
	$text = preg_replace("/\[list=number\]/i", "[list]", $text);

	return $text;
}

function updateSearchIndex($articleid, $articletype, $text)
{
	global $db, $FSXL;

	mysql_query("DELETE FROM `$FSXL[tableset]_".$articletype."connect` WHERE `article` = '$articleid'");
	$cleantext = cleanText($text);
	$words = explode(',', $cleantext);
	foreach($words AS $word)
	{
		$index = mysql_query("INSERT INTO `$FSXL[tableset]_wordindex` (`id`, `word`) VALUES (NULL, '$word')");
		if ($index)
		{
			$wid = mysql_insert_id();
		}
		else
		{
			$index = mysql_query("SELECT * FROM `$FSXL[tableset]_wordindex` WHERE `word` = '$word'");
			$blub = mysql_fetch_assoc($index);
			$wid = $blub[id];
		}
		mysql_query("INSERT INTO `$FSXL[tableset]_".$articletype."connect` (`word`, `article`) VALUES ('$wid', '$articleid')");
	}
}

// Dateigröße umrechnen
function size_it($value)
{
	if ($value < 1024) $value .= ' KB';
	elseif ($value < 1048576) $value = round($value/1024, 2) . ' MB';
	elseif ($value < 1073741824) $value = round($value/1048576, 2) . ' GB';

	return $value;
}


// Schimpfwortfilter
function replaceSpam($text, $string)
{
	if ($string)
	{
		$keywords = explode("\r\n", $string);

		foreach($keywords as $value)
		{
			$replacestring = str_repeat('*', strlen($value));
			$text = eregi_replace($value, $replacestring, $text);
		}

	}
	return $text;
}


// Hexadezimal Farben
function ImageColorAllocateFromHex ($img, $hexstr)
{
	$int = hexdec($hexstr);
	return ImageColorAllocate ($img, 0xFF & ($int >> 0x10), 0xFF & ($int >> 0x8), 0xFF & $int);
} 


// Random String
function createRandomString($length)
{
	$SChars = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z');
	$BChars = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'N', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
	$numbers = array(2, 3, 4, 5, 6, 7, 8, 9, 0);

	$string = '';
	for ($i=0; $i<$length; $i++)
	{
		switch (rand(1, 3))
		{
			case 1:
				$string .= $SChars[rand(0, 25)];
				break;
			case 2:
				$string .= $BChars[rand(0, 25)];
				break;
			case 3:
				$string .= $numbers[rand(0, 8)];
				break;
		}
	}
	return $string;
}

// Error Message
function createErrorMsg($msg)
{
	echo'
		<div style="padding:20px; text-align:center;">'.$msg.'</div>
	';
}

// Reload Page
function reloadPage($page = "")
{
	global $_GET, $FSXL, $db;

	$hostname = $_SERVER['HTTP_HOST'];
	$path = dirname($_SERVER['PHP_SELF']);
	
	if ($_GET[zone] > 1) {
		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_zones` WHERE `id` = '$_GET[zone]'");
		$zone = @mysql_fetch_assoc($index);
		$page = $zone[url] . '/' . $page;
	}

	$header = 'Location: http://'.$hostname.($path == '/' ? '' : $path).'/' . $page;
	$header = str_replace('\\', '', $header);
	header($header);
}

// Menupunkt erzeugen
function createMenu($mod, $text)
{
	global $FSXL, $_SESSION, $_COOKIE, $FS_PHRASES;

	// Head
	if ($_COOKIE[$mod] != "close")
		$text = preg_replace("/(\[menuhead\|)(.*?)(\])/i", "<div class=\"menucat\"><a href=\"?close=$mod\"><img border=\"0\" src=\"images/$FSXL[style]_arrow_left.gif\" alt=\"$FS_PHRASES[main_menu_close]\" style=\"float:right; margin-right:8px;\"></a>$2</div>", $text);
	else
		$text = preg_replace("/(\[menuhead\|)(.*?)(\])/i", "<div class=\"menucat\"><a href=\"?open=$mod\"><img border=\"0\" src=\"images/$FSXL[style]_arrow_bottom.gif\" alt=\"$FS_PHRASES[main_menu_open]\" style=\"float:right; margin-right:8px;\"></a>$2</div>", $text);

	if ($FSXL[config][admin_show_config] == 0 && $mod != 'main')
	{
		$text = preg_replace("/(\[menuitem\|)(.*?)(\|)config(\])/i", "", $text);
	}

	if ($_COOKIE[$mod] != "close")
	{
		// Item
		preg_match_all("/(\[menuitem\|)(.*?)(\|)(.*?)(\])/i", $text, $match);
		$items = 0;
		foreach($match[4] AS $value)
		{
			if (($_SESSION[user]->access[$mod][$value] == true) || (in_array($_SESSION[user]->userid, $FSXL[superadmin])))
			{
				$string = '/(\[menuitem\|)(.*?)(\|)'.$value.'(\])/i';
				$text = preg_replace($string, "<div class=\"menuitem\"><a href=\"?mod=$mod&go=$value\">$2</a></div>", $text);
				$items++;
			}
		}
	}
	else
	{
		// Item
		preg_match_all("/(\[menuitem\|)(.*?)(\|)(.*?)(\])/i", $text, $match);
		$items = 0;
		$lettopopen = false;
		foreach($match[4] AS $value)
		{
			if (($_SESSION[user]->access[$mod][$value] == true) || (in_array($_SESSION[user]->userid, $FSXL[superadmin])))
			{
				$lettopopen = true;
			}
		}
	}

	$text = preg_replace("/(\[menuitem\|)(.*?)(\|)([a-z]*?)(\])/i", "", $text);

	if ($items == 0 && !$lettopopen)
	{
		$text = preg_replace("/<div class=\"menucat\">(.*?)<\/div>/i", "", $text);
	}

	$text .= "<p>";
	return $text;
}

// Smilie ersetzen
function replaceSmilies($text)
{
	global $FSXL;

	if (!$FSXL[smilies])
	{
		$FSXL[smilies] = array();
		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_smilies`");
		while ($smilie = mysql_fetch_assoc($index))
		{
			$text = str_replace($smilie[code], '<img border="0" src="images/smilies/'.$smilie[id].'.gif" alt="">', $text);
			$FSXL[smilies][$smilie[id]] = $smilie[code];
		}
	}
	else
	{
		foreach ($FSXL[smilies] AS $id => $code)
		{
			$text = str_replace($code, '<img border="0" src="images/smilies/'.$id.'.gif" alt="">', $text);
		}
	}

	return $text;
}

function fscode($code, $soft=false)
{
	global $FSXL;

	$code = str_replace("<","&lt;",$code);
	$code = str_replace(">","&gt;",$code);

	// Zeilenumbrüche
	$code = preg_replace("/(\n\r|\r\n|\n|\r)/is", "\n", $code);
	
	// Alterscheck
	$code = preg_replace_callback("/\[age=([0-9]*?)\](.*?)\[\/age\]/is", "bb_age", $code);

	// Text Design
	$code = preg_replace("/\[b\](.*?)\[\/b\]/is", "<strong>$1</strong>", $code);
	$code = preg_replace("/\[u\](.*?)\[\/u\]/is", "<u>$1</u>", $code);
	$code = preg_replace("/\[i\](.*?)\[\/i\]/is", "<em>$1</em>", $code);
	$code = preg_replace("/\[s\](.*?)\[\/s\]/is", "<strike>$1</strike>", $code);

	$code = preg_replace("/\[pre\](.*?)\[\/pre\]/is", "<pre>$1</pre>", $code);

	// Texteinrückung
	while (preg_match("/\[dir\](.*?)\n*\[\/dir\]/is", $code) == 1)
	{
		$code = preg_replace("/\[dir\](.*?)\n*\[\/dir\]/is", "<BLOCKQUOTE>$1</BLOCKQUOTE>", $code);
	}

	// Textausrichtung
	while (preg_match("/\[center\](.*?)\[\/center\]/is", $code) == 1)
	{
		$code = preg_replace("/\[center\](.*?)\[\/center\]/is", "<p align=center>$1</p>", $code);
	}
	$code = preg_replace("/\[right\](.*?)\[\/right\]/is", "<p align=right>$1</p>", $code);
	$code = preg_replace("/\[block\](.*?)\[\/block\]/is", "<p align=justify>$1</p>", $code);

	// Links
	if (!$soft)
	{
		$code = preg_replace("/(^|\s)http:\/\/(.*?)(\s|$)/is", "$1<a href=\"http://$2\" target=\"_blank\">http://$2</a>$3", $code);
		$code = preg_replace("/\[url=\"{0,1}www.(.*?)\"{0,1} blank\](.*?)\[\/url\]/is", "<A href=\"http://www.$1\" target=\"_blank\">$2</A>", $code);
		$code = preg_replace("/\[url=\"{0,1}(.*?)\"{0,1} blank\](.*?)\[\/url\]/is", "<A href=\"$1\" target=\"_blank\">$2</A>", $code);
		$code = preg_replace("/\[url=\"{0,1}www.(.*?)\"{0,1}\](.*?)\[\/url\]/is", "<A href=\"http://www.$1\">$2</A>", $code);
		$code = preg_replace("/\[url=\"{0,1}(.*?)\"{0,1}\](.*?)\[\/url\]/is", "<A href=\"$1\">$2</A>", $code);
		$code = preg_replace("/\[url\](http:\/\/){0,1}www.(.*?)\[\/url\]/is", "<A href=\"http://www.$2\" target=\"_blank\">$1www.$2</A>", $code);
		$code = preg_replace("/\[url\](.*?)\[\/url\]/is", "<A href=\"$1\">$1</A>", $code);
	}
	else
	{
		$code = preg_replace("/(^|\s)http:\/\/(.*?)(\s|$)/is", "$1<a href=\"http://$2\" target=\"_blank\" rel=\"nofollow\">http://$2</a>$3", $code);
		$code = preg_replace("/\[url=\"{0,1}www.(.*?)\"{0,1} blank\](.*?)\[\/url\]/is", "<A href=\"http://www.$1\" target=\"_blank\" rel=\"nofollow\">$2</A>", $code);
		$code = preg_replace("/\[url=\"{0,1}(.*?)\"{0,1} blank\](.*?)\[\/url\]/is", "<A href=\"$1\" target=\"_blank\" rel=\"nofollow\">$2</A>", $code);
		$code = preg_replace("/\[url=\"{0,1}www.(.*?)\"{0,1}\](.*?)\[\/url\]/is", "<A href=\"http://www.$1\" rel=\"nofollow\">$2</A>", $code);
		$code = preg_replace("/\[url=\"{0,1}(.*?)\"{0,1}\](.*?)\[\/url\]/is", "<A href=\"$1\" rel=\"nofollow\">$2</A>", $code);
		$code = preg_replace("/\[url\](http:\/\/){0,1}www.(.*?)\[\/url\]/is", "<A href=\"http://www.$2\" target=\"_blank\" rel=\"nofollow\">$1www.$2</A>", $code);
		$code = preg_replace("/\[url\](.*?)\[\/url\]/is", "<A href=\"$1\" rel=\"nofollow\">$1</A>", $code);
	}

	// Images
	$code = preg_replace("/\[img\]([0-9]*)\[\/img\]/is", "<IMG alt=\"\" src=\"images/imgmanager/$1.jpg\" border=0>", $code);
	$code = preg_replace("/\[img\]([0-9]*)\.(png|gif)\[\/img\]/is", "<IMG alt=\"\" src=\"images/imgmanager/$1.$2\" border=0>", $code);
	$code = preg_replace("/\[img\](.*?)\[\/img\]/is", "<IMG alt=\"\" src=\"$1\" border=0>", $code);
	$code = preg_replace("/\[img ([0-9]*?)\|([0-9]*?)\]([0-9]*)\[\/img\]/is", "<IMG style=\"WIDTH:$1px; HEIGHT:$2px\" alt=\"\" src=\"images/imgmanager/$3.jpg\" border=0>", $code);
	$code = preg_replace("/\[img ([0-9]*?)\|([0-9]*?)\]([0-9]*)\.(png|gif)\[\/img\]/is", "<IMG style=\"WIDTH:$1px; HEIGHT:$2px\" alt=\"\" src=\"images/imgmanager/$3.$4\" border=0>", $code);
	$code = preg_replace("/\[img ([0-9]*?)\|([0-9]*?)\](.*?)\[\/img\]/is", "<IMG style=\"WIDTH:$1px; HEIGHT:$2px\" alt=\"\" src=\"$3\" border=0>", $code);

	// HR
	$code = preg_replace("/\n{0,1}-{3,}\n{0,1}/is", "<hr>", $code);

	// Listen
	$code = preg_replace("/\n*\[\*\]/is", "<LI>", $code);
	$code = preg_replace("/\[list=number\](.*?)\n*\[\/list\]\n{0,1}/is", "<OL>$1</OL>", $code);
	$code = preg_replace("/\[list\](.*?)\n*\[\/list\]\n{0,1}/is", "<UL>$1</UL>", $code);

	// Tabellen
	$code = preg_replace_callback("/\n{0,1}\[table(.*?)\](.*?)\[\/table\]/is", "ohtmlTable", $code);
	$code = preg_replace("/\n*\[tr\]\n*(.*?)\n*\[\/tr\]\n*/is", "<TR>$1</TR>", $code);
	$code = preg_replace("/\n*\[td\](.*?)\[\/td\]\n*/is", "<TD>$1</TD>", $code);
	$code = preg_replace("/\n*\[td (.*?)\]\n{0,1}(.*?)\[\/td\]\n*/is", "<TD class=$1>$2</TD>", $code);

	// Float Box
	$code = preg_replace("/\[floatleft\](.*?)\[\/floatleft\]\n{0,1}/is", "<DIV style=\"MARGIN-RIGHT: 8px; FLOAT: left;\">$1</DIV>", $code);
	$code = preg_replace("/\[floatright\](.*?)\[\/floatright\]\n{0,1}/is", "<DIV style=\"MARGIN-LEFT: 8px; FLOAT: right;\">$1</DIV>", $code);

	// Schriftart größe farbe
	$code = preg_replace("/\[size=([0-9])\](.*?)\[\/size\]/is", "<font size=$1>$2</font>", $code);
	$code = preg_replace("/\[color=#{0,1}([0-9a-f]{6})\](.*?)\[\/color\]/is", "<span style=\"color:#$1\">$2</span>", $code);
	$code = preg_replace("/\[color=([a-z]*)\](.*?)\[\/color\]/is", "<span style=\"color:$1\">$2</span>", $code);
	$code = preg_replace("/\[font=(.*?)\](.*?)\[\/font\]/is", "<font face=\"$1\">$2</font>", $code);
	
	if (!$soft)
	{
		// Galerie
		$code = preg_replace_callback("/\[gallery(=){0,1}([0-9]*)(,){0,1}([0-9]*?)\]([0-9]*?)\[\/gallery\]\n{0,1}/is", "galleryTag", $code);	
		
		// Video
		$code = preg_replace_callback("/\[video(=){0,1}(.*?)\](.*?)\[\/video\]\n{0,1}/is", "videoTag", $code);
		
		// Umfrage
		$code = preg_replace_callback("/\[poll\]([0-9]*?)\[\/poll\]\n{0,1}/is", "pollTag", $code);
	}

	$code = preg_replace("/\n/is", "<br>", $code);

	$code = replaceSmilies($code);

	if (!$soft)
	{
		// Eigene Codes
		if ($FSXL[config][fscodes] > 0)	$code = replaceDynamicFSCode($code);
	}

	return $code;
}

// Eigene Codes ersetzen
function replaceDynamicFSCode($code)
{
	global $FSXL, $db;
	
	// Cache
	if ($FSXL[fscodes])
	{
		foreach ($FSXL[fscodes] AS $fscode)
		{
			$code = preg_replace($fscode[0], $fscode[1], $code);
		}
	}
	// Datenbank
	else
	{
		$FSXL[fscodes] = array();
		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_fscodes`");
		while ($fscode = mysql_fetch_assoc($index))
		{
			$fscode[code] = str_replace('{x}', '$3', $fscode[code]);
			$fscode[code] = str_replace('{y}', '$2', $fscode[code]);
			$pattern = "/\[$fscode[name](=){0,1}(.*?)\](.*?)\[\/$fscode[name]\]/is";
			$FSXL[fscodes][] = array($pattern, $fscode[code]);
			$code = preg_replace($pattern, $fscode[code], $code);
		}
	}
	
	return $code;
}

// Poll Tag ersetzen
function pollTag($treffer)
{
	global $db, $FSXL;
	
	// Umfrage lesen
	$index = mysql_query("SELECT p.id AS id, p.startdate AS startdate, p.enddate AS enddate, p.question AS question, SUM(a.hits) AS hits
				FROM $FSXL[tableset]_poll p, $FSXL[tableset]_poll_answers a 
				WHERE p.startdate <= '$FSXL[time]' AND p.id = '$treffer[1]' AND a.poll = p.id
				GROUP BY a.poll");
	if (mysql_num_rows($index) > 0)
	{
		// Template lesen
		$tag_tpl = new template('polltag');
		$tag_tpl->getItem('answer');

		$poll = mysql_fetch_assoc($index);
		if ($poll[hits] == 0) $poll[hits] = 1;
		$tag_tpl->replaceTplVar('{question}', $poll[question]);
		$tag_tpl->replaceTplVar('{fromdate}', date($FSXL[config][dateformat], $poll[startdate]));
		$tag_tpl->replaceTplVar('{todate}', date($FSXL[config][dateformat], $poll[enddate]));
		
		// Antworten
		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_poll_answers` WHERE `poll` = '$poll[id]' ORDER BY `hits` DESC");
		while ($answer = mysql_fetch_assoc($index))
		{
			$i++;
			$tag_tpl->newItemNode('answer');
			$tag_tpl->replaceNodeVar('{altnum}', $i%2==0?1:2);
			$tag_tpl->replaceNodeVar('{answer}', $answer[answer]);
			$tag_tpl->replaceNodeVar('{hits}', $answer[hits]);
			$tag_tpl->replaceNodeVar('{percent}', round($answer[hits]*100/$poll[hits]));
			$tag_tpl->replaceNodeVar('{width}', round($answer[hits]*100/$poll[hits])+1);
		}
		$tag_tpl->replaceItem('answer');

		$output = $tag_tpl->code;
		$output = str_replace("\n", '', $output);
	}
	// Umfrage nicht gefunden
	else
	{
		$output = '';
	}

	return $output;
}

// Video Tag ersetzen
function videoTag($treffer)
{
	global $db, $FSXL;
	
	if (preg_match("/^([0-9]*)$/i", $treffer[3], $match))
	{
		$index = mysql_query("SELECT `id`, `name`, `url` FROM `$FSXL[tableset]_videos` WHERE `id` = '$treffer[3]'");
		if (mysql_num_rows($index) > 0)
		{
			$video = mysql_fetch_assoc($index);
		}
	}
	else
	{
		$video = array();
		$video[url] = $treffer[3];
		$video[link] = '#';
		$video[name] = '';
	}

	// Wenn Video vorhanden
	if ($video)
	{
		// Template lesen
		$tag_tpl = new template('videotag');

		// Video Player Style
		$style = $FSXL[config][video_showplay] . ',';
		$style .= $FSXL[config][video_showstop] . ',';
		$style .= $FSXL[config][video_showseek] . ',';
		$style .= $FSXL[config][video_showtime] . ',';
		$style .= $FSXL[config][video_showvolbar] . ',';
		$style .= $FSXL[config][video_showmute] . ',';
		$style .= $FSXL[config][video_showfullscreen];
		
		$name = $treffer[2] ? $treffer[2] : $video[name];
		$link = $video[link] ? $video[link] : 'index.php?section=video&id='.$video[id];

		$tag_tpl->replaceTplVar('{name}', $name);
		$tag_tpl->replaceTplVar('{color}', $FSXL[config][video_color]);
		$tag_tpl->replaceTplVar('{video}', $video[url]);
		$tag_tpl->replaceTplVar('{style}', $style);
		$tag_tpl->replaceTplVar('{link}', $link);
		
		$output = $tag_tpl->code;
		$output = str_replace("\n", '', $output);
	}
	// Keine Galerie gefunden
	else
	{
		$output = '';
	}
	
	return $output;
}

// Gallery Tag ersetzen
function galleryTag($treffer)
{
	global $db, $FSXL;
	$index = mysql_query("SELECT `id`, `name`, `type`, `pics` FROM `$FSXL[tableset]_galleries` WHERE `id` = '$treffer[5]'");
	// Wenn Galerie vorhanden
	if (mysql_num_rows($index) > 0)
	{
		$gallery = mysql_fetch_assoc($index);
		
		// Template lesen
		$tag_tpl = new template('gallerytag');
		$tag_tpl->getItem('thumbnail');
	
		// Bildanzahl einstellen
		if (!$treffer[2]) $treffer[2] = 3;
		if ($gallery[pics] < $treffer[2]) $treffer[2] = $gallery[pics];
				
		// Bilder auslesen
		if ($gallery[type] == 1) {
			$order = 'ASC';
			$order2 = '<';
		}
		else {
			$order = 'DESC';
			$order2 = '>';
		}

		// Start
		if ($treffer[4] > 0) {
			$index = mysql_query("SELECT COUNT(`id`) AS `value` FROM `$FSXL[tableset]_gallerypics` 
									WHERE `galleryid` = '$gallery[id]' AND `position` $order2 $treffer[4] AND `release` < $FSXL[time]
									ORDER BY `position` $order");
			if (mysql_num_rows($index) > 0) {
				$start = mysql_result($index, 0, 'value');
			} else {
				$start = 0;
			}
		}
		else {
			$start = 0;
		}

		$index = mysql_query("SELECT `id`, `titel`, `date` FROM `$FSXL[tableset]_gallerypics` WHERE `galleryid` = '$gallery[id]' 
								ORDER BY `position` $order LIMIT $start, $treffer[2]");
		while ($pic = mysql_fetch_assoc($index))
		{
			$hash = md5($pic[date].$pic[id]);
			$tag_tpl->newItemNode('thumbnail');
			$tag_tpl->replaceNodeVar('{title}', $pic[titel]);
			$tag_tpl->replaceNodeVar('{detaillink}', '?section=gallery&detail='.$pic[id]);
			$tag_tpl->replaceNodeVar('{thumb}', 'images/gallery/'.$gallery[id].'/'.$hash.'s.jpg');
		}
		$tag_tpl->replaceItem('thumbnail');
		
		$tag_tpl->replaceTplVar('{galleryname}', $gallery[name]);
		$tag_tpl->replaceTplVar('{gallerylink}', '?section=gallery&id='.$gallery[id]);
		
		$output = $tag_tpl->code;
		$output = str_replace("\n", '', $output);
	}
	// Keine Galerie gefunden
	else
	{
		$output = '';
	}
	
	return $output;
}

function bb_age($treffer)
{
	$check = agecheck($treffer[1]);
	if ($check[0]) {
		return $treffer[2];
	}
	else {
		$age_tpl = new template('bb_ageblocker');
		$age_tpl->replaceTplVar('{time}', $check[1]);
		return $age_tpl->code;
	}
}

function ohtmlTable($treffer)
{
	// class
	if (preg_match("/class=\"{0,1}([a-zA-Z-0-9_\-]*)\"{0,1}/is", $treffer[1], $match)) $class = ' class="'.$match[1].'"';
	else $class = '';

	// padding
	if (preg_match("/padding=\"{0,1}([0-9]){1,}\"{0,1}/is", $treffer[1], $match)) $padding = ' cellpadding='.$match[1];
	else $padding = ' cellpadding=0';

	// size
	if (preg_match("/size=([0-9]*)|([0-9]*)/is", $treffer[1], $match))
	{
		$width = $match[1];
		$height = $match[2];
	}
	if (!$width)
	{
		if (preg_match("/size=([0-9]*)(%){0,1}/is", $treffer[1], $match))
		{
			$width = $match[1].$match[2];
			$size = ' width="'.$width.'"';
		}
		else
		{
			$size = ' width="100%"';
		}
	}
	else
	{
		$size = ' style="width:'.$width.'px; height:'.$height.'px;"';
	}

	$border = ' border=0';

	$string = '<table cellspacing=0'.$class.$padding.$size.$border.'><tbody>'.$treffer[2].'</tbody></table>';
	return $string;
}


?>