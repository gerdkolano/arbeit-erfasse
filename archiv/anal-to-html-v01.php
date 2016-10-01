<?php

$debug = false;
$debug = true;

lies_salden( "salden.txt");

function lies_salden( $dateiname) {
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
        printf( "x| %02d | %02d | %02d | %6.2f <br />\n", 
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
  
          $pattern = "/(^#.*|^\s*$)/";
          if ( 1 == preg_match ( $pattern, $zeile, $matches)) {
            printf( "c| %s <br />\n", $matches[1]);
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
    fclose($file);
    echo "<pre>"; print_r( $liste); echo "</pre>";
  }
}
?>

