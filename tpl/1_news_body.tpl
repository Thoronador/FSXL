<div id="contentheader">
	<div id="contentlogo">
		<span style="float:right">{date}</span>
		<a href="{commentlink}" style="color:#FFFFFF;">[{catname}] {title}</a>
	</div>
</div>
<div id="contentwindow" style="text-align:center;">
	<table border="0" cellpadding="2" cellspacing="0" style="text-align:left; width:90%; margin:0px auto;">
		<tr>
			<td>
				<br>{text}
			</td>
		</tr>
	<-- if links -->
		<tr>
			<td>
				<br><b>Links:</b><br><hr>
				<-- link -->
				• <a href="{linkurl}" style="text-decoration:underline;" target="{linktarget}">{linkname}</a><br>
				<-- /link -->
			</td>
		</tr>
	<-- /if links -->
	</table>
	<p>
	<div style="text-align:left; height:12px;">
		<span style="float:right">geschrieben von {username}</span>
	<-- if comments -->
		<-- if vB -->
			<a href="{commentlink}" style="text-decoration:underline;" target="_blank">Kommentare</a>
		<-- else vB -->
			<a href="{commentlink}" style="text-decoration:underline;">Kommentare ({comments})</a>
		<-- /if vB -->
	<-- /if comments -->
	</div>
</div>