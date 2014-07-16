<div id="contentheader">
	<div id="contentlogo">
		UMFRAGE
	</div>
</div>
<div id="contentwindow">
	<div style="width:90%; margin:0px auto;">
		<b>{question}</b><br>
		Umfragedauer: Vom {startdate} bis zum {enddate}<br>
	<-- if useronly -->
		• Diese Umfrage ist nur für registrierte Benutzer<br>
	<-- /if useronly -->
	<-- if multiselect -->
		• Bei Dieser Umfrage sind Mehrfachauswahlen möglich<br>
	<-- /if multiselect -->
	</div>
</div>
<table border="0" cellpadding="2" cellspacing="1" width="100%" style="margin:-1px;">
	<tr>
		<td class="thead"><b>Antwort</b></td>
		<td class="thead"><a href="?section=pollarchiv&id={pollid}&order=hits" style="color:#FFFFFF;"><b>Hits</b></a></td>
		<td class="thead"><b>Prozent</b></td>
	</tr>
<-- answer -->
	<tr>
		<td class="alt{altnum}">{answer}</td>
		<td class="alt{altnum}" align="center" valign="top">{hits}</td>
		<td class="alt{altnum}" align="center" valign="top">{percent}%</td>
	</tr>
<-- /answer -->
</table>
