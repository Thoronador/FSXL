<?php

$FSXL[title] = 'Unstrip MysqlDB';

switch($_GET[table])
{
	case 'article':
		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_article`");
		while ($article = mysql_fetch_assoc($index))
		{
			$title = mysql_real_escape_string(stripslashes($article[titel]));
			$text = mysql_real_escape_string(stripslashes($article[text]));
			mysql_query("UPDATE `$FSXL[tableset]_article` SET `titel` = '$title', `text` = '$text' WHERE `id` = $article[id]");
		}
		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_article_cat`");
		while ($cat = mysql_fetch_assoc($index))
		{
			$name = mysql_real_escape_string(stripslashes($cat[name]));
			$text = mysql_real_escape_string(stripslashes($cat[text]));
			mysql_query("UPDATE `$FSXL[tableset]_article_cat` SET `name` = '$name', `text` = '$text' WHERE `id` = $cat[id]");
		}
		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_article_pages`");
		while ($page = mysql_fetch_assoc($index))
		{
			$text = mysql_real_escape_string(stripslashes($page[text]));
			mysql_query("UPDATE `$FSXL[tableset]_article_pages` SET `text` = '$text' WHERE `id` = $page[id]");
		}
		$FSXL[content] .= 'Artikel wurden gestripped<p>';
		break;

	case 'main':
		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_config`");
		while ($config = mysql_fetch_assoc($index))
		{
			$name = mysql_real_escape_string(stripslashes($config[name]));
			$value = mysql_real_escape_string(stripslashes($config[value]));
			mysql_query("UPDATE `$FSXL[tableset]_config` SET `name` = '$name', `value` = '$value' WHERE `name` = $name");
		}
		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_images`");
		while ($img = mysql_fetch_assoc($index))
		{
			$title = mysql_real_escape_string(stripslashes($img[title]));
			$filename = mysql_real_escape_string(stripslashes($img[filename]));
			mysql_query("UPDATE `$FSXL[tableset]_images` SET `title` = '$title', `filename` = '$filename' WHERE `id` = $img[id]");
		}
		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_imgcat`");
		while ($cat = mysql_fetch_assoc($index))
		{
			$name = mysql_real_escape_string(stripslashes($cat[name]));
			mysql_query("UPDATE `$FSXL[tableset]_imgcat` SET `name` = '$name' WHERE `id` = $cat[id]");
		}
		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_smilies`");
		while ($smilie = mysql_fetch_assoc($index))
		{
			$code = mysql_real_escape_string(stripslashes($smilie[code]));
			mysql_query("UPDATE `$FSXL[tableset]_smilies` SET `code` = '$code' WHERE `id` = $smilie[id]");
		}
		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_styles`");
		while ($style = mysql_fetch_assoc($index))
		{
			$name = mysql_real_escape_string(stripslashes($style[name]));
			mysql_query("UPDATE `$FSXL[tableset]_styles` SET `name` = '$name' WHERE `id` = $style[id]");
		}
		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_templates`");
		while ($template = mysql_fetch_assoc($index))
		{
			$name = mysql_real_escape_string(stripslashes($template[name]));
			$code = mysql_real_escape_string(stripslashes($template[code]));
			mysql_query("UPDATE `$FSXL[tableset]_templates` SET `name` = '$name', `code` = '$code' WHERE `id` = $template[id]");
		}
		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_template_history`");
		while ($template = mysql_fetch_assoc($index))
		{
			$code = mysql_real_escape_string(stripslashes($template[code]));
			mysql_query("UPDATE `$FSXL[tableset]_template_history` SET `code` = '$code' WHERE `id` = $template[id]");
		}
		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_tplvars_code`");
		while ($tplvar = mysql_fetch_assoc($index))
		{
			$code = mysql_real_escape_string(stripslashes($tplvar[code]));
			mysql_query("UPDATE `$FSXL[tableset]_tplvars_code` SET `code` = '$code' WHERE `id` = $tplvar[id]");
		}
		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_user`");
		while ($userdat = mysql_fetch_assoc($index))
		{
			$name = mysql_real_escape_string(stripslashes($userdat[name]));
			mysql_query("UPDATE `$FSXL[tableset]_user` SET `name` = '$name' WHERE `id` = $userdat[id]");
		}
		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_userdata`");
		while ($userdat = mysql_fetch_assoc($index))
		{
			$email = mysql_real_escape_string(stripslashes($userdat[email]));
			$homepage = mysql_real_escape_string(stripslashes($userdat[homepage]));
			$icq = mysql_real_escape_string(stripslashes($userdat[icq]));
			$msn = mysql_real_escape_string(stripslashes($userdat[msn]));
			mysql_query("UPDATE `$FSXL[tableset]_user` SET `email` = '$email', `homepage` = '$homepage', `icq` = '$icq', `msn` = '$msn' WHERE `userid` = $userdat[userid]");
		}
		$FSXL[content] .= 'Main wurden gestripped<p>';
		break;

	case 'dl':
		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_dl`");
		while ($dl = mysql_fetch_assoc($index))
		{
			$name = mysql_real_escape_string(stripslashes($dl[name]));
			$text = mysql_real_escape_string(stripslashes($dl[text]));
			$autor = mysql_real_escape_string(stripslashes($dl[autor]));
			mysql_query("UPDATE `$FSXL[tableset]_dl` SET `name` = '$name', `text` = '$text', `autor` = '$autor' WHERE `id` = $dl[id]");
		}
		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_dl_cat`");
		while ($cat = mysql_fetch_assoc($index))
		{
			$name = mysql_real_escape_string(stripslashes($cat[name]));
			$desc = mysql_real_escape_string(stripslashes($cat[desc]));
			mysql_query("UPDATE `$FSXL[tableset]_dl_cat` SET `name` = '$name', `desc` = '$desc' WHERE `id` = $cat[id]");
		}
		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_dl_links`");
		while ($link = mysql_fetch_assoc($index))
		{
			$name = mysql_real_escape_string(stripslashes($link[name]));
			$url = mysql_real_escape_string(stripslashes($link[url]));
			mysql_query("UPDATE `$FSXL[tableset]_dl_links` SET `name` = '$name', `url` = '$url' WHERE `id` = $link[id]");
		}
		$FSXL[content] .= 'Downloads wurden gestripped<p>';
		break;

	case 'gallery':
		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_galleries`");
		while ($gallery = mysql_fetch_assoc($index))
		{
			$name = mysql_real_escape_string(stripslashes($gallery[name]));
			$text = mysql_real_escape_string(stripslashes($gallery[text]));
			mysql_query("UPDATE `$FSXL[tableset]_galleries` SET `name` = '$name', `text` = '$text' WHERE `id` = $gallery[id]");
		}
		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_gallerypics`");
		while ($pic = mysql_fetch_assoc($index))
		{
			$titel = mysql_real_escape_string(stripslashes($pic[titel]));
			$text = mysql_real_escape_string(stripslashes($pic[text]));
			mysql_query("UPDATE `$FSXL[tableset]_gallerypics` SET `titel` = '$titel', `text` = '$text' WHERE `id` = $pic[id]");
		}
		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_gallery_cat`");
		while ($cat = mysql_fetch_assoc($index))
		{
			$name = mysql_real_escape_string(stripslashes($cat[name]));
			$text = mysql_real_escape_string(stripslashes($cat[text]));
			mysql_query("UPDATE `$FSXL[tableset]_gallery_cat` SET `name` = '$name', `text` = '$text' WHERE `id` = $cat[id]");
		}
		$FSXL[content] .= 'Galerie wurden gestripped<p>';
		break;

	case 'news':
		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_news`");
		while ($news = mysql_fetch_assoc($index))
		{
			$titel = mysql_real_escape_string(stripslashes($news[titel]));
			$text = mysql_real_escape_string(stripslashes($news[text]));
			mysql_query("UPDATE `$FSXL[tableset]_news` SET `titel` = '$titel', `text` = '$text' WHERE `id` = $news[id]");
		}
		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_news_cat`");
		while ($cat = mysql_fetch_assoc($index))
		{
			$name = mysql_real_escape_string(stripslashes($cat[name]));
			mysql_query("UPDATE `$FSXL[tableset]_news_cat` SET `name` = '$name' WHERE `id` = $cat[id]");
		}
		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_news_comments`");
		while ($comment = mysql_fetch_assoc($index))
		{
			$text = mysql_real_escape_string(stripslashes($comment[text]));
			mysql_query("UPDATE `$FSXL[tableset]_news_comments` SET `text` = '$text' WHERE `id` = $comment[id]");
		}
		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_news_links`");
		while ($link = mysql_fetch_assoc($index))
		{
			$name = mysql_real_escape_string(stripslashes($link[name]));
			$url = mysql_real_escape_string(stripslashes($link[url]));
			mysql_query("UPDATE `$FSXL[tableset]_news_links` SET `name` = '$name', `url` = '$url' WHERE `id` = $link[id]");
		}
		$FSXL[content] .= 'News wurden gestripped<p>';
		break;

	case 'poll':
		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_poll`");
		while ($poll = mysql_fetch_assoc($index))
		{
			$question = mysql_real_escape_string(stripslashes($poll[question]));
			mysql_query("UPDATE `$FSXL[tableset]_poll` SET `question` = '$question' WHERE `id` = $poll[id]");
		}
		$index = mysql_query("SELECT * FROM `$FSXL[tableset]_poll_answers`");
		while ($answers = mysql_fetch_assoc($index))
		{
			$answer = mysql_real_escape_string(stripslashes($answers[answer]));
			mysql_query("UPDATE `$FSXL[tableset]_poll_answers` SET `answer` = '$answer' WHERE `id` = $answers[id]");
		}
		$FSXL[content] .= 'Umfragen wurden gestripped<p>';
		break;
}

$FSXL[content] .= '
			<a href="?mod=main&go=unstripdb&table=article">- Artikel</a><br>
			<a href="?mod=main&go=unstripdb&table=main">- Main</a><br>
			<a href="?mod=main&go=unstripdb&table=dl">- Downloads</a><br>
			<a href="?mod=main&go=unstripdb&table=gallery">- Galerie</a><br>
			<a href="?mod=main&go=unstripdb&table=news">- News</a><br>
			<a href="?mod=main&go=unstripdb&table=poll">- Umfragen</a><br>
';

?>
