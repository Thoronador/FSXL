-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb2+deb7u1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 19. Jul 2014 um 17:28
-- Server Version: 5.5.37
-- PHP-Version: 5.4.4-14+deb7u12

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `db_frogsystem`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fsxl_article`
--

CREATE TABLE IF NOT EXISTS `fsxl_article` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `titel` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `datum` int(11) NOT NULL,
  `short` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `autor` int(11) NOT NULL,
  `text` text COLLATE latin1_general_ci NOT NULL,
  `type` tinyint(4) NOT NULL,
  `cat` int(11) NOT NULL,
  `zoneid` int(11) NOT NULL,
  `showuser` tinyint(4) NOT NULL,
  `invisible` tinyint(4) NOT NULL,
  `regonly` tinyint(4) NOT NULL,
  `pages` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `short` (`short`),
  KEY `datum` (`datum`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=2 ;

--
-- Daten für Tabelle `fsxl_article`
--

INSERT INTO `fsxl_article` (`id`, `titel`, `datum`, `short`, `autor`, `text`, `type`, `cat`, `zoneid`, `showuser`, `invisible`, `regonly`, `pages`) VALUES
(1, 'Testartikel', 1283687880, NULL, 1, 'Seite1', 1, 0, 0, 0, 0, 0, 3);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fsxl_articleconnect`
--

