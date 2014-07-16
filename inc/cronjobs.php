<?php

if ($FSXL[config][crontime] <= $FSXL[time]-600)
{
	@include ('admin/mod_news/lang_'.$FSXL[config][syslanguage].'.php');
	
	// Zeit neu setzen
	mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = '$FSXL[time]' WHERE `name` = 'crontime'");

	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_cronjobs`");
	while ($cronjob = mysql_fetch_assoc($index))
	{
		if ($cronjob[date] <= $FSXL[time])
		{
			// News im Forum posten
			if (preg_match("/newsforumpost->([0-9]*)/", $cronjob[order], $match))
			{
				$index2 = mysql_query("SELECT * FROM `$FSXL[tableset]_news` WHERE `id` = '$match[1]'");
				if (mysql_num_rows($index2) > 0)
				{
					$news = mysql_fetch_assoc($index2);
					if ($news[postid] == 0)
					{
						$index2 = mysql_query("SELECT * FROM `$FSXL[tableset]_news_cat` WHERE `id` = '$news[catid]'");
						$cat = mysql_fetch_assoc($index2);
						$forumid = $cat[forumid] > 0 ? $cat[forumid] : $FSXL[config][vb_forum];
						$vbtext = vBText($news[text]);
						$vbtitle = $FSXL[config][vb_prefix] . ' ' . $news[titel];
						$url = $FSXL[config][siteurl] . '/?section=newsdetail&id='.$news[id];
						$vbtext .= "\r\n\r\n[url=$url]$FS_PHRASES[news_add_vb_homepage][/url]";
						
						$vb = new vbConnect($FSXL[config][vb_url], $FSXL[config][vb_user], $FSXL[config][vb_password]);
						$vbComments = $news[comments]==1?false:true;
						$postid = $vb->newThread($forumid, $vbtitle, $vbtext, $vbComments);
						
						if ($postid) {
							mysql_query("UPDATE `$FSXL[tableset]_news` SET `vbnews` = 1, `postid` = '$postid' WHERE `id` = '$match[1]'");
						}
					}
					else
					{
						mysql_query("DELETE FROM `$FSXL[tableset]_cronjobs` WHERE `id` = '$cronjob[id]'");
					}
				}
			}
			// Galerien aktualisieren
			if (preg_match("/updategalleries/", $cronjob[order], $match))
			{
				$index2 = mysql_query("SELECT `id` FROM `$FSXL[tableset]_galleries`");
				while ($gallery = mysql_fetch_assoc($index2))
				{
					$index3 = mysql_query("SELECT COUNT(`id`) AS `value` FROM `$FSXL[tableset]_gallerypics` 
											WHERE `galleryid` = '$gallery[id]' AND `release` < '$FSXL[time]'");
					$numpics = mysql_fetch_assoc($index3);
					mysql_query("UPDATE `$FSXL[tableset]_galleries` SET `pics` = '$numpics[value]' WHERE `id` = $gallery[id]");
				}
				
				// Zeit aktualisieren (30 min)
				mysql_query("UPDATE `$FSXL[tableset]_cronjobs` SET `date` = $FSXL[time]+1800 WHERE `id` = '$cronjob[id]'");
			}
		}
	}
}

?>