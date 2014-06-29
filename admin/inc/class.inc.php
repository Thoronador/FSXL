<?php

// IMG Converter
class imgConvert
{
	var $sourceimg = false;
	var $sourcewidth = 0;
	var $sourceheight = 0;
	var $sourceaspect = 0;
	var $sourcetype = false;
	var $output = false;
	var $filename = '';
	
	// Bild einlesen
	function readIMG($img)
	{
		// Bildtyp auswerten
		$imginfo = getimagesize($img['tmp_name']);
		$this->filename = $img['tmp_name'];
		switch ($imginfo[2])
		{
			case 2: // JPG
				$this->sourceimg = imagecreatefromjpeg($img['tmp_name']);
				$this->sourcetype = 'JPG';
				break;
			case 1: // GIF
				$this->sourceimg = imagecreatefromgif($img['tmp_name']);
				$this->sourcetype = 'GIF';
				break;
			case 3: // PNG
				$this->sourceimg = imagecreatefrompng($img['tmp_name']);
				imageAlphaBlending($this->sourceimg, false);
				imageSaveAlpha($this->sourceimg, true);
				$this->sourcetype = 'PNG';
				break;
			case 6: // BMP
			case 15: // WBMP
				$this->sourceimg = imagecreatefromwbmp($img['tmp_name']);
				$this->sourcetype = 'BMP';
				break;
			default:
				return false;
		}
		
		$this->sourcewidth = $imginfo[0];
		$this->sourceheight = $imginfo[1];
		$this->sourceaspect = $imginfo[0] / $imginfo[1];
		return true;
	}
	
	// Bild skalieren
	function scaleIMG($width, $height, $mode, $bgcolor, $transparent=false)
	{
		$width = $width==0 ? 1 : $width;
		$height = $height==0 ? 1 : $height;
	
		// Methode auswählen
		switch (strtoupper($mode))
		{
			case 'SCALE_TO_WIDTH':
				$height = round($width/$this->sourceaspect);
				$offset = array(0, 0, $width, $height);
				break;
			case 'SCALE_TO_HEIGHT':
				$width = round($height*$this->sourceaspect);
				$offset = array(0, 0, $width, $height);
				break;
			case 'LETTERBOX':
				if ($this->sourceaspect >= $width/$height) {
					$offset[2] = $width;
					$offset[3] = round($this->sourceheight / ($this->sourcewidth / $width));
					$offset[0] = 0;
					$offset[1] = round(($height - $offset[3]) / 2);
				}
				else {
					$offset[2] = round($this->sourcewidth / ($this->sourceheight / $height));
					$offset[3] = $height;
					$offset[0] = round(($width - $offset[2]) / 2);
					$offset[1] = 0;
				}
				break;
			case 'CROP':
				if ($width/$height >= $this->sourceaspect) {
					$offset[2] = $width;
					$offset[3] = round($this->sourceheight / ($this->sourcewidth / $width));
					$offset[0] = 0;
					$offset[1] = round(($height - $offset[3]) / 2);
				}
				else {
					$offset[2] = round($this->sourcewidth / ($this->sourceheight / $height));
					$offset[3] = $height;
					$offset[0] = round(($width - $offset[2]) / 2);
					$offset[1] = 0;
				}
				break;
			case 'RESIZE':
				if ($this->sourceaspect >= $width/$height) {
					$offset[0] = 0;
					$offset[1] = 0;
					$offset[2] = $width;
					$offset[3] = round($this->sourceheight / ($this->sourcewidth / $width));
					$height = $offset[3];
				}
				else {
					$offset[0] = 0;
					$offset[1] = 0;
					$offset[2] = round($this->sourcewidth / ($this->sourceheight / $height));
					$offset[3] = $height;
					$width = $offset[2];
				}
				break;
			default:
				return false;
		}
		
		// Bild erzeugen
		$this->outputimg = imagecreatetruecolor($width, $height);
		
		// Hintergrundfarbe
		$bg = $this->ImageColorAllocateFromHex($this->outputimg, $bgcolor);
		imagefill($this->outputimg, 0, 0, $bg);

		// Transparenter Hintergrund
		if ($transparent) {
			if ($this->sourcetype == 'PNG') {
				imageAlphaBlending($this->outputimg, false);
				imageSaveAlpha($this->outputimg, true);
				$alpha = imagecolorallocatealpha($this->outputimg, 0, 0, 0, 127);
				imagefill($this->outputimg, 0, 0, $alpha);
			}
			imagecolortransparent($this->outputimg, $bg);
		}
		
		// Quelle kopieren
		imagecopyresampled($this->outputimg, $this->sourceimg, $offset[0], $offset[1], 0, 0, $offset[2], $offset[3], $this->sourcewidth, $this->sourceheight);
	}
	
