<?php
require_once( "../include/datum.php");

if (true) eine_woche_mit_leer( "2016-04-01");
if (true) alle_monate( false);
if (true) alle_monate( true );

function eine_woche_mit_leer( $eine_woche) {
  $konverter = "/usr/bin/wkhtmltopdf";
  $quelle = "http://zoe.xeo/arbeit/erfasse/zeitkonto.php?verbose=1woche&wann=$eine_woche";
  $ziel   = "/daten/srv/www/htdocs/arbeit/pdf/$eine_woche.pdf";
  $kommando = "$konverter --margin-left 20 --margin-top 20 --page-size A4 --orientation Landscape \"$quelle\" $ziel";
  echo "$kommando\n";
  system( $kommando);
}

function alle_monate( $mit_wochen) {
  $konverter = "/usr/bin/wkhtmltopdf";
  
  for ($monat = new datum_objekt( "2014-11-15"); $monat <= new datum_objekt(); $monat->modify( "+1 month")) {
    $wann = $monat->format( "Y-m");
    $param = "wann=" . $wann;
    $quelle = $mit_wochen
      ? "http://zoe.xeo/arbeit/erfasse/zeitkonto.php?verbose=5wochen&$param"
      : "http://zoe.xeo/arbeit/erfasse/zeitkonto.php?verbose=monate&$param"
      ;
    $ziel = $mit_wochen
      ? "/daten/srv/www/htdocs/arbeit/pdf/mit-wochen-$wann.pdf"
      : "/daten/srv/www/htdocs/arbeit/pdf/ohne-wochen-$wann.pdf"
      ;
    $orientation = $mit_wochen
      ? "--orientation Landscape"
      : "--orientation portrait"
      ;
    $kommando = "$konverter --margin-left 20 --margin-top 20 --page-size A4 $orientation \"$quelle\" $ziel";
    echo "$kommando\n";
    system( $kommando);
  }
  $kommando = "/usr/bin/pdftk  /daten/srv/www/htdocs/arbeit/pdf/ohne*.pdf  cat output /tmp/ohne.pdf";
  echo "$kommando\n";
  system( $kommando);
}

exit( 0);

# Seite 5 : pdftk  A=/daten/srv/www/htdocs/arbeit/pdf/2016-04.pdf  cat A5 output /tmp/2016-04.pdf
# pdftk  /daten/srv/www/htdocs/arbeit/pdf/ohne*.pdf  cat output /tmp/ohne.pdf

#system( "/bin/ls > /daten/srv/www/htdocs/arbeit/pdf/formular-woche.pdf");
#exit( 0);

$konverter = "";
$konverter = "/zoe-home/zoe-hanno/dwhelper/wkhtmltox/bin/wkhtmltopdf";
$konverter = "/usr/bin/wkhtmltopdf";

$monat = "2016-04";
$param = "wann=$monat";
$quelle = "";
$quelle = "http://zoe.xeo/arbeit/erfasse/formular-woche.php";
$quelle = "http://zoe.xeo/arbeit/erfasse/zeitkonto.php?start=2016-04&verbose=7";
$quelle = "http://zoe.xeo/arbeit/erfasse/zeitkonto.php?verbose=5wochen&$param";

$ziel = "";
$ziel   = "/daten/srv/www/htdocs/arbeit/pdf/formular-woche.pdf";
$ziel   = "../pdf/formular-woche.pdf";
$ziel   = "/daten/srv/www/htdocs/arbeit/pdf/$monat.pdf";

$kommando = "$konverter  -L 20 -T 20 -s A4 -O Landscape \"$quelle\" $ziel";
echo "$kommando\n";
system( $kommando);

# system( "wkhtmltopdf -O Landscape $quelle $ziel");
# chown -R hanno:    /daten/srv/www/htdocs/arbeit/pdf
# chown -R www-data: /daten/srv/www/htdocs/arbeit/pdf
# http://zoe.xeo/arbeit/erfasse/zeitkonto.php?start=2016-03&verbose=7
?>
