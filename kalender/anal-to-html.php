<?php
require_once( "datum.php");
require_once( "helfer.php");

# Urlaub mit oder ohne Jahreszahl
#
$debug = true;
$debug = false;
setlocale(LC_ALL,"de_DE.utf8");

html_header();

# zeige_tabelle( lies_salden_aus_einer_datei( "salden.txt"));
zeige_tabelle( lies_salden_aus_einer_datenbank( "salden.txt"));

if ($debug) zeige_liste();

function html_header() {
  $schriftgröße = "75%";
  $html_header = ""
    . "<!DOCTYPE html>\n"
    . "<html>\n<head>\n"
    . "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n"
    . "<style>  tr td:nth-child(3) {text-align: right;}  </style>\n"
    . "<style>  tr td:nth-child(4) {text-align: right;}  </style>\n"
    . "</head>\n<body style=\"font-size:$schriftgröße\">\n"
    ;
  echo $html_header;
}

function zeige_tabelle( $liste) {
  $erg = "";
  $kommentar = "";
  $eps = 0.001; 
  $fmt = new IntlDateFormatter(
      'de-DE',
      IntlDateFormatter::FULL,
      IntlDateFormatter::FULL,
      'Europe/Berlin',
      IntlDateFormatter::GREGORIAN,
      "ww '<td>' yyyy-MM-dd EEEE"   // http://www.unicode.org/reports/tr35/tr35-dates.html#Date_Field_Symbol_Table
    );
  $fmt_monat = new IntlDateFormatter(
      'de-DE',
      IntlDateFormatter::FULL,
      IntlDateFormatter::FULL,
      'Europe/Berlin',
      IntlDateFormatter::GREGORIAN,
      "MMMM y"   // http://www.unicode.org/reports/tr35/tr35-dates.html#Date_Field_Symbol_Table
    );
  $vormonat = "";
  $vorzeit = 0.0;
  foreach ( $liste as $zeile) {

    if (isset($zeile["jahr"])) {
      $datum =  datumsobjekt( sprintf( "%04d-%02d-%02d", $zeile["jahr"], $zeile["monat"],$zeile["tag"] ));
      $dieser_monat = $datum->format('n');
      if ($dieser_monat != $vormonat) {
        $vormonat = $dieser_monat;
        $erg .= sprintf( "<tr><th style=\"border-left:blank\" colspan=\"4\"> %s \n", $fmt_monat->format( $datum));
      }
    }
 
    switch ($zeile["art"]) {
    case "kommentar" :
      $kommentar .= $zeile["text"];
      break;
    case "urlaub anfang" :
      $erg .= sprintf( "<tr><td> %s <td> %s  <td> %s\n", $fmt->format( $datum), "Anfang Urlaub", "");
      break;
    case "urlaub ende" :
      $erg .= sprintf( "<tr><td> %s <td> %s  <td> %s\n", $fmt->format( $datum), "Ende Urlaub", "");
      break;
    case "abgelesen" :
      $zeit = $zeile["hundertstel"] / 100.0 + $zeile["stunden"];
      if ($zeile["vorzeichen"] == "-") { $zeit = - $zeit; } 
      $differenz = $zeit - $vorzeit;
      $differenz_wort = abs( $differenz) < $eps ? "" : sprintf( "%6.2f", $differenz);
      $differenz_wort = str_replace( "-", "⁒ ", $differenz_wort);
      $vorzeit = $zeit;
      $zeit_wort = str_replace( "-", "⁒ ", sprintf( "%6.2f", $zeit));
      $erg .= sprintf( "<tr><td> %s <td> %s Std <td> %s\n", $fmt->format( $datum), $zeit_wort, $differenz_wort);
      break;
    default:
      $erg .= sprintf( "<tr><td> %s <td> %s <td> %s     <td> %s\n", "", "F010 art unbekannt", "", "");
      break;
    }
  }
  $tablehead = "<tr><th>        <th colspan=\"2\"> Infotaste <th>           \n";
  $tablehead .= "<tr><th> Woche <th> Datum <th> Saldo        <th> Differenz \n";
  // $erg = $tablehead . $erg;
  printf( "<table cellspacing=\"0\" cellpadding=\"2\" border=\"1\">\n<caption> %s </caption>\n%s%s </table>\n",
    $kommentar,
    $tablehead,
    $erg
  );
}

function zeige_liste() {
  $liste = lies_salden( "salden.txt");
# echo "<pre>"; print_r( $liste); echo "</pre>";
  foreach ( $liste as $zeile) {
    if ($debug) printf( "r| %02d | %02d | %02d | %02d | %02d | %6.2f <br />\n",
      $zeile["tag"]        ,
      $zeile["monat"]      ,
      $zeile["jahr"]       ,
      $zeile["vorzeichen"] ,      
      $zeile["stunden"]    ,   
      $zeile["hundertstel"]       
    );
    printf( "%04d-%02d-%02d %6.2f Std<br />\n",
      $zeile["jahr"]       ,
      $zeile["monat"]      ,
      $zeile["tag"]        ,
      $zeile["hundertstel"] / 100.0 + $zeile["stunden"] 
    );
  }
}

