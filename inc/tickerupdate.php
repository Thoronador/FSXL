<?php

if ($FSXL[config][ticker_lastupdate] < $FSXL[time] - $FSXL[config][ticker_interval])
{
	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `ticker_lastupdate` = '$FSXL[time]'");
	$index = @mysql_query("SELECT `id`, `rss` FROM `$FSXL[tableset]_ticker` WHERE `active` = 1 AND `rss` != ''");
	
	while ($ticker = mysql_fetch_assoc($index))
	{
		if ($xml = @simplexml_load_file($ticker[rss]))
		{
			foreach($xml->channel[0]->item AS $item)
			{
				$description = mysql_real_escape_string(utf8_decode($item->description));
				$date = strtotime($item->pubDate);
				@mysql_query("INSERT INTO `$FSXL[tableset]_ticker_text` (`id`, `ticker`, `text`, `date`)
								VALUES (NULL, '$ticker[id]', '$description', '$date')");
			}
		}
	}
}

?>