	// Bild speichern
	function saveIMG($destination, $filename, $type=false, $quality=85)
	{
		// Falls noch kein Bild vorhanden ist
		if ($this->outputimg == false) {
			$this->outputimg = $this->sourceimg;
		}
		
		// Ausgabeformat bestimmen
		$type = $type ? strtoupper($type) : $this->sourcetype;
		
		// Alte Bilder löschen
		@unlink($destination.$filename.'.png');
		@unlink($destination.$filename.'.gif');
		@unlink($destination.$filename.'.jpg');
		@unlink($destination.$filename.'.bmp');
		
		switch ($type)
		{
			case 'JPG':
				$chk = @imagejpeg($this->outputimg, $destination.$filename.'.jpg', $quality);
				break;
			case 'GIF':
				$chk = @imagegif($this->outputimg, $destination.$filename.'.gif');
				break;
			case 'PNG':
				$chk = @imagepng($this->outputimg, $destination.$filename.'.png');
				break;
			case 'BMP':
				$type = 'PNG';
				$chk = @imagepng($this->outputimg, $destination.$filename.'.png');
				break;
			case 'COPY':
				$type = $this->sourcetype;
				$chk = @move_uploaded_file($this->filename, $destination.$filename.'.'.strtolower($type));
				@chmod ($destination.$filename.'.'.$type, 0644);
				break;
			default:
				return false;
		}
		
		if ($chk) {
			$size = filesize($destination.$filename.'.'.strtolower($type));
			$solution = getimagesize($destination.$filename.'.'.strtolower($type));
			return array($solution[0], $solution[1], $size, $type);
		}
		// Bild konnte nicht erstellt werden
		else {
			return false;
		}
	}
	
	// Hexadezimalfarben umwandeln
	function ImageColorAllocateFromHex($img, $hexstr)
	{
		$int = hexdec($hexstr);
		return ImageColorAllocate ($img, 0xFF & ($int >> 0x10), 0xFF & ($int >> 0x8), 0xFF & $int);
	} 
}

// MYSQL
class mysql
{
	var $host = '';
	var $user = '';
	var $pass = '';
	var $data = '';
	var $dbconnect = '';
	var $error = array();

	// Verbindung aufbauen (Konstruktor)
	function mysql($host, $user, $pass, $data)
	{
		$this->host = $host;
		$this->user = $user;
		$this->pass = $pass;
		$this->data = $data;

		$this->dbconnect = @mysql_connect($this->host, $this->user, $this->pass);

		if (!($this->dbconnect))
		{
			$this->error[error] = true;
			$this->error[msg] = "error_db_nodbconnect";
			return false;
		}
		else
		{
			if($this->data == "")
			{
				$this->error[error] = true;
				$this->error[msg] = "error_db_nodb";
				return false;
			}
			else
			{
				if (!(mysql_select_db($this->data, $this->dbconnect)))
				{
					$this->error[error] = true;
					$this->error[msg] = "error_db_nodbselect";
					return false;
				}
				else
				{
					return true;
				}
			}
		}
	}

	// Verbingund trennen
	function close()
	{
		if (!($this->dbconnect))
		{
			$this->error[error] = true;
			$this->error[msg] = "error_db_nodbdisconnect";
			return false;
		}
		else
		{
			@mysql_close($this->dbconnect);
			return true;
		}
	}
}

