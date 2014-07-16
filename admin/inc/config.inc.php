<?php

$FSXL = array();

// Globale Daten
$FSXL[superadmin] = array(1, 4); //User IDs der Superadmins
$FSXL[tableset] = "fsxl"; //Tabellen Pr�fix
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
$SQL[user] = "db_frogsystem";
$SQL[data] = "db_frogsystem";
$SQL[pass] = "frogsystem";


?>