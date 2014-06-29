<?php

include('inc/risendb_functions.php');
$FSXL[template] .= $rdb_notice;

// Skill anzeigen
if ($_GET[id])
{
	settype($_GET[id], 'integer');
	$index = mysql_query("SELECT * FROM `risendb_skills` WHERE `id` = '$_GET[id]'");

	if (mysql_num_rows($index) > 0) {
		$skill = mysql_fetch_assoc($index);
		
		// Kopf ausgeben
		$FSXL[template] .= genDBHeader('Risen Datenbank: '.$skill[name]);
								
		$FSXL[template] .= '
			<table border="0" cellpadding="2" cellspacing="0" style="margin:0px auto;" width="100%">
				<tr>
					<td rowspan="2" width="50"><img border="0" src="images/risendb/skills/'.$skill[id].'.jpg" alt=""></td>
					<td width="100"><b>Name:</b></td>
					<td>'.$skill[name].'</td>
				</tr>
				<tr>
					<td valign="top"><b>Beschreibung:</b></td>
					<td>'.$skill[desc].'</td>
				</tr>
			</table>
		';
		
		// Lehrer
		$index = mysql_query("SELECT n.id, n.name, n.code, c.level FROM `risendb_npc_skillconnect` c, `risendb_npc` n
								WHERE c.skill = '$skill[id]' AND n.id = c.npc ORDER BY n.name");
		if (mysql_num_rows($index) > 0)
		{
			$FSXL[template] .= '
					<br><br><b>Lehrer:</b>
					<table border="0" cellpadding="2" cellspacing="1" style="margin:0px auto;" width="100%">
						<tr>
							<td class="inheadline"><b>Name</b></td>
							<td class="inheadline"><b>Insert Code</b></td>
							<td class="inheadline"><b>Bis Stufe</b></td>
						</tr>
			';
			while ($npc = mysql_fetch_assoc($index)) {
				$i++;
				$FSXL[template] .= '
					<tr>
						<td class="alt'.($i%2==0?1:2).'"><a href="rdb_npcs.htm?id='.$npc[id].'">'.$npc[name].'</a></td>
						<td class="alt'.($i%2==0?1:2).'">'.$npc[code].'</td>
						<td class="alt'.($i%2==0?1:2).'">'.$npc[level].'</td>
					</tr>
				';
			}
			$FSXL[template] .= '</table>';
		}
	}

	// Nicht gefunden
	else {
		$FSXL[template] .= genDBHeader('Risen Datenbank');
		$FSXL[template] .= 'Fertigkeit wurde nicht gefunden.';
	}

	$FSXL[template] .= genDBFooter();
}

// Liste
else
{
	// Kopf ausgeben
	$FSXL[template] .= genDBHeader('Risen Datenbank: Fertigkeiten');
	
	$index = mysql_query("SELECT `id`, `name`, `desc` FROM `risendb_skills` ORDER BY `name`");
	$FSXL[template] .= '
		<table border="0" cellpadding="2" cellspacing="1" style="margin:0px auto;" width="100%">
			<tr>
				<td class="inheadline" width="20"> </td>
				<td class="inheadline"><b>Name</b></td>
				<td class="inheadline"><b>Beschreibung</b></td>
			</tr>
	';
	
	// Skills ausgeben
	while ($skill = mysql_fetch_assoc($index))
	{
		$i++;
		$FSXL[template] .= '
			<tr>
				<td class="alt'.($i%2==0?1:2).'" valign="top">
					<img border="0" src="images/risendb/skills/'.$skill[id].'.jpg" width="20" height="20" alt="">
				</td>
				<td class="alt'.($i%2==0?1:2).'" valign="top" nowrap><a href="rdb_skills.htm?id='.$skill[id].'">'.$skill[name].'</a></td>
				<td class="alt'.($i%2==0?1:2).'">'.$skill[desc].'</td>
			</tr>
		';
	}
	
	$FSXL[template] .= '</table>';
			
	// Fuß ausgeben
	$FSXL[template] .= genDBFooter();
}

?>