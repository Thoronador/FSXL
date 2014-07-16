<div id="contentheader"><div id="contentlogo">PROFIL VON {username}</div></div>
<div id="contentwindow">
	<form action="?section=profile" method="post">
	<input type="hidden" name="action" value="edit">
	<table border="0" cellpadding="2" cellspacing="0" style="margin:0px auto; width:500px;">
		<tr>
			<td colspan="2">
				 <br><b>Profil Informationen:</b><br><hr>
			</td>
		</tr>
		<tr>
			<td><b>Neues Passwort:</b></td>
			<td><input type="password" name="pass" class="textinput" style="width:200px;"></td>
		</tr>
		<tr>
			<td><b>Passwort wiederholen:</b></td>
			<td><input type="password" name="pass2" class="textinput" style="width:200px;"></td>
		</tr>
		<tr>
			<td><b>E-Mail Adresse:</b></td>
			<td><input name="email" class="textinput" style="width:200px;" value="{email}"></td>
		</tr>
	<-- if style_selectable -->
		<tr>
			<td><b>Style:</b></td>
			<td>
				<select name="style" class="textinput" style="width:200px;">
					{styleoptions}
				</select>
			</td>
		</tr>
	<-- /if style_selectable -->
		<tr>
			<td colspan="2">
				 <br><b>Zusätzliche Informationen:</b><br>
				Diese Informationen sind öffentlich einsehbar.<hr>
			</td>
		</tr>
		<tr>
			<td><b>Homepage:</b></td>
			<td><input name="homepage" class="textinput" style="width:200px;" value="{homepage}"></td>
		</tr>
		<tr>
			<td><b>ICQ-Nummer:</b></td>
			<td><input name="icq" class="textinput" style="width:200px;" value="{icq}"></td>
		</tr>
		<tr>
			<td><b>MSN Messenger:</b></td>
			<td><input name="msn" class="textinput" style="width:200px;" value="{msn}"></td>
		</tr>
		<tr>
			<td><b>Registriert seit:</b></td>
			<td>{regdate}</td>
		</tr>
		<tr>
			<td colspan="2">
				 <br>
				<input type="submit" class="button" value="Absenden" style="float:right;">
			</td>
		</tr>
	</table>
	</form>
</div>