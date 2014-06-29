var editorState = 'design';
var editorName = 'wysiwyg';
var closeTimer = '';
var range = '';
var rangestart = '';
var rangeend = '';
var rangetext = '';
var bookmark = '';
var isBBCode = false;

function createWysiwyg()
{
	if (invi == 'ja')
		document.write('<div id="wysiwyg_container" style="display:none;">');
	else
		document.write('<div id="wysiwyg_container">');

		document.write('<div unselectable="on" class="fp_bar" id="fp_toolbar1">');
			document.write('<div unselectable="on" class="dropdown" id="fonttype" style="width:110px;" onclick="openDropdown(\'fontdropdown\')" onmouseout="closeDropdown()">Arial</div>');
				document.write('<div unselectable="on" id="fontdropdown" onmouseover="stopClose()" onmouseout="closeDropdown()">');
					document.write('<div unselectable="on" id="font_arial" style="font-family:Arial;" class="dropdown_item" nowrap>Arial</div>');
					document.write('<div unselectable="on" id="font_arialblack" style="font-family:Arial Black;" class="dropdown_item" nowrap>Arial Black</div>');
					document.write('<div unselectable="on" id="font_comic" style="font-family:Comic Sans MS;" class="dropdown_item" nowrap>Comic Sans MS</div>');
					document.write('<div unselectable="on" id="font_courier" style="font-family:Courier New;" class="dropdown_item" nowrap>Courier New</div>');
					document.write('<div unselectable="on" id="font_impact" style="font-family:Impact;" class="dropdown_item" nowrap>Impact</div>');
					document.write('<div unselectable="on" id="font_tahoma" style="font-family:Tahoma;" class="dropdown_item" nowrap>Tahoma</div>');
					document.write('<div unselectable="on" id="font_times" style="font-family:Times New Roman;" class="dropdown_item" nowrap>Times New Roman</div>');
					document.write('<div unselectable="on" id="font_verdana" style="font-family:Verdana;" class="dropdown_item" nowrap>Verdana</div>');
				document.write('</div>');

			document.write('<div unselectable="on" class="dropdown" id="fontsize" style="width:25px;" onclick="openDropdown(\'sizedropdown\')" onmouseout="closeDropdown()">2</div>');
				document.write('<div unselectable="on" id="sizedropdown" onmouseover="stopClose()" onmouseout="closeDropdown()">');
					document.write('<div unselectable="on" id="size_1" class="dropdown_item" nowrap><font size="1">1</font></div>');
					document.write('<div unselectable="on" id="size_2" class="dropdown_item" nowrap><font size="2">2</font></div>');
					document.write('<div unselectable="on" id="size_3" class="dropdown_item" nowrap><font size="3">3</font></div>');
					document.write('<div unselectable="on" id="size_4" class="dropdown_item" nowrap><font size="4">4</font></div>');
					document.write('<div unselectable="on" id="size_5" class="dropdown_item" nowrap><font size="5">5</font></div>');
					document.write('<div unselectable="on" id="size_6" class="dropdown_item" nowrap><font size="6">6</font></div>');
					document.write('<div unselectable="on" id="size_7" class="dropdown_item" nowrap><font size="7">7</font></div>');
				document.write('</div>');

			document.write('<div unselectable="on" class="dropdown" id="fontcolor" style="width:30px;" onclick="openDropdown(\'colordropdown\')" onmouseout="closeDropdown()"><div class="colorimage"></div><div unselectable="on" id="colorpicker" class="colorhandler"></div></div>');
				document.write('<div unselectable="on" id="colordropdown" onmouseover="stopClose()" onmouseout="closeDropdown()" nowrap>');
					document.write('<div unselectable="on" id="color_000000" class="dropdown_coloritem" style="background-color:#000000;" nowrap></div>');
					document.write('<div unselectable="on" id="color_993300" class="dropdown_coloritem" style="background-color:#993300;" nowrap></div>');
					document.write('<div unselectable="on" id="color_333300" class="dropdown_coloritem" style="background-color:#333300;" nowrap></div>');
					document.write('<div unselectable="on" id="color_003300" class="dropdown_coloritem" style="background-color:#003300;" nowrap></div>');
					document.write('<div unselectable="on" id="color_003366" class="dropdown_coloritem" style="background-color:#003366;" nowrap></div>');
					document.write('<div unselectable="on" id="color_000080" class="dropdown_coloritem" style="background-color:#000080;" nowrap></div>');
					document.write('<div unselectable="on" id="color_333399" class="dropdown_coloritem" style="background-color:#333399;" nowrap></div>');
					document.write('<div unselectable="on" id="color_333333" class="dropdown_coloritem" style="background-color:#333333;" nowrap></div>');
					document.write('<div style="clear:both;"></div>');
					document.write('<div unselectable="on" id="color_800000" class="dropdown_coloritem" style="background-color:#800000;" nowrap></div>');
					document.write('<div unselectable="on" id="color_FF6600" class="dropdown_coloritem" style="background-color:#FF6600;" nowrap></div>');
					document.write('<div unselectable="on" id="color_808000" class="dropdown_coloritem" style="background-color:#808000;" nowrap></div>');
					document.write('<div unselectable="on" id="color_008000" class="dropdown_coloritem" style="background-color:#008000;" nowrap></div>');
					document.write('<div unselectable="on" id="color_008080" class="dropdown_coloritem" style="background-color:#008080;" nowrap></div>');
					document.write('<div unselectable="on" id="color_0000FF" class="dropdown_coloritem" style="background-color:#0000FF;" nowrap></div>');
					document.write('<div unselectable="on" id="color_666699" class="dropdown_coloritem" style="background-color:#666699;" nowrap></div>');
					document.write('<div unselectable="on" id="color_808080" class="dropdown_coloritem" style="background-color:#808080;" nowrap></div>');
					document.write('<div style="clear:both;"></div>');
					document.write('<div unselectable="on" id="color_FF0000" class="dropdown_coloritem" style="background-color:#FF0000;" nowrap></div>');
					document.write('<div unselectable="on" id="color_FF9900" class="dropdown_coloritem" style="background-color:#FF9900;" nowrap></div>');
					document.write('<div unselectable="on" id="color_99CC00" class="dropdown_coloritem" style="background-color:#99CC00;" nowrap></div>');
					document.write('<div unselectable="on" id="color_339966" class="dropdown_coloritem" style="background-color:#339966;" nowrap></div>');
					document.write('<div unselectable="on" id="color_33CCCC" class="dropdown_coloritem" style="background-color:#33CCCC;" nowrap></div>');
					document.write('<div unselectable="on" id="color_3366FF" class="dropdown_coloritem" style="background-color:#3366FF;" nowrap></div>');
					document.write('<div unselectable="on" id="color_800080" class="dropdown_coloritem" style="background-color:#800080;" nowrap></div>');
					document.write('<div unselectable="on" id="color_999999" class="dropdown_coloritem" style="background-color:#999999;" nowrap></div>');
					document.write('<div style="clear:both;"></div>');
					document.write('<div unselectable="on" id="color_FF00FF" class="dropdown_coloritem" style="background-color:#FF00FF;" nowrap></div>');
					document.write('<div unselectable="on" id="color_FFCC00" class="dropdown_coloritem" style="background-color:#FFCC00;" nowrap></div>');
					document.write('<div unselectable="on" id="color_FFFF00" class="dropdown_coloritem" style="background-color:#FFFF00;" nowrap></div>');
					document.write('<div unselectable="on" id="color_00FF00" class="dropdown_coloritem" style="background-color:#00FF00;" nowrap></div>');
					document.write('<div unselectable="on" id="color_00FFFF" class="dropdown_coloritem" style="background-color:#00FFFF;" nowrap></div>');
					document.write('<div unselectable="on" id="color_00CCFF" class="dropdown_coloritem" style="background-color:#00CCFF;" nowrap></div>');
					document.write('<div unselectable="on" id="color_993366" class="dropdown_coloritem" style="background-color:#993366;" nowrap></div>');
					document.write('<div unselectable="on" id="color_C0C0C0" class="dropdown_coloritem" style="background-color:#C0C0C0;" nowrap></div>');
					document.write('<div style="clear:both;"></div>');
					document.write('<div unselectable="on" id="color_FF99CC" class="dropdown_coloritem" style="background-color:#FF99CC;" nowrap></div>');
					document.write('<div unselectable="on" id="color_FFCC99" class="dropdown_coloritem" style="background-color:#FFCC99;" nowrap></div>');
					document.write('<div unselectable="on" id="color_FFFF99" class="dropdown_coloritem" style="background-color:#FFFF99;" nowrap></div>');
					document.write('<div unselectable="on" id="color_CCFFCC" class="dropdown_coloritem" style="background-color:#CCFFCC;" nowrap></div>');
					document.write('<div unselectable="on" id="color_CCFFFF" class="dropdown_coloritem" style="background-color:#CCFFFF;" nowrap></div>');
					document.write('<div unselectable="on" id="color_99CCFF" class="dropdown_coloritem" style="background-color:#99CCFF;" nowrap></div>');
					document.write('<div unselectable="on" id="color_CC99FF" class="dropdown_coloritem" style="background-color:#CC99FF;" nowrap></div>');
					document.write('<div unselectable="on" id="color_FFFFFF" class="dropdown_coloritem" style="background-color:#FFFFFF;" nowrap></div>');
					document.write('<div style="clear:both;"></div>');
					document.write('<div unselectable="on" style="border-bottom:1px solid #85CC85;"></div>');
					document.write('<div unselectable="on" id="color_none" class="dropdown_coloritem" style="background-image:url(frogpad/images/nocolor.gif);" nowrap></div>');
					document.write('<div unselectable="on" style="padding-top:2px; font-size:8pt; font-family:Arial;">Keine Farbe</div>');
				document.write('</div>');

			document.write('<div unselectable="on" class="fp_trenner"></div>');
			document.write('<div unselectable="on" class="fp_button" id="btbold"><img border="0" src="frogpad/images/bold.gif" alt="Fett"></div>');
			document.write('<div unselectable="on" class="fp_button" id="btitalic"><img border="0" src="frogpad/images/italic.gif" alt="Kursiv"></div>');
			document.write('<div unselectable="on" class="fp_button" id="btunderline"><img border="0" src="frogpad/images/underline.gif" alt="Unterstrichen"></div>');
			document.write('<div unselectable="on" class="fp_button" id="btstroke"><img border="0" src="frogpad/images/stroke.gif" alt="Durchgestrichen"></div>');
			document.write('<div unselectable="on" class="fp_trenner"></div>');
			document.write('<div unselectable="on" class="fp_button" id="btleft"><img border="0" src="frogpad/images/left.gif" alt="Linksb&uuml;ndig"></div>');
			document.write('<div unselectable="on" class="fp_button" id="btcenter"><img border="0" src="frogpad/images/center.gif" alt="Zentrieren"></div>');
			document.write('<div unselectable="on" class="fp_button" id="btright"><img border="0" src="frogpad/images/right.gif" alt="Rechtsb&uuml;ndig"></div>');
			document.write('<div unselectable="on" class="fp_button" id="btblock"><img border="0" src="frogpad/images/block.gif" alt="Blocksatz"></div>');
		document.write('</div>');

		document.write('<div unselectable="on" class="fp_bar" id="fp_toolbar2">');
			document.write('<div unselectable="on" class="fp_button" id="btlink"><img border="0" src="frogpad/images/link.gif" alt="Link hinzuf&uuml;gen"></div>');
			document.write('<div unselectable="on" class="fp_button" id="btbreaklink"><img border="0" src="frogpad/images/breaklink.gif" alt="Link entfernen"></div>');
			document.write('<div unselectable="on" class="fp_button" id="btimage"><img border="0" src="frogpad/images/image.gif" alt="Bild einf&uuml;gen"></div>');
			document.write('<div unselectable="on" class="fp_trenner"></div>');
			document.write('<div unselectable="on" class="fp_button" id="btol"><img border="0" src="frogpad/images/ol.gif" alt="Nummerische Liste"></div>');
			document.write('<div unselectable="on" class="fp_button" id="btul"><img border="0" src="frogpad/images/ul.gif" alt="Stickpunkte"></div>');
			document.write('<div unselectable="on" class="fp_trenner"></div>');
			document.write('<div unselectable="on" class="fp_button" id="btindent"><img border="0" src="frogpad/images/indent.gif" alt="Text einr&uuml;cken"></div>');
			document.write('<div unselectable="on" class="fp_button" id="btoudent"><img border="0" src="frogpad/images/oudent.gif" alt="Einr&uuml;cken entfernen"></div>');
			document.write('<div unselectable="on" class="fp_trenner"></div>');
			document.write('<div unselectable="on" class="fp_button" id="bttablepreset" onclick="openDropdown(\'tbpresetdropdown\')" onmouseout="closeDropdown()"><img border="0" src="frogpad/images/tablepreset.gif" alt="Tabellen Vorlage einf&uuml;gen"></div>');
				document.write('<div unselectable="on" id="tbpresetdropdown" onmouseover="stopClose()" onmouseout="closeDropdown()">');
					document.write('<div unselectable="on" id="tbpre1" class="dropdown_item" nowrap>Frogstyle</div>');
				document.write('</div>');
			document.write('<div unselectable="on" class="fp_button" id="bttable"><img border="0" src="frogpad/images/table.gif" alt="Tabelle einf&uuml;gen"></div>');
			document.write('<div unselectable="on" class="fp_button" id="bttbcolafter"><img border="0" src="frogpad/images/tbcolafter.gif" alt="Spalte links einf&uuml;gen"></div>');
			document.write('<div unselectable="on" class="fp_button" id="bttbcolbefore"><img border="0" src="frogpad/images/tbcolbefore.gif" alt="Spalte rechts einf&uuml;gen"></div>');
			document.write('<div unselectable="on" class="fp_button" id="bttbrowafter"><img border="0" src="frogpad/images/tbrowafter.gif" alt="Zeile unten einf&uuml;gen"></div>');
			document.write('<div unselectable="on" class="fp_button" id="bttbrowbefore"><img border="0" src="frogpad/images/tbrowbefore.gif" alt="Zeile oben einf&uuml;gen"></div>');
			document.write('<div unselectable="on" class="fp_button" id="bttbdelcol"><img border="0" src="frogpad/images/tbdelcol.gif" alt="Spalte l&ouml;schen"></div>');
			document.write('<div unselectable="on" class="fp_button" id="bttbdelrow"><img border="0" src="frogpad/images/tbdelrow.gif" alt="Zeile l&ouml;schen"></div>');
		document.write('</div>');

		document.write('<div unselectable="on" class="fp_bar" id="fp_toolbar3">');
			document.write('<div unselectable="on" class="fp_button" id="btfloatleft"><img border="0" src="frogpad/images/floatleft.gif" alt="Textbox links einf&uuml;gen"></div>');
			document.write('<div unselectable="on" class="fp_button" id="btfloatright"><img border="0" src="frogpad/images/floatright.gif" alt="Textbox rechts einf&uuml;gen"></div>');
			document.write('<div unselectable="on" class="fp_button" id="btline"><img border="0" src="frogpad/images/line.gif" alt="Trennlinie"></div>');
			document.write('<div unselectable="on" class="fp_trenner"></div>');
			document.write('<div unselectable="on" class="fp_button" id="bterase"><img border="0" src="frogpad/images/eraser.gif" alt="Formatierung entfernen"></div>');
			document.write('<div unselectable="on" class="fp_button" id="btundo"><img border="0" src="frogpad/images/undo.gif" alt="R&uuml;ckg&auml;ngig"></div>');
			document.write('<div unselectable="on" class="fp_button" id="btredo"><img border="0" src="frogpad/images/redo.gif" alt="Wiederherstellen"></div>');
			if (document.all) document.write('<div unselectable="on" class="fp_trenner"></div>');
			if (document.all) document.write('<div unselectable="on" class="fp_button" id="btcut"><img border="0" src="frogpad/images/cut.gif" alt="Ausschneiden"></div>');
			if (document.all) document.write('<div unselectable="on" class="fp_button" id="btcopy"><img border="0" src="frogpad/images/copy.gif" alt="Kopieren"></div>');
			if (document.all) document.write('<div unselectable="on" class="fp_button" id="btpaste"><img border="0" src="frogpad/images/paste.gif" alt="Einf&uuml;gen"></div>');
			if (document.all) document.write('<div unselectable="on" class="fp_trenner"></div>');
			if (document.all) document.write('<div unselectable="on" class="fp_button" id="btprint"><img border="0" src="frogpad/images/print.gif" alt="Drucken"></div>');
		document.write('</div>');

		document.write('<div unselectable="on" class="fp_bar" id="fp_toolbar_link" style="display:none;">');
			document.write('<div style="padding:2px; font-size:8pt;">');
			document.write('URL: <input style="width:200px;" class="fp_input" name="fp_linkurl" id="fp_linkurl">');
			document.write(' Ziel: <select style="margin-bottom:-1px;" class="fp_input" name="fp_linktarget" id="fp_linktarget">');
				document.write('<option value="_self">Gleiches Fenster</option>');
				document.write('<option value="_blank">Neues Fenster</option>');
			document.write('</select>');
			document.write(' <input style="width:30px;" type="button" class="fp_input" value="ok" onclick="addhyperlink()">');
			document.write('</div>');
		document.write('</div>');

		document.write('<div unselectable="on" class="fp_bar" id="fp_toolbar_image" style="display:none; height:auto;">');
			document.write('<div style="padding:2px; font-size:8pt;">');
			document.write('URL: <input style="width:200px;" class="fp_input" name="fp_imgurl" id="fp_imgurl">');
			document.write(' <input style="width:30px;" type="button" class="fp_input" value="ok" onclick="addimage()">');
			document.write(imgbar);
			document.write('</div>');
		document.write('</div>');

		document.write('<div unselectable="on" class="fp_bar" id="fp_toolbar_table" style="display:none;">');
			document.write('<div style="padding:2px; font-size:8pt;">');
			document.write('Zeilen: <input style="width:15px;" class="fp_input" name="fp_tbrows" id="fp_tbrows" value="1">');
			document.write(' Spalten: <input style="width:15px;" class="fp_input" name="fp_tbcols" id="fp_tbcols" value="1">');
			document.write(' Breite: <input style="width:25px;" class="fp_input" name="fp_tbwidth" id="fp_tbwidth" value="100">');
			document.write(' <select style="margin-bottom:-1px;" class="fp_input" name="fp_tbwidth2" id="fp_tbwidth2">');
				document.write('<option value="percent">%</option>');
				document.write('<option value="absolute">Pixel</option>');
			document.write('</select>');
			document.write(' Zellenabstand: <input style="width:15px;" class="fp_input" name="fp_tbpadding" id="fp_tbpadding" value="0">');
			document.write(' <input style="width:30px;" type="button" class="fp_input" value="ok" onclick="addtable()">');
			document.write('</div>');
		document.write('</div>');

		document.write('<iframe class="wysiwyg" name="'+editorName+'" id="'+editorName+'" frameborder="0"></iframe>');
		document.write('<textarea name="fp_code" id="fp_code"></textarea>');

		document.write('<div class="fp_footer">');
			document.write('<div style="float:right;">FrogPad Version 0.1</div>');
			document.write('<div class="fp_button2_down" id="btdesign"><img border="0" src="frogpad/images/design.gif" alt="Design"></div>');
			document.write('<div class="fp_button2" id="btcode"><img border="0" src="frogpad/images/code.gif" alt="Code"></div>');
		document.write('</div>');
	document.write('</div>');

	document.getElementById(editorName).contentWindow.document.open();
	document.getElementById(editorName).contentWindow.document.write('<html><head><link rel="stylesheet" type="text/css" href="frogpad/wysiwyg.css"></head><body>&nbsp;&nbsp;</body></html>');
	document.getElementById(editorName).contentWindow.document.close();

	document.getElementById(editorName).contentWindow.document.designMode = "On";

	resetButtons();
	setDropdownFunctions();

	document.getElementById('btcode').onmouseover = highlight; 
	document.getElementById('btcode').onmouseout= deblur; 
	document.getElementById('btcode').onmousedown= pressbutton; 
	document.getElementById('btcode').onmouseup= releasebutton; 
	document.getElementById('btcode').onclick= switchCode;

	if(window.addEventListener)
	{
		document.getElementById(editorName).contentWindow.document.addEventListener("click", getSelectedTag, true);
		document.getElementById(editorName).contentWindow.document.addEventListener("keyup", getSelectedTag, true);
		document.getElementById(editorName).contentWindow.document.addEventListener("mouseup", getSelectedTag, true);
	}
	else
	{
		document.getElementById(editorName).contentWindow.document.attachEvent("onclick", getSelectedTag);
		document.getElementById(editorName).contentWindow.document.attachEvent("onkeyup", getSelectedTag);
		document.getElementById(editorName).contentWindow.document.attachEvent("onmouseup", getSelectedTag);
	}
	window.setTimeout("setDBCode()", 300);
}
function createWysiwyg_soft()
{
	if (invi == 'ja')
		document.write('<div id="wysiwyg_container" style="display:none;">');
	else
		document.write('<div id="wysiwyg_container">');

		document.write('<textarea name="fp_code" id="fp_code" style="display:block;"></textarea>');

	document.write('</div>');

	if (dbcode)
	{
		dbcode = dbcode.replace(/\{\[n\]\}/gi, "\n");
		document.getElementById('fp_code').value = dbcode;
	}

	editorState = 'code';
}

