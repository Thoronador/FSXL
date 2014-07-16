<?php

if ($_POST[action] == "edit")
{
	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = '$_POST[language]' WHERE `name` = 'syslanguage'");

	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = '$_POST[contactmail]' WHERE `name` = 'kontaktmail'");

	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = '$_POST[systemmail]' WHERE `name` = 'systemmail'");

	$_POST[admin_show_config] = $_POST[admin_show_config] ? 1 : 0;
	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = $_POST[admin_show_config] WHERE `name` = 'admin_show_config'");

	$_POST[admin_jobmail] = $_POST[admin_jobmail] ? 1 : 0;
	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = $_POST[admin_jobmail] WHERE `name` = 'jobmail'");

	$_POST[contacttojob] = $_POST[contacttojob] ? 1 : 0;
	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = $_POST[contacttojob] WHERE `name` = 'contacttojob'");

	settype($_POST[stdstyle], 'integer');
	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = $_POST[stdstyle] WHERE `name` = 'stdstyle'");

	$_POST[userselectstyle] = $_POST[userselectstyle] ? 1 : 0;
	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = $_POST[userselectstyle] WHERE `name` = 'user_select_style'");

	$_POST[showregonly] = $_POST[showregonly] ? 1 : 0;
	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = $_POST[showregonly] WHERE `name` = 'showregonly'");

	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = '$_POST[pagetitle]' WHERE `name` = 'pagetitle'");

	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = '$_POST[siteurl]' WHERE `name` = 'siteurl'");

	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = '$_POST[pageid]' WHERE `name` = 'bez'");

	settype($_POST[defaultzone], 'integer');
	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = $_POST[defaultzone] WHERE `name` = 'defaultzone'");

	$_POST[showzonename] = $_POST[showzonename] ? 1 : 0;
	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = $_POST[showzonename] WHERE `name` = 'showzonename'");

	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = '$_POST[startpage]' WHERE `name` = 'startpage'");

	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = '$_POST[dateformat]' WHERE `name` = 'dateformat'");

	settype($_POST[maxwords], 'integer');
	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = $_POST[maxwords] WHERE `name` = 'search_maxwords'");

	settype($_POST[searchtime], 'integer');
	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = $_POST[searchtime] WHERE `name` = 'search_time'");

	settype($_POST[searchpreview], 'integer');
	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = $_POST[searchpreview] WHERE `name` = 'search_previewlength'");

	settype($_POST[tplhistory], 'integer');
	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = $_POST[tplhistory] WHERE `name` = 'tpl_history_steps'");

	$_POST[use_tpl_cache] = $_POST[use_tpl_cache] ? 1 : 0;
	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = $_POST[use_tpl_cache] WHERE `name` = 'use_tpl_cache'");

	$_POST[use_tplvar_cache] = $_POST[use_tplvar_cache] ? 1 : 0;
	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = $_POST[use_tplvar_cache] WHERE `name` = 'use_tplvar_cache'");

	settype($_POST[tagcloud_words], 'integer');
	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = $_POST[tagcloud_words] WHERE `name` = 'tagcloud_words'");

	settype($_POST[tagcloud_minsize], 'integer');
	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = $_POST[tagcloud_minsize] WHERE `name` = 'tagcloud_minsize'");

	settype($_POST[tagcloud_maxsize], 'integer');
	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = $_POST[tagcloud_maxsize] WHERE `name` = 'tagcloud_maxsize'");

	$_POST[usesafelinks] = $_POST[usesafelinks] ? 1 : 0;
	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = $_POST[usesafelinks] WHERE `name` = 'use_safe_links'");

	settype($_POST[loginattempts], 'integer');
	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = $_POST[loginattempts] WHERE `name` = 'login_attempts'");

	settype($_POST[loginblocktime], 'integer');
	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = $_POST[loginblocktime] WHERE `name` = 'login_blocktime'");

	$_POST[admincookielogin] = $_POST[admincookielogin] ? 1 : 0;
	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = $_POST[admincookielogin] WHERE `name` = 'admin_cookielogin'");

	settype($_POST[countersavetime], 'integer');
	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = $_POST[countersavetime] WHERE `name` = 'counter_savetime'");

	$ages = explode(',', $_POST[agerating]);
	foreach($ages AS $key => $value) $ages[$key] = trim($ages[$key]);
	sort($ages);
	$_POST[agerating] = implode(',', $ages);
	@mysql_query("UPDATE `$FSXL[tableset]_config` SET `value` = '$_POST[agerating]' WHERE `name` = 'ageratings'");

	reloadPage('?mod=main&go=config');
}

