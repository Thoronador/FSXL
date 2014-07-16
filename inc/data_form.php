<?php

// Fragebogen auswerten
if ($_POST[action] == 'submit' && $_POST[name] && $_POST[mail])
{
	$FSXL[template] .= '
			<div id="contentheader">
				<div id="contentlogo">FRAGEBOGEN</div>
			</div>
			<div id="contentwindow">
				<div style="width:90%; margin:0px auto;" align="center">
	';
		
	// Wenn kein Bot
	if ($_POST[checksum] <= ($FSXL[time]-10) && !$_POST[mail2])
	{
		settype($_POST[id], 'integer');
		
		// Auswertung
		$correct = 1;
		$result = array();
		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_form_fields` WHERE `form` = '$_POST[id]' ORDER BY `pos` ASC");
		while ($field = mysql_fetch_assoc($index))
		{
			switch($field[type])
			{
				case 1: // Radio
					$opts = explode("/boundary/", $field[text]);
					$i = 0;
					foreach($opts AS $value) {
						// Richtig
						if ("this->".$_POST[field][$field[id]] == $value) {
							break;
						}
						// Falsch
						elseif  ($_POST[field][$field[id]] == $value) {
							$correct = 0;
							break;
						}
						$i++;
					}
					$result[] = 'id'.$field[id].'=>'.$i;
					break;
				case 3: // Eingabetext
					$result[] = 'id'.$field[id].'=>'.$_POST[field][$field[id]];
			}
		}
		
		$result = implode("/boundary/", $result);
				
		$chk = mysql_query("INSERT INTO `$FSXL[tableset]_form_results` (`id`, `form`, `name`, `mail`, `ip`, `date`, `correct`, `result`)
							VALUES (NULL, '$_POST[id]', '$_POST[name]', '$_POST[mail]', '$_SERVER[REMOTE_ADDR]', '$FSXL[time]', '$correct', '$result')");
		// Schonmal teilgenommen
		if (!$chk) {
			$FSXL[template] .= 'Du hast bereits teilgenommen.';
		}
		// Formular wurde gespeichert
		else {
			$FSXL[template] .= 'Vielen dankt für deine Teilnahme.';
		}
	}
	// Botverdacht
	else
	{
		$FSXL[template] .= 'Formular konnte nicht gesendet werden. Bitte versuche es später erneut.';
	}

	$FSXL[template] .= '
				</div>
			</div>
	';
}

// Fragebogen Detailansicht
elseif ($_GET[id])
{
	// Daten einlesen
	settype($_GET[id], 'integer');

	// Admin
	if ($_SESSION[user]->isadmin) {
		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_forms` WHERE `id` = '$_GET[id]'");
	}
	// User
	else {
		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_forms` WHERE `id` = '$_GET[id]' AND `start` <= '$FSXL[time]'");
	}

	if (mysql_num_rows($index) > 0)
	{
		$form = mysql_fetch_assoc($index);
		$FSXL[template] .= '
				<div id="contentheader">
					<div id="contentlogo">FRAGEBOGEN - '.$form[title].'</div>
				</div>
				<div id="contentwindow" align="left">
					<div style="width:90%; margin:0px auto;">
						'.fscode($form[desc]).'
						<p/>
						<b>Startdatum:</b> '.date("d.m.Y - H:i", $form[start]).'<br/>
						<b>Enddatum:</b> '.date("d.m.Y - H:i", $form[end]).'
					</div>
					<div style="width:500px; margin:0px auto; padding-top:30px;">
						<script type="text/javascript">
							function chkFormForm() {
								if (document.formform.name.value && document.formform.name.value) {
									return true;
								} else {
									alert("Du musst einen Namen und eine Mailadresse angeben.");
									return false;
								}
							}
						</script>
						<form action="?go=form" method="post" onSubmit="return chkFormForm()" name="formform">
						<input type="hidden" name="action" value="submit">
						<input type="hidden" name="id" value="'.$_GET[id].'">
						<input type="hidden" name="checksum" value="'.$FSXL[time].'">
		';
		
		// Felder auslesen
		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_form_fields` WHERE `form` = '$form[id]' ORDER BY `pos` ASC");
		while ($field = mysql_fetch_assoc($index))
		{
			switch($field[type])
			{
				case 1: // Radio
					$FSXL[template] .= '
						<b>'.$field[title].'</b>
							<dir>
					';
					$opts = explode("/boundary/", $field[text]);
					foreach($opts AS $value) {
						if ($value) {
							if (preg_match("/^this->(.*)/", $value, $treffer)) {
								$value = $treffer[1];
							}
							$FSXL[template] .= '<input value="'.$value.'" type="radio" name="field['.$field[id].']"> '.$value.'<br/>';
						}
					}
					$FSXL[template] .= '
							</dir>
						<p/>
					';
					break;
				case 2: // Trenner
					$FSXL[template] .= '
						<div style="border-bottom:1px solid #666666; font-size:12pt; font-weight:bold;">'.$field[title].'</div>
						'.fscode($field[text]).'
						<p/>
					';
					break;
				case 3: // Eingabetext
					$FSXL[template] .= '
						<b>'.$field[title].'</b>
							<dir>
								<textarea class="textinput" name="field['.$field[id].']" style="width:400px; height:50px;"></textarea>
							</dir>
						<p/>
					';
					break;
			}
		}
		
		// Absenden
		if ($form[end] >= $FSXL[time])
		{
			$FSXL[template] .= '
							<table border="0" cellpadding="2" cellspacing="0" style="margin:0px auto; padding-top:30px;">
								<tr>
									<td colspan="2" style="font-size:12pt;"><b>Teilnahme Formular</b></td>
								</tr>
								<tr>
									<td><b>Name:</b></td>
									<td><input class="textinput" name="name" style="width:300px;" /></td>
								</tr>
								<tr>
									<td><b>E-Mail:</b></td>
									<td><input class="textinput" name="mail" style="width:300px;" /></td>
								</tr>
								<tr style="display:none;">
									<td><b>E-Mail bestätigen:</b></td>
									<td><input class="textinput" name="mail2" style="width:300px;" /></td>
								</tr>
								<tr>
									<td colspan="2" align="right"><input type="submit" class="button" value="Absenden" /></td>
								</tr>
							</table>
						</form>
					</div>
				</div>
			';
		}
		// Abgelaufen
		else
		{
			$FSXL[template] .= '
						</form>
					</div>
				</div>
			';
		}
	}
	// Fragebogen nicht gefunden oder noch nicht freigegeben
	else
	{
		$FSXL[template] .= errorMsg('errorfilenotfound');
	}
}

// Default
else
{
	$FSXL[template] .= errorMsg('errorfilenotfound');
}

?>