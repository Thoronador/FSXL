<?php

$FSXL[title] = $FS_PHRASES[contest_add_title];
$FSXL[content] = '';

// Wettbewerb hinzufügen
if ($_POST[action] == 'add' && $_POST[title] && $_POST[fp_code] && $_POST[winners])
{
	// Datum auswerten
	if ($_POST[sday] != '' && $_POST[smonth] != '' && $_POST[syear] != '' && $_POST[shour] != '' && $_POST[smin] != '')
		$startdate = mktime($_POST[shour], $_POST[smin], 0, $_POST[smonth], $_POST[sday], $_POST[syear]);
	else
		$startdate = time();
	if ($_POST[eday] != '' && $_POST[emonth] != '' && $_POST[eyear] != '' && $_POST[ehour] != '' && $_POST[emin] != '')
		$enddate = mktime($_POST[ehour], $_POST[emin], 0, $_POST[emonth], $_POST[eday], $_POST[eyear]);
	else
		$enddate = time()+2592000;
	if ($_POST[xday] != '' && $_POST[xmonth] != '' && $_POST[xyear] != '' && $_POST[xhour] != '' && $_POST[xmin] != '')
		$xdate = mktime($_POST[xhour], $_POST[xmin], 0, $_POST[xmonth], $_POST[xday], $_POST[xyear]);
	else
		$xdate = time()+3196800;
		
	settype($_POST[winners], 'integer');
	settype($_POST[type], 'integer');
	settype($_POST[analysis], 'integer');
	settype($_POST[secret], 'integer');
	$multiple = $_POST[multiple] ? 1 : 0;

	$chk = mysql_query("INSERT INTO `$FSXL[tableset]_contests` (`id`, `title`, `startdate`, `enddate`, `text`, `type`, `secret`, `multiple`, `analysis`, `votedate`, `winner`, `done`)
						VALUES (NULL, '$_POST[title]', $startdate, $enddate, '$_POST[fp_code]', $_POST[type], $_POST[secret], $multiple, $_POST[analysis], $xdate, $_POST[winners], 0)");
						
	if ($chk)
	{
		if ($_POST[type] == 1)
		{
			$id = mysql_insert_id();
			mkdir('../images/contests/'.$id, 0777);
		}
		
		$FSXL[content] = '
				<div style="padding:20px; text-align:center;">'.$FS_PHRASES[contest_add_added].'</div>
		';
		$_SESSION[unset_tmptext] = true;
	}
	else
	{
		$FSXL[content] = '
				<div style="padding:20px; text-align:center;">'.$FS_PHRASES[contest_add_failed].'</div>
		';
	}
}

// Formular
else
{
	$FSXL[content] .= '
				<form action="?mod=contest&go=addcontest" method="post" name="contestform" onSubmit="return chkContestAddForm('.$_SESSION[user]->editor.')">
				<input type="hidden" name="action" value="add">
				<table border="0" cellpadding="2" cellspacing="1" width="95%" style="margin:0px auto;">
					<tr>
						<td width="200"><b>'.$FS_PHRASES[contest_add_ctitle].':</b></td>
						<td><input class="textinput" name="title" style="width:350px;"></td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[contest_add_startdate].':</b><br><span class="small">'.$FS_PHRASES[contest_add_dateformat].'</span></td>
						<td valign="top">
							<input class="textinput" name="sday" style="width:20px;" value="'.date("d").'">
							<input class="textinput" name="smonth" style="width:20px;" value="'.date("m").'">
							<input class="textinput" name="syear" style="width:40px;" value="'.date("Y").'"> -
							<input class="textinput" name="shour" style="width:20px;" value="'.date("H").'">
							<input class="textinput" name="smin" style="width:20px;" value="'.date("i").'">
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[contest_add_enddate].':</b><br><span class="small">'.$FS_PHRASES[contest_add_dateformat].'</span></td>
						<td valign="top">
							<input class="textinput" name="eday" style="width:20px;" value="'.date("d", time()+2592000).'">
							<input class="textinput" name="emonth" style="width:20px;" value="'.date("m", time()+2592000).'">
							<input class="textinput" name="eyear" style="width:40px;" value="'.date("Y", time()+2592000).'"> -
							<input class="textinput" name="ehour" style="width:20px;" value="'.date("H", time()+2592000).'">
							<input class="textinput" name="emin" style="width:20px;" value="'.date("i", time()+2592000).'">
						</td>
					</tr>
					<tr>
						<td valign="top"><b>'.$FS_PHRASES[contest_add_description].':</b></td>
						<td>
	';

	// Editor einbinden
	$FSXL[content] .= setEditor($_SESSION[user]->editor, 1, $_SESSION[tmptext]);
	if ($_SESSION[user]->editor == 0) include('frogpad/fpinclude.php');

	$FSXL[content] .= '<textarea name="html_code" id="html_code" class="htmlinput" style="display:none;"></textarea>';

	$FSXL[content] .= '
						</td>
					</tr>
					<tr>
						<td valign="top"><b>'.$FS_PHRASES[contest_add_type].':</b><br>'.$FS_PHRASES[contest_add_type_sub].'</td>
						<td>
							<input type="radio" name="type" value="1" checked> '.$FS_PHRASES[contest_add_pic].'<br>
							<input type="radio" name="type" value="2"> '.$FS_PHRASES[contest_add_text].'
						</td>
					</tr>
					<tr>
						<td valign="top"><b>'.$FS_PHRASES[contest_add_secret].':</b><br>'.$FS_PHRASES[contest_add_secret_sub].'</td>
						<td>
							<input type="radio" name="secret" value="1" checked> '.$FS_PHRASES[contest_add_secret_sub1].'<br>
							<input type="radio" name="secret" value="2"> '.$FS_PHRASES[contest_add_secret_sub2].'<br>
							<input type="radio" name="secret" value="3"> '.$FS_PHRASES[contest_add_secret_sub3].'
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[contest_add_multiple].':</b><br>'.$FS_PHRASES[contest_add_multiple_sub].'</td>
						<td valign="top"><input type="checkbox" name="multiple"></td>
					</tr>
					<tr>
						<td valign="top"><b>'.$FS_PHRASES[contest_add_analysis].':</b><br>'.$FS_PHRASES[contest_add_analysis_sub].'</td>
						<td>
							<input type="radio" name="analysis" value="1" checked> '.$FS_PHRASES[contest_add_lottery].'<br>
							<input type="radio" name="analysis" value="2"> '.$FS_PHRASES[contest_add_jury].'<br>
							<input type="radio" name="analysis" value="3"> '.$FS_PHRASES[contest_add_uservote].'
							<input class="textinput" name="xday" style="width:20px;" value="'.date("d", time()+3196800).'">
							<input class="textinput" name="xmonth" style="width:20px;" value="'.date("m", time()+3196800).'">
							<input class="textinput" name="xyear" style="width:40px;" value="'.date("Y", time()+3196800).'"> -
							<input class="textinput" name="xhour" style="width:20px;" value="'.date("H", time()+3196800).'">
							<input class="textinput" name="xmin" style="width:20px;" value="'.date("i", time()+3196800).'">
						</td>
					</tr>
					<tr>
						<td width="200"><b>'.$FS_PHRASES[contest_add_winners].':</b></td>
						<td><input class="textinput" name="winners" style="width:50px;"></td>
					</tr>
					<tr>
						<td colspan="2" align="right">
							<input type="submit" class="button" value="'.$FS_PHRASES[global_send].'">
						</td>
					</tr>
				</table>
				</form>
	';
}

?>