// User
class user
{
	var $username = '';
	var $password = '';
	var $userid = '';
	var $error = array();
	var $access = array();
	var $email = '';
	var $adminstyle = '';
	var $editor = '';
	var $isadmin = false;
	var $cookielogin = true;
	var $logins = 0;
	var $adminlang = '';
	var $adminlangid = 0;

	// User überprüfen (Konstruktor)
	function user($name, $pass, $cookie, $admin)
	{
		global $FSXL, $_COOKIE, $_SESSION;

		if ($cookie == false) $this->cookielogin = false;

		// Login erlaubt?
		$_SESSION[loginerror] = false;
		$deltime = $FSXL[time] - ($FSXL[config][login_blocktime]*60);
		@mysql_query("DELETE FROM `$FSXL[tableset]_logins` WHERE `date` < '$deltime'");
		$index = @mysql_query("SELECT * FROM `$FSXL[tableset]_logins` WHERE `ip` = '$_SERVER[REMOTE_ADDR]'");
		$login = mysql_fetch_assoc($index);
		$this->logins = $login[trys];
		if (($login[trys] >= $FSXL[config][login_attempts]) && (mysql_num_rows($index) > 0))
		{
			$this->error[error] = true;
			$this->error[msg] = "error_login_timeout";
			return false;
		}
		else
		{
			// User vorhanden?
			$this->username = $name;
			$index = @mysql_query("SELECT * FROM `$FSXL[tableset]_user` WHERE `name` = '$this->username'");
			if (mysql_num_rows($index) == 0)
			{
				$this->error[error] = true;
				$this->error[msg] = "error_login_nouser";
				return false;
			}
			else
			{
				$userdata = mysql_fetch_assoc($index);

				// Passwort generieren und prüfen
				if ($this->cookielogin)
				{
					$this->password = $pass;
				}
				else
				{
					$this->password = md5($pass.$userdata[salt]);
				}
				$this->superadmin = $FSXL[superadmin];

				// Falsches Passwort
				if ($this->password != $userdata[password])
				{
					// Logins zählen
					$index = @mysql_query("INSERT INTO `$FSXL[tableset]_logins` (`ip`, `date`, `trys`) VALUES ('$_SERVER[REMOTE_ADDR]', '$FSXL[time]', 1)");
					if (!$index)
					{
						@mysql_query("UPDATE `$FSXL[tableset]_logins` SET `trys` = `trys` + 1, `date` = '$FSXL[time]' WHERE `ip` = '$_SERVER[REMOTE_ADDR]'");
					}

					$this->error[error] = true;
					$this->error[msg] = "error_login_wrongpass";
					return false;
				}
				else
				{
					$this->userid = $userdata[id];
					if ($admin)
					{
						if (($this->cookielogin && $FSXL[config][admin_cookielogin] == 1) || !$this->cookielogin)
						{
							$this->setAccess();
							$this->username = $userdata[name];
							$this->setUserdata();
							return true;
						}
					}
					else
					{
						$this->setAccess();
						$this->username = $userdata[name];
						$this->setUserdata();
						return true;
					}
				}
			}
		}
	}

	// User Zugänge auslesen
	function setAccess()
	{
		global $FSXL;

		// Superadmin
		if (in_array($this->userid, $this->superadmin))
		{
			$this->isadmin = true;
		}
		else
		{
			// Direkter Zugang
			$index = @mysql_query("SELECT * FROM `$FSXL[tableset]_useraccess` WHERE `userid` = ".$this->userid);
			if (mysql_num_rows($index) > 0)
			{
				$this->isadmin = true;
				while ($db = mysql_fetch_assoc($index))
				{
					$this->access[$db[mod]][$db[page]] = true;
				}
			}

			// Benutzergruppen
			$index = @mysql_query("SELECT * FROM `$FSXL[tableset]_user_groupconnect` WHERE `user` = ".$this->userid);
			while ($group = mysql_fetch_assoc($index))
			{
				$index2 = @mysql_query("SELECT * FROM `$FSXL[tableset]_user_groupaccess` WHERE `group` = '$group[group]'");
				if (mysql_num_rows($index2) > 0)
				{
					$this->isadmin = true;
					while ($db = mysql_fetch_assoc($index2))
					{
						$this->access[$db[mod]][$db[page]] = true;
					}
				}
			}
		}
	}

