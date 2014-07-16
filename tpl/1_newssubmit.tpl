<script type="text/javascript">
	function chkSubmitForm()
	{
		if (document.getElementById("s_title").value != "" && 
			document.getElementById("s_text").value != "" &&
			document.getElementById("s_source").value != "")
		{
			return true;
		}
		else
		{
			alert("Bitte fülle alle mit einem * gekennzeichneten Felder aus");
			return false;
		}
	}
</script>
<div id="contentheader"><div id="contentlogo">NEWS EINSENDEN</div></div>
<div id="contentwindow">
<-- if user_loggedin -->
	<form action="?section=submitnews" method="post" onSubmit="return chkSubmitForm()">
	<input type="hidden" name="action" value="submit">
	<table border="0" cellpadding="2" cellspacing="0" style="margin:0px auto; width:500px;">
		<tr>
			<td><b>Titel*:</b></td>
			<td><input name="title" id="s_title" class="textinput" style="width:300px;"></td>
		</tr>
		<tr style="visibility:collapse;">
			<td><b>EMail*:</b></td>
			<td><input name="email" id="s_email" class="textinput" style="width:300px;"></td>
		</tr>
		<tr>
			<td valign="top"><b>Nachricht*:</b></td>
			<td><textarea name="text" id="s_text" class="textinput" style="width:500px; height:150px;"></textarea></td>
		</tr>
		<tr>
			<td><b>Quelle*:</b></td>
			<td><input name="source" id="s_source" class="textinput" style="width:500px;"></td>
		</tr>
		<tr>
			<td colspan="2">
				 <br>
				<input type="submit" class="button" value="Abschicken" style="float:right;">
			</td>
		</tr>
	</table>
	</form>
<-- else user_loggedin -->
	<div style="text-align:center; padding:20px;">
		Du musst angemeldet sein, um eine News einzusenden.
	</div>
<-- /if user_loggedin -->
</div>
