<?php
$file = fopen("salden.txt","r");

$matches = array();

printf( "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n");
if ($file) {
  while(! feof($file)) {
    $zeile = fgets($file);
    $zeile = trim( $zeile);

    $pattern = "#([0-9]*)\.([0-9]*)\.(.*)#";

    $pattern = "#(\d+)\.(\d+)\. ([+-]{0,1}\d+)\.(\d+)#";
    $err = preg_match ( $pattern, $zeile, $matches);
    if ( $err == 1) {
      printf( "| %s | %s | %s | %s | ", $matches[1], $matches[2], $matches[3], $matches[4]);
    }

    $pattern = "#(^\d{4})#";
    $err = preg_match ( $pattern, $zeile, $matches);
    if ( $err == 1) {
      printf( "| %s | %s | %s | %s | ", $matches[1], $matches[2], $matches[3], $matches[4]);
    }

    $pattern = "#(^#.*)#";
    $err = preg_match ( $pattern, $zeile, $matches);
    if ( $err == 1) {
      printf( "| %s | %s | %s | %s | ", $matches[1], $matches[2], $matches[3], $matches[4]);
    }
    
    echo "quelle=\"$zeile\"<br />\n";
  }
  fclose($file);
}
?>

