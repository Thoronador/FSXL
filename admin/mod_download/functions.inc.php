<?php

function dl_create_admin_overview($start, $deep, $extended=false)
{
	global $FSXL, $db, $i, $FS_PHRASES;
	$deep++;
	$data = "";

	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_dl_cat` WHERE `parentid` = $start ORDER BY `name`");
	while ($cat = mysql_fetch_assoc($index))
	{
		$i++;
		$index2 = mysql_query("SELECT `id` FROM `$FSXL[tableset]_dl` WHERE `catid` = $cat[id] ORDER BY `name`");
		if (mysql_num_rows($index2) > 0) {
			$pic = "2";
		}
		else {
			$pic = "";
		}

		// Mit Dateien
		if ($extended)
		{
			$data .= '
					<tr>
						<td class="alt'.($i%2==0?1:2).'" style="padding-left:'.($deep*40).'px;" colspan="2">
							<img border="0" src="mod_download/'.$FSXL[style].'_folder'.$pic.'.png" alt="" style="float:left; margin-right:8px;">
							<b>'.$cat[name].'</b><br>
							'.$cat[desc].'
						</td>
					</tr>
			';

			$index2 = mysql_query("SELECT `id`, `name`, `active` FROM `$FSXL[tableset]_dl` WHERE `catid` = $cat[id] ORDER BY `name`");
			while ($file = mysql_fetch_assoc($index2))
			{
				$yellow = $file[active]==0 ? 'background-color:#EEEE55;' : '';
				$i++;
				$data .= '
					<tr>
						<td class="alt'.($i%2==0?1:2).'" style="padding-left:'.($deep*40+40).'px; '.$yellow.'">
							<a href="?mod=download&go=editdl&id='.$file[id].'">
								<img border="0" src="mod_download/file.png" alt="" width="16" height="16" style="margin-bottom:-5px;">
								'.$file[name].'
							</a>
						</td>
						<td class="alt'.($i%2==0?1:2).'" align="center" style="'.$yellow.'"><a href="../index.php?section=download&id='.$file[id].'" target="_blank"><img border="0" src="images/'.$FSXL[style].'_link.gif" alt=""></a></td>
					</tr>
				';
			}
		}

		// Ohne Dateien
		else
		{
			$data .= '
					<tr>
						<td class="alt'.($i%2==0?1:2).'" style="padding-left:'.($deep*40).'px;">
							<a href="?mod=download&go=cats&edit='.$cat[id].'">
							<img border="0" src="mod_download/'.$FSXL[style].'_folder'.$pic.'.png" alt="" style="float:left; margin-right:8px;">
							<b>'.$cat[name].'</b><br>
							'.$cat[desc].'
							</a>
						</td>
						<td align="center" class="alt'.($i%2==0?1:2).'"><a href="?mod=download&go=cats&newfolder='.$cat[id].'">'.$FS_PHRASES[download_cats_addfolder].'</a></td>
						<td class="alt'.($i%2==0?1:2).'" align="center"><a href="../index.php?section=download&folder='.$cat[id].'" target="_blank"><img border="0" src="images/'.$FSXL[style].'_link.gif" alt=""></a></td>
					</tr>
			';
		}

		$data .= dl_create_admin_overview($cat[id], $deep, $extended);
	}

	return $data;
}


function dl_create_optionlist($start, $deep, $select, $exclude)
{
	global $FSXL, $db;
	$deep++;
	$data = "";

	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_dl_cat` WHERE `parentid`= $start ORDER BY `name`");
	while ($cat = mysql_fetch_assoc($index))
	{
		if ($cat[id] != $exclude)
		{
			$deepstring = "";
			for ($i=0; $i<$deep; $i++)
			{
				$deepstring .= "&nbsp;&nbsp;&nbsp;&nbsp;";
			}

			$data .= '
				<option value="'.$cat[id].'" '.($select == $cat[id] ? "selected" : "").'>'.$deepstring . $cat[name].'</option>
			';

			$data .= dl_create_optionlist($cat[id], $deep, $select, $exclude);
		}
	}

	return $data;
}

?>