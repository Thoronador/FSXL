<?php

$FSXL[title] = $FS_PHRASES[shoplt_edit_title];
$FSXL[content] = '';

if ($_POST[editid] && $_POST[name] && $_POST[url] && $_POST[price])
{
	settype($_POST[editid], 'integer');
	if ($_POST[del])
	{
		mysql_query("DELETE FROM `$FSXL[tableset]_shoplt` WHERE `id` = $_POST[editid]");
		if(file_exists('../images/shoplt/'.$_POST[editid].'s.jpg'))
		{
			unlink('../images/shoplt/'.$_POST[editid].'s.jpg');
			unlink('../images/shoplt/'.$_POST[editid].'.jpg');
		}

		$FSXL[content] = '
					<div style="padding:20px; text-align:center;">'.$FS_PHRASES[shoplt_edit_deleted].'</div>
		';
	}
	else
	{
		settype($_POST[cat], 'integer');
		$_POST[shortcut] = $_POST[shortcut] ? 1 : 0;

		mysql_query("UPDATE `$FSXL[tableset]_shoplt` SET `name` = '$_POST[name]',
								`cat` = $_POST[cat], 
								`url` = '$_POST[url]', 
								`text` = '$_POST[text]', 
								`price` = '$_POST[price]', 
								`shortcut` = $_POST[shortcut] WHERE `id` = $_POST[editid]");

		if($_FILES[pic])
		{
			$img = new imgConvert();
			if ($img->readIMG($_FILES[pic]))
			{
				$img->saveIMG('../images/shoplt/', $_POST[editid], 'jpg');

				$img->scaleIMG($FSXL[config][shoplt_thumbx], $FSXL[config][shoplt_thumby], 'RESIZE', '000000');
				$img->saveIMG('../images/shoplt/', $_POST[editid].'s', 'jpg');
			}
		}

		$FSXL[content] = '<div style="padding:20px; text-align:center;">'.$FS_PHRASES[shoplt_edit_done].'</div>';
	}
}


// Übersicht
elseif ($_GET[id])
{
	settype($_GET[id],'integer');
	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_shoplt` WHERE `id` = $_GET[id]");
	$article = mysql_fetch_assoc($index);

	$FSXL[content] .= '
				<div>
				<form action="?mod=shoplt&go=editarticle" method="post" name="shopform" enctype="multipart/form-data" onSubmit="return chkShopltEditForm()">
				<input type="hidden" name="action" value="editcat">
				<input type="hidden" name="editid" value="'.$article[id].'">
				<table border="0" cellpadding="2" cellspacing="1" width="90%" style="margin:0px auto;">
					<tr>
						<td><b>'.$FS_PHRASES[shoplt_add_name].':</b></td>
						<td align="right">
							<div style="width:406px;" align="left"><input class="textinput" value="'.$article[name].'" name="name" style="width:300px;"></div>
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[shoplt_add_cat].':</b></td>
						<td align="right">
							<div style="width:406px;" align="left">
								<select class="textinput" name="cat" style="width:300px;">
									<option value="0">'.$FS_PHRASES[shoplt_cats_blank].'</option>
									<option value="0">---------------------------</option>
	';

	// Kategorien auflisten
	$index = mysql_query("SELECT * FROM `$FSXL[tableset]_shoplt_cat` ORDER BY `name`");
	while ($cat = mysql_fetch_assoc($index))
	{
		$FSXL[content] .= '
									<option value="'.$cat[id].'" '.($cat[id] == $article[cat] ? "selected" : "").'>'.$cat[name].'</option>
		';
	}

	$FSXL[content] .= '
								</select>
							</div>
						</td>
					</tr>
	';

	if(file_exists('../images/shoplt/'.$article[id].'s.jpg'))
	{
		$FSXL[content] .= '
					<tr>
						<td valign="top"><b>'.$FS_PHRASES[shoplt_add_pic].':</b></td>
						<td align="right">
							<div style="width:406px;" align="left"><img border="0" src="../images/shoplt/'.$article[id].'s.jpg" alt=""></div>
						</td>
					</tr>
		';
	}

	$FSXL[content] .= '
					<tr>
						<td><b>'.$FS_PHRASES[shoplt_edit_newpic].':</b></td>
						<td align="right">
							<div style="width:406px;" align="left"><input class="textinput" name="pic" type="file" style="width:400px;"></div>
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[shoplt_add_link].':</b></td>
						<td align="right">
							<div style="width:406px;" align="left"><input class="textinput" value="'.$article[url].'" name="url" style="width:400px;"></div>
						</td>
					</tr>
					<tr>
						<td valign="top"><b>'.$FS_PHRASES[shoplt_cats_text].':</b></td>
						<td align="right"><textarea class="textinput" name="text" style="width:400px; height:100px;">'.$article[text].'</textarea></td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[shoplt_add_price].':</b></td>
						<td align="right">
							<div style="width:406px;" align="left"><input class="textinput" value="'.$article[price].'" name="price" style="width:100px;"></div>
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[shoplt_add_shortcut].':</b></td>
						<td align="right">
							<div style="width:406px;" align="left"><input type="checkbox" name="shortcut" '.($article[shortcut] == 1 ? "checked" : "").'></div>
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[shoplt_cats_delete].':</b></td>
						<td>
							<input type="checkbox" name="del" id="delete" onClick="delMessage(\''.$FS_PHRASES[shoplt_edit_delmessage].'\');">
						</td>
					</tr>
					<tr>
						<td colspan="2" align="right"><input type="submit" class="button" value="'.$FS_PHRASES[global_send].'"></td>
					</tr>
				</table>
				</div>

	';
}

else
{
	$FSXL[content] .= '
				<table border="0" cellpadding="2" cellspacing="1" width="90%" style="margin:0px auto;">
					<tr>
						<td colspan="3" style="padding:0px;"><span style="font-size:12pt;"><b>'.$FS_PHRASES[shoplt_edit_select].'</b></span><hr></td>
					</tr>
					<tr>
						<td class="alt0"><b>'.$FS_PHRASES[shoplt_add_name].'</b></td>
						<td class="alt0" align="center"><b>'.$FS_PHRASES[shoplt_add_price].'</b></td>
						<td class="alt0" align="center"><b>'.$FS_PHRASES[shoplt_edit_link].'</b></td>
					</tr>
	';

	$index = mysql_query("SELECT `id`, `name`, `cat`, `price` FROM `$FSXL[tableset]_shoplt` ORDER BY `cat`, `name`");
	$currentcat = '';
	while ($shop = mysql_fetch_assoc($index))
	{
		$i++;
		if ($currentcat != $shop[cat])
		{
			$currentcat = $shop[cat];
			if($currentcat == 0)
			{
				$cat[name] = $FS_PHRASES[shoplt_edit_nocat];
			}
			else
			{
				$index2 = mysql_query("SELECT `id`, `name` FROM `$FSXL[tableset]_shoplt_cat` WHERE `id` = $shop[cat]");
				$cat = mysql_fetch_assoc($index2);
			}
			$FSXL[content] .= '
					<tr>
						<td class="alt'.($i%2==0?1:2).'" colspan="3"><b>'.$cat[name].'</b></td>
					</tr>
			';
			$i++;
		}

		$FSXL[content] .= '
					<tr>
						<td class="alt'.($i%2==0?1:2).'" style="padding-left:20px;"><a href="?mod=shoplt&go=editarticle&id='.$shop[id].'">'.$shop[name].'</a></td>
						<td class="alt'.($i%2==0?1:2).'" align="center">'.$shop[price].'</td>
						<td align="center" class="alt'.($i%2==0?1:2).'"><a href="../index.php?section=shoplt&cat='.$cat[id].'" target="_blank"><img border="0" src="images/'.$FSXL[style].'_link.gif" alt=""></a></td>
					</tr>
		';
	}

	$FSXL[content] .= '
				</table>
				</div>

	';
}

?>