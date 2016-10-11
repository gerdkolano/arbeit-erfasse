<?php
require_once("../kalender/datum.php");

class abgegolten {
  private $mysqli;

  function __construct( $host) {
    $this->mysqli = $this->verbinde( $host);
  }

  public function verbinde( $host) {
    $mysqli = new mysqli("zoe.xeo", "hanno", "geheim", "arbeit");
    if ($mysqli->connect_errno) {
      echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    }
    return $mysqli;
  }
  
  public function abgegolten( datum_objekt $monat) {
    $table = "verdienst";
    $where = sprintf( "WHERE datum = '%s'", $monat->format( "Y-m-d"));
    $query = "select 0.01*round(la422/2/(round(la300/14400*1.04))) as abgegolten from verdienst $where;";
    $res = $this->mysqli->query( $query);
    if ( !$res) {
        echo "Table $table opening failed: (" . $this->mysqli->errno . ") " . $this->mysqli->error;
    }
    
    $erg = "";
    $res->data_seek(0);
    $finfo = $res->fetch_fields();
    while ($row = $res->fetch_assoc()) {
        return $row['abgegolten' ];
    }
  }

}

$abgegolten = new abgegolten( "zoe.xeo");

echo $abgegolten->abgegolten( new datum_objekt( "2015-01-01"));

echo "<br />\n";
$startzeit = "2014-12";
$stopzeit  = "2017-2";
$laufobjekt = new datum_objekt( $startzeit);
$stopobjekt = new datum_objekt( $stopzeit);
while ( $laufobjekt < $stopobjekt) {
  echo $abgegolten->abgegolten( $laufobjekt) . "<br />\n";
  $laufobjekt->add( new DateInterval( 'P1M'));
}
echo "<br />\n";

?>
