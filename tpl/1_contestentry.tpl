<div id="contentheader">
	<div id="contentlogo">
		{contesttitle} - {entrytitle} - ({currententry}/{totalentries})
	</div>
</div>
<div id="contentwindow" style="text-align:center;">
	<table border="0" cellpadding="0" cellspacing="0" width="95%" align="center" style="table-layout:fixed;">
		<-- if img_contest -->
		<tr>
			<td colspan="2" style="padding-bottom:5px;" align="center">
				<a href="{image}" target="_blank">
					<img border="0" src="{image}" alt="" style="max-width:100%;">
				</a>
			</td>
		</tr>
		<-- /if img_contest -->
		<tr>
			<td colspan="2" align="left">{entrydescription}<p></td>
		</tr>
		<tr>
			<td align="left">
				<-- if vote -->
					<-- if user_loggedin -->
						<-- if user_voted -->
							<script type="text/javascript">genVoteButtons2({entrypoints});</script>
						<-- else user_voted -->
							<script type="text/javascript">genVoteButtons({entryid});</script>
						<-- /if user_voted -->
					<-- else user_loggedin -->
						<i>Du musst eingeloggt sein, um abzustimmen.</i>
					<-- /if user_loggedin -->
				<-- /if vote -->
			</td>
			<td align="right">Einsendung von: <b>{username}</b></td>
		</tr>
		<tr><td colspan="2" style="height:10px;"></td></tr>
		<tr>
			<td align="left"><a href="{prevlink}" class="button" style="display:block; width:50px; text-align:center;">Zurück</a></td>
			<td align="right"><a href="{nextlink}" class="button" style="display:block; width:50px; text-align:center;">Weiter</a></td>
		</tr>
		<tr>
			<td colspan="2" align="center"><a href="{contestlink}" style="text-decoration:underline;">Zurück zur Übersicht</a></td>
		</tr>
	</table>
</div>