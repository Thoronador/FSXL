<table border="0" cellpadding="4" cellspacing="1" width="100%" style="margin:-1px;">
<-- cat -->
	<-- if cat -->
	<tr>
		<td colspan="4" class="thead">
			<a href="{caturl}" style="color:#FFFFFF;">Kategorie: {catname}</a>
		</td>
	</tr>
	<tr>
		<td class="alt{altnum}" colspan="4">{catdescription}</td>
	</tr>
	<-- /if cat -->
	<tr>
		<td class="thead"><b>Galerie</b></td>
		<td class="thead"><b>Bilder</b></td>
		<td class="thead"><b>Datum</b></td>
		<td class="thead"><b>Beschreibung</b></td>
	</tr>
<-- /cat -->
<-- gallery -->
	<tr>
		<td class="alt{altnum}" valign="top" nowrap><a href="{galleryurl}">{gallery}</a></td>
		<td class="alt{altnum}" valign="top" align="center" nowrap>{numpics}</td>
		<td class="alt{altnum}" valign="top" nowrap>{date}</td>
		<td class="alt{altnum}">{preview}</td>
	</tr>
<-- /gallery -->
</table>