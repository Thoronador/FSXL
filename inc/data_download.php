<?php

// Download Detailansicht
if ($_GET[id])
{
	// Download lesen
	settype($_GET[id], 'integer');

	// Admin
	if ($_SESSION[user]->isadmin) {
		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_dl` WHERE `id` = '$_GET[id]'");
	}
	// User
	else {
		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_dl` WHERE `id` = '$_GET[id]' AND `date` <= '$FSXL[time]' AND `active` = 1");
	}

	if (mysql_num_rows($index) > 0)
	{
		$download = mysql_fetch_assoc($index);

		// Alterscheck
		$agecheck = agecheck($download[age]);
		if ($agecheck[0] || $_SESSION[user]->isadmin)
		{
			// Pagetitle
			$FSXL[pgtitle] = $download[name];

			// Download Views erhöhen
			@mysql_query("UPDATE `$FSXL[tableset]_dl` SET `views` = `views` + 1 WHERE `id` = '$download[id]'");

			// Kategorie lesen
			$index = mysql_query("SELECT `name` FROM `$FSXL[tableset]_dl_cat` WHERE `id` = '$download[catid]'");
			$cat = mysql_fetch_assoc($index);

			// Template lesen
			$download_tpl = new template('download');
			$download_tpl->getItem('link');

			// Links auslesen
			$index = mysql_query("SELECT * FROM `$FSXL[tableset]_dl_links` WHERE `dlid` = '$download[id]'");
			$totalhits = 0;
			if (mysql_num_rows($index) > 0)
			{
				while ($link = mysql_fetch_assoc($index))
				{
					$i++;
					$download_tpl->newItemNode('link');
					$download_tpl->replaceNodeVar('{altnum}', $i%2==0?1:2);
					$download_tpl->replaceNodeVar('{linkname}', $link[name]);
					$download_tpl->replaceNodeVar('{linkurl}', '/download.php?id=' . $link[id]);
					$download_tpl->replaceNodeVar('{linktarget}', ($link[target] == 1 ? '_blank' : '_self'));
					$download_tpl->replaceNodeVar('{linkhits}', $link[count]);
					$download_tpl->replaceNodeVar('{linksize}', size_it($link[size]));
					$totalhits += $link[count];
				}
			}

			// Statische Variablen ersetzen
			$download_tpl->replaceTplVar('{altnum}', '1');
			$download_tpl->replaceTplVar('{name}', $download[name]);
			$download_tpl->replaceTplVar('{text}', fscode($download[text]));
			$download_tpl->replaceTplVar('{date}', date($FSXL[config][dateformat], $download[date]));
			$download_tpl->replaceTplVar('{autor}', $download[autor]);
			$download_tpl->replaceTplVar('{catname}', $cat[name]);
			$download_tpl->replaceTplVar('{views}', $download[views]);
			$download_tpl->replaceTplVar('{folderid}', $download[catid]);
			$download_tpl->replaceTplVar('{totaldls}', $totalhits);
			if (preg_match('/([a-zA-Z0-9-_\.]*?)@([a-zA-Z0-9-_\.]*?)\.([a-zA-Z]{2,6})/', $download[autor_url], $treffer)) {
				$download[autor_url] = $treffer[3].'%A7%A7'.$treffer[2].'%A7%A7'.$treffer[1];
				$download_tpl->replaceTplVar('{autorurl}', 'inc/mailto.php?link='.$download[autor_url]);
			} else {
				$download_tpl->replaceTplVar('{autorurl}', $download[autor_url]);
			}

			// Links eintragen
			$download_tpl->replaceItem('link');

			// Nur für Benutzer Abfrage
			if ($download[regonly] == 1 && !$_SESSION[loggedin])
			{
				$permission = false;
			}
			else
			{
				$permission = true;
			}
			$download_tpl->switchCondition('permission', $permission);
			
			// Autor URL vorhanden?
			$download_tpl->switchCondition('autorurl', $download[autor_url]);

			// Template ausgeben
			$FSXL[template] .= $download_tpl->code;
			unset($download_tpl);
		}
		// Uhrzeit nicht passend für Alter
		else
		{
			$age_tpl = new template('ageblocker');
			$age_tpl->replaceTplVar('{time}', $agecheck[1]);
			$FSXL[template] .= $age_tpl->code;
		}
	}

	// Download nicht gefunden oder noch nicht freigegeben
	else
	{
		$FSXL[template] .= errorMsg('errorfilenotfound');
	}
}


// Download Übersicht
else
{
	function create_folder_overview($start, $folder, $deep)
	{
		global $FSXL, $download_tpl;

		$deep++;

		// Datenlesen
		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_dl_cat` WHERE `parentid` = '$start' ORDER BY `name`");
		while ($cat = mysql_fetch_assoc($index))
		{
			$index2 = mysql_query("SELECT `id`, `name` FROM `$FSXL[tableset]_dl` WHERE `catid` = '$cat[id]' AND `date` <= '$FSXL[time]' AND `active` = 1 ORDER BY `name`");

			$download_tpl->newItemNode('folder');
			$download_tpl->replaceNodeVar('{foldername}', $cat[name]);
			$download_tpl->replaceNodeVar('{foldertext}', fscode($cat[desc]));
			$download_tpl->replaceNodeVar('{folderid}', $cat[id]);
			$download_tpl->replaceNodeVar('{deep}', ($deep*40));
			$download_tpl->replaceNodeVar('{numfiles}', mysql_num_rows($index2));

			$download_tpl->switchCondition('selected_folder', ($cat[id] == $folder ? true : false), true);

			// Dateien auflisten
			if ($cat[id] == $folder)
			{
				while ($file = mysql_fetch_assoc($index2))
				{
					$download_tpl->newItemNode('folder', 'file');
					$download_tpl->replaceNodeVar('{downloadname}', $file[name]);
					$download_tpl->replaceNodeVar('{downloadid}', $file[id]);
					$download_tpl->replaceNodeVar('{deep}', ($deep*40+40));
				}
			}

			// Unterordner ausgeben
			create_folder_overview($cat[id], $folder, $deep);
		}
	}

	// Template lesen
	$download_tpl = new template('dloverview');
	$download_tpl->getItem('folder');
	$download_tpl->getItem('file');

	// Liste erzeugen
	create_folder_overview(0, $_GET[folder], 0);

	// Template füllen
	$download_tpl->replaceTplVar('{pagetitle}', $FSXL[config][pagetitle]);
	$download_tpl->clearItem('file');
	$download_tpl->replaceItem('folder');

	// Template ausgeben
	$FSXL[template] .= $download_tpl->code;
	unset($download_tpl);
}


?>