<?php

include('inc/risendb_functions.php');

$FSXL[template] .= $rdb_notice;

// Rezept anzeigen
if ($_GET[id])
{
	settype($_GET[id], 'integer');
	$index = mysql_query("SELECT * FROM `risendb_recipe` WHERE `id` = '$_GET[id]'");

	if (mysql_num_rows($index) > 0) {
		$recipe = mysql_fetch_assoc($index);

		// Items auslesen
		$index = mysql_query("SELECT * FROM `risendb_items` WHERE `id` = '$recipe[item]' OR `id` = '$recipe[recipe]'");
		$sourceitems = array();
		while ($item = mysql_fetch_assoc($index)) {
			$sourceitems[$item[id]] = $item[name];
		}

		// Skill auslesen
		$index = mysql_query("SELECT * FROM `risendb_skills` WHERE `id` = '$recipe[skill]'");
		@$skill = mysql_fetch_assoc($index);
		
		// Kopf ausgeben
		$FSXL[template] .= genDBHeader('Risen Datenbank: Rezept: '.$recipe[name]);
				
		$FSXL[template] .= '
			<table border="0" cellpadding="2" cellspacing="0" style="margin:0px auto;" width="100%">
				<tr>
					<td width="150"><b>Name:</b></td>
					<td>'.$recipe[name].'</td>
				</tr>
				<tr>
					<td><b>Erzeugtes Item:</b></td>
					<td><a href="rdb_items.htm?id='.$recipe[item].'" onmouseover="showItem('.$recipe[item].')"  onmouseout="hideItem()">'.$sourceitems[$recipe[item]].'</a></td>
				</tr>
		';

		if ($recipe[skill] != 0) {
			$FSXL[template] .= '
				<tr>
					<td><b>Benötigte Fertigkeit:</b></td>
					<td>
						<a href="rdb_skills.htm?id='.$recipe[skill].'">'.$skill[name].'</a>
						auf Stufe '.$recipe[level].'
					</td>
				</tr>
			';
		}

		if ($recipe[recipe] != 0) {
			$FSXL[template] .= '
				<tr>
					<td><b>Benötigtes Rezept:</b></td>
					<td><a href="rdb_items.htm?id='.$recipe[recipe].'" onmouseover="showItem('.$recipe[recipe].')"  onmouseout="hideItem()">'.$sourceitems[$recipe[recipe]].'</a></td>
				</tr>
			';
		}

		$FSXL[template] .= '
				<tr>
					<td colspan="2" valign="top">
		';
		
		// Zutaten
		$index = mysql_query("SELECT * FROM `risendb_recipeconnect` c, `risendb_items` i
								WHERE c.recipe = '$recipe[id]' AND i.id = c.item ORDER BY i.name");
		if (mysql_num_rows($index) > 0)
		{
			$FSXL[template] .= '
					<br><br><b>Zutaten:</b>
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
	$FSXL[template] .= genDBHeader('Risen Datenbank: Rezepte');
	
	$index = mysql_query("SELECT `id`, `name`, `item` FROM `risendb_recipe` ORDER BY `name`");
	$FSXL[template] .= '
		<table border="0" cellpadding="2" cellspacing="1" style="margin:0px auto;" width="100%">
			<tr>
				<td class="inheadline"><b>Name</b></td>
			</tr>
	';
	
	// Rezepte ausgeben
	while ($recipe = mysql_fetch_assoc($index))
	{
		$i++;
		$FSXL[template] .= '
			<tr>
				<td class="alt'.($i%2==0?1:2).'" valign="top" nowrap><a href="rdb_recipe.htm?id='.$recipe[id].'" onmouseover="showItem('.$recipe[item].')"  onmouseout="hideItem()">'.$recipe[name].'</a></td>
			</tr>
		';
	}
	
	$FSXL[template] .= '</table>';
			
	// Fuß ausgeben
	$FSXL[template] .= genDBFooter();
}

?>