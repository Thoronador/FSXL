<?php


@include("config.inc.php");
@include("functions.inc.php");
@include("class.inc.php");

// Datanbank Verbindung aufbauen
@$db = new mysql($SQL[host], $SQL[user], $SQL[pass], $SQL[data]);
if (!$db->error[error])
{
	// Konfiguration laden
	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_config`");
	while ($arr = mysql_fetch_assoc($index))
	{
		$FSXL[config][$arr[name]] = $arr[value];
	}
	include('lang_'.$FSXL[config][syslanguage].'.php');

	// Phrases und Funktionen der Mods einbinden
	$index = @mysql_query("SELECT `name` FROM `$FSXL[tableset]_mod`");
	while ($arr = mysql_fetch_assoc($index))
	{
		// Phrasen
		if (file_exists('../mod_'.$arr[name].'/lang_'.$FSXL[config][syslanguage].'.php'))
		{
			include('../mod_'.$arr[name].'/lang_'.$FSXL[config][syslanguage].'.php');
		}
		// Falls Sprache nicht vorhanden default versuchen zu laden
		elseif (file_exists('mod_'.$arr[name].'/lang_'.$FSXL[defaultlanguage].'.php'))
		{
			include('../mod_'.$arr[name].'/lang_'.$FSXL[defaultlanguage].'.php');
		}
	}

	//Style auswählen
	switch ($_SESSION[user]->adminstyle)
	{
		// Rot
		case 2:
			$FSXL[style] = "red";
			break;
		// blau
		case 3:
			$FSXL[style] = "blue";
			break;
		// Grün
		default:
			$FSXL[style] = "green";
	}


	header("Content-type: text/javascript");

	echo '
		// Job Text anzeigen
		function toggleJob(id)
		{
			if (document.getElementById("job"+id).style.display == "none")
			{
				document.getElementById("job"+id).style.display = "block";
				document.getElementById("jobimg"+id).src = "images/'.$FSXL[style].'_arrow_left.gif";
			}
			else
			{
				document.getElementById("job"+id).style.display = "none";
				document.getElementById("jobimg"+id).src = "images/'.$FSXL[style].'_arrow_bottom.gif";
			}
		}
	
		// Tooltip anzeigen
		function toggleTooltip(id)
		{
			imgTooltip = document.getElementById("imgtooltip"+id);
			if (document.getElementById("imgtooltip"+id).style.display == "block")
			{
				document.getElementById("imgtooltip"+id).style.display = "none";
			}
			else
			{
				document.getElementById("imgtooltip"+id).style.display = "block";
			}
		}

		// Text speichern
		function saveText(type)
		{
			if(document.getElementById("fp_code") && !document.getElementById("delete"))
			{
				switch(type)
				{
					// Frogpad
					case 0:
						if (editorState == "design")
						{
							htmlToBBcode();
						}
					// Frogedit
					case 1:
					// Textfeld
					case 2:
						var text = document.getElementById("fp_code").value;
						break;
				}

				http_request = false;
		
				if (window.XMLHttpRequest) // Mozilla, Safari,...
				{
					http_request = new XMLHttpRequest();
				}
				else if (window.ActiveXObject) // IE
				{
					try
					{
						http_request = new ActiveXObject("Msxml2.XMLHTTP");
					}
					catch (e)
					{
						try
						{
							http_request = new ActiveXObject("Microsoft.XMLHTTP");
						}
						catch (e) {}
					}
				}

				if (!http_request)
				{
					alert("Ende :( Kann keine XMLHTTP-Instanz erzeugen");
					return false;
				}
				text = text.replace(/&/gi, "u|n|d");
				params="text="+text;
				http_request.open("POST", "inc/savetext.php", true);
				http_request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				http_request.setRequestHeader("Content-length", params.length);
				http_request.setRequestHeader("Connection", "close");
				//http_request.onreadystatechange = false;
				http_request.send(params);
			}
		}

		// Texteditor
		var idTextfeld = \'fp_code\';
		var rangeIE = null;

		function fe_setFont(font)
		{
			insertFSCode("font", font);
		}
		function fe_setSize(size)
		{
			insertFSCode("size", size);
		}
		function fe_setColor(color)
		{
			insertFSCode("color", color);
		}

		var rangeIE = "";
		function fe_openLinkBar()
		{
			// IE, Opera
			if(typeof document.selection != \'undefined\')
			{
				rangeIE = document.selection.createRange();
			}

			document.getElementById("fp_toolbar_link").style.display = "block";
		}
		function fe_addLink()
		{
			// IE, Opera
			if(typeof document.selection != \'undefined\')
			{
				rangeIE.select();
			}

			link = document.getElementById("fp_linkurl").value
			target = document.getElementById("fp_linktarget").value
			if (target == "_blank")
			{
				insertFSCode("url", link+" blank");
			}
			else
			{
				insertFSCode("url", link);
			}

			document.getElementById("fp_toolbar_link").style.display = "none";
			document.getElementById("fp_linkurl").value = "";
		}

		function fe_addImage()
		{
			// IE, Opera
			if(typeof document.selection != \'undefined\')
			{
				rangeIE.select();
			}

			link = document.getElementById("fp_imgurl").value
			insertText("[img]"+link+"[/img]", "");

			document.getElementById("fp_toolbar_image").style.display = "none";
			document.getElementById("fp_imgurl").value = "";
		}

		function fe_openImgBar()
		{
			// IE, Opera
			if(typeof document.selection != \'undefined\')
			{
				rangeIE = document.selection.createRange();
			}
			document.getElementById("fp_toolbar_image").style.display = "block";
		}
		function fe_closeImgBar()
		{
			document.getElementById("fp_toolbar_image").style.display = "none";
		}

		function insertFSCode(tag, add)
		{
			if (add) insertText(\'[\' + tag + \'=\' + add + \']\', \'[\/\' + tag + \']\');
			else insertText(\'[\' + tag + \']\', \'[\/\' + tag + \']\');
		}

		function insertText(vor, nach)
		{
			var textfeld = document.getElementById(idTextfeld);
			textfeld.focus();

			// IE, Opera
			if(typeof document.selection != \'undefined\')
			{
				insertIE(textfeld, vor, nach);
			}
			// Gecko
			else if (typeof textfeld.selectionStart != \'undefined\')
			{
				insertGecko(textfeld, vor, nach);
			}
		}

		// IE, OPERA
		function insertIE(textfeld, vor, nach)
		{
			if(!rangeIE) rangeIE = document.selection.createRange();

			if(rangeIE.parentElement().id != idTextfeld)
			{
				rangeIE = null;
				return;
			}
		
			var alterText = rangeIE.text;
			rangeIE.text = vor + alterText + nach;

			if (alterText.length == 0) rangeIE.move(\'character\', -nach.length);
			else rangeIE.moveStart(\'character\', rangeIE.text.length);
     
			rangeIE.select();
			rangeIE = null;
		}

		// Gecko
		function insertGecko(textfeld, vor, nach)
		{
			von = textfeld.selectionStart;
			bis = textfeld.selectionEnd;
			var scrollPos = textfeld.scrollTop;

			anfang = textfeld.value.slice(0, von);
			mitte  = textfeld.value.slice(von, bis);
			ende   = textfeld.value.slice(bis);

			textfeld.value = anfang + vor + mitte + nach + ende;

			if(bis - von == 0)
			{
				textfeld.selectionStart = von + vor.length;
				textfeld.selectionEnd = textfeld.selectionStart;
			}
			else
			{
				textfeld.selectionEnd = bis + vor.length;
				textfeld.selectionStart = von + vor.length;
				//textfeld.setSelectionRange(von + vor.length, bis + vor.length);
			}
			
			textfeld.scrollTop = scrollPos;
			textfeld.focus();
		}

		// Farbe in Galerie einfügen
		function insertColor(color)
		{
			document.getElementById("color").value = color;
			document.getElementById("colorsample").style.backgroundColor = "#" + document.getElementById("color").value;
		}

		// Objekt Löschen
		function delMessage(text)
		{
			if (document.getElementById(\'delete\').checked)
				alert(text);
		}

		// vBObjekt Löschen
		function vbDelMessage(text)
		{
			if (document.getElementById(\'vbnews\').checked == false)
				alert(text);
		}

		// Template Variablen switchen
		function switchTplvar(type)
		{
			document.getElementById("singlebox").style.display = "none";
			document.getElementById("multibox").style.display = "none";
			document.getElementById("includebox").style.display = "none";

			if (document.getElementById("type1").checked)
			{
				document.getElementById("singlebox").style.display = "block";
			}
			if (document.getElementById("type2").checked)
			{
				document.getElementById("multibox").style.display = "block";
			}
			if (document.getElementById("type3").checked)
			{
				document.getElementById("includebox").style.display = "block";
			}
		}

		// HTML oder FS Code switchen
		function switchHTMLFS()
		{
			if (document.getElementById("type1").checked)
			{
				document.getElementById("html_code").style.display = "none";
				document.getElementById("wysiwyg_container").style.display = "block";
			}
			if (document.getElementById("type2").checked)
			{
				document.getElementById("html_code").style.display = "block";
				document.getElementById("wysiwyg_container").style.display = "none";
			}
		}

		// Link hinzufügen Formular Überprüfen
		function chkLinkAddForm()
		{
			if (document.linkform.name.value)
			{
				return true;
			}
			else
			{
				alert(\''.$FS_PHRASES[link_add_js_chkform].'\');
				return false;
			}
		}

		// Link bearbeiten Formular Überprüfen
		function chkLinkEditForm()
		{
			if (document.linkform.name.value &&
					document.linkform.day.value != "" &&
					document.linkform.month.value != "" &&
					document.linkform.year.value != "" &&
					document.linkform.hour.value != "" &&
					document.linkform.min.value != "")
			{
				return true;
			}
			else
			{
				alert(\''.$FS_PHRASES[link_edit_js_chkform].'\');
				return false;
			}
		}

		// Video hinzufügen Formular Überprüfen
		function chkVideoAddForm()
		{
			if (document.videoform.name.value && document.videoform.url.value)
			{
				return true;
			}
			else
			{
				alert(\''.$FS_PHRASES[video_add_js_chkform].'\');
				return false;
			}
		}

		// Video bearbeiten Formular Überprüfen
		function chkVideoEditForm()
		{
			if (document.videoform.name.value && document.videoform.url.value &&
					document.videoform.day.value != "" &&
					document.videoform.month.value != "" &&
					document.videoform.year.value != "" &&
					document.videoform.hour.value != "" &&
					document.videoform.min.value != "")
			{
				return true;
			}
			else
			{
				alert(\''.$FS_PHRASES[video_edit_js_chkform].'\');
				return false;
			}
		}

		// Ticker Formular Überprüfen
		function chkTickerForm()
		{
			if (document.tickerform.name.value && document.tickerform.interval.value)
			{
				return true;
			}
			else
			{
				alert(\''.$FS_PHRASES[ticker_add_js_chkform].'\');
				return false;
			}
		}

		// Tepmale Variable (single) Formular überprüfen
		function chksingleform()
		{
			if (document.singleform.name.value && document.singleform.code.value && document.singleform.interval.value)
			{
				return true;
			}
			else
			{
				alert(\''.$FS_PHRASES[main_tplvars_js_chksingleform].'\');
				return false;
			}
		}

		// Tepmale Variable (multi) Formular überprüfen
		function chkmultiform()
		{
			if (document.multiform.name.value && document.multiform.interval.value)
			{
				return true;
			}
			else
			{
				alert(\''.$FS_PHRASES[main_tplvars_js_chkmultiform].'\');
				return false;
			}
		}

		// Tepmale Variable (include) Formular überprüfen
		function chkincludeform()
		{
			if (document.includeform.name.value && document.includeform.file.value && document.includeform.interval.value)
			{
				return true;
			}
			else
			{
				alert(\''.$FS_PHRASES[main_tplvars_js_chkincludeform].'\');
				return false;
			}
		}

		// Shop Light hinzufügen Formular überprüfen
		function chkShopltAddForm()
		{
			if (document.shopform.name.value && document.shopform.url.value && document.shopform.price.value)
			{
				return true;
			}
			else
			{
				alert(\''.$FS_PHRASES[shoplt_add_js_chkform].'\');
				return false;
			}
		}

		// Shop Light bearbeiten Formular überprüfen
		function chkShopltEditForm()
		{
			return chkShopltAddForm();
		}

		// Download hinzufügen Formular überprüfen
		function chkDlAddForm()
		{
			if (document.dlform.title.value)
			{
				return true;
			}
			else
			{
				alert(\''.$FS_PHRASES[download_add_js_chkform].'\');
				return false;
			}
		}

		// Download bearbeiten Formular überprüfen
		function chkDlEditForm()
		{
			if (document.dlform.title.value && document.dlform.day.value != "" && document.dlform.month.value != "" && 
				document.dlform.year.value != "" && document.dlform.hour.value != "" && document.dlform.min.value != "")
			{
				return true;
			}
			else
			{
				alert(\''.$FS_PHRASES[download_edit_js_chkform].'\');
				return false;
			}
		}

		// Galerie hinzufügen Formular überprüfen
		function chkGalleryAddForm()
		{
			if (document.galleryform.name.value)
			{
				if (document.galleryform.color.value.match(/^([0-9a-f]{6})$/gi))
				{
					if (document.galleryform.thumbx.value.match(/^([0-9]+)$/gi) && 
						document.galleryform.thumby.value.match(/^([0-9]+)$/gi) && 
						document.galleryform.cols.value.match(/^([0-9]+)$/gi))
					{
						return true;
					}
					else
					{
						alert (\''.$FS_PHRASES[galery_add_js_chkform_3].'\');
						return false;
					}
				}
				else
				{
					alert (\''.$FS_PHRASES[galery_add_js_chkform_2].'\');
					return false;
				}
			}
			else
			{
				alert (\''.$FS_PHRASES[galery_add_js_chkform_1].'\');
				return false;
			}
		}

		// Galerie bearbeiten Formular überprüfen
		function chkGalleryEditForm()
		{
			if (document.galleryform.name.value)
			{
				if (document.galleryform.cols.value.match(/^([0-9]+)$/gi) &&
					document.galleryform.day.value != "" &&
					document.galleryform.month.value != "" &&
					document.galleryform.year.value != "" &&
					document.galleryform.hour.value != "" &&
					document.galleryform.min.value != "")
				{
					return true;
				}
				else
				{
					alert (\''.$FS_PHRASES[galery_edit_js_chkform].'\');
					return false;
				}
			}
			else
			{
				alert (\''.$FS_PHRASES[galery_add_js_chkform_1].'\');
				return false;
			}
		}

		// Contest hinzufügen Formular überprüfen
		function chkContestAddForm(type)
		{
			if (type == 0)
			{
				fpGenCode(\'chkContestAddForm2()\');
				return false;
			}
			else
			{
				chkContestAddForm2();
				return false;
			}
		}

		// Contest hinzufügen Formular überprüfen (fortsetzung)
		function chkContestAddForm2()
		{
			if(document.contestform.title.value && document.contestform.winners.value)
			{
				if (document.contestform.fp_code.value)
				{
					document.getElementByName("contestform").submit();
					isBBCode = false;
				}
				else
				{
					alert("'.$FS_PHRASES[contest_add_js_chkform].'");
				}
			}
			else
			{
				alert("'.$FS_PHRASES[contest_add_js_chkform].'");
			}
		}

		// News bearbeiten Formular überprüfen
		function chkNewsEditForm(type)
		{
			if (type == 0)
			{
				fpGenCode(\'chkNewsEditForm2()\');
				return false;
			}
			else
			{
				chkNewsEditForm2();
				return false;
			}
		}

		// News bearbeiten Formular überprüfen (fortsetzung)
		function chkNewsEditForm2()
		{
			if (document.newsform.del.checked == false)
			{
				if(document.newsform.title.value && document.newsform.username.value && document.newsform.day.value != "" && 
					document.newsform.month.value != "" && document.newsform.year.value != "" && document.newsform.hour.value != "" && 
					document.newsform.min.value != "")
				{
				if (document.getElementById("type1").checked && document.newsform.fp_code.value)
					{
						document.getElementByName("newsform").submit();
						isBBCode = false;
					}
					else
					{
						if(document.getElementById("type2").checked && document.newsform.html_code.value)
						{
							document.getElementByName("newsform").submit();
						}
						else
						{
							alert("'.$FS_PHRASES[news_edit_js_chkform_2].'");
						}
					}
				}
				else
				{
					alert("'.$FS_PHRASES[news_edit_js_chkform_1].'");
				}
			}
			else
			{
				document.getElementByName("newsform").submit();
			}
		}

		// News hinzufügen Formular überprüfen
		function chkNewsAddForm(type)
		{
			if (type == 0)
			{
				fpGenCode(\'chkNewsAddForm2()\');
				return false;
			}
			else
			{
				chkNewsAddForm2();
				return false;
			}
		}

		// News hinzufügen Formular überprüfen (fortsetzung)
		function chkNewsAddForm2()
		{
			if(document.newsform.title.value && document.newsform.username.value)
			{
				if (document.getElementById("type1").checked && document.newsform.fp_code.value)
				{
					document.getElementByName("newsform").submit();
					isBBCode = false;
				}
				else
				{
					if(document.getElementById("type2").checked && document.newsform.html_code.value)
					{
						document.getElementByName("newsform").submit();
					}
					else
					{
						alert("'.$FS_PHRASES[news_edit_js_chkform_2].'");
					}
				}
			}
			else
			{
				alert("'.$FS_PHRASES[news_add_js_chkform].'");
			}
		}

		// Artikel bearbeiten Formular überprüfen
		function chkArticleEditForm(type)
		{
			if (type == 0)
			{
				fpGenCode(\'chkArticleEditForm2()\');
				return false;
			}
			else
			{
				chkArticleEditForm2();
				return false;
			}
		}

		// Artikel bearbeiten Formular überprüfen (fortsetzung)
		function chkArticleEditForm2()
		{
			if (!document.articleform.del.checked)
			{
				if(document.articleform.title.value && document.articleform.username.value && 
					document.articleform.day.value != "" && document.articleform.month.value != "" && 
					document.articleform.year.value != "" && document.articleform.hour.value != "" && 
					document.articleform.min.value != "")
				{
				if (document.getElementById("type1").checked && document.articleform.fp_code.value)
					{
						document.getElementByName("articleform").submit();
						isBBCode = false;
					}
					else
					{
						if(document.getElementById("type2").checked && document.articleform.html_code.value)
						{
							document.getElementByName("articleform").submit();
						}
						else
						{
							alert("'.$FS_PHRASES[article_edit_js_chkform_2].'");
						}
					}
				}
				else
				{
					alert("'.$FS_PHRASES[article_edit_js_chkform_1].'");
				}
			}
			else
			{
				document.getElementByName("articleform").submit();
			}
		}

		// Artikel hinzufügen Formular überprüfen
		function chkArticleAddForm(type)
		{
			if (type == 0)
			{
				fpGenCode(\'chkArticleAddForm2()\');
				return false;
			}
			else
			{
				chkArticleAddForm2();
				return false;
			}
		}

		// Artikel hinzufügen Formular überprüfen (fortsetzung)
		function chkArticleAddForm2()
		{
			if(document.articleform.title.value && document.articleform.username.value)
			{
				if (document.getElementById("type1").checked && document.articleform.fp_code.value)
				{
					document.getElementByName("articleform").submit();
					isBBCode = false;
				}
				else
				{
					if(document.getElementById("type2").checked && document.articleform.html_code.value)
					{
						document.getElementByName("articleform").submit();
					}
					else
					{
						alert("'.$FS_PHRASES[article_edit_js_chkform_2].'");
					}
				}
			}
			else
			{
				alert("'.$FS_PHRASES[article_add_js_chkform].'");
			}
		}

		// Umfrage hinzufügen Formular überprüfen
		function chkPollAddForm()
		{
			if(document.pollform.question.value)
			{
				return true;
			}
			else
			{
				alert("'.$FS_PHRASES[poll_add_js_chkform].'");
				return false;
			}
		}

		// Umfrage bearbeiten Formular überprüfen
		function chkPollEditForm()
		{
			if(document.pollform.question.value
				&& document.pollform.sday.value != ""
				&& document.pollform.smonth.value != ""
				&& document.pollform.syear.value != ""
				&& document.pollform.shour.value != ""
				&& document.pollform.smin.value != ""
				&& document.pollform.eday.value != ""
				&& document.pollform.emonth.value != ""
				&& document.pollform.eyear.value != ""
				&& document.pollform.ehour.value != ""
				&& document.pollform.emin.value != "")
			{
				return true;
			}
			else
			{
				alert("'.$FS_PHRASES[poll_edit_js_chkform].'");
				return false;
			}
		}

		// Benutzer Suchen
		function findUser()
		{
			doUserRequest(document.getElementById(\'username\').value);
		}

		// Ajax User suchen
		function doUserRequest(username)
		{
			http_request = false;
		
			if (window.XMLHttpRequest) // Mozilla, Safari,...
			{
				http_request = new XMLHttpRequest();
			}
			else if (window.ActiveXObject) // IE
			{
				try
				{
					http_request = new ActiveXObject("Msxml2.XMLHTTP");
				}
				catch (e)
				{
					try
					{
						http_request = new ActiveXObject("Microsoft.XMLHTTP");
					}
					catch (e) {}
				}
			}

			if (!http_request)
			{
				alert("Ende :( Kann keine XMLHTTP-Instanz erzeugen");
				return false;
			}
			username = username.replace(/&/gi, "u|n|d");
			params="name="+username;
			http_request.open("POST", "inc/searchuser.php", true);
			http_request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			http_request.setRequestHeader("Content-length", params.length);
			http_request.setRequestHeader("Connection", "close");
			http_request.onreadystatechange = getUserNames;
			http_request.send(params);
		}

		// Benutzernamen in Template schreiben
		function getUserNames()
		{
			if (http_request.readyState == 4)
			{
				// Kein Treffer
				if (http_request.responseText == "not found")
				{
				}
				//Treffer
				else
				{
					if (http_request.responseText)
					{
						document.getElementById("userdropdown").innerHTML = http_request.responseText;
						openUser();
					}
					else
					{
						closeUserDrop();
					}
				}
			}
		}

		// News Laden
		function RequestXLNews()
		{
			http_request = false;
		
			if (window.XMLHttpRequest) // Mozilla, Safari,...
			{
				http_request = new XMLHttpRequest();
			}
			else if (window.ActiveXObject) // IE
			{
				try
				{
					http_request = new ActiveXObject("Msxml2.XMLHTTP");
				}
				catch (e)
				{
					try
					{
						http_request = new ActiveXObject("Microsoft.XMLHTTP");
					}
					catch (e) {}
				}
			}

			if (!http_request)
			{
				alert("Ende :( Kann keine XMLHTTP-Instanz erzeugen");
				return false;
			}
			var version = \''.$FSXL[config][version].'\';
			params="version="+version;
			http_request.onreadystatechange = setXLNews;
			http_request.open("POST", "inc/fsxl_news.php", true);
			http_request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			http_request.setRequestHeader("Content-length", params.length);
			http_request.setRequestHeader("Connection", "close");
			http_request.send(params);
		}

		// Benutzernamen in Template schreiben
		function setXLNews()
		{
			if (http_request.readyState == 4)
			{
				document.getElementById("fsxl_news").innerHTML = http_request.responseText;
			}
		}

		// Userdropdown schließen stoppen
		function stopUserClose()
		{
			window.clearTimeout(userCloseTimer);
		}

		// Userdropdown schließen
		function closeUserDrop()
		{
			document.getElementById(\'userdropdown\').style.display = \'none\';	
		}

		// Userdropdown schließen starten
		function closeUser()
		{
			userCloseTimer = window.setTimeout("closeUserDrop()", 500);
		}

		// Userdropdownöffnen
		function openUser()
		{
			closeUser();
			stopUserClose();
			document.getElementById(\'userdropdown\').style.display = \'block\';
		}

		// Template Variablenfeld hinzufügen
		function addTplvarCode(field)
		{
			tr = field.parentNode.parentNode;
			index = tr.rowIndex;
			html = field.parentNode.innerHTML;
			var fieldIndex = html.match(/code\[([0-9]*)\]/);

			if((fieldIndex[1] == currentLinkIndex) && (document.getElementById(\'code[\'+fieldIndex[1]+\']\').value != \'\'))
			{
				currentLinkIndex += 1;
				if (currentLinkIndex % 2 == 0) tdclass = \'alt1\';
				else tdclass = \'alt2\';

				html = findAndReplace(\'code[\' + fieldIndex[1] + \']\', \'code[\'+currentLinkIndex+\']\', html);
				//html = html.replace(/>(.*?)<\/textarea>\s/gi, \'></textarea>\');

				var newtr = tr.parentNode.insertRow(index+1);
				var newtd = newtr.insertCell(0);
				newtd.innerHTML = html;
				newtd.className = tdclass;
				newtd.style.padding = \'5px\';
				document.getElementById(\'code[\'+currentLinkIndex+\']\').value = \'\';
			}
		}

		// News Link Feld hinzufügen
		function addNewsLink(field)
		{
			tr = field.parentNode.parentNode;
			index = tr.rowIndex;
			html = field.parentNode.innerHTML;
			var fieldIndex = html.match(/linkname\[([0-9]*)\]/);

			if((fieldIndex[1] == currentLinkIndex) && (document.getElementById(\'linkname[\'+fieldIndex[1]+\']\').value != \'\') && (document.getElementById(\'linkurl[\'+fieldIndex[1]+\']\').value != \'\'))
			{
				currentLinkIndex += 1;
				if (currentLinkIndex % 2 == 0) tdclass = \'alt1\';
				else tdclass = \'alt2\';

				html = findAndReplace(\'linkname[\' + fieldIndex[1] + \']\', \'linkname[\'+currentLinkIndex+\']\', html);
				html = findAndReplace(\'linktype[\' + fieldIndex[1] + \']\', \'linktype[\'+currentLinkIndex+\']\', html);
				html = findAndReplace(\'linkurl[\' + fieldIndex[1] + \']\', \'linkurl[\'+currentLinkIndex+\']\', html);
				html = html.replace(/value="{0,1}(.*?)"{0,1}\s/gi, \'\');
				html = html.replace(/checked/gi, \'\');

				var newtr = tr.parentNode.insertRow(index+1);
				var newtd = newtr.insertCell(0);
				newtd.innerHTML = html;
				newtd.className = tdclass;
				newtd.style.padding = \'5px\';
			}
		}

		// Umfrage Antwor Feld hinzufügen
		function addPollAnswer(field)
		{
			tr = field.parentNode.parentNode;
			index = tr.rowIndex;
			html = field.parentNode.innerHTML;
			var fieldIndex = html.match(/answer\[([0-9]*)\]/);

			if((fieldIndex[1] == currentAnswerIndex) && (document.getElementById(\'answer[\'+fieldIndex[1]+\']\').value != \'\'))
			{
				currentAnswerIndex += 1;
				if (currentAnswerIndex % 2 == 0) tdclass = \'alt1\';
				else tdclass = \'alt2\';

				html = findAndReplace(\'answer[\' + fieldIndex[1] + \']\', \'answer[\'+currentAnswerIndex+\']\', html);
				html = findAndReplace(\'position[\' + fieldIndex[1] + \']\', \'position[\'+currentAnswerIndex+\']\', html);
				html = html.replace(/value="{0,1}(.*?)"{0,1}\s/gi, \'\');
				html = html.replace(/checked/gi, \'\');

				var newtr = tr.parentNode.insertRow(index+1);
				var newtd = newtr.insertCell(0);
				newtd.innerHTML = html;
				newtd.className = tdclass;
				newtd.style.padding = \'5px\';

				document.getElementById(\'position[\'+currentAnswerIndex+\']\').value = parseInt(document.getElementById(\'position[\'+fieldIndex[1]+\']\').value)+1;
			}
		}

		// Download Link hinzufügen
		function addDlLink(field)
		{
			tr = field.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode;
			index = tr.rowIndex;
			html = field.parentNode.parentNode.parentNode.parentNode.parentNode.innerHTML;
			var fieldIndex = html.match(/linkname\[([0-9]*)\]/);

			if((fieldIndex[1] == currentLinkIndex) && 
				(document.getElementById(\'linkname[\'+fieldIndex[1]+\']\').value != \'\') && 
				(document.getElementById(\'linksize[\'+fieldIndex[1]+\']\').value != \'\') && 
				(document.getElementById(\'linkurl[\'+fieldIndex[1]+\']\').value != \'\'))
			{
				currentLinkIndex += 1;
				if (currentLinkIndex % 2 == 0) tdclass = \'alt1\';
				else tdclass = \'alt2\';

				html = findAndReplace(\'linkname[\' + fieldIndex[1] + \']\', \'linkname[\'+currentLinkIndex+\']\', html);
				html = findAndReplace(\'linksize[\' + fieldIndex[1] + \']\', \'linksize[\'+currentLinkIndex+\']\', html);
				html = findAndReplace(\'linktype[\' + fieldIndex[1] + \']\', \'linktype[\'+currentLinkIndex+\']\', html);
				html = findAndReplace(\'linkurl[\' + fieldIndex[1] + \']\', \'linkurl[\'+currentLinkIndex+\']\', html);
				html = findAndReplace(\'urlpre[\' + fieldIndex[1] + \']\', \'urlpre[\'+currentLinkIndex+\']\', html);
				html = html.replace(/value="{0,1}(.*?)"{0,1}\s/gi, \'\');
				html = html.replace(/checked/gi, \'\');

				var newtr = tr.parentNode.insertRow(index+1);
				var newtd = newtr.insertCell(0);
				newtd.innerHTML = html;
				newtd.className = tdclass;
				newtd.style.padding = \'5px\';

				document.getElementById(\'urlpre[\'+currentLinkIndex+\']\').value = document.getElementById(\'urlpre[\'+fieldIndex[1]+\']\').value;
			}
		}

		// Download Prefix eintragen
		function addDlPrefix(field)
		{
			html = field.parentNode.parentNode.parentNode.parentNode.parentNode.innerHTML;
			var fieldIndex = html.match(/linkname\[([0-9]*)\]/);

			document.getElementById(\'linkurl[\'+fieldIndex[1]+\']\').value = "'.$FSXL[config][dl_prefix].'";
		}

		// Suchen und ersetzen
		function findAndReplace(oldtext, newtext, text)
		{
			var oldlength = oldtext.length;
			var newlength = newtext.length;
			var pos = text.indexOf(oldtext, 0);

			while (pos >= 0)
			{
				text = text.substring(0, pos) + newtext + text.substring(pos + oldlength);
				pos = text.indexOf(oldtext, pos + newlength);
			}
			return text;
		}

		// Image Manager Code switchen
		function switchImgManagerType()
		{
			kids = document.getElementsByTagName(\'input\');
			for (var i=0; i<kids.length; i++)
			{
				var id = kids[i].id.match(/codebox([0-9]*)/);
				var typepng = kids[i].value.match(/\.png/);
				var typegif = kids[i].value.match(/\.gif/);
				if (id)
				{
					if (document.getElementById(\'pretype1\').checked)
					{
						if (typepng)
						{
							kids[i].value = \'[IMG]\'+id[1]+\'.png[/IMG]\';
						}
						else if (typegif)
						{
							kids[i].value = \'[IMG]\'+id[1]+\'.gif[/IMG]\';
						}
						else
						{
							kids[i].value = \'[IMG]\'+id[1]+\'[/IMG]\';
						}
					}
					if (document.getElementById(\'pretype2\').checked)
					{
						if (typepng)
						{
							kids[i].value = \'<img border="0" src="images/imgmanager/\'+id[1]+\'.png" alt="">\';
						}
						else if (typegif)
						{
							kids[i].value = \'<img border="0" src="images/imgmanager/\'+id[1]+\'.gif" alt="">\';
						}
						else
						{
							kids[i].value = \'<img border="0" src="images/imgmanager/\'+id[1]+\'.jpg" alt="">\';
						}
					}
				}
			}
		}
';

	// Datenbank Verbindung schließen
	$db->close();
}

?>