function setDBCode()
{
	if (dbcode)
	{
		dbcode = dbcode.replace(/\{\[n\]\}/gi, "\n");
		document.getElementById('fp_code').value = dbcode;
		BBcodeToHtml();
	}
}

function setDropdownFunctions()
{
	var dropdownitems = new Array('tbpre1', 'font_arial', 'font_arialblack', 'font_verdana', 'font_times', 'font_tahoma', 'font_courier', 'font_comic', 'font_impact', 'size_1', 'size_2', 'size_3', 'size_4', 'size_5', 'size_6', 'size_7');
	for (i=0; i<dropdownitems.length; i++)
	{
		document.getElementById(dropdownitems[i]).className = 'dropdown_item'; 
		document.getElementById(dropdownitems[i]).onmouseover = highlightDropdown; 
		document.getElementById(dropdownitems[i]).onmouseout= deblurDropdown; 
		document.getElementById(dropdownitems[i]).onmousedown= formatText;
	}

	var divs = document.getElementsByTagName("DIV");
	for (i=0; i<divs.length; i++)
	{
		match = divs[i].id.match(/color_([0-9A-Fa-f]{6}|none)/g);
		if (match)
		{
			divs[i].className = 'dropdown_coloritem'; 
			divs[i].onmouseover = highlightDropdown; 
			divs[i].onmouseout= deblurDropdown; 
			divs[i].onmousedown= formatText;
		}
	}
}

