<?php

$FSXL[title] = $FS_PHRASES[news_add_title];

// News eintragen
if ($_POST[title] && ($_POST[fp_code] || $_POST[html_code]) && $_POST[username])
{
	// Datum auswerten
	if ($_POST[day] != '' && $_POST[month] != '' && $_POST[year] != '' && $_POST[hour] != '' && $_POST[min] != '')
	{
		$date = mktime($_POST[hour], $_POST[min], 0, $_POST[month], $_POST[day], $_POST[year]);
	}
	else
	{
		$date = time();
	}

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

	$index = mysql_query("INSERT INTO `$FSXL[tableset]_news` (`id`, `catid`, `zoneid`, `titel`, `datum`, `autor`, `text`, `type`, `comments`, `numcomments`, `vbnews`, `postid`)
				VALUES (NULL, $_POST[cat], $_POST[zone], '$_POST[title]', $date, $userid, '$text', $_POST[type], $_POST[comments], 0, 0, 0)");

	$id = mysql_insert_id();
	
	// Newstozone einfügen
	mysql_query("INSERT INTO `$FSXL[tableset]_newstozone` (`newsid`, `zoneid`, `catid`, `date`)
					VALUES ($id, $_POST[zone], 0, $date)");
	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_news_catconnect` WHERE `catid` = $_POST[cat]");
	while ($cat = mysql_fetch_assoc($index)) {
		mysql_query("INSERT INTO `$FSXL[tableset]_newstozone` (`newsid`, `zoneid`, `catid`, `date`)
						VALUES ($id, $cat[zoneid], $_POST[cat], $date)");
	}
	
	// vB News
	if ($_POST[vbnews] == 1)
	{
		// Sofort eintragen
		if ($date <= $FSXL[time])
		{
			$index = mysql_query("SELECT * FROM `$FSXL[tableset]_news` WHERE `id` = $id");
			$news = mysql_fetch_assoc($index);
			$index = mysql_query("SELECT * FROM `$FSXL[tableset]_news_cat` WHERE `id` = $news[catid]");
			$cat = mysql_fetch_assoc($index);
			$forumid = $cat[forumid] > 0 ? $cat[forumid] : $FSXL[config][vb_forum];
			$vbtext = vBText($news[text]);
			$vbtitle = $FSXL[config][vb_prefix] . ' ' . $news[titel];
			$script = preg_replace("/(.*?)\/(admin){0,1}(\/){0,1}(index\.php){0,1}/i", "$1", $_SERVER[SCRIPT_NAME]);
			$url = 'http://'.$_SERVER[SERVER_NAME].'/'.$script.'?section=newsdetail&id='.$id;
			$vbtext .= "\r\n\r\n[url=$url]$FS_PHRASES[news_add_vb_homepage][/url]";
			
			$vb = new vbConnect($FSXL[config][vb_url], $FSXL[config][vb_user], $FSXL[config][vb_password]);
			$vbComments = $_POST[comments]==1?false:true;
			$postid = $vb->newThread($forumid, $vbtitle, $vbtext, $vbComments);
			
			if ($postid)
			{
				mysql_query("UPDATE `$FSXL[tableset]_news` SET `vbnews` = 1, `postid` = $postid WHERE `id` = $id");
				$vb_result = $FS_PHRASES[news_add_vb_added];
			}
			else
			{
				$vb_result = $FS_PHRASES[news_add_vb_addfailed];
			}
		}
		// Später eintragen
		else
		{
			mysql_query("INSERT INTO `$FSXL[tableset]_cronjobs` (`id`, `date`, `order`)
						VALUES (NULL, $date, 'newsforumpost->$id')");
		}
	}
	
	// Submit löschen
	if($_POST[submitid])
	{
		settype($_POST[submitid], 'integer');
		mysql_query("DELETE FROM `$FSXL[tableset]_news_submit` WHERE `id` = $_POST[submitid]");
	}

	// Suchindex
	updateSearchIndex($id, 'news', $text);

	// Links
	foreach ($_POST[linkname] as $key => $value)
	{
		if ($_POST[linkname][$key] && $_POST[linkurl][$key])
		{
			$ltype = $_POST[linktype][$key] ? 1 : 0;

			mysql_query("INSERT INTO `$FSXL[tableset]_news_links` (`id`, `newsid`, `name`, `url`, `type`)
					VALUES (NULL, $id, '".$_POST[linkname][$key]."', '".$_POST[linkurl][$key]."', $ltype)");
		}
	}

	$_SESSION[unset_tmptext] = true;
	$FSXL[content] = '
				<div style="padding:20px; text-align:center;">'.$FS_PHRASES[news_add_added].'<br>'.$vb_result.'</div>
	';

	// Seite neu laden
	if ($_POST[takeover])
	{
		reloadPage('?mod=news&go=editnews&id='.$id);
	}
}

// Übersicht
else
{
	$postername = $_SESSION[user]->username;
	
	// News einsendung
	if ($_GET[submit])
	{
		settype($_GET[submit], 'integer');
		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_news_submit` WHERE `id` = $_GET[submit]");
		if (mysql_num_rows($index) > 0)
		{
			$submit = mysql_fetch_assoc($index);
			$index = mysql_query("SELECT `name` FROM `$FSXL[tableset]_user` WHERE `id` = $submit[user]");
			$postername = mysql_result($index, 0, 'name');
			
			$submitadd = '
				<div style="width:95%; margin:0px auto; padding-bottom:10px;">
					<b>-> <a href="?mod=news&go=submit&del='.$submit[id].'">'.$FS_PHRASES[news_submit_delsubmit].'</a></b>
				</div>
				<input type="hidden" name="submitid" value="'.$submit[id].'">
			';
			
			$title = $submit[title];
			$text = $submit[text];
			$date = $FSXL[time];
			$userid = $submit[user];
			$newstype = 1;
		}
	}
	// News kopie
	elseif ($_GET[cp])
	{
		settype($_GET[cp], 'integer');
		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_news` WHERE `id` = $_GET[cp]");
		if (mysql_num_rows($index) > 0)
		{
			$cp = mysql_fetch_assoc($index);
			$title = $cp[titel];
			$text = $cp[text];
			$date = $cp[datum];

			$index = mysql_query("SELECT `name` FROM `$FSXL[tableset]_user` WHERE `id` = $cp[autor]");
			$postername = mysql_result($index, 0, 'name');

			$userid = $cp[user];
			$catid = $cp[catid];
			$zoneid = $cp[zoneid];
			$newstype = $cp[type];
		}
	}
	// Neue News
	else
	{
		$text = $_SESSION[tmptext];
		$date = $FSXL[time];
		$userid = $_SESSION[user]->userid;
		$newstype = 1;
	}
	
	$postername = str_replace('"', '&quot;', $postername);
	$postername = str_replace("'", '&39;', $postername);

	$FSXL[content] .= '
				<div style="margin-bottom:20px;">
				<form action="?mod=news&go=addnews" method="post" name="newsform" onSubmit="return chkNewsAddForm('.$_SESSION[user]->editor.')" autocomplete="off">
				'.$submitadd.'
				<table border="0" cellpadding="0" cellspacing="0" align="center" width="95%" style="table-layout:fixed">
					<tr>
						<td width="120"><b>'.$FS_PHRASES[news_add_newstitle].':</b></td>
						<td><input class="textinput" name="title" style="width:400px;" value="'.$title.'"></td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[news_add_newsdate].':</b><br><span class="small">'.$FS_PHRASES[news_add_dateformat].'</span></td>
						<td valign="top">
							<input class="textinput" name="day" style="width:20px;" value="'.date("d", $date).'">
							<input class="textinput" name="month" style="width:20px;" value="'.date("m", $date).'">
							<input class="textinput" name="year" style="width:40px;" value="'.date("Y", $date).'"> -
							<input class="textinput" name="hour" style="width:20px;" value="'.date("H", $date).'">
							<input class="textinput" name="min" style="width:20px;" value="'.date("i", $date).'">
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[news_add_newsautor].':</b></td>
						<td>
							<input id="username" onkeyup="findUser()" class="textinput" style="width:200px;" name="username" value="'.$postername.'">
							<input id="userid" type="hidden" name="userid" value="'.$userid.'">
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
			$FSXL[content] .= '<option value="'.$cat[id].'" style="padding-left:30px;" '.($cat[id]==$catid?"selected":"").'>'.$cat[name].'</option>';
		}
		// Normaler Eintrag
		else {
			$FSXL[content] .= '<option value="'.$cat[id].'" '.($cat[id]==$catid?"selected":"").'>'.$cat[name].'</option>';
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
								<option value="'.$zone[id].'" '.($zone[id]==$zoneid?"selected":"").'>'.$zone[name].'</option>
		';
	}

	if (!$_POST[type]) $_POST[type] = 1;
	$FSXL[content] .= '
							</select>
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[news_add_newsformat].':</b></td>
						<td>
							'.$FS_PHRASES[news_add_fscode].'
							<input type="radio" name="type" id="type1" value="1" checked onClick="switchHTMLFS()" '.($newstype == 1 ? "checked" : "").'>
							'.$FS_PHRASES[news_add_htmlcode].'
							<input type="radio" name="type" id="type2" value="2" onClick="switchHTMLFS()" '.($newstype == 2 ? "checked" : "").'>
						</td>
					</tr>
					<tr>
						<td valign="top"><b>'.$FS_PHRASES[news_add_newstext].':</b></td>
						<td>
	';


	// Editor einbinden
	$FSXL[content] .= setEditor($_SESSION[user]->editor, $newstype, $text);
	if ($_SESSION[user]->editor == 0) include('frogpad/fpinclude.php');
	
	if (!$FSXL[config][vb_url] ||!$FSXL[config][vb_user] || !$FSXL[config][vb_password] || !$FSXL[config][vb_forum]) {
		$vbdisable = 'disabled';
	}
	elseif ($FSXL[config][news_vbselect] == 1) {
		$vbnews = 'checked';
	}

	$FSXL[content] .= '
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[news_add_comments].':</b></td>
						<td><input type="checkbox" name="comments" '.($FSXL[config][news_selectcomments]==1?"checked":"").'></td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[news_add_vb].':</b></td>
						<td><input type="checkbox" name="vbnews" '.$vbnews.' '.$vbdisable.'></td>
					</tr>
					<tr>
						<td colspan="2" align="right">
							<input type="submit" name="submit" class="button" value="'.$FS_PHRASES[global_send].'">
							<input type="submit" name="takeover" class="button" value="'.$FS_PHRASES[global_takeover].'">
						</td>
					</tr>
				</table>
				<script type="text/javascript">var currentLinkIndex = 0;</script>
				<table border="0" cellpadding="0" cellspacing="1" align="center" width="95%">
					<tr><td><hr></td></tr>
					<tr>
						<td>
							<b>'.$FS_PHRASES[news_add_links].':</b>
						</td>
					</tr>
	';

	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_news_links` WHERE `newsid` = '$cp[id]' ORDER BY `id`");
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
							<input class="textinput" name="linkname['.$i.']" id="linkname['.$i.']" style="width:150px; margin-bottom:-2px;" onkeyup="addNewsLink(this);" value="'.($submit[source]?$FS_PHRASES[news_submit_source]:"").'">
							<b>'.$FS_PHRASES[news_add_linkurl].':</b>
							<input class="textinput" name="linkurl['.$i.']" id="linkurl['.$i.']" style="width:300px; margin-bottom:-2px;" onkeyup="addNewsLink(this);" value="'.$submit[source].'">
							<br>
							<b>'.$FS_PHRASES[news_add_newwindow].':</b>
							<input type="checkbox" name="linktype['.$i.']" id="linktype['.$i.']" style="margin-bottom:-2px;" '.($submit[source]?"checked":"").'>
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
				</div><p>
	';
}

?>