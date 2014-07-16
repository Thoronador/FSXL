<?php

$FSXL[title] = $FS_PHRASES[form_results_title];

// Userlöschen
if ($_POST[user])
{
	settype($_POST[user], 'integer');
	settype($_GET[id], 'integer');

	if ($_POST[del]) {
		mysql_query("DELETE FROM `$FSXL[tableset]_form_results` WHERE `id` = '$_POST[user]'");
	}

	reloadPage("?mod=form&go=results&id=$_GET[id]");
}

// User anzeigen
elseif ($_GET[user])
{
	settype($_GET[user], 'integer');
	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_form_results` WHERE `id` = '$_GET[user]'");
	$user = mysql_fetch_assoc($index);
	
	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_forms` WHERE `id` = '$user[form]'");
	$form = mysql_fetch_assoc($index);

	$FSXL[content] .= '
				<form action="?mod=form&go=results&id='.$form[id].'" method="post">
				<input type="hidden" name="user" value="'.$user[id].'">
				<table border="0" cellpadding="2" cellspacing="0" align="center" width="90%">
					<tr>
						<td width="100"><b>'.$FS_PHRASES[form_add_name].':</b></td>
						<td>'.$form[title].'</td>
					</tr>
					<tr>
						<td width="100"><b>'.$FS_PHRASES[form_results_user].':</b></td>
						<td>'.$user[name].'</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[form_results_mail].':</b></td>
						<td>'.$user[mail].'</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[form_results_date].':</b></td>
						<td>'.date($FSXL[config][dateformat], $user[date]).'</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[form_results_ip].':</b></td>
						<td>'.$user[ip].'</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[form_results_right].':</b></td>
						<td>'.($user[correct]==1?$FS_PHRASES[form_results_yes]:$FS_PHRASES[form_results_no]).'</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[form_edit_delete].':</b></td>
						<td>
							<input type="checkbox" name="del" id="delete" onClick="delMessage(\''.$FS_PHRASES[form_results_delmsg].'\');">
						</td>
					</tr>
					<tr>
						<td colspan="2" align="right"><input type="submit" class="button" value="'.$FS_PHRASES[global_send].'"></td>
					</tr>
				</table>
				</form>
				<p/>
				<div style="width:90%; margin:0px auto;">
					<span style="font-size:12pt;"><b>'.$FS_PHRASES[form_edit_fields].'</b></span><hr>
	';
	
	$result = explode("/boundary/", $user[result]);
	foreach($result AS $value) {
		preg_match("/^id([0-9]*?)=>(.*?)$/", $value, $treffer);
		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_form_fields` WHERE `id` = '$treffer[1]'");
		$field = @mysql_fetch_assoc($index);
		switch($field[type])
		{
			case 1: // Radio			
				$FSXL[content] .= '<b>' . $field[title] . '</b><dir>';
				$opts = explode("/boundary/", $field[text]);
				$i=0;
				foreach($opts AS $optname) {
					if ($optname) {
						if (preg_match("/^this->(.*)/", $optname, $treffer2)) {
							$optname = '<span style="color:#00FF00;">'.$optname.'</span>';
						}
						$FSXL[content] .= $treffer[2] == $i ? $optname.' <b>(x)</b>' : $optname;
						$FSXL[content] .= '<br/>';
					}
					$i++;
				}
				$FSXL[content] .= '</dir><p/>';
				break;
			case 3: // EIngabetext
				$FSXL[content] .= '<b>' . $field[title] . '</b><dir>'.$treffer[2].'</dir><p/>';
				break;
		}
	}
	
	$FSXL[content] .= '
				</div>
	';
}

// Ergebnis
elseif ($_GET[id])
{
	settype($_GET[id], 'integer');
	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_forms` WHERE `id` = '$_GET[id]'");
	$form = mysql_fetch_assoc($index);

	$index = mysql_query("SELECT COUNT(`id`) AS `value` FROM `$FSXL[tableset]_form_results` WHERE `form` = '$_GET[id]'");
	$user = mysql_fetch_assoc($index);
	$index = mysql_query("SELECT COUNT(`id`) AS `value` FROM `$FSXL[tableset]_form_results` WHERE `form` = '$_GET[id]' AND `correct` = 1");
	$userright = mysql_fetch_assoc($index);

	$FSXL[content] .= '
				<table border="0" cellpadding="2" cellspacing="0" align="center" width="90%">
					<tr>
						<td><b>'.$FS_PHRASES[form_add_name].':</b></td>
						<td>'.$form[title].'</td>
					</tr>
					<tr>
						<td valign="top"><b>'.$FS_PHRASES[form_add_desc].':</b></td>
						<td>'.$form[desc].'</td>
					</tr>
					<tr>
						<td valign="top"><b>'.$FS_PHRASES[form_results_user].':</b></td>
						<td>'.$user[value].'</td>
					</tr>
					<tr>
						<td valign="top" nowrap><b>'.$FS_PHRASES[form_results_userright].':</b></td>
						<td>'.$userright[value].'</td>
					</tr>
					<tr>
						<td valign="top"><b>'.$FS_PHRASES[form_add_startdate].':</b></td>
						<td>'.date($FSXL[config][dateformat], $form[start]).'</td>
					</tr>
					<tr>
						<td valign="top"><b>'.$FS_PHRASES[form_add_enddate].':</b></td>
						<td>'.date($FSXL[config][dateformat], $form[end]).'</td>
					</tr>
				</table>
				<p/>
				<table border="0" cellpadding="2" cellspacing="1" align="center" width="90%">
					<tr>
						<td class="alt0" align="center"><b>'.$FS_PHRASES[form_results_name].'</b></td>
						<td class="alt0" align="center"><b>'.$FS_PHRASES[form_results_mail].'</b></td>
						<td class="alt0" align="center"><b>'.$FS_PHRASES[form_results_right].'</b></td>
					</tr>
	';
	
	// USer auslesen
	$index = mysql_query("SELECT `id`, `name`, `mail`, `correct` FROM `$FSXL[tableset]_form_results` WHERE `form` = '$_GET[id]' ORDER BY `correct` DESC, `name`");
	while($user = mysql_fetch_assoc($index))
	{
		$i++;
		$FSXL[content] .= '
					<tr>
						<td class="alt'.($i%2==0?1:2).'"><a href="?mod=form&go=results&user='.$user[id].'">'.$user[name].'</a></td>
						<td class="alt'.($i%2==0?1:2).'">'.$user[mail].'</td>
						<td class="alt'.($i%2==0?1:2).'" align="center">'.($user[correct]==1?$FS_PHRASES[form_results_yes]:$FS_PHRASES[form_results_no]).'</td>
					</tr>
		';
	}
	
	$FSXL[content] .= '
				</table>
	';
}

// Übersicht
else
{
	$FSXL[content] .= '
				<div>
				<table border="0" cellpadding="2" cellspacing="1" width="90%" style="margin:0px auto;">
					<tr>
						<td colspan="4"><span style="font-size:12pt;"><b>'.$FS_PHRASES[form_edit_select].'</b></span><hr></td>
					</tr>
					<tr>
						<td>
							<table border="0" cellpadding="2" cellspacing="1" width="100%">
								<tr>
									<td class="alt0"><b>'.$FS_PHRASES[form_add_name].'</b></td>
									<td class="alt0" align="center"><b>'.$FS_PHRASES[form_add_startdate].'</b></td>
									<td class="alt0" align="center"><b>'.$FS_PHRASES[form_add_enddate].'</b></td>
									<td class="alt0" align="center"><b>'.$FS_PHRASES[form_edit_link].'</b></td>
								</tr>
	';

	// Liste
	$index = mysql_query("SELECT `id`, `title`, `start`, `end` FROM `$FSXL[tableset]_forms` ORDER BY `start` DESC");
	while ($form = mysql_fetch_assoc($index))
	{
		$i++;
		$FSXL[content] .= '
					<tr>
						<td class="alt'.($i%2==0?1:2).'"><a href="?mod=form&go=results&id='.$form[id].'">'.$form[title].'</a></td>
						<td class="alt'.($i%2==0?1:2).'" align="center">'.date($FSXL[config][dateformat], $form[start]).'</td>
						<td class="alt'.($i%2==0?1:2).'" align="center">'.date($FSXL[config][dateformat], $form[end]).'</td>
						<td class="alt'.($i%2==0?1:2).'" align="center"><a href="../index.php?section=form&id='.$form[id].'" target="_blank"><img border="0" src="images/'.$FSXL[style].'_link.gif" alt=""></a></td>
					</tr>
		';
	}

	$FSXL[content] .= '
							</td>
						</tr>
				</table>
				</div>
	';
}

?>