<?php

$FSXL[title] = $FS_PHRASES[article_edit_title];

// Artikl bearbeiten
if ($_POST[title] && ($_POST[fp_code] || $_POST[html_code]) && $_POST[username] && $_POST[day] != '' && $_POST[month] != '' && $_POST[year] != '' && $_POST[hour] != '' && $_POST[min] != '')
{
	settype($_POST[editid], 'integer');

	// Artikel löschen
	if ($_POST[del])
	{
		mysql_query("DELETE FROM `$FSXL[tableset]_article` WHERE `id` = $_POST[editid]");
		mysql_query("DELETE FROM `$FSXL[tableset]_articleconnect` WHERE `article` = $_POST[editid]");
		mysql_query("DELETE FROM `$FSXL[tableset]_article_pages` WHERE `article` = $_POST[editid]");

		$FSXL[content] = '
				<div style="padding:20px; text-align:center;">'.$FS_PHRASES[article_edit_deleted].'</div>
		';
	}

	// Artikel editieren
	else
	{
		$date = mktime($_POST[hour], $_POST[min], 0, $_POST[month], $_POST[day], $_POST[year]);

		// Userid ermitteln
		settype($_POST[userid], 'integer');
		$userid = $_POST[userid];

		if ($_POST[short]) $shortcut = "'".$_POST[short]."'";
		else $shortcut = 'NULL';

		settype($_POST[cat], 'integer');
		settype($_POST[zone], 'integer');
		settype($_POST[type], 'integer');
		$showuser = $_POST[showuser] ? 1 : 0;
		$hide = $_POST[hide] ? 1 : 0;
		$regonly = $_POST[regonly] ? 1 : 0;

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

		$index = mysql_query("UPDATE `$FSXL[tableset]_article` SET
					`titel` = '$_POST[title]',
					`datum` = $date,
					`short` = $shortcut,
					`autor` = $userid,
					`text` = '$text',
					`type` = $_POST[type],
					`cat` = $_POST[cat],
					`zoneid` = $_POST[zone],
					`showuser` = $showuser,
					`invisible` = $hide,
					`regonly` = $regonly
					WHERE `id` = $_POST[editid]");

		if ($index)
		{
			// Suchindex
			if ($hide == 1) updateSearchIndex($_POST[editid], 'article', ' ');
			else updateSearchIndex($_POST[editid], 'article', $text);

			$FSXL[content] = '
				<div style="padding:20px; text-align:center;">'.$FS_PHRASES[article_edit_editdone].'</div>
			';

			// Seite neu laden
			if ($_POST[takeover])
			{
				reloadPage('?mod=article&go=editarticle&id='.$_POST[editid]);
			}
		}
		else
		{
			$FSXL[content] = '
				<div style="padding:20px; text-align:center;">'.$FS_PHRASES[article_edit_failed].'</div>
			';
		}
	}
}

// Page hinzufügen/bearbeiten
elseif($_POST[editid] && $_POST[pageid] >= 0 && ($_POST[fp_code] || $_POST[html_code]))
{
	settype($_POST[pageid], 'integer');
	settype($_POST[editid], 'integer');
	$text = $_POST[fp_code] ? $_POST[fp_code] : $_POST[html_code];

	// Page hinzufügen
	if ($_POST[pageid] == 0)
	{
		$chk = mysql_query("INSERT INTO `$FSXL[tableset]_article_pages` (`id`, `article`, `text`) VALUES (NULL, $_POST[editid], '$text')");
		
		// Hinzufügen erfolgreich
		if ($chk)
		{
			$id = mysql_insert_id();
			mysql_query("UPDATE `$FSXL[tableset]_article` SET `pages` = `pages` + 1 WHERE `id` = $_POST[editid]");

			$_SESSION[unset_tmptext] = true;
			$FSXL[content] = '
				<div style="padding:20px; text-align:center;">'.$FS_PHRASES[article_edit_pageadded].'</div>
			';

			if ($_POST[takeover])
			{
				reloadPage('?mod=article&go=editarticle&id='.$_POST[editid].'&page='.$id);
			}
		}
	}
	// Page bearbeiten
	else
	{
		$_SESSION[unset_tmptext] = true;
		// Löschen
		if ($_POST[del])
		{
			mysql_query("DELETE FROM `$FSXL[tableset]_article_pages` WHERE `id` = $_POST[pageid]");
			mysql_query("UPDATE `$FSXL[tableset]_article` SET `pages` = `pages` - 1 WHERE `id` = $_POST[editid]");

			$FSXL[content] = '
				<div style="padding:20px; text-align:center;">'.$FS_PHRASES[article_edit_pagedeleted].'</div>
			';
		}
		// Bearbeiten
		else
		{
			mysql_query("UPDATE `$FSXL[tableset]_article_pages` SET `text` = '$text' WHERE `id` = $_POST[pageid]");

			$FSXL[content] = '
				<div style="padding:20px; text-align:center;">'.$FS_PHRASES[article_edit_pageedited].'</div>
			';
		
			if ($_POST[takeover])
			{
				reloadPage('?mod=article&go=editarticle&id='.$_POST[editid].'&page='.$_POST[pageid]);
			}
		}
	}
}

// Unterseite einfügen/bearbeiten Formular
elseif ($_GET[id] && $_GET[page])
{
	settype($_GET[id], 'integer');
	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_article` WHERE `id` = $_GET[id]");
	$article = mysql_fetch_assoc($index);
	
	// Neue Seite
	if ($_GET[page] == 'new')
	{
		$pagenum = $article[pages] + 1;
		$page[text] = '';
		$page[id] = '0';
	}
	else
	{
		settype($_GET[page], 'integer');
		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_article_pages` WHERE `id` = $_GET[page]");
		$page = mysql_fetch_assoc($index);

		$index = mysql_query("SELECT COUNT(`id`) AS `num` FROM `$FSXL[tableset]_article_pages` WHERE `id` <= $_GET[page] AND `article` = $_GET[id]");
		$pagenum = mysql_result($index, 0, 'num')+1;
	}

	$FSXL[content] = '
				<div style="margin-bottom:20px;">
				<form action="?mod=article&go=editarticle&id='.$article[id].'" method="post" name="articleform" autocomplete="off">
				<input type="hidden" name="editid" value="'.$article[id].'">
				<input type="hidden" name="pageid" value="'.$page[id].'">
				<table border="0" cellpadding="0" cellspacing="0" align="center" width="95%" style="table-layout:fixed">
					<tr>
						<td width="120"><b>'.$FS_PHRASES[article_add_articletitle].':</b></td>
						<td>
							'.$article[titel].' ('.$FS_PHRASES[article_edit_page].' '.$pagenum.')
						</td>
					</tr>
					<tr>
						<td width="120" style="width:120px;" valign="top"><b>'.$FS_PHRASES[article_add_articletext].':</b></td>
						<td>
	';

	// Editor einbinden
	$FSXL[content] .= setEditor($_SESSION[user]->editor, $article[type], $page[text]);
	if ($_SESSION[user]->editor == 0) include('frogpad/fpinclude.php');

	$FSXL[content] .= '
						</td>
					</tr>
	';
	
	if ($article[pages] > 1)
	{
		$j = 2;
		$pagenavi = '<b>';
		$index2 = mysql_query("SELECT `id` FROM `$FSXL[tableset]_article_pages` WHERE `article` = $article[id] ORDER BY `id`");
		while ($page2 = mysql_fetch_assoc($index2))
		{
			$pagenavi .= '<a href="?mod=article&go=editarticle&id='.$article[id].'&page='.$page2[id].'">['.$j.']</a> ';
			$j++;
		}
		$pagenavi = substr($pagenavi, 0, strlen($pagenavi)-1) . '</b>';

		$FSXL[content] .= '
					<tr>
						<td width="120"><b>'.$FS_PHRASES[article_edit_pages].':</b></td>
						<td>
							<a href="?mod=article&go=editarticle&id='.$article[id].'"><b>[1]</b></a>
							'.$pagenavi.'
						</td>
					</tr>
		';
	}

	if ($page[id] > 0)
	{
		$FSXL[content] .= '
					<tr>
						<td><b>'.$FS_PHRASES[article_edit_delete].':</b></td>
						<td>
							<input type="checkbox" name="del" id="delete" onClick="delMessage(\''.$FS_PHRASES[article_edit_delmessagepage].'\');">
						</td>
					</tr>
		';
	}
	
	$FSXL[content] .= '
					<tr>
						<td colspan="2" align="right">
							<input type="submit" name="submit" class="button" value="'.$FS_PHRASES[global_send].'">
							<input type="submit" name="takeover" class="button" value="'.$FS_PHRASES[global_takeover].'">
						</td>
					</tr>
				</table>
				</form>
				</div>
	';
}

