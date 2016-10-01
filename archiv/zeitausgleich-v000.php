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

function lies( $table_name, $conn) {
  echo "################# $query<br />\n";
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
  $erg = $conn->hol_array_of_objects( "$query");
  foreach ($erg as $key=>$value) {
    printf( "key %s<br />\n", $key);
    foreach ($value as $schlüssel=>$wert) {
      printf( "%s %s %s<br />\n", $key, $schlüssel, $wert);
    }
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

