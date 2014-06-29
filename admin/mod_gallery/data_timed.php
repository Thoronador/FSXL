<?php

$FSXL[title] = $FS_PHRASES[gallery_timed_title];

// Bild eintragen
if ($_FILES[file][tmp_name])
{
	// Zeitpunkt ermitteln
	$index = mysql_query("SELECT `enddate` FROM `$FSXL[tableset]_gallery_timed` ORDER BY `enddate` DESC LIMIT 1");
	if (mysql_num_rows($index) == 0)
		$lastpic[enddate] = time();
	else
		$lastpic = mysql_fetch_assoc($index);

	// Datum auswerten
	if ($_POST[sday] != '' && $_POST[smonth] != '' && $_POST[syear] != '' && $_POST[shour] != '' && $_POST[smin] != '')
		$startdate = mktime($_POST[shour], $_POST[smin], 0, $_POST[smonth], $_POST[sday], $_POST[syear]);
	else
		$startdate = $lastpic[enddate]+1;
	if ($_POST[eday] != '' && $_POST[emonth] != '' && $_POST[eyear] != '' && $_POST[ehour] != '' && $_POST[emin] != '')
		$enddate = mktime($_POST[ehour], $_POST[emin], 0, $_POST[emonth], $_POST[eday], $_POST[eyear]);
	else
		$enddate = $lastpic[enddate]+604800;
		
	$chk = @mysql_query("INSERT INTO `$FSXL[tableset]_gallery_timed` (`id`, `titel`, `text`, `date`, `startdate`, `enddate`) 
						VALUES (NULL, '$_POST[title]', '$_POST[text]', $FSXL[time], $startdate, $enddate)");
						
	if ($chk)
	{	
		$id = mysql_insert_id();
		$hash = md5($FSXL[time].$id);

		$img = new imgConvert();
		if ($img->readIMG($_FILES[file]))
		{
			$img->saveIMG('../images/timed/', $hash, 'jpg');

			$img->scaleIMG($FSXL[config][timed_xsize], $FSXL[config][timed_ysize], 'LETTERBOX', $FSXL[config][timed_color]);
			$img->saveIMG('../images/timed/', $hash.'s', 'jpg');

			$FSXL[content] = '<div style="padding:20px; text-align:center;">'.$FS_PHRASES[gallery_pics_picadded].'</div>';
		}
		else
		{
			mysql_query("DELETE FROM `$FSXL[tableset]_gallery_timed` WHERE `id` = $id");
			$FSXL[content] = '<div style="padding:20px; text-align:center;">'.$FS_PHRASES[gallery_pics_nopic].'</div>';
		}
	}
	else
	{
		$FSXL[content] = '<div style="padding:20px; text-align:center;">'.$FS_PHRASES[gallery_timed_failed].'</div>';
	}
}

// Bild bearbeiten
elseif ($_POST[editid])
{
	settype($_POST[editid], 'integer');
	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_gallery_timed` WHERE `id` = $_POST[editid]");
	$pic = mysql_fetch_assoc($index);
	$hash = md5($pic[date].$pic[id]);
	
	// Löschen
	if ($_POST[del])
	{
		mysql_query("DELETE FROM `$FSXL[tableset]_gallery_timed` WHERE `id` = $pic[id]");
		@unlink('../images/timed/'.$hash.'s.jpg');
		@unlink('../images/timed/'.$hash.'.jpg');

		$FSXL[content] = '
				<div style="padding:20px; text-align:center;">'.$FS_PHRASES[gallery_timed_deleted].'</div>
		';
	}
	// Bearbeiten
	else
	{
		// Datum auswerten
		if ($_POST[sday] != '' && $_POST[smonth] != '' && $_POST[syear] != '' && $_POST[shour] != '' && $_POST[smin] != '')
			$startdate = mktime($_POST[shour], $_POST[smin], 0, $_POST[smonth], $_POST[sday], $_POST[syear]);
		else
			$startdate = $pic[startdate];
		if ($_POST[eday] != '' && $_POST[emonth] != '' && $_POST[eyear] != '' && $_POST[ehour] != '' && $_POST[emin] != '')
			$enddate = mktime($_POST[ehour], $_POST[emin], 0, $_POST[emonth], $_POST[eday], $_POST[eyear]);
		else
			$enddate = $pic[enddate];
						
		mysql_query("UPDATE `$FSXL[tableset]_gallery_timed` SET `titel` = '$_POST[title]', `text` = '$_POST[text]', 
						`startdate` = $startdate, `enddate` = $enddate WHERE `id` = $pic[id]");

		$FSXL[content] = '
				<div style="padding:20px; text-align:center;">'.$FS_PHRASES[gallery_timed_edited].'</div>
		';
	}
}

// Bild bearbeiten Formular
elseif ($_GET[id])
{
	settype($_GET[id], 'integer');
	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_gallery_timed` WHERE `id` = $_GET[id]");
	$pic = mysql_fetch_assoc($index);
	$hash = md5($pic[date].$pic[id]);

	$FSXL[content] .= '
				<form action="?mod=gallery&go=timed" method="post" name="timedform">
				<input type="hidden" name="action" value="newpic">
				<input type="hidden" name="editid" value="'.$pic[id].'">
				<table border="0" cellpadding="2" cellspacing="1" width="90%" style="margin:0px auto;">
					<tr>
						<td colspan="2">
							<span style="font-size:12pt"><b>'.$FS_PHRASES[gallery_timed_editpic].'</b></span><hr>
						</td>
					</tr>
					<tr>
						<td width="250" valign="top"><b>'.$FS_PHRASES[gallery_timed_pic].':</b></td>
						<td><img border="0" src="../images/timed/'.$hash.'s.jpg" alt=""></td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[gallery_pics_pictitle].':</b></td>
						<td><input class="textinput" name="title" style="width:300px;" value="'.$pic[titel].'"></td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[gallery_timed_startdate].':</b><br><span class="small">'.$FS_PHRASES[galery_add_dateformat].'</span></td>
						<td valign="top">
							<input class="textinput" name="sday" style="width:20px;" value="'.date("d", $pic[startdate]).'">
							<input class="textinput" name="smonth" style="width:20px;" value="'.date("m", $pic[startdate]).'">
							<input class="textinput" name="syear" style="width:40px;" value="'.date("Y", $pic[startdate]).'"> -
							<input class="textinput" name="shour" style="width:20px;" value="'.date("H", $pic[startdate]).'">
							<input class="textinput" name="smin" style="width:20px;" value="'.date("i", $pic[startdate]).'">
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[gallery_timed_enddate].':</b><br><span class="small">'.$FS_PHRASES[galery_add_dateformat].'</span></td>
						<td valign="top">
							<input class="textinput" name="eday" style="width:20px;" value="'.date("d", $pic[enddate]).'">
							<input class="textinput" name="emonth" style="width:20px;" value="'.date("m", $pic[enddate]).'">
							<input class="textinput" name="eyear" style="width:40px;" value="'.date("Y", $pic[enddate]).'"> -
							<input class="textinput" name="ehour" style="width:20px;" value="'.date("H", $pic[enddate]).'">
							<input class="textinput" name="emin" style="width:20px;" value="'.date("i", $pic[enddate]).'">
						</td>
					</tr>
					<tr>
						<td valign="top"><b>'.$FS_PHRASES[gallery_pics_text].':</b></td>
						<td><textarea class="textinput" name="text" style="width:300px; height:100px;">'.$pic[text].'</textarea></td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[gallery_potm_delete].':</b></td>
						<td>
							<input type="checkbox" name="del" id="delete" onClick="delMessage(\''.$FS_PHRASES[gallery_timed_delmessage].'\');">
						</td>
					</tr>
					<tr>
						<td colspan="2" align="right">
							<input type="submit" class="button" value="'.$FS_PHRASES[global_send].'">
						</td>
					</tr>
				</table>
				</form>
	';
}

// Formular und Übersicht
else
{
	// Zeitpunkt ermitteln
	$index = mysql_query("SELECT `enddate` FROM `$FSXL[tableset]_gallery_timed` ORDER BY `enddate` DESC LIMIT 1");
	if (mysql_num_rows($index) == 0)
		$lastpic[enddate] = time();
	else
		$lastpic = mysql_fetch_assoc($index);

	$FSXL[content] .= '
				<form action="?mod=gallery&go=timed" method="post" name="timedform" enctype="multipart/form-data">
				<input type="hidden" name="action" value="newpic">
				<table border="0" cellpadding="2" cellspacing="1" width="90%" style="margin:0px auto;">
					<tr>
						<td colspan="2">
							<span style="font-size:12pt"><b>'.$FS_PHRASES[gallery_pics_newpic].'</b></span><hr>
						</td>
					</tr>
					<tr>
						<td width="250"><b>'.$FS_PHRASES[gallery_pics_file].':</b><br>'.$FS_PHRASES[gallery_pics_file_sub].'</td>
						<td><input type="file" class="textinput" name="file" style="width:300px;"></td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[gallery_pics_pictitle].':</b></td>
						<td><input class="textinput" name="title" style="width:300px;"></td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[gallery_timed_startdate].':</b><br><span class="small">'.$FS_PHRASES[galery_add_dateformat].'</span></td>
						<td valign="top">
							<input class="textinput" name="sday" style="width:20px;" value="'.date("d", $lastpic[enddate]+1).'">
							<input class="textinput" name="smonth" style="width:20px;" value="'.date("m", $lastpic[enddate]+1).'">
							<input class="textinput" name="syear" style="width:40px;" value="'.date("Y", $lastpic[enddate]+1).'"> -
							<input class="textinput" name="shour" style="width:20px;" value="'.date("H", $lastpic[enddate]+1).'">
							<input class="textinput" name="smin" style="width:20px;" value="'.date("i", $lastpic[enddate]+1).'">
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[gallery_timed_enddate].':</b><br><span class="small">'.$FS_PHRASES[galery_add_dateformat].'</span></td>
						<td valign="top">
							<input class="textinput" name="eday" style="width:20px;" value="'.date("d", $lastpic[enddate]+604800).'">
							<input class="textinput" name="emonth" style="width:20px;" value="'.date("m", $lastpic[enddate]+604800).'">
							<input class="textinput" name="eyear" style="width:40px;" value="'.date("Y", $lastpic[enddate]+604800).'"> -
							<input class="textinput" name="ehour" style="width:20px;" value="'.date("H", $lastpic[enddate]+604800).'">
							<input class="textinput" name="emin" style="width:20px;" value="'.date("i", $lastpic[enddate]+604800).'">
						</td>
					</tr>
					<tr>
						<td valign="top"><b>'.$FS_PHRASES[gallery_pics_text].':</b></td>
						<td><textarea class="textinput" name="text" style="width:300px; height:100px;"></textarea></td>
					</tr>
					<tr>
						<td colspan="2" align="right">
							<input type="submit" class="button" value="'.$FS_PHRASES[global_send].'">
						</td>
					</tr>
				</table>
				</form>
				<table border="0" cellpadding="2" cellspacing="1" width="90%" style="margin:0px auto;">
					<tr>
						<td>
							<span style="font-size:12pt"><b>'.$FS_PHRASES[gallery_timed_editpics].'</b></span><hr>
						</td>
					</tr>
					<tr>
						<td>
							<table border="0" cellpadding="2" cellspacing="1" width="100%">
								<tr>
									<td class="alt0"><b>'.$FS_PHRASES[gallery_pics_pictitle].'</b></td>
									<td class="alt0" align="center"><b>'.$FS_PHRASES[gallery_timed_startdate].'</b></td>
									<td class="alt0" align="center"><b>'.$FS_PHRASES[gallery_timed_enddate].'</b></td>
									<td class="alt0" align="center"><b>'.$FS_PHRASES[gallery_galleries_link].'</b></td>
								</tr>
	';

	$index = mysql_query("SELECT `id`, `titel`, `startdate`, `enddate` FROM `$FSXL[tableset]_gallery_timed` ORDER BY `startdate` DESC");
	$i=0;
	while ($pic = mysql_fetch_assoc($index))
	{
		$i++;
		
		if ($pic[startdate] < time() && time() < $pic[enddate])
			$title = '<b>'.$pic[titel].'</b>';
		else
			$title = $pic[titel];
		
		$FSXL[content] .= '
					<tr>
						<td class="alt'.($i%2==0?1:2).'"><a href="?mod=gallery&go=timed&id='.$pic[id].'">'.$title.'</a></td>
						<td align="center" class="alt'.($i%2==0?1:2).'">'.date("d.m.Y | H:i", $pic[startdate]).'</td>
						<td align="center" class="alt'.($i%2==0?1:2).'">'.date("d.m.Y | H:i", $pic[enddate]).'</td>
						<td class="alt'.($i%2==0?1:2).'" align="center"><a href="../index.php?section=timed&detail='.$pic[id].'" target="_blank"><img border="0" src="images/'.$FSXL[style].'_link.gif" alt=""></a></td>
					</tr>
		';
	}
}

?>