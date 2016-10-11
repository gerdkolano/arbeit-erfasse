<?php
require_once("../kalender/datum.php");

$startzeit = "2015-12";
$stopzeit  = "2017-2";

erzeuge_abrechnungstag( new datum_objekt( $startzeit), new datum_objekt( $stopzeit));

function erzeuge_abrechnungstag( $laufobjekt, $stopobjekt) {
  while ( $laufobjekt < $stopobjekt) {
    $ultimo = clone $laufobjekt;
    $ultimo->modify('last day of this month');
    $abtag = clone $ultimo;
    if (($ultimo->format('N')) < 4) {                                    // mo di mi    zurück
      $abtag->modify(sprintf( "%+d day",   - $abtag->format("N")));                             // N Wochentag mo=1 so=7
    } else {                                                             // do fr sa so    vor
      $abtag->modify(sprintf( "%+d day", 7 - $abtag->format("N")));
    };
    printf( "Monat %s Ultimo %s Abrechnungstag %s<br />\n", $laufobjekt->format('Y-m'), $ultimo->format('Y-m-d N'), $abtag->format('Y-m-d D'));
    $laufobjekt->add( new DateInterval( 'P1M'));
  }
}

echo "<br />\n";

function verbinde( $host) {
  $mysqli = new mysqli("zoe.xeo", "hanno", "geheim", "arbeit");
  if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
  }
  return $mysqli;
}

function lies_viele( $mysqli) {
  $table = "verdienst";
  $query = "SELECT id, datum FROM $table ORDER BY id ASC";
  $query = "select datum, round(la300/144) as std1, round(la300/144*1.04) as std2, 0.01*round(la422/2/(round(la300/14400*1.04))) as abgegolten, zt305, sa305, la305, round( zt305 * sa305 / 100) as l305, zt307, sa307, la307, round( zt307 * sa307 / 100) as l307, zt357, sa357, la357, round( zt357 * sa357 / 500) as l357, zt770, sa770, la770, ceil( zt770 * sa770 / 200) as l770 from verdienst order by datum;";
  
  $res = $mysqli->query( $query);
  if ( !$res) {
      echo "Table $table opening failed: (" . $mysqli->errno . ") " . $mysqli->error;
  }
  
  $erg = "";
  $res->data_seek(0);
  $finfo = $res->fetch_fields();
  foreach ($finfo as $val) {printf("%s\n",   $val->name);}
  echo "<br />\n";
  
  echo "std1 datum<br />\n";
  while ($row = $res->fetch_assoc()) {
      echo $row['datum'      ] . "\n";
      echo $row['std1'       ] . "\n";
      echo $row['abgegolten' ] . "\n";
      echo "<br />\n";
  }
}

function abgegolten( $mysqli, datum_objekt $monat) {
  $table = "verdienst";
  $where = sprintf( "WHERE datum = '%s'", $monat->format( "Y-m-d"));
  $query = "select 0.01*round(la422/2/(round(la300/14400*1.04))) as abgegolten from verdienst $where;";
  $res = $mysqli->query( $query);
  if ( !$res) {
      echo "Table $table opening failed: (" . $mysqli->errno . ") " . $mysqli->error;
  }
  
  $erg = "";
  $res->data_seek(0);
  $finfo = $res->fetch_fields();
  while ($row = $res->fetch_assoc()) {
      return $row['abgegolten' ];
  }
}

function lies_einen_monat( $mysqli, datum_objekt $monat) {
  $table = "verdienst";
  $where = sprintf( "WHERE datum = '%s'", $monat->format( "Y-m-d"));
  $query = "select datum, round(la300/144) as std1, round(la300/144*1.04) as std2, 0.01*round(la422/2/(round(la300/14400*1.04))) as abgegolten from verdienst $where;";
  $res = $mysqli->query( $query);
  if ( !$res) {
      echo "Table $table opening failed: (" . $mysqli->errno . ") " . $mysqli->error;
  }
  
  $erg = "";
  $res->data_seek(0);
  $finfo = $res->fetch_fields();
  while ($row = $res->fetch_assoc()) {
      echo $row['datum'      ] . "\n";
      echo $row['std1'       ] . "\n";
      echo $row['abgegolten' ] . "\n";
      echo "<br />\n";
  }
}

$mysqli = verbinde( "zoe.xeo");
lies_viele( $mysqli);
echo "<br />\n";
lies_einen_monat( $mysqli, new datum_objekt( "2015-01-01"));

echo "<br />\n";
echo abgegolten( $mysqli, new datum_objekt( "2015-01-01"));

echo "<br />\n";
$startzeit = "2014-12";
$stopzeit  = "2017-2";
$laufobjekt = new datum_objekt( $startzeit);
$stopobjekt = new datum_objekt( $stopzeit);
while ( $laufobjekt < $stopobjekt) {
  echo abgegolten( $mysqli, $laufobjekt) . "<br />\n";
  $laufobjekt->add( new DateInterval( 'P1M'));
}
echo "<br />\n";

?>
