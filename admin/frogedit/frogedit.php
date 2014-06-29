<?php

$index = mysql_query("SELECT * FROM `$FSXL[tableset]_images` ORDER BY `id` DESC LIMIT 10");
if (mysql_num_rows($index) > 0)
{
	$imghtml = '<table border="0" cellpadding="1" cellspacing="0"><tr>';
	while($img = mysql_fetch_assoc($index))
	{
		if (file_exists('../images/imgmanager/'.$img[id].'.png'))
		{
			$imghtml .= '<td valign="bottom"><img width="32" border="0" src="../images/imgmanager/'.$img[id].'s.jpg" alt="" title="'.$img[title].'" class="fp_thumb" style="cursor:pointer;" onclick="insertText(\'[img]'.$img[id].'.png[/img]\', \'\ fe_closeImgBar();"></td>';
		}
		elseif (file_exists('../images/imgmanager/'.$img[id].'.gif'))
		{
			$imghtml .= '<td valign="bottom"><img width="32" border="0" src="../images/imgmanager/'.$img[id].'s.jpg" alt="" title="'.$img[title].'" class="fp_thumb" style="cursor:pointer;" onclick="insertText(\'[img]'.$img[id].'.gif[/img]\', \'\ fe_closeImgBar();"></td>';
		}
		else
		{
			$imghtml .= '<td valign="bottom"><img width="32" border="0" src="../images/imgmanager/'.$img[id].'s.jpg" alt="" title="'.$img[title].'" class="fp_thumb" style="cursor:pointer;" onclick="insertText(\'[img]'.$img[id].'[/img]\', \'\ fe_closeImgBar();"></td>';
		}
	}
	$imghtml .= '</tr></table>';
}


