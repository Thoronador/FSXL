<?php

if($_POST[direction] == 'HTMLToBB')
{
	$code = str_replace ('u|n|d', '&', $_POST[code]);
	$code = str_replace ('&nbsp;', ' ', $code);
	$code = stripslashes($code);
	$code = preg_replace("/(\n\r|\r\n|\n|\r)/is", "\n", $code);


	///////////////////
	//// Firefox 3 ////
	///////////////////

	// Farbe zu Hex
	$code = preg_replace_callback("/rgb\(([0-9]{1,3}),\s*([0-9]{1,3}),\s*([0-9]{1,3})\)/is", "rgbToHex", $code);
	
	// Text design
	$tags = array('span', 'div', 'br', 'p', 'DIV', 'SPAN', 'BR', 'P');
	preg_match_all("/\<([\/a-zA-Z]*)\s*(.*?)\>/is", $code, $hits, PREG_OFFSET_CAPTURE); 
	foreach ($hits[1] as $key => $tag)
	{
		if (in_array($tag[0],$tags))
		{ 
			$openers[] = $tag; 
			$additionals[] = isset($hits[2][$key][0]) ? $hits[2][$key][0] : '';  
		} 
		elseif ($tag[0][0] == '/' AND in_array(substr($tag[0],1),$tags))
		{ 
			$last = array_pop($openers); 
			if ($last[0] == substr($tag[0],1))
			{
				$add = array_pop($additionals);
				$open = '';
				$close = '';
				if (preg_match("/font-weight:\s*bold;{0,1}/is", $add))
				{
					$open = '[b]'.$open;
					$close .= '[/b]';
				}
				if (preg_match("/font-style:\s*italic;{0,1}/is", $add))
				{
					$open = '[i]'.$open;
					$close .= '[/i]';
				}
				if (preg_match("/underline/is", $add))
				{
					$open = '[u]'.$open;
					$close .= '[/u]';
				}
				if (preg_match("/line-through/is", $add))
				{
					$open = '[s]'.$open;
					$close .= '[/s]';
				}
				if (preg_match("/font-family:\s*\'{0,1}([a-zA-Z0-9\s\-_]*)\'{0,1};{0,1}/is", $add, $match))
				{
					$open = '[font='.$match[1].']'.$open;
					$close .= '[/font]';
				}
				if (preg_match("/color:\s*(#[0-9a-f]{6});{0,1}/is", $add, $match))
				{
					$open = '[color='.$match[1].']'.$open;
					$close .= '[/color]';
				}
				if (preg_match("/text-align:\s*center;{0,1}/is", $add, $match))
				{
					$open = '[center]'.$open;
					$close .= '[/center]';
				}
				if (preg_match("/text-align:\s*right;{0,1}/is", $add, $match))
				{
					$open = '[right]'.$open;
					$close .= '[/right]';
				}
				if (preg_match("/text-align:\s*justify;{0,1}/is", $add, $match))
				{
					$open = '[block]'.$open;
					$close .= '[/block]';
				}
				if (preg_match("/float:\s*left;{0,1}/is", $add, $match))
				{
					$open = '[floatleft]'.$open;
					$close .= '[/floatleft]';
				}
				if (preg_match("/float:\s*right;{0,1}/is", $add, $match))
				{
					$open = '[floatright]'.$open;
					$close .= '[/floatright]';
				}
	
				$pairs[] = array('opentag' => $last[0], 'offset' => $last[1], 'additional' => $add, 'bbtag' => $open); 
				$pairs[] = array('closetag' => $tag[0], 'offset' => $tag[1], 'bbtag' => $close); 
			} 
			else array_push($openers,$last); 
		} 
	}
	if ($pairs)
	{
		uasort($pairs, "sortByOffset");
		foreach ($pairs as $pair)
		{ 
			if (isset($pair['opentag']))
			{ 
				$code = substr_replace($code, $pair['bbtag'], $pair['offset']-1, 0); 
			} 
			else
			{ 
				$code = substr_replace($code, $pair['bbtag'], $pair['offset']-1, 0); 
			} 
		} 
	}
	$code = preg_replace("/\n\[floatleft\]/is", "[floatleft]", $code);
	$code = preg_replace("/\n\[floatright\]/is", "[floatright]", $code);

	// HP Pfad
	$hppath = 'http://' . $_SERVER["HTTP_HOST"] . substr($_SERVER["REQUEST_URI"], 0, strlen($_SERVER["REQUEST_URI"])-strlen('admin/frogpad/bbcodeparser.php'));
	$code = str_replace($hppath, "", $code);
	
	// Texteinrückung
	while (preg_match("/style=\"(.*)margin-left:\s*([0-9]{2,})px;{0,1}\s{0,1}(.*?)\">(.*?)<\//is", $code) == 1)
	{
		$code = preg_replace("/style=\"(.*)margin-left:\s*40px;{0,1}\s{0,1}(.*?)\">(.*?)<\//is", "style=\"$1$2\">[dir]$3[/dir]</", $code);
		$code = preg_replace("/style=\"(.*)margin-left:\s*80px;{0,1}\s{0,1}(.*?)\">(.*?)<\//is", "style=\"$1$2\">[dir][dir]$3[/dir][/dir]</", $code);
		$code = preg_replace("/style=\"(.*)margin-left:\s*120px;{0,1}\s{0,1}(.*?)\">(.*?)<\//is", "style=\"$1$2\">[dir][dir][dir]$3[/dir][/dir][/dir]</", $code);
	}


	//////////////
	//// IE 7 ////
	//////////////

	// Zeilenumbrüche
	$code = preg_replace("/<br>/is", "\n", $code);
	$code = preg_replace("/<br \/>/is", "\n", $code);

	// Text Design
	$code = preg_replace("/<strong>(.*?)<\/strong>/is", "[b]$1[/b]", $code);
	$code = preg_replace("/<b>(.*?)<\/b>/is", "[b]$1[/b]", $code);
	$code = preg_replace("/<u>(.*?)<\/u>/is", "[u]$1[/u]", $code);
	$code = preg_replace("/<em>(.*?)<\/em>/is", "[i]$1[/i]", $code);
	$code = preg_replace("/<strike>(.*?)<\/strike>/is", "[s]$1[/s]", $code);

	// Texteinrückung
	while (preg_match("/<BLOCKQUOTE(.*?)>\n*(.*?)\n*<\/BLOCKQUOTE>/is", $code) == 1)
	{
		$code = preg_replace("/<BLOCKQUOTE(.*?)>\n*(.*?)\n*<\/BLOCKQUOTE>/is", "[dir]$2[/dir]", $code);
	}

	// Textausrichtung
	$code = preg_replace("/\salign=left/is", "", $code);
	while (preg_match("/<p align=\"{0,1}center\"{0,1}>(.*?)<\/p>/is", $code) == 1)
	{
		$code = preg_replace("/<p align=\"{0,1}center\"{0,1}>(.*?)<\/p>/is", "[center]$1[/center]", $code);
	}
	while (preg_match("/<div align=\"{0,1}center\"{0,1}>(.*?)<\/div>/is", $code) == 1)
	{
		$code = preg_replace("/<div align=\"{0,1}center\"{0,1}>(.*?)<\/div>/is", "[center]$1[/center]", $code);
	}
	$code = preg_replace("/<p align=\"{0,1}right\"{0,1}>(.*?)<\/p>/is", "[right]$1[/right]", $code);
	$code = preg_replace("/<p align=\"{0,1}justify\"{0,1}>(.*?)<\/p>/is", "[block]$1[/block]", $code);
	$code = preg_replace("/<div align=\"{0,1}right\"{0,1}>(.*?)<\/div>/is", "[right]$1[/right]", $code);
	$code = preg_replace("/<div align=\"{0,1}justify\"{0,1}>(.*?)<\/div>/is", "[block]$1[/block]", $code);

	// Links
	$code = preg_replace_callback("/<A(.*?)>(.*?)<\/A>/is", "bbURL", $code);
	$code = preg_replace("/\[url=admin\//is", "[url=", $code);
	
	// HR
	$code = preg_replace("/\n{0,1}<HR(.*?)>(<\/hr>){0,1}\n{0,1}/is", "\n---\n", $code);

	// Images
	$code = preg_replace_callback("/<img(.*?)>/is", "bbImage", $code);

	// Listen
	$code = preg_replace("/\n{0,1}<OL>(.*?)\n*<\/OL>/is", "[list numbers]$1\n[/list]", $code);
	$code = preg_replace("/\n{0,1}<UL>(.*?)\n*<\/UL>/is", "[list]$1\n[/list]", $code);
	$code = preg_replace("/\n*<LI>(.*?)<\/LI>/is", "\n[*]$1", $code);
	$code = preg_replace("/\n*<LI>/is", "\n[*]", $code);

	// Tabellen
	$code = preg_replace("/<TBODY>(.*?)<\/TBODY>/is", "$1", $code);
	$code = preg_replace_callback("/<table(.*?)>\n*(.*?)<\/table>/is", "bbTable", $code);
	$code = preg_replace("/<TR>\n*(.*?)<\/TR>\n*/is", "[tr]\n$1[/tr]\n", $code);
	$code = preg_replace("/<TD>(.*?)<\/TD>\n*/is", "[td]$1[/td]\n", $code);
	$code = preg_replace("/<TD class=\"{0,1}(.*?)\"{0,1}>(.*?)<\/TD>\n*/is", "[td $1]$2[/td]\n", $code);

	// Schriftart größe farbe
	$code = preg_replace("/\ssize=\"{0,1}([0-9])\"{0,1}>(.*?)<\/font>/is", ">[size=$1]$2[/size]</font>", $code);
	$code = preg_replace("/\scolor=\"{0,1}#{0,1}([0-9a-f]{6})\"{0,1}>(.*?)<\/font>/is", ">[color=$1]$2[/color]</font>", $code);
	$code = preg_replace("/\scolor=([a-z]*)>(.*?)<\/font>/is", ">[color=$1]$2[/color]</font>", $code);
	$code = preg_replace("/\sface=\"{0,1}(.*?)\"{0,1}>(.*?)<\/font>/is", ">[font=$1]$2[/font]</font>", $code);
	$code = preg_replace("/<font>/is", "", $code);
	$code = preg_replace("/<\/font>/is", "", $code);

	////////////////
	//// Safari ////
	////////////////

	$code = preg_replace("/\s*class=\"Apple-style-span\"/is", "", $code);
	$code = preg_replace("/style=\"(.*)font-size:\s*x-small;{0,1}\s{0,1}(.*?)\">(.*?)<\//is", "style=\"$1$2\">[size=1]$3[/size]</", $code);
	$code = preg_replace("/style=\"(.*)font-size:\s*small;{0,1}\s{0,1}(.*?)\">(.*?)<\//is", "style=\"$1$2\">$3</", $code);
	$code = preg_replace("/style=\"(.*)font-size:\s*medium;{0,1}\s{0,1}(.*?)\">(.*?)<\//is", "style=\"$1$2\">[size=3]$3[/size]</", $code);
	$code = preg_replace("/style=\"(.*)font-size:\s*large;{0,1}\s{0,1}(.*?)\">(.*?)<\//is", "style=\"$1$2\">[size=4]$3[/size]</", $code);
	$code = preg_replace("/style=\"(.*)font-size:\s*x-large;{0,1}\s{0,1}(.*?)\">(.*?)<\//is", "style=\"$1$2\">[size=5]$3[/size]</", $code);
	$code = preg_replace("/style=\"(.*)font-size:\s*xx-large;{0,1}\s{0,1}(.*?)\">(.*?)<\//is", "style=\"$1$2\">[size=6]$3[/size]</", $code);
	$code = preg_replace("/style=\"(.*)font-size:\s*-webkit-xxx-large;{0,1}\s{0,1}(.*?)\">(.*?)<\//is", "style=\"$1$2\">[size=7]$3[/size]</", $code);

	////////////////////////////

	// Rückstände
	$code = preg_replace("/\s*style=\"(.*?)\"\s*/is", "", $code);
	$code = preg_replace("/<span>/is", "", $code);
	$code = preg_replace("/<\/span>/is", "", $code);
	$code = preg_replace("/<div>/is", "", $code);
	$code = preg_replace("/<\/div>/is", "", $code);
	$code = preg_replace("/<p>(.*?)<\/p>/is", "$1", $code);

	$code = preg_replace("/\n\[center\]/is", "[center]", $code);
	$code = preg_replace("/\n\[right\]/is", "[right]", $code);
	$code = preg_replace("/\n\[block\]/is", "[block]", $code);

	$code = preg_replace("/^\s*/is", "", $code);
	$code = preg_replace("/\s*$/is", "", $code);
	echo $code;
}

