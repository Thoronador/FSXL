<div id="contentheader">
	<div id="contentlogo">LINKS - {catname}</div>
</div>
<div id="contentwindow">
	<div style="width:90%; margin:0px auto;">
		{catdescription}
	</div>
</div>
<p>
<table border="0" cellpadding="4" cellspacing="1" width="100%" style="margin:-1px;">
<-- subcat -->
	<-- if subcat -->
	<tr>
		<td colspan="3" class="alt{altnum}">
			<a href="{subcaturl}"><b>{subcatname}</b></a>
		</td>
	</tr>
	<-- /if subcat -->
	<tr>
		<td class="thead"><b>Name</b></td>
		<td class="thead"><b>Erscheinungsdatum</b></td>
		<td class="thead"><b>Beschreibung</b></td>
	</tr>
<-- /subcat -->
<-- link -->
	<tr>
		<td class="alt{altnum}" valign="top">
			<-- if url -->
				<a href="{url}"><b>{name}</b></a>
			<-- else url -->
				<b>{name}</b>
			<-- /if url -->
		</td>
		<td class="alt{altnum}" valign="top" align="center">{date}</td>
		<td class="alt{altnum}">{description}</td>
	</tr>
<-- /link -->
</table>