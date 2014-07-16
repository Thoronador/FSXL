{news}
<p>
<-- if comments -->

<-- commentbody -->
<div id="contentheader">
	<div id="contentlogo">
		<span style="float:right;">{commentdate}</span>
		<b>{commentnum}:</b> <-- if commentuser -->{commentautor}<-- else commentuser -->Gast<-- /if commentuser -->
	</div>
</div>
<div id="contentwindow" style="text-align:center;">
	<table border="0" cellpadding="2" cellspacing="0" style="text-align:left; width:90%;">
		<tr>
			<td>{commenttext}</td>
		</tr>
	</table>
</div>
<p>
<-- /commentbody -->
<div align="right">{pageselect}</div>
<p>
<div id="contentheader">
	<div id="contentlogo">
		KOMMENTAR HINZUFÜGEN
	</div>
</div>
<div id="contentwindow" style="text-align:center;">
	<script src="inc/comment.js" type="text/javascript"></script>
	<form action="?section=newsdetail" method="post">
	<input type="hidden" name="newsid" value="{newsid}">
	<table border="0" cellpadding="2" cellspacing="0" style="text-align:left; width:500px;">
	<-- if postpermission -->
		<tr>
			<td colspan="2">
				<b>Posten als:</b> <-- if user -->{username}<-- else user -->Gast<-- /if user -->
				<input name="email" class="textinput" style="display:none;">
				<input name="formdate" type="hidden" value="{time}">
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<div class="editorbutton"><img border="0" src="images/styles/froggreen/texteditor/bold.gif" alt="Fett" onClick="insertFSCode('b')"></div>
				<div class="editorbutton"><img border="0" src="images/styles/froggreen/texteditor/italic.gif" alt="Kursiv" onClick="insertFSCode('i')"></div>
				<div class="editorbutton"><img border="0" src="images/styles/froggreen/texteditor/underline.gif" alt="Unterstrichen" onClick="insertFSCode('u')"></div>
				<div class="editorbutton"><img border="0" src="images/styles/froggreen/texteditor/stroke.gif" alt="Durchgestrichen" onClick="insertFSCode('s')"></div>
				<div class="editorbutton"><img border="0" src="images/styles/froggreen/texteditor/center.gif" alt="Zentriert" onClick="insertFSCode('center')"></div>
				<div class="editorbutton"><img border="0" src="images/styles/froggreen/texteditor/right.gif" alt="Rechtsbündig" onClick="insertFSCode('right')"></div>
				<div class="editorbutton"><img border="0" src="images/styles/froggreen/texteditor/block.gif" alt="Blocksatz" onClick="insertFSCode('block')"></div>
			</td>
		</tr>
		<tr>
			<td valign="top"><textarea name="text" id="ctext" class="textinput" style="height:120px; width:400px;">{text}</textarea></td>
			<td valign="top">
				<fieldset style="width:90px;">
				<legend>Smilies</legend>
					{smilies}
				</fieldset>
			</td>
		</tr>
	</table>
	<table border="0" cellpadding="2" cellspacing="0" style="text-align:left; width:500px;">
		<tr>
			<td colspan="2"><input type="submit" class="button" value="Absenden"></td>
		</tr>
	<-- else postpermission -->
		<tr>
			<td style="padding:2px;" align="center">Du musst registriert sein, um Kommentare schreiben zu können.</td>
		</tr>
	<-- /if postpermission -->
	</table>
	</form>
</div>
<-- /if comments -->