	function setUserdata()
	{
		global $FSXL;

		$index = @mysql_query("SELECT * FROM `$FSXL[tableset]_userdata` WHERE `userid` = ".$this->userid);
		$db = mysql_fetch_assoc($index);
		$this->email = $db[email];
		$this->adminstyle = $db[adminstyle];
		$this->editor = $db[editor];
		$this->style = $db[style];
		$this->homepage = $db[homepage];
		$this->icq = $db[icq];
		$this->msn = $db[msn];
		$this->regdate = $db[regdate];

		if ($db[adminlang] == 0) {
			$this->adminlang = $FSXL[config][syslanguage];
			$this->adminlangid = 0;
		} else {
			foreach ($FSXL[languages] AS $key => $lang) {
				if ($lang[0] == $db[adminlang]) {
					$this->adminlang = $key;
					$this->adminlangid = $lang[0];
					break;
				}
			}
		}
	}
}

// Template
class template
{
	var $code = '';
	var $items = array();
	var $item_tpls = array();
	var $lastnodename = '';
	var $list = array();

	// Template lesen
	function template($name, $direct = false)
	{
		global $FSXL;

		if (!$direct)
		{
			// Aus Datenbank
			if ($FSXL[config][use_tpl_cache] == 0)
			{
				$index = @mysql_query("SELECT `code` FROM `$FSXL[tableset]_templates` WHERE `styleid` = '$FSXL[style]' AND `shortcut` = '$name'");
				$this->code = @mysql_result($index, 0, 'code');
			}
			// AUs Cache
			else
			{
				// APC Cache
				if (function_exists('apc_cache_info'))
				{
					$apc_name = $FSXL[config][bez].'/tpl/'.$FSXL[style].'_'.$name.'.tpl';
					$this->code = apc_fetch($apc_name);


					if (strlen($this->code) == 0)
					{
						$index = @mysql_query("SELECT `code` FROM `$FSXL[tableset]_templates` WHERE `styleid` = '$FSXL[style]' AND `shortcut` = '$name'");
						$this->code = @mysql_result($index, 0, 'code');
						apc_store($apc_name, $this->code);

					}
				}
				else
				{
					if (file_exists('tpl/'.$FSXL[style].'_'.$name.'.tpl'))
					{
						$this->code = implode('', file('tpl/'.$FSXL[style].'_'.$name.'.tpl'));
					}
					// Cache anlegen
					else
					{
						$index = @mysql_query("SELECT `code` FROM `$FSXL[tableset]_templates` WHERE `styleid` = '$FSXL[style]' AND `shortcut` = '$name'");
						$this->code = @mysql_result($index, 0, 'code');
						$fp = fopen('tpl/'.$FSXL[style].'_'.$name.'.tpl', 'w');
						fwrite($fp, $this->code);
						fclose($fp);
					}
				}
			}
		}
		else
		{
			$this->code = $name;
		}
	}

	// Liste ergänzen
	function newListItem()
	{
		array_push($this->list, $this->code);
	}

	// Listen Eintrag ersetzen
	function replaceListItem($name)
	{
		$searchstring = '/<-- '.$name.' -->(.*?)<-- \/'.$name.' -->/is';
		$code = implode('', $this->item_tpls[$name]);
		$tmpcode = array_pop($this->list);
		$tmpcode = preg_replace($searchstring, $code, $tmpcode);
		array_push($this->list, $tmpcode);
		$this->item_tpls[$name] = array();
	}

	// Listen Variable ersetzen
	function replaceListVar($var, $code)
	{
		$tmpcode = array_pop($this->list);
		$tmpcode = str_replace($var, $code, $tmpcode);		
		array_push($this->list, $tmpcode);
	}

	// Liste formatieren
	function collapseList()
	{
		$this->code = implode('', $this->list);
	}

