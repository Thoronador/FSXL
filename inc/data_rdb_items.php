<?php

include('inc/risendb_functions.php');
$FSXL[template] .= $rdb_notice;

// Item anzeigen
if ($_GET[id])
{
	settype($_GET[id], 'integer');

	if ($item = genItem($id)) {
		// Kopf ausgeben
		$FSXL[template] .= genDBHeader('Risen Datenbank: '.$item[name]);
		
		// Item ausgeben
		$FSXL[template] .= $item[html];
		$FSXL[template] .= '<br><b>Insert Code:</b> ' . $item[code];

		// Truhen
		$index = mysql_query("SELECT * FROM `risendb_crate_itemconnect` c, `risendb_crates` t
								WHERE c.item = '$item[id]' AND t.id = c.crate ORDER BY t.name");
		if (mysql_num_rows($index) > 0)
		{
			$FSXL[template] .= '
					<br><br><b>Ist zu finden in:</b>
					<table border="0" cellpadding="2" cellspacing="1" style="margin:0px auto;" width="100%">
						<tr>
							<td class="inheadline"><b>Name</b></td>
							<td class="inheadline"><b>Menge</b></td>
							<td class="inheadline"><b>Beschreibung</b></td>
						</tr>
			';
			while ($crate = mysql_fetch_assoc($index)) {
				$i++;
				$FSXL[template] .= '
					<tr>
						<td class="alt'.($i%2==0?1:2).'" valign="top" nowrap><a href="rdb_crates.htm?id='.$crate[id].'">'.$crate[name].'</a></td>
						<td class="alt'.($i%2==0?1:2).'" valign="top">'.$crate[value].'</td>
						<td class="alt'.($i%2==0?1:2).'">'.replaceRDBLinks($crate[desc]).'</td>
					</tr>
				';
			}
			$FSXL[template] .= '</table>';
		}

		// Wahre
		$index = mysql_query("SELECT * FROM `risendb_npc_traderconnect` c, `risendb_npc` n
								WHERE c.item = '$item[id]' AND n.id = c.npc ORDER BY n.name");
		if (mysql_num_rows($index) > 0)
		{
			$FSXL[template] .= '
					<br><br><b>Wird verkauft von:</b>
					<table border="0" cellpadding="2" cellspacing="1" style="margin:0px auto;" width="100%">
						<tr>
							<td class="inheadline"><b>NPC</b></td>
							<td class="inheadline"><b>Insert Code</b></td>
						</tr>
			';
			while ($npc = mysql_fetch_assoc($index)) {
				$i++;
				$FSXL[template] .= '
					<tr>
						<td class="alt'.($i%2==0?1:2).'" valign="top" nowrap><a href="rdb_npcs.htm?id='.$npc[id].'">'.$npc[name].'</a></td>
						<td class="alt'.($i%2==0?1:2).'">'.$npc[code].'</td>
					</tr>
				';
			}
			$FSXL[template] .= '</table>';
		}

		// Beute
		$index = mysql_query("SELECT * FROM `risendb_monster_itemconnect` c, `risendb_monster` m
								WHERE c.item = '$item[id]' AND m.id = c.monster ORDER BY m.name");
		if (mysql_num_rows($index) > 0)
		{
			$FSXL[template] .= '
					<br><br><b>Kann erbeutet werden von:</b>
					<table border="0" cellpadding="2" cellspacing="1" style="margin:0px auto;" width="100%">
						<tr>
							<td class="inheadline"><b>Monster</b></td>
							<td class="inheadline"><b>Insert Code</b></td>
						</tr>
			';
			while ($monster = mysql_fetch_assoc($index)) {
				$i++;
				$FSXL[template] .= '
					<tr>
						<td class="alt'.($i%2==0?1:2).'" valign="top" nowrap><a href="rdb_monster.htm?id='.$monster[id].'">'.$monster[name].'</a></td>
						<td class="alt'.($i%2==0?1:2).'">'.$monster[code].'</td>
					</tr>
				';
			}
			$FSXL[template] .= '</table>';
		}

		// Zuatat
		$index = mysql_query("SELECT * FROM `risendb_recipeconnect` c, `risendb_recipe` r
								WHERE c.item = '$item[id]' AND r.id = c.recipe ORDER BY r.name");
		if (mysql_num_rows($index) > 0)
		{
			$FSXL[template] .= '
					<br><br><b>Zutat für:</b>
					<table border="0" cellpadding="2" cellspacing="1" style="margin:0px auto;" width="100%">
						<tr>
							<td class="inheadline"><b>Rezept</b></td>
							<td class="inheadline"><b>Menge</b></td>
						</tr>
			';
			while ($recipe = mysql_fetch_assoc($index)) {
				$i++;
				$FSXL[template] .= '
					<tr>
						<td class="alt'.($i%2==0?1:2).'" valign="top" nowrap><a href="rdb_recipe.htm?id='.$recipe[id].'" onmouseover="showItem('.$recipe[item].')"  onmouseout="hideItem()">'.$recipe[name].'</a></td>
						<td class="alt'.($i%2==0?1:2).'">'.$recipe[value].'</td>
					</tr>
				';
			}
			$FSXL[template] .= '</table>';
		}

		// Thief
		$index = mysql_query("SELECT * FROM `risendb_npc_thiefconnect` c, `risendb_npc` n
								WHERE c.item = '$item[id]' AND n.id = c.npc ORDER BY n.name");
		if (mysql_num_rows($index) > 0)
		{
			$FSXL[template] .= '
					<br><br><b>Kann gestohlen werden von:</b>
					<table border="0" cellpadding="2" cellspacing="1" style="margin:0px auto;" width="100%">
						<tr>
							<td class="inheadline"><b>Name</b></td>
							<td class="inheadline"><b>Menge</b></td>
							<td class="inheadline" nowrap><b>Benötigte Stufe</b></td>
						</tr>
			';
			while ($npc = mysql_fetch_assoc($index)) {
				$i++;
				$FSXL[template] .= '
					<tr>
						<td class="alt'.($i%2==0?1:2).'"><a href="rdb_npcs.htm?id='.$npc[id].'">'.$npc[name].'</a></td>
						<td class="alt'.($i%2==0?1:2).'">'.$npc[value].'</td>
						<td class="alt'.($i%2==0?1:2).'">'.$npc[level].'</td>
					</tr>
				';
			}
			$FSXL[template] .= '</table>';
		}

		// Inventar
		$index = mysql_query("SELECT * FROM `risendb_npc_invconnect` c, `risendb_npc` n
								WHERE c.item = '$item[id]' AND n.id = c.npc ORDER BY n.name");
		if (mysql_num_rows($index) > 0)
		{
			$FSXL[template] .= '
					<br><br><b>Im Inventar von:</b>
					<table border="0" cellpadding="2" cellspacing="1" style="margin:0px auto;" width="100%">
						<tr>
							<td class="inheadline"><b>Name</b></td>
							<td class="inheadline"><b>Menge</b></td>
							<td class="inheadline"><b>Insert Code</b></td>
						</tr>
			';
			while ($npc = mysql_fetch_assoc($index)) {
				$i++;
				$FSXL[template] .= '
					<tr>
						<td class="alt'.($i%2==0?1:2).'"><a href="rdb_npcs.htm?id='.$npc[id].'">'.$npc[name].'</a></td>
						<td class="alt'.($i%2==0?1:2).'">'.$npc[value].'</td>
						<td class="alt'.($i%2==0?1:2).'">'.$npc[code].'</td>
					</tr>
				';
			}
			$FSXL[template] .= '</table>';
		}

		// Belohnung
		$index = mysql_query("SELECT * FROM `risendb_quest_itemconnect` c, `risendb_quests` q
								WHERE c.item = '$item[id]' AND q.id = c.quest ORDER BY q.name");
		if (mysql_num_rows($index) > 0)
		{
			$FSXL[template] .= '
					<br><br><b>Questbelohnung für:</b>
					<table border="0" cellpadding="2" cellspacing="1" style="margin:0px auto;" width="100%">
						<tr>
							<td class="inheadline"><b>Quest</b></td>
						</tr>
			';
			while ($quest = mysql_fetch_assoc($index)) {
				$i++;
				$FSXL[template] .= '
					<tr>
						<td class="alt'.($i%2==0?1:2).'" valign="top" nowrap><a href="rdb_quest.htm?id='.$quest[id].'">'.$quest[name].'</a></td>
					</tr>
				';
			}
			$FSXL[template] .= '</table>';
		}

		// Fundorte
		$index = mysql_query("SELECT * FROM `risendb_item_coords` WHERE `item` = '$item[id]'");
		if (mysql_num_rows($index) > 0)
		{
			$FSXL[template] .= '
					<br><br><b>Fundorte:</b>
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
		$FSXL[template] .= 'Item wurde nicht gefunden.';
	}

	$FSXL[template] .= genDBFooter();
}

// Suche
if ($_POST[action] == "search")
{
	// Kopf ausgeben
	$FSXL[template] .= genDBHeader('Risen Datenbank: <a href="rdb_items.htm">Item suchen</a>');
	
	// Keyword Suche
	if ($_POST[keyword])
	{
		$index = mysql_query("SELECT * FROM `risendb_items` WHERE MATCH (`name`, `desc`) AGAINST ('$_POST[keyword]')");
		if (mysql_num_rows($index) == 1) {
			$item = mysql_fetch_assoc($index);
			reloadPage("rdb_items.htm?id=$item[id]");
		}
		elseif (mysql_num_rows($index) > 0) {
			$FSXL[template] .= '
				Suchergebnisse für "<i>'.$_POST[keyword].'</i>":
				<p>
				<table border="0" cellpadding="2" cellspacing="1" style="margin:0px auto;" width="100%">
					<tr>
						<td class="inheadline" width="20"> </td>
						<td class="inheadline"><b>Name</b></td>
						<td class="inheadline"><b>Insert Code</b></td>
					</tr>
			';
			
			// Items ausgeben
			while ($item = mysql_fetch_assoc($index))
			{
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
		// Nicht gefunden
		else {
			$FSXL[template] .= 'Die Suche nach "<i>'.$_POST[keyword].'</i>" ergab keine Treffer.';
		}
	}
	
	// Kategorie anzeigen
	elseif ($_POST[cat])
	{
		settype($_POST[cat], 'integer');
		$index = mysql_query("SELECT * FROM `risendb_items` WHERE `cat` = '$_POST[cat]' ORDER BY `name`");
		if (mysql_num_rows($index) > 0) {
			$index2 = mysql_query("SELECT * FROM `risendb_itemcat` WHERE `id` = '$_POST[cat]'");
			$cat = @mysql_fetch_assoc($index2);			
			$FSXL[template] .= '
				<b>'.$cat[name].'</b>:
				<p>
				<table border="0" cellpadding="2" cellspacing="1" style="margin:0px auto;" width="100%">
					<tr>
						<td class="inheadline" width="20"> </td>
						<td class="inheadline"><b>Name</b></td>
						<td class="inheadline"><b>Insert Code</b></td>
					</tr>
			';
			
			// Items ausgeben
			while ($item = mysql_fetch_assoc($index))
			{
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
		// Nicht gefunden
		else {
			$FSXL[template] .= 'Die Suche ergab keine Treffer.';
		}
	}
	
	// Attribut Suche
	else
	{
		settype($_POST[attr], 'integer');
		settype($_POST[attr_value], 'integer');
		$order = $_POST[attr_type] == 1 ? ">=" : "<=";
		$index = mysql_query("SELECT * FROM `risendb_item_statconnect` c, `risendb_items` i
								WHERE c.stat = '$_POST[attr]' AND c.value $order '$_POST[attr_value]' AND i.id = c.item
								ORDER BY c.value ASC");
		if (mysql_num_rows($index) > 0) {
			$index2 = mysql_query("SELECT * FROM `risendb_itemstat` WHERE `id` = '$_POST[attr]'");
			$stat = @mysql_fetch_assoc($index2);			
			
			$FSXL[template] .= '
				<table border="0" cellpadding="2" cellspacing="1" style="margin:0px auto;" width="100%">
					<tr>
						<td class="inheadline" width="20"> </td>
						<td class="inheadline"><b>Name</b></td>
						<td class="inheadline"><b>'.$stat[name].'</b></td>
						<td class="inheadline"><b>Insert Code</b></td>
					</tr>
			';
			// Items ausgeben
			while ($item = mysql_fetch_assoc($index))
			{
				$i++;
				$plus = in_array($attr[id], $rdb_nonAdditiveAttributes) ? "" : "+";
				$FSXL[template] .= '
					<tr>
						<td class="alt'.($i%2==0?1:2).'">
							<img border="0" src="images/risendb/items/'.$item[id].'.jpg" width="20" height="20" alt="" onmouseover="showItem('.$item[id].')"  onmouseout="hideItem()">
						</td>
						<td class="alt'.($i%2==0?1:2).'"><a href="rdb_items.htm?id='.$item[id].'" onmouseover="showItem('.$item[id].')"  onmouseout="hideItem()">'.$item[name].'</a></td>
						<td class="alt'.($i%2==0?1:2).'">'.$plus.$item[value].'</td>
						<td class="alt'.($i%2==0?1:2).'">'.$item[code].'</td>
					</tr>
				';
			}
			$FSXL[template] .= '</table>';
		}
		// Nicht gefunden
		else {
			$FSXL[template] .= 'Die Suche ergab keine Treffer.';
		}
	}
	
	// Fuß ausgeben
	$FSXL[template] .= genDBFooter();
}

// Suchfelder
// Kopf ausgeben
$FSXL[template] .= genDBHeader('Risen Datenbank: Item suchen');

$FSXL[template] .= '
	<form action="rdb_items.htm" method="post">
	<input type="hidden" name="action" value="search">
	<table border="0" cellpadding="3" cellspacing="0" style="margin:0px auto;">
		<tr>
			<td><b>Suchen nach:</b></td>
			<td>
				<input class="textinput" name="keyword" style="width:370px;">
			</td>
		</tr>
		<tr>
			<td><b>Nach Attribut suchen:</b></td>
			<td>
				<select name="attr" class="textinput" style="width:200px;">
';

// Attribute auslesen
$index = mysql_query("SELECT * FROM `risendb_itemstat`");
while ($attr = mysql_fetch_assoc($index)) {
	$FSXL[template] .= '<option value="'.$attr[id].'">'.$attr[name].'</option>';
}

$FSXL[template] .= '
				</select>
				<select name="attr_type" class="textinput">
					<option value="1">größer gleich</option>
					<option value="2">kleiner gleich</option>
				</select>
				<input class="textinput" name="attr_value" style="width:50px;" value="0">
			</td>
		</tr>
		<tr>
			<td><b>Kategorie anzeigen:</b></td>
			<td>
				<select name="cat" class="textinput" style="width:370px;">
					<option value="">---------------</option>
';

// Kategorie auslesen
$index = mysql_query("SELECT * FROM `risendb_itemcat` ORDER BY `name`");
while ($cat = mysql_fetch_assoc($index)) {
	$FSXL[template] .= '<option value="'.$cat[id].'">'.$cat[name].'</option>';
}

$FSXL[template] .= '
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="2" align="right"><input type="submit" class="button" value="Los"></td>
		</tr>
	</table>
	</form>
';

// Fuß ausgeben
$FSXL[template] .= genDBFooter();

?>