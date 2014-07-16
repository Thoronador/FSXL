<?php

$FSXL[title] = $FS_PHRASES[news_submit_title];

// News bearbeiten
if ($_GET[del])
{
	$_SESSION[unset_tmptext] = true;
	settype($_GET[del], 'integer');
	mysql_query("DELETE FROM `$FSXL[tableset]_news_submit` WHERE `id` = $_GET[del]");

	$FSXL[content] = '<div style="padding:20px; text-align:center;">'.$FS_PHRASES[news_submit_deleted].'</div>';
}

// Einsendungen löschen
elseif($_POST[action] == 'delete')
{
	foreach($_POST[del] AS $id => $value)
	{
		settype($id, 'integer');
		mysql_query("DELETE FROM `$FSXL[tableset]_news_submit` WHERE `id` = $id");

		$FSXL[content] = '<div style="padding:20px; text-align:center;">'.$FS_PHRASES[news_submit_deleted].'</div>';
	}
}

// Liste ausgeben
else
{
	$FSXL[content] .= '
				<div>
				<form action="?mod=news&go=submit" method="post">
				<input type="hidden" name="action" value="delete">
				<table border="0" cellpadding="2" cellspacing="1" width="90%" style="margin:0px auto;">
					<tr>
						<td colspan="4" style="padding:0px;s"><span style="font-size:12pt;"><b>'.$FS_PHRASES[news_submit_select].'</b></span><hr></td>
					</tr>
					<tr>
						<td class="alt0"><b>'.$FS_PHRASES[news_add_newstitle].'</b></td>
						<td class="alt0" align="center"><b>'.$FS_PHRASES[news_submit_date].'</b></td>
						<td class="alt0" align="center"><b>'.$FS_PHRASES[news_add_newsautor].'</b></td>
						<td class="alt0" align="center"><b>'.$FS_PHRASES[news_edit_delete].'</b></td>
					</tr>
	';

	// Liste
	$index = mysql_query("SELECT `id`, `title`, `date`, `user` FROM `$FSXL[tableset]_news_submit` ORDER BY `date` DESC");
	while ($news = mysql_fetch_assoc($index))
	{
		$i++;
		$index2 = mysql_query("SELECT `name` FROM `$FSXL[tableset]_user` WHERE `id` = $news[user]");
		$userdat = mysql_fetch_assoc($index2);
		$FSXL[content] .= '
					<tr>
						<td class="alt'.($i%2==0?1:2).'"><a href="?mod=news&go=addnews&submit='.$news[id].'">'.$news[title].'</a></td>
						<td class="alt'.($i%2==0?1:2).'" align="center">'.date("d.m.Y | H:i", $news[date]).'</td>
						<td class="alt'.($i%2==0?1:2).'" align="center">'.$userdat[name].'</td>
						<td align="center" class="alt'.($i%2==0?1:2).'"><input type="checkbox" name="del['.$news[id].']"></td>
					</tr>
		';
	}

	$FSXL[content] .= '
					<tr>
						<td align="right" colspan="4"><input type="submit" value="'.$FS_PHRASES[global_send].'" class="button"></td>
					</tr>
				</table>
				</form>
				</div>
	';
}


?>