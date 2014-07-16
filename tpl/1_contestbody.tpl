<div id="contentheader">
	<div id="contentlogo">WETTBEWERB: {title}</div>
</div>
<div id="contentwindow">
	<div style="width:90%; margin:0px auto;">
		<br>
		{description}
		<p>
		Der Wettbewerb endet am <b>{enddate}</b>
		<p>
		<-- if entries -->
			<a href="index.php?section=contestentries&id={contestid}"><b>-> Einsendungen ansehen</b></a>
			<p>
		<-- /if entries -->
	<-- if contest_open -->
		<-- if user_loggedin -->
			<-- if user_submitted -->
				<b>Du hast and diesem Wettbewerb bereits teilgenommen</b>
			<-- else user_submitted -->
				<br>
				<-- if img_contest -->
					<form action="index.php?section=contest" method="post" enctype="multipart/form-data">
					<input type="hidden" name="contestid" value="{contestid}">
					<div id="contentheader" style="width:500px; margin:0px auto;">
						<div id="contentlogo">Beitrag einsenden</div>
					</div>
					<div id="contentwindow" style="width:488px; margin:0px auto;">
					<table border="0" cellpadding="2" cellspacing="0" width="100%">
						<tr>
							<td width="200"><b>Titel:</b></td>
							<td><input name="title" class="textinput" style="width:300px;"></td>
						</tr>
						<tr>
							<td><b>Bild:</b><br>PNG, JPG oder GIF</td>
							<td><input type="file" name="img" class="textinput"></td>
						</tr>
						<tr>
							<td valign="top"><b>Beschreibung:</b></td>
							<td><textarea name="text" class="textinput" style="width:300px; height:100px;"></textarea></td>
						</tr>
						<tr>
							<td colspan="2" align="right"><input type="submit" value="Absenden" class="button"></td>
						</tr>
					</table>
					</div>
					</form>
				<-- else img_contest -->
					<form action="index.php?section=contest" method="post">
					<input type="hidden" name="contestid" value="{contestid}">
					<div id="contentheader" style="width:500px; margin:0px auto;">
						<div id="contentlogo">Beitrag einsenden</div>
					</div>
					<div id="contentwindow" style="width:488px; margin:0px auto;">
					<table border="0" cellpadding="2" cellspacing="0" width="100%">
						<tr>
							<td valign="top"><b>Beitrag:</b></td>
							<td><textarea name="text" class="textinput" style="width:300px; height:200px;"></textarea></td>
						</tr>
						<tr>
							<td colspan="2" align="right"><input type="submit" value="Absenden" class="button"></td>
						</tr>
					</table>
					</div>
					</form>
				<-- /if img_contest -->
			<-- /if user_submitted -->
		<-- else user_loggedin -->
			<b>Du musst eingeloggt sein, um am Wettbewerb teil zu nemen.</b>
		<-- /if user_loggedin -->
	<-- else contest_open -->
		<b>Der Wettbewerb ist beendet.</b>
	<-- /if contest_open -->
		<br>
	</div>
</div>