function lies_salden_aus_einer_datenbank( $dateiname) {
  $tabelle = "zeiten";
  $query = "SELECT"
    .   " datum,"
    .   " i_saldo_dauer,"
    .   " floor( i_saldo_dauer ) as std,"
    .   " floor((i_saldo_dauer - floor( i_saldo_dauer ))*100) as hundertstel"
    . " FROM $tabelle "
    . " where i_saldo_dauer != 0"
    . " and "
    .   " datum > '2015-01-01' "
    . " ORDER BY datum";
  $conn = new conn();
  $conn->frage( 0, "USE arbeit");

  // Hole die Daten aus der Datenbank
  $daten_2D = $conn->hol_array_of_objects( "$query");
  $liste = array();
  foreach ($daten_2D as $zeilennummer=>$value) {
    $date = DateTime::createFromFormat("Y-m-d", $value["datum"]);
    if ( !$date) continue;
/*
    printf( "'%s'  <br />\n", $value["datum"]);
    printf( "%s %s <br />\n", $value["datum"], $date->format("Y-m-d"));
    echo "<pre>\n";    
    print_r( DateTime::getLastErrors());
    $err = DateTime::getLastErrors();
    printf( "errors %d <br />\n", $err["error_count"]);
    echo "</pre>\n";    
 */
    $tag         = $date->format("d");
    $monat       = $date->format("m");
    $jahr        = $date->format("Y");
    $vorzeichen  = $value["i_saldo_dauer"] < 0 ? "-" : "";
    $stunden     = $value["std"];
    $hundertstel = $value["hundertstel"];
    $zeile = array(
      "art"         => "abgelesen",
      "tag"         => $tag,
      "monat"       => $monat,
      "jahr"        => $jahr,
      "vorzeichen"  => $vorzeichen,
      "stunden"     => $stunden,
      "hundertstel" => $hundertstel
    );
    $liste[] = $zeile; 
  }
  return $liste;
}

function lies_salden_aus_einer_datei( $dateiname) {
  global $debug;
  $file = fopen( $dateiname,"r");
  $liste = array();
  if ($file) {
    $jahr = "0000";
    while(! feof($file)) {
      $zeile = fgets($file);
      $zeile = trim( $zeile);
  
      $pattern = "#([0-9]*)\.([0-9]*)\.(.*)#";
  
      $pattern = "#^(\d+)\.(\d+)\.\s+([+-]{0,1})\s*(\d+)\.(\d+)#";
      if ( 1 == preg_match ( $pattern, $zeile, $matches)) {
        if ($debug) printf( "a| %s | %s | %s | %s | %s | <br />\n", $matches[1], $matches[2], $matches[3], $matches[4], $matches[5]);
        $tag         = $matches[1];
        $monat       = $matches[2];
        $vorzeichen  = $matches[3];
        $stunden     = $matches[4];
        $hundertstel = $matches[5];
        $zeit        = $hundertstel / 100.0 + $stunden;
        if ($vorzeichen == "-") {$zeit = - $zeit;}
        if ($debug) printf( "x| %02d | %02d | %02d | %6.2f <br />\n", 
          $tag        ,
          $monat      ,
          $jahr       ,
          $zeit
        );
        $zeile = array(
          "art"         => "abgelesen",
          "tag"         => $tag,
          "monat"       => $monat,
          "jahr"        => $jahr,
          "vorzeichen"  => $vorzeichen,
          "stunden"     => $stunden,
          "hundertstel" => $hundertstel
          );
        $liste[] = $zeile;
  
      } else {
  
        $pattern = "#(^\d{4})#";
        if ( 1 == preg_match ( $pattern, $zeile, $matches)) {
          if ($debug) printf( "b| %s <br />\n", $matches[1]);
          $jahr =  $matches[1];
        } else {
  
          $pattern = "/^#(.*)/";
          if ( 1 == preg_match ( $pattern, $zeile, $matches)) {
            if ($debug) printf( "k| %s <br />\n", $matches[1]);
            $zeile = array (
              "art"         => "kommentar",
              "text"        => $matches[1]
            );
            $liste[] = $zeile;
          } else {
  
            $pattern = "/(^\s*$)|ende/i";
            if ( 1 == preg_match ( $pattern, $zeile, $matches)) {
            if ($debug) printf( "c| %s <br />\n", $matches[1]);
            } else {
           
                $pattern = "/(^urlaub)[\s\.]*(\d+)[\s\.]*(\d+)(.*)/i";
                $pattern = "/(^urlaub)[\s\.]*(\d+)[\s\.]*(?:(\d{4})[\s\.]*){0,1}(\d+)[-\s\.]*(\d+)[\s\.]*(\d+)[\s\.]*(\d+)(.*)/i";
                $pattern = "/(^urlaub)
                  [\s\.]*(\d+)
                  [\s\.]*(\d+)
               (?:[\s\.]*(\d+))?
                  [-\s\.]*(\d+)
                  [\s\.]*(\d+)
               (?:[\s\.]*(\d+))?
                  (.*)/ix";
              if ( 1 == preg_match ( $pattern, $zeile, $ma)) {
                if ($debug) printf( "d| %s | %s | %s | %s | %s | %s | %s | <br />\n", $ma[1], $ma[2], $ma[3], $ma[4], $ma[5], $ma[6], $ma[7]);
                $zeile = array (
                  "art"      => "urlaub anfang",
                  "tag"     => $ma[2],
                  "monat"   => $ma[3],
                  "jahr"    => isset( $ma[4]) ? $jahr : $ma[4],
                  );
                $liste[] = $zeile;
                $zeile = array (
                  "art"      => "urlaub ende",
                  "tag"     => $ma[5],
                  "monat"   => $ma[6],
                  "jahr"    => isset( $ma[7]) ? $jahr : $ma[7]
                  );
                $liste[] = $zeile;
              } else {
                echo "In salden.txt unklar: \"$zeile\"<br />\n";
              }
            }
          }
        }
      }
    }
    fclose($file);
  }
  return $liste;
}
?>

