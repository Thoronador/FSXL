<div id="contentheader">
	<div id="contentlogo">
		<div style="float:right;">{gallerydate}</div>
		<-- if gallery -->
			{galleryname} - 
		<-- /if gallery -->
		{title} - ({currentpic}/{totalpics})
	</div>
</div>
<div id="contentwindow" style="text-align:center;">
	<table border="0" cellpadding="0" cellspacing="0" width="95%" align="center" style="table-layout:fixed;">
		<tr>
			<td colspan="2" style="padding-bottom:5px;" align="center">
				<a href="{piclink}" target="_blank">
					<img border="0" src="{piclink}" alt="" style="max-width:100%;">
				</a>
			</td>
		</tr>
		<tr>
			<td colspan="2" align="left">{text}<p></td>
		</tr>
		<tr>
			<td align="left"><a href="{prevlink}" class="button" style="display:block; width:50px; text-align:center;">Zurück</a></td>
			<td align="right"><a href="{nextlink}" class="button" style="display:block; width:50px; text-align:center;">Weiter</a></td>
		</tr>
	<-- if gallery -->
		<tr>
			<td colspan="2" align="center"><a href="{gallerylink}" style="text-decoration:underline;">Zurück zur Übersicht</a></td>
		</tr>
	<-- /if gallery -->
	</table>
</div>