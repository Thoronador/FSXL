<?php

$FSXL[title] = $FS_PHRASES[shoplt_add_title];
$FSXL[content] = '';

if ($_POST[name] && $_POST[url] && $_POST[price])
{
	settype($_POST[cat], 'integer');
	$_POST[shortcut] = $_POST[shortcut] ? 1 : 0;

	mysql_query("INSERT INTO `$FSXL[tableset]_shoplt` (`id`, `name`, `cat`, `url`, `text`, `price`, `shortcut`)
			VALUES (NULL, '$_POST[name]', $_POST[cat], '$_POST[url]', '$_POST[text]', '$_POST[price]', $_POST[shortcut])");
	$id = mysql_insert_id();

	if ($_FILES[pic])
	{
		$img = new imgConvert();
		if ($img->readIMG($_FILES[pic]))
		{
			$img->saveIMG('../images/shoplt/', $id, 'jpg');

			$img->scaleIMG($FSXL[config][shoplt_thumbx], $FSXL[config][shoplt_thumby], 'RESIZE', '000000');
			$img->saveIMG('../images/shoplt/', $id.'s', 'jpg');
		}
	}

	$FSXL[content] = '<div style="padding:20px; text-align:center;">'.$FS_PHRASES[shoplt_add_added].'</div>';
}


// Übersicht
else
{
	$FSXL[content] .= '
				<div>
				<form action="?mod=shoplt&go=addarticle" method="post" name="shopform" enctype="multipart/form-data" onSubmit="return chkShopltAddForm()">
				<input type="hidden" name="action" value="newcat">
				<table border="0" cellpadding="2" cellspacing="1" width="90%" style="margin:0px auto;">
					<tr>
						<td><b>'.$FS_PHRASES[shoplt_add_name].':</b></td>
						<td align="right">
							<div style="width:406px;" align="left"><input class="textinput" name="name" style="width:300px;"></div>
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
									<option value="'.$cat[id].'">'.$cat[name].'</option>
		';
	}

	$FSXL[content] .= '
								</select>
							</div>
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[shoplt_add_pic].':</b></td>
						<td align="right">
							<div style="width:406px;" align="left"><input class="textinput" name="pic" type="file" style="width:400px;"></div>
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[shoplt_add_link].':</b></td>
						<td align="right">
							<div style="width:406px;" align="left"><input class="textinput" name="url" style="width:400px;"></div>
						</td>
					</tr>
					<tr>
						<td valign="top"><b>'.$FS_PHRASES[shoplt_cats_text].':</b></td>
						<td align="right"><textarea class="textinput" name="text" style="width:400px; height:100px;"></textarea></td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[shoplt_add_price].':</b></td>
						<td align="right">
							<div style="width:406px;" align="left"><input class="textinput" name="price" style="width:100px;"></div>
						</td>
					</tr>
					<tr>
						<td><b>'.$FS_PHRASES[shoplt_add_shortcut].':</b></td>
						<td align="right">
							<div style="width:406px;" align="left"><input type="checkbox" name="shortcut"></div>
						</td>
					</tr>
					<tr>
						<td colspan="2" align="right"><input type="submit" class="button" value="'.$FS_PHRASES[global_send].'"></td>
					</tr>
				</table>
				</div>

	';
}

?>