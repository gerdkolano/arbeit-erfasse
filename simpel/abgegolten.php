<?php
require_once("../kalender/datum.php");

class prämie_und_lohn {
  public $verkaufsstellenprämie;
  public $gehalt;
  public $abgegoltene_zeit;

  function __construct( $verkaufsstellenprämie, $gehalt, $abgegoltene_zeit) {
    $this->verkaufsstellenprämie = $verkaufsstellenprämie ;
    $this->gehalt                = $gehalt                ;
    $this->abgegoltene_zeit      = $abgegoltene_zeit      ;
  }

  function __toString() {
    return sprintf( "%s %s %s", $this->verkaufsstellenprämie, $this->gehalt, $this->abgegoltene_zeit);
  }
}

class abgegolten {
  private $mysqli;
  private $datum;

  function __construct( datum_objekt $datum, $host = "zoe.xeo") {
    $this->mysqli = $this->verbinde( $host);
    $this->datum = $datum;
  }

  function __toString() {
    return "" . $this->abgegolten( $this->datum);
  }

  public function verbinde( $host) {
    $mysqli = new mysqli("zoe.xeo", "hanno", (new konst)->parole, "arbeit");
    if ($mysqli->connect_errno) {
      echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    }
    return $mysqli;
  }
  
  public function abgegolten( datum_objekt $monat) {
    $table = "verdienst";
    $where = sprintf( "WHERE datum = '%s'", $monat->format( "Y-m-d"));
    $query = "SELECT la422, la300, 0.01*round(la422/2/(round(la300/14400*1.04))) AS abgegolten FROM verdienst $where;";
    $res = $this->mysqli->query( $query);
    if ( !$res) {
        echo "Table $table opening failed: (" . $this->mysqli->errno . ") " . $this->mysqli->error;
    }
    
    $res->data_seek(0);
    while ($row = $res->fetch_assoc()) {
      $erg .= sprintf( "%s %s %s", $row['abgegolten'], $row['la422'], $row['la300']);
      $p_und_l = new prämie_und_lohn( $row['la422'], $row['la300'], $row['abgegolten']);
    }
    return $p_und_l;
  }

}

?>
