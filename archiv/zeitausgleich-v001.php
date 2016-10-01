<?php
require_once( "helfer.php");
require_once( "tabelle.php");

function head() {
    printf( "<!DOCTYPE html>\n");
    printf( "<html>\n");
    printf( "<head>\n");
    printf( "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n");
    printf( "</head>\n");
    printf( "<body>\n");
}

function hhmm( $wort) { // string ziffern nichtziffern ziffern
  $erg = "";
  $erg = $wort;
  preg_match( "/(\d+)[^\d](\d+)/", $wort, $matches);
  $erg = sprintf( "%02d.%02d", $matches[1], $matches[2]);
  return $erg;
}

function dauer( $anfang, $ende) { // string ziffern nichtziffern ziffern
  $differenz = minuten( $ende) - minuten( $anfang);
# printf( "MIN020 anfang=%s ende=%s differenz=%d<br />\n", $anfang, $ende, $differenz);
  return $differenz / 60;
}

function dauer_mit_pause( $anfang, $ende) { // string ziffern nichtziffern ziffern
  $differenz = minuten( $ende) - minuten( $anfang);
  if ($differenz < 390) { $pause = 15; } else { $pause = 30; } 
# printf( "MIN020 anfang=%s ende=%s differenz=%d<br />\n", $anfang, $ende, $differenz);
  $differenz -= $pause;
# printf( "MIN030 anfang=%s ende=%s differenz=%d<br />\n", $anfang, $ende, $differenz);
  return $differenz / 60;
}

function minuten( $wort) { // string ziffern nichtziffern ziffern
  $erg = "";
  $erg = $wort;
  preg_match( "/(\d+)[^\d](\d+)/", $wort, $matches);
  $erg = sprintf( "%4d", $matches[1] * 60 + $matches[2]);
  $erg = $matches[1] * 60 + $matches[2];
# printf( "MIN010 %s:%s=%dmin<br />\n", $matches[1], $matches[2], $erg);
  return $erg;
}

function lies( $table_name, $conn) {
  $query = "SELECT id,
    datum, 
    erscheine, 
    pause1_geht,
    pause1_kommt,
    pause2_geht,
    pause2_kommt,
    arbzeit_plan_anfang,
    arbzeit_plan_ende,
    arbzeit_ist_anfang,
    arbzeit_ist_ende
    FROM $table_name WHERE id < 13";
# echo "################# $query<br />\n";
  $erg = $conn->hol_array_of_objects( "$query");
  foreach ($erg as $key=>$value) {
#   printf( "key %s<br />\n", $key);
    foreach ($value as $schlüssel=>$wert) {
#       printf( "%s %s %s<br />\n", $key, $schlüssel, $wert);
#       printf( "%s %s <br />\n", hhmm( $wert), minuten( $wert));
    }
    printf( "erscheine %s <br />\n", hhmm( $value["erscheine"]));
#   printf( "geplant %.2f <br />\n",  dauer( $value["arbzeit_plan_anfang"], $value["arbzeit_plan_ende"]));
    printf( "geplant %.2f Stunden von %s bis %s<br />\n",
      dauer_mit_pause( $value["arbzeit_plan_anfang"], $value["arbzeit_plan_ende"]),
      hhmm( $value["arbzeit_plan_anfang"]),
      hhmm( $value["arbzeit_plan_ende"])
    );
  }
}

head();

# echo "<pre>"; print_r( $_POST); echo "</pre>";
# echo "<pre>"; print_r( $erg); echo "</pre>";

$database_name = "arbeit";
$table_name = "zeiten";

$gepostet = new gepostet();
$gepostet->zeig();

$conn = new conn();
$erg = $conn->frage( 0, "USE $database_name");

lies( $table_name,  $conn);
?>

