<table border="0" cellpadding="4" cellspacing="1" width="100%" style="margin:-1px;">
<-- cat -->
	<-- if cat -->
	<tr>
		<td colspan="3" class="thead">
			<a href="{caturl}" style="color:#FFFFFF">Kategorie: {catname}</a>
		</td>
	</tr>
	<tr>
		<td class="alt{altnum}" colspan="3">{catdescription}</td>
	</tr>
	<-- /if cat -->
	<tr>
		<td class="thead"><b>Name</b></td>
		<td class="thead"><b>Erscheinungsdatum</b></td>
		<td class="thead"><b>Beschreibung</b></td>
	</tr>
<-- /cat -->
<-- video -->
	<tr>
		<td class="alt{altnum}" valign="top"><a href="{url}">{name}</a></td>
		<td class="alt{altnum}" align="center" valign="top">{date}</td>
		<td class="alt{altnum}" valign="top">{description}</td>
	</tr>
<-- /video -->
</table>