	// Item auslesen
	function getItem($name, $inlist=false)
	{
		$searchstring = '/<-- '.$name.' -->(.*?)<-- \/'.$name.' -->/is';
		if ($inlist)
		{
			$tmpcode = array_pop($this->list);
			preg_match($searchstring, $tmpcode, $tpl);	
			array_push($this->list, $tmpcode);
		}
		else
		{
			preg_match($searchstring, $this->code, $tpl);
		}
		$this->items[$name] = $tpl[1];
		$this->item_tpls[$name] = array();
	}

	// Neuen Item Eintrag hinzufügen
	function newItemNode($tpl, $code='')
	{
		$code = $code ? $code : $tpl;
		array_push($this->item_tpls[$tpl], $this->items[$code]);
		$this->lastnodename = $tpl;
	}

	// Variable in Eintrag ersetzen
	function replaceNodeVar($var, $code)
	{
		$tmpcode = array_pop($this->item_tpls[$this->lastnodename]);
		$tmpcode = str_replace($var, $code, $tmpcode);		
		array_push($this->item_tpls[$this->lastnodename], $tmpcode);
	}

	// Item ersetzen
	function replaceItem($name)
	{
		$searchstring = '/<-- '.$name.' -->(.*?)<-- \/'.$name.' -->/is';
		$code = implode('', $this->item_tpls[$name]);
		$this->code = preg_replace($searchstring, $code, $this->code);
		$this->item_tpls[$name] = array();
	}

	// Item löschen
	function clearItem($name)
	{
		$searchstring = '/<-- '.$name.' -->(.*?)<-- \/'.$name.' -->/is';
		$this->code = preg_replace($searchstring, '', $this->code);
	}

	// Variable in Template ersetzen
	function replaceTplVar($var, $code)
	{
		$this->code = str_replace($var, $code, $this->code);		
	}

	// String suchen
	function match($string)
	{
		if (strpos($this->code, $string))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	// Template Bedingung
	function switchCondition($condition, $state, $lastnode = false)
	{
		// IF - ELSE
		$searchstring = '/<-- if '.$condition.' -->(.*?)<-- else '.$condition.' -->(.*?)<-- \/if '.$condition.' -->/is';
		if ($state == true)
		{
			$replacestring = '\\1';
		}
		else
		{
			$replacestring = '\\2';
		}
		// Node
		if ($lastnode == true)
		{
			$tmpcode = array_pop($this->item_tpls[$this->lastnodename]);
			$tmpcode = preg_replace($searchstring, $replacestring, $tmpcode);
			array_push($this->item_tpls[$this->lastnodename], $tmpcode);
		}
		// Template
		else
		{
			$this->code = preg_replace($searchstring, $replacestring, $this->code);
		}

		// IF
		$searchstring = '/<-- if '.$condition.' -->(.*?)<-- \/if '.$condition.' -->/is';
		if ($state == true)
		{
			$replacestring = '\\1';
		}
		else
		{
			$replacestring = '';
		}
		// Node
		if ($lastnode == true)
		{
			$tmpcode = array_pop($this->item_tpls[$this->lastnodename]);
			$tmpcode = preg_replace($searchstring, $replacestring, $tmpcode);
			array_push($this->item_tpls[$this->lastnodename], $tmpcode);
		}
		// Template
		else
		{
			$this->code = preg_replace($searchstring, $replacestring, $this->code);
		}
	}

	// Template Bedingung
	function switchListCondition($condition, $state)
	{
		$tmpcode = array_pop($this->list);

		// IF - ELSE
		$searchstring = '/<-- if '.$condition.' -->(.*?)<-- else '.$condition.' -->(.*?)<-- \/if '.$condition.' -->/is';
		if ($state == true)
		{
			$replacestring = '\\1';
		}
		else
		{
			$replacestring = '\\2';
		}
		$tmpcode = preg_replace($searchstring, $replacestring, $tmpcode);

		// IF
		$searchstring = '/<-- if '.$condition.' -->(.*?)<-- \/if '.$condition.' -->/is';
		if ($state == true)
		{
			$replacestring = '\\1';
		}
		else
		{
			$replacestring = '';
		}
		$tmpcode = preg_replace($searchstring, $replacestring, $tmpcode);

		array_push($this->list, $tmpcode);
	}
}

// Admin Page
class adminPage
{
	var $code = '';
	var $currentTblCols = 2;

