<?php

include('inc/risendb_functions.php');
$FSXL[template] .= $rdb_notice;

// Monster anzeigen
if ($_GET[id])
{
	settype($_GET[id], 'integer');
	$index = mysql_query("SELECT * FROM `risendb_monster` WHERE `id` = '$_GET[id]'");

	if (mysql_num_rows($index) > 0) {
		$monster = mysql_fetch_assoc($index);
		
		// Kopf ausgeben
		$FSXL[template] .= genDBHeader('Risen Datenbank: '.$monster[name]);
						
		$FSXL[template] .= '
			<table border="0" cellpadding="2" cellspacing="0" style="margin:0px auto;" width="100%">
				<tr>
					<td rowspan="40" width="200" valign="top">
						<img border="0" src="images/risendb/monster/'.$monster[id].'.png" alt="" width="250" style="margin-left:-50px;">
					</td>
					<td width="200" height="10"><b>Name:</b></td>
					<td>'.$monster[name].'</td>
				</tr>
				<tr>
					<td height="10"><b>Insert Code:</b></td>
					<td>'.$monster[code].'</td>
				</tr>
				<tr>
					<td height="10"><b>Stufe:</b></td>
					<td>'.$monster[lv].'</td>
				</tr>
				<tr>
					<td height="10"><b>Erfahrung:</b></td>
					<td>'.($monster[lv]*10).'</td>
				</tr>
				<tr>
					<td height="10"><b>Lebenspunkte:</b></td>
					<td>'.$monster[hp].'</td>
				</tr>
				<tr>
					<td height="10"><b>Mana:</b></td>
					<td>'.$monster[mp].'</td>
				</tr>
				<tr>
					<td height="10"><b>Stärke:</b></td>
					<td>'.$monster[str].'</td>
				</tr>
				<tr>
					<td height="10"><b>Geschicklichkeit:</b></td>
					<td>'.$monster[dex].'</td>
				</tr>
				<tr>
					<td height="10"><b>Weisheit:</b></td>
					<td>'.$monster['int'].'</td>
				</tr>
				<tr>
					<td height="10" colspan="2"></td>
				</tr>
				<tr>
					<td height="10"><b>Schutz vor Klingen:</b></td>
					<td>'.$monster[pedge].'</td>
				</tr>
				<tr>
					<td height="10"><b>Schutz vor Hiebwaffen:</b></td>
					<td>'.$monster[pblunt].'</td>
				</tr>
				<tr>
					<td height="10"><b>Schutz vor Stichwaffen:</b></td>
					<td>'.$monster[ppoint].'</td>
				</tr>
				<tr>
					<td height="10"><b>Schutz vor Feuer:</b></td>
					<td>'.$monster[pfire].'</td>
				</tr>
				<tr>
					<td height="10"><b>Schutz vor Eis:</b></td>
					<td>'.$monster[pice].'</td>
				</tr>
				<tr>
					<td height="10"><b>Schutz vor Magie:</b></td>
					<td>'.$monster[pmagic].'</td>
				</tr>
				<tr>
					<td height="10" colspan="2"></td>
				</tr>
		';
		if ($monster[csword] > 0) {
			$FSXL[template] .= '
				<tr>
					<td height="10"><b>Schwertkampf:</b></td>
					<td>'.$monster[csword].'</td>
				</tr>
			';
		}
		if ($monster[caxe] > 0) {
			$FSXL[template] .= '
				<tr>
					<td height="10"><b>Axtkampf:</b></td>
					<td>'.$monster[caxe].'</td>
				</tr>
			';
		}
		if ($monster[cstaff] > 0) {
			$FSXL[template] .= '
				<tr>
					<td height="10"><b>Stabkampf:</b></td>
					<td>'.$monster[cstaff].'</td>
				</tr>
			';
		}
		if ($monster[cbow] > 0) {
			$FSXL[template] .= '
				<tr>
					<td height="10"><b>Bogen:</b></td>
					<td>'.$monster[cbow].'</td>
				</tr>
			';
		}
		if ($monster[ccrossbow] > 0) {
			$FSXL[template] .= '
				<tr>
					<td height="10"><b>Armbrust:</b></td>
					<td>'.$monster[ccrossbow].'</td>
				</tr>
			';
		}
		if ($monster[circle] > 0) {
			$FSXL[template] .= '
				<tr>
					<td height="10"><b>Siegel:</b></td>
					<td>'.$monster[circle].'</td>
				</tr>
			';
		}
		if ($monster[fire] > 0) {
			$FSXL[template] .= '
				<tr>
					<td height="10"><b>Feuerball:</b></td>
					<td>'.$monster[fire].'</td>
				</tr>
			';
		}
		if ($monster[frost] > 0) {
			$FSXL[template] .= '
				<tr>
					<td height="10"><b>Frost:</b></td>
					<td>'.$monster[frost].'</td>
				</tr>
			';
		}
		if ($monster[missile] > 0) {
			$FSXL[template] .= '
				<tr>
					<td height="10"><b>Magisches Geschoss:</b></td>
					<td>'.$monster[missile].'</td>
				</tr>
			';
		}
		$FSXL[template] .= '
				<tr>
					<td colspan="2" valign="top">
		';
		
		// Buete
		$index = mysql_query("SELECT * FROM `risendb_monster_itemconnect` c, `risendb_items` i
								WHERE c.monster = '$monster[id]' AND i.id = c.item ORDER BY i.name");
		if (mysql_num_rows($index) > 0)
		{
			$FSXL[template] .= '
					<br><br><b>Beute:</b>
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

		$FSXL[template] .= '
					</td>
				</tr>
			</table>
		';
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
	$FSXL[template] .= genDBHeader('Risen Datenbank: Monster');
	
	switch ($_GET[order])
	{
		case 'lv':
			$order = '`lv` DESC';
			break;
		default:
			$order = '`name`';
	}
	
	$index = mysql_query("SELECT `id`, `name`, `lv`, `code` FROM `risendb_monster` ORDER BY $order");
	$FSXL[template] .= '
		<table border="0" cellpadding="2" cellspacing="1" style="margin:0px auto;" width="100%">
			<tr>
				<td class="inheadline"><a href="rdb_monster.htm?order=name"><b>Name</b></a></td>
				<td class="inheadline"><a href="rdb_monster.htm?order=lv"><b>Stufe</b></a></td>
				<td class="inheadline"><b>Insert Code</b></td>
			</tr>
	';
	
	// NPCs ausgeben
	while ($monster = mysql_fetch_assoc($index))
	{
		$i++;
		$FSXL[template] .= '
			<tr>
				<td class="alt'.($i%2==0?1:2).'" valign="top" nowrap><a href="rdb_monster.htm?id='.$monster[id].'">'.$monster[name].'</a></td>
				<td class="alt'.($i%2==0?1:2).'">'.$monster[lv].'</td>
				<td class="alt'.($i%2==0?1:2).'">'.$monster[code].'</td>
			</tr>
		';
	}
	
	$FSXL[template] .= '</table>';
			
	// Fuß ausgeben
	$FSXL[template] .= genDBFooter();
}

?>