// Artikel Formular
elseif ($_GET[id] && !$_GET[page])
{
	settype($_GET[id], 'integer');
	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_article` WHERE `id` = $_GET[id]");
	$article = mysql_fetch_assoc($index);

	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_user` WHERE `id` = $article[autor]");
	$userdata = mysql_fetch_assoc($index);

	$FSXL[content] = '
				<div style="margin-bottom:20px;">
				<form action="?mod=article&go=editarticle&id='.$_GET[id].'" method="post" name="articleform" onSubmit="return chkArticleEditForm('.$_SESSION[user]->editor.')" autocomplete="off">
				<input type="hidden" name="editid" value="'.$article[id].'">
				<table border="0" cellpadding="0" cellspacing="0" align="center" width="95%" style="table-layout:fixed">
					<tr>
						<td width="120"><b>'.$FS_PHRASES[article_add_articletitle].':</b></td>
						<td>
							<input class="textinput" name="title" style="width:390px;" value="'.htmlentities($article[titel], ENT_QUOTES).'">
							<a href="../index.php?section=article&id='.$article[id].'" target="_blank"><img border="0" src="images/'.$FSXL[style].'_link.gif" style="margin-bottom:2px;" alt=""></a>
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[article_add_articledate].':</b><br>'.$FS_PHRASES[article_add_dateformat].'</td>
						<td valign="top">
							<input class="textinput" name="day" style="width:20px;" value="'.date("d", $article[datum]).'">
							<input class="textinput" name="month" style="width:20px;" value="'.date("m", $article[datum]).'">
							<input class="textinput" name="year" style="width:40px;" value="'.date("Y", $article[datum]).'"> -
							<input class="textinput" name="hour" style="width:20px;" value="'.date("H", $article[datum]).'">
							<input class="textinput" name="min" style="width:20px;" value="'.date("i", $article[datum]).'">
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[article_add_shortcut].':</b></td>
						<td><input class="textinput" name="short" style="width:200px;" value="'.$article[short].'"></td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[article_add_articleautor].':</b></td>
						<td>
							<input id="username" onkeyup="findUser()" class="textinput" style="width:200px;" name="username" value="'.htmlentities($userdata[name], ENT_QUOTES).'">
							<input id="userid" type="hidden" name="userid" value="'.$userdata[id].'">
							<div unselectable="on" id="userdropdown" class="dropdownwindow" onmouseover="stopUserClose()" onmouseout="closeUser()" style="left:345px; top:285px;"></div>
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[article_add_articlecat].':</b></td>
						<td>
							<select name="cat" class="textinput" style="width:400px;">
								<option value="0">'.$FS_PHRASES[article_cats_blank].'</option>
								<option value="0">------------------------------</option>
	';

	// Kategorien auslesen
	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_article_cat` ORDER BY `name`");
	while ($cat = mysql_fetch_assoc($index))
	{
		$FSXL[content] .= '
								<option value="'.$cat[id].'" '.($cat[id] == $article[cat] ? "selected" : "").'>'.$cat[name].'</option>
		';
	}

	$FSXL[content] .= '
							</select>
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[article_add_zone].':</b><br>'.$FS_PHRASES[article_add_zone_sub].'</td>
						<td>
							<select name="zone" class="textinput" style="width:400px;">
								<option value="0" style="font-style:italic;">'.$FS_PHRASES[article_add_nozone].'</option>
	';

	// Zonen auslesen
	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_zones` ORDER BY `name`");
	while ($zone = mysql_fetch_assoc($index))
	{
		$FSXL[content] .= '
								<option value="'.$zone[id].'" '.($zone[id] == $article[zoneid] ? "selected" : "").'>'.$zone[name].'</option>
		';
	}

	$FSXL[content] .= '
							</select>
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[article_add_articleformat].':</b></td>
						<td>
							'.$FS_PHRASES[article_add_fscode].'
							<input type="radio" name="type" onClick="switchHTMLFS()" id="type1" value="1" '.($article[type] == 1 ? "checked" : "").'>
							'.$FS_PHRASES[article_add_htmlcode].'
							<input type="radio" name="type" onClick="switchHTMLFS()" id="type2" value="2" '.($article[type] == 2 ? "checked" : "").'>
						</td>
					</tr>
					<tr>
						<td width="120" style="width:120px;" valign="top"><b>'.$FS_PHRASES[article_add_articletext].':</b></td>
						<td>
	';

	// Editor einbinden
	$FSXL[content] .= setEditor($_SESSION[user]->editor, $article[type], $article[text]);
	if ($_SESSION[user]->editor == 0) include('frogpad/fpinclude.php');

	if ($article[pages] > 1)
	{
		$j = 2;
		$pagenavi = '<b>';
		$index2 = mysql_query("SELECT `id` FROM `$FSXL[tableset]_article_pages` WHERE `article` = $article[id] ORDER BY `id`");
		while ($page = mysql_fetch_assoc($index2))
		{
			$pagenavi .= '<a href="?mod=article&go=editarticle&id='.$article[id].'&page='.$page[id].'">['.$j.']</a> ';
			$j++;
		}
		$pagenavi = substr($pagenavi, 0, strlen($pagenavi)-1) . '</b>';
	}

	$FSXL[content] .= '
						</td>
					</tr>
					<tr>
						<td width="120"><b>'.$FS_PHRASES[article_edit_pages].':</b></td>
						<td>
							'.$pagenavi.'
							<a href="?mod=article&go=editarticle&id='.$article[id].'&page=new">'.$FS_PHRASES[article_edit_addpage].'</a>
						</td>
					</tr>
					<tr>
						<td width="120"><b>'.$FS_PHRASES[article_add_showuser].':</b></td>
						<td>
							<input type="checkbox" name="showuser"'.($article[showuser] == 1 ? "checked" : "").'>
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[article_add_hide].':</b></td>
						<td>
							<input type="checkbox" name="hide"'.($article[invisible] == 1 ? "checked" : "").'>
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[article_add_regonly].':</b></td>
						<td>
							<input type="checkbox" name="regonly"'.($article[regonly] == 1 ? "checked" : "").'>
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[article_edit_delete].':</b></td>
						<td>
							<input type="checkbox" name="del" id="delete" onClick="delMessage(\''.$FS_PHRASES[article_edit_delmessage].'\');">
						</td>
					</tr>
					<tr>
						<td colspan="2" align="right">
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
				<table border="0" cellpadding="2" cellspacing="1" width="90%" style="margin:0px auto;">
					<tr>
						<td>
							<span style="font-size:12pt"><b>'.$FS_PHRASES[article_edit_select].'</b></span><hr>
						</td>
					</tr>
					<tr>
						<td>
							<table border="0" cellpadding="2" cellspacing="1" width="100%">
								<tr>
									<td class="alt0"><b>'.$FS_PHRASES[article_add_articletitle].'</b></td>
									<td class="alt0" align="center"><b>'.$FS_PHRASES[article_add_articledate].'</b></td>
									<td class="alt0" align="center"><b>'.$FS_PHRASES[article_edit_link].'</b></td>
								</tr>
	';

	// Liste
	$currentcat = '';
	$index = mysql_query("SELECT `id`, `titel`, `datum`, `cat`, `pages` FROM `$FSXL[tableset]_article` ORDER BY `cat`, `datum` DESC");
	$i=0;
	while ($article = mysql_fetch_assoc($index))
	{
		$i++;
		$pagenavi = '';
		if ($currentcat != $article[cat])
		{
			$currentcat = $article[cat];
			if ($currentcat != 0)
			{
				$index2 = mysql_query("SELECT `name` FROM `$FSXL[tableset]_article_cat` WHERE `id` = $article[cat]");
				$cat = mysql_fetch_assoc($index2);
			}
			else
			{
				$cat[name] = $FS_PHRASES[article_edit_blank];
			}
			$FSXL[content] .= '
					<tr>
						<td colspan="3" class="alt'.($i%2==0?1:2).'"><b>'.$cat[name].'</b></td>
					</tr>
			';
			$i++;
		}
		
		// Pages
		if ($article[pages] > 1)
		{
			$j = 2;
			$pagenavi = '<i>(' . $FS_PHRASES[article_edit_page] . ': ';
			$index2 = mysql_query("SELECT `id` FROM `$FSXL[tableset]_article_pages` WHERE `article` = $article[id] ORDER BY `id`");
			while ($page = mysql_fetch_assoc($index2))
			{
				$pagenavi .= '<a href="?mod=article&go=editarticle&id='.$article[id].'&page='.$page[id].'">'.$j.'</a>, ';
				$j++;
			}
			$pagenavi = substr($pagenavi, 0, strlen($pagenavi)-2) . ')</i>';
		}
		
		$FSXL[content] .= '
					<tr>
						<td class="alt'.($i%2==0?1:2).'" style="padding-left:20px;"><a href="?mod=article&go=editarticle&id='.$article[id].'">'.$article[titel].'</a> '.$pagenavi.'</td>
						<td align="center" class="alt'.($i%2==0?1:2).'">'.date("d.m.Y | H:i", $article[datum]).'</td>
						<td align="center" class="alt'.($i%2==0?1:2).'"><a href="../index.php?section=article&id='.$article[id].'" target="_blank"><img border="0" src="images/'.$FSXL[style].'_link.gif" alt=""></a></td>
					</tr>
		';
	}

	$FSXL[content] .= '
							</table
						<td>
					<tr>
				</table>
	';
}


?>