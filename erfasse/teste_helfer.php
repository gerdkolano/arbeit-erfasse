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

echo "################# SELECT 22 as zahl <br />\n";
$erg = $conn->hol_array_of_objects( "SELECT 22 as zahl");
echo "<pre> \$erg"; print_r( $erg); echo "</pre>";
echo "<pre> \$erg[0]"; print_r( $erg[0]); echo "</pre>";
echo "<pre> \$erg[0][\"zahl\"]"; print_r( $erg[0]["zahl"]); echo "</pre>";
echo "<pre> current( \$erg)"; print_r( current( $erg)); echo "</pre>";

while ($zahl = current( $erg)) {
  echo "<pre> \$zahl"; print_r( $zahl); echo "</pre>";
  printf( "%s <br />\n", $zahl["zahl"]);
  next( $erg);
}

echo "################# SELECT 333<br />\n";
$erg = $conn->hol_array_of_objects( "SELECT 333");
foreach ($erg as $key=>$value) {
  foreach ($value as $schlüssel=>$wert) {
    printf( "%s %s<br />\n", $schlüssel, $wert);
  }
}

$query = "SELECT vorname, name from joo336.st_stamm WHERE selbst < 13";
echo "################# $query<br />\n";
$erg = $conn->hol_array_of_objects( "$query");
printf( "T010 %s %s %s<br />\n", 0, "name", $erg[0]["name"]);
foreach ($erg as $key=>$value) {
  printf( "key %s<br />\n", $key);
  foreach ($value as $schlüssel=>$wert) {
    printf( "%s %s %s<br />\n", $key, $schlüssel, $wert);
  }
}
echo "------------------ nur die erste row -- nur der erste Datensatz<br />\n";
  foreach ($erg[0] as $schlüssel=>$wert) {
    printf( "%s %s<br />\n", $schlüssel, $wert);
  }
/* key 0
 * 0 vorname Hanno Gerd Michael 哈诺
 * 0 name Schallehn
 * key 1
 * 1 vorname Eugen Женя Евгений
 * 1 name
 * */
    $conn->frage( 0, "USE arbeit");

    $query = "SELECT datum, datum_auto, erscheine FROM zeiten WHERE datum_auto >= '2016-01-01' and datum_auto < '2016-02-01' ORDER BY datum_auto";
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

class ein_monat {
  private $ein_tag;
  function __construct() {
    $query = "SELECT FROM zeiten WHERE datum_auto >= '2016-01-01' and datum_auto < '2016-02-01'";
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
?>
