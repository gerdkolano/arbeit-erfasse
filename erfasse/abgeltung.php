<?php
require_once( "../include/datum.php");
require_once( "../include/konst.php");

class prämie_und_lohn {
  public $verkaufsstellenprämie ;
  public $stundenlohn           ;
  public $gehalt                ;
  public $abgegoltene_zeit      ;

  function __construct( $verkaufsstellenprämie, $stundenlohn, $gehalt, $abgegoltene_zeit) {
    $this->verkaufsstellenprämie = $verkaufsstellenprämie ;
    $this->stundenlohn           = $stundenlohn           ;
    $this->gehalt                = $gehalt                ;
    $this->abgegoltene_zeit      = $abgegoltene_zeit      ;
  }

  function __toString() {
    return sprintf( "%s %s %s", $this->verkaufsstellenprämie, $this->stundenlohn, $this->gehalt, $this->abgegoltene_zeit);
  }
}

class abgegolten {
  private $mysqli;
  private $datum;

  function __construct( datum_objekt $datum, $host) {
    $this->mysqli = $this->verbinde( $host);
    $this->datum = $datum->erster_tag_des_monats();
  }

  function __toString() {
    return "" . $this->abgegolten( $this->datum);
  }

  public function verbinde( $host) {
    $mysqli = new mysqli( konst::$host_name, "hanno", (new konst)->parole, "arbeit");
    if ($mysqli->connect_errno) {
      echo "A010 Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    }
    return $mysqli;
  }
  
  public function abgegolten() {
    $table = "verdienst";
    $where = sprintf( "WHERE datum = '%s'", $this->datum->format( "Y-m-d"));
#   $query = "SELECT la422, la300, round(la300/144*1.04) AS stundenlohn, round(la422/2/(round(la300/14400*1.04))) AS abgegolten FROM verdienst $where;";
    $query = "SELECT la422, la300, round(1.04*la300/144) AS stundenlohn, round(la422*50/(round(1.04*la300/144))) AS abgegolten FROM verdienst $where;";
#   echo $query;
    $res = $this->mysqli->query( $query);
    if ( !$res) {
        echo "Table $table opening failed: (" . $this->mysqli->errno . ") " . $this->mysqli->error;
    }
    
    $res->data_seek(0);
    if ($row = $res->fetch_assoc()) {
      $p_und_l = new prämie_und_lohn(
        sprintf( "%.2f", $row['la422'       ] / 100.0 ),
        sprintf( "%.2f", $row['stundenlohn' ] / 100.0 ),
        $row['la300'],
        $row['abgegolten']
      );
    } else {
      $p_und_l = new prämie_und_lohn( "", "", "", "");
    }
    return $p_und_l;
  }

}

?>
