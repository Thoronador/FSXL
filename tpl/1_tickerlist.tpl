<table border="0" cellpadding="4" cellspacing="1" width="100%" style="margin:-1px;">
	<tr>
		<td class="thead"><b>Ticker</b></td>
		<td class="thead"><b>Aktiv</b></td>
		<td class="thead"><b>Letzter Eintrag</b></td>
		<td class="thead"><b>Beschreibung</b></td>
	</tr>
<-- ticker -->
	<tr>
		<td class="alt{altnum}" valign="top"><a href="{url}">{name}</a></td>
		<-- if active -->
			<td class="alt{altnum}" valign="top" align="center">Ja</td>
		<-- else active -->
			<td class="alt{altnum}" valign="top" align="center">Nein</td>
		<-- /if active -->
		<td class="alt{altnum}" valign="top" align="center">{lastentry}</td>
		<td class="alt{altnum}" valign="top">{description}</td>
	</tr>
<-- /ticker -->
</table>