<?php

include('inc/risendb_functions.php');
$cats = array(1=>'Welt', 2=>'Hafenstadt', 3=>'Vulkanfestung', 4=>'Banditenlager');
$FSXL[template] .= $rdb_notice;

// Quest anzeigen
if ($_GET[id])
{
	settype($_GET[id], 'integer');
	$index = mysql_query("SELECT * FROM `risendb_quests` WHERE `id` = '$_GET[id]'");

	if (mysql_num_rows($index) > 0) {
		$quest = mysql_fetch_assoc($index);
		
		// Kopf ausgeben
		$FSXL[template] .= genDBHeader('Risen Datenbank: '.$quest[name]);
		
		if ($quest[startnpc] != 0) {
			$index = mysql_query("SELECT `id`, `name` FROM `risendb_npc` WHERE `id` = '$quest[startnpc]'");
			$npc = @mysql_fetch_assoc($index);
			$startnpc = '<a href="rdb_npcs.htm?id='.$npc[id].'">'.$npc[name].'</a>';
		} else {
			$startnpc = '-';
		}
		
		// Links
		$quest[solution] = replaceRDBLinks($quest[solution]);
						
		$FSXL[template] .= '
			<table border="0" cellpadding="2" cellspacing="0" style="margin:0px auto;" width="100%">
				<tr>
					<td width="100"><b>Name:</b></td>
					<td>'.$quest[name].'</td>
				</tr>
				<tr>
					<td><b>Region:</b></td>
					<td>'.$cats[$quest[cat]].'</td>
				</tr>
				<tr>
					<td><b>Auftraggeber:</b></td>
					<td>'.$startnpc.'</td>
				</tr>
				<tr>
					<td valign="top"><b>Lösung:</b></td>
					<td>'.$quest[solution].'</td>
				</tr>
				<tr>
					<td><b>Erfahrung:</b></td>
					<td>'.$quest[ep].'</td>
				</tr>
			</table>
		';
		
		// Belohnungen
		$index = mysql_query("SELECT * FROM `risendb_quest_itemconnect` c, `risendb_items` i
								WHERE c.quest = '$quest[id]' AND i.id = c.item ORDER BY i.name");
		if (mysql_num_rows($index) > 0)
		{
			$FSXL[template] .= '
					<br><br><b>Belohnungen:</b>
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

		// NPCs
		$index = mysql_query("SELECT n.id, n.name, n.code FROM `risendb_quest_npcconnect` c, `risendb_npc` n
								WHERE c.quest = '$quest[id]' AND n.id = c.npc ORDER BY n.name");
		if (mysql_num_rows($index) > 0)
		{
			$FSXL[template] .= '
					<br><br><b>Beteiligte NPCs:</b>
					<table border="0" cellpadding="2" cellspacing="1" style="margin:0px auto;" width="100%">
						<tr>
							<td class="inheadline"><b>Name</b></td>
							<td class="inheadline"><b>Insert Code</b></td>
						</tr>
			';
			while ($npc = mysql_fetch_assoc($index)) {
				$i++;
				$FSXL[template] .= '
					<tr>
						<td class="alt'.($i%2==0?1:2).'"><a href="rdb_npcs.htm?id='.$npc[id].'">'.$npc[name].'</a></td>
						<td class="alt'.($i%2==0?1:2).'">'.$npc[code].'</td>
					</tr>
				';
			}
			$FSXL[template] .= '</table>';
		}
	}

	// Nicht gefunden
	else {
		$FSXL[template] .= genDBHeader('Risen Datenbank');
		$FSXL[template] .= 'Quest wurde nicht gefunden.';
	}

	$FSXL[template] .= genDBFooter();
}

// Liste
else
{
	// Kopf ausgeben
	$FSXL[template] .= genDBHeader('Risen Datenbank: Quests');
	
	$index = mysql_query("SELECT `id`, `name`, `cat` FROM `risendb_quests` ORDER BY `cat`, `name`");
	$FSXL[template] .= '
		<table border="0" cellpadding="2" cellspacing="1" style="margin:0px auto;" width="100%">
	';
	
	// Quests ausgeben
	while ($quest = mysql_fetch_assoc($index))
	{
		$i++;
		if ($prevcat != $quest[cat]) {
			$FSXL[template] .= '<tr><td class="inheadline"><b>'.$cats[$quest[cat]	].'</b></td></tr>';
			$prevcat = $quest[cat];
		}
		$FSXL[template] .= '
			<tr>
				<td class="alt'.($i%2==0?1:2).'" valign="top" nowrap><a href="rdb_quest.htm?id='.$quest[id].'">'.$quest[name].'</a></td>
			</tr>
		';
	}
	
	$FSXL[template] .= '</table>';
			
	// Fuß ausgeben
	$FSXL[template] .= genDBFooter();
}

?>