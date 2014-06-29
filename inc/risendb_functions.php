<?php

// Item Attribute
$rdb_nonGreenAttributes = array(1, 2, 9);
$rdb_nonAdditiveAttributes = array(3, 12, 14, 31);

// Karten
$rdb_maps = array();
$rdb_maps[world] = array("Karte der Insel", "world", 54133, 53255, -35802, -58471, 640, 512);
$rdb_maps[monastery] = array("Vulkanfestung", "monastery", 37723, 17918, 19271, -537, 512, 512);
$rdb_maps[city] = array("Hafenstadt", "city", -6258, 8698, -33764, -18807, 512, 512);
$rdb_maps[don] = array("Banditenlager", "don", 44780, -14474, 23974, -35282, 512, 512);
$rdb_maps[vulcan] = array("Höhlen und Wehranlagen unter dem Vulkan", "vulcan", 54078, 15285, 27780, -11009, 512, 512);
$rdb_maps[graveeast] = array("Priestergrab in den östlichen Vulkangrotten", "graveeast", 72622, 16921, 54660, -1040, 512, 512);
$rdb_maps[gravewater] = array("Priestergrab hinter dem Wasserfall", "gravewater", 41826, -20670, 20388, -42109, 512, 512);
$rdb_maps[lizard] = array("Echsengefängnis", "lizard", 48751, 18195, 31976, 1426, 512, 512);
$rdb_maps[easttemple] = array("Osttempel", "easttemple", 22492, 42543, -606, 19443, 512, 512);
$rdb_maps[cave] = array("Vulkangrotten", "cave", 71287, 17158, 36293, -17834, 512, 512);

// Hinweise
$rdb_notice = genDBHeader('Risen Datenbank: Hinweis');
$rdb_notice .= '
	<span style="color:#FF0000;"><b>Achtung!</b></span> Diese Datenbank enthält viele Spoiler. Wenn du das Spiel noch nicht
	durchgespielt hast, solltest sie mit Vorsicht nutzen.
	<p>
	Unsere Datenbank befindet sich noch im Aufbau. Es sind schon viele Informationen enthalten, aber eben noch nicht alle.
	Wenn du mithelfen möchtest sie zum komplettieren, ergänzungen hast, oder dir Fehler aufgefallen sind, dann schau mal 
	<a href="http://forum.worldofplayers.de/forum/showthread.php?t=682512" target="_blank">hier</a> vorbei.
';
$rdb_notice .= genDBFooter();

// CSS
$FSXL[template] .= '
	<style type="text/css">
		.item_top
		{
			background-image:url(images/risendb/item_top.png);
			background-repeat:no-repeat;
			width:380px;
			margin:0px auto;
		}
		.item_bottom
		{
			background-image:url(images/risendb/item_bottom.png);
			background-position:bottom;
			background-repeat:no-repeat;
			width:380px;
			padding-top:11px;
			padding-bottom:10px;
		}
		.item_middle
		{
			background-image:url(images/risendb/item_middle.png);
			background-repeat:repeat-y;
			width:364px;
			padding-left:8px;
			padding-right:8px;
			font-size:11pt;
			font-weight:bold;
			color:#d2c7a9;
			line-height:1.3;
		}
		.item_green
		{
			color:#00f900;
		}
		.item_red
		{
			color:#fc0000;
		}
		#item_tooltip
		{
			position:absolute;
			display:none;
			z-index:20;
		}
		.item_pointer
		{
			position:absolute; 
			color:#000000;
			font-size:7pt;
			font-weight:bold;
		}
	</style>
	<script type="text/javascript" src="inc/risendb_functions.js"></script>
	<div id="item_tooltip"></div>
';

// Links ersetzen
function replaceRDBLinks ($text)
{
	$text = preg_replace("/\[quest=([0-9]*?)\](.*?)\[\/quest\]/is", "<a href=\"rdb_quest.htm?id=$1\">$2</a>", $text);
	$text = preg_replace("/\[npc=([0-9]*?)\](.*?)\[\/npc\]/is", "<a href=\"rdb_npcs.htm?id=$1\">$2</a>", $text);
	$text = preg_replace("/\[item=([0-9]*?)\](.*?)\[\/item\]/is", "<a href=\"rdb_items.htm?id=$1\" onmouseover=\"showItem($1)\"  onmouseout=\"hideItem()\">$2</a>", $text);
	$text = preg_replace("/\[monster=([0-9]*?)\](.*?)\[\/monster\]/is", "<a href=\"rdb_monster.htm?id=$1\">$2</a>", $text);
	
	return $text;
}