function resetButtons()
{
	var menubuttons = new Array('btfloatright', 'btfloatleft', 'btoudent', 'btindent', 'btprint', 'btpaste', 'btcopy', 'btcut', 'bttable', 'btimage', 'btbreaklink', 'btlink', 'btul', 'btol', 'bterase','btline', 'btbold', 'btitalic', 'btunderline', 'btleft', 'btcenter', 'btright', 'btundo', 'btredo', 'btstroke', 'btblock');
	for (i=0; i<menubuttons.length; i++)
	{
		if(document.getElementById(menubuttons[i]))
		{
			document.getElementById(menubuttons[i]).className = 'fp_button'; 
			document.getElementById(menubuttons[i]).onmouseover = highlight; 
			document.getElementById(menubuttons[i]).onmouseout = deblur;
			document.getElementById(menubuttons[i]).onmousedown = formatText; 
			document.getElementById(menubuttons[i]).onmouseup = releasebutton; 
		}
	}

	var tablebuttons = new Array('bttbcolbefore', 'bttbcolafter', 'bttbrowbefore', 'bttbrowafter', 'bttbdelcol', 'bttbdelrow');
	for (i=0; i<tablebuttons.length; i++)
	{
		document.getElementById(tablebuttons[i]).className = 'fp_button'; 
		document.getElementById(tablebuttons[i]).onmouseover = nixda; 
		document.getElementById(tablebuttons[i]).onmouseout = deblur; 
		document.getElementById(tablebuttons[i]).onmousedown = nixda; 
		document.getElementById(tablebuttons[i]).onmouseup = nixda; 
	}

	document.getElementById('fp_toolbar_link').style.display = 'none';
	document.getElementById('fp_toolbar_image').style.display = 'none';
	document.getElementById('fp_toolbar_table').style.display = 'none';
}

function nixda()
{
	return true;
}

function fpGenCode(funct)
{
	if (editorState == 'design')
	{
		htmlToBBcode();
	}
	window.setTimeout(funct, 200);
}

function switchCode()
{
	resetButtons();
	if (editorState == 'design')
	{
		htmlToBBcode();
		document.getElementById(editorName).style.display = 'none';
		document.getElementById('fp_toolbar1').style.display = 'none';
		document.getElementById('fp_toolbar2').style.display = 'none';
		document.getElementById('fp_toolbar3').style.display = 'none';
		document.getElementById('fp_code').style.display = 'block';

		document.getElementById('btcode').className = 'fp_button2_down';
		document.getElementById('btdesign').className = 'fp_button2';

		document.getElementById('btdesign').onmouseover = highlight; 
		document.getElementById('btdesign').onmouseout= deblur; 
		document.getElementById('btdesign').onmousedown= pressbutton; 
		document.getElementById('btdesign').onmouseup= releasebutton; 
		document.getElementById('btdesign').onclick= switchCode;
		document.getElementById('btcode').onmouseover = pressbutton; 
		document.getElementById('btcode').onmouseout= pressbutton; 
		document.getElementById('btcode').onmousedown= pressbutton; 
		document.getElementById('btcode').onmouseup= pressbutton; 
		document.getElementById('btcode').onclick= nixda;

		document.getElementById('fp_code').focus();

		editorState = 'code';
	}
	else if (editorState == 'code')
	{
		BBcodeToHtml();
		resetButtons();
		document.getElementById(editorName).style.display = 'block';
		document.getElementById('fp_toolbar1').style.display = 'block';
		document.getElementById('fp_toolbar2').style.display = 'block';
		document.getElementById('fp_toolbar3').style.display = 'block';
		document.getElementById('fp_code').style.display = 'none';

		document.getElementById('btcode').className = 'fp_button2';
		document.getElementById('btdesign').className = 'fp_button2_down';

		document.getElementById('btdesign').onmouseover = pressbutton; 
		document.getElementById('btdesign').onmouseout= pressbutton; 
		document.getElementById('btdesign').onmousedown= pressbutton; 
		document.getElementById('btdesign').onmouseup= pressbutton; 
		document.getElementById('btdesign').onclick = nixda;
		document.getElementById('btcode').onmouseover = highlight; 
		document.getElementById('btcode').onmouseout= deblur; 
		document.getElementById('btcode').onmousedown= pressbutton; 
		document.getElementById('btcode').onmouseup= releasebutton; 
		document.getElementById('btcode').onclick= switchCode;

		editorState = 'design';
	}
}

function stopClose()
{
	window.clearTimeout(closeTimer);
}
function closeDrops()
{
	document.getElementById('fontdropdown').style.display = 'none';	
	document.getElementById('sizedropdown').style.display = 'none';	
	document.getElementById('colordropdown').style.display = 'none';	
	document.getElementById('tbpresetdropdown').style.display = 'none';	
}
function closeDropdown()
{
	closeTimer = window.setTimeout("closeDrops()", 500);
}
function openDropdown(which)
{
	closeDrops();
	stopClose();
	switch(which)
	{
		case 'tbpresetdropdown':
			document.getElementById('tbpresetdropdown').style.left = -230 + getOffsetLeft(document.getElementById('bttablepreset'))+3+'px';
			document.getElementById('tbpresetdropdown').style.top = getOffsetTop(document.getElementById('bttablepreset'))+24+'px';
			document.getElementById(which).style.display = 'block';
			break;
		case 'fontdropdown':
			document.getElementById('fontdropdown').style.left = -230 + getOffsetLeft(document.getElementById('fonttype'))+3+'px';
			document.getElementById('fontdropdown').style.top = getOffsetTop(document.getElementById('fonttype'))+24+'px';
			document.getElementById(which).style.display = 'block';
			break;
		case 'sizedropdown':
			document.getElementById('sizedropdown').style.left = -230 + getOffsetLeft(document.getElementById('fontsize'))+3+'px';
			document.getElementById('sizedropdown').style.top = getOffsetTop(document.getElementById('fontsize'))+24+'px';
			document.getElementById(which).style.display = 'block';
			break;
		case 'colordropdown':
			document.getElementById('colordropdown').style.left = -230 + getOffsetLeft(document.getElementById('fontcolor'))+3+'px';
			document.getElementById('colordropdown').style.top = getOffsetTop(document.getElementById('fontcolor'))+24+'px';
			document.getElementById(which).style.display = 'block';
			break;
	}
}
function getOffsetLeft(tag)
{
	var leftoffset = tag.offsetLeft;
	var parentoffset = tag.offsetParent;
	while (parentoffset)
	{
		leftoffset += parentoffset.offsetLeft;
		parentoffset= parentoffset.offsetParent;
	}
	return leftoffset;
}
function getOffsetTop(tag)
{
	var topoffset = tag.offsetTop;
	var parentoffset = tag.offsetParent;
	while (parentoffset)
	{
		topoffset += parentoffset.offsetTop;
		parentoffset= parentoffset.offsetParent;
	}
	return topoffset;
}


function highlightDropdown()
{
	if (this.className == 'dropdown_coloritem')
	{
		this.className = 'dropdown_coloritem2';
	}
	else
	{
		this.className = 'dropdown_item2';
	}
}
function deblurDropdown()
{
	if (this.className == 'dropdown_coloritem2')
	{
		this.className = 'dropdown_coloritem';
	}
	else
	{
		this.className = 'dropdown_item';
	}
}


function highlight()
{
	if (this.className == 'fp_button' || this.className == 'fp_button_down')
	{
		this.className = 'fp_button_over';
	}
	else if (this.className == 'fp_button2' || this.className == 'fp_button2_down')
	{
		this.className = 'fp_button2_over';
	}
}
function deblur()
{
	if (this.className == 'fp_button_over' || this.className == 'fp_button_down')
	{
		this.className = 'fp_button';
	}
	else if (this.className == 'fp_button2_over' || this.className == 'fp_button2_down')
	{
		this.className = 'fp_button2';
	}
}
function pressbutton()
{
	if (this.className == 'fp_button_over' || this.className == 'fp_button')
	{
		this.className = 'fp_button_down';
	}
	else if (this.className == 'fp_button2_over' || this.className == 'fp_button2')
	{
		this.className = 'fp_button2_down';
	}
}
function releasebutton()
{
	if (this.className == 'fp_button_down' || this.className == 'fp_button')
	{
		this.className = 'fp_button_over';
	}
	else if (this.className == 'fp_button2_down' || this.className == 'fp_button2')
	{
		this.className = 'fp_button2_over';
	}
}


function doCommand(command, wert)
{
	// IE
	if (document.all) frames[editorName].document.execCommand(command, false, wert);
	// Mozilla
	else document.getElementById(editorName).contentWindow.document.execCommand(command, false, wert);
}

function addhyperlink()
{
	reSelect(bookmark);
	doCommand("createlink", document.getElementById('fp_linkurl').value);
	if (window.getSelection)
	{
		var selected_obj = document.getElementById(editorName).contentWindow.window.getSelection().focusNode.parentNode;
	}
	else if (document.getSelection)
	{
		var selected_obj = document.getElementById(editorName).contentWindow.document.getSelection().focusNode.parentNode;
	}
	else if (document.selection)
	{
		var selected_obj = document.getElementById(editorName).contentWindow.document.selection.createRange().parentElement();
	}
	selected_obj.target = document.getElementById('fp_linktarget').value;

	document.getElementById('fp_toolbar_link').style.display = 'none';
}
function addimage(img)
{
	reSelect(bookmark);
	if (img)
	{
		var html = '<img border="0" src="../images/imgmanager/'+img+'" alt="">';
	}
	else
	{
		var html = '<img border="0" src="' + document.getElementById('fp_imgurl').value + '" alt="">';
	}
	insertHTML(html);
	document.getElementById('fp_toolbar_image').style.display = 'none';
}
function doFloatLeft()
{
	reSelect(bookmark);
	var html = '<div style="float:left; border:2px dashed #FF0000; margin-right:8px;">&nbsp;</div>';
	insertHTML(html);
}
function doFloatRight()
{
	reSelect(bookmark);
	var html = '<div style="float:right; border:2px dashed #FF0000; margin-left:8px;">&nbsp;</div>';
	insertHTML(html);
}

