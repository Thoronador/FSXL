<?php

$FSXL[title] = $FS_PHRASES[news_comments_title];

// Kommentare bearbeiten
if ($_POST[action] == 'edit')
{
	foreach($_POST[text] as $key => $value)
	{
		settype($key, 'integer');
		settype($_POST[newsid], 'integer');

		// Löschen
		if ($_POST[delete][$key])
		{
			mysql_query("DELETE FROM `$FSXL[tableset]_news_comments` WHERE `id` = $key");
			mysql_query("UPDATE `$FSXL[tableset]_news` SET `numcomments` = `numcomments` - 1 WHERE `id` = $_POST[newsid]");
		}
		else
		{
			if ($_POST[text][$key])
			{
				mysql_query("UPDATE `$FSXL[tableset]_news_comments` SET `text` = '".$_POST[text][$key]."' WHERE `id` = $key");
			}
		}
	}

	$FSXL[content] = '
				<div style="padding:20px; text-align:center;">'.$FS_PHRASES[news_comments_editdone].'</div>
	';
}

// Formular
else
{
	// News aulesen
	settype($_GET[id], 'integer');
	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_news` WHERE `id` = $_GET[id]");
	$news = mysql_fetch_assoc($index);

	if ($news[type] == 1)
	{
		$news[text] = fscode($news[text]);
	}


	$FSXL[content] .= '
				<div>
				<form action="?mod=news&go=comments" method="post">
				<input type="hidden" name="newsid" value="'.$_GET[id].'">
				<input type="hidden" name="action" value="edit">
				<input type="hidden" name="newsid" value="'.$news[id].'">
				<table border="0" cellpadding="0" cellspacing="0" align="center" width="95%">
					<tr>
						<td colspan="2"><b>'.$news[titel].'</b></td>
					</tr>
					<tr>
						<td colspan="2">'.$news[text].'</td>
					</tr>
					<tr>
						<td colspan="2"><hr><b>'.$FS_PHRASES[news_config_comments].'</b>:</td>
					</tr>
	';

	// Kategorien auslesen
	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_news_comments` WHERE `newsid` = $news[id] ORDER BY `num`");
	while ($comment = mysql_fetch_assoc($index))
	{
		// Username lesen
		$index2 = mysql_query("SELECT `name` FROM `$FSXL[tableset]_user` WHERE `id` = $comment[userid]");
		if (mysql_num_rows($index2) > 0)
		{
			$name = mysql_result($index2, 0, 'name');
		}
		else
		{
			$name = $FS_PHRASES[news_comments_guest];
		}

		$FSXL[content] .= '
					<tr>
						<td valign="top" width="150">
							<b>'.$comment[num].':</b>
							'.$name.'<br>
							'.$FS_PHRASES[news_edit_delete].':
							<input type="checkbox" name="delete['.$comment[id].']">
						</td>
						<td>
							<textarea name="text['.$comment[id].']" class="textinput" style="width:400px; height:50px;">'.$comment[text].'</textarea>
						</td>
					</tr>
		';
	}

	$FSXL[content] .= '
					<tr>
						<td colspan="2">
							<input type="submit" class="button" value="'.$FS_PHRASES[global_send].'" style="float:right;">
						</td>
					</tr>
				</table>
				</form>
				</div>
	';
}

?>