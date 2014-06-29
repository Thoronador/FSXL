<?php

include('inc/risendb_functions.php');
$FSXL[template] .= $rdb_notice;

// Truhe anzeigen
if ($_GET[id])
{
	settype($_GET[id], 'integer');
	$index = mysql_query("SELECT * FROM `risendb_crates` WHERE `id` = '$_GET[id]'");

	if (mysql_num_rows($index) > 0) {
		$crate = mysql_fetch_assoc($index);
		
		// Kopf ausgeben
		$FSXL[template] .= genDBHeader('Risen Datenbank: '.$crate[name]);
		
		// Kombination
		if(preg_match("/^([adlr]*)$/i", $crate[lock])) {
			for ($i=0; $i<strlen($crate[lock]); $i++) {
				$char = substr($crate[lock], $i, 1);
				if (preg_match("/^([al]*)$/i", $char)) {
					$char = '<img border="0" src="images/risendb/arrow_left.png" alt="Links" style="margin-left:5px;">';
				}
				elseif (preg_match("/^([dr]*)$/i", $char)) {
					$char = '<img border="0" src="images/risendb/arrow_right.png" alt="Rechts" style="margin-left:5px;">';
				}
				$lock .= $char;
			}
			$crate[lock] = $lock;
		}
		
		// Position
		foreach ($rdb_maps AS $map) {
			if ($crate[x]>=$map[5] && $crate[x]<=$map[3] && $crate[y]>=$map[4] && $crate[y]<=$map[2]) {
				$position = calculatePosition($map, $crate[x], $crate[y]);
				$mapimg = $map[1];
				break;
			}
		}
		
		$FSXL[template] .= '
			<table border="0" cellpadding="2" cellspacing="0" style="margin:0px auto;" width="100%">
				<tr>
					<td><b>Name:</b></td>
					<td>'.$crate[name].'</td>
				</tr>
				<tr>
					<td valign="top"><b>Beschreibung:</b></td>
					<td>'.replaceRDBLinks($crate[desc]).'</td>
				</tr>
				<tr>
					<td><b>Kombination:</b></td>
					<td>'.replaceRDBLinks($crate[lock]).'</td>
				</tr>
				<tr>
					<td><b>Position:</b></td>
					<td>
						<b>X:</b> '.$crate[x].' &nbsp;&nbsp;&nbsp;
						<b>Z:</b> '.$crate[z].' &nbsp;&nbsp;&nbsp;
						<b>Y:</b> '.$crate[y].'
					</td>
				</tr>
				<tr>
					<td nowrap><b>Karte auswählen:</b></td>
					<td>
						<select name="map" class="textinput" style="width:300px;">
		';
		
		// Karten auslesen
		foreach ($rdb_maps AS $map) {
			if ($crate[x]>=$map[5] && $crate[x]<=$map[3] && $crate[y]>=$map[4] && $crate[y]<=$map[2]) {
				$pos = calculatePosition($map, $crate[x], $crate[y]);
				$FSXL[template] .= '<option onclick="changeMap(\''.$map[1].'\', '.$pos[0].', '.$pos[1].')">'.$map[0].'</option>';
			}
		}
		
		$FSXL[template] .= '
						</select>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<div style="position:relative;">
							<img border="0" src="images/risendb/map_'.$mapimg.'.png" alt="" id="rdb_map">
							<img border="0" src="images/risendb/pointer_'.$crate[type].'.png" id="rdb_pointer" title="'.$crate[name].'" alt="" style="position:absolute; left:'.$position[0].'px; top:'.$position[1].'px;">
						</div>
					</td>
				</tr>
			</table>
		';
		
		// Inhalt
		$index = mysql_query("SELECT * FROM `risendb_crate_itemconnect` c, `risendb_items` i
								WHERE c.crate = '$crate[id]' AND i.id = c.item ORDER BY i.name");
		if (mysql_num_rows($index) > 0)
		{
			$FSXL[template] .= '
					<br><br><b>Inhalt:</b>
					<table border="0" cellpadding="2" cellspacing="1" style="margin:0px auto;" width="100%">
						<tr>
							<td class="inheadline" width="20"> </td>
							<td class="inheadline"><b>Name</b></td>
							<td class="inheadline"><b>Menge</b></td>
							<td class="inheadline"><b>Insert Code</b></td>
						</tr>
			';
			while ($item = mysql_fetch_assoc($index)) {
				$i++;
				$FSXL[template] .= '
					<tr>
						<td class="alt'.($i%2==0?1:2).'">
							<img border="0" src="images/risendb/items/'.$item[id].'.jpg" width="20" height="20" alt="" onmouseover="showItem('.$item[id].')"  onmouseout="hideItem()">
						</td>
						<td class="alt'.($i%2==0?1:2).'"><a href="rdb_items.htm?id='.$item[id].'" onmouseover="showItem('.$item[id].')"  onmouseout="hideItem()">'.$item[name].'</a></td>
						<td class="alt'.($i%2==0?1:2).'">'.$item[value].'</td>
						<td class="alt'.($i%2==0?1:2).'">'.$item[code].'</td>
					</tr>
				';
			}
			$FSXL[template] .= '</table>';
		}
	}

	// Nicht gefunden
	else {
		$FSXL[template] .= genDBHeader('Risen Datenbank');
		$FSXL[template] .= 'Truhe wurde nicht gefunden.';
	}

	$FSXL[template] .= genDBFooter();
}

// Suche
if ($_GET[keyword] || $_GET[map])
{
	// Kopf ausgeben
	$FSXL[template] .= genDBHeader('Risen Datenbank: <a href="rdb_items.htm">Truhen und Co suchen</a>');
	
	// Keyword Suche
	if ($_GET[keyword])
	{
		$index = mysql_query("SELECT * FROM `risendb_crates` WHERE MATCH (`name`, `desc`) AGAINST ('$_GET[keyword]')");
		if (mysql_num_rows($index) > 0) {
			$FSXL[template] .= '
				Suchergebnisse für "<i>'.$_GET[keyword].'</i>":
				<p>
				<table border="0" cellpadding="2" cellspacing="1" style="margin:0px auto;" width="100%">
					<tr>
						<td class="inheadline"> </td>
						<td class="inheadline"><b>Name</b></td>
						<td class="inheadline"><b>Beschreibung</b></td>
					</tr>
				<br>
			';

			// Truhen ausgeben
			$i=0;
			$coords = array();
			while ($crate = mysql_fetch_assoc($index))
			{
				$i++;
				$coords[$i] = array($crate[name], $crate[x], $crate[y], $crate[type]);
				$FSXL[template] .= '
					<tr>
						<td class="alt'.($i%2==0?1:2).'" valign="top" align="right"><b>'.$i.':</b></td>
						<td class="alt'.($i%2==0?1:2).'" valign="top" nowrap><a href="rdb_crates.htm?id='.$crate[id].'">'.$crate[name].'</a></td>
						<td class="alt'.($i%2==0?1:2).'">'.$crate[desc].'</td>
					</tr>
				';
			}
			
			$FSXL[template] .= '
				</table>
				<p>
				<select name="map" class="textinput" style="width:300px;">
			';
			
			// Verfügbare Karten auslesen
			$available_maps = array();
			foreach ($coords AS $coord) {
				foreach ($rdb_maps AS $map) {
					if ($coord[1]>=$map[5] && $coord[1]<=$map[3] && $coord[2]>=$map[4] && $coord[2]<=$map[2]) {
						$available_maps[$map[1]] = $map;
					}
				}
			}
			foreach ($available_maps AS $map) {
				if (!$firstmap) $firstmap = $map;
				$FSXL[template] .= '<option onclick="changeMultiMap(\''.$map[1].'\', '.$map[2].', '.$map[3].', '.$map[4].', '.$map[5].', '.$map[6].', '.$map[7].')">'.$map[0].'</option>';
			}
			
			$FSXL[template] .= '
				</select>
				<div style="position:relative;">
					<img border="0" src="images/risendb/map_'.$firstmap[1].'.png" alt="" id="rdb_map">
			';
			foreach ($coords AS $num => $coord) {
				$pos = calculatePosition($firstmap, $coord[1], $coord[2]);
				if ($coord[1]>=$firstmap[5] && $coord[1]<=$firstmap[3] && $coord[2]>=$firstmap[4] && $coord[2]<=$firstmap[2]) {
					$FSXL[template] .= '
						<div class="item_pointer" id="rdb_pointer,'.$coord[1].','.$coord[2].'" style="left:'.$pos[0].'px; top:'.$pos[1].'px;">
							<img border="0" src="images/risendb/pointer_'.$coord[3].'.png" title="'.$coord[0].'" alt="">
							'.$num.'
						</div>
					';
				}
				else {
					$FSXL[template] .= '
						<div class="item_pointer" id="rdb_pointer,'.$coord[1].','.$coord[2].'" style="left:'.$pos[0].'px; top:'.$pos[1].'px; visibility:hidden;">
							<img border="0" src="images/risendb/pointer_'.$coord[3].'.png" title="'.$coord[0].'" alt="">
							'.$num.'
						</div>
					';
				}
			}
			$FSXL[template] .= '
				</div>
			';
		}
		// Nicht gefunden
		else {
			$FSXL[template] .= 'Die Suche nach "<i>'.$_GET[keyword].'</i>" ergab keine Treffer.';
		}
	}
	
	// Kartensuche
	else
	{
		$index = mysql_query("SELECT `name`, `id`, `x`, `y`, `type` FROM `risendb_crates`
								WHERE `x` >= '".$rdb_maps[$_GET[map]][5]."'
								AND `x` <= '".$rdb_maps[$_GET[map]][3]."'
								AND `y` >= '".$rdb_maps[$_GET[map]][4]."'
								AND `y` <= '".$rdb_maps[$_GET[map]][2]."'");
		$FSXL[template] .= '
			<b>'.$rdb_maps[$_GET[map]][0].':</b>
			<div style="position:relative;">
				<img border="0" src="images/risendb/map_'.$rdb_maps[$_GET[map]][1].'.png" alt="" id="rdb_map">
		';
		
		while ($crate = mysql_fetch_assoc($index)) {
			$pos = calculatePosition($rdb_maps[$_GET[map]], $crate[x], $crate[y]);
			$FSXL[template] .= '
				<a href="rdb_crates.htm?id='.$crate[id].'">
					<img border="0" src="images/risendb/pointer_'.$crate[type].'.png" id="rdb_pointer" title="'.$crate[name].'" alt="" style="position:absolute; left:'.$pos[0].'px; top:'.$pos[1].'px;">
				</a>
			';
		}

		$FSXL[template] .= '</div>';
		
	}
			
	// Fuß ausgeben
	$FSXL[template] .= genDBFooter();
}

// Suchfelder
// Kopf ausgeben
$FSXL[template] .= genDBHeader('Risen Datenbank: Truhen und Co suchen');

$FSXL[template] .= '
	<form action="rdb_crates.htm" method="get">
	<table border="0" cellpadding="3" cellspacing="0" style="margin:0px auto;">
		<tr>
			<td><b>Suchen nach:</b></td>
			<td><input class="textinput" name="keyword" style="width:370px;"></td>
		</tr>
		<tr>
			<td><b>Karte auswählen:</b></td>
			<td>
				<select class="textinput" name="map" style="width:370px">
';

// Karten anzeigen
foreach ($rdb_maps AS $map) {
	$FSXL[template] .= '<option value="'.$map[1].'">'.$map[0].'</option>';
}

$FSXL[template] .= '
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="2" align="right"><input type="submit" class="button" value="Suchen"></td>
		</tr>
	</table>
	</form>
';

// Fuß ausgeben
$FSXL[template] .= genDBFooter();

?>