function createTablePreset(style)
{
	reSelect(bookmark);
	switch (style)
	{
		case 'tbpre1':
			var html = '<table border="0" cellspacing="0" cellpadding="0" width="100%" class="bbfs">';
			html += '<tr><td class="bbfstd_head">&nbsp;</td></tr>';
			html += '<tr><td class="bbfstd_normal">&nbsp;</td></tr>';
			html += '</table>';
			break;
	}
	insertHTML(html);
}
function addtable()
{
	reSelect(bookmark);
	var width = document.getElementById('fp_tbwidth').value;
	if (document.getElementById('fp_tbwidth2').value == 'percent') width += '%';

	var html = '<table border="1" cellspacing="0" cellpadding="'+document.getElementById('fp_tbpadding').value+'" width="'+width+'">';

	var rows = document.getElementById('fp_tbrows').value;
	var cols = document.getElementById('fp_tbcols').value;
	for (var i=0; i<rows; i++)
	{
		html += '<tr>';
		for (var j=0; j<cols; j++)
		{
			html += '<td>&nbsp;</td>';
		}
		html += '</tr>';
	}

	html += '</table>';

	insertHTML(html);
	document.getElementById('fp_toolbar_table').style.display = 'none';
}
function insertcolbefore()
{
	this.className = 'fp_button_down';
	if (window.getSelection)
	{
		var selObj = document.getElementById(editorName).contentWindow.window.getSelection().focusNode;
	}
	else if (document.getSelection)
	{
		var selObj = document.getElementById(editorName).contentWindow.document.getSelection().focusNode;
	}
	else if	(document.selection)
	{
		var selObj = document.getElementById(editorName).contentWindow.document.selection.createRange().parentElement();
	}
	current = selObj;
	while (current.tagName != "TABLE")
	{
		if (current.tagName == "TD")
		{
			index = current.cellIndex;
		}
		if (current.parentNode.tagName == "TBODY")
		{
			rows = current.parentNode.rows.length;
			var x=current.parentNode;
			for (i=0; i<rows; i++)
			{
				var j=x.rows[i].insertCell(index);
				j.innerHTML = '&nbsp;';
				if (x.rows[i].cells[index+1].className) j.className = x.rows[i].cells[index+1].className;
			}
		}
		current = current.parentNode;
	}
}
function insertcolafter()
{
	this.className = 'fp_button_down';
	if (window.getSelection)
	{
		var selObj = document.getElementById(editorName).contentWindow.window.getSelection().focusNode;
	}
	else if (document.getSelection)
	{
		var selObj = document.getElementById(editorName).contentWindow.document.getSelection().focusNode;
	}
	else if	(document.selection)
	{
		var selObj = document.getElementById(editorName).contentWindow.document.selection.createRange().parentElement();
	}
	current = selObj;
	while (current.tagName != "TABLE")
	{
		if (current.tagName == "TD")
		{
			index = current.cellIndex;
		}
		if (current.parentNode.tagName == "TBODY")
		{
			rows = current.parentNode.rows.length;
			var x=current.parentNode;
			for (i=0; i<rows; i++)
			{
				var j=x.rows[i].insertCell(index+1);
				j.innerHTML = '&nbsp;';
				if (x.rows[i].cells[index].className) j.className = x.rows[i].cells[index].className;
			}
		}
		current = current.parentNode;
	}
}
function insertrowbefore()
{
	this.className = 'fp_button_down';
	if (window.getSelection)
	{
		var selObj = document.getElementById(editorName).contentWindow.window.getSelection().focusNode;
	}
	else if (document.getSelection)
	{
		var selObj = document.getElementById(editorName).contentWindow.document.getSelection().focusNode;
	}
	else if	(document.selection)
	{
		var selObj = document.getElementById(editorName).contentWindow.document.selection.createRange().parentElement();
	}
	current = selObj;
	while (current.tagName != "TABLE")
	{
		if (current.tagName== "TD")
		{
			if (current.className) klasse = current.className;
			else klasse = '';
		}
		if (current.tagName== "TR")
		{
			cells = current.cells.length;
			index = current.rowIndex;
		}
		if (current.parentNode.tagName == "TBODY")
		{
			var x=current.parentNode.insertRow(index);
			for (i=0; i<cells; i++)
			{
				var j=x.insertCell(i);
				j.innerHTML = '&nbsp;';
				if (klasse) j.className = klasse;
			}
		}
		current = current.parentNode;
	}
}
function insertrowafter()
{	
	this.className = 'fp_button_down';
	if (window.getSelection)
	{
		var selObj = document.getElementById(editorName).contentWindow.window.getSelection().focusNode;
	}
	else if (document.getSelection)
	{
		var selObj = document.getElementById(editorName).contentWindow.document.getSelection().focusNode;
	}
	else if	(document.selection)
	{
		var selObj = document.getElementById(editorName).contentWindow.document.selection.createRange().parentElement();
	}
	current = selObj;
	while (current.tagName != "TABLE")
	{
		if (current.tagName== "TD")
		{
			if (current.className) klasse = current.className;
			else klasse = '';
		}
		if (current.tagName== "TR")
		{
			cells = current.cells.length;
			index = current.rowIndex;
		}
		if (current.parentNode.tagName == "TBODY")
		{
			var x=current.parentNode.insertRow(index+1);
			for (i=0; i<cells; i++)
			{
				var j=x.insertCell(i);
				j.innerHTML = '&nbsp;';
				if (klasse) j.className = klasse;
			}
		}
		current = current.parentNode;
	}
}
function delcol()
{
	this.className = 'fp_button_down';
	if (window.getSelection)
	{
		var selObj = document.getElementById(editorName).contentWindow.window.getSelection().focusNode;
	}
	else if (document.getSelection)
	{
		var selObj = document.getElementById(editorName).contentWindow.document.getSelection().focusNode;
	}
	else if	(document.selection)
	{
		var selObj = document.getElementById(editorName).contentWindow.document.selection.createRange().parentElement();
	}
	current = selObj;
	while (current.tagName != "TABLE")
	{
		if (current.tagName == "TD")
		{
			index = current.cellIndex;
		}
		if (current.parentNode.tagName == "TBODY")
		{
			rows = current.parentNode.rows.length;
			var x=current.parentNode;
			for (i=0; i<rows; i++)
			{
				j=x.rows[i].deleteCell(index);
			}
		}
		current = current.parentNode;
	}
}
function delrow()
{
	this.className = 'fp_button_down';
	if (window.getSelection)
	{
		var selObj = document.getElementById(editorName).contentWindow.window.getSelection().focusNode;
	}
	else if (document.getSelection)
	{
		var selObj = document.getElementById(editorName).contentWindow.document.getSelection().focusNode;
	}
	else if	(document.selection)
	{
		var selObj = document.getElementById(editorName).contentWindow.document.selection.createRange().parentElement();
	}
	current = selObj;
	while (current.tagName != "TABLE")
	{
		if (current.tagName== "TR")
		{
			index = current.rowIndex;
		}
		if (current.parentNode.tagName == "TBODY")
		{
			rows = current.parentNode.rows.length;
			var x=current.parentNode;
			x.deleteRow(index);
		}
		current = current.parentNode;
	}
}

function insertHTML(htmlcode)
{
	if (document.all)
	{
		bookmark.pasteHTML(htmlcode);
		bookmark.collapse(false);
		bookmark.select();
	}
	else
	{
		doCommand('insertHTML', htmlcode);
	}
}