if($_POST[direction] == 'BBToHTML')
{
	$code = str_replace ('u|n|d', '&', $_POST[code]);
	$code = stripslashes($code);


	// Zeilenumbrüche
	$code = preg_replace("/(\n\r|\r\n|\n|\r)/is", "\n", $code);

	// Text Design
	$code = preg_replace("/\[b\](.*?)\[\/b\]/is", "<strong>$1</strong>", $code);
	$code = preg_replace("/\[u\](.*?)\[\/u\]/is", "<u>$1</u>", $code);
	$code = preg_replace("/\[i\](.*?)\[\/i\]/is", "<em>$1</em>", $code);
	$code = preg_replace("/\[s\](.*?)\[\/s\]/is", "<strike>$1</strike>", $code);

	// Texteinrückung
	while (preg_match("/\[dir\](.*?)\n*\[\/dir\]/is", $code) == 1)
	{
		$code = preg_replace("/\[dir\](.*?)\n*\[\/dir\]/is", "<BLOCKQUOTE>$1</BLOCKQUOTE>", $code);
	}

	// Textausrichtung
	while (preg_match("/\[center\](.*?)\[\/center\]/is", $code) == 1)
	{
		$code = preg_replace("/\[center\](.*?)\[\/center\]/is", "<div align=center>$1</div>", $code);
	}
	$code = preg_replace("/\[right\](.*?)\[\/right\]/is", "<div align=right>$1</div>", $code);
	$code = preg_replace("/\[block\](.*?)\[\/block\]/is", "<div align=justify>$1</div>", $code);

	// Links
	$code = preg_replace("/\[url=\"{0,1}www.(.*?)\"{0,1} blank\](.*?)\[\/url\]/is", "<A href=\"http://www.$1\" target=_blank>$2</A>", $code);
	$code = preg_replace("/\[url=\"{0,1}(.*?)\"{0,1} blank\](.*?)\[\/url\]/is", "<A href=\"$1\" target=_blank>$2</A>", $code);
	$code = preg_replace("/\[url=\"{0,1}www.(.*?)\"{0,1}\](.*?)\[\/url\]/is", "<A href=\"http://www.$1\" target=_self>$2</A>", $code);
	$code = preg_replace("/\[url=\"{0,1}(.*?)\"{0,1}\](.*?)\[\/url\]/is", "<A href=\"$1\" target=_self>$2</A>", $code);
	$code = preg_replace("/\[url\](http:\/\/){0,1}www.(.*?)\[\/url\]/is", "<A href=\"http://www.$2\" target=_blank>$1www.$2</A>", $code);
	$code = preg_replace("/\[url\](.*?)\[\/url\]/is", "<A href=\"$1\">$1</A>", $code);

	// Images
	$code = preg_replace("/\[img\]([0-9]*)\[\/img\]/is", "<IMG alt=\"\" src=\"../images/imgmanager/$1.jpg\" border=0>", $code);
	$code = preg_replace("/\[img\]([0-9]*)\.png\[\/img\]/is", "<IMG alt=\"\" src=\"../images/imgmanager/$1.png\" border=0>", $code);
	$code = preg_replace("/\[img\]http:\/\/(.*?)\[\/img\]/is", "<IMG alt=\"\" src=\"http://$1\" border=0>", $code);
	$code = preg_replace("/\[img\](.*?)\[\/img\]/is", "<IMG alt=\"\" src=\"../$1\" border=0>", $code);
	$code = preg_replace("/\[img ([0-9]*?)\|([0-9]*?)\]([0-9]*)\[\/img\]/is", "<IMG style=\"WIDTH:$1px; HEIGHT:$2px\" alt=\"\" src=\"../images/imgmanager/$3.jpg\" border=0>", $code);
	$code = preg_replace("/\[img ([0-9]*?)\|([0-9]*?)\]([0-9]*)\.png\[\/img\]/is", "<IMG style=\"WIDTH:$1px; HEIGHT:$2px\" alt=\"\" src=\"../images/imgmanager/$3.png\" border=0>", $code);
	$code = preg_replace("/\[img ([0-9]*?)\|([0-9]*?)\]http:\/\/(.*?)\[\/img\]/is", "<IMG style=\"WIDTH:$1px; HEIGHT:$2px\" alt=\"\" src=\"http://$3\" border=0>", $code);
	$code = preg_replace("/\[img ([0-9]*?)\|([0-9]*?)\](.*?)\[\/img\]/is", "<IMG style=\"WIDTH:$1px; HEIGHT:$2px\" alt=\"\" src=\"../$3\" border=0>", $code);

	// HR
	$code = preg_replace("/\n{0,1}-{3,}\n{0,1}/is", "<hr>", $code);

	// Listen
	$code = preg_replace("/\n*\[\*\]/is", "<LI>", $code);
	$code = preg_replace("/\[list numbers\](.*?)\n*\[\/list\]\n{0,1}/is", "<OL>$1</OL>", $code);
	$code = preg_replace("/\[list\](.*?)\n*\[\/list\]\n{0,1}/is", "<UL>$1</UL>", $code);

	// Tabellen
	$code = preg_replace_callback("/\n{0,1}\[table(.*?)\](.*?)\[\/table\]/is", "htmlTable", $code);
	$code = preg_replace("/\n*\[tr\]\n*(.*?)\n*\[\/tr\]\n*/is", "<TR>$1</TR>", $code);
	$code = preg_replace("/\n*\[td\](.*?)\[\/td\]\n*/is", "<TD>$1</TD>", $code);
	$code = preg_replace("/\n*\[td (.*?)\]\n{0,1}(.*?)\[\/td\]\n*/is", "<TD class=$1>$2</TD>", $code);

	// Float Box
	$code = preg_replace("/\[floatleft\](.*?)\[\/floatleft\]\n{0,1}/is", "<DIV style=\"MARGIN-RIGHT: 8px; BORDER: #ff0000 2px dashed; FLOAT: left;\">$1</DIV>", $code);
	$code = preg_replace("/\[floatright\](.*?)\[\/floatright\]\n{0,1}/is", "<DIV style=\"MARGIN-LEFT: 8px; BORDER: #ff0000 2px dashed; FLOAT: right;\">$1</DIV>", $code);

	// Schriftart größe farbe
	$code = preg_replace("/\[size=([0-9])\](.*?)\[\/size\]/is", "<font size=$1>$2</font>", $code);
	$code = preg_replace("/\[color=#{0,1}([0-9a-f]{6})\](.*?)\[\/color\]/is", "<font color=$1>$2</font>", $code);
	$code = preg_replace("/\[color=([a-z]*)\](.*?)\[\/color\]/is", "<font color=$1>$2</font>", $code);
	$code = preg_replace("/\[font=(.*?)\](.*?)\[\/font\]/is", "<font face=\"$1\">$2</font>", $code);

	$code = preg_replace("/\n/is", "<br>", $code);
	echo $code;
}

