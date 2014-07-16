<?php

$FSXL[title] = $FS_PHRASES[news_cats_title];
$FSXL[content] = '';

// Kategorie erstellen
if ($_POST[action] == 'newcat' && $_POST[name])
{
	settype($_POST[forumid], 'integer');
	$index = mysql_query("INSERT INTO `$FSXL[tableset]_news_cat` (`id`, `name`, `forumid`) 
							VALUES (NULL, '$_POST[name]', $_POST[forumid])");
	$id = mysql_insert_id();

	// Zonen einfügen
	if ($_POST[zone])
	{
		foreach ($_POST[zone] as $key => $value)
		{
			settype($key, 'integer');
			$index = mysql_query("INSERT INTO `$FSXL[tableset]_news_catconnect` (`catid`, `zoneid`) VALUES ($id, $key)");
		}
	}

	$FSXL[content] .= '
				<div style="padding:20px; text-align:center;">'.$FS_PHRASES[news_cats_catcreated].'</div><p>
	';
}

// Kategorie editieren
if ($_POST[action] == 'editcat' && $_POST[name])
{
	settype($_POST[editid], 'integer');
	settype($_POST[forumid], 'integer');
	$index = mysql_query("UPDATE `$FSXL[tableset]_news_cat` SET `name` = '$_POST[name]', `forumid` =  $_POST[forumid] WHERE `id` = $_POST[editid]");

	// Zonen löschen
	$index = mysql_query("DELETE FROM `$FSXL[tableset]_news_catconnect` WHERE `catid` = $_POST[editid]");

	// Newsconnect löschen
	$index = mysql_query("DELETE FROM `$FSXL[tableset]_newstozone` WHERE `catid` = $_POST[editid]");
	
	// News auslesen
	$index = mysql_query("SELECT `id`, `datum` FROM `$FSXL[tableset]_news` WHERE `catid` = $_POST[editid]");
	$newsids = array();
	while ($news = mysql_fetch_assoc($index)) {
		array_push($newsids, array($news[id], $news[datum]));
	}

	// Zonen einfügen
	if ($_POST[zone])
	{
		foreach ($_POST[zone] as $key => $value)
		{
			settype($key, 'integer');
			$index = mysql_query("INSERT INTO `$FSXL[tableset]_news_catconnect` (`catid`, `zoneid`) VALUES ($_POST[editid], $key)");
			
			// Newstozone einfügen
			$query = 'INSERT INTO `fsxl_newstozone` (`newsid`, `zoneid`, `catid`, `date`) VALUES';
			foreach($newsids AS $news){
				$query .= " ($news[0], $key, $_POST[editid], $news[1]),";
			}
			$query = substr($query, 0, -1) . ';';
			$index = mysql_query($query);
		}
	}
	
	$FSXL[content] = '
				<div style="padding:20px; text-align:center;">'.$FS_PHRASES[news_cats_cateditdone].'</div>
	';
}

// Kategorie editieren (formular)
elseif ($_GET[id])
{
	settype($_GET[id], 'integer');
	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_news_cat` WHERE `id` = $_GET[id]");
	$cat = mysql_fetch_assoc($index);

	$FSXL[content] = '
				<form action="?mod=news&go=cats" method="post">
				<input type="hidden" name="action" value="editcat">
				<input type="hidden" name="editid" value="'.$cat[id].'">
				<table border="0" cellpadding="2" cellspacing="1" width="90%" style="margin:0px auto;">
					<tr>
						<td width="250"><b>'.$FS_PHRASES[news_cats_name].':</b></td>
						<td><input class="textinput" name="name" style="width:300px;" value="'.$cat[name].'"></td>
					</tr>
					<tr>
						<td width="250"><b>'.$FS_PHRASES[news_config_vbforum].':</b><br>'.$FS_PHRASES[news_config_vbforum_sub2].'</td>
						<td><input class="textinput" name="forumid" style="width:50px;" value="'.$cat[forumid].'"></td>
					</tr>
					<tr>
						<td valign="top"><b>'.$FS_PHRASES[news_cats_inzones].':</b><br>'.$FS_PHRASES[news_cats_inzones_sub].'</td>
						<td>
	';

	// Zonen auflisten
	$index = @mysql_query("SELECT * FROM `$FSXL[tableset]_zones` ORDER BY `name`");
	while ($zone = mysql_fetch_assoc($index))
	{
		$index2 = mysql_query("SELECT * FROM `$FSXL[tableset]_news_catconnect` WHERE `catid` = $_GET[id] AND `zoneid` = $zone[id]");
		if (mysql_num_rows($index2) == 0)
		{
			$select = "";
		}
		else
		{
			$select = "checked";
		}

		$FSXL[content] .= '
						<input type="checkbox" name="zone['.$zone[id].']" '.$select.'> '.$zone[name].'<br>
		';
	}

	$FSXL[content] .= '
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

// Übersicht
else
{
	$FSXL[content] .= '
				<form action="?mod=news&go=cats" method="post">
				<input type="hidden" name="action" value="newcat">
				<table border="0" cellpadding="2" cellspacing="1" width="90%" style="margin:0px auto;">
					<tr>
						<td colspan="2"><span style="font-size:12pt;"><b>'.$FS_PHRASES[news_cats_newcat].'</b></span><hr></td>
					</tr>
					<tr>
						<td width="250"><b>'.$FS_PHRASES[news_cats_name].':</b></td>
						<td><input class="textinput" name="name" style="width:300px;"></td>
					</tr>
					<tr>
						<td width="250"><b>'.$FS_PHRASES[news_config_vbforum].':</b><br>'.$FS_PHRASES[news_config_vbforum_sub2].'</td>
						<td><input class="textinput" name="forumid" style="width:50px;"></td>
					</tr>
					<tr>
						<td valign="top"><b>'.$FS_PHRASES[news_cats_inzones].':</b><br>'.$FS_PHRASES[news_cats_inzones_sub].'</td>
						<td>
	';

	// Zonen auflisten
	$index = @mysql_query("SELECT * FROM `$FSXL[tableset]_zones` ORDER BY `name`");
	while ($zone = mysql_fetch_assoc($index))
	{
		$FSXL[content] .= '
							<input type="checkbox" name="zone['.$zone[id].']"> '.$zone[name].'<br>
		';
	}

	$FSXL[content] .= '
						</td>
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
						<td><span style="font-size:12pt;"><b>'.$FS_PHRASES[news_cats_editcat].'</b></span><hr></td>
					</tr>
					<tr>
						<td>
							<table border="0" cellpadding="2" cellspacing="1" width="100%">
								<tr>
									<td class="alt0"><b>'.$FS_PHRASES[news_cats_name].'</span></td>
								</tr>
					';

	// Kategorien auflisten
	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_news_cat` ORDER BY `name`");
	$i=0;
	while ($cat = mysql_fetch_assoc($index))
	{
		$i++;
		$FSXL[content] .= '
					<tr>
						<td class="alt'.($i%2==0?1:2).'">
							<a href="?mod=news&go=cats&id='.$cat[id].'">'.$cat[name].'</a><br>
						</td>
					</tr>
		';
	}

	$FSXL[content] .= '
							</table>
						</td>
					</tr>
				</table>
	';
}

?>