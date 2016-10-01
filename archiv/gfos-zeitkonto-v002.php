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

class zeile {
  public $label;
  function __construct ( $label) {
    $this->label     = $label;
  }
}

class tabelle {
  function __construct() {
    $this->felder = array (
      "id"             => (new zeile( "id"             )),
      "datum_auto"     => (new zeile( "datum_auto"     )),
      "arbeit_kommt"   => (new zeile( "arbeit_kommt"   )),
      "pause1_geht"    => (new zeile( "pause1_geht"    )),
      "pause1_kommt"   => (new zeile( "pause1_kommt"   )),
      "pause2_geht"    => (new zeile( "pause2_geht"    )),
      "pause2_kommt"   => (new zeile( "pause2_kommt"   )),
      "arbeit_geht"    => (new zeile( "arbeit_geht"    )),
      "i_saldo_dauer"  => (new zeile( "i_saldo_dauer"  )),
      "i_saldo_datum"  => (new zeile( "i_saldo_datum"  )),
    );
  }
}

class ein_monat {
  private $ein_tag;
  private $tabelle;
  private $conn;
  function __construct() {
    $this->tabelle = new tabelle();
    echo "<pre>"; print_r( $this->tabelle->felder); echo "!</pre><br />\n";
    echo "<pre>"; print_r( $this->tabelle->felder["id"]); echo "!</pre><br />\n";
    foreach (($this->tabelle->felder) as $key => $val) {
      printf( "K010 %s<br />\n", $key);
    }
    $conn = new conn();
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

  }
}

new ein_monat();

?>
