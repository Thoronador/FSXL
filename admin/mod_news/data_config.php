<?php

if ($_POST[action] == "edit")
{
	settype($_POST[perpage], 'integer');
	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = $_POST[perpage] WHERE `name` = 'news_perpage'");

	settype($_POST[headlines], 'integer');
	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = $_POST[headlines] WHERE `name` = 'news_headlines'");

	$_POST[submit] = $_POST[submit] ? 1 : 0;
	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = $_POST[submit] WHERE `name` = 'submitnews'");

	$_POST[submitmail] = $_POST[submitmail] ? 1 : 0;
	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = $_POST[submitmail] WHERE `name` = 'newssubmitmail'");

	$_POST[guestcomments] = $_POST[guestcomments] ? 1 : 0;
	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = $_POST[guestcomments] WHERE `name` = 'news_guestcomments'");

	settype($_POST[spamtime], 'integer');
	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = $_POST[spamtime] WHERE `name` = 'news_spamtime'");

	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = '$_POST[spamfilter]' WHERE `name` = 'news_spamfilter'");

	settype($_POST[commentsperpage], 'integer');
	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = $_POST[commentsperpage] WHERE `name` = 'news_commentsperpage'");

	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = '$_POST[rssdesc]' WHERE `name` = 'news_rssdesc'");

	settype($_POST[rssnum], 'integer');
	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = $_POST[rssnum] WHERE `name` = 'news_rssnum'");

	settype($_POST[rsslen], 'integer');
	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = $_POST[rsslen] WHERE `name` = 'news_rsslen'");

	settype($_POST[sort], 'integer');
	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = $_POST[sort] WHERE `name` = 'news_comment_order'");

	$_POST[vbselect] = $_POST[vbselect] ? 1 : 0;
	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = $_POST[vbselect] WHERE `name` = 'news_vbselect'");

	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = '$_POST[vbprefix]' WHERE `name` = 'vb_prefix'");

	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = '$_POST[vburl]' WHERE `name` = 'vb_url'");

	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = '$_POST[vbuser]' WHERE `name` = 'vb_user'");

	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = '$_POST[vbpassword]' WHERE `name` = 'vb_password'");

	settype($_POST[vbforum], 'integer');
	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = $_POST[vbforum] WHERE `name` = 'vb_forum'");

	$_POST[commentselect] = $_POST[commentselect] ? 1 : 0;
	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = $_POST[commentselect] WHERE `name` = 'news_selectcomments'");

	reloadPage('?mod=news&go=config');
}

$FSXL[title] = $FS_PHRASES[news_config_title];

