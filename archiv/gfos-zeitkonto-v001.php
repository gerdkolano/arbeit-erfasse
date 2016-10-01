<?php
require_once( "helfer.php");

function head() {
    printf( "<!DOCTYPE html>\n");
    printf( "<html>\n");
    printf( "<head>\n");
    printf( "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n");
    printf( "</head>\n");
    printf( "<body>\n");
}

head();

# echo "<pre>"; print_r( $_POST); echo "</pre>";

$gepostet = new gepostet();
$gepostet->toString();

$conn = new conn();
$conn->frage( 0, "SELECT 11");

class ein_monat {
  private $ein_tag;
  private $conn;
  function __construct() {
    $conn = new conn();
    $conn->frage( 0, "USE arbeit");
    $query = "SELECT datum, datum_auto, erscheine FROM zeiten WHERE datum_auto >= '2016-01-01' and datum_auto < '2016-02-01' ORDER BY datum_auto";
    echo "################# $query<br />\n";
    $tabelle_2D = $conn->hol_array_of_objects( "$query");
    printf( "T010 %s %s %s<br />\n", 0, "datum_auto", $tabelle_2D[0]["datum_auto"]);
    printf( "T010 %s %s %s<br />\n", 1, "datum_auto", $tabelle_2D[1]["datum_auto"]);

    foreach ($tabelle_2D as $zeilennummer=>$value) {
      printf( "zeile nummer %s ", $zeilennummer);
      foreach ($value as $kolumne=>$wert) {
        printf( "%s %s ", $kolumne, $wert);
      }
      printf( "%s <br />\n", " #");
    }

  }
}

class ein_tag {
  private $kommt_geht = array();
  private $datum,
    $ar_kommt,
    $p1_geht,
    $p1_kommt,
    $p2_geht,
    $p2_kommt,
    $ar_geht;
}
new ein_monat();

?>
