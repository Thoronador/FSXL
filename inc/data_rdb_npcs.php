<?php

include('inc/risendb_functions.php');

$FSXL[template] .= $rdb_notice;

// NPC anzeigen
if ($_GET[id])
{
	settype($_GET[id], 'integer');
	$index = mysql_query("SELECT * FROM `risendb_npc` WHERE `id` = '$_GET[id]'");

	if (mysql_num_rows($index) > 0) {
		$npc = mysql_fetch_assoc($index);
		
		// Kopf ausgeben
		$FSXL[template] .= genDBHeader('Risen Datenbank: '.$npc[name]);
				
		$FSXL[template] .= '
			<table border="0" cellpadding="2" cellspacing="0" style="margin:0px auto;" width="100%">
				<tr>
					<td rowspan="4" width="200" valign="top">
						<img border="0" src="images/risendb/npc/'.$npc[id].'.png" alt="" width="250" style="margin-left:-50px;">
					</td>
					<td width="150" height="10"><b>Name:</b></td>
					<td>'.$npc[name].'</td>
				</tr>
				<tr>
					<td valign="top" height="10"><b>Beschreibung:</b></td>
					<td>'.replaceRDBLinks(fscode($npc[comment])).'</td>
				</tr>
				<tr>
					<td height="10"><b>Insert Code:</b></td>
					<td>'.$npc[code].'</td>
				</tr>
				<tr>
					<td colspan="2" valign="top">
		';
		
		// Händler
		$index = mysql_query("SELECT * FROM `risendb_npc_traderconnect` c, `risendb_items` i
								WHERE c.npc = '$npc[id]' AND i.id = c.item ORDER BY i.name");
		if (mysql_num_rows($index) > 0)
		{
			$FSXL[template] .= '
					<br><br><b>Händler für:</b>
					<table border="0" cellpadding="2" cellspacing="1" style="margin:0px auto;" width="100%">
						<tr>
							<td class="inheadline" width="20"> </td>
							<td class="inheadline"><b>Name</b></td>
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
						<td class="alt'.($i%2==0?1:2).'">'.$item[code].'</td>
					</tr>
				';
			}
			$FSXL[template] .= '</table>';
		}

		// Lehrer
		$index = mysql_query("SELECT * FROM `risendb_npc_skillconnect` c, `risendb_skills` s
								WHERE c.npc = '$npc[id]' AND s.id = c.skill ORDER BY s.name");
		if (mysql_num_rows($index) > 0)
		{
			$FSXL[template] .= '
					<br><br><b>Lehrer für:</b>
					<table border="0" cellpadding="2" cellspacing="1" style="margin:0px auto;" width="100%">
						<tr>
							<td class="inheadline" width="20"> </td>
							<td class="inheadline"><b>Name</b></td>
							<td class="inheadline"><b>Beschreibung</b></td>
							<td class="inheadline" nowrap><b>Bis Stufe</b></td>
						</tr>
			';
			while ($skill = mysql_fetch_assoc($index)) {
				$i++;
				$FSXL[template] .= '
					<tr>
						<td class="alt'.($i%2==0?1:2).'">
							<img border="0" src="images/risendb/skills/'.$skill[id].'.jpg" width="20" height="20" alt="">
						</td>
						<td class="alt'.($i%2==0?1:2).'"><a href="rdb_skills.htm?id='.$skill[id].'">'.$skill[name].'</a></td>
						<td class="alt'.($i%2==0?1:2).'">'.$skill[desc].'</td>
						<td class="alt'.($i%2==0?1:2).'">'.$skill[level].'</td>
					</tr>
				';
			}
			$FSXL[template] .= '</table>';
		}

		// Thief
		$index = mysql_query("SELECT * FROM `risendb_npc_thiefconnect` c, `risendb_items` i
								WHERE c.npc = '$npc[id]' AND i.id = c.item ORDER BY i.name");
		if (mysql_num_rows($index) > 0)
		{
			$FSXL[template] .= '
					<br><br><b>Taschendiebstahl:</b>
					<table border="0" cellpadding="2" cellspacing="1" style="margin:0px auto;" width="100%">
						<tr>
							<td class="inheadline" width="20"> </td>
							<td class="inheadline"><b>Name</b></td>
							<td class="inheadline"><b>Menge</b></td>
							<td class="inheadline" nowrap><b>Benötigte Stufe</b></td>
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
						<td class="alt'.($i%2==0?1:2).'">'.$item[level].'</td>
					</tr>
				';
			}
			$FSXL[template] .= '</table>';
		}

		// Inventar
		$index = mysql_query("SELECT * FROM `risendb_npc_invconnect` c, `risendb_items` i
								WHERE c.npc = '$npc[id]' AND i.id = c.item ORDER BY i.name");
		if (mysql_num_rows($index) > 0)
		{
			$FSXL[template] .= '
					<br><br><b>Inventar:</b>
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

		// Quests
		$index = mysql_query("SELECT q.id, q.name FROM `risendb_quest_npcconnect` c, `risendb_quests` q
								WHERE c.npc = '$npc[id]' AND q.id = c.quest ORDER BY q.name");
		if (mysql_num_rows($index) > 0)
		{
			$FSXL[template] .= '
					<br><br><b>An Quest beteiligt:</b>
					<table border="0" cellpadding="2" cellspacing="1" style="margin:0px auto;" width="100%">
						<tr>
							<td class="inheadline"><b>Name</b></td>
						</tr>
			';
			while ($quest = mysql_fetch_assoc($index)) {
				$i++;
				$FSXL[template] .= '
					<tr>
						<td class="alt'.($i%2==0?1:2).'"><a href="rdb_quest.htm?id='.$quest[id].'">'.$quest[name].'</a></td>
					</tr>
				';
			}
			$FSXL[template] .= '</table>';
		}

		$FSXL[template] .= '
					</td>
				</tr>
			</table>
		';

		// Fundorte
		$index = mysql_query("SELECT * FROM `risendb_npc_coords` WHERE `npc` = '$npc[id]'");
		if (mysql_num_rows($index) > 0)
		{
			$FSXL[template] .= '
					<br><br><b>Aufenthaltsorte:</b>
					<table border="0" cellpadding="2" cellspacing="1" style="margin:0px auto;" width="100%">
						<tr>
							<td class="inheadline"> </td>
							<td class="inheadline"><b>X</b></td>
							<td class="inheadline"><b>Z</b></td>
							<td class="inheadline"><b>Y</b></td>
							<td class="inheadline"><b>Beschreibung</b></td>
						</tr>
			';
			$coords = array();
			$i=0;
			while ($coord = mysql_fetch_assoc($index)) {
				$i++;
				$coords[$i] = array($coord[text], $coord[x], $coord[y]);
				$FSXL[template] .= '
					<tr>
						<td class="alt'.($i%2==0?1:2).'" valign="top" align="right"><b>'.$i.':</b></td>
						<td class="alt'.($i%2==0?1:2).'" valign="top">'.$coord[x].'</td>
						<td class="alt'.($i%2==0?1:2).'" valign="top">'.$coord[z].'</td>
						<td class="alt'.($i%2==0?1:2).'" valign="top">'.$coord[y].'</td>
						<td class="alt'.($i%2==0?1:2).'">'.$coord[text].'</td>
					</tr>
				';
			}
			$FSXL[template] .= '
				</table>
				<br>
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
							<img border="0" src="images/risendb/pointer_green.png" title="'.$coord[0].'" alt="">
							'.$num.'
						</div>
					';
				}
				else {
					$FSXL[template] .= '
						<div class="item_pointer" id="rdb_pointer,'.$coord[1].','.$coord[2].'" style="left:'.$pos[0].'px; top:'.$pos[1].'px; visibility:hidden;">
							<img border="0" src="images/risendb/pointer_green.png" title="'.$coord[0].'" alt="">
							'.$num.'
						</div>
					';
				}
			}
			$FSXL[template] .= '
				</div>
			';
		}
	}

	// Nicht gefunden
	else {
		$FSXL[template] .= genDBHeader('Risen Datenbank');
		$FSXL[template] .= 'NPC wurde nicht gefunden.';
	}

	$FSXL[template] .= genDBFooter();
}

// Liste
else
{
	// Kopf ausgeben
	$FSXL[template] .= genDBHeader('Risen Datenbank: NPCs');
	
	$index = mysql_query("SELECT `id`, `name`, `code` FROM `risendb_npc` ORDER BY `name`");
	$FSXL[template] .= '
		<table border="0" cellpadding="2" cellspacing="1" style="margin:0px auto;" width="100%">
			<tr>
				<td class="inheadline"><b>Name</b></td>
				<td class="inheadline"><b>Insert Code</b></td>
			</tr>
	';
	
	// NPCs ausgeben
	while ($npc = mysql_fetch_assoc($index))
	{
		$i++;
		$FSXL[template] .= '
			<tr>
				<td class="alt'.($i%2==0?1:2).'" valign="top" nowrap><a href="rdb_npcs.htm?id='.$npc[id].'">'.$npc[name].'</a></td>
				<td class="alt'.($i%2==0?1:2).'">'.$npc[code].'</td>
			</tr>
		';
	}
	
	$FSXL[template] .= '</table>';
			
	// Fuß ausgeben
	$FSXL[template] .= genDBFooter();
}

?>