$FSXL[content] .= '
				<form action="?mod=news&go=config" method="post">
				<input type="hidden" name="action" value="edit">
				<table border="0" cellpadding="2" cellspacing="1" width="90%" style="margin:0px auto;">
					<tr>
						<td colspan="2">
							<span style="font-size:12pt"><b>'.$FS_PHRASES[news_config_news].'</b></span><hr>
						</td>
					</tr>
					<tr>
						<td width="250"><b>'.$FS_PHRASES[news_config_perpage].':</b><br>'.$FS_PHRASES[news_config_perpage_sub].'</td>
						<td>
							<input name="perpage" class="textinput" style="width:50px;" value="'.$FSXL[config][news_perpage].'">
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[news_config_headlines].':</b><br>'.$FS_PHRASES[news_config_headlines_sub].'</td>
						<td>
							<input name="headlines" class="textinput" style="width:50px;" value="'.$FSXL[config][news_headlines].'">
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[news_config_submit].':</b><br>'.$FS_PHRASES[news_config_submit_sub].'</td>
						<td>
							<input name="submit" type="checkbox" '.($FSXL[config][submitnews]==1?"checked":"").'>
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[news_config_submitmail].':</b><br>'.$FS_PHRASES[news_config_submitmail_sub].'</td>
						<td>
							<input name="submitmail" type="checkbox" '.($FSXL[config][newssubmitmail]==1?"checked":"").'>
						</td>
					</tr>


					<tr>
						<td colspan="2">
							<span style="font-size:12pt"><br><b>'.$FS_PHRASES[news_config_comments].'</b></span><hr>
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[news_config_commentselect].':</b><br>'.$FS_PHRASES[news_config_commentselect_sub].'</td>
						<td>
							<input type="checkbox" name="commentselect" '.($FSXL[config][news_selectcomments] == 1 ? "checked" : "").'>
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[news_config_sort].':</b><br>'.$FS_PHRASES[news_config_sort_sub].'</td>
						<td>
							<input value="1" type="radio" name="sort" '.($FSXL[config][news_comment_order] == 1 ? "checked" : "").'>
							'.$FS_PHRASES[news_config_down].'<br>
							<input value="2" type="radio" name="sort" '.($FSXL[config][news_comment_order] == 2 ? "checked" : "").'>
							'.$FS_PHRASES[news_config_up].'
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[news_config_guestcomments].':</b><br>'.$FS_PHRASES[news_config_guestcomments_sub].'</td>
						<td>
							<input type="checkbox" name="guestcomments" '.($FSXL[config][news_guestcomments] == 1 ? "checked" : "").'>
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[news_config_commentsperpage].':</b><br>'.$FS_PHRASES[news_config_commentsperpage_sub].'</td>
						<td>
							<input name="commentsperpage" class="textinput" style="width:50px;" value="'.$FSXL[config][news_commentsperpage].'">
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[news_config_spamtime].':</b><br>'.$FS_PHRASES[news_config_spamtime_sub].'</td>
						<td>
							<input name="spamtime" class="textinput" style="width:50px;" value="'.$FSXL[config][news_spamtime].'">
						</td>
					</tr>
					<tr>
						<td valign="top">
							<b>'.$FS_PHRASES[news_config_spamfilter].':</b><br>
							'.$FS_PHRASES[news_config_spamfilter_sub].'
						</td>
						<td>
							<textarea name="spamfilter" class="textinput" style="width:300px; height:100px;">'.$FSXL[config][news_spamfilter].'</textarea>
						</td>
					</tr>


					<tr>
						<td colspan="2">
							<span style="font-size:12pt"><br><b>'.$FS_PHRASES[news_config_rss].'</b></span><hr>
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[news_config_rssnum].':</b><br>'.$FS_PHRASES[news_config_rssnum_sub].'</td>
						<td>
							<input name="rssnum" class="textinput" style="width:50px;" value="'.$FSXL[config][news_rssnum].'">
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[news_config_rsslen].':</b><br>'.$FS_PHRASES[news_config_rsslen_sub].'</td>
						<td>
							<input name="rsslen" class="textinput" style="width:50px;" value="'.$FSXL[config][news_rsslen].'">
						</td>
					</tr>
					<tr>
						<td valign="top"><b>'.$FS_PHRASES[news_config_rssdesc].':</b><br>'.$FS_PHRASES[news_config_rssdesc_sub].'</td>
						<td>
							<textarea name="rssdesc" class="textinput" style="width:300px; height:100px;">'.$FSXL[config][news_rssdesc].'</textarea>
						</td>
					</tr>
';

if (in_array($_SESSION[user]->userid, $FSXL[superadmin]))
{
	$FSXL[content] .= '
					<tr>
						<td colspan="2">
							<span style="font-size:12pt"><br><b>'.$FS_PHRASES[news_config_vbulletin].'</b></span><hr>
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[news_config_vbselect].':</b><br>'.$FS_PHRASES[news_config_vbselect_sub].'</td>
						<td>
							<input type="checkbox" name="vbselect" '.($FSXL[config][news_vbselect] == 1 ? "checked" : "").'>
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[news_config_vbprefix].':</b><br>'.$FS_PHRASES[news_config_vbprefix_sub].'</td>
						<td>
							<input name="vbprefix" class="textinput" style="width:300px;" value="'.$FSXL[config][vb_prefix].'">
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[news_config_vburl].':</b><br>'.$FS_PHRASES[news_config_vburl_sub].'</td>
						<td>
							<input name="vburl" class="textinput" style="width:300px;" value="'.$FSXL[config][vb_url].'">
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[news_config_vbuser].':</b><br>'.$FS_PHRASES[news_config_vbuser_sub].'</td>
						<td>
							<input name="vbuser" class="textinput" style="width:300px;" value="'.$FSXL[config][vb_user].'">
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[news_config_vbpassword].':</b><br>'.$FS_PHRASES[news_config_vbpassword_sub].'</td>
						<td>
							<input name="vbpassword" class="textinput" style="width:300px;" value="'.$FSXL[config][vb_password].'">
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[news_config_vbforum].':</b><br>'.$FS_PHRASES[news_config_vbforum_sub].'</td>
						<td>
							<input name="vbforum" class="textinput" style="width:50px;" value="'.$FSXL[config][vb_forum].'">
						</td>
					</tr>
	';
}

$FSXL[content] .= '
					<tr>
						<td colspan="2" align="right"><input type="submit" class="button" value="'.$FS_PHRASES[global_takeover].'"></td>
					</tr>
				</table>
				</form>
';

?>