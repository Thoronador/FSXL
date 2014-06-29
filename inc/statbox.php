<?php

// Template lesen
$stat_tpl = new template('statbox');

// Counter lesen
$counter = explode(',', implode('', file('cache/counter.cch')));

// Variablen ersetzen
$stat_tpl->replaceTplVar('{visitsall}', formatNumber($counter[0]));
$stat_tpl->replaceTplVar('{hitsall}', formatNumber($counter[1]));
$stat_tpl->replaceTplVar('{visitstoday}', formatNumber($counter[2]));
$stat_tpl->replaceTplVar('{hitstoday}', formatNumber($counter[3]));
$stat_tpl->replaceTplVar('{useronline}', formatNumber($counter[4]));

// Template ausgeben
$stattpl = $stat_tpl->code;
unset($stat_tpl);

?>