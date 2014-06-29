<script type="text/javascript">
	function chkContactForm()
	{
		if (document.getElementById("ct_name").value != "" && 
			document.getElementById("ct_subject").value != "" &&
			document.getElementById("ct_mail").value != "" &&
			document.getElementById("ct_text").value != "")
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
<div id="contentheader"><div id="contentlogo">KONTAKTFORMULAR</div></div>
<div id="contentwindow">
	<form action="?section=contact" method="post" onSubmit="return chkContactForm()">
	<input type="hidden" name="action" value="submit">
	<input type="hidden" name="time" value="{time}">
	<table border="0" cellpadding="2" cellspacing="0" style="margin:0px auto; width:500px;">
		<tr>
			<td><b>Name*:</b></td>
			<td><input name="Name" id="ct_name" class="textinput" style="width:200px;"></td>
		</tr>
		<tr>
			<td><b>Betreff*:</b></td>
			<td><input name="Betreff" id="ct_subject" class="textinput" style="width:200px;"></td>
		</tr>
		<tr>
			<td><b>E-Mail*:</b></td>
			<td><input name="EMail" id="ct_mail" class="textinput" style="width:300px;"></td>
		</tr>
		<tr style="visibility:collapse;">
			<td><b>Mail*:</b></td>
			<td><input name="mail" id="s_email" class="textinput" style="width:300px;"></td>
		</tr>
		<tr>
			<td valign="top"><b>Nachricht*:</b></td>
			<td><textarea name="Nachricht" id="ct_text" class="textinput" style="width:500px; height:150px;"></textarea></td>
		</tr>
		<tr>
			<td colspan="2">
				 <br>
				<input type="submit" class="button" value="Abschicken" style="float:right;">
			</td>
		</tr>
	</table>
	</form>
</div>
