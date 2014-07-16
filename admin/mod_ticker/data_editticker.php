<?php

$FSXL[title] = $FS_PHRASES[ticker_edit_title];

if ($_POST[newitem] && $_GET[id])
{
	$date = time();
	settype($_GET[id], 'integer');
	mysql_query("INSERT INTO `$FSXL[tableset]_ticker_text` (`id`, `ticker`, `text`, `date`) VALUES (NULL, $_GET[id], '$_POST[newitem]', $date)");
}


if ($_POST[editid] && $_POST[name] && $_POST[interval])
{
	settype($_POST[editid], 'integer');

	if ($_POST[del])
	{
		mysql_query("DELETE FROM `$FSXL[tableset]_ticker` WHERE `id` = $_POST[editid]");
		mysql_query("DELETE FROM `$FSXL[tableset]_ticker_text` WHERE `ticker` = $_POST[editid]");

		$FSXL[content] = '
				<div style="padding:20px; text-align:center;">'.$FS_PHRASES[ticker_edit_deleted].'</div>
		';
	}
	else
	{
		$active  = $_POST[active] ? 1 : 0;
		$show  = $_POST[show] ? 1 : 0;
		settype($_POST[interval], 'integer');

		mysql_query("UPDATE `$FSXL[tableset]_ticker` SET `name` = '$_POST[name]', `text` = '$_POST[text]', 
					`active` = $active, `rss` = '$_POST[rss]', `interval` = $_POST[interval], `url` = '$_POST[url]', `show` = $show
					WHERE `id` = $_POST[editid]");

		if ($_POST[item])
		{
			foreach ($_POST[item] AS $key => $value)
			{
				settype($key, 'integer');
				if ($_POST[delete][$key])
				{
					mysql_query("DELETE FROM `$FSXL[tableset]_ticker_text` WHERE `id` = $key");
				}
				elseif ($value)
				{
					mysql_query("UPDATE `$FSXL[tableset]_ticker_text` SET `text` = '$value' WHERE `id` = $key");
				}
			}
		}

		$FSXL[content] = '
				<div style="padding:20px; text-align:center;">'.$FS_PHRASES[ticker_edit_edited].'</div>
		';
	}
}

elseif ($_GET[id])
{
	settype($_GET[id], 'integer');
	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_ticker` WHERE `id` = $_GET[id]");
	$ticker = mysql_fetch_assoc($index);

	$FSXL[content] .= '
				<table border="0" cellpadding="2" cellspacing="1" width="90%" style="margin:0px auto;">
					<tr>
						<td colspan="2" style="padding:0px;"><span style="font-size:12pt;"><b>'.$FS_PHRASES[ticker_edit_additem].'</b></span><hr></td>
					</tr>
					<tr>
						<td colspan="2">
							<form action="?mod=ticker&go=editticker&id='.$_GET[id].'" method="post">
							<textarea name="newitem" class="textinput" style="width:99%; height:100px;"></textarea><br>
							<input type="submit" class="button" value="'.$FS_PHRASES[global_send].'" style="float:right;">
							</form>
						</td>
					</tr>
					<tr>
						<td colspan="2" style="padding:0px;"><span style="font-size:12pt;"><b>'.$FS_PHRASES[ticker_edit_editticker].'</b></span><hr></td>
						<form action="?mod=ticker&go=editticker" method="post" name="tickerform" onSubmit="return chkTickerForm()">
						<input type="hidden" name="editid" value="'.$ticker[id].'">
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[ticker_add_name].':</b></td>
						<td><input value="'.$ticker[name].'" name="name" class="textinput" style="width:350px;"></td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[ticker_add_rssfeed].':</b><br>'.$FS_PHRASES[ticker_add_rssfeed_sub].'</td>
						<td><input name="rss" class="textinput" style="width:350px;" value="'.$ticker[rss].'"></td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[ticker_add_url].':</b><br>'.$FS_PHRASES[ticker_add_url_sub].'</td>
						<td><input name="url" class="textinput" style="width:350px;" value="'.$ticker[url].'"></td>
					</tr>
					<tr>
						<td class="form"><b>'.$FS_PHRASES[ticker_add_text].':</b></td>
						<td>
							<textarea name="text" class="textinput" style="width:350px; height:100px;">'.$ticker[text].'</textarea>
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[ticker_add_interval].':</b><br>'.$FS_PHRASES[ticker_add_interval_sub].'</td>
						<td><input name="interval" class="textinput" style="width:50px;" value="'.$ticker[interval].'"></td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[ticker_add_active].':</b><br>'.$FS_PHRASES[ticker_add_active_sub].'</td>
						<td><input name="active" type="checkbox" '.($ticker[active] == 1 ? "checked" : "").'></td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[ticker_add_show].':</b><br>'.$FS_PHRASES[ticker_add_show_sub].'</td>
						<td><input name="show" type="checkbox" '.($ticker[show] == 1 ? "checked" : "").'></td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[ticker_edit_delete].':</b></td>
						<td><input type="checkbox" name="del" id="delete" onClick="delMessage(\''.$FS_PHRASES[ticker_edit_delmsg].'\');"></td>
					</tr>
					<tr>
						<td colspan="2" align="right"><input type="submit" class="button" value="'.$FS_PHRASES[global_send].'"></td>
					</tr>
					<tr>
						<td colspan="2" style="padding:0px;"><span style="font-size:12pt;"><b>'.$FS_PHRASES[ticker_edit_items].'</b></span><hr></td>
					</tr>
	';

	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_ticker_text` WHERE `ticker` = $ticker[id] ORDER BY `date` DESC");
	while ($text = mysql_fetch_assoc($index))
	{
		$FSXL[content] .= '
					<tr>
						<td valign="top">
							<b>'.date("d.m.Y H:i", $text[date]).'</b><br>
							'.$FS_PHRASES[ticker_edit_delete].'
							<input type="checkbox" name="delete['.$ticker[id].']" style="margin-bottom:-2px;">
						</td>
						<td><textarea name="item['.$text[id].']" class="textinput" style="width:350px; height:50px;">'.$text[text].'</textarea></td>
					</tr>
		';
	}

	$FSXL[content] .= '
					<tr>
						<td colspan="2" align="right"><input type="submit" class="button" value="'.$FS_PHRASES[global_send].'"></td>
					</tr>
				</table>
				</form>

	';
}

else
{
	$FSXL[content] .= '
				<div>
				<table border="0" cellpadding="2" cellspacing="1" width="90%" style="margin:0px auto;">
					<tr>
						<td colspan="4" style="padding:0px;"><span style="font-size:12pt;"><b>'.$FS_PHRASES[ticker_edit_select].'</b></span><hr></td>
					</tr>
					<tr>
						<td class="alt0"><b>'.$FS_PHRASES[ticker_add_name].'</b></td>
						<td class="alt0" align="center"><b>'.$FS_PHRASES[ticker_add_active].'</b></td>
						<td class="alt0" align="center"><b>'.$FS_PHRASES[ticker_edit_lastitem].'</b></td>
						<td class="alt0" align="center"><b>'.$FS_PHRASES[ticker_edit_link].'</b></td>
					</tr>
	';

	// Liste
	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_ticker` ORDER BY `id` DESC");
	while ($ticker = mysql_fetch_assoc($index))
	{
		$i++;
		$index2 =  mysql_query("SELECT * FROM `$FSXL[tableset]_ticker_text` WHERE `ticker` = $ticker[id] ORDER BY `date` DESC LIMIT 1");
		$text = mysql_fetch_assoc($index2);
		$FSXL[content] .= '
					<tr>
						<td class="alt'.($i%2==0?1:2).'" style="padding-left:20px;"><a href="?mod=ticker&go=editticker&id='.$ticker[id].'">'.$ticker[name].'</a></td>
						<td align="center" class="alt'.($i%2==0?1:2).'">'.($ticker[active] == 1 ? $FS_PHRASES[ticker_edit_yes] : $FS_PHRASES[ticker_edit_no]).'</td>
						<td align="center" class="alt'.($i%2==0?1:2).'">'.($text[date] ? date("d.m.Y H:i", $text[date]) : $FS_PHRASES[ticker_edit_never]).'</td>
						<td align="center" class="alt'.($i%2==0?1:2).'"><a href="../index.php?section=ticker&id='.$ticker[id].'" target="_blank"><img border="0" src="images/'.$FSXL[style].'_link.gif" alt=""></a></td>
					</tr>
		';
	}

	$FSXL[content] .= '
				</table>
				</div>
	';
}

?>