	// Template lesen
	function adminPage()
	{
	}

	// Form öffnen
	function openForm($action, $name=false, $onsubmit=false, $enctype=false, $method=false)
	{
		$name = $name ? 'name="'.$name.'"' : '';
		$enctype = $enctype ? 'enctype="multipart/form-data"' : '';
		$onsubmit = $onsubmit ? 'onSubmit="return '.$onsubmit.'()"' : '';
		$method = $method ? 'get' : 'post';
		$this->code .= '<form action="'.$action.'" '.$name.' '.$enctype.' method="'.$method.'" '.$onsubmit.'>'."\n";
	}

	// Form schließen
	function closeForm()
	{
		$this->code .= "</form>\n";
	}

	// Inputfeld Hidden
	function newHiddenInput($name, $value)
	{
		$this->code .= '<input type="hidden" name="'.$name.'" value="'.$value.'">'."\n";
	}

	// Tabelle öffnen
	function openTable($cols=2)
	{
		$this->code .= '<table border="0" cellpadding="2" cellspacing="1" width="90%" style="margin:0px auto;">'."\n";
		$this->currentTblCols = $cols;
	}

	// Tabelle schließen
	function closeTable()
	{
		$this->code .= '</table>';
	}

	// Tabellen Headline
	function newTblHeadline($title)
	{
		$this->code .= "\t<tr>\n";
		$this->code .= "\t\t".'<td colspan="'.$this->currentTblCols.'"><span style="font-size:12pt;"><b>'.$title.'</b></span><hr></td>'."\n";
		$this->code .= "\t</tr>\n";
	}

	// Tabellen Select öffnen
	function openTblSelect($title, $name, $width=false, $sub='', $color='')
	{
		if ($color) $col = 'style="background-color:#'.$color.';"';
		$this->code .= "\t<tr>\n";
		$this->code .= "\t\t<td $col><b>$title:</b><br>$sub</td>\n";
		$this->code .= "\t\t<td width=\"50\" $col>\n";
		$width = $width ? $width.'px' : '400px';
		$this->code .= "\t\t\t".'<select name="'.$name.'" class="textinput" style="width:'.$width.';">'."\n";
	}

	// Tabellen Select schließen
	function closeTblSelect()
	{
		$this->code .= "\t\t\t</select>\n";
		$this->code .= "\t\t</td>\n";
		$this->code .= "\t</tr>\n";
	}

	// Tabellen Select Option
	function newSelectOption($value, $name, $selected=false)
	{
		$this->code .= "\t\t\t\t".'<option value="'.$value.'" '.($selected ? 'selected' : '').'>'.$name.'</option>'."\n";
	}

	// Tabellen Checkbox
	function newTblCheckbox($title, $name, $checked=false, $sub='', $color='')
	{
		if ($color) $col = 'style="background-color:#'.$color.';"';
		$this->code .= "\t<tr>\n";
		$this->code .= "\t\t<td $col><b>$title:</b><br>$sub</td>\n";
		$this->code .= "\t\t".'<td width="50" '.$col.'><input type="checkbox" name="'.$name.'" '.($checked ? 'checked' : '').'></td>'."\n";
		$this->code .= "\t</tr>\n";
	}

	// Tabellen Input Text
	function newTblInput($title, $name, $value='', $width=false, $sub='', $color='')
	{
		if ($color) $col = 'style="background-color:#'.$color.';"';
		$this->code .= "\t<tr>\n";
		$this->code .= "\t\t<td $col><b>$title:</b><br>$sub</td>\n";
		$width = $width ? $width.'px' : '400px';
		$this->code .= "\t\t".'<td width="50" '.$col.'><input name="'.$name.'" class="textinput" style="width:'.$width.';" value="'.$value.'"></td>'."\n";
		$this->code .= "\t</tr>\n";
	}

