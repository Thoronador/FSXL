<?php

$FSXL = array();

// Globale Daten
$FSXL[superadmin] = array(1); //User IDs der Superadmins
$FSXL[tableset] = "fsxl"; //Tabellen Präfix
$FSXL[defaultlanguage] = "german"; //Standard Sprache
$FSXL[startpage] = "news"; //default Startseite
$FSXL[rss_cache_time] = 15; // Zeit die der RSS Feed im Cache bleibt, bevor er aktualisiert wird (in Minuten)

// Sprachen
$FSXL[languages] = array();
$FSXL[languages][german] = array(1, 'Deutsch');
$FSXL[languages][english] = array(2, 'English');
$FSXL[languages][russian] = array(3, 'Russian');

// SQL-Zugangsdaten

$SQL = array();
$SQL[host] = "localhost";
$SQL[user] = "root";
$SQL[data] = "fsxl";
$SQL[pass] = "";


?>