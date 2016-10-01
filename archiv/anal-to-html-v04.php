<?php
require_once( "datum.php");

$debug = true;
$debug = false;
setlocale(LC_ALL,"de_DE.utf8");

zeige_tabelle();
if ($debug) zeige_liste();

printf( "<style>  tr td:nth-child(3) {text-align: right;}  </style>\n");
printf( "<style>  tr td:nth-child(4) {text-align: right;}  </style>\n");
function zeige_tabelle() {
  $liste = lies_salden( "salden.txt");
  $erg = "";
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
    if ($zeile["art"] == "urlaub") {
      $adatum =  datumsobjekt( sprintf( "%04d-%02d-%02d", $zeile["ajahr"], $zeile["amonat"],$zeile["atag"] ));
      $edatum =  datumsobjekt( sprintf( "%04d-%02d-%02d", $zeile["ejahr"], $zeile["emonat"],$zeile["etag"] ));
      $erg .= sprintf( "<tr><td> %s <td> %s Std <td> %s\n",
        "",
        $fmt->format( $adatum),
        ""
    );
      continue;
    }
    $zeit = $zeile["hundertstel"] / 100.0 + $zeile["stunden"];
    //if ($zeit != $vorzeit) {
    $differenz = $zeit - $vorzeit;
    $differenz_wort = abs( $differenz) < $eps ? "" : sprintf( "%6.2f", $differenz);
    //}
    $vorzeit = $zeit;
    if ($zeile["vorzeichen"] == "-") {
      // "⁒"
      $zeit = - $zeit;
    } 
    $zeit = str_replace( "-", "⁒", sprintf( "%6.2f", $zeit));
    $datum =  datumsobjekt( sprintf( "%04d-%02d-%02d", $zeile["jahr"], $zeile["monat"],$zeile["tag"] ));
    $dieser_monat = $datum->format('n');
    if ($dieser_monat != $vormonat) {
      $vormonat = $dieser_monat;
      $erg .= sprintf( "<tr><th colspan=\"4\">" . $fmt_monat->format( $datum));
    }

    //$erg .= sprintf( "%04d-%02d-%02d %6.2f Std<br />\n",
    $erg .= sprintf( "<tr><td> %s <td> %s Std <td> %s\n",
      $fmt->format( $datum),
      $zeit                ,
      $differenz_wort
  );
  }
  $erg = "<tr><th> Woche <th> Datum <th> Stunden <th> Differenz " . $erg;
  printf( "<table cellspacing=\"0\" cellpadding=\"2\" border=\"1\"><caption>Zeiterfassung</caption>%s</table>\n", $erg);
}

function zeige_liste() {
  $liste = lies_salden( "salden.txt");
  #echo "<pre>"; print_r( $liste); echo "</pre>";
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


function lies_salden( $dateiname) {
  global $debug;
  $file = fopen( $dateiname,"r");
  $liste = array();
  printf( "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n");
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
          printf( "b| %s <br />\n", $matches[1]);
          $jahr =  $matches[1];
        } else {
  
          $pattern = "/(^#.*)/";
          if ( 1 == preg_match ( $pattern, $zeile, $matches)) {
            printf( "k| %s <br />\n", $matches[1]);
          } else {
  
          $pattern = "/(^\s*$)/";
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
              printf( "d| %s | %s | %s | %s | %s | %s | %s | <br />\n", $ma[1], $ma[2], $ma[3], $ma[4], $ma[5], $ma[6], $ma[7]);
        $zeile = array(
          "art"      => "urlaub",
          "atag"     => $ma[2],
          "amonat"   => $ma[3],
          "ajahr"    => $ma[4] ? $jahr : $ma[4],
          "etag"     => $ma[5],
          "emonat"   => $ma[6],
          "ejahr"    => $ma[7]
          );
        $liste[] = $zeile;
            } else {
              echo "quelle=\"$zeile\"<br />\n";
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

