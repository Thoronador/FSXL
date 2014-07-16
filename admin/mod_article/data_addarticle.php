<?php

$FSXL[title] = $FS_PHRASES[article_add_title];

// Artikel eintragen
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

	$index = mysql_query("INSERT INTO `$FSXL[tableset]_article` (`id`, `titel`, `datum`, `short`, `autor`, `text`, `type`, `cat`, `zoneid`, `showuser`, `invisible`, `regonly`, `pages`)
				VALUES (NULL, '$_POST[title]', $date, $shortcut, $userid, '$text', $_POST[type], $_POST[cat], $_POST[zone], $showuser, $hide, $regonly, 1)");

	if ($index)
	{
		$id = mysql_insert_id();

		// Suchindex
		if ($hide == 0) updateSearchIndex($id, 'article', $text.' '.$_POST[title]);

		$FSXL[content] = '
				<div style="padding:20px; text-align:center;">'.$FS_PHRASES[article_add_added].'</div>
		';
		$_SESSION[unset_tmptext] = true;

		// Seite neu laden
		if ($_POST[takeover])
		{
			reloadPage('?mod=article&go=editarticle&id='.$id);
		}
	}
	else
	{
		$FSXL[content] = '
				<div style="padding:20px; text-align:center;">'.$FS_PHRASES[article_add_failed].'</div>
		';
	}
}


// Übersicht
else
{
	$postername = $_SESSION[user]->username;
	$postername = str_replace('"', '&quot;', $postername);
	$postername = str_replace("'", '&39;', $postername);

	$FSXL[content] .= '
				<div style="margin-bottom:20px;">
				<form action="?mod=article&go=addarticle" method="post" name="articleform" onSubmit="return chkArticleAddForm('.$_SESSION[user]->editor.')" autocomplete="off">
				<table border="0" cellpadding="0" cellspacing="0" align="center" width="95%" style="table-layout:fixed">
					<tr>
						<td width="120"><b>'.$FS_PHRASES[article_add_articletitle].':</b></td>
						<td><input class="textinput" name="title" style="width:400px;"></td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[article_add_articledate].':</b><br>'.$FS_PHRASES[article_add_dateformat].'</span></td>
						<td valign="top">
							<input class="textinput" name="day" style="width:20px;" value="'.date("d").'">
							<input class="textinput" name="month" style="width:20px;" value="'.date("m").'">
							<input class="textinput" name="year" style="width:40px;" value="'.date("Y").'"> -
							<input class="textinput" name="hour" style="width:20px;" value="'.date("H").'">
							<input class="textinput" name="min" style="width:20px;" value="'.date("i").'">
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[article_add_shortcut].':</b></td>
						<td><input class="textinput" name="short" style="width:200px;"></td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[article_add_articleautor].':</b></td>
						<td>
							<input id="username" onkeyup="findUser()" class="textinput" style="width:200px;" name="username" value="'.$postername.'">
							<input id="userid" type="hidden" name="userid" value="'.$_SESSION[user]->userid.'">
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
								<option value="'.$cat[id].'">'.$cat[name].'</option>
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
								<option value="'.$zone[id].'">'.$zone[name].'</option>
		';
	}

	if (!$_POST[type]) $_POST[type] = 1;
	$FSXL[content] .= '
							</select>
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[article_add_articleformat].':</b></td>
						<td>
							'.$FS_PHRASES[article_add_fscode].'
							<input type="radio" name="type" id="type1" value="1" checked onClick="switchHTMLFS()">
							'.$FS_PHRASES[article_add_htmlcode].'
							<input type="radio" name="type" id="type2" value="2" onClick="switchHTMLFS()">
						</td>
					</tr>
					<tr>
						<td valign="top"><b>'.$FS_PHRASES[article_add_articletext].':</b></td>
						<td>
	';


	// Editor einbinden
	$FSXL[content] .= setEditor($_SESSION[user]->editor, 1, $_SESSION[tmptext]);
	if ($_SESSION[user]->editor == 0) include('frogpad/fpinclude.php');

	$FSXL[content] .= '
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[article_add_showuser].':</b></td>
						<td>
							<input type="checkbox" name="showuser">
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[article_add_hide].':</b></td>
						<td>
							<input type="checkbox" name="hide">
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[article_add_regonly].':</b></td>
						<td>
							<input type="checkbox" name="regonly">
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

?>