$FSXL[title] = $FS_PHRASES[main_config_title];

// Neues Admin Template erzeugen
$tpl = new adminPage();

// Formular beginnen
$tpl->openForm('?mod=main&go=config');
$tpl->newHiddenInput('action', 'edit');
$tpl->openTable();

// Adminbereich Konfiguration
$tpl->newTblHeadline($FS_PHRASES[main_config_title_admin]);
$tpl->openTblSelect($FS_PHRASES[main_config_language], 'language', 150, $FS_PHRASES[main_config_language_sub]);
foreach ($FSXL[languages] AS $key => $lang)
{
	$tpl->newSelectOption($key, $lang[1], ($FSXL[config][syslanguage] == $key ? true : false));
}
$tpl->closeTblSelect();
$tpl->newTblCheckbox($FS_PHRASES[main_config_showconfig], 'admin_show_config', ($FSXL[config][admin_show_config] == 1 ? true : false), $FS_PHRASES[main_config_showconfig_sub]);
$tpl->newTblCheckbox($FS_PHRASES[main_config_jobmail], 'admin_jobmail', ($FSXL[config][jobmail] == 1 ? true : false), $FS_PHRASES[main_config_jobmail_sub]);
$tpl->newTblCheckbox($FS_PHRASES[main_config_contacttojob], 'contacttojob', ($FSXL[config][contacttojob] == 1 ? true : false), $FS_PHRASES[main_config_contacttojob_sub]);

// Seiteninformationen Konfiguration
$tpl->newTblSpacer();
$tpl->newTblHeadline($FS_PHRASES[main_config_title_siteinfos]);
$tpl->newTblInput($FS_PHRASES[main_config_pagetitle], 'pagetitle', $FSXL[config][pagetitle], 250, $FS_PHRASES[main_config_pagetitle_sub]);
$tpl->newTblInput($FS_PHRASES[main_config_siteurl], 'siteurl', $FSXL[config][siteurl], 250);
$tpl->openTblSelect($FS_PHRASES[main_config_defaultzone], 'defaultzone', 250, $FS_PHRASES[main_config_defaultzone_sub]);
$index = @mysql_query("SELECT * FROM `$FSXL[tableset]_zones` ORDER BY `name`");
while ($zone = mysql_fetch_assoc($index))
{
	$tpl->newSelectOption($zone[id], $zone[name], ($FSXL[config][defaultzone] == $zone[id] ? true : false));
}
$tpl->closeTblSelect();
$tpl->newTblCheckbox($FS_PHRASES[main_config_showzonename], 'showzonename', ($FSXL[config][showzonename] == 1 ? true : false), $FS_PHRASES[main_config_showzonename_sub]);
$tpl->newTblInput($FS_PHRASES[main_config_startpage], 'startpage', $FSXL[config][startpage], 250, $FS_PHRASES[main_config_startpage_sub]);
$tpl->newTblInput($FS_PHRASES[main_config_dateformat], 'dateformat', $FSXL[config][dateformat], 80, $FS_PHRASES[main_config_dateformat_sub]);
$tpl->newTblCheckbox($FS_PHRASES[main_config_showregonly], 'showregonly', ($FSXL[config][showregonly] == 1 ? true : false), $FS_PHRASES[main_config_showregonly_sub]);
$tpl->newTblInput($FS_PHRASES[main_config_contactmail], 'contactmail', $FSXL[config][kontaktmail], 250, $FS_PHRASES[main_config_contactmail_sub]);
$tpl->newTblInput($FS_PHRASES[main_config_systemmail], 'systemmail', $FSXL[config][systemmail], 250, $FS_PHRASES[main_config_systemmail_sub]);
$tpl->newTblCheckbox($FS_PHRASES[main_config_usesafelinks], 'usesafelinks', ($FSXL[config][use_safe_links] == 1 ? true : false), $FS_PHRASES[main_config_usesafelinks_sub]);
$tpl->newTblInput($FS_PHRASES[main_config_pageid], 'pageid', $FSXL[config][bez], 250, $FS_PHRASES[main_config_pageid_sub]);
$tpl->newTblInput($FS_PHRASES[main_config_agerating], 'agerating', $FSXL[config][ageratings], 250, $FS_PHRASES[main_config_agerating_sub]);