function bbURL($treffer)
{
	if(preg_match("/target=\"{0,1}_blank\"{0,1}/is", $treffer[1], $match))
	{
		$target = ' blank';
	}
	else
	{
		$target = '';
	}

	if(preg_match("/href=\"www.(.*?)\"/is", $treffer[1], $match))
	{
		$url = 'http://www.'.$match[1];
	}
	else
	{
		if(preg_match("/href=\"(.*?)\"/is", $treffer[1], $match))
		{
			$url = $match[1];
		}
	}

	return '[url='.$url.$target.']'.$treffer[2].'[/url]';
}

function bbImage($treffer)
{
	if(preg_match("/imgmanager\/([0-9]*)\.jpg/is", $treffer[1], $match)) $src = $match[1];
	elseif(preg_match("/imgmanager\/([0-9]*)\.png/is", $treffer[1], $match)) $src = $match[1].'.png';
	elseif(preg_match("/src=\"(.*?)\"/is", $treffer[1], $match)) $src = $match[1];

	if(preg_match("/width:\s*([0-9]*)px;{0,1}/is", $treffer[1], $match)) $width = $match[1];
	if (!$width)
	{
		if(preg_match("/width=\"{0,1}([0-9]*)\"{0,1}/is", $treffer[1], $match)) $width = $match[1];
	}
	if(preg_match("/height:\s*([0-9]*)px;{0,1}/is", $treffer[1], $match)) $height = $match[1];
	if (!$height)
	{
		if(preg_match("/height=\"{0,1}([0-9]*)\"{0,1}/is", $treffer[1], $match)) $height = $match[1];
	}

	if ($width) $size = ' '.$width.'|'.$height;

	return '[img'.$size.']'.$src.'[/img]';
}