	// Tabellen Passwort
	function newTblPassword($title, $name, $value='', $width=false, $sub='', $color='')
	{
		if ($color) $col = 'style="background-color:#'.$color.';"';
		$this->code .= "\t<tr>\n";
		$this->code .= "\t\t<td $col><b>$title:</b><br>$sub</td>\n";
		$width = $width ? $width.'px' : '400px';
		$this->code .= "\t\t".'<td width="50" '.$col.'><input type="password" name="'.$name.'" class="textinput" style="width:'.$width.';" value="'.$value.'"></td>'."\n";
		$this->code .= "\t</tr>\n";
	}

	// Tabellen Leerzeile
	function newTblSpacer()
	{
		$this->code .= "\t".'<tr><td colspan="'.$this->currentTblCols.'">&nbsp;</td></tr>'."\n";
	}

	// Tabellen Leerzeile
	function newTblSubmitButton($value)
	{
		$this->code .= "\t<tr>\n";
		$this->code .= "\t\t".'<td colspan="'.$this->currentTblCols.'" align="right"><input type="submit" class="button" value="'.$value.'"></td>'."\n";
		$this->code .= "\t</tr>\n";
	}

	// Message Box
	function newMsgBox($text)
	{
		$this->code .= '<div align="center" style="padding:20px;">'.$text.'</div>';
	}

	// Tabellen Text
	function newTblText($title, $text, $sub='', $color='')
	{
		if ($color) $col = 'style="background-color:#'.$color.';"';
		$this->code .= "\t<tr>\n";
		$this->code .= "\t\t<td $col><b>$title:</b><br>$sub</td>\n";
		$this->code .= "\t\t".'<td width="50" '.$col.'>'.$text.'</td>'."\n";
		$this->code .= "\t</tr>\n";
	}
}

// vBulletin 3.8.1
class vbConnect
{
	var $host = '';
	var $path = '';
	var $user = '';
	var $userid = '';
	var $password = '';
	var $cookie = array();
	
	// Einloggen
	function vbConnect ($url, $user, $password)
	{
		// Variabeln belegen
		$this->user = $user;
		$this->password = $password;
		
		preg_match("/(http:\/\/){0,1}([a-zA-Z0-9]*)\.([a-zA-Z0-9]*)\.([a-zA-Z]*)(\/){0,1}(.*)/i", $url, $match);
		$this->host = $match[2].'.'.$match[3].'.'.$match[4];
		if (substr($match[6], -1) == '/') {
			$this->path = substr($match[6], 0, -1);
		}
		else {
			$this->path = $match[6];
		}
		
		// Loginstring erzeugen
		$data_to_send = 'vb_login_username=' . $this->user;
		$data_to_send .= '&vb_login_password=' . $this->password;
		$data_to_send .= '&s=&do=login&vb_login_md5password=&vb_login_md5password_utf=';
		$page = 'login.php?do=login';
		
		// Daten senden
		$data = $this->sendAndLoadData($page, $data_to_send);
		$this->getCookieData($data[0]);
		
		// Userid auslesen
		preg_match("/IDstack=\%2C([0-9]*?)\%2C/i", $data[0], $match);
		$this->userid = $match[1];
	}
	
	// Neuen Thread erstellen
	function newThread($forumid, $title, $text, $closed=false)
	{
		$token = $this->getToken('newthread.php?do=newthread&f='.$forumid);

		// String erzeugen
		$data_to_send = 'subject=' . urlencode($title);
		$data_to_send .= '&message=' . urlencode($text);
		$data_to_send .= '&wysiwyg=0&iconid=0&s=';
		$data_to_send .= '&securitytoken=' . $token[0];
		$data_to_send .= '&f=' . $forumid;
		$data_to_send .= '&do=postthread';
		$data_to_send .= '&posthash=' . $token[1];
		$data_to_send .= '&poststarttime=' . (time()-10);
		$data_to_send .= '&loggedinuser=' . $this->userid;
		$data_to_send .= '&parseurl=1';
		if ($closed) {
			$data_to_send .= '&openclose=1';
		}
		$page = 'newthread.php?do=postthread&f=' . $forumid;
		
		// Daten senden
		$data = $this->sendAndLoadData($page, $data_to_send);
		
		// Post ID einlesen
		preg_match("/\?p=([0-9]*)/is", $data[0], $match);
		
		return $match[1];
	}
	