$frogedit_code = '
	<div id="wysiwyg_container">
		<div unselectable="on" class="fp_bar" id="fp_toolbar1">
			<div unselectable="on" id="tbpresetdropdown"></div>
			<div unselectable="on" class="dropdown" id="fonttype" style="width:110px;" onclick="openDropdown(\'fontdropdown\')" onmouseout="closeDropdown()">Arial</div>
				<div unselectable="on" id="fontdropdown" onmouseover="stopClose()" onmouseout="closeDropdown()">
					<div unselectable="on" id="font_arial" style="font-family:Arial;" class="dropdown_item" onClick="fe_setFont(\'Arial\'); closeDrops();" nowrap>Arial</div>
					<div unselectable="on" id="font_arialblack" style="font-family:Arial Black;" class="dropdown_item" onClick="fe_setFont(\'Arial Black\'); closeDrops();" nowrap>Arial Black</div>
					<div unselectable="on" id="font_comic" style="font-family:Comic Sans MS;" class="dropdown_item" onClick="fe_setFont(\'Comic Sans MS\'); closeDrops();" nowrap>Comic Sans MS</div>
					<div unselectable="on" id="font_courier" style="font-family:Courier New;" class="dropdown_item" onClick="fe_setFont(\'Courier New\'); closeDrops();" nowrap>Courier New</div>
					<div unselectable="on" id="font_impact" style="font-family:Impact;" class="dropdown_item" onClick="fe_setFont(\'Impact\'); closeDrops();" nowrap>Impact</div>
					<div unselectable="on" id="font_tahoma" style="font-family:Tahoma;" class="dropdown_item" onClick="fe_setFont(\'Tahoma\'); closeDrops();" nowrap>Tahoma</div>
					<div unselectable="on" id="font_times" style="font-family:Times New Roman;" class="dropdown_item" onClick="fe_setFont(\'Times New Roman\'); closeDrops();" nowrap>Times New Roman</div>
					<div unselectable="on" id="font_verdana" style="font-family:Verdana;" class="dropdown_item" onClick="fe_setFont(\'Verdana\'); closeDrops();" nowrap>Verdana</div>
				</div>

			<div unselectable="on" class="dropdown" id="fontsize" style="width:25px;" onclick="openDropdown(\'sizedropdown\')" onmouseout="closeDropdown()">2</div>
				<div unselectable="on" id="sizedropdown" onmouseover="stopClose()" onmouseout="closeDropdown()">
					<div unselectable="on" id="size_1" class="dropdown_item" nowrap><font size="1" onClick="fe_setSize(1); closeDrops();">1</font></div>
					<div unselectable="on" id="size_2" class="dropdown_item" nowrap><font size="2" onClick="fe_setSize(2); closeDrops();">2</font></div>
					<div unselectable="on" id="size_3" class="dropdown_item" nowrap><font size="3" onClick="fe_setSize(3); closeDrops();">3</font></div>
					<div unselectable="on" id="size_4" class="dropdown_item" nowrap><font size="4" onClick="fe_setSize(4); closeDrops();">4</font></div>
					<div unselectable="on" id="size_5" class="dropdown_item" nowrap><font size="5" onClick="fe_setSize(5); closeDrops();">5</font></div>
					<div unselectable="on" id="size_6" class="dropdown_item" nowrap><font size="6" onClick="fe_setSize(6); closeDrops();">6</font></div>
					<div unselectable="on" id="size_7" class="dropdown_item" nowrap><font size="7" onClick="fe_setSize(7); closeDrops();">7</font></div>
				</div>

			<div unselectable="on" class="dropdown" id="fontcolor" style="width:30px;" onclick="openDropdown(\'colordropdown\')" onmouseout="closeDropdown()"><div class="colorimage"></div><div unselectable="on" id="colorpicker" class="colorhandler"></div></div>
				<div unselectable="on" id="colordropdown" onmouseover="stopClose()" onmouseout="closeDropdown()" nowrap>
					<div unselectable="on" id="color_000000" class="dropdown_coloritem" style="background-color:#000000;" onClick="fe_setColor(\'#000000\'); closeDrops();" nowrap></div>
					<div unselectable="on" id="color_993300" class="dropdown_coloritem" style="background-color:#993300;" onClick="fe_setColor(\'#993300\'); closeDrops();" nowrap></div>
					<div unselectable="on" id="color_333300" class="dropdown_coloritem" style="background-color:#333300;" onClick="fe_setColor(\'#333300\'); closeDrops();" nowrap></div>
					<div unselectable="on" id="color_003300" class="dropdown_coloritem" style="background-color:#003300;" onClick="fe_setColor(\'#003300\'); closeDrops();" nowrap></div>
					<div unselectable="on" id="color_003366" class="dropdown_coloritem" style="background-color:#003366;" onClick="fe_setColor(\'#003366\'); closeDrops();" nowrap></div>
					<div unselectable="on" id="color_000080" class="dropdown_coloritem" style="background-color:#000080;" onClick="fe_setColor(\'#000080\'); closeDrops();" nowrap></div>
					<div unselectable="on" id="color_333399" class="dropdown_coloritem" style="background-color:#333399;" onClick="fe_setColor(\'#333399\'); closeDrops();" nowrap></div>
					<div unselectable="on" id="color_333333" class="dropdown_coloritem" style="background-color:#333333;" onClick="fe_setColor(\'#333333\'); closeDrops();" nowrap></div>
					<div style="clear:both;"></div>
					<div unselectable="on" id="color_800000" class="dropdown_coloritem" style="background-color:#800000;" onClick="fe_setColor(\'#800000\'); closeDrops();" nowrap></div>
					<div unselectable="on" id="color_FF6600" class="dropdown_coloritem" style="background-color:#FF6600;" onClick="fe_setColor(\'#FF6600\'); closeDrops();" nowrap></div>
					<div unselectable="on" id="color_808000" class="dropdown_coloritem" style="background-color:#808000;" onClick="fe_setColor(\'#808000\'); closeDrops();" nowrap></div>
					<div unselectable="on" id="color_008000" class="dropdown_coloritem" style="background-color:#008000;" onClick="fe_setColor(\'#008000\'); closeDrops();" nowrap></div>
					<div unselectable="on" id="color_008080" class="dropdown_coloritem" style="background-color:#008080;" onClick="fe_setColor(\'#008080\'); closeDrops();" nowrap></div>
					<div unselectable="on" id="color_0000FF" class="dropdown_coloritem" style="background-color:#0000FF;" onClick="fe_setColor(\'#0000FF\'); closeDrops();" nowrap></div>
					<div unselectable="on" id="color_666699" class="dropdown_coloritem" style="background-color:#666699;" onClick="fe_setColor(\'#666699\'); closeDrops();" nowrap></div>
					<div unselectable="on" id="color_808080" class="dropdown_coloritem" style="background-color:#808080;" onClick="fe_setColor(\'#808080\'); closeDrops();" nowrap></div>
					<div style="clear:both;"></div>
					<div unselectable="on" id="color_FF0000" class="dropdown_coloritem" style="background-color:#FF0000;" onClick="fe_setColor(\'#FF0000\'); closeDrops();" nowrap></div>
					<div unselectable="on" id="color_FF9900" class="dropdown_coloritem" style="background-color:#FF9900;" onClick="fe_setColor(\'#FF9900\'); closeDrops();" nowrap></div>
					<div unselectable="on" id="color_99CC00" class="dropdown_coloritem" style="background-color:#99CC00;" onClick="fe_setColor(\'#99CC00\'); closeDrops();" nowrap></div>
					<div unselectable="on" id="color_339966" class="dropdown_coloritem" style="background-color:#339966;" onClick="fe_setColor(\'#339966\'); closeDrops();" nowrap></div>
					<div unselectable="on" id="color_33CCCC" class="dropdown_coloritem" style="background-color:#33CCCC;" onClick="fe_setColor(\'#33CCCC\'); closeDrops();" nowrap></div>
					<div unselectable="on" id="color_3366FF" class="dropdown_coloritem" style="background-color:#3366FF;" onClick="fe_setColor(\'#3366FF\'); closeDrops();" nowrap></div>
					<div unselectable="on" id="color_800080" class="dropdown_coloritem" style="background-color:#800080;" onClick="fe_setColor(\'#800080\'); closeDrops();" nowrap></div>
					<div unselectable="on" id="color_999999" class="dropdown_coloritem" style="background-color:#999999;" onClick="fe_setColor(\'#999999\'); closeDrops();" nowrap></div>
					<div style="clear:both;"></div>
					<div unselectable="on" id="color_FF00FF" class="dropdown_coloritem" style="background-color:#FF00FF;" onClick="fe_setColor(\'#FF00FF\'); closeDrops();" nowrap></div>
					<div unselectable="on" id="color_FFCC00" class="dropdown_coloritem" style="background-color:#FFCC00;" onClick="fe_setColor(\'#FFCC00\'); closeDrops();" nowrap></div>
					<div unselectable="on" id="color_FFFF00" class="dropdown_coloritem" style="background-color:#FFFF00;" onClick="fe_setColor(\'#FFFF00\'); closeDrops();" nowrap></div>
					<div unselectable="on" id="color_00FF00" class="dropdown_coloritem" style="background-color:#00FF00;" onClick="fe_setColor(\'#00FF00\'); closeDrops();" nowrap></div>
					<div unselectable="on" id="color_00FFFF" class="dropdown_coloritem" style="background-color:#00FFFF;" onClick="fe_setColor(\'#00FFFF\'); closeDrops();" nowrap></div>
					<div unselectable="on" id="color_00CCFF" class="dropdown_coloritem" style="background-color:#00CCFF;" onClick="fe_setColor(\'#00CCFF\'); closeDrops();" nowrap></div>
					<div unselectable="on" id="color_993366" class="dropdown_coloritem" style="background-color:#993366;" onClick="fe_setColor(\'#993366\'); closeDrops();" nowrap></div>
					<div unselectable="on" id="color_C0C0C0" class="dropdown_coloritem" style="background-color:#C0C0C0;" onClick="fe_setColor(\'#C0C0C0\'); closeDrops();" nowrap></div>
					<div style="clear:both;"></div>
					<div unselectable="on" id="color_FF99CC" class="dropdown_coloritem" style="background-color:#FF99CC;" onClick="fe_setColor(\'#FF99CC\'); closeDrops();" nowrap></div>
					<div unselectable="on" id="color_FFCC99" class="dropdown_coloritem" style="background-color:#FFCC99;" onClick="fe_setColor(\'#FFCC99\'); closeDrops();" nowrap></div>
					<div unselectable="on" id="color_FFFF99" class="dropdown_coloritem" style="background-color:#FFFF99;" onClick="fe_setColor(\'#FFFF99\'); closeDrops();" nowrap></div>
					<div unselectable="on" id="color_CCFFCC" class="dropdown_coloritem" style="background-color:#CCFFCC;" onClick="fe_setColor(\'#CCFFCC\'); closeDrops();" nowrap></div>
					<div unselectable="on" id="color_CCFFFF" class="dropdown_coloritem" style="background-color:#CCFFFF;" onClick="fe_setColor(\'#CCFFFF\'); closeDrops();" nowrap></div>
					<div unselectable="on" id="color_99CCFF" class="dropdown_coloritem" style="background-color:#99CCFF;" onClick="fe_setColor(\'#99CCFF\'); closeDrops();" nowrap></div>
					<div unselectable="on" id="color_CC99FF" class="dropdown_coloritem" style="background-color:#CC99FF;" onClick="fe_setColor(\'#CC99FF\'); closeDrops();" nowrap></div>
					<div unselectable="on" id="color_FFFFFF" class="dropdown_coloritem" style="background-color:#FFFFFF;" onClick="fe_setColor(\'#FFFFFF\'); closeDrops();" nowrap></div>
					<div style="clear:both;"></div>
				</div>

			<div unselectable="on" class="fp_trenner"></div>
			<div unselectable="on" class="fp_button" id="btbold"><img border="0" src="frogpad/images/bold.gif" onClick="insertFSCode(\'b\')" alt="Fett"></div>
			<div unselectable="on" class="fp_button" id="btitalic"><img border="0" src="frogpad/images/italic.gif" onClick="insertFSCode(\'i\')" alt="Kursiv"></div>
			<div unselectable="on" class="fp_button" id="btunderline"><img border="0" src="frogpad/images/underline.gif" onClick="insertFSCode(\'u\')" alt="Unterstrichen"></div>
			<div unselectable="on" class="fp_button" id="btstroke"><img border="0" src="frogpad/images/stroke.gif" onClick="insertFSCode(\'s\')" alt="Durchgestrichen"></div>
			<div unselectable="on" class="fp_trenner"></div>
			<div unselectable="on" class="fp_button" id="btcenter"><img border="0" src="frogpad/images/center.gif" onClick="insertFSCode(\'center\')" alt="Zentrieren"></div>
			<div unselectable="on" class="fp_button" id="btright"><img border="0" src="frogpad/images/right.gif" onClick="insertFSCode(\'right\')" alt="Rechtsb&uuml;ndig"></div>
			<div unselectable="on" class="fp_button" id="btblock"><img border="0" src="frogpad/images/block.gif" onClick="insertFSCode(\'block\')" alt="Blocksatz"></div>
		</div>

		<div unselectable="on" class="fp_bar" id="fp_toolbar2">
			<div unselectable="on" class="fp_button" id="btlink"><img border="0" src="frogpad/images/link.gif" alt="Link hinzuf&uuml;gen" onClick="fe_openLinkBar();"></div>
			<div unselectable="on" class="fp_button" id="btimage"><img border="0" src="frogpad/images/image.gif" alt="Bild einf&uuml;gen" onClick="fe_openImgBar();"></div>
			<div unselectable="on" class="fp_trenner"></div>
			<div unselectable="on" class="fp_button" id="btol"><img border="0" src="frogpad/images/ol.gif" onclick="insertText(\'[list=number]\n[*]\', \'\n[/list]\')" alt="Nummerische Liste"></div>
			<div unselectable="on" class="fp_button" id="btul"><img border="0" src="frogpad/images/ul.gif" onclick="insertText(\'[list]\n[*]\', \'\n[/list]\')" alt="Stickpunkte"></div>
			<div unselectable="on" class="fp_trenner"></div>
			<div unselectable="on" class="fp_button" id="btindent"><img border="0" src="frogpad/images/indent.gif" onClick="insertFSCode(\'dir\')" alt="Text einr&uuml;cken"></div>
			<div unselectable="on" class="fp_button" id="btfloatleft"><img border="0" src="frogpad/images/floatleft.gif" onClick="insertFSCode(\'floatleft\')" alt="Textbox links einf&uuml;gen"></div>
			<div unselectable="on" class="fp_button" id="btfloatright"><img border="0" src="frogpad/images/floatright.gif" onClick="insertFSCode(\'floatright\')" alt="Textbox rechts einf&uuml;gen"></div>
			<div unselectable="on" class="fp_button" id="btpre"><img border="0" src="frogpad/images/pre.gif" onClick="insertFSCode(\'pre\')" alt="Vorformatierten Text einf&uuml;gen"></div>
			<div unselectable="on" class="fp_button" id="btline"><img border="0" src="frogpad/images/line.gif" onclick="insertText(\'\n---\n\', \'\')" alt="Trennlinie"></div>
		</div>

		<div unselectable="on" class="fp_bar" id="fp_toolbar_link" style="display:none;">
			<div style="padding:2px; font-size:8pt;">
			URL: <input style="width:200px;" class="fp_input" name="fp_linkurl" id="fp_linkurl">
			 Ziel: <select style="margin-bottom:-1px;" class="fp_input" name="fp_linktarget" id="fp_linktarget">
				<option value="_self">Gleiches Fenster</option>
				<option value="_blank">Neues Fenster</option>
			</select>
			 <input style="width:30px;" type="button" class="fp_input" value="ok" onclick="fe_addLink()">
			</div>
		</div>
		<div unselectable="on" class="fp_bar" id="fp_toolbar_image" style="display:none; height:auto;">
			<div style="padding:2px; font-size:8pt;">
			URL: <input style="width:200px;" class="fp_input" name="fp_imgurl" id="fp_imgurl">
			 <input style="width:30px;" type="button" class="fp_input" value="ok" onclick="fe_addImage()">
			'.$imghtml.'
			</div>
		</div>
		<textarea name="fp_code" id="fp_code" class="textinput" style="display:inline; width:405px; height:492px; color:#000000; font-size:10pt; padding:10px;">'.$text.'</textarea>
		<div class="fp_footer">
			<div style="float:right;">FrogEdit Version 1.0</div>
		</div>
	</div>
	<textarea name="html_code" id="html_code" class="htmlinput" style="display:none;"></textarea>
';

?>