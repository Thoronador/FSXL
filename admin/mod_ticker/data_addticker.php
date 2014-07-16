<?php

$FSXL[title] = $FS_PHRASES[ticker_add_title];


if ($_POST[action] == "add" && $_POST[name] && $_POST[interval])
{
	$active  = $_POST[active] ? 1 : 0;
	$show  = $_POST[show] ? 1 : 0;
	settype($_POST[interval], 'integer');

	mysql_query("INSERT INTO `$FSXL[tableset]_ticker` (`id`, `name`, `text`, `active`, `rss`, `interval`, `url`, `show`) 
					VALUES (NULL, '$_POST[name]', '$_POST[text]', $active, '$_POST[rss]', $_POST[interval], '$_POST[url]', $show)");

	$FSXL[content] = '
				<div style="padding:20px; text-align:center;">'.$FS_PHRASES[ticker_add_added].'</div>
	';
}

else
{
	$FSXL[content] .= '
				<form action="?mod=ticker&go=addticker" method="post" name="tickerform" onSubmit="return chkTickerForm()">
				<input type="hidden" name="action" value="add">
				<table border="0" cellpadding="0" cellspacing="0" align="center" width="90%">
					<tr>
						<td><b>'.$FS_PHRASES[ticker_add_name].':</b></td>
						<td><input name="name" class="textinput" style="width:350px;"></td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[ticker_add_rssfeed].':</b><br>'.$FS_PHRASES[ticker_add_rssfeed_sub].'</td>
						<td><input name="rss" class="textinput" style="width:350px;"></td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[ticker_add_url].':</b><br>'.$FS_PHRASES[ticker_add_url_sub].'</td>
						<td><input name="url" class="textinput" style="width:350px;"></td>
					</tr>
					<tr>
						<td class="form"><b>'.$FS_PHRASES[ticker_add_text].':</b></td>
						<td align="right">
							<textarea name="text" class="textinput" style="width:350px; height:100px;"></textarea>
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[ticker_add_interval].':</b><br>'.$FS_PHRASES[ticker_add_interval_sub].'</td>
						<td><input name="interval" class="textinput" style="width:50px;" value="10"></td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[ticker_add_active].':</b><br>'.$FS_PHRASES[ticker_add_active_sub].'</td>
						<td><input name="active" type="checkbox"></td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[ticker_add_show].':</b><br>'.$FS_PHRASES[ticker_add_show_sub].'</td>
						<td><input name="show" type="checkbox"></td>
					</tr>
					<tr>
						<td colspan="2" align="right"><input type="submit" class="button" value="'.$FS_PHRASES[global_send].'"></td>
					</tr>
				</table>
				</form>

	';
}

?>