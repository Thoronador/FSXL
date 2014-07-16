<div id="contentheader">
	<div id="contentlogo">WETTBEWERB: {contesttitle}</div>
</div>
<div id="contentwindow">
	<div style="width:90%; margin:0px auto;">
		<br>
		{contestdescription}
		<p>
		<-- if img_contest -->
			<table border="0" cellpadding="2" cellspacing="0" style="margin:0px auto;">
			<-- entry -->
				<tr>
					<td width="170" valign="top" rowspan="2"><a href="{entrylink}"><img border="0" src="{entrythumb}" alt=""></a></td>
					<td width="300" valign="top">
						<span style="font-size:10pt"><a href="{entrylink}"><b>{entrytitle}</b></a></span><p>
						{entrydescription}
					</td>
				</tr>
				<tr>
					<td valign="bottom">
						<span style="float:right;">Einsendung von:<b> {entryuser}</b></span>
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
				</tr>
				<tr><td colspan="2"><hr></td></tr>
			<-- /entry -->
			</table>
		<-- else img_contest -->
			<table border="0" cellpadding="2" cellspacing="0" style="margin:0px auto;" width="500">
			<-- entry -->
				<tr>
					<td><span style="font-size:10pt"><a href="{entrylink}"><b>Einsendung von: {entryuser}</b></a></span></td>
				</tr>
				<tr>
					<td>{entrydescription}</td>
				</tr>
				<tr>
					<td valign="bottom">
						<span style="float:right"><a href="{entrylink}"><b>-> ...mehr</b></a></span>
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
				</tr>
				<tr><td colspan="2"><hr></td></tr>
			<-- /entry -->
			</table>
		<-- /if img_contest -->
	</div>
	<div align="right">
		<-- page -->
			<-- if currentpage -->
				<b>[{pagenum}]</b>
			<-- else currentpage -->
				<a href="{pagelink}">[{pagenum}]</a>
			<-- /if currentpage -->
		<-- /page -->
	</div>
</div>