CREATE TABLE IF NOT EXISTS `fsxl_articleconnect` (
  `word` int(11) NOT NULL DEFAULT '0',
  `article` int(11) NOT NULL DEFAULT '0',
  UNIQUE KEY `word` (`word`,`article`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Daten für Tabelle `fsxl_articleconnect`
--

INSERT INTO `fsxl_articleconnect` (`word`, `article`) VALUES
(11, 1),
(12, 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fsxl_article_cat`
--

CREATE TABLE IF NOT EXISTS `fsxl_article_cat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `text` text COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fsxl_article_pages`
--

CREATE TABLE IF NOT EXISTS `fsxl_article_pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `article` int(11) NOT NULL,
  `text` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Daten für Tabelle `fsxl_article_pages`
--

INSERT INTO `fsxl_article_pages` (`id`, `article`, `text`) VALUES
(1, 1, 'Seite 2'),
(2, 1, 'Seite 3');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fsxl_config`
--

CREATE TABLE IF NOT EXISTS `fsxl_config` (
  `name` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `value` varchar(255) COLLATE latin1_general_ci NOT NULL,
  UNIQUE KEY `unique` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Daten für Tabelle `fsxl_config`
--

INSERT INTO `fsxl_config` (`name`, `value`) VALUES
('syslanguage', 'german'),
('use_tplvar_cache', '1'),
('stdstyle', '1'),
('user_select_style', '0'),
('news_rssnum', '6'),
('pagetitle', 'Frogsystem XL'),
('defaultzone', '1'),
('showzonename', '1'),
('startpage', ''),
('news_guestcomments', '0'),
('news_perpage', '10'),
('kontaktmail', 'frogsystem.xl@frogspawn.de'),
('news_spamtime', '60'),
('news_spamfilter', ''),
('news_commentsperpage', '30'),
('news_headlines', '6'),
('dateformat', 'd.m.Y H:i'),
('article_headlines', '6'),
('article_previewlength', '250'),
('gallery_headlines', '6'),
('gallery_potmsingle', '1'),
('download_headlines', '6'),
('search_maxwords', '3'),
('search_time', '10'),
('search_previewlength', '250'),
('shoplt_thumbx', '160'),
('shoplt_thumby', '120'),
('ticker_interval', '10'),
('admin_show_config', '1'),
('gallery_colors', 'Schwarz,000000\r\nWeiß,FFFFFF'),
('video_color', '00FF00'),
('video_showplay', '1'),
('video_showstop', '1'),
('video_showseek', '1'),
('video_showtime', '1'),
('video_showvolbar', '1'),
('video_showmute', '1'),
('video_showfullscreen', '1'),
('news_rssdesc', ''),
('news_rsslen', '350'),
('showregonly', '1'),
('gallery_thumbx', '160'),
('gallery_thumby', '120'),
('gallery_cols', '3'),
('dl_prefix', 'http://ftp.frogspawn.de/downloads/'),
('use_tpl_cache', '1'),
('news_comment_order', '2'),
('tpl_history_steps', '3'),
('tagcloud_words', '20'),
('tagcloud_minsize', '8'),
('tagcloud_maxsize', '24'),
('use_safe_links', '1'),
('version', '1.0.7'),
('login_attempts', '5'),
('login_blocktime', '15'),
('admin_cookielogin', '1'),
('bez', 'fsxl'),
('timed_xsize', '160'),
('timed_ysize', '120'),
('timed_color', '000000'),
('contest_thumbx', '160'),
('contest_thumby', '120'),
('contest_thumbcolor', '000000'),
('contest_preview', '250'),
('submitnews', '1'),
('newssubmitmail', '1'),
('jobmail', '1'),
('systemmail', 'frogsystem.xl@frogspawn.de'),
('ticker_lastupdate', '0'),
('link_headlines', '6'),
('fscodes', '1'),
('article_showall', '1'),
('gallery_showall', '1'),
('vb_url', ''),
('vb_user', ''),
('vb_password', ''),
('vb_forum', '0'),
('news_selectcomments', '1'),
('news_vbselect', '0'),
('crontime', '1405783003'),
('contacttojob', '1'),
('vb_prefix', '[NEWS]'),
('video_showall', '1'),
('counter_savetime', '2'),
('contest_perpage', '25'),
('shoplt_order', '1'),
('ageratings', '16>22,18>24'),
('siteurl', '');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fsxl_contests`
--

CREATE TABLE IF NOT EXISTS `fsxl_contests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `startdate` int(11) NOT NULL,
  `enddate` int(11) NOT NULL,
  `text` text NOT NULL,
  `type` smallint(6) NOT NULL,
  `secret` tinyint(4) NOT NULL,
  `multiple` tinyint(4) NOT NULL,
  `analysis` smallint(6) NOT NULL,
  `votedate` int(11) NOT NULL,
  `winner` int(11) NOT NULL,
  `done` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fsxl_contest_entries`
--

CREATE TABLE IF NOT EXISTS `fsxl_contest_entries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contest` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `user` int(11) NOT NULL,
  `date` int(11) NOT NULL,
  `text` text NOT NULL,
  `active` tinyint(4) NOT NULL,
  `ip` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fsxl_contest_votes`
--

CREATE TABLE IF NOT EXISTS `fsxl_contest_votes` (
  `contest` int(11) NOT NULL,
  `entry` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `points` int(11) NOT NULL,
  UNIQUE KEY `entry` (`entry`,`user`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fsxl_contest_winner`
--

CREATE TABLE IF NOT EXISTS `fsxl_contest_winner` (
  `contest` int(11) NOT NULL,
  `position` int(11) NOT NULL,
  `entry` int(11) NOT NULL,
  UNIQUE KEY `contest` (`contest`,`position`,`entry`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fsxl_counter_article`
--

CREATE TABLE IF NOT EXISTS `fsxl_counter_article` (
  `year` int(11) NOT NULL,
  `month` int(11) NOT NULL,
  `day` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `hits` int(10) unsigned NOT NULL,
  UNIQUE KEY `year` (`year`,`month`,`day`,`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Daten für Tabelle `fsxl_counter_article`
--

INSERT INTO `fsxl_counter_article` (`year`, `month`, `day`, `id`, `hits`) VALUES
(2010, 9, 5, 1, 6),
(2014, 7, 18, 1, 3);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fsxl_counter_bots`
--

CREATE TABLE IF NOT EXISTS `fsxl_counter_bots` (
  `date` int(11) NOT NULL,
  `startdate` int(11) NOT NULL,
  `ip` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `agent` varchar(255) COLLATE latin1_general_ci NOT NULL,
  UNIQUE KEY `date` (`date`,`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fsxl_counter_gallery`
--

CREATE TABLE IF NOT EXISTS `fsxl_counter_gallery` (
  `year` int(11) NOT NULL,
  `month` int(11) NOT NULL,
  `day` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `hits` int(10) unsigned NOT NULL,
  UNIQUE KEY `year` (`year`,`month`,`day`,`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Daten für Tabelle `fsxl_counter_gallery`
--

INSERT INTO `fsxl_counter_gallery` (`year`, `month`, `day`, `id`, `hits`) VALUES
(2010, 9, 5, 1, 11);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fsxl_counter_news`
--

CREATE TABLE IF NOT EXISTS `fsxl_counter_news` (
  `year` int(11) NOT NULL,
  `month` int(11) NOT NULL,
  `day` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `hits` int(11) unsigned NOT NULL,
  UNIQUE KEY `year` (`year`,`month`,`day`,`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Daten für Tabelle `fsxl_counter_news`
--

INSERT INTO `fsxl_counter_news` (`year`, `month`, `day`, `id`, `hits`) VALUES
(2014, 7, 19, 1, 2);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fsxl_counter_stat`
--

CREATE TABLE IF NOT EXISTS `fsxl_counter_stat` (
  `year` int(4) DEFAULT NULL,
  `month` int(2) DEFAULT NULL,
  `day` int(2) DEFAULT NULL,
  `visits` int(11) DEFAULT NULL,
  `hits` int(11) DEFAULT NULL,
  UNIQUE KEY `year` (`year`,`month`,`day`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Daten für Tabelle `fsxl_counter_stat`
--

INSERT INTO `fsxl_counter_stat` (`year`, `month`, `day`, `visits`, `hits`) VALUES
(2009, 10, 18, 0, 0),
(2009, 10, 24, 1, 1),
(2010, 4, 2, 1, 17),
(2010, 4, 3, 1, 11),
(2010, 4, 4, 1, 126),
(2010, 4, 5, 0, 0),
(2010, 4, 7, 1, 11),
(2010, 4, 9, 0, 0),
(2010, 9, 5, 1, 107),
(2014, 7, 18, 2, 18),
(2014, 7, 19, 1, 5);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fsxl_counter_user`
--

CREATE TABLE IF NOT EXISTS `fsxl_counter_user` (
  `date` int(11) NOT NULL,
  `startdate` int(11) NOT NULL,
  `enddate` int(11) NOT NULL,
  `views` int(11) NOT NULL,
  `referer` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `ip` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `agent` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `lang` varchar(255) COLLATE latin1_general_ci NOT NULL,
  UNIQUE KEY `date` (`date`,`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Daten für Tabelle `fsxl_counter_user`
--

INSERT INTO `fsxl_counter_user` (`date`, `startdate`, `enddate`, `views`, `referer`, `ip`, `agent`, `lang`) VALUES
(1255816800, 1255857244, 1255857276, 2, 'http://frogsystem.net/admin/?mod=gallery&go=pics&galleryid=1', '127.0.0.1', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.1.3) Gecko/20090824 Firefox/3.5.3 (.NET CLR 3.5.30729)', 'de-de,de;q=0.8,en-us;q=0.5,en;q=0.3'),
(1256335200, 1256384754, 1256386025, 2, '', '127.0.0.1', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.1.3) Gecko/20090824 Firefox/3.5.3 (.NET CLR 3.5.30729)', 'de-de,de;q=0.8,en-us;q=0.5,en;q=0.3'),
(1270159200, 1270208653, 1270216391, 17, '', '127.0.0.1', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.1.6) Gecko/20091201 Firefox/3.5.6 (.NET CLR 3.5.30729)', 'de-de,de;q=0.8,en-us;q=0.5,en;q=0.3'),
(1270245600, 1270304299, 1270305074, 11, '', '127.0.0.1', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.1.9) Gecko/20100315 Firefox/3.5.9 (.NET CLR 3.5.30729)', 'de-de,de;q=0.8,en-us;q=0.5,en;q=0.3'),
(1270332000, 1270371861, 1270386793, 126, '', '127.0.0.1', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.1.9) Gecko/20100315 Firefox/3.5.9 (.NET CLR 3.5.30729)', 'de-de,de;q=0.8,en-us;q=0.5,en;q=0.3'),
(1270418400, 1270466487, 1270466487, 1, '', '127.0.0.1', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.1.9) Gecko/20100315 Firefox/3.5.9 (.NET CLR 3.5.30729)', 'de-de,de;q=0.8,en-us;q=0.5,en;q=0.3'),
(1270591200, 1270658392, 1270659344, 12, '', '127.0.0.1', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.1.9) Gecko/20100315 Firefox/3.5.9 (.NET CLR 3.5.30729)', 'de-de,de;q=0.8,en-us;q=0.5,en;q=0.3'),
(1270764000, 1270833551, 1270833551, 1, '', '127.0.0.1', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.1.9) Gecko/20100315 Firefox/3.5.9 (.NET CLR 3.5.30729)', 'de-de,de;q=0.8,en-us;q=0.5,en;q=0.3'),
(1283637600, 1283673879, 1283688993, 108, '', '127.0.0.1', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.8) Gecko/20100722 Firefox/3.6.8 ( .NET CLR 3.5.30729)', 'de-de,de;q=0.8,en-us;q=0.5,en;q=0.3');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fsxl_cronjobs`
--

CREATE TABLE IF NOT EXISTS `fsxl_cronjobs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` int(11) NOT NULL,
  `order` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Daten für Tabelle `fsxl_cronjobs`
--

INSERT INTO `fsxl_cronjobs` (`id`, `date`, `order`) VALUES
(2, 1405784803, 'updategalleries');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fsxl_dl`
--

CREATE TABLE IF NOT EXISTS `fsxl_dl` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `catid` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `text` text COLLATE latin1_general_ci NOT NULL,
  `views` int(10) unsigned NOT NULL,
  `autor` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `date` int(11) NOT NULL,
  `regonly` tinyint(4) NOT NULL,
  `active` tinyint(4) NOT NULL,
  `autor_url` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `age` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `date` (`date`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=3 ;

--
-- Daten für Tabelle `fsxl_dl`
--

INSERT INTO `fsxl_dl` (`id`, `catid`, `name`, `text`, `views`, `autor`, `date`, `regonly`, `active`, `autor_url`, `age`) VALUES
(2, 0, 'Testdownload', 'Das ist ein DOwnload', 18, 'Hans Wurst', 1270214160, 0, 1, 'hans@wurst.de', 16);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fsxl_dl_cat`
--

CREATE TABLE IF NOT EXISTS `fsxl_dl_cat` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parentid` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `desc` varchar(255) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fsxl_dl_links`
--

CREATE TABLE IF NOT EXISTS `fsxl_dl_links` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dlid` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `url` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `count` int(11) NOT NULL,
  `target` tinyint(4) NOT NULL,
  `size` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=2 ;

--
-- Daten für Tabelle `fsxl_dl_links`
--

INSERT INTO `fsxl_dl_links` (`id`, `dlid`, `name`, `url`, `count`, `target`, `size`) VALUES
(1, 2, 'Name', 'Url', 0, 1, 1234);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fsxl_downloadconnect`
--

CREATE TABLE IF NOT EXISTS `fsxl_downloadconnect` (
  `word` int(11) NOT NULL,
  `article` int(11) NOT NULL,
  UNIQUE KEY `word` (`word`,`article`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Daten für Tabelle `fsxl_downloadconnect`
--

INSERT INTO `fsxl_downloadconnect` (`word`, `article`) VALUES
(3, 2),
(4, 2);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fsxl_forms`
--

CREATE TABLE IF NOT EXISTS `fsxl_forms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `desc` text NOT NULL,
  `start` int(11) NOT NULL,
  `end` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Daten für Tabelle `fsxl_forms`
--

INSERT INTO `fsxl_forms` (`id`, `title`, `desc`, `start`, `end`) VALUES
(1, 'Testfragebogen', 'Dieser Fragenbogen diehnt alleine Testzwecken, um herauszufinden, wie alles funktioniert.', 1270372920, 1270977720);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fsxl_form_fields`
--

CREATE TABLE IF NOT EXISTS `fsxl_form_fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `form` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `pos` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Daten für Tabelle `fsxl_form_fields`
--

INSERT INTO `fsxl_form_fields` (`id`, `form`, `type`, `title`, `text`, `pos`) VALUES
(2, 1, 1, 'Wer setzte den ersten Post ins Forum?', 'Don-Esteban/boundary/meditate/boundary/blutfeuer/boundary/stressi/boundary/this->[HW]Deathweaver/boundary//boundary//boundary//boundary//boundary/', 2),
(3, 1, 1, 'Wann wurde der erste Post ins Forum gesetzt?', '1998/boundary/1999/boundary/this->2000 am 5.6.2000/boundary/2001/boundary/2002/boundary//boundary//boundary//boundary//boundary/', 3),
(4, 1, 2, 'Das ist ein trenner!', '', 1),
(6, 1, 3, 'Was ist dein Hobby', '', 4);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fsxl_form_results`
--

CREATE TABLE IF NOT EXISTS `fsxl_form_results` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `form` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `mail` varchar(255) NOT NULL,
  `ip` varchar(255) NOT NULL,
  `date` int(11) NOT NULL,
  `correct` tinyint(4) NOT NULL,
  `result` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `form` (`form`,`mail`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Daten für Tabelle `fsxl_form_results`
--

INSERT INTO `fsxl_form_results` (`id`, `form`, `name`, `mail`, `ip`, `date`, `correct`, `result`) VALUES
(2, 1, 'Kemrit', 'mail@frogspawn.de', '127.0.0.1', 1270386793, 0, 'id2=>0/boundary/id3=>2'),
(3, 1, 'Hans', 'hans@wurst.de', '127.0.0.1', 1270659056, 0, 'id2=>2/boundary/id3=>3/boundary/id6=>Radeln'),
(4, 1, 'Peter', 'Peter@pan.de', '127.0.0.1', 1270659344, 1, 'id2=>4/boundary/id3=>2/boundary/id6=>');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fsxl_fscodes`
--

CREATE TABLE IF NOT EXISTS `fsxl_fscodes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `code` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fsxl_galleries`
--

CREATE TABLE IF NOT EXISTS `fsxl_galleries` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `text` text COLLATE latin1_general_ci NOT NULL,
  `datum` int(11) NOT NULL,
  `thumbx` smallint(6) NOT NULL,
  `thumby` smallint(6) NOT NULL,
  `color` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `cols` smallint(6) NOT NULL,
  `type` tinyint(4) NOT NULL,
  `cat` int(11) NOT NULL,
  `zoneid` int(11) NOT NULL,
  `regonly` tinyint(4) NOT NULL,
  `pics` int(11) NOT NULL,
  `age` tinyint(4) NOT NULL DEFAULT '0',
  `hidden` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `datum` (`datum`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=2 ;

--
-- Daten für Tabelle `fsxl_galleries`
--

INSERT INTO `fsxl_galleries` (`id`, `name`, `text`, `datum`, `thumbx`, `thumby`, `color`, `cols`, `type`, `cat`, `zoneid`, `regonly`, `pics`, `age`, `hidden`) VALUES
(1, 'Testgalerie', '', 1270208640, 160, 120, '000000', 3, 1, 0, 0, 0, 0, 17, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fsxl_gallerypics`
--

CREATE TABLE IF NOT EXISTS `fsxl_gallerypics` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `galleryid` int(10) unsigned NOT NULL,
  `titel` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `text` text COLLATE latin1_general_ci NOT NULL,
  `position` int(11) NOT NULL,
  `date` int(11) NOT NULL,
  `hits` int(11) NOT NULL,
  `release` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `position` (`position`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fsxl_gallery_cat`
--

CREATE TABLE IF NOT EXISTS `fsxl_gallery_cat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `text` text COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fsxl_gallery_potm`
--

CREATE TABLE IF NOT EXISTS `fsxl_gallery_potm` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `gallery` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fsxl_gallery_timed`
--

CREATE TABLE IF NOT EXISTS `fsxl_gallery_timed` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titel` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `date` int(11) NOT NULL,
  `startdate` int(11) NOT NULL,
  `enddate` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fsxl_images`
--

CREATE TABLE IF NOT EXISTS `fsxl_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cat` int(11) NOT NULL DEFAULT '0',
  `title` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `filename` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `height` int(11) NOT NULL,
  `width` int(11) NOT NULL,
  `size` int(11) NOT NULL,
  `type` tinyint(4) NOT NULL COMMENT '1=jpg,2=png,3=gif',
  `date` int(11) NOT NULL,
  `lastmod` int(11) NOT NULL,
  `autor` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fsxl_imgcat`
--

CREATE TABLE IF NOT EXISTS `fsxl_imgcat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `pics` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=2 ;

--
-- Daten für Tabelle `fsxl_imgcat`
--

INSERT INTO `fsxl_imgcat` (`id`, `name`, `pics`) VALUES
(1, 'default', 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fsxl_jobs`
--

CREATE TABLE IF NOT EXISTS `fsxl_jobs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `desc` text NOT NULL,
  `date` int(11) NOT NULL,
  `autor` int(11) NOT NULL,
  `edate` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `state` tinyint(1) NOT NULL,
  `cdate` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Daten für Tabelle `fsxl_jobs`
--

INSERT INTO `fsxl_jobs` (`id`, `name`, `desc`, `date`, `autor`, `edate`, `user`, `state`, `cdate`) VALUES
(1, 'Testjob', 'Blubber', 1270210172, 1, 0, 0, 1, 0),
(2, 'Kontaktanfrage (Test)', 'Name:\r\nHans\r\n\r\nBetreff:\r\nTest\r\n\r\nEMail:\r\nmail@frogspawn.de\r\n\r\nNachricht:\r\nDas ist ein Test\r\n\r\nkermit@frogspawn.de\r\n\r\n\r\n', 1270210236, 1, 0, 0, 1, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fsxl_link`
--

CREATE TABLE IF NOT EXISTS `fsxl_link` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cat` int(11) NOT NULL,
  `subcat` int(11) NOT NULL,
  `date` int(11) NOT NULL,
  `name` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `url` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `text` text COLLATE latin1_general_ci NOT NULL,
  `tag` varchar(255) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fsxl_link_cat`
--

CREATE TABLE IF NOT EXISTS `fsxl_link_cat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `text` text COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fsxl_link_subcat`
--

CREATE TABLE IF NOT EXISTS `fsxl_link_subcat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `tag` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fsxl_logins`
--

CREATE TABLE IF NOT EXISTS `fsxl_logins` (
  `ip` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `date` int(11) NOT NULL,
  `trys` int(11) NOT NULL,
  UNIQUE KEY `ip` (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fsxl_mod`
--

CREATE TABLE IF NOT EXISTS `fsxl_mod` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `aktiv` tinyint(1) NOT NULL,
  `position` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1006 ;

--
-- Daten für Tabelle `fsxl_mod`
--

INSERT INTO `fsxl_mod` (`id`, `name`, `aktiv`, `position`) VALUES
(1, 'main', 1, 1),
(2, 'news', 1, 2),
(3, 'article', 1, 3),
(4, 'gallery', 1, 4),
(5, 'download', 1, 5),
(6, 'poll', 1, 6),
(7, 'stat', 1, 12),
(8, 'shoplt', 1, 7),
(9, 'ticker', 1, 8),
(10, 'video', 1, 9),
(11, 'link', 1, 11),
(12, 'contest', 1, 10),
(1005, 'form', 1, 10);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fsxl_news`
--

CREATE TABLE IF NOT EXISTS `fsxl_news` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `catid` int(11) NOT NULL,
  `zoneid` int(11) NOT NULL,
  `titel` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `datum` int(11) NOT NULL,
  `autor` int(11) NOT NULL,
  `text` text COLLATE latin1_general_ci NOT NULL,
  `type` tinyint(4) NOT NULL,
  `comments` tinyint(4) NOT NULL,
  `numcomments` int(11) NOT NULL,
  `vbnews` tinyint(11) NOT NULL,
  `postid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `datum` (`datum`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=3 ;

--
-- Daten für Tabelle `fsxl_news`
--

INSERT INTO `fsxl_news` (`id`, `catid`, `zoneid`, `titel`, `datum`, `autor`, `text`, `type`, `comments`, `numcomments`, `vbnews`, `postid`) VALUES
(1, 1, 0, 'Willkommen im Frogsystem XL', 1251539280, 1, 'Willkommen im Frogsystem XL\r\n\r\n[age=16]Der Text hier wird verschlüsselt[/age]', 1, 1, 0, 0, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fsxl_newsconnect`
--

CREATE TABLE IF NOT EXISTS `fsxl_newsconnect` (
  `word` int(11) NOT NULL DEFAULT '0',
  `article` int(11) NOT NULL DEFAULT '0',
  UNIQUE KEY `word` (`word`,`article`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Daten für Tabelle `fsxl_newsconnect`
--

INSERT INTO `fsxl_newsconnect` (`word`, `article`) VALUES
(1, 1),
(2, 1),
(6, 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fsxl_newstozone`
--

CREATE TABLE IF NOT EXISTS `fsxl_newstozone` (
  `newsid` int(11) NOT NULL,
  `zoneid` int(11) NOT NULL,
  `catid` int(11) NOT NULL,
  `date` int(11) NOT NULL,
  KEY `date` (`date`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `fsxl_newstozone`
--

INSERT INTO `fsxl_newstozone` (`newsid`, `zoneid`, `catid`, `date`) VALUES
(1, 1, 1, 1251539280),
(1, 0, 0, 1251539280);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fsxl_news_cat`
--

CREATE TABLE IF NOT EXISTS `fsxl_news_cat` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `forumid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=2 ;

--
-- Daten für Tabelle `fsxl_news_cat`
--

INSERT INTO `fsxl_news_cat` (`id`, `name`, `forumid`) VALUES
(1, 'Allgemein', 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fsxl_news_catconnect`
--

CREATE TABLE IF NOT EXISTS `fsxl_news_catconnect` (
  `catid` int(11) NOT NULL,
  `zoneid` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Daten für Tabelle `fsxl_news_catconnect`
--

INSERT INTO `fsxl_news_catconnect` (`catid`, `zoneid`) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fsxl_news_comments`
--

CREATE TABLE IF NOT EXISTS `fsxl_news_comments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `newsid` int(10) unsigned NOT NULL,
  `userid` int(10) unsigned NOT NULL,
  `datum` int(11) NOT NULL,
  `text` text COLLATE latin1_general_ci NOT NULL,
  `num` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fsxl_news_links`
--

CREATE TABLE IF NOT EXISTS `fsxl_news_links` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `newsid` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `url` mediumtext COLLATE latin1_general_ci NOT NULL,
  `type` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fsxl_news_spamblock`
--

CREATE TABLE IF NOT EXISTS `fsxl_news_spamblock` (
  `ip` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `date` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fsxl_news_submit`
--

CREATE TABLE IF NOT EXISTS `fsxl_news_submit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `source` varchar(255) NOT NULL,
  `date` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `ip` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fsxl_poll`
--

CREATE TABLE IF NOT EXISTS `fsxl_poll` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question` text COLLATE latin1_general_ci NOT NULL,
  `startdate` int(11) NOT NULL,
  `enddate` int(11) NOT NULL,
  `multiselect` tinyint(4) NOT NULL,
  `useronly` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fsxl_polltozone`
--

CREATE TABLE IF NOT EXISTS `fsxl_polltozone` (
  `pollid` int(11) NOT NULL,
  `zoneid` int(11) NOT NULL,
  UNIQUE KEY `pollid` (`pollid`,`zoneid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fsxl_poll_answers`
--

CREATE TABLE IF NOT EXISTS `fsxl_poll_answers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `poll` int(11) NOT NULL,
  `answer` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `position` int(11) NOT NULL,
  `hits` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fsxl_poll_iplist`
--

CREATE TABLE IF NOT EXISTS `fsxl_poll_iplist` (
  `poll` int(11) NOT NULL,
  `ip` varchar(255) COLLATE latin1_general_ci NOT NULL,
  UNIQUE KEY `poll` (`poll`,`ip`),
  KEY `ip` (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fsxl_poll_userlist`
--

CREATE TABLE IF NOT EXISTS `fsxl_poll_userlist` (
  `poll` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  UNIQUE KEY `poll` (`poll`,`user`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fsxl_rewritemap`
--

CREATE TABLE IF NOT EXISTS `fsxl_rewritemap` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from` varchar(255) NOT NULL,
  `to` varchar(255) NOT NULL,
  `zone` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQUE` (`from`,`zone`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fsxl_search_iplist`
--

CREATE TABLE IF NOT EXISTS `fsxl_search_iplist` (
  `ip` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `date` int(11) NOT NULL,
  UNIQUE KEY `ip` (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fsxl_search_words`
--

CREATE TABLE IF NOT EXISTS `fsxl_search_words` (
  `word` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `hits` int(11) NOT NULL,
  `date` int(11) NOT NULL,
  UNIQUE KEY `word` (`word`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fsxl_shoplt`
--

CREATE TABLE IF NOT EXISTS `fsxl_shoplt` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `cat` int(11) NOT NULL,
  `url` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `text` text COLLATE latin1_general_ci NOT NULL,
  `price` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `shortcut` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fsxl_shoplt_cat`
--

CREATE TABLE IF NOT EXISTS `fsxl_shoplt_cat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `text` text COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fsxl_smilies`
--

CREATE TABLE IF NOT EXISTS `fsxl_smilies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=16 ;

--
-- Daten für Tabelle `fsxl_smilies`
--

INSERT INTO `fsxl_smilies` (`id`, `code`) VALUES
(7, ':)'),
(6, ':D'),
(8, ':p'),
(9, ':rolleyes:'),
(10, ':o'),
(11, ':mad:'),
(12, ':('),
(13, ':eek:'),
(14, ':cool:'),
(15, ';)');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fsxl_styles`
--

CREATE TABLE IF NOT EXISTS `fsxl_styles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=12 ;

--
-- Daten für Tabelle `fsxl_styles`
--

INSERT INTO `fsxl_styles` (`id`, `name`) VALUES
(1, 'FrogGreen');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fsxl_templates`
--

CREATE TABLE IF NOT EXISTS `fsxl_templates` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shortcut` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `name` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `code` text COLLATE latin1_general_ci NOT NULL,
  `styleid` int(11) NOT NULL,
  `mod` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique` (`shortcut`,`styleid`),
  KEY `shortcut` (`shortcut`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=421 ;

--
-- Daten für Tabelle `fsxl_templates`
--

INSERT INTO `fsxl_templates` (`id`, `shortcut`, `name`, `code`, `styleid`, `mod`) VALUES
(1, 'css', 'CSS Anweisungen', 'body\r\n{\r\n	margin:0px;\r\n	background-color:#FFFFFF;\r\n	background-image:url(images/styles/froggreen/green_bg.jpg);\r\n	background-repeat:repeat-x;\r\n	color:#017801;\r\n	font-family:Arial;\r\n	font-size:8pt;\r\n}\r\npre\r\n{\r\n	font-family:Arial;\r\n}\r\n#header\r\n{\r\n	position:absolute;\r\n	background-image:url(images/styles/froggreen/green_headerbg.jpg);\r\n	background-repeat:repeat-x;\r\n	width:100%;\r\n	height:100px;\r\n	top:0px;\r\n	left:0px;\r\n}\r\n#topmenu\r\n{\r\n	position:absolute;\r\n	width:100%;\r\n	height:23px;\r\n	background-image:url(images/styles/froggreen/green_topmenubg.gif);\r\n	background-repeat:repeat-x;\r\n	padding-top:5px;\r\n	top:100px;\r\n}\r\n#container\r\n{\r\n	position:absolute;\r\n	top:128px;\r\n}\r\n.menucat\r\n{\r\n	width:170px;\r\n	height:20px;\r\n	background-image:url(images/styles/froggreen/green_menucat.jpg);\r\n	color:#FFFFFF;\r\n	padding-left:10px;\r\n	padding-top:5px;\r\n}\r\n.menulogin\r\n{\r\n	width:168px;\r\n	background-image:url(images/styles/froggreen/green_loginbg.gif);\r\n	background-repeat:repeat-x;\r\n	background-color:#D7FFD7;\r\n	padding-top:3px;\r\n	padding-left:10px;\r\n	border-left:1px solid #95FE95;\r\n	border-right:1px solid #95FE95;\r\n	border-bottom:1px solid #95FE95;\r\n}\r\n.menubox\r\n{\r\n	width:178px;\r\n	background-image:url(images/styles/froggreen/green_loginbg.gif);\r\n	background-repeat:repeat-x;\r\n	background-color:#D7FFD7;\r\n	padding-top:3px;\r\n	padding-bottom:3px;\r\n	border-left:1px solid #95FE95;\r\n	border-right:1px solid #95FE95;\r\n	border-bottom:1px solid #95FE95;\r\n}\r\n.menuitem\r\n{\r\n	width:170px;\r\n	height:17px;\r\n	background-image:url(images/styles/froggreen/green_menuitem.gif);\r\n	padding-top:3px;\r\n	padding-left:10px;\r\n}\r\n.button\r\n{\r\n	color:#017801;\r\n	background-color:#FFFFFF;\r\n	border-top:1px solid #DBDBDB;\r\n	border-left:1px solid #DBDBDB;\r\n	border-right:1px solid #808080;\r\n	border-bottom:1px solid #808080;\r\n	font-size:8pt;\r\n	padding:2px;\r\n}\r\n.editorbutton\r\n{\r\n	background-color:#D7FFD7;\r\n	border:1px solid #017801;\r\n	margin-right:2px;\r\n	float:left;\r\n	width:24px;\r\n	height:24px;\r\n}\r\n.textinput\r\n{\r\n	color:#017801;\r\n	background-color:#FFFFFF;\r\n	border-top:1px solid #808080;\r\n	border-left:1px solid #808080;\r\n	border-right:1px solid #DBDBDB;\r\n	border-bottom:1px solid #DBDBDB;\r\n	font-size:8pt;\r\n	padding:2px;\r\n	font-family:Arial;\r\n}\r\n.topmenuitem\r\n{\r\n	width:80px;\r\n	text-align:center;\r\n	float:left;\r\n}\r\n.trenner\r\n{\r\n	width:2px;\r\n	height:16px;\r\n	background-image:url(images/styles/froggreen/green_trenner.gif);\r\n	float:left;\r\n}\r\nform\r\n{\r\n	display:inline;\r\n}\r\n#contentheader\r\n{\r\n	height:25px;\r\n	background-image:url(images/styles/froggreen/green_contentbg.jpg);\r\n}\r\n#contentlogo\r\n{\r\n	height:20px;\r\n	background-image:url(images/styles/froggreen/green_contentheader.jpg);\r\n	background-repeat:no-repeat;\r\n	color:#FFFFFF;\r\n	padding-left:15px;\r\n	padding-right:15px;\r\n	padding-top:5px;\r\n}\r\n#contentwindow\r\n{\r\n	background-color:#D7FFD7;\r\n	border-left:1px solid #95FE95;\r\n	border-right:1px solid #95FE95;\r\n	border-bottom:1px solid #95FE95;\r\n	background-image:url(images/styles/froggreen/green_contentbg.gif);\r\n	background-repeat:repeat-x;\r\n	margin-bottom:20px;\r\n	padding:5px;\r\n}\r\na\r\n{\r\n	color:#017801;\r\n	text-decoration:none;\r\n}\r\n.thead\r\n{\r\n	background-image:url(images/styles/froggreen/green_contentbg.jpg);\r\n	padding-left:10px;\r\n	color:#FFFFFF;\r\n	height:25px;\r\n	white-space:nowrap;\r\n}\r\n.alt1\r\n{\r\n	padding-left:10px;\r\n	background-color:#C5F2C5;\r\n}\r\n.alt2\r\n{\r\n	padding-left:10px;\r\n	background-color:#BBE9BB;\r\n}', 1, 1),
(2, 'header', 'Header', '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">\r\n<html>\r\n<head>\r\n	<title>{pagetitle}</title>\r\n	<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">\r\n	<meta name="description" content="Das Frogsystem XL ist ein CMS speziell für Community Seiten">\r\n	<meta name="author" content="Kermit">\r\n	<meta name="keywords" content="CMS, Community, Frogsystem">\r\n	<meta name="date" content="2008-08-01T08:00:00+02:00">\r\n	<link rel="stylesheet" type="text/css" href="{css}">\r\n	<link rel="alternate" type="application/rss+xml" title="News-Feed" href="/rss2.xml_1.php">\r\n</head>\r\n<body>\r\n	<div id="header">\r\n		<img border="0" src="images/styles/froggreen/green_logo.jpg" alt="">\r\n        </div>\r\n	<div id="topmenu">\r\n		<div class="topmenuitem"><a href="./?zone=1">Home</a></div>\r\n		<div class="trenner"></div>\r\n	<-- if user_isadmin -->\r\n		<div class="topmenuitem"><a href="/admin/">Admin</a></div>\r\n		<div class="trenner"></div>\r\n	<-- /if user_isadmin -->\r\n	<-- if user_loggedin -->\r\n		<div class="topmenuitem" style="float:right;"><a href="logout.php">Logout</a></div>\r\n		<div class="trenner" style="float:right;"></div>\r\n		<div class="topmenuitem" style="float:right;"><a href="?section=profile">Profil</a></div>\r\n	<-- else user_loggedin -->\r\n		<div class="topmenuitem" style="float:right;"><a href="?section=register">Registrieren</a></div>\r\n	<-- /if user_loggedin -->\r\n	</div>\r\n	<table border="0" cellpadding="0" cellspacing="0" width="100%" id="container">\r\n		<tr>\r\n			<td style="padding-left:10px; padding-top:15px; width:190px;" valign="top">\r\n				<div class="menucat">ALLGEMEIN</div>\r\n				<div class="menuitem"><a href="?section=news">News</a></div>\r\n				<div class="menuitem"><a href="?section=newsarchiv">Newsarchiv</a></div>\r\n				<div class="menuitem"><a href="?section=submitnews">News einsenden</a></div>\r\n				<div class="menuitem"><a href="?section=article">Artikel</a></div>\r\n				<div class="menuitem"><a href="?section=gallery">Galerien</a></div>\r\n				<div class="menuitem"><a href="?section=download">Downloads</a></div>\r\n				<div class="menuitem"><a href="?section=pollarchiv">Umfragen Archiv</a></div>\r\n				<div class="menuitem"><a href="?section=shoplt">Shop</a></div>\r\n				<div class="menuitem"><a href="?section=ticker">LiveTicker Archiv</a></div>\r\n				<div class="menuitem"><a href="?section=video">Videos</a></div>\r\n				<div class="menuitem"><a href="?section=links">Links</a></div>\r\n				<div class="menuitem"><a href="?section=contact">Kontakt</a></div>\r\n				<p>\r\n				{search}\r\n			</td>\r\n			<td style="padding-top:15px;" valign="top">\r\n	<-- if home -->\r\n		<table border="0" cellpadding="0" cellspacing="0" width="100%">\r\n			<tr>\r\n				<td width="50%" style="padding-right:5px;" valign="top">{headlines}</td>\r\n				<td width="50%" valign="top">{articleheadlines}</td>\r\n			</tr>\r\n			<tr>\r\n				<td width="50%" style="padding-right:5px;" valign="top">{galleryheadlines}</td>\r\n				<td width="50%" valign="top">{downloadheadlines}</td>\r\n			</tr>\r\n			<tr>\r\n				<td width="50%" style="padding-right:5px;" valign="top">{linkheadlines}</td>\r\n				<td width="50%" valign="top"></td>\r\n			</tr>\r\n		</table>\r\n	<-- /if home -->', 1, 1),
(346, 'newssubmit', 'News einsenden Formular', '<script type="text/javascript">\r\n	function chkSubmitForm()\r\n	{\r\n		if (document.getElementById("s_title").value != "" && \r\n			document.getElementById("s_text").value != "" &&\r\n			document.getElementById("s_source").value != "")\r\n		{\r\n			return true;\r\n		}\r\n		else\r\n		{\r\n			alert("Bitte fülle alle mit einem * gekennzeichneten Felder aus");\r\n			return false;\r\n		}\r\n	}\r\n</script>\r\n<div id="contentheader"><div id="contentlogo">NEWS EINSENDEN</div></div>\r\n<div id="contentwindow">\r\n<-- if user_loggedin -->\r\n	<form action="?section=submitnews" method="post" onSubmit="return chkSubmitForm()">\r\n	<input type="hidden" name="action" value="submit">\r\n	<table border="0" cellpadding="2" cellspacing="0" style="margin:0px auto; width:500px;">\r\n		<tr>\r\n			<td><b>Titel*:</b></td>\r\n			<td><input name="title" id="s_title" class="textinput" style="width:300px;"></td>\r\n		</tr>\r\n		<tr style="visibility:collapse;">\r\n			<td><b>EMail*:</b></td>\r\n			<td><input name="email" id="s_email" class="textinput" style="width:300px;"></td>\r\n		</tr>\r\n		<tr>\r\n			<td valign="top"><b>Nachricht*:</b></td>\r\n			<td><textarea name="text" id="s_text" class="textinput" style="width:500px; height:150px;"></textarea></td>\r\n		</tr>\r\n		<tr>\r\n			<td><b>Quelle*:</b></td>\r\n			<td><input name="source" id="s_source" class="textinput" style="width:500px;"></td>\r\n		</tr>\r\n		<tr>\r\n			<td colspan="2">\r\n				 <br>\r\n				<input type="submit" class="button" value="Abschicken" style="float:right;">\r\n			</td>\r\n		</tr>\r\n	</table>\r\n	</form>\r\n<-- else user_loggedin -->\r\n	<div style="text-align:center; padding:20px;">\r\n		Du musst angemeldet sein, um eine News einzusenden.\r\n	</div>\r\n<-- /if user_loggedin -->\r\n</div>\r\n', 1, 2),
(3, 'footer', 'Footer', '			</td>\r\n			<td style="padding-left:10px; padding-top:15px; width:190px;" valign="top">\r\n				{loginform}\r\n				{potm}\r\n				{poll}\r\n				{shoplt}\r\n				{ticker}\r\n				{tagcloud}\r\n				{stat}\r\n			</td>\r\n		</tr>\r\n		<tr>\r\n			<td colspan="3" align="center">{copyright}</td>\r\n		</tr>\r\n	</table>\r\n</body>\r\n</html>', 1, 1),
(4, 'loginform', 'Login Formular', '<-- if user_loggedin -->\r\n<-- else user_loggedin -->\r\n<div class="menucat">LOGIN</div>\r\n<div class="menulogin">\r\n	<form action="" method="post">\r\n		Username:\r\n		<input class="textinput" name="username" style="width:150px;">\r\n		Passwort:\r\n		<input class="textinput" name="userpass" type="password" style="width:150px; margin-bottom:5px;">\r\n		angemeldet bleiben:\r\n		<input type="checkbox" name="staylogged"><br>\r\n		<input type="submit" class="button" value="login" style="width:50px; margin-left:105px;; margin-bottom:5px;">\r\n		<a href="?section=register">Noch nicht registriert? Klicke <u>hier</u></a>\r\n	</form>\r\n</div>\r\n<p>\r\n<-- /if user_loggedin -->', 1, 1),
(24, 'profile', 'Profil Formular', '<div id="contentheader"><div id="contentlogo">PROFIL VON {username}</div></div>\r\n<div id="contentwindow">\r\n	<form action="?section=profile" method="post">\r\n	<input type="hidden" name="action" value="edit">\r\n	<table border="0" cellpadding="2" cellspacing="0" style="margin:0px auto; width:500px;">\r\n		<tr>\r\n			<td colspan="2">\r\n				 <br><b>Profil Informationen:</b><br><hr>\r\n			</td>\r\n		</tr>\r\n		<tr>\r\n			<td><b>Neues Passwort:</b></td>\r\n			<td><input type="password" name="pass" class="textinput" style="width:200px;"></td>\r\n		</tr>\r\n		<tr>\r\n			<td><b>Passwort wiederholen:</b></td>\r\n			<td><input type="password" name="pass2" class="textinput" style="width:200px;"></td>\r\n		</tr>\r\n		<tr>\r\n			<td><b>E-Mail Adresse:</b></td>\r\n			<td><input name="email" class="textinput" style="width:200px;" value="{email}"></td>\r\n		</tr>\r\n	<-- if style_selectable -->\r\n		<tr>\r\n			<td><b>Style:</b></td>\r\n			<td>\r\n				<select name="style" class="textinput" style="width:200px;">\r\n					{styleoptions}\r\n				</select>\r\n			</td>\r\n		</tr>\r\n	<-- /if style_selectable -->\r\n		<tr>\r\n			<td colspan="2">\r\n				 <br><b>Zusätzliche Informationen:</b><br>\r\n				Diese Informationen sind öffentlich einsehbar.<hr>\r\n			</td>\r\n		</tr>\r\n		<tr>\r\n			<td><b>Homepage:</b></td>\r\n			<td><input name="homepage" class="textinput" style="width:200px;" value="{homepage}"></td>\r\n		</tr>\r\n		<tr>\r\n			<td><b>ICQ-Nummer:</b></td>\r\n			<td><input name="icq" class="textinput" style="width:200px;" value="{icq}"></td>\r\n		</tr>\r\n		<tr>\r\n			<td><b>MSN Messenger:</b></td>\r\n			<td><input name="msn" class="textinput" style="width:200px;" value="{msn}"></td>\r\n		</tr>\r\n		<tr>\r\n			<td><b>Registriert seit:</b></td>\r\n			<td>{regdate}</td>\r\n		</tr>\r\n		<tr>\r\n			<td colspan="2">\r\n				 <br>\r\n				<input type="submit" class="button" value="Absenden" style="float:right;">\r\n			</td>\r\n		</tr>\r\n	</table>\r\n	</form>\r\n</div>', 1, 1),
(23, 'regdone', 'Registrierung erfolgreich', '<div id="contentheader"><div id="contentlogo">REGISTRIERUNG</div></div>\r\n<div id="contentwindow" style="text-align:center; padding:20px;">\r\n	Vielen Dank {username}, deine Registrierung war erfolgreich, du kannst \r\n	nun alle Bereiche der Seite nutzen.\r\n</div>\r\n', 1, 1),
(18, 'errormsg', 'Fehlermeldung', '<div id="contentheader"><div id="contentlogo">FEHLER</div></div>\r\n<div id="contentwindow" style="text-align:center; padding:20px;">\r\n	{message}\r\n</div>\r\n', 1, 1),
(19, 'errornotfilled', 'Fehler: Nicht alle Felder ausgefüllt', '<b>Du hast nicht alle Felder korrekt ausgefüllt</b><p>\r\n<input type="button" class="button" value="Zurück" onClick="javascript:history.back();">', 1, 1),
(20, 'errorpassnotmatch', 'Fehler: Passwörter stimmen nicht überein', '<b>Die von dir eingegebenen Passwörter stimmen nicht überein</b><p>\r\n<input type="button" class="button" value="Zurück" onClick="javascript:history.back();">', 1, 1),
(21, 'errorbotdetect', 'Fehler: Bot Verdacht', '<b>Der Vorgang konnte nicht durchgeführt werden, da verdacht auf einen Spambot besteht.<br>Sollte dies nicht der Fall sein, so versuche es bitte noch einmal.</b><p>\r\n<input type="button" class="button" value="Zurück" onClick="javascript:history.back();">', 1, 1),
(22, 'erroruserexists', 'Fehler: User existiert schon', '<b>Der von dir gewähle Benutzername oder die E-Mail Adresse existieren schon.</b><p>\r\n<input type="button" class="button" value="Zurück" onClick="javascript:history.back();">', 1, 1),
(17, 'regform', 'Registrierungs Formular', '<div id="contentheader"><div id="contentlogo">REGISTRIEREN</div></div>\r\n<div id="contentwindow" style="text-align:center;">\r\n	<form action="?section=register" method="post">\r\n	<input type="hidden" name="action" value="reg">\r\n	<input type="hidden" name="formdate" value="{time}">\r\n	<table border="0" cellpadding="2" cellspacing="0" style="text-align:left; width:500px;">\r\n		<tr>\r\n			<td colspan="2">\r\n				 <br><b>Registrierungs Informationen:</b><br>\r\n				Bevor du alle Funktionen auf dieser Seite benutzen kannst musst du dich erst \r\n				registrieren. Bitte gib deine Daten in das folgende Formular ein.<hr>\r\n			</td>\r\n		</tr>\r\n		<tr>\r\n			<td><b>Benutzername:</b></td>\r\n			<td><input name="name" class="textinput" style="width:200px;"></td>\r\n		</tr>\r\n		<tr>\r\n			<td><b>Passwort:</b></td>\r\n			<td><input type="password" name="pass" class="textinput" style="width:200px;"></td>\r\n		</tr>\r\n		<tr>\r\n			<td><b>Passwort wiederholen:</b></td>\r\n			<td><input type="password" name="pass2" class="textinput" style="width:200px;"></td>\r\n		</tr>\r\n		<tr>\r\n			<td><b>E-Mail Adresse:</b></td>\r\n			<td>\r\n				<input name="email" class="textinput" style="width:200px;">\r\n				<input name="myemail" class="textinput" style="width:200px; display:none;">\r\n			</td>\r\n		</tr>\r\n		<tr>\r\n			<td colspan="2">\r\n				 <br><b>Zusätzliche Informationen:</b><br>\r\n				Diese Informationen sind öffentlich einsehbar.<hr>\r\n			</td>\r\n		</tr>\r\n		<tr>\r\n			<td><b>Homepage:</b></td>\r\n			<td><input name="homepage" class="textinput" style="width:200px;"></td>\r\n		</tr>\r\n		<tr>\r\n			<td><b>ICQ-Nummer:</b></td>\r\n			<td><input name="icq" class="textinput" style="width:200px;"></td>\r\n		</tr>\r\n		<tr>\r\n			<td><b>MSN Messenger:</b></td>\r\n			<td><input name="msn" class="textinput" style="width:200px;"></td>\r\n		</tr>\r\n		<tr>\r\n			<td colspan="2">\r\n				 <br>\r\n				<input type="submit" class="button" value="Registrierung abschicken" style="float:right;">\r\n			</td>\r\n		</tr>\r\n	</table>\r\n	</form>\r\n</div>\r\n', 1, 1),
(25, 'profileeditdone', 'Profil wurde editiert', '<div id="contentheader"><div id="contentlogo">PROFIL</div></div>\r\n<div id="contentwindow" style="text-align:center; padding:20px;">\r\n	Dein Profil wurde aktualisiert.\r\n</div>\r\n', 1, 1),
(26, 'news_body', 'News Body', '<div id="contentheader">\r\n	<div id="contentlogo">\r\n		<span style="float:right">{date}</span>\r\n		<a href="{commentlink}" style="color:#FFFFFF;">[{catname}] {title}</a>\r\n	</div>\r\n</div>\r\n<div id="contentwindow" style="text-align:center;">\r\n	<table border="0" cellpadding="2" cellspacing="0" style="text-align:left; width:90%; margin:0px auto;">\r\n		<tr>\r\n			<td>\r\n				<br>{text}\r\n			</td>\r\n		</tr>\r\n	<-- if links -->\r\n		<tr>\r\n			<td>\r\n				<br><b>Links:</b><br><hr>\r\n				<-- link -->\r\n				• <a href="{linkurl}" style="text-decoration:underline;" target="{linktarget}">{linkname}</a><br>\r\n				<-- /link -->\r\n			</td>\r\n		</tr>\r\n	<-- /if links -->\r\n	</table>\r\n	<p>\r\n	<div style="text-align:left; height:12px;">\r\n		<span style="float:right">geschrieben von {username}</span>\r\n	<-- if comments -->\r\n		<-- if vB -->\r\n			<a href="{commentlink}" style="text-decoration:underline;" target="_blank">Kommentare</a>\r\n		<-- else vB -->\r\n			<a href="{commentlink}" style="text-decoration:underline;">Kommentare ({comments})</a>\r\n		<-- /if vB -->\r\n	<-- /if comments -->\r\n	</div>\r\n</div>', 1, 2),
(27, 'article', 'Artikel Body', '<div id="contentheader">\r\n	<div id="contentlogo">\r\n		<span style="float:right">{date}</span>\r\n	<-- if has_cat -->\r\n		[<a href="{caturl}" style="color:#FFFFFF;">{catname}</a>]\r\n	<-- /if has_cat -->\r\n		{title}\r\n	<-- if pages -->\r\n		<i>(Seite: {currentpage})</i>\r\n	<-- /if pages -->\r\n	</div>\r\n</div>\r\n<div id="contentwindow">\r\n	<div style="width:90%; margin:0px auto;">\r\n		<br>{text}\r\n	</div>\r\n	<p>\r\n	<-- if pages -->\r\n		<div style="float:left">\r\n			Seite:\r\n			<-- pagelink -->\r\n				<a href="{pagelink}">[{pagenum}]</a>\r\n			<-- /pagelink -->\r\n		</div>\r\n	<-- /if pages -->\r\n	<div style="text-align:right;">\r\n		<-- if show_user -->\r\n		geschrieben von {username}\r\n		<-- /if show_user -->\r\n	</div>\r\n</div>\r\n', 1, 3),
(28, 'gallery_thumb', 'Galerie-Thumbnail', '<div style="text-align:center;">\r\n	<a href="{detaillink}">\r\n		<img border="0" src="{thumblink}" alt="">\r\n	</a><br>\r\n	{title}\r\n</div>\r\n', 1, 4),
(29, 'gallery', 'Galerie', '<div id="contentheader">\r\n	<div id="contentlogo">\r\n		<div style="float:right;">{date}</div>\r\n		{name} - {numpics} Bilder\r\n	</div>\r\n</div>\r\n<div id="contentwindow" style="text-align:center;">\r\n	<div style="width:90%; margin:0px auto;" align="left">{description}</div><p>\r\n	{thumbs}\r\n</div>', 1, 4),
(30, 'errorfilenotfound', 'Fehler: Datei nicht gefunden', '<b>Es ist ein Fehler aufgetreten. Die angeforderte Datei konnte nicht gefunden werden.</b><p>\r\n<input type="button" class="button" value="Zurück" onClick="javascript:history.back();">', 1, 1),
(31, 'gallery_detail', 'Galerie Detailansicht', '<div id="contentheader">\r\n	<div id="contentlogo">\r\n		<div style="float:right;">{gallerydate}</div>\r\n		<-- if gallery -->\r\n			{galleryname} - \r\n		<-- /if gallery -->\r\n		{title} - ({currentpic}/{totalpics})\r\n	</div>\r\n</div>\r\n<div id="contentwindow" style="text-align:center;">\r\n	<table border="0" cellpadding="0" cellspacing="0" width="95%" align="center" style="table-layout:fixed;">\r\n		<tr>\r\n			<td colspan="2" style="padding-bottom:5px;" align="center">\r\n				<a href="{piclink}" target="_blank">\r\n					<img border="0" src="{piclink}" alt="" style="max-width:100%;">\r\n				</a>\r\n			</td>\r\n		</tr>\r\n		<tr>\r\n			<td colspan="2" align="left">{text}<p></td>\r\n		</tr>\r\n		<tr>\r\n			<td align="left"><a href="{prevlink}" class="button" style="display:block; width:50px; text-align:center;">Zurück</a></td>\r\n			<td align="right"><a href="{nextlink}" class="button" style="display:block; width:50px; text-align:center;">Weiter</a></td>\r\n		</tr>\r\n	<-- if gallery -->\r\n		<tr>\r\n			<td colspan="2" align="center"><a href="{gallerylink}" style="text-decoration:underline;">Zurück zur Übersicht</a></td>\r\n		</tr>\r\n	<-- /if gallery -->\r\n	</table>\r\n</div>', 1, 4),
(44, 'galleryheadlines', 'Neuste Galerien', '<div id="contentheader">\r\n	<div id="contentlogo">\r\n		NEUSTE GALERIEN\r\n	</div>\r\n</div>\r\n<div id="contentwindow">\r\n	<-- gallery -->\r\n	<div><span style="float:right;">{date}</span>• <a href="{url}">{title}</a><br></div>\r\n	<-- /gallery -->\r\n</div>', 1, 4),
(45, 'potm', 'Picture of the Moment', '<div class="menucat">{title}</div>\r\n<div class="menubox" align="center">\r\n	<a href="{img}"><img border="0" src="{thumb}" alt="{picname}" width="140"></a><br>\r\n	{picname}\r\n</div>\r\n<p>', 1, 4),
(32, 'news_detail', 'News Detailansicht', '{news}\r\n<p>\r\n<-- if comments -->\r\n\r\n<-- commentbody -->\r\n<div id="contentheader">\r\n	<div id="contentlogo">\r\n		<span style="float:right;">{commentdate}</span>\r\n		<b>{commentnum}:</b> <-- if commentuser -->{commentautor}<-- else commentuser -->Gast<-- /if commentuser -->\r\n	</div>\r\n</div>\r\n<div id="contentwindow" style="text-align:center;">\r\n	<table border="0" cellpadding="2" cellspacing="0" style="text-align:left; width:90%;">\r\n		<tr>\r\n			<td>{commenttext}</td>\r\n		</tr>\r\n	</table>\r\n</div>\r\n<p>\r\n<-- /commentbody -->\r\n<div align="right">{pageselect}</div>\r\n<p>\r\n<div id="contentheader">\r\n	<div id="contentlogo">\r\n		KOMMENTAR HINZUFÜGEN\r\n	</div>\r\n</div>\r\n<div id="contentwindow" style="text-align:center;">\r\n	<script src="inc/comment.js" type="text/javascript"></script>\r\n	<form action="?section=newsdetail" method="post">\r\n	<input type="hidden" name="newsid" value="{newsid}">\r\n	<table border="0" cellpadding="2" cellspacing="0" style="text-align:left; width:500px;">\r\n	<-- if postpermission -->\r\n		<tr>\r\n			<td colspan="2">\r\n				<b>Posten als:</b> <-- if user -->{username}<-- else user -->Gast<-- /if user -->\r\n				<input name="email" class="textinput" style="display:none;">\r\n				<input name="formdate" type="hidden" value="{time}">\r\n			</td>\r\n		</tr>\r\n		<tr>\r\n			<td colspan="2">\r\n				<div class="editorbutton"><img border="0" src="images/styles/froggreen/texteditor/bold.gif" alt="Fett" onClick="insertFSCode(''b'')"></div>\r\n				<div class="editorbutton"><img border="0" src="images/styles/froggreen/texteditor/italic.gif" alt="Kursiv" onClick="insertFSCode(''i'')"></div>\r\n				<div class="editorbutton"><img border="0" src="images/styles/froggreen/texteditor/underline.gif" alt="Unterstrichen" onClick="insertFSCode(''u'')"></div>\r\n				<div class="editorbutton"><img border="0" src="images/styles/froggreen/texteditor/stroke.gif" alt="Durchgestrichen" onClick="insertFSCode(''s'')"></div>\r\n				<div class="editorbutton"><img border="0" src="images/styles/froggreen/texteditor/center.gif" alt="Zentriert" onClick="insertFSCode(''center'')"></div>\r\n				<div class="editorbutton"><img border="0" src="images/styles/froggreen/texteditor/right.gif" alt="Rechtsbündig" onClick="insertFSCode(''right'')"></div>\r\n				<div class="editorbutton"><img border="0" src="images/styles/froggreen/texteditor/block.gif" alt="Blocksatz" onClick="insertFSCode(''block'')"></div>\r\n			</td>\r\n		</tr>\r\n		<tr>\r\n			<td valign="top"><textarea name="text" id="ctext" class="textinput" style="height:120px; width:400px;">{text}</textarea></td>\r\n			<td valign="top">\r\n				<fieldset style="width:90px;">\r\n				<legend>Smilies</legend>\r\n					{smilies}\r\n				</fieldset>\r\n			</td>\r\n		</tr>\r\n	</table>\r\n	<table border="0" cellpadding="2" cellspacing="0" style="text-align:left; width:500px;">\r\n		<tr>\r\n			<td colspan="2"><input type="submit" class="button" value="Absenden"></td>\r\n		</tr>\r\n	<-- else postpermission -->\r\n		<tr>\r\n			<td style="padding:2px;" align="center">Du musst registriert sein, um Kommentare schreiben zu können.</td>\r\n		</tr>\r\n	<-- /if postpermission -->\r\n	</table>\r\n	</form>\r\n</div>\r\n<-- /if comments -->', 1, 2),
(35, 'comment_done', 'Kommentar eingetragen', '<div id="contentheader"><div id="contentlogo">NEWS</div></div>\r\n<div id="contentwindow" style="text-align:center; padding:20px;">\r\n	Dein Kommentar wurde eingetragen<p>\r\n	<input type="button" class="button" value="Zurück zur News" onClick="javascript:history.back();">\r\n</div>\r\n', 1, 2),
(36, 'errorspamblock', 'Fehler: Postlimit', '<b>Du kannst nur alle {seconds} Sekunden ein Kommentar schreiben.</b><p>\r\n<input type="button" class="button" value="Zurück" onClick="javascript:history.back();">', 1, 2),
(37, 'download', 'Download Body', '<div id="contentheader">\r\n	<div id="contentlogo">\r\n		<b><a href="?section=download&folder={folderid}" style="color:#FFFFFF;">{catname}</a> > {name}</b>\r\n	</div>\r\n</div>\r\n<div id="contentwindow">\r\n	<table border="0" cellpadding="2" cellspacing="0" style="margin:0px auto; width:90%;">\r\n		<tr>\r\n			<td width="150"><b>Name:</b></td>\r\n			<td>{name}</td>\r\n		</tr>\r\n		<tr>\r\n			<td><b>Autor:</b></td>\r\n			<td>\r\n				<-- if autorurl -->\r\n					<a href="{autorurl}" target="_blank">{autor}</a>\r\n				<-- else autorurl -->\r\n					{autor}\r\n				<-- /if autorurl -->\r\n			</td>\r\n		</tr>\r\n		<tr>\r\n			<td><b>Hinzugefügt:</b></td>\r\n			<td>{date}</td>\r\n		</tr>\r\n		<tr>\r\n			<td><b>Views:</b></td>\r\n			<td>{views}</td>\r\n		</tr>\r\n		<tr>\r\n			<td><b>Downloads:</b></td>\r\n			<td>{totaldls}</td>\r\n		</tr>\r\n		<tr>\r\n			<td valign="top"><b>Beschreibung:</b></td>\r\n			<td>{text}</td>\r\n		</tr>\r\n	</table>\r\n</div>\r\n<-- if permission -->\r\n<table border="0" cellpadding="2" cellspacing="1" width="100%" style="margin:-1px;">\r\n	<tr>\r\n		<td class="thead"><b>Link</b></td>\r\n		<td class="thead"><b>Größe</b></td>\r\n		<td class="thead"><b>Hits</b></td>\r\n	</tr>\r\n	<-- link -->\r\n	<tr>\r\n		<td class="alt{altnum}"><a href="{linkurl}" target="{linktarget}">{linkname}</a></td>\r\n		<td class="alt{altnum}" align="center">{linksize}</td>\r\n		<td class="alt{altnum}" align="center">{linkhits}</td>\r\n	</tr>\r\n	<-- /link -->\r\n</table>\r\n<-- else permission -->\r\n<table border="0" cellpadding="2" cellspacing="0" width="100%" style="margin:-1px;">\r\n	<tr>\r\n		<td class="thead">Links</td>\r\n	</tr>\r\n	<tr>\r\n		<td class="alt{altnum}"><b>Du musst angemeldet sein, um diesen Download zu starten.</b></td>\r\n	</tr>\r\n</table>\r\n<-- /if permission -->\r\n', 1, 5),
(38, 'dloverview', 'Download Übersicht', '<div id="contentheader">\r\n	<div id="contentlogo">\r\n		<b>Downloads</b>\r\n	</div>\r\n</div>\r\n<div id="contentwindow">\r\n	<div style="padding:10px;">\r\n		<div style="height:38px;">\r\n			<img border="0" src="images/styles/froggreen/download/root.gif" alt="" style="float:left; margin-right:8px;">\r\n			<b>{pagetitle}</b><br>\r\n			Stammverzeichnis\r\n		</div>\r\n	<-- folder -->\r\n		<div style="height:38px; margin-left:{deep}px;">\r\n			<a href="?section=download&folder={folderid}">\r\n				<img border="0" src="images/styles/froggreen/download/folder<-- if selected_folder -->2<-- /if selected_folder -->.gif" alt="" style="float:left; margin-right:8px;">\r\n			</a>\r\n			<b>{foldername} ({numfiles})</b><br>\r\n			{foldertext}\r\n		</div>\r\n	<-- /folder -->\r\n	<-- file -->\r\n		<div style="height:18px; margin-left:{deep}px;">\r\n			<a href="?section=download&id={downloadid}">\r\n				<img border="0" src="images/styles/froggreen/download/file.gif" alt="" width="16" height="16" style="margin-bottom:-5px;">\r\n				{downloadname}\r\n			</a>\r\n		</div>\r\n	<-- /file -->\r\n	</div>\r\n</div>\r\n', 1, 5),
(39, 'newsheadlines', 'News Headlines', '<div id="contentheader">\r\n	<div id="contentlogo">\r\n		HEADLINES\r\n	</div>\r\n</div>\r\n<div id="contentwindow">\r\n	<-- headline -->\r\n	<div><span style="float:right;">{date}</span>• <a href="{url}">{title}</a><br></div>\r\n	<-- /headline -->\r\n</div>', 1, 2),
(41, 'articlelist', 'Artikel Übersicht', '<table border="0" cellpadding="4" cellspacing="1" width="100%" style="margin:-1px;">\r\n<-- cat -->\r\n	<-- if cat -->\r\n	<tr>\r\n		<td colspan="3" class="thead">\r\n			<a href="{caturl}" style="color:#FFFFFF">Kategorie: {catname}</a>\r\n		</td>\r\n	</tr>\r\n	<tr>\r\n		<td class="alt{altnum}" colspan="3">{catdescription}</td>\r\n	</tr>\r\n	<-- /if cat -->\r\n	<tr>\r\n		<td class="thead"><b>Artikel</b></td>\r\n		<td class="thead"><b>Datum</b></td>\r\n		<td class="thead"><b>Vorschau</b></td>\r\n	</tr>\r\n<-- /cat -->\r\n<-- article -->\r\n	<tr>\r\n		<td class="alt{altnum}" valign="top" nowrap><a href="{articleurl}">{article}</a></td>\r\n		<td class="alt{altnum}" valign="top" nowrap>{date}</td>\r\n		<td class="alt{altnum}">{preview}</td>\r\n	</tr>\r\n<-- /article -->\r\n</table>\r\n', 1, 3),
(40, 'newsarchiv', 'News Archiv', '<div id="contentheader">\r\n	<div id="contentlogo">\r\n		NEWS ARCHIV\r\n	</div>\r\n</div>\r\n<div id="contentwindow" style="padding-left:20px;">\r\n	<form action="./">\r\n		<input type="hidden" name="section" value="newsarchiv">\r\n		News anzeigen vom\r\n		<select name="month" class="textinput">\r\n			<option value="1">Januar</option>\r\n			<option value="2">Februar</option>\r\n			<option value="3">März</option>\r\n			<option value="4">April</option>\r\n			<option value="5">Mai</option>\r\n			<option value="6">Juni</option>\r\n			<option value="7">Juli</option>\r\n			<option value="8">August</option>\r\n			<option value="9">September</option>\r\n			<option value="10">Oktober</option>\r\n			<option value="11">November</option>\r\n			<option value="12">Dezember</option>\r\n		</select>\r\n		<select name="year" class="textinput">\r\n			{yearoptions}\r\n		</select>\r\n		<input type="submit" value="los" class="button">\r\n	</form>\r\n</div>\r\n<p>\r\n{news}\r\n<div align="right">\r\n	<-- page -->\r\n		<-- if currentpage -->\r\n			<b>[{pagenum}]</b>\r\n		<-- else currentpage -->\r\n			<a href="{pagelink}">[{pagenum}]</a>\r\n		<-- /if currentpage -->\r\n	<-- /page -->\r\n</div>\r\n<p/>', 1, 2),
(42, 'articleheadlines', 'Neuste Artikel', '<div id="contentheader">\r\n	<div id="contentlogo">\r\n		NEUSTE ARTIKEL\r\n	</div>\r\n</div>\r\n<div id="contentwindow">\r\n	<-- article -->\r\n	<div><span style="float:right;">{date}</span>• <a href="{url}">{title}</a><br></div>\r\n	<-- /article -->\r\n</div>', 1, 3),
(43, 'gallerylist', 'Galerie Übersicht', '<table border="0" cellpadding="4" cellspacing="1" width="100%" style="margin:-1px;">\r\n<-- cat -->\r\n	<-- if cat -->\r\n	<tr>\r\n		<td colspan="4" class="thead">\r\n			<a href="{caturl}" style="color:#FFFFFF;">Kategorie: {catname}</a>\r\n		</td>\r\n	</tr>\r\n	<tr>\r\n		<td class="alt{altnum}" colspan="4">{catdescription}</td>\r\n	</tr>\r\n	<-- /if cat -->\r\n	<tr>\r\n		<td class="thead"><b>Galerie</b></td>\r\n		<td class="thead"><b>Bilder</b></td>\r\n		<td class="thead"><b>Datum</b></td>\r\n		<td class="thead"><b>Beschreibung</b></td>\r\n	</tr>\r\n<-- /cat -->\r\n<-- gallery -->\r\n	<tr>\r\n		<td class="alt{altnum}" valign="top" nowrap><a href="{galleryurl}">{gallery}</a></td>\r\n		<td class="alt{altnum}" valign="top" align="center" nowrap>{numpics}</td>\r\n		<td class="alt{altnum}" valign="top" nowrap>{date}</td>\r\n		<td class="alt{altnum}">{preview}</td>\r\n	</tr>\r\n<-- /gallery -->\r\n</table>', 1, 4),
(46, 'downloadheadlines', 'Neuste Downloads', '<div id="contentheader">\r\n	<div id="contentlogo">\r\n		NEUSTE DOWNLOADS\r\n	</div>\r\n</div>\r\n<div id="contentwindow">\r\n	<-- download -->\r\n	<div><span style="float:right;">{date}</span>• <a href="{url}">{title}</a><br></div>\r\n	<-- /download -->\r\n</div>', 1, 5),
(47, 'searchform', 'Suchfeld', '<div class="menucat">SUCHEN</div>\r\n<div class="menubox" align="center">\r\n	<form action="?section=search" method="post">\r\n		<input class="textinput" name="keywords" style="width:90px;">\r\n		<input class="button" type="submit" value="los">\r\n	</form>\r\n</div>\r\n<p>', 1, 1),
(48, 'search', 'Suche', '<div id="contentheader">\r\n	<div id="contentlogo">\r\n		SUCHE\r\n	</div>\r\n</div>\r\n<div id="contentwindow">\r\n	<div style="margin:0px auto; width:90%;">\r\n		Die Suche nach <b><i>{searchstring}</i></b> ergab folgende Treffer in den News:\r\n		<p>\r\n		<-- news -->\r\n			<span style="float:right;">{date}</span>\r\n			<a href="{url}" style="font-size:10pt;"><b><u>{title}</u></b></a><br>\r\n			{text}\r\n			<p>\r\n		<-- /news -->\r\n		<hr>\r\n		Die Suche nach <b><i>{searchstring}</i></b> ergab folgende Treffer in den Artikeln:\r\n		<p>\r\n		<-- article -->\r\n			<span style="float:right;">{date}</span>\r\n			<a href="{url}" style="font-size:10pt;"><b><u>{title}</u></b></a><br>\r\n			{text}\r\n			<p>\r\n		<-- /article -->\r\n		<hr>\r\n		Die Suche nach <b><i>{searchstring}</i></b> ergab folgende Treffer in den Downloads:\r\n		<p>\r\n		<-- download -->\r\n			<span style="float:right;">{date}</span>\r\n			<a href="{url}" style="font-size:10pt;"><b><u>{title}</u></b></a><br>\r\n			{text}\r\n			<p>\r\n		<-- /download -->\r\n	</div>\r\n</div>', 1, 1),
(49, 'errorsearch', 'Fehler: Suchwörter zu kurz', '<b>Die von dir eingegebenen Suchwörter sind zu kurz</b><p>\r\n<input type="button" class="button" value="Zurück" onClick="javascript:history.back();">', 1, 1),
(50, 'errorsearchtime', 'Fehler: Zu kurzes Suchintervall', '<b>Du kannst nur alle {seconds} Sekunden eine Suche starten</b><p>\r\n<input type="button" class="button" value="Zurück" onClick="javascript:history.back();">', 1, 1),
(51, 'pollbox', 'Umfrage Box', '<div class="menucat">UMFRAGE</div>\r\n<div class="menubox" align="center">\r\n<-- if useronly -->\r\n	<table border="0" cellpadding="1" cellspacing="0" width="95%">\r\n		<tr>\r\n			<td colspan="2"><b>{question}</b></td>\r\n		<tr>\r\n	<-- answer -->\r\n		<tr>\r\n			<td colspan="2" align="left" style="padding-bottom:2px;">{answer}</td>\r\n		</tr>\r\n	<-- /answer -->\r\n		<tr>\r\n			<td colspan="2"><b>An dieser Umfrage kannst du nur als User teilnehmen.</b></td>\r\n		<tr>\r\n	</table>\r\n<-- else useronly -->\r\n	<-- if has_submit -->\r\n		<table border="0" cellpadding="1" cellspacing="0" width="95%">\r\n			<tr>\r\n				<td colspan="2"><b>{question}</b></td>\r\n			<tr>\r\n		<-- answer -->\r\n			<tr>\r\n				<td align="left" valign="top" style="padding-bottom:2px;">{answer}</td>\r\n				<td align="left" valign="top" style="padding-left:5px; padding-bottom:2px;" nowrap>{hits} / {percent}%</td>\r\n			</tr>\r\n		<-- /answer -->\r\n			<tr>\r\n				<td colspan="2"><b>Du hast an dieser Umfrage teilgenommen.</b></td>\r\n			<tr>\r\n		</table>\r\n	<-- else has_submit -->\r\n		<form action="" method="post">\r\n		<input type="hidden" name="pollid" value="{pollid}">\r\n		<table border="0" cellpadding="1" cellspacing="0" width="95%">\r\n			<tr>\r\n				<td colspan="2"><b>{question}</b></td>\r\n			<tr>\r\n		<-- answer -->\r\n			<tr>\r\n				<td valign="top" style="padding-bottom:2px;"><input type="{polltype}" name="pollanswer[{answerid}]" value="{answerid}"></td>\r\n				<td align="left" style="padding-bottom:2px;">{answer}</td>\r\n			</tr>\r\n		<-- /answer -->\r\n			<tr>\r\n				<td colspan="2"><input type="submit" class="button" value="Abstimmen"></td>\r\n			<tr>\r\n		</table>\r\n		</form>\r\n	<-- /if has_submit -->	\r\n<-- /if useronly -->\r\n</div>\r\n<p>', 1, 6),
(52, 'polllist', 'Umfragen Übersicht', '<table border="0" cellpadding="2" cellspacing="1" width="100%" style="margin:-1px;">\r\n	<tr>\r\n		<td class="thead"><b>Umfrage</b></td>\r\n		<td class="thead"><b>Erscheinungsdatum</b></td>\r\n		<td class="thead"><b>Enddatum</b></td>\r\n		<td class="thead"><b>Teilnehmer</b></td>\r\n	</tr>\r\n<-- poll -->\r\n	<tr>\r\n		<td class="alt{altnum}"><a href="{url}">{question}</a></td>\r\n		<td class="alt{altnum}" align="center" valign="top">{startdate}</td>\r\n		<td class="alt{altnum}" align="center" valign="top">{enddate}</td>\r\n		<td class="alt{altnum}" align="center" valign="top">{hits}</td>\r\n	</tr>\r\n<-- /poll -->\r\n</table>\r\n', 1, 6),
(53, 'polldetail', 'Umfrage Detailansicht', '<div id="contentheader">\r\n	<div id="contentlogo">\r\n		UMFRAGE\r\n	</div>\r\n</div>\r\n<div id="contentwindow">\r\n	<div style="width:90%; margin:0px auto;">\r\n		<b>{question}</b><br>\r\n		Umfragedauer: Vom {startdate} bis zum {enddate}<br>\r\n	<-- if useronly -->\r\n		• Diese Umfrage ist nur für registrierte Benutzer<br>\r\n	<-- /if useronly -->\r\n	<-- if multiselect -->\r\n		• Bei Dieser Umfrage sind Mehrfachauswahlen möglich<br>\r\n	<-- /if multiselect -->\r\n	</div>\r\n</div>\r\n<table border="0" cellpadding="2" cellspacing="1" width="100%" style="margin:-1px;">\r\n	<tr>\r\n		<td class="thead"><b>Antwort</b></td>\r\n		<td class="thead"><a href="?section=pollarchiv&id={pollid}&order=hits" style="color:#FFFFFF;"><b>Hits</b></a></td>\r\n		<td class="thead"><b>Prozent</b></td>\r\n	</tr>\r\n<-- answer -->\r\n	<tr>\r\n		<td class="alt{altnum}">{answer}</td>\r\n		<td class="alt{altnum}" align="center" valign="top">{hits}</td>\r\n		<td class="alt{altnum}" align="center" valign="top">{percent}%</td>\r\n	</tr>\r\n<-- /answer -->\r\n</table>\r\n', 1, 6),
(54, 'statbox', 'Statistik Box', '<div class="menucat">STATISTIK</div>\r\n<div class="menubox" align="center">\r\n	<table border="0" cellpadding="1" cellspacing="0" width="95%">\r\n		<tr>\r\n			<td colspan="2" align="center">Zur Zeit sind {useronline} User online.</td>\r\n		</tr>\r\n		<tr>\r\n			<td align="right" style="padding-right:5px;">{visitstoday}</td>\r\n			<td align="left">Besucher Heute</td>\r\n		</tr>\r\n		<tr>\r\n			<td align="right" style="padding-right:5px;">{hitstoday}</td>\r\n			<td align="left">Klicks Heute</td>\r\n		</tr>\r\n		<tr>\r\n			<td align="right" style="padding-right:5px;">{visitsall}</td>\r\n			<td align="left">Besucher gesamt</td>\r\n		</tr>\r\n		<tr>\r\n			<td align="right" style="padding-right:5px;">{hitsall}</td>\r\n			<td align="left">Klicks gesamt</td>\r\n		</tr>\r\n	</table>\r\n</div>\r\n<p>', 1, 7),
(55, 'shopltbox', 'Shop Box', '<div class="menucat">SHOP</div>\r\n<div class="menubox" align="center">\r\n	<table border="0" cellpadding="1" cellspacing="0" width="95%">\r\n		<-- article -->\r\n		<tr>\r\n			<td rowspan="2">\r\n			<-- if thumb -->\r\n				<a href="{url}" target="_blank"><img width="50" border="0" src="{thumb}" alt=""></a>\r\n			<-- else thumb -->\r\n				<i>Kein Bild vorhanden</i>\r\n			<-- /if thumb -->\r\n			</td>\r\n			<td align="left" valign="top"><a href="{url}" target="_blank"><b>{name}</b></a></td>\r\n		</tr>\r\n		<tr>\r\n			<td align="right" valign="bottom">{price}</td>\r\n		</tr>\r\n		<-- /article -->\r\n		<tr>\r\n			<td colspan="2" align="right"><a href="?section=shoplt">...mehr</a></td>\r\n		</tr>\r\n	</table>\r\n</div>\r\n<p>', 1, 8),
(56, 'shopltlist', 'Shop Übersicht', '<table border="0" cellpadding="4" cellspacing="1" width="100%" style="margin:-1px;">\r\n	<tr>\r\n		<td class="thead" colspan="2">SHOP</td>\r\n	</tr>\r\n<-- cat -->\r\n	<-- if cat -->\r\n	<tr>\r\n		<td class="alt{altnum}"><a href="{caturl}"><b>{catname}</b></a><br></td>\r\n		<td class="alt{altnum}">{catdescription}	</td>\r\n	</tr>\r\n	<-- /if cat -->\r\n<-- /cat -->\r\n<-- article -->\r\n	<tr>\r\n		<td class="alt{altnum}" valign="top">\r\n		<-- if thumb -->\r\n			<a href="{img}" target="_blank"><img border="0" src="{thumb}" alt=""></a>\r\n		<-- else thumb -->\r\n			<i>Kein Bild vorhanden</i>\r\n		<-- /if thumb -->\r\n		</td>\r\n		<td class="alt{altnum}" valign="top">\r\n			<a href="{url}" target="_blank"><b>{name}</b></a>\r\n			<p>\r\n			{text}\r\n			<p>\r\n			<span style="float:right;">{price}</span>\r\n			<a href="{url}" target="_blank"><b><u>jetzt kaufen</u></b></a>\r\n		</td>\r\n	</tr>\r\n<-- /article -->\r\n</table>\r\n', 1, 8),
(57, 'tickerbox', 'Ticker Box', '<div class="menucat">LIVE TICKER</div>\r\n<div class="menubox" align="center">\r\n	<div style="width:95%; text-align:left;">\r\n		<b>{name}</b><br>\r\n		{description}\r\n		<p>\r\n		<b>Neuster Eintrag:</b><br>\r\n		{lastentry}\r\n		<div align="right"><a href="{url}"><u>Zum Ticker</u></a></div>\r\n	</div>\r\n</div>\r\n<p>', 1, 9),
(348, 'linkheadlines', 'Link Headlines', '<div id="contentheader">\r\n	<div id="contentlogo">\r\n		NEUSTE LINKS\r\n	</div>\r\n</div>\r\n<div id="contentwindow">\r\n	<-- link -->\r\n	<div><span style="float:right;">{date}</span>• <a href="{url}">{title}</a> <-- if tag -->({tag})<-- /if tag --></div>\r\n	<-- /link -->\r\n</div>', 1, 11),
(60, 'video_list', 'Video Übersicht', '<table border="0" cellpadding="4" cellspacing="1" width="100%" style="margin:-1px;">\r\n<-- cat -->\r\n	<-- if cat -->\r\n	<tr>\r\n		<td colspan="3" class="thead">\r\n			<a href="{caturl}" style="color:#FFFFFF">Kategorie: {catname}</a>\r\n		</td>\r\n	</tr>\r\n	<tr>\r\n		<td class="alt{altnum}" colspan="3">{catdescription}</td>\r\n	</tr>\r\n	<-- /if cat -->\r\n	<tr>\r\n		<td class="thead"><b>Name</b></td>\r\n		<td class="thead"><b>Erscheinungsdatum</b></td>\r\n		<td class="thead"><b>Beschreibung</b></td>\r\n	</tr>\r\n<-- /cat -->\r\n<-- video -->\r\n	<tr>\r\n		<td class="alt{altnum}" valign="top"><a href="{url}">{name}</a></td>\r\n		<td class="alt{altnum}" align="center" valign="top">{date}</td>\r\n		<td class="alt{altnum}" valign="top">{description}</td>\r\n	</tr>\r\n<-- /video -->\r\n</table>', 1, 10),
(58, 'tickerlist', 'Ticker Übersicht', '<table border="0" cellpadding="4" cellspacing="1" width="100%" style="margin:-1px;">\r\n	<tr>\r\n		<td class="thead"><b>Ticker</b></td>\r\n		<td class="thead"><b>Aktiv</b></td>\r\n		<td class="thead"><b>Letzter Eintrag</b></td>\r\n		<td class="thead"><b>Beschreibung</b></td>\r\n	</tr>\r\n<-- ticker -->\r\n	<tr>\r\n		<td class="alt{altnum}" valign="top"><a href="{url}">{name}</a></td>\r\n		<-- if active -->\r\n			<td class="alt{altnum}" valign="top" align="center">Ja</td>\r\n		<-- else active -->\r\n			<td class="alt{altnum}" valign="top" align="center">Nein</td>\r\n		<-- /if active -->\r\n		<td class="alt{altnum}" valign="top" align="center">{lastentry}</td>\r\n		<td class="alt{altnum}" valign="top">{description}</td>\r\n	</tr>\r\n<-- /ticker -->\r\n</table>', 1, 9),
(59, 'tickerdetail', 'Ticker Detailansicht', '<div id="contentheader">\r\n	<div id="contentlogo">LIVE TICKER - {name}</div>\r\n</div>\r\n<div id="contentwindow">\r\n	<div style="width:90%; margin:0px auto;">\r\n		{description}\r\n	</div>\r\n</div>\r\n<table border="0" cellpadding="4" cellspacing="1" width="100%" style="margin:-1px;">\r\n	<tr>\r\n		<td class="thead"><b>Zeit</b></td>\r\n		<td class="thead"><b>Eintrag</b></td>\r\n	</tr>\r\n<-- entry -->\r\n	<tr>\r\n		<td class="alt{altnum}" valign="top">{date}</td>\r\n		<td class="alt{altnum}" valign="top">{text}</td>\r\n	</tr>\r\n<-- /entry -->\r\n</table>\r\n{script}', 1, 9),
(61, 'video_detail', 'Video Detailansicht', '<div id="contentheader">\r\n	<div id="contentlogo"><span style="float:right;">{date}</span>VIDEO ARCHIV - {name}</div>\r\n</div>\r\n<div>\r\n	<script type="text/javascript">\r\n		var width = ''100%'';\r\n		var height = 450;\r\n		var color = ''{color}'';\r\n		var video = ''{video}'';\r\n		var style = ''{style}'';\r\n	</script>\r\n	<script src="inc/frogplayer.js" type="text/javascript"></script>\r\n</div>\r\n<p>\r\n<div id="contentheader">\r\n	<div id="contentlogo"><span style="float:right;">{date}</span>{name}</div>\r\n</div>\r\n<div id="contentwindow">\r\n	<div style="width:90%; margin:0px auto;">\r\n		{description}\r\n	</div>\r\n</div>', 1, 10),
(62, 'errorregonly', 'Fehler: Nicht angemeldet', '<b>Du musst angemeldet sein, um diesen Inhalt anzusehen.</b><p>\r\n<input type="button" class="button" value="Zurück" onClick="javascript:history.back();">', 1, 1),
(66, 'contact', 'Kontaktformular', '<script type="text/javascript">\r\n	function chkContactForm()\r\n	{\r\n		if (document.getElementById("ct_name").value != "" && \r\n			document.getElementById("ct_subject").value != "" &&\r\n			document.getElementById("ct_mail").value != "" &&\r\n			document.getElementById("ct_text").value != "")\r\n		{\r\n			return true;\r\n		}\r\n		else\r\n		{\r\n			alert("Bitte fülle alle mit einem * gekennzeichneten Felder aus");\r\n			return false;\r\n		}\r\n	}\r\n</script>\r\n<div id="contentheader"><div id="contentlogo">KONTAKTFORMULAR</div></div>\r\n<div id="contentwindow">\r\n	<form action="?section=contact" method="post" onSubmit="return chkContactForm()">\r\n	<input type="hidden" name="action" value="submit">\r\n	<input type="hidden" name="time" value="{time}">\r\n	<table border="0" cellpadding="2" cellspacing="0" style="margin:0px auto; width:500px;">\r\n		<tr>\r\n			<td><b>Name*:</b></td>\r\n			<td><input name="Name" id="ct_name" class="textinput" style="width:200px;"></td>\r\n		</tr>\r\n		<tr>\r\n			<td><b>Betreff*:</b></td>\r\n			<td><input name="Betreff" id="ct_subject" class="textinput" style="width:200px;"></td>\r\n		</tr>\r\n		<tr>\r\n			<td><b>E-Mail*:</b></td>\r\n			<td><input name="EMail" id="ct_mail" class="textinput" style="width:300px;"></td>\r\n		</tr>\r\n		<tr style="visibility:collapse;">\r\n			<td><b>Mail*:</b></td>\r\n			<td><input name="mail" id="s_email" class="textinput" style="width:300px;"></td>\r\n		</tr>\r\n		<tr>\r\n			<td valign="top"><b>Nachricht*:</b></td>\r\n			<td><textarea name="Nachricht" id="ct_text" class="textinput" style="width:500px; height:150px;"></textarea></td>\r\n		</tr>\r\n		<tr>\r\n			<td colspan="2">\r\n				 <br>\r\n				<input type="submit" class="button" value="Abschicken" style="float:right;">\r\n			</td>\r\n		</tr>\r\n	</table>\r\n	</form>\r\n</div>\r\n', 1, 1),
(63, 'articleheader', 'Artikel Index', '<-- article -->\r\n	<a href="{url}">{name}</a> |\r\n<-- /article -->\r\n<hr>', 1, 3),
(64, 'link_list', 'Link Übersicht', '<table border="0" cellpadding="4" cellspacing="1" width="100%" style="margin:-1px;">\r\n	<tr>\r\n		<td class="thead"><b>Link Kategorie</b></td>\r\n		<td class="thead"><b>Beschreibung</b></td>\r\n	</tr>\r\n<-- cat -->\r\n	<tr>\r\n		<td valign="top" class="alt{altnum}"><a href="{url}"><b>{name}</b></a></td>\r\n		<td class="alt{altnum}">{description}</td>\r\n	</tr>\r\n<-- /cat -->\r\n</table>', 1, 11),
(65, 'link_cat', 'Link Kategorie', '<div id="contentheader">\r\n	<div id="contentlogo">LINKS - {catname}</div>\r\n</div>\r\n<div id="contentwindow">\r\n	<div style="width:90%; margin:0px auto;">\r\n		{catdescription}\r\n	</div>\r\n</div>\r\n<p>\r\n<table border="0" cellpadding="4" cellspacing="1" width="100%" style="margin:-1px;">\r\n<-- subcat -->\r\n	<-- if subcat -->\r\n	<tr>\r\n		<td colspan="3" class="alt{altnum}">\r\n			<a href="{subcaturl}"><b>{subcatname}</b></a>\r\n		</td>\r\n	</tr>\r\n	<-- /if subcat -->\r\n	<tr>\r\n		<td class="thead"><b>Name</b></td>\r\n		<td class="thead"><b>Erscheinungsdatum</b></td>\r\n		<td class="thead"><b>Beschreibung</b></td>\r\n	</tr>\r\n<-- /subcat -->\r\n<-- link -->\r\n	<tr>\r\n		<td class="alt{altnum}" valign="top">\r\n			<-- if url -->\r\n				<a href="{url}"><b>{name}</b></a>\r\n			<-- else url -->\r\n				<b>{name}</b>\r\n			<-- /if url -->\r\n		</td>\r\n		<td class="alt{altnum}" valign="top" align="center">{date}</td>\r\n		<td class="alt{altnum}">{description}</td>\r\n	</tr>\r\n<-- /link -->\r\n</table>', 1, 11),
(67, 'contactsend', 'Kontaktformular gesendet', '<div id="contentheader"><div id="contentlogo">KONTAKTFORMULAR</div></div>\r\n<div id="contentwindow" style="text-align:center; padding:20px;">\r\n	Deine Anfrage wurde gesendet.\r\n</div>\r\n', 1, 1),
(334, 'errorwrongpass', 'Fehler: Falsches Passwort', '<b>Das von dir eingegebene Passwort ist falsch. Du hast noch {trys} Versuche, das richtige einzugeben</b><br>(<a href="?section=pwrecover"><u>Passwort vergessen?</u></a>)\r\n<p>\r\n<input type="button" class="button" value="Zurück" onClick="javascript:history.back();">', 1, 1),
(335, 'errornouser', 'Fehler: User nicht gefunden', '<b>Dieser User wurde nicht gefunden.</b><p>\r\n<input type="button" class="button" value="Zurück" onClick="javascript:history.back();">', 1, 1),
(336, 'errortimeout', 'Fehler: Zu viele Logins', '<b>Du hast zu oft das falsche Passwort eingegeben. Bitte warte {minutes} Minuten bis du es wieder versuchst.</b><br>(<a href="?section=pwrecover"><u>Passwort vergessen?</u></a>)\r\n<p>\r\n<input type="button" class="button" value="Zurück" onClick="javascript:history.back();">', 1, 1),
(280, 'tagcloud', 'Such-Wolke', '<div class="menucat">SUCH-WOLKE</div>\r\n<div class="menubox" align="center">\r\n	<-- tag -->\r\n		<span style="font-size:{size}px;">{word}</span>\r\n	<-- /tag -->\r\n</div>\r\n<p>', 1, 7),
(337, 'gallerytag', 'Galerie Tag', '<div id="contentheader">\r\n	<div id="contentlogo">\r\n		<a href="{gallerylink}" style="color:#FFFFFF;">{galleryname}</a>\r\n	</div>\r\n</div>\r\n<div id="contentwindow">\r\n	<table border="0" width="100%" cellpadding="0" cellspacing="2">\r\n		<tr>\r\n			<-- thumbnail -->\r\n			<td align="center">\r\n				<div style="text-align:center;">\r\n					<a href="{detaillink}">\r\n						<img border="0" src="{thumb}" alt="">\r\n					</a><br>\r\n					{title}\r\n				</div>\r\n			</td>\r\n			<-- /thumbnail -->\r\n		</tr>\r\n	</table>\r\n	<div align=right><a href="{gallerylink}"><b>-> Zur Galerie</b></a></div>\r\n</div>', 1, 4);
INSERT INTO `fsxl_templates` (`id`, `shortcut`, `name`, `code`, `styleid`, `mod`) VALUES
(338, 'contestbody', 'Wettbewerb Body', '<div id="contentheader">\r\n	<div id="contentlogo">WETTBEWERB: {title}</div>\r\n</div>\r\n<div id="contentwindow">\r\n	<div style="width:90%; margin:0px auto;">\r\n		<br>\r\n		{description}\r\n		<p>\r\n		Der Wettbewerb endet am <b>{enddate}</b>\r\n		<p>\r\n		<-- if entries -->\r\n			<a href="index.php?section=contestentries&id={contestid}"><b>-> Einsendungen ansehen</b></a>\r\n			<p>\r\n		<-- /if entries -->\r\n	<-- if contest_open -->\r\n		<-- if user_loggedin -->\r\n			<-- if user_submitted -->\r\n				<b>Du hast and diesem Wettbewerb bereits teilgenommen</b>\r\n			<-- else user_submitted -->\r\n				<br>\r\n				<-- if img_contest -->\r\n					<form action="index.php?section=contest" method="post" enctype="multipart/form-data">\r\n					<input type="hidden" name="contestid" value="{contestid}">\r\n					<div id="contentheader" style="width:500px; margin:0px auto;">\r\n						<div id="contentlogo">Beitrag einsenden</div>\r\n					</div>\r\n					<div id="contentwindow" style="width:488px; margin:0px auto;">\r\n					<table border="0" cellpadding="2" cellspacing="0" width="100%">\r\n						<tr>\r\n							<td width="200"><b>Titel:</b></td>\r\n							<td><input name="title" class="textinput" style="width:300px;"></td>\r\n						</tr>\r\n						<tr>\r\n							<td><b>Bild:</b><br>PNG, JPG oder GIF</td>\r\n							<td><input type="file" name="img" class="textinput"></td>\r\n						</tr>\r\n						<tr>\r\n							<td valign="top"><b>Beschreibung:</b></td>\r\n							<td><textarea name="text" class="textinput" style="width:300px; height:100px;"></textarea></td>\r\n						</tr>\r\n						<tr>\r\n							<td colspan="2" align="right"><input type="submit" value="Absenden" class="button"></td>\r\n						</tr>\r\n					</table>\r\n					</div>\r\n					</form>\r\n				<-- else img_contest -->\r\n					<form action="index.php?section=contest" method="post">\r\n					<input type="hidden" name="contestid" value="{contestid}">\r\n					<div id="contentheader" style="width:500px; margin:0px auto;">\r\n						<div id="contentlogo">Beitrag einsenden</div>\r\n					</div>\r\n					<div id="contentwindow" style="width:488px; margin:0px auto;">\r\n					<table border="0" cellpadding="2" cellspacing="0" width="100%">\r\n						<tr>\r\n							<td valign="top"><b>Beitrag:</b></td>\r\n							<td><textarea name="text" class="textinput" style="width:300px; height:200px;"></textarea></td>\r\n						</tr>\r\n						<tr>\r\n							<td colspan="2" align="right"><input type="submit" value="Absenden" class="button"></td>\r\n						</tr>\r\n					</table>\r\n					</div>\r\n					</form>\r\n				<-- /if img_contest -->\r\n			<-- /if user_submitted -->\r\n		<-- else user_loggedin -->\r\n			<b>Du musst eingeloggt sein, um am Wettbewerb teil zu nemen.</b>\r\n		<-- /if user_loggedin -->\r\n	<-- else contest_open -->\r\n		<b>Der Wettbewerb ist beendet.</b>\r\n	<-- /if contest_open -->\r\n		<br>\r\n	</div>\r\n</div>', 1, 12),
(339, 'error_wrongimg', 'Fehler: Falsches Bildformat', '<b>Die von dir hochgeladene Datei ist kein gültiges Bildformat.</b><p>\r\n<input type="button" class="button" value="Zurück" onClick="javascript:history.back();">', 1, 12),
(340, 'contestsubmitmsg', 'Meldung: Beitrag empfangen', '<div id="contentheader"><div id="contentlogo">{contesttitle}</div></div>\r\n<div id="contentwindow" style="text-align:center; padding:20px;">\r\n	<b>Vielen Dank für deine Einsendung zum Wettbewerb.</b><p>\r\n	<input type="button" class="button" value="Zurück" onClick="javascript:history.back();">\r\n</div>\r\n', 1, 12),
(341, 'contestentries', 'Wettbewerb Einsendungen', '<div id="contentheader">\r\n	<div id="contentlogo">WETTBEWERB: {contesttitle}</div>\r\n</div>\r\n<div id="contentwindow">\r\n	<div style="width:90%; margin:0px auto;">\r\n		<br>\r\n		{contestdescription}\r\n		<p>\r\n		<-- if img_contest -->\r\n			<table border="0" cellpadding="2" cellspacing="0" style="margin:0px auto;">\r\n			<-- entry -->\r\n				<tr>\r\n					<td width="170" valign="top" rowspan="2"><a href="{entrylink}"><img border="0" src="{entrythumb}" alt=""></a></td>\r\n					<td width="300" valign="top">\r\n						<span style="font-size:10pt"><a href="{entrylink}"><b>{entrytitle}</b></a></span><p>\r\n						{entrydescription}\r\n					</td>\r\n				</tr>\r\n				<tr>\r\n					<td valign="bottom">\r\n						<span style="float:right;">Einsendung von:<b> {entryuser}</b></span>\r\n						<-- if vote -->\r\n							<-- if user_loggedin -->\r\n								<-- if user_voted -->\r\n									<script type="text/javascript">genVoteButtons2({entrypoints});</script>\r\n								<-- else user_voted -->\r\n									<script type="text/javascript">genVoteButtons({entryid});</script>\r\n								<-- /if user_voted -->\r\n							<-- else user_loggedin -->\r\n								<i>Du musst eingeloggt sein, um abzustimmen.</i>\r\n							<-- /if user_loggedin -->\r\n						<-- /if vote -->\r\n					</td>\r\n				</tr>\r\n				<tr><td colspan="2"><hr></td></tr>\r\n			<-- /entry -->\r\n			</table>\r\n		<-- else img_contest -->\r\n			<table border="0" cellpadding="2" cellspacing="0" style="margin:0px auto;" width="500">\r\n			<-- entry -->\r\n				<tr>\r\n					<td><span style="font-size:10pt"><a href="{entrylink}"><b>Einsendung von: {entryuser}</b></a></span></td>\r\n				</tr>\r\n				<tr>\r\n					<td>{entrydescription}</td>\r\n				</tr>\r\n				<tr>\r\n					<td valign="bottom">\r\n						<span style="float:right"><a href="{entrylink}"><b>-> ...mehr</b></a></span>\r\n						<-- if vote -->\r\n							<-- if user_loggedin -->\r\n								<-- if user_voted -->\r\n									<script type="text/javascript">genVoteButtons2({entrypoints});</script>\r\n								<-- else user_voted -->\r\n									<script type="text/javascript">genVoteButtons({entryid});</script>\r\n								<-- /if user_voted -->\r\n							<-- else user_loggedin -->\r\n								<i>Du musst eingeloggt sein, um abzustimmen.</i>\r\n							<-- /if user_loggedin -->\r\n						<-- /if vote -->\r\n					</td>\r\n				</tr>\r\n				<tr><td colspan="2"><hr></td></tr>\r\n			<-- /entry -->\r\n			</table>\r\n		<-- /if img_contest -->\r\n	</div>\r\n	<div align="right">\r\n		<-- page -->\r\n			<-- if currentpage -->\r\n				<b>[{pagenum}]</b>\r\n			<-- else currentpage -->\r\n				<a href="{pagelink}">[{pagenum}]</a>\r\n			<-- /if currentpage -->\r\n		<-- /page -->\r\n	</div>\r\n</div>', 1, 12),
(342, 'contestentrieshidden', 'Meldung: Einsendungen verborgen', '<div id="contentheader"><div id="contentlogo">{contesttitle}</div></div>\r\n<div id="contentwindow" style="text-align:center; padding:20px;">\r\n	<b>Die Einsendungen dieses Wettbewerbs sind unsichtbar.</b><p>\r\n	<input type="button" class="button" value="Zurück" onClick="javascript:history.back();">\r\n</div>\r\n', 1, 12),
(344, 'contestentry', 'Einsendung Detailansicht', '<div id="contentheader">\r\n	<div id="contentlogo">\r\n		{contesttitle} - {entrytitle} - ({currententry}/{totalentries})\r\n	</div>\r\n</div>\r\n<div id="contentwindow" style="text-align:center;">\r\n	<table border="0" cellpadding="0" cellspacing="0" width="95%" align="center" style="table-layout:fixed;">\r\n		<-- if img_contest -->\r\n		<tr>\r\n			<td colspan="2" style="padding-bottom:5px;" align="center">\r\n				<a href="{image}" target="_blank">\r\n					<img border="0" src="{image}" alt="" style="max-width:100%;">\r\n				</a>\r\n			</td>\r\n		</tr>\r\n		<-- /if img_contest -->\r\n		<tr>\r\n			<td colspan="2" align="left">{entrydescription}<p></td>\r\n		</tr>\r\n		<tr>\r\n			<td align="left">\r\n				<-- if vote -->\r\n					<-- if user_loggedin -->\r\n						<-- if user_voted -->\r\n							<script type="text/javascript">genVoteButtons2({entrypoints});</script>\r\n						<-- else user_voted -->\r\n							<script type="text/javascript">genVoteButtons({entryid});</script>\r\n						<-- /if user_voted -->\r\n					<-- else user_loggedin -->\r\n						<i>Du musst eingeloggt sein, um abzustimmen.</i>\r\n					<-- /if user_loggedin -->\r\n				<-- /if vote -->\r\n			</td>\r\n			<td align="right">Einsendung von: <b>{username}</b></td>\r\n		</tr>\r\n		<tr><td colspan="2" style="height:10px;"></td></tr>\r\n		<tr>\r\n			<td align="left"><a href="{prevlink}" class="button" style="display:block; width:50px; text-align:center;">Zurück</a></td>\r\n			<td align="right"><a href="{nextlink}" class="button" style="display:block; width:50px; text-align:center;">Weiter</a></td>\r\n		</tr>\r\n		<tr>\r\n			<td colspan="2" align="center"><a href="{contestlink}" style="text-decoration:underline;">Zurück zur Übersicht</a></td>\r\n		</tr>\r\n	</table>\r\n</div>', 1, 12),
(347, 'newssubmitted', 'Meldung: News eingesendet', '<div id="contentheader"><div id="contentlogo">NEWS EINSENDEN</div></div>\r\n<div id="contentwindow" style="text-align:center; padding:20px;">\r\n<-- if submitted -->\r\n	<b>Vielen dank für deine Einsendung</b>\r\n<-- else submitted -->\r\n	<b>News konnte nicht eingesendet werden. Bitte versuche es später erneut.</b>\r\n	<p>\r\n	<input type="button" class="button" value="Zurück" onClick="javascript:history.back();">\r\n<-- /if submitted -->\r\n</div>\r\n', 1, 2),
(343, 'contestvotescript', 'Wettbewerb Javascript', '<script type="text/javascript">\r\n	function genVoteButtons(entryid)\r\n	{\r\n		document.writeln(''<div id="entry''+entryid+''">'');\r\n		document.writeln(''<img id="star_''+entryid+''_1" border="0" src="images/styles/froggreen/star_empty.gif" alt="" style="cursor:pointer;" onmouseover="highStar(''+entryid+'', 1)" onclick="voteEntry(''+entryid+'', 1)">'');\r\n		document.writeln(''<img id="star_''+entryid+''_2" border="0" src="images/styles/froggreen/star_empty.gif" alt="" style="cursor:pointer;" onmouseover="highStar(''+entryid+'', 2)" onclick="voteEntry(''+entryid+'', 2)">'');\r\n		document.writeln(''<img id="star_''+entryid+''_3" border="0" src="images/styles/froggreen/star_empty.gif" alt="" style="cursor:pointer;" onmouseover="highStar(''+entryid+'', 3)" onclick="voteEntry(''+entryid+'', 3)">'');\r\n		document.writeln(''<img id="star_''+entryid+''_4" border="0" src="images/styles/froggreen/star_empty.gif" alt="" style="cursor:pointer;" onmouseover="highStar(''+entryid+'', 4)" onclick="voteEntry(''+entryid+'', 4)">'');\r\n		document.writeln(''<img id="star_''+entryid+''_5" border="0" src="images/styles/froggreen/star_empty.gif" alt="" style="cursor:pointer;" onmouseover="highStar(''+entryid+'', 5)" onclick="voteEntry(''+entryid+'', 5)">'');\r\n		document.writeln(''</div>'');\r\n	}\r\n	function highStar(entryid, starnum)\r\n	{\r\n		document.getElementById(''star_''+entryid+''_1'').src = "images/styles/froggreen/star_empty.gif";\r\n		document.getElementById(''star_''+entryid+''_2'').src = "images/styles/froggreen/star_empty.gif";\r\n		document.getElementById(''star_''+entryid+''_3'').src = "images/styles/froggreen/star_empty.gif";\r\n		document.getElementById(''star_''+entryid+''_4'').src = "images/styles/froggreen/star_empty.gif";\r\n		document.getElementById(''star_''+entryid+''_5'').src = "images/styles/froggreen/star_empty.gif";\r\n\r\n		for (i=1; i<=starnum; i++)\r\n		{\r\n			document.getElementById(''star_''+entryid+''_''+i).src = "images/styles/froggreen/star_full.gif";\r\n		}\r\n	}\r\n	function voteEntry(entryid, points)\r\n	{\r\n		http_request = false;\r\n		if (window.XMLHttpRequest) // Mozilla, Safari,...\r\n		{\r\n			http_request = new XMLHttpRequest();\r\n		}\r\n		else if (window.ActiveXObject) // IE\r\n		{\r\n			try\r\n			{\r\n				http_request = new ActiveXObject("Msxml2.XMLHTTP");\r\n			}\r\n			catch (e)\r\n			{\r\n				try\r\n				{\r\n					http_request = new ActiveXObject("Microsoft.XMLHTTP");\r\n				}\r\n				catch (e) {}\r\n			}\r\n		}\r\n		if (!http_request)\r\n		{\r\n			alert("Ende :( Kann keine XMLHTTP-Instanz erzeugen");\r\n			return false;\r\n		}\r\n		params="entryid="+entryid+"&points="+points;\r\n		http_request.open("POST", "inc/votecontest.php", true);\r\n		http_request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");\r\n		http_request.setRequestHeader("Content-length", params.length);\r\n		http_request.setRequestHeader("Connection", "close");\r\n		http_request.onreadystatechange = setVoteButtons;\r\n		http_request.send(params);\r\n	}\r\n	function setVoteButtons()\r\n	{\r\n		if (http_request.readyState == 4)\r\n		{\r\n			if (http_request.responseText)\r\n			{\r\n				var result = http_request.responseText.split(''_'');\r\n				var newHTML = "";\r\n				for (i=1; i<=result[1]; i++)\r\n				{\r\n					newHTML += ''<img border="0" src="images/styles/froggreen/star_full2.gif" alt=""> '';\r\n				}\r\n				for (i; i<=5; i++)\r\n				{\r\n					newHTML += ''<img border="0" src="images/styles/froggreen/star_empty.gif" alt=""> '';\r\n				}\r\n				document.getElementById(''entry''+result[0]).innerHTML = newHTML;\r\n			}\r\n		}\r\n	}\r\n	function genVoteButtons2(points)\r\n	{\r\n		document.writeln(''<div>'');\r\n		for (i=1; i<=points; i++)\r\n		{\r\n			document.writeln(''<img border="0" src="images/styles/froggreen/star_full2.gif" alt="">'');\r\n		}\r\n		for (i; i<=5; i++)\r\n		{\r\n			document.writeln(''<img border="0" src="images/styles/froggreen/star_empty.gif" alt="">'');\r\n		}\r\n		document.writeln(''</div>'');\r\n	}\r\n</script>', 1, 12),
(345, 'videotag', 'Video Tag', '<div align="center">\r\n	<div id="contentheader" align="left" style="width:480px;">\r\n		<div id="contentlogo"><a href="{link}" style="color:#FFFFFF;">{name}</a></div>\r\n	</div>\r\n	<div>\r\n		<script type="text/javascript">\r\n			var width = 480;\r\n			var height = 270;\r\n			var color = ''{color}'';\r\n			var video = ''{video}'';\r\n			var style = ''{style}'';\r\n		</script>\r\n		<script src="inc/frogplayer.js" type="text/javascript"></script>\r\n	</div>\r\n</div>\r\n', 1, 10),
(419, 'ageblocker', 'Meldung: Altersfreigabe', '<div id="contentheader"><div id="contentlogo">ALTERSFREIGABE</div></div>\r\n<div id="contentwindow" style="text-align:center; padding:20px;">\r\n	Dieser Inhalt wird erst ab {time} Uhr verfügbar sein.\r\n</div>', 1, 1),
(420, 'bb_ageblocker', 'FS-Code: Altersfreigabe', '<div style="text-align:center; padding:20px; border:1px dashed #017801;">Dieser Inhalt wird erst ab {time} Uhr verfügbar sein.</div>', 1, 1),
(416, 'pwrecovered', 'Meldung: Passwort wurde verschickt', '<div id="contentheader"><div id="contentlogo">PASSWORT VERGESSEN</div></div>\r\n<div id="contentwindow" style="text-align:center; padding:20px;">\r\n<-- if recovered -->\r\n	<b>Dir wurde ein neues Passwort zugeschickt.</b>\r\n<-- else recovered -->\r\n	<b>Passwort konnte nicht verschickt werden, da die E-Mail Adresse nicht gefunden wurde.</b>\r\n	<p>\r\n	<input type="button" class="button" value="Zurück" onClick="javascript:history.back();">\r\n<-- /if recovered -->\r\n</div>\r\n', 1, 1),
(417, 'polltag', 'Umfrage-Tag', '<div id="contentheader">\r\n	<div id="contentlogo">\r\n		<div style="float:right;">{fromdate} - {todate}</div>\r\n		{question}\r\n	</div>\r\n</div>\r\n<div id="contentwindow">\r\n	<table border="0" width="100%" cellpadding="0" cellspacing="2">\r\n		<-- answer -->\r\n		<tr>\r\n			<td class="alt{altnum}"><div style="background-color:#666666; height:5px; width:{width}px;"></div></td>\r\n			<td class="alt{altnum}">{answer}</td>\r\n			<td align="center" class="alt{altnum}">{hits}</td>\r\n			<td align="center" class="alt{altnum}">{percent} %</td>\r\n		</tr>\r\n		<-- /answer -->\r\n	</table>\r\n</div>', 1, 6),
(414, 'pwrecover', 'Passwort zusenden Formular', '<div id="contentheader"><div id="contentlogo">PASSWORT VERGESSEN</div></div>\r\n<div id="contentwindow">\r\n	<form action="?section=pwrecover" method="post">\r\n	<table border="0" cellpadding="2" cellspacing="0" style="margin:0px auto; width:500px;">\r\n		<tr>\r\n			<td colspan="2">\r\n				 Wenn du dein Passwort vergessen hast, kannst du hier die E-Mail Adresse\r\n				angeben, mit der du dich registriert hast. Dann wird dir ein neues Passwort\r\n				zugeschickt.\r\n			</td>\r\n		</tr>\r\n		<tr>\r\n			<td><b>E-Mail:</b></td>\r\n			<td><input name="email" class="textinput" style="width:200px;"></td>\r\n		</tr>\r\n		<tr>\r\n			<td colspan="2" align="right">\r\n				<input type="submit" class="button" value="Absenden">\r\n			</td>\r\n		</tr>\r\n	</table>\r\n	</form>\r\n</div>', 1, 1),
(415, 'pwrecovermail', 'Passwort zusenden E-Mail', 'Du hast dein Passwort auf {pagename} wiederherstellen lassen. Hier sind deine neuen Zugangsdaten:\r\n\r\nBenutzername: {username}\r\nPasswort: {password}\r\n\r\nDies ist eine automatisch generierte E-Mail.', 1, 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fsxl_templatevars`
--

CREATE TABLE IF NOT EXISTS `fsxl_templatevars` (
  `name` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `intemplate` varchar(255) COLLATE latin1_general_ci NOT NULL,
  UNIQUE KEY `name` (`name`,`intemplate`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Daten für Tabelle `fsxl_templatevars`
--

INSERT INTO `fsxl_templatevars` (`name`, `intemplate`) VALUES
('{altnum}', 'articlelist'),
('{altnum}', 'download'),
('{altnum}', 'gallerylist'),
('{altnum}', 'link_cat'),
('{altnum}', 'link_list'),
('{altnum}', 'polldetail'),
('{altnum}', 'polllist'),
('{altnum}', 'polltag'),
('{altnum}', 'shopltlist'),
('{altnum}', 'tickerdetail'),
('{altnum}', 'tickerlist'),
('{altnum}', 'video_list'),
('{answerid}', 'pollbox'),
('{answer}', 'pollbox'),
('{answer}', 'polldetail'),
('{answer}', 'polltag'),
('{articleheadlines}', 'header'),
('{articleurl}', 'articlelist'),
('{article}', 'articlelist'),
('{autorurl}', 'download'),
('{autor}', 'download'),
('{catdescription}', 'articlelist'),
('{catdescription}', 'gallerylist'),
('{catdescription}', 'link_cat'),
('{catdescription}', 'shopltlist'),
('{catdescription}', 'video_list'),
('{catid}', 'news_body'),
('{catname}', 'article'),
('{catname}', 'articlelist'),
('{catname}', 'download'),
('{catname}', 'gallerylist'),
('{catname}', 'link_cat'),
('{catname}', 'news_body'),
('{catname}', 'shopltlist'),
('{catname}', 'video_list'),
('{caturl}', 'article'),
('{caturl}', 'articlelist'),
('{caturl}', 'gallerylist'),
('{caturl}', 'shopltlist'),
('{caturl}', 'video_list'),
('{color}', 'videotag'),
('{color}', 'video_detail'),
('{commentautor}', 'news_detail'),
('{commentdate}', 'news_detail'),
('{commentlink}', 'news_body'),
('{commentnum}', 'news_detail'),
('{comments}', 'news_body'),
('{commenttext}', 'news_detail'),
('{contestdescription}', 'contestentries'),
('{contestid}', 'contestbody'),
('{contestlink}', 'contestentry'),
('{contesttitle}', 'contestentries'),
('{contesttitle}', 'contestentrieshidden'),
('{contesttitle}', 'contestentry'),
('{contesttitle}', 'contestsubmitmsg'),
('{copyright}', 'footer'),
('{css}', 'header'),
('{currententry}', 'contestentry'),
('{currentpage}', 'article'),
('{currentpic}', 'gallery_detail'),
('{date}', 'article'),
('{date}', 'articleheader'),
('{date}', 'articleheadlines'),
('{date}', 'articlelist'),
('{date}', 'download'),
('{date}', 'downloadheadlines'),
('{date}', 'gallery'),
('{date}', 'galleryheadlines'),
('{date}', 'gallerylist'),
('{date}', 'linkheadlines'),
('{date}', 'link_cat'),
('{date}', 'newsheadlines'),
('{date}', 'news_body'),
('{date}', 'tickerdetail'),
('{date}', 'video_detail'),
('{date}', 'video_list'),
('{deep}', 'dloverview'),
('{description}', 'contestbody'),
('{description}', 'gallery'),
('{description}', 'link_cat'),
('{description}', 'link_list'),
('{description}', 'tickerbox'),
('{description}', 'tickerdetail'),
('{description}', 'tickerlist'),
('{description}', 'video_detail'),
('{description}', 'video_list'),
('{detaillink}', 'gallerytag'),
('{detaillink}', 'gallery_thumb'),
('{downloadid}', 'dloverview'),
('{downloadname}', 'dloverview'),
('{email}', 'profile'),
('{enddate}', 'contestbody'),
('{enddate}', 'polldetail'),
('{enddate}', 'polllist'),
('{entrydescription}', 'contestentries'),
('{entrydescription}', 'contestentry'),
('{entryid}', 'contestentries'),
('{entryid}', 'contestentry'),
('{entrylink}', 'contestentries'),
('{entrypoints}', 'contestentries'),
('{entrypoints}', 'contestentry'),
('{entrythumb}', 'contestentries'),
('{entrytitle}', 'contestentries'),
('{entrytitle}', 'contestentry'),
('{entryuser}', 'contestentries'),
('{folderid}', 'dloverview'),
('{folderid}', 'download'),
('{foldername}', 'dloverview'),
('{foldertext}', 'dloverview'),
('{fromdate}', 'polltag'),
('{gallerydate}', 'gallery_detail'),
('{galleryheadlines}', 'header'),
('{gallerylink}', 'gallerytag'),
('{gallerylink}', 'gallery_detail'),
('{galleryname}', 'gallerytag'),
('{galleryname}', 'gallery_detail'),
('{galleryurl}', 'gallerylist'),
('{gallery}', 'gallerylist'),
('{headlines}', 'header'),
('{hitsall}', 'statbox'),
('{hitstoday}', 'statbox'),
('{hits}', 'pollbox'),
('{hits}', 'polldetail'),
('{hits}', 'polllist'),
('{hits}', 'polltag'),
('{homepage}', 'profile'),
('{icq}', 'profile'),
('{image}', 'contestentry'),
('{img}', 'potm'),
('{img}', 'shopltlist'),
('{lastentry}', 'tickerbox'),
('{lastentry}', 'tickerlist'),
('{linkheadlines}', 'header'),
('{linkhits}', 'download'),
('{linkname}', 'download'),
('{linksize}', 'download'),
('{linktarget}', 'download'),
('{linkurl}', 'download'),
('{link}', 'videotag'),
('{loginform}', 'footer'),
('{loginform}', 'header'),
('{message}', 'errormsg'),
('{minutes}', 'errortimeout'),
('{msn}', 'profile'),
('{name}', 'articleheader'),
('{name}', 'download'),
('{name}', 'gallery'),
('{name}', 'link_cat'),
('{name}', 'link_list'),
('{name}', 'shopltbox'),
('{name}', 'shopltlist'),
('{name}', 'tickerbox'),
('{name}', 'tickerdetail'),
('{name}', 'tickerlist'),
('{name}', 'videotag'),
('{name}', 'video_detail'),
('{name}', 'video_list'),
('{newsid}', 'news_detail'),
('{news}', 'newsarchiv'),
('{news}', 'news_detail'),
('{nextlink}', 'contestentry'),
('{nextlink}', 'gallery_detail'),
('{numfiles}', 'dloverview'),
('{numpics}', 'gallery'),
('{pagelink}', 'article'),
('{pagelink}', 'contestentries'),
('{pagelink}', 'newsarchiv'),
('{pagename}', 'pwrecovermail'),
('{pagenum}', 'article'),
('{pagenum}', 'contestentries'),
('{pagenum}', 'newsarchiv'),
('{pageselect}', 'news_detail'),
('{pagetitle}', 'dloverview'),
('{pagetitle}', 'header'),
('{password}', 'pwrecovermail'),
('{percent}', 'pollbox'),
('{percent}', 'polldetail'),
('{percent}', 'polltag'),
('{piclink}', 'gallery_detail'),
('{picname}', 'potm'),
('{pollid}', 'pollbox'),
('{pollid}', 'polldetail'),
('{polltype}', 'pollbox'),
('{poll}', 'footer'),
('{poll}', 'header'),
('{potm}', 'footer'),
('{potm}', 'header'),
('{preview}', 'articlelist'),
('{preview}', 'gallerylist'),
('{prevlink}', 'contestentry'),
('{prevlink}', 'gallery_detail'),
('{price}', 'shopltbox'),
('{price}', 'shopltlist'),
('{question}', 'pollbox'),
('{question}', 'polldetail'),
('{question}', 'polllist'),
('{question}', 'polltag'),
('{regdate}', 'profile'),
('{script}', 'tickerdetail'),
('{search}', 'footer'),
('{search}', 'header'),
('{seconds}', 'errorsearchtime'),
('{seconds}', 'errorspamblock'),
('{shoplt}', 'footer'),
('{shoplt}', 'header'),
('{size}', 'tagcloud'),
('{startdate}', 'contestbody'),
('{startdate}', 'polldetail'),
('{startdate}', 'polllist'),
('{stat}', 'footer'),
('{stat}', 'header'),
('{styleoptions}', 'profile'),
('{style}', 'videotag'),
('{style}', 'video_detail'),
('{subcatname}', 'link_cat'),
('{subcaturl}', 'link_cat'),
('{tagcloud}', 'footer'),
('{tagcloud}', 'header'),
('{tag}', 'linkheadlines'),
('{text}', 'article'),
('{text}', 'download'),
('{text}', 'gallery_detail'),
('{text}', 'gallery_thumb'),
('{text}', 'news_body'),
('{text}', 'news_detail'),
('{text}', 'shopltlist'),
('{text}', 'tickerdetail'),
('{thumblink}', 'gallery_thumb'),
('{thumbs}', 'gallery'),
('{thumb}', 'gallerytag'),
('{thumb}', 'potm'),
('{thumb}', 'shopltbox'),
('{thumb}', 'shopltlist'),
('{ticker}', 'footer'),
('{ticker}', 'header'),
('{time}', 'ageblocker'),
('{time}', 'bb_ageblocker'),
('{time}', 'contact'),
('{time}', 'news_detail'),
('{time}', 'regform'),
('{title}', 'article'),
('{title}', 'articleheadlines'),
('{title}', 'contestbody'),
('{title}', 'downloadheadlines'),
('{title}', 'galleryheadlines'),
('{title}', 'gallerytag'),
('{title}', 'gallery_detail'),
('{title}', 'gallery_thumb'),
('{title}', 'linkheadlines'),
('{title}', 'newsheadlines'),
('{title}', 'news_body'),
('{title}', 'potm'),
('{todate}', 'polltag'),
('{totaldls}', 'download'),
('{totalentries}', 'contestentry'),
('{totalpics}', 'gallery_detail'),
('{trys}', 'errorwrongpass'),
('{url}', 'articleheader'),
('{url}', 'articleheadlines'),
('{url}', 'downloadheadlines'),
('{url}', 'galleryheadlines'),
('{url}', 'linkheadlines'),
('{url}', 'link_cat'),
('{url}', 'link_list'),
('{url}', 'newsheadlines'),
('{url}', 'polllist'),
('{url}', 'shopltbox'),
('{url}', 'shopltlist'),
('{url}', 'tickerbox'),
('{url}', 'tickerlist'),
('{url}', 'video_list'),
('{username}', 'article'),
('{username}', 'contestentry'),
('{username}', 'news_body'),
('{username}', 'news_detail'),
('{username}', 'profile'),
('{username}', 'pwrecovermail'),
('{username}', 'regdone'),
('{useronline}', 'statbox'),
('{video}', 'videotag'),
('{video}', 'video_detail'),
('{views}', 'download'),
('{visitsall}', 'statbox'),
('{visitstoday}', 'statbox'),
('{width}', 'polltag'),
('{word}', 'tagcloud'),
('{yearoptions}', 'newsarchiv');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fsxl_template_history`
--

CREATE TABLE IF NOT EXISTS `fsxl_template_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tpl` int(11) NOT NULL,
  `autor` int(11) NOT NULL,
  `date` int(11) NOT NULL,
  `code` text COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=6 ;

--
-- Daten für Tabelle `fsxl_template_history`
--

INSERT INTO `fsxl_template_history` (`id`, `tpl`, `autor`, `date`, `code`) VALUES
(1, 37, 1, 1270214961, '<div id="contentheader">\r\n	<div id="contentlogo">\r\n		<b><a href="?section=download&folder={folderid}" style="color:#FFFFFF;">{catname}</a> > {name}</b>\r\n	</div>\r\n</div>\r\n<div id="contentwindow">\r\n	<table border="0" cellpadding="2" cellspacing="0" style="margin:0px auto; width:90%;">\r\n		<tr>\r\n			<td width="150"><b>Name:</b></td>\r\n			<td>{name}</td>\r\n		</tr>\r\n		<tr>\r\n			<td><b>Autor:</b></td>\r\n			<td>{autor}</td>\r\n		</tr>\r\n		<tr>\r\n			<td><b>Hinzugefügt:</b></td>\r\n			<td>{date}</td>\r\n		</tr>\r\n		<tr>\r\n			<td><b>Views:</b></td>\r\n			<td>{views}</td>\r\n		</tr>\r\n		<tr>\r\n			<td valign="top"><b>Beschreibung:</b></td>\r\n			<td>{text}</td>\r\n		</tr>\r\n	</table>\r\n</div>\r\n<-- if permission -->\r\n<table border="0" cellpadding="2" cellspacing="1" width="100%" style="margin:-1px;">\r\n	<tr>\r\n		<td class="thead"><b>Link</b></td>\r\n		<td class="thead"><b>Größe</b></td>\r\n		<td class="thead"><b>Hits</b></td>\r\n	</tr>\r\n	<-- link -->\r\n	<tr>\r\n		<td class="alt{altnum}"><a href="{linkurl}" target="{linktarget}">{linkname}</a></td>\r\n		<td class="alt{altnum}" align="center">{linksize}</td>\r\n		<td class="alt{altnum}" align="center">{linkhits}</td>\r\n	</tr>\r\n	<-- /link -->\r\n</table>\r\n<-- else permission -->\r\n<table border="0" cellpadding="2" cellspacing="0" width="100%" style="margin:-1px;">\r\n	<tr>\r\n		<td class="thead">Links</td>\r\n	</tr>\r\n	<tr>\r\n		<td class="alt{altnum}"><b>Du musst angemeldet sein, um diesen Download zu starten.</b></td>\r\n	</tr>\r\n</table>\r\n<-- /if permission -->\r\n'),
(2, 37, 1, 1270215478, '<div id="contentheader">\r\n	<div id="contentlogo">\r\n		<b><a href="?section=download&folder={folderid}" style="color:#FFFFFF;">{catname}</a> > {name}</b>\r\n	</div>\r\n</div>\r\n<div id="contentwindow">\r\n	<table border="0" cellpadding="2" cellspacing="0" style="margin:0px auto; width:90%;">\r\n		<tr>\r\n			<td width="150"><b>Name:</b></td>\r\n			<td>{name}</td>\r\n		</tr>\r\n		<tr>\r\n			<td><b>Autor:</b></td>\r\n			<td>\r\n				<-- if autorurl -->\r\n					<a href="{autorurl}" target="_blank">{autor}</a>\r\n				<-- else autorurl -->\r\n					{autor}\r\n				<-- /if autorurl -->\r\n			</td>\r\n		</tr>\r\n		<tr>\r\n			<td><b>Hinzugefügt:</b></td>\r\n			<td>{date}</td>\r\n		</tr>\r\n		<tr>\r\n			<td><b>Views:</b></td>\r\n			<td>{views}</td>\r\n		</tr>\r\n		<tr>\r\n			<td valign="top"><b>Beschreibung:</b></td>\r\n			<td>{text}</td>\r\n		</tr>\r\n	</table>\r\n</div>\r\n<-- if permission -->\r\n<table border="0" cellpadding="2" cellspacing="1" width="100%" style="margin:-1px;">\r\n	<tr>\r\n		<td class="thead"><b>Link</b></td>\r\n		<td class="thead"><b>Größe</b></td>\r\n		<td class="thead"><b>Hits</b></td>\r\n	</tr>\r\n	<-- link -->\r\n	<tr>\r\n		<td class="alt{altnum}"><a href="{linkurl}" target="{linktarget}">{linkname}</a></td>\r\n		<td class="alt{altnum}" align="center">{linksize}</td>\r\n		<td class="alt{altnum}" align="center">{linkhits}</td>\r\n	</tr>\r\n	<-- /link -->\r\n</table>\r\n<-- else permission -->\r\n<table border="0" cellpadding="2" cellspacing="0" width="100%" style="margin:-1px;">\r\n	<tr>\r\n		<td class="thead">Links</td>\r\n	</tr>\r\n	<tr>\r\n		<td class="alt{altnum}"><b>Du musst angemeldet sein, um diesen Download zu starten.</b></td>\r\n	</tr>\r\n</table>\r\n<-- /if permission -->\r\n'),
(3, 37, 1, 1270215527, '<div id="contentheader">\r\n	<div id="contentlogo">\r\n		<b><a href="?section=download&folder={folderid}" style="color:#FFFFFF;">{catname}</a> > {name}</b>\r\n	</div>\r\n</div>\r\n<div id="contentwindow">\r\n	<table border="0" cellpadding="2" cellspacing="0" style="margin:0px auto; width:90%;">\r\n		<tr>\r\n			<td width="150"><b>Name:</b></td>\r\n			<td>{name}</td>\r\n		</tr>\r\n		<tr>\r\n			<td><b>Autor:</b></td>\r\n			<td>\r\n				<-- if autorurl -->\r\n					<a href="{autorurl}" target="_blank">{autor}</a>\r\n				<-- else autorurl -->\r\n					{autor}\r\n				<-- /if autorurl -->\r\n			</td>\r\n		</tr>\r\n		<tr>\r\n			<td><b>Hinzugefügt:</b></td>\r\n			<td>{date}</td>\r\n		</tr>\r\n		<tr>\r\n			<td><b>Views:</b></td>\r\n			<td>{views}</td>\r\n		</tr>\r\n		<tr>\r\n			<td valign="top"><b>Beschreibung:</b></td>\r\n			<td>{text}</td>\r\n		</tr>\r\n	</table>\r\n</div>\r\n<-- if permission -->\r\n<table border="0" cellpadding="2" cellspacing="1" width="100%" style="margin:-1px;">\r\n	<tr>\r\n		<td class="thead"><b>Link</b></td>\r\n		<td class="thead"><b>Größe</b></td>\r\n		<td class="thead"><b>Hits</b></td>\r\n	</tr>\r\n	<-- link -->\r\n	<tr>\r\n		<td class="alt{altnum}"><a href="{linkurl}" target="{linktarget}">{linkname}</a></td>\r\n		<td class="alt{altnum}" align="center">{linksize}</td>\r\n		<td class="alt{altnum}" align="center">{linkhits}</td>\r\n	</tr>\r\n	<-- /link -->\r\n	<tr>\r\n		<td colspan="3" class="thead" align="right"><b>Total: {totaldls}</b></td>\r\n	</tr>\r\n</table>\r\n<-- else permission -->\r\n<table border="0" cellpadding="2" cellspacing="0" width="100%" style="margin:-1px;">\r\n	<tr>\r\n		<td class="thead">Links</td>\r\n	</tr>\r\n	<tr>\r\n		<td class="alt{altnum}"><b>Du musst angemeldet sein, um diesen Download zu starten.</b></td>\r\n	</tr>\r\n</table>\r\n<-- /if permission -->\r\n'),
(4, 340, 1, 1270833574, '<div id="contentheader"><div id="contentlogo">{contesttitle}</div></div>\r\n<div id="contentwindow" style="text-align:center; padding:20px;">\r\n	<b>Vielen dank für deine Einseundung zum Wettbewerb.</b><p>\r\n	<input type="button" class="button" value="Zurück" onClick="javascript:history.back();">\r\n</div>\r\n'),
(5, 418, 1, 1283678985, '<div id="contentheader"><div id="contentlogo">ALTERSFREIGABE</div></div>\r\n<div id="contentwindow" style="text-align:center; padding:20px;">\r\n	Dieser Inhalt wird erst ab {time} Uhr verfügbar sein.\r\n</div>');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fsxl_ticker`
--

CREATE TABLE IF NOT EXISTS `fsxl_ticker` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `text` text COLLATE latin1_general_ci NOT NULL,
  `active` tinyint(4) NOT NULL,
  `rss` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `interval` int(11) NOT NULL,
  `url` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `show` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fsxl_ticker_text`
--

CREATE TABLE IF NOT EXISTS `fsxl_ticker_text` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ticker` int(11) NOT NULL,
  `text` text COLLATE latin1_general_ci NOT NULL,
  `date` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQUE` (`ticker`,`date`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fsxl_tplvars`
--

CREATE TABLE IF NOT EXISTS `fsxl_tplvars` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `type` tinyint(4) NOT NULL,
  `display` tinyint(4) NOT NULL,
  `interval` int(11) NOT NULL,
  `startdate` int(11) NOT NULL,
  `enddate` int(11) NOT NULL,
  `include` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `zone` int(11) NOT NULL,
  `section` varchar(255) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fsxl_tplvars_code`
--

CREATE TABLE IF NOT EXISTS `fsxl_tplvars_code` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `var` int(11) NOT NULL,
  `code` text COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fsxl_user`
--

CREATE TABLE IF NOT EXISTS `fsxl_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `password` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `salt` varchar(255) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQUE` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=2 ;

--
-- Daten für Tabelle `fsxl_user`
--

INSERT INTO `fsxl_user` (`id`, `name`, `password`, `salt`) VALUES
(1, 'Admin', '21232f297a57a5a743894a0e4a801fc3', '');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fsxl_useraccess`
--

CREATE TABLE IF NOT EXISTS `fsxl_useraccess` (
  `userid` int(10) unsigned NOT NULL,
  `mod` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `page` varchar(255) COLLATE latin1_general_ci NOT NULL,
  UNIQUE KEY `userid` (`userid`,`mod`,`page`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fsxl_userdata`
--

CREATE TABLE IF NOT EXISTS `fsxl_userdata` (
  `userid` int(10) unsigned NOT NULL,
  `email` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `adminstyle` tinyint(4) NOT NULL,
  `style` int(10) unsigned NOT NULL,
  `homepage` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `icq` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `msn` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `regdate` int(11) NOT NULL,
  `regip` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `editor` tinyint(4) NOT NULL,
  `adminlang` int(11) NOT NULL,
  PRIMARY KEY (`userid`),
  UNIQUE KEY `UNIQUE` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Daten für Tabelle `fsxl_userdata`
--

INSERT INTO `fsxl_userdata` (`userid`, `email`, `adminstyle`, `style`, `homepage`, `icq`, `msn`, `regdate`, `regip`, `editor`, `adminlang`) VALUES
(1, 'mail@frogspawn.de', 1, 0, 'http://www.frogspawn.de', '', '', 0, '', 1, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fsxl_user_groupaccess`
--

CREATE TABLE IF NOT EXISTS `fsxl_user_groupaccess` (
  `group` int(11) NOT NULL,
  `mod` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `page` varchar(255) COLLATE latin1_general_ci NOT NULL,
  UNIQUE KEY `group` (`group`,`mod`,`page`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fsxl_user_groupconnect`
--

CREATE TABLE IF NOT EXISTS `fsxl_user_groupconnect` (
  `user` int(11) NOT NULL,
  `group` int(11) NOT NULL,
  UNIQUE KEY `user` (`user`,`group`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fsxl_user_groups`
--

CREATE TABLE IF NOT EXISTS `fsxl_user_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fsxl_videos`
--

CREATE TABLE IF NOT EXISTS `fsxl_videos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cat` int(11) NOT NULL,
  `date` int(11) NOT NULL,
  `name` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `url` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `text` text COLLATE latin1_general_ci NOT NULL,
  `regonly` tinyint(4) NOT NULL,
  `age` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=2 ;

--
-- Daten für Tabelle `fsxl_videos`
--

INSERT INTO `fsxl_videos` (`id`, `cat`, `date`, `name`, `url`, `text`, `regonly`, `age`) VALUES
(1, 0, 1283677980, 'Testvideo', 'sdsdf', 'asdad', 0, 16);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fsxl_video_cat`
--

CREATE TABLE IF NOT EXISTS `fsxl_video_cat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `text` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fsxl_wordindex`
--

CREATE TABLE IF NOT EXISTS `fsxl_wordindex` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `word` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `word` (`word`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=13 ;

--
-- Daten für Tabelle `fsxl_wordindex`
--

INSERT INTO `fsxl_wordindex` (`id`, `word`) VALUES
(1, 'Willkommen'),
(2, 'Frogsystem'),
(3, 'DOwnload'),
(4, 'Testdownload'),
(5, ''),
(6, 'r'),
(7, 'Text'),
(8, 'hier'),
(9, 'wird'),
(10, 'verschlüsselt'),
(11, 'Seite1'),
(12, 'Testartikel');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fsxl_zones`
--

CREATE TABLE IF NOT EXISTS `fsxl_zones` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `style` int(10) unsigned NOT NULL,
  `url` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `page` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `single` tinyint(4) NOT NULL DEFAULT '0',
  `headlines` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `url` (`url`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=10 ;

--
-- Daten für Tabelle `fsxl_zones`
--

INSERT INTO `fsxl_zones` (`id`, `name`, `style`, `url`, `page`, `single`, `headlines`) VALUES
(1, 'GreenZone', 1, '', '', 0, 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
