<?php

$FSXL[title] = $FS_PHRASES[main_tplvars_title];

if ($_POST[editid] && $_POST[name] && $_POST[interval])
{
	settype($_POST[editid], 'integer');

	if ($_POST[del])
	{
		@unlink('../cache/tplvar_'.$_POST[editid].'.cch');
		mysql_query("DELETE FROM `$FSXL[tableset]_tplvars` WHERE `id` = $_POST[editid]");
		mysql_query("DELETE FROM `$FSXL[tableset]_tplvars_code` WHERE `var` = $_POST[editid]");

		$FSXL[content] = '
				<div style="padding:20px; text-align:center;">'.$FS_PHRASES[main_tplvars_deleted].'</div>
		';
	}
	else
	{
		settype($_POST[interval], 'integer');
		settype($_POST[show], 'integer');
		settype($_POST[zone], 'integer');

		if ($_POST[limit])
		{
			if ($_POST[sday] != '' && $_POST[smonth] != '' && $_POST[syear] != '' && $_POST[shour] != '' && $_POST[smin] != '')
				$startdate = mktime($_POST[shour], $_POST[smin], 0, $_POST[smonth], $_POST[sday], $_POST[syear]);
			else
				$startdate = time();
			if ($_POST[eday] != '' && $_POST[emonth] != '' && $_POST[eyear] != '' && $_POST[ehour] != '' && $_POST[emin] != '')
				$enddate = mktime($_POST[ehour], $_POST[emin], 0, $_POST[emonth], $_POST[eday], $_POST[eyear]);
			else
				$enddate = time()+2592000;
		}
		else
		{
			$startdate = $enddate = 0;
		}

		$index = mysql_query("UPDATE `$FSXL[tableset]_tplvars` SET `name` = '$_POST[name]', `display` = $_POST[show], 
					`interval` = $_POST[interval], `startdate` = $startdate, `enddate` = $enddate, 
					`include` = '$_POST[file]', `zone` = $_POST[zone], `section` = '$_POST[section]'
					WHERE `id` = $_POST[editid]");
		if ($index)
		{
			if ($_POST[type] == 1) {
				$_POST[code] = array($_POST[code]);
				$_POST[codeid] = array($_POST[codeid]);
			}

			if ($_POST[type] != 3)
			{
				foreach($_POST[code] AS $key => $value)
				{
					settype ($_POST[codeid][$key], 'integer');
					if ($_POST[delcode][$key])
					{
						mysql_query("DELETE FROM `$FSXL[tableset]_tplvars_code` WHERE `id` = ".$_POST[codeid][$key]);
					}
					elseif ($_POST[codeid][$key] && $value)
					{
						$index = mysql_query("UPDATE `$FSXL[tableset]_tplvars_code` SET `code` = '$value' WHERE `id` = ".$_POST[codeid][$key]);
					}
					elseif ($value)
					{
						$index = mysql_query("INSERT INTO `$FSXL[tableset]_tplvars_code` (`id`, `var`, `code`)
									VALUES (NULL, $_POST[editid], '$value')");
					}
				}
			}
			
			// Cache aktualisieren
			if ($_POST[type] == 1)
			{
				$index = mysql_query("SELECT * FROM `$FSXL[tableset]_tplvars_code` WHERE `var` = '$_POST[editid]'");
				$vardata = @mysql_fetch_assoc($index);

				// Template Cache aktualisieren
				unlink('../cache/tplvar_'.$_POST[editid].'.cch');
				$fp = fopen('../cache/tplvar_'.$_POST[editid].'.cch', 'w');
				fwrite($fp, stripslashes($vardata[code]));
				fclose($fp);

				// APC Cache aktualisieren
				if (function_exists('apc_cache_info'))
				{
					$apc_name = $FSXL[config][bez].'/cache/tplvar_'.$_POST[editid].'.cch';
					apc_store($apc_name, stripslashes($vardata[code]));

				}
			}

			$FSXL[content] = '
				<div style="padding:20px; text-align:center;">'.$FS_PHRASES[main_tplvars_edited].'</div>
			';
		}
		else
		{
			$FSXL[content] = '
				<div style="padding:20px; text-align:center;">'.$FS_PHRASES[main_tplvars_editfailed].'</div>
			';
		}
	}
}

// Edit FOrm
elseif ($_GET[edit])
{
	settype($_GET[edit], 'integer');
	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_tplvars` WHERE `id` = $_GET[edit]");
	$var = mysql_fetch_assoc($index);

	// Single
	if ($var[type] == 1)
	{
		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_tplvars_code` WHERE `var` = $var[id]");
		$code = mysql_fetch_assoc($index);

		$FSXL[content] .= '
				<form action="?mod=main&go=tplvars" method="post" name="singleform" onSubmit="return chksingleform()">
				<input type="hidden" name="editid" value="'.$var[id].'">
				<input type="hidden" name="type" value="1">
				<table border="0" cellpadding="0" cellspacing="0" align="center" width="90%">
					<tr>
						<td class="form"><b>'.$FS_PHRASES[main_tplvars_name].':</b></td>
						<td>
							<input name="name" class="textinput" style="width:100px;" value="'.$var[name].'">
						</td>
					</tr>
					<tr>
						<td class="form"><b>'.$FS_PHRASES[main_tplvars_htmlcode].':</b></td>
						<td>
							<input type="hidden" name="codeid" value="'.$code[id].'">
							<textarea name="code" class="textinput" style="width:400px; height:300px;">'.$code[code].'</textarea>
						</td>
					</tr>
					<tr>
						<td class="form"><b>'.$FS_PHRASES[main_tplvars_show].':</b></td>
						<td>
							<input type="radio" name="show" value="1" style="margin-bottom:-1px;" '.($var[display] == 1 ? "checked" : "").'> '.$FS_PHRASES[main_tplvars_always].'<br>
							<input type="radio" name="show" value="2" style="margin-bottom:-1px;" '.($var[display] == 2 ? "checked" : "").'> '.$FS_PHRASES[main_tplvars_all].'
							<input name="interval" class="textinput" style="width:30px;" value="'.$var[interval].'" style="margin-bottom:-2px;">
							'.$FS_PHRASES[main_tplvars_pages].'<br>
							<input type="radio" name="show" value="3" style="margin-bottom:-1px;" '.($var[display] == 3 ? "checked" : "").'> '.$FS_PHRASES[main_tplvars_once].'<br>
							<input type="radio" name="show" value="4" style="margin-bottom:-1px;" '.($var[display] == 4 ? "checked" : "").'> '.$FS_PHRASES[main_tplvars_home].'<br>
							<input type="radio" name="show" value="5" style="margin-bottom:-1px;" '.($var[display] == 5 ? "checked" : "").'> '.$FS_PHRASES[main_tplvars_zone].'
							<select name="zone" class="textinput">
		';
		// Zonenauslesen
		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_zones` ORDER BY `name`");
		while ($zone = mysql_fetch_assoc($index))
		{
			$FSXL[content] .= '<option value="'.$zone[id].'" '.($var[zone] == $zone[id] ? "selected" : "").'>'.$zone[name].'</option>';
		}
		$FSXL[content] .= '
							</select>
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[main_tplvars_section].':</b></td>
						<td>
							<input name="section" class="textinput" style="width:100px;" value="'.$var[section].'">
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[main_tplvars_limit].':</b></td>
						<td>
							<input type="checkbox" name="limit" '.($var[startdate] != 0 ? "checked" : "").'>
						</td>
					</tr>
					<tr>
						<td></td>
						<td>
							<table border="0" cellspacing="0" cellpadding="2">
								<tr>
									<td>'.$FS_PHRASES[main_tplvars_from].'</td>
									<td>
										<input class="textinput" name="sday" style="width:20px;" value="'.($var[startdate] != 0 ? date("d", $var[startdate]) : date("d")).'">
										<input class="textinput" name="smonth" style="width:20px;" value="'.($var[startdate] != 0 ? date("m", $var[startdate]) : date("m")).'">
										<input class="textinput" name="syear" style="width:40px;" value="'.($var[startdate] != 0 ? date("Y", $var[startdate]) : date("Y")).'"> -
										<input class="textinput" name="shour" style="width:20px;" value="'.($var[startdate] != 0 ? date("H", $var[startdate]) : date("H")).'">
										<input class="textinput" name="smin" style="width:20px;" value="'.($var[startdate] != 0 ? date("i", $var[startdate]) : date("i")).'">
									</td>
								</tr>
								<tr>
									<td>'.$FS_PHRASES[main_tplvars_to].'</td>
									<td>
										<input class="textinput" name="eday" style="width:20px;" value="'.($var[enddate] != 0 ? date("d", $var[enddate]) : date("d", time()+2592000)).'">
										<input class="textinput" name="emonth" style="width:20px;" value="'.($var[enddate] != 0 ? date("m", $var[enddate]) : date("m", time()+2592000)).'">
										<input class="textinput" name="eyear" style="width:40px;" value="'.($var[enddate] != 0 ? date("Y", $var[enddate]) : date("Y", time()+2592000)).'"> -
										<input class="textinput" name="ehour" style="width:20px;" value="'.($var[enddate] != 0 ? date("H", $var[enddate]) : date("H", time()+2592000)).'">
										<input class="textinput" name="emin" style="width:20px;" value="'.($var[enddate] != 0 ? date("i", $var[enddate]) : date("i", time()+2592000)).'">
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[main_tplvars_delete].':</b></td>
						<td>
							<input type="checkbox" name="del" id="delete" onClick="delMessage(\''.$FS_PHRASES[main_tplvars_delmessage].'\');">
						</td>
					</tr>
					<tr>
						<td colspan="2" align="right"><input type="submit" class="button" value="'.$FS_PHRASES[global_send].'"></td>
					</tr>
				</table>
				</form>
		';
	}
	elseif ($var[type] == 2)
	{
		$FSXL[content] .= '
				<form action="?mod=main&go=tplvars" method="post" name="multiform" onSubmit="return chkmultiform()">
				<input type="hidden" name="editid" value="'.$var[id].'">
				<input type="hidden" name="type" value="2">
				<table border="0" cellpadding="0" cellspacing="0" align="center" width="90%">
					<tr>
						<td class="form"><b>'.$FS_PHRASES[main_tplvars_name].':</b></td>
						<td>
							<input name="name" class="textinput" style="width:100px;" value="'.$var[name].'">
						</td>
					</tr>
					<tr>
						<td class="form"><b>'.$FS_PHRASES[main_tplvars_show].':</b></td>
						<td>
							<input type="radio" name="show" value="1" style="margin-bottom:-1px;" '.($var[display] == 1 ? "checked" : "").'> '.$FS_PHRASES[main_tplvars_always].'<br>
							<input type="radio" name="show" value="2" style="margin-bottom:-1px;" '.($var[display] == 2 ? "checked" : "").'> '.$FS_PHRASES[main_tplvars_all].'
							<input name="interval" class="textinput" style="width:30px;" value="'.$var[interval].'" style="margin-bottom:-2px;">
							'.$FS_PHRASES[main_tplvars_pages].'<br>
							<input type="radio" name="show" value="3" style="margin-bottom:-1px;" '.($var[display] == 3 ? "checked" : "").'> '.$FS_PHRASES[main_tplvars_once].'<br>
							<input type="radio" name="show" value="4" style="margin-bottom:-1px;" '.($var[display] == 4 ? "checked" : "").'> '.$FS_PHRASES[main_tplvars_home].'<br>
							<input type="radio" name="show" value="5" style="margin-bottom:-1px;" '.($var[display] == 5 ? "checked" : "").'> '.$FS_PHRASES[main_tplvars_zone].'
							<select name="zone" class="textinput">
		';
		// Zonenauslesen
		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_zones` ORDER BY `name`");
		while ($zone = mysql_fetch_assoc($index))
		{
			$FSXL[content] .= '<option value="'.$zone[id].'" '.($var[zone] == $zone[id] ? "selected" : "").'>'.$zone[name].'</option>';
		}
		$FSXL[content] .= '
							</select>
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[main_tplvars_section].':</b></td>
						<td>
							<input name="section" class="textinput" style="width:100px;" value="'.$var[section].'">
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[main_tplvars_limit].':</b></td>
						<td>
							<input type="checkbox" name="limit" '.($var[startdate] != 0 ? "checked" : "").'>
						</td>
					</tr>
					<tr>
						<td></td>
						<td>
							<table border="0" cellspacing="0" cellpadding="2">
								<tr>
									<td>'.$FS_PHRASES[main_tplvars_from].'</td>
									<td>
										<input class="textinput" name="sday" style="width:20px;" value="'.($var[startdate] != 0 ? date("d", $var[startdate]) : date("d")).'">
										<input class="textinput" name="smonth" style="width:20px;" value="'.($var[startdate] != 0 ? date("m", $var[startdate]) : date("m")).'">
										<input class="textinput" name="syear" style="width:40px;" value="'.($var[startdate] != 0 ? date("Y", $var[startdate]) : date("Y")).'"> -
										<input class="textinput" name="shour" style="width:20px;" value="'.($var[startdate] != 0 ? date("H", $var[startdate]) : date("H")).'">
										<input class="textinput" name="smin" style="width:20px;" value="'.($var[startdate] != 0 ? date("i", $var[startdate]) : date("i")).'">
									</td>
								</tr>
								<tr>
									<td>'.$FS_PHRASES[main_tplvars_to].'</td>
									<td>
										<input class="textinput" name="eday" style="width:20px;" value="'.($var[enddate] != 0 ? date("d", $var[enddate]) : date("d", time()+2592000)).'">
										<input class="textinput" name="emonth" style="width:20px;" value="'.($var[enddate] != 0 ? date("m", $var[enddate]) : date("m", time()+2592000)).'">
										<input class="textinput" name="eyear" style="width:40px;" value="'.($var[enddate] != 0 ? date("Y", $var[enddate]) : date("Y", time()+2592000)).'"> -
										<input class="textinput" name="ehour" style="width:20px;" value="'.($var[enddate] != 0 ? date("H", $var[enddate]) : date("H", time()+2592000)).'">
										<input class="textinput" name="emin" style="width:20px;" value="'.($var[enddate] != 0 ? date("i", $var[enddate]) : date("i", time()+2592000)).'">
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[main_tplvars_delete].':</b></td>
						<td>
							<input type="checkbox" name="del" id="delete" onClick="delMessage(\''.$FS_PHRASES[main_tplvars_delmessage].'\');">
						</td>
					</tr>
					<tr>
						<td colspan="2" align="right"><input type="submit" class="button" value="'.$FS_PHRASES[global_send].'"></td>
					</tr>
				</table>
				<table border="0" cellpadding="0" cellspacing="1" align="center" width="90%">
					<tr><td><hr></td></tr>
		';

		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_tplvars_code` WHERE `var` = $var[id]");
		$FSXL[content] .= '<tr><td><script type="text/javascript">var currentLinkIndex = '.mysql_num_rows($index).';</script></td></tr>';
		$i=0;
		while ($code = mysql_fetch_assoc($index))
		{
			$FSXL[content] .= '
					<tr>
						<td class="alt'.($i%2 == 0 ? 1 : 2).'" style="padding:5px;">
							<input type="hidden" name="codeid['.$i.']" value="'.$code[id].'">
							<span style="float:right;">'.$FS_PHRASES[main_tplvars_delete].': <input type="checkbox" name="delcode['.$i.']"></span>
							<b>'.$FS_PHRASES[main_tplvars_htmlcode].':</b><br>
							<textarea class="textinput" name="code['.$i.']" style="width:500px; height:200px;" onkeyup="addTplvarCode(this);">'.$code[code].'</textarea>
						</td>
					</tr>
			';
			$i++;
		}

		$FSXL[content] .= '
					<tr>
						<td class="alt'.($i%2 == 0 ? 1 : 2).'" style="padding:5px;">
							<b>'.$FS_PHRASES[main_tplvars_htmlcode].':</b><br>
							<textarea class="textinput" name="code['.$i.']" id="code['.$i.']" style="width:500px; height:200px;" onkeyup="addTplvarCode(this);"></textarea>
						</td>
					</tr>
					<tr>
						<td>
							<br>
							<input type="submit" class="button" value="'.$FS_PHRASES[global_send].'" style="float:right;">
						</td>
					</tr>
				</table>
				</form>
		';
	}
	else
	{
		$FSXL[content] .= '
				<form action="?mod=main&go=tplvars" method="post" name="includeform" onSubmit="return chkincludeform()">
				<input type="hidden" name="editid" value="'.$var[id].'">
				<input type="hidden" name="type" value="3">
				<table border="0" cellpadding="0" cellspacing="0" align="center" width="90%">
					<tr>
						<td class="form"><b>'.$FS_PHRASES[main_tplvars_name].':</b></td>
						<td>
							<input name="name" class="textinput" style="width:100px;" value="'.$var[name].'">
						</td>
					</tr>
					<tr>
						<td class="form"><b>'.$FS_PHRASES[main_tplvars_file].':</b></td>
						<td>
							<input name="file" class="textinput" style="width:300px;" value="'.$var['include'].'">
						</td>
					</tr>
					<tr>
						<td class="form"><b>'.$FS_PHRASES[main_tplvars_show].':</b></td>
						<td>
							<input type="radio" name="show" value="1" style="margin-bottom:-1px;" '.($var[display] == 1 ? "checked" : "").'> '.$FS_PHRASES[main_tplvars_always].'<br>
							<input type="radio" name="show" value="2" style="margin-bottom:-1px;" '.($var[display] == 2 ? "checked" : "").'> '.$FS_PHRASES[main_tplvars_all].'
							<input name="interval" class="textinput" style="width:30px;" value="'.$var[interval].'" style="margin-bottom:-2px;">
							'.$FS_PHRASES[main_tplvars_pages].'<br>
							<input type="radio" name="show" value="3" style="margin-bottom:-1px;" '.($var[display] == 3 ? "checked" : "").'> '.$FS_PHRASES[main_tplvars_once].'<br>
							<input type="radio" name="show" value="4" style="margin-bottom:-1px;" '.($var[display] == 4 ? "checked" : "").'> '.$FS_PHRASES[main_tplvars_home].'<br>
							<input type="radio" name="show" value="5" style="margin-bottom:-1px;" '.($var[display] == 5 ? "checked" : "").'> '.$FS_PHRASES[main_tplvars_zone].'
							<select name="zone" class="textinput">
		';
		// Zonenauslesen
		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_zones` ORDER BY `name`");
		while ($zone = mysql_fetch_assoc($index))
		{
			$FSXL[content] .= '<option value="'.$zone[id].'" '.($var[zone] == $zone[id] ? "selected" : "").'>'.$zone[name].'</option>';
		}
		$FSXL[content] .= '
							</select>
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[main_tplvars_section].':</b></td>
						<td>
							<input name="section" class="textinput" style="width:100px;" value="'.$var[section].'">
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[main_tplvars_limit].':</b></td>
						<td>
							<input type="checkbox" name="limit" '.($var[startdate] != 0 ? "checked" : "").'>
						</td>
					</tr>
					<tr>
						<td></td>
						<td>
							<table border="0" cellspacing="0" cellpadding="2">
								<tr>
									<td>'.$FS_PHRASES[main_tplvars_from].'</td>
									<td>
										<input class="textinput" name="sday" style="width:20px;" value="'.($var[startdate] != 0 ? date("d", $var[startdate]) : date("d")).'">
										<input class="textinput" name="smonth" style="width:20px;" value="'.($var[startdate] != 0 ? date("m", $var[startdate]) : date("m")).'">
										<input class="textinput" name="syear" style="width:40px;" value="'.($var[startdate] != 0 ? date("Y", $var[startdate]) : date("Y")).'"> -
										<input class="textinput" name="shour" style="width:20px;" value="'.($var[startdate] != 0 ? date("H", $var[startdate]) : date("H")).'">
										<input class="textinput" name="smin" style="width:20px;" value="'.($var[startdate] != 0 ? date("i", $var[startdate]) : date("i")).'">
									</td>
								</tr>
								<tr>
									<td>'.$FS_PHRASES[main_tplvars_to].'</td>
									<td>
										<input class="textinput" name="eday" style="width:20px;" value="'.($var[enddate] != 0 ? date("d", $var[enddate]) : date("d", time()+2592000)).'">
										<input class="textinput" name="emonth" style="width:20px;" value="'.($var[enddate] != 0 ? date("m", $var[enddate]) : date("m", time()+2592000)).'">
										<input class="textinput" name="eyear" style="width:40px;" value="'.($var[enddate] != 0 ? date("Y", $var[enddate]) : date("Y", time()+2592000)).'"> -
										<input class="textinput" name="ehour" style="width:20px;" value="'.($var[enddate] != 0 ? date("H", $var[enddate]) : date("H", time()+2592000)).'">
										<input class="textinput" name="emin" style="width:20px;" value="'.($var[enddate] != 0 ? date("i", $var[enddate]) : date("i", time()+2592000)).'">
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[main_tplvars_delete].':</b></td>
						<td>
							<input type="checkbox" name="del" id="delete" onClick="delMessage(\''.$FS_PHRASES[main_tplvars_delmessage].'\');">
						</td>
					</tr>
					<tr>
						<td colspan="2" align="right"><input type="submit" class="button" value="'.$FS_PHRASES[global_send].'"></td>
					</tr>
				</table>
				</form>
		';
	}
}


// Einfügen
elseif (($_POST[action] == "addsingle" || $_POST[action] == "addmulti" || ($_POST[action] == "addinclude" && $_POST[file])) && $_POST[name] && $_POST[interval])
{
	settype($_POST[interval], 'integer');
	settype($_POST[show], 'integer');
	settype($_POST[type], 'integer');
	settype($_POST[zone], 'integer');
	$type = $_POST[type];

	if ($_POST[limit])
	{
		if ($_POST[sday] != '' && $_POST[smonth] != '' && $_POST[syear] != '' && $_POST[shour] != '' && $_POST[smin] != '')
			$startdate = mktime($_POST[shour], $_POST[smin], 0, $_POST[smonth], $_POST[sday], $_POST[syear]);
		else
			$startdate = time();
		if ($_POST[eday] != '' && $_POST[emonth] != '' && $_POST[eyear] != '' && $_POST[ehour] != '' && $_POST[emin] != '')
			$enddate = mktime($_POST[ehour], $_POST[emin], 0, $_POST[emonth], $_POST[eday], $_POST[eyear]);
		else
			$enddate = time()+2592000;
	}
	else
	{
		$startdate = $enddate = 0;
	}

	$index = mysql_query("INSERT INTO `$FSXL[tableset]_tplvars` (`id`, `name`, `type`, `display`, `interval`, `startdate`, `enddate`, `include`, `zone`, `section`)
				VALUES (NULL, '$_POST[name]', $type, $_POST[show], $_POST[interval], $startdate, $enddate, '$_POST[file]', $_POST[zone], '$_POST[section]')");

	if ($index)
	{
		$id = mysql_insert_id();
		if ($_POST[action] == "addsingle") $_POST[code] = array($_POST[code]);

		if ($type != 3)
		{
			foreach($_POST[code] AS $key => $value)
			{		
				if ($value)
				{
					$index = mysql_query("INSERT INTO `$FSXL[tableset]_tplvars_code` (`id`, `var`, `code`)
								VALUES (NULL, $id, '$value')");
				}
			}
		}

		// Cache erstellen
		if ($type == 1)
		{
			$index = mysql_query("SELECT * FROM `$FSXL[tableset]_tplvars_code` WHERE `var` = $id");
			$vardata = @mysql_fetch_assoc($index);
			$fp = fopen('../cache/tplvar_'.$id.'.cch', 'w');
			fwrite($fp, stripslashes($vardata[code]));
			fclose($fp);

			// APC Cache aktualisieren
			if (function_exists('apc_cache_info'))
			{
				$apc_name = $FSXL[config][bez].'/cache/tplvar_'.$id.'.cch';
				apc_store($apc_name, stripslashes($vardata[code]));

			}
		}

		$FSXL[content] = '
				<div style="padding:20px; text-align:center;">'.$FS_PHRASES[main_tplvars_added].'</div>
		';
	}
	else
	{
		$FSXL[content] = '
				<div style="padding:20px; text-align:center;">'.$FS_PHRASES[main_tplvars_addfailed].'</div>
		';
	}
}
else
{
	// Zonenauslesen
	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_zones` ORDER BY `name`");
	$zoneoptions = '';
	while ($zone = mysql_fetch_assoc($index))
	{
		$zoneoptions .= '<option value="'.$zone[id].'">'.$zone[name].'</option>';
	}

	$FSXL[content] .= '
				<table border="0" cellpadding="0" cellspacing="0" align="center" width="90%" style="margin:0px auto;">
					<tr>
						<td>
							<span style="font-size:12pt"><b>'.$FS_PHRASES[main_tplvars_add].'</b></span><br>
							<form method="post">
								'.$FS_PHRASES[main_tplvars_single].'
								<input type="radio" name="type" id="type1" style="margin-bottom:-2px;" onclick="switchTplvar()" checked>
								'.$FS_PHRASES[main_tplvars_multi].'
								<input type="radio" name="type" id="type2" style="margin-bottom:-2px;" onclick="switchTplvar()">
								'.$FS_PHRASES[main_tplvars_include].'
								<input type="radio" name="type" id="type3" style="margin-bottom:-2px;" onclick="switchTplvar()">
							</form>
							<hr>
						</td>
					</tr>
				</table>
			<div id="singlebox">
				<form action="?mod=main&go=tplvars" method="post" name="singleform" onSubmit="return chksingleform()">
				<input type="hidden" name="action" value="addsingle">
				<input type="hidden" name="type" value="1">
				<table border="0" cellpadding="0" cellspacing="0" align="center" width="90%">
					<tr>
						<td><b>'.$FS_PHRASES[main_tplvars_name].':</b></td>
						<td>
							<input name="name" class="textinput" style="width:100px;">
						</td>
					</tr>
					<tr>
						<td class="form"><b>'.$FS_PHRASES[main_tplvars_htmlcode].':</b></td>
						<td>
							<textarea name="code" class="textinput" style="width:370px; height:300px;"></textarea>
						</td>
					</tr>
					<tr>
						<td class="form"><b>'.$FS_PHRASES[main_tplvars_show].':</b></td>
						<td>
							<input type="radio" name="show" value="1" style="margin-bottom:-1px;" checked> '.$FS_PHRASES[main_tplvars_always].'<br>
							<input type="radio" name="show" value="2" style="margin-bottom:-1px;"> '.$FS_PHRASES[main_tplvars_all].'
							<input name="interval" class="textinput" style="width:30px;" value="100" style="margin-bottom:-2px;">
							'.$FS_PHRASES[main_tplvars_pages].'<br>
							<input type="radio" name="show" value="3" style="margin-bottom:-1px;"> '.$FS_PHRASES[main_tplvars_once].'<br>
							<input type="radio" name="show" value="4" style="margin-bottom:-1px;"> '.$FS_PHRASES[main_tplvars_home].'<br>
							<input type="radio" name="show" value="5" style="margin-bottom:-1px;"> '.$FS_PHRASES[main_tplvars_zone].'
							<select name="zone" class="textinput">
								'.$zoneoptions.'
							</select>
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[main_tplvars_section].':</b></td>
						<td>
							<input name="section" class="textinput" style="width:100px;">
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[main_tplvars_limit].':</b></td>
						<td>
							<input type="checkbox" name="limit">
						</td>
					</tr>
					<tr>
						<td></td>
						<td>
							<table border="0" cellspacing="0" cellpadding="2">
								<tr>
									<td>'.$FS_PHRASES[main_tplvars_from].'</td>
									<td>
										<input class="textinput" name="sday" style="width:20px;" value="'.date("d").'">
										<input class="textinput" name="smonth" style="width:20px;" value="'.date("m").'">
										<input class="textinput" name="syear" style="width:40px;" value="'.date("Y").'"> -
										<input class="textinput" name="shour" style="width:20px;" value="'.date("H").'">
										<input class="textinput" name="smin" style="width:20px;" value="'.date("i").'">
									</td>
								</tr>
								<tr>
									<td>'.$FS_PHRASES[main_tplvars_to].'</td>
									<td>
										<input class="textinput" name="eday" style="width:20px;" value="'.date("d", time()+2592000).'">
										<input class="textinput" name="emonth" style="width:20px;" value="'.date("m", time()+2592000).'">
										<input class="textinput" name="eyear" style="width:40px;" value="'.date("Y", time()+2592000).'"> -
										<input class="textinput" name="ehour" style="width:20px;" value="'.date("H", time()+2592000).'">
										<input class="textinput" name="emin" style="width:20px;" value="'.date("i", time()+2592000).'">
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td colspan="2" align="right"><input type="submit" class="button" value="'.$FS_PHRASES[global_send].'"></td>
					</tr>
				</table>
				</form>
			</div>
			<div id="multibox" style="display:none;">
				<form action="?mod=main&go=tplvars" method="post" name="multiform" onSubmit="return chkmultiform()">
				<input type="hidden" name="action" value="addmulti">
				<input type="hidden" name="type" value="2">
				<table border="0" cellpadding="0" cellspacing="0" align="center" width="90%">
					<tr>
						<td class="form"><b>'.$FS_PHRASES[main_tplvars_name].':</b></td>
						<td>
							<input name="name" class="textinput" style="width:100px;">
						</td>
					</tr>
					<tr>
						<td class="form"><b>'.$FS_PHRASES[main_tplvars_show].':</b></td>
						<td>
							<input type="radio" name="show" value="1" style="margin-bottom:-1px;" checked> '.$FS_PHRASES[main_tplvars_always].'<br>
							<input type="radio" name="show" value="2" style="margin-bottom:-1px;"> '.$FS_PHRASES[main_tplvars_all].'
							<input name="interval" class="textinput" style="width:30px;" value="100" style="margin-bottom:-2px;">
							'.$FS_PHRASES[main_tplvars_pages].'<br>
							<input type="radio" name="show" value="3" style="margin-bottom:-1px;"> '.$FS_PHRASES[main_tplvars_once].'<br>
							<input type="radio" name="show" value="4" style="margin-bottom:-1px;"> '.$FS_PHRASES[main_tplvars_home].'<br>
							<input type="radio" name="show" value="5" style="margin-bottom:-1px;"> '.$FS_PHRASES[main_tplvars_zone].'
							<select name="zone" class="textinput">
								'.$zoneoptions.'
							</select>
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[main_tplvars_section].':</b></td>
						<td>
							<input name="section" class="textinput" style="width:100px;">
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[main_tplvars_limit].':</b></td>
						<td>
							<input type="checkbox" name="limit">
						</td>
					</tr>
					<tr>
						<td></td>
						<td>
							<table border="0" cellspacing="0" cellpadding="2">
								<tr>
									<td>'.$FS_PHRASES[main_tplvars_from].'</td>
									<td>
										<input class="textinput" name="sday" style="width:20px;" value="'.date("d").'">
										<input class="textinput" name="smonth" style="width:20px;" value="'.date("m").'">
										<input class="textinput" name="syear" style="width:40px;" value="'.date("Y").'"> -
										<input class="textinput" name="shour" style="width:20px;" value="'.date("H").'">
										<input class="textinput" name="smin" style="width:20px;" value="'.date("i").'">
									</td>
								</tr>
								<tr>
									<td>'.$FS_PHRASES[main_tplvars_to].'</td>
									<td>
										<input class="textinput" name="eday" style="width:20px;" value="'.date("d", time()+2592000).'">
										<input class="textinput" name="emonth" style="width:20px;" value="'.date("m", time()+2592000).'">
										<input class="textinput" name="eyear" style="width:40px;" value="'.date("Y", time()+2592000).'"> -
										<input class="textinput" name="ehour" style="width:20px;" value="'.date("H", time()+2592000).'">
										<input class="textinput" name="emin" style="width:20px;" value="'.date("i", time()+2592000).'">
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td colspan="2" align="right"><input type="submit" class="button" value="'.$FS_PHRASES[global_send].'"></td>
					</tr>
				</table>
				<script type="text/javascript">var currentLinkIndex = 0;</script>
				<table border="0" cellpadding="0" cellspacing="1" align="center" width="90%">
					<tr><td><hr></td></tr>
					<tr>
						<td class="alt1" style="padding:5px;">
							<b>'.$FS_PHRASES[main_tplvars_htmlcode].':</b><br>
							<textarea class="textinput" name="code[0]" id="code[0]" style="width:500px; height:200px;" onkeyup="addTplvarCode(this);"></textarea>
						</td>
					</tr>
					<tr>
						<td>
							<br>
							<input type="submit" class="button" value="'.$FS_PHRASES[global_send].'" style="float:right;">
						</td>
					</tr>
				</table>
				</form>
			</div>
			<div id="includebox" style="display:none;">
				<form action="?mod=main&go=tplvars" method="post" name="includeform" onSubmit="return chkincludeform()">
				<input type="hidden" name="action" value="addinclude">
				<input type="hidden" name="type" value="3">
				<table border="0" cellpadding="0" cellspacing="0" align="center" width="90%">
					<tr>
						<td class="form"><b>'.$FS_PHRASES[main_tplvars_name].':</b></td>
						<td>
							<input name="name" class="textinput" style="width:100px;">
						</td>
					</tr>
					<tr>
						<td class="form"><b>'.$FS_PHRASES[main_tplvars_file].':</b></td>
						<td>
							<input name="file" class="textinput" style="width:300px;">
						</td>
					</tr>
					<tr>
						<td class="form"><b>'.$FS_PHRASES[main_tplvars_show].':</b></td>
						<td>
							<input type="radio" name="show" value="1" style="margin-bottom:-1px;" checked> '.$FS_PHRASES[main_tplvars_always].'<br>
							<input type="radio" name="show" value="2" style="margin-bottom:-1px;"> '.$FS_PHRASES[main_tplvars_all].'
							<input name="interval" class="textinput" style="width:30px;" value="100" style="margin-bottom:-2px;">
							'.$FS_PHRASES[main_tplvars_pages].'<br>
							<input type="radio" name="show" value="3" style="margin-bottom:-1px;"> '.$FS_PHRASES[main_tplvars_once].'<br>
							<input type="radio" name="show" value="4" style="margin-bottom:-1px;"> '.$FS_PHRASES[main_tplvars_home].'<br>
							<input type="radio" name="show" value="5" style="margin-bottom:-1px;"> '.$FS_PHRASES[main_tplvars_zone].'
							<select name="zone" class="textinput">
								'.$zoneoptions.'
							</select>
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[main_tplvars_section].':</b></td>
						<td>
							<input name="section" class="textinput" style="width:100px;">
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[main_tplvars_limit].':</b></td>
						<td>
							<input type="checkbox" name="limit">
						</td>
					</tr>
					<tr>
						<td></td>
						<td>
							<table border="0" cellspacing="0" cellpadding="2">
								<tr>
									<td>'.$FS_PHRASES[main_tplvars_from].'</td>
									<td>
										<input class="textinput" name="sday" style="width:20px;" value="'.date("d").'">
										<input class="textinput" name="smonth" style="width:20px;" value="'.date("m").'">
										<input class="textinput" name="syear" style="width:40px;" value="'.date("Y").'"> -
										<input class="textinput" name="shour" style="width:20px;" value="'.date("H").'">
										<input class="textinput" name="smin" style="width:20px;" value="'.date("i").'">
									</td>
								</tr>
								<tr>
									<td>'.$FS_PHRASES[main_tplvars_to].'</td>
									<td>
										<input class="textinput" name="eday" style="width:20px;" value="'.date("d", time()+2592000).'">
										<input class="textinput" name="emonth" style="width:20px;" value="'.date("m", time()+2592000).'">
										<input class="textinput" name="eyear" style="width:40px;" value="'.date("Y", time()+2592000).'"> -
										<input class="textinput" name="ehour" style="width:20px;" value="'.date("H", time()+2592000).'">
										<input class="textinput" name="emin" style="width:20px;" value="'.date("i", time()+2592000).'">
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td colspan="2" align="right"><input type="submit" class="button" value="'.$FS_PHRASES[global_send].'"></td>
					</tr>
				</table>
				</form>
			</div>
				<table border="0" cellpadding="0" cellspacing="0" align="center" width="90%" style="margin:0px auto;">
					<tr>
						<td colspan="3">
							<span style="font-size:12pt"><b>'.$FS_PHRASES[main_tplvars_edit].'</b></span>
							<hr>
						</td>
					</tr>
					<tr>
						<td>
							<table border="0" cellpadding="0" cellspacing="1" align="center" width="100%">
								<tr>
									<td class="alt0"><b>'.$FS_PHRASES[main_tplvars_name].'</b></td>
									<td class="alt0" align="center"><b>'.$FS_PHRASES[main_tplvars_type].'</b></td>
									<td class="alt0" align="center"><b>'.$FS_PHRASES[main_tplvars_show].'</b></td>
								</tr>
	';

	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_tplvars` ORDER BY `name`");
	$i=0;
	While ($var = mysql_fetch_assoc($index))
	{
		$i++;
		switch($var[display])
		{
			case 1:
				$display = $FS_PHRASES[main_tplvars_always];
				break;
			case 2:
				$display = $FS_PHRASES[main_tplvars_all].$var[interval].$FS_PHRASES[main_tplvars_pages];
				break;
			case 3:
				$display = $FS_PHRASES[main_tplvars_once];
				break;
			case 4:
				$display = $FS_PHRASES[main_tplvars_home];
				break;
			case 5:
				$index2 = mysql_query("SELECT * FROM `$FSXL[tableset]_zones` WHERE `id` = $var[zone]");
				$zone = mysql_fetch_assoc($index2);
				$display = $FS_PHRASES[main_tplvars_zone] . ' <i><b>'.$zone[name].'</b></i>';
				break;
		}

		switch ($var[type])
		{
			case 1:
				$type = $FS_PHRASES[main_tplvars_single];
				break;
			case 2:
				$type = $FS_PHRASES[main_tplvars_multi];
				break;
			case 3:
				$type = $FS_PHRASES[main_tplvars_include];
				break;
		}

		$FSXL[content] .= '
					<tr>
						<td class="alt'.($i%2==0?1:2).'"><a href="?mod=main&go=tplvars&edit='.$var[id].'">[§'.$var[name].'§]</a></td>
						<td class="alt'.($i%2==0?1:2).'" align="center">'.$type.'</td>
						<td class="alt'.($i%2==0?1:2).'" align="center">'.$display.'</td>
					</tr>
		';
	}

	$FSXL[content] .= '
							</table>
						</td>
					</tr>
				</table>
	';
}

?>