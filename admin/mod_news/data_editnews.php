<?php

$FSXL[title] = $FS_PHRASES[news_edit_title];

// News bearbeiten
if ($_POST[title] && ($_POST[fp_code] || $_POST[html_code]) && $_POST[username] && $_POST[day] != '' && $_POST[month] != '' && $_POST[year] != '' && $_POST[hour] != '' && $_POST[min] != '')
{
	settype($_POST[editid], 'integer');
	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_news` WHERE `id` = $_POST[editid]");
	$news = @mysql_fetch_assoc($index);
	mysql_query("DELETE FROM `$FSXL[tableset]_cronjobs` WHERE `order` = 'newsforumpost->$_POST[editid]'");

	// News löschen
	if ($_POST[del])
	{
		mysql_query("DELETE FROM `$FSXL[tableset]_news` WHERE `id` = $_POST[editid]");
		mysql_query("DELETE FROM `$FSXL[tableset]_news_links` WHERE `newsid` = $_POST[editid]");
		mysql_query("DELETE FROM `$FSXL[tableset]_news_comments` WHERE `newsid` = $_POST[editid]");
		mysql_query("DELETE FROM `$FSXL[tableset]_newsconnect` WHERE `article` = $_POST[editid]");
		mysql_query("DELETE FROM `$FSXL[tableset]_newstozone` WHERE `newsid` = $_POST[editid]");
		
		// vB Löschen
		if ($news[postid]) {
			$vb = new vbConnect($FSXL[config][vb_url], $FSXL[config][vb_user], $FSXL[config][vb_password]);
			$vbchk = $vb->deletePost($news[postid]);
			if ($vbchk) $vb_result = $FS_PHRASES[news_edit_vb_deleted];
			else $vb_result = $FS_PHRASES[news_edit_vb_deletfailed];
		}

		$FSXL[content] = '
				<div style="padding:20px; text-align:center;">'.$FS_PHRASES[news_edit_editdeleted].'</div>
		';
	}

	// News editieren
	else
	{
		$date = mktime($_POST[hour], $_POST[min], 0, $_POST[month], $_POST[day], $_POST[year]);

		// Userid ermitteln
		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_user` WHERE `name` = '$_POST[username]'");
		if (mysql_num_rows($index) > 0) {
			$userid = mysql_result($index, 0, 'id');
		} else {
			settype($_POST[userid], 'integer');
			$userid = $_POST[userid];
		}

		settype($_POST[cat], 'integer');
		settype($_POST[zone], 'integer');
		settype($_POST[type], 'integer');
		$_POST[comments] = $_POST[comments] ? 1 : 0;
		$_POST[vbnews] = $_POST[vbnews] ? 1 : 0;

		// html oder fs
		if ($_POST[type] == 1 && $_POST[fp_code])
		{
			$text = $_POST[fp_code];
		}
		elseif ($_POST[type] == 2 && $_POST[html_code])
		{
			$text = $_POST[html_code];
		}
		elseif ($_POST[type] == 1 && $_POST[html_code])
		{
			$text = $_POST[html_code];
			$_POST[type] = 2;
		}
		else
		{
			$text = $_POST[fp_code];
			$_POST[type] = 1;
		}

		$index = mysql_query("UPDATE `$FSXL[tableset]_news` SET
					`catid` = $_POST[cat],
					`zoneid` = $_POST[zone],
					`titel` = '$_POST[title]',
					`datum` = $date,
					`autor` = $userid,
					`text` = '$text',
					`type` = $_POST[type],
					`comments` = $_POST[comments],
					`vbnews` = '$_POST[vbnews]'
					WHERE `id` = $_POST[editid]");

		// Newstozone einfügen
		mysql_query("DELETE FROM `$FSXL[tableset]_newstozone` WHERE `newsid` = $_POST[editid]");
		if ($_POST[zone] != 0) {
			mysql_query("INSERT INTO `$FSXL[tableset]_newstozone` (`newsid`, `zoneid`, `catid`, `date`)
							VALUES ($_POST[editid], $_POST[zone], 0, $date)");
		}
		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_news_catconnect` WHERE `catid` = $_POST[cat]");
		while ($cat = mysql_fetch_assoc($index)) {
			mysql_query("INSERT INTO `$FSXL[tableset]_newstozone` (`newsid`, `zoneid`, `catid`, `date`)
							VALUES ($_POST[editid], $cat[zoneid], $_POST[cat], $date)");
		}
					
		// vB News
		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_news` WHERE `id` = $_POST[editid]");
		$news = mysql_fetch_assoc($index);
		$vbtext = vBText($news[text]);
		$vbtext = str_replace('\r\n', "\r\n", $vbtext);
		$vbtitle = $FSXL[config][vb_prefix] . ' ' . $news[titel];
		$script = preg_replace("/(.*?)\/(admin){0,1}(\/){0,1}(index\.php){0,1}/i", "$1", $_SERVER[SCRIPT_NAME]);
		$url = 'http://'.$_SERVER[SERVER_NAME].'/'.$script.'?section=newsdetail&id='.$_POST[editid];
		$vbtext .= "\r\n\r\n[url=$url]$FS_PHRASES[news_add_vb_homepage][/url]";

		// vB News updaten
		if ($_POST[vbnews] == 1 && $news[vbnews] == 1 && $FSXL[config][vb_url] && $FSXL[config][vb_user] && $FSXL[config][vb_password] && $FSXL[config][vb_forum])
		{
			// Thread existiert schon
			if ($news[postid])
			{
				$vb = new vbConnect($FSXL[config][vb_url], $FSXL[config][vb_user], $FSXL[config][vb_password]);
				$vbComments = $news[comments] == $_POST[comments] ? false : true;
				$vbchk = $vb->editPost($news[postid], $vbtitle, $vbtext, $vbComments);
				
				if ($vbchk) $vb_result = $FS_PHRASES[news_edit_vb_edited];
				else $vb_result = $FS_PHRASES[news_add_vb_editfailed];
			}
			// Thread muss angelegt werden
			else {
				$postnewthread = true;
			}
		}
		// vB News neu erstellen
		if (($_POST[vbnews] == 1 && $news[vbnews] == 0) || $postnewthread)
		{
			// Sofort eintragen
			if ($date <= $FSXL[time])
			{				
				$index = mysql_query("SELECT * FROM `$FSXL[tableset]_news_cat` WHERE `id` = $news[catid]");
				$cat = mysql_fetch_assoc($index);
				$forumid = $cat[forumid] > 0 ? $cat[forumid] : $FSXL[config][vb_forum];
				$vb = new vbConnect($FSXL[config][vb_url], $FSXL[config][vb_user], $FSXL[config][vb_password]);
				$vbComments = $_POST[comments]==1?false:true;
				$postid = $vb->newThread($forumid, $vbtitle, $vbtext, $vbComments);
				
				if ($postid) {
					mysql_query("UPDATE `$FSXL[tableset]_news` SET `vbnews` = 1, `postid` = $postid WHERE `id` = $_POST[editid]");
					$vb_result = $FS_PHRASES[news_add_vb_added];
				}
				else $vb_result = $FS_PHRASES[news_add_vb_addfailed];
			}
			// Später eintragen
			else {
				mysql_query("INSERT INTO `$FSXL[tableset]_cronjobs` (`id`, `date`, `order`)
							VALUES (NULL, $date, 'newsforumpost->$_POST[editid]')");
			}
		}
		// News löschen
		if ($_POST[vbnews] == 0 && $news[vbnews] == 1 && $news[postid])
		{
			$vb = new vbConnect($FSXL[config][vb_url], $FSXL[config][vb_user], $FSXL[config][vb_password]);
			$vbchk = $vb->deletePost($news[postid]);
			
			if ($vbchk) {
				mysql_query("UPDATE `$FSXL[tableset]_news` SET `vbnews` = 0, `postid` = 0 WHERE `id` = $_POST[editid]");
				$vb_result = $FS_PHRASES[news_edit_vb_deleted];
			}
			else $vb_result = $FS_PHRASES[news_edit_vb_deletfailed];
		}

		// Suchindex
		updateSearchIndex($_POST[editid], 'news', $text);

		if ($_POST[linkname])
		{
			foreach($_POST[linkname] as $key => $value)
			{
				if ($_POST[linkid][$key])
				{
					settype($_POST[linkid][$key], 'integer');
					// Link löschen
					if ($_POST[linkdel][$key])
					{
						mysql_query("DELETE FROM `$FSXL[tableset]_news_links` WHERE `id` = " . $_POST[linkid][$key]);
					}
					// Link bearbeiten
					elseif($_POST[linkname][$key] && $_POST[linkurl][$key])
					{
						$ltype = $_POST[linktype][$key] ? 1 : 0;
	
						mysql_query("UPDATE `$FSXL[tableset]_news_links` SET
							`name` = '".$_POST[linkname][$key]."',
							`url`= '".$_POST[linkurl][$key]."',
							`type` = $ltype
							WHERE `id` = " . $_POST[linkid][$key]);
					}
				}
				// Link einfügen
				elseif ($_POST[linkname][$key] && $_POST[linkurl][$key])
				{
					$ltype = $_POST[linktype][$key] ? 1 : 0;
	
					mysql_query("INSERT INTO `$FSXL[tableset]_news_links` (`id`, `newsid`, `name`, `url`, `type`)
							VALUES (NULL, $_POST[editid], '".$_POST[linkname][$key]."', '".$_POST[linkurl][$key]."', $ltype)");
				}
			}
		}

		$FSXL[content] = '
				<div style="padding:20px; text-align:center;">'.$FS_PHRASES[news_edit_editdone].'<br>'.$vb_result.'</div>
		';

		// Seite neu laden
		if ($_POST[takeover])
		{
			reloadPage('?mod=news&go=editnews&id='.$_POST[editid]);
		}
	}
}

// Übersicht
elseif ($_GET[id])
{
	settype($_GET[id], 'integer');
	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_news` WHERE `id` = $_GET[id]");
	$news = mysql_fetch_assoc($index);

	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_user` WHERE `id` = $news[autor]");
	$userdata = mysql_fetch_assoc($index);

	$FSXL[content] = '
				<div style="margin-bottom:20px;">
				<form action="?mod=news&go=editnews&id='.$_GET[id].'" method="post" name="newsform" onSubmit="return chkNewsEditForm('.$_SESSION[user]->editor.')" autocomplete="off">
				<input type="hidden" name="editid" value="'.$news[id].'">
				<table border="0" cellpadding="0" cellspacing="0" align="center" width="95%" style="table-layout:fixed">
					<tr>
						<td width="120"><b>'.$FS_PHRASES[news_add_newstitle].':</b></td>
						<td>
							<input class="textinput" name="title" style="width:380px;" value="'.htmlentities($news[titel], ENT_QUOTES).'">
							<a href="../index.php?section=newsdetail&id='.$news[id].'" target="_blank"><img border="0" src="images/'.$FSXL[style].'_link.gif" style="margin-bottom:2px;" alt=""></a>
							<a href="?mod=news&go=addnews&cp='.$news[id].'"><img border="0" src="images/copy.gif" style="margin-bottom:-3px;" alt="" title="'.$FS_PHRASES[news_edit_copy].'"></a>
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[news_add_newsdate].':</b><br>'.$FS_PHRASES[news_add_dateformat].'</td>
						<td valign="top">
							<input class="textinput" name="day" style="width:20px;" value="'.date("d", $news[datum]).'">
							<input class="textinput" name="month" style="width:20px;" value="'.date("m", $news[datum]).'">
							<input class="textinput" name="year" style="width:40px;" value="'.date("Y", $news[datum]).'"> -
							<input class="textinput" name="hour" style="width:20px;" value="'.date("H", $news[datum]).'">
							<input class="textinput" name="min" style="width:20px;" value="'.date("i", $news[datum]).'">
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[news_add_newsautor].':</b></td>
						<td>
							<input id="username" onkeyup="findUser()" class="textinput" style="width:200px;" name="username" value="'.htmlentities($userdata[name], ENT_QUOTES).'">
							<input id="userid" type="hidden" name="userid" value="'.$userdata[id].'">
							<div unselectable="on" id="userdropdown" class="dropdownwindow" onmouseover="stopUserClose()" onmouseout="closeUser()" style="left:345px; top:258px;"></div>
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[news_add_newscat].':</b></td>
						<td>
							<select name="cat" class="textinput" style="width:400px;">
	';

	// Kategorien auslesen
	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_news_cat` ORDER BY `name`");
	$lastgroup = '';
	while ($cat = mysql_fetch_assoc($index))
	{
		// Gruppe
		if (preg_match("/([a-zA-Z0-9]*?)_(.*)/i", $cat[name], $match)) {
			// Neue Gruppe
			if ($match[1] != $lastgroup) {
				$lastgroup = $match[1];
				$FSXL[content] .= '<option style="font-weight:bold; font-style:italic;" disabled="disabled">'.$match[1].'_</option>';
			}
			$cat[name] = preg_replace("/([a-zA-Z0-9]*?)_(.*)/i", "$2", $cat[name]);
			$FSXL[content] .= '<option value="'.$cat[id].'" style="padding-left:30px;" '.($cat[id]==$news[catid]?"selected":"").'>'.$cat[name].'</option>';
		}
		// Normaler Eintrag
		else {
			$FSXL[content] .= '<option value="'.$cat[id].'" '.($cat[id]==$news[catid]?"selected":"").'>'.$cat[name].'</option>';
		}
	}

	$FSXL[content] .= '
							</select>
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[news_add_zone].':</b><br>'.$FS_PHRASES[news_add_zone_sub].'</td>
						<td>
							<select name="zone" class="textinput" style="width:400px;">
								<option value="0" style="font-style:italic;">'.$FS_PHRASES[news_add_nozone].'</option>
	';

	// Zonen auslesen
	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_zones` ORDER BY `name`");
	while ($zone = mysql_fetch_assoc($index))
	{
		$FSXL[content] .= '
								<option value="'.$zone[id].'" '.($zone[id] == $news[zoneid] ? "selected" : "").'>'.$zone[name].'</option>
		';
	}

	$FSXL[content] .= '
							</select>
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[news_add_newsformat].':</b></td>
						<td>
							'.$FS_PHRASES[news_add_fscode].'
							<input type="radio" name="type" onClick="switchHTMLFS()" id="type1" value="1" '.($news[type] == 1 ? "checked" : "").'>
							'.$FS_PHRASES[news_add_htmlcode].'
							<input type="radio" name="type" onClick="switchHTMLFS()" id="type2" value="2" '.($news[type] == 2 ? "checked" : "").'>
						</td>
					</tr>
					<tr>
						<td valign="top"><b>'.$FS_PHRASES[news_add_newstext].':</b></td>
						<td>
	';

	// Editor einbinden
	$FSXL[content] .= setEditor($_SESSION[user]->editor, $news[type], $news[text]);
	if ($_SESSION[user]->editor == 0) include('frogpad/fpinclude.php');

	if (!$FSXL[config][vb_url] ||!$FSXL[config][vb_user] || !$FSXL[config][vb_password] || !$FSXL[config][vb_forum]) {
		$vbdisable = 'disabled';
	}

	$FSXL[content] .= '
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[news_add_comments].':</b></td>
						<td><input type="checkbox" name="comments" '.($news[comments] == 1 ? "checked" : "").'></td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[news_add_vb].':</b></td>
						<td><input type="checkbox" name="vbnews" '.($news[vbnews]==1?"checked":"").' '.$vbdisable.' id="vbnews"  onClick="vbDelMessage(\''.$FS_PHRASES[news_edit_vbdelmessage].'\');"></td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[news_edit_delete].':</b></td>
						<td>
							<input type="checkbox" name="del" id="delete" onClick="delMessage(\''.$FS_PHRASES[news_edit_delmessage].'\');">
						</td>
					</tr>
					<tr>
						<td colspan="2" align="right">
							<input type="submit" name="submit" class="button" value="'.$FS_PHRASES[global_send].'">
							<input type="submit" name="takeover" class="button" value="'.$FS_PHRASES[global_takeover].'">
						</td>
					</tr>
				</table>
				<table border="0" cellpadding="0" cellspacing="1" align="center" width="95%">
					<tr><td><hr></td></tr>
					<tr>
						<td>
							<b>'.$FS_PHRASES[news_add_links].':</b>
							<input type="hidden" name="numlinks" id="numlinks">
						</td>
					</tr>
	';

	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_news_links` WHERE `newsid` = $_GET[id] ORDER BY `id`");
	$FSXL[content] .= '<tr><td><script type="text/javascript">var currentLinkIndex = '.mysql_num_rows($index).';</script></td></tr>';
	$i=0;
	while ($link = mysql_fetch_assoc($index))
	{
		$FSXL[content] .= '
					<tr>
						<td class="alt'.($i%2 == 0 ? 1 : 2).'" style="padding:5px;">
							<input type="hidden" name="linkid['.$i.']" value="'.$link[id].'">
							<b>'.$FS_PHRASES[news_add_linkname].':</b>
							<input class="textinput" name="linkname['.$i.']" style="width:150px; margin-bottom:-2px;" value="'.$link[name].'">
							<b>'.$FS_PHRASES[news_add_linkurl].':</b>
							<input class="textinput" name="linkurl['.$i.']" style="width:300px; margin-bottom:-2px;" value="'.$link[url].'">
							<br>
							<div style="float:right;">
								<b>'.$FS_PHRASES[news_edit_delete].':</b>
								<input type="checkbox" name="linkdel['.$i.']" style="margin-bottom:-2px;">
							</div>
							<b>'.$FS_PHRASES[news_add_newwindow].':</b>
							<input type="checkbox" name="linktype['.$i.']" style="margin-bottom:-2px;" '.($link[type] == 1 ? "checked" : "").'>
						</td>
					</tr>
		';
		$i++;
	}

	$FSXL[content] .= '
					<tr>
						<td class="alt'.($i%2 == 0 ? 1 : 2).'" style="padding:5px;">
							<b>'.$FS_PHRASES[news_add_linkname].':</b>
							<input class="textinput" name="linkname['.$i.']" id="linkname['.$i.']" style="width:150px; margin-bottom:-2px;" onkeyup="addNewsLink(this);">
							<b>'.$FS_PHRASES[news_add_linkurl].':</b>
							<input class="textinput" name="linkurl['.$i.']" id="linkurl['.$i.']" style="width:300px; margin-bottom:-2px;" onkeyup="addNewsLink(this);">
							<br>
							<b>'.$FS_PHRASES[news_add_newwindow].':</b>
							<input type="checkbox" name="linktype['.$i.']" id="linktype['.$i.']" style="margin-bottom:-2px;">
						</td>
					</tr>
					<tr>
						<td align="right">
							<br>
							<input type="submit" name="submit" class="button" value="'.$FS_PHRASES[global_send].'">
							<input type="submit" name="takeover" class="button" value="'.$FS_PHRASES[global_takeover].'">
						</td>
					</tr>
				</table>
				</form>
				</div>
	';
}

// Liste ausgeben
else
{
	$FSXL[content] .= '
				<div>
				<table border="0" cellpadding="2" cellspacing="1" width="90%" style="margin:0px auto;">
					<tr>
						<td colspan="4"><span style="font-size:12pt;"><b>'.$FS_PHRASES[news_edit_select].'</b></span><hr></td>
					</tr>
					<tr>
						<td>
							<table border="0" cellpadding="2" cellspacing="1" width="100%">
								<tr>
									<td class="alt0"><b>'.$FS_PHRASES[news_add_newstitle].'</b></td>
									<td class="alt0" align="center"><b>'.$FS_PHRASES[news_add_newsdate].'</b></td>
									<td class="alt0" align="center"><b>'.$FS_PHRASES[news_config_comments].'</b></td>
									<td class="alt0" align="center"><b>'.$FS_PHRASES[news_edit_link].'</b></td>
								</tr>
	';

	// Liste
	if (!$_GET[start]) $_GET[start] = 0;
	settype($_GET[start], 'integer');
	$index = mysql_query("SELECT COUNT(`id`) AS `news` FROM `$FSXL[tableset]_news`");
	$count = mysql_fetch_assoc($index);
	$index = mysql_query("SELECT `id`, `titel`, `datum`, `numcomments`, `vbnews`, `postid` FROM `$FSXL[tableset]_news` ORDER BY `datum` DESC LIMIT $_GET[start], 50");
	$currentmonth = '';
	$i=1;
	while ($news = mysql_fetch_assoc($index))
	{
		$i++;
		if (date("n", $news[datum]) != $currentmonth)
		{
			$currentmonth = date("n", $news[datum]);
			$FSXL[content] .= '
					<tr>
						<td class="alt'.($i%2==0?1:2).'" colspan="4"><b>'.$FS_PHRASES[news_edit_months][$currentmonth-1].' '.date("Y", $news[datum]).'</b></td>
					</tr>
			';
			$i++;
		}
		
		if ($news[vbnews] == 1) {
			if ($news[postid]) {
				if (substr($FSXL[config][vb_url], -1) != '/') $FSXL[config][vb_url] .= '/';
				$commentlink = '<a href="'.$FSXL[config][vb_url].'showthread.php?p='.$news[postid].'#post'.$news[postid].'" target="_blank"><img border="0" src="images/'.$FSXL[style].'_link.gif" alt=""></a>';
			}
			else {
				$commentlink = '<img border="0" src="images/'.$FSXL[style].'_link.gif" alt="">';
			}
		}
		else {
			$commentlink = '<a href="?mod=news&go=comments&id='.$news[id].'">'.$news[numcomments].'</a>';
		}
		
		$FSXL[content] .= '
					<tr>
						<td class="alt'.($i%2==0?1:2).'" style="padding-left:20px;"><a href="?mod=news&go=editnews&id='.$news[id].'">'.$news[titel].'</a></td>
						<td class="alt'.($i%2==0?1:2).'" align="center">'.date("d.m.Y | H:i", $news[datum]).'</td>
						<td class="alt'.($i%2==0?1:2).'" align="center">'.$commentlink.'</td>
						<td align="center" class="alt'.($i%2==0?1:2).'"><a href="../index.php?section=newsdetail&id='.$news[id].'" target="_blank"><img border="0" src="images/'.$FSXL[style].'_link.gif" alt=""></a></td>
					</tr>
		';
	}

	$FSXL[content] .= '
					<tr>
						<td colspan="4" style="padding-top:20px;">
	';
	if ($_GET[start]+50 < $count[news])
	{
		$offset = $_GET[start] + 50;
		$FSXL[content] .= '<span style="float:right;"><a href="?mod=news&go=editnews&start='.$offset.'"><b>'.$FS_PHRASES[news_edit_older].' ></b></a></span>';
	}
	if ($_GET[start] > 0)
	{
		if ($_GET[start] > 50) $offset = $_GET[start] - 50;
		else $offset = 0;
		$FSXL[content] .= '<a href="?mod=news&go=editnews&start='.$offset.'"><b>< '.$FS_PHRASES[news_edit_newer].'</b></a>';
	}
	$FSXL[content] .= '
							</table>
						</td>
					</tr>
				</table>
				</div>
	';
}


?>