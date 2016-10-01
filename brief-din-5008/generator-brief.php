<?php
require_once( "../include/datum.php");

function brief( $quelle) {
  // A4 210 by 297 millimetres
  // --user-style-sheet
  $konverter = "/usr/bin/wkhtmltopdf";
  $ziel   = "/daten/srv/www/htdocs/arbeit/pdf/brief.pdf";
  $param  = "--margin-top  0 --margin-right 0 --margin-bottom 0 --margin-left 0 --orientation Portrait";
  $param .= " --user-style-sheet http://zoe.xeo/arbeit/brief-din-5008/css-din-5008-bunt.css"; // wirkungslos
  $kommando = "$konverter --margin-left 20 --margin-top 20 --page-size A4 --orientation Landscape \"$quelle\" $ziel";
  $kommando = "$konverter --margin-top  0 --margin-right 0 --margin-bottom 0 --margin-left 0 --page-height 600mm --page-width 400mm --orientation Portrait  \"$quelle\" $ziel";
  $kommando = "$konverter $param --page-size A4 --orientation Portrait  \"$quelle\" $ziel";
  $kommando = "$konverter $param --page-width  70mm --page-height  99mm \"$quelle\" $ziel";
  $kommando = "$konverter $param --page-width 140mm --page-height 198mm \"$quelle\" $ziel";
  $kommando = "$konverter $param --page-width 170mm --page-height 297mm \"$quelle\" $ziel";
  $kommando = "$konverter $param --page-width 210mm --page-height 297mm \"$quelle\" $ziel";
  $kommando = "$konverter $param --page-width 170mm --page-height 252mm \"$quelle\" $ziel";
  echo "$kommando\n";
  system( $kommando);
}

require_once( "include-generator.php");

# echo "parameter $parameter";

$url = "http://zoe.xeo/arbeit/brief-din-5008/brief-din-5008.php" . "?" . $parameter ;

if (true) brief( $url);

?>
