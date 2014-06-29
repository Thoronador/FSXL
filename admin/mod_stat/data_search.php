<?php

$FSXL[title] = $FS_PHRASES[stat_search_title];

$FSXL[content] .= '
				<table border="0" cellpadding="2" cellspacing="1" align="center" width="90%" style="margin:0px auto;">
					<tr>
						<td class="alt0"><b>'.$FS_PHRASES[stat_search_keyword].'</b></td>
						<td class="alt0" align="center"><a href="?mod=stat&go=search&order=hits" style="color:#FFFFFF;"><b>'.$FS_PHRASES[stat_search_hits].'</b></a></td>
						<td class="alt0" align="center"><a href="?mod=stat&go=search&order=date" style="color:#FFFFFF;"><b>'.$FS_PHRASES[stat_search_last].'</b></a></td>
					</tr>
';

	if (!$_GET[order] || $_GET[order] == 'date')
		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_search_words` ORDER BY `date` DESC LIMIT 100");
	else
		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_search_words` ORDER BY `hits` DESC LIMIT 100");

	$i=0;
	while ($word = mysql_fetch_assoc($index))
	{
		$FSXL[content] .= '
					<tr>
						<td class="alt'.($i%2 == 0 ? "1" : "2").'">'.$word[word].'</td>
						<td align="center" class="alt'.($i%2 == 0 ? "1" : "2").'">'.$word[hits].'</td>
						<td align="center" class="alt'.($i%2 == 0 ? "1" : "2").'">'.date($FSXL[config][dateformat], $word[date]).'</td>
					</tr>
		';
		$i++;
	}

	$FSXL[content] .= '
				</table>
	';

?>