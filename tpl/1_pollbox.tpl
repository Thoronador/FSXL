<div class="menucat">UMFRAGE</div>
<div class="menubox" align="center">
<-- if useronly -->
	<table border="0" cellpadding="1" cellspacing="0" width="95%">
		<tr>
			<td colspan="2"><b>{question}</b></td>
		<tr>
	<-- answer -->
		<tr>
			<td colspan="2" align="left" style="padding-bottom:2px;">{answer}</td>
		</tr>
	<-- /answer -->
		<tr>
			<td colspan="2"><b>An dieser Umfrage kannst du nur als User teilnehmen.</b></td>
		<tr>
	</table>
<-- else useronly -->
	<-- if has_submit -->
		<table border="0" cellpadding="1" cellspacing="0" width="95%">
			<tr>
				<td colspan="2"><b>{question}</b></td>
			<tr>
		<-- answer -->
			<tr>
				<td align="left" valign="top" style="padding-bottom:2px;">{answer}</td>
				<td align="left" valign="top" style="padding-left:5px; padding-bottom:2px;" nowrap>{hits} / {percent}%</td>
			</tr>
		<-- /answer -->
			<tr>
				<td colspan="2"><b>Du hast an dieser Umfrage teilgenommen.</b></td>
			<tr>
		</table>
	<-- else has_submit -->
		<form action="" method="post">
		<input type="hidden" name="pollid" value="{pollid}">
		<table border="0" cellpadding="1" cellspacing="0" width="95%">
			<tr>
				<td colspan="2"><b>{question}</b></td>
			<tr>
		<-- answer -->
			<tr>
				<td valign="top" style="padding-bottom:2px;"><input type="{polltype}" name="pollanswer[{answerid}]" value="{answerid}"></td>
				<td align="left" style="padding-bottom:2px;">{answer}</td>
			</tr>
		<-- /answer -->
			<tr>
				<td colspan="2"><input type="submit" class="button" value="Abstimmen"></td>
			<tr>
		</table>
		</form>
	<-- /if has_submit -->	
<-- /if useronly -->
</div>
<p>