<?php
require_once( "datum.php");

$debug = true;
$debug = false;
setlocale(LC_ALL,"de_DE.utf8");
echo strftime("%A"); // Wochentagsname
echo strftime("%B") . "<br />\n"; // Monatsname
setlocale(LC_TIME, "de_DE.utf8"); # hanno@zoe:/daten/srv/www/htdocs/erprobe/kalender$ locale -a
echo strftime("%A"); // Wochentagsname
echo strftime("%B") . "<br />\n"; // Monatsname
$dtz = new DateTimeZone( "Europe/Berlin");
echo $dtz->getName ( );
$heute = new DateTime();
$heute->setTimezone( $dtz);
echo $heute->format('l') . "<br />\n";
$fmt = new IntlDateFormatter(
    'de-DE',
    IntlDateFormatter::FULL,
    IntlDateFormatter::FULL,
    'Europe/Berlin',
    IntlDateFormatter::GREGORIAN
);
echo 'Second Formatted output is ' . $fmt->format(3000000000) . "<br />\n";
echo 'Second Formatted output is ' . $fmt->format( $heute) . "<br />\n";


zeige_tabelle();
if ($debug) zeige_liste();
printf( "<style>  tr td:nth-child(2) {text-align: right;}  </style>\n");
function zeige_tabelle() {
  $liste = lies_salden( "salden.txt");
  $erg = "";
  foreach ( $liste as $zeile) {
    $zeit = $zeile["hundertstel"] / 100.0 + $zeile["stunden"];
    if ($zeile["vorzeichen"] == "-") {
      // "⁒"
      $zeit = - $zeit;
    } 
    $zeit = str_replace( "-", "⁒", sprintf( "%6.2f", $zeit));
    $datum =  datumsobjekt( sprintf( "%04d-%02d-%02d", $zeile["jahr"], $zeile["monat"],$zeile["tag"] ));
 
    //$erg .= sprintf( "%04d-%02d-%02d %6.2f Std<br />\n",
    $erg .= sprintf( "<tr><td>%04d-%02d-%02d %s<td>%s Std\n",
      $zeile["jahr"]       ,
      $zeile["monat"]      ,
      $zeile["tag"]        , $datum->format('l'),
      $zeit
    );
  }
  $erg = "<tr><th>Datum<th>Stunden" . $erg;
  printf( "<table border>%s</table>\n", $erg);
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
        $zeile = array( "tag"=>$tag, "monat"=>$monat, "jahr"=>$jahr, "vorzeichen" => $vorzeichen, "stunden" =>  $stunden, "hundertstel" => $hundertstel);
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