// Position
function calculatePosition($map, $x, $y)
{
	$g_width = abs($map[5] - $map[3]);
	$m_width = $map[6];
	$f_width = $g_width / $m_width;
	$g_height = abs($map[2] - $map[4]);
	$m_height = $map[7];
	$f_height = $g_height / $m_height;
	
	$xpos = round(abs($x - $map[5]) / $f_width) - 5;
	$ypos = $map[7] - round(abs($y - $map[4]) / $f_height) - 5;
	
	return array($xpos, $ypos);
}

// Kopf
function genDBHeader($title)
{
	$code = '
		<div style="padding:0px 20px;">
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td class="ct_topleft"><b>'.$title.'</b></td>
					<td class="ct_top"><img border="0" src="images/risenstyle/spacer.gif" alt=""></td>
					<td class="ct_topright"></td>
				</tr>
			</table>
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td class="ct_left"></td>
					<td class="ct_bg">
						<div style="width:90%; margin:0px auto;">
	';
	return $code;
}

// Fuß
function genDBFooter()
{
	$code = '
						</div>
					</td>
					<td class="ct_right"></td>
				</tr>
			</table>
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td class="ct_bottomleft"><img border="0" src="images/risenstyle/spacer.gif" alt=""></td>
					<td class="ct_bottom"><img border="0" src="images/risenstyle/spacer.gif" alt=""></td>
					<td class="ct_bottomright"><img border="0" src="images/risenstyle/spacer.gif" alt=""></td>
				</tr>
			</table>
		</div>
		<p>
	';
	return $code;
}

function genItem($id)
{
	global $db, $rdb_nonGreenAttributes, $rdb_nonAdditiveAttributes;

	$index = mysql_query("SELECT * FROM `risendb_items` WHERE `id` = '$id'");
	if (mysql_num_rows($index) > 0)
	{
		$item = mysql_fetch_assoc($index);

		// Klasse
		if ($item['class'] != 0) {
			$index = mysql_query("SELECT `name` FROM `risendb_itemclass` WHERE `id` = '$item[class]'");
			$item_class = mysql_result($index, 0, 'name');
		}
		
		$code = '
			<div class="item_top"><div class="item_bottom"><div class="item_middle">
					<img border="0" src="/images/risendb/items/'.$item[id].'.jpg" alt="" style="margin-right:8px; float:left;">
					<span style="color:#FFFFFF;">'.$item[name].'</span><br>
					'.$item_class.'
					<div style="clear:both;"></div>
					<br>
					'.$item[desc].'
					<br><br>
		';
		
		// Attribute
		$index = mysql_query("SELECT * FROM `risendb_item_statconnect` c, `risendb_itemstat` s
								WHERE c.item = '$item[id]' AND s.id = c.stat ORDER BY c.pos");
		while ($attr = mysql_fetch_assoc($index))
		{
			if (in_array($attr[id], $rdb_nonGreenAttributes)) {
				if ($prevtype == 2) {
					$code .= '<br>';
				}
				$prevtype = 1;
				$code .= ''.$attr[name].' '.$attr[value].'<br>';
			}
			else {
				if ($prevtype == 1) {
					$code .= '<br>';
				}
				$plus = in_array($attr[id], $rdb_nonAdditiveAttributes) ? "" : "+";
				if ($attr[value] >= 0) {
					$code .= '<span class="item_green" style="float:right;">'.$plus.$attr[value].'</span>';
					$code .= '<span class="item_green">'.$attr[name].'</span><br>';
				} else {
					$code .= '<span class="item_red" style="float:right;">'.$attr[value].'</span>';
					$code .= '<span class="item_red">'.$attr[name].'</span><br>';
				}
				$prevtype = 2;
			}
		}
		if (mysql_num_rows($index) > 0) $code .= '<br>';
		
		$code .= '
					<span style="float:right">'.$item[gold].'</span>Goldwert
			</div></div></div>
		';
		
		return array('name' => $item[name], 'id' => $item[id], 'code' => $item[code], 'html' => $code);
	}
	
	// Item nicht gefunden
	else
	{
		return false;
	}
}

?>