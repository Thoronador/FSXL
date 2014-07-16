<table border="0" cellpadding="4" cellspacing="1" width="100%" style="margin:-1px;">
	<tr>
		<td class="thead" colspan="2">SHOP</td>
	</tr>
<-- cat -->
	<-- if cat -->
	<tr>
		<td class="alt{altnum}"><a href="{caturl}"><b>{catname}</b></a><br></td>
		<td class="alt{altnum}">{catdescription}	</td>
	</tr>
	<-- /if cat -->
<-- /cat -->
<-- article -->
	<tr>
		<td class="alt{altnum}" valign="top">
		<-- if thumb -->
			<a href="{img}" target="_blank"><img border="0" src="{thumb}" alt=""></a>
		<-- else thumb -->
			<i>Kein Bild vorhanden</i>
		<-- /if thumb -->
		</td>
		<td class="alt{altnum}" valign="top">
			<a href="{url}" target="_blank"><b>{name}</b></a>
			<p>
			{text}
			<p>
			<span style="float:right;">{price}</span>
			<a href="{url}" target="_blank"><b><u>jetzt kaufen</u></b></a>
		</td>
	</tr>
<-- /article -->
</table>
