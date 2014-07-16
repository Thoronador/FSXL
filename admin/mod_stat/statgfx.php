<?php

@include("../inc/config.inc.php");
@include("../inc/functions.inc.php");
@include("../inc/class.inc.php");

@session_start();

//Style auswählen
switch ($_SESSION[user]->adminstyle)
{
	// Rot
	case 2:
		$FSXL[style] = "red";
		break;
	// blau
	case 3:
		$FSXL[style] = "blue";
		break;
	// Grün
	default:
		$FSXL[style] = "green";
}

// Datanbank Verbindung aufbauen
@$db = new mysql($SQL[host], $SQL[user], $SQL[pass], $SQL[data]);

// Variablen
$xoffset = 46;
$graphwidth = 452;

settype($_GET[year], 'integer');
settype($_GET[month], 'integer');
if ($_GET[year] && $_GET[month])
{
	$index = mysql_query("SELECT day AS item, SUM(visits) AS visits, SUM(hits) AS hits 
				FROM `$FSXL[tableset]_counter_stat` WHERE `year` = $_GET[year] AND `month` = $_GET[month] GROUP BY `day` ORDER BY `day`");
	$start = 1;
	$end = date("t", mktime (0, 0, 0, $_GET[month], 1, $_GET[year]));
}
elseif ($_GET[year])
{
	$index = mysql_query("SELECT month AS item, SUM(visits) AS visits, SUM(hits) AS hits 
				FROM `$FSXL[tableset]_counter_stat` WHERE `year` = $_GET[year] GROUP BY `month` ORDER BY `month`");
	$start = 1;
	$end = 12;
}
else
{
	$index = mysql_query("SELECT year AS item, SUM(visits) AS visits, SUM(hits) AS hits 
			FROM `$FSXL[tableset]_counter_stat` GROUP BY `year` ORDER BY `year`");
}
$data = array();
$maxhits = 1;
$maxvisits = 1;
while ($stat = mysql_fetch_assoc($index))
{
	if ($stat[hits] > $maxhits) $maxhits = $stat[hits];
	if ($stat[visits] > $maxvisits) $maxvisits = $stat[visits];
	$data[$stat[item]] = $stat;
}

header("Content-type: image/png");
$img = imagecreatefrompng('stat_'.$FSXL[style].'.png');
switch($FSXL[style]) {
	case 'red':
		$fontcolor = imagecolorallocate($img, 123, 0, 0);
		$hitcolor = imagecolorallocate($img, 239, 148, 141);
		$visitcolor = imagecolorallocate($img, 215, 127, 121);
		break;
	case 'blue':
		$fontcolor = imagecolorallocate($img, 0, 57, 123);
		$hitcolor = imagecolorallocate($img, 141, 187, 239);
		$visitcolor = imagecolorallocate($img, 121, 165, 215);
		break;
	default:
		$fontcolor = imagecolorallocate($img, 0, 123, 0);
		$hitcolor = imagecolorallocate($img, 141, 239, 141);
		$visitcolor = imagecolorallocate($img, 121, 215, 121);
		break;
}

if (!$_GET[year] && !$_GET[month])
{
	$tmp = $data;
	$start = array_shift($tmp);
	$start = $start[item];
	$end = array_pop($tmp);
	$end = $end[item];
}


// horizontal
$maxline = 100;
while ($maxline <= $maxhits)
{
	$maxline += 100;
}
for ($i=0; $i<5; $i++)
{
	$currentline = formatNumber($maxline/5*(5-$i));
	$fontfile = substr($_SERVER["SCRIPT_FILENAME"], 0, strlen($_SERVER["SCRIPT_FILENAME"])-11) . 'font.dat';
	$font = imagettfbbox (6, 40, $fontfile, $currentline);
	$fontwidth = -$font[5];
	$y = 118 - ($maxline*110/$maxhits) + ($i*118/5);
	imageline ($img, 42, $y, 500, $y, $fontcolor);
	imagettftext ($img, 6, 0, 35-$fontwidth, $y+3, $fontcolor, $fontfile, $currentline);
}


// Vertikale Linien
$textoffset = $graphwidth / ($end-$start+1) / 2 + 10;
$blockwidth = $graphwidth / ($end-$start+1) - 8;

$i=0;
for ($j=$start; $j<=$end; $j++)
{
	$x = $graphwidth / ($end-$start+1) * $i + $xoffset;
	$blockheight = $data[$j][hits]*110/$maxhits;
	if(!$blockheight) $blockheight = 1;

	// Linien
	imageline ($img, $x, 120, $x, 124, $fontcolor);
	imagefilledrectangle ($img, $x+2, 119-$blockheight, $x+2+$blockwidth, 119, $hitcolor);
	imagerectangle ($img, $x+2, 119-$blockheight, $x+2+$blockwidth, 119, $fontcolor);

	if ($_GET[year] && $_GET[month])
	{
		$text = $i+1 . '. ' . date("l", mktime(0, 0, 0, $_GET[month], $i+1, $_GET[year]));
	}
	elseif ($_GET[year])
	{
		$text = date("F", mktime(0, 0, 0, $i+1, 1, $_GET[year]));
	}
	else
	{
		$text = $data[$j][item];
	}

	// Text
	$font = imagettfbbox (5, 40, $fontfile, $text);
	$fontwidth = ($font[6] - $font[2]) * -1;
	$fontheight = ($font[5] - $font[1]) * -1;
	$fontfile = substr($_SERVER["SCRIPT_FILENAME"], 0, strlen($_SERVER["SCRIPT_FILENAME"])-11) . 'font.dat';
	imagettftext ($img, 5, 40, $textoffset+$x-$fontwidth, 118+$fontheight, $fontcolor, $fontfile, $text);

	$i++;
}
$i=0;
for ($j=$start; $j<=$end; $j++)
{
	$x = $graphwidth / ($end-$start+1) * $i + $xoffset;
	$blockheight = $data[$j][visits]*110/$maxhits*2;
	if(!$blockheight) $blockheight = 1;

	// Linien
	imagefilledrectangle ($img, $x+6, 119-$blockheight, $x+6+$blockwidth, 119, $visitcolor);
	imagerectangle ($img, $x+6, 119-$blockheight, $x+6+$blockwidth, 119, $fontcolor);

	$i++;
}


// Bild ausgeben
imagepng($img);
imagedestroy($img);



// Datenbank Verbindung schließen
$db->close();

?>