// Style Konfiguration
$tpl->newTblSpacer();
$tpl->newTblHeadline($FS_PHRASES[main_config_title_styles]);
$tpl->openTblSelect($FS_PHRASES[main_config_stdstyle], 'stdstyle', 250, $FS_PHRASES[main_config_stdstyle_sub]);
$index = @mysql_query("SELECT * FROM `$FSXL[tableset]_styles` ORDER BY `name`");
while ($styles = mysql_fetch_assoc($index))
{
	$tpl->newSelectOption($styles[id], $styles[name], ($FSXL[config][stdstyle] == $styles[id] ? true : false));
}
$tpl->closeTblSelect();
$tpl->newTblCheckbox($FS_PHRASES[main_config_userselectstyle], 'userselectstyle', ($FSXL[config][user_select_style] == 1 ? true : false), $FS_PHRASES[main_config_userselectstyle_sub]);
$tpl->newTblCheckbox($FS_PHRASES[main_config_usetplcache], 'use_tpl_cache', ($FSXL[config][use_tpl_cache] == 1 ? true : false), $FS_PHRASES[main_config_usetplcache_sub]);
$tpl->newTblCheckbox($FS_PHRASES[main_config_usetplvarcache], 'use_tplvar_cache', ($FSXL[config][use_tplvar_cache] == 1 ? true : false), $FS_PHRASES[main_config_usetplvarcache_sub]);
$tpl->newTblInput($FS_PHRASES[main_config_tplhistory], 'tplhistory', $FSXL[config][tpl_history_steps], 50, $FS_PHRASES[main_config_tplhistory_sub]);

// Suche Konfiguration
$tpl->newTblSpacer();
$tpl->newTblHeadline($FS_PHRASES[main_config_search]);
$tpl->newTblInput($FS_PHRASES[main_config_search_maxwords], 'maxwords', $FSXL[config][search_maxwords], 50, $FS_PHRASES[main_config_search_maxwords_sub]);
$tpl->newTblInput($FS_PHRASES[main_config_search_time], 'searchtime', $FSXL[config][search_time], 50, $FS_PHRASES[main_config_search_time_sub]);
$tpl->newTblInput($FS_PHRASES[main_config_search_preview], 'searchpreview', $FSXL[config][search_previewlength], 50, $FS_PHRASES[main_config_search_preview_sub]);
$tpl->newTblInput($FS_PHRASES[main_config_tagcloud_words], 'tagcloud_words', $FSXL[config][tagcloud_words], 50, $FS_PHRASES[main_config_tagcloud_words_sub]);
$tpl->newTblInput($FS_PHRASES[main_config_tagcloud_minsize], 'tagcloud_minsize', $FSXL[config][tagcloud_minsize], 50, $FS_PHRASES[main_config_tagcloud_minsize_sub]);
$tpl->newTblInput($FS_PHRASES[main_config_tagcloud_maxsize], 'tagcloud_maxsize', $FSXL[config][tagcloud_maxsize], 50, $FS_PHRASES[main_config_tagcloud_maxsize_sub]);

// Suche Konfiguration
$tpl->newTblSpacer();
$tpl->newTblHeadline($FS_PHRASES[main_config_title_counter]);
$tpl->newTblInput($FS_PHRASES[main_config_countersavetime], 'countersavetime', $FSXL[config][counter_savetime], 50, $FS_PHRASES[main_config_countersavetime_sub]);


// Sicherheit Konfiguration
$tpl->newTblSpacer();
$tpl->newTblHeadline($FS_PHRASES[main_config_title_secure]);
$tpl->newTblInput($FS_PHRASES[main_config_loginattempts], 'loginattempts', $FSXL[config][login_attempts], 50, $FS_PHRASES[main_config_loginattempts_sub], ($FSXL[config][login_attempts]>20?'FF8888':''));
$tpl->newTblInput($FS_PHRASES[main_config_loginblocktime], 'loginblocktime', $FSXL[config][login_blocktime], 50, $FS_PHRASES[main_config_loginblocktime_sub], ($FSXL[config][login_blocktime]<3?'FF8888':''));
$tpl->newTblCheckbox($FS_PHRASES[main_config_admincookielogin], 'admincookielogin', ($FSXL[config][admin_cookielogin] == 1 ? true : false), $FS_PHRASES[main_config_admincookielogin_sub], ($FSXL[config][admin_cookielogin]==1?'FF8888':''));

// Formular schließen
$tpl->newTblSubmitButton($FS_PHRASES[global_takeover]);
$tpl->closeTable();
$tpl->closeForm();

// Template ausgeben
$FSXL[content] = $tpl->code;

?>