	// Post editieren
	function editPost($postid, $title, $text, $closed=false)
	{
		// Token lesen
		$token = $this->getToken('editpost.php?do=editpost&p='.$postid);

		// Strings erzeugen
		$data_to_send = 'title=' . urlencode($title);
		$data_to_send .= '&message=' . urlencode($text);
		$data_to_send .= '&wysiwyg=0&iconid=0&s=';
		$data_to_send .= '&securitytoken=' . $token[0];
		$data_to_send .= '&do=updatepost';
		$data_to_send .= '&p=' . $postid;
		$data_to_send .= '&posthash=' . $token[1];
		$data_to_send .= '&poststarttime=' . (time()-10);
		if ($closed) {
			$data_to_send .= '&openclose=1';
		}
		$page = 'editpost.php?do=updatepost&p=' . $postid;

		// Daten senden
		$data = $this->sendAndLoadData($page, $data_to_send);
		
		if (preg_match("/\?p=([0-9]*)/is", $data[0])) {
			return true;
		}
		else {
			return false;
		}
	}
	
	// Post löschen
	function deletePost($postid)
	{
		// Token lesen
		$token = $this->getToken('editpost.php?do=editpost&p='.$postid);

		// Strings erzeugen
		$data_to_send .= '&s=';
		$data_to_send .= '&securitytoken=' . $token[0];
		$data_to_send .= '&p=' . $postid;
		$data_to_send .= '&do=deletepost';
		$data_to_send .= '&deletepost=delete';
		$page = 'editpost.php?do=deletepost&p=' . $postid;

		// Daten senden
		$data = $this->sendAndLoadData($page, $data_to_send);
		
		if (preg_match("/\?f=([0-9]*)/is", $data[0])) {
			return true;
		}
		else {
			return false;
		}
	}
	
	// Security Token einlesen
	function getToken($page)
	{		
		// Daten senden
		$data = $this->sendAndLoadData($page, '', 'GET');
		
		// Token auslesen
		preg_match("/name=\"securitytoken\" value=\"([a-z0-9-]*)\"/is", $data[1], $token);
		preg_match("/name=\"posthash\" value=\"([a-z0-9]*)\"/is", $data[1], $hash);
		
		return array($token[1], $hash[1]);
	}
	
	// Verbindung aufbauen
	function sendAndLoadData($page, $data_to_send='', $type='POST')
	{
		// Verbindung aufbauen
		$fp = fsockopen($this->host, 80);
		fputs($fp, "$type /".$this->path."/$page HTTP/1.1\r\n");
		fputs($fp, "Host: ".$this->host."\r\n");
		fputs($fp, "Cookie: ");
		if (!empty($this->cookie))
		{
			foreach ($this->cookie as $key => $val) {
				$pair = $key.'='.urlencode($val).';';
				fputs($fp, $pair);
			}
		}
		fputs($fp, "\r\n");
		fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
		fputs($fp, "Content-length: ". strlen($data_to_send) ."\r\n");
		fputs($fp, "Connection: close\r\n\r\n");
		fputs($fp, $data_to_send);

		// Daten empfangen
		$header = '';
		$content = '';

		$bHead = TRUE;
		while (!feof($fp))
		{
			$buf = fgets($fp);
			if ($bHead) {
				$header .= $buf;
				$buf = trim($buf);
				if (empty($buf))
					$bHead = FALSE;
			}
			else
				$content .= $buf;
		}
		
		// Verbindung schließen
		fclose($fp);
		
		return array($header, $content);
	}
	
	// Cookie einlesen
	function getCookieData($header)
	{
		if (preg_match_all("/Set-Cookie: ([^=]*)=([^,;\r\n ]*)/s", $header, $match))
		{
			for ($i=0; $i<count($match[0]); $i++)
				$cookies[$match[1][$i]] = urldecode($match[2][$i]);
		}
		$this->cookie = $cookies;
	}
}

?>