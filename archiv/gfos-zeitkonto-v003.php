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

head();

# echo "<pre>"; print_r( $_POST); echo "</pre>";

$gepostet = new gepostet();
$gepostet->toString();

class ein_monat {
  private $ein_tag;
  private $tabelle;
  private $conn;
  function __construct() {
    $tabelle = new tabelle();
    $this->tabelle = $tabelle;
    $spalte = $tabelle->kurzfelder;
    $comma_separated = implode(",", $tabelle->zeitkonto);
    $table = "zeiten";
    $where = "WHERE datum_auto >= '2016-01-01' and datum_auto < '2016-02-01' ORDER BY datum_auto";
    $query = "SELECT $comma_separated  FROM $table $where";
    # echo "<pre>"; print_r( $this->tabelle->felder); echo "!</pre><br />\n";
    $conn = new conn();
    $conn->frage( 0, "USE arbeit");
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

new ein_monat();

?>