function formatText()
{
	if (this.id == 'btol' || this.id == 'btul')
	{
		document.getElementById('btol').className = 'fp_button';
		document.getElementById('btol').onmouseout = deblur;
		document.getElementById('btol').onmouseover = highlight;
		document.getElementById('btul').className = 'fp_button';
		document.getElementById('btul').onmouseout = deblur;
		document.getElementById('btul').onmouseover = highlight;
	}
	if (this.id == 'btblock' || this.id == 'btcenter' || this.id == 'btleft' || this.id == 'btright')
	{
		document.getElementById('btblock').className = 'fp_button';
		document.getElementById('btblock').onmouseout = deblur;
		document.getElementById('btblock').onmouseover = highlight;
		document.getElementById('btcenter').className = 'fp_button';
		document.getElementById('btcenter').onmouseout = deblur;
		document.getElementById('btcenter').onmouseover = highlight;
		document.getElementById('btleft').className = 'fp_button';
		document.getElementById('btleft').onmouseout = deblur;
		document.getElementById('btleft').onmouseover = highlight;
		document.getElementById('btright').className = 'fp_button';
		document.getElementById('btright').onmouseout = deblur;
		document.getElementById('btright').onmouseover = highlight;
	}
	if (this.id == 'btindent' || this.id == 'btol' || this.id == 'btul' || this.id == 'btbold' || this.id == 'btitalic' || this.id == 'btunderline' || this.id == 'btstroke' || this.id == 'btblock' || this.id == 'btcenter' || this.id == 'btleft' || this.id == 'btright')
	{
		if (this.className == 'fp_button_down' && this.id != 'btindent')
		{
			this.className = 'fp_button_over';
			this.onmouseout = deblur;
			this.onmouseover = highlight;
		}
		else
		{
			this.className = 'fp_button_down';
			this.onmouseout = nixda;
			this.onmouseup = nixda;
			this.onmouseover = nixda;
		}
	}
	else
	{
		match = this.id.match(/color_([0-9A-Fa-f]{6}|none)/g);
		if (!match)
		{
			this.className = 'fp_button_down';
		}
	}
	switch (this.id)
	{
		case "btprint":
			if (document.all)
			{
				window.frames[editorName].focus();
				window.frames[editorName].print();
			}
			else
			{
				document.getELementById(editorName).contentWindow.focus();
				document.getELementById(editorName).contentWindow.window.print();
			}
			break;
		case "btfloatleft":
			doFloatLeft();
			break;
		case "btfloatright":
			doFloatRight();
			break;
		case "btindent":
			doCommand("indent");
			break;
		case "btoudent":
			doCommand("outdent");
			break;
		case "btcut":
			doCommand("cut");
			break;
		case "btcopy":
			doCommand("copy");
			break;
		case "btpaste":
			doCommand("paste");
			break;
		case "btbold":
			doCommand("bold");
			break;
		case "btitalic":
			doCommand("italic");
			break;
		case "btunderline":
			doCommand("underline");
			break;
		case "btcenter":
			doCommand("justifycenter");
			break;
		case "btleft":
			doCommand("justifyleft");
			break;
		case "btright":
			doCommand("justifyright");
			break;
		case "btundo":
			doCommand("undo");
			break;
		case "btredo":
			doCommand("redo");
			break;
		case "btline":
			doCommand("inserthorizontalrule");
			break;
		case "bterase":
			doCommand("removeformat");
			break;
		case "btol":
			doCommand("insertorderedlist");
			break;
		case "btul":
			doCommand("insertunorderedlist");
			break;
		case "btbreaklink":
			doCommand("unlink");
			document.getElementById('fp_toolbar_link').style.display = 'none';
			break;
		case "btlink":
			document.getElementById('fp_linkurl').value = '';
			document.getElementById('fp_toolbar_link').style.display = 'block';
			break;
		case "btimage":
			document.getElementById('fp_imgurl').value = '';
			document.getElementById('fp_toolbar_image').style.display = 'block';
			break;
		case "bttable":
			document.getElementById('fp_tbrows').value = '1';
			document.getElementById('fp_tbcols').value = '1';
			document.getElementById('fp_tbwidth').value = '100';
			document.getElementById('fp_tbpadding').value = '0';
			document.getElementById('fp_toolbar_table').style.display = 'block';
			break;
		case "font_arial":
			doCommand("fontname", "Arial");
			document.getElementById('fonttype').innerHTML = 'Arial';
			break;
		case "font_arialblack":
			doCommand("fontname", "Arial Black");
			document.getElementById('fonttype').innerHTML = 'Arial Black';
			break;
		case "font_times":
			doCommand("fontname", "Times New Roman");
			document.getElementById('fonttype').innerHTML = 'Times New Roman';
			break;
		case "font_verdana":
			doCommand("fontname", "Verdana");
			document.getElementById('fonttype').innerHTML = 'Verdana';
			break;
		case "font_tahoma":
			doCommand("fontname", "Tahoma");
			document.getElementById('fonttype').innerHTML = 'Tahoma';
			break;
		case "font_comic":
			doCommand("fontname", "Comic Sans MS");
			document.getElementById('fonttype').innerHTML = 'Comic Sans MS';
			break;
		case "font_courier":
			doCommand("fontname", "Courier New");
			document.getElementById('fonttype').innerHTML = 'Courier New';
			break;
		case "font_impact":
			doCommand("fontname", "Impact");
			document.getElementById('fonttype').innerHTML = 'Impact';
			break;
		case "size_1":
			doCommand("fontsize", "1");
			document.getElementById('fontsize').innerHTML = '1';
			break;
		case "size_2":
			doCommand("fontsize", "2");
			document.getElementById('fontsize').innerHTML = '2';
			break;
		case "size_3":
			doCommand("fontsize", "3");
			document.getElementById('fontsize').innerHTML = '3';
			break;
		case "size_4":
			doCommand("fontsize", "4");
			document.getElementById('fontsize').innerHTML = '4';
			break;
		case "size_5":
			doCommand("fontsize", "5");
			document.getElementById('fontsize').innerHTML = '5';
			break;
		case "size_6":
			doCommand("fontsize", "6");
			document.getElementById('fontsize').innerHTML = '6';
			break;
		case "size_7":
			doCommand("fontsize", "7");
			document.getElementById('fontsize').innerHTML = '7';
			break;
		case "color_none":
			doCommand("forecolor", "transparent");
			document.getElementById('colorpicker').style.backgroundColor = "#000000";
			break;
		case "btstroke":
			doCommand("strikethrough");
			break;
		case "btblock":
			doCommand("justifyfull");
			break;
		default:
			match = this.id.match(/color_([0-9A-Fa-f]{6})/g);
			if (match)
			{
				colorcode = this.id.replace(/color_([0-9A-Fa-f]{6})/g, "$1");
				doCommand("forecolor", "#"+colorcode);
				document.getElementById('colorpicker').style.backgroundColor = "#"+colorcode;
			}
			match = this.id.match(/tbpre[0-9]*/g);
			if (match)
			{
				createTablePreset(this.id);
			}
	}
	closeDrops();
	if (this.id == 'btoudent')
	{
		getSelectedTag();
	}
	document.getElementById(editorName).contentWindow.document.body.focus();
}

function doRequest(direction, code)
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
	code = code.replace(/&/gi, "u|n|d");
	params="direction="+direction+"&code="+code;
	http_request.open("POST", "frogpad/bbcodeparser.php", true);
	http_request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	http_request.setRequestHeader("Content-length", params.length);
	http_request.setRequestHeader("Connection", "close");
	if (direction == "HTMLToBB")
	{
		http_request.onreadystatechange = setBBCode;
	}
	else if (direction == "BBToHTML")
	{
		http_request.onreadystatechange = setHTMLCode;
	}
	http_request.send(params);
}
function setBBCode()
{
	if (http_request.readyState == 4)
	{
		document.getElementById("fp_code").value = http_request.responseText;
		isBBCode = true;
	}
}

function setHTMLCode()
{
	if (http_request.readyState == 4)
	{
		document.getElementById(editorName).contentWindow.document.body.innerHTML = http_request.responseText;
		isBBCode = false;
	}
}

function in_array(item,arr)
{
	for(p=0; p<arr.length; p++)
	{
		if (item == arr[p])
		{
			return true;
			break;
		}
	}
	return false;
}
function array_pop(array)
{
	if(!array.length)
	{
		return null;
	}
	return array.pop();
}
function array_push (array)
{
	var i, argv = arguments, argc = argv.length;

	for (i=1; i < argc; i++)
	{
		array[array.length++] = argv[i];
	}

	return array.length;
}
function sortByOffset(a,b)
{ 
	if (a['offset'] == b['offset'])
	{ 
		return 0; 
	} 
	return (a['offset'] > b['offset']) ? -1 : 1; 
}  
function dezToHex(wert) {
	var hex = "0123456789ABCDEF";
	var out = '';
	var inp = parseInt(wert);
	while(inp != 0)
	{
		out = hex.charAt(inp%16) + out;
		inp = inp >> 4;
	}
	return out;
}
function str_replace(search, replace, subject)
{
	return subject.split(search).join(replace);
}