function rgbToHex($treffer)
{
	$r = (strlen(dechex($treffer[1])) == 1 ? "0" : "") . dechex($treffer[1]);
	$g = (strlen(dechex($treffer[2])) == 1 ? "0" : "") . dechex($treffer[2]);
	$b = (strlen(dechex($treffer[3])) == 1 ? "0" : "") . dechex($treffer[3]);
	return '#'.$r.$g.$b;
}

function htmlTable($treffer)
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

	if (!$class) $border = ' border=1';
	else $border = ' border=0';

	$string = '<table cellspacing=0'.$class.$padding.$size.$border.'><tbody>'.$treffer[2].'</tbody></table>';
	return $string;
}
function bbTable($treffer)
{
	// class
	if (preg_match("/class=\"{0,1}([a-zA-Z-0-9_\-]*)\"{0,1}/is", $treffer[1], $match)) $class = ' class='.$match[1];
	else $class = '';

	// padding
	if (preg_match("/cellpadding=\"{0,1}([0-9]){1,}\"{0,1}/is", $treffer[1], $match)) $padding = ' padding='.$match[1];
	else $padding = '';

	// height
	if (preg_match("/height:\s*([0-9]*)px/is", $treffer[1], $match)) $height = $match[1];
	else $height = '';

	// width
	if (preg_match("/width:\s*([0-9]*)px/is", $treffer[1], $match)) $width = $match[1];
	elseif (preg_match("/width=\"{0,1}([0-9]*)(%){0,1}\"{0,1}/is", $treffer[1], $match)) $width = $match[1].$match[2];
	else $width = '';

	if ($height != '') $size = ' size='.$width.'|'.$height;
	elseif ($width != "100%" && $width != '') $size = ' size='.$width;
	else $size = '';	

	$string = '[table'.$size.$padding.$class.']'."\n".$treffer[2].'[/table]';
	return $string;
}

function sortByOffset($a,$b)
{ 
	if ($a['offset'] == $b['offset'])
	{ 
		return 0; 
	} 
	return ($a['offset'] > $b['offset']) ? -1 : 1; 
}  
?>