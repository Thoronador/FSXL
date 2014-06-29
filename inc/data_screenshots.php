<?php
switch($_GET[catid]){
  case 1:
    $a=11;
    break;
  case 2:
    $a=9;
    break;
  case 3:
    $a=12;
    break;
  case 7:
    $a=7;
    break;
  case 8:
    $a=7;
    break;
  case 9:
    $a=7;
    break;
  case 10:
    $a=7;
    break;
  case 11:
    $a=13;
    break;
  case 13:
    $a=4;
    break;
  case 15:
    $a=12;
    break;
  case 16:
    $a=5;
    break;
  case 17:
    $a=6;
    break;
  case 19:
    $a=5;
    break;
  case 20:
    $a=6;
    break;
  case 22:
    $a=3;
    break;
  default:
    $a="";
    break;
}
$_GET[id]=$a;
include('inc/data_gallery.php');
?>