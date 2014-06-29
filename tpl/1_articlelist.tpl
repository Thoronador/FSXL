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
		<td class="thead"><b>Artikel</b></td>
		<td class="thead"><b>Datum</b></td>
		<td class="thead"><b>Vorschau</b></td>
	</tr>
<-- /cat -->
<-- article -->
	<tr>
		<td class="alt{altnum}" valign="top" nowrap><a href="{articleurl}">{article}</a></td>
		<td class="alt{altnum}" valign="top" nowrap>{date}</td>
		<td class="alt{altnum}">{preview}</td>
	</tr>
<-- /article -->
</table>