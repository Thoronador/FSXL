<div id="contentheader">
	<div id="contentlogo">
		<b><a href="?section=download&folder={folderid}" style="color:#FFFFFF;">{catname}</a> > {name}</b>
	</div>
</div>
<div id="contentwindow">
	<table border="0" cellpadding="2" cellspacing="0" style="margin:0px auto; width:90%;">
		<tr>
			<td width="150"><b>Name:</b></td>
			<td>{name}</td>
		</tr>
		<tr>
			<td><b>Autor:</b></td>
			<td>
				<-- if autorurl -->
					<a href="{autorurl}" target="_blank">{autor}</a>
				<-- else autorurl -->
					{autor}
				<-- /if autorurl -->
			</td>
		</tr>
		<tr>
			<td><b>Hinzugefügt:</b></td>
			<td>{date}</td>
		</tr>
		<tr>
			<td><b>Views:</b></td>
			<td>{views}</td>
		</tr>
		<tr>
			<td><b>Downloads:</b></td>
			<td>{totaldls}</td>
		</tr>
		<tr>
			<td valign="top"><b>Beschreibung:</b></td>
			<td>{text}</td>
		</tr>
	</table>
</div>
<-- if permission -->
<table border="0" cellpadding="2" cellspacing="1" width="100%" style="margin:-1px;">
	<tr>
		<td class="thead"><b>Link</b></td>
		<td class="thead"><b>Größe</b></td>
		<td class="thead"><b>Hits</b></td>
	</tr>
	<-- link -->
	<tr>
		<td class="alt{altnum}"><a href="{linkurl}" target="{linktarget}">{linkname}</a></td>
		<td class="alt{altnum}" align="center">{linksize}</td>
		<td class="alt{altnum}" align="center">{linkhits}</td>
	</tr>
	<-- /link -->
</table>
<-- else permission -->
<table border="0" cellpadding="2" cellspacing="0" width="100%" style="margin:-1px;">
	<tr>
		<td class="thead">Links</td>
	</tr>
	<tr>
		<td class="alt{altnum}"><b>Du musst angemeldet sein, um diesen Download zu starten.</b></td>
	</tr>
</table>
<-- /if permission -->