function htmlToBBcode()
{
	var code = document.getElementById(editorName).contentWindow.document.body.innerHTML;

	code = code.replace(/u\|n\|d/ig, '&');
	code = code.replace(/&nbsp;/ig, ' ');
	code = code.replace(/(\n\r|\r\n|\r)/ig, "\n");

	// Farbe zu Hex
	var color = code.match(/rgb\(([0-9]{1,3}),\s*([0-9]{1,3}),\s*([0-9]{1,3})\)/ig);
	for (i in color)
	{
		if (i >= 0)
		{
			werte = color[i].match(/rgb\(([0-9]{1,3}),\s*([0-9]{1,3}),\s*([0-9]{1,3})\)/i);

			var R = dezToHex(werte[1]);
			var G = dezToHex(werte[2]);
			var B = dezToHex(werte[3]);
			var hexcolor = '#'+R+G+B;
			
			code = code.replace(/rgb\(([0-9]{1,3}),\s*([0-9]{1,3}),\s*([0-9]{1,3})\)/i, hexcolor);
		}
	}


	var tags = new Array('FONT', 'SPAN', 'DIV', 'BR', 'P', 'span', 'div', 'br', 'p', 'font');
	var openers = new Array();
	var additionals = new Array();
	var pairs = new Array();
	var offset = 0;

	Treffer = code.match(/\<([\/a-zA-Z]*)\s*([\s\S]*?)\>/ig);
	for (i in Treffer)
	{
		if (i >= 0)
		{
			offset = code.indexOf(Treffer[i], offset);
			tagname = Treffer[i].substr(1, Treffer[i].search(/\s|>/)-1);
			tag = new Array(tagname, offset);
			if (in_array(tag[0], tags))
			{
				openers[openers.length] = tag;
				additionals[additionals.length] = Treffer[i].substr(tag.length+1, Treffer[i].length-tag.length-2);
			}
			else if (tag[0].substr(0, 1) == '/' && in_array(tag[0].substr(1, tag[0].length-1), tags))
			{
				var last = array_pop(openers);
				if (last[0] == tag[0].substr(1, tag[0].length-1))
				{
					var add = array_pop(additionals);
					var open = '';
					var close = '';

					if (add.match(/font-weight:\s*bold;{0,1}/i))
					{
						open = '[b]'+open;
						close += '[/b]';
					}
					if (add.match(/font-style:\s*italic;{0,1}/i))
					{
						open = '[i]'+open;
						close += '[/i]';
					}
					if (add.match(/underline/i))
					{
						open = '[u]'+open;
						close += '[/u]';
					}
					if (add.match(/line-through/i))
					{
						open = '[s]'+open;
						close += '[/s]';
					}

					Tr = add.match(/font-family:\s*\'{0,1}([a-zA-Z0-9\s\-_]*)\'{0,1};{0,1}/i);
					if (Tr)
					{
						open = '[font='+Tr[1]+']'+open;
						close += '[/font]';
					}
					Tr = add.match(/color:\s*(#[0-9a-f]{6});{0,1}/i);
					if (Tr)
					{
						open = '[color='+Tr[1]+']'+open;
						close += '[/color]';
					}
					if (add.match(/text-align:\s*center;{0,1}/i))
					{
						open = '[center]'+open;
						close += '[/center]';
					}
					if (add.match(/text-align:\s*right;{0,1}/i))
					{
						open = '[right]'+open;
						close += '[/right]';
					}
					if (add.match(/text-align:\s*justify;{0,1}/i))
					{
						open = '[block]'+open;
						close += '[/block]';
					}
					if (add.match(/float:\s*left;{0,1}/i))
					{
						open = '[floatleft]'+open;
						close += '[/floatleft]';
					}
					if (add.match(/float:\s*right;{0,1}/i))
					{
						open = '[floatright]'+open;
						close += '[/floatright]';
					}

					Tr = add.match(/size=\"{0,1}([0-9])\"{0,1}/i);
					if (Tr)
					{
						open = '[size='+Tr[1]+']'+open;
						close += '[/size]';
					}
					Tr = add.match(/color=\"{0,1}#{0,1}([0-9a-f]{6})\"{0,1}/i);
					if (Tr)
					{
						open = '[color='+Tr[1]+']'+open;
						close += '[/color]';
					}
					else
					{
						Tr = add.match(/color=([a-z]*)/i);
						if (Tr)
						{
							open = '[color='+Tr[1]+']'+open;
							close += '[/color]';
						}
					}
					Tr = add.match(/face=\"{0,1}([a-zA-Z\s]*)\"{0,1}/i);
					if (Tr)
					{
						open = '[font='+Tr[1]+']'+open;
						close += '[/font]';
					}

					var posi = pairs.length;
					pairs[posi] = new Object();
					pairs[posi]['opentag'] = last[0];
					pairs[posi]['offset'] = last[1];
					pairs[posi]['additional'] = add;
					pairs[posi]['bbtag'] = open;
					var posi = pairs.length;
					pairs[posi] = new Object();
					pairs[posi]['closetag'] = tag[0];
					pairs[posi]['offset'] = tag[1];
					pairs[posi]['bbtag'] = close;
				}
				else array_push(openers, last);
			}
		}
	}
	if (pairs.length > 0)
	{
		var tmpcode = '';
		pairs.sort(sortByOffset);
		for (i=0; i<pairs.length; i++)
		{ 
			if (pairs[i]['opentag'])
			{
				tmpcode = code.slice(0, pairs[i]['offset']);
				tmpcode += pairs[i]['bbtag'];
				tmpcode += code.slice(pairs[i]['offset'], code.length);
				code = tmpcode;
			} 
			else
			{ 
				tmpcode = code.slice(0, pairs[i]['offset']);
				tmpcode += pairs[i]['bbtag'];
				tmpcode += code.slice(pairs[i]['offset'], code.length);
				code = tmpcode;
			} 
		} 
	}

	code = code.replace(/\n\[floatleft\]/ig, '[floatleft]');
	code = code.replace(/\n\[floatright\]/ig, '[floatright]');

	// HP Pfad
	var url = document.URL;
	var hppath = url.substr(0, url.lastIndexOf('admin/'))+'admin/';
	code = str_replace(hppath, '', code);

	// Texteinrückung
	while (code.match(/style=\"([\s\S]*)margin-left:\s*([0-9]{2,})px;{0,1}\s{0,1}([\s\S]*?)\">([\s\S]*?)<\//ig))
	{
		code = code.replace(/style=\"([\s\S]*)margin-left:\s*40px;{0,1}\s{0,1}([\s\S]*?)\">([\s\S]*?)<\//ig, "style=\"$1$2\">[dir]$3[/dir]</");
		code = code.replace(/style=\"([\s\S]*)margin-left:\s*80px;{0,1}\s{0,1}([\s\S]*?)\">([\s\S]*?)<\//ig, "style=\"$1$2\">[dir][dir]$3[/dir][/dir]</");
		code = code.replace(/style=\"([\s\S]*)margin-left:\s*120px;{0,1}\s{0,1}([\s\S]*?)\">([\s\S]*?)<\//ig, "style=\"$1$2\">[dir][dir][dir]$3[/dir][/dir][/dir]</");
	}

	// Text Design
	code = code.replace(/<strong>([\s\S]*?)<\/strong>/ig, "[b]$1[/b]");
	code = code.replace(/<b>([\s\S]*?)<\/b>/ig, "[b]$1[/b]");
	code = code.replace(/<u>([\s\S]*?)<\/u>/ig, "[u]$1[/u]");
	code = code.replace(/<em>([\s\S]*?)<\/em>/ig, "[i]$1[/i]");
	code = code.replace(/<strike>([\s\S]*?)<\/strike>/ig, "[s]$1[/s]");

	// Texteinrückung
	while (code.match(/<BLOCKQUOTE([\s\S]*?)>\n*([\s\S]*?)\n*<\/BLOCKQUOTE>/ig))
	{
		code = code.replace(/<BLOCKQUOTE([\s\S]*?)>\n*([\s\S]*?)\n*<\/BLOCKQUOTE>/ig, "[dir]$2[/dir]");
	}

	// Textausrichtung
	code = code.replace(/\salign=left/ig, "");
	while (code.match(/<p align=\"{0,1}center\"{0,1}>([\s\S]*?)<\/p>/ig))
	{
		code = code.replace(/<p align=\"{0,1}center\"{0,1}>([\s\S]*?)<\/p>/ig, "[center]$1[/center]");
	}
	while (code.match(/<div align=\"{0,1}center\"{0,1}>([\s\S]*?)<\/div>/ig))
	{
		code = code.replace(/<div align=\"{0,1}center\"{0,1}>([\s\S]*?)<\/div>/ig, "[center]$1[/center]");
	}
	code = code.replace(/<p align=\"{0,1}right\"{0,1}>([\s\S]*?)<\/p>/ig, "[right]$1[/right]");
	code = code.replace(/<p align=\"{0,1}justify\"{0,1}>([\s\S]*?)<\/p>/ig, "[block]$1[/block]");
	code = code.replace(/<div align=\"{0,1}right\"{0,1}>([\s\S]*?)<\/div>/ig, "[right]$1[/right]");
	code = code.replace(/<div align=\"{0,1}justify\"{0,1}>([\s\S]*?)<\/div>/ig, "[block]$1[/block]");

	// Links
	Treffer = code.match(/<A([\s\S]*?)>([\s\S]*?)<\/A>/ig);
	for (i in Treffer)
	{
		if (i >= 0)
		{
			match = Treffer[i].match(/<A([\s\S]*?)>([\s\S]*?)<\/A>/i);
			if (match[1].match(/target=\"{0,1}_blank\"{0,1}/i))
			{
				var target = ' blank';
			}
			else
			{
				var target = '';
			}
			Tr = match[1].match(/href=\"www.([\s\S]*?)\"/i);
			if (Tr)
			{
				var url = 'http://www.'+Tr[1];
			}
			else
			{
				Tr = match[1].match(/href=\"([\s\S]*?)\"/i);
				if (Tr)
				{
					var url = Tr[1];
				}
			}
			var urlreplace = '[url='+url+target+']'+match[2]+'[/url]';
			code = str_replace(match[0], urlreplace, code);
		}
	}
	code = code.replace(/\[url=admin\//ig, "[url=");

	// HR
	code = code.replace(/\n{0,1}<HR([\s\S]*?)>(<\/hr>){0,1}\n{0,1}/ig, "\n---\n");

	// Images
	Treffer = code.match(/<img([\s\S]*?)>/ig);
	for (i in Treffer)
	{
		if (i >= 0)
		{
			match = Treffer[i].match(/<img([\s\S]*?)>/i);
			Tr = match[1].match(/imgmanager\/([0-9]*)\.jpg/i);
			if (Tr)
			{
				var src = Tr[1];
			}
			else
			{
				Tr = match[1].match(/imgmanager\/([0-9]*)\.(png|gif)/i);
				if (Tr)
				{
					var src = Tr[1]+'.'+Tr[2];
				}
				else
				{
					Tr = match[1].match(/src=\"([\s\S]*?)\"/i);
					if (Tr)
					{
						var src = Tr[1];
					}
					else
					{
						var src = '';
					}
				}
			}

			Tr = match[1].match(/width:\s*([0-9]*)px;{0,1}/i);
			if (Tr)
			{
				var width = Tr[1];
			}
			if (!width)
			{
				Tr = match[1].match(/width=\"{0,1}([0-9]*)\"{0,1}/i);
				if (Tr)
				{
					var width = Tr[1];
				}
			}

			Tr = match[1].match(/height:\s*([0-9]*)px;{0,1}/i);
			if (Tr)
			{
				var height = Tr[1];
			}
			if (!height)
			{
				Tr = match[1].match(/height=\"{0,1}([0-9]*)\"{0,1}/i);
				if (Tr)
				{
					var height = Tr[1];
				}
			}

			if (width)
			{
				var size = ' '+width+'|'+height;
			}
			else
			{
				var size = '';
			}

			var imgreplace = '[img'+size+']'+src+'[/img]';
			code = str_replace(match[0], imgreplace, code);
		}
	}

	// Listen
	code = code.replace(/\n{0,1}<OL>([\s\S]*?)\n*<\/OL>/ig, "[list numbers]$1\n[/list]");
	code = code.replace(/\n{0,1}<UL>([\s\S]*?)\n*<\/UL>/ig, "[list]$1\n[/list]");
	code = code.replace(/\n*<LI>([\s\S]*?)<\/LI>/ig, "\n[*]$1");
	code = code.replace(/\n*<LI>/ig, "\n[*]");

	// Tabellen
	Treffer = code.match(/<table([\s\S]*?)>\n*([\s\S]*?)<\/table>/ig);
	for (i in Treffer)
	{
		if (i >= 0)
		{
			match = Treffer[i].match(/<table([\s\S]*?)>\n*([\s\S]*?)<\/table>/i);

			// Class
			Tr = match[1].match(/class=\"{0,1}([a-zA-Z-0-9_\-]*)\"{0,1}/i);
			if (Tr)
			{
				var tclass = ' class='+Tr[1];
			}
			else
			{
				var tclass = '';
			}

			// Padding
			Tr = match[1].match(/cellpadding=\"{0,1}([0-9]){1,}\"{0,1}/i);
			if (Tr)
			{
				var padding = ' padding='+Tr[1];
			}
			else
			{
				var padding = '';
			}

			// Height
			Tr = match[1].match(/height:\s*([0-9]*)px/i);
			if (Tr)
			{
				var height = Tr[1];
			}
			else
			{
				var height = '';
			}

			// Width
			Tr = match[1].match(/width:\s*([0-9]*)px/i);
			if (Tr)
			{
				var width = Tr[1];
			}
			else
			{
				Tr = match[1].match(/width=\"{0,1}([0-9]*)(%){0,1}\"{0,1}/i);
				if (Tr)
				{
					var width = Tr[1]+Tr[2];
				}
				else
				{
					var width = '';
				}
			}

			if (height != '')
			{
				var size = ' size='+width+'|'+height;
			}
			else if (width != '100%' && width != '')
			{
				var size = ' size='+width;
			}
			else
			{
				var size = '';
			}

			var tblreplace = '[table'+size+padding+tclass+']'+match[2]+'[/table]';
			code = str_replace(match[0], tblreplace, code);
		}
	}
	code = code.replace(/<TBODY>/ig, "");
	code = code.replace(/<\/TBODY>/ig, "");
	code = code.replace(/<TR>\n*([\s\S]*?)<\/TR>\n*/ig, "[tr]\n$1[/tr]\n");
	code = code.replace(/<TD>([\s\S]*?)<\/TD>\n*/ig, "[td]$1[/td]\n");
	code = code.replace(/<TD class=\"{0,1}([\s\S]*?)\"{0,1}>(.*?)<\/TD>\n*/ig, "[td $1]$2[/td]\n");

	// Safari
	code = code.replace(/\s*class=\"Apple-style-span\"/ig, "");
	code = code.replace(/style=\"([\s\S]*)font-size:\s*x-small;{0,1}\s{0,1}([\s\S]*?)\">([\s\S]*?)<\//ig, "style=\"$1$2\">[size=1]$3[/size]</");
	code = code.replace(/style=\"([\s\S]*)font-size:\s*small;{0,1}\s{0,1}([\s\S]*?)\">([\s\S]*?)<\//ig, "style=\"$1$2\">$3</");
	code = code.replace(/style=\"([\s\S]*)font-size:\s*medium;{0,1}\s{0,1}([\s\S]*?)\">([\s\S]*?)<\//ig, "style=\"$1$2\">[size=3]$3[/size]</");
	code = code.replace(/style=\"([\s\S]*)font-size:\s*large;{0,1}\s{0,1}([\s\S]*?)\">([\s\S]*?)<\//ig, "style=\"$1$2\">[size=4]$3[/size]</");
	code = code.replace(/style=\"([\s\S]*)font-size:\s*x-large;{0,1}\s{0,1}([\s\S]*?)\">([\s\S]*?)<\//ig, "style=\"$1$2\">[size=5]$3[/size]</");
	code = code.replace(/style=\"([\s\S]*)font-size:\s*xx-large;{0,1}\s{0,1}([\s\S]*?)\">([\s\S]*?)<\//ig, "style=\"$1$2\">[size=6]$3[/size]</");
	code = code.replace(/style=\"([\s\S]*)font-size:\s*-webkit-xxx-large;{0,1}\s{0,1}([\s\S]*?)\">([\s\S]*?)<\//ig, "style=\"$1$2\">[size=7]$3[/size]</");

	// Zeilenumbrüche
	code = code.replace(/<br>/ig, "\n");
	code = code.replace(/<br \/>/ig, "\n");

	// Rückstände
	code = code.replace(/<font([\s\S]*?)>/ig, "");
	code = code.replace(/<\/font>/ig, "");
	code = code.replace(/\s*style=\"([\s\S]*?)\"\s*/ig, "");
	code = code.replace(/<span>/ig, "");
	code = code.replace(/<\/span>/ig, "");
	code = code.replace(/<div>/ig, "");
	code = code.replace(/<\/div>/ig, "");
	code = code.replace(/<p>([\s\S]*?)<\/p>/ig, "$1");

	code = code.replace(/\n\[center\]/ig, "[center]");
	code = code.replace(/\n\[right\]/ig, "[right]");
	code = code.replace(/\n\[block\]/ig, "[block]");

	code = code.replace(/^\s*/ig, "");
	code = code.replace(/\s*$/ig, "");

	document.getElementById("fp_code").value = code;
}
function BBcodeToHtml()
{
	var code = document.getElementById("fp_code").value;

	code = code.replace(/u\|n\|d/ig, '&');

	// Zeilenumbrüche
	code = code.replace(/(\n\r|\r\n|\r)/ig, "\n");

	// Text Design
	code = code.replace(/\[b\]([\s\S]*?)\[\/b\]/ig, "<strong>$1</strong>");
	code = code.replace(/\[u\]([\s\S]*?)\[\/u\]/ig, "<u>$1</u>");
	code = code.replace(/\[i\]([\s\S]*?)\[\/i\]/ig, "<em>$1</em>");
	code = code.replace(/\[s\]([\s\S]*?)\[\/s\]/ig, "<strike>$1</strike>");

	// Texteinrückung
	while (code.match(/\[dir\]([\s\S]*?)\n*\[\/dir\]/ig))
	{
		code = code.replace(/\[dir\]([\s\S]*?)\n*\[\/dir\]/ig, "<BLOCKQUOTE>$1</BLOCKQUOTE>");
	}

	// Textausrichtung
	while (code.match(/\[center\]([\s\S]*?)\[\/center\]/ig))
	{
		code = code.replace(/\[center\]([\s\S]*?)\[\/center\]/ig, "<div align=center>$1</div>");
	}
	code = code.replace(/\[right\]([\s\S]*?)\[\/right\]/ig, "<div align=right>$1</div>");
	code = code.replace(/\[block\]([\s\S]*?)\[\/block\]/ig, "<div align=justify>$1</div>");

	// Links
	code = code.replace(/\[url=\"{0,1}www.([\s\S]*?)\"{0,1} blank\]([\s\S]*?)\[\/url\]/ig, "<A href=\"http://www.$1\" target=_blank>$2</A>");
	code = code.replace(/\[url=\"{0,1}([\s\S]*?)\"{0,1} blank\]([\s\S]*?)\[\/url\]/ig, "<A href=\"$1\" target=_blank>$2</A>");
	code = code.replace(/\[url=\"{0,1}www.([\s\S]*?)\"{0,1}\]([\s\S]*?)\[\/url\]/ig, "<A href=\"http://www.$1\" target=_self>$2</A>");
	code = code.replace(/\[url=\"{0,1}([\s\S]*?)\"{0,1}\]([\s\S]*?)\[\/url\]/ig, "<A href=\"$1\" target=_self>$2</A>");
	code = code.replace(/\[url\](http:\/\/){0,1}www.([\s\S]*?)\[\/url\]/ig, "<A href=\"http://www.$2\" target=_blank>$1www.$2</A>");
	code = code.replace(/\[url\]([\s\S]*?)\[\/url\]/ig, "<A href=\"$1\">$1</A>");

	// Images
	code = code.replace(/\[img\]([0-9]*)\[\/img\]/ig, "<IMG alt=\"\" src=\"../images/imgmanager/$1.jpg\" border=0>");
	code = code.replace(/\[img\]([0-9]*)\.(png|gif)\[\/img\]/ig, "<IMG alt=\"\" src=\"../images/imgmanager/$1.$2\" border=0>");
	code = code.replace(/\[img\]http:\/\/([\s\S]*?)\[\/img\]/ig, "<IMG alt=\"\" src=\"http://$1\" border=0>");
	code = code.replace(/\[img\]([\s\S]*?)\[\/img\]/ig, "<IMG alt=\"\" src=\"../$1\" border=0>");
	code = code.replace(/\[img ([0-9]*?)\|([0-9]*?)\]([0-9]*)\[\/img\]/ig, "<IMG style=\"WIDTH:$1px; HEIGHT:$2px\" alt=\"\" src=\"../images/imgmanager/$3.jpg\" border=0>");
	code = code.replace(/\[img ([0-9]*?)\|([0-9]*?)\]([0-9]*)\.(png|gif)\[\/img\]/ig, "<IMG style=\"WIDTH:$1px; HEIGHT:$2px\" alt=\"\" src=\"../images/imgmanager/$3.$4\" border=0>");
	code = code.replace(/\[img ([0-9]*?)\|([0-9]*?)\]http:\/\/([\s\S]*?)\[\/img\]/ig, "<IMG style=\"WIDTH:$1px; HEIGHT:$2px\" alt=\"\" src=\"http://$3\" border=0>");
	code = code.replace(/\[img ([0-9]*?)\|([0-9]*?)\]([\s\S]*?)\[\/img\]/ig, "<IMG style=\"WIDTH:$1px; HEIGHT:$2px\" alt=\"\" src=\"../$3\" border=0>");

	// HR
	code = code.replace(/\n{0,1}-{3,}\n{0,1}/ig, "<hr>");

	// Listen
	code = code.replace(/\n*\[\*\]/ig, "<LI>");
	code = code.replace(/\[list numbers\]([\s\S]*?)\n*\[\/list\]\n{0,1}/ig, "<OL>$1</OL>");
	code = code.replace(/\[list\]([\s\S]*?)\n*\[\/list\]\n{0,1}/ig, "<UL>$1</UL>");

	// Tabellen
	Treffer = code.match(/\n{0,1}\[table([\s\S]*?)\]([\s\S]*?)\[\/table\]/ig);
	for (i in Treffer)
	{
		if (i >= 0)
		{
			match = Treffer[i].match(/\n{0,1}\[table([\s\S]*?)\]([\s\S]*?)\[\/table\]/i);
			// Class
			Tr = match[1].match(/class=\"{0,1}([a-zA-Z-0-9_\-]*)\"{0,1}/i);
			if (Tr)
			{
				var tclass = ' class='+Tr[1];
			}
			else
			{
				var tclass = '';
			}

			// Padding
			Tr = match[1].match(/padding=\"{0,1}([0-9]){1,}\"{0,1}/i);
			if (Tr)
			{
				var padding = ' cellpadding='+Tr[1];
			}
			else
			{
				var padding = ' cellpadding=0';
			}

			// Size
			Tr = match[1].match(/size=([0-9]*)|([0-9]*)/i);
			if (Tr)
			{
				var width = Tr[1];
				var height = Tr[2];
			}
			if (!width)
			{
				Tr = match[1].match(/size=([0-9]*)(%){0,1}/i);
				if (Tr)
				{
					var width = Tr[1].Tr[2];
					var size = ' width="'+width+'"';
				}
				else
				{
					var size = ' width="100%"';
				}
			}
			else
			{
				var size = ' style="width:'+width+'px; height:'+height+'px;"';
			}

			if (tclass == '')
			{
				var border = ' border=1';
			}
			else
			{
				var border = ' border=0';
			}

			var tblreplace = '<table cellspacing=0'+tclass+padding+size+border+'><tbody>'+match[2]+'</tbody></table>';
			code = str_replace(match[0], tblreplace, code);
		}
	}
	code = code.replace(/\n*\[tr\]\n*([\s\S]*?)\n*\[\/tr\]\n*/ig, "<TR>$1</TR>");
	code = code.replace(/\n*\[td\]([\s\S]*?)\[\/td\]\n*/ig, "<TD>$1</TD>");
	code = code.replace(/\n*\[td ([\s\S]*?)\]\n{0,1}([\s\S]*?)\[\/td\]\n*/ig, "<TD class=$1>$2</TD>");

	// Float Box
	code = code.replace(/\[floatleft\]([\s\S]*?)\[\/floatleft\]\n{0,1}/ig, "<DIV style=\"MARGIN-RIGHT: 8px; BORDER: #ff0000 2px dashed; FLOAT: left;\">$1</DIV>");
	code = code.replace(/\[floatright\]([\s\S]*?)\[\/floatright\]\n{0,1}/ig, "<DIV style=\"MARGIN-LEFT: 8px; BORDER: #ff0000 2px dashed; FLOAT: right;\">$1</DIV>");

	// Schriftart größe farbe
	code = code.replace(/\[size=([0-9])\]([\s\S]*?)\[\/size\]/ig, "<font size=$1>$2</font>");
	code = code.replace(/\[color=#{0,1}([0-9a-f]{6})\]([\s\S]*?)\[\/color\]/ig, "<font color=$1>$2</font>");
	code = code.replace(/\[color=([a-z]*)\]([\s\S]*?)\[\/color\]/ig, "<font color=$1>$2</font>");
	code = code.replace(/\[font=([\s\S]*?)\]([\s\S]*?)\[\/font\]/ig, "<font face=\"$1\">$2</font>");

	code = code.replace(/\n/ig, "<br>");

	document.getElementById(editorName).contentWindow.document.body.innerHTML = code;
}

function reSelect(bookmark)
{
	var _document = document.getElementById(editorName).contentWindow.document;
	if(_document.selection) // IE
	{
		bookmark.select();
	}
	else if (window.getSelection) //Moz/W3C
	{
		var selection = document.getElementById(editorName).contentWindow.window.getSelection;
		if(selection && selection.removeAllRanges)
		{
			selection.removeAllRanges();
			selection.addRange(bookmark);
		}
	}
	else if (document.getSelection) //Moz/W3C
	{
		var selection = document.getElementById(editorName).contentWindow.document.getSelection;
		if(selection && selection.removeAllRanges)
		{
			selection.removeAllRanges();
			selection.addRange(bookmark);
		}
	}
}

function createBookmark()
{
	var selection = document.getElementById(editorName).contentWindow.document.selection;
	if (selection) // IE
	{
		var bookmark = selection.createRange();
	}
	else if (window.getSelection)
	{
		selection = document.getElementById(editorName).contentWindow.window.getSelection();
		if(selection)
		{
			range = selection.getRangeAt(0);
			bookmark = range.cloneRange();
		}
	}
	else if (document.getSelection)
	{
		selection = document.getElementById(editorName).contentWindow.document.getSelection();
		if(selection)
		{
			range = selection.getRangeAt(0);
			bookmark = range.cloneRange();
		}
	}
	return bookmark;
}

function getSelectedTag()
{
	resetButtons();
	document.getElementById('fonttype').innerHTML = 'Arial';
	document.getElementById('fontsize').innerHTML = '2';
	document.getElementById('colorpicker').style.backgroundColor = '#000000';

	bookmark = createBookmark();

	if (window.getSelection)
	{
		var selected_obj = document.getElementById(editorName).contentWindow.window.getSelection().focusNode;
	}
	else if (document.getSelection)
	{
		var selected_obj = document.getElementById(editorName).contentWindow.document.getSelection().focusNode;
	}
	else if (document.selection)
	{
		var selected_obj = document.getElementById(editorName).contentWindow.document.selection.createRange().parentElement();
	}
	var current_tag = selected_obj;
	if (current_tag != null)
	{
		var previous_tagName = selected_obj.tagName;
	}
	else
	{
		var previous_tagName = "HTML";
	}

	while(previous_tagName != "HTML")
	{
		if (current_tag.tagName)
		{
			if (previous_tagName == 'TD')
			{
				document.getElementById('bttbcolbefore').onmouseover = highlight; 
				document.getElementById('bttbcolbefore').onmousedown= insertcolbefore; 
				document.getElementById('bttbcolbefore').onmouseup= releasebutton; 
				document.getElementById('bttbcolafter').onmouseover = highlight; 
				document.getElementById('bttbcolafter').onmousedown= insertcolafter; 
				document.getElementById('bttbcolafter').onmouseup= releasebutton;
				document.getElementById('bttbrowbefore').onmouseover = highlight; 
				document.getElementById('bttbrowbefore').onmousedown= insertrowbefore; 
				document.getElementById('bttbrowbefore').onmouseup= releasebutton;
				document.getElementById('bttbrowafter').onmouseover = highlight; 
				document.getElementById('bttbrowafter').onmousedown= insertrowafter; 
				document.getElementById('bttbrowafter').onmouseup= releasebutton;
				document.getElementById('bttbdelcol').onmouseover = highlight; 
				document.getElementById('bttbdelcol').onmousedown= delcol; 
				document.getElementById('bttbdelcol').onmouseup= releasebutton;
				document.getElementById('bttbdelrow').onmouseover = highlight; 
				document.getElementById('bttbdelrow').onmousedown= delrow; 
				document.getElementById('bttbdelrow').onmouseup= releasebutton;
			}
			if (previous_tagName == 'BLOCKQUOTE' || current_tag.style.marginLeft == '40px' || current_tag.style.marginLeft == '80px' || current_tag.style.marginLeft == '120px')
			{
				document.getElementById('btindent').className = 'fp_button_down';
				document.getElementById('btindent').onmouseover = pressbutton;
				document.getElementById('btindent').onmouseout = pressbutton;
			}
			if (previous_tagName == 'UL')
			{
				document.getElementById('btul').className = 'fp_button_down';
				document.getElementById('btul').onmouseover = pressbutton;
				document.getElementById('btul').onmouseout = pressbutton;
			}
			if (previous_tagName == 'OL')
			{
				document.getElementById('btol').className = 'fp_button_down';
				document.getElementById('btol').onmouseover = pressbutton;
				document.getElementById('btol').onmouseout = pressbutton;
			}
			if (previous_tagName == 'B' || previous_tagName == 'STRONG' || current_tag.style.fontWeight == 'bold')
			{
				document.getElementById('btbold').className = 'fp_button_down';
				document.getElementById('btbold').onmouseover = pressbutton;
				document.getElementById('btbold').onmouseout = pressbutton;
			}
			if (previous_tagName == 'I' || previous_tagName == 'EM' || current_tag.style.fontStyle == 'italic')
			{
				document.getElementById('btitalic').className = 'fp_button_down';
				document.getElementById('btitalic').onmouseover = pressbutton;
				document.getElementById('btitalic').onmouseout = pressbutton;
			}
			var match = current_tag.style.textDecoration.search(/underline/);
			if (previous_tagName == 'U' || match != -1)
			{
				document.getElementById('btunderline').className = 'fp_button_down';
				document.getElementById('btunderline').onmouseover = pressbutton;
				document.getElementById('btunderline').onmouseout = pressbutton;
			}
			var match = current_tag.style.textDecoration.search(/line\-through/);
			if (previous_tagName == 'STRIKE' || match != -1)
			{
				document.getElementById('btstroke').className = 'fp_button_down';
				document.getElementById('btstroke').onmouseover = pressbutton;
				document.getElementById('btstroke').onmouseout = pressbutton;
			}
			match = '-1';
			if (current_tag.align == 'left' || current_tag.style.textAlign == 'left')
			{
				document.getElementById('btleft').className = 'fp_button_down';
				document.getElementById('btleft').onmouseover = pressbutton;
				document.getElementById('btleft').onmouseout = pressbutton;
			}
			if (current_tag.align == 'right' || current_tag.style.textAlign == 'right')
			{
				document.getElementById('btright').className = 'fp_button_down';
				document.getElementById('btright').onmouseover = pressbutton;
				document.getElementById('btright').onmouseout = pressbutton;
			}
			if (current_tag.align == 'center' || current_tag.style.textAlign == 'center')
			{
				document.getElementById('btcenter').className = 'fp_button_down';
				document.getElementById('btcenter').onmouseover = pressbutton;
				document.getElementById('btcenter').onmouseout = pressbutton;
			}
			if (current_tag.align == 'justify' || current_tag.style.textAlign == 'justify')
			{
				document.getElementById('btblock').className = 'fp_button_down';
				document.getElementById('btblock').onmouseover = pressbutton;
				document.getElementById('btblock').onmouseout = pressbutton;
			}
			if (previous_tagName == 'A')
			{
				document.getElementById('fp_toolbar_link').style.display = 'block';
				document.getElementById('fp_linkurl').value = current_tag.href;
				if (current_tag.target == '_blank')
				{
					document.getElementById('fp_linktarget').selectedIndex = 1;
				}
				else
				{
					document.getElementById('fp_linktarget').selectedIndex = 0;
				}
			}

			if (current_tag.face == 'Arial Black' || current_tag.style.fontFamily == 'Arial Black' || current_tag.style.fontFamily == "'Arial Black'")
			{
				document.getElementById('fonttype').innerHTML = 'Arial Black';
			}
			else if (current_tag.face == 'Comic Sans MS' || current_tag.style.fontFamily == 'Comic Sans MS' || current_tag.style.fontFamily == "'Comic Sans MS'")
			{
				document.getElementById('fonttype').innerHTML = 'Comic Sans MS';
			}
			else if (current_tag.face == 'Courier New' || current_tag.style.fontFamily == 'Courier New' || current_tag.style.fontFamily == "'Courier New'")
			{
				document.getElementById('fonttype').innerHTML = 'Courier New';
			}
			else if (current_tag.face == 'Impact' || current_tag.style.fontFamily == 'Impact' || current_tag.style.fontFamily == "'Impact'")
			{
				document.getElementById('fonttype').innerHTML = 'Impact';
			}
			else if (current_tag.face == 'Times New Roman' || current_tag.style.fontFamily == 'Times New Roman' || current_tag.style.fontFamily == "'Times New Roman'")
			{
				document.getElementById('fonttype').innerHTML = 'Times New Roman';
			}
			else if (current_tag.face == 'Tahoma' || current_tag.style.fontFamily == 'Tahoma' || current_tag.style.fontFamily == "'Tahoma'")
			{
				document.getElementById('fonttype').innerHTML = 'Tahoma';
			}
			else if (current_tag.face == 'Verdana' || current_tag.style.fontFamily == 'Verdana' || current_tag.style.fontFamily == "'Verdana'")
			{
				document.getElementById('fonttype').innerHTML = 'Verdana';
			}
			else if (current_tag.face == 'Arial' || current_tag.style.fontFamily == 'Arial' || current_tag.style.fontFamily == "'Arial'")
			{
				document.getElementById('fonttype').innerHTML = 'Arial';
			}

			if (current_tag.size == '1' || current_tag.style.fontSize == 'x-small')
			{
				document.getElementById('fontsize').innerHTML = '1';
			}
			else if (current_tag.size == '2' || current_tag.style.fontSize == 'small')
			{
				document.getElementById('fontsize').innerHTML = '3';
			}
			else if (current_tag.size == '3' || current_tag.style.fontSize == 'medium')
			{
				document.getElementById('fontsize').innerHTML = '3';
			}
			else if (current_tag.size == '4' || current_tag.style.fontSize == 'large')
			{
				document.getElementById('fontsize').innerHTML = '4';
			}
			else if (current_tag.size == '5' || current_tag.style.fontSize == 'x-large')
			{
				document.getElementById('fontsize').innerHTML = '5';
			}
			else if (current_tag.size == '6' || current_tag.style.fontSize == 'xx-large')
			{
				document.getElementById('fontsize').innerHTML = '6';
			}
			else if (current_tag.size == '7' || current_tag.style.fontSize == '-webkit-xxx-large')
			{
				document.getElementById('fontsize').innerHTML = '7';
			}

			if (current_tag.color != null || current_tag.style.color)
			{
				if (current_tag.color == "transparent" || current_tag.style.color == 'transparent')
				{
					document.getElementById('colorpicker').style.backgroundColor = '#000000';
				}
				else
				{
					if (current_tag.color)
					{
						document.getElementById('colorpicker').style.backgroundColor = current_tag.color;
					}
					else if (current_tag.style.color)
					{
						document.getElementById('colorpicker').style.backgroundColor = current_tag.style.color;
					}
				}
			}
		}
		current_tag = current_tag.parentNode;
		previous_tagName = current